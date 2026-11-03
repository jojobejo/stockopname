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
                <?php if ($this->session->userdata('jobdesk') == 'ADMINKEUTC') : ?>
                    <div class="container-fluid">
                        <div class="card">
                            <div class="card-body">
                                <table id="tbDashboardLogistik" class="table table-bordered table-striped">
                                    <h3>Delivery Order List</h3>
                                    <thead style="background-color: #212529; color:white;">
                                        <tr>
                                            <!-- <td>Kode DO</td> -->
                                            <td>Tgl. Buat</td>
                                            <td>Rute</td>
                                            <td>Total Faktur</td>
                                            <td>Total Barang</td>
                                            <!-- <td>Status</td> -->
                                            <td>#</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($listdo as $i) :
                                            $status = $i->status;
                                            if ($status == '1') {
                                                $datasts = '<div class="col"><a href="#" class="btn btn-sm btn-warning btn-block">Draft</a></div>';
                                            } else if ($status == '2') {
                                                $datasts = '<div class="col"><a href="#" class="btn btn-sm btn-info btn-block">On Delivery</a></div>';
                                            } else if ($status == '3') {
                                                $datasts = '<div class="col"><a href="#" class="btn btn-sm btn-success btn-block">On Delivery</a></div>';
                                            }
                                        ?>
                                            <tr>
                                                <!-- <td><?= $i->kddo ?></td> -->
                                                <td><?= $i->createat ?></td>
                                                <td><?= $i->rute ?></td>
                                                <td><?= $i->totalfaktur ?></td>
                                                <td><?= $i->totalbarang ?></td>
                                                <!-- <td>
                                                    <?= $datasts ?>
                                                </td> -->
                                                <?php if ($i->status == '1') : ?>
                                                    <td>
                                                        <a href="<?= base_url('detail_do/') . $i->kddo ?>" class="btn btn-sm btn-info btn-block"><i class="fas fa-eye"></i></a>
                                                    </td>
                                                <?php elseif ($i->status == '2') : ?>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col">
                                                                <a href="<?= base_url('detail_do/') . $i->kddo ?>" class="btn btn-sm btn-info btn-block"><i class="fas fa-eye"></i></a>
                                                            </div>
                                                            <div class="col">
                                                                <a href="<?= base_url('printdo/') . $i->kddo ?>" class="btn btn-sm btn-success btn-block"><i class="fas fa-print"></i></a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                <?php elseif ($this->session->userdata('jobdesk') == 'ADMINKEU') : ?>
                    <div class="container-fluid">
                        <?php if ($this->session->userdata('lv') == 1) : ?>
                            <a href="<?= base_url('insertmodule') ?>" class="btn btn-primary mb-2">Update Data Stock</a>
                            <a href="<?= base_url('gudang/1') ?>" class="btn btn-success mb-2">Gudang Global</a>
                            <a href="<?= base_url('gudang/2') ?>" class="btn btn-success mb-2">Gudang Induk</a>
                            <a href="<?= base_url('gudang/3') ?>" class="btn btn-success mb-2">Gudang Rusak</a>
                            <a href="<?= base_url('daily_stock_lot') ?>" class="btn btn-success mb-2">Stock Expired & Lot</a>
                            <a href="<?= base_url('pendingpo') ?>" class="btn btn-success mb-2">Pending PO</a>
                            <a href="<?= base_url('master_barang') ?>" class="btn btn-success mb-2">Master Barang</a>
                        <?php else : ?>
                            <a href="<?= base_url('gudang/1') ?>" class="btn btn-success mb-2">Gudang Global</a>
                            <a href="<?= base_url('gudang/2') ?>" class="btn btn-success mb-2">Gudang Induk</a>
                            <a href="<?= base_url('gudang/3') ?>" class="btn btn-success mb-2">Gudang Rusak</a>
                            <a href="<?= base_url('daily_stock_lot') ?>" class="btn btn-success mb-2">Stock Expired & Lot</a>
                            <a href="<?= base_url('pendingpo') ?>" class="btn btn-success mb-2">Pending PO</a>
                            <a href="<?= base_url('master_barang') ?>" class="btn btn-success mb-2">Master Barang</a>
                            <a href="<?= base_url('pricelist_online') ?>" class="btn btn-success mb-2">Pricelist Online</a>
                        <?php endif; ?>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3>Daily Stock</h3>
                        </div>
                        <div class="card-body">
                            <table id="tbgudang" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <td>Total Data</td>
                                        <td>Gdg.Global</td>
                                        <td>Gdg.Induk</td>
                                        <td>Gdg.Rusak</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($count_gudang as $c) : ?>
                                        <tr>
                                            <td><?= number_format($c->total_data) ?></td>
                                            <td><?= number_format($c->global) ?></td>
                                            <td><?= number_format($c->induk) ?></td>
                                            <td><?= number_format($c->rusak) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                <?php elseif ($this->session->userdata('jobdesk') == 'DIREKTUR') : ?>
                    <div class="card">
                        <div class="card-header">
                            <h3>Stock Log </h3>
                        </div>
                        <div class="card-body">
                            <table id="tbupdate" class="table table-bordered table-striped mt-2">
                                <thead>
                                    <tr>
                                        <td>Nama Gudang</td>
                                        <td>Last Updated</td>
                                        <td>#</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($updated as $up) :
                                        date_default_timezone_set("Asia/Jakarta");
                                        $date_c = date_create($up->updated);
                                        $date = date_format($date_c, "Y-m-d H:i:s");
                                        $id = $up->gdgid;
                                    ?>
                                        <tr>
                                            <td><?= $up->gudang ?></td>
                                            <td><?= format_indo($date) ?></td>
                                            <td style="width: 5%;"><a href="<?= base_url('gudang/' . $id) ?>" class="btn btn-primary"><i class="fas fa-home"></i></a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php elseif ($this->session->userdata('jobdesk') == 'ADMINPURCHASING') : ?>
                    <div class="container-fluid">
                        <a href="<?= base_url('pendingpo') ?>" class="btn btn-secondary mb-2">Dashboard</a>
                        <a href="<?= base_url('pendingpo') ?>" class="btn btn-success mb-2">Pending PO</a>
                        <a href="<?= base_url('master_barang') ?>" class="btn btn-success mb-2">Master Barang</a>
                    </div>
                <?php endif; ?>
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