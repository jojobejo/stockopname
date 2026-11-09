<?php
/** Local development only. Full backup + restore verification before additive DDL. */
if(PHP_SAPI!=='cli')exit;
date_default_timezone_set('Asia/Bangkok');
define('BASEPATH',__DIR__);define('ENVIRONMENT','testing');require __DIR__.'/../../application/config/database.php';
$c=$db['default'];
if(!in_array($c['hostname'],['localhost','127.0.0.1','::1'],true))throw new RuntimeException('Refusing non-local target.');
if(($argv[1]??'')!=='--apply')throw new RuntimeException('Use --apply for the authorized local migration.');
mysqli_report(MYSQLI_REPORT_ERROR|MYSQLI_REPORT_STRICT);
$d=new mysqli($c['hostname'],$c['username'],$c['password'],$c['database']);$d->set_charset('utf8mb4');
$new=['stockopname_zahir_batch','stockopname_zahir_active','stockopname_zahir_qrlotexp','stockopname_zahir_audit','stockopname_catalog_qr_issue'];
$tables=array_column($d->query('SHOW TABLES')->fetch_all(MYSQLI_NUM),0);
$existing=array_intersect($new,$tables);
if($existing)throw new RuntimeException('Snapshot tables already exist. Audit schema and use the documented upgrade path; this runner only installs a fresh schema.');
$lock=$d->query("SELECT GET_LOCK('stockopname_master_barang_kiubr',0)")->fetch_row()[0];
if((int)$lock!==1)throw new RuntimeException('Import/master/QR operation running; try again later.');
$folder='C:/xampp/backups/stockopname/'.date('Ymd_His').'_'.bin2hex(random_bytes(3));
$private=null;$restoreDb=null;$created=[];
function summaryTables($d,$tables){
 $out=[];
 foreach($tables as $t){$q='`'.str_replace('`','``',$t).'`';$ddl=$d->query('SHOW CREATE TABLE '.$q)->fetch_row()[1];$checksum=$d->query('CHECKSUM TABLE '.$q.' EXTENDED')->fetch_row()[1];if($checksum===null)throw new RuntimeException('Cannot verify table checksum.');$out[$t]=['rows'=>(int)$d->query('SELECT COUNT(*) FROM '.$q)->fetch_row()[0],'checksum'=>$checksum,'schema_sha256'=>hash('sha256',$ddl)];}
 return $out;
}
function runMysqlTool($args,$folder,$tag,$input=null){
 $descriptors=[0=>$input?['file',$input,'r']:['pipe','r'],1=>['file',$folder.'/'.$tag.'.stdout','w'],2=>['file',$folder.'/'.$tag.'.stderr','w']];
 $process=proc_open($args,$descriptors,$pipes,null,null,['bypass_shell'=>true]);
 if(!is_resource($process))throw new RuntimeException('Cannot start database tool.');
 if(!$input)fclose($pipes[0]);
 if(proc_close($process)!==0)throw new RuntimeException('Database tool failed; inspect private '.$tag.' log.');
}
try {
 if(!mkdir($folder,0700,true))throw new RuntimeException('Cannot create private backup folder.');
 $before=summaryTables($d,$tables);
 file_put_contents($folder.'/before.json',json_encode(['database'=>$c['database'],'tables'=>$before],JSON_PRETTY_PRINT));
 $private=tempnam(sys_get_temp_dir(),'so_mysql_');chmod($private,0600);
 $quote=function($s){return '"'.str_replace(["\\","\"","\n","\r"],["\\\\","\\\"","\\n","\\r"],$s).'"';};
 file_put_contents($private,"[client]\nhost=".$quote($c['hostname'])."\nuser=".$quote($c['username'])."\npassword=".$quote($c['password'])."\n",LOCK_EX);
 $backup=$folder.'/before.sql';
 runMysqlTool(['C:/xampp/mysql/bin/mysqldump.exe','--defaults-extra-file='.$private,'--single-transaction','--routines','--triggers','--events','--hex-blob','--result-file='.$backup,$c['database']],$folder,'backup');
 if(!is_file($backup)||filesize($backup)===0)throw new RuntimeException('Backup empty.');
 $restoreDb='stockopname_migration_verify_'.bin2hex(random_bytes(6));
 if(!preg_match('/^stockopname_migration_verify_[a-f0-9]{12}$/D',$restoreDb))throw new RuntimeException('Invalid restore target.');
 $d->query('CREATE DATABASE `'.$restoreDb.'` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
 runMysqlTool(['C:/xampp/mysql/bin/mysql.exe','--defaults-extra-file='.$private,'--database='.$restoreDb,'--binary-mode=1'],$folder,'restore',$backup);
 $d->select_db($restoreDb);$restored=summaryTables($d,$tables);
 if($restored!==$before)throw new RuntimeException('Backup restore comparison failed. Migration not started.');
 $d->select_db($c['database']);$d->query('DROP DATABASE `'.$restoreDb.'`');$restoreDb=null;
 // Refuse if operational data changed during backup/verification.
 if(summaryTables($d,$tables)!==$before)throw new RuntimeException('Operational data changed. Back up again before migration.');
 foreach(['stockopname_zahir_snapshot.sql','stockopname_catalog_qr_issue.sql'] as $file){
  $sql=file_get_contents(__DIR__.'/../sql/'.$file);
  foreach(explode(';',$sql) as $statement)if(trim($statement)){
   $d->query($statement);
   if(preg_match('/CREATE TABLE\s+(\w+)/i',$statement,$match))$created[]=$match[1];
  }
 }
 $after=summaryTables($d,$tables);
 if($after!==$before)throw new RuntimeException('Operational table comparison differs; investigate before using import.');
 foreach($new as $table){
  $engine=$d->query("SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='".$table."'")->fetch_row()[0];
  if($engine!=='InnoDB'||(int)$d->query('SELECT COUNT(*) FROM `'.$table.'`')->fetch_row()[0]!==0)throw new RuntimeException('New table verification failed.');
 }
 $result=['database'=>$c['database'],'backup'=>$backup,'backup_bytes'=>filesize($backup),'backup_sha256'=>hash_file('sha256',$backup),'restore_verified'=>true,'original_tables_verified'=>count($tables),'operational_data_unchanged'=>true,'created_tables'=>$created,'imported_business_rows'=>0];
 file_put_contents($folder.'/migration-result.json',json_encode($result,JSON_PRETTY_PRINT));
 file_put_contents($folder.'/after.json',json_encode($after,JSON_PRETTY_PRINT));
 echo json_encode($result,JSON_PRETTY_PRINT),PHP_EOL;
} catch(Throwable $e){
 if(is_dir($folder))file_put_contents($folder.'/migration-error.json',json_encode(['error'=>$e->getMessage(),'created_tables'=>$created],JSON_PRETTY_PRINT));
 throw $e;
} finally {
 if($restoreDb){$d->select_db($c['database']);$d->query('DROP DATABASE `'.$restoreDb.'`');}
 if($private&&is_file($private))unlink($private);
 $d->query("SELECT RELEASE_LOCK('stockopname_master_barang_kiubr')");$d->close();
}
