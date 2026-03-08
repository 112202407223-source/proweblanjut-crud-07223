<?php
// edit.php
require_once 'koneksi.php';

if(!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// Ambil dta
$stmt = $db->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->execute([$id]);
$barang = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$barang) {
    header("Location: index.php?error=Data tidak ditemukan");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_barang = $_POST['nama_barang'];
    $kategori = $_POST['kategori'];
    $jumlah = $_POST['jumlah'];
    $harga = $_POST['harga'];
    $supplier = $_POST['supplier'];
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $stok_minimum = $_POST['stok_minimum'];
    $keterangan = $_POST['keterangan'];
    
    $stmt = $db->prepare("UPDATE barang SET nama_barang=?, kategori=?, jumlah=?, harga=?, supplier=?, tanggal_masuk=?, stok_minimum=?, keterangan=? WHERE id=?");
    
    if($stmt->execute([$nama_barang, $kategori, $jumlah, $harga, $supplier, $tanggal_masuk, $stok_minimum, $keterangan, $id])) {
        header("Location: index.php?message=Data berhasil diupdate");
        exit();
    } else {
        $error = "Gagal mengupdate data";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Barang</title>
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="btn btn-back">Kembali</a>
        
        <h2>Edit Barang</h2>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Nama Barang *</label>
                <input type="text" name="nama_barang" required maxlength="100" value="<?php echo $barang['nama_barang']; ?>">
            </div>
            
            <div class="form-group">
                <label>Kategori</label>
                <input type="text" name="kategori" maxlength="50" value="<?php echo $barang['kategori']; ?>">
            </div>
            
            <div class="form-group">
                <label>Jumlah *</label>
                <input type="number" name="jumlah" required min="0" value="<?php echo $barang['jumlah']; ?>">
            </div>
            
            <div class="form-group">
                <label>Harga *</label>
                <input type="number" name="harga" required min="0" value="<?php echo $barang['harga']; ?>">
            </div>
            
            <div class="form-group">
                <label>Supplier</label>
                <input type="text" name="supplier" maxlength="100" value="<?php echo $barang['supplier']; ?>">
            </div>
            
            <div class="form-group">
                <label>Tanggal Masuk</label>
                <input type="date" name="tanggal_masuk" value="<?php echo $barang['tanggal_masuk']; ?>">
            </div>
            
            <div class="form-group">
                <label>Stok Minimum</label>
                <input type="number" name="stok_minimum" min="0" value="<?php echo $barang['stok_minimum']; ?>">
            </div>
            
            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan"><?php echo $barang['keterangan']; ?></textarea>
            </div>
            
            <button type="submit" class="btn btn-add">Update</button>
        </form>
    </div>
</body>
</html>