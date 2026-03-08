<?php
// hapus.php
require_once 'koneksi.php';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    
    $stmt = $db->prepare("DELETE FROM barang WHERE id = ?");
    
    if($stmt->execute([$id])) {
        header("Location: index.php?message=Data berhasil dihapus");
    } else {
        header("Location: index.php?error=Gagal menghapus data");
    }
} else {
    header("Location: index.php");
}
exit();
?>