<?php
session_start();
require('../db/db_connection_festive.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

// Fetch user details
$user_query = "SELECT name, role FROM user WHERE id = ?";
$stmt = $conn->prepare($user_query);
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($judge_name, $user_role);
$stmt->fetch();
$stmt->close();

// Check if user is an admin
if ($user_role != 2) {
    echo "Access denied. Only admins can make deductions.";
    exit;
}

// Validate POST data
if (!isset($_POST['entry_num']) || !isset($_POST['deduction'])) {
    echo "Invalid request.";
    exit;
}

$entry_num = intval($_POST['entry_num']);
$deduction = intval($_POST['deduction']);

if ($deduction > 100) {
    $deduction = 100;
}

// Update the deduction
$updateDeductionSql = "UPDATE scores SET deduction = ? WHERE entry_num = ?";
$stmt = $conn->prepare($updateDeductionSql);
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt->bind_param("ii", $deduction, $entry_num);
if (!$stmt->execute()) {
    die('Execute failed: ' . htmlspecialchars($stmt->error));
}
$stmt->close();

// Fetch current scores and deduction
$fetchScoresSql = "SELECT festive_spirit, costume_and_props, relevance_to_the_theme, deduction FROM scores WHERE entry_num = ?";
$stmt = $conn->prepare($fetchScoresSql);
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt->bind_param("i", $entry_num);
$stmt->execute();
$stmt->bind_result($fsp, $cap, $rt, $current_deduction);

if ($stmt->fetch()) {
    $stmt->close();

    // Calculate total score
    $total_score = ($fsp + $cap + $rt) - $deduction;

    // Update the total score
    $updateTotalScoreSql = "UPDATE scores SET total_score = ? WHERE entry_num = ?";
    $stmt = $conn->prepare($updateTotalScoreSql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("ii", $total_score, $entry_num);
    if (!$stmt->execute()) {
        die('Execute failed: ' . htmlspecialchars($stmt->error));
    }
    $stmt->close();
} else {
    die('No scores found for entry number ' . htmlspecialchars($entry_num));
}

header("Location: festivededuction.php?entry_num=" . urlencode($entry_num));
exit;
?>
