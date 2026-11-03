<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="content-header">
                <section class="content">

                    <div class="row">
                        <div class="col-auto">
                            <a href="<?= base_url('admstocktracking') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-home"></i></a>
                        </div>
                        <div class="col-auto">
                            <!-- <a href="#" id="btn-export-compare-allbarang" class="btn btn-md btn-primary w-100 mb-3"><i class="far fa-file-excel"></i> Export CSV</a> -->
                        </div>
                    </div>

                    <div class="card card-primary mt-2 mb-2">
                        <div class="card-header">
                            <h5 class="card-title mt-2">Compare Wilayah</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-sm" id="tb_wilayah ">
                                <thead>
                                    <tr>
                                        <th>Wilayah</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($wilayah as $w) : ?>
                                        <tr>
                                            <td><?= $w->wilayah ?></td>
                                            <td><a href="<?= base_url('compare_wilayah/') . $w->id ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                        </div>
                    </div>

                    <div class="card card-primary mt-2 mb-5">
                        <div class="card-header">
                            <h5 class="card-title mt-2">Compare Tim - Allbarang</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-sm" id="compare_allbarang">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">#</th>
                                        <th>Nama Barang</th>
                                        <th style="text-align: center;">QTY 1</th>
                                        <th style="text-align: center;">QTY 2</th>
                                        <th style="text-align: center;">SALDO BUKU </th>
                                        <th style="text-align: center;">PENDING</th>
                                        <th style="text-align: center;">SUPPLIER</th>
                                        <th style="text-align: center;">QTY</th>
                                        <th style="text-align: center;">SELISIH QTY 1</th>
                                        <th style="text-align: center;">SELISIH QTY 2</th>
                                        <th style="text-align: center;">STATUS TIM 1</th>
                                        <th style="text-align: center;">STATUS TIM 2</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($allbarang as $i => $rowall) : ?>
                                        <tr>
                                            <td style="text-align: center;">
                                                <a href="<?= base_url('detailtrack/' . $rowall->kd_barang . '/allbarang') ?>" class="btn btn-sm btn-info" title="Detail Tracking">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                            <td><?= $rowall->nama_barang ?></td>
                                            <td style="text-align: center;"><?= $rowall->qty_fisik_tim1 ?></td>
                                            <td style="text-align: center;"><?= $rowall->qty_fisik_tim2 ?></td>
                                            <td style="text-align: center;"><?= $rowall->qty_zahir ?></td>
                                            <td style="text-align: center;"><?= $rowall->qty_pending ?></td>
                                            <td style="text-align: center;"><?= $rowall->qty_supp ?></td>
                                            <td style="text-align: center;"><?= $rowall->qty_sistem_final ?></td>
                                            <td style="text-align: center;"><?= $rowall->selisih_qty_tim1 ?></td>
                                            <td style="text-align: center;"><?= $rowall->selisih_qty_tim2 ?></td>
                                            <td style="text-align: center;">
                                                <?= ($rowall->status_tim1 == 'MATCH') ? '<span class="badge badge-success w-70">MATCH</span>' : '<span class="badge badge-danger w-70">NOTMATCH</span>'; ?>
                                            </td>
                                            <td style="text-align: center;">
                                                <?= ($rowall->status_tim2 == 'MATCH') ? '<span class="badge badge-success w-70">MATCH</span>' : '<span class="badge badge-danger w-70">NOTMATCH</span>'; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>

                            </table>
                        </div>
                    </div>

                    <div class="card card-primary">
                        <div class="card-header">
                            <h5 class="card-title">Compare Tim - Expired Date</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-sm" id="compare_expired_date">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">#</th>
                                        <th>Nama Barang</th>
                                        <th>Expired Date</th>
                                        <th style="text-align: center;">QTY 1</th>
                                        <th style="text-align: center;">QTY 2</th>
                                        <th style="text-align: center;">SALDO BUKU</th>
                                        <th style="text-align: center;">PENDING</th>
                                        <th style="text-align: center;">SUPPLIER</th>
                                        <th style="text-align: center;">QTY</th>
                                        <th style="text-align: center;">SELISIH TIM 1</th>
                                        <th style="text-align: center;">SELISIH TIM 2</th>
                                        <th style="text-align: center;">STATUS TIM 1</th>
                                        <th style="text-align: center;">STATUS TIM 2</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($expired_date as $row) : ?>
                                        <tr>
                                            <td style="text-align: center;">
                                                <a href="<?= base_url('detailtrack/' . $row->kd_barang . '/allbarang') ?>" class="btn btn-sm btn-info" title="Detail Tracking">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                            <td><?= $row->nama_barang ?></td>
                                            <td><?= $row->exp_date ?></td>
                                            <td style="text-align: center;"><?= $row->qty_fisik_tim1 ?></td>
                                            <td style="text-align: center;"><?= $row->qty_fisik_tim2 ?></td>
                                            <td style="text-align: center;"><?= $row->qty_zahir ?></td>
                                            <td style="text-align: center;"><?= $row->qty_pending ?></td>
                                            <td style="text-align: center;"><?= $row->qty_supp ?></td>
                                            <td style="text-align: center;"><?= $row->qty_sistem ?></td>
                                            <td style="text-align: center;"><?= $row->selisih_tim1 ?></td>
                                            <td style="text-align: center;"><?= $row->selisih_tim2 ?></td>
                                            <td style="text-align: center;">
                                                <?= ($row->status_tim1 == 'MATCH') ? '<span class="badge badge-success">MATCH</span>' : '<span class="badge badge-danger">NOTMATCH</span>'; ?>
                                            </td>
                                            <td style="text-align: center;">
                                                <?= ($row->status_tim2 == 'MATCH') ? '<span class="badge badge-success">MATCH</span>' : '<span class="badge badge-danger">NOTMATCH</span>'; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>

                            </table>
                        </div>
                    </div>

                </section>
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
        $('#btn-export-compare-allbarang').on('click', function() {
            window.location.href = "<?= site_url('export_compare_allbarang') ?>";
        });
    </script>