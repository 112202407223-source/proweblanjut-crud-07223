<?php
// hapus.php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Ambil nama file gambar sebelum hapus
    $stmt = $db->prepare("SELECT gambar FROM barang WHERE id = ?");
    $stmt->execute([$id]);
    $barang = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($barang) {
        // Hapus file gambar jika ada
        if (!empty($barang['gambar']) && file_exists('uploads/' . $barang['gambar'])) {
            unlink('uploads/' . $barang['gambar']);
        }
        // Hapus record dari database
        $stmt = $db->prepare("DELETE FROM barang WHERE id = ?");
        if ($stmt->execute([$id])) {
            header("Location: index.php?message=Data berhasil dihapus");
        } else {
            header("Location: index.php?error=Gagal menghapus data");
        }
    } else {
        header("Location: index.php?error=Data tidak ditemukan");
    }
} else {
    header("Location: index.php");
}
exit;
?>