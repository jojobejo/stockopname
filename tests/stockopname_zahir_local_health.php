<?php
if(PHP_SAPI!=='cli')exit;
define('BASEPATH',__DIR__.'/../system/');define('APPPATH',__DIR__.'/../application/');define('ENVIRONMENT','testing');
function log_message($l,$m){}function is_php($v){return version_compare(PHP_VERSION,$v,'>=');}
class CI_Model {}
require APPPATH.'config/database.php';require BASEPATH.'database/DB.php';require APPPATH.'config/zahir_stock_lot.php';
require APPPATH.'models/admin/M_Zahir_snapshot.php';require APPPATH.'libraries/Zahir_lot_catalog.php';
class MigrationHealthModel extends M_Zahir_snapshot {public $db,$config;}
class MigrationHealthConfig {public $values;public function item($k){return $this->values[$k]??null;}}
class MigrationHealthLoader {public $model;public function config($name){}public function model($name,$alias){$ci=&get_instance();$ci->$alias=$this->model;}}
$ciHealth=new stdClass;function &get_instance(){global $ciHealth;return $ciHealth;}
$c=$db['default'];$c['db_debug']=false;
if(!in_array($c['hostname'],['localhost','127.0.0.1','::1'],true))throw new RuntimeException('Local verification only.');
$model=new MigrationHealthModel;$model->db=DB($c,true);$model->config=new MigrationHealthConfig;$model->config->values=$config;
try{
 $model->ready();echo "PASS: actual M_Zahir_snapshot::ready on configured local database\n";
 $ciHealth->config=$model->config;$ciHealth->load=new MigrationHealthLoader;$ciHealth->load->model=$model;
 if($config['zahir_catalog_mode']!=='imported')throw new RuntimeException('Configured source is not imported. No direct network request made.');
 $catalog=new Zahir_lot_catalog;$result=$catalog->rows(true);
 if(empty($result['status'])||($result['source']['mode']??null)!=='imported')throw new RuntimeException('Imported reader failed.');
 echo 'PASS: imported refresh reads local database; active_catalog_rows=',count($result['data']),PHP_EOL;
 $tables=['stockopname_zahir_batch','stockopname_zahir_active','stockopname_zahir_qrlotexp','stockopname_zahir_audit','stockopname_catalog_qr_issue'];
 foreach($tables as $t){$ddl=$model->db->query('SHOW CREATE TABLE `'.$t.'`')->row_array();if(strpos(implode(' ',$ddl),'ENGINE=InnoDB')===false)throw new RuntimeException('Invalid engine.');echo $t,': ',$model->db->count_all($t),' rows',PHP_EOL;}
 echo "PASS: CSV migration installed, empty state ready for direct CSV upload\n";
}finally{$model->db->close();}
