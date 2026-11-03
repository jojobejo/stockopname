<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="Karisma" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>

        <div class="content-wrapper env-page">
            <section class="content-header pb-2">
                <div class="container-fluid">
                    <div class="env-toolbar">
                        <div>
                            <h1 class="env-title">Dashboard Penilaian Lingkungan</h1>
                            <p class="env-subtitle mb-0">Ringkasan issue, prioritas, dan tindak lanjut area kantor.</p>
                        </div>
                        <div class="btn-group env-nav">
                            <a href="<?= base_url('dashboard_penilaian') ?>" class="btn btn-sm btn-dark active"><i class="fas fa-chart-pie mr-1"></i> Dashboard</a>
                            <a href="<?= base_url('hrd/penilaian_lingkungan/monitoring') ?>" class="btn btn-sm btn-outline-dark"><i class="fas fa-desktop mr-1"></i> Monitoring</a>
                            <a href="<?= base_url('penilaian_lingkungan') ?>" class="btn btn-sm btn-outline-dark"><i class="fas fa-plus mr-1"></i> Form</a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row env-kpi-row" id="dashboardSummary">
                        <div class="col-6 col-md-3 col-xl">
                            <div class="env-kpi">
                                <span class="env-kpi-icon text-info"><i class="fas fa-map-marker-alt"></i></span>
                                <small>Lokasi</small>
                                <strong id="summaryLocations">0</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-xl">
                            <div class="env-kpi">
                                <span class="env-kpi-icon text-warning"><i class="fas fa-inbox"></i></span>
                                <small>Open</small>
                                <strong id="summaryOpen">0</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-xl">
                            <div class="env-kpi">
                                <span class="env-kpi-icon text-secondary"><i class="fas fa-hourglass-half"></i></span>
                                <small>Pending</small>
                                <strong id="summaryPending">0</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-xl">
                            <div class="env-kpi">
                                <span class="env-kpi-icon text-primary"><i class="fas fa-spinner"></i></span>
                                <small>Proses</small>
                                <strong id="summaryInProgress">0</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-xl">
                            <div class="env-kpi">
                                <span class="env-kpi-icon text-success"><i class="fas fa-check-circle"></i></span>
                                <small>Selesai</small>
                                <strong id="summaryResolved">0</strong>
                            </div>
                        </div>
                    </div>

                    <div class="card env-card">
                        <div class="card-body py-3">
                            <div class="form-row align-items-end">
                                <div class="form-group col-md-3 mb-md-0">
                                    <label>Lokasi</label>
                                    <select id="filterLocation" class="form-control form-control-sm">
                                        <option value="">Semua lokasi</option>
                                        <?php foreach ($lokasi as $loc) : ?>
                                            <option value="<?= $loc->id ?>"><?= htmlspecialchars($loc->name) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-3 mb-md-0">
                                    <label>Status</label>
                                    <select id="filterStatus" class="form-control form-control-sm">
                                        <option value="">Semua status</option>
                                        <?php foreach ($status as $st) : ?>
                                            <option value="<?= $st->id ?>"><?= htmlspecialchars($st->name) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-2 mb-md-0">
                                    <label>Mulai</label>
                                    <input id="filterFrom" type="date" class="form-control form-control-sm">
                                </div>
                                <div class="form-group col-md-2 mb-md-0">
                                    <label>Akhir</label>
                                    <input id="filterTo" type="date" class="form-control form-control-sm">
                                </div>
                                <div class="form-group col-md-2 mb-md-0">
                                    <button id="reloadIssues" class="btn btn-sm btn-dark btn-block"><i class="fas fa-sync-alt mr-1"></i> Terapkan</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card env-card">
                                        <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center">
                                            <h3 class="card-title font-weight-bold">Pie Issue per Lokasi</h3>
                                            <button type="button" class="btn btn-xs btn-outline-dark" id="btnLocationChartDetail">
                                                <i class="fas fa-search-plus mr-1"></i> Detail
                                            </button>
                                        </div>
                                        <div class="card-body pt-2">
                                            <div class="env-chart-wrap">
                                                <canvas id="locationPieChart" height="220"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card env-card">
                                        <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center">
                                            <h3 class="card-title font-weight-bold">Pie Prioritas Rating</h3>
                                            <button type="button" class="btn btn-xs btn-outline-dark" id="btnRatingChartDetail">
                                                <i class="fas fa-search-plus mr-1"></i> Detail
                                            </button>
                                        </div>
                                        <div class="card-body pt-2">
                                            <div class="env-chart-wrap">
                                                <canvas id="ratingPieChart" height="220"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card env-card">
                                <div class="card-header border-0 pb-0">
                                    <h3 class="card-title font-weight-bold">Daftar Issue</h3>
                                </div>
                                <div class="card-body pt-2">
                                    <div id="adminFeedback"></div>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover env-table" id="issueTable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Lokasi</th>
                                                    <th>Prioritas</th>
                                                    <th>Deskripsi</th>
                                                    <th>Lapor</th>
                                                    <th>Due</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card env-card">
                                <div class="card-header border-0 pb-0">
                                    <h3 class="card-title font-weight-bold">Issue per Lokasi</h3>
                                </div>
                                <div class="card-body pt-2">
                                    <div id="locationMiniList" class="env-mini-list"></div>
                                </div>
                            </div>
                            <div class="card env-card">
                                <div class="card-header border-0 pb-0">
                                    <h3 class="card-title font-weight-bold">Prioritas Rating</h3>
                                </div>
                                <div class="card-body pt-2">
                                    <div id="ratingMiniList" class="env-mini-list"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="issueUpdateModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content env-modal">
                                <div class="modal-header">
                                    <h5 class="modal-title">Update Issue</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="issueUpdateForm" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div id="updateFeedback"></div>
                                        <input type="hidden" name="issue_id" id="updateIssueId">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label>Rating</label>
                                                <select class="form-control" name="rating_id" id="updateRating" required>
                                                    <option value="">Pilih rating</option>
                                                    <?php foreach ($rating as $rate) : ?>
                                                        <option value="<?= $rate->id ?>"><?= htmlspecialchars($rate->name) ?> (<?= $rate->score ?>)</option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Status</label>
                                                <select class="form-control" name="status_id" id="updateStatus" required>
                                                    <option value="">Pilih status</option>
                                                    <?php foreach ($status as $st) : ?>
                                                        <option value="<?= $st->id ?>"><?= htmlspecialchars($st->name) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Tanggal penyelesaian</label>
                                                <input type="date" class="form-control" name="due_date" id="updateDueDate">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Catatan update</label>
                                            <textarea class="form-control" name="note" id="updateNote" rows="3"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Upload bukti tambahan</label>
                                            <input type="file" name="evidence[]" id="updateEvidence" accept="image/jpeg,image/png" class="form-control" multiple>
                                            <small class="form-text text-muted">JPG/PNG maksimum 5MB per file.</small>
                                        </div>
                                        <div id="currentEvidence" class="mb-3"></div>
                                        <div id="historyLogs"></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-dark"><i class="fas fa-save mr-1"></i> Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="issueBreakdownModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content env-modal">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="issueBreakdownTitle">Detail Analisa Issue</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-3" id="issueBreakdownSummary">
                                        <div class="col-md col-6 mb-2">
                                            <div class="env-breakdown-stat">
                                                <small>Total Issue</small>
                                                <strong id="breakdownTotalIssues">0</strong>
                                            </div>
                                        </div>
                                        <div class="col-md col-6 mb-2">
                                            <div class="env-breakdown-stat">
                                                <small>Open</small>
                                                <strong id="breakdownOpenIssues">0</strong>
                                            </div>
                                        </div>
                                        <div class="col-md col-6 mb-2">
                                            <div class="env-breakdown-stat">
                                                <small>Pending</small>
                                                <strong id="breakdownPendingIssues">0</strong>
                                            </div>
                                        </div>
                                        <div class="col-md col-6 mb-2">
                                            <div class="env-breakdown-stat">
                                                <small>Proses</small>
                                                <strong id="breakdownProgressIssues">0</strong>
                                            </div>
                                        </div>
                                        <div class="col-md col-6 mb-2">
                                            <div class="env-breakdown-stat">
                                                <small>Selesai</small>
                                                <strong id="breakdownResolvedIssues">0</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover env-table" id="issueBreakdownTable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Lokasi</th>
                                                    <th>Prioritas</th>
                                                    <th>Deskripsi</th>
                                                    <th>Lapor</th>
                                                    <th>Due</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <footer class="main-footer">
            <strong>Copyright &copy; 2022 <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block"><b>Version</b> 1.0</div>
        </footer>
        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>
