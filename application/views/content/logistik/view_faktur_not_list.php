<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-auto">
                            <a href="<?= base_url('logistik') ?>" class="btn btn-secondary mb-2">
                                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard Logistik
                            </a>
                        </div>
                        <div class="col-auto">
                            <button type="button" id="btn_reload_faktur_not_list" class="btn btn-primary mb-2">
                                <i class="fas fa-sync-alt"></i> Muat Ulang
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="card">
                    <div class="card-body">
                        <h4>List Faktur Barang Belum Terdaftar Master Barang</h4>
                        <table class="table table-bordered table-striped" id="tbl_faktur_not_list">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Input</th>
                                    <th>Kode Faktur</th>
                                    <th>Nama Customer</th>
                                    <th>Kios</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Total Row</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>

        <div class="modal fade" id="modal_edit_kd_barang_not_list" tabindex="-1" role="dialog" aria-labelledby="modalEditKdBarangLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="modalEditKdBarangLabel"><i class="fas fa-edit"></i> Edit Kode Barang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_kd_faktur">
                        <input type="hidden" id="edit_old_kd_barang">

                        <div class="form-group">
                            <label>Kode Faktur</label>
                            <input type="text" class="form-control" id="edit_kd_faktur_label" readonly>
                        </div>
                        <div class="form-group">
                            <label>Kode Barang Lama</label>
                            <input type="text" class="form-control" id="edit_old_kd_barang_label" readonly>
                        </div>
                        <div class="form-group">
                            <label>Kode Barang Baru</label>
                            <input type="text" class="form-control" id="edit_new_kd_barang" placeholder="Masukkan kode barang baru">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="btn_submit_edit_kd_barang">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        <footer class="main-footer">
            <strong>Copyright &copy; 2022 <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0
            </div>
        </footer>

        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>
</body>