<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>
        <!-- Content Wrapper. Contains page content -->
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
            <div class="container">
                <a href="../../index3.html" class="navbar-brand">
                    <img src="<?= base_url('assets/images/Karisma.png') ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="brand-text font-weight-light">PT.Karisma Indoagro Universal</span>
                </a>
            </div>
        </nav>

        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">

                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>

            <div class="content">
                <div class="container">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="m-0"> List Tamu Direktur</h3>
                        </div>
                        <div class="card-body">
                            <table id="tbtamu" class="table table-bordered table-striped">
                                <thead style="background-color: #212529; color:white;">
                                    <tr style="text-align: center;">
                                        <td>Tanggal</td>
                                        <td>Jam</td>
                                        <td>Nama</td>
                                        <td>Instansi</td>
                                        <td>Tujuan</td>
                                        <td>Keterangan</td>
                                        <td>Status</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tamu as $t) :
                                        $status     = $t->status;
                                    ?>
                                        <tr style="text-align: center;">
                                            <td><?= format_indo($t->tanggal) ?></td>
                                            <td><?= $t->jam ?></td>
                                            <td><?= $t->pic ?></td>
                                            <td><?= $t->suplier ?></td>
                                            <td><?= $t->tujuan ?></td>
                                            <td><?= $t->keterangan ?></td>
                                            <td>
                                                <?php if ($status == '1') : ?>
                                                    <a href="#" class="btn btn-warning btn-sm btn-block"><i class="fas fa-business-time"></i></a>
                                                <?php elseif ($status == '2') : ?>
                                                    <a href="#" class="btn btn-danger btn-sm btn-block"><i class="fas fa-times-circle"></i></a>
                                                <?php elseif ($status == '3') : ?>
                                                    <a href="#" class="btn btn-success btn-sm btn-block"><i class="fas fa-check-circle"></i></a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
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