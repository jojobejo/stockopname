<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>
        <?php $this->load->view('content/kpi/modaldashboardkpi') ?>
        <?php $this->load->view('content/kpi/modal/modalkpi') ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <a href="" class="btn btn-md btn-info" data-toggle="modal" data-target="#addwhatindi">Tambah point</a>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg connectedSortable">
                            <?php foreach ($kpi as $k) : ?>
                                <div class="card mb-4">
                                    <div style="height: 50px; margin-top: -3px;" class="card-header bg-primary">
                                        <h5 style="color:white; font-weight: bolder;" class="card-title"><?= $k->nm_poin ?></h5>
                                        <h5 style="color:white; margin-left: 15px; font-weight: bolder; font-size: medium;" class="badge bg-warning">Bobot : <?= $k->bobot_poin ?> %</h5>
                                        <div class="card-tools">
                                            <button style="color: white; margin-top: -20px; margin-right: 5px;" type="button" data-toggle="modal" data-target="#EditModalIndikator<?= $k->id ?>" class="btn btn-tool">
                                                <i class="fa fa-pencil-alt fs-6"></i>
                                            </button>
                                            <button style="color: white; margin-top: -20px; margin-right: 5px;" type="button" data-toggle="modal" data-target="#HapusModalindikator<?= $k->id ?>" class="btn btn-tool">
                                                <i class="fa fa-pencil-alt fs-6"></i>
                                            </button>
                                            <button style="color: white; margin-top: -20px; margin-right: 5px; " type="button" data-toggle="dropdown" class="btn btn-tool dropdown">
                                                <i class="fa fa-plus-circle fs-6"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-left" role="menu">
                                                <a href="#" class="dropdown-item" data-toggle="modal" data-target="#WhatModal<?= $k->id ?>">Tambah What </a>
                                                <a href="#" class="dropdown-item" data-toggle="modal" data-target="#HowModal<?= $k->id ?>">Tambah How </a>
                                            </div>
                                            <button style="color: white;" type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body pt-3 pb-0">
                                        <div class="card">
                                            <div class="card-body p-0">
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Whats</th>
                                                            <th style="width: 30%">Hasil</th>
                                                            <th style="width: 5%">
                                                                <center>Nilai</center>
                                                            </th>
                                                            <th style="width: 4%">
                                                                <center>Bobot</center>
                                                            </th>
                                                            <th style="width: 4%">
                                                                <center>Hasil Total</center>
                                                            </th>
                                                            <th style="width: 8%">
                                                                <center>Indikator</center>
                                                            </th>
                                                            </th>
                                                            <th style="width: 9%">
                                                                <center>Action</center>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="align-middle">
                                                            <td></td>
                                                            <td></td>
                                                            <td>
                                                                <center>
                                                            </td>
                                                            <td>
                                                                <center>%
                                                            </td>
                                                            <td>
                                                                <center>
                                                            </td>
                                                            </td>
                                                            <td>
                                                                <center>
                                                                    <a value="" type="button" name="add_indi" class="btn btn-success btn-sm " data-bs-toggle="modal" data-bs-target="#addwhatindi"><i class="bi bi-plus-circle-fill"></i></a>
                                                                    <a value="" type="button" name="select_indi" class="btn btn-success btn-sm " data-bs-toggle="modal" data-bs-target="#selectwhatindi"><i class="bi bi-check2-circle"></i></a>
                                                            </td>

                                                            <td>
                                                                <center>
                                                                    <a value="" type="button" name="what_edit" class="btn btn-success btn-sm " data-bs-toggle="modal" data-bs-target="#EditWhatModal"><i class="bi bi-pencil"></i></a>
                                                                    <a type="button" name="what_hapus" class="btn btn-warning btn-sm " data-bs-toggle="modal" data-bs-target="#HapusWhatModal"><i class="bi bi-trash"></i></a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="card">
                                            <div class="card-body p-0">
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Hows</th>
                                                            <th style="width: 30%">Hasil</th>
                                                            <th style="width: 5%">
                                                                <center>Nilai</center>
                                                            </th>
                                                            <th style="width: 4%">
                                                                <center>Bobot</center>
                                                            </th>
                                                            <th style="width: 4%">
                                                                <center>Hasil Total</center>
                                                            </th>
                                                            <th style="width: 8%">
                                                                <center>Indikator</center>
                                                            </th>
                                                            <th style="width: 9%">
                                                                <center>Action</center>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="align-middle">
                                                            <td></td>
                                                            <td></td>
                                                            <td>
                                                                <center>
                                                            </td>
                                                            <td>
                                                                <center>%
                                                            </td>
                                                            <td>
                                                                <center>
                                                            </td>
                                                            <td>
                                                                <center>
                                                                    <a type="button" name="add_indihow" class="btn btn-success btn-sm " data-bs-toggle="modal" data-bs-target="#addhowindi"><i class="bi bi-plus-circle-fill"></i></a>
                                                                    <a type="button" name="select_indihow" class="btn btn-success btn-sm " data-bs-toggle="modal" data-bs-target="#selecthowindi"><i class="bi bi-check2-circle"></i></a>
                                                                    <!-- <?php include('pages/kpi/k_modalSelectHowindi.php'); ?> -->
                                                            </td>
                                                            <td>
                                                                <center>
                                                                    <a value="" type="button" name="how_edit" class="btn btn-success btn-sm " data-bs-toggle="modal" data-bs-target="#EditHowModal"><i class="bi bi-pencil"></i></a>
                                                                    <a type="button" name="how_hapus" class="btn btn-warning btn-sm " data-bs-toggle="modal" data-bs-target="#HapusHowModal"><i class="bi bi-trash"></i></a>
                                                            </td>
                                                            <!-- <?php include('pages/kpi/k_modalHapushow.php'); ?> -->
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div> <!-- /.card-body -->
                                        </div> <!-- /.card -->
                                    </div>
                                </div>
                            <?php endforeach; ?>
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