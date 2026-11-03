

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
                        <div class="card-body">
                            <?php foreach ($count as $c) :
                                $total = $c->jumlah; ?>
                                <?php if ($total == 0) : ?>
                                    <div class="col mb-2">
                                        <h2>Upload CSV</h2>
                                        <?php if (isset($error)) { ?>
                                            <p style="color: red;"><?php echo $error; ?></p>
                                        <?php } ?>
                                        <?php if (isset($success)) { ?>
                                            <p style="color: green;"><?php echo $success; ?></p>
                                        <?php } ?>

                                        <?php echo form_open_multipart('process-csv');
                                        date_default_timezone_set("Asia/Jakarta");
                                        $now = date('Y-m-d H:i:s'); ?>

                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="exampleInputFile" name="csv_file">
                                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                </div>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Upload</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="kdgenerates" value="<?= $kd ?>" hidden>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="dateupload" value="<?= $now ?>" hidden>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-sm btn-block">Upload</button>

                                        <?php echo form_close(); ?>
                                    </div>
                                <?php else : ?>
                                    <?php foreach ($updated as $u) :
                                        date_default_timezone_set("Asia/Jakarta");
                                        $date_c = date_create($u->lastupdated);
                                        $date = date_format($date_c, "Y-m-d H:i:s");
                                    ?>
                                        <h2>Last Updated : <?= format_indo($date) ?></h2>
                                        <a href="<?= base_url('truncateitm/' . $u->kd) ?>" class="btn btn-sm btn-primary">Delete</a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
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


    