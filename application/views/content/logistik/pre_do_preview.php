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
            <?php $this->load->view('content/logistik/modal/modal_do_upload') ?>
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-auto">
                            <a href="<?= base_url('create_do') ?>" class="btn btn-success mb-2"><i class="fas fa-arrow-left"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <h3>Preview Data CSV</h3>
                    <h5 class="text-success">Data Baru (Akan diinsert)</h5>
                    <?php if (!empty($data_baru)) : ?>
                        <table class="table table-bordered table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>Kode Faktur</th>
                                    <th>Tanggal Faktur</th>
                                    <th>Kode Customer</th>
                                    <th>Kode Barang</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data_baru as $row) : ?>
                                    <tr>
                                        <td><?= $row['kd_faktur'] ?></td>
                                        <td><?= $row['tgl_inputer'] ?></td>
                                        <td><?= $row['kd_customer'] ?></td>
                                        <td><?= $row['kd_barang'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <p class="text-muted">Tidak ada data baru.</p>
                    <?php endif; ?>

                    <h5 class="text-danger">Data Duplikat (Tidak akan diinsert)</h5>
                    <?php if (!empty($data_duplikat)) : ?>
                        <table class="table table-bordered table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>Kode Faktur</th>
                                    <th>Tanggal Faktur</th>
                                    <th>Kode Customer</th>
                                    <th>Kode Barang</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data_duplikat as $row) : ?>
                                    <tr>
                                        <td><?= $row['kd_faktur'] ?></td>
                                        <td><?= $row['tgl_inputer'] ?></td>
                                        <td><?= $row['kd_customer'] ?></td>
                                        <td><?= $row['kd_barang'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <p class="text-muted">Tidak ada duplikat.</p>
                    <?php endif; ?>

                    <form action="<?= base_url('pre_do/insert_csv') ?>" method="post">
                        <button type="submit" class="btn btn-success" <?= empty($data_baru) ? 'disabled' : '' ?>>Konfirmasi Insert</button>
                        <a href="<?= base_url('pre_do') ?>" class="btn btn-secondary">Batal</a>
                    </form>

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