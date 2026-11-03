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

                    <?php if ($this->session->userdata('lv') == 1) : ?>
                        <?php if ($gudangid == '1') : ?>
                            <a href="<?= base_url('keuangan') ?>" class="btn btn-primary mb-2"><i class="fas fa-home"></i></a>
                            <a href="<?= base_url('gudang/1') ?>" class="btn btn-secondary mb-2">Gudang Global</a>
                            <a href="<?= base_url('gudang/2') ?>" class="btn btn-success mb-2">Gudang Induk</a>
                            <a href="<?= base_url('gudang/3') ?>" class="btn btn-success mb-2">Gudang Rusak</a>
                        <?php elseif ($gudangid == '2') : ?>
                            <a href="<?= base_url('keuangan') ?>" class="btn btn-primary mb-2"><i class="fas fa-home"></i></a>
                            <a href="<?= base_url('gudang/1') ?>" class="btn btn-success mb-2">Gudang Global</a>
                            <a href="<?= base_url('gudang/2') ?>" class="btn btn-secondary mb-2">Gudang Induk</a>
                            <a href="<?= base_url('gudang/3') ?>" class="btn btn-success mb-2">Gudang Rusak</a>
                        <?php elseif ($gudangid == '3') : ?>
                            <a href="<?= base_url('keuangan') ?>" class="btn btn-primary mb-2"><i class="fas fa-home"></i></a>
                            <a href="<?= base_url('gudang/1') ?>" class="btn btn-success mb-2">Gudang Global</a>
                            <a href="<?= base_url('gudang/2') ?>" class="btn btn-success mb-2">Gudang Induk</a>
                            <a href="<?= base_url('gudang/3') ?>" class="btn btn-secondary mb-2">Gudang Rusak</a>
                        <?php elseif ($gudangid == '4') : ?>
                            <a href="<?= base_url('keuangan') ?>" class="btn btn-primary mb-2"><i class="fas fa-home"></i></a>
                            <a href="<?= base_url('gudang/1') ?>" class="btn btn-success mb-2">Gudang Global</a>
                            <a href="<?= base_url('gudang/2') ?>" class="btn btn-success mb-2">Gudang Induk</a>
                        <?php endif; ?>
                        <!-- LV-5 & Lv 2,3,4 -->
                    <?php else : ?>
                        <?php if ($gudangid == '1') : ?>
                            <a href="<?= base_url('keuangan') ?>" class="btn btn-primary mb-2"><i class="fas fa-home"></i></a>
                            <a href="<?= base_url('gudang/1') ?>" class="btn btn-secondary mb-2">Gudang Global</a>
                            <a href="<?= base_url('gudang/2') ?>" class="btn btn-success mb-2">Gudang Induk</a>
                            <a href="<?= base_url('gudang/3') ?>" class="btn btn-success mb-2">Gudang Rusak</a>
                        <?php elseif ($gudangid == '2') : ?>
                            <a href="<?= base_url('keuangan') ?>" class="btn btn-primary mb-2"><i class="fas fa-home"></i></a>
                            <a href="<?= base_url('gudang/1') ?>" class="btn btn-success mb-2">Gudang Global</a>
                            <a href="<?= base_url('gudang/2') ?>" class="btn btn-secondary mb-2">Gudang Induk</a>
                            <a href="<?= base_url('gudang/3') ?>" class="btn btn-success mb-2">Gudang Rusak</a>
                        <?php elseif ($gudangid == '3') : ?>
                            <a href="<?= base_url('keuangan') ?>" class="btn btn-primary mb-2"><i class="fas fa-home"></i></a>
                            <a href="<?= base_url('gudang/1') ?>" class="btn btn-success mb-2">Gudang Global</a>
                            <a href="<?= base_url('gudang/2') ?>" class="btn btn-success mb-2">Gudang Induk</a>
                            <a href="<?= base_url('gudang/3') ?>" class="btn btn-secondary mb-2">Gudang Rusak</a>
                        <?php elseif ($gudangid == '4') : ?>
                            <a href="<?= base_url('keuangan') ?>" class="btn btn-primary mb-2"><i class="fas fa-home"></i></a>
                            <a href="<?= base_url('gudang/1') ?>" class="btn btn-success mb-2">Gudang Global</a>
                            <a href="<?= base_url('gudang/2') ?>" class="btn btn-success mb-2">Gudang Induk</a>
                            <a href="<?= base_url('gudang/3') ?>" class="btn btn-success mb-2">Gudang Rusak</a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="card">
                        <div class="card-body">
                            <?php foreach ($updated as $u) :
                                date_default_timezone_set("Asia/Jakarta");
                                $date_c = date_create($u->lastupdated);
                                $date = date_format($date_c, "Y-m-d H:i:s");
                            ?>
                                <div class="row">
                                    <div class="col">
                                        <h2>(<?= $gudang ?>) Updated : <?= format_indo($date) ?></h2>
                                    </div>
                                    <div class="col-4">
                                        <?php if ($this->session->userdata('lv') == 1) : ?>
                                            <a href="<?= base_url('truncateitm/' . $u->kd . '/' . $u->gdgid) ?>" class="btn btn-sm btn-danger btn-block mb-2">Delete Data</a>
                                            <a href="<?= base_url('list_stock_minimum/' . $gudangid) ?>" class="btn btn-sm btn-primary btn-block mb-2">Stock Minimum</a>
                                        <?php else : ?>
                                            <a href="<?= base_url('list_stock_minimum/' . $gudangid) ?>" class="btn btn-sm btn-primary btn-block mb-2">Stock Minimum</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>


                            <?php if ($this->session->userdata('lv') != '1') : ?>
                                <div class="row">
                                    <div class="col">
                                        <a href="<?= base_url('gudang/' . $gudangid . '/' . 'suplier/' . '1') ?>" class="btn btn-sm btn-success btn-block mb-2">BASF</a>
                                    </div>
                                    <div class="col">
                                        <a href="<?= base_url('gudang/' . $gudangid . '/' . 'suplier/' . '2') ?>" class="btn btn-sm btn-success btn-block mb-2">SYNGENTA</a>
                                    </div>
                                    <div class="col">
                                        <a href="<?= base_url('gudang/' . $gudangid . '/' . 'suplier/' . '3') ?>" class="btn btn-sm btn-success btn-block mb-2">DUPONT</a>
                                    </div>
                                </div>
                            <?php else : ?>
                            <?php endif; ?>

                            <?php if ($gudang != 'Global') : ?>
                                <table id="tb_qty" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <td>Nama Suplier</td>
                                            <td>Nama Barang</td>
                                            <td>Box</td>
                                            <td>Pcs</td>
                                        </tr>
                                    </thead>
                                </table>
                            <?php else : ?>
                                <table id="tb_qty_global" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <td>Nama Suplier</td>
                                            <td>Nama Barang</td>
                                            <td>Box</td>
                                            <td>Pcs</td>
                                            <td>#</td>
                                        </tr>
                                    </thead>
                                </table>
                            <?php endif; ?>
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