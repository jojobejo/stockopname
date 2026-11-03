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

                    <div class="card card-primary mt-2 mb-5">
                        <div class="card-header">
                            <h5 class="card-title mt-2">Data Stock Barang Pending</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-sm" id="dt_pending">
                                <thead>
                                    <tr>
                                        <th>Kode Faktur</th>
                                        <th>Nama Barang</th>
                                        <th>Expired Date</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pending as $p) : ?>
                                        <tr>
                                            <td><?= $p->kd_faktur ?></td>
                                            <td><?= $p->nama_barang ?></td>
                                            <td><?= $p->exp_date ?></td>
                                            <td><?= $p->qty ?></td>
                                        </tr>
                                    <?php endforeach; ?>
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