<?php
defined('BASEPATH') or exit('No direct script access allowed');

$items = $items ?? [];
$inventoryDate = $inventory_date ?? date('d/m/Y');
$printHeading = $print_heading ?? 'Print Preview Asset Stockopname';
$printDescription = $print_description ?? ('F4, 12 preview asset per kertas, total ' . count($items) . ' asset siap print');
$itemsPerSheet = 12;
$sheets = array_chunk($items, $itemsPerSheet);

function so_asset_print_e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function so_asset_print_date($value)
{
    $value = trim((string)$value);
    if ($value === '') {
        return '-';
    }

    $timestamp = strtotime($value);
    if (!$timestamp) {
        return $value;
    }

    return date('d/m/Y', $timestamp);
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= so_asset_print_e($page_title ?? 'Print Preview Asset') ?></title>
    <style>
        *{box-sizing:border-box}
        @page{size:215mm 330mm;margin:2mm}
        body{margin:0;background:#e5e7eb;color:#111827;font-family:Arial,Helvetica,sans-serif}
        .toolbar{position:sticky;top:0;z-index:5;display:flex;align-items:center;justify-content:space-between;gap:12px;padding:12px 16px;background:#111827;color:#fff;box-shadow:0 8px 20px rgba(15,23,42,.18)}
        .toolbar-title{font-size:14px;font-weight:800;line-height:1.25}
        .toolbar-meta{font-size:12px;color:#cbd5e1;margin-top:2px}
        .toolbar button{border:0;border-radius:6px;background:#0f766e;color:#fff;font-weight:800;padding:8px 12px;cursor:pointer}
        .print-wrap{padding:6mm 0}
        .sheet{width:211mm;height:326mm;margin:0 auto 6mm;background:#fff;display:grid;grid-template-columns:repeat(2,1fr);grid-template-rows:repeat(6,1fr);gap:1.2mm;break-after:page;page-break-after:always}
        .sheet:last-child{break-after:auto;page-break-after:auto}
        .asset-card{height:100%;min-height:52mm;border:1px solid #111827;background:#fff;color:#111827;text-align:center;display:grid;grid-template-rows:7.5mm 1fr 6mm 11mm;overflow:hidden;print-color-adjust:exact;-webkit-print-color-adjust:exact}
        .asset-card-header{padding:1mm 2mm 0}
        .asset-card-kicker{font-size:7pt;font-weight:800;line-height:1}
        .asset-card-location{font-size:7.5pt;font-weight:800;line-height:1.05;margin-top:.5mm}
        .asset-card-location:after{content:"";display:block;border-top:1px solid #111827;margin:1.2mm -2mm 0}
        .asset-card-description{border-bottom:1px solid #111827;display:grid;grid-template-columns:1fr 28mm;gap:1.5mm;padding:1.5mm 2mm;min-width:0}
        .asset-card-info{min-width:0;display:flex;flex-direction:column;align-items:center;justify-content:center}
        .asset-card-description-title{font-size:7pt;font-weight:800;margin-bottom:.6mm}
        .asset-card-description-text{font-size:8pt;font-weight:800;line-height:1.1;max-height:17mm;overflow:hidden;display:-webkit-box;-webkit-line-clamp:4;-webkit-box-orient:vertical;word-break:break-word}
        .asset-card-meta{margin-top:.8mm;font-size:6pt;font-weight:700;line-height:1.18;color:#4b5563;max-width:100%}
        .asset-card-meta div{word-break:break-word}
        .asset-card-qr{display:flex;align-items:center;justify-content:center}
        .asset-card-qr img{width:26mm;height:26mm;object-fit:contain}
        .asset-card-date{border-bottom:1px solid #111827;padding:1.5mm 2mm;font-size:7pt;font-weight:800;white-space:nowrap}
        .asset-card-signature{display:grid;grid-template-columns:1fr 1fr}
        .asset-card-signature div{display:flex;align-items:center;justify-content:center;padding:.8mm;font-size:7pt;font-weight:800}
        .asset-card-signature div+div{border-left:1px solid #111827}
        .empty-state{width:211mm;margin:16mm auto;background:#fff;border:1px solid #cbd5e1;border-radius:8px;padding:18mm;text-align:center;color:#475569;font-weight:800}
        @media print{
            body{background:#fff}
            .toolbar{display:none}
            .print-wrap{padding:0}
            .sheet{width:100%;height:326mm;margin:0;box-shadow:none}
            .empty-state{display:none}
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <div>
            <div class="toolbar-title"><?= so_asset_print_e($printHeading) ?></div>
            <div class="toolbar-meta"><?= so_asset_print_e($printDescription) ?></div>
        </div>
        <button type="button" onclick="window.print()">Print</button>
    </div>

    <main class="print-wrap">
        <?php if (empty($sheets)): ?>
            <div class="empty-state">Belum ada preview asset dengan QR Code siap print.</div>
        <?php endif; ?>

        <?php foreach ($sheets as $sheet): ?>
            <section class="sheet">
                <?php foreach ($sheet as $item): ?>
                    <?php
                    $barang = $item['barang'] ?? [];
                    $qrcode = $item['qrcode'] ?? [];
                    ?>
                    <article class="asset-card">
                        <div class="asset-card-header">
                            <div class="asset-card-kicker">LOCATION</div>
                            <div class="asset-card-location">GUDANG INDUK</div>
                        </div>

                        <div class="asset-card-description">
                            <div class="asset-card-info">
                                <div class="asset-card-description-title">DESCRIPTION</div>
                                <div class="asset-card-description-text"><?= so_asset_print_e($barang['nama_barang'] ?? '-') ?></div>
                                <div class="asset-card-meta">
                                    <div><?= so_asset_print_e($barang['kode_barang'] ?? '-') ?></div>
                                    <div><?= so_asset_print_e(so_asset_print_date($barang['expired_date'] ?? '')) ?> | <?= so_asset_print_e($barang['no_lot'] ?? '-') ?></div>
                                </div>
                            </div>
                            <div class="asset-card-qr">
                                <img src="<?= so_asset_print_e($qrcode['url'] ?? '') ?>" alt="QRCode <?= so_asset_print_e($item['scan_value'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="asset-card-date">INVENTORY DATE : <?= so_asset_print_e($inventoryDate) ?></div>
                        <div class="asset-card-signature">
                            <div>Paraf 1</div>
                            <div>Paraf 2</div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php endforeach; ?>
    </main>

    <script>
    window.addEventListener('load', function () {
        var images = Array.prototype.slice.call(document.images || []);
        var pending = images.filter(function (img) { return !img.complete; });
        if (!pending.length) {
            window.setTimeout(function () { window.print(); }, 250);
            return;
        }

        var left = pending.length;
        function done() {
            left -= 1;
            if (left <= 0) {
                window.setTimeout(function () { window.print(); }, 250);
            }
        }

        pending.forEach(function (img) {
            img.addEventListener('load', done, {once: true});
            img.addEventListener('error', done, {once: true});
        });
    });
    </script>
</body>
</html>
