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

                    <a href="<?= base_url('daily_stock_lot') ?>" class="btn btn-primary mb-2"><i class="fas fa-home"></i></a>

                    <div class="card">
                        <div class="card-header">
                            <?php foreach ($barang as $b) : ?>
                                <h3><?= $b->nmbarang . " " . '-' . " " . $b->nmsuplier ?></h3>
                            <?php endforeach; ?>
                        </div>
                        <div class="card-body">

                            <table id="dettbstock_lot" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <td>No.Lot</td>
                                        <td>Expired Date</td>
                                        <td>Qty</td>
                                        <td>Gudang</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($detail_lot as $d) : ?>
                                        <tr>
                                            <td><?= $d->nolot ?></td>
                                            <td><?= $d->expdate ?></td>
                                            <td><?= $d->qty_lot ?></td>
                                            <td><?= $d->nmgudang ?></td>
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