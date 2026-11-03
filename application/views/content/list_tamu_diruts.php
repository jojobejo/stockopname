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
                    <span class="brand-text font-weight-bold">PT.Karisma Indoagro Universal</span>
                </a>

                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="<?= base_url('') ?>">
                                <i class="fas fa-home"></i>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="<?= base_url('') ?>">
                                <i class="fas fa-sign-out-alt"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <?php $this->load->view('content/schedule/modalschedule.php') ?>

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
                            <div class="row">
                                <h3 class="m-0"> List Tamu Direktur</h3>
                            </div>
                        </div>
                        <div class="card-body">

                            <a href="#" class="btn btn-sm btn-info btn-block mb-2" data-toggle="modal" data-target="#addschedule"><i class="fas fa-plus-circle"></i></a>

                            <table id="tb_schedule" class="table table-bordered table-striped">
                                <thead style="background-color: #212529; color:white;">
                                    <tr style="text-align: center;">
                                        <td>Tanggal</td>
                                        <td>Jam</td>
                                        <td>Nama</td>
                                        <td>Instansi</td>
                                        <td>Tujuan</td>
                                        <td>Keterangan</td>
                                        <td>Status</td>
                                        <td>#</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tamu as $t) :
                                        $status     = "";
                                        if ($t->status == '0') {
                                            $status = 'NO STATUS';
                                        } else if ($t->status == '1') {
                                            $status = '1';
                                        } else if ($t->status == '2') {
                                            $status = '2';
                                        } else if ($t->status == '3') {
                                            $status = '3';
                                        } else if ($t->status == '4') {
                                            $status = '4';
                                        }
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
                                            <td>
                                                <?php if ($status == '1') : ?>
                                                    <a href="#" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editedschedule<?= $t->id ?>"><i class="fas fa-pencil-alt"></i></a>
                                                    <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#cancelschedule<?= $t->id ?>"><i class="fas fa-times-circle"></i></a>
                                                    <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#reschedule<?= $t->id ?>"><i class="fas fa-sync-alt"></i></a>
                                                    <a href="#" class="btn btn-sm btn-success" data-toggle="modal" data-target="#scheduledone<?= $t->id ?>"><i class="fas fa-check-circle"></i></a>
                                                <?php elseif ($status == '2') : ?>
                                                    <a href="#" class="btn btn-sm btn-danger btn-block" data-toggle="modal" data-target="#deleteschedule<?= $t->id ?>"><i class="fas fa-trash-alt"></i></a>
                                                <?php elseif ($status == '3') : ?>
                                                    <a href="#" class="btn btn-sm btn-success btn-block" data-toggle="modal" data-target="#archived<?= $t->id ?>"><i class="fas fa-check-circle"></i></a>
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