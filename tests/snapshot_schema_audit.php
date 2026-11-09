<?php
if (PHP_SAPI !== 'cli') exit;
define('BASEPATH', __DIR__); define('ENVIRONMENT','testing');
require __DIR__.'/../application/config/database.php';
$c=$db['default']; mysqli_report(MYSQLI_REPORT_ERROR|MYSQLI_REPORT_STRICT);
$d=new mysqli($c['hostname'],$c['username'],$c['password'],$c['database']);
foreach(['tb_master_barang_all','stockopname_catalog_qrcode','stockopname_master_item'] as $t) {
 echo $t,PHP_EOL; foreach($d->query('SHOW CREATE TABLE '.$t)->fetch_assoc() as $k=>$v) echo $v,PHP_EOL;
 echo json_encode($d->query('SHOW INDEX FROM '.$t)->fetch_all(MYSQLI_ASSOC)),PHP_EOL;
}
echo 'duplicates: ',json_encode($d->query('SELECT COUNT(*) n FROM (SELECT UPPER(TRIM(kd_barang)) k FROM tb_master_barang_all GROUP BY k HAVING COUNT(*)>1) x')->fetch_assoc()),PHP_EOL;
