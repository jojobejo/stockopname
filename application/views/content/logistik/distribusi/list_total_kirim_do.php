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
                <style>
                    #tbl_kirim_do tr.row-min td {
                        background-color: #fff7ed;
                        color: #9a3412;
                        font-weight: 600;
                    }

                    #tbl_kirim_do tr.row-max td {
                        background-color: #ecfdf3;
                        color: #166534;
                        font-weight: 700;
                    }
                </style>

                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-3" id="tab_kirim_do" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" href="#" data-status="LK">Luar-Kota</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-status="KK">Kota-Kota</a>
                            </li>
                        </ul>

                        <div class="mb-3" id="summary_kirim_do">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-muted small">Top 2 Terkirim Terbanyak</div>
                                    <ul id="summary_top_kirim_do" class="pl-3 mb-0"></ul>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-muted small">Top 2 Terkirim Tersedikit</div>
                                    <ul id="summary_bottom_kirim_do" class="pl-3 mb-0"></ul>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label>Rentang Tanggal Pengiriman</label>
                                <input type="text" id="filter_tanggal_kirim_do" class="form-control" placeholder="YYYY-MM-DD - YYYY-MM-DD">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" id="btn_filter_kirim_do" class="btn btn-primary btn-block">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" id="btn_reload_kirim_do" class="btn btn-info btn-block">
                                    <i class="fas fa-sync-alt"></i> Muat Ulang
                                </button>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" id="btn_export_kirim_do" class="btn btn-success btn-block">
                                    <i class="fas fa-file-excel"></i> Export Excel
                                </button>
                            </div>
                        </div>
                        <div class="mt-2 small text-muted">
                            Highlight:
                            <span class="badge badge-success">Terkirim Terbanyak</span>
                            <span class="badge badge-warning">Terkirim Tersedikit</span>
                        </div>
                        <hr>

                        <table class="table table-bordered table-striped" id="tbl_kirim_do">
                            <thead>
                                <tr>
                                    <th>Rute</th>
                                    <th>Total Faktur</th>
                                    <th>Faktur Terkirim</th>
                                    <th>Faktur Pending</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
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
