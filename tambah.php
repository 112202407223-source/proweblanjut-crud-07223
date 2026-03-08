<?php
// tambah.php
require_once 'koneksi.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_barang = $_POST['nama_barang'];
    $kategori = $_POST['kategori'];
    $jumlah = $_POST['jumlah'];
    $harga = $_POST['harga'];
    $supplier = $_POST['supplier'];
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $stok_minimum = $_POST['stok_minimum'];
    $keterangan = $_POST['keterangan'];
    
    $stmt = $db->prepare("INSERT INTO barang (nama_barang, kategori, jumlah, harga, supplier, tanggal_masuk, stok_minimum, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    if($stmt->execute([$nama_barang, $kategori, $jumlah, $harga, $supplier, $tanggal_masuk, $stok_minimum, $keterangan])) {
        header("Location: index.php?message=Data berhasil ditambahkan");
        exit();
    } else {
        $error = "Gagal menambahkan data";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Barang</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="btn btn-back">Kembali</a>
        
        <h2>Tambah Barang Baru</h2>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Nama Barang *</label>
                <input type="text" name="nama_barang" required maxlength="100">
            </div>
            
            <div class="form-group">
                <label>Kategori</label>
                <input type="text" name="kategori" maxlength="50">
            </div>
            
            <div class="form-group">
                <label>Jumlah *</label>
                <input type="number" name="jumlah" required min="0">
            </div>
            
            <div class="form-group">
                <label>Harga *</label>
                <input type="number" name="harga" required min="0">
            </div>
            
            <div class="form-group">
                <label>Supplier</label>
                <input type="text" name="supplier" maxlength="100">
            </div>
            
            <div class="form-group">
                <label>Tanggal Masuk</label>
                <input type="date" name="tanggal_masuk" value="<?php echo date('Y-m-d'); ?>">
            </div>
            
            <div class="form-group">
                <label>Stok Minimum</label>
                <input type="number" name="stok_minimum" min="0" value="0">
            </div>
            
            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan"></textarea>
            </div>
            
            <button type="submit" class="btn btn-add">Simpan</button>
        </form>
    </div>
</body>
</html>