<?php
include('include/deduction_nav.php');
require('../db/db_connection.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

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

if ($user_role != 2) {
    echo "Access denied. Only admins can make deductions.";
    exit;
}

if (!isset($_GET['entry_num'])) {
    echo "No contestant selected.";
    exit;
}

$entry_num = $_GET['entry_num'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Deduction Float</title>
    <link rel="stylesheet" href="../float/css/judgeTable.css">
    <script>
        function confirmSubmission() {
            return confirm("Are you sure you want to apply this deduction?");
        }

        document.addEventListener("DOMContentLoaded", function() {
            const deductionInput = document.getElementById("deduction");
            deductionInput.addEventListener("input", function() {
                if (this.value > 100) {
                    this.value = 100;
                }
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="contestantname">
            <h1><?php echo '<strong>Contestant ' . htmlspecialchars($entry_num) . '</strong>'; ?></h1>
        </div>
        <h1>DEDUCTION FLOAT COMPETITION</h1>
        <form action="submit_deduction.php" method="post" onsubmit="return confirmSubmission()">
            <input type="hidden" id="entry_num" name="entry_num" value="<?php echo htmlspecialchars($entry_num); ?>">
            <div class="form-group">
                <label for="deduction"><?php echo '<strong>Deduct Points for contestant #: ' . htmlspecialchars($entry_num) . '</strong>'; ?></label>
                <input id="deduction" name="deduction" type="number" min="1" max="100" placeholder="1-100" required>
            </div>
            <div class="buttons">
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
</body>
</html>
