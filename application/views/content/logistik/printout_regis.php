<style>
    @media print {
        .table thead th {
            background-color: rgb(0, 0, 0) !important;
            color: #fff !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }

    .footer-highlight {
        background-color: black !important;
        color: white;
        font-weight: bold;
        text-align: center;
    }

    .footer-black {
        background-color: black !important;
        color: white;
        text-align: center;
    }


    .badge-tempo {
        display: inline-block;
        padding: 6px 12px;
        font-size: 14px;
        font-weight: bold;
        border-radius: 6px;
        min-width: 70px;
        text-align: center;
    }

    .badge-cash {
        background-color: #007bff;
        color: #fff;
    }

    .badge-30 {
        background-color: #ffc107;
        color: #000;
    }

    .badge-other {
        background-color: #28a745;
        color: #fff;
    }


    table {
        font-size: 14px;
        white-space: nowrap;
    }

    th,
    td {
        vertical-align: middle;
        text-align: center;
    }

    .table thead th {
        background-color: rgb(0, 0, 0);
        color: #fff !important;
    }

    .header-title {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
        text-transform: uppercase;
    }

    .info-faktur {
        margin-bottom: 20px;
        font-size: 16px;
    }

    .info-faktur div {
        margin-bottom: 5px;
    }

    .wrap-text {
        white-space: normal !important;
        text-align: left;
    }

    .wrap-text-left {
        text-align: left !important;
    }
</style>

<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper m-5">
        <?php foreach ($dostatus as $d) : ?>
            <div class="header-title"><?= $d->regional ?></div>
            <div class="info-faktur">
                <?php foreach ($doprintsts as $print) :
                    $tonase = ($print->total_tonase_faktur / 1000);
                    $tgl_kirim = $this->input->get('tgl_kirim');
                    $driver = $this->input->get('driver');
                    $plat = $this->input->get('plat');
                ?>
                    <div>Tanggal Kirim : <?= htmlspecialchars($tgl_kirim) ?></div>
                    <div>Driver : <?= htmlspecialchars($driver) ?></div>
                    <div>No Lambung : <?= htmlspecialchars($plat) ?></div>
                <?php endforeach; ?>
            </div>

            <table class="table table-bordered" id="tb_checker_do">
                <thead>
                    <tr>
                        <th colspan="2">Data Kios</th>
                        <th colspan="3">TTB</th>
                        <th rowspan="2">Cash / Tempo</th>
                    </tr>
                    <tr>
                        <th>Nama Kios</th>
                        <th>Alamat</th>
                        <th>Kode Faktur</th>
                        <th>Tgl Input</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_value = 0;
                    $rowspan_count = [];
                    foreach ($datatc as $row) {
                        if (!isset($rowspan_count[$row->kd_faktur])) {
                            $rowspan_count[$row->kd_faktur] = 0;
                        }
                        $rowspan_count[$row->kd_faktur]++;
                    }
                    $printed_faktur = [];
                    foreach ($datatc as $row) :
                        $show_faktur_info = !in_array($row->kd_faktur, $printed_faktur);
                        if ($show_faktur_info) {
                            $printed_faktur[] = $row->kd_faktur;
                        }

                        // Total value
                        $total_value += $row->nominal_p;

                        // Format tempo
                        if ($row->jtempo == '0') {
                            $tempo = '<span class="badge-tempo badge-cash">Cash</span>';
                        } elseif ($row->jtempo == '30') {
                            $tempo = '<span class="badge-tempo badge-30">30 Hari</span>';
                        } else {
                            $tempo = '<span class="badge-tempo badge-other">' . htmlspecialchars($row->jtempo) . ' Hari</span>';
                        }
                    ?>
                        <tr>
                            <?php if ($show_faktur_info) : ?>
                                <td rowspan="<?= $rowspan_count[$row->kd_faktur] ?>"><?= $row->nama_kios ?></td>
                                <td rowspan="<?= $rowspan_count[$row->kd_faktur] ?>" class="wrap-text-left"><?= $row->alamat_kios ?></td>
                                <td rowspan="<?= $rowspan_count[$row->kd_faktur] ?>"><?= $row->kd_faktur ?></td>
                                <td rowspan="<?= $rowspan_count[$row->kd_faktur] ?>"><?= $row->tgl_transaksi ?></td>
                            <?php endif; ?>
                            <td><?= format_rupiah($row->nominal_p) ?></td>
                            <td><?= $tempo ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" style="text-align: right;">Total</th>
                        <th><?= format_rupiah($total_value) ?></th>
                        <th class="footer-highlight">&nbsp;</th>
                    </tr>
                </tfoot>
            </table>
        <?php endforeach; ?>
    </div>
</body>