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
                            <div class="row">
                                <a href="<?= base_url('tracking_driver') ?>"><i class="fas fa-play fa-flip-horizontal mr-2"></i></a>
                                <h3 class="card-title">Detail Driver</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="tb_det_list_driver_order" class="table table-bordered table-striped" style="text-align: center;">
                                <?php foreach ($driver as $d) : ?>
                                    <h3><?= $d->nama_driver ?> </h3>
                                <?php endforeach; ?>
                                <thead>
                                    <tr>
                                        <th>Kode Deliveri</th>
                                        <th>Tanggal Jalan</th>
                                        <th>Kode Truk</th>
                                        <th>Nomor Plat</th>
                                        <th>Helper</th>
                                        <th>Destinasi </th>
                                        <th>Status Keberangkatan</th>
                                        <th>Keterangan </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($detail as $det) : ?>
                                        <tr>
                                            <th><?= $det->kd_deliveri ?></th>
                                            <th><?= format_indo($det->tgl_jalan) ?></th>
                                            <th><?= $det->kd_truk ?></th>
                                            <th><?= $det->noplat ?></th>
                                            <th><?= $det->nama_helper ?></th>
                                            <th><?= $det->destinasi ?></th>
                                            <?php if ($det->sts_driver == 'READY') : ?>
                                                <th>
                                                    <a href="#" class=" btn btn-success btn-sm btn-block ">
                                                        READY
                                                    </a>
                                                </th>
                                            <?php elseif ($det->sts_driver == 'WAITING') : ?>
                                                <th>
                                                    <a href="#" class=" btn btn-warning btn-sm btn-block ">
                                                        WAITING
                                                    </a>
                                                </th>
                                            <?php elseif ($det->sts_driver == 'PENDING') : ?>
                                                <th>
                                                    <a href="#" class=" btn btn-danger btn-sm btn-block ">
                                                        PENDING
                                                    </a>
                                                </th>
                                            <?php elseif ($det->sts_driver == 'ON THE ROAD') : ?>
                                                <th>
                                                    <a href="#" class=" btn btn-secondary btn-sm btn-block ">
                                                        ON THE ROAD
                                                    </a>
                                                </th>
                                            <?php endif; ?>
                                            <th><?= $det->keterangan ?></th>
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