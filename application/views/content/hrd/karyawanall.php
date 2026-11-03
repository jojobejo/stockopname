<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>
        <?php $this->load->view('content/hrd/modalkaryawan') ?>

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
                            <h3 class="card-title">Data All Karyawan</h3>
                        </div>
                        <div class="ml-2">
                            <button type="button" class="btn btn-primary m-2 ml-3" data-toggle="modal" data-target="#addkaryawan">
                                <i class="fas fa-pen"></i>
                                Input Karyawan Baru
                            </button>
                        </div>
                        <div class="card-body">
                            <table id="tb_lap_distribusi" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Departemen</th>
                                        <th>Alamat</th>
                                        <th>Tgl Lahir</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($karyawan as $k) :

                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $k->nik ?></td>
                                            <td><?= $k->nama_lengkap ?></td>
                                            <td><?= $k->departemen ?></td>
                                            <td><?= $k->alamat ?></td>
                                            <td><?= format_tgl_lahir($k->tgl_lahir) ?></td>
                                            <td>
                                                <div class="row">
                                                    <a class="btn btn-primary btn-sm" href="<?= base_url('detail_karyawan/') . $k->nik ?>"><i class="fas fa-eye"></i></a>
                                                    <a class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editkaryawan<?= $k->id ?>"><i class="fas fa-pencil-alt"></i></a>
                                                </div>
                                            </td>
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