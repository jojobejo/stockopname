<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>

        <div class="content-wrapper">

            <div class="content-header">
                <section class="content">
                    <div class="container-fluid">
                        <div class="row align-items-start">
                            <div class="col-12 col-sm-6 col-md-2">
                                <a href="<?= base_url('admstocktracking'); ?>" class="btn btn-primary w-10"><i class="fas fa-home"></i></a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card mt-2">
                        <div class="card-body">
                            <h3>List Request</h3>
                            <table class="table table-bordered table-sm" id="dtrequest_opname">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Expired Date</th>
                                        <th style="text-align: center;">Qty</th>
                                        <th style="text-align: center;">Qty Box</th>
                                        <th style="text-align: center;">Qty Pcs</th>
                                        <th>Inputer</th>
                                        <th style="text-align: center;">TIM</th>
                                        <th style="text-align: center;">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($request as $req) : ?>
                                        <tr>
                                            <td><?= $req->nama_barang ?></td>
                                            <td><?= $req->exp_date ?></td>
                                            <td style="text-align: center;"><?= $req->qty ?></td>
                                            <td style="text-align: center;"><?= $req->qty_box ?></td>
                                            <td style="text-align: center;"><?= $req->qty_pcs ?></td>
                                            <td><?= $req->inputer ?></td>
                                            <td style="text-align: center;"><?= $req->tim ?></td>
                                            <td style="text-align: center;">
                                                <a href="<?= base_url('req_opname_acc/') . $req->id ?>" class="btn btn-md btn-success"><i class="fas fa-check-double"></i></a>
                                            </td>
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
        </aside>
        <!-- /.control-sidebar -->
    </div>