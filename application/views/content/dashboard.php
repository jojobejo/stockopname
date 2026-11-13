<body class="hold-transition sidebar-mini">
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
            $isSuperadmin = ($username === 'superadmin' || $jobdesk === 'SUPERADMIN');
            $isAdminDashboard = !empty($is_admin_dashboard)
                || $this->session->userdata('is_admin_dashboard')
                || in_array($username, ['admin', 'admin1', 'superadmin', 'stockopname'], true)
                || in_array($jobdesk, ['SUPERADMIN', 'ADMIN_STOCKOPNAME', 'ADMIN_STOCKOPNAME_LOGISTIK'], true)
                || ($lvuser === 1 && $jobdesk === 'ADMIN');
            ?>
            <?php if ($isAdminDashboard) : ?>

                <section class="content">
                    <div class="container-fluid">
                        <div class="card">
                            <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                                <div>
                                    <h3 class="card-title mb-0"><?= $isSuperadmin ? 'Dashboard Superadmin' : 'Dashboard Admin' ?></h3>
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
                                    <?php if ($isSuperadmin) : ?>
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <a href="<?= base_url('master/user-management') ?>" class="text-decoration-none">
                                            <div class="small-box bg-primary mb-0">
                                                <div class="inner">
                                                    <h4>User Management</h4>
                                                    <p>Kelola user, jobdesk &amp; akses</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-users-cog"></i>
                                                </div>
                                                <span class="small-box-footer">
                                                    Buka modul <i class="fas fa-arrow-circle-right"></i>
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <a href="<?= base_url('keuangan') ?>" class="text-decoration-none">
                                            <div class="small-box bg-success mb-0">
                                                <div class="inner">
                                                    <h4>Keuangan &amp; Daily Stock</h4>
                                                    <p>Monitoring stok &amp; pending PO</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-chart-line"></i>
                                                </div>
                                                <span class="small-box-footer">
                                                    Buka modul <i class="fas fa-arrow-circle-right"></i>
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <a href="<?= base_url('master_barang') ?>" class="text-decoration-none">
                                            <div class="small-box bg-teal mb-0">
                                                <div class="inner">
                                                    <h4>Master Barang</h4>
                                                    <p>Katalog barang &amp; dimensi</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-cubes"></i>
                                                </div>
                                                <span class="small-box-footer">
                                                    Buka modul <i class="fas fa-arrow-circle-right"></i>
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <a href="<?= base_url('logistik') ?>" class="text-decoration-none">
                                            <div class="small-box bg-warning mb-0">
                                                <div class="inner">
                                                    <h4>Logistik &amp; DO</h4>
                                                    <p>Delivery Order &amp; Faktur</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-truck"></i>
                                                </div>
                                                <span class="small-box-footer">
                                                    Buka modul <i class="fas fa-arrow-circle-right"></i>
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <a href="<?= base_url('ics/ics_diffrent') ?>" class="text-decoration-none">
                                            <div class="small-box bg-secondary mb-0">
                                                <div class="inner">
                                                    <h4>Logistik ICS</h4>
                                                    <p>Kontrol stok, LPB &amp; mutasi</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-warehouse"></i>
                                                </div>
                                                <span class="small-box-footer">
                                                    Buka modul <i class="fas fa-arrow-circle-right"></i>
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <a href="<?= base_url('checker') ?>" class="text-decoration-none">
                                            <div class="small-box bg-indigo mb-0">
                                                <div class="inner">
                                                    <h4>Warehouse Checker</h4>
                                                    <p>Aktivitas checker gudang</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-clipboard-list"></i>
                                                </div>
                                                <span class="small-box-footer">
                                                    Buka modul <i class="fas fa-arrow-circle-right"></i>
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <a href="<?= base_url('logistik/distibusi') ?>" class="text-decoration-none">
                                            <div class="small-box bg-olive mb-0">
                                                <div class="inner">
                                                    <h4>Distribusi</h4>
                                                    <p>Monitoring rute &amp; driver</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-route"></i>
                                                </div>
                                                <span class="small-box-footer">
                                                    Buka modul <i class="fas fa-arrow-circle-right"></i>
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <a href="<?= base_url('sales_order') ?>" class="text-decoration-none">
                                            <div class="small-box bg-purple mb-0">
                                                <div class="inner">
                                                    <h4>Sales Order</h4>
                                                    <p>Manajemen order penjualan</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-shopping-cart"></i>
                                                </div>
                                                <span class="small-box-footer">
                                                    Buka modul <i class="fas fa-arrow-circle-right"></i>
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <a href="<?= base_url('hrd/penilaian_lingkungan/admin') ?>" class="text-decoration-none">
                                            <div class="small-box bg-danger mb-0">
                                                <div class="inner">
                                                    <h4>HRD Lingkungan</h4>
                                                    <p>Penilaian lingkungan kantor</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-building"></i>
                                                </div>
                                                <span class="small-box-footer">
                                                    Buka modul <i class="fas fa-arrow-circle-right"></i>
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <a href="<?= base_url('schedule_direktur') ?>" class="text-decoration-none">
                                            <div class="small-box bg-dark mb-0">
                                                <div class="inner">
                                                    <h4>Schedule Direktur</h4>
                                                    <p>Jadwal tamu &amp; agenda</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </div>
                                                <span class="small-box-footer">
                                                    Buka modul <i class="fas fa-arrow-circle-right"></i>
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                    <?php endif; ?>
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
