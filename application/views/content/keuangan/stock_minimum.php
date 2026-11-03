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
                        <a href="<?= base_url('insertmodule') ?>" class="btn btn-primary mb-2">Update Data Stock</a>
                        <?php if ($gudangid == '1') : ?>
                            <a href="<?= base_url('keuangan') ?>" class="btn btn-primary mb-2"><i class="fas fa-home"></i></a>
                            <a href="<?= base_url('list_stock_minimum/1') ?>" class="btn  btn-secondary mb-2">Stock Minimum Global</a>
                            <a href="<?= base_url('list_stock_minimum/2') ?>" class="btn  btn-success  mb-2">Stock Minimum Induk</a>
                            <a href="<?= base_url('list_stock_minimum/3') ?>" class="btn  btn-success  mb-2">Stock Minimum Rusak</a>
                        <?php elseif ($gudangid == '2') : ?>
                            <a href="<?= base_url('keuangan') ?>" class="btn btn-primary mb-2"><i class="fas fa-home"></i></a>
                            <a href="<?= base_url('list_stock_minimum/1') ?>" class="btn  btn-success  mb-2">Stock Minimum Global</a>
                            <a href="<?= base_url('list_stock_minimum/2') ?>" class="btn  btn-secondary  mb-2">Stock Minimum Induk</a>
                            <a href="<?= base_url('list_stock_minimum/3') ?>" class="btn  btn-success  mb-2">Stock Minimum Rusak</a>
                        <?php elseif ($gudangid == '3') : ?>
                            <a href="<?= base_url('keuangan') ?>" class="btn btn-primary mb-2"><i class="fas fa-home"></i></a>
                            <a href="<?= base_url('list_stock_minimum/1') ?>" class="btn  btn-success  mb-2">Stock Minimum Global</a>
                            <a href="<?= base_url('list_stock_minimum/2') ?>" class="btn  btn-success  mb-2">Stock Minimum Induk</a>
                            <a href="<?= base_url('list_stock_minimum/3') ?>" class="btn  btn-secondary  mb-2">Stock Minimum Rusak</a>
                        <?php endif; ?>
                    <?php else : ?>
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
                                        <h2>Last Updated : <?= format_indo($date) ?></h2>
                                    </div>
                                    <div class="col">
                                        <a href="<?= base_url('gudang/' . $gudangid) ?>" class="btn btn-sm btn-primary btn-block mb-2">List Stock</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <h3><?= $gudang ?></h3>

                            <?php if ($gudangid == '1') : ?>
                                <table id="tbminglobal" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <td>Nama Suplier</td>
                                            <td>Nama Barang</td>
                                            <td>Satuan</td>
                                            <td>Stock</td>
                                            <td>Minimum</td>
                                            <td>Minimum Box</td>
                                            <td>Minimum Pcs</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($stock as $s) : ?>
                                            <tr>
                                                <td><?= $s->nmsuplier ?></td>
                                                <td><?= $s->nmbarang ?></td>
                                                <td><?= $s->satuan ?></td>
                                                <td><?= $s->qty ?></td>
                                                <td><?= $s->qty_min ?></td>
                                                <td><?= $s->qty_box ?></td>
                                                <td><?= $s->qty_pcs ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else :  ?>
                                <table id="tbmingdg" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <td>Nama Suplier</td>
                                            <td>Nama Barang</td>
                                            <td>Satuan</td>
                                            <td>Stock</td>
                                            <td>Minimum</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($stock as $s) : ?>
                                            <tr>
                                                <td><?= $s->nmsuplier ?></td>
                                                <td><?= $s->nmbarang ?></td>
                                                <td><?= $s->satuan ?></td>
                                                <td><?= $s->qty ?></td>
                                                <td><?= $s->qtymin ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
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