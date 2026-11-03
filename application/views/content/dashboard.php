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
            <?php
            $lvuser = (int)$this->session->userdata('lv');
            $jobdesk = strtoupper(trim((string)$this->session->userdata('jobdesk')));
            $username = strtolower(trim((string)$this->session->userdata('username')));
            $isAdminDashboard = !empty($is_admin_dashboard) || $this->session->userdata('is_admin_dashboard') || $username === 'admin' || ($lvuser === 1 && $jobdesk === 'ADMIN');
            ?>
            <?php if ($isAdminDashboard) : ?>

                <section class="content">
                    <div class="container-fluid">
                        <div class="card">
                            <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                                <div>
                                    <h3 class="card-title mb-0">Dashboard Admin</h3>
                                    <small class="text-muted">Monitoring modul utama KARISMA ERP</small>
                                </div>
                                <a href="<?= base_url('admin/stockopname') ?>" class="btn btn-primary btn-sm mt-2 mt-sm-0">
                                    <i class="fas fa-clipboard-check"></i> Stockopname
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <a href="<?= base_url('admin/stockopname') ?>" class="text-decoration-none">
                                            <div class="small-box bg-info mb-0">
                                                <div class="inner">
                                                    <h4>Stockopname</h4>
                                                    <p>Dashboard admin stok fisik</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-boxes"></i>
                                                </div>
                                                <span class="small-box-footer">
                                                    Buka dashboard <i class="fas fa-arrow-circle-right"></i>
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            <?php elseif ($jobdesk == 'DIREKTUR') : ?>

                <section class="content">
                    <div class="container-fluid">
                        <div class="card">
                            <div class="card-body">
                                <h2>Modules List</h2>
                                <a href="<?= base_url('keuangan') ?>" class="btn btn-primary mb-2">Daily Stock</a>
                            </div>
                        </div>
                    </div>
                </section>

            <?php elseif ($jobdesk == 'ADMINGA') : ?>
                <section class="content">
                    <div class="container-fluid">
                        <div class="card">
                            <div class="card-body">
                                Tamu
                            </div>
                        </div>
                    </div>
                </section>

            <?php endif; ?>
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
