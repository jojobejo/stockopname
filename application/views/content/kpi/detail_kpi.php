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
                        <div class="row">
                            <div class="col m-1">
                                <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#addkpi">
                                    <i class="fas fa-pen"></i>
                                    Tambah Tujuan Penilaian
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="row">
                            <div class="col m-1">
                                <a href="<?= base_url('detail_tujuan/tjuan/') ?>" class="btn btn-sm btn-success">Tujuan </a>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="ml-2">
                            <h3>Tujuan 1</h3>
                            <h6>Deskripsi</h6>
                            <h6>Bobot 40%</h6>
                        </div>
                        <div class="row">
                            <div class="col m-1">
                                <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#addkpi">
                                    <i class="fas fa-pen"></i>
                                    Tambah Varibel WHATS
                                </button>
                            </div>
                            <div class="col m-1">
                                <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#addkpi">
                                    <i class="fas fa-pen"></i>
                                    Tambah Indikator Penilaian
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- START TABLE WHATS -->
                                <div class="col">
                                    <table id="tb_lap_distribusi" class="table table-bordered table-striped" style="text-align: center;">
                                        <thead>
                                            <tr>
                                                <td rowspan="2" style="text-align: center;">No</td>
                                                <td rowspan="2" style="text-align: center;">WHATS</td>
                                                <td colspan="2" style="text-align: center;">Nilai</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: center;">Tercapai</td>
                                                <td style="text-align: center;">Bobot Nilai : 70%</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Pembuatan Aplikasi Mencapai 4</td>
                                                <td>1</td>
                                                <td>80</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td>Hasil</td>
                                                <td colspan="3">7 Applikasi (Pricelist , Katalog , PO , Stockopname , IT , Schedule Logistik , HRD Digital)</td>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                                <!-- END TABLE WHATS -->

                                <!-- START TABLE INDIKATOR WHATS -->
                                <div class="col">
                                    <table id="tb_lap_distribusi" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <td colspan="3" style="text-align: center;">INDIKATOR WHATS TERCAPAI</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>> 4 Aplikasi</td>
                                                <td>115</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- END INDIKATOR WHATS -->
                            </div>

                            <div class="row mb-2">
                                <div class="col m-1">
                                    <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#addkpi">
                                        <i class="fas fa-pen"></i>
                                        Tambah Varibel HOW
                                    </button>
                                </div>
                                <div class="col m-1">
                                    <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#addkpi">
                                        <i class="fas fa-pen"></i>
                                        Tambah Indikator Penilaian HOW
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <!-- TABEL HOW -->
                                <div class="col">
                                    <table id="tb_lap_distribusi" class="table table-bordered table-striped" style="text-align: center;">
                                        <thead>
                                            <tr>
                                                <td rowspan="2" style="text-align: center;">No</td>
                                                <td rowspan="2" style="text-align: center;">HOW</td>
                                                <td colspan="2" style="text-align: center;">Nilai</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: center;">Tercapai</td>
                                                <td style="text-align: center;">Bobot Nilai : 20%</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Waktu pengerjaan dapat terselesaikan maksimal H+1 sesuai dengan akhir bulan</td>
                                                <td>2</td>
                                                <td>20</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td>Hasil</td>
                                                <td colspan="3">Rata - Rata sesuai dengan target pengerjaan</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- END TABEL HOW -->
                                <!-- INDIKATOR HOW -->
                                <div class="col">
                                    <table id="tb_lap_distribusi" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <td colspan="3" style="text-align: center;">INDIKATOR HOW TERCAPAI</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>H-0</td>
                                                <td>115</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>H+1</td>
                                                <td>100</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>H+2</td>
                                                <td>90</td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>H+3</td>
                                                <td>50</td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td> > H+3</td>
                                                <td>0</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- END INDIKATOR HOW -->
                            </div>
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