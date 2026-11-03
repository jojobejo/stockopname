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
            <div class="content-header">
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-auto ml-2">
                                <h3>Stock ICS</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-4">
                                <a href="<?= base_url('icsglobal') ?>" class="btn btn-secondary w-100">STOCK GLOBAL</a>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <a href="" class="btn btn-primary w-100">STOCK INDUK</a>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <a href="" class="btn btn-primary w-100">STOCK LOT</a>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="content">
                    <div class="container-fluid">
                        <div class="card mt-4 mb-2">
                            <div class="card-body">
                                <table id="tbics" class="table table-bordered table-striped">
                                    <thead style="background-color: #212529; color:white;">
                                        <tr>
                                            <td>Kode Barang</td>
                                            <td>Nama Barang</td>
                                            <td>Kordinat</td>
                                            <td>#</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($listics as $list) : ?>
                                            <tr>
                                                <td><?= $list->kode_barang ?></td>
                                                <td><?= $list->nm_barang ?></td>
                                                <td><?= $list->kordinat ?></td>
                                                <td>
                                                    <a href="<?= base_url('detailbarangs/') . $list->kode_barang ?>" class="btn btn-info w-100"></a>
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