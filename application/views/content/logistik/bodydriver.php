<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>
        <?php $this->load->view('content/logistik/modallogistik') ?>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <a href="<?= base_url('truckoprational') ?>"><i class="fas fa-play fa-flip-horizontal mr-2"></i></a>
                                <h3 class="card-title">Driver Listing</h3>
                            </div>
                        </div>
                        <div class="ml-2">
                            <div class="row">
                                <button type="button" class="btn btn-primary m-2 ml-3" data-toggle="modal" data-target="#adddriver">
                                    <i class="fas fa-plus"></i>
                                    Tambah Driver Baru
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="tb_driver" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Driver</th>
                                        <th>Status</th>
                                        <th style="width: 10px;">No.Urut Hari Ini</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($driver as $d) : ?>
                                        <tr>
                                            <th><?= $d->nama_driver ?></th>
                                            <th><?= $d->status ?></th>
                                            <th style="width: 5px;"><?= $d->no_urut_hr_i ?></th>
                                            <th>
                                                <a href="#" class="btn btn-primary btn-sm " data-toggle="modal" data-target="#editdriver<?= $d->id ?>">
                                                    <i class="fa fa-solid fa-pencil-alt"></i>
                                                </a>
                                                <a href="#" class="btn btn-danger btn-sm ml-2 " data-toggle="modal" data-target="#hapusdriver<?= $d->id ?>">
                                                    <i class="fa fa-solid fa-trash-alt"></i>
                                                </a>
                                                <?php if ($d->status == 'ACTIVE') : ?>
                                                    <a href="<?= base_url('nonactivedriver/') . $d->kd_driver  ?>" class="btn btn-warning btn-sm ml-2 ">
                                                        <i class="fa fa-solid fa-ban"></i>
                                                    </a>
                                                <?php elseif ($d->status == 'NON-ACTIVE') : ?>
                                                    <a href="<?= base_url('activedrver/') . $d->kd_driver ?>" class="btn btn-success btn-sm ml-2">
                                                        <i class="fa fa-solid fa-user-check"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </th>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

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