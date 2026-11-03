<?php
// Read-only integration against the configured database. No controller/ensure DDL runs.
if (PHP_SAPI !== 'cli') { exit; }
define('BASEPATH', __DIR__ . '/../system/');
define('APPPATH', __DIR__ . '/../application/');
define('ENVIRONMENT', 'testing');
define('FCPATH', dirname(__DIR__) . '/');
function log_message($level, $message) {}
function is_php($version) { return version_compare(PHP_VERSION, $version, '>='); }
class CI_Model {}
require APPPATH . 'config/database.php';
require BASEPATH . 'database/DB.php';
require APPPATH . 'models/admin/M_Stockopname.php';
class ReadOnlyOpname extends M_Stockopname {
    public $db;
    public function ensure_pending_tables() { return true; }
}
$model = new ReadOnlyOpname();
$config = $db['default'];
$config['db_debug'] = false;
$model->db = DB($config, true);
$options = $model->manual_barang_options('', 1, 2);
if (empty($options['results'])) { throw new RuntimeException('Katalog kosong'); }
$code = $options['results'][0]['id'];
$item = $model->catalog_item(0, $code);
if (!$item || !$model->catalog_scan('BR|'.$item['barang_id'])) { throw new RuntimeException('Lookup QR barang gagal'); }
echo "PASS: Lookup katalog dan QR barang\n";
$pending = $model->pending_master_options(2);
if ($pending) {
    $row = $pending[0];
    if (!$model->pending_master_option($row['kode_barang'], $row['expired_date'], $row['no_lot'])) { throw new RuntimeException('Pilihan pending gagal'); }
    if (!$model->get_master_barang_by_id($row['id'])) { throw new RuntimeException('Detail master gagal'); }
    $model->manual_expired_options($row['kode_barang'], '');
}
echo "PASS: Pilihan lot/expired, detail master, dan pending\n";
foreach (['base_query','monitoring_compare_all_base','monitoring_compare_lot_base'] as $name) {
    $method = new ReflectionMethod(M_Stockopname::class, $name);
    $method->setAccessible(true);
    $result = $model->db->query($method->invoke($model) . ' LIMIT 2');
    if ($result === false) { throw new RuntimeException($name . ': query gagal'); }
    echo 'PASS: Query database aktif ' . $name . PHP_EOL;
}
$rows = $model->pending_rows('', 500);
$unmapped = count(array_filter($rows, static function ($row) { return empty($row['master_id']); }));
$summary = $model->pending_summary();
if ($unmapped !== (int)$summary['unmatched_count']) { throw new RuntimeException('Status pemetaan pending tidak konsisten'); }
echo "PASS: Status pending dan peringatan konsisten ($unmapped belum terpetakan)\n";
$model->db->close();
