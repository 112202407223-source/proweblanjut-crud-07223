<?php
// koneksi.php
$host = 'localhost';
$dbname = 'db_inventories';
$username = 'root';
$password = '';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("set names utf8mb4");
} catch(PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>