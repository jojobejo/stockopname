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
                    <div class="row mb-2">
                        <div class="col-sm-6">
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <?php if ($this->session->userdata('jobdesk') == 'LOGISTIK') : ?>
                                <div class="row">
                                    <a href="<?= base_url('logistik') ?>" class="btn btn-sm btn-primary mr-2"><i class="fas fa-home "></i></a>
                                    <h3 class="card-title">Master Customer</h3>
                                </div>
                            <?php elseif ($this->session->userdata('jobdesk') != 'LOGISTIK') : ?>
                                <div class="row">
                                    <a href="<?= base_url('') ?>" class="btn btn-sm btn-primary mr-2"><i class="fas fa-home "></i></a>
                                    <h3 class="card-title">Master Customer</h3>
                                </div>
                            <?php endif ?>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 text-right">
                                <button type="button" class="btn btn-success" id="btnTambahMasterCustomer">
                                    <i class="fas fa-plus mr-1"></i> Tambah Customer
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table id="tb_master_customer" class="table table-bordered table-striped" style="text-align: center; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Kode Customer</th>
                                            <th>Nama Customer</th>
                                            <th>Nama Kios</th>
                                            <th>Alamat Kios</th>
                                            <th>Telp 1</th>
                                            <th>Telp 2</th>
                                            <th>Regional</th>
                                            <th>Jam Buka/Tutup</th>
                                            <th>Karakteristik Kios</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <footer class="main-footer">
            <strong>Copyright &copy; 2022 <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0
            </div>
        </footer>

        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>

    <div class="modal fade" id="modalMasterCustomer" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="formMasterCustomer">
                <input type="hidden" id="customer_id" name="id">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="modalMasterCustomerTitle">Tambah Master Customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="kd_customer">Kode Customer</label>
                                <input type="text" class="form-control" id="kd_customer" name="kd_customer" maxlength="25" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="nama_customer">Nama Customer</label>
                                <input type="text" class="form-control" id="nama_customer" name="nama_customer" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="nama_kios">Nama Kios</label>
                                <input type="text" class="form-control" id="nama_kios" name="nama_kios">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="regional">Regional</label>
                                <input type="text" class="form-control" id="regional" name="regional">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="telp1">Telp 1</label>
                                <input type="text" class="form-control" id="telp1" name="telp1">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="telp2">Telp 2</label>
                                <input type="text" class="form-control" id="telp2" name="telp2">
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="jam_buka_tutup">Jam Buka/Tutup</label>
                                <input type="text" class="form-control" id="jam_buka_tutup" name="jam_buka_tutup">
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="alamat_kios">Alamat Kios</label>
                                <textarea class="form-control" id="alamat_kios" name="alamat_kios" rows="2"></textarea>
                            </div>
                            <div class="col-md-12 form-group mb-0">
                                <label for="karakteristik_kios">Karakteristik Kios</label>
                                <textarea class="form-control" id="karakteristik_kios" name="karakteristik_kios" rows="2"></textarea>
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