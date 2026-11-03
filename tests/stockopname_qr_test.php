<?php
if (PHP_SAPI !== 'cli') { exit; }
define('BASEPATH', dirname(__DIR__).'/system/');
define('APPPATH', dirname(__DIR__).'/application/');
require APPPATH.'libraries/Karisma_code_generator.php';
if (!is_file(APPPATH.'libraries/stockopnameqr/qrcode.php')) { throw new RuntimeException('Library QR lokal tidak ada'); }
$path = tempnam(sys_get_temp_dir(), 'opname_qr_');
try {
    $generator = new Karisma_code_generator();
    if (!$generator->qrcode('BR|123', $path)) { throw new RuntimeException('Generate QR lokal gagal'); }
    $info = getimagesize($path);
    if (!$info || $info[2] !== IMAGETYPE_PNG) { throw new RuntimeException('QR bukan PNG valid'); }
    echo "PASS: QR barang dibuat secara lokal menjadi PNG valid\n";
} finally { if (is_file($path)) { unlink($path); } }
