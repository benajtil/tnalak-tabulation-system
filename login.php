<?php
session_start();
require(__DIR__ . '/db/db_connection_sqlite.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $sql = $conn->prepare("SELECT id, role, password FROM user WHERE username = :username");
        $sql->bindValue(':username', $username, PDO::PARAM_STR);
        $sql->execute();
        $user = $sql->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $user['role'];
                $_SESSION['judge_id'] = $user['id'];

                if ($user['role'] == 1) {
                    header("Location: splash.php");
                } elseif ($user['role'] == 2) {
                    header("Location: adminDashboard.php");
                } else {
                    $_SESSION['error_message'] = "Invalid role.";
                    header("Location: index.php");
                }
            } else {
                $_SESSION['error_message'] = "Invalid password.";
                header("Location: index.php");
            }
        } else {
            $_SESSION['error_message'] = "No user found with that username.";
            header("Location: index.php");
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
        header("Location: index.php");
    }

    exit;
}
?>
