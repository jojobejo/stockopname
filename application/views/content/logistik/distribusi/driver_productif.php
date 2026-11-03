<style>
    #tbl_driver_productif thead th {
        white-space: nowrap;
        min-width: 110px;
        text-align: center;
    }
    #tbl_driver_productif thead th:first-child {
        min-width: 180px;
        text-align: left;
    }
    #tbl_driver_productif tbody td {
        white-space: nowrap;
    }
    #tbl_driver_productif tfoot td {
        font-weight: 700;
        background-color: #f8fafc;
    }
</style>

<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-auto">
                            <a href="<?= base_url('logistik/distibusi') ?>" class="btn btn-secondary mb-2">
                                <i class="fas fa-arrow-left"></i> Kembali ke Distribusi
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <ul class="nav nav-tabs" id="tab_driver_productif" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#" data-status="">Seluruh Kota</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#" data-status="LK">Luar-Kota</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#" data-status="KK">Kota-Kota</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Rentang Tanggal Pengiriman</label>
                                <input type="text" id="filter_tanggal_driver_productif" class="form-control" placeholder="YYYY-MM-DD - YYYY-MM-DD">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" id="btn_filter_driver_productif" class="btn btn-primary btn-block">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" id="btn_reload_driver_productif" class="btn btn-info btn-block">
                                    <i class="fas fa-sync-alt"></i> Muat Ulang
                                </button>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" id="btn_export_driver_productif" class="btn btn-success btn-block">
                                    <i class="fas fa-file-excel"></i> Export Excel
                                </button>
                            </div>
                        </div>
                        <div class="mt-3" id="summary_driver_productif">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-muted small">Top 3 Driver Produktif</div>
                                    <ul id="summary_top_driver" class="pl-3 mb-0"></ul>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-muted small">Top 3 Driver Terendah</div>
                                    <ul id="summary_bottom_driver" class="pl-3 mb-0"></ul>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2 small text-muted">
                            Menampilkan driver produktif berdasarkan status DO = 2 dan rentang tanggal pengiriman.
                        </div>
                        <hr>

                        <div class="table-responsive" style="overflow-x:auto;">
                        <table class="table table-bordered table-striped" id="tbl_driver_productif">
                            <thead>
                                <tr>
                                    <th>Nama Driver</th>
                                    <th>Total Kirim</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot></tfoot>
                        </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <footer class="main-footer">
            <strong>Copyright &copy; 2022 <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0
            </div>
        </footer>

        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>
</body>
