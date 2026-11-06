<?php
if (PHP_SAPI !== 'cli') { exit; }
define('BASEPATH', dirname(__DIR__) . '/system/');
require dirname(__DIR__) . '/application/libraries/Zahir_lot_catalog.php';

$catalog = (new ReflectionClass('Zahir_lot_catalog'))->newInstanceWithoutConstructor();
$merge = new ReflectionMethod('Zahir_lot_catalog', 'mergeLotRows');
$merge->setAccessible(true);
$rows = $merge->invoke($catalog, [
    ['kode_barang' => 'A100', 'nama_barang' => 'Barang * A', 'expired_date' => '2027-01-01', 'qty_lot' => 3],
    ['kode_barang' => 'Q100', 'nama_barang' => 'Barang A', 'expired_date' => '2027-01-01', 'qty_lot' => 2],
    ['kode_barang' => 'Q000', 'nama_barang' => 'Barang A', 'expired_date' => '2027-01-01', 'qty_lot' => 0],
    ['kode_barang' => 'Q200', 'nama_barang' => 'Barang A', 'expired_date' => '2027-01-01', 'qty_lot' => 1],
    ['kode_barang' => 'Q200', 'nama_barang' => 'Barang A', 'expired_date' => '2027-02-01', 'qty_lot' => 4],
    ['kode_barang' => 'A300', 'nama_barang' => 'Barang B', 'expired_date' => '2027-01-01', 'qty_lot' => 0],
    ['kode_barang' => 'A400', 'nama_barang' => 'Barang C', 'expired_date' => '2027-01-01', 'qty_lot' => 5],
    ['kode_barang' => 'A400', 'nama_barang' => 'Barang C', 'expired_date' => '2027-01-01', 'qty_lot' => -5],
    ['kode_barang' => 'Q500', 'nama_barang' => 'Barang D', 'expired_date' => '2027-01-01', 'qty_lot' => 0],
    ['kode_barang' => 'A500', 'nama_barang' => 'Barang D', 'expired_date' => '2027-01-01', 'qty_lot' => 1],
]);

if ($rows !== [
    ['kode_barang' => 'A500', 'nama_barang' => 'Barang D', 'expired_date' => '2027-01-01'],
    ['kode_barang' => 'Q100', 'nama_barang' => 'Barang A', 'expired_date' => '2027-01-01'],
    ['kode_barang' => 'Q200', 'nama_barang' => 'Barang A', 'expired_date' => '2027-02-01'],
]) {
    throw new RuntimeException('Penggabungan LOT, prioritas Q, atau filter qty tidak sesuai: ' . json_encode($rows));
}
echo "PASS: nama tanpa *, expired terpisah, kode Q positif diprioritaskan, qty kosong tidak tampil\n";
