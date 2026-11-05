<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="id"><head><meta charset="utf-8"><title>QR Barang</title>
<style>body{font:16px Arial;text-align:center;padding:24px}.label{display:inline-block;border:1px solid #333;padding:20px;max-width:340px}img{width:220px;height:220px}button{margin:20px;padding:10px}@media print{button{display:none}}</style></head>
<body><div class="label"><h2><?= html_escape($barang['nama_barang']) ?></h2>
<p><?= html_escape($barang['kode_barang']) ?></p>
<img src="<?= base_url($qr_path) ?>" alt="QR barang">
<p>Expired Date: <?= html_escape($barang['expired_date']) ?></p><p>Scan untuk input opname berdasarkan Data Master Barang.</p></div><br>
<button onclick="window.print()">Cetak QR Barang</button></body></html>
