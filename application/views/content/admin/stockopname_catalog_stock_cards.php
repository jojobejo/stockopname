<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
function so_catalog_card_date($value)
{
    $parts = explode('-', (string)$value);
    return count($parts) === 3 ? $parts[2] . '/' . $parts[1] . '/' . $parts[0] : (string)$value;
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Cetak Kartu Stock</title>
    <style>
        *{box-sizing:border-box}body{margin:0;background:#eef2f7;color:#111827;font-family:Arial,sans-serif}
        .toolbar{position:sticky;top:0;z-index:2;background:#fff;border-bottom:1px solid #dbe2ea;padding:12px 20px;display:flex;justify-content:space-between;align-items:center;gap:12px}
        .toolbar strong{font-size:16px}.toolbar small{display:block;color:#64748b;margin-top:3px}.toolbar button{border:0;border-radius:6px;background:#0878ef;color:#fff;padding:9px 18px;font-weight:700;cursor:pointer}
        .sheet{width:194mm;min-height:268mm;margin:14px auto;padding:0;display:grid;grid-template-columns:repeat(2,94mm);grid-auto-rows:126mm;gap:4mm;background:#fff}
        .stock-card{width:94mm;height:126mm;border:1px solid #111827;background:#fff;text-align:center;display:flex;flex-direction:column;align-items:center;overflow:hidden}
        .stock-card-name{width:100%;padding:6mm 4mm 2mm;font-size:12pt;font-weight:700;line-height:1.2;min-height:23mm;display:flex;align-items:center;justify-content:center;word-break:break-word}
        .stock-card-qr{width:100%;height:87mm;display:flex;align-items:center;justify-content:center}
        .stock-card-qr img{width:80mm;height:80mm;object-fit:contain}
        .stock-card-expired{width:100%;margin-top:5mm;border-top:1px solid #111827;padding:3mm 2mm;font-size:10pt;font-weight:700}
        @page{size:A4 portrait;margin:8mm}
        @media print{body{background:#fff}.toolbar{display:none}.sheet{width:194mm;min-height:0;margin:0;break-after:page;page-break-after:always}.sheet:last-child{break-after:auto;page-break-after:auto}}
    </style>
</head>
<body>
    <div class="toolbar"><div><strong>Cetak Kartu Stock</strong><small><?= count($items) ?> kartu QR siap cetak · <?= $mode === 'all' ? 'semua' : 'terpilih' ?></small></div><button type="button" onclick="window.print()">Cetak</button></div>
    <?php foreach (array_chunk($items, 4) as $page): ?>
        <div class="sheet">
            <?php foreach ($page as $item): ?>
                <article class="stock-card">
                    <div class="stock-card-name"><?= html_escape($item['nama_barang']) ?></div>
                    <div class="stock-card-qr"><img src="<?= html_escape($item['qrcode_url']) ?>" alt="QR barang"></div>
                    <div class="stock-card-expired">EXPIRED DATE : <?= html_escape(so_catalog_card_date($item['expired_date'])) ?></div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</body>
</html>
