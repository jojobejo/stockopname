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
                    <a href="<?= base_url('daily_stock_lot') ?>" class="btn btn-secondary mb-2">Stock Expired & Lot</a>

                    <div class="card">
                        <div class="card-body">

                            <table id="tbstock_lot" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <td>Nama Barang</td>
                                        <td>Qty (KECIL)</td>
                                        <td>Box</td>
                                        <td>Pcs</td>
                                        <td>#</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stocklist as $s) : ?>
                                        <tr>
                                            <td><?= $s->nama_barang ?></td>
                                            <td><?= $s->tot_qty ?></td>
                                            <td><?= $s->qty_box ?></td>
                                            <td><?= $s->qty_pcs ?></td>
                                            <td><a href="<?= base_url('detail_lot/') . $s->kd_system ?>" class="btn btn-block btn-primary"><i class="fas fa-eye"></i></a></td>
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