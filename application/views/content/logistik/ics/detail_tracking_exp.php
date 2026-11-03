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
                            <a href="<?= base_url('compare_exp') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-arrow-left"></i></a>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mt-2">Barang Compare - By Expired Date</h5>
                            <table class="table table-bordered table-sm" id="tb_dash_allbarang">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Expired Date</th>
                                        <th>Qty</th>
                                        <th>Qty Box</th>
                                        <th>Qty Pcs</th>
                                        <th>Inputer</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($list as $row) : ?>
                                        <tr>
                                            <td><?= $row->nama_barang ?></td>
                                            <td><?= $row->exp_date ?></td>
                                            <td><?= $row->qty ?></td>
                                            <td><?= $row->qty_box ?></td>
                                            <td><?= $row->qty_pcs ?></td>
                                            <td><?= $row->inputer ?></td>
                                            <td>
                                                <div class="row">
                                                    <h4 class="ml-2"><a href="<?= base_url('editedopname/') . $row->id ?>" class="badge bg-success"><i class="fas fa-pen"></i></a></h4>
                                                    <h4 class="ml-2"><a href="<?= base_url('editedopname/') . $row->id ?>" class="badge bg-danger"><i class="fas fa-trash"></i></a></h4>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
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