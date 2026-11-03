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

                    <a href="<?= base_url('keuangan') ?>" class="btn btn-primary mb-2"><i class="fas fa-home"></i></a>
                    <a href="<?= base_url('Pending PO') ?>" class="btn btn-secondary mb-2">Pending PO</a>

                    <div class="card">
                        <div class="card-body">

                            <table id="tbstock_lot" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <td>Tanggal</td>
                                        <td>No PO</td>
                                        <td>Nama Suplier</td>
                                        <td>Nama Barang</td>
                                        <td>QTY PO</td>
                                        <td>QTY DATANG</td>
                                        <td>QTY SISA</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stocklist as $s) : ?>
                                        <tr>
                                            <td><?= $s->tgl ?></td>
                                            <td><?= $s->po ?></td>
                                            <td><?= $s->nmsuplier ?></td>
                                            <td><?= $s->nmbarang ?></td>
                                            <td><?= $s->qtyorder ?></td>
                                            <td><?= $s->qtydone ?></td>
                                            <td><?= $s->qtykurang ?></td>
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