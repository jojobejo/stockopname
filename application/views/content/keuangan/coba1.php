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
                    <a href="<?= base_url('keuangan') ?>" class="btn btn-md btn-primary mb-2">Home</a>
                    <a href="<?= base_url('insertmodule') ?>" class="btn btn-md btn-secondary mb-2">Update Stock</a>
                    <a href="<?= base_url('insermodule_lot') ?>" class="btn btn-md btn-primary mb-2">Update Qty-LOT-EXP</a>
                    <a href="<?= base_url('insertmodule_pnd') ?>" class="btn btn-md btn-primary mb-2">Update Pending PO</a>
                    <div class="card">
                        <div class="card-body">
                            <h3>Upload Data Stock</h3>
                            <form method="post" enctype="multipart/form-data" action="<?= base_url('csv_import') ?>">
                                <?php date_default_timezone_set("Asia/Jakarta");
                                $now = date('Y-m-d H:i:s'); ?>
                                <div class="form-group">
                                    <select name="gdgid" id="gdgid" class="form-control">
                                        <option value="1">Global</option>
                                        <option value="2">Gudang Induk</option>
                                        <option value="3">Gudang Rusak</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="exampleInputFile" name="csv_file" required>
                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Upload</span>
                                        </div>
                                    </div>
                                </div>
                                <input type="text" class="form-control" name="kdgenerates" value="<?= $kd ?>" hidden>
                                <input type="text" class="form-control" name="dateupload" value="<?= $now ?>" hidden>
                                <button type="submit" class="btn btn-success btn-sm btn-block">Upload</button>
                            </form>
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