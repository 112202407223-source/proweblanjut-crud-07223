<?php
// logout.php
session_start();
session_destroy();

// Hapus cookie remember me
if (isset($_COOKIE['remember_username'])) {
    setcookie('remember_username', '', time() - 3600, '/');
}

header("Location: login.php?logout=1");
exit();
?>