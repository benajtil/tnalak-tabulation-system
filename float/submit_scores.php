<?php
session_start();
require('../db/db_connection_sqlite.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$user_query = "SELECT name FROM user WHERE id = ?";
$stmt = $conn->prepare($user_query);
$stmt->execute([$_SESSION['user_id']]);
$judge_name = $stmt->fetchColumn();
$stmt->closeCursor();

if (!isset($_POST['entry_num'], $_POST['overall_appearance'], $_POST['artistry_design'], $_POST['craftsmanship'], $_POST['relevance_theme'])) {
    die('All fields are required.');
}

$entry_num = intval($_POST['entry_num']);
$overall_appearance = intval($_POST['overall_appearance']);
$artistry_design = intval($_POST['artistry_design']);
$craftsmanship = intval($_POST['craftsmanship']);
$relevance_theme = intval($_POST['relevance_theme']);

$total_score = $overall_appearance + $artistry_design + $craftsmanship + $relevance_theme;

$conn->beginTransaction();

try {
    $checkJudgeScoreSql = "SELECT * FROM scores WHERE entry_num = ? AND judge_name = ?";
    $stmt = $conn->prepare($checkJudgeScoreSql);
    $stmt->execute([$entry_num, $judge_name]);

    if ($stmt->fetch()) {
        echo "<script>alert('You have already scored this contestant.'); window.location.href = 'navigation.php';</script>";
        exit;
    }
    $stmt->closeCursor();

    $insertScoreSql = "INSERT INTO scores (entry_num, judge_name, overall_appearance, artistry_design, craftsmanship, relevance_theme, deductions, total_score) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertScoreSql);
    $stmt->execute([$entry_num, $judge_name, $overall_appearance, $artistry_design, $craftsmanship, $relevance_theme, 0, $total_score]);
    $stmt->closeCursor();

    $fetchAllScoresSql = "SELECT overall_appearance, artistry_design, craftsmanship, relevance_theme, deductions FROM scores WHERE entry_num = ?";
    $stmt = $conn->prepare($fetchAllScoresSql);
    $stmt->execute([$entry_num]);

    $total_oa = $total_ad = $total_cr = $total_rt = $total_ded = $count = 0;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $total_oa += $row['overall_appearance'];
        $total_ad += $row['artistry_design'];
        $total_cr += $row['craftsmanship'];
        $total_rt += $row['relevance_theme'];
        $total_ded += $row['deductions'];
        $count++;
    }
    $stmt->closeCursor();

    if ($count > 0) {
        $avg_oa = $total_oa / $count;
        $avg_ad = $total_ad / $count;
        $avg_cr = $total_cr / $count;
        $avg_rt = $total_rt / $count;
        $avg_total_score = $avg_oa + $avg_ad + $avg_cr + $avg_rt;

        $avg_total_score -= $total_ded; // Subtract deductions from the average total score

        $updateOverallScoresSql = "INSERT INTO overallscores (entry_num, compiled_scores) VALUES (?, ?) ON CONFLICT(entry_num) DO UPDATE SET compiled_scores = ?";
$stmt = $conn->prepare($updateOverallScoresSql);
$stmt->execute([$entry_num, $avg_total_score, $avg_total_score]);

        $stmt->closeCursor();
    }

    $conn->commit();
    header("Location: judgeTable.php");
    exit;
} catch (PDOException $e) {
    $conn->rollBack();
    die('Transaction failed: ' . htmlspecialchars($e->getMessage()));
}
?>
