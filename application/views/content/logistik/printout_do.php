<style>
    @media print {
        body {
            font-family: "Courier New", monospace;
            font-size: 16px;
            line-height: 2.0;
        }

        .header-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
        }

        .header-title-rute {
            font-size: 16px;
            margin-bottom: 10px;
            text-align: center;
        }

        .info-faktur {
            font-size: 16px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 16px;
            page-break-inside: auto;
            /* izinkan tabel terbagi */
        }

        table th,
        table td {
            border: 1px solid black;
            padding: 2px 4px;
        }

        tr {
            page-break-inside: auto;
        }

        td,
        th {
            page-break-inside: auto;
        }

        thead {
            display: table-header-group;
        }

    }
</style>

<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">
        <?php foreach ($dostatus as $d) : ?>
            <div class="header-title">FAKTUR DELIVERY ORDER</div>
            <div class="header-title-rute">RUTE : <?= $d->regional ?></div>

            <div class="info-faktur">
                <?php
                $tgl_kirim = $this->input->get('tgl_kirim');
                ?>
                <div>Tanggal Kirim : <?= htmlspecialchars($tgl_kirim) ?></div>
                <div>Driver : <?= htmlspecialchars($doprintsts->nama_driver) ?></div>
                <div>No Polisi : <?= htmlspecialchars($doprintsts->noplat) ?></div>
                <div>Total Customer :<?= $doprintsts->totalfaktur ?> </div>
                <div>Total Barang : <?= $doprintsts->total_barang ?></div>
                <div>Tonase : <?= $doprintsts->total_tonase_faktur . ' (Ton)' ?></div>
                <div>Kubikasi : <?= $doprintsts->total_kubikasi . ' (m³) ' ?></div>
            </div>

            <table class="table table-bordered" id="tb_checker_do">
                <thead>
                    <tr>
                        <th rowspan="2">Nama Kios</th>
                        <th rowspan="2">Rute</th>
                        <th rowspan="2">Kode Faktur</th>
                        <th rowspan="2">Tgl Input</th>
                        <th rowspan="2">No</th>
                        <th rowspan="2">Nama Barang</th>
                        <th rowspan="2">No Lot</th>
                        <th colspan="2">Qty</th>
                    </tr>
                    <tr>
                        <th>Besar</th>
                        <th>Kecil</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $prev_norut = null;
                    $rowspan_count = [];
                    $norut_counter = 1;

                    foreach ($data_list as $row) {
                        if (!isset($rowspan_count[$row->kd_faktur])) {
                            $rowspan_count[$row->kd_faktur] = 0;
                        }
                        $rowspan_count[$row->kd_faktur]++;
                    }

                    $printed_faktur = [];
                    foreach ($data_list as $row) :
                        $show_faktur_info = !in_array($row->kd_faktur, $printed_faktur);
                        if ($show_faktur_info) {
                            $printed_faktur[] = $row->kd_faktur;
                            $norut_counter = 1;
                        }

                        if ($row->karakteristik_kios == '') {
                            $karakteristik_kios = '-';
                        } else {
                            $karakteristik_kios = $row->karakteristik_kios;
                        }

                        if ($row->jam_buka_tutup == '') {
                            $jambukatutup = '-';
                        } else {
                            $jambukatutup = $row->jam_buka_tutup;
                        }
                    ?>
                        <tr>
                            <?php if ($show_faktur_info) :
                            ?>
                                <td class="nama-kios" rowspan="<?= $rowspan_count[$row->kd_faktur] ?>"><?= $row->nama_kios . "</br>" . "(" . $row->telp1 . "/" . $row->telp2 . ")" ?></td>
                                <td rowspan="<?= $rowspan_count[$row->kd_faktur] ?>"><?= $row->kd_rute ?></td>
                                <td rowspan="<?= $rowspan_count[$row->kd_faktur] ?>"><?= $row->kd_faktur ?></td>
                                <td rowspan="<?= $rowspan_count[$row->kd_faktur] ?>"><?= $row->tgl_transaksi ?></td>
                            <?php endif; ?>
                            <td><?= $norut_counter++ ?></td>
                            <td class="nama-barang"><?= $row->nm_barang ?></td>
                            <td class="no-lot"><?= $row->no_lot ?> - <?= $row->tgl_exp ?></td>
                            <td><?= $row->qty_box ?></td>
                            <td><?= $row->qty_pcs ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    </div>
    <!-- ./wrapper -->