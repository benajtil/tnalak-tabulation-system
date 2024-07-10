<?php
try {
    $mysqlHost = 'localhost';
    $mysqlDb = 'floatparade';
    $mysqlUser = 'root';
    $mysqlPass = '';


    $mysqlConn = new PDO("mysql:host=$mysqlHost;dbname=$mysqlDb;charset=utf8mb4", $mysqlUser, $mysqlPass);

    $mysqlConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "MySQL Connection failed: " . $e->getMessage();
}
?>
