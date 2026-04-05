<?php
// proses_login.php
session_start();
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;

    if (empty($username) || empty($password)) {
        header("Location: login.php?error=1");
        exit();
    }

    $sql = "SELECT id, username, password FROM users WHERE username = :username";
    $stmt = $db->prepare($sql);
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifikasi password terhash
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];

        // Set cookie jika remember me dicentang
        if ($remember) {
            setcookie('remember_username', $user['username'], time() + (86400 * 7), '/');
        } else {
            if (isset($_COOKIE['remember_username'])) {
                setcookie('remember_username', '', time() - 3600, '/');
            }
        }

        header("Location: index.php");
        exit();
    } else {
        header("Location: login.php?error=1");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>