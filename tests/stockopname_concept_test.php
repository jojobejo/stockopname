<?php
// Read-only SQL regression: CTE fixtures shadow application tables; no DDL/DML.
if (PHP_SAPI !== 'cli') { exit; }
define('BASEPATH', __DIR__);
define('ENVIRONMENT', 'testing');
class CI_Model {}
require __DIR__ . '/../application/models/admin/M_Stockopname.php';
class MetadataFixture {
    public function field_exists($field, $table) { return true; }
    public function table_exists($table) { return true; }
}
class OpnameFixture extends M_Stockopname {
    public $db;
    public function ensure_pending_tables() { return true; }
}
require __DIR__ . '/../application/config/database.php';
$config = $db['default'];
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$connection = new mysqli($config['hostname'], $config['username'], $config['password'], $config['database']);
$connection->set_charset('utf8mb4');
$model = new OpnameFixture();
$model->db = new MetadataFixture();
function invokeModel($name, $args = []) {
    global $model;
    $method = new ReflectionMethod(M_Stockopname::class, $name);
    $method->setAccessible(true);
    return $method->invokeArgs($model, $args);
}
function check($condition, $message) {
    if (!$condition) { throw new RuntimeException($message); }
    echo "PASS: $message\n";
}
$fixture = "WITH stockopname_master_item AS (
    SELECT 1 id, 'A' kode_barang, 'Barang' nama_barang, '2027-01-01' expired_date, 'LOT1' no_lot,
        100 qty, 4 qty_box, 4 qty_pcs, 0 pending_qty, 0 pending_qty_box, 0 pending_qty_pcs
    UNION ALL SELECT 2,'A','Barang','2027-01-01','LOT2',50,2,2,0,0,0
    UNION ALL SELECT 3,'B','Barang','2027-01-01','LOT1',20,0,20,0,0,0
), stockopname_pending AS (
    SELECT 'A' kode_barang, 'Barang' nama_barang, '2027-01-01' expired_date, 'LOT1' no_lot, 10 qty, 0 qty_box, 10 qty_pcs
), stockopname_opname AS (
    SELECT 'A' kode_barang, 'Barang' nama_barang, '2027-01-01' expired_date, 'LOT1' no_lot,
        105 qty, 4 qty_box, 9 qty_pcs, 1 tim_opname, 'U1' input_by, 1 wilayah, 'scan_qrcode' input_source, '2026-11-03' created_at
    UNION ALL SELECT 'A','Barang','2027-01-01','LOT1',110,4,14,2,'U2',1,'manual_input','2026-11-03'
    UNION ALL SELECT 'A','Barang','2027-01-01','LOT2',50,2,2,1,'U1',1,'scan_qrcode','2026-11-03'
) ";
function fixtureRows($method) {
    global $connection, $fixture;
    return $connection->query($fixture . invokeModel($method))->fetch_all(MYSQLI_ASSOC);
}
$rows = fixtureRows('monitoring_compare_lot_base');
check(count($rows) === 3, 'Lot berbeda dan kode berbeda tidak tergabung');
$keyed = [];
foreach ($rows as $row) { $keyed[$row['kode_barang'].'|'.$row['no_lot']] = $row; }
check((int)$keyed['A|LOT1']['qty_buku'] === 110, 'Buku 100 + pending 10 = pembanding 110');
check((int)$keyed['A|LOT2']['qty_buku'] === 50, 'Pending tidak bocor ke lot lain');
check((int)$keyed['B|LOT1']['qty_buku'] === 20, 'Nama sama tidak mencampur pending kode lain');
check($keyed['A|LOT1']['status_opname'] === 'tim_2', 'Tim 2 dipilih ketika hanya tim 2 match');
check($keyed['A|LOT1']['kelengkapan_tim'] === 'lengkap' && $keyed['A|LOT1']['perbandingan_tim'] === 'berbeda', 'Kelengkapan dan beda qty kedua tim');
check($keyed['A|LOT2']['kelengkapan_tim'] === 'menunggu_tim_2', 'Tim yang belum mengisi dibedakan dari qty nol');
check($keyed['B|LOT1']['kelengkapan_tim'] === 'belum_diisi', 'Barang belum dihitung tetap terlihat');
$rows = fixtureRows('base_query');
foreach ($rows as $row) {
    if ($row['kode_barang'] === 'A' && $row['no_lot'] === 'LOT1') {
        check((int)$row['qty_fisik'] === 110 && (int)$row['selisih'] === 0, 'Dashboard memakai qty tim yang match');
    }
}
check(count(fixtureRows('monitoring_compare_all_base')) === 2, 'Ringkasan per barang dapat dieksekusi');
check($model->normalize_pending_calculation_mode('subtract') === 'add', 'Pending selalu menambah');
$payload = invokeModel('opname_payload_from_master', [['id'=>1,'kode_barang'=>'A','dimensi'=>24], ['qty_box'=>3,'qty_pcs'=>5], 'scan_qrcode']);
check($payload['qty'] === 77 && $payload['qty_box'] === 3 && $payload['qty_pcs'] === 5, '3 box isi 24 + 5 pcs = 77');
$all = fixtureRows('monitoring_compare_all_base');
foreach ($all as $row) {
    if ($row['kode_barang'] === 'A') {
        check($row['kelengkapan_tim'] === 'belum_lengkap' && (int)$row['lot_tim_2'] === 1, 'Kelengkapan barang memeriksa semua lot');
    }
}
$originalFixture = $fixture;
$fixture = str_replace('105 qty, 4 qty_box, 9 qty_pcs', '110 qty, 4 qty_box, 14 qty_pcs', $originalFixture);
$rows = fixtureRows('monitoring_compare_lot_base');
foreach ($rows as $row) { if ($row['kode_barang'] === 'A' && $row['no_lot'] === 'LOT1') { check($row['status_opname'] === 'all_match' && $row['perbandingan_tim'] === 'sama', 'Kedua tim match dan sama'); } }
$fixture = str_replace("'LOT1',110,4,14,2", "'LOT1',109,4,13,2", $originalFixture);
$rows = fixtureRows('monitoring_compare_lot_base');
foreach ($rows as $row) { if ($row['kode_barang'] === 'A' && $row['no_lot'] === 'LOT1') { check($row['status_opname'] === 're_check', 'Tidak ada tim match perlu cek ulang'); } }
$fixture = str_replace('100 qty, 4 qty_box, 4 qty_pcs, 0 pending_qty', '110 qty, 4 qty_box, 14 qty_pcs, 10 pending_qty', $originalFixture);
$rows = fixtureRows('monitoring_compare_lot_base');
foreach ($rows as $row) { if ($row['kode_barang'] === 'A' && $row['no_lot'] === 'LOT1') { check((int)$row['qty_buku'] === 110, 'Marker pending legacy tidak dihitung dua kali'); } }
$fixture = str_replace("'LOT2',50,2,2,0,0,0", "'LOT2',0,0,0,0,0,0", $originalFixture);
$fixture = str_replace("'LOT2',50,2,2,1", "'LOT2',0,0,0,1", $fixture);
$rows = fixtureRows('monitoring_compare_lot_base');
foreach ($rows as $row) { if ($row['kode_barang'] === 'A' && $row['no_lot'] === 'LOT2') { check($row['status_opname'] === 'tim_1' && $row['kelengkapan_tim'] === 'menunggu_tim_2', 'Qty nol tercatat sebagai sudah diperiksa'); } }
class DeleteCaptureFixture extends MetadataFixture {
    public $filters = [];
    public function where($key, $value) { $this->filters[$key] = $value; return $this; }
    public function delete($table) { return true; }
    public function affected_rows() { return 1; }
}
$model->db = new DeleteCaptureFixture();
invokeModel('delete_rows_by_barang_expired_lot', ['stockopname_opname', 'A', '2027-01-01', 'LOT1']);
check($model->db->filters === ['kode_barang'=>'A','expired_date'=>'2027-01-01','no_lot'=>'LOT1'], 'Penghapusan detail membatasi kode, expired, dan lot');
$connection->close();
