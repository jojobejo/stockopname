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
                            <div class="row">
                                <a href="<?= base_url('hrd_lap_distribusi')?>"><i class="fas fa-play fa-flip-horizontal mr-2"></i>
                                </a>
                                <h3 class="card-title">Laporan Keluar Masuk Kendaraan</h3>
                            </div>

                        </div>
                        <?php if ($this->session->userdata('departemen') == 'HRD GA' || $this->session->userdata('departemen') == 'IT') : ?>
                            <div class="row">
                                <div class="ml-2">
                                    <button type="button" class="btn btn-primary m-2 ml-3" data-toggle="modal" data-target="#addInventaris">
                                        <i class="fas fa-pen"></i>
                                        Input Laporan Baru
                                    </button>
                                </div>
                                <?php echo form_open_multipart('search_lap_distribusi'); ?>
                                <div class="row ml-1 mt-2">
                                    <div class="form-group ml-3">
                                        <select name="sc_bar" id="sc_bar" class="form-control">
                                            <option value="nopol">Nopol</option>
                                            <option value="nolambung">No.Lambung</option>
                                            <option value="namadriver">Nama Driver</option>
                                            <option value="namahelper">Nama Helper</option>
                                            <option value="tujuan">Tujuan</option>
                                        </select>
                                    </div>
                                    <div class="form-group ml-2">
                                        <input type="text" id="nmsearh" name="nmsearch" class="form-control" placeholder="Input yang akan dicari" value="">
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary ml-2">
                                            <i class="fas fa-search"></i>
                                            Cari Data
                                        </button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row">
                                <div class="ml-2">
                                    <button type="button" class="btn btn-primary m-2 ml-3" data-toggle="modal" data-target="#addInventaris">
                                        <i class="fas fa-pen"></i>
                                        Cari Data
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="tb_lap_distribusi" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tgl Keluar</th>
                                            <th>Tgl Masuk</th>
                                            <th>Nopol</th>
                                            <th>Nolambung</th>
                                            <th>Nama Driver</th>
                                            <th>Nama Helper</th>
                                            <th>Tujuan</th>
                                            <th>Jam Keluar</th>
                                            <th>Km(Keluar)</th>
                                            <th>Jam Masuk</th>
                                            <th>Km(Masuk)</th>
                                            <th>Keterangan</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($vcari as $l) :
                                            $no = 1; ?>
                                            <tr>
                                                <th><?= $l->tglkeluar ?></th>
                                                <th><?= $l->tglmasuk ?></th>
                                                <th><?= $l->nopol ?></th>
                                                <th><?= $l->nolambung ?></th>
                                                <th><?= $l->namadriver ?></th>
                                                <th><?= $l->namahelper ?></th>
                                                <th><?= $l->tujuan ?></th>
                                                <th><?= $l->jamkeluar ?></th>
                                                <th><?= $l->kmkeluar ?></th>
                                                <th><?= $l->jammasuk ?></th>
                                                <th><?= $l->kmmasuk ?></th>
                                                <th><?= $l->keterangan ?></th>
                                                <th>
                                                    <div class="row">
                                                        <a href="#" class="btn btn-warning btn-sm " data-toggle="modal" data-target="#editlap<?= $l->id ?>">
                                                            <i class="fa fa-solid fa-pencil-alt"></i>
                                                        </a>
                                                        <a href="#" class="btn btn-danger btn-sm " data-toggle="modal" data-target="#hapuslap<?= $l->id ?>">
                                                            <i class="fa fa-solid fa-trash-alt"></i>
                                                        </a>
                                                    </div>
                                                </th>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php elseif ($this->session->userdata('departemen') == 'LOGISTIK') : ?>
                            <div class="row">
                                <div class="ml-2">
                                    <a type="button" class="btn btn-success m-2 ml-3" href="<?= base_url('export_excel_lap_distribusi') ?>">
                                        <i class="fas fa-file-excel"></i> &nbsp;
                                        Export Excel
                                    </a>
                                </div>
                                <?php echo form_open_multipart('search_lap_distribusi'); ?>
                                <div class="row ml-1 mt-2">
                                    <div class="form-group ml-3">
                                        <select name="sc_bar" id="sc_bar" class="form-control">
                                            <option value="nopol">Nopol</option>
                                            <option value="nolambung">No.Lambung</option>
                                            <option value="namadriver">Nama Driver</option>
                                            <option value="namahelper">Nama Helper</option>
                                            <option value="tujuan">Tujuan</option>
                                        </select>
                                    </div>
                                    <div class="form-group ml-2">
                                        <input type="text" id="nmsearh" name="nmsearch" class="form-control" placeholder="Input yang akan dicari" value="">
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary ml-2">
                                            <i class="fas fa-search"></i>
                                            Cari Data
                                        </button>
                                    </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card-body">
                                <table id="tb_lap_distribusi" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nopol</th>
                                            <th>Nolambung</th>
                                            <th>Nama Driver</th>
                                            <th>Nama Helper</th>
                                            <th>Tujuan</th>
                                            <th>Tgl Keluar</th>
                                            <th>Jam Keluar</th>
                                            <th>Km(Keluar)</th>
                                            <th>Tgl Masuk</th>
                                            <th>Jam Masuk</th>
                                            <th>Km(Masuk)</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($vcari as $l) :
                                            $no = 1; ?>
                                            <tr>
                                                <th><?= $l->nopol ?></th>
                                                <th><?= $l->nolambung ?></th>
                                                <th><?= $l->namadriver ?></th>
                                                <th><?= $l->namahelper ?></th>
                                                <th><?= $l->tujuan ?></th>
                                                <th><?= $l->tglkeluar ?></th>
                                                <th><?= $l->jamkeluar ?></th>
                                                <th><?= $l->kmkeluar ?></th>
                                                <th><?= $l->tglmasuk ?></th>
                                                <th><?= $l->jammasuk ?></th>
                                                <th><?= $l->kmmasuk ?></th>
                                                <th><?= $l->keterangan ?></th>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
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