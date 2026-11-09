<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Explicit source: never fall back from imported to direct/cache.
$config['zahir_catalog_mode'] = ENVIRONMENT === 'production' ? 'imported' : (getenv('ZAHIR_CATALOG_MODE') ?: 'imported');
$config['zahir_compare_local'] = ENVIRONMENT !== 'production';
$config['zahir_import_source_instance'] = getenv('ZAHIR_SOURCE_INSTANCE') ?: 'zahir-local';
$config['zahir_import_scope'] = getenv('ZAHIR_SCOPE') ?: 'all';

// Halaman yang sudah ada di ZahirDigital. Koneksi Firebird tetap dikelola di sana.
$config['zahir_lot_page_url'] = 'https://10.10.10.12/ZahirDigital/LOGISTIK/lotbarang_v2.php';
$config['zahir_master_barang_url'] = 'https://10.10.10.12/ZahirDigital/LOGISTIK/Daftar%20Barang%20new.php';
