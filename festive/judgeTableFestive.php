<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Most Festive Contingent</title>
    <link rel="stylesheet" href="../float/css/judgeTable.css?v=1.0">
</head>
<body>
<?php 
    include ('include/navigation.php');
?>
<?php
require('../db/db_connection_sqlite.php'); 

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "You must be logged in to access this page.";
    header("Location: ../index.php");
    exit;
}

$user_query = "SELECT role, name FROM user WHERE id = :user_id";
$stmt = $conn->prepare($user_query);
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->errorInfo()[2]));
}
$stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$result = $stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    die('User not found');
}
$role = $row['role'];
$judge_name = $row['name'];

if ($role !== 1) {
    $_SESSION['error_message'] = "You do not have permission to score.";
    exit;
}

if (!isset($_GET['entry_num'])) {
    echo "No contestant selected.";
    exit;
}

$entry_num = $_GET['entry_num'];

$checkJudgeScoreSql = "SELECT * FROM scores WHERE entry_num = :entry_num AND judge_name = :judge_name";
$stmt = $conn->prepare($checkJudgeScoreSql);
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->errorInfo()[2]));
}
$stmt->bindValue(':entry_num', $entry_num, PDO::PARAM_INT);
$stmt->bindValue(':judge_name', $judge_name, PDO::PARAM_STR);
$result = $stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    echo "<script>
            window.onload = function() {
                alert('You have already scored this contestant.');
                window.location.href = 'judgeTable.php';
            };
          </script>";
    exit;
}

?>
<div class="container">
<div class="contestantname"> 
<h1><?php echo '<strong>Contestant ' . htmlspecialchars($entry_num) . '</strong></a>'; ?> </h1>

</div>
    <h1>SEARCH FOR MOST FESTIVE CONTINGENT</h1>
    <form action="submit_score.php" method="post" onsubmit="return confirmSubmission()">
    <input type="hidden" id="entry_num" name="entry_num" value="<?php echo htmlspecialchars($entry_num); ?>">
        <div class="form-group">
            <label for="festive_spirit">Festive Spirit of Parade Participants (50%)</label>
            <input id="festive_spirit" placeholder ="1-50" name="festive_spirit" type="number" min="1" max="50" required>
            <p>(Festive-feel, Festive-look, Festivity, Color, Use of Liveners, Enthusiasm)</p>

        </div>
        <div class="form-group">
            <label for="costume_and_props">Costume and Props (30%)</label>
            <input id="costume_and_props" name="costume_and_props" type="number" min="1" max="20" placeholder ="1-20" required>
            <p>(Creativity, Uniqueness)</p>
        </div>
        <div class="form-group">
            <label for="relevance_to_the_theme">relevance to the theme (20%)</label>
            <input id="relevance_to_the_theme" name="relevance_to_the_theme" type="number" min="1" max="30" placeholder ="1-30" required>
            <p>(Theme: "CULTURAL BRILLIANCE: Weaving Traditions, Celebrating Legacies")</em></p>

        </div>
        <div class="buttons">
            <button type="submit">Submit</button>
        </div>
    </form>
    <div id="message" style="display:none;"></div>
</div>
<div class="background">
    <img src="../images/tnalakbg.png" alt="t`nalak Background">
</div>

<script>
    function confirmSubmission() {
        return confirm('Are you sure you want to submit?');
    }
</script>
<script src="../festive/js/judgeTable.js"></script>
</body>
</html>
