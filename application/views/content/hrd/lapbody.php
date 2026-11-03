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
                            <h3 class="card-title"><a href="<?= base_url('dashboard') ?>"><i class="fas fa-backward"></i></a> Keluar Masuk Kendaraan</h3>
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
                                    <div class="form-group">
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
                                <table id="tb_lap_distribusia" class="table table-bordered table-striped">
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
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary ml-2">
                                                <i class="fas fa-search"></i>
                                                Cari Data
                                            </button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                              
                                <div class="card-body">
                                    <table id="tb_lap_distribusia" class="table table-bordered table-striped">
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