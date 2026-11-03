<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>
        <?php $this->load->view('content/kpi/modaldashboardkpi') ?>


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
                            <h3 class="card-title">WHAT KPI</h3>
                        </div>
                        <div class="ml-2">
                            <button type="button" class="btn btn-primary m-2 ml-3" data-toggle="modal" data-target="#addkpi">
                                <i class="fas fa-pen"></i>
                                ADD WHAT KPI
                            </button>
                        </div>
                        <div class="card-body">
                            <table id="tb_lap_distribusi" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Bulan KPI</th>
                                        <th>Descripsi</th>
                                        <th>Descripsi Indikator</th>
                                        <th>Nilai Indikator</th>
                                        <th>Bobot</th>
                                        <th>Bobot Utama</th>
                                        <th>Nilai</th>
                                        <th>Total Nilai</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($allkpi as $a) :
                                    ?>
                                        <tr>
                                            <td><?= $a->bulan ?></td>
                                            <td><?= $a->what ?></td>
                                            <td><?= $a->indikator ?></td>
                                            <td><?= $a->nilai_indikator ?></td>
                                            <td><?= $a->bobot ?></td>
                                            <td><?= $a->bobotutama ?></td>
                                            <td><?= $a->nilai ?></td>
                                            <td>1</td>
                                            <td><a class="btn btn-primary btn-sm" href="<?= base_url('detail_kpi/') . $a->kd_user . '/' . $a->bln_pembuatan ?>"><i class="fas fa-eye"></i></a></td>
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