<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Float Competition</title>
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
    <h1>CIVIC PARADE: FLOAT COMPETITION</h1>
    <form action="submit_scores.php" method="POST" onsubmit="return confirmSubmission()">
        <input type="hidden" id="entry_num" name="entry_num" value="<?php echo htmlspecialchars($entry_num); ?>">
        <div class="form-group">
            <label for="overall_appearance">Overall Appearance and Impact:</label>
            <input id="overall_appearance" placeholder="1-30" name="overall_appearance" type="number" min="1" max="30" required>
        </div>
        <div class="form-group">
            <label for="artistry_design">Artistry/Design:</label>
            <input id="artistry_design" name="artistry_design" type="number" min="1" max="20" placeholder="1-20" required>
        </div>
        <div class="form-group">
            <label for="craftsmanship">Craftsmanship:</label>
            <input id="craftsmanship" name="craftsmanship" type="number" min="1" max="30" placeholder="1-30" required>
        </div>
        <div class="form-group">
            <label for="relevance_theme">Relevance to Festival Theme:</label>
            <input id="relevance_theme" name="relevance_theme" type="number" min="1" max="20" placeholder="1-20" required>
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
<script src="../float/js/judgeTable.js"></script>
</body>
</html>
