<?php
$databaseFile = __DIR__ . '/floatparade.db';

try {
    $conn = new PDO("sqlite:$databaseFile");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
