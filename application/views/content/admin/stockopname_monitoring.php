<body class="hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="Karisma Logo" height="150" width="300">
    </div>

    <?php $this->load->view('partial/main/navbar') ?>
    <?php $this->load->view('partial/main/sidebar') ?>

    <?php
    $so_e = function ($value) {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    };
    $monitoring_summary = $monitoring_summary ?? [];
    $activity_logs = $activity_logs ?? [];
    $team1 = $monitoring_summary['team_1'] ?? [];
    $team2 = $monitoring_summary['team_2'] ?? [];
    $input_source = $monitoring_summary['input_source'] ?? [];
    $wilayah_input = $monitoring_summary['wilayah_input'] ?? [];
    $manual_input = $input_source['manual'] ?? [];
    $request_input = $input_source['request'] ?? [];
    $pending_summary = $pending_summary ?? ($monitoring_summary['pending_summary'] ?? []);
    $pending_mode = in_array(($pending_mode ?? ($pending_summary['mode'] ?? 'add')), ['add', 'subtract'], true) ? ($pending_mode ?? ($pending_summary['mode'] ?? 'add')) : 'add';
    $so_metric = function ($team, $group, $key, $default = 0) {
        return $team[$group][$key] ?? $default;
    };
    ?>

    <div class="content-wrapper opname-monitoring">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-7">
                        <h1 class="m-0">Opname Monitoring</h1>
                    </div>
                    <div class="col-sm-5 text-sm-right mt-2 mt-sm-0">
                        <a href="<?= base_url('admin/stockopname') ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Stockopname
                        </a>
                        <button type="button" class="btn btn-primary btn-sm" id="btnRefreshMonitoring">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="d-flex flex-wrap justify-content-sm-end" style="gap:6px">
                            <a href="<?= base_url('admin/stockopname/monitoring/export-excel/compare-all') ?>" class="btn btn-success btn-sm">
                                <i class="fas fa-file-excel"></i> Compare Stock Buku vs Stock Opname - All Barang
                            </a>
                            <a href="<?= base_url('admin/stockopname/monitoring/export-excel/compare-expired') ?>" class="btn btn-success btn-sm">
                                <i class="fas fa-file-excel"></i> Compare Stock Buku vs Stock Opname - By Expired Date
                            </a>
                            <a href="<?= base_url('admin/stockopname/monitoring/export-excel/master-all') ?>" class="btn btn-success btn-sm">
                                <i class="fas fa-file-excel"></i> Data master Opname All Barang
                            </a>
                            <a href="<?= base_url('admin/stockopname/monitoring/export-excel/master-expired') ?>" class="btn btn-success btn-sm">
                                <i class="fas fa-file-excel"></i> Data Master Opname Barang with Expired Date
                            </a>
                            <a href="<?= base_url('admin/stockopname/monitoring/export-excel/opname-input') ?>" class="btn btn-success btn-sm">
                                <i class="fas fa-file-excel"></i> Data Opname
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <style>
                    .opname-monitoring{background:#f5f7fb}
                    .om-panel{background:#fff;border:1px solid #e1e7ef;border-radius:8px;box-shadow:0 8px 22px rgba(16,24,40,.06)}
                    .om-panel-header{padding:14px 16px;border-bottom:1px solid #e8edf3;display:flex;align-items:center;justify-content:space-between;gap:10px}
                    .om-title{font-weight:800;color:#1f2937;margin:0;font-size:16px}
                    .om-muted{color:#64748b;font-size:12px}
                    .om-stat{background:#fff;border:1px solid #e1e7ef;border-radius:8px;padding:16px;min-height:142px;box-shadow:0 8px 22px rgba(16,24,40,.05)}
                    .om-stat-label{font-size:12px;text-transform:uppercase;color:#64748b;font-weight:800}
                    .om-stat-value{font-size:30px;line-height:1.05;font-weight:850;color:#111827;margin-top:8px}
                    .om-stat-meta{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-top:10px;font-size:12px;color:#475569}
                    .om-stat.blue{border-left:4px solid #2563eb}.om-stat.green{border-left:4px solid #16a34a}.om-stat.orange{border-left:4px solid #f59e0b}.om-stat.red{border-left:4px solid #dc2626}
                    .om-result-card{background:#fff;border:1px solid #e1e7ef;border-radius:8px;padding:16px;min-height:226px;box-shadow:0 8px 22px rgba(16,24,40,.05)}
                    .om-result-card.team-1{border-left:4px solid #16a34a}.om-result-card.team-2{border-left:4px solid #f59e0b}
                    .om-result-head{display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:14px}
                    .om-result-title{font-size:16px;font-weight:850;color:#111827;margin:0}
                    .om-result-grid{display:grid;grid-template-columns:1.15fr 1fr 1fr;gap:10px}
                    .om-result-item{border:1px solid #dbe5ef;border-radius:8px;background:#f8fafc;padding:12px;min-width:0}
                    .om-result-item.primary{background:#f0fdf4;border-color:#bbf7d0}
                    .team-2 .om-result-item.primary{background:#fffbeb;border-color:#fde68a}
                    .om-result-value{font-size:28px;font-weight:850;color:#111827;line-height:1.05;margin:8px 0 10px}
                    .om-mini-row{display:grid;grid-template-columns:1fr 1fr;gap:8px}
                    .om-match-row{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:12px}
                    .om-pill{border:1px solid #dbe5ef;border-radius:8px;padding:8px;background:#f8fafc}
                    .om-pill b{display:block;font-size:14px;color:#111827}.om-pill span{font-size:11px;color:#64748b;text-transform:uppercase;font-weight:800}
                    .om-badge{display:inline-flex;align-items:center;border-radius:999px;padding:4px 9px;font-size:12px;font-weight:800;background:#eef2ff;color:#3730a3}
                    .om-badge.all_match{background:#dcfce7;color:#166534}.om-badge.tim_1{background:#dbeafe;color:#1d4ed8}.om-badge.tim_2{background:#ede9fe;color:#6d28d9}.om-badge.not_match{background:#e5e7eb;color:#374151}.om-badge.re_check{background:#fee2e2;color:#991b1b}
                    .om-code{font-family:monospace;font-size:12px;background:#f8fafc;border:1px solid #dbe5ef;border-radius:6px;padding:4px 7px;color:#334155}
                    .om-log{max-height:322px;overflow:auto}
                    .om-log-item{display:flex;align-items:flex-start;gap:10px;padding:12px 0;border-bottom:1px solid #edf2f7}
                    .om-log-item:last-child{border-bottom:0}
                    .om-log-icon{width:34px;height:34px;border-radius:8px;background:#eef6ff;color:#2563eb;display:flex;align-items:center;justify-content:center;flex:0 0 34px}
                    .om-log-title{font-weight:800;color:#1f2937;font-size:13px}
                    .om-source-grid{display:grid;grid-template-columns:1fr;gap:10px;padding:14px}
                    .om-source-item{border:1px solid #dbe5ef;border-radius:8px;background:#f8fafc;padding:12px}
                    .om-source-item.request{background:#fffbeb;border-color:#fde68a}.om-source-item.manual{background:#f0fdf4;border-color:#bbf7d0}.om-source-item.pending{background:#eff6ff;border-color:#bfdbfe}
                    .om-source-head{display:flex;align-items:center;justify-content:space-between;gap:10px}
                    .om-source-label{font-size:12px;text-transform:uppercase;font-weight:850;color:#475569}
                    .om-source-open{display:inline-flex;align-items:center;justify-content:center;gap:6px;border-radius:8px;padding:7px 9px;font-size:12px;font-weight:850}
                    .om-source-open i{margin-right:0}
                    .om-source-value{font-size:28px;font-weight:850;color:#111827;line-height:1.05;margin-top:8px}
                    .om-source-last{font-size:12px;color:#64748b;margin-top:10px}
                    .om-filter{display:grid;grid-template-columns:minmax(220px,1fr) 180px 96px;gap:10px}
                    .om-number-plus{color:#15803d;font-weight:800}.om-number-minus{color:#b91c1c;font-weight:800}.om-number-zero{color:#374151;font-weight:800}
                    .om-table-wrap{padding:16px}
                    .table td,.table th{vertical-align:middle}.btn i{margin-right:5px}.progress{height:8px;border-radius:99px}
                    .om-action-btn{width:32px;height:32px;padding:0;display:inline-flex;align-items:center;justify-content:center}.om-action-btn i{margin-right:0}
                    .om-wilayah-table th{white-space:nowrap;font-size:11px;text-transform:uppercase;color:#64748b}.om-wilayah-table td{white-space:nowrap}
                    @media(min-width:1200px){.om-log{max-height:610px}}
                    @media(max-width:992px){.om-result-grid{grid-template-columns:1fr}}
                    @media(max-width:768px){.content-header h1{font-size:22px}.om-filter{grid-template-columns:1fr}.om-panel-header,.om-result-head{align-items:flex-start;flex-direction:column}.om-stat-value,.om-result-value{font-size:25px}}
                </style>

                <div class="row">
                    <div class="col-xl-6 col-lg-12">
                        <div class="mb-3">
                            <div class="om-result-card team-1" data-card="team_1">
                                <div class="om-result-head">
                                    <h2 class="om-result-title">Result Tim 1</h2>
                                    <span class="om-badge tim_1">Tim 1</span>
                                </div>
                                <div class="om-result-grid">
                                    <div class="om-result-item primary" data-metric="progress_input">
                                        <div class="om-stat-label">Progress Hasil Input Opname</div>
                                        <div class="om-result-value js-percent-input"><?= number_format((float)$so_metric($team1, 'progress_input', 'persen_input'), 2, ',', '.') ?>%</div>
                                        <div class="progress"><div class="progress-bar bg-success js-progress-input" style="width:<?= (float)$so_metric($team1, 'progress_input', 'persen_input') ?>%"></div></div>
                                        <div class="om-match-row">
                                            <div class="om-pill"><span>Input</span><b class="js-input-count"><?= number_format((int)$so_metric($team1, 'progress_input', 'input'), 0, ',', '.') ?></b></div>
                                            <div class="om-pill"><span>Total</span><b class="js-total-count"><?= number_format((int)$so_metric($team1, 'progress_input', 'total'), 0, ',', '.') ?></b></div>
                                        </div>
                                    </div>
                                    <div class="om-result-item" data-metric="compare_all">
                                        <div class="om-stat-label">Compare All Barang</div>
                                        <div class="om-result-value js-percent-match"><?= number_format((float)$so_metric($team1, 'compare_all', 'persen_match'), 2, ',', '.') ?>%</div>
                                        <div class="om-mini-row">
                                            <div class="om-pill"><span>Match</span><b class="js-match-count"><?= number_format((int)$so_metric($team1, 'compare_all', 'match'), 0, ',', '.') ?></b></div>
                                            <div class="om-pill"><span>Not Match</span><b class="js-not-count"><?= number_format((int)$so_metric($team1, 'compare_all', 'not_match'), 0, ',', '.') ?></b></div>
                                        </div>
                                    </div>
                                    <div class="om-result-item" data-metric="compare_lot">
                                        <div class="om-stat-label">Compare By Expired Date</div>
                                        <div class="om-result-value js-percent-match"><?= number_format((float)$so_metric($team1, 'compare_lot', 'persen_match'), 2, ',', '.') ?>%</div>
                                        <div class="om-mini-row">
                                            <div class="om-pill"><span>Match</span><b class="js-match-count"><?= number_format((int)$so_metric($team1, 'compare_lot', 'match'), 0, ',', '.') ?></b></div>
                                            <div class="om-pill"><span>Not Match</span><b class="js-not-count"><?= number_format((int)$so_metric($team1, 'compare_lot', 'not_match'), 0, ',', '.') ?></b></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="om-result-card team-2" data-card="team_2">
                                <div class="om-result-head">
                                    <h2 class="om-result-title">Result Tim 2</h2>
                                    <span class="om-badge tim_2">Tim 2</span>
                                </div>
                                <div class="om-result-grid">
                                    <div class="om-result-item primary" data-metric="progress_input">
                                        <div class="om-stat-label">Progress Hasil Input Opname</div>
                                        <div class="om-result-value js-percent-input"><?= number_format((float)$so_metric($team2, 'progress_input', 'persen_input'), 2, ',', '.') ?>%</div>
                                        <div class="progress"><div class="progress-bar bg-warning js-progress-input" style="width:<?= (float)$so_metric($team2, 'progress_input', 'persen_input') ?>%"></div></div>
                                        <div class="om-match-row">
                                            <div class="om-pill"><span>Input</span><b class="js-input-count"><?= number_format((int)$so_metric($team2, 'progress_input', 'input'), 0, ',', '.') ?></b></div>
                                            <div class="om-pill"><span>Total</span><b class="js-total-count"><?= number_format((int)$so_metric($team2, 'progress_input', 'total'), 0, ',', '.') ?></b></div>
                                        </div>
                                    </div>
                                    <div class="om-result-item" data-metric="compare_all">
                                        <div class="om-stat-label">Compare All Barang</div>
                                        <div class="om-result-value js-percent-match"><?= number_format((float)$so_metric($team2, 'compare_all', 'persen_match'), 2, ',', '.') ?>%</div>
                                        <div class="om-mini-row">
                                            <div class="om-pill"><span>Match</span><b class="js-match-count"><?= number_format((int)$so_metric($team2, 'compare_all', 'match'), 0, ',', '.') ?></b></div>
                                            <div class="om-pill"><span>Not Match</span><b class="js-not-count"><?= number_format((int)$so_metric($team2, 'compare_all', 'not_match'), 0, ',', '.') ?></b></div>
                                        </div>
                                    </div>
                                    <div class="om-result-item" data-metric="compare_lot">
                                        <div class="om-stat-label">Compare By Expired Date</div>
                                        <div class="om-result-value js-percent-match"><?= number_format((float)$so_metric($team2, 'compare_lot', 'persen_match'), 2, ',', '.') ?>%</div>
                                        <div class="om-mini-row">
                                            <div class="om-pill"><span>Match</span><b class="js-match-count"><?= number_format((int)$so_metric($team2, 'compare_lot', 'match'), 0, ',', '.') ?></b></div>
                                            <div class="om-pill"><span>Not Match</span><b class="js-not-count"><?= number_format((int)$so_metric($team2, 'compare_lot', 'not_match'), 0, ',', '.') ?></b></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 mb-3">
                        <div class="om-panel h-100">
                            <div class="om-panel-header">
                                <h2 class="om-title">Log Aktifitas Opname</h2>
                                <a href="<?= base_url('admin/stockopname/monitoring/activity-log') ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-list"></i> Lihat Log
                                </a>
                            </div>
                            <div class="px-3 om-log" id="activityLog">
                                <?php if (empty($activity_logs)) : ?>
                                    <div class="py-3 om-muted">Belum ada aktifitas opname.</div>
                                <?php endif ?>
                                <?php foreach ($activity_logs as $row) : ?>
                                    <div class="om-log-item">
                                        <div class="om-log-icon"><i class="fas fa-clipboard-check"></i></div>
                                        <div>
                                            <div class="om-log-title"><?= $so_e($row['kode_barang'] ?? '-') ?> | <?= $so_e($row['input_by'] ?? '-') ?></div>
                                            <div class="om-muted"><?= $so_e($row['nama_barang'] ?? '-') ?></div>
                                            <div class="om-muted">Qty <?= number_format((int)($row['qty'] ?? 0), 0, ',', '.') ?> | Tim <?= $so_e($row['tim_opname'] ?? '-') ?> | <?= $so_e($row['created_at'] ?? $row['input_at'] ?? '-') ?></div>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 mb-3">
                        <div class="om-panel h-100">
                            <div class="om-panel-header">
                                <h2 class="om-title">Input User Opname</h2>
                                <span class="om-muted">Request, Manual, Pending</span>
                            </div>
                            <div class="om-source-grid" id="inputSourceSummary">
                                <div class="om-source-item pending" data-source="pending">
                                    <div class="om-source-head">
                                        <a href="<?= base_url('admin/stockopname/monitoring/pending-opname') ?>" class="btn btn-outline-primary om-source-open">
                                            <i class="fas fa-box-open"></i> Pending Opname
                                        </a>
                                        <span class="om-badge tim_1">Gudang</span>
                                    </div>
                                    <div class="om-source-value js-total-input"><?= number_format((int)($pending_summary['total_item'] ?? 0), 0, ',', '.') ?></div>
                                    <div class="om-source-last">Qty: <span class="js-total-qty"><?= number_format((int)($pending_summary['total_qty'] ?? 0), 0, ',', '.') ?></span> | Terakhir: <span class="js-last-input"><?= $so_e($pending_summary['last_input'] ?? '-') ?></span></div>
                                    <form id="monitoringPendingModeForm" class="input-group input-group-sm mt-2">
                                        <select id="monitoringPendingMode" name="mode" class="form-control">
                                            <option value="add" <?= $pending_mode === 'add' ? 'selected' : '' ?>>Qty dasar + pending</option>
                                            <option value="subtract" <?= $pending_mode === 'subtract' ? 'selected' : '' ?>>Qty dasar - pending</option>
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-primary" type="submit"><i class="fas fa-save"></i></button>
                                        </div>
                                    </form>
                                </div>
                                <div class="om-source-item request" data-source="request">
                                    <div class="om-source-head">
                                        <a href="<?= base_url('admin/stockopname/monitoring/request-opname') ?>" class="btn btn-outline-warning om-source-open">
                                            <i class="fas fa-clipboard-list"></i> Request Opname
                                        </a>
                                        <span class="om-badge re_check">Master</span>
                                    </div>
                                    <div class="om-source-value js-total-input"><?= number_format((int)($request_input['total_input'] ?? 0), 0, ',', '.') ?></div>
                                    <div class="om-source-last">Terakhir: <span class="js-last-input"><?= $so_e($request_input['last_input'] ?? '-') ?></span></div>
                                </div>
                                <div class="om-source-item manual" data-source="manual">
                                    <div class="om-source-head">
                                        <a href="<?= base_url('admin/stockopname/monitoring/manual-opname') ?>" class="btn btn-outline-success om-source-open">
                                            <i class="fas fa-keyboard"></i> Manual Opname
                                        </a>
                                        <span class="om-badge all_match">Input</span>
                                    </div>
                                    <div class="om-source-value js-total-input"><?= number_format((int)($manual_input['total_input'] ?? 0), 0, ',', '.') ?></div>
                                    <div class="om-source-last">Terakhir: <span class="js-last-input"><?= $so_e($manual_input['last_input'] ?? '-') ?></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="om-panel">
                            <div class="om-panel-header">
                                <div>
                                    <h2 class="om-title">Monitoring Input Opname per Wilayah</h2>
                                    <div class="om-muted">Rekap seluruh input dari Tim 1 dan Tim 2.</div>
                                </div>
                                <select id="wilayahTeamFilter" class="custom-select custom-select-sm" style="width:160px">
                                    <option value="">Semua Tim</option>
                                    <option value="1">Input Tim 1</option>
                                    <option value="2">Input Tim 2</option>
                                </select>
                            </div>
                            <div class="table-responsive p-3">
                                <table class="table table-sm table-bordered table-hover mb-0 om-wilayah-table">
                                    <thead>
                                        <tr>
                                            <th>Wilayah</th>
                                            <th class="text-right">Input Tim 1</th>
                                            <th class="text-right">Input Tim 2</th>
                                            <th class="text-right">Total Input</th>
                                            <th class="text-right">Barang</th>
                                            <th class="text-right">Qty Tim 1</th>
                                            <th class="text-right">Qty Tim 2</th>
                                            <th class="text-right">Total Qty</th>
                                            <th class="text-right">User</th>
                                            <th>Input Terakhir</th>
                                            <th class="text-center">Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody id="wilayahInputSummary">
                                        <?php if (empty($wilayah_input)) : ?>
                                            <tr><td colspan="11" class="text-center om-muted py-3">Belum ada input opname per wilayah.</td></tr>
                                        <?php endif ?>
                                        <?php foreach ($wilayah_input as $row) : ?>
                                            <tr>
                                                <td><span class="om-badge tim_1">Wilayah <?= $so_e($row['wilayah'] ?? '-') ?></span></td>
                                                <td class="text-right"><?= number_format((int)($row['input_tim_1'] ?? 0), 0, ',', '.') ?></td>
                                                <td class="text-right"><?= number_format((int)($row['input_tim_2'] ?? 0), 0, ',', '.') ?></td>
                                                <td class="text-right font-weight-bold"><?= number_format((int)($row['total_input'] ?? 0), 0, ',', '.') ?></td>
                                                <td class="text-right"><?= number_format((int)($row['total_barang'] ?? 0), 0, ',', '.') ?></td>
                                                <td class="text-right"><?= number_format((int)($row['qty_tim_1'] ?? 0), 0, ',', '.') ?></td>
                                                <td class="text-right"><?= number_format((int)($row['qty_tim_2'] ?? 0), 0, ',', '.') ?></td>
                                                <td class="text-right font-weight-bold"><?= number_format((int)($row['total_qty'] ?? 0), 0, ',', '.') ?></td>
                                                <td class="text-right"><?= number_format((int)($row['total_user'] ?? 0), 0, ',', '.') ?></td>
                                                <td><?= $so_e($row['last_input'] ?? '-') ?></td>
                                                <td class="text-center">
                                                    <a href="<?= base_url('admin/stockopname/monitoring/activity-log?wilayah=' . rawurlencode((string)($row['wilayah'] ?? ''))) ?>" class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-list"></i> Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="om-panel h-100">
                            <div class="om-panel-header">
                                <h2 class="om-title">Rekonsiliasi Stok per Tim</h2>
                                <div class="om-filter">
                                    <input type="search" class="form-control form-control-sm" id="omStockSearch" placeholder="Cari barang, kode, expired, lot">
                                    <select class="form-control form-control-sm" id="omStockStatus">
                                        <option value="">Semua status</option>
                                        <option value="all_match">All Match</option>
                                        <option value="tim_1">Match Tim 1</option>
                                        <option value="tim_2">Match Tim 2</option>
                                        <option value="not_match">Tidak Match (Belum Input)</option>
                                        <option value="re_check">Re-check</option>
                                    </select>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="omStockReset"><i class="fas fa-undo"></i>Reset</button>
                                </div>
                            </div>
                            <div class="om-table-wrap">
                                <table class="table table-sm table-hover table-bordered w-100" id="tableMonitoringStockTeam">
                                    <thead>
                                        <tr>
                                            <th>Nama Barang</th>
                                            <th>expired</th>
                                            <th>Saldo Buku</th>
                                            <th>Pending</th>
                                            <th>Saldo Sistem</th>
                                            <th>Tim 1</th>
                                            <th>Selisih T1</th>
                                            <th>Tim 2</th>
                                            <th>Selisih T2</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="om-panel">
                            <div class="om-panel-header">
                                <h2 class="om-title">Compare Stock Buku vs Stock Opname - All Barang</h2>
                                <div>
                                    <select class="custom-select custom-select-sm js-status-filter" data-target="#tableCompareAll" style="width:150px">
                                        <option value="">Semua Status</option>
                                        <option value="all_match">All Match</option>
                                        <option value="tim_1">Tim 1</option>
                                        <option value="tim_2">Tim 2</option>
                                        <option value="re_check">Re-Check</option>
                                    </select>
                                </div>
                            </div>
                            <div class="table-responsive p-3">
                                <table class="table table-sm table-bordered table-hover w-100" id="tableCompareAll">
                                    <thead>
                                        <tr>
                                            <th>Nama Barang</th>
                                            <th class="text-right">Stock Buku</th>
                                            <th class="text-right">Qty Tim 1</th>
                                            <th class="text-right">Qty Tim 2</th>
                                            <th>Status</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <div class="om-panel">
                            <div class="om-panel-header">
                                <h2 class="om-title">Compare Stock Buku vs Stock Opname - By Expired Date</h2>
                                <div>
                                    <select class="custom-select custom-select-sm js-status-filter" data-target="#tableCompareLot" style="width:150px">
                                        <option value="">Semua Status</option>
                                        <option value="all_match">All Match</option>
                                        <option value="tim_1">Tim 1</option>
                                        <option value="tim_2">Tim 2</option>
                                        <option value="re_check">Re-Check</option>
                                    </select>
                                </div>
                            </div>
                            <div class="table-responsive p-3">
                                <table class="table table-sm table-bordered table-hover w-100" id="tableCompareLot">
                                    <thead>
                                        <tr>
                                            <th>Nama Barang</th>
                                            <th>Exp Date</th>
                                            <th class="text-right">Stock Buku</th>
                                            <th class="text-right">Qty Tim 1</th>
                                            <th class="text-right">Qty Tim 2</th>
                                            <th>Status</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                </table>
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
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0
        </div>
    </footer>
</div>

<script>
$(function () {
    var wilayahInputRows = <?= json_encode(array_values($wilayah_input), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    function numberId(value) {
        return new Intl.NumberFormat('id-ID').format(parseInt(value || 0, 10));
    }

    function formatExpiredDate(value) {
        if (!value) { return '-'; }
        var match = String(value).match(/^(\d{4})-(\d{2})-(\d{2})(?:[ T].*)?$/);
        return match ? match[3] + '/' + match[2] + '/' + match[1] : value;
    }

    function percentId(value) {
        return new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2}).format(parseFloat(value || 0)) + '%';
    }

    function escapeHtml(value) {
        return $('<div>').text(value || '-').html();
    }

    function notifyMonitoring(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({toast:true,position:'top-end',icon:'success',title:message,showConfirmButton:false,timer:2200});
        } else {
            alert(message);
        }
    }

    function statusBadge(status) {
        var labels = {
            all_match: 'All Match',
            tim_1: 'Tim 1',
            tim_2: 'Tim 2',
            not_match: 'Tidak Match',
            re_check: 'Re-Check'
        };
        var label = labels[status] || 'Re-Check';
        return '<span class="om-badge ' + status + '">' + label + '</span>';
    }

    function codeBadge(value) {
        return '<span class="om-code">' + escapeHtml(value) + '</span>';
    }

    function detailInputButton(value) {
        var kodeBarang = value || '';
        var url = '<?= base_url('admin/stockopname/detail_input_opname') ?>?kode_barang=' + encodeURIComponent(kodeBarang);
        return '<a href="' + url + '" class="btn btn-outline-primary btn-sm om-action-btn" title="Detail input opname">' +
            '<i class="fas fa-eye"></i>' +
        '</a>';
    }

    function renderSelisih(value) {
        value = parseInt(value || 0, 10);
        var cls = value > 0 ? 'om-number-plus' : (value < 0 ? 'om-number-minus' : 'om-number-zero');
        return '<span class="' + cls + '">' + numberId(value) + '</span>';
    }

    function renderTeamQty(row, team) {
        if (parseInt(row['input_tim_' + team] || 0, 10) === 0) {
            return '<span class="text-muted">-</span>';
        }
        return numberId(row['qty_tim_' + team]);
    }

    function renderTeamDifference(row, team) {
        if (parseInt(row['input_tim_' + team] || 0, 10) === 0) {
            return '<span class="text-muted">-</span>';
        }
        return renderSelisih(parseInt(row['qty_tim_' + team] || 0, 10) - parseInt(row.qty_sistem || 0, 10));
    }

    function stockStatusBadge(value) {
        var labels = {
            all_match: 'All Match',
            tim_1: 'Match Tim 1',
            tim_2: 'Match Tim 2',
            not_match: 'Tidak Match',
            re_check: 'Re-check'
        };
        var label = labels[value] || 'Re-check';
        return '<span class="om-badge ' + value + '">' + label + '</span>';
    }

    var stockTeamTable = $('#tableMonitoringStockTeam').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        searchDelay: 350,
        lengthMenu: [[10, 25, 50], [10, 25, 50]],
        ajax: {
            url: '<?= base_url('admin/stockopname/list') ?>',
            type: 'POST',
            data: function (d) {
                d.search = {value: $('#omStockSearch').val()};
                d.status = $('#omStockStatus').val();
            }
        },
        columns: [
            {data: 'nama_barang'},
            {data: 'expired_date', render: formatExpiredDate},
            {data: 'qty_buku', className: 'text-right font-weight-bold', render: function (data) { return numberId(data); }},
            {data: 'qty_pending', className: 'text-right', render: function (data) { return numberId(data); }},
            {data: 'qty_sistem', className: 'text-right font-weight-bold', render: function (data) { return numberId(data); }},
            {data: null, className: 'text-right', render: function (data, type, row) { return renderTeamQty(row, 1); }},
            {data: null, className: 'text-right', render: function (data, type, row) { return renderTeamDifference(row, 1); }},
            {data: null, className: 'text-right', render: function (data, type, row) { return renderTeamQty(row, 2); }},
            {data: null, className: 'text-right', render: function (data, type, row) { return renderTeamDifference(row, 2); }},
            {data: 'kode_barang', className: 'text-center', orderable: false, searchable: false, render: detailInputButton}
        ],
        order: [[0, 'asc']]
    });

    var compareAll = $('#tableCompareAll').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        order: [[0, 'asc']],
        ajax: {
            url: '<?= base_url('admin/stockopname/monitoring/compare-all') ?>',
            type: 'POST',
            data: function (d) {
                d.status = $('.js-status-filter[data-target="#tableCompareAll"]').val();
            }
        },
        columns: [
            {data: 'nama_barang'},
            {data: 'qty_buku', className: 'text-right font-weight-bold', render: function (data) { return numberId(data); }},
            {data: 'qty_tim_1', className: 'text-right font-weight-bold', render: function (data) { return numberId(data); }},
            {data: 'qty_tim_2', className: 'text-right font-weight-bold', render: function (data) { return numberId(data); }},
            {data: 'status_opname', orderable: false, render: statusBadge},
            {data: 'kode_barang', className: 'text-center', orderable: false, searchable: false, render: detailInputButton}
        ]
    });

    var compareLot = $('#tableCompareLot').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        order: [[0, 'asc']],
        ajax: {
            url: '<?= base_url('admin/stockopname/monitoring/compare-lot') ?>',
            type: 'POST',
            data: function (d) {
                d.status = $('.js-status-filter[data-target="#tableCompareLot"]').val();
            }
        },
        columns: [
            {data: 'nama_barang'},
            {data: 'expired_date', defaultContent: '-', render: function (value) { return formatExpiredDate(value); }},
            {data: 'qty_buku', className: 'text-right font-weight-bold', render: function (data) { return numberId(data); }},
            {data: 'qty_tim_1', className: 'text-right font-weight-bold', render: function (data) { return numberId(data); }},
            {data: 'qty_tim_2', className: 'text-right font-weight-bold', render: function (data) { return numberId(data); }},
            {data: 'status_opname', orderable: false, render: statusBadge},
            {data: 'kode_barang', orderable: false, render: codeBadge}
        ]
    });

    function renderSummary(data) {
        $.each(['team_1', 'team_2'], function (_, key) {
            var item = data[key] || {};
            var card = $('[data-card="' + key + '"]');
            var progress = item.progress_input || {};

            card.find('[data-metric="progress_input"] .js-percent-input').text(percentId(progress.persen_input));
            card.find('[data-metric="progress_input"] .js-progress-input').css('width', parseFloat(progress.persen_input || 0) + '%');
            card.find('[data-metric="progress_input"] .js-input-count').text(numberId(progress.input));
            card.find('[data-metric="progress_input"] .js-total-count').text(numberId(progress.total));

            $.each(['compare_all', 'compare_lot'], function (_, metric) {
                var compare = item[metric] || {};
                var metricCard = card.find('[data-metric="' + metric + '"]');
                metricCard.find('.js-percent-match').text(percentId(compare.persen_match));
                metricCard.find('.js-match-count').text(numberId(compare.match));
                metricCard.find('.js-not-count').text(numberId(compare.not_match));
            });
        });

        renderInputSourceSummary(data.input_source || {});
        wilayahInputRows = data.wilayah_input || [];
        renderWilayahInputSummary(wilayahInputRows);
    }

    function renderWilayahInputSummary(rows) {
        var target = $('#wilayahInputSummary');
        var selectedTeam = parseInt($('#wilayahTeamFilter').val() || '0', 10);
        rows = (rows || []).filter(function (row) {
            return selectedTeam === 0 || parseInt(row['input_tim_' + selectedTeam] || 0, 10) > 0;
        });
        if (!rows || rows.length === 0) {
            target.html('<tr><td colspan="11" class="text-center om-muted py-3">Belum ada input untuk tim pada filter ini.</td></tr>');
            return;
        }

        target.html(rows.map(function (row) {
            return '<tr>' +
                '<td><span class="om-badge tim_1">Wilayah ' + escapeHtml(row.wilayah || '-') + '</span></td>' +
                '<td class="text-right">' + numberId(row.input_tim_1) + '</td>' +
                '<td class="text-right">' + numberId(row.input_tim_2) + '</td>' +
                '<td class="text-right font-weight-bold">' + numberId(row.total_input) + '</td>' +
                '<td class="text-right">' + numberId(row.total_barang) + '</td>' +
                '<td class="text-right">' + numberId(row.qty_tim_1) + '</td>' +
                '<td class="text-right">' + numberId(row.qty_tim_2) + '</td>' +
                '<td class="text-right font-weight-bold">' + numberId(row.total_qty) + '</td>' +
                '<td class="text-right">' + numberId(row.total_user) + '</td>' +
                '<td>' + escapeHtml(row.last_input || '-') + '</td>' +
                '<td class="text-center"><a href="<?= base_url('admin/stockopname/monitoring/activity-log') ?>?wilayah=' + encodeURIComponent(row.wilayah || '-') + '" class="btn btn-outline-primary btn-sm"><i class="fas fa-list"></i> Detail</a></td>' +
            '</tr>';
        }).join(''));
    }

    function renderInputSourceSummary(data) {
        $.each(['request', 'manual'], function (_, key) {
            var item = data[key] || {};
            var card = $('[data-source="' + key + '"]');

            card.find('.js-total-input').text(numberId(item.total_input));
            card.find('.js-last-input').text(item.last_input || '-');
        });
    }

    function renderPendingSummary(summary) {
        summary = summary || {};
        var card = $('[data-source="pending"]');
        card.find('.js-total-input').text(numberId(summary.total_item));
        card.find('.js-total-qty').text(numberId(summary.total_qty));
        card.find('.js-last-input').text(summary.last_input || '-');
        if (summary.mode) {
            $('#monitoringPendingMode').val(summary.mode);
        }
    }

    function renderActivity(rows) {
        var target = $('#activityLog');
        if (!rows || rows.length === 0) {
            target.html('<div class="py-3 om-muted">Belum ada aktifitas opname.</div>');
            return;
        }

        target.html(rows.map(function (row) {
            return '<div class="om-log-item">' +
                '<div class="om-log-icon"><i class="fas fa-clipboard-check"></i></div>' +
                '<div>' +
                    '<div class="om-log-title">' + escapeHtml(row.kode_barang) + ' | ' + escapeHtml(row.input_by) + '</div>' +
                    '<div class="om-muted">' + escapeHtml(row.nama_barang) + '</div>' +
                    '<div class="om-muted">Qty ' + numberId(row.qty) + ' | Tim ' + escapeHtml(row.tim_opname) + ' | ' + escapeHtml(row.created_at || row.input_at) + '</div>' +
                '</div>' +
            '</div>';
        }).join(''));
    }

    function refreshMonitoring() {
        $.getJSON('<?= base_url('admin/stockopname/monitoring/summary') ?>', function (res) {
            if (res.status) {
                renderSummary(res.data || {});
                renderPendingSummary((res.data || {}).pending_summary || {});
            }
        });

        $.getJSON('<?= base_url('admin/stockopname/monitoring/activity') ?>', function (res) {
            if (res.status) {
                renderActivity(res.data || []);
            }
        });

        compareAll.ajax.reload(null, false);
        compareLot.ajax.reload(null, false);
        stockTeamTable.ajax.reload(null, false);
    }

    $('.js-status-filter').on('change', function () {
        $($(this).data('target')).DataTable().ajax.reload();
    });

    $('#wilayahTeamFilter').on('change', function () {
        renderWilayahInputSummary(wilayahInputRows);
    });

    $('#omStockSearch').on('keyup', function () {
        stockTeamTable.ajax.reload();
    });

    $('#omStockStatus').on('change', function () {
        stockTeamTable.ajax.reload();
    });

    $('#omStockReset').on('click', function () {
        $('#omStockSearch').val('');
        $('#omStockStatus').val('');
        stockTeamTable.ajax.reload();
    });

    $('#btnRefreshMonitoring').on('click', refreshMonitoring);

    $('#monitoringPendingModeForm').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            url: '<?= base_url('admin/stockopname/pending-mode') ?>',
            type: 'POST',
            dataType: 'json',
            data: {mode: $('#monitoringPendingMode').val()}
        }).done(function (res) {
            notifyMonitoring(res.message || 'Mode pending diproses');
            refreshMonitoring();
        }).fail(function () {
            alert('Server gagal menyimpan mode pending');
        });
    });
});
</script>
