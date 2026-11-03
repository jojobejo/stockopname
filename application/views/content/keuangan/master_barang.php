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
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>

            <!-- /.content-header -->
            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <?php if ($this->session->userdata('jobdesk') == 'LOGISTIK') : ?>
                                <div class="row">
                                    <a href="<?= base_url('logistik') ?>" class="btn btn-sm btn-primary mr-2"><i class="fas fa-home "></i></a>
                                    <h3 class="card-title">Master Barang</h3>
                                </div>
                            <?php elseif ($this->session->userdata('jobdesk') != 'LOGISTIK') : ?>
                                <div class="row">
                                    <a href="<?= base_url('') ?>" class="btn btn-sm btn-primary mr-2"><i class="fas fa-home "></i></a>
                                    <h3 class="card-title">Master Barang</h3>
                                </div>
                            <?php endif ?>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 text-right">
                                <button type="button" class="btn btn-success" id="btnTambahMasterBarang">
                                    <i class="fas fa-plus mr-1"></i> Tambah Barang
                                </button>
                            </div>
                            <table id="tb_master_barang" class="table table-bordered table-striped" style="text-align: center; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Bahan Aktif</th>
                                        <th>Satuan</th>
                                        <th>Berat</th>
                                        <th>Kubikasi</th>
                                        <th>Dimensi</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
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

    <div class="modal fade" id="modalMasterBarang" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="formMasterBarang">
                <input type="hidden" id="master_id" name="id">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="modalMasterBarangTitle">Tambah Master Barang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="kode_barang">Kode Barang</label>
                                <input type="text" class="form-control" id="kode_barang" name="kode_barang" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="nama_barang">Nama Barang</label>
                                <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="bahan_aktif">Bahan Aktif</label>
                                <input type="text" class="form-control" id="bahan_aktif" name="bahan_aktif">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="satuan">Satuan</label>
                                <input type="text" class="form-control" id="satuan" name="satuan">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="berat">Berat</label>
                                <input type="number" step="any" class="form-control" id="berat" name="berat">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="kubikasi">Kubikasi</label>
                                <input type="number" step="any" class="form-control" id="kubikasi" name="kubikasi">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="p">P</label>
                                <input type="number" class="form-control" id="p" name="p" min="0">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="l">L</label>
                                <input type="number" class="form-control" id="l" name="l" min="0">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="t">T</label>
                                <input type="number" class="form-control" id="t" name="t" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>