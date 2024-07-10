<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // Connect to SQLite
    $sqlite = new PDO('sqlite:db/floatparade.db');
    $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "SQLite Connection successful!\n";

    // Connect to MySQL
    $mysql = new PDO('mysql:host=192.168.0.104;dbname=floatparade', 'root', '');
    $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "MySQL Connection successful!\n";

    // Function to sync table data
    function syncTable($sqlite, $mysql, $table, $columns) {
        echo "Syncing '$table' table...\n";
        $result = $sqlite->query("SELECT * FROM $table");
        $data = $result->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $row) {
            $placeholders = array_map(function($col) { return ":$col"; }, array_keys($row));
            $updates = array_map(function($col) { return "$col = :$col"; }, array_keys($row));
            $sql = "INSERT INTO $table (" . implode(", ", array_keys($row)) . ")
                    VALUES (" . implode(", ", $placeholders) . ")
                    ON DUPLICATE KEY UPDATE " . implode(", ", $updates);
            $stmt = $mysql->prepare($sql);

            foreach ($row as $col => $value) {
                $stmt->bindValue(":$col", $value);
            }

            if (!$stmt->execute()) {
                echo "Error syncing $table data: " . implode(", ", $stmt->errorInfo()) . "\n";
            }
        }
        echo "Sync for '$table' table completed.\n";
    }

    // Define table and columns to sync
    $tables = [
        'contestant' => ['id', 'name', 'entry_num', 'created_at', 'updated_at', 'scored'],
        'overallscores' => ['id', 'entry_num', 'created_at', 'updated_at', 'compiled_scores'],
        'scores' => ['entry_num', 'judge_name', 'overall_appearance', 'artistry_design', 'craftsmanship', 'relevance_theme', 'created', 'updated', 'total_score'],
        'user' => ['id', 'username', 'password', 'name', 'role', 'status', 'created_at', 'updated_at']
    ];

    // Sync each table
    foreach ($tables as $table => $columns) {
        syncTable($sqlite, $mysql, $table, $columns);
    }

    echo "Data sync completed successfully.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
