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
                            <h3 class="card-title">Laporan Keluar Masuk Kendaraan</h3>
                        </div>
                        <div class="ml-2">
                            <button type="button" class="btn btn-primary m-2 ml-3" data-toggle="modal" data-target="#addInventaris">
                                <i class="fas fa-pen"></i>
                                Input Laporan Baru
                            </button>
                        </div>
                        <div class="card-body">
                            <table id="tb_lap_distribusi" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nopol</th>
                                        <th>Nolambung</th>
                                        <th>Nama Driver</th>
                                        <th>Nama Helper</th>
                                        <th>Tujuan</th>
                                        <th>Tgl Keluar</th>
                                        <th>Jam Keluar</th>
                                        <th>Km(Keluar)</th>
                                        <th>Tgl Masuk</th>
                                        <th>Jam Masuk</th>
                                        <th>Km(Masuk)</th>
                                        <th>Keterangan</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($laporan as $l) :
                                        $no = 1; ?>
                                        <tr>
                                            <th><?= $l->nopol ?></th>
                                            <th><?= $l->nolambung ?></th>
                                            <th><?= $l->namadriver ?></th>
                                            <th><?= $l->namahelper ?></th>
                                            <th><?= $l->tujuan ?></th>
                                            <th><?= $l->tglkeluar ?></th>
                                            <th><?= $l->jamkeluar ?></th>
                                            <th><?= $l->kmkeluar ?></th>
                                            <th><?= $l->tglmasuk ?></th>
                                            <th><?= $l->jammasuk ?></th>
                                            <th><?= $l->kmmasuk ?></th>
                                            <th><?= $l->keterangan ?></th>
                                            <th>
                                                <div class="row">
                                                    <a href="#" class="btn btn-warning btn-sm " data-toggle="modal" data-target="#editlap<?= $l->id ?>">
                                                        <i class="fa fa-solid fa-pencil-alt"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm " data-toggle="modal" data-target="#hapuslap<?= $l->id ?>">
                                                        <i class="fa fa-solid fa-trash-alt"></i>
                                                    </a>
                                                </div>
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