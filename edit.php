<?php
// edit.php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require_once 'koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}
$id = $_GET['id'];

// Ambil data lama dari database
$stmt = $db->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->execute([$id]);
$barang = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$barang) {
    header("Location: index.php?error=Data tidak ditemukan");
    exit;
}

$errors = [];
$old_input = []; // untuk retensi input saat validasi gagal

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
    $gambar_lama   = $barang['gambar'];

    // Simpan input untuk retensi jika gagal
    $old_input = compact('nama_barang', 'kategori', 'jumlah', 'harga', 'supplier', 'tanggal_masuk', 'stok_minimum', 'keterangan');

    // Validasi
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
    } else {
        // Validasi format tanggal YYYY-MM-DD
        $date = DateTime::createFromFormat('Y-m-d', $tanggal_masuk);
        if (!$date || $date->format('Y-m-d') !== $tanggal_masuk) {
            $errors[] = "Format tanggal tidak valid (gunakan YYYY-MM-DD).";
        }
    }

    // Proses upload gambar baru
    $gambar_baru = $gambar_lama;
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
        // Validasi MIME type dengan getimagesize
        if (empty($errors)) {
            $check = getimagesize($file_tmp);
            if ($check === false) {
                $errors[] = "File bukan gambar yang valid.";
            }
        }
        if (empty($errors)) {
            // Pastikan folder uploads ada
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }
            $nama_unik = uniqid() . '.' . $file_ext;
            $upload_path = 'uploads/' . $nama_unik;
            if (move_uploaded_file($file_tmp, $upload_path)) {
                // Hapus gambar lama jika ada
                if ($gambar_lama && file_exists('uploads/' . $gambar_lama)) {
                    unlink('uploads/' . $gambar_lama);
                }
                $gambar_baru = $nama_unik;
            } else {
                $errors[] = "Gagal mengunggah gambar.";
            }
        }
    }

    // Update database jika tidak ada error
    if (empty($errors)) {
        $sql = "UPDATE barang SET 
                nama_barang = ?, kategori = ?, jumlah = ?, harga = ?,
                supplier = ?, tanggal_masuk = ?, stok_minimum = ?,
                keterangan = ?, gambar = ?
                WHERE id = ?";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([$nama_barang, $kategori, $jumlah, $harga, $supplier, $tanggal_masuk, $stok_minimum, $keterangan, $gambar_baru, $id]);
        if ($result) {
            header("Location: index.php?message=Data berhasil diupdate");
            exit;
        } else {
            $errors[] = "Gagal mengupdate data.";
        }
    }
}

// Fungsi helper untuk menampilkan nilai input (prioritaskan old_input jika ada)
function getOldOrDb($field, $old_input, $barang) {
    if (!empty($old_input) && isset($old_input[$field])) {
        return htmlspecialchars($old_input[$field]);
    }
    return htmlspecialchars($barang[$field] ?? '');
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
            <input type="text" name="nama_barang" required maxlength="100" value="<?= getOldOrDb('nama_barang', $old_input, $barang) ?>">
        </div>
        <div class="form-group">
            <label>Kategori</label>
            <input type="text" name="kategori" maxlength="50" value="<?= getOldOrDb('kategori', $old_input, $barang) ?>">
        </div>
        <div class="form-group">
            <label>Jumlah *</label>
            <input type="number" name="jumlah" required min="0" value="<?= getOldOrDb('jumlah', $old_input, $barang) ?>">
        </div>
        <div class="form-group">
            <label>Harga *</label>
            <input type="number" name="harga" required min="0" value="<?= getOldOrDb('harga', $old_input, $barang) ?>">
        </div>
        <div class="form-group">
            <label>Supplier</label>
            <input type="text" name="supplier" maxlength="100" value="<?= getOldOrDb('supplier', $old_input, $barang) ?>">
        </div>
        <div class="form-group">
            <label>Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" value="<?= getOldOrDb('tanggal_masuk', $old_input, $barang) ?>">
        </div>
        <div class="form-group">
            <label>Stok Minimum</label>
            <input type="number" name="stok_minimum" min="0" value="<?= getOldOrDb('stok_minimum', $old_input, $barang) ?>">
        </div>
        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan"><?= getOldOrDb('keterangan', $old_input, $barang) ?></textarea>
        </div>
        <div class="form-group">
            <label>Gambar Saat Ini</label><br>
            <?php if ($barang['gambar'] && file_exists('uploads/' . $barang['gambar'])): ?>
                <img src="uploads/<?= $barang['gambar'] ?>" width="100" style="border-radius: 8px;"><br>
            <?php else: ?>
                <span style="color:#999;">Tidak ada gambar</span><br>
            <?php endif; ?>
            <label style="margin-top: 10px;">Ganti Gambar</label>
            <input type="file" name="gambar" accept="image/jpeg,image/png">
            <small style="color:#666;">Kosongkan jika tidak ingin mengubah gambar (format JPG/PNG, maks 2MB)</small>
        </div>
        <button type="submit" class="btn btn-add">Update</button>
    </form>
</div>
</body>
</html>