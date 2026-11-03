<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($page_title ?? 'Print Record LPB') ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #111827;
            margin: 0;
            padding: 18px;
        }

        .print-sheet {
            margin-bottom: 28px;
            page-break-after: always;
        }

        .print-sheet:last-child {
            page-break-after: auto;
        }

        .print-title {
            text-align: center;
            margin-bottom: 14px;
        }

        .print-title h2 {
            margin: 0 0 4px;
            font-size: 20px;
        }

        .print-title p {
            margin: 0;
            font-size: 12px;
        }

        .header-table,
        .detail-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table {
            margin-bottom: 14px;
        }

        .header-table td {
            border: 1px solid #111;
            padding: 7px 8px;
            vertical-align: top;
        }

        .header-label {
            width: 22%;
            font-weight: bold;
            background: #f3f4f6;
        }

        .detail-table th,
        .detail-table td {
            border: 1px solid #111;
            padding: 7px 8px;
        }

        .detail-table th {
            background: #e5e7eb;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .summary-note {
            margin-bottom: 10px;
            font-size: 12px;
        }

        @media print {
            body {
                padding: 0;
            }
        }
    </style>
</head>

<body onload="window.print();">
    <?php
    $mode = $print_mode ?? 'single';
    $printDate = date('d/m/Y H:i:s');
    ?>

    <?php foreach (($records ?? []) as $index => $record) :
        $header = $record['header'] ?? [];
        $rows = $record['rows'] ?? [];
    ?>
        <div class="print-sheet">
            <div class="print-title">
                <h2>PRINT OUT LPB</h2>
                <p>PT. KARISMA INDOARGO UNIVERSAL</p>
                <?php if ($mode === 'all') : ?>
                    <p>KD PO: <?= htmlspecialchars($kd_po ?? '-') ?> | No PO: <?= htmlspecialchars($no_po ?? '-') ?></p>
                <?php endif; ?>
                <p>Tanggal Cetak: <?= htmlspecialchars($printDate) ?></p>
            </div>

            <div class="summary-note">
                LPB #<?= htmlspecialchars($header['id_lpb'] ?? '-') ?>
            </div>

            <table class="header-table">
                <tr>
                    <td class="header-label">Nomor SJ</td>
                    <td><?= htmlspecialchars($header['nosj'] ?? '-') ?></td>
                    <td class="header-label">Tanggal SJ</td>
                    <td><?= htmlspecialchars($header['tgl_sj'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="header-label">Invoice</td>
                    <td><?= htmlspecialchars($header['no_invoice'] ?? '-') ?></td>
                    <td class="header-label">Total Qty</td>
                    <td><?= htmlspecialchars((string) ($header['total_qty'] ?? '0')) ?></td>
                </tr>
                <tr>
                    <td class="header-label">Tanggal Input</td>
                    <td><?= htmlspecialchars($header['input_at'] ?? '-') ?></td>
                    <td class="header-label">Keterangan</td>
                    <td><?= htmlspecialchars($header['keterangan'] ?? '-') ?></td>
                </tr>
            </table>

            <table class="detail-table">
                <thead>
                    <tr>
                        <th style="width: 6%;">No</th>
                        <th>Nama Barang</th>
                        <th style="width: 16%;">Qty Diterima (pcs)</th>
                        <th style="width: 16%;">No Lot</th>
                        <th style="width: 18%;">Expired Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($rows)) : ?>
                        <?php foreach ($rows as $rowIndex => $row) : ?>
                            <tr>
                                <td class="text-center"><?= $rowIndex + 1 ?></td>
                                <td><?= htmlspecialchars($row['nama_barang'] ?? '-') ?></td>
                                <td class="text-right"><?= htmlspecialchars((string) ($row['qty_diterima'] ?? 0)) ?></td>
                                <td><?= htmlspecialchars($row['no_lot'] ?? '-') ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['expired_date'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="text-center">Detail LPB kosong.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>
</body>

</html>