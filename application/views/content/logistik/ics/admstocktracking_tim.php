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
                            <div class="col-12 col-sm-6">
                                <a href="<?= base_url('admstocktracking'); ?>" class="btn btn-primary w-10"><i class="fas fa-home"></i></a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="content-body">
                <section class="content">
                    <div class="container-fluid">

                        <!-- Result TIM By Expired Date -->
                        <div class="card mt-4 mb-2">
                            <div class="card-body">
                                <h4>Result TIM By Expired Date</h4>
                                <table class="table table-bordered table-sm" id="tb_timby_expdate">
                                    <thead>
                                        <tr>
                                            <th>Nama Barang</th>
                                            <th>Expired Date</th>
                                            <th>Qty Fisik</th>
                                            <th>Qty Buku</th>
                                            <th>Qty Pending</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($resultcomparebyexp as $row) : ?>
                                            <tr>
                                                <td><?= $row->nama_barang ?></td>
                                                <td><?= $row->exp_date ?></td>
                                                <td><?= $row->qty_fisik ?></td>
                                                <td><?= $row->qty_buku ?></td>
                                                <td><?= $row->qty_pending ?></td>
                                                <td>
                                                    <span class="badge <?= $row->status == 'MATCH' ? 'bg-success' : 'bg-danger' ?>">
                                                        <?= $row->status ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Result TIM By Allbarang -->
                        <div class="card mt-4 mb-2">
                            <div class="card-body">
                                <h4>Result TIM By Allbarang</h4>
                                <table class="table table-bordered table-sm" id="tb_timby_allbarang">
                                    <thead>
                                        <tr>
                                            <th>Nama Barang</th>
                                            <th>Qty Fisik</th>
                                            <th>Qty Buku</th>
                                            <th>Qty Pending</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($resultcompare as $row) : ?>
                                            <tr>
                                                <td><?= $row->nama_barang ?></td>
                                                <td><?= $row->qty_fisik ?></td>
                                                <td><?= $row->qty_buku ?></td>
                                                <td><?= $row->qty_pending ?></td>
                                                <td>
                                                    <span class="badge <?= $row->status == 'MATCH' ? 'bg-success' : 'bg-danger' ?>">
                                                        <?= $row->status ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Stock Opname - Tracking -->
                        <div class="card mt-4 mb-2">
                            <div class="card-body">
                                <h4>Stock Opname - Tracking</h4>
                                <table class="table table-bordered" id="tb_stock_tracking">
                                    <thead>
                                        <tr>
                                            <th>Nama Barang</th>
                                            <th style="width: 15%;">Expired Date</th>
                                            <th style="width: 10%;">Qty</th>
                                            <th style="width: 10%;">Qty Box</th>
                                            <th style="width: 10%;">Qty Pcs</th>
                                            <th style="width: 10%;">Inputer</th>
                                            <th style="width: 5%;">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($trkopname as $item) : ?>
                                            <tr>
                                                <td><?= $item->nama_barang ?></td>
                                                <td><?= $item->exp_date ?></td>
                                                <td><?= $item->qty ?></td>
                                                <td><?= $item->qty_box ?></td>
                                                <td><?= $item->qty_pcs ?></td>
                                                <td><?= $item->inputer ?></td>
                                                <td><?= $item->id ?></td>
                                            </tr>
                                        <?php endforeach ?>
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