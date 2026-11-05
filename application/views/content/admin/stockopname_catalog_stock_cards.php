<?php defined('BASEPATH') or exit('No direct script access allowed');

$items = $items ?? [];
$sheets = array_chunk($items, 15);
$escape = static function ($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
};
$printDate = static function ($value) {
    $value = trim((string)$value);
    if ($value === '') { return '-'; }
    $date = DateTime::createFromFormat('!Y-m-d', $value);
    return $date && $date->format('Y-m-d') === $value ? $date->format('d/m/Y') : $value;
};
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Kartu Stock</title>
    <style>
        *{box-sizing:border-box}
        @page{size:A4 portrait;margin:5mm}
        body{margin:0;background:#e5e7eb;color:#111827;font-family:Arial,Helvetica,sans-serif}
        .toolbar{position:sticky;top:0;z-index:2;display:flex;align-items:center;justify-content:space-between;gap:12px;padding:12px 18px;background:#111827;color:#fff}
        .toolbar strong{font-size:15px}.toolbar small{display:block;margin-top:3px;color:#d1d5db}
        .toolbar button{border:0;border-radius:5px;background:#0f766e;color:#fff;padding:9px 16px;font-weight:700;cursor:pointer}
        .print-wrap{padding:8mm 0}
        .sheet{width:200mm;height:287mm;margin:0 auto 8mm;background:#fff;display:grid;grid-template-columns:repeat(3,minmax(0,1fr));grid-template-rows:repeat(5,minmax(0,1fr));gap:2mm;break-after:page;page-break-after:always}
        .sheet:last-child{break-after:auto;page-break-after:auto}
        .stock-card{min-width:0;min-height:0;border:1px solid #1f2937;display:flex;flex-direction:column;align-items:center;text-align:center;overflow:hidden;break-inside:avoid;page-break-inside:avoid}
        .stock-card-name{width:100%;height:12mm;padding:1.2mm 1.5mm .5mm;font-size:7.5pt;font-weight:700;line-height:1.15;overflow:hidden;overflow-wrap:anywhere;display:flex;align-items:center;justify-content:center}
        .stock-card-qr{flex:1;min-height:0;display:flex;align-items:center;justify-content:center}
        .stock-card-qr img{display:block;width:35mm;height:35mm;object-fit:contain}
        .stock-card-date{width:100%;min-height:6mm;display:flex;align-items:center;justify-content:center;border-top:1px solid #1f2937;padding:.5mm 1mm;font-size:7pt;font-weight:700;line-height:1.1}
        @media print{
            body{background:#fff}
            .toolbar{display:none}
            .print-wrap{padding:0}
            .sheet{margin:0;width:200mm;height:287mm}
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <div><strong>Cetak Kartu Stock</strong><small><?= count($items) ?> kartu · <?= count($sheets) ?> halaman A4 · maksimal 15 kartu per halaman</small></div>
        <button type="button" onclick="window.print()">Cetak</button>
    </div>
    <main class="print-wrap">
        <?php foreach ($sheets as $sheet): ?>
            <section class="sheet">
                <?php foreach ($sheet as $item): ?>
                    <article class="stock-card">
                        <div class="stock-card-name"><?= $escape($item['nama_barang'] ?? '-') ?></div>
                        <div class="stock-card-qr"><img src="<?= $escape($item['qrcode_url'] ?? '') ?>" alt="QR <?= $escape($item['kode_barang'] ?? '') ?>"></div>
                        <div class="stock-card-date">EXPIRED DATE: <?= $escape($printDate($item['expired_date'] ?? '')) ?></div>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php endforeach; ?>
    </main>
</body>
</html>
