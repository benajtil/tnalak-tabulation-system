<?php
require('../db/db_connection_sqlite.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $entry_num = $_POST['entry_num'];

    try {
        $stmt = $conn->prepare("INSERT INTO contestant (name, entry_num) VALUES (:name, :entry_num)");
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':entry_num', $entry_num, PDO::PARAM_INT);
        $stmt->execute();
        echo "Contestant added successfully!";
        
        // Redirect to prevent form resubmission
        header("Location: add_contestant.php");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Contestant</title>
</head>
<body>
    <h1>Add New Contestant</h1>
    <form action="add_contestant.php" method="post">
        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div>
            <label for="entry_num">Entry Number:</label>
            <input type="number" id="entry_num" name="entry_num" required>
        </div>
        <button type="submit">Add Contestant</button>
    </form>
    <?php 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $entry_num = $_POST['entry_num'];
    
        try {
            $stmt = $conn->prepare("UPDATE contestant SET name = :name, entry_num = :entry_num WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':entry_num', $entry_num, PDO::PARAM_INT);
            $stmt->execute();
            echo "Contestant modified successfully!";
            
            // Redirect to prevent form resubmission
            header("Location: add_contestant.php");
            exit;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }?>
    <h1>Modify Contestant</h1>
    <form action="add_contestant.php" method="post">
        <div>
            <label for="id">Contestant ID:</label>
            <input type="number" id="id" name="id" required>
        </div>
        <div>
            <label for="name">New Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div>
            <label for="entry_num">New Entry Number:</label>
            <input type="number" id="entry_num" name="entry_num" required>
        </div>
        <button type="submit">Modify Contestant</button>
    </form>
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    try {
        $stmt = $conn->prepare("DELETE FROM contestant WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        echo "Contestant removed successfully!";
        
        // Redirect to prevent form resubmission
        header("Location: add_contestant.php");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

    <h1>Remove Contestant</h1>
    <form action="add_contestant.php" method="post">
        <div>
            <label for="id">Contestant ID:</label>
            <input type="number" id="id" name="id" required>
        </div>
        <button type="submit">Remove Contestant</button>
    </form>

    <?php

try {
    $stmt = $conn->query("SELECT id, entry_num, name FROM contestant ORDER BY id");
    if ($stmt === false) {
        die("Error fetching contestants: " . htmlspecialchars($conn->errorInfo()[2]));
    }

    echo "<h1>Contestants List</h1>";
    echo "<table border='1'>";
    echo "<thead><tr><th>ID</th><th>Entry Number</th><th>Name</th></tr></thead>";
    echo "<tbody>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['entry_num']) . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null; // Close connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Contestants</title>
</head>
<body>
    <!-- Add your HTML content for display here if needed -->
</body>
</html>

</body>
</html>
