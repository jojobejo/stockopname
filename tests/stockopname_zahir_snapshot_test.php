<?php
if(PHP_SAPI!=='cli')exit;
define('BASEPATH',__DIR__.'/../system/');define('APPPATH',__DIR__.'/../application/');
define('ENVIRONMENT',in_array('--production',$argv,true)?'production':'testing');
$assetRoot=sys_get_temp_dir().'/so_csv_qr_test_'.bin2hex(random_bytes(6)).'/';
define('FCPATH',$assetRoot);
function base_url($p=""){return "https://test.invalid/".$p;} function log_message($l,$m){} function is_php($v){return version_compare(PHP_VERSION,$v,'>=');}
class CI_Model {} class CI_Controller {}
require APPPATH.'config/database.php';require BASEPATH.'database/DB.php';
require APPPATH.'libraries/Zahir_snapshot_validator.php';require APPPATH.'libraries/Zahir_lot_catalog.php';require APPPATH.'libraries/Karisma_code_generator.php';
require APPPATH.'models/admin/M_Zahir_snapshot.php';require APPPATH.'models/admin/M_Catalog_qr_issue.php';require APPPATH.'models/admin/M_Stockopname.php';require APPPATH.'controllers/admin/C_Stockopname.php';
class SnapshotTest extends M_Zahir_snapshot {public $db,$config,$load,$zahir_lot_catalog;}
class IssueTest extends M_Catalog_qr_issue {public $db;}
class ScanTest extends C_Stockopname {public $load,$stockopname,$zahir_lot_catalog,$config,$input,$session,$output,$catalog_qr_issue,$karisma_code_generator;}
class BatchInputFixture {public $fields=[];function method($upper=false){return $upper?'POST':'post';}function post($key,$clean=false){return $this->fields[$key]??null;}}
class BatchSessionFixture {function userdata($key){return $key==='master_action_token'?'batch-token':($key==='username'?'batch-admin':null);}}
class BatchOutputFixture {public $body;function set_content_type($type){return $this;}function set_output($text){$this->body=$text;return $this;}}
class BatchCatalogFixture {public $data;function rows($refresh=false){return ['status'=>true,'stale'=>false,'data'=>$this->data,'source'=>['mode'=>'imported','snapshot_id'=>'batch-test']];}}
class CatalogModelTest extends M_Stockopname {public $db;}
class TestConfig {public $values;function __construct(){$this->values=['zahir_catalog_mode'=>'imported','zahir_import_source_instance'=>'zahir-local','zahir_import_scope'=>'all','zahir_compare_local'=>ENVIRONMENT!=='production'];}function item($k){return $this->values[$k]??null;}}
class TestLoader {function config($s){} function library($s){} function model($s,$alias){}}
$instance=new stdClass;function &get_instance(){global $instance;return $instance;}
function check($ok,$message){if(!$ok)throw new RuntimeException($message);echo 'PASS: '.$message.PHP_EOL;}
function rejects($fn,$message){try{$fn();}catch(Throwable $e){echo 'PASS: '.$message.PHP_EOL;return;}throw new RuntimeException('Expected rejection: '.$message);}
$c=$db['default'];$c['db_debug']=false;$source=DB($c,true);
$testName='stockopname_snapshot_test_'.bin2hex(random_bytes(4));
if(!preg_match('/^stockopname_snapshot_test_[a-f0-9]{8}$/D',$testName))exit(1);
if(!$source->query('CREATE DATABASE `'.$testName.'` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci'))throw new RuntimeException('Cannot create isolated test DB.');
try {
 $c['database']=$testName;$test=DB($c,true);
 foreach(['stockopname_opname','stockopname_pending','tb_master_barang_all','stockopname_catalog_qrcode','stockopname_master_item'] as $t)check($test->query('CREATE TABLE `'.$t.'` LIKE `'.$source->database.'`.`'.$t.'`')!==false,'clone test structure '.$t);
 $businessBefore=[];
 foreach(['stockopname_opname','stockopname_pending'] as $table) {
  check($test->query('INSERT INTO `'.$table.'` SELECT * FROM `'.$source->database.'`.`'.$table.'` LIMIT 1')!==false,'seed business fixture');
  $businessBefore[$table]=$test->get($table)->result_array();
 }
 foreach(['stockopname_zahir_snapshot.sql','stockopname_catalog_qr_issue.sql'] as $file)foreach(explode(';',file_get_contents(__DIR__.'/../database/sql/'.$file)) as $sql)if(trim($sql))check($test->query($sql)!==false,'isolated migration');
 $m=new SnapshotTest;$m->db=$test;$m->config=new TestConfig;$m->load=new TestLoader;
 $instance->config=$m->config;$instance->load=$m->load;$instance->zahir_snapshot=$m;
 $catalog=new Zahir_lot_catalog;$m->zahir_lot_catalog=$catalog;$validator=new Zahir_snapshot_validator;
 $previousMode=getenv('ZAHIR_CATALOG_MODE');putenv('ZAHIR_CATALOG_MODE=direct');$config=[];require APPPATH.'config/zahir_stock_lot.php';
 check($config['zahir_catalog_mode']===(ENVIRONMENT==='production'?'imported':'direct'),'production cannot override source to direct; local direct remains available');
 putenv($previousMode===false?'ZAHIR_CATALOG_MODE':'ZAHIR_CATALOG_MODE='.$previousMode);
 $meta=['source_instance'=>'zahir-local','scope'=>'all','source_file'=>'synthetic.csv'];
 $csv="kode_barang,nama_barang,expired_date,qty_lot,p,l,t\nA1,Test * One,2027-01-01,3.000001,,,\nQ1,Test One,2027-01-01,2,nonsense,-5,1.8\n";
 $v=$validator->decode($csv,'csv',$meta);$checksum=hash('sha256',$csv);
 check(!$v['errors'] && !isset($v['rows'][0]['p']) && $v['document']['exported_at']===null,'CSV no metadata; p/l/t invalid or missing ignored');
 $negative=$validator->decode("kode_barang,nama_barang,expired_date,qty_lot\nN1,Negative,2028-01-01,-1.000001\nZ1,Zero,2028-01-01,0\n",'csv',$meta);
 check(!$negative['errors']&&count($catalog->groupRows($negative['rows']))===0,'zero/negative retained as valid source but inactive catalog');
 rejects(function()use($validator,$meta){$validator->decode('{}','json',$meta);},'JSON upload rejected');
 rejects(function()use($validator,$meta){$validator->decode('kode_barang,nama_barang,expired_date,qty_lot','csv',$meta);},'empty CSV rejected');
 $bad=$v['document'];$bad['rows'][1]['expired_date']='';check(count($validator->validate($bad)['errors'])===1,'empty expiry holds import');
 $bad=$v['document'];$bad['rows'][1]['qty_lot']='1.1234567';check(count($validator->validate($bad)['errors'])===1,'excess decimal precision rejected');
 $bad=$v['document'];$bad['rows'][1]=$bad['rows'][0];check($validator->validate($bad)['duplicates']===1,'ambiguous duplicate rejected');
 $p=$m->preview($v,$checksum);check(count($p['new'])===2&&$test->count_all('tb_master_barang_all')===0,'preview no operational write');
 $m->commitSnapshot($v,$checksum,$p['fingerprint'],'test-admin');
 check($test->count_all('stockopname_zahir_qrlotexp')===2,'source codes retained in new reference table');
 $ids=$test->select('id,kd_barang,kode_barang_system')->order_by('id')->get('tb_master_barang_all')->result_array();
 check($test->where('id',$ids[0]['id'])->get('tb_master_barang_all')->row_array()['dimensi']==0,'new master dimensions use legacy zero default');
 $test->where('id',$ids[0]['id'])->update('tb_master_barang_all',['p'=>2,'l'=>3,'t'=>4,'dimensi'=>24]);
 $test->where('id',$ids[1]['id'])->update('tb_master_barang_all',['p'=>5,'l'=>6,'t'=>7,'dimensi'=>210]);
 $rv=$validator->decode($csv,'csv',$meta);$rp=$m->preview($rv,$checksum);check($rp['idempotent'],'identical CSV recognized despite new internal UUID');$m->commitSnapshot($rv,$checksum,$rp['fingerprint'],'test');
 check($test->count_all('stockopname_zahir_batch')===1&&$test->count_all('tb_master_barang_all')===2,'repeat no duplicate batch/master');
 check(count($rp['local_comparison'])===(ENVIRONMENT==='production'?0:2),'local comparison vs production CSV authority');
 $rows=$catalog->rows(true);check($rows['status']&&count($rows['data'])===1&&$rows['data'][0]['kode_barang']==='Q1'&&$rows['data'][0]['dimensi']==210&&$rows['source']['exported_at']===null,'offline imported grouping Q priority uses local dimensions');
 $scan=(new ReflectionClass('ScanTest'))->newInstanceWithoutConstructor();$scan->load=$m->load;$scan->config=$m->config;$scan->zahir_lot_catalog=$catalog;$scan->stockopname=new CatalogModelTest;$scan->stockopname->db=$test;
 $method=new ReflectionMethod('C_Stockopname','scan_catalog_qrcode');$method->setAccessible(true);
 check($method->invoke($scan,'MB|'.$ids[1]['id'].'|2027-01-01')['status'],'current MB QR scan valid');
 $test->where('id',$ids[1]['id'])->update('tb_master_barang_all',['nama_barang'=>'Local Different']);
 check($method->invoke($scan,'MB|'.$ids[1]['id'].'|2027-01-01')['status']===(ENVIRONMENT==='production'),'local name mismatch blocks local; production trusts CSV');
 $test->where('id',$ids[1]['id'])->update('tb_master_barang_all',['nama_barang'=>'Test One']);
 $issuer=new IssueTest;$issuer->db=$test;$generator=new Karisma_code_generator;
 $validate=function()use($method,$scan,$ids){$r=$method->invoke($scan,'MB|'.$ids[1]['id'].'|2027-01-01');return $r['status']?['status'=>true,'barang'=>$r['item']]:$r;};
 $generate=function($value,$path)use($generator){return $generator->qrcode($value,$path);};
 $qr=$issuer->issue($ids[1]['id'],'2027-01-01','admin',false,'','',$validate,$generate);
 check($qr['status']&&is_file(FCPATH.$qr['path']),'first QR issue creates local PNG');
 $held=FCPATH.$qr['path'].'.held';rename(FCPATH.$qr['path'],$held);
 try {
  $missing=$issuer->issue($ids[1]['id'],'2027-01-01','admin',false,'','',$validate,$generate);
  check(!$missing['status']&&$missing['skipped'],'missing asset does not bypass duplicate generate protection');
 } finally {rename($held,FCPATH.$qr['path']);}
 $again=$issuer->issue($ids[1]['id'],'2027-01-01','admin',false,'','',$validate,$generate);check(!$again['status']&&$again['skipped'],'normal repeat generate rejected');
 rejects(function()use($issuer,$ids,$qr,$validate,$generate){$issuer->issue($ids[1]['id'],'2027-01-01','admin',true,'bad',$qr['path'],$validate,$generate);},'resign requires reason');
 $reissued=$issuer->issue($ids[1]['id'],'2027-01-01','admin',true,'Label rusak perlu dicetak ulang',$qr['path'],$validate,$generate);
 check($reissued['status']&&$reissued['path']!==$qr['path']&&is_file(FCPATH.$qr['path']),'resign creates new asset and preserves original');
 check($test->count_all('stockopname_catalog_qr_issue')===2&&$test->count_all('stockopname_catalog_qrcode')===1,'issuance/resign audit with one active QR row');
 check($scan->stockopname->catalog_qrcode_asset_path($ids[1]['id'],'2027-01-01')===$reissued['path'],'consumer asset lookup follows resigned QR');
 $printRow=['kode_barang'=>'Q1','nama_barang'=>'Test One','expired_date'=>'2027-01-01'];
 $assetMethod=new ReflectionMethod('C_Stockopname','catalog_qrcode_for_master_row');$assetMethod->setAccessible(true);
 check($assetMethod->invoke($scan,$printRow)['asset']['path']===$reissued['path'],'preview/print uses resigned active asset');
 $test->insert('stockopname_master_item',['kode_barang'=>'Q1','nama_barang'=>'Test One','expired_date'=>'2027-01-01','no_lot'=>'-','qty'=>1]);$manualFixtureId=$test->insert_id();
 $byCode=['Q1'=>$catalog->rows()['data']];
 $list=$scan->stockopname->get_master_barang_datatable(['draw'=>1,'start'=>0,'length'=>10], 'positive',$byCode);
 check($list['data'][0]['qrcode_ready']===1&&$list['data'][0]['sync_status']==='generated','Master Opname list follows resigned asset');
 check($scan->stockopname->qrcode_summary('positive',$byCode)['done']===1&&$scan->stockopname->qrcode_summary('positive',[])['done']===0,'summary counts only active reference QR');
 check($scan->stockopname->get_master_barang_print_assets()[0]['qrcode']===$reissued['path'],'print asset list follows resigned asset');
 $test->where('id',$manualFixtureId)->delete('stockopname_master_item');
 rejects(function()use($issuer,$ids,$qr,$validate,$generate){$issuer->issue($ids[1]['id'],'2027-01-01','admin',true,'Stale double click',$qr['path'],$validate,$generate);},'stale concurrent resign rejected');
 $current=$test->get('stockopname_catalog_qrcode')->row_array();
 $test->query("CREATE TRIGGER synthetic_qr_fail BEFORE INSERT ON stockopname_catalog_qr_issue FOR EACH ROW SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='synthetic failure'");
 rejects(function()use($issuer,$ids,$current,$validate,$generate){$issuer->issue($ids[1]['id'],'2027-01-01','admin',true,'Synthetic DB failure',$current['qrcode_file'],$validate,$generate);},'resign DB failure rolls back publication');
 $test->query('DROP TRIGGER synthetic_qr_fail');check($test->get('stockopname_catalog_qrcode')->row_array()===$current,'failed resign preserves active QR');
 $other=DB($c,true);$other->query("SELECT GET_LOCK('stockopname_master_barang_kiubr',0)");rejects(function()use($issuer,$ids,$current,$validate,$generate){$issuer->issue($ids[1]['id'],'2027-01-01','admin',true,'Concurrent lock test',$current['qrcode_file'],$validate,$generate);},'concurrent QR/import uses same KIUBR lock');$other->query("SELECT RELEASE_LOCK('stockopname_master_barang_kiubr')");$other->close();
 $neverGenerate=function(){throw new RuntimeException('Healthy/unissued rows must not reach generator.');};
 $auditBefore=$test->count_all('stockopname_catalog_qr_issue');
 $healthy=$issuer->issue($ids[1]['id'],'2027-01-01','admin',true,'Reassign massal: file QR hilang',$current['qrcode_file'],$validate,$neverGenerate,true);
 $unissued=$issuer->issue($ids[0]['id'],'2027-01-01','admin',true,'Reassign massal: file QR hilang','',$validate,$neverGenerate,true);
 check(!empty($healthy['skipped'])&&!empty($unissued['skipped'])&&$test->count_all('stockopname_catalog_qr_issue')===$auditBefore,'missing-only skips available files and unissued QR without audit/mutation');
 $scan->input=new BatchInputFixture;$scan->session=new BatchSessionFixture;$scan->output=new BatchOutputFixture;$scan->catalog_qr_issue=$issuer;$scan->karisma_code_generator=$generator;
 $batchCatalog=new BatchCatalogFixture;$batchCatalog->data=[];
 for($i=1;$i<=12;$i++)$batchCatalog->data[]=['master_id'=>$ids[1]['id'],'kode_barang'=>'Q1','nama_barang'=>'Test One','expired_date'=>'2027-01-'.str_pad($i,2,'0',STR_PAD_LEFT),'qty_lot'=>'1','p'=>5,'l'=>6,'t'=>7,'dimensi'=>210];
 $scan->zahir_lot_catalog=$batchCatalog;
 $held=FCPATH.$current['qrcode_file'].'.held';rename(FCPATH.$current['qrcode_file'],$held);
 try {
  $scan->input->fields=['action_token'=>'batch-token','offset'=>0];$scan->ajax_reassign_missing_catalog_qrcode();$first=json_decode($scan->output->body,true);
  check($first['status']&&$first['data']['next_offset']===10&&$first['data']['total']===12&&$first['data']['counts']===['success'=>1,'skipped'=>9,'failed'=>0],'batch repairs only missing recorded QR, scans stable first ten rows');
  $scan->input->fields=['action_token'=>'batch-token','offset'=>10,'catalog_fingerprint'=>$first['data']['catalog_fingerprint']];$scan->ajax_reassign_missing_catalog_qrcode();$last=json_decode($scan->output->body,true);
  check($last['status']&&$last['data']['next_offset']===12&&$last['data']['counts']['skipped']===2,'next page is not shifted after a repair');
  $scan->input->fields=['action_token'=>'batch-token','offset'=>0];$scan->ajax_reassign_missing_catalog_qrcode();$repeat=json_decode($scan->output->body,true);
  check($repeat['data']['counts']['success']===0&&$test->count_all('stockopname_catalog_qr_issue')===$auditBefore+1,'batch rerun does not reissue files that now exist');
  $batchCatalog->data[0]['nama_barang']='Changed during batch';$scan->input->fields=['action_token'=>'batch-token','offset'=>10,'catalog_fingerprint'=>$first['data']['catalog_fingerprint']];$scan->ajax_reassign_missing_catalog_qrcode();
  check(!json_decode($scan->output->body,true)['status'],'catalog changed between batch requests is rejected');
  $scan->input->fields=['action_token'=>'invalid','offset'=>0];$scan->ajax_reassign_missing_catalog_qrcode();check(!json_decode($scan->output->body,true)['status'],'batch mutation requires valid session token');
 } finally {rename($held,FCPATH.$current['qrcode_file']);$scan->zahir_lot_catalog=$catalog;}
 $repaired=$test->get('stockopname_catalog_qrcode')->row_array();
 check($repaired['qrcode_file']!==$current['qrcode_file']&&$repaired['qrcode_value']===$current['qrcode_value']&&is_file(FCPATH.$repaired['qrcode_file']),'batch retains QR payload and replaces missing asset pointer');
 $event=$test->order_by('id','DESC')->get('stockopname_catalog_qr_issue')->row_array();check($event['actor']==='batch-admin'&&$event['operation']==='resign'&&$event['reason']==='Reassign massal: file QR hilang','batch repair records actor, reason and existing resign operation');
 $current=$repaired;
 $lateDuplicate=$test->where('id',$ids[1]['id'])->get('tb_master_barang_all')->row_array();unset($lateDuplicate['id']);$lateDuplicate['kode_barang_system']='LATE-DUP-QR';$test->insert('tb_master_barang_all',$lateDuplicate);$lateId=$test->insert_id();
 check(!$method->invoke($scan,'MB|'.$lateId.'|2027-01-01')['status'],'imported QR is bound to recorded local master ID');
 $ambiguous=$scan->stockopname->catalog_qrcode_by_codes(['Q1']);check(array_key_exists('Q1',$ambiguous)&&$ambiguous['Q1']===null,'catalog does not choose smallest duplicate master ID');
 $test->where('id',$lateId)->delete('tb_master_barang_all');
 $nextCsv="kode_barang,nama_barang,expired_date,qty_lot\nA1,Changed,2028-01-01,5\nQ1,Test One,2027-01-01,0\n";
 $nv=$validator->decode($nextCsv,'csv',$meta);$nc=hash('sha256',$nextCsv);$np=$m->preview($nv,$nc);check(count($np['missing'])===1&&count($np['updates'])===1,'preview missing grain and name changes');
 $bad=$nv;$bad['errors'][]=['row'=>1,'errors'=>['bad']];rejects(function()use($m,$bad,$nc,$np){$m->commitSnapshot($bad,$nc,$np['fingerprint'],'test');},'one row error aborts all master changes');
 $test->where('id',$ids[0]['id'])->update('tb_master_barang_all',['p'=>9]);rejects(function()use($m,$nv,$nc,$np){$m->commitSnapshot($nv,$nc,$np['fingerprint'],'test');},'master edited after preview holds commit');$test->where('id',$ids[0]['id'])->update('tb_master_barang_all',['p'=>2]);
 $duplicate=$test->where('id',$ids[0]['id'])->get('tb_master_barang_all')->row_array();unset($duplicate['id']);$duplicate['kode_barang_system']='DUP-TEST';$test->insert('tb_master_barang_all',$duplicate);$dupId=$test->insert_id();check(count($m->preview($nv,$nc)['conflicts'])===1,'duplicate local identity holds import');$test->where('id',$dupId)->delete('tb_master_barang_all');
 $np=$m->preview($nv,$nc);$test->query("CREATE TRIGGER synthetic_import_fail BEFORE INSERT ON stockopname_zahir_qrlotexp FOR EACH ROW SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='synthetic failure'");
 rejects(function()use($m,$nv,$nc,$np){$m->commitSnapshot($nv,$nc,$np['fingerprint'],'test');},'reference staging DB failure rolls back activation and master');$test->query('DROP TRIGGER synthetic_import_fail');
 check($m->active()['checksum']===$checksum,'previous CSV remains active after failures');
 $np=$m->preview($nv,$nc);$m->commitSnapshot($nv,$nc,$np['fingerprint'],'test');
 $r=$catalog->rows(true)['data'];check(count($r)===1&&$r[0]['kode_barang']==='A1'&&$r[0]['expired_date']==='2028-01-01','zero qty, changed representative and expired active correctly');
 check(!$method->invoke($scan,'MB|'.$ids[1]['id'].'|2027-01-01')['status'],'old representative QR retained but unavailable');
 $held=FCPATH.$current['qrcode_file'].'.held';rename(FCPATH.$current['qrcode_file'],$held);
 try {
  $auditBefore=$test->count_all('stockopname_catalog_qr_issue');$scan->input->fields=['action_token'=>'batch-token','offset'=>0];$scan->ajax_reassign_missing_catalog_qrcode();$inactive=json_decode($scan->output->body,true);
  check($inactive['status']&&$inactive['data']['counts']['success']===0&&$test->count_all('stockopname_catalog_qr_issue')===$auditBefore,'missing files of inactive historical QR are not reassigned');
 } finally {rename($held,FCPATH.$current['qrcode_file']);}
 check($assetMethod->invoke($scan,$printRow)['value']==='','inactive QR excluded from preview/print');
 check($test->get('stockopname_catalog_qrcode')->row_array()===$current,'CSV activation preserves QR row');
 check($ids===$test->select('id,kd_barang,kode_barang_system')->where_in('id',array_column($ids,'id'))->order_by('id')->get('tb_master_barang_all')->result_array(),'existing IDs/system codes unchanged');
 $a=$test->where('id',$ids[0]['id'])->get('tb_master_barang_all')->row_array();check($a['p']==2&&$a['l']==3&&$a['t']==4&&$a['dimensi']==24,'existing dimensions preserved');
 rejects(function()use($m,$validator,$csv,$meta,$checksum){$m->preview($validator->decode($csv,'csv',$meta),$checksum);},'historical CSV replay requires separate recovery');
 $m->restorePrevious($nv['document']['snapshot_id'],'test');check($m->active()['checksum']===$checksum&&$test->where('id',$ids[0]['id'])->get('tb_master_barang_all')->row_array()['nama_barang']==='Test One','rollback restores prior CSV/name and retains dimensions');
 $csvIndex=array_search('--csv',$argv,true);
 if($csvIndex!==false) {
  $bytes=file_get_contents($argv[$csvIndex+1]);$av=$validator->decode($bytes,'csv',$meta);$ac=hash('sha256',$bytes);$ap=$m->preview($av,$ac);$m->commitSnapshot($av,$ac,$ap['fingerprint'],'attached-csv-test');
  check((int)$m->active()['row_count']===$av['source_count']&&!$av['errors'],'attached CSV imports unchanged without source metadata');
 }
 if(in_array('--capacity',$argv,true)) {
  $bulk="kode_barang,nama_barang,expired_date,qty_lot,p,l,t\n";
  for($i=0;$i<10000;$i++)$bulk.='SYN'.$i.',Synthetic '.$i.str_repeat('x',330).",2028-01-01,1.000001,ignored,,\n";
  $start=microtime(true);$bv=$validator->decode($bulk,'csv',$meta);$bc=hash('sha256',$bulk);$bp=$m->preview($bv,$bc);$previewSeconds=microtime(true)-$start;$start=microtime(true);$m->commitSnapshot($bv,$bc,$bp['fingerprint'],'capacity');
  echo 'CAPACITY '.json_encode(['rows'=>10000,'file_bytes'=>strlen($bulk),'preview_seconds'=>$previewSeconds,'commit_seconds'=>microtime(true)-$start,'peak_bytes'=>memory_get_peak_usage(true)]).PHP_EOL;
 }
 check($test->count_all('stockopname_master_item')===0,'no automatic Master Opname');
 foreach($businessBefore as $table=>$before)check($test->get($table)->result_array()===$before,'business data unchanged '.$table);
 echo 'ALL CSV/QR TESTS PASSED ('.ENVIRONMENT.')'.PHP_EOL;
} finally {
 if(isset($test))$test->close();$source->query('DROP DATABASE `'.$testName.'`');$source->close();
 // Only remove artifacts inside the random test root created by this script.
 foreach(glob($assetRoot.'assets/qrcode/stockopname/*.png')?:[] as $file)if(strpos(str_replace('\\','/',$file),str_replace('\\','/',$assetRoot))===0)unlink($file);
 foreach(['assets/qrcode/stockopname','assets/qrcode','assets',''] as $dir)if(is_dir($assetRoot.$dir))rmdir($assetRoot.$dir);
}
