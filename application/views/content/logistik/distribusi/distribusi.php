<style>
    :root {
        --dash-bg: #0f172a;
        --dash-accent: #10b981;
        --dash-accent-2: #38bdf8;
        --dash-muted: #94a3b8;
        --dash-card: #ffffff;
        --dash-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    }

    #tbl_driver_rute thead th {
        background-color: #1f2937;
        /* slate-800 */
        color: #fff;
        text-align: center;
        font-size: 13px;
        vertical-align: middle;
    }

    #tbl_driver_rute tbody td:first-child {
        background-color: #f3f4f6;
        /* gray-100 */
        font-weight: 600;
        white-space: nowrap;
    }

    #tbl_driver_rute tbody td {
        text-align: center;
        font-size: 13px;
    }

    .cell-zero {
        color: #9ca3af;
        /* gray-400 */
    }

    .cell-active {
        background-color: #dcfce7;
        /* green-100 */
        color: #166534;
        /* green-800 */
        font-weight: 600;
        border-radius: 4px;
    }

    .table-responsive {
        max-height: 65vh;
        overflow-y: auto;
    }

    /* sticky header */
    #tbl_driver_rute thead th {
        position: sticky;
        top: 0;
        z-index: 2;
    }

    .dash-hero {
        background: radial-gradient(1200px 300px at 10% -20%, #38bdf8 0%, rgba(56, 189, 248, 0) 70%),
            radial-gradient(1200px 300px at 90% -20%, #10b981 0%, rgba(16, 185, 129, 0) 70%),
            #0f172a;
        color: #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        box-shadow: var(--dash-shadow);
    }

    .dash-hero h4 {
        font-weight: 700;
        letter-spacing: 0.3px;
        margin-bottom: 6px;
    }

    .dash-filter .btn {
        border-radius: 999px;
        padding: 6px 16px;
        font-weight: 600;
        border: 1px solid rgba(226, 232, 240, 0.2);
        color: #e2e8f0;
        background: rgba(15, 23, 42, 0.15);
    }

    .dash-filter .btn.active {
        background: var(--dash-accent);
        border-color: var(--dash-accent);
        color: #052e2b;
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
    }

    .dash-card {
        background: var(--dash-card);
        border-radius: 16px;
        padding: 16px 18px;
        box-shadow: var(--dash-shadow);
        height: 100%;
        border: 1px solid #eef2f7;
    }

    .dash-card h6 {
        color: #475569;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .dash-metric {
        font-size: 28px;
        font-weight: 700;
        color: #0f172a;
    }

    .dash-sub {
        color: var(--dash-muted);
        font-size: 12px;
        letter-spacing: 0.4px;
        text-transform: uppercase;
    }

    .dash-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .dash-list li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px dashed #e2e8f0;
        font-size: 14px;
    }

    .dash-list li:last-child {
        border-bottom: none;
    }

    .dash-pill {
        background: rgba(56, 189, 248, 0.12);
        color: #0369a1;
        padding: 2px 10px;
        border-radius: 999px;
        font-weight: 600;
        font-size: 12px;
    }

    .chart-wrap {
        position: relative;
        height: 260px;
    }

    .chart-wrap-sm {
        height: 220px;
    }
</style>


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
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-auto">
                            <a href="<?= base_url('histori_driver') ?>" class="btn btn-primary mb-2">Histori Driver</a>
                        </div>
                        <div class="col-auto">
                            <a href="<?= base_url('logistik/distibusi/list_faktur_status') ?>" class="btn btn-success mb-2">List Faktur Terkirim / Belum</a>
                        </div>
                        <div class="col-auto">
                            <a href="<?= base_url('logistik/distibusi/list_total_kirim_do') ?>" class="btn btn-info mb-2">Total Kirim DO</a>
                        </div>
                        <div class="col-auto">
                            <a href="<?= base_url('logistik/distibusi/driver_productif') ?>" class="btn btn-warning mb-2">Analisa Driver</a>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="dash-hero mb-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4>Dashboard Distribusi</h4>
                            <div class="text-small">
                                Ringkasan faktur terkirim dan produktivitas driver tanpa reload halaman.
                            </div>
                            <div class="dash-sub mt-2" id="dash-periode-label">Periode: -</div>
                        </div>
                        <div class="col-md-6 text-md-right mt-3 mt-md-0">
                            <div class="d-flex flex-column flex-md-row justify-content-md-end align-items-md-center">
                                <div class="mr-md-2 mb-2 mb-md-0">
                                    <select class="form-control form-control-sm" id="dash-ket-status">
                                        <option value="">Semua Rute</option>
                                        <option value="KK">Rute KK</option>
                                        <option value="LK">Rute LK</option>
                                    </select>
                                </div>
                                <div class="mr-md-2 mb-2 mb-md-0">
                                    <select class="form-control form-control-sm" id="dash-rute">
                                        <option value="">Semua Rute (Global)</option>
                                        <?php foreach ($all_rute as $rute_item) : ?>
                                            <option value="<?= $rute_item->kd_rute ?>" data-ket-status="<?= $rute_item->ket_status ?>">
                                                <?= $rute_item->kd_rute ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mr-md-2 mb-2 mb-md-0">
                                    <input type="text" class="form-control form-control-sm" id="dash-range" placeholder="Rentang tanggal">
                                </div>
                                <div class="btn-group dash-filter" role="group" aria-label="Filter Periode">
                                    <button type="button" class="btn btn-sm active" data-range="today">Hari Ini</button>
                                    <button type="button" class="btn btn-sm" data-range="month">Bulan Ini</button>
                                    <button type="button" class="btn btn-sm" data-range="year">Tahun Ini</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <div class="dash-card">
                            <h6>Total Faktur Terkirim</h6>
                            <div class="dash-metric" id="dash-total-terkirim">0</div>
                            <div class="dash-sub" id="dash-total-faktur">Total faktur: 0</div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="dash-card">
                            <h6>Driver Produktif</h6>
                            <div class="dash-metric" id="dash-total-driver">0</div>
                            <div class="dash-sub">Driver dengan DO selesai</div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="dash-card">
                            <h6>Faktur Pending</h6>
                            <div class="dash-metric" id="dash-total-pending">0</div>
                            <div class="dash-sub">Menunggu terkirim</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8 mb-3">
                        <div class="dash-card">
                            <h6>Tren Faktur Terkirim</h6>
                            <div class="chart-wrap">
                                <canvas id="chartFaktur"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="dash-card">
                            <h6>Top Driver Produktif</h6>
                            <div class="chart-wrap chart-wrap-sm">
                                <canvas id="chartDriver"></canvas>
                            </div>
                            <ul class="dash-list mt-3" id="dash-top-driver-list">
                                <li>
                                    <span class="text-muted">Memuat...</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <div class="dash-card">
                            <h6>Rute Paling Banyak Dikirim</h6>
                            <ul class="dash-list" id="dash-top-rute-list">
                                <li>
                                    <span class="text-muted">Memuat...</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="dash-card">
                            <h6>Rute Paling Sedikit Dikirim</h6>
                            <ul class="dash-list" id="dash-bottom-rute-list">
                                <li>
                                    <span class="text-muted">Memuat...</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <table id="faktur_result" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Total Faktur</th>
                                    <th>Terkirim</th>
                                    <th>Pending</th>
                                    <th>% Terkirim</th>
                                    <th>% Pending</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($faktur as $f) : ?>
                                    <tr>
                                        <td><?= $f->total_faktur ?></td>
                                        <td><?= $f->total_terkirim ?></td>
                                        <td><?= $f->total_belum ?></td>
                                        <td><?= $f->persen_terkirim ?></td>
                                        <td><?= $f->persen_belum_terkirim ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <table id="tbtotal_tonase" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Rute</th>
                                    <th>Tonase Terkirim</th>
                                    <th>Tonase Pending</th>
                                    <th>Total Tonase</th>
                                    <th>Faktur Terkirim</th>
                                    <th>Faktur Pending</th>
                                    <th>Total Faktur</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($total_tonase as $tot) : ?>
                                    <tr>
                                        <td><?= $tot->rute ?></td>
                                        <td><?= $tot->tonase_terkirim ?></td>
                                        <td><?= $tot->tonase_belum_terkirim ?></td>
                                        <td><?= $tot->total_tonase ?></td>
                                        <td><?= $tot->total_faktur_terkirim ?></td>
                                        <td><?= $tot->total_faktur_pending ?></td>
                                        <td><?= $tot->total_faktur ?></td>
                                        <td><a href="<?= base_url('detail_tonase/') . $tot->rute ?>" class="btn btn-info btn-block"><i class="fas fa-eye"></i></a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- <div class="card">
                    <div class="card-body">
                        <div class="mt-4">
                            <div class="row">
                                <div class="col-md">
                                    <label>Rute</label>
                                    <select name="s_rute" id="s_rute" class="form-control">
                                        <option value="">-- Semua Rute --</option>
                                        <?php foreach ($all_rute as $rute) : ?>
                                            <option value="<?= $rute->kd_rute ?>"><?= $rute->kd_rute ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md">
                                    <label>Rentang Tanggal</label>
                                    <input type="text" id="filter_tanggal" class="form-control" placeholder="YYYY-MM-DD - YYYY-MM-DD">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <table class="table table-bordered table-striped mt-3">
                            <thead>
                                <tr>
                                    <th>Rute</th>
                                    <th>Tanggal Pengiriman</th>
                                </tr>
                            </thead>
                            <tbody id="result_data">
                                <tr>
                                    <td colspan="2" class="text-center">Silakan pilih filter</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div> -->

                <div class="card">
                    <div class="card-body">
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label>Rentang Tanggal</label>
                                <input type="text" id="filter_tanggal_driver" class="form-control" placeholder="YYYY-MM-DD - YYYY-MM-DD">
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <div class="mt-2 text-muted small">
                                Menampilkan distribusi driver berdasarkan rute <strong>(status DO = selesai)</strong>
                            </div>
                            <table class="table table-bordered table-striped" id="tbl_driver_rute">
                                <thead id="thead_rute"></thead>
                                <tbody id="tbody_driver"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tbl_sales">

                            </table>
                        </div>
                    </div>
                </div> -->
                <!-- 
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-4">
                                <label>Rentang Tanggal</label>
                                <input type="text" id="filter_tanggal" class="form-control" placeholder="YYYY-MM-DD - YYYY-MM-DD">
                            </div>

                            <div class="col-md-4">
                                <label>Pilih Rute</label>
                                <select id="filter_rute" class="form-control">
                                    <option value="">-- Pilih Rute --</option>
                                    <?php foreach ($all_rute as $r) : ?>
                                        <option value="<?= $r->kd_rute ?>"><?= $r->kd_rute ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <hr>

                        <h6 class="text-muted">Driver Ready untuk Diploting</h6>

                        <table class="table table-bordered table-striped mt-2">
                            <thead>
                                <tr>
                                    <th>Driver</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_ready">
                                <tr>
                                    <td colspan="2" class="text-center text-muted">
                                        Lengkapi filter terlebih dahulu
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div> -->


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
