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
                        <div class="modal-body">
                            <?php foreach ($laporan as $l) : ?>
                                <?php echo form_open_multipart('edit_lap_distribusi_hrd'); ?>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-3 control-label text-right" for="id_bar">Nopol<span class="required">*</span></label>
                                        <div class="col-sm-8"><input class="form-control" type="text" id="nopol" name="nopol" value="<?= $l->nopol ?>" />
                                            <input class="form-control" type="text" id="id_isi" name="id_isi" value="<?= $l->id ?>" hidden />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-3 control-label text-right" for="id_bar">No Lambung<span class="required">*</span></label>
                                        <div class="col-sm-8"><input class="form-control" type="text" id="nolambung" name="nolambung" value="<?= $l->nolambung ?>" /></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-3 control-label text-right" for="id_bar">Nama Driver<span class="required">*</span></label>
                                        <div class="col-sm-8"><input class="form-control" type="text" id="nm_driver" name="nm_driver" value="<?= $l->namadriver ?>" /></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-3 control-label text-right" for="id_bar">Nama Helper<span class="required">*</span></label>
                                        <div class="col-sm-8"><input class="form-control" type="text" id="nm_helper" name="nm_helper" value="<?= $l->namahelper ?>" /></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-3 control-label text-right" for="id_bar">Tujuan<span class="required">*</span></label>
                                        <div class="col-sm-8"><input class="form-control" type="text" id="tujuan" name="tujuan" value="<?= $l->tujuan ?>" /></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-3 control-label text-right" for="id_bar">Tanggal Keluar<span class="required">*</span></label>
                                        <div class="col-sm-8"><input class="form-control" type="date" id="tgl_keluar" name="tgl_keluar" value="<?= $l->tglkeluar ?>" /></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-3 control-label text-right" for="id_bar">Jam Keluar<span class="required">*</span></label>
                                        <div class="col-sm-8"><input class="form-control" type="text" id="jm_keluar" name="jm_keluar" value="<?= $l->jamkeluar ?>" /></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-3 control-label text-right" for="id_bar">Km Keluar<span class="required">*</span></label>
                                        <div class="col-sm-8"><input class="form-control" type="number" id="km_keluar" name="km_keluar" value="<?= $l->kmkeluar ?>" /></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-3 control-label text-right" for="id_bar">Tgl Masuk<span class="required">*</span></label>
                                        <div class="col-sm-8"><input class="form-control" type="date" id="tgl_masuk" name="tgl_masuk" value="<?= $l->tglmasuk ?>" /></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-3 control-label text-right" for="id_bar">Jam Masuk<span class="required">*</span></label>
                                        <div class="col-sm-8"><input class="form-control" type="text" id="jm_masuk" name="jm_masuk" value="<?= $l->jammasuk ?>" /></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-3 control-label text-right" for="id_bar">Km Masuk<span class="required">*</span></label>
                                        <div class="col-sm-8"><input class="form-control" type="number" id="km_masuk" name="km_masuk" value="<?= $l->kmmasuk ?>" /></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-3 control-label text-right" for="id_bar">Keterangan<span class="required">*</span></label>
                                        <div class="col-sm-8"><input class="form-control" type="text" id="keterangan" name="keterangan" value="<?= $l->keterangan ?>" /></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                        </form>
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