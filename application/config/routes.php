<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

// Sistem Routes
$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Portal Dashboard
$route['portal'] = 'Portal/index';
$route['auth'] = 'Auth/index';

//Auth Login
$route['process']                                   = 'Auth/process';
$route['logout']                                    = 'Auth/logout';
$route['dashboard']                                 = 'Dashboard/index';
$route['admin/stockopname']                        = 'admin/C_Stockopname/index';
$route['admin/stockopname/monitoring']             = 'admin/C_Stockopname/monitoring';
$route['admin/stockopname/monitoring/activity-log'] = 'admin/C_Stockopname/monitoring_activity_log';
$route['admin/stockopname/monitoring/pending-opname'] = 'admin/C_Stockopname/monitoring_pending_opname';
$route['admin/stockopname/monitoring/request-opname'] = 'admin/C_Stockopname/monitoring_request_opname';
$route['admin/stockopname/ajax-affirm-request-opname-bulk'] = 'admin/C_Stockopname/ajax_affirm_request_opname_bulk';
$route['admin/stockopname/monitoring/manual-opname'] = 'admin/C_Stockopname/monitoring_manual_opname';
$route['admin/stockopname/ajax-affirm-manual-opname-bulk'] = 'admin/C_Stockopname/ajax_affirm_manual_opname_bulk';
$route['admin/stockopname/monitoring/summary']     = 'admin/C_Stockopname/monitoring_summary';
$route['admin/stockopname/monitoring/activity']    = 'admin/C_Stockopname/monitoring_activity';
$route['admin/stockopname/monitoring/compare-all'] = 'admin/C_Stockopname/monitoring_compare_all';
$route['admin/stockopname/monitoring/compare-lot'] = 'admin/C_Stockopname/monitoring_compare_lot';
$route['admin/stockopname/pending-mode']           = 'admin/C_Stockopname/ajax_set_pending_calculation_mode';
$route['admin/stockopname/monitoring/export-excel/(:any)'] = 'admin/C_Stockopname/monitoring_export_excel/$1';
$route['admin/stockopname/monitoring/export-excel'] = 'admin/C_Stockopname/monitoring_export_excel';
$route['admin/stockopname/detail_input_opname/update'] = 'admin/C_Stockopname/ajax_update_input_opname';
$route['admin/stockopname/detail_input_opname/delete'] = 'admin/C_Stockopname/ajax_delete_input_opname';
$route['admin/stockopname/detail_input_opname/repost'] = 'admin/C_Stockopname/ajax_repost_input_opname';
$route['admin/stockopname/detail_input_opname/add_request'] = 'admin/C_Stockopname/ajax_add_request_item';
$route['admin/stockopname/detail_input_opname/delete_request'] = 'admin/C_Stockopname/ajax_delete_request_item';
$route['admin/stockopname/detail_input_opname/add_input'] = 'admin/C_Stockopname/ajax_add_input_opname_detail';
$route['admin/stockopname/detail_input_opname/delete_master_item'] = 'admin/C_Stockopname/ajax_delete_master_item_detail';
$route['admin/stockopname/detail_input_opname/update_dimensi'] = 'admin/C_Stockopname/ajax_update_detail_dimensi';
$route['admin/stockopname/detail_input_opname'] = 'admin/C_Stockopname/detail_input_opname';
$route['admin/stockopname/detail_input_opname/(:any)'] = 'admin/C_Stockopname/detail_input_opname/$1';
$route['admin/stockopname/widgets']                = 'admin/C_Stockopname/widgets';
$route['admin/stockopname/list']                   = 'admin/C_Stockopname/list';
$route['admin/stockopname/demo-preview']           = 'admin/C_Stockopname/demo_preview';
$route['admin/stockopname/input']                  = 'admin/C_Stockopname/input_opname';
$route['admin/stockopname/input/lookup']           = 'admin/C_Stockopname/ajax_input_lookup';
$route['admin/stockopname/input/save']             = 'admin/C_Stockopname/ajax_input_save';
$route['admin/stockopname/input/manual/barang']    = 'admin/C_Stockopname/ajax_manual_barang';
$route['admin/stockopname/input/manual/lot']       = 'admin/C_Stockopname/ajax_manual_lot';
$route['admin/stockopname/input/manual/expired']   = 'admin/C_Stockopname/ajax_manual_expired';
$route['admin/stockopname/input/manual/save']      = 'admin/C_Stockopname/ajax_manual_save';
$route['admin/stockopname/input/request/save']     = 'admin/C_Stockopname/ajax_request_save';
$route['stockopname/input']                        = 'admin/C_Stockopname/input_opname';
$route['stockopname/history-input']                = 'admin/C_Stockopname/history_input';
$route['stockopname/history-input/delete']         = 'admin/C_Stockopname/ajax_delete_history_input';
$route['supervisi-opname']                         = 'admin/C_Stockopname/supervisor_opname';
$route['supervisi-opname/afirmasi']                = 'admin/C_Stockopname/ajax_supervisor_affirm_request';
$route['supervisi-opname/tracking']                = 'admin/C_Stockopname/supervisor_tracking';
$route['supervisi-opname/tracking/list']           = 'admin/C_Stockopname/ajax_supervisor_tracking_list';
$route['admin/stockopname/history-input']          = 'admin/C_Stockopname/history_input';
$route['admin/stockopname/history-input/delete']   = 'admin/C_Stockopname/ajax_delete_history_input';
$route['admin/stockopname/master_opname']          = 'admin/C_Stockopname/master_barang';
$route['admin/stockopname/master_opname/widgets']  = 'admin/C_Stockopname/master_barang_widgets';
$route['admin/stockopname/master_opname/list']     = 'admin/C_Stockopname/ajax_master_barang_list';
$route['admin/stockopname/master_opname/ajax-list'] = 'admin/C_Stockopname/ajax_master_barang_list';
$route['admin/stockopname/master_opname/detail']   = 'admin/C_Stockopname/ajax_master_barang_detail';
$route['admin/stockopname/master_opname/update']   = 'admin/C_Stockopname/ajax_update_master_barang';
$route['admin/stockopname/master_opname/item-search'] = 'admin/C_Stockopname/ajax_master_barang_source_search';
$route['admin/stockopname/master_opname/create']   = 'admin/C_Stockopname/ajax_create_master_barang';
$route['admin/stockopname/master_barang']          = 'admin/C_Stockopname/master_barang_catalog';
$route['admin/stockopname/master_barang/list']     = 'admin/C_Stockopname/ajax_master_barang_catalog_list';
$route['admin/stockopname/master_barang/generate-qrcode'] = 'admin/C_Stockopname/ajax_generate_catalog_qrcode';
$route['admin/stockopname/master_barang/print-kartu-stock'] = 'admin/C_Stockopname/print_catalog_stock_cards';
$route['admin/stockopname/master_barang/create']   = 'admin/C_Stockopname/ajax_create_master_barang_catalog';
$route['admin/stockopname/master_barang/update']   = 'admin/C_Stockopname/ajax_update_master_barang_catalog';
$route['admin/stockopname/barang-pending']         = 'admin/C_Stockopname/barang_pending';
$route['admin/stockopname/barang-pending/list']    = 'admin/C_Stockopname/ajax_barang_pending_list';
$route['admin/stockopname/barang-pending/detail']  = 'admin/C_Stockopname/ajax_barang_pending_detail';
$route['admin/stockopname/barang-pending/save']    = 'admin/C_Stockopname/ajax_save_barang_pending';
$route['admin/stockopname/barang-pending/delete']  = 'admin/C_Stockopname/ajax_delete_barang_pending';
$route['admin/stockopname/barang-pending/export-csv'] = 'admin/C_Stockopname/barang_pending_export_csv';
$route['admin/stockopname/master_opname/qty-zero'] = 'admin/C_Stockopname/master_barang_qty_zero';
$route['admin/stockopname/master_opname/qty-zero/list'] = 'admin/C_Stockopname/ajax_master_barang_qty_zero_list';
$route['admin/stockopname/master_opname/qty-zero/ajax-list'] = 'admin/C_Stockopname/ajax_master_barang_qty_zero_list';
$route['admin/stockopname/master_opname/qty-zero/detail'] = 'admin/C_Stockopname/ajax_master_barang_qty_zero_detail';
$route['admin/stockopname/master_opname/qty-zero/generate-qrcode'] = 'admin/C_Stockopname/ajax_generate_qrcode_qty_zero';
$route['admin/stockopname/master_opname/qty-zero/preview-asset'] = 'admin/C_Stockopname/ajax_preview_asset_qty_zero';
$route['admin/stockopname/master_opname/qty-zero/print-preview-asset'] = 'admin/C_Stockopname/print_preview_asset_qty_zero';
$route['admin/stockopname/master_opname/qty-zero/print-qrcode/(:num)'] = 'admin/C_Stockopname/print_qrcode_qty_zero/$1';
$route['admin/stockopname/qrcode/summary']         = 'admin/C_Stockopname/qrcode_summary';
$route['admin/stockopname/qrcode/generate_batch']  = 'admin/C_Stockopname/qrcode_generate_batch';
$route['admin/stockopname/qrcode/retry_failed']    = 'admin/C_Stockopname/qrcode_retry_failed';
$route['admin/stockopname/qrcode/failed_list']     = 'admin/C_Stockopname/qrcode_failed_list';
$route['admin/stockopname/qrcode/reset']           = 'admin/C_Stockopname/qrcode_reset';
$route['admin/stockopname/qrcode/qty-zero/summary'] = 'admin/C_Stockopname/qrcode_qty_zero_summary';
$route['admin/stockopname/qrcode/qty-zero/generate_batch'] = 'admin/C_Stockopname/qrcode_qty_zero_generate_batch';
$route['admin/stockopname/qrcode/qty-zero/retry_failed'] = 'admin/C_Stockopname/qrcode_qty_zero_retry_failed';
$route['admin/stockopname/qrcode/qty-zero/failed_list'] = 'admin/C_Stockopname/qrcode_qty_zero_failed_list';
$route['admin/stockopname/master_opname/generate-qrcode'] = 'admin/C_Stockopname/ajax_generate_qrcode';
$route['admin/stockopname/master_opname/generate-qrcode-all'] = 'admin/C_Stockopname/ajax_generate_all_qrcode';
$route['admin/stockopname/master_opname/generate-barcode'] = 'admin/C_Stockopname/ajax_generate_barcode';
$route['admin/stockopname/master_opname/preview-asset'] = 'admin/C_Stockopname/ajax_preview_asset';
$route['admin/stockopname/master_opname/print-preview-asset'] = 'admin/C_Stockopname/print_preview_asset';
$route['admin/stockopname/master_opname/positive-qty-pcs-ids'] = 'admin/C_Stockopname/ajax_master_barang_positive_qty_pcs_ids';
$route['admin/stockopname/master_opname/print-sebagian'] = 'admin/C_Stockopname/print_kartu_stock_sebagian';
$route['admin/stockopname/master_opname/print-kartu-stock-3075-3267'] = 'admin/C_Stockopname/print_kartu_stock_3075_3267';
$route['admin/stockopname/master_opname/print-qrcode/(:num)'] = 'admin/C_Stockopname/print_qrcode/$1';

// Penilaian Lingkungan Kantor alias route
$route['penilaian_lingkungan']                      = 'hrd/C_Hrd/penilaian_lingkungan';

//DAILY STOCK AHMAD & PENDINGPO
$route['keuangan']                                  = 'keuangan/C_Keuangan';
$route['pendingpo']                                 = 'keuangan/C_Keuangan/pendingpo';
$route['insertmodule']                              = 'keuangan/C_Keuangan/insertmodule';
$route['insermodule_lot']                           = 'keuangan/C_Keuangan/insermodule_lot';
$route['insertmodule_pnd']                          = 'keuangan/C_Keuangan/insertmodule_pnd';
$route['csv_import']                                = 'keuangan/C_Keuangan/import';
$route['csv_import_lot']                            = 'keuangan/C_Keuangan/csv_import_lot';
$route['daily_stock_lot']                           = 'keuangan/C_Keuangan/daily_stock_lot';
$route['csv_import_po_pnd']                         = 'keuangan/C_Keuangan/csv_import_po_pnd';
$route['get_data_a/(:any)']                         = 'keuangan/C_Keuangan/get_stock_a/$1';
$route['detail_lot/(:any)']                         = 'keuangan/C_Keuangan/detail_lot/$1';
$route['gudang/(:any)']                             = 'keuangan/C_Keuangan/gudang/$1';
$route['get_data_global']                           = 'keuangan/C_Keuangan/get_data_global';
$route['list_stock_minimum/(:any)']                 = 'keuangan/C_Keuangan/list_stock_minimum/$1';
$route['truncateitm/(:any)/(:any)']                 = 'keuangan/C_Keuangan/trsuncateitm/$1/$2';
$route['deletedata/(:any)']                         = 'keuangan/C_Keuangan/deletedata/$1';
$route['pagination']                                = 'keuangan/C_Coba1';
$route['gudang/(:any)/suplier/(:any)']              = 'keuangan/C_Keuangan/stock_suplier/$1/$2';

// STOCK ONLINE GUDANG
$route['stock']                                     = 'logistik/C_Logistik/stock_control';

// MASTER BARANG
$route['master_barang']                             = 'keuangan/C_Keuangan/master_barang';
$route['master_barang/list']                        = 'keuangan/C_Keuangan/master_barang_list';
$route['master_barang/detail']                      = 'keuangan/C_Keuangan/master_barang_detail';
$route['master_barang/store']                       = 'keuangan/C_Keuangan/master_barang_store';
$route['master_barang/update']                      = 'keuangan/C_Keuangan/master_barang_update';
$route['master_barang/delete']                      = 'keuangan/C_Keuangan/master_barang_delete';
$route['purchase/listBarang']                       = 'keuangan/C_Keuangan/master_barang';
$route['purchase/listBarang/list']                  = 'keuangan/C_Keuangan/master_barang_list';
$route['purchase/listBarang/detail']                = 'keuangan/C_Keuangan/master_barang_detail';
$route['purchase/listBarang/store']                 = 'keuangan/C_Keuangan/master_barang_store';
$route['purchase/listBarang/update']                = 'keuangan/C_Keuangan/master_barang_update';
$route['purchase/listBarang/delete']                = 'keuangan/C_Keuangan/master_barang_delete';
$route['master_customer']                           = 'keuangan/C_Keuangan/master_customer';
$route['master_customer/list']                      = 'keuangan/C_Keuangan/master_customer_list';
$route['master_customer/detail']                    = 'keuangan/C_Keuangan/master_customer_detail';
$route['master_customer/store']                     = 'keuangan/C_Keuangan/master_customer_store';
$route['master_customer/update']                    = 'keuangan/C_Keuangan/master_customer_update';
$route['master_customer/delete']                    = 'keuangan/C_Keuangan/master_customer_delete';

// MASTER - USER MANAGEMENT
$route['master/user-management']                    = 'master/C_Usermanagement';
$route['master/user-management/list']               = 'master/C_Usermanagement/list';
$route['master/user-management/detail/(:num)']      = 'master/C_Usermanagement/detail/$1';
$route['master/user-management/save']               = 'master/C_Usermanagement/save';
$route['master/user-management/update/(:num)']      = 'master/C_Usermanagement/update/$1';
$route['master/user-management/delete/(:num)']      = 'master/C_Usermanagement/delete/$1';
$route['master/user-management/reset-password/(:num)'] = 'master/C_Usermanagement/reset_password/$1';
$route['master/user-management/toggle-status/(:num)'] = 'master/C_Usermanagement/toggle_status/$1';
$route['master/user-management/options']            = 'master/C_Usermanagement/select_options';
$route['master/jobdesk']                            = 'master/C_Jobdesk';
$route['master/jobdesk/list']                       = 'master/C_Jobdesk/list';
$route['master/jobdesk/detail/(:num)']              = 'master/C_Jobdesk/detail/$1';
$route['master/jobdesk/save']                       = 'master/C_Jobdesk/save';
$route['master/jobdesk/update/(:num)']              = 'master/C_Jobdesk/update/$1';
$route['master/jobdesk/delete/(:num)']              = 'master/C_Jobdesk/delete/$1';
$route['master/akses-level']                        = 'master/C_Akseslevel';
$route['master/akses-level/list']                   = 'master/C_Akseslevel/list';
$route['master/akses-level/detail/(:num)']          = 'master/C_Akseslevel/detail/$1';
$route['master/akses-level/save']                   = 'master/C_Akseslevel/save';
$route['master/akses-level/update/(:num)']          = 'master/C_Akseslevel/update/$1';
$route['master/akses-level/delete/(:num)']          = 'master/C_Akseslevel/delete/$1';
$route['master/akses-level/matrix/(:num)']          = 'master/C_Akseslevel/matrix/$1';
$route['master/akses-level/update-permission']      = 'master/C_Akseslevel/update_permission';
$route['master/menu']                               = 'master/C_Menu';
$route['master/menu/list']                          = 'master/C_Menu/list';
$route['master/menu/detail/(:num)']                 = 'master/C_Menu/detail/$1';
$route['master/menu/save']                          = 'master/C_Menu/save';
$route['master/menu/update/(:num)']                 = 'master/C_Menu/update/$1';
$route['master/menu/delete/(:num)']                 = 'master/C_Menu/delete/$1';
$route['master/menu/sidebar']                       = 'master/C_Menu/sidebar';

// LOGISTIK ICS
$route['ics']                                       = 'logistik/C_Ics';

$route['ics/master_barang']                         = 'logistik/C_Ics/master_barang';
$route['ics/save_mbarang']                          = 'logistik/C_Ics/add_master_barang';
$route['ics/save_edit_mbarang']                     = 'logistik/C_Ics/edit_master_barang';
$route['ics/get_detail_mbarang']                    = 'logistik/C_Ics/get_detail_mbarang';

$route['ics/by_expdate']                            = 'logistik/C_Ics/ics_by_expdate';
$route['ics/by_allbarang']                          = 'logistik/C_Ics/ics_by_allbarang';
$route['ics/updateinline']                          = 'logistik/C_Ics/update_inline';
$route['ics/icsdo']                                 = 'logistik/C_Ics/ics_do';
$route['ics/icsdo/dohistori']                       = 'logistik/C_Ics/history_ics_do';
$route['ics/icspo']                                 = 'logistik/C_Ics/ics_po';
$route['ics/diffrent']                              = 'logistik/C_Ics/ics_diffrent';
$route['ics/sv_opname']                             = 'logistik/C_Ics/save_opname_ics';
$route['ics/get_detail_barang']                     = 'logistik/C_Ics/get_detail_barang';
$route['ics/ics_stock_controller/(:any)']           = 'logistik/C_Ics/ics_stock_controller/$1';
$route['ics/get_detail_by_exp_date']                = 'logistik/C_Ics/get_detail_by_exp_date';
$route['ics/stock_by_kodebr/(:any)']                = 'logistik/C_Ics/stock_by_kodebr/$1';
$route['ics/get_detail_by_exp']                     = 'logistik/C_Ics/get_detail_by_exp';
$route['ics/by_allbarang_ics/(:any)']               = 'logistik/C_Ics/by_allbarang_ics/$1';
$route['ics/detail_allbarang/(:any)']               = 'logistik/C_Ics/ics_detail_allbarang/$1';
$route['ics/by_expdate_ics/(:any)']                 = 'logistik/C_Ics/by_expdate_ics/$1';
$route['ics/ics_diffrent']                          = 'logistik/C_Ics/ics_diffrent';
$route['ics/import_csv']                            = 'logistik/C_Ics/import_csv_po';
$route['ics/sc_do_by_date_range']                   = 'logistik/C_Ics/sc_do_by_date_range';
$route['ics/kalkulatorics']                         = 'logistik/C_Ics/kalkulator_operan';
$route['ics/detail_barang/(:any)']                  = 'logistik/C_Ics/view_detail_master_barang/$1';

$route['ics/gudang']                                = 'logistik/C_Ics/master_gudang';
$route['ics/gudang_list']                           = 'logistik/C_Ics/gudang_list';
$route['ics/gudang_save']                           = 'logistik/C_Ics/gudang_save';
$route['ics/get_wilayah_by_gudang']                 = 'logistik/C_Ics/get_wilayah_by_gudang';
$route['ics/update_gudang']                         = 'logistik/C_Ics/update_gudang';
$route['ics/update_wilayah']                        = 'logistik/C_Ics/update_wilayah';
$route['ics/detail_wilayah/(:any)']                 = 'logistik/C_Ics/detail_wilayah/$1';
$route['data_lpb_zahir']                            = 'logistik/C_Ics/data_lpb_zahir';
$route['ics/sync_po_pre_do']                        = 'logistik/C_Ics/sync_po_pre_do';
$route['save_qty_diterima']                         = 'logistik/C_Ics/save_qty_diterima';
$route['get_barang_by_po']                          = 'logistik/C_ics/get_barang_by_po';
$route['po_selesai']                                = 'logistik/C_Ics/po_selesai';
$route['riwayat_barang_masuk']                      = 'logistik/C_Ics/riwayat_barang_masuk';
$route['get_lpb']                                   = 'logistik/C_Ics/get_lpb';
$route['ics/detail_po']                             = 'logistik/C_Ics/detail_po';
$route['ics/detail_record_lpb']                     = 'logistik/C_Ics/detail_record_lpb';
$route['ics/ajax_get_lpb_records_by_kd_po']         = 'logistik/C_Ics/ajax_get_lpb_records_by_kd_po';
$route['ics/ajax_get_lpb_record_detail']            = 'logistik/C_Ics/ajax_get_lpb_record_detail';
$route['ics/ajax_get_pre_po_adjustment']            = 'logistik/C_Ics/ajax_get_pre_po_adjustment';
$route['ics/ajax_submit_adjustment']                = 'logistik/C_Ics/ajax_submit_adjustment';
$route['ics/ajax_history_adjustment']               = 'logistik/C_Ics/ajax_history_adjustment';
$route['ics/ajax_history_invoice']                  = 'logistik/C_Ics/ajax_history_invoice';
$route['ics/ajax_history_diskon']                   = 'logistik/C_Ics/ajax_history_diskon';
$route['ics/ajax_update_invoice']                   = 'logistik/C_Ics/ajax_update_invoice';
$route['ics/ajax_get_tmp_po_received_item']         = 'logistik/C_Ics/ajax_get_tmp_po_received_item';
$route['ics/ajax_get_tmp_po_received_summary']      = 'logistik/C_Ics/ajax_get_tmp_po_received_summary';
$route['ics/ajax_save_tmp_po_received']             = 'logistik/C_Ics/ajax_save_tmp_po_received';
$route['ics/ajax_finalize_tmp_po_received']         = 'logistik/C_Ics/ajax_finalize_tmp_po_received';
$route['ics/barangpic']                             = 'logistik/C_Ics/pic_barang';
$route['ics/barangpic/(:any)']                      = 'logistik/C_Ics/pic_barang/$1';
$route['ics/update_pic_lokasi']                     = 'logistik/C_Ics/update_pic_lokasi';
$route['ics/barangpergudang']                       = 'logistik/C_Ics/barangpergudang';
$route['ics/ajax_barang_pergudang']                 = 'logistik/C_Ics/ajax_barang_pergudang';
$route['ics/api/stock_per_gudang']                  = 'logistik/C_Ics/api_stock_per_gudang';


$route['api/v1/stock/(:any)']                       = 'logistik/C_Ics/api_stock/$1';

$route['ics/retur']                                 = 'logistik/C_Ics/dash_retur';
$route['ics/retur/penjualan']                       = 'logistik/C_Ics/retur_penjualan';
$route['ics/retur/pembelian']                       = 'logistik/C_Ics/retur_pembelian';
$route['ics/retur/faktur_select2']                  = 'logistik/C_Ics/ajax_retur_faktur_select2';
$route['ics/retur/barang_select2']                  = 'logistik/C_Ics/ajax_retur_barang_select2';
$route['ics/retur/lot_select2']                     = 'logistik/C_Ics/ajax_retur_lot_select2';
$route['ics/retur/exp_select2']                     = 'logistik/C_Ics/ajax_retur_exp_select2';
$route['ics/retur/add_detail']                      = 'logistik/C_Ics/ajax_retur_add_detail';
$route['ics/retur/list_detail']                     = 'logistik/C_Ics/ajax_retur_list_detail';
$route['ics/retur/delete_detail']                   = 'logistik/C_Ics/ajax_retur_delete_detail';
$route['ics/retur/rekam_penjualan']                 = 'logistik/C_Ics/ajax_retur_rekam_penjualan';
$route['ics/retur/rekam_pembelian']                 = 'logistik/C_Ics/ajax_retur_rekam_pembelian';
$route['ics/retur/detail_retur']                    = 'logistik/C_Ics/detail_retur';
$route['ics/retur/detail_retur/(:any)']             = 'logistik/C_Ics/detail_retur/$1';
$route['ics/retur/pembelian/faktur_select2']        = 'logistik/C_Ics/ajax_retur_pembelian_faktur_select2';
$route['ics/retur/pembelian/barang_select2']        = 'logistik/C_Ics/ajax_retur_pembelian_barang_select2';
$route['ics/retur/pembelian/exp_select2']           = 'logistik/C_Ics/ajax_retur_pembelian_exp_select2';
$route['ics/retur/pembelian/add_detail']            = 'logistik/C_Ics/ajax_retur_pembelian_add_detail';
$route['ics/retur/pembelian/list_detail']           = 'logistik/C_Ics/ajax_retur_pembelian_list_detail';
$route['ics/retur/pembelian/delete_detail']         = 'logistik/C_Ics/ajax_retur_pembelian_delete_detail';

// MUTASI BARANG GUDANG
$route['ics/mutasi_barang']                         = 'logistik/C_Ics/mutasi_barang';
$route['ics/mutasi_barang/input']                   = 'logistik/C_Ics/input_mutasi_barang';

$route['ics/ajax_barang_select2']                   = 'logistik/C_Ics/ajax_barang_select2';
$route['ics/ajax_expired_by_barang']                = 'logistik/C_Ics/ajax_expired_by_barang';

$route['ics/ajax_barang_by_gudang']                 = 'logistik/C_Ics/ajax_barang_by_gudang';
$route['ics/ajax_exp_by_gudang_barang']             = 'logistik/C_Ics/ajax_exp_by_gudang_barang';
$route['ics/ajax_get_qty_gudang']                   = 'logistik/C_Ics/ajax_get_qty_gudang';
$route['ics/ajax_add_tmp_mutasi']                   = 'logistik/C_Ics/ajax_add_tmp_mutasi';
$route['ics/ajax_list_tmp_mutasi']                  = 'logistik/C_Ics/ajax_list_tmp_mutasi';
$route['ics/ajax_update_tmp_mutasi']                = 'logistik/C_Ics/ajax_update_tmp_mutasi';
$route['ics/ajax_delete_tmp_mutasi']                = 'logistik/C_Ics/ajax_delete_tmp_mutasi';
$route['ics/ajax_rekam_mutasi']                     = 'logistik/C_Ics/ajax_rekam_mutasi';

$route['ics/ajax_detail_mutasi']                    = 'logistik/C_Ics/ajax_detail_mutasi';
$route['ics/ajax_rollback_mutasi']                  = 'logistik/C_Ics/ajax_rollback_mutasi';
$route['ics/ajax_delete_mutasi']                    = 'logistik/C_Ics/ajax_delete_mutasi';
$route['ics/ajax_unpost_mutasi']                    = 'logistik/C_Ics/ajax_unpost_mutasi';
$route['ics/ajax_filter_mutasi']                    = 'logistik/C_Ics/ajax_filter_mutasi';


$route['logistik/stock']                            = 'logistik/C_Ics/saldo_stock';
$route['logistik/lpb']                              = 'logistik/C_Ics/create_lpb';

// LOGISTIK - DISTRIBUSI
$route['logistik/distibusi']                        = 'logistik/C_Distribusi';
$route['logistik/distibusi/get_ploting_rute']       = 'logistik/C_Distribusi/get_ploting_rute';
$route['logistik/distibusi/driver_rute_matrix']     = 'logistik/C_Distribusi/driver_rute_matrix';
$route['logistik/distibusi/driver_ready']           = 'logistik/C_Distribusi/driver_ready';
$route['logistik/distibusi/list_faktur_status']     = 'logistik/C_Distribusi/list_faktur_status';
$route['logistik/distibusi/ajax_list_faktur_status'] = 'logistik/C_Distribusi/ajax_list_faktur_status';
$route['logistik/distibusi/list_total_kirim_do']    = 'logistik/C_Distribusi/list_total_kirim_do';
$route['logistik/distibusi/ajax_total_kirim_do']    = 'logistik/C_Distribusi/ajax_total_kirim_do';
$route['logistik/distibusi/export_total_kirim_do']    = 'logistik/C_Distribusi/export_total_kirim_do';
$route['logistik/distibusi/driver_productif']       = 'logistik/C_Distribusi/driver_productif';
$route['logistik/distibusi/ajax_driver_productif']  = 'logistik/C_Distribusi/ajax_driver_productif';
$route['logistik/distibusi/export_driver_productif']  = 'logistik/C_Distribusi/export_driver_productif';
$route['logistik/distibusi/list_do_status_2']       = 'logistik/C_Distribusi/list_do_status_2';
$route['logistik/distibusi/ajax_detail_tonase_by_rute'] = 'logistik/C_Distribusi/ajax_detail_tonase_by_rute';
$route['logistik/distibusi/ajax_dashboard_distribusi'] = 'logistik/C_Distribusi/ajax_dashboard_distribusi';

$route['distibusi/detail_rute/(:any)']              = 'logistik/C_Distribusi/detail_rute/$1';
$route['detail_tonase/(:any)']                      = 'logistik/C_Distribusi/detail_tonase_by_rute/$1';


// LOGISTIK & OPNAME
$route['final_result']                              = 'logistik/C_Logistik/final_result_opname';
$route['ics/print_lpb_record/(:num)']               = 'logistik/C_Ics/print_lpb_record/$1';
$route['ics/print_lpb_records_all']                 = 'logistik/C_Ics/print_lpb_records_all';
$route['ics/(:any)']                                = 'logistik/C_Logistik/ics/$1';
$route['stockopname']                               = 'logistik/C_Logistik/stockopname';
$route['gudang']                                    = 'logistik/C_Logistik/module_gudang';
$route['detailbarang/(:any)']                       = 'logistik/C_Logistik/detailbarang/$1';
$route['forminput/(:any)/(:any)']                   = 'logistik/C_Logistik/forminput/$1/$2';
$route['insertopname']                              = 'logistik/C_Logistik/insertopname';
$route['stkopname_tracking']                        = 'logistik/C_Logistik/stkopname_tracking';
$route['admstocktracking']                          = 'logistik/C_Logistik/admstocktracking';
$route['searchbarang']                              = 'logistik/C_Logistik/searchbarang';
$route['search_get_exp_date']                       = 'logistik/C_Logistik/search_get_exp_date';
$route['save_opname']                               = 'logistik/C_Logistik/save_opname';
$route['save_edit_opname']                          = 'logistik/C_Logistik/save_edit_opname';
$route['request_opname']                            = 'logistik/C_Logistik/request_opname';
$route['cek_req_user_opname/(:any)/(:any)']         = 'logistik/C_Logistik/cek_req_user_opname/$1/$2';
$route['req_opname_acc/(:any)']                     = 'logistik/C_Logistik/req_opname_acc/$1';
$route['trackingtim/(:any)']                        = 'logistik/C_Logistik/admtrackingtim/$1';
$route['compare_opname']                            = 'logistik/C_Logistik/compare_opname';
$route['compare_wilayah/(:any)']                    = 'logistik/C_Logistik/compare_wilayah/$1';
$route['opname_datapending']                        = 'logistik/C_Logistik/opname_datapending';
$route['request_opname_admin']                      = 'logistik/C_Logistik/request_opname_admin';
$route['detailtrack/(:any)/(:any)']                 = 'logistik/C_Logistik/detail_tracking_input/$1/$2';
$route['export_compare_allbarang']                  = 'logistik/C_Logistik/export_compare_allbarang';
$route['usropname_input']                           = 'logistik/C_Logistik/usropname_input';
$route['delete_opname/(:any)']                      = 'logistik/C_Logistik/delete_opname/$1';
$route['data_final_input_opname']                   = 'logistik/C_Logistik/data_final_input_opname';

//LOGISTIK - DO
$route['data_preview_do']                           = 'logistik/C_Logistik/preview_csv';
$route['pre_do/insert_csv']                         = 'logistik/C_Logistik/insert_csv';
$route['logistik']                                  = 'logistik/C_Logistik/delivery_order';
$route['logistikprepare']                           = 'logistik/C_Logistik/delivery_order';
$route['create_do']                                 = 'logistik/C_Logistik/create_do';
$route['view_faktur_not_list']                      = 'logistik/C_Logistik/view_faktur_not_list';
$route['ajax_view_faktur_not_list']                 = 'logistik/C_Logistik/ajax_view_faktur_not_list';
$route['ajax_update_kd_barang_not_list']            = 'logistik/C_Logistik/ajax_update_kd_barang_not_list';
$route['faktur_on_site']                            = 'logistik/C_Logistik/faktur_on_site';
$route['edited_rute_do']                            = 'logistik/C_Logistik/edited_rute_do';
$route['insert_tmp/(:any)/(:any)']                  = 'logistik/C_Logistik/insert_tmp/$1/$2';
$route['revert_do/(:any)/(:any)']                   = 'logistik/C_Logistik/revert_do/$1/$2';
$route['cancel_fk/(:any)/(:any)']                   = 'logistik/C_Logistik/cancel_fk/$1/$2';
$route['detail_fk/(:any)']                          = 'logistik/C_Logistik/detail_fk/$1';
$route['insertfromdraft/(:any)/(:any)']             = 'logistik/C_Logistik/insertfromdraft/$1/$2';
$route['detail_do/(:any)']                          = 'logistik/C_Logistik/detail_do/$1';
$route['logistik/get_faktur']                       = 'logistik/C_Logistik/get_faktur';

$route['faktur_bintang']                            = 'logistik/C_Logistik/faktur_bintang';
$route['get_fktur_bintang']                         = 'logistik/C_Logistik/get_fktur_bintang';
$route['get_customer_bintang']                      = 'logistik/C_Logistik/get_customer_bintang';
$route['update_customer_faktur']                    = 'logistik/C_Logistik/update_customer_faktur';

$route['tonase_report']                             = 'logistik/C_Logistik/tonase_report';
$route['do/confirm_sales']              = 'logistik/C_Logistik/confirm_sales';
$route['get_list_faktur_ajax'] = 'logistik/C_Logistik/get_list_faktur_ajax';

//LOGISTIK - Checker
$route['checker']                                   = 'logistik/C_Checker/index';
$route['checker/dashboard']                         = 'logistik/C_Checker/dashboard';
$route['checker/arsip']                             = 'logistik/C_Checker/arsip';
$route['checker/store']                             = 'logistik/C_Checker/store';
$route['checker/start']                             = 'logistik/C_Checker/start';
$route['checker/update_progres']                    = 'logistik/C_Checker/update_progres';
$route['checker/done']                              = 'logistik/C_Checker/done';
$route['checker/update_status_bongkaran']           = 'logistik/C_Checker/update_status_bongkaran';
$route['checker/archive_all_today']                 = 'logistik/C_Checker/archive_all_today';
$route['checker/store_kk']                          = 'logistik/C_Checker/store_kk';
$route['checker/update_kk']                         = 'logistik/C_Checker/update_kk';
$route['checker/store_lk']                          = 'logistik/C_Checker/store_lk';
$route['checker/update_lk']                         = 'logistik/C_Checker/update_lk';
$route['checker/start_lk']                          = 'logistik/C_Checker/start_lk';
$route['checker/update_progres_lk']                 = 'logistik/C_Checker/update_progres_lk';
$route['checker/done_lk']                           = 'logistik/C_Checker/done_lk';
$route['checker/start_kk']                          = 'logistik/C_Checker/start_kk';
$route['checker/update_progres_kk']                 = 'logistik/C_Checker/update_progres_kk';
$route['checker/done_kk']                           = 'logistik/C_Checker/done_kk';

//LOGISTIK - DO (FAKTUR PENDING)
$route['detail_fk_pnd/(:any)']                      = 'logistik/C_Logistik/detail_fk_pnd/$1';
$route['create_pending_do/(:any)']                  = 'logistik/C_Logistik/create_pending_do/$1';

// $route['list_faktur/(:any)/(:any)']              = 'logistik/C_Logistik/list_faktur_sortby_rute/$1/$2';

$route['list_faktur/(:any)']                        = 'logistik/C_Logistik/list_faktur_sortby_rute/$1';
$route['acc_check/(:any)/(:any)/(:any)']            = 'logistik/C_Logistik/acc_check/$1/$2/$3';
$route['rekam_order_check']                         = 'logistik/C_Logistik/rekam_order_check';
$route['do/repost_status']                          = 'logistik/C_Logistik/repost_status';
$route['do/delete_ics_do']                          = 'logistik/C_Logistik/delete_ics_do';
$route['print_do/(:any)']                           = 'logistik/C_Logistik/print_do/$1';
$route['print_regis/(:any)']                        = 'logistik/C_Logistik/print_regis/$1';
$route['print_checker/(:any)']                      = 'logistik/C_Logistik/print_checker/$1';
$route['pnd_br_detpo/(:any)/(:any)/(:any)']         = 'logistik/C_Logistik/pnd_br_detpo/$1/$2/$3';
$route['get_barang']                                = 'logistik/C_Logistik/get_barang';
$route['get_barang_pending_detail']                 = 'logistik/C_Logistik/get_barang_pending_detail';
$route['update_barang_pending']                     = 'logistik/C_Logistik/update_barang_pending';
$route['update_barang']                             = 'logistik/C_Logistik/update_barang';
$route['rekam_do']                                  = 'logistik/C_Logistik/rekam_do';
$route['truncatelog/(:any)/(:any)']                 = 'logistik/C_Logistik/truncatelog/$1/$2';
$route['get_tmp_do']                                = 'logistik/C_Logistik/get_tmp_do';
$route['get_tmpdonorut']                            = 'logistik/C_Logistik/get_tmpdonorut';
$route['update_norut']                              = 'logistik/C_Logistik/update_norut';
$route['save_do']                                   = 'logistik/C_Logistik/save_do';
$route['custupdate']                                = 'logistik/C_Logistik/custupdate';

// ---- Sales Order ----
$route['sales_order']                               = 'sales/C_SalesOrder/index';
$route['sales_order/create']                        = 'sales/C_SalesOrder/create';
$route['sales_order/store']                         = 'sales/C_SalesOrder/store';
$route['sales_order/detail/(:any)']                 = 'sales/C_SalesOrder/detail/$1';
$route['sales_order/edit/(:any)']                   = 'sales/C_SalesOrder/edit/$1';
$route['sales_order/update/(:any)']                 = 'sales/C_SalesOrder/update/$1';
$route['sales_order/cancel/(:any)']                 = 'sales/C_SalesOrder/cancel/$1';
$route['sales_order/approval']                      = 'sales/C_SalesOrder/approval';
$route['sales_order/approve']                       = 'sales/C_SalesOrder/approve';
$route['sales_order/get_stock']                     = 'sales/C_SalesOrder/get_stock';
$route['sales_order/get_barang']                    = 'sales/C_SalesOrder/get_barang';
$route['sales_order/activity_log']                  = 'sales/C_SalesOrder/activity_log';
$route['sales_order/list_do']                       = 'sales/C_SalesOrder/list_do';
$route['sales_order/detail_do/(:any)']              = 'sales/C_SalesOrder/detail_do/$1';
$route['sales_order/confirm_loading']               = 'sales/C_SalesOrder/confirm_loading';

// COBA API
$route['getdata_kiupo']                             = 'api/C_Api';
$route['sync_pre_po_erp']                          = 'api/C_Api/sync_pre_po_erp';

//SCHEDULE DIREKTUR
$route['schedule_direktur']                         = 'schedule/C_Schedule';
$route['act_schedule/(:any)']                       = 'schedule/C_Schedule/act_schedule/$1';

// DEVELOPMENT 
$route['development']                               = 'schedule/C_Development/dashboard_do';

// EXTRAVAGANZA - UNDIAN
$route['extravaganza']                              = 'extravaganza/C_Extravaganza';
$route['extravaganza_undian']                       = 'extravaganza/C_Extravaganza/undian';
$route['extravaganza_savewin']                      = 'extravaganza/C_Extravaganza/save_win';

// EXTRAVAGANZA - REGISTRASI
$route['extravaganza_registrasi']                   = 'extravaganza/C_Extravaganza/registrasi_tamu';

// USER REPORT
$route['user_report']                               = 'karyawan_report/C_Report';

// PRICELIST - ONLINE
$route['pricelist_online']                          = 'keuangan/C_Keuangan/pricelist_online';

// SALES TONASE KUBIKASI
$route['sales_report']                              = 'sales/C_Sales/dashboard_sales';

// EXPORT
$route['export-stock']                              = 'logistik/C_ExportStock/export';

// SALES KIU KATALOG
$route['kiu_katalog']                               = 'sales/C_ExportStock/export';

//IndeksNilaiKepuasanPelanggan
$route['kepuasan_pelanggan']                        = 'pelanggan/C_Pelanggan';
$route['nilai_ipkp/(:any)']                         = 'pelanggan/C_Pelanggan/input_nilai/$1';
$route['ratingreview']                              = 'pelanggan/C_Pelanggan/rating_review';


//LOGISTIK - Checker
$route['checker/edit_kk']                           = 'logistik/C_Checker/edit_kk';
$route['checker/hapus_kk']                          = 'logistik/C_Checker/hapus_kk';
$route['checker/edit_lk']                           = 'logistik/C_Checker/edit_lk';
$route['checker/hapus_lk']                          = 'logistik/C_Checker/hapus_lk';
$route['checker/pause']                             = 'logistik/C_Checker/pause';
$route['checker/resume']                            = 'logistik/C_Checker/resume';
$route['checker/pause_kk']                          = 'logistik/C_Checker/pause_kk';
$route['checker/resume_kk']                         = 'logistik/C_Checker/resume_kk';
$route['checker/pause_lk']                          = 'logistik/C_Checker/pause_lk';
$route['checker/resume_lk']                         = 'logistik/C_Checker/resume_lk';
$route['checker/start_siapkan_kk']                  = 'logistik/C_Checker/start_siapkan_kk';
$route['checker/update_progres_siapkan_kk']         = 'logistik/C_Checker/update_progres_siapkan_kk';
$route['checker/done_siapkan_kk']                   = 'logistik/C_Checker/done_siapkan_kk';
$route['checker/pause_siapkan_kk']                  = 'logistik/C_Checker/pause_siapkan_kk';
$route['checker/resume_siapkan_kk']                 = 'logistik/C_Checker/resume_siapkan_kk';
$route['checker/start_siapkan_lk']                  = 'logistik/C_Checker/start_siapkan_lk';
$route['checker/update_progres_siapkan_lk']         = 'logistik/C_Checker/update_progres_siapkan_lk';
$route['checker/done_siapkan_lk']                   = 'logistik/C_Checker/done_siapkan_lk';
$route['checker/pause_siapkan_lk']                  = 'logistik/C_Checker/pause_siapkan_lk';
$route['checker/resume_siapkan_lk']                 = 'logistik/C_Checker/resume_siapkan_lk';
$route['checker/detail_kk/(:any)']                  = 'logistik/C_Checker/detail_kk/$1';
$route['checker/detail_lk/(:any)']                  = 'logistik/C_Checker/detail_lk/$1';

// dashboard_penilaian
$route['dashboard_penilaian']                       = 'hrd/C_Hrd/dashboard_penilaian';

// Mobile ERP modern UI
$route['mobile-erp']                                = 'hrd/C_Hrd/mobile_erp_dashboard';
$route['mobile-erp/list']                           = 'hrd/C_Hrd/mobile_erp_list';
$route['mobile-erp/detail/(:num)']                  = 'hrd/C_Hrd/mobile_erp_detail/$1';
$route['mobile-erp/profile']                        = 'hrd/C_Hrd/mobile_erp_profile';

// Penilaian Lingkungan Kantor
$route['penilaian_lingkungan']                     = 'hrd/C_Hrd/penilaian_lingkungan';
$route['hrd/penilaian_lingkungan/admin']           = 'hrd/C_Hrd/penilaian_lingkungan_admin';
$route['hrd/penilaian_lingkungan/monitoring']      = 'hrd/C_Hrd/penilaian_lingkungan_monitoring';
$route['hrd/penilaian_lingkungan/submit']          = 'hrd/C_Hrd/submit_environment_issue';
$route['hrd/penilaian_lingkungan/list']            = 'hrd/C_Hrd/get_environment_issue_list';
$route['hrd/penilaian_lingkungan/detail/(:num)']   = 'hrd/C_Hrd/get_environment_issue_detail/$1';
$route['hrd/penilaian_lingkungan/update']          = 'hrd/C_Hrd/update_environment_issue';
$route['hrd/penilaian_lingkungan/stats']           = 'hrd/C_Hrd/get_environment_issue_stats';
$route['hrd/penilaian_lingkungan/breakdown']       = 'hrd/C_Hrd/get_environment_issue_breakdown';
$route['hrd/penilaian_lingkungan/locations']       = 'hrd/C_Hrd/get_hrd_locations';
$route['hrd/penilaian_lingkungan/locations/save']  = 'hrd/C_Hrd/save_hrd_location';
$route['hrd/penilaian_lingkungan/locations/delete'] = 'hrd/C_Hrd/delete_hrd_location';
$route['hrd/penilaian_lingkungan/ratings']         = 'hrd/C_Hrd/get_hrd_ratings';
$route['hrd/penilaian_lingkungan/ratings/save']    = 'hrd/C_Hrd/save_hrd_rating';
$route['hrd/penilaian_lingkungan/ratings/delete']  = 'hrd/C_Hrd/delete_hrd_rating';

$route['admin/stockopname/master_barang/qrcode/(:num)'] = 'admin/C_Stockopname/catalog_qrcode/$1';
