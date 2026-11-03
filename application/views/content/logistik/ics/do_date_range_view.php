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
                    <div class="row">
                        <div class="col-auto">
                            <a href="<?= base_url('ics/by_expdate') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-arrow-left"></i></a>
                        </div>
                        <?php if ($this->session->userdata('lv') == '1') : ?>
                            <div class="col-auto">
                                <a href="<?= base_url('ics/icsdo') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-minus-circle"></i> Out Today</a>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('ics/icspo') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-plus-circle"></i> Data LPB</a>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('ics/icsdo/dohistori') ?>" class="btn btn-md btn-secondary w-100 mb-3"><i class="fas fa-recycle"></i> Histori DO</a>
                            </div>
                        <?php else : ?>
                        <?php endif; ?>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="container-fluid">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Kode DO</th>
                                            <th>Kode Faktur</th>
                                            <th>Tgl Transaksi</th>
                                            <th>Nama Barang</th>
                                            <th>Qty</th>
                                            <th>No Lot</th>
                                            <th>Exp Date</th>
                                            <th>Input At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($results)) : ?>
                                            <?php foreach ($results as $row) : ?>
                                                <tr>
                                                    <td><?= $row->id ?></td>
                                                    <td><?= $row->kd_do ?></td>
                                                    <td><?= $row->kd_faktur ?></td>
                                                    <td><?= date('d/m/Y', strtotime($row->tgl_transaksi)) ?></td>
                                                    <td><?= $row->nama_barang ?></td>
                                                    <td><?= $row->qty ?></td>
                                                    <td><?= $row->no_lot ?></td>
                                                    <td><?= date('d/m/Y', strtotime($row->exp_date)) ?></td>
                                                    <td><?= date('d/m/Y', strtotime($row->input_at)) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="9" class="text-center">Data tidak ditemukan.</td>
                                            </tr>
                                        <?php endif; ?>
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