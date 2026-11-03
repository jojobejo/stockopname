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
                        <div class="card-header bg-primary text-white">
                            <?php foreach ($nmbarang as $nm) : ?>
                                <h5 class="card-title">Detail Inputer Barang - <b><?= $nm->nama_barang . ' ' . '(' . $nm->exp_date . ')' ?></b></h5>
                            <?php endforeach; ?>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/by_expdate') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-arrow-left"></i></a>
                                </div>

                                <?php foreach ($nmbarang as $nm) : ?>
                                    <div class="col-auto">
                                        <a href="<?= base_url('ics/detail_allbarang/') . $nm->nama_barang ?>" class="btn btn-md btn-primary">
                                            <i class="fas fa-boxes"></i> Allbarang
                                        </a>
                                    </div>
                                <?php endforeach; ?>

                                <div class="col-auto">
                                    <button class="btn btn-md btn-secondary">
                                        <i class="fas fa-date"></i> FEFO
                                    </button>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-md btn-primary" id="btndo">
                                        <i class="fas fa-search-minus"></i> DO
                                    </button>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-md btn-primary" id="btnpo">
                                        <i class="fas fa-search-plus"></i> PO
                                    </button>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-md btn-success" data-toggle="modal" data-target="#modalAddOpname">
                                        <i class="fas fa-plus"></i> Add
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-auto">
                                    <!-- <div class="form-group" style="position: relative;background: #fff;">
                                        <h5 class="card-title mt-2 mb-2"><b>Compare By All Barang</b></h5>
                                        <table style="border: 1px solid #000000; border-collapse: collapse; width: 100%; text-align: center;">
                                            <thead>
                                                <tr>
                                                    <th style="border: 1px solid #000000; padding: 5px;">Qty</th>
                                                    <th style="border: 1px solid #000000; padding: 5px;">DO</th>
                                                    <th style="border: 1px solid #000000; padding: 5px;">PO</th>
                                                    <th style="border: 1px solid #000000; padding: 5px;">Qty All</th>
                                                    <th style="border: 1px solid #000000; padding: 5px;">Ics</th>
                                                    <th style="border: 1px solid #000000; padding: 5px;">Selisih</th>
                                                    <th style="border: 1px solid #000000; padding: 5px;">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($detail_allbarang as $dallbarang) : ?>
                                                    <tr>
                                                        <td style="border: 1px solid #000000; padding: 5px;"><?= $dallbarang->qty_awal ?></td>
                                                        <td style="border: 1px solid #000000; padding: 5px;"><?= $dallbarang->DO ?></td>
                                                        <td style="border: 1px solid #000000; padding: 5px;"><?= $dallbarang->PO ?></td>
                                                        <td style="border: 1px solid #000000; padding: 5px;"><?= $dallbarang->qty_all ?></td>
                                                        <td style="border: 1px solid #000000; padding: 5px;"><?= $dallbarang->ICS ?></td>
                                                        <td style="border: 1px solid #000000; padding: 5px;"><?= $dallbarang->selisih ?></td>
                                                        <?php if ($dallbarang->status == '0') : ?>
                                                            <td style="border: 1px solid #000000; padding: 5px;"><a href="#" class="btn btn-sm btn-danger w-100"><i class="fas fa-times"></i></a></td>
                                                        <?php else : ?>
                                                            <td style="border: 1px solid #000000; padding: 5px;"><a href="#" class="btn btn-sm btn-success w-100"><i class="fas fa-check"></i></a></td>
                                                        <?php endif; ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div> -->
                                </div>
                                <div class="col-auto">
                                    <div class="form-group" style="position: relative;background: #fff;">
                                        <h5 class="card-title mt-2 mb-2"><b>Compare By Expired Date</b></h5>
                                        <table style="border: 1px solid #000000; border-collapse: collapse; width: 100%; text-align: center;">
                                            <thead>
                                                <tr>
                                                    <th style="border: 1px solid #000000; padding: 5px;">Expired</th>
                                                    <th style="border: 1px solid #000000; padding: 5px;">Qty</th>
                                                    <th style="border: 1px solid #000000; padding: 5px;">DO</th>
                                                    <th style="border: 1px solid #000000; padding: 5px;">PO</th>
                                                    <th style="border: 1px solid #000000; padding: 5px;">Qty All</th>
                                                    <th style="border: 1px solid #000000; padding: 5px;">Ics</th>
                                                    <th style="border: 1px solid #000000; padding: 5px;">Selisih</th>
                                                    <th style="border: 1px solid #000000; padding: 5px;">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($detail_stok as $dstock) : ?>
                                                    <tr>
                                                        <td style="border: 1px solid #000000; padding: 5px;"><?= $dstock->exp_date ?></td>
                                                        <td style="border: 1px solid #000000; padding: 5px;"><?= $dstock->qty_awal ?></td>
                                                        <td style="border: 1px solid #000000; padding: 5px;"><?= $dstock->DO ?></td>
                                                        <td style="border: 1px solid #000000; padding: 5px;"><?= $dstock->PO ?></td>
                                                        <td style="border: 1px solid #000000; padding: 5px;"><?= $dstock->qty_all ?></td>
                                                        <td style="border: 1px solid #000000; padding: 5px;"><?= $dstock->ICS ?></td>
                                                        <td style="border: 1px solid #000000; padding: 5px;"><?= $dstock->selisih ?></td>
                                                        <?php if ($dstock->status == '0') : ?>
                                                            <td style="border: 1px solid #000000; padding: 5px;"><a href="#" class="btn btn-sm btn-danger w-100"><i class="fas fa-times"></i></a></td>
                                                        <?php else : ?>
                                                            <td style="border: 1px solid #000000; padding: 5px;"><a href="#" class="btn btn-sm btn-success w-100"><i class="fas fa-check"></i></a></td>
                                                        <?php endif; ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h3 class="card-title mb-3"><strong>Tracking Inputer</strong></h3>
                                    <table class="table table-bordered table-sm w-100" id="tracking_input_ics_byexp">
                                        <thead class="bg-info text-white text-center">
                                            <tr>
                                                <th>Nama Barang</th>
                                                <th>Expired Date</th>
                                                <th>QTY</th>
                                                <th>Box</th>
                                                <th>Pcs</th>
                                                <th>Inputer</th>
                                                <th>Keterangan</th>
                                                <th>Input At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($input_log as $log) : ?>
                                                <tr>
                                                    <td><?= $log->nama_barang ?></td>
                                                    <td><?= $log->exp_date ?></td>
                                                    <td><?= $log->qty ?></td>
                                                    <td><?= $log->qty_box ?></td>
                                                    <td><?= $log->qty_pcs ?></td>
                                                    <td><?= $log->inputer ?></td>
                                                    <td><?= $log->keterangan ?></td>
                                                    <td><?= $log->tgl_input ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card DO -->
                    <div class="card mt-3" id="card_do" style="display: none;">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title">Data DO</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-sm" id="ics_do_byexp">
                                <thead class="bg-warning text-white text-center">
                                    <tr>
                                        <th>Kode Faktur</th>
                                        <th>Tgl Transaksi</th>
                                        <th>Nama Barang</th>
                                        <th>Expired Date</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($data_do as $do) : ?>
                                        <tr>
                                            <td><?= $do->kd_faktur ?></td>
                                            <td><?= $do->tgl_transaksi ?></td>
                                            <td><?= $do->nama_barang ?></td>
                                            <td><?= $do->exp_date ?></td>
                                            <td><?= $do->qty ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Card PO -->
                    <div class="card mt-3" id="card_po" style="display: none;">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title">Data PO</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-sm" id="ics_po_byexp">
                                <thead class="bg-warning text-white text-center">
                                    <tr>
                                        <th>Kode Faktur</th>
                                        <th>Tgl Transaksi</th>
                                        <th>Nama Barang</th>
                                        <th>Expired Date</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($data_po as $po) : ?>
                                        <tr>
                                            <td><?= $po->kd_faktur_lpb ?></td>
                                            <td><?= $po->tgl_transaksi ?></td>
                                            <td><?= $po->nama_barang ?></td>
                                            <td><?= $po->exp_date ?></td>
                                            <td><?= $po->qty ?></td>
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

        <!-- Modal Add Opname -->
        <div class="modal fade" id="modalAddOpname" tabindex="-1" role="dialog" aria-labelledby="modalAddOpnameLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="<?= base_url('ics/sv_opname') ?>" method="post">
                    <div class="modal-content">
                        <div class="modal-header bg-success">
                            <h5 class="modal-title" id="modalAddOpnameLabel"><i class="fas fa-box"></i> Input Data Opname</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php foreach ($detail_stok as $dstock) : ?>
                            <div class="modal-body">
                                <!-- Nama Barang -->
                                <div class="form-group">
                                    <label for="nama_barang">Nama Barang</label>
                                    <input type="text" name="nama_barang" class="form-control" value="<?= $dstock->nama_barang ?>" readonly required>
                                    <input type="text" name="dimensi" class="form-control" value="<?= $dstock->dimensi ?>" hidden readonly>
                                    <input type="text" name="id" class="form-control" value="<?= $dstock->id ?>" readonly hidden>
                                    <input type="hidden" name="action" id="action_id" value="formdetail">
                                </div>
                                <!-- Expired Date -->
                                <div class="form-group">
                                    <label for="exp_date">Expired Date</label>
                                    <input type="text" name="exp_date" class="form-control" value="<?= $dstock->exp_date ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="exp_date">Keterangan</label>
                                    <textarea class="form-control" name="keterangan_isi" id="modal_keterangan" required placeholder="Tambahkan keterangan inputer"></textarea>
                                </div>
                                <!-- Qty Box -->
                                <div class="form-group">
                                    <label for="qty_box">Qty Box</label>
                                    <input type="number" name="qty_box" class="form-control" placeholder="0">
                                </div>
                                <!-- Qty Pcs -->
                                <div class="form-group">
                                    <label for="qty_pcs">Qty Pcs</label>
                                    <input type="number" name="qty_pcs" class="form-control" placeholder="0">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </form>
            </div>
        </div>


        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
    </div>

    <script>
        $(document).ready(function() {
            $('#btndo').click(function() {
                $('#card_do').slideToggle(); // toggle DO card
                $('#card_po').slideUp(); // sembunyikan PO saat DO tampil
            });

            $('#btnpo').click(function() {
                $('#card_po').slideToggle(); // toggle PO card
                $('#card_do').slideUp(); // sembunyikan DO saat PO tampil
            });
        });
    </script>