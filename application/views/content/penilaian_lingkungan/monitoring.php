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
                            <h1 class="env-title">Monitoring Penilaian Lingkungan</h1>
                            <p class="env-subtitle mb-0">Pantau issue, proses input user, dan master data dalam satu halaman.</p>
                        </div>
                        <div class="btn-group env-nav">
                            <a href="<?= base_url('dashboard_penilaian') ?>" class="btn btn-sm btn-outline-dark"><i class="fas fa-chart-pie mr-1"></i> Dashboard</a>
                            <a href="<?= base_url('hrd/penilaian_lingkungan/monitoring') ?>" class="btn btn-sm btn-dark active"><i class="fas fa-desktop mr-1"></i> Monitoring</a>
                            <a href="<?= base_url('penilaian_lingkungan') ?>" class="btn btn-sm btn-outline-dark"><i class="fas fa-plus mr-1"></i> Form</a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row env-kpi-row">
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
                        <div class="card-header border-0 pb-0">
                            <ul class="nav nav-pills env-tabs" id="environmentMonitoringTabs" role="tablist">
                                <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#envOverview" role="tab"><i class="fas fa-chart-bar mr-1"></i> Overview</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#envPending" role="tab"><i class="fas fa-tasks mr-1"></i> Input User</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#envMaster" role="tab"><i class="fas fa-sliders-h mr-1"></i> Master Data</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="envOverview" role="tabpanel">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <h6 class="font-weight-bold mb-2">Issue per Lokasi</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm env-table" id="locationCountsTable">
                                                    <thead><tr><th>Lokasi</th><th class="text-right">Issue</th></tr></thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <h6 class="font-weight-bold mb-2">Issue berdasarkan Rating</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm env-table" id="ratingCountsTable">
                                                    <thead><tr><th>Rating</th><th>Skor</th><th class="text-right">Issue</th></tr></thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="envPending" role="tabpanel">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="font-weight-bold mb-0">Input User Belum Diproses</h6>
                                        <button id="reloadPendingIssues" class="btn btn-sm btn-outline-dark"><i class="fas fa-sync-alt mr-1"></i> Muat ulang</button>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover env-table" id="pendingIssuesTable">
                                            <thead>
                                                <tr>
                                                    <th>Lokasi</th>
                                                    <th>Deskripsi</th>
                                                    <th>Lapor</th>
                                                    <th>Pelapor</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="envMaster" role="tabpanel">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="env-master-panel">
                                                <h6 class="font-weight-bold">Master Lokasi</h6>
                                                <div id="locationSettingsFeedback"></div>
                                                <form id="locationSettingsForm" class="env-inline-form">
                                                    <input type="hidden" id="locationSettingsId" name="id">
                                                    <div class="form-row">
                                                        <div class="form-group col-md-7">
                                                            <label>Nama lokasi</label>
                                                            <input type="text" class="form-control form-control-sm" id="locationSettingsName" name="name" required>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label>Aktif</label>
                                                            <select class="form-control form-control-sm" id="locationSettingsActive" name="is_active">
                                                                <option value="1">Ya</option>
                                                                <option value="0">Tidak</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-2 d-flex align-items-end">
                                                            <button type="submit" class="btn btn-sm btn-dark btn-block"><i class="fas fa-save"></i></button>
                                                        </div>
                                                    </div>
                                                    <button type="button" id="resetLocationSettings" class="btn btn-xs btn-link px-0">Reset form</button>
                                                </form>
                                                <div class="table-responsive">
                                                    <table class="table table-sm env-table" id="locationSettingsTable">
                                                        <thead><tr><th>Lokasi</th><th>Aktif</th><th>Aksi</th></tr></thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="env-master-panel">
                                                <h6 class="font-weight-bold">Master Rating</h6>
                                                <div id="ratingSettingsFeedback"></div>
                                                <form id="ratingSettingsForm" class="env-inline-form">
                                                    <input type="hidden" id="ratingSettingsId" name="id">
                                                    <div class="form-row">
                                                        <div class="form-group col-md-7">
                                                            <label>Nama rating</label>
                                                            <input type="text" class="form-control form-control-sm" id="ratingSettingsName" name="name" required>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label>Skor</label>
                                                            <input type="number" class="form-control form-control-sm" id="ratingSettingsScore" name="score" required min="0" step="1">
                                                        </div>
                                                        <div class="form-group col-md-2 d-flex align-items-end">
                                                            <button type="submit" class="btn btn-sm btn-dark btn-block"><i class="fas fa-save"></i></button>
                                                        </div>
                                                    </div>
                                                    <button type="button" id="resetRatingSettings" class="btn btn-xs btn-link px-0">Reset form</button>
                                                </form>
                                                <div class="table-responsive">
                                                    <table class="table table-sm env-table" id="ratingSettingsTable">
                                                        <thead><tr><th>Rating</th><th>Skor</th><th>Aksi</th></tr></thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="pendingIssueUpdateModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content env-modal">
                                <div class="modal-header">
                                    <h5 class="modal-title">Update Input User</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="pendingIssueUpdateForm">
                                    <div class="modal-body">
                                        <div id="pendingFeedback"></div>
                                        <input type="hidden" name="issue_id" id="pendingUpdateIssueId">
                                        <div class="env-detail-box mb-3">
                                            <div><small>Lokasi</small><strong id="pendingDetailLocation">-</strong></div>
                                            <div><small>Lapor</small><strong id="pendingDetailReportDatetime">-</strong></div>
                                            <div class="wide"><small>Deskripsi</small><strong id="pendingDetailDescription">-</strong></div>
                                            <div><small>Rating saat ini</small><strong id="pendingDetailCurrentRating">-</strong></div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label>Status</label>
                                                <select class="form-control" name="status_id" id="pendingUpdateStatus" required>
                                                    <option value="">Pilih status</option>
                                                    <?php foreach ($status as $st) : ?>
                                                        <option value="<?= $st->id ?>"><?= htmlspecialchars($st->name) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Rating prioritas</label>
                                                <select class="form-control" name="rating_id" id="pendingUpdateRating">
                                                    <option value="">Pilih rating</option>
                                                    <?php foreach ($ratings as $rt) : ?>
                                                        <option value="<?= $rt->id ?>"><?= htmlspecialchars($rt->name) ?> (<?= $rt->score ?>)</option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Tanggal penyelesaian</label>
                                                <input type="date" class="form-control" name="due_date" id="pendingUpdateDueDate">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-dark"><i class="fas fa-save mr-1"></i> Simpan</button>
                                    </div>
                                </form>
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
