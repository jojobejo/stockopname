<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>

        <div class="content-wrapper">
            <div class="row m-2">
                <h2 class="m-2">Detail Wilayah Gudang (<?= htmlspecialchars($gudang->nama_gudang) ?>) </h2>

            </div>

            <div class="card m-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-auto mb-3">
                            <a href="<?= base_url('ics/gudang') ?>" class="btn btn-secondary btn-md">
                                Kembali
                            </a>
                        </div>
                        <div class="col-auto mb-3">
                            <a href="<?= base_url('ics/gudang') ?>" class="btn btn-success btn-md">
                                Tambah Wilayah
                            </a>
                        </div>

                    </div>

                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama Wilayah</th>
                                <th width="15%">Total Barang</th>
                                <th width="15%">Status</th>
                                <th width="5%">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($wilayah) : ?>
                                <?php $no = 1;
                                foreach ($wilayah as $w) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($w->nama_wilayah) ?></td>
                                        <td>0</td>
                                        <td>
                                            <?php if ($w->is_active == 1) : ?>
                                                <span class="badge badge-success">Aktif</span>
                                            <?php else : ?>
                                                <span class="badge badge-secondary">Nonaktif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $w->id_wilayah ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        Belum ada wilayah untuk gudang ini
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>


                </div>
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