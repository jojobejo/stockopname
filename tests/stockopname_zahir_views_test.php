<?php
if (PHP_SAPI !== 'cli') exit;
define('BASEPATH', __DIR__.'/../system/');
function html_escape($s) { return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8'); }
function site_url($s) { return 'https://test.invalid/'.$s; }
function base_url($s) { return site_url($s); }
class ConfigViewsFixture {
 public $csrf=true;
 public function item($key) { return ['zahir_catalog_mode'=>'imported','zahir_compare_local'=>true,'zahir_import_scope'=>'all','csrf_protection'=>$this->csrf][$key]??null; }
}
class SecurityViewsFixture {
 public function get_csrf_token_name(){return 'csrf_fixture';}
 public function get_csrf_hash(){return 'csrf_hash_fixture';}
}
class SnapshotViewsFixture {
 public $missing=false;
 public function ready(){if($this->missing)throw new RuntimeException('Migration CSV belum dipasang.');}
 public function active(){return ['source_file'=>'<script>test</script>.csv','scope'=>'all','imported_at'=>gmdate('Y-m-d H:i:s'),'actor'=>'fixture','snapshot_id'=>'fixture-id','row_count'=>2];}
}
class LoaderViewsFixture {
 public function config($s){}
 public function model($name,$alias){global $ciViews,$snapshotViews;$ciViews->$alias=$snapshotViews;}
}
// CI_Loader receives an initial property snapshot; models loaded inside views
// are attached to the controller, not retroactively to this rendering context.
class ViewContextFixture {
 public $config,$security,$load;
 public function render($path,$values=[]){extract($values,EXTR_SKIP);ob_start();try{include $path;return ob_get_clean();}catch(Throwable $e){ob_end_clean();throw $e;}}
}
$ciViews=new stdClass;$ciViews->config=new ConfigViewsFixture;$ciViews->security=new SecurityViewsFixture;$ciViews->load=new LoaderViewsFixture;
$snapshotViews=new SnapshotViewsFixture;
function &get_instance(){global $ciViews;return $ciViews;}
function checkView($ok,$message){if(!$ok)throw new RuntimeException($message);echo 'PASS: '.$message.PHP_EOL;}
$view=new ViewContextFixture;$view->config=$ciViews->config;$view->security=$ciViews->security;$view->load=$ciViews->load;
$root=dirname(__DIR__).'/application/views/content/admin/';
set_error_handler(function($severity,$message,$file,$line){throw new ErrorException($message,0,$severity,$file,$line);});
try {
 $status=$view->render($root.'stockopname_zahir_status.php');
 checkView(!isset($view->zahir_snapshot)&&isset($ciViews->zahir_snapshot)&&trim($status)==='','status resolves model on controller without rendering active import banner');
 $snapshotViews->missing=true;$status=$view->render($root.'stockopname_zahir_status.php');
 checkView(strpos($status,'Migration CSV belum dipasang.')!==false&&strpos($status,'Undefined property')===false,'missing migration produces status without PHP property notice');
 $values=['embedded'=>true,'import_completed'=>false,'message'=>'Preview siap','active'=>$snapshotViews->active(),'report'=>['valid_count'=>2,'new'=>[],'updates'=>[],'errors'=>[['row'=>2,'errors'=>['Invalid <img src=x onerror=alert(1)>']]],'conflicts'=>[]],'canCommit'=>true,'action_token'=>'session_fixture','preview_token'=>'preview_fixture'];
 $form=$view->render($root.'stockopname_zahir_import.php',$values);
 checkView(strpos($form,'<html')===false&&strpos($form,'<body')===false,'modal fragment contains no nested HTML document');
 checkView(substr_count($form,'action="https://test.invalid/admin/stockopname/zahir-csv?embedded=1"')===1 && strpos($form,'name="operation" value="import"')!==false,'one modal form posts directly to import endpoint');
 checkView(strpos($form,'value="session_fixture"')!==false&&substr_count($form,'type="hidden" name="csrf_fixture"')===1&&strpos($form,'name="preview_token"')===false,'direct import keeps session/CSRF tokens without preview confirmation');
 checkView(strpos($form,'<img src=x')===false&&strpos($form,'&lt;img')!==false,'row error source data is escaped');
 checkView(strpos($form,'Belum ada data')===false&&strpos($form,'<pre>')===false,'compact form has no misleading empty status or technical dump');
 $values['import_completed']=true;$form=$view->render($root.'stockopname_zahir_import.php',$values);
 checkView(strpos($form,'data-import-completed="1"')!==false,'completed response signals parent catalog refresh');
 $values['embedded']=false;$form=$view->render($root.'stockopname_zahir_import.php',$values);
 checkView(strpos($form,'<!doctype html>')!==false&&strpos($form,'?embedded=1')===false,'standalone import page remains available');
 echo "ALL VIEW REGRESSION TESTS PASSED\n";
} finally {restore_error_handler();}
