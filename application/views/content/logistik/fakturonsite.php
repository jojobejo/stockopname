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
                <div class="container-fluid">
                    <a href="<?= base_url('logistik') ?>" class="btn btn-primary mb-2"><i class="fas fa-home"></i></a>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-body">
                            <table id="fkcashonsite" class="table table-bordered table-striped">
                                <thead style="background-color: #212529; color:white;">
                                    <tr>
                                        <th>Kode Faktur</th>
                                        <th>Nama Customer</th>
                                        <th>Nama Kios</th>
                                        <th style="text-align: center;">Total Barang</th>
                                        <th style="text-align: center;">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($list_faktur as $l) :
                                        $status = $l->data_sts;
                                    ?>
                                        <tr>
                                            <td><?= $l->kd_faktur ?></td>
                                            <td><?= $l->nama_customer ?></td>
                                            <td><?= $l->nama_kios ?></td>
                                            <td style="text-align: center;"><?= $l->total_barang ?></td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <a href="<?= base_url('detail_fk/') . $l->kd_faktur ?>" class="btn btn-info btn-sm btn-block"><i class="fas fa-eye"></i></a>
                                                    </div>
                                                    <div class="col-6">
                                                        <a href="<?= base_url('insert_tmp/') . $l->kd_faktur . '/' . 'formonsite' ?>" class="btn btn-success btn-sm btn-block"><i class="fas fa-plus"></i></a>
                                                    </div>
                                                </div>
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
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->