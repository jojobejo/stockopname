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

                    <div class="card">
                        <div class="card-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-auto">
                                        <a class="btn btn-primary mb-3" href="<?= base_url('ics/retur') ?>">
                                            <i class="fas fa-home"></i>
                                        </a>
                                    </div>
                                    <div class="col">
                                        <h5 class="mt-2 mb-3">Detail Retur: <?= $kd_retur ?></h5>
                                    </div>
                                </div>

                                <table class="table table-bordered" id="tb_detail_retur">
                                    <thead class="bg-primary text-white text-center">
                                        <tr>
                                            <th>Kode Faktur</th>
                                            <th>Kode Barang</th>
                                            <th>No LOT</th>
                                            <th>Tgl Expired</th>
                                            <th>Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($detail_retur)) : ?>
                                            <?php foreach ($detail_retur as $row) : ?>
                                                <tr>
                                                    <td><?= $row->kd_faktur ?></td>
                                                    <td><?= $row->kd_barang ?></td>
                                                    <td><?= $row->no_lot ?></td>
                                                    <td><?= $row->tgl_expired ?></td>
                                                    <td class="text-center"><?= $row->qty ?></td>
                                                </tr>
                                            <?php endforeach; ?>
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

    <script>
        $(function() {
            $('#tb_detail_retur').DataTable({
                pageLength: 25,
                order: [
                    [0, 'asc']
                ]
            });
        });
    </script>