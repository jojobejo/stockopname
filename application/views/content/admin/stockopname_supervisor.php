<?php
$formatDate = static function ($value) {
    $value = trim((string)$value);
    if ($value === '' || strpos($value, '0000-00-00') === 0) return '-';
    $timestamp = strtotime($value);
    return $timestamp === false ? '-' : date('d/m/Y', $timestamp);
};
$formatTime = static function ($value) {
    $timestamp = strtotime((string)$value);
    return $timestamp === false ? '-' : date('H:i', $timestamp);
};
?>

<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">
        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>

        <div class="content-wrapper supervisor-opname-page">
            <section class="content">
                <div class="container-fluid py-3 pb-4">
                    <style>
                        .supervisor-opname-page {
                            background: #eef3f8
                        }

                        .sup-shell {
                            max-width: 520px;
                            margin: 0 auto
                        }

                        .sup-panel {
                            background: #fff;
                            border: 1px solid #dce5ee;
                            border-radius: 8px;
                            box-shadow: 0 8px 22px rgba(15, 23, 42, .07)
                        }

                        .sup-panel-head {
                            padding: 14px 16px;
                            border-bottom: 1px solid #e6edf4;
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            gap: 10px
                        }

                        .sup-title {
                            font-size: 16px;
                            font-weight: 800;
                            color: #172033;
                            margin: 0
                        }

                        .sup-muted {
                            color: #64748b;
                            font-size: 12px
                        }

                        .sup-user {
                            display: flex;
                            align-items: center;
                            gap: 12px;
                            padding: 16px
                        }

                        .sup-avatar {
                            width: 44px;
                            height: 44px;
                            flex: 0 0 44px;
                            border-radius: 50%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            background: #e0edff;
                            color: #1d4ed8;
                            font-size: 18px
                        }

                        .sup-user-name {
                            font-size: 15px;
                            font-weight: 800;
                            color: #172033
                        }

                        .sup-user-meta {
                            display: flex;
                            flex-wrap: wrap;
                            gap: 6px 12px;
                            margin-top: 4px;
                            font-size: 12px;
                            color: #64748b
                        }

                        .sup-user-meta span {
                            display: inline-flex;
                            align-items: center;
                            gap: 5px
                        }

                        .sup-actions {
                            display: grid;
                            grid-template-columns: 1fr 1fr;
                            gap: 10px;
                            padding: 0 16px 16px
                        }

                        .sup-actions .btn {
                            font-weight: 800
                        }

                        .sup-check-panel {
                            display: none
                        }

                        .sup-check-panel.is-active {
                            display: block
                        }

                        .sup-scan-box {
                            background: #0f172a;
                            border-radius: 8px;
                            overflow: hidden;
                            min-height: 240px;
                            display: none
                        }

                        .sup-scan-box video {
                            object-fit: cover
                        }

                        .sup-check-form {
                            padding: 16px;
                            display: grid;
                            gap: 12px
                        }

                        .sup-check-form label {
                            font-size: 11px;
                            font-weight: 800;
                            color: #475569;
                            text-transform: uppercase
                        }

                        .sup-check-result {
                            border: 1px solid #dbe4ef;
                            border-radius: 8px;
                            background: #f8fafc;
                            padding: 12px;
                            display: none
                        }

                        .sup-check-result.is-active {
                            display: block
                        }

                        .sup-check-name {
                            font-size: 14px;
                            font-weight: 900;
                            color: #111827;
                            line-height: 1.35
                        }

                        .sup-check-meta {
                            display: flex;
                            flex-wrap: wrap;
                            gap: 6px 12px;
                            margin-top: 6px;
                            color: #64748b;
                            font-size: 12px
                        }

                        .sup-list {
                            display: grid;
                            gap: 10px
                        }

                        .sup-request {
                            background: #f8fafc;
                            border: 1px solid #dbe4ef;
                            border-radius: 8px;
                            padding: 12px
                        }

                        .sup-request-name {
                            font-size: 14px;
                            font-weight: 800;
                            color: #111827;
                            line-height: 1.35
                        }

                        .sup-request-meta {
                            font-size: 12px;
                            color: #64748b;
                            margin-top: 5px;
                            display: flex;
                            flex-wrap: wrap;
                            gap: 5px 12px
                        }

                        .sup-region {
                            display: inline-flex;
                            align-items: center;
                            gap: 5px;
                            color: #1d4ed8;
                            font-weight: 800
                        }

                        .sup-status {
                            display: inline-block;
                            padding: 3px 8px;
                            border-radius: 999px;
                            background: #e2e8f0;
                            color: #334155;
                            font-size: 10px;
                            font-weight: 900
                        }

                        .sup-empty {
                            text-align: center;
                            color: #64748b;
                            padding: 28px 12px
                        }

                        .sup-qty-grid {
                            display: grid;
                            grid-template-columns: repeat(3, minmax(0, 1fr));
                            gap: 8px;
                            margin-top: 10px
                        }

                        .sup-qty {
                            background: #fff;
                            border: 1px solid #e1e8f0;
                            border-radius: 8px;
                            padding: 8px;
                            text-align: center;
                            min-width: 0
                        }

                        .sup-qty small {
                            display: block;
                            color: #64748b;
                            font-size: 10px;
                            font-weight: 800;
                            text-transform: uppercase
                        }

                        .sup-qty strong {
                            display: block;
                            color: #172033;
                            font-size: 16px;
                            font-weight: 900
                        }

                        .sup-filter {
                            padding: 12px 16px;
                            border-bottom: 1px solid #e6edf4
                        }

                        .sup-filter label {
                            font-size: 11px;
                            font-weight: 800;
                            color: #475569;
                            text-transform: uppercase
                        }

                        .sup-request-action {
                            display: flex;
                            justify-content: flex-end;
                            margin-top: 10px
                        }

                        .sup-request-action .btn {
                            font-weight: 800
                        }

                        .sup-pagination {
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            gap: 10px;
                            margin-top: 14px
                        }

                        .sup-pagination .btn {
                            font-weight: 800
                        }

                        .sup-chart-grid {
                            display: grid;
                            grid-template-columns: 1fr;
                            gap: 12px;
                            padding: 16px
                        }

                        .sup-chart-card {
                            border: 1px solid #e1e7ef;
                            border-radius: 8px;
                            padding: 12px
                        }

                        .sup-chart-title {
                            font-size: 12px;
                            font-weight: 800;
                            color: #334155;
                            text-transform: uppercase
                        }

                        .sup-chart-meta {
                            font-size: 11px;
                            color: #64748b
                        }

                        .sup-chart-wrap {
                            height: 220px
                        }
                    </style>

                    <div class="sup-shell">
                        <div class="sup-panel mb-3">
                            <div class="sup-user">
                                <div class="sup-avatar"><i class="fas fa-user-tie"></i></div>
                                <div class="flex-grow-1">
                                    <div class="sup-user-name"><?= html_escape($supervisor_nama ?? '-') ?></div>
                                    <div class="sup-user-meta">
                                        <span><i class="fas fa-users"></i> Tim <?= html_escape($supervisor_tim ?: '-') ?></span>
                                        <span><i class="fas fa-map-marker-alt"></i> Wilayah <?= html_escape($nama_wilayah ?? '-') ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="sup-actions">
                                <button type="button" class="btn btn-primary btn-sup-mode" data-mode="scan"><i class="fas fa-qrcode"></i> Scan</button>
                                <button type="button" class="btn btn-outline-success btn-sup-mode" data-mode="manual"><i class="fas fa-keyboard"></i> Input Manual</button>
                                <a href="<?= base_url('supervisi-opname/tracking') ?>" class="btn btn-outline-primary"><i class="fas fa-map-marked-alt"></i> Tracking Inputer</a>
                                <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
                            </div>
                        </div>

                        <div class="sup-panel sup-check-panel mb-3" id="supScanPanel">
                            <div class="sup-panel-head">
                                <h1 class="sup-title">Cek QR Stockopname</h1>
                                <span class="sup-muted" id="supScanStatus"></span>
                            </div>
                            <div class="sup-check-form">
                                <button type="button" class="btn btn-primary btn-block" id="btnOpenScan"><i class="fas fa-camera"></i> Buka Scan</button>
                                <div class="sup-scan-box" id="supQrReader"></div>
                                <div class="sup-check-result" id="supScanResult">
                                    <div class="sup-check-name" data-field="nama_barang">-</div>
                                    <div class="sup-check-meta">
                                        <span>Kode: <strong data-field="kode_barang">-</strong></span>
                                        <span>Expired: <strong data-field="expired_date">-</strong></span>
                                        <span>Dimensi: <strong data-field="dimensi">0</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="sup-panel sup-check-panel mb-3" id="supManualPanel">
                            <div class="sup-panel-head">
                                <h1 class="sup-title">Cek Data Manual</h1>
                            </div>
                            <div class="sup-check-form">
                                <div class="form-group mb-0">
                                    <label>Nama Barang</label>
                                    <select class="form-control" id="supManualBarang"></select>
                                </div>
                                <div class="form-group mb-0">
                                    <label>Expired Date</label>
                                    <select class="form-control" id="supManualExpired" disabled>
                                        <option value="">Pilih nama barang dahulu</option>
                                    </select>
                                </div>
                                <div class="sup-check-result" id="supManualResult">
                                    <div class="sup-check-name" data-field="nama_barang">-</div>
                                    <div class="sup-check-meta">
                                        <span>Kode: <strong data-field="kode_barang">-</strong></span>
                                        <span>Expired: <strong data-field="expired_date">-</strong></span>
                                        <span>Dimensi: <strong data-field="dimensi">0</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="sup-panel mb-3">
                            <div class="sup-panel-head">
                                <h1 class="sup-title">Daftar Request Opname</h1>
                                <span class="sup-muted"><?= (int)($request_total ?? 0) ?> data</span>
                            </div>
                            <form method="get" action="<?= base_url('supervisi-opname') ?>" class="sup-filter">
                                <div class="form-group">
                                    <label for="filterWilayah">Filter Wilayah</label>
                                    <select name="wilayah" id="filterWilayah" class="form-control" onchange="this.form.submit()">
                                        <option value="0">Semua Wilayah Supervisi</option>
                                        <?php foreach (($wilayah_rows ?? []) as $wilayahRow) : ?>
                                            <option value="<?= (int)$wilayahRow['id'] ?>" <?= (int)$wilayah_filter === (int)$wilayahRow['id'] ? 'selected' : '' ?>><?= html_escape($wilayahRow['nama_wilayah']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group mb-0">
                                    <label for="filterKeyword">Cari Nama Barang Request Opname</label>
                                    <div class="input-group">
                                        <input type="text" name="keyword" id="filterKeyword" class="form-control" value="<?= html_escape($request_keyword ?? '') ?>" placeholder="Masukkan nama barang">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                                            <?php if (trim((string)($request_keyword ?? '')) !== '') : ?>
                                                <a class="btn btn-outline-secondary" href="<?= base_url('supervisi-opname?wilayah=' . (int)$wilayah_filter) ?>"><i class="fas fa-times"></i></a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="p-3">
                                <?php if (empty($request_rows)) : ?>
                                    <div class="sup-empty">Belum ada request opname untuk wilayah supervisi ini.</div>
                                <?php else : ?>
                                    <div class="sup-list">
                                        <?php foreach ($request_rows as $row) : ?>
                                            <div class="sup-request">
                                                <div class="d-flex justify-content-between align-items-start" style="gap:8px">
                                                    <div class="sup-request-name"><?= html_escape($row['nama_barang'] ?? '-') ?></div>
                                                    <span class="sup-status"><?= html_escape($row['status'] ?? '-') ?></span>
                                                </div>
                                                <div class="sup-request-meta">
                                                    <span>Exp: <?= $formatDate($row['expired_date'] ?? '') ?></span>
                                                    <span>Input: <?= $formatDate($row['requested_at'] ?? '') ?> <?= $formatTime($row['requested_at'] ?? '') ?></span>
                                                </div>
                                                <div class="sup-request-meta">
                                                    <span class="sup-region"><i class="fas fa-map-marker-alt"></i><?= html_escape($row['nama_wilayah'] ?? '-') ?></span>
                                                    <span>Oleh: <?= html_escape($row['requested_by'] ?? '-') ?></span>
                                                </div>
                                                <div class="sup-qty-grid">
                                                    <div class="sup-qty"><small>Qty</small><strong><?= number_format((int)($row['qty'] ?? 0), 0, ',', '.') ?></strong></div>
                                                    <div class="sup-qty"><small>Qty Box</small><strong><?= number_format((int)($row['qty_box'] ?? 0), 0, ',', '.') ?></strong></div>
                                                    <div class="sup-qty"><small>Qty Pcs</small><strong><?= number_format((int)($row['qty_pcs'] ?? 0), 0, ',', '.') ?></strong></div>
                                                </div>
                                                <div class="sup-request-action">
                                                    <button type="button" class="btn btn-success btn-sm btn-affirm" data-id="<?= (int)$row['id'] ?>"><i class="fas fa-check-circle"></i> Afirmasi Request</button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php if (($total_pages ?? 1) > 1) : ?>
                                        <?php
                                        $queryParams = [];
                                        if ((int)$wilayah_filter > 0) $queryParams['wilayah'] = (int)$wilayah_filter;
                                        if (trim((string)($request_keyword ?? '')) !== '') $queryParams['keyword'] = (string)$request_keyword;
                                        $filterQuery = $queryParams ? '&' . http_build_query($queryParams) : '';
                                        ?>
                                        <div class="sup-pagination">
                                            <a class="btn btn-outline-secondary btn-sm <?= $current_page <= 1 ? 'disabled' : '' ?>" href="<?= base_url('supervisi-opname?page=' . max(1, $current_page - 1) . $filterQuery) ?>"><i class="fas fa-chevron-left"></i> Sebelumnya</a>
                                            <span class="sup-muted">Halaman <?= (int)$current_page ?> / <?= (int)$total_pages ?></span>
                                            <a class="btn btn-outline-secondary btn-sm <?= $current_page >= $total_pages ? 'disabled' : '' ?>" href="<?= base_url('supervisi-opname?page=' . min($total_pages, $current_page + 1) . $filterQuery) ?>">Berikutnya <i class="fas fa-chevron-right"></i></a>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="sup-panel">
                            <div class="sup-panel-head">
                                <h1 class="sup-title">Stockopname Result</h1>
                            </div>
                            <div class="sup-chart-grid">
                                <div class="sup-chart-card">
                                    <div class="sup-chart-title">All Barang</div>
                                    <p class="sup-chart-meta" id="supAllMeta">0% match; Tim 1 atau Tim 2</p>
                                    <div class="sup-chart-wrap"><canvas id="supAllChart"></canvas></div>
                                </div>
                                <div class="sup-chart-card">
                                    <div class="sup-chart-title">By Expired Date + LOT</div>
                                    <p class="sup-chart-meta" id="supExpiredMeta">0% match; Tim 1 atau Tim 2</p>
                                    <div class="sup-chart-wrap"><canvas id="supExpiredChart"></canvas></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        $(function() {
            var resultCharts = <?= json_encode($result_charts ?? [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
            var scanner = null;
            var scanning = false;

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

            function escapeHtml(value) {
                return String(value === null || value === undefined ? '' : value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function formatExpiredDate(value) {
                value = $.trim(value || '');
                var match = value.match(/^(\d{4})-(\d{2})-(\d{2})/);
                return match ? match[3] + '/' + match[2] + '/' + match[1] : (value || '-');
            }

            function ajaxMessage(xhr, fallback) {
                if (xhr.responseJSON && xhr.responseJSON.message) return xhr.responseJSON.message;
                if (xhr.responseText) {
                    try {
                        var parsed = JSON.parse(xhr.responseText);
                        if (parsed.message) return parsed.message;
                    } catch (e) {}
                }
                return fallback;
            }

            function fillCheckResult($target, row) {
                row = row || {};
                $target.find('[data-field="nama_barang"]').html(escapeHtml(row.nama_barang || '-'));
                $target.find('[data-field="kode_barang"]').html(escapeHtml(row.kode_barang || '-'));
                $target.find('[data-field="expired_date"]').html(escapeHtml(formatExpiredDate(row.expired_date)));
                $target.find('[data-field="dimensi"]').text((parseInt(row.dimensi || 0, 10) || 0).toLocaleString('id-ID'));
                $target.addClass('is-active');
            }

            function stopScanner() {
                if (!scanner || !scanning) return $.Deferred().resolve().promise();
                return scanner.stop().then(function() {
                    scanning = false;
                    $('#supQrReader').hide();
                    $('#btnOpenScan').prop('disabled', false).html('<i class="fas fa-camera"></i> Buka Scan');
                    $('#supScanStatus').text('');
                }).catch(function() {
                    scanning = false;
                    $('#supQrReader').hide();
                    $('#btnOpenScan').prop('disabled', false).html('<i class="fas fa-camera"></i> Buka Scan');
                });
            }

            function showMode(mode) {
                $('.sup-check-panel').removeClass('is-active');
                $('.btn-sup-mode').removeClass('btn-primary btn-success').addClass('btn-outline-primary');
                if (mode === 'manual') {
                    stopScanner();
                    $('#supManualPanel').addClass('is-active');
                    $('.btn-sup-mode[data-mode="manual"]').removeClass('btn-outline-primary btn-outline-success').addClass('btn-success');
                    return;
                }
                $('#supScanPanel').addClass('is-active');
                $('.btn-sup-mode[data-mode="scan"]').removeClass('btn-outline-primary').addClass('btn-primary');
            }

            function lookupScan(value) {
                value = $.trim(value || '');
                if (!value) {
                    toast('warning', 'QRCode belum terbaca');
                    return;
                }
                $('#supScanStatus').text('Mencari');
                $.ajax({
                    url: '<?= base_url('admin/stockopname/input/lookup') ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        scan_value: value
                    },
                    success: function(res) {
                        if (!res.status) {
                            toast('warning', res.message || 'Data QR tidak ditemukan');
                            $('#supScanStatus').text('');
                            return;
                        }
                        fillCheckResult($('#supScanResult'), res.data || {});
                        toast('success', 'Data QR ditemukan');
                        stopScanner();
                    },
                    error: function(xhr) {
                        toast('error', ajaxMessage(xhr, 'Server tidak merespons'));
                        $('#supScanStatus').text('');
                    }
                });
            }

            function renderResult(canvasId, metaId, data, colors) {
                data = data || {};
                var match = parseInt(data.match || 0, 10),
                    notMatch = parseInt(data.not_match || 0, 10);
                $('#' + metaId).text(Number(data.persen_match || 0).toLocaleString('id-ID') + '% match; Tim 1 atau Tim 2');
                var canvas = document.getElementById(canvasId);
                if (canvas && typeof Chart !== 'undefined') new Chart(canvas, {
                    type: 'pie',
                    data: {
                        labels: ['Match', 'Not Match'],
                        datasets: [{
                            data: [match, notMatch],
                            backgroundColor: colors,
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }
            renderResult('supAllChart', 'supAllMeta', resultCharts.all_barang, ['#2563eb', '#f59e0b']);
            renderResult('supExpiredChart', 'supExpiredMeta', resultCharts.expired, ['#0f766e', '#f59e0b']);

            $('.btn-sup-mode').on('click', function() {
                var mode = $(this).data('mode') === 'manual' ? 'manual' : 'scan';
                showMode(mode);
            });

            $('#btnOpenScan').on('click', function() {
                showMode('scan');
                if (scanning) {
                    stopScanner();
                    return;
                }
                if (typeof Html5Qrcode === 'undefined') {
                    toast('error', 'Library scanner belum termuat');
                    return;
                }
                $('#supQrReader').show();
                $('#btnOpenScan').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Membuka kamera');
                $('#supScanStatus').text('Kamera');
                scanner = scanner || new Html5Qrcode('supQrReader');
                scanner.start({
                        facingMode: 'environment'
                    }, {
                        fps: 10,
                        qrbox: {
                            width: 220,
                            height: 220
                        }
                    },
                    function(decodedText) {
                        if (!decodedText) return;
                        $('#supScanStatus').text('Terbaca');
                        lookupScan(decodedText);
                    }
                ).then(function() {
                    scanning = true;
                    $('#btnOpenScan').prop('disabled', false).html('<i class="fas fa-stop"></i> Tutup Scan');
                }).catch(function() {
                    $('#supQrReader').hide();
                    $('#btnOpenScan').prop('disabled', false).html('<i class="fas fa-camera"></i> Buka Scan');
                    $('#supScanStatus').text('');
                    toast('error', 'Kamera tidak dapat dibuka');
                });
            });

            $('#supManualBarang').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: 'Cari nama barang',
                allowClear: true,
                ajax: {
                    url: '<?= base_url('admin/stockopname/input/manual/barang') ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term || '',
                            page: params.page || 1
                        };
                    },
                    processResults: function(data) {
                        return data;
                    }
                }
            });

            $('#supManualBarang').on('change', function() {
                var kodeBarang = $(this).val();
                $('#supManualResult').removeClass('is-active');
                $('#supManualExpired').prop('disabled', true).empty().append(new Option(kodeBarang ? 'Memuat expired date' : 'Pilih nama barang dahulu', '', true, true)).trigger('change');
                if (!kodeBarang) return;
                $.ajax({
                    url: '<?= base_url('admin/stockopname/input/manual/expired') ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        kode_barang: kodeBarang
                    },
                    success: function(res) {
                        var selected = $('#supManualBarang').select2('data')[0] || {};
                        if (!res.status || !(res.data || []).length) {
                            $('#supManualExpired').prop('disabled', true).empty().append(new Option('Expired date tidak tersedia', '', true, true)).trigger('change');
                            toast('warning', res.message || 'Expired date tidak tersedia');
                            return;
                        }
                        $('#supManualExpired').empty().append(new Option('Pilih expired date', '', true, true));
                        $.each(res.data || [], function(_, row) {
                            var option = new Option(formatExpiredDate(row.expired_date || row.text), row.id, false, false);
                            $(option)
                                .attr('data-kode-barang', selected.kode_barang || kodeBarang)
                                .attr('data-nama-barang', selected.nama_barang || selected.text || '')
                                .attr('data-expired-date', row.expired_date || row.text || '')
                                .attr('data-dimensi', row.dimensi || selected.dimensi || 0);
                            $('#supManualExpired').append(option);
                        });
                        $('#supManualExpired').prop('disabled', false).trigger('change');
                    },
                    error: function(xhr) {
                        $('#supManualExpired').prop('disabled', true).empty().append(new Option('Gagal memuat expired date', '', true, true)).trigger('change');
                        toast('error', ajaxMessage(xhr, 'Server tidak merespons'));
                    }
                });
            });

            $('#supManualExpired').on('change', function() {
                var selected = $(this).find(':selected');
                if (!$(this).val()) {
                    $('#supManualResult').removeClass('is-active');
                    return;
                }
                fillCheckResult($('#supManualResult'), {
                    kode_barang: selected.attr('data-kode-barang') || $('#supManualBarang').val() || '-',
                    nama_barang: selected.attr('data-nama-barang') || '-',
                    expired_date: selected.attr('data-expired-date') || '',
                    dimensi: selected.attr('data-dimensi') || 0
                });
            });

            $('.btn-affirm').on('click', function() {
                var button = $(this),
                    id = parseInt(button.data('id'), 10);
                if (!id) return;
                var submit = function() {
                    button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses');
                    $.post('<?= base_url('supervisi-opname/afirmasi') ?>', {
                            id: id
                        }, null, 'json')
                        .done(function(res) {
                            if (res && res.status) return window.location.reload();
                            button.prop('disabled', false).html('<i class="fas fa-check-circle"></i> Afirmasi Request');
                            Swal.fire('Gagal', (res && res.message) || 'Request gagal diproses.', 'error');
                        }).fail(function() {
                            button.prop('disabled', false).html('<i class="fas fa-check-circle"></i> Afirmasi Request');
                            Swal.fire('Gagal', 'Server tidak merespons.', 'error');
                        });
                };
                Swal.fire({
                        title: 'Afirmasi request?',
                        text: 'Data akan disimpan ke hasil opname dan request ditandai selesai.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, afirmasi',
                        cancelButtonText: 'Batal'
                    })
                    .then(function(result) {
                        if (result.isConfirmed) submit();
                    });
            });
        });
    </script>
