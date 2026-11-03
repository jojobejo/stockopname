<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>


        <?php $this->load->view('content/schedule/modalschedule.php') ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h1></h1>
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
                                <div class="col ">
                                    <h3 class="">Schedule Direktur</h3>
                                </div>
                                <?php if ($this->session->userdata('lv') != '5') : ?>
                                    <div>
                                        <a class="btn btn-primary ml-4 text-center" data-toggle="modal" data-target="#addschedule"><i class="fas fa-plus"></i><b style="text-transform: uppercase;"> Add Schedule</b></a>
                                    </div>
                                <?php else : ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="tbtamu" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <td>No</td>
                                        <td>Tanggal</td>
                                        <td>Jam</td>
                                        <td>Instansi</td>
                                        <td>PIC</td>
                                        <td>Estimasi</td>
                                        <td>Tujuan</td>
                                        <td>Status</td>
                                        <td>Keterangan</td>
                                        <?php if ($this->session->userdata('lv') != '5') : ?>
                                            <td>#</td>
                                        <?php else : ?>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($getschedule as $get) :
                                        $status = "";
                                        if ($get->status == '0') {
                                            $status = 'NO STATUS';
                                        } else if ($get->status == '1') {
                                            $status = '1';
                                        } else if ($get->status == '2') {
                                            $status = '2';
                                        } else if ($get->status == '3') {
                                            $status = '3';
                                        } else if ($get->status == '4') {
                                            $status = '4';
                                        }
                                    ?>
                                        <tr>
                                            <th><?= $no++ ?></th>
                                            <th><?= format_tgl_lahir($get->tanggal)  ?></th>
                                            <th style="width: 5%;text-align: center;"><?= $get->jam ?></th>
                                            <th><?= $get->suplier ?></th>
                                            <th><?= $get->pic ?></th>
                                            <th style="width: 7%;text-align: center;"><?= $get->estimasi_end ?></th>
                                            <th><?= $get->tujuan ?></th>
                                            <th style="width: 5%;text-align: center;">
                                                <?php if ($status == '1') : ?>
                                                    <a href="#" class="btn btn-warning btn-sm btn-block"><i class="fas fa-business-time"></i></a>
                                                <?php elseif ($status == '2') : ?>
                                                    <a href="#" class="btn btn-danger btn-sm btn-block"><i class="fas fa-times-circle"></i></a>
                                                <?php elseif ($status == '3') : ?>
                                                    <a href="#" class="btn btn-success btn-sm btn-block"><i class="fas fa-check-circle"></i></a>
                                                <?php endif; ?>
                                            </th>
                                            <th><?= $get->keterangan ?></th>
                                            <?php if ($this->session->userdata('lv') != '5') : ?>
                                                <th style="width: 13%;text-align: center;">
                                                    <?php if ($status == '1') : ?>
                                                        <a href="#" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editedschedule<?= $get->id ?>"><i class="fas fa-pencil-alt"></i></a>
                                                        <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#cancelschedule<?= $get->id ?>"><i class="fas fa-times-circle"></i></a>
                                                        <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#reschedule<?= $get->id ?>"><i class="fas fa-sync-alt"></i></a>
                                                        <a href="#" class="btn btn-sm btn-success" data-toggle="modal" data-target="#scheduledone<?= $get->id ?>"><i class="fas fa-check-circle"></i></a>
                                                    <?php elseif ($status == '2') : ?>
                                                        <a href="#" class="btn btn-sm btn-danger btn-block" data-toggle="modal" data-target="#deleteschedule<?= $get->id ?>"><i class="fas fa-trash-alt"></i></a>
                                                    <?php elseif ($status == '3') : ?>
                                                        <a href="#" class="btn btn-sm btn-success btn-block" data-toggle="modal" data-target="#archived<?= $get->id ?>"><i class="fas fa-check-circle"></i></a>
                                                    <?php endif; ?>
                                                </th>
                                            <?php else : ?>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
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