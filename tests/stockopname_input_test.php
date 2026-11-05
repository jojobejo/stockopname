<?php
// Controller contract tests with in-memory doubles; no application sessions/data changed.
if (PHP_SAPI !== 'cli') { exit; }
define('BASEPATH', __DIR__);
class CI_Controller {}
function show_error($message, $code = 500, $title = '') { throw new RuntimeException($message, $code); }
function redirect($target) { throw new RuntimeException('redirect', 302); }
require __DIR__.'/../application/controllers/admin/C_Stockopname.php';
class InputFixture {
    public $values = [];
    public function post($key = null, $filter = true) { return $key === null ? $this->values : ($this->values[$key] ?? null); }
}
class SessionFixture {
    public $values = ['logged_in'=>true,'jobdesk'=>'STOCKOPNAME','tim'=>1,'wilayah'=>7,'nama'=>'Tester'];
    public function userdata($key) { return $this->values[$key] ?? null; }
}
class OutputFixture {
    public $result;
    public function set_content_type($type) { return $this; }
    public function set_output($data) { $this->result = json_decode($data, true); return $this; }
}
class RouterFixture {
    public $method = 'ajax_input_save';
    public function fetch_method() { return $this->method; }
}
class CatalogFixture {
    public $rows = [['kode_barang'=>'A','nama_barang'=>'Barang','expired_date'=>'2027-01-01','dimensi'=>24]];
    public function rows($refresh = false) { return ['status'=>true,'data'=>$this->rows]; }
}
class LoaderFixture {
    public function library($name) {}
}
class StockFixture {
    public $writes = [];
    public $dimensi = 24;
    public function ensure_master_code_columns() { return true; }
    public function ensure_manual_tables() { return true; }
    public function wilayah_by_id($id) { return $id === 7 ? ['id'=>7] : null; }
    public function catalog_item($id = 0, $code = '') {
        return $id === 99 ? null : ['barang_id'=>3,'kode_barang'=>'A','nama_barang'=>'Barang','dimensi'=>$this->dimensi];
    }
    public function get_master_barang_by_id($id) {
        return $id === 10 ? ['id'=>10,'kode_barang'=>'A','nama_barang'=>'Barang','no_lot'=>'LOT1','expired_date'=>'2027-01-01','dimensi'=>$this->dimensi] : null;
    }
    public function save_mobile_opname($row, $payload) { $this->writes[] = ['scan',$row,$payload]; return 1; }
    public function save_manual_input_to_opname($row, $payload) {
        $this->writes[] = ['manual',$row,$payload]; return ['status'=>true,'data'=>['id'=>1]];
    }
    public function save_manual_master_item_queue($row, $payload, $status) {
        $this->writes[] = ['queue',$row,$payload,$status]; return ['status'=>true,'data'=>['id'=>1]];
    }
}
function checkInput($condition, $message) {
    if (!$condition) { throw new RuntimeException($message); }
    echo "PASS: $message\n";
}
$controller = (new ReflectionClass(C_Stockopname::class))->newInstanceWithoutConstructor();
$controller->input = new InputFixture();
$controller->session = new SessionFixture();
$controller->output = new OutputFixture();
$controller->router = new RouterFixture();
$controller->stockopname = new StockFixture();
$controller->load = new LoaderFixture();
$controller->zahir_lot_catalog = new CatalogFixture();
$valid = ['scan_value'=>'MB|3|2027-01-01','qty_box'=>'3','qty_pcs'=>'5','wilayah'=>999,'tim_opname'=>2];
$controller->router->method = 'ajax_input_lookup';
$controller->input->values = $valid;
$controller->ajax_input_lookup();
checkInput($controller->output->result['status'] && $controller->output->result['data']['expired_date'] === '2027-01-01', 'Scan menampilkan expired date dari Data Master Barang');
$controller->router->method = 'ajax_input_save';
$controller->input->values = $valid;
$controller->ajax_input_save();
$write = $controller->stockopname->writes[0];
checkInput($controller->output->result['status'] && $write[2]['wilayah'] === 7 && $write[2]['tim_opname'] === 1, 'Tim/wilayah berasal dari akun, bukan manipulasi POST');
checkInput($write[2]['input_source'] === 'scan_qrcode', 'Scan memiliki tipe input scan_qrcode');
checkInput($write[1]['scan_code'] === $valid['scan_value'] && $write[1]['nama_barang'] === 'Barang', 'Data opname berasal dari QR dan Data Master Barang');
foreach ([['scan_value'=>'BR|3'], ['scan_value'=>'MB|99|2027-01-01'], ['scan_value'=>'MB|3|2027-02-01'], ['qty_box'=>'-1'], ['qty_pcs'=>'1.5'], ['qty_box'=>'','qty_pcs'=>'']] as $invalid) {
    $controller->input->values = array_merge($valid, $invalid);
    $before = count($controller->stockopname->writes);
    $controller->ajax_input_save();
    checkInput(!$controller->output->result['status'] && count($controller->stockopname->writes) === $before, 'Input tidak valid ditolak: '.json_encode($invalid));
}
$controller->input->values = array_merge($valid, ['qty_box'=>'0','qty_pcs'=>'0']);
$controller->ajax_input_save();
checkInput($controller->output->result['status'], 'Nol eksplisit diterima sebagai pemeriksaan');
$controller->input->values = $valid;
$controller->stockopname->dimensi = 0;
$controller->ajax_input_save();
checkInput(!$controller->output->result['status'], 'Box tanpa isi yang valid ditolak');
$controller->stockopname->dimensi = 24;
$controller->session->values['tim'] = 0;
$controller->ajax_input_save();
checkInput(!$controller->output->result['status'], 'Akun tanpa tim ditolak');
$controller->session->values['tim'] = 1;
$controller->input->values = ['manual_kode_barang'=>'A','manual_expired_id'=>'2027-01-01','qty_box'=>'1','qty_pcs'=>'2'];
$controller->ajax_manual_save();
$write = end($controller->stockopname->writes);
checkInput($controller->output->result['status'] && $write[0] === 'manual' && $write[1]['expired_date'] === '2027-01-01', 'Input manual memakai expired date Data Master Barang');
$controller->zahir_lot_catalog->rows[0]['nama_barang'] = 'Nama Berbeda';
$controller->input->values = $valid;
$before = count($controller->stockopname->writes);
$controller->ajax_input_save();
checkInput(!$controller->output->result['status'] && count($controller->stockopname->writes) === $before, 'Nama barang yang berubah pada master ditolak saat simpan');
$controller->zahir_lot_catalog->rows[0]['nama_barang'] = 'Barang';
$controller->input->values = ['request_kode_barang'=>'A','request_no_lot'=>'LOT-BARU','request_expired_date'=>'01/02/2027','qty_box'=>'1','qty_pcs'=>'2'];
$controller->ajax_request_save();
$write = end($controller->stockopname->writes);
checkInput($controller->output->result['status'] && $write[0] === 'queue' && $write[2]['no_lot'] === 'LOT-BARU' && $write[3] === 'Request Master Item', 'Request masuk antrean admin, bukan hasil opname');
$guard = new ReflectionMethod(C_Stockopname::class, 'guard');
$guard->setAccessible(true);
$controller->session->values['jobdesk'] = 'SUPERVISOR_OPNAME';
$controller->router->method = 'ajax_supervisor_affirm_request';
$denied = false;
try { $guard->invoke($controller); } catch (RuntimeException $e) { $denied = $e->getCode() === 403; }
checkInput($denied, 'Supervisor tidak dapat memasukkan request ke hitungan');
$controller->router->method = 'ajax_affirm_request_opname_bulk';
$controller->session->values['is_admin_dashboard'] = true;
$guard->invoke($controller);
checkInput(true, 'Admin dapat mengakses aksi pemrosesan request');
