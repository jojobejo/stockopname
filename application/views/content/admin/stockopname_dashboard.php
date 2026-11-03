<body class="hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="Karisma Logo" height="150" width="300">
    </div>

    <?php $this->load->view('partial/main/navbar') ?>
    <?php $this->load->view('partial/main/sidebar') ?>

    <div class="content-wrapper stockopname-admin">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-7">
                        <h1 class="m-0">Dashboard Admin Stockopname</h1>
                    </div>
                    <div class="col-sm-5 text-sm-right mt-2 mt-sm-0">
                        <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Dashboard
                        </a>
                        <a href="<?= base_url('admin/stockopname/input') ?>" class="btn btn-success btn-sm">
                            <i class="fas fa-mobile-alt"></i> Input Opname
                        </a>
                        <button type="button" class="btn btn-primary btn-sm" id="btnRefreshStockopname">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <style>
                    .stockopname-admin{background:#f5f7fb}
                    .so-panel{background:#fff;border:1px solid #e1e7ef;border-radius:8px;box-shadow:0 8px 22px rgba(16,24,40,.06)}
                    .so-panel-header{padding:14px 16px;border-bottom:1px solid #e8edf3;display:flex;align-items:center;justify-content:space-between;gap:10px}
                    .so-panel-title{font-weight:700;color:#1f2937;margin:0;font-size:16px}
                    .so-stat{min-height:108px;padding:16px;border-radius:8px;border:1px solid #e1e7ef;background:#fff}
                    .so-stat-label{font-size:12px;text-transform:uppercase;color:#64748b;font-weight:700}
                    .so-stat-value{font-size:26px;font-weight:800;color:#111827;line-height:1.1;margin-top:8px}
                    .so-stat-meta{font-size:12px;color:#6b7280;margin-top:6px}
                    .so-stat.info{border-left:4px solid #2563eb}.so-stat.success{border-left:4px solid #16a34a}.so-stat.warning{border-left:4px solid #f59e0b}.so-stat.danger{border-left:4px solid #dc2626}.so-stat.master{border-left:4px solid #0f766e}
                    .so-stat-link{display:block;color:inherit}.so-stat-link:hover{color:inherit;text-decoration:none}.so-stat-link:hover .so-stat{border-color:#8bb8ad;box-shadow:0 12px 28px rgba(15,118,110,.12)}
                    .so-filter{display:grid;grid-template-columns:minmax(220px,1fr) 180px 96px;gap:10px}
                    .so-chart-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px;padding:16px}
                    .so-chart-card{border:1px solid #e1e7ef;border-radius:8px;padding:14px;min-height:310px;display:flex;flex-direction:column}
                    .so-chart-title{font-size:13px;font-weight:800;color:#334155;text-transform:uppercase;margin:0 0 4px}
                    .so-chart-meta{font-size:12px;color:#64748b;margin:0 0 10px}
                    .so-chart-canvas{height:220px;position:relative;flex:1}
                    .so-table-wrap{padding:16px}
                    .so-badge{display:inline-flex;align-items:center;border-radius:999px;padding:4px 9px;font-size:12px;font-weight:700}
                    .so-badge.all_match{background:#dcfce7;color:#166534}.so-badge.tim_1{background:#dbeafe;color:#1d4ed8}.so-badge.tim_2{background:#ede9fe;color:#6d28d9}.so-badge.not_match{background:#e5e7eb;color:#374151}.so-badge.re_check{background:#fee2e2;color:#991b1b}
                    .so-number-plus{color:#15803d;font-weight:700}.so-number-minus{color:#b91c1c;font-weight:700}.so-number-zero{color:#374151;font-weight:700}
                    .progress{height:8px;border-radius:99px}.table td,.table th{vertical-align:middle}.btn i{margin-right:5px}
                    @media(max-width:992px){.so-chart-grid{grid-template-columns:1fr}}
                    @media(max-width:768px){.so-filter{grid-template-columns:1fr}.so-stat-value{font-size:22px}.so-panel-header{align-items:flex-start;flex-direction:column}.content-header h1{font-size:22px}}
                </style>

                <div class="row">
                    <div class="col-6 col-md-4 col-xl mb-3">
                        <div class="so-stat info">
                            <div class="so-stat-label">Progress Input</div>
                            <div class="so-stat-value"><span id="soProgress">0</span>%</div>
                            <div class="progress mt-2"><div class="progress-bar bg-primary" id="soProgressBar" style="width:0%"></div></div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-xl mb-3">
                        <a class="so-stat-link" href="<?= base_url('admin/stockopname/monitoring') ?>">
                            <div class="so-stat success">
                                <div class="so-stat-label">Opname Monitoring</div>
                                <div class="so-stat-value">Buka</div>
                                <div class="so-stat-meta">Module demo pondasi</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-xl mb-3">
                        <div class="so-stat warning">
                            <div class="so-stat-label">Selisih</div>
                            <div class="so-stat-value" id="soSelisih">0</div>
                            <div class="so-stat-meta">Item perlu validasi</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-xl mb-3">
                        <a class="so-stat-link" href="<?= base_url('admin/stockopname/master_opname') ?>">
                            <div class="so-stat master">
                                <div class="so-stat-label">Master Opname</div>
                                <div class="so-stat-value" id="soMasterBarang">0</div>
                                <div class="so-stat-meta">Grup nama, expired, lot</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-xl mb-3">
                        <a class="so-stat-link" href="<?= base_url('admin/stockopname/barang-pending') ?>">
                            <div class="so-stat warning">
                                <div class="so-stat-label">Barang Pending</div>
                                <div class="so-stat-value" id="soBarangPending"><?= number_format((int)($pending_summary['total_item'] ?? 0), 0, ',', '.') ?></div>
                                <div class="so-stat-meta">Fisik gudang, sistem transaksi</div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="so-panel">
                            <div class="so-panel-header">
                                <h2 class="so-panel-title">Stockopname Result</h2>
                            </div>
                            <div class="so-chart-grid">
                                <div class="so-chart-card">
                                    <h3 class="so-chart-title">All Barang</h3>
                                    <p class="so-chart-meta" id="soAllBarangMeta">0% match; cukup salah satu tim</p>
                                    <div class="so-chart-canvas">
                                        <canvas id="soAllBarangChart"></canvas>
                                    </div>
                                </div>
                                <div class="so-chart-card">
                                    <h3 class="so-chart-title">By Expired Date + LOT</h3>
                                    <p class="so-chart-meta" id="soFefoMeta">0% match; cukup salah satu tim</p>
                                    <div class="so-chart-canvas">
                                        <canvas id="soFefoChart"></canvas>
                                    </div>
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
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0
        </div>
    </footer>
</div>

<script>
window.addEventListener('load', function () {
    var charts = {};

    function formatNumber(value) {
        value = parseInt(value || 0, 10);
        return value.toLocaleString('id-ID');
    }

    function formatExpiredDate(value) {
        if (!value) return '-';
        var match = String(value).match(/^(\d{4})-(\d{2})-(\d{2})(?:[ T].*)?$/);
        return match ? match[3] + '/' + match[2] + '/' + match[1] : value;
    }

    function toast(icon, title) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: icon,
            title: title,
            showConfirmButton: false,
            timer: 2200
        });
    }

    function loadWidgets(showToast) {
        $.getJSON('<?= base_url('admin/stockopname/widgets') ?>', function (res) {
            if (!res.status) {
                toast('error', res.message || 'Gagal memuat widget');
                return;
            }
            var data = res.data || {};
            renderSummary(data.summary || {});
            renderMasterBarang(data.master_barang || {});
            renderPendingSummary(data.pending_summary || {});
            renderCharts(data);
            if (showToast) toast('success', 'Dashboard stockopname diperbarui');
        }).fail(function () {
            toast('error', 'Server tidak merespons');
        });
    }

    function renderSummary(summary) {
        var progress = parseFloat(summary.progress || 0);
        $('#soProgress').text(progress.toLocaleString('id-ID'));
        $('#soProgressBar').css('width', Math.min(progress, 100) + '%');
        $('#soSelisih').text(formatNumber(summary.selisih_item));
    }

    function renderMasterBarang(summary) {
        $('#soMasterBarang').text(formatNumber(summary.total_item));
    }

    function renderPendingSummary(summary) {
        $('#soBarangPending').text(formatNumber(summary.total_item));
    }

    function renderCharts(data) {
        var allBarang = data.all_barang_result || {};
        var fefo = data.expired_lot_result || data.fefo_result || {};

        $('#soAllBarangMeta').text(formatPercent(allBarang.persen_match) + ' match; Tim 1 atau Tim 2');
        $('#soFefoMeta').text(formatPercent(fefo.persen_match) + ' match; Tim 1 atau Tim 2');

        renderPie('allBarang', 'soAllBarangChart', ['Match', 'Not Match'], [allBarang.match, allBarang.not_match], ['#2563eb', '#f59e0b']);
        renderPie('fefo', 'soFefoChart', ['Match', 'Not Match'], [fefo.match, fefo.not_match], ['#0f766e', '#f59e0b']);
    }

    function renderPie(key, canvasId, labels, values, colors) {
        var ctx = document.getElementById(canvasId);
        values = values.map(function (value) {
            return parseInt(value || 0, 10);
        });
        if (charts[key]) {
            charts[key].data.datasets[0].data = values;
            charts[key].update();
            return;
        }
        charts[key] = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {position: 'bottom'},
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                var total = context.dataset.data.reduce(function (sum, value) {
                                    return sum + value;
                                }, 0);
                                var value = context.parsed || 0;
                                var percent = total > 0 ? (value / total) * 100 : 0;
                                return context.label + ': ' + formatNumber(value) + ' (' + formatPercent(percent) + ')';
                            }
                        }
                    }
                }
            }
        });
    }

    function formatPercent(value) {
        value = parseFloat(value || 0);
        return value.toLocaleString('id-ID', {maximumFractionDigits: 2}) + '%';
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    $('#btnRefreshStockopname').on('click', function () {
        loadWidgets(true);
    });
    loadWidgets(false);
});
</script>
