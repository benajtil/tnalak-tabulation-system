<?php
session_start();
require('../db/db_connection_sqlite.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$user_query = "SELECT role, name FROM user WHERE id = :user_id";
$stmt = $conn->prepare($user_query);
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->errorInfo()[2]));
}
$stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    die('User not found');
}

$judge_name = $row['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Judge Panel</title>
    <link rel="stylesheet" href="../float/css/navigation.css?v=1.0">
    <style>
        .panel.clicked {
            background-color: rgb(68, 133, 255); 
            transform: translateY(2px); 
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const lastClickedEntryNum = localStorage.getItem("lastClickedEntryNum");
            if (lastClickedEntryNum) {
                const lastClickedPanel = document.querySelector(`.panel[data-entry-num="${lastClickedEntryNum}"]`);
                if (lastClickedPanel) {
                    lastClickedPanel.classList.add("clicked");
                }
            }

            document.querySelectorAll(".panel").forEach(panel => {
                panel.addEventListener("click", function() {
                    if (this.classList.contains("clicked")) return;

                    document.querySelectorAll(".panel").forEach(p => p.classList.remove("clicked"));
                    this.classList.add("clicked");
                    localStorage.setItem("lastClickedEntryNum", this.getAttribute("data-entry-num"));
                });
            });
        });
    </script>
</head>
<body>
    <div class="sidebyside">
        <div class="sidebar">
            <div class="eulaplogo">
                <img src="../images/eulaplogo.png" alt="Eulap image">
            </div>

            <?php
            $sql = "SELECT entry_num FROM contestant";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $contestants = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($contestants as $row) {
                $entry_num = $row["entry_num"];
                $class = '';

                $checkJudgeScoreSql = "SELECT * FROM scores WHERE entry_num = :entry_num AND judge_name = :judge_name";
                $stmt = $conn->prepare($checkJudgeScoreSql);
                if ($stmt === false) {
                    die('Prepare failed: ' . htmlspecialchars($conn->errorInfo()[2]));
                }

                $stmt->bindValue(':entry_num', $entry_num, PDO::PARAM_INT);
                $stmt->bindValue(':judge_name', $judge_name, PDO::PARAM_STR);
                $stmt->execute();
                $resultCheck = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($resultCheck) {
                    $class = 'scored';
                }

                echo '<a href="judgeTable.php?entry_num=' . htmlspecialchars($entry_num) . '" class="panel ' . $class . '" data-entry-num="' . htmlspecialchars($entry_num) . '"><strong>Contestant ' . htmlspecialchars($entry_num) . '</strong></a>';
            }
            ?>

            <form method="post" action="../logout.php">
                <div class="logout-button">
                    <button type="submit" name="logout"><strong>LOGOUT</strong></button>
                </div>
                <div class="judgeName">
                    <h1>Welcome, <?php echo htmlspecialchars($judge_name); ?></h1>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
