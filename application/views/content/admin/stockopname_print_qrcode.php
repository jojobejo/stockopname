<?php
defined('BASEPATH') or exit('No direct script access allowed');

$barang = $barang ?? [];
$qrcode = $qrcode ?? ['exists' => false, 'url' => '', 'path' => ''];
$scanValue = $scan_value ?? '';
$inventoryDate = $inventory_date ?? date('d/m/Y');

function so_print_e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= so_print_e($page_title ?? 'Print Kartu Stock') ?></title>
    <style>
        *{box-sizing:border-box}
        body{margin:0;background:#eef2f7;color:#111827;font-family:Arial,Helvetica,sans-serif}
        .page{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px}
        .asset-card{width:96mm;background:#fff;border:1px solid #111827;color:#111827;text-align:center;print-color-adjust:exact;-webkit-print-color-adjust:exact}
        .asset-card-header{padding:3mm 3mm 0}
        .asset-card-kicker{font-size:11pt;font-weight:800;line-height:1.15}
        .asset-card-location{font-size:13pt;font-weight:800;line-height:1.2;margin-top:1mm}
        .asset-card-location:after{content:"";display:block;border-top:1px solid #111827;margin:2mm -3mm 0}
        .asset-card-description{border-bottom:1px solid #111827;padding:3mm 3mm 4mm}
        .asset-card-description-title{font-size:11pt;font-weight:800;margin-bottom:1.5mm}
        .asset-card-description-text{font-size:13pt;font-weight:800;line-height:1.25;min-height:12mm;display:flex;align-items:center;justify-content:center;word-break:break-word}
        .asset-card-meta{margin-top:2mm;font-size:9pt;font-weight:700;line-height:1.35;color:#6b7280}
        .asset-card-meta div{word-break:break-word}
        .asset-card-qr{display:flex;align-items:center;justify-content:center;min-height:58mm;margin:3mm 0 1mm}
        .asset-card-qr img{width:56mm;height:56mm;object-fit:contain}
        .asset-card-qr-empty{width:56mm;height:56mm;border:1px dashed #6b7280;display:flex;align-items:center;justify-content:center;padding:4mm;color:#4b5563;font-size:10pt;line-height:1.25}
        .asset-card-date{border-bottom:1px solid #111827;padding:2.5mm 3mm;font-size:11pt;font-weight:800}
        .asset-card-signature{display:grid;grid-template-columns:1fr 1fr;min-height:24mm}
        .asset-card-signature div{display:flex;align-items:flex-start;justify-content:center;padding-top:3mm;font-size:11pt;font-weight:800}
        .asset-card-signature div+div{border-left:1px solid #111827}
        @media print{
            body{background:#fff}
            .page{min-height:auto;padding:0;display:block}
            .asset-card{width:100%;border:1px solid #111827}
        }
    </style>
</head>
<body>
    <main class="page">
        <section class="asset-card">
            <div class="asset-card-header">
                <div class="asset-card-kicker">LOCATION</div>
                <div class="asset-card-location">GUDANG INDUK</div>
            </div>

            <div class="asset-card-description">
                <div class="asset-card-description-title">DESCRIPTION</div>
                <div class="asset-card-description-text"><?= so_print_e($barang['nama_barang'] ?? '-') ?></div>
                <div class="asset-card-meta">
                    <div><?= so_print_e($barang['kode_barang'] ?? '-') ?></div>
                    <div><?= so_print_e($barang['expired_date'] ?? '-') ?> | <?= so_print_e($barang['no_lot'] ?? '-') ?></div>
                </div>

                <div class="asset-card-qr">
                    <?php if (!empty($qrcode['exists']) && !empty($qrcode['url'])): ?>
                        <img src="<?= so_print_e($qrcode['url']) ?>" alt="QRCode <?= so_print_e($scanValue) ?>">
                    <?php else: ?>
                        <div class="asset-card-qr-empty">QR Code belum tergenerate</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="asset-card-date">INVENTORY DATE : <?= so_print_e($inventoryDate) ?></div>
            <div class="asset-card-signature">
                <div>Paraf 1</div>
                <div>Paraf 2</div>
            </div>
        </section>
    </main>

    <script>
    window.addEventListener('load', function () {
        window.print();
    });
    </script>
</body>
</html>
