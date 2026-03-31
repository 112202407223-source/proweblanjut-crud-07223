<?php
// index.php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once 'koneksi.php';

$stmt = $db->query("SELECT * FROM barang ORDER BY id DESC");
$barang = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Inventaris Barang</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <!-- Info User dan Logout -->
        <div class="user-info">
            <div class="user-greeting">
                 Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
            </div>
            <a href="logout.php" class="logout-btn" onclick="return confirm('Apakah Anda yakin ingin logout?')">Logout</a>
        </div>
        
        <h1>Manajemen Inventaris Barang</h1>
       
        <div style="margin-bottom: 20px;">
            <a href="tambah.php" class="btn btn-add">Tambah Barang Baru</a>
        </div>
       
        <?php if(isset($_GET['message'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
        <?php endif; ?>
        
        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
       
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Supplier</th>
                    <th>Tgl Masuk</th>
                    <th>Stok Min</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if($barang): ?>
                    <?php foreach($barang as $b): ?>
                    <tr>
                        <td><?php echo $b['id']; ?></td>
                        <td><?php echo htmlspecialchars($b['nama_barang']); ?></td>
                        <td><?php echo htmlspecialchars($b['kategori']); ?></td>
                        <td><?php echo $b['jumlah']; ?></td>
                        <td>Rp <?php echo number_format($b['harga'],0,',','.'); ?></td>
                        <td><?php echo htmlspecialchars($b['supplier']); ?></td>
                        <td><?php echo $b['tanggal_masuk']; ?></td>
                        <td><?php echo $b['stok_minimum']; ?></td>
                        <td><?php echo htmlspecialchars($b['keterangan']); ?></td>
                        <td class="action-buttons">
                            <a href="edit.php?id=<?php echo $b['id']; ?>" class="btn btn-edit">Edit</a>
                            <form method="POST" action="hapus.php" onsubmit="return confirm('Yakin ingin menghapus data ini?');" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $b['id']; ?>">
                                <button type="submit" class="btn btn-delete">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center">Tidak ada data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>