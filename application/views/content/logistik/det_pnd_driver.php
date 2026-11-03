<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>
        <?php $this->load->view('content/logistik/modaldetaildriverorder') ?>


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
                            <div class="row">
                                    <a href="<?= base_url('deliveriorder') ?>"><i class="fas fa-play fa-flip-horizontal mr-2"></i></a>
                                <h3 class="card-title">Driver Listing Order</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="modal fade" id="maddnotepending">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Reschedule Driver</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <?php echo form_open_multipart('#'); ?>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="col-sm-3 control-label text-right" for="id_bar">Note Pending<span class="required">*</span></label>
                                                        <div class="col-sm-8"><input class="form-control" type="text" id="pnd_isi" name="pnd_isi" value="" /></div>
                                                        <?php foreach ($detail as $d) : ?>
                                                            <input class="form-control" type="text" id="id_isi[]" name="id_isi[]" value="<?= $d->id ?>" readonly hidden />
                                                            <input class="form-control" type="text" id="kd_deliv_isi[]" name="kd_deliv_isi[]" value="<?= $d->kd_deliveri ?>" hidden readonly />
                                                            <input class="form-control" type="text" id="tgl_isi[]" name="tgl_isi[]" value="<?= $d->tgl_jalan ?>" readonly hidden />
                                                            <input class="form-control" type="text" id="driver_isi[]" name="driver_isi[]" value="<?= $d->kd_driver ?>" hidden readonly />
                                                            <input class="form-control" type="text" id="truk_isi[]" name="truk_isi[]" value="<?= $d->kd_truk ?>" readonly hidden />
                                                            <input class="form-control" type="text" id="destinasi_isi[]" name="destinasi_isi[]" value="<?= $d->destinasi ?>" hidden readonly />
                                                            <input class="form-control" type="text" id="tko_isi[]" name="tko_isi[]" value="<?= $d->nm_toko ?>" readonly hidden />
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                            </form>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <a href="#" class="btn btn-danger mb-2 btn-lg" data-toggle="modal" data-target="#maddnotepending">
                                    <i class="fa fa-solid fa-ban"></i>
                                    Reschedule Driver
                                </a>
                            </div>
                            </form>
                            <table id="tb_det_list_driver_order" class="table table-bordered table-striped" style="text-align: center;">
                                <thead>
                                    <tr>
                                        <th>Nama Driver</th>
                                        <th>Kode Truk</th>
                                        <th>Nomor PLat</th>
                                        <th>Destinasi Toko</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($detail as $d) : ?>
                                        <tr>
                                            <th><?= $d->nama_driver ?></th>
                                            <th><?= $d->kd_truk ?></th>
                                            <th><?= $d->noplat ?></th>
                                            <th><?= $d->nm_toko ?></th>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
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