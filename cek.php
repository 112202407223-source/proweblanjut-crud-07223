<?php
// cek.php - Simpan di C:\xampp\htdocs\proweblanjut-crud-nim\cek.php
echo "<h2>DEBUG SISTEM INVENTARIS</h2>";

// 1. Cek folder
echo "<h3>1. Cek Struktur Folder:</h3>";
$folders = [
    'assets' => is_dir('assets'),
    'assets/css' => is_dir('assets/css'),
    'assets/css/style.css' => file_exists('assets/css/style.css')
];

foreach($folders as $nama => $exists) {
    if($exists) {
        echo "✅ $nama - ADA<br>";
    } else {
        echo "❌ $nama - TIDAK ADA<br>";
    }
}

// 2. Tampilkan isi folder
echo "<h3>2. Isi Folder Saat Ini:</h3>";
$files = scandir('.');
echo "<ul>";
foreach($files as $file) {
    if($file != '.' && $file != '..') {
        if(is_dir($file)) {
            echo "<li>📁 $file/</li>";
        } else {
            echo "<li>📄 $file</li>";
        }
    }
}
echo "</ul>";

// 3. Test koneksi database
echo "<h3>3. Test Koneksi Database:</h3>";
try {
    include 'koneksi.php';
    if(isset($db)) {
        echo "✅ Koneksi database berhasil<br>";
        
        // Test query
        $test = $db->query("SELECT COUNT(*) as total FROM barang");
        $result = $test->fetch(PDO::FETCH_ASSOC);
        echo "✅ Total data: " . $result['total'] . " barang<br>";
    } else {
        echo "❌ Variabel \$db tidak ditemukan<br>";
    }
} catch(Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

// 4. Cek file edit.php
echo "<h3>4. Cek File edit.php:</h3>";
if(file_exists('edit.php')) {
    echo "✅ File edit.php ADA<br>";
    
    // Baca 10 baris pertama
    $content = file('edit.php');
    echo "5 baris pertama file edit.php:<br>";
    echo "<pre>";
    for($i=0; $i<min(10, count($content)); $i++) {
        echo htmlspecialchars($content[$i]);
    }
    echo "</pre>";
} else {
    echo "❌ File edit.php TIDAK ADA<br>";
}

// 5. Informasi Path
echo "<h3>5. Informasi Path:</h3>";
echo "Current directory: " . __DIR__ . "<br>";
echo "Document root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Script name: " . $_SERVER['SCRIPT_NAME'] . "<br>";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "<br>";
?>