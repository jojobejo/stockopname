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
                <section class="content">
                    <div class="row">
                        <div class="col-auto">
                            <a href="<?= base_url('ics/by_expdate') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-arrow-left"></i></a>
                        </div>
                        <?php if ($this->session->userdata('lv') == '1') : ?>
                            <div class="col-auto">
                                <a href="<?= base_url('ics/icsdo') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-minus-circle"></i> Out Today</a>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('ics/icspo') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-plus-circle"></i> Data LPB</a>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('ics/icsdo/dohistori') ?>" class="btn btn-md btn-secondary w-100 mb-3"><i class="fas fa-recycle"></i> Histori DO</a>
                            </div>
                        <?php else : ?>
                        <?php endif; ?>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="container-fluid">

                                <form action="<?= base_url('ics/sc_do_by_date_range') ?>" method="post" class="form-inline mb-3">
                                    <div class="form-group mr-2">
                                        <label for="tgl1" class="mr-2">Tanggal Start</label>
                                        <input type="text" name="tgl1" id="tgl1" class="form-control" placeholder="dd/mm/yyyy" autocomplete="off" required value="<?= isset($tgl1) ? $tgl1 : date('d/m/Y') ?>">
                                    </div>
                                    <div class="form-group mr-2">
                                        <label for="tgl2" class="mr-2">Tanggal End</label>
                                        <input type="text" name="tgl2" id="tgl2" class="form-control" placeholder="dd/mm/yyyy" autocomplete="off" required value="<?= isset($tgl2) ? $tgl2 : date('d/m/Y') ?>">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Cari</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </section>
            </div>
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