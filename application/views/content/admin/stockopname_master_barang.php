<?php
$pageHeading = $page_heading ?? 'Master Opname';
$tableTitle = $table_title ?? 'Data Master Opname';
$routeBase = $route_base ?? 'admin/stockopname/master_opname';
$qrcodeRouteBase = $qrcode_route_base ?? 'admin/stockopname/qrcode';
$showQtyZeroWidget = !empty($show_qty_zero_widget);
$showResetQrcode = !isset($show_reset_qrcode) || !empty($show_reset_qrcode);
$showStockCardRangePrint = !empty($show_stock_card_range_print);
$qtyZeroCount = (int)($qty_zero_count ?? 0);
?>
<body class="hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="Karisma Logo" height="150" width="300">
    </div>

    <?php $this->load->view('partial/main/navbar') ?>
    <?php $this->load->view('partial/main/sidebar') ?>

    <div class="content-wrapper master-barang-page">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-7">
                        <h1 class="m-0"><?= html_escape($pageHeading) ?></h1>
                    </div>
                    <div class="col-sm-5 text-sm-right mt-2 mt-sm-0">
                        <a href="<?= base_url('admin/stockopname') ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Stockopname
                        </a>
                        <a href="<?= base_url('admin/stockopname/input') ?>" class="btn btn-success btn-sm">
                            <i class="fas fa-mobile-alt"></i> Input Opname
                        </a>
                        <a href="<?= base_url('admin/stockopname/master_barang') ?>" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-cubes"></i> Master Barang
                        </a>
                        <?php if (!$showQtyZeroWidget) : ?>
                            <a href="<?= base_url('admin/stockopname/master_opname') ?>" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-boxes"></i> Qty Aktif
                            </a>
                        <?php endif; ?>
                        <button type="button" class="btn btn-primary btn-sm" id="btnTambahMaster" data-toggle="modal" data-target="#modalTambahMaster">
                            <i class="fas fa-plus"></i> Tambah Master
                        </button>
                        <button type="button" class="btn btn-primary btn-sm" id="btnRefreshMasterBarang">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <style>
                    .master-barang-page{background:#f5f7fb}.mb-panel{background:#fff;border:1px solid #e1e7ef;border-radius:8px;box-shadow:0 8px 22px rgba(16,24,40,.06)}
                    .mb-panel-header{padding:14px 16px;border-bottom:1px solid #e8edf3;display:flex;align-items:center;justify-content:space-between;gap:10px}.mb-panel-title{font-weight:700;color:#1f2937;margin:0;font-size:16px}
                    .mb-stat{min-height:96px;padding:16px;border-radius:8px;border:1px solid #e1e7ef;background:#fff;border-left:4px solid #0f766e}.mb-stat-label{font-size:12px;text-transform:uppercase;color:#64748b;font-weight:700}.mb-stat-value{font-size:28px;font-weight:800;color:#111827;line-height:1.1;margin-top:8px}.mb-stat-meta{font-size:12px;color:#6b7280;margin-top:6px}
                    .mb-stat.filter-card{width:100%;text-align:left;cursor:pointer;transition:border-color .15s ease,box-shadow .15s ease,transform .15s ease}.mb-stat.filter-card:hover{border-color:#94a3b8;box-shadow:0 10px 24px rgba(16,24,40,.1);transform:translateY(-1px)}.mb-stat.filter-card.active{border-color:#0f766e;box-shadow:0 0 0 3px rgba(15,118,110,.14),0 8px 22px rgba(16,24,40,.08)}
                    .mb-bulk-action{min-height:96px;width:100%;border-radius:8px;border:1px solid #0f766e;background:#0f766e;color:#fff;display:flex;align-items:center;justify-content:center;gap:10px;font-weight:800;transition:background .15s ease,box-shadow .15s ease,transform .15s ease}.mb-bulk-action:hover{background:#115e59;color:#fff;box-shadow:0 10px 24px rgba(15,118,110,.22);transform:translateY(-1px)}.mb-bulk-action:disabled{cursor:not-allowed;opacity:.7;transform:none}.mb-print-widget{align-items:flex-start;flex-direction:column;text-align:left;padding:16px;border-left:4px solid #111827}.mb-print-widget i{font-size:20px;margin-right:0}.mb-print-widget-title{font-size:15px;line-height:1.2}.mb-print-widget-meta{font-size:12px;opacity:.86;font-weight:700}
                    .mb-filter{display:flex;align-items:center;justify-content:flex-end;flex-wrap:wrap;gap:10px}.mb-filter #mbSearch{min-width:240px;flex:1 1 240px}.mb-select-filter{display:inline-flex;align-items:center;gap:6px;margin:0;padding:5px 9px;border:1px solid #cbd5e1;border-radius:4px;background:#f8fafc;color:#334155;font-size:12px;font-weight:700;white-space:nowrap;cursor:pointer}.mb-select-filter input{margin:0}.mb-selected-count{font-weight:800}.mb-table-wrap{padding:16px}.mb-badge{display:inline-flex;align-items:center;border-radius:999px;padding:4px 9px;font-size:12px;font-weight:700;white-space:nowrap}.mb-badge.ready{background:#dcfce7;color:#166534}.mb-badge.pending{background:#ffedd5;color:#9a3412}
                    .mb-preview-box{border:1px dashed #cbd5e1;border-radius:8px;min-height:176px;display:flex;align-items:center;justify-content:center;background:#f8fafc;overflow:hidden}.mb-preview-box img{max-width:100%;max-height:220px;object-fit:contain}.mb-preview-placeholder{color:#64748b;font-size:13px}.mb-asset-path{font-size:11px;color:#64748b;word-break:break-all}.mb-print-qrcode{display:flex;align-items:center;justify-content:center;width:100%;min-height:42px;font-weight:800}.table td,.table th{vertical-align:middle}.btn i{margin-right:5px}.mb-actions{display:flex;flex-wrap:wrap;gap:6px}.mb-actions .btn{white-space:nowrap}.mb-icon-btn{width:31px;height:31px;display:inline-flex;align-items:center;justify-content:center;padding:0}.mb-icon-btn i{margin-right:0}.table-hover tbody tr.master-row-selected,.table-hover tbody tr.master-row-selected:hover{background:#dcfce7}
                    .asset-card{width:100%;max-width:360px;margin:0 auto;background:#fff;border:1px solid #111827;color:#111827;font-family:Arial,Helvetica,sans-serif;text-align:center}.asset-card-header{padding:9px 10px 0}.asset-card-kicker{font-size:12px;font-weight:800;line-height:1.15}.asset-card-location{font-size:15px;font-weight:800;line-height:1.2;margin-top:3px}.asset-card-location:after{content:"";display:block;border-top:1px solid #111827;margin:2px -10px 0}.asset-card-description{border-bottom:1px solid #111827;padding:9px 10px 11px}.asset-card-description-title{font-size:12px;font-weight:800;margin-bottom:4px}.asset-card-description-text{font-size:15px;font-weight:800;line-height:1.25;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}.asset-card-meta{margin-top:6px;font-size:11px;font-weight:700;line-height:1.35;color:#6b7280}.asset-card-meta div{word-break:break-word}.asset-card-qr{display:flex;align-items:center;justify-content:center;min-height:178px;margin:11px 0 2px}.asset-card-qr img{width:172px;height:172px;object-fit:contain}.asset-card-qr-empty{width:172px;height:172px;border:1px dashed #6b7280;display:flex;align-items:center;justify-content:center;padding:12px;color:#4b5563;font-size:12px;line-height:1.25}.asset-card-date{border-bottom:1px solid #111827;padding:8px 10px;font-size:13px;font-weight:800}.asset-card-signature{display:grid;grid-template-columns:1fr 1fr;min-height:82px}.asset-card-signature div{display:flex;align-items:flex-start;justify-content:center;padding-top:10px;font-size:13px;font-weight:800}.asset-card-signature div+div{border-left:1px solid #111827}.asset-card-empty{min-height:300px;border:1px dashed #cbd5e1;border-radius:8px;background:#f8fafc;display:flex;align-items:center;justify-content:center;text-align:center;color:#64748b;padding:24px;font-weight:700}.no-print{print-color-adjust:exact;-webkit-print-color-adjust:exact}
                    .qr-progress-meta{display:flex;flex-wrap:wrap;gap:8px;margin-top:10px}.qr-progress-meta span{border:1px solid #e1e7ef;border-radius:6px;padding:6px 10px;background:#f8fafc;font-size:12px;color:#334155;font-weight:700}.qr-progress-meta span.is-running{border-color:#99f6e4;background:#ecfdf5;color:#0f766e}.qr-action-row{display:flex;flex-wrap:wrap;gap:8px}.qr-failed-list{max-height:220px;overflow:auto;border:1px solid #e8edf3;border-radius:8px}.qr-failed-list table{margin-bottom:0}.progress{height:18px;border-radius:8px;background:#e5e7eb;overflow:hidden;position:relative}.progress-bar{font-size:11px;font-weight:800;transition:width .35s ease;background-size:18px 18px}.qr-progress-track.is-running:after{content:"";position:absolute;inset:0;background:linear-gradient(90deg,transparent,rgba(255,255,255,.55),transparent);transform:translateX(-100%);animation:qrTrackSweep 1.05s linear infinite}.qr-progress-bar.is-running{background-image:linear-gradient(45deg,rgba(255,255,255,.25) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.25) 50%,rgba(255,255,255,.25) 75%,transparent 75%,transparent);animation:qrStripeMove .75s linear infinite}.qr-pulse{display:inline-flex;align-items:center;gap:6px}.qr-pulse:before{content:"";width:7px;height:7px;border-radius:999px;background:#10b981;box-shadow:0 0 0 0 rgba(16,185,129,.45);animation:qrPulse 1.1s ease-out infinite}@keyframes qrStripeMove{from{background-position:0 0}to{background-position:18px 0}}@keyframes qrTrackSweep{to{transform:translateX(100%)}}@keyframes qrPulse{70%{box-shadow:0 0 0 8px rgba(16,185,129,0)}100%{box-shadow:0 0 0 0 rgba(16,185,129,0)}}
                    @media(max-width:768px){.mb-filter{width:100%;justify-content:flex-start}.mb-filter #mbSearch{width:100%}.mb-panel-header{align-items:flex-start;flex-direction:column}.content-header h1{font-size:22px}.mb-stat-value{font-size:24px}}
                </style>

                <div class="row">
                    <div class="col-12 col-md-6 col-xl-3 mb-3">
                        <div class="mb-stat">
                            <div class="mb-stat-label">Total Data</div>
                            <div class="mb-stat-value" id="qrTotalItem">0</div>
                            <div class="mb-stat-meta">Sumber: stockopname_master_item</div>
                        </div>
                    </div>
                    <?php if ($showQtyZeroWidget) : ?>
                        <div class="col-12 col-md-6 col-xl-3 mb-3">
                            <a href="<?= base_url('admin/stockopname/master_opname/qty-zero') ?>" class="mb-stat filter-card d-block text-decoration-none">
                                <div class="mb-stat-label">Total Barang Qty 0</div>
                                <div class="mb-stat-value" id="qtyZeroItem"><?= number_format($qtyZeroCount, 0, ',', '.') ?></div>
                                <div class="mb-stat-meta">Klik untuk melihat data qty 0</div>
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="col-12 col-md-6 col-xl-3 mb-3">
                        <button type="button" class="mb-stat filter-card" data-qrcode-status="generated">
                            <div class="mb-stat-label">QR Code Selesai</div>
                            <div class="mb-stat-value" id="qrDoneItem">0</div>
                            <div class="mb-stat-meta">Klik untuk filter DONE</div>
                        </button>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3 mb-3">
                        <button type="button" class="mb-stat filter-card" data-qrcode-status="pending">
                            <div class="mb-stat-label">QR Code Pending</div>
                            <div class="mb-stat-value" id="qrPendingItem">0</div>
                            <div class="mb-stat-meta">Klik untuk filter PENDING</div>
                        </button>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3 mb-3">
                        <button type="button" class="mb-stat filter-card" data-qrcode-status="failed">
                            <div class="mb-stat-label">QR Code Gagal</div>
                            <div class="mb-stat-value" id="qrFailedItem">0</div>
                            <div class="mb-stat-meta">Klik untuk filter FAILED</div>
                        </button>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3 mb-3">
                        <button type="button" class="mb-bulk-action mb-print-widget" id="btnPrintAssetPreview" disabled>
                            <i class="fas fa-print"></i>
                            <span class="mb-print-widget-title">Print Preview Asset</span>
                            <span class="mb-print-widget-meta">F4, 10 kartu per kertas</span>
                        </button>
                    </div>
                    <?php if ($showStockCardRangePrint) : ?>
                        <div class="col-12 col-md-6 col-xl-3 mb-3">
                            <button type="button" class="mb-bulk-action mb-print-widget bg-primary border-primary" id="btnPrintStockCardRange">
                                <i class="fas fa-id-card"></i>
                                <span class="mb-print-widget-title">Print Kartu Stock</span>
                                <span class="mb-print-widget-meta">Khusus ID 3075-3267</span>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="mb-panel">
                            <div class="mb-panel-header">
                                <h2 class="mb-panel-title">Generate QR Code Opname</h2>
                                <div class="qr-action-row">
                                    <button type="button" class="btn btn-success btn-sm" id="btnGenerateQrBatch">
                                        <i class="fas fa-qrcode"></i> Generate QR Code
                                    </button>
                                    <button type="button" class="btn btn-warning btn-sm" id="btnRetryFailedQr">
                                        <i class="fas fa-redo"></i> Retry Data Gagal
                                    </button>
                                    <?php if ($showResetQrcode) : ?>
                                        <button type="button" class="btn btn-danger btn-sm" id="btnResetQrOpname">
                                            <i class="fas fa-trash-alt"></i> Reset QR &amp; Input
                                        </button>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnRefreshQrStatus">
                                        <i class="fas fa-sync-alt"></i> Refresh Status
                                    </button>
                                </div>
                            </div>
                            <div class="p-3">
                                <div class="progress qr-progress-track" id="qrProgressTrack">
                                    <div class="progress-bar bg-success qr-progress-bar" id="qrProgressBar" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" style="width:0%">0%</div>
                                </div>
                                <div class="qr-progress-meta">
                                    <span id="qrProgressProcessed">Processed: 0 / 0</span>
                                    <span id="qrProgressSuccess">Sukses: 0</span>
                                    <span id="qrProgressFailed">Gagal: 0</span>
                                    <span id="qrProgressStatus">Status: idle</span>
                                    <span>Batch size: 100</span>
                                </div>
                                <div class="qr-failed-list mt-3" id="qrFailedListWrap" style="display:none">
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Kode Barang</th>
                                                <th>Error</th>
                                                <th>Attempt</th>
                                                <th>Update</th>
                                            </tr>
                                        </thead>
                                        <tbody id="qrFailedListBody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-8 mb-3">
                        <div class="mb-panel h-100">
                            <div class="mb-panel-header">
                                <h2 class="mb-panel-title"><?= html_escape($tableTitle) ?></h2>
                                <div class="mb-filter">
                                    <input type="search" class="form-control form-control-sm" id="mbSearch" placeholder="Cari nama, expired date, no lot">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="mbReset"><i class="fas fa-undo"></i>Reset</button>
                                    <?php if ($showStockCardRangePrint) : ?>
                                        <label class="mb-select-filter" title="Centang seluruh barang aktif yang memiliki Qty Pcs lebih dari 0">
                                            <input type="checkbox" id="checkPositiveQtyPcs">
                                            Centang semua Qty Pcs &gt; 0
                                        </label>
                                        <button type="button" class="btn btn-primary btn-sm" id="btnPrintSelected" disabled>
                                            <i class="fas fa-print"></i> Print Sebagian (<span class="mb-selected-count">0</span>)
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="mb-table-wrap">
                                <table class="table table-sm table-hover table-bordered w-100" id="tableMasterBarang">
                                    <thead>
                                        <tr>
                                            <th>Nama Barang</th>
                                            <th>Expired Date</th>
                                            <th>No Lot</th>
                                            <th>Qty</th>
                                            <th>Qty Pcs</th>
                                            <th>Qty Box</th>
                                            <th>#</th>
                                            <?php if ($showStockCardRangePrint) : ?>
                                                <th>Pilih</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 mb-3">
                        <div class="mb-panel h-100">
                            <div class="mb-panel-header">
                                <h2 class="mb-panel-title">Preview Asset</h2>
                                <span class="text-muted small" id="previewItemLabel">Pilih barang</span>
                            </div>
                            <div class="p-3">
                                <div id="previewAssetCard">
                                    <div class="asset-card-empty">Pilih item dari tabel untuk melihat kartu stock</div>
                                </div>
                                <button type="button" class="btn btn-success mb-print-qrcode mt-3 no-print" id="previewPrintQr" disabled>
                                    <i class="fas fa-print"></i> Print Kartu Stock
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="modalTambahMaster" tabindex="-1" role="dialog" aria-labelledby="modalTambahMasterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form class="modal-content" id="formTambahMaster" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahMasterTitle"><i class="fas fa-plus-circle mr-1"></i> Tambah Master Opname</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group position-relative">
                        <label for="masterSourceSearch">Cari barang</label>
                        <input type="search" class="form-control" id="masterSourceSearch" placeholder="Ketik nama atau kode barang (minimal 2 karakter)">
                        <div class="list-group master-source-result" id="masterSourceResult" style="display:none"></div>
                        <small class="form-text text-muted">Sumber data: <code>tb_master_barang_all</code>.</small>
                    </div>
                    <input type="hidden" name="source_id" id="masterSourceId">
                    <div class="alert alert-light border mb-3" id="masterSourceSelected">Pilih barang dari hasil pencarian untuk mengisi data dan dimensi.</div>
                    <div class="row">
                        <div class="col-md-6 form-group"><label>Kode barang</label><input type="text" class="form-control" id="masterKodeBarang" readonly></div>
                        <div class="col-md-6 form-group"><label>Dimensi</label><input type="text" class="form-control" id="masterDimensi" readonly placeholder="P × L × T"></div>
                        <div class="col-12 form-group"><label>Nama barang</label><input type="text" class="form-control" id="masterNamaBarang" readonly></div>
                        <div class="col-md-4 form-group"><label for="masterExpiredDate">Expired date</label><input type="date" class="form-control" name="expired_date" id="masterExpiredDate" required></div>
                        <div class="col-md-4 form-group"><label for="masterNoLot">No. lot</label><input type="text" class="form-control" name="no_lot" id="masterNoLot" maxlength="100" required></div>
                        <div class="col-md-2 form-group"><label for="masterQtyPcs">Qty pcs</label><input type="number" class="form-control" name="qty_pcs" id="masterQtyPcs" min="0" step="1" value="0" required></div>
                        <div class="col-md-2 form-group"><label for="masterQtyBox">Qty box</label><input type="number" class="form-control" name="qty_box" id="masterQtyBox" min="0" step="1" value="0" required></div>
                    </div>
                    <small class="text-muted">Qty buku dihitung otomatis: (Qty box × dimensi) + Qty pcs.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpanMaster"><i class="fas fa-save"></i> Simpan Master</button>
                </div>
            </form>
        </div>
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
    var selectedId = null;
    var selectedPrintIds = {};
    var currentPrintUrl = '';
    var qrcodeStatus = '';
    var isQrRunning = false;
    var qrRun = {processed: 0, success: 0, failed: 0, total: 0, mode: 'normal'};
    var qrButtonHtml = {
        normal: $('#btnGenerateQrBatch').html(),
        retry: $('#btnRetryFailedQr').html(),
        reset: $('#btnResetQrOpname').length ? $('#btnResetQrOpname').html() : ''
    };
    $('[data-toggle="tooltip"]').tooltip();
    var table;
    table = $('#tableMasterBarang').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        searchDelay: 350,
        order: [[0, 'asc']],
        ajax: {
            url: '<?= base_url($routeBase . '/ajax-list') ?>',
            type: 'POST',
            data: function (d) {
                d.search = {value: $('#mbSearch').val()};
                d.qrcode_status = qrcodeStatus;
            }
        },
        columns: [
            {data: 'nama_barang'},
            {data: 'expired_date', render: function (value) { return formatExpiredDate(value); }},
            {data: 'no_lot'},
            {data: 'qty', render: function (value) { return formatNumber(value); }},
            {data: 'qty_pcs', render: function (value) { return formatNumber(value); }},
            {data: 'qty_box', render: function (value) { return formatNumber(value); }},
            {data: null, orderable: false, searchable: false, render: renderActions}
            <?php if ($showStockCardRangePrint) : ?>,
            {data: null, orderable: false, searchable: false, className: 'text-center', render: function (_, type, row) {
                var checked = selectedPrintIds[String(row.id)] ? ' checked' : '';
                return '<input type="checkbox" class="print-item-check" data-id="' + escapeHtml(row.id) + '"' + checked + ' aria-label="Pilih ' + escapeHtml(row.nama_barang) + '">';
            }}
            <?php endif; ?>
        ],
        rowCallback: function (row, data) {
            $(row).toggleClass('master-row-selected', !!selectedId && String(data.id) === String(selectedId));
        },
        drawCallback: function () {
            var rows = this.api().rows({page: 'current'}).data();
            if (!selectedId && rows.length > 0) {
                selectedId = rows[0].id;
                loadPreview(selectedId);
            } else if (!selectedId && rows.length === 0) {
                resetPreviewCard('Pilih item dari tabel untuk melihat kartu stock');
            } else {
                highlightSelectedRow();
            }
            syncPrintSelectionUi();
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    function selectedPrintIdList() {
        return Object.keys(selectedPrintIds).filter(function (id) {
            return selectedPrintIds[id];
        }).map(function (id) {
            return parseInt(id, 10);
        }).filter(function (id) {
            return id > 0;
        });
    }

    function syncPrintSelectionUi() {
        var total = selectedPrintIdList().length;
        $('.mb-selected-count').text(formatNumber(total));
        $('#btnPrintSelected').prop('disabled', total === 0);
        $('#tableMasterBarang .print-item-check').each(function () {
            this.checked = !!selectedPrintIds[String($(this).data('id'))];
        });
    }

    function formatNumber(value) {
        var parsed = Number(value || 0);
        return parsed.toLocaleString('id-ID');
    }

    function parseDisplayNumber(value) {
        return parseInt(String(value || '0').replace(/[^\d]/g, ''), 10) || 0;
    }

    function escapeHtml(value) {
        return String(value === null || value === undefined ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function toast(icon, title) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: icon,
                title: title,
                showConfirmButton: false,
                timer: 2400
            });
            return;
        }
        alert(title);
    }

    function ajaxMessage(xhr, fallback) {
        if (xhr.responseJSON && xhr.responseJSON.message) {
            return xhr.responseJSON.message;
        }
        if (xhr.responseText) {
            try {
                var parsed = JSON.parse(xhr.responseText);
                if (parsed.message) {
                    return parsed.message;
                }
            } catch (e) {}
        }
        return fallback;
    }

    function renderAssetCell(path, id, ready) {
        if (!path) {
            return '<span class="mb-badge pending">Belum Ada</span>';
        }
        if (parseInt(ready || 0, 10) !== 1) {
            return '<span class="mb-badge pending">File Hilang</span><div class="mb-asset-path mt-1">' + escapeHtml(path) + '</div>';
        }
        return '<button type="button" class="btn btn-outline-success btn-xs mb-icon-btn btn-preview-asset" data-toggle="tooltip" title="Preview asset" data-id="' + id + '"><i class="fas fa-eye"></i></button><div class="mb-asset-path mt-1">' + escapeHtml(path) + '</div>';
    }

    function renderActions(row) {
        var html = '<div class="mb-actions">';
        var status = String(row.qrcode_status || '').toUpperCase();
        html += '<button type="button" class="btn btn-outline-success btn-xs mb-icon-btn btn-preview-asset" data-toggle="tooltip" title="Preview kartu stock" data-id="' + row.id + '"><i class="fas fa-qrcode"></i></button>';
        if (status !== 'DONE') {
            html += '<button type="button" class="btn btn-outline-primary btn-xs mb-icon-btn btn-generate-asset" data-toggle="tooltip" title="Generate QRCode" data-type="qrcode" data-regenerate="0" data-id="' + row.id + '"><i class="fas fa-sync-alt"></i></button>';
        } else if (parseInt(row.qrcode_ready || 0, 10) !== 1) {
            html += '<button type="button" class="btn btn-outline-warning btn-xs mb-icon-btn btn-generate-asset" data-toggle="tooltip" title="Generate ulang QRCode" data-type="qrcode" data-regenerate="1" data-id="' + row.id + '"><i class="fas fa-sync-alt"></i></button>';
        }
        html += '</div>';
        return html;
    }

    function displayValue(value) {
        var text = String(value === null || value === undefined ? '' : value).trim();
        return text === '' ? '-' : text;
    }

    function formatExpiredDate(value) {
        value = displayValue(value);
        if (value === '-') { return value; }

        var match = value.match(/^(\d{4})-(\d{2})-(\d{2})(?:[ T].*)?$/);
        return match ? match[3] + '/' + match[2] + '/' + match[1] : value;
    }

    function resetPreviewCard(message) {
        $('#previewAssetCard').html('<div class="asset-card-empty">' + escapeHtml(message || 'Pilih item dari tabel untuk melihat kartu stock') + '</div>');
        $('#previewItemLabel').text('Pilih barang');
        $('#previewPrintQr').prop('disabled', true);
        currentPrintUrl = '';
        highlightSelectedRow();
    }

    function highlightSelectedRow() {
        if (!table) {
            return;
        }
        table.rows({page: 'current'}).every(function () {
            var data = this.data();
            $(this.node()).toggleClass('master-row-selected', !!selectedId && data && String(data.id) === String(selectedId));
        });
    }

    function renderStockCard(item) {
        var qrcode = item.qrcode || {};
        var qrHtml = qrcode.exists && qrcode.url
            ? '<img src="' + escapeHtml(qrcode.url + '?t=' + new Date().getTime()) + '" alt="QRCode ' + escapeHtml(item.id) + '">'
            : '<div class="asset-card-qr-empty">QR Code belum tergenerate</div>';

        var html = '' +
            '<section class="asset-card">' +
                '<div class="asset-card-header">' +
                    '<div class="asset-card-kicker">LOCATION</div>' +
                    '<div class="asset-card-location">GUDANG INDUK</div>' +
                '</div>' +
                '<div class="asset-card-description">' +
                    '<div class="asset-card-description-title">DESCRIPTION</div>' +
                    '<div class="asset-card-description-text">' + escapeHtml(displayValue(item.nama_barang)) + '</div>' +
                    '<div class="asset-card-meta">' +
                        '<div>' + escapeHtml(displayValue(item.kode_barang)) + '</div>' +
                        '<div>' + escapeHtml(formatExpiredDate(item.expired_date)) + ' | ' + escapeHtml(displayValue(item.no_lot)) + '</div>' +
                    '</div>' +
                    '<div class="asset-card-qr">' + qrHtml + '</div>' +
                '</div>' +
                '<div class="asset-card-date">INVENTORY DATE : ' + escapeHtml(displayValue(item.inventory_date)) + '</div>' +
                '<div class="asset-card-signature">' +
                    '<div>Paraf 1</div>' +
                    '<div>Paraf 2</div>' +
                '</div>' +
            '</section>';

        $('#previewAssetCard').html(html);
    }

    function loadSummary(showToast) {
        $.getJSON('<?= base_url($qrcodeRouteBase . '/summary') ?>', function (res) {
            if (!res.success) {
                toast('error', res.message || 'Gagal memuat summary');
                return;
            }
            $('#qrTotalItem').text(formatNumber(res.total || 0));
            $('#qrDoneItem').text(formatNumber(res.done || 0));
            $('#qrPendingItem').text(formatNumber(res.pending || 0));
            $('#qrFailedItem').text(formatNumber(res.failed || 0));
            var qtyZeroTotal = res.qty_zero_total !== undefined ? res.qty_zero_total : <?= $qtyZeroCount ?>;
            $('#qtyZeroItem').text(formatNumber(qtyZeroTotal));
            $('#btnRetryFailedQr').prop('disabled', isQrRunning || parseInt(res.failed || 0, 10) <= 0);
            $('#btnPrintAssetPreview').prop('disabled', isQrRunning || parseInt(res.done || 0, 10) <= 0);
            if (showToast) {
                toast('success', 'Status QR Code diperbarui');
            }
        }).fail(function () {
            toast('error', 'Server tidak merespons');
        });
    }

    function loadPreview(id) {
        if (!id) {
            return;
        }
        $.ajax({
            url: '<?= base_url($routeBase . '/preview-asset') ?>',
            type: 'POST',
            dataType: 'json',
            data: {id: id},
            success: function (res) {
                if (!res.status) {
                    toast('error', res.message || 'Gagal memuat preview');
                    return;
                }
                selectedId = res.data.id;
                $('#previewItemLabel').text('#' + res.data.id + ' - ' + res.data.nama_barang);
                renderStockCard(res.data);
                currentPrintUrl = '<?= base_url($routeBase . '/print-qrcode') ?>/' + res.data.id;
                $('#previewPrintQr').prop('disabled', false);
                highlightSelectedRow();
            },
            error: function (xhr) {
                toast('error', ajaxMessage(xhr, 'Gagal memuat preview asset'));
            }
        });
    }

    function setQrButtons(disabled) {
        $('#btnGenerateQrBatch,#btnRetryFailedQr,#btnResetQrOpname,#btnRefreshQrStatus,#btnRefreshMasterBarang,#btnPrintAssetPreview').prop('disabled', disabled);
        if (!disabled) {
            $('#btnGenerateQrBatch').html(qrButtonHtml.normal);
            $('#btnRetryFailedQr').html(qrButtonHtml.retry);
            if ($('#btnResetQrOpname').length) {
                $('#btnResetQrOpname').html(qrButtonHtml.reset);
            }
            loadSummary(false);
        }
    }

    function setQrRunningState(running, mode) {
        $('#qrProgressTrack,#qrProgressBar').toggleClass('is-running', running);
        $('#qrProgressStatus').toggleClass('is-running', running);
        if (running) {
            var $button = mode === 'retry' ? $('#btnRetryFailedQr') : $('#btnGenerateQrBatch');
            var label = mode === 'retry' ? 'Retry berjalan' : 'Generate berjalan';
            $button.html('<i class="fas fa-spinner fa-spin"></i> ' + label);
            return;
        }

        $('#btnGenerateQrBatch').html(qrButtonHtml.normal);
        $('#btnRetryFailedQr').html(qrButtonHtml.retry);
        if ($('#btnResetQrOpname').length) {
            $('#btnResetQrOpname').html(qrButtonHtml.reset);
        }
    }

    function setQrProgress(percent, processedText, statusText) {
        percent = Math.max(0, Math.min(100, parseInt(percent || 0, 10)));
        var displayPercent = isQrRunning && percent === 0 ? 4 : percent;
        $('#qrProgressBar')
            .css('width', displayPercent + '%')
            .attr('aria-valuenow', percent)
            .text(percent + '%');
        $('#qrProgressProcessed').text(processedText);
        $('#qrProgressSuccess').text('Sukses: ' + formatNumber(qrRun.success));
        $('#qrProgressFailed').text('Gagal: ' + formatNumber(qrRun.failed));
        $('#qrProgressStatus').html(isQrRunning ? '<span class="qr-pulse">Status: ' + escapeHtml(statusText) + '</span>' : 'Status: ' + escapeHtml(statusText));
    }

    function loadFailedList() {
        $.getJSON('<?= base_url($qrcodeRouteBase . '/failed_list') ?>', function (res) {
            if (!res.success || !res.data || !res.data.length) {
                $('#qrFailedListWrap').hide();
                $('#qrFailedListBody').empty();
                return;
            }

            var html = '';
            $.each(res.data, function (_, row) {
                html += '<tr>' +
                    '<td>' + escapeHtml(row.id) + '</td>' +
                    '<td>' + escapeHtml(row.kode_barang) + '</td>' +
                    '<td>' + escapeHtml(row.qrcode_error_message || '-') + '</td>' +
                    '<td>' + escapeHtml(row.qrcode_attempt_count || 0) + '</td>' +
                    '<td>' + escapeHtml(row.qrcode_updated_at || '-') + '</td>' +
                    '</tr>';
            });
            $('#qrFailedListBody').html(html);
            $('#qrFailedListWrap').show();
        });
    }

    function startQrRun(mode) {
        if (isQrRunning) {
            return;
        }

        mode = mode === 'retry' ? 'retry' : 'normal';
        var title = mode === 'retry' ? 'Retry data QR Code gagal?' : 'Generate QR Code opname?';
        var text = mode === 'retry'
            ? 'Sistem hanya memproses data FAILED dengan retry flag aktif.'
            : 'Sistem memproses data PENDING bertahap per batch 100 tanpa reload halaman.';

        function begin() {
            isQrRunning = true;
            qrRun = {
                processed: 0,
                success: 0,
                failed: 0,
                total: mode === 'retry' ? parseDisplayNumber($('#qrFailedItem').text()) : parseDisplayNumber($('#qrTotalItem').text()),
                mode: mode
            };
            setQrButtons(true);
            setQrRunningState(true, mode);
            var totalLabel = qrRun.total > 0 ? formatNumber(qrRun.total) : 'menghitung';
            setQrProgress(0, 'Processed: 0 / ' + totalLabel, mode === 'retry' ? 'mulai retry' : 'mulai generate');
            runQrBatch(mode);
        }

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: title,
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: mode === 'retry' ? 'Retry' : 'Generate',
                cancelButtonText: 'Batal'
            }).then(function (result) {
                if (result.isConfirmed) {
                    begin();
                }
            });
            return;
        }

        if (confirm(title)) {
            begin();
        }
    }

    function runQrBatch(mode) {
        var url = mode === 'retry'
            ? '<?= base_url($qrcodeRouteBase . '/retry_failed') ?>'
            : '<?= base_url($qrcodeRouteBase . '/generate_batch') ?>';

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            timeout: 28000,
            data: {batch_size: 100},
            success: function (res) {
                if (!res.success) {
                    toast('error', res.message || 'Generate QR Code gagal');
                    isQrRunning = false;
                    setQrRunningState(false, mode);
                    setQrButtons(false);
                    return;
                }

                qrRun.processed += parseInt(res.processed || 0, 10);
                qrRun.success += parseInt(res.success_count || 0, 10);
                qrRun.failed += parseInt(res.failed_count || 0, 10);
                qrRun.total = mode === 'retry'
                    ? parseInt(res.total_failed || (qrRun.processed + (res.remaining_failed || 0)), 10)
                    : parseInt(res.total || 0, 10);

                var remaining = mode === 'retry' ? parseInt(res.remaining_failed || 0, 10) : parseInt(res.remaining || 0, 10);
                var processedLabel = 'Processed: ' + formatNumber(qrRun.processed) + ' / ' + formatNumber(qrRun.total || qrRun.processed);
                var statusLabel = res.is_completed ? 'selesai' : 'batch berjalan, sisa ' + formatNumber(remaining);
                setQrProgress(res.percent || 0, processedLabel, statusLabel);
                loadSummary(false);

                if (!res.is_completed && (parseInt(res.processed || 0, 10) > 0 || res.time_limited)) {
                    window.setTimeout(function () {
                        runQrBatch(mode);
                    }, 250);
                    return;
                }

                isQrRunning = false;
                setQrRunningState(false, mode);
                setQrButtons(false);
                setQrProgress(res.percent || 100, processedLabel, 'selesai');
                table.ajax.reload(null, false);
                if (selectedId) {
                    loadPreview(selectedId);
                }
                loadFailedList();
                toast('success', mode === 'retry' ? 'Retry QR Code selesai' : 'Generate QR Code selesai');
            },
            error: function (xhr) {
                isQrRunning = false;
                setQrRunningState(false, mode);
                setQrButtons(false);
                setQrProgress(0, 'Processed: ' + formatNumber(qrRun.processed) + ' / ' + formatNumber(qrRun.total), 'request gagal');
                loadSummary(false);
                loadFailedList();
                toast('error', ajaxMessage(xhr, 'Server gagal memproses batch QR Code'));
            }
        });
    }

    function resetQrOpnameData() {
        if (isQrRunning) {
            return;
        }

        function begin() {
            isQrRunning = true;
            setQrButtons(true);
            $('#btnResetQrOpname').html('<i class="fas fa-spinner fa-spin"></i> Reset berjalan');
            setQrProgress(0, 'Processed: 0 / 0', 'reset data');

            $.ajax({
                url: '<?= base_url('admin/stockopname/qrcode/reset') ?>',
                type: 'POST',
                dataType: 'json',
                timeout: 30000,
                data: {confirm_reset: 1},
                success: function (res) {
                    if (!res.status) {
                        toast('error', res.message || 'Reset QR Code gagal');
                        return;
                    }

                    var data = res.data || {};
                    selectedId = null;
                    qrcodeStatus = '';
                    $('.filter-card').removeClass('active');
                    $('#qrFailedListWrap').hide();
                    $('#qrFailedListBody').empty();
                    resetPreviewCard('Pilih item dari tabel untuk melihat kartu stock');
                    qrRun = {processed: data.master_rows_reset || 0, success: 0, failed: 0, total: data.master_rows_reset || 0, mode: 'reset'};
                    setQrProgress(100, 'Processed: ' + formatNumber(qrRun.processed) + ' / ' + formatNumber(qrRun.total), 'reset selesai');
                    table.ajax.reload(null, false);
                    loadSummary(false);
                    toast('success', res.message || 'Reset QR Code selesai');
                },
                error: function (xhr) {
                    toast('error', ajaxMessage(xhr, 'Server gagal reset QR Code opname'));
                },
                complete: function () {
                    isQrRunning = false;
                    setQrRunningState(false, 'normal');
                    setQrButtons(false);
                }
            });
        }

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Reset QR Code opname?',
                text: 'Gambar QR akan dihapus, data input opname dikosongkan, index input direset, dan status QR master kembali PENDING.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Reset',
                cancelButtonText: 'Batal'
            }).then(function (result) {
                if (result.isConfirmed) {
                    begin();
                }
            });
            return;
        }

        if (confirm('Reset QR Code opname?')) {
            begin();
        }
    }

    $('#mbSearch').on('keyup', function () {
        selectedId = null;
        table.ajax.reload();
    });

    $('#mbReset').on('click', function () {
        $('#mbSearch').val('');
        qrcodeStatus = '';
        $('.filter-card').removeClass('active');
        selectedPrintIds = {};
        $('#checkPositiveQtyPcs').prop('checked', false);
        syncPrintSelectionUi();
        selectedId = null;
        table.ajax.reload();
    });

    $('#checkPositiveQtyPcs').on('change', function () {
        var $checkbox = $(this);
        if (!$checkbox.is(':checked')) {
            selectedPrintIds = {};
            syncPrintSelectionUi();
            table.rows({page: 'current'}).invalidate('data').draw(false);
            return;
        }

        $checkbox.prop('disabled', true);
        $.getJSON('<?= base_url($routeBase . '/positive-qty-pcs-ids') ?>')
            .done(function (res) {
                if (!res.status || !res.data || !Array.isArray(res.data.ids)) {
                    $checkbox.prop('checked', false);
                    toast('error', res.message || 'Gagal memilih barang Qty Pcs lebih dari 0');
                    return;
                }

                selectedPrintIds = {};
                res.data.ids.forEach(function (id) {
                    selectedPrintIds[String(id)] = true;
                });
                syncPrintSelectionUi();
                table.rows({page: 'current'}).invalidate('data').draw(false);
                toast('success', res.message || 'Barang Qty Pcs lebih dari 0 berhasil dipilih');
            })
            .fail(function (xhr) {
                $checkbox.prop('checked', false);
                toast('error', ajaxMessage(xhr, 'Server gagal mengambil pilihan Qty Pcs'));
            })
            .always(function () {
                $checkbox.prop('disabled', false);
            });
    });

    $('#tableMasterBarang').on('change', '.print-item-check', function () {
        var id = String($(this).data('id'));
        if (this.checked) {
            selectedPrintIds[id] = true;
        } else {
            delete selectedPrintIds[id];
            $('#checkPositiveQtyPcs').prop('checked', false);
        }
        syncPrintSelectionUi();
    });

    $('#btnPrintSelected').on('click', function () {
        var ids = selectedPrintIdList();
        if (!ids.length) {
            toast('warning', 'Pilih minimal satu barang untuk print');
            return;
        }

        var form = $('<form>', {
            method: 'POST',
            action: '<?= base_url($routeBase . '/print-sebagian') ?>',
            target: '_blank'
        }).append($('<input>', {
            type: 'hidden',
            name: 'selected_ids',
            value: JSON.stringify(ids)
        }));
        $('body').append(form);
        form.trigger('submit');
        form.remove();
    });

    $('.filter-card').on('click', function () {
        var status = $(this).data('qrcode-status') || '';
        qrcodeStatus = qrcodeStatus === status ? '' : status;
        $('.filter-card').removeClass('active');
        if (qrcodeStatus) {
            $(this).addClass('active');
        }
        selectedId = null;
        table.ajax.reload();
    });

    $('#btnRefreshMasterBarang').on('click', function () {
        table.ajax.reload(null, false);
        if (selectedId) {
            loadPreview(selectedId);
        }
        loadSummary(true);
        loadFailedList();
    });

    $('#btnGenerateQrBatch').on('click', function () {
        startQrRun('normal');
    });

    $('#btnRetryFailedQr').on('click', function () {
        startQrRun('retry');
    });

    $('#btnResetQrOpname').on('click', function () {
        resetQrOpnameData();
    });

    $('#btnRefreshQrStatus').on('click', function () {
        loadSummary(true);
        loadFailedList();
    });

    $('#btnPrintAssetPreview').on('click', function () {
        if (parseDisplayNumber($('#qrDoneItem').text()) <= 0) {
            toast('warning', 'Belum ada QR Code siap print');
            return;
        }
        window.open('<?= base_url($routeBase . '/print-preview-asset') ?>', '_blank', 'noopener');
    });

    $('#btnPrintStockCardRange').on('click', function () {
        window.open('<?= base_url('admin/stockopname/master_opname/print-kartu-stock-3075-3267') ?>', '_blank', 'noopener');
    });

    $('#previewPrintQr').on('click', function () {
        if (!currentPrintUrl) {
            return;
        }
        window.open(currentPrintUrl, '_blank', 'noopener');
    });

    $('#tableMasterBarang tbody').on('click', 'tr', function (e) {
        if ($(e.target).closest('button,a,input').length) {
            return;
        }
        var row = table.row(this).data();
        if (row && row.id) {
            loadPreview(row.id);
        }
    });

    $('#tableMasterBarang').on('click', '.btn-preview-asset', function () {
        $('[data-toggle="tooltip"]').tooltip('hide');
        loadPreview($(this).data('id'));
    });

    $('#tableMasterBarang').on('click', '.btn-generate-asset', function () {
        var $button = $(this);
        var id = $button.data('id');
        var regenerate = $button.data('regenerate') || 0;
        var original = $button.html();
        var url = '<?= base_url($routeBase . '/generate-qrcode') ?>';

        $('[data-toggle="tooltip"]').tooltip('hide');
        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: {id: id, regenerate: regenerate},
            success: function (res) {
                if (!res.status) {
                    toast('error', res.message || 'Generate gagal');
                    return;
                }
                selectedId = id;
                table.ajax.reload(null, false);
                loadPreview(id);
                loadSummary(false);
                toast('success', res.message || 'Asset berhasil dibuat');
            },
            error: function (xhr) {
                toast('error', ajaxMessage(xhr, 'Server gagal generate asset'));
            },
            complete: function () {
                $button.prop('disabled', false).html(original);
            }
        });
    });

    var masterSearchTimer = null;
    var masterSearchRequest = null;
    var masterSearchItems = {};

    function resetMasterSourceSelection() {
        $('#masterSourceId,#masterKodeBarang,#masterNamaBarang,#masterDimensi').val('');
        $('#masterSourceSelected').removeClass('alert-success').addClass('alert-light')
            .text('Pilih barang dari hasil pencarian untuk mengisi data dan dimensi.');
    }

    function hideMasterSearchResults() {
        $('#masterSourceResult').empty().hide();
    }

    function showMasterSearchResults(items) {
        masterSearchItems = {};
        if (!items || !items.length) {
            $('#masterSourceResult').html('<div class="list-group-item text-muted">Barang tidak ditemukan.</div>').show();
            return;
        }
        var html = '';
        items.forEach(function (item) {
            masterSearchItems[String(item.id)] = item;
            html += '<button type="button" class="list-group-item list-group-item-action btn-select-master-source" data-id="' + escapeHtml(item.id) + '">' +
                '<strong>' + escapeHtml(item.nama_barang) + '</strong><br><small>' + escapeHtml(item.kd_barang || item.kode_barang_system || '-') + ' &middot; P×L×T: ' + escapeHtml(item.p) + '×' + escapeHtml(item.l) + '×' + escapeHtml(item.t) + '</small></button>';
        });
        $('#masterSourceResult').html(html).show();
    }

    $('#masterSourceSearch').on('input', function () {
        var keyword = $.trim($(this).val());
        resetMasterSourceSelection();
        window.clearTimeout(masterSearchTimer);
        if (masterSearchRequest) {
            masterSearchRequest.abort();
        }
        if (keyword.length < 2) {
            hideMasterSearchResults();
            return;
        }
        masterSearchTimer = window.setTimeout(function () {
            masterSearchRequest = $.ajax({
                url: '<?= base_url($routeBase . '/item-search') ?>',
                type: 'GET',
                dataType: 'json',
                data: {keyword: keyword},
                success: function (res) {
                    if (!res.status) {
                        hideMasterSearchResults();
                        toast('error', res.message || 'Gagal mencari barang');
                        return;
                    }
                    showMasterSearchResults(res.data || []);
                },
                error: function (xhr, status) {
                    if (status !== 'abort') {
                        toast('error', ajaxMessage(xhr, 'Server gagal mencari barang'));
                    }
                },
                complete: function () { masterSearchRequest = null; }
            });
        }, 300);
    });

    $('#masterSourceResult').on('click', '.btn-select-master-source', function () {
        var item = masterSearchItems[String($(this).data('id'))];
        if (!item) { return; }
        var dimension = (parseInt(item.p || 0, 10) * parseInt(item.l || 0, 10) * parseInt(item.t || 0, 10));
        $('#masterSourceId').val(item.id);
        $('#masterKodeBarang').val(item.kd_barang || item.kode_barang_system || '');
        $('#masterNamaBarang').val(item.nama_barang || '');
        $('#masterDimensi').val((item.p || 0) + ' × ' + (item.l || 0) + ' × ' + (item.t || 0) + ' = ' + dimension);
        $('#masterSourceSelected').removeClass('alert-light').addClass('alert-success')
            .text('Barang dipilih. Dimensi akan dihitung ulang oleh server saat disimpan.');
        hideMasterSearchResults();
    });

    $('#formTambahMaster').on('submit', function (e) {
        e.preventDefault();
        if (!$('#masterSourceId').val()) {
            toast('warning', 'Pilih barang dari hasil pencarian terlebih dahulu');
            return;
        }
        var $button = $('#btnSimpanMaster');
        var original = $button.html();
        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan');
        $.ajax({
            url: '<?= base_url($routeBase . '/create') ?>',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            success: function (res) {
                if (!res.status) {
                    toast('error', res.message || 'Gagal menyimpan master opname');
                    return;
                }
                $('#modalTambahMaster').modal('hide');
                table.ajax.reload(null, false);
                loadSummary(false);
                toast('success', res.message || 'Master opname berhasil ditambahkan');
            },
            error: function (xhr) { toast('error', ajaxMessage(xhr, 'Server gagal menyimpan master opname')); },
            complete: function () { $button.prop('disabled', false).html(original); }
        });
    });

    $('#modalTambahMaster').on('hidden.bs.modal', function () {
        $('#formTambahMaster')[0].reset();
        hideMasterSearchResults();
        resetMasterSourceSelection();
    });

    loadSummary(false);
    loadFailedList();
});
</script>
