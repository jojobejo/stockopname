<?php
if(PHP_SAPI!=='cli')exit;
define('BASEPATH',__DIR__);define('ENVIRONMENT','testing');require __DIR__.'/../application/config/database.php';
$c=$db['default'];mysqli_report(MYSQLI_REPORT_ERROR|MYSQLI_REPORT_STRICT);
$d=new mysqli($c['hostname'],$c['username'],$c['password'],$c['database']);
$name='stockopname_snapshot_test_'.bin2hex(random_bytes(4));
if(!preg_match('/^stockopname_snapshot_test_[a-f0-9]{8}$/D',$name))exit(1);
$d->query('CREATE DATABASE `'.$name.'`');$d->select_db($name);
function runSql($d,$sql){foreach(explode(';',$sql) as $s)if(trim($s))$d->query($s);}
try {
 $sql=file_get_contents(__DIR__.'/../database/sql/stockopname_zahir_snapshot.sql');
 $sql=preg_replace("/\r?\n input_format VARCHAR\(20\) NOT NULL DEFAULT 'csv-v1',\r?\n source_file VARCHAR\(255\) DEFAULT NULL,/",'',$sql);
 $sql=str_replace(['exported_at VARCHAR(40) DEFAULT NULL','exported_epoch BIGINT DEFAULT NULL','p INT DEFAULT NULL, l INT DEFAULT NULL, t INT DEFAULT NULL, dimensi INT DEFAULT NULL'],['exported_at VARCHAR(40) NOT NULL','exported_epoch BIGINT NOT NULL','p INT NOT NULL, l INT NOT NULL, t INT NOT NULL, dimensi INT NOT NULL'],$sql);
 runSql($d,$sql);
 $d->query("INSERT INTO stockopname_zahir_batch(snapshot_id,checksum,source_instance,scope,exported_at,exported_epoch,imported_at,actor,row_count,master_changes) VALUES('1930f639-8e84-452b-9dd3-20d67f2bf731',REPEAT('a',64),'legacy','all','2026-11-01T00:00:00Z',1793491200,NOW(),'test',1,'[]')");
 $d->query("INSERT INTO stockopname_zahir_qrlotexp(batch_id,source_row,kode_barang,master_id,nama_sumber,nama_normalized,expired_date,qty_lot,p,l,t,dimensi) VALUES(1,1,'LEGACY',42,'Legacy','Legacy','2028-01-01',2.000001,2,3,4,24)");
 $before=$d->query('SELECT * FROM stockopname_zahir_qrlotexp')->fetch_all(MYSQLI_ASSOC);
 runSql($d,file_get_contents(__DIR__.'/../database/sql/stockopname_zahir_csv_upgrade.sql'));
 if($before!==$d->query('SELECT * FROM stockopname_zahir_qrlotexp')->fetch_all(MYSQLI_ASSOC))throw new RuntimeException('Upgrade changed legacy rows.');
 $row=$d->query('SELECT * FROM stockopname_zahir_batch')->fetch_assoc();
 if($row['input_format']!=='legacy-json' || $row['source_file']!==null || $row['snapshot_id']!=='1930f639-8e84-452b-9dd3-20d67f2bf731')throw new RuntimeException('Upgrade lost legacy batch identity.');
 echo "PASS: upgrade preserves legacy rows, dimensions, batch identity and audit provenance\n";
}finally{$d->select_db($c['database']);$d->query('DROP DATABASE `'.$name.'`');$d->close();}
