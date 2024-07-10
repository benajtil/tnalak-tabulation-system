<?php
require('../db/db_connection_sqlite.php');

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
