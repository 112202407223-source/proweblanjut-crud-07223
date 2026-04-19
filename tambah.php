<?php
// tambah.php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require_once 'koneksi.php';

$errors = [];
$old_input = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil input
    $nama_barang   = trim($_POST['nama_barang'] ?? '');
    $kategori      = trim($_POST['kategori'] ?? '');
    $jumlah        = trim($_POST['jumlah'] ?? '');
    $harga         = trim($_POST['harga'] ?? '');
    $supplier      = trim($_POST['supplier'] ?? '');
    $tanggal_masuk = $_POST['tanggal_masuk'] ?? '';
    $stok_minimum  = trim($_POST['stok_minimum'] ?? '');
    $keterangan    = trim($_POST['keterangan'] ?? '');

    // Simpan input lama untuk ditampilkan ulang
    $old_input = compact('nama_barang', 'kategori', 'jumlah', 'harga', 'supplier', 'tanggal_masuk', 'stok_minimum', 'keterangan');


    if (empty($nama_barang)) {
        $errors[] = "Nama barang harus diisi.";
    }
    if (!is_numeric($jumlah) || $jumlah < 0) {
        $errors[] = "Jumlah harus berupa angka positif.";
    }
    if (!is_numeric($harga) || $harga < 0) {
        $errors[] = "Harga harus berupa angka positif.";
    }
    if (empty($tanggal_masuk)) {
        $errors[] = "Tanggal masuk harus diisi.";
    }

    // Proses upload gambar
    $gambar_name = null;
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['gambar']['tmp_name'];
        $file_size = $_FILES['gambar']['size'];
        $file_ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];

        if (!in_array($file_ext, $allowed)) {
            $errors[] = "Format gambar harus JPG, JPEG, atau PNG.";
        }
        if ($file_size > 1 * 1024 * 1024) {
            $errors[] = "Ukuran gambar maksimal 1MB.";
        }
        if (empty($errors)) {
            $gambar_name = uniqid() . '.' . $file_ext;
            $upload_path = 'uploads/' . $gambar_name;
            if (!move_uploaded_file($file_tmp, $upload_path)) {
                $errors[] = "Gagal mengunggah gambar.";
                $gambar_name = null;
            }
        }
    }

    // Jika tidak ada error, simpan ke database
    if (empty($errors)) {
        $sql = "INSERT INTO barang (nama_barang, kategori, jumlah, harga, supplier, tanggal_masuk, stok_minimum, keterangan, gambar) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([$nama_barang, $kategori, $jumlah, $harga, $supplier, $tanggal_masuk, $stok_minimum, $keterangan, $gambar_name]);
        if ($result) {
            header("Location: index.php?message=Data berhasil ditambahkan");
            exit;
        } else {
            $errors[] = "Gagal menyimpan data ke database.";
        }
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

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $err): ?>
                <p><?= htmlspecialchars($err) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nama Barang *</label>
            <input type="text" name="nama_barang" required maxlength="100" value="<?= htmlspecialchars($old_input['nama_barang'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Kategori</label>
            <input type="text" name="kategori" maxlength="50" value="<?= htmlspecialchars($old_input['kategori'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Jumlah *</label>
            <input type="number" name="jumlah" required min="0" value="<?= htmlspecialchars($old_input['jumlah'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Harga *</label>
            <input type="number" name="harga" required min="0" value="<?= htmlspecialchars($old_input['harga'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Supplier</label>
            <input type="text" name="supplier" maxlength="100" value="<?= htmlspecialchars($old_input['supplier'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" value="<?= htmlspecialchars($old_input['tanggal_masuk'] ?? date('Y-m-d')) ?>">
        </div>
        <div class="form-group">
            <label>Stok Minimum</label>
            <input type="number" name="stok_minimum" min="0" value="<?= htmlspecialchars($old_input['stok_minimum'] ?? 0) ?>">
        </div>
        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan"><?= htmlspecialchars($old_input['keterangan'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Gambar Produk</label>
            <input type="file" name="gambar" accept="image/jpeg,image/png">
            <small style="color:#666;">Format JPG/JPEG/PNG, maks 2MB</small>
        </div>
        <button type="submit" class="btn btn-add">Simpan</button>
    </form>
</div>
</body>
</html>