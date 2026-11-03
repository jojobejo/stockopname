<?php
// Read-only aggregate audit; never prints credentials or individual business records.
if (PHP_SAPI !== 'cli') { exit; }
define('BASEPATH', __DIR__);
define('ENVIRONMENT', 'testing');
require __DIR__ . '/../application/config/database.php';
$c = $db['default'];
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli($c['hostname'], $c['username'], $c['password'], $c['database']);
$queries = [
    'master_barang' => 'SELECT COUNT(*) AS total, SUM(dimensi <> p*l*t) AS beda_dimensi, SUM(p*l*t <= 0) AS isi_box_nol FROM tb_master_barang_all',
    'kode_duplikat' => 'SELECT COUNT(*) AS total FROM (SELECT kd_barang FROM tb_master_barang_all GROUP BY kd_barang HAVING COUNT(*) > 1) d',
    'master_opname' => 'SELECT COUNT(*) AS total, SUM(pending_qty <> 0 OR pending_qty_box <> 0 OR pending_qty_pcs <> 0) AS marker_legacy FROM stockopname_master_item',
    'hasil' => 'SELECT COUNT(*) AS total FROM stockopname_opname',
    'pending_key' => "SELECT SUM(TRIM(kode_barang)='') AS kode_kosong, SUM(expired_date IS NULL) AS expired_kosong, SUM(TRIM(no_lot) IN ('','-','0')) AS lot_kosong, SUM(EXISTS(SELECT 1 FROM stockopname_master_item m WHERE m.kode_barang=p.kode_barang)) AS cocok_kode, SUM(EXISTS(SELECT 1 FROM stockopname_master_item m WHERE m.nama_barang=p.nama_barang AND m.expired_date=p.expired_date)) AS cocok_nama_expired FROM stockopname_pending p",
    'pending' => "SELECT COUNT(*) AS total, SUM(NOT EXISTS(SELECT 1 FROM stockopname_master_item m WHERE m.kode_barang=p.kode_barang AND m.expired_date=p.expired_date AND COALESCE(NULLIF(TRIM(m.no_lot),''),'-')=COALESCE(NULLIF(TRIM(p.no_lot),''),'-'))) AS belum_terpetakan FROM stockopname_pending p",
];
foreach ($queries as $name => $sql) { echo $name . ': ' . json_encode($conn->query($sql)->fetch_assoc()) . PHP_EOL; }
$conn->close();
