<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>
        <?php $this->load->view('content/hrd/modallapdistribusihrd') ?>

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
                            <h3 class="card-title"> <a href="<?= base_url('hrd_lap_distribusi') ?>"><i class="fas fa-backward"></i></a> Laporan Keluar Masuk Kendaraan</h3>
                        </div>
                        <?php foreach ($laporan as $l) : ?>
                            <div class="modal-body">
                                <?php echo form_open_multipart('hapus_lap_distribusi_hrd'); ?>
                                <h3>DATA YANG TERHAPUS AKAN HILANG PERMANEN</h3>
                                <div class="form-group" hidden>
                                    <div class="row">
                                        <label class="col-sm-3 control-label text-right" for="id_bar">Nopol<span class="required">*</span></label>
                                        <input class="form-control" type="text" id="id_isi" name="id_isi" value="<?= $l->id ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="submit" class="btn btn-primary btn-block">DELETE DATA</button>
                            </div>
                            </form>
                        <?php endforeach; ?>
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