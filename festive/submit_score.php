<?php
session_start();
require('../db/db_connection_sqlite_festive.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$user_query = "SELECT name FROM user WHERE id = ?";
$stmt = $conn->prepare($user_query);
$stmt->execute([$_SESSION['user_id']]);
$judge_name = $stmt->fetchColumn();
$stmt->closeCursor();

if (!isset($_POST['entry_num'], $_POST['festive_spirit'], $_POST['costume_and_props'], $_POST['relevance_to_the_theme'])) {
    die('All fields are required.');
}

$entry_num = intval($_POST['entry_num']);
$festive_spirit = intval($_POST['festive_spirit']);
$costume_and_props = intval($_POST['costume_and_props']);
$relevance_to_the_theme = intval($_POST['relevance_to_the_theme']);

$total_score = $festive_spirit + $costume_and_props + $relevance_to_the_theme;

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

    $insertScoreSql = "INSERT INTO scores (entry_num, judge_name, festive_spirit, costume_and_props, relevance_to_the_theme, total_score) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertScoreSql);
    $stmt->execute([$entry_num, $judge_name, $festive_spirit, $costume_and_props, $relevance_to_the_theme, $total_score]);
    $stmt->closeCursor();

    $fetchAllScoresSql = "SELECT festive_spirit, costume_and_props, relevance_to_the_theme FROM scores WHERE entry_num = ?";
    $stmt = $conn->prepare($fetchAllScoresSql);
    $stmt->execute([$entry_num]);

    $total_fsp = $total_cap = $total_rt = $count = 0;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $total_fsp += $row['festive_spirit'];
        $total_cap += $row['costume_and_props'];
        $total_rt += $row['relevance_to_the_theme'];
        $count++;
    }
    $stmt->closeCursor();

    if ($count > 0) {
        $avg_fsp = $total_fsp / $count;
        $avg_cap = $total_cap / $count;
        $avg_rt = $total_rt / $count;
        $avg_compiled_scores = $avg_fsp + $avg_cap + $avg_rt;

        $updateOverallScoresSql = "INSERT INTO overallscores (entry_num, compiled_scores) VALUES (?, ?) ON CONFLICT(entry_num) DO UPDATE SET compiled_scores = excluded.compiled_scores";
        $stmt = $conn->prepare($updateOverallScoresSql);
        $stmt->execute([$entry_num, $avg_compiled_scores]);

        $stmt->closeCursor();
    }

    $conn->commit();
    header("Location: judgeTableFestive.php");
    exit;
} catch (PDOException $e) {
    $conn->rollBack();
    die('Transaction failed: ' . htmlspecialchars($e->getMessage()));
}
?>
