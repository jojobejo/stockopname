<?php
if(PHP_SAPI!=='cli')exit;
define('BASEPATH',__DIR__);define('ENVIRONMENT','testing');require __DIR__.'/../application/config/database.php';
$c=$db['default'];mysqli_report(MYSQLI_REPORT_ERROR|MYSQLI_REPORT_STRICT);
if(!in_array($c['hostname'],['localhost','127.0.0.1','::1'],true))throw new RuntimeException('Only local development target permitted.');
$d=new mysqli($c['hostname'],$c['username'],$c['password'],$c['database']);
echo json_encode(['database'=>$c['database'],'target'=>'local-development','size'=>$d->query('SELECT COUNT(*) tables_count,SUM(DATA_LENGTH+INDEX_LENGTH) bytes FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE()')->fetch_assoc()],JSON_PRETTY_PRINT),PHP_EOL;
foreach(['stockopname_zahir_batch','stockopname_zahir_active','stockopname_zahir_qrlotexp','stockopname_zahir_audit','stockopname_catalog_qr_issue'] as $t){$r=$d->query("SHOW TABLES LIKE '".$d->real_escape_string($t)."'");if($r->num_rows){echo $d->query('SHOW CREATE TABLE `'.$t.'`')->fetch_row()[1],PHP_EOL;echo 'rows: ',$d->query('SELECT COUNT(*) FROM `'.$t.'`')->fetch_row()[0],PHP_EOL;}else echo $t,': absent',PHP_EOL;}
