<?php
defined('BASEPATH') or exit('No direct script access allowed');
$zahirStatusCi =& get_instance();
$zahirStatusCi->load->config('zahir_stock_lot');
if ($zahirStatusCi->config->item('zahir_catalog_mode') === 'imported') {
    $status='Impor CSV Zahir: belum ada data. Buka Impor Data Zahir CSV.';
    try {
        $zahirStatusCi->load->model('admin/M_Zahir_snapshot','zahir_snapshot');
        $zahirStatusCi->zahir_snapshot->ready(); $b=$zahirStatusCi->zahir_snapshot->active();
        if($b) $status=null;
    } catch(Throwable $e) { $status='Impor CSV Zahir: '.$e->getMessage(); }
    if($status!==null) echo '<div class="alert alert-info zahir-source-status" role="status">'.html_escape($status).'</div>';
}
?>
