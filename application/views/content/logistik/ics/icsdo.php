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
                            <a href="<?= base_url('ics/ics_diffrent') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-arrow-left"></i></a>
                        </div>
                        <?php if ($this->session->userdata('lv') == '1') : ?>
                            <div class="col-auto">
                                <a href="<?= base_url('ics/icsdo') ?>" class="btn btn-md btn-secondary w-100 mb-3"><i class="fas fa-minus-circle"></i> Out Today</a>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('ics/icspo') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-plus-circle"></i> Data LPB</a>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('ics/export_opname') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-file-export"></i> Export DO</a>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('ics/icsdo/dohistori') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-recycle"></i> Histori DO</a>
                            </div>
                        <?php else : ?>
                            <div class="col-auto">
                                <a href="<?= base_url('ics/icsdo') ?>" class="btn btn-md btn-secondary w-100 mb-3"><i class="fas fa-minus-circle"></i> Out Today</a>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('ics/icspo') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-plus-circle"></i> Data LPB</a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="container-fluid">
                                <table class="table table-bordered " id="tb_ics_do">
                                    <thead class="bg-primary text-white text-center">
                                        <tr>
                                            <th>Kode Faktur</th>
                                            <!-- <th>Tgl Transaksi</th> -->
                                            <th>Kios</th>
                                            <th>Rute</th>
                                            <th>Nama Barang</th>
                                            <th>Expired Date</th>
                                            <th>Qty</th>
                                            <th>Box</th>
                                            <th>Pcs</th>
                                            <th>Lot</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($ics_do as $do) : ?>
                                            <tr>
                                                <td><?= $do->kd_faktur ?></td>
                                                <!-- <td><?= $do->tgl_transaksi ?></td> -->
                                                <td><?= $do->nm_kios ?></td>
                                                <td><?= $do->rute ?></td>
                                                <td><?= $do->nama_barang ?></td>
                                                <td><?= $do->exp_date ?></td>
                                                <td><?= $do->qty ?></td>
                                                <td><?= $do->qty_box ?></td>
                                                <td><?= $do->qty_pcs ?></td>
                                                <td><?= $do->no_lot ?></td>
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