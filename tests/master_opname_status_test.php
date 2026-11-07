<?php
if (PHP_SAPI !== 'cli') { exit; }
define('BASEPATH', __DIR__ . '/../system/');
define('APPPATH', __DIR__ . '/../application/');
define('ENVIRONMENT', 'testing');
define('FCPATH', dirname(__DIR__) . '/');
function log_message($level, $message) {}
function is_php($version) { return version_compare(PHP_VERSION, $version, '>='); }
class CI_Model {}
require APPPATH . 'models/admin/M_Stockopname.php';
$model = new M_Stockopname();
$base = ['catalog_id'=>2,'kode_barang'=>'Q1','nama_barang'=>'Barang A','catalog_nama_barang'=>'Barang A','expired_date'=>'2028-01-01','qrcode_ready'=>0,'qrcode_variant_saved'=>0];
$lots = ['Q1'=>[['nama_barang'=>'Barang A','expired_date'=>'2028-01-01']]];
$cases = [
 ['not_in_local', array_merge($base,['catalog_id'=>null]), $lots],
 ['name_mismatch', array_merge($base,['catalog_nama_barang'=>'Barang B']), $lots],
 ['unverified', $base, null],
 ['not_in_zahir', $base, []],
 ['pair_missing', $base, ['Q1'=>[['nama_barang'=>'Barang A','expired_date'=>'2029-01-01']]]],
 ['not_generated', $base, $lots],
 ['file_missing', array_merge($base,['qrcode_variant_saved'=>1]), $lots],
 ['generated', array_merge($base,['qrcode_ready'=>1,'qrcode_variant_saved'=>1]), $lots],
];
foreach ($cases as [$expected,$row,$source]) {
 $actual = $model->master_barang_sync_status($row,$source)['status'];
 if ($actual !== $expected) { throw new RuntimeException("$expected: $actual"); }
}
echo "PASS: Delapan kategori status termasuk sumber gagal\n";
require APPPATH . 'config/database.php';
require BASEPATH . 'database/DB.php';
$model->db = DB(array_merge($db['default'],['db_debug'=>false]),true);
foreach (['','pending','mismatch','generated'] as $status) {
 foreach ([0,1] as $page) {
  $result = $model->get_master_barang_datatable(['draw'=>1,'start'=>$page*5,'length'=>5,'qrcode_status'=>$status,'search'=>['value'=>'A']], 'positive', []);
  if ($result['recordsFiltered'] > $result['recordsTotal'] || count($result['data']) > 5) { throw new RuntimeException("Filter/page invalid: $status/$page"); }
  if ($page && $result['recordsFiltered'] <= 5 && count($result['data'])) { throw new RuntimeException("Pagination invalid: $status"); }
 }
}
echo "PASS: Filter, pencarian, jumlah, pagination DataTables\n";
$model->db->close();
