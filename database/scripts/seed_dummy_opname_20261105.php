<?php
// CLI only. Dry run by default; --apply inserts the two reviewed test items.
if (PHP_SAPI !== 'cli') { exit(1); }
define('BASEPATH', dirname(__DIR__, 2) . '/system/');
define('ENVIRONMENT', 'production');
require dirname(__DIR__, 2) . '/application/config/database.php';
date_default_timezone_set('Asia/Bangkok');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$config = $db['default'];
$conn = new mysqli($config['hostname'], $config['username'], $config['password'], $config['database']);
$conn->set_charset('utf8mb4');
$apply = in_array('--apply', $argv, true);
$items = [
    ['AALUF01', 'Alufos Agro 56 TB 42 X 90 gr*', 42, '2031-04-01', 210, 5, 0],
    ['AAMOL01', 'Amolin 300 EC 100 X 100 ml', 100, '2028-01-01', 315, 3, 15],
];
$marker = 'DUMMY-OPNAME-20261105';
$actor = 'codex-dummy-test';
$folder = dirname(__DIR__) . '/backups/dummy_opname_20261105_' . date('Ymd_His');
try {
    $masterBarang = $conn->query("SELECT id,kd_barang,nama_barang,dimensi FROM tb_master_barang_all WHERE kd_barang IN ('AALUF01','AAMOL01') ORDER BY kd_barang")->fetch_all(MYSQLI_ASSOC);
    if (count($masterBarang) !== 2) { throw new RuntimeException('Master Barang tidak tepat dua kode unik.'); }
    foreach ($items as $item) {
        $matches = array_values(array_filter($masterBarang, static function ($row) use ($item) { return $row['kd_barang'] === $item[0]; }));
        if (count($matches) !== 1 || $matches[0]['nama_barang'] !== $item[1] || (int)$matches[0]['dimensi'] !== $item[2]) {
            throw new RuntimeException('Nama/dimensi Master Barang tidak sesuai untuk ' . $item[0]);
        }
        if ($item[4] !== $item[5] * $item[2] + $item[6]) { throw new RuntimeException('Konversi box/pcs tidak sesuai.'); }
    }
    $existingMaster = (int)$conn->query("SELECT COUNT(*) n FROM stockopname_master_item WHERE kode_barang IN ('AALUF01','AAMOL01')")->fetch_assoc()['n'];
    $existingPending = (int)$conn->query("SELECT COUNT(*) n FROM stockopname_pending WHERE kode_barang IN ('AALUF01','AAMOL01') OR kd_do = 'DUMMY-OPNAME-20261105'")->fetch_assoc()['n'];
    $plan = ['mode' => $apply ? 'apply' : 'dry-run', 'master_barang' => $masterBarang,
        'existing_master' => $existingMaster, 'existing_pending' => $existingPending,
        'new_master' => $items, 'new_pending' => ['kode_barang' => 'AALUF01', 'expired_date' => '2031-04-01', 'no_lot' => '-', 'qty_box' => 1, 'qty_pcs' => 7, 'qty' => 49]];
    if ($existingMaster !== 0 || $existingPending !== 0) { throw new RuntimeException('Kode target atau marker dummy sudah digunakan; tidak menambah duplikat.'); }
    if (!$apply) { echo json_encode($plan, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL; exit(0); }
    if (!mkdir($folder, 0775, true)) { throw new RuntimeException('Folder backup gagal dibuat.'); }
    file_put_contents($folder . '/before.json', json_encode($plan, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR), LOCK_EX);
    $conn->begin_transaction();
    $insertMaster = $conn->prepare('INSERT INTO stockopname_master_item (kode_barang,nama_barang,dimensi,expired_date,qty,qty_box,qty_pcs,no_lot,input_source) VALUES (?,?,?,?,?,?,?,?,?)');
    $masterIds = [];
    foreach ($items as $item) {
        [$code, $name, $dimensi, $expiry, $qty, $box, $pcs] = $item;
        $lot = '-'; $source = 'DUMMY_TEST_20261105';
        $insertMaster->bind_param('ssisiiiss', $code, $name, $dimensi, $expiry, $qty, $box, $pcs, $lot, $source);
        $insertMaster->execute();
        $masterIds[$code] = $conn->insert_id;
    }
    $pending = $conn->prepare('INSERT INTO stockopname_pending (kd_do,kode_barang,kd_faktur,tgl_transaksi,nama_barang,expired_date,qty,qty_pcs,qty_box,created_by,no_lot,exp_date) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)');
    $date = date('Y-m-d'); $code = $items[0][0]; $name = $items[0][1]; $expiry = $items[0][3];
    $qty = 49; $pcs = 7; $box = 1; $lot = '-';
    $pending->bind_param('ssssssiiisss', $marker, $code, $marker, $date, $name, $expiry, $qty, $pcs, $box, $actor, $lot, $expiry);
    $pending->execute();
    $pendingId = $conn->insert_id;
    $result = $conn->query("SELECT m.id,m.kode_barang,m.nama_barang,m.expired_date,m.no_lot,m.dimensi,m.qty,m.qty_box,m.qty_pcs,COALESCE(p.qty,0) pending_qty,COALESCE(p.qty_box,0) pending_box,COALESCE(p.qty_pcs,0) pending_pcs,m.qty+COALESCE(p.qty,0) qty_sistem FROM stockopname_master_item m LEFT JOIN stockopname_pending p ON p.kode_barang=m.kode_barang AND p.no_lot=m.no_lot AND p.expired_date=m.expired_date WHERE m.kode_barang IN ('AALUF01','AAMOL01') ORDER BY m.kode_barang")->fetch_all(MYSQLI_ASSOC);
    if (count($result) !== 2 || (int)$result[0]['qty_sistem'] !== 259 || (int)$result[1]['qty_sistem'] !== 315 || (int)$result[1]['pending_qty'] !== 0) {
        throw new RuntimeException('Verifikasi perhitungan dummy gagal.');
    }
    $rollback = "-- Jalankan setelah memastikan tidak ada hasil opname baru yang memakai kedua master.\nSTART TRANSACTION;\n"
        . "DELETE FROM stockopname_pending WHERE id = {$pendingId} AND kd_do = '{$marker}' AND kode_barang = 'AALUF01';\n";
    foreach ($masterIds as $itemCode => $id) {
        $rollback .= "DELETE FROM stockopname_master_item WHERE id = {$id} AND kode_barang = '{$itemCode}' AND input_source = 'DUMMY_TEST_20261105' AND NOT EXISTS (SELECT 1 FROM stockopname_opname WHERE source_id = {$id}) AND NOT EXISTS (SELECT 1 FROM stockopname_pending WHERE kode_barang = '{$itemCode}');\n";
    }
    $rollback .= "COMMIT;\n";
    file_put_contents($folder . '/rollback.sql', $rollback, LOCK_EX);
    file_put_contents($folder . '/verification.json', json_encode(['master_ids' => $masterIds, 'pending_id' => $pendingId, 'rows' => $result], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR), LOCK_EX);
    $conn->commit();
    echo json_encode(['backup' => $folder, 'master_ids' => $masterIds, 'pending_id' => $pendingId, 'rows' => $result], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
} catch (Throwable $error) {
    try { $conn->rollback(); } catch (Throwable $ignored) {}
    fwrite(STDERR, 'Seed dibatalkan: ' . $error->getMessage() . PHP_EOL);
    exit(1);
} finally {
    $conn->close();
}
