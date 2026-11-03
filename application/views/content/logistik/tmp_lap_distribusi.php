<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>
        <?php $this->load->view('content/logistik/modaltmpdistribusi') ?>

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
                                <h3 class="card-title">Data Distribusi Logistik</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="tb_tmp_distribusi" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Tanggal Inputan</th>
                                        <th>Nama Driver</th>
                                        <th>Nama Helper</th>
                                        <th>No Lambung</th>
                                        <th>Nomor Plat</th>
                                        <th>Destinasi</th>
                                        <th>Tanggal Keluar</th>
                                        <th>Jam Keluar</th>
                                        <th>Kilometer Keluar</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Jam Masuk</th>
                                        <th>Kilometer Masuk</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data as $d) :
                                        if ($d->jm_keluar == '')
                                            $d->jm_keluar = '-';
                                        if ($d->km_keluar == '0')
                                            $d->km_keluar = '-';
                                        if ($d->jm_masuk == '')
                                            $d->jm_masuk = '-';
                                        if ($d->km_masuk == '0')
                                            $d->km_masuk = '-';
                                    ?>
                                        <tr>
                                            <th><?= format_indo($d->tgl_jalan) ?></th>
                                            <th><?= $d->nama_driver ?></th>
                                            <th><?= $d->nama_helper ?></th>
                                            <th><?= $d->nm_truk ?></th>
                                            <th><?= $d->noplat ?></th>
                                            <th><?= $d->destinasi ?></th>
                                            <th><?= format_tgl($d->tgl_keluar) ?></th>
                                            <th><?= $d->jm_keluar ?></th>
                                            <th><?= $d->km_keluar ?></th>
                                            <th><?= format_tgl($d->tgl_masuk) ?></th>
                                            <th><?= $d->jm_masuk ?></th>
                                            <th><?= $d->km_masuk ?></th>
                                            <th>
                                                <a href="#" class="btn btn-primary btn-sm ml-2 " data-toggle="modal" data-target="#edittmp<?= $d->id_lap_dis ?>">
                                                    <i class="fa fa-solid fa-pen"></i>
                                                </a>
                                                <a href="#" class="btn btn-success btn-sm ml-2 " data-toggle="modal" data-target="#insertlap<?= $d->id_lap_dis ?>">
                                                    <i class="fa fa-solid fa-check-double"></i>
                                                </a>
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