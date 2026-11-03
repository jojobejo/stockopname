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
                    <div class="row">
                        <div class="col-auto">
                            <a href="<?= base_url('final_result') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-home"></i></a>
                        </div>
                    </div>
                    <div class="card card-primary mt-2 mb-3">
                        <div class="card-header">
                            <h5 class="card-title mt-2">FINAL ALL BARANG</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-sm" id="finalist">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Nama Barang</th>
                                        <th style="text-align: center;">SALDO BUKU</th>
                                        <th style="text-align: center;">QTY 1</th>
                                        <th style="text-align: center;">QTY 2</th>
                                        <th style="text-align: center;">KETERANGAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($listallbarang as $list) : ?>
                                        <tr>
                                            <td><?= $list->kode_barang ?></td>
                                            <td><?= $list->nama_barang ?></td>
                                            <td><?= $list->qty_sistem ?></td>
                                            <td><?= $list->qty_fisik_tim1 ?></td>
                                            <td><?= $list->qty_fisik_tim2 ?></td>
                                            <?php if ($list->keterangan == 'MATCH KEDUANYA') : ?>
                                                <td><a href="#" class="btn btn-sm btn-success">MATCH KEDUANYA</a></td>
                                            <?php elseif ($list->keterangan == 'Ambil dari Tim 1') : ?>
                                                <td><a href="#" class="btn btn-sm btn-warning">TIM 1</a></td>
                                            <?php elseif ($list->keterangan == 'Ambil dari Tim 2') : ?>
                                                <td><a href="#" class="btn btn-sm btn-warning">TIM 2</a></td>
                                            <?php elseif ($list->keterangan == 'CEK ULANG') : ?>
                                                <td><a href="#" class="btn btn-sm btn-danger">CEK ULANG</a></td>
                                            <?php else : ?>
                                                <td><a href="#" class="btn btn-sm btn-danger">-</a></td>
                                            <?php endif; ?>

                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card card-primary mt-2 mb-3">
                        <div class="card-header">
                            <h5 class="card-title mt-2">FINAL FEFO</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-sm" id="finalqtyexp">
                                <thead>
                                    <tr>
                                        <th>NAMA BARANG</th>
                                        <th>EXP DATE</th>
                                        <th>QTY 1</th>
                                        <th>QTY 2</th>
                                        <th>SALDO BUKU</th>
                                        <th>KETERANGAN</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($fefo_final as $fefo) : ?>
                                        <tr>
                                            <td><?= $fefo->nama_barang ?></td>
                                            <td><?= $fefo->exp_date ?></td>
                                            <td><?= $fefo->qty_fisik_tim1 ?></td>
                                            <td><?= $fefo->qty_fisik_tim2 ?></td>
                                            <td><?= $fefo->qty_sistem ?></td>
                                            <?php if ($fefo->keterangan == 'MATCH KEDUANYA') : ?>
                                                <td><a href="#" class="btn btn-sm btn-success">MATCH KEDUANYA</a></td>
                                            <?php elseif ($fefo->keterangan == 'Ambil dari Tim 1') : ?>
                                                <td><a href="#" class="btn btn-sm btn-warning">TIM 1</a></td>
                                            <?php elseif ($fefo->keterangan == 'Ambil dari Tim 2') : ?>
                                                <td><a href="#" class="btn btn-sm btn-warning">TIM 2</a></td>
                                            <?php elseif ($fefo->keterangan == 'CEK ULANG') : ?>
                                                <td><a href="#" class="btn btn-sm btn-danger">CEK ULANG</a></td>
                                            <?php else : ?>
                                                <td><a href="#" class="btn btn-sm btn-danger">-</a></td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
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