<style>
    .table-fixed-header thead th {
        position: sticky;
        top: 0;
        background-color: #343a40;
        /* warna sesuai thead-dark */
        color: white;
        z-index: 10;
    }
</style>

<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>

        <div class="content-wrapper">
            <div class="content-header">
                <?php if ($this->session->userdata('jobdesk') == 'ADMINICS') : ?>
                    <section class="content">
                        <?php

                        $lv     = $this->session->userdata('lv');
                        $tim    = $this->session->userdata('tim');

                        $pic = $this->session->userdata('nama');

                        if ($lv == 1) {
                            $akses = 'admin';
                        } elseif ($lv == 2) {
                            $akses = 'ics';
                        }
                        ?>
                        <div class="row">
                            <?php if ($akses == 'admin') : ?>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/by_allbarang') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-box"></i> Data All Barang</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/by_expdate') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-calendar"></i> Data By Expired Date</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/icsdo') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-minus-circle"></i> Data DO</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/gudang') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-minus-circle"></i> Master Gudang</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/icspo') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-plus-circle"></i> Data PO</a>
                                </div>
                                <!-- <div class="col-auto">
                                    <a href="<?= base_url('logistik/stock') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-plus-circle"></i> LOGISTIK V2</a>
                                </div> -->
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/barangpic') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-database"></i> Master Barang PIC</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/barangpergudang') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-database"></i> Barang Per Gudang</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/mutasi_barang') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-database"></i> Mutasi Barang Gudang</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/ics_diffrent') ?>" class="btn btn-md btn-secondary w-100 mb-3"><i class="fas fa-eye"></i> Show Diffrent</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('logistik/distibusi/list_faktur_status') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-file"></i> List Faktur Terkirim / Belum</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('export-stock') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-file-export"></i> Export Data Expired Date</a>
                                </div>
                            <?php elseif ($akses == 'ics') : ?>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/by_allbarang_ics/') . $tim ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-box"></i> Data All Barang</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/by_expdate_ics/') . $tim ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-calendar"></i> Data By Expired Date</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/icsdo') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-minus-circle"></i> Data DO</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/icspo') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-plus-circle"></i> Data PO</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/ics_diffrent_ics/') . $tim ?>" class="btn btn-md btn-secondary w-100 mb-3"><i class="fas fa-eye"></i> Show Diffrent</a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="card">
                            <?php if ($akses == 'admin') : ?>
                                <div class="card-header ui-sortable-handle " style="cursor: move">
                                    <h3 class="card-title">
                                        Stock Diffrent
                                    </h3>
                                    <div class="card-tools">
                                        <ul class="nav nav-pills ml-auto">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#ics_a" data-toggle="tab">A</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#ics_b" data-toggle="tab">B</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#ics_c" data-toggle="tab">C</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#ics_d" data-toggle="tab">D</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#ics_e" data-toggle="tab">E</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#ics_0" data-toggle="tab">0</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content p-0">
                                        <div class="chart tab-pane active" id="ics_a">
                                            <h3>VIEW STOCK DIFFRENT TIM A</h3>
                                            <table class="table table-bordered table-hover table-sm table-fixed-header" id="tbics_erp">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" class="align-middle bg-info text-white text-center">#</th>
                                                        <th colspan="2" class="bg-primary text-white text-center">NAMA</th>
                                                        <th colspan="2" class="bg-info text-white text-center">Saldo Awal</th>
                                                        <th colspan="2" class="bg-success text-white text-center">LPB</th>
                                                        <th colspan="2" class="bg-danger text-white text-center">DO</th>
                                                        <th colspan="2" class="bg-success text-white text-center">RETUR</th>
                                                        <th colspan="2" class="bg-info text-white text-center">Sistem</th>
                                                        <th colspan="2" class="bg-success text-white text-center">Fisik</th>
                                                        <th colspan="3" class="align-middle bg-danger text-white text-center">Selisih</th>
                                                        <th rowspan="2" class="align-middle bg-primary text-white text-center">Kordinat</th>
                                                        <th rowspan="2" class="align-middle bg-success text-white text-center">Status</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="bg-primary text-white">Nama Barang</th>
                                                        <th class="bg-primary text-white">Date</th>
                                                        <th class="bg-info text-white">Box</th>
                                                        <th class="bg-info text-white">Pcs</th>
                                                        <th class="bg-success text-white">Box</th>
                                                        <th class="bg-success text-white">Pcs</th>
                                                        <th class="bg-danger text-white">Box</th>
                                                        <th class="bg-danger text-white">Pcs</th>
                                                        <th class="bg-success text-white">Jual</th>
                                                        <th class="bg-success text-white">Beli</th>
                                                        <th class="bg-info text-white">Box</th>
                                                        <th class="bg-info text-white">Pcs</th>
                                                        <th class="bg-success text-white">Box</th>
                                                        <th class="bg-success text-white">Pcs</th>
                                                        <th class="bg-danger text-white">Qty</th>
                                                        <th class="bg-danger text-white">Box</th>
                                                        <th class="bg-danger text-white">Pcs</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($barang_ics_a as $br) : ?>
                                                        <tr>
                                                            <td>
                                                                <a href="<?= base_url('ics/stock_by_kodebr/' . $br->kd)  ?>" target="__blank" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                                            </td>
                                                            <td><?= $br->nama_barang ?></td>
                                                            <td><?= $br->exp_date ?></td>
                                                            <td><?= $br->saldo_awal_box ?></td>
                                                            <td><?= $br->saldo_awal_pcs ?></td>
                                                            <td><?= $br->in_box ?></td>
                                                            <td><?= $br->in_pcs ?></td>
                                                            <td><?= $br->out_box ?></td>
                                                            <td><?= $br->out_pcs ?></td>
                                                            <td><?= $br->qty_rjual ?></td>
                                                            <td><?= $br->qty_rbeli ?></td>
                                                            <td><?= $br->saldo_akhir_box ?></td>
                                                            <td><?= $br->saldo_akhir_pcs ?></td>
                                                            <td><?= $br->fisik_box ?></td>
                                                            <td><?= $br->fisik_pcs ?></td>
                                                            <td><?= $br->qty_selisih ?></td>
                                                            <td><?= $br->selisih_box ?></td>
                                                            <td><?= $br->selisih_pcs ?></td>
                                                            <td style="text-align: center;"><?= $br->kordinat ?></td>
                                                            <?php if ($br->status_kesesuaian == 'KLOP') : ?>
                                                                <td style="text-align: center;">
                                                                    <a href="#" class="btn btn-sm btn-success"><i class="fas fa-check-circle"></i></a>
                                                                </td>
                                                            <?php else : ?>
                                                                <td style="text-align: center;">
                                                                    <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-times-circle"></i></a>
                                                                </td>
                                                            <?php endif; ?>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="chart tab-pane" id="ics_b">
                                            <h3>VIEW STOCK DIFFRENT TIM B</h3>
                                            <table class="table table-bordered table-hover table-sm table-fixed-header" id="tbics_erp_b">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" class="align-middle bg-info text-white text-center">#</th>
                                                        <th colspan="2" class="bg-primary text-white text-center">NAMA</th>
                                                        <th colspan="2" class="bg-info text-white text-center">Saldo Awal</th>
                                                        <th colspan="2" class="bg-success text-white text-center">LPB</th>
                                                        <th colspan="2" class="bg-danger text-white text-center">DO</th>
                                                        <th colspan="2" class="bg-success text-white text-center">RETUR</th>
                                                        <th colspan="2" class="bg-info text-white text-center">Sistem</th>
                                                        <th colspan="2" class="bg-success text-white text-center">Fisik</th>
                                                        <th colspan="3" class="align-middle bg-danger text-white text-center">Selisih</th>
                                                        <th rowspan="2" class="align-middle bg-primary text-white text-center">Kordinat</th>
                                                        <th rowspan="2" class="align-middle bg-success text-white text-center">Status</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="bg-primary text-white">Nama Barang</th>
                                                        <th class="bg-primary text-white">Date</th>
                                                        <th class="bg-info text-white">Box</th>
                                                        <th class="bg-info text-white">Pcs</th>
                                                        <th class="bg-success text-white">Box</th>
                                                        <th class="bg-success text-white">Pcs</th>
                                                        <th class="bg-danger text-white">Box</th>
                                                        <th class="bg-danger text-white">Pcs</th>
                                                        <th class="bg-success text-white">Jual</th>
                                                        <th class="bg-success text-white">Beli</th>
                                                        <th class="bg-info text-white">Box</th>
                                                        <th class="bg-info text-white">Pcs</th>
                                                        <th class="bg-success text-white">Box</th>
                                                        <th class="bg-success text-white">Pcs</th>
                                                        <th class="bg-danger text-white">Qty</th>
                                                        <th class="bg-danger text-white">Box</th>
                                                        <th class="bg-danger text-white">Pcs</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($barang_ics_b as $br) : ?>
                                                        <tr>
                                                            <td>
                                                                <a href="<?= base_url('ics/stock_by_kodebr/' . $br->kd)  ?>" target="__blank" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                                            </td>
                                                            <td><?= $br->nama_barang ?></td>
                                                            <td><?= $br->exp_date ?></td>
                                                            <td><?= $br->saldo_awal_box ?></td>
                                                            <td><?= $br->saldo_awal_pcs ?></td>
                                                            <td><?= $br->in_box ?></td>
                                                            <td><?= $br->in_pcs ?></td>
                                                            <td><?= $br->out_box ?></td>
                                                            <td><?= $br->out_pcs ?></td>
                                                            <td><?= $br->qty_rjual ?></td>
                                                            <td><?= $br->qty_rbeli ?></td>
                                                            <td><?= $br->saldo_akhir_box ?></td>
                                                            <td><?= $br->saldo_akhir_pcs ?></td>
                                                            <td><?= $br->fisik_box ?></td>
                                                            <td><?= $br->fisik_pcs ?></td>
                                                            <td><?= $br->qty_selisih ?></td>
                                                            <td><?= $br->selisih_box ?></td>
                                                            <td><?= $br->selisih_pcs ?></td>
                                                            <td style="text-align: center;"><?= $br->kordinat ?></td>
                                                            <?php if ($br->status_kesesuaian == 'KLOP') : ?>
                                                                <td style="text-align: center;">
                                                                    <a href="#" class="btn btn-sm btn-success"><i class="fas fa-check-circle"></i></a>
                                                                </td>
                                                            <?php else : ?>
                                                                <td style="text-align: center;">
                                                                    <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-times-circle"></i></a>
                                                                </td>
                                                            <?php endif; ?>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="chart tab-pane" id="ics_c">
                                            <h3>VIEW STOCK DIFFFRENT C</h3>
                                            <table class="table table-bordered table-hover table-sm table-fixed-header" id="tbics_erp_c">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" class="align-middle bg-info text-white text-center">#</th>
                                                        <th colspan="2" class="bg-primary text-white text-center">NAMA</th>
                                                        <th colspan="2" class="bg-info text-white text-center">Saldo Awal</th>
                                                        <th colspan="2" class="bg-success text-white text-center">LPB</th>
                                                        <th colspan="2" class="bg-danger text-white text-center">DO</th>
                                                        <th colspan="2" class="bg-success text-white text-center">RETUR</th>
                                                        <th colspan="2" class="bg-info text-white text-center">Sistem</th>
                                                        <th colspan="2" class="bg-success text-white text-center">Fisik</th>
                                                        <th colspan="3" class="align-middle bg-danger text-white text-center">Selisih</th>
                                                        <th rowspan="2" class="align-middle bg-primary text-white text-center">Kordinat</th>
                                                        <th rowspan="2" class="align-middle bg-success text-white text-center">Status</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="bg-primary text-white">Nama Barang</th>
                                                        <th class="bg-primary text-white">Date</th>
                                                        <th class="bg-info text-white">Box</th>
                                                        <th class="bg-info text-white">Pcs</th>
                                                        <th class="bg-success text-white">Box</th>
                                                        <th class="bg-success text-white">Pcs</th>
                                                        <th class="bg-danger text-white">Box</th>
                                                        <th class="bg-danger text-white">Pcs</th>
                                                        <th class="bg-success text-white">Jual</th>
                                                        <th class="bg-success text-white">Beli</th>
                                                        <th class="bg-info text-white">Box</th>
                                                        <th class="bg-info text-white">Pcs</th>
                                                        <th class="bg-success text-white">Box</th>
                                                        <th class="bg-success text-white">Pcs</th>
                                                        <th class="bg-danger text-white">Qty</th>
                                                        <th class="bg-danger text-white">Box</th>
                                                        <th class="bg-danger text-white">Pcs</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($barang_ics_c as $br) : ?>
                                                        <tr>
                                                            <td>
                                                                <a href="<?= base_url('ics/stock_by_kodebr/' . $br->kd)  ?>" target="__blank" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                                            </td>
                                                            <td><?= $br->nama_barang ?></td>
                                                            <td><?= $br->exp_date ?></td>
                                                            <td><?= $br->saldo_awal_box ?></td>
                                                            <td><?= $br->saldo_awal_pcs ?></td>
                                                            <td><?= $br->in_box ?></td>
                                                            <td><?= $br->in_pcs ?></td>
                                                            <td><?= $br->out_box ?></td>
                                                            <td><?= $br->out_pcs ?></td>
                                                            <td><?= $br->qty_rjual ?></td>
                                                            <td><?= $br->qty_rbeli ?></td>
                                                            <td><?= $br->saldo_akhir_box ?></td>
                                                            <td><?= $br->saldo_akhir_pcs ?></td>
                                                            <td><?= $br->fisik_box ?></td>
                                                            <td><?= $br->fisik_pcs ?></td>
                                                            <td><?= $br->qty_selisih ?></td>
                                                            <td><?= $br->selisih_box ?></td>
                                                            <td><?= $br->selisih_pcs ?></td>
                                                            <td style="text-align: center;"><?= $br->kordinat ?></td>
                                                            <?php if ($br->status_kesesuaian == 'KLOP') : ?>
                                                                <td style="text-align: center;">
                                                                    <a href="#" class="btn btn-sm btn-success"><i class="fas fa-check-circle"></i></a>
                                                                </td>
                                                            <?php else : ?>
                                                                <td style="text-align: center;">
                                                                    <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-times-circle"></i></a>
                                                                </td>
                                                            <?php endif; ?>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="chart tab-pane" id="ics_d">
                                            <h3>VIEW STOCK DIFFFRENT D</h3>
                                            <table class="table table-bordered table-hover table-sm table-fixed-header" id="tbics_erp_d">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" class="align-middle bg-info text-white text-center">#</th>
                                                        <th colspan="2" class="bg-primary text-white text-center">NAMA</th>
                                                        <th colspan="2" class="bg-info text-white text-center">Saldo Awal</th>
                                                        <th colspan="2" class="bg-success text-white text-center">LPB</th>
                                                        <th colspan="2" class="bg-danger text-white text-center">DO</th>
                                                        <th colspan="2" class="bg-success text-white text-center">RETUR</th>
                                                        <th colspan="2" class="bg-info text-white text-center">Sistem</th>
                                                        <th colspan="2" class="bg-success text-white text-center">Fisik</th>
                                                        <th colspan="3" class="align-middle bg-danger text-white text-center">Selisih</th>
                                                        <th rowspan="2" class="align-middle bg-primary text-white text-center">Kordinat</th>
                                                        <th rowspan="2" class="align-middle bg-success text-white text-center">Status</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="bg-primary text-white">Nama Barang</th>
                                                        <th class="bg-primary text-white">Date</th>
                                                        <th class="bg-info text-white">Box</th>
                                                        <th class="bg-info text-white">Pcs</th>
                                                        <th class="bg-success text-white">Box</th>
                                                        <th class="bg-success text-white">Pcs</th>
                                                        <th class="bg-danger text-white">Box</th>
                                                        <th class="bg-danger text-white">Pcs</th>
                                                        <th class="bg-success text-white">Jual</th>
                                                        <th class="bg-success text-white">Beli</th>
                                                        <th class="bg-info text-white">Box</th>
                                                        <th class="bg-info text-white">Pcs</th>
                                                        <th class="bg-success text-white">Box</th>
                                                        <th class="bg-success text-white">Pcs</th>
                                                        <th class="bg-danger text-white">Qty</th>
                                                        <th class="bg-danger text-white">Box</th>
                                                        <th class="bg-danger text-white">Pcs</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($barang_ics_d as $br) : ?>
                                                        <tr>
                                                            <td>
                                                                <a href="<?= base_url('ics/stock_by_kodebr/' . $br->kd)  ?>" target="__blank" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                                            </td>
                                                            <td><?= $br->nama_barang ?></td>
                                                            <td><?= $br->exp_date ?></td>
                                                            <td><?= $br->saldo_awal_box ?></td>
                                                            <td><?= $br->saldo_awal_pcs ?></td>
                                                            <td><?= $br->in_box ?></td>
                                                            <td><?= $br->in_pcs ?></td>
                                                            <td><?= $br->out_box ?></td>
                                                            <td><?= $br->out_pcs ?></td>
                                                            <td><?= $br->qty_rjual ?></td>
                                                            <td><?= $br->qty_rbeli ?></td>
                                                            <td><?= $br->saldo_akhir_box ?></td>
                                                            <td><?= $br->saldo_akhir_pcs ?></td>
                                                            <td><?= $br->fisik_box ?></td>
                                                            <td><?= $br->fisik_pcs ?></td>
                                                            <td><?= $br->qty_selisih ?></td>
                                                            <td><?= $br->selisih_box ?></td>
                                                            <td><?= $br->selisih_pcs ?></td>
                                                            <td style="text-align: center;"><?= $br->kordinat ?></td>
                                                            <?php if ($br->status_kesesuaian == 'KLOP') : ?>
                                                                <td style="text-align: center;">
                                                                    <a href="#" class="btn btn-sm btn-success"><i class="fas fa-check-circle"></i></a>
                                                                </td>
                                                            <?php else : ?>
                                                                <td style="text-align: center;">
                                                                    <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-times-circle"></i></a>
                                                                </td>
                                                            <?php endif; ?>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="chart tab-pane" id="ics_e">
                                            <h3>VIEW STOCK DIFFFRENT E</h3>
                                            <table class="table table-bordered table-hover table-sm table-fixed-header" id="tbics_erp_e">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" class="align-middle bg-info text-white text-center">#</th>
                                                        <th colspan="2" class="bg-primary text-white text-center">NAMA</th>
                                                        <th colspan="2" class="bg-info text-white text-center">Saldo Awal</th>
                                                        <th colspan="2" class="bg-success text-white text-center">LPB</th>
                                                        <th colspan="2" class="bg-danger text-white text-center">DO</th>
                                                        <th colspan="2" class="bg-success text-white text-center">RETUR</th>
                                                        <th colspan="2" class="bg-info text-white text-center">Sistem</th>
                                                        <th colspan="2" class="bg-success text-white text-center">Fisik</th>
                                                        <th colspan="3" class="align-middle bg-danger text-white text-center">Selisih</th>
                                                        <th rowspan="2" class="align-middle bg-primary text-white text-center">Kordinat</th>
                                                        <th rowspan="2" class="align-middle bg-success text-white text-center">Status</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="bg-primary text-white">Nama Barang</th>
                                                        <th class="bg-primary text-white">Date</th>
                                                        <th class="bg-info text-white">Box</th>
                                                        <th class="bg-info text-white">Pcs</th>
                                                        <th class="bg-success text-white">Box</th>
                                                        <th class="bg-success text-white">Pcs</th>
                                                        <th class="bg-danger text-white">Box</th>
                                                        <th class="bg-danger text-white">Pcs</th>
                                                        <th class="bg-success text-white">Jual</th>
                                                        <th class="bg-success text-white">Beli</th>
                                                        <th class="bg-info text-white">Box</th>
                                                        <th class="bg-info text-white">Pcs</th>
                                                        <th class="bg-success text-white">Box</th>
                                                        <th class="bg-success text-white">Pcs</th>
                                                        <th class="bg-danger text-white">Qty</th>
                                                        <th class="bg-danger text-white">Box</th>
                                                        <th class="bg-danger text-white">Pcs</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($barang_ics_e as $br) : ?>
                                                        <tr>
                                                            <td>
                                                                <a href="<?= base_url('ics/stock_by_kodebr/' . $br->kd)  ?>" target="__blank" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                                            </td>
                                                            <td><?= $br->nama_barang ?></td>
                                                            <td><?= $br->exp_date ?></td>
                                                            <td><?= $br->saldo_awal_box ?></td>
                                                            <td><?= $br->saldo_awal_pcs ?></td>
                                                            <td><?= $br->in_box ?></td>
                                                            <td><?= $br->in_pcs ?></td>
                                                            <td><?= $br->out_box ?></td>
                                                            <td><?= $br->out_pcs ?></td>
                                                            <td><?= $br->qty_rjual ?></td>
                                                            <td><?= $br->qty_rbeli ?></td>
                                                            <td><?= $br->saldo_akhir_box ?></td>
                                                            <td><?= $br->saldo_akhir_pcs ?></td>
                                                            <td><?= $br->fisik_box ?></td>
                                                            <td><?= $br->fisik_pcs ?></td>
                                                            <td><?= $br->qty_selisih ?></td>
                                                            <td><?= $br->selisih_box ?></td>
                                                            <td><?= $br->selisih_pcs ?></td>
                                                            <td style="text-align: center;"><?= $br->kordinat ?></td>
                                                            <?php if ($br->status_kesesuaian == 'KLOP') : ?>
                                                                <td style="text-align: center;">
                                                                    <a href="#" class="btn btn-sm btn-success"><i class="fas fa-check-circle"></i></a>
                                                                </td>
                                                            <?php else : ?>
                                                                <td style="text-align: center;">
                                                                    <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-times-circle"></i></a>
                                                                </td>
                                                            <?php endif; ?>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="chart tab-pane" id="ics_0">
                                            <h3>VIEW STOCK DIFFFRENT 0</h3>
                                            <table class="table table-bordered table-hover table-sm table-fixed-header" id="tbics_erp_0">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" class="align-middle bg-info text-white text-center">#</th>
                                                        <th colspan="2" class="bg-primary text-white text-center">NAMA</th>
                                                        <th colspan="2" class="bg-info text-white text-center">Saldo Awal</th>
                                                        <th colspan="2" class="bg-success text-white text-center">LPB</th>
                                                        <th colspan="2" class="bg-danger text-white text-center">DO</th>
                                                        <th colspan="2" class="bg-success text-white text-center">RETUR</th>
                                                        <th colspan="2" class="bg-info text-white text-center">Sistem</th>
                                                        <th colspan="2" class="bg-success text-white text-center">Fisik</th>
                                                        <th colspan="3" class="align-middle bg-danger text-white text-center">Selisih</th>
                                                        <th rowspan="2" class="align-middle bg-primary text-white text-center">Kordinat</th>
                                                        <th rowspan="2" class="align-middle bg-success text-white text-center">Status</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="bg-primary text-white">Nama Barang</th>
                                                        <th class="bg-primary text-white">Date</th>
                                                        <th class="bg-info text-white">Box</th>
                                                        <th class="bg-info text-white">Pcs</th>
                                                        <th class="bg-success text-white">Box</th>
                                                        <th class="bg-success text-white">Pcs</th>
                                                        <th class="bg-danger text-white">Box</th>
                                                        <th class="bg-danger text-white">Pcs</th>
                                                        <th class="bg-success text-white">Jual</th>
                                                        <th class="bg-success text-white">Beli</th>
                                                        <th class="bg-info text-white">Box</th>
                                                        <th class="bg-info text-white">Pcs</th>
                                                        <th class="bg-success text-white">Box</th>
                                                        <th class="bg-success text-white">Pcs</th>
                                                        <th class="bg-danger text-white">Qty</th>
                                                        <th class="bg-danger text-white">Box</th>
                                                        <th class="bg-danger text-white">Pcs</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($barang_ics_0 as $br) : ?>
                                                        <tr>
                                                            <td>
                                                                <a href="<?= base_url('ics/stock_by_kodebr/' . $br->kd)  ?>" target="__blank" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                                            </td>
                                                            <td><?= $br->nama_barang ?></td>
                                                            <td><?= $br->exp_date ?></td>
                                                            <td><?= $br->saldo_awal_box ?></td>
                                                            <td><?= $br->saldo_awal_pcs ?></td>
                                                            <td><?= $br->in_box ?></td>
                                                            <td><?= $br->in_pcs ?></td>
                                                            <td><?= $br->out_box ?></td>
                                                            <td><?= $br->out_pcs ?></td>
                                                            <td><?= $br->qty_rjual ?></td>
                                                            <td><?= $br->qty_rbeli ?></td>
                                                            <td><?= $br->saldo_akhir_box ?></td>
                                                            <td><?= $br->saldo_akhir_pcs ?></td>
                                                            <td><?= $br->fisik_box ?></td>
                                                            <td><?= $br->fisik_pcs ?></td>
                                                            <td><?= $br->qty_selisih ?></td>
                                                            <td><?= $br->selisih_box ?></td>
                                                            <td><?= $br->selisih_pcs ?></td>
                                                            <td style="text-align: center;"><?= $br->kordinat ?></td>
                                                            <?php if ($br->status_kesesuaian == 'KLOP') : ?>
                                                                <td style="text-align: center;">
                                                                    <a href="#" class="btn btn-sm btn-success"><i class="fas fa-check-circle"></i></a>
                                                                </td>
                                                            <?php else : ?>
                                                                <td style="text-align: center;">
                                                                    <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-times-circle"></i></a>
                                                                </td>
                                                            <?php endif; ?>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php elseif ($akses == 'ics') : ?>
                                <div class="card-header ui-sortable-handle " style="cursor: move">
                                    <h3 class="card-title">
                                        Stock Diffrent
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <?php if ($pic == 'Admin ICS 1') : ?>
                                        <table class="table table-bordered table-hover table-sm table-fixed-header" id="tbics_erp_a1">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" class="align-middle bg-info text-white text-center">#</th>
                                                    <th colspan="2" class="bg-primary text-white text-center">NAMA</th>
                                                    <th colspan="2" class="bg-info text-white text-center">Saldo Awal</th>
                                                    <th colspan="2" class="bg-success text-white text-center">LPB</th>
                                                    <th colspan="2" class="bg-danger text-white text-center">DO</th>
                                                    <th colspan="2" class="bg-info text-white text-center">Sistem</th>
                                                    <th colspan="2" class="bg-success text-white text-center">Fisik</th>
                                                    <th rowspan="2" class="align-middle bg-info text-white text-center">Selisih</th>
                                                    <th rowspan="2" class="align-middle bg-primary text-white text-center">Kordinat</th>
                                                    <th rowspan="2" class="align-middle bg-success text-white text-center">Status</th>
                                                </tr>
                                                <tr>
                                                    <th class="bg-primary text-white">Nama Barang</th>
                                                    <th class="bg-primary text-white">Date</th>
                                                    <th class="bg-info text-white">Box</th>
                                                    <th class="bg-info text-white">Pcs</th>
                                                    <th class="bg-success text-white">Box</th>
                                                    <th class="bg-success text-white">Pcs</th>
                                                    <th class="bg-danger text-white">Box</th>
                                                    <th class="bg-danger text-white">Pcs</th>
                                                    <th class="bg-info text-white">Box</th>
                                                    <th class="bg-info text-white">Pcs</th>
                                                    <th class="bg-success text-white">Box</th>
                                                    <th class="bg-success text-white">Pcs</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($barang_ics_a as $br) : ?>
                                                    <tr>
                                                        <td>
                                                            <a href="<?= base_url('ics/stock_by_kodebr/' . $br->kd)  ?>" target="__blank" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                                        </td>
                                                        <td><?= $br->nama_barang ?></td>
                                                        <td><?= $br->exp_date ?></td>
                                                        <td><?= $br->saldo_awal_box ?></td>
                                                        <td><?= $br->saldo_awal_pcs ?></td>
                                                        <td><?= $br->in_box ?></td>
                                                        <td><?= $br->in_pcs ?></td>
                                                        <td><?= $br->out_box ?></td>
                                                        <td><?= $br->out_pcs ?></td>
                                                        <td><?= $br->saldo_akhir_box ?></td>
                                                        <td><?= $br->saldo_akhir_pcs ?></td>
                                                        <td><?= $br->fisik_box ?></td>
                                                        <td><?= $br->fisik_pcs ?></td>
                                                        <td><?= $br->qty_selisih ?></td>
                                                        <td style="text-align: center;"><?= $br->kordinat ?></td>
                                                        <?php if ($br->status_kesesuaian == 'KLOP') : ?>
                                                            <td style="text-align: center;">
                                                                <a href="#" class="btn btn-sm btn-success"><i class="fas fa-check-circle"></i></a>
                                                            </td>
                                                        <?php else : ?>
                                                            <td style="text-align: center;">
                                                                <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-times-circle"></i></a>
                                                            </td>
                                                        <?php endif; ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php elseif ($pic == 'Admin ICS 2') : ?>
                                        <table class="table table-bordered table-hover table-sm table-fixed-header" id="tbics_erp_b1">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" class="align-middle bg-info text-white text-center">#</th>
                                                    <th colspan="2" class="bg-primary text-white text-center">NAMA</th>
                                                    <th colspan="2" class="bg-info text-white text-center">Saldo Awal</th>
                                                    <th colspan="2" class="bg-success text-white text-center">LPB</th>
                                                    <th colspan="2" class="bg-danger text-white text-center">DO</th>
                                                    <th colspan="2" class="bg-info text-white text-center">Sistem</th>
                                                    <th colspan="2" class="bg-success text-white text-center">Fisik</th>
                                                    <th rowspan="2" class="align-middle bg-info text-white text-center">Selisih</th>
                                                    <th rowspan="2" class="align-middle bg-primary text-white text-center">Kordinat</th>
                                                    <th rowspan="2" class="align-middle bg-success text-white text-center">Status</th>
                                                </tr>
                                                <tr>
                                                    <th class="bg-primary text-white">Nama Barang</th>
                                                    <th class="bg-primary text-white">Date</th>
                                                    <th class="bg-info text-white">Box</th>
                                                    <th class="bg-info text-white">Pcs</th>
                                                    <th class="bg-success text-white">Box</th>
                                                    <th class="bg-success text-white">Pcs</th>
                                                    <th class="bg-danger text-white">Box</th>
                                                    <th class="bg-danger text-white">Pcs</th>
                                                    <th class="bg-info text-white">Box</th>
                                                    <th class="bg-info text-white">Pcs</th>
                                                    <th class="bg-success text-white">Box</th>
                                                    <th class="bg-success text-white">Pcs</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($barang_ics_b as $br) : ?>
                                                    <tr>
                                                        <td>
                                                            <a href="<?= base_url('ics/stock_by_kodebr/' . $br->kd)  ?>" target="__blank" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                                        </td>
                                                        <td><?= $br->nama_barang ?></td>
                                                        <td><?= $br->exp_date ?></td>
                                                        <td><?= $br->saldo_awal_box ?></td>
                                                        <td><?= $br->saldo_awal_pcs ?></td>
                                                        <td><?= $br->in_box ?></td>
                                                        <td><?= $br->in_pcs ?></td>
                                                        <td><?= $br->out_box ?></td>
                                                        <td><?= $br->out_pcs ?></td>
                                                        <td><?= $br->saldo_akhir_box ?></td>
                                                        <td><?= $br->saldo_akhir_pcs ?></td>
                                                        <td><?= $br->fisik_box ?></td>
                                                        <td><?= $br->fisik_pcs ?></td>
                                                        <td><?= $br->qty_selisih ?></td>
                                                        <td style="text-align: center;"><?= $br->kordinat ?></td>
                                                        <?php if ($br->status_kesesuaian == 'KLOP') : ?>
                                                            <td style="text-align: center;">
                                                                <a href="#" class="btn btn-sm btn-success"><i class="fas fa-check-circle"></i></a>
                                                            </td>
                                                        <?php else : ?>
                                                            <td style="text-align: center;">
                                                                <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-times-circle"></i></a>
                                                            </td>
                                                        <?php endif; ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php elseif ($pic == 'Admin ICS 3') : ?>
                                        <table class="table table-bordered table-hover table-sm table-fixed-header" id="tbics_erp_c1">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" class="align-middle bg-info text-white text-center">#</th>
                                                    <th colspan="2" class="bg-primary text-white text-center">NAMA</th>
                                                    <th colspan="2" class="bg-info text-white text-center">Saldo Awal</th>
                                                    <th colspan="2" class="bg-success text-white text-center">LPB</th>
                                                    <th colspan="2" class="bg-danger text-white text-center">DO</th>
                                                    <th colspan="2" class="bg-info text-white text-center">Sistem</th>
                                                    <th colspan="2" class="bg-success text-white text-center">Fisik</th>
                                                    <th rowspan="2" class="align-middle bg-info text-white text-center">Selisih</th>
                                                    <th rowspan="2" class="align-middle bg-primary text-white text-center">Kordinat</th>
                                                    <th rowspan="2" class="align-middle bg-success text-white text-center">Status</th>
                                                </tr>
                                                <tr>
                                                    <th class="bg-primary text-white">Nama Barang</th>
                                                    <th class="bg-primary text-white">Date</th>
                                                    <th class="bg-info text-white">Box</th>
                                                    <th class="bg-info text-white">Pcs</th>
                                                    <th class="bg-success text-white">Box</th>
                                                    <th class="bg-success text-white">Pcs</th>
                                                    <th class="bg-danger text-white">Box</th>
                                                    <th class="bg-danger text-white">Pcs</th>
                                                    <th class="bg-info text-white">Box</th>
                                                    <th class="bg-info text-white">Pcs</th>
                                                    <th class="bg-success text-white">Box</th>
                                                    <th class="bg-success text-white">Pcs</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($barang_ics_c as $br) : ?>
                                                    <tr>
                                                        <td>
                                                            <a href="<?= base_url('ics/stock_by_kodebr/' . $br->kd)  ?>" target="__blank" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                                        </td>
                                                        <td><?= $br->nama_barang ?></td>
                                                        <td><?= $br->exp_date ?></td>
                                                        <td><?= $br->saldo_awal_box ?></td>
                                                        <td><?= $br->saldo_awal_pcs ?></td>
                                                        <td><?= $br->in_box ?></td>
                                                        <td><?= $br->in_pcs ?></td>
                                                        <td><?= $br->out_box ?></td>
                                                        <td><?= $br->out_pcs ?></td>
                                                        <td><?= $br->saldo_akhir_box ?></td>
                                                        <td><?= $br->saldo_akhir_pcs ?></td>
                                                        <td><?= $br->fisik_box ?></td>
                                                        <td><?= $br->fisik_pcs ?></td>
                                                        <td><?= $br->qty_selisih ?></td>
                                                        <td style="text-align: center;"><?= $br->kordinat ?></td>
                                                        <?php if ($br->status_kesesuaian == 'KLOP') : ?>
                                                            <td style="text-align: center;">
                                                                <a href="#" class="btn btn-sm btn-success"><i class="fas fa-check-circle"></i></a>
                                                            </td>
                                                        <?php else : ?>
                                                            <td style="text-align: center;">
                                                                <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-times-circle"></i></a>
                                                            </td>
                                                        <?php endif; ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php elseif ($pic == 'Admin ICS 4') : ?>
                                        <table class="table table-bordered table-hover table-sm table-fixed-header" id="tbics_erp_d1">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" class="align-middle bg-info text-white text-center">#</th>
                                                    <th colspan="2" class="bg-primary text-white text-center">NAMA</th>
                                                    <th colspan="2" class="bg-info text-white text-center">Saldo Awal</th>
                                                    <th colspan="2" class="bg-success text-white text-center">LPB</th>
                                                    <th colspan="2" class="bg-danger text-white text-center">DO</th>
                                                    <th colspan="2" class="bg-info text-white text-center">Sistem</th>
                                                    <th colspan="2" class="bg-success text-white text-center">Fisik</th>
                                                    <th rowspan="2" class="align-middle bg-info text-white text-center">Selisih</th>
                                                    <th rowspan="2" class="align-middle bg-success text-white text-center">Status</th>
                                                </tr>
                                                <tr>
                                                    <th class="bg-primary text-white">Nama Barang</th>
                                                    <th class="bg-primary text-white">Date</th>
                                                    <th class="bg-info text-white">Box</th>
                                                    <th class="bg-info text-white">Pcs</th>
                                                    <th class="bg-success text-white">Box</th>
                                                    <th class="bg-success text-white">Pcs</th>
                                                    <th class="bg-danger text-white">Box</th>
                                                    <th class="bg-danger text-white">Pcs</th>
                                                    <th class="bg-info text-white">Box</th>
                                                    <th class="bg-info text-white">Pcs</th>
                                                    <th class="bg-success text-white">Box</th>
                                                    <th class="bg-success text-white">Pcs</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php foreach ($barang_ics_d as $br) : ?>
                                                    <tr>
                                                        <td>
                                                            <a href="<?= base_url('ics/stock_by_kodebr/' . $br->kd)  ?>" target="__blank" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                                        </td>
                                                        <td><?= $br->nama_barang ?></td>
                                                        <td><?= $br->exp_date ?></td>
                                                        <td><?= $br->saldo_awal_box ?></td>
                                                        <td><?= $br->saldo_awal_pcs ?></td>
                                                        <td><?= $br->in_box ?></td>
                                                        <td><?= $br->in_pcs ?></td>
                                                        <td><?= $br->out_box ?></td>
                                                        <td><?= $br->out_pcs ?></td>
                                                        <td><?= $br->saldo_akhir_box ?></td>
                                                        <td><?= $br->saldo_akhir_pcs ?></td>
                                                        <td><?= $br->fisik_box ?></td>
                                                        <td><?= $br->fisik_pcs ?></td>
                                                        <td><?= $br->qty_selisih ?></td>
                                                        <td style="text-align: center;"><?= $br->kordinat ?></td>
                                                        <?php if ($br->status_kesesuaian == 'KLOP') : ?>
                                                            <td style="text-align: center;">
                                                                <a href="#" class="btn btn-sm btn-success"><i class="fas fa-check-circle"></i></a>
                                                            </td>
                                                        <?php else : ?>
                                                            <td style="text-align: center;">
                                                                <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-times-circle"></i></a>
                                                            </td>
                                                        <?php endif; ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php elseif ($pic == 'Admin ICS 5') : ?>
                                        <table class="table table-bordered table-hover table-sm table-fixed-header" id="tbics_erp_e1">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" class="align-middle bg-info text-white text-center">#</th>
                                                    <th colspan="2" class="bg-primary text-white text-center">NAMA</th>
                                                    <th colspan="2" class="bg-info text-white text-center">Saldo Awal</th>
                                                    <th colspan="2" class="bg-success text-white text-center">LPB</th>
                                                    <th colspan="2" class="bg-danger text-white text-center">DO</th>
                                                    <th colspan="2" class="bg-info text-white text-center">Sistem</th>
                                                    <th colspan="2" class="bg-success text-white text-center">Fisik</th>
                                                    <th rowspan="2" class="align-middle bg-info text-white text-center">Selisih</th>
                                                    <th rowspan="2" class="align-middle bg-success text-white text-center">Status</th>
                                                </tr>
                                                <tr>
                                                    <th class="bg-primary text-white">Nama Barang</th>
                                                    <th class="bg-primary text-white">Date</th>
                                                    <th class="bg-info text-white">Box</th>
                                                    <th class="bg-info text-white">Pcs</th>
                                                    <th class="bg-success text-white">Box</th>
                                                    <th class="bg-success text-white">Pcs</th>
                                                    <th class="bg-danger text-white">Box</th>
                                                    <th class="bg-danger text-white">Pcs</th>
                                                    <th class="bg-info text-white">Box</th>
                                                    <th class="bg-info text-white">Pcs</th>
                                                    <th class="bg-success text-white">Box</th>
                                                    <th class="bg-success text-white">Pcs</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php foreach ($barang_ics_e as $br) : ?>
                                                    <tr>
                                                        <td>
                                                            <a href="<?= base_url('ics/stock_by_kodebr/' . $br->kd)  ?>" target="__blank" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                                        </td>
                                                        <td><?= $br->nama_barang ?></td>
                                                        <td><?= $br->exp_date ?></td>
                                                        <td><?= $br->saldo_awal_box ?></td>
                                                        <td><?= $br->saldo_awal_pcs ?></td>
                                                        <td><?= $br->in_box ?></td>
                                                        <td><?= $br->in_pcs ?></td>
                                                        <td><?= $br->out_box ?></td>
                                                        <td><?= $br->out_pcs ?></td>
                                                        <td><?= $br->saldo_akhir_box ?></td>
                                                        <td><?= $br->saldo_akhir_pcs ?></td>
                                                        <td><?= $br->fisik_box ?></td>
                                                        <td><?= $br->fisik_pcs ?></td>
                                                        <td><?= $br->qty_selisih ?></td>
                                                        <td style="text-align: center;"><?= $br->kordinat ?></td>
                                                        <?php if ($br->status_kesesuaian == 'KLOP') : ?>
                                                            <td style="text-align: center;">
                                                                <a href="#" class="btn btn-sm btn-success"><i class="fas fa-check-circle"></i></a>
                                                            </td>
                                                        <?php else : ?>
                                                            <td style="text-align: center;">
                                                                <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-times-circle"></i></a>
                                                            </td>
                                                        <?php endif; ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>
                <?php endif; ?>
            </div>

            <div class="modal fade" id="modalAddOpname" tabindex="-1" role="dialog" aria-labelledby="modalAddOpnameLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form action="<?= base_url('ics/sv_opname') ?>" method="post">
                        <div class="modal-content">
                            <div class="modal-header bg-success">
                                <h5 class="modal-title" id="modalAddOpnameLabel"><i class="fas fa-box"></i> Input Data Opname</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" id="modal_id">
                                <input type="hidden" name="dimensi" id="modal_dimensi">
                                <input type="hidden" name="action" id="action_id" value="diffrent">
                                <div class="form-group">
                                    <label for="nama_barang">Nama Barang</label>
                                    <input type="text" name="nama_barang" id="modal_nama_barang" class="form-control" readonly required>
                                </div>
                                <div class="form-group">
                                    <label for="exp_date">Expired Date</label>
                                    <input type="text" name="exp_date" id="modal_exp_date" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="qty_box">Qty Box</label>
                                    <input type="number" name="qty_box" id="modal_qty_box" class="form-control" placeholder="0">
                                </div>
                                <div class="form-group">
                                    <label for="qty_pcs">Qty Pcs</label>
                                    <input type="number" name="qty_pcs" id="modal_qty_pcs" class="form-control" placeholder="0">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2022 <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0
            </div>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <script>
        $(document).ready(function() {
            $('.btn-open-opname').on('click', function() {
                const rowId = $(this).data('id');

                $.ajax({
                    url: "<?= base_url('ics/get_detail_barang') ?>",
                    method: "POST",
                    data: {
                        id: rowId
                    },
                    dataType: "json",
                    success: function(data) {
                        $('#modal_id').val(data.id);
                        $('#modal_nama_barang').val(data.nama_barang);
                        $('#modal_exp_date').val(data.exp_date);
                        $('#modal_dimensi').val(data.dimensi);
                        $('#modal_qty_box').val('');
                        $('#modal_qty_pcs').val('');
                        $('#modalAddOpname').modal('show');
                    },
                    error: function() {
                        alert('Gagal mengambil data barang.');
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.editable').on('blur', function() {
                var data_id = $(this).data('id').split('|');
                var nama_barang = data_id[0];
                var exp_date = data_id[1];
                var field = $(this).data('field');
                var value = $(this).text();

                $.ajax({
                    url: "<?= base_url('ics/updateinline') ?>",
                    method: "POST",
                    data: {
                        nama_barang: nama_barang,
                        exp_date: exp_date,
                        field: field,
                        value: value
                    },
                    success: function(response) {
                        console.log(response);
                    },
                    error: function() {
                        alert('Gagal menyimpan data');
                    }
                });
            });
        });
    </script>

    <script>
        $('#btnSimpanOpname').on('click', function() {
            if (confirm("Apakah Anda yakin ingin menyimpan semua data ke Opname hari ini? Data akan disalin dari tb_ics.")) {
                $.ajax({
                    url: "<?= base_url('ics/simpan_opname') ?>",
                    type: "POST",
                    dataType: "json",
                    success: function(response) {
                        if (response.status === 'success') {
                            alert("Berhasil menyimpan data opname.");
                            location.reload();
                        } else {
                            alert("Gagal menyimpan data opname: " + response.message);
                        }
                    },
                    error: function() {
                        alert("Terjadi kesalahan saat menghubungi server.");
                    }
                });
            }
        });
    </script>