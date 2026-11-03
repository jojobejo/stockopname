<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>
        <?php $this->load->view('content/hrd/modallaptamuhrd_lb') ?>

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
                            <h3 class="card-title">Buku Tamu</h3>

                        </div>
                        <div class="card-body">
                            <div class="modal-body">
                                <?php echo form_open_multipart('tambah_lap_tamu'); ?>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-3 control-label text-right" for="id_bar">Nama<span class="required">*</span></label>
                                        <div class="col-sm-8"><input class="form-control" type="text" id="nama" name="nama" value="" /></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-3 control-label text-right" for="id_bar">Perusahaan<span class="required">*</span></label>
                                        <div class="col-sm-8"><input class="form-control" type="text" id="perusahaan" name="perusahaan" value="" /></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-3 control-label text-right" for="id_bar">Alamat<span class="required">*</span></label>
                                        <div class="col-sm-8"><input class="form-control" type="text" id="alamat" name="alamat" value="" /></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-3 control-label text-right" for="id_bar">Jumlah Personil<span class="required">*</span></label>
                                        <div class="col-sm-8"><input class="form-control" type="text" id="jumlahpersonil" name="jumlahpersonil" value="" /></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-3 control-label text-right" for="id_bar">Tujuan<span class="required">*</span></label>
                                        <div class="col-sm-8"><input class="form-control" type="text" id="tujuan" name="tujuan" value="" /></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-3 control-label text-right" for="id_bar">Keterangan<span class="required">*</span></label>
                                        <div class="col-sm-8"><input class="form-control" type="text" id="keterangan" name="keterangan" value="" /></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Buku Tamu</h3>
                        </div>
                        <a href="<?= base_url('hrd_lap_tamu') ?>" type="button" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                            Detail All Laporan Buku Tamu
                        </a>
                        <table id="tb_list_order" class="table table-bordered table-striped" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th>Nama Tamu</th>
                                    <th>Perushaan</th>
                                    <th>Alamat</th>
                                    <th>Jumlah Personil</th>
                                    <th>Tujuan</th>
                                    <th>Keterangan</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tamu as $d) : ?>
                                    <tr>
                                        <th><?= $d->nama ?></th>
                                        <th><?= $d->perusahaan ?></th>
                                        <th><?= $d->alamat ?></th>
                                        <th><?= $d->jumlahpersonil ?></th>
                                        <th><?= $d->tujuan ?></th>
                                        <th><?= $d->keterangan ?></th>
                                        <th>
                                            <a href="#" class="btn btn-success btn-sm " data-toggle="modal" data-target="#edittamu<?= $d->id ?>">
                                                <i class="fa fa-solid fa-check"></i>
                                            </a>
                                        </th>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
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