<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>

        <div class="content-wrapper">

            <div class="row m-2 ">
                <div class="col-auto">
                    <a href="<?= base_url('ics/ics_diffrent') ?>" class="btn btn-md btn-primary">Dashboard</a>
                </div>
                <div class="col-auto">
                    <a href="<?= base_url('ics/mutasi_barang/input') ?>" class="btn btn-md btn-success">Input Data</a>
                </div>
            </div>

            <div class="card m-3">
                <div class="card-body">
                    <div class="row mb-3">

                        <div class="col-md-3">
                            <label>Gudang Asal</label>
                            <select id="filter_gudang" class="form-control">
                                <option value="">-- Semua Gudang --</option>
                                <?php foreach ($gudang as $g) : ?>
                                    <option value="<?= $g->id_gudang ?>"><?= $g->nama_gudang ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Rentang Tanggal</label>
                            <input type="text" id="filter_tanggal" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label>Status Mutasi</label>
                            <select id="filter_status" class="form-control">
                                <option value="">-- Status --</option>
                                <option value="POSTED">POSTED</option>
                                <option value="UNPOST">UNPOST</option>
                                <option value="ROLLBACK">ROLLBACK</option>
                            </select>
                        </div>

                        <div class="col-md-3 mt-2">
                            <label> </label>
                            <button class="btn btn-primary btn-block" id="btnFilter">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>

                    </div>
                    <table class="table table-bordered table-striped" id="mutasi_barang" width="100%">
                        <thead>
                            <tr>
                                <th>Tanggal Input</th>
                                <th>Not Refrensi</th>
                                <th>Gudang Awal</th>
                                <th>Gudang Tujuan</th>
                                <th>Keterangan</th>
                                <th>Inputer</th>
                                <th>Status</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($faktur_mutasi as $fm) : ?>
                                <tr>
                                    <td><?= $fm->tgl_transaksi ?></td>
                                    <td><?= $fm->noreff ?></td>
                                    <td><?= $fm->gudang_a ?></td>
                                    <td><?= $fm->gudang_b ?></td>
                                    <td><?= $fm->keterangan ?></td>
                                    <td><?= $fm->nm_karyawan ?></td>
                                    <td>
                                        <?php if ($fm->status == 'POSTED') : ?>
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle"></i> POSTED
                                            </span>
                                        <?php elseif ($fm->status == 'UNPOST') : ?>
                                            <span class="badge badge-info">
                                                <i class="fas fa-clock"></i> UNPOST
                                            </span>
                                        <?php elseif ($fm->status == 'HOLD') : ?>
                                            <span class="badge badge-warning">
                                                <i class="fas fa-pause"></i> HOLD
                                            </span>
                                        <?php else : ?>
                                            <span class="badge badge-danger">
                                                <i class="fas fa-undo"></i> ROLLBACK
                                            </span>
                                        <?php endif ?>
                                    </td>

                                    <td class="text-center">
                                        <?php if ($fm->status == 'POSTED') : ?>
                                            <button class="btn btn-sm btn-info btn-detail" data-id="<?= $fm->noreff ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning btn-unpost" data-id="<?= $fm->noreff ?>">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger btn-delete" data-id="<?= $fm->noreff ?>">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        <?php elseif ($fm->status == 'UNPOST') : ?>
                                            <button class="btn btn-sm btn-info btn-detail" data-id="<?= $fm->noreff ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger btn-delete" data-id="<?= $fm->noreff ?>">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        <?php elseif ($fm->status == 'HOLD') : ?>
                                            <button class="btn btn-sm btn-info btn-detail" data-id="<?= $fm->noreff ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-success btn-rollback" data-id="<?= $fm->noreff ?>">
                                                <i class="fas fa-thumbs-up"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger btn-delete" data-id="<?= $fm->noreff ?>">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        <?php else : ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="modal fade" id="modalDetailMutasi" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title">Detail Mutasi</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <div class="modal-body">
                                    <div id="infoMutasi"></div>

                                    <table class="table table-sm table-bordered mt-2">
                                        <thead>
                                            <tr>
                                                <th>Kode</th>
                                                <th>Barang</th>
                                                <th>Exp</th>
                                                <th>Qty</th>
                                            </tr>
                                        </thead>
                                        <tbody id="detailMutasiBody"></tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>

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