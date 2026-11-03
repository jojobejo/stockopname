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
            <?php $this->load->view('content/logistik/modal/modal_do_upload') ?>

            <div class="content-header">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-auto ml-2">
                            <h3>Dashboard Delivery Order</h3>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-auto">
                            <a href="#" class="btn btn-primary mb-2" data-toggle="modal" data-target="#muploadlog">Update Data DO</a>
                        </div>
                        <div class="col-auto">
                            <!-- <a href="https://10.10.10.12/Zahirdigital/keuangan/export_do.php" class="btn btn-info mb-2">Ambil Data Penjualan(TODAY)</a> -->
                            <a href="https://10.10.10.12/zahirdigital/keuangan/export_do_cb.php" class="btn btn-info mb-2">Ambil Data Penjualan(TODAY)</a>
                        </div>
                        <div class="col-auto">
                            <a href="<?= base_url('create_do') ?>" class="btn btn-success mb-2">Add Delivery Order</a>
                        </div>
                    </div>

                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-body">
                            <table id="tbDashboardLogistik" class="table table-bordered table-striped">
                                <h3>List Faktur Penjualan</h3>
                                <thead style="background-color: #212529; color:white;">
                                    <tr>
                                        <td>Kode DO</td>
                                        <td>Tgl. Buat</td>
                                        <td>Tgl. Kirim</td>
                                        <td>No Kendaraan</td>
                                        <td>Rute</td>
                                        <td>Total Faktur</td>
                                        <td>Total Barang</td>
                                        <td>Status</td>
                                        <td>#</td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">

                            <table id="tbDashboardLogistik" class="table table-bordered table-striped">
                                <h3>List Delivery Order To </h3>
                                <thead style="background-color: #212529; color:white;">
                                    <tr>
                                        <td>Kode DO</td>
                                        <td>Tgl. Buat</td>
                                        <td>Tgl. Kirim</td>
                                        <td>No Kendaraan</td>
                                        <td>Rute</td>
                                        <td>Total Faktur</td>
                                        <td>Total Barang</td>
                                        <td>Status</td>
                                        <td>#</td>
                                    </tr>
                                </thead>
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