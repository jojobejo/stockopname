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
                            <a href="<?= base_url('exportcsv_track_opname_exp') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="far fa-file-excel"></i> Export CSV</a>
                        </div>
                    </div>

                    <div class="card mt-2 mb-5">
                        <div class="card-body">
                            <h5 class="mt-2">Compare Tim - Allbarang</h5>
                            <table class="table table-bordered table-sm" id="tb_dash_allbarang">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>QTY Fisik 1</th>
                                        <th>QTY Fisik 2</th>
                                        <th>QTY Zahir</th>
                                        <th>Status TIM 1</th>
                                        <th>Status TIM 2</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php foreach ($allbarang as $rowall) : ?>
                                            <td><?= $rowall->nama_barang ?></td>
                                            <td><?= $rowall->qty_fisik_tim1 ?></td>
                                            <td><?= $rowall->qty_fisik_tim2 ?></td>
                                            <td><?= $rowall->qty_sistem ?></td>
                                            <td><?= $rowall->status_tim1 ?></td>
                                            <td><?= $rowall->status_tim2 ?></td>
                                        <?php endforeach ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h5 class="mt-2">Compare Tim - Expired Date</h5>
                            <table class="table table-bordered table-sm" id="tb_dash_allbarang">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Expired Date</th>
                                        <th>QTY Fisik 1</th>
                                        <th>QTY Fisik 2</th>
                                        <th>QTY Zahir</th>
                                        <th>Status TIM 1</th>
                                        <th>Status TIM 2</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($expired_date as $row) : ?>
                                        <tr>
                                            <td><?= $row->nama_barang ?></td>
                                            <td><?= $row->exp_date ?></td>
                                            <td><?= $row->qty_fisik_tim1 ?></td>
                                            <td><?= $row->qty_fisik_tim2 ?></td>
                                            <td><?= $row->qty_sistem ?></td>
                                            <td><?= $row->status_tim1 ?></td>
                                            <td><?= $row->status_tim2 ?></td>
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