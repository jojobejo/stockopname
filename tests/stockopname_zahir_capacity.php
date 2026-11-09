<?php
if(PHP_SAPI!=='cli')exit;
define('BASEPATH',__DIR__);require __DIR__.'/../application/libraries/Zahir_snapshot_validator.php';
$v=new Zahir_snapshot_validator;$meta=['source_instance'=>'zahir-local','scope'=>'all','source_file'=>'capacity.csv'];
if(isset($argv[1]))$bytes=file_get_contents($argv[1]);
else {
 $bytes="kode_barang,nama_barang,expired_date,qty_lot,p,l,t\n";
 for($i=0;$i<10000;$i++)$bytes.='SYN'.$i.',Synthetic '.$i.str_repeat('x',330).",2028-01-01,999999999999999999.999999,ignored,,\n";
}
$start=microtime(true);$r=$v->decode($bytes,'csv',$meta);
echo json_encode(['file_bytes'=>strlen($bytes),'source_count'=>$r['source_count'],'valid_count'=>$r['valid_count'],'error_rows'=>count($r['errors']),'duplicates'=>$r['duplicates'],'seconds'=>microtime(true)-$start,'peak_bytes'=>memory_get_peak_usage(true)],JSON_PRETTY_PRINT),PHP_EOL;
