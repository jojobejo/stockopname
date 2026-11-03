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
                    <div class="row">
                        <a href="<?= base_url('detail_do/') . $kdfaktur ?>" class="btn btn-primary mb-2 ml-2"><i class="fas fa-arrow-circle-left"></i></a>
                        <h3></h3>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title">List Faktur Tambahan</h3>
                        </div>
                        <div class="card-body">
                            <table id="lsfakturbyrute" class="table table-bordered table-striped">
                                <thead style="background-color: #212529; color:white;">
                                    <tr>
                                        <td>TANGGAL TRANSAKSI</td>
                                        <td>FAKTUR</td>
                                        <td>KIOS</td>
                                        <td>Alamat Kios</td>
                                        <td>RUTE</td>
                                        <td>REGIONAL</td>
                                        <td>ITEM</td>
                                        <td>#</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($list_faktur as $l) :
                                        $status = $l->data_sts;
                                    ?>
                                        <tr>
                                            <td><?= $l->tgl_inputer ?></td>
                                            <td><?= $l->kd_faktur ?></td>
                                            <td><?= $l->nama_kios ?></td>
                                            <td><?= $l->alamat_kios  ?></td>
                                            <td><?= $l->kd_rute ?></td>
                                            <td><?= $l->regional ?></td>
                                            <td><?= $l->total_barang ?></td>
                                            <td>
                                                <div class="row">
                                                    <a href="<?= base_url('insertfromdraft/') . $kdfaktur . '/' . $l->kd_faktur ?>" class="btn btn-success btn-block btn-sm"><i class="fas fa-plus"></i></a>
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