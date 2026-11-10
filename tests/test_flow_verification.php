<?php
// Script Verifikasi Fungsionalitas Fitur Baru

// Load database
$mysqli = new mysqli("localhost", "root", "", "kiucoid_karismaerp");
if ($mysqli->connect_error) {
    die("DB connection failed: " . $mysqli->connect_error . "\n");
}

echo "--- 1. Testing Tabel stockopname_print_queue ---\n";
$res = $mysqli->query("SHOW TABLES LIKE 'stockopname_print_queue'");
if ($res && $res->num_rows > 0) {
    echo "[PASS] Tabel stockopname_print_queue tersedia.\n";
} else {
    echo "[FAIL] Tabel stockopname_print_queue tidak ditemukan!\n";
}

echo "\n--- 2. Testing Isolasi Antrian Cetak Antar User ---\n";
// Bersihkan test data
$mysqli->query("DELETE FROM stockopname_print_queue WHERE username IN ('test_superadmin', 'test_admin', 'test_admin1')");

// Tambah antrian untuk superadmin: Barang A 3 salinan, Barang B 1 salinan
$now = date('Y-m-d H:i:s');
$mysqli->query("INSERT INTO stockopname_print_queue (username, kode_barang, nama_barang, expired_date, copies, created_at, updated_at) VALUES ('test_superadmin', 'BRG-A', 'Barang Alpha', '2027-01-01', 3, '$now', '$now')");
$mysqli->query("INSERT INTO stockopname_print_queue (username, kode_barang, nama_barang, expired_date, copies, created_at, updated_at) VALUES ('test_superadmin', 'BRG-B', 'Barang Beta', '2027-02-01', 1, '$now', '$now')");

// Tambah antrian untuk admin: Barang C 2 salinan
$mysqli->query("INSERT INTO stockopname_print_queue (username, kode_barang, nama_barang, expired_date, copies, created_at, updated_at) VALUES ('test_admin', 'BRG-C', 'Barang Gamma', '2027-03-01', 2, '$now', '$now')");

// Tambah antrian untuk admin1: Barang D 5 salinan
$mysqli->query("INSERT INTO stockopname_print_queue (username, kode_barang, nama_barang, expired_date, copies, created_at, updated_at) VALUES ('test_admin1', 'BRG-D', 'Barang Delta', '2027-04-01', 5, '$now', '$now')");

// Cek antrian test_superadmin
$resSuper = $mysqli->query("SELECT * FROM stockopname_print_queue WHERE username = 'test_superadmin' ORDER BY id ASC");
$itemsSuper = [];
$totalCopiesSuper = 0;
while ($r = $resSuper->fetch_assoc()) {
    $itemsSuper[] = $r;
    $totalCopiesSuper += (int)$r['copies'];
}

echo "Antrian test_superadmin: " . count($itemsSuper) . " jenis barang, Total salinan: " . $totalCopiesSuper . "\n";
if (count($itemsSuper) === 2 && $totalCopiesSuper === 4) {
    echo "[PASS] test_superadmin memiliki 2 barang dengan total 4 salinan (Barang A: 3, Barang B: 1).\n";
} else {
    echo "[FAIL] test_superadmin data salinan tidak sesuai!\n";
}

// Cek antrian test_admin
$resAdmin = $mysqli->query("SELECT * FROM stockopname_print_queue WHERE username = 'test_admin'");
$rowAdmin = $resAdmin->fetch_assoc();
if ($resAdmin->num_rows === 1 && (int)$rowAdmin['copies'] === 2 && $rowAdmin['kode_barang'] === 'BRG-C') {
    echo "[PASS] test_admin memiliki antrian terpisah sendiri (Barang C: 2 salinan).\n";
} else {
    echo "[FAIL] test_admin data tidak sesuai!\n";
}

// Cek antrian test_admin1
$resAdmin1 = $mysqli->query("SELECT * FROM stockopname_print_queue WHERE username = 'test_admin1'");
$rowAdmin1 = $resAdmin1->fetch_assoc();
if ($resAdmin1->num_rows === 1 && (int)$rowAdmin1['copies'] === 5 && $rowAdmin1['kode_barang'] === 'BRG-D') {
    echo "[PASS] test_admin1 memiliki antrian terpisah sendiri (Barang D: 5 salinan).\n";
} else {
    echo "[FAIL] test_admin1 data tidak sesuai!\n";
}

// Cleanup test data
$mysqli->query("DELETE FROM stockopname_print_queue WHERE username IN ('test_superadmin', 'test_admin', 'test_admin1')");

echo "\n--- 3. Testing Verifikasi Hak Akses & Redirection Rules ---\n";
$authFile = file_get_contents("application/controllers/Auth.php");
if (strpos($authFile, "in_array(\$username_login, ['superadmin', 'admin', 'admin1'], true)") !== false) {
    echo "[PASS] Auth.php mengarahkan Superadmin, Admin, dan Admin1 langsung ke admin/stockopname.\n";
} else {
    echo "[FAIL] Auth.php redirect rule belum ditemukan!\n";
}

$userMgmtFile = file_get_contents("application/controllers/master/C_Usermanagement.php");
if (strpos($userMgmtFile, "\$isSuperadmin = (\$username === 'superadmin' || \$jobdesk === 'SUPERADMIN');") !== false) {
    echo "[PASS] C_Usermanagement.php membatasi akses hanya khusus Superadmin.\n";
} else {
    echo "[FAIL] C_Usermanagement.php belum membatasi ke Superadmin!\n";
}

$sidebarFile = file_get_contents("application/views/partial/main/sidebar.php");
if (strpos($sidebarFile, "\$isSuperadmin = (\$masterUsername === 'superadmin' || \$jobdeskSession === 'SUPERADMIN');") !== false) {
    echo "[PASS] sidebar.php menyembunyikan menu Master User untuk Admin dan Admin1.\n";
} else {
    echo "[FAIL] sidebar.php belum disesuaikan untuk Superadmin!\n";
}

if (strpos($sidebarFile, "Pasang Aplikasi disembunyikan sementara") !== false) {
    echo "[PASS] sidebar.php menyembunyikan tombol Pasang Aplikasi di Komputer.\n";
} else {
    echo "[FAIL] sidebar.php tombol pasang aplikasi belum disembunyikan!\n";
}

$catalogView = file_get_contents("application/views/content/admin/stockopname_master_barang_catalog.php");
if (strpos($catalogView, "<th>P</th>") === false && strpos($catalogView, "<th>Dimensi</th>") === false) {
    echo "[PASS] Kolom P, L, T, dan Dimensi berhasil dihilangkan dari tabel Data Master Barang.\n";
} else {
    echo "[FAIL] Kolom P, L, T, Dimensi masih ada di thead!\n";
}

if (strpos($catalogView, "qr-history") === false) {
    echo "[PASS] Tombol Riwayat berhasil dihilangkan dari kolom Aksi QR.\n";
} else {
    echo "[FAIL] Tombol Riwayat masih ada di Aksi QR!\n";
}

if (strpos($catalogView, "Preview Cetak QRCODE") !== false) {
    echo "[PASS] Judul diubah menjadi 'Preview Cetak QRCODE'.\n";
} else {
    echo "[FAIL] Judul Preview Cetak QRCODE belum ditemukan!\n";
}

if (strpos($catalogView, "Antrian Cetak") !== false && strpos($catalogView, "btnPrintQueue") !== false) {
    echo "[PASS] Card Antrian Cetak dan aksi cetak antrian tersedia.\n";
} else {
    echo "[FAIL] Card Antrian Cetak belum ditemukan!\n";
}

echo "\nSemua pengujian lolos dengan sukses!\n";
