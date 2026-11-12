<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$embedded = !empty($embedded);
$isSuperadmin = isset($is_superadmin) ? !empty($is_superadmin) : (strtolower(trim((string)get_instance()->session->userdata('username'))) === 'superadmin' || strtoupper(trim((string)get_instance()->session->userdata('jobdesk'))) === 'SUPERADMIN');
?>
<?php if (!$embedded): ?>
<body class="hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view('partial/main/navbar') ?>
    <?php $this->load->view('partial/main/sidebar') ?>
    <div class="content-wrapper">
<div id="catalogZahirSourceStatus"><?php $this->load->view('content/admin/stockopname_zahir_status'); ?></div>
        <section class="content-header"><div class="container-fluid d-flex align-items-center justify-content-between">
            <h1 class="m-0"><?= html_escape($page_heading ?? 'Master Barang') ?></h1>
            <a href="<?= base_url('admin/stockopname/master_opname') ?>" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left"></i> Master Opname</a>
        </div></section>
        <section class="content"><div class="container-fluid">
<?php else: ?>
        <section class="content pt-3"><div class="container-fluid">
<?php endif; ?>
            <div class="row catalog-stats">
                <div class="col-12 col-md-6 col-xl-3 mb-3"><button type="button" class="catalog-stat" data-toggle="modal" data-target="#zahirCsvImportModal"><span>Referensi Zahir</span><strong style="font-size:20px"><i class="fas fa-file-upload mr-2" aria-hidden="true"></i>Impor CSV</strong><small>Pilih file dan langsung impor</small></button></div>
                <div class="col-12 col-md-6 col-xl-3 mb-3"><button type="button" class="catalog-stat" data-status=""><span>Total Data</span><strong id="catalogTotal">0</strong><small>Kelompok nama dan expired date</small></button></div>
                <div class="col-12 col-md-6 col-xl-3 mb-3">
                    <?php if ($isSuperadmin): ?>
                        <a class="catalog-stat" href="<?= base_url('admin/stockopname/master_opname/qty-zero') ?>"><span>Total Barang Qty 0</span><strong id="catalogQtyZero">0</strong><small>Master opname lokal · lihat data qty 0</small></a>
                    <?php else: ?>
                        <div class="catalog-stat" style="cursor:default"><span>Total Barang Qty 0</span><strong id="catalogQtyZero">0</strong><small>Data katalog Zahir</small></div>
                    <?php endif; ?>
                </div>
                <div class="col-12 col-md-6 col-xl-3 mb-3"><button type="button" class="catalog-stat" data-status="generated"><span>QR Code Selesai</span><strong id="catalogQrDone">0</strong><small>Jumlah barang siap · filter selesai</small></button></div>
                <div class="col-12 col-md-6 col-xl-3 mb-3"><button type="button" class="catalog-stat" data-status="not_generated"><span>QR Code Pending</span><strong id="catalogQrPending">0</strong><small>Jumlah barang pending · filter pending</small></button></div>
                <div class="col-12 col-md-6 col-xl-3 mb-3"><button type="button" class="catalog-print" id="printStockCardWidget" disabled><i class="fas fa-id-card"></i><strong>Print Kartu Stock</strong><small>Cetak kartu barang terpilih</small></button></div>
            </div>
            <div class="row">
                <div class="col-lg-8 mb-3">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Data barang dan expired date dari ZahirDigital</span>
                            <div><small id="catalogSyncStatus" class="text-muted mr-2"></small><button type="button" class="btn btn-primary btn-sm mr-1" data-toggle="modal" data-target="#zahirCsvImportModal"><i class="fas fa-upload" aria-hidden="true"></i> Impor CSV</button><button type="button" class="btn btn-outline-primary btn-sm" id="refreshMasterBarang"><i class="fas fa-sync-alt"></i> Refresh</button></div>
                        </div>
                        <div class="card-body">
                            <div id="masterBarangError" class="alert alert-danger" role="alert" style="display:none"></div>
                            <div class="form-group d-flex align-items-center flex-wrap" style="gap:8px">
                                <button type="button" class="btn btn-primary btn-sm" id="generateAllCatalogQr">Generate All QR Code</button>
                                <button type="button" class="btn btn-outline-warning btn-sm" id="reassignMissingCatalogQr"><i class="fas fa-redo mr-1" aria-hidden="true"></i>Reassign All QR Hilang</button>
                                <?php if ($isSuperadmin): ?>
                                    <button type="button" class="btn btn-warning btn-sm" id="btnRegenerateAllCatalogQr"><i class="fas fa-sync-alt mr-1"></i> Generate Ulang Semua QR</button>
                                <?php endif; ?>
                                <span id="generateAllProgress" role="status" class="small text-muted font-weight-bold"></span>
                                <label for="filterQrStatus" class="mb-0 ml-auto">Status QR</label>
                                <select id="filterQrStatus" class="form-control form-control-sm" style="max-width:210px">
                                    <option value="">Semua</option><option value="generated">Sudah digenerate</option><option value="not_generated">Belum digenerate</option>
                                </select>
                            </div>
                            <div class="d-flex align-items-center flex-wrap mb-2" style="gap:8px">
                                <button type="button" class="btn btn-info btn-sm" id="btnAddToQueue" disabled><i class="fas fa-plus-circle mr-1"></i> Masukkan Antrian (<span id="queueSelectedCount">0</span>)</button>
                                <button type="button" class="btn btn-success btn-sm" id="printSelectedCards" disabled><i class="fas fa-print mr-1"></i> Cetak Tercentang (<span id="selectedCardCount">0</span>)</button>
                                <button type="button" class="btn btn-primary btn-sm" id="printAllCards" disabled><i class="fas fa-print mr-1"></i> Cetak Semua QR Siap (<span id="allReadyCount">0</span>)</button>
                            </div>
                            <div class="table-responsive">
                                <table id="tableMasterBarangZahir" class="table table-bordered table-striped table-hover w-100">
                                    <thead>
                                        <tr>
                                            <th style="width:36px"><input type="checkbox" id="selectPageCards" title="Centang semua QR siap di halaman ini" aria-label="Centang semua QR siap di halaman ini"></th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Expired Date</th>
                                            <th>Aksi QR</th>
                                            <th>Status QR</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-3" style="position:sticky;top:12px">
                    <!-- Card Atas: Antrian Cetak (Rasio 50%) -->
                    <div class="card mb-3" style="height: 380px; display:flex; flex-direction:column;">
                        <div class="card-header d-flex justify-content-between align-items-center py-2">
                            <div class="d-flex align-items-center">
                                <strong style="font-size:14px"><i class="fas fa-list-ol mr-1 text-primary"></i> Antrian Cetak</strong>
                                <span class="badge badge-info ml-2" id="queueBadgeCount">0 item</span>
                            </div>
                            <div>
                                <button type="button" class="btn btn-outline-danger btn-xs mr-1" id="btnClearQueue" title="Kosongkan Antrian">
                                    <i class="fas fa-trash-alt"></i> Bersihkan
                                </button>
                                <button type="button" class="btn btn-success btn-xs" id="btnPrintQueue" disabled>
                                    <i class="fas fa-print"></i> Cetak Antrian (<span id="queueTotalCopies">0</span>)
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-2" style="overflow-y:auto; flex:1;">
                            <div id="queueEmptyNotice" class="text-muted text-center p-4 small">
                                <i class="fas fa-inbox fa-2x mb-2 d-block opacity-50"></i>
                                Antrian cetak masih kosong.<br>Centang barang pada tabel lalu klik <strong>Masukkan Antrian</strong>.
                            </div>
                            <div id="queueListWrap" class="list-group list-group-flush"></div>
                        </div>
                        <div class="card-footer py-2 px-3 bg-light d-flex justify-content-between align-items-center small text-muted">
                            <span>Total Item: <strong id="queueDistinctCount">0</strong></span>
                            <span>Total Label: <strong id="queueTotalCopiesFooter">0</strong></span>
                        </div>
                    </div>

                    <!-- Card Bawah: Preview Cetak QRCODE (Rasio 50%) -->
                    <div class="card" style="height: 380px; display:flex; flex-direction:column;">
                        <div class="card-header d-flex justify-content-between align-items-center py-2">
                            <span><strong>Preview Cetak QRCODE</strong></span>
                            <button type="button" class="btn btn-success btn-xs" id="printStockCard" disabled><i class="fas fa-print"></i> Cetak 1 Lembar</button>
                        </div>
                        <div class="card-body p-2 text-center" style="overflow-y:auto; flex:1;">
                            <p class="text-muted small mb-2">Pilih barang dari tabel atau antrian untuk melihat label QRCODE.</p>
                            <div id="previewStockCard" class="stock-card-preview">
                                <div class="text-muted text-center p-4">Pilih barang dari tabel atau antrian.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<?php if (!$embedded): ?>
        </section>
    </div>
    <footer class="main-footer"><strong>Copyright &copy; 2022 <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.</strong></footer>
</div>
<?php else: ?>
        </div></section>
<?php endif; ?>
<?php $this->load->view('content/admin/stockopname_zahir_import_modal'); ?>
<style>
    .catalog-stat{width:100%;min-height:112px;display:flex;flex-direction:column;align-items:flex-start;text-align:left;padding:16px;border:1px solid #dfe5ec;border-left:4px solid #0f766e;border-radius:8px;background:#fff;color:#172033;text-decoration:none;cursor:pointer}
    .catalog-stat:hover,.catalog-stat.active{border-color:#0f766e;color:#172033;text-decoration:none;box-shadow:0 0 0 3px rgba(15,118,110,.12)}
    .catalog-stat span{font-size:11px;font-weight:800;text-transform:uppercase;color:#64748b}.catalog-stat strong{font-size:28px;line-height:1.15;margin-top:8px}.catalog-stat small{font-size:11px;color:#64748b;margin-top:5px}
    .catalog-print{width:100%;min-height:112px;display:flex;flex-direction:column;align-items:flex-start;text-align:left;padding:16px;border:0;border-left:4px solid #0759b7;border-radius:8px;background:#0878ef;color:#fff;cursor:pointer}.catalog-print:disabled{opacity:.65;cursor:not-allowed}.catalog-print i{font-size:18px}.catalog-print strong{font-size:15px;margin-top:8px}.catalog-print small{font-size:11px;margin-top:6px}
    #tableMasterBarangZahir tbody tr{cursor:pointer}#tableMasterBarangZahir tbody tr.selected{background:#e8f4ff}
    .stock-card-preview{width:100%;max-width:96mm;margin:0 auto;border:1px solid #111827;background:#fff;color:#111827;text-align:center}
    .stock-card-preview .stock-card-name{padding:14px 10px 6px;font-size:15px;font-weight:700;word-break:break-word}
    .stock-card-preview .stock-card-qr{height:200px;display:flex;align-items:center;justify-content:center;margin-bottom:12px}
    .stock-card-preview .stock-card-qr img{width:190px;height:190px;object-fit:contain}
    .stock-card-preview .stock-card-foot{border-top:1px solid #111827;padding:8px;font-size:12px;font-weight:700}
    .queue-item{border-left:3px solid #0f766e;margin-bottom:6px;border-radius:4px;background:#f8fafc;padding:8px 10px;cursor:pointer;transition:all .15s ease}
    .queue-item:hover{background:#f1f5f9;box-shadow:0 2px 6px rgba(0,0,0,.06)}
    .queue-item.active{border-left-color:#0878ef;background:#eff6ff}
    .queue-title{font-size:12px;font-weight:700;color:#1e293b;line-height:1.2;margin-bottom:3px;word-break:break-word}
    .queue-meta{font-size:11px;color:#64748b}
    .queue-copies-input{width:46px;text-align:center;padding:2px 4px;font-size:12px;font-weight:700;border:1px solid #cbd5e1;border-radius:3px}
    @media print{body *{visibility:hidden!important}#previewStockCard,#previewStockCard *{visibility:visible!important}#previewStockCard label{display:none!important}#previewStockCard{position:absolute;left:0;top:0;width:96mm;max-width:none}}
</style>
<script>
window.addEventListener('load', function () {
    var isSuperadmin = <?= $isSuperadmin ? 'true' : 'false' ?>;
    var refreshBusy = false;
    var refreshNextDraw = false;
    var selectedRow = null;
    var selectedVariant = null;
    var selectedCards = {};
    var readyCount = 0;
    var listUrl = '<?= base_url('admin/stockopname/master_barang/list') ?>';
    var printUrl = '<?= base_url('admin/stockopname/master_barang/print-kartu-stock') ?>';
    var actionToken = '<?= html_escape($master_action_token ?? '') ?>';
    var qrCsrf = <?= json_encode($this->config->item('csrf_protection') ? ['name'=>$this->security->get_csrf_token_name(),'hash'=>$this->security->get_csrf_hash()] : null) ?>;

    // Inisialisasi Zahir Import Modal
    window.StockopnameZahirImport.init({
        url: <?= json_encode(site_url('admin/stockopname/zahir-csv') . '?embedded=1') ?>,
        onResponse: function (panel) {
            var name = panel.attr('data-csrf-name');
            if (name) { qrCsrf = {name: name, hash: panel.attr('data-csrf-hash')}; }
        },
        onCommitted: function () {
            selectedCards = {};
            table.ajax.reload(null, false);
            loadPrintQueue();
            $.get(<?= json_encode(site_url('admin/stockopname/zahir-csv') . '?status_only=1') ?>, function (html) {
                var fresh = $('<div>').append($.parseHTML(html, document, false)).find('.zahir-source-status').first();
                if (fresh.length) { $('.zahir-source-status').each(function () { $(this).replaceWith(fresh.clone()); }); }
                else if ($.trim(html) === '') { $('.zahir-source-status').remove(); }
            });
        }
    });

    function secureQrData(data) {
        if (!data) { data = {}; }
        if (qrCsrf) { data[qrCsrf.name] = qrCsrf.hash; }
        return data;
    }

    function alertMessage(icon, message) {
        if (typeof Swal !== 'undefined') { Swal.fire({icon: icon, text: message}); }
        else { window.alert(message); }
    }

    function esc(value) { return $('<div>').text(value === null || value === undefined ? '' : String(value)).html(); }
    function formatDate(value) { var p = String(value || '').split('-'); return p.length === 3 ? p[2] + '/' + p[1] + '/' + p[0] : (value || '-'); }
    function cardValues(row) { return [row.kode_barang, row.nama_barang, row.expired_date]; }
    function cardKey(row) { return JSON.stringify(cardValues(row)); }
    function readyVariants(row) { return (row.variants || []).filter(function (variant) { return !!variant.qrcode_url; }); }
    function groupKey(row) { return JSON.stringify([row.nama_barang, row.expired_date]); }

    function updateSelectionUi(api) {
        api = api || table;
        var count = Object.keys(selectedCards).length;
        $('#selectedCardCount').text(count);
        $('#queueSelectedCount').text(count);
        $('#printSelectedCards,#printStockCardWidget,#btnAddToQueue').prop('disabled', count === 0);
        $('#allReadyCount').text(readyCount);
        $('#printAllCards').prop('disabled', readyCount === 0);
        var current = api.rows({page: 'current'}).data().toArray().filter(function (row) { return readyVariants(row).length > 0; });
        var checked = current.filter(function (row) { return readyVariants(row).every(function (variant) { return !!selectedCards[cardKey(variant)]; }); }).length;
        $('#selectPageCards').prop('checked', current.length > 0 && checked === current.length)
            .prop('indeterminate', checked > 0 && checked < current.length)
            .prop('disabled', current.length === 0);
    }

    function submitPrint(mode, values) {
        var form = $('<form>', {method: 'POST', action: printUrl, target: '_blank'});
        form.append($('<input>', {type: 'hidden', name: 'mode', value: mode}));
        if (mode === 'selected') {
            form.append($('<input>', {type: 'hidden', name: 'selected_items', value: JSON.stringify(values)}));
        }
        $('body').append(form);
        form.trigger('submit');
        form.remove();
    }

    function renderPreview(row, variant) {
        selectedRow = row;
        variant = variant || (selectedVariant && (row.variants || []).filter(function (item) { return item.kode_barang === selectedVariant.kode_barang; })[0])
            || readyVariants(row)[0] || row.variants[0];
        selectedVariant = variant;
        var qr = variant.qrcode_url ? '<img src="' + esc(variant.qrcode_url) + '" alt="QR barang">' : '<span class="text-muted">QR Code belum digenerate</span>';
        var options = (row.variants || []).map(function (item) {
            return '<option value="' + esc(item.kode_barang) + '"' + (item.kode_barang === variant.kode_barang ? ' selected' : '') + '>' + esc(item.kode_barang) + (item.qrcode_url ? '' : ' · QR belum dibuat') + '</option>';
        }).join('');
        $('#previewStockCard').html((row.variants.length > 1 ? '<label class="d-block p-1 mb-1 small">Kode barang <select id="previewCardCode" class="form-control form-control-sm">' + options + '</select></label>' : '') +
            '<div class="stock-card-name">' + esc(row.nama_barang) + '</div>' +
            '<div class="stock-card-qr">' + qr + '</div>' +
            '<div class="stock-card-foot">EXPIRED DATE : ' + esc(formatDate(row.expired_date)) + '</div>');
        $('#printStockCard').prop('disabled', !variant.qrcode_url);
    }

    $('#previewStockCard').on('change', '#previewCardCode', function () {
        if (!selectedRow) { return; }
        var code = $(this).val();
        var variant = selectedRow.variants.filter(function (item) { return item.kode_barang === code; })[0];
        if (variant) { renderPreview(selectedRow, variant); }
    });

    // ==========================================
    // ANTRIAN CETAK & SALINAN (PER USER)
    // ==========================================
    var queueData = [];
    function loadPrintQueue() {
        $.getJSON('<?= base_url('admin/stockopname/print-queue/list') ?>', function (res) {
            if (!res.status) { return; }
            queueData = res.data || [];
            renderQueueUi();
        });
    }

    function renderQueueUi() {
        var totalCopies = 0;
        var distinctCount = queueData.length;
        $('#queueBadgeCount').text(distinctCount + ' item');
        $('#queueDistinctCount').text(distinctCount);

        if (distinctCount === 0) {
            $('#queueEmptyNotice').show();
            $('#queueListWrap').empty();
            $('#queueTotalCopies').text(0);
            $('#queueTotalCopiesFooter').text(0);
            $('#btnPrintQueue').prop('disabled', true);
            return;
        }

        $('#queueEmptyNotice').hide();
        var html = '';
        queueData.forEach(function (item) {
            var copies = Number(item.copies || 1);
            totalCopies += copies;
            html += '<div class="queue-item d-flex justify-content-between align-items-center" data-queue-id="' + esc(item.id) + '" data-code="' + esc(item.kode_barang) + '" data-name="' + esc(item.nama_barang) + '" data-exp="' + esc(item.expired_date) + '" data-qr="' + esc(item.qrcode_url || '') + '">' +
                '<div style="flex:1;min-width:0;padding-right:8px">' +
                    '<div class="queue-title text-truncate">' + esc(item.nama_barang) + '</div>' +
                    '<div class="queue-meta">' + esc(item.kode_barang) + ' · Exp: ' + esc(formatDate(item.expired_date)) + (item.qrcode_ready ? ' <span class="badge badge-success" style="font-size:9px">Siap</span>' : ' <span class="badge badge-warning" style="font-size:9px">QR Belum Ada</span>') + '</div>' +
                '</div>' +
                '<div class="d-flex align-items-center" style="gap:4px">' +
                    '<button type="button" class="btn btn-outline-secondary btn-xs btn-queue-dec" title="Kurangi Salinan" style="width:22px;height:22px;padding:0">-</button>' +
                    '<input type="number" class="queue-copies-input" min="1" max="500" value="' + copies + '" title="Jumlah Salinan">' +
                    '<button type="button" class="btn btn-outline-secondary btn-xs btn-queue-inc" title="Tambah Salinan" style="width:22px;height:22px;padding:0">+</button>' +
                    '<button type="button" class="btn btn-outline-danger btn-xs btn-queue-remove ml-1" title="Hapus Dari Antrian" style="width:22px;height:22px;padding:0"><i class="fas fa-times"></i></button>' +
                '</div>' +
            '</div>';
        });

        $('#queueListWrap').html(html);
        $('#queueTotalCopies').text(totalCopies);
        $('#queueTotalCopiesFooter').text(totalCopies);
        $('#btnPrintQueue').prop('disabled', totalCopies === 0);
    }

    // Klik item antrian untuk preview di card bawah
    $('#queueListWrap').on('click', '.queue-item', function (e) {
        if ($(e.target).closest('input,button').length) { return; }
        $('.queue-item').removeClass('active');
        $(this).addClass('active');
        var code = $(this).attr('data-code');
        var name = $(this).attr('data-name');
        var exp = $(this).attr('data-exp');
        var qrUrl = $(this).attr('data-qr');

        var qr = qrUrl ? '<img src="' + esc(qrUrl) + '" alt="QR barang">' : '<span class="text-muted">QR Code belum digenerate</span>';
        $('#previewStockCard').html('<div class="stock-card-name">' + esc(name) + '</div>' +
            '<div class="stock-card-qr">' + qr + '</div>' +
            '<div class="stock-card-foot">EXPIRED DATE : ' + esc(formatDate(exp)) + '</div>');
        $('#printStockCard').prop('disabled', !qrUrl);
    });

    // Tambah / Kurang salinan
    $('#queueListWrap').on('click', '.btn-queue-inc', function () {
        var wrap = $(this).closest('.queue-item');
        var id = wrap.attr('data-queue-id');
        var input = wrap.find('.queue-copies-input');
        var copies = Math.min(500, Number(input.val() || 1) + 1);
        input.val(copies);
        saveQueueCopies(id, copies);
    });

    $('#queueListWrap').on('click', '.btn-queue-dec', function () {
        var wrap = $(this).closest('.queue-item');
        var id = wrap.attr('data-queue-id');
        var input = wrap.find('.queue-copies-input');
        var copies = Math.max(1, Number(input.val() || 1) - 1);
        input.val(copies);
        saveQueueCopies(id, copies);
    });

    $('#queueListWrap').on('change', '.queue-copies-input', function () {
        var wrap = $(this).closest('.queue-item');
        var id = wrap.attr('data-queue-id');
        var copies = Math.max(1, Math.min(500, Number($(this).val() || 1)));
        $(this).val(copies);
        saveQueueCopies(id, copies);
    });

    function saveQueueCopies(id, copies) {
        $.post('<?= base_url('admin/stockopname/print-queue/update-copies') ?>', secureQrData({
            action_token: actionToken, id: id, copies: copies
        }), function (res) {
            if (res.csrf) { qrCsrf = res.csrf; }
            var item = queueData.filter(function (q) { return String(q.id) === String(id); })[0];
            if (item) { item.copies = copies; }
            renderQueueUi();
        }, 'json');
    }

    // Hapus satu item dari antrian
    $('#queueListWrap').on('click', '.btn-queue-remove', function () {
        var wrap = $(this).closest('.queue-item');
        var id = wrap.attr('data-queue-id');
        $.post('<?= base_url('admin/stockopname/print-queue/remove') ?>', secureQrData({
            action_token: actionToken, id: id
        }), function (res) {
            if (res.csrf) { qrCsrf = res.csrf; }
            queueData = queueData.filter(function (q) { return String(q.id) !== String(id); });
            renderQueueUi();
        }, 'json');
    });

    // Kosongkan seluruh antrian
    $('#btnClearQueue').on('click', function () {
        if (!queueData.length) { return; }
        if (!window.confirm('Kosongkan semua daftar antrian cetak Anda?')) { return; }
        $.post('<?= base_url('admin/stockopname/print-queue/clear') ?>', secureQrData({
            action_token: actionToken
        }), function (res) {
            if (res.csrf) { qrCsrf = res.csrf; }
            queueData = [];
            renderQueueUi();
        }, 'json');
    });

    // Masukkan barang terpilih ke antrian
    $('#btnAddToQueue').on('click', function () {
        var keys = Object.keys(selectedCards);
        if (!keys.length) { return; }
        var items = keys.map(function (k) {
            var v = selectedCards[k];
            return {kode_barang: v[0], nama_barang: v[1], expired_date: v[2], copies: 1};
        });

        $('#btnAddToQueue').prop('disabled', true).text('Menyimpan...');
        $.post('<?= base_url('admin/stockopname/print-queue/add') ?>', secureQrData({
            action_token: actionToken, items: JSON.stringify(items)
        }), function (res) {
            if (res.csrf) { qrCsrf = res.csrf; }
            alertMessage(res.status ? 'success' : 'error', res.message);
            loadPrintQueue();
        }, 'json').always(function () {
            $('#btnAddToQueue').prop('disabled', false).html('<i class="fas fa-plus-circle mr-1"></i> Masukkan Antrian (<span id="queueSelectedCount">' + Object.keys(selectedCards).length + '</span>)');
        });
    });

    // Cetak Seluruh Antrian
    $('#btnPrintQueue').on('click', function () {
        if (!queueData.length) { return; }
        submitPrint('queue');
    });

    // ==========================================
    // DATATABLE MASTER BARANG (LAZY-LOAD)
    // ==========================================
    $.fn.dataTable.ext.errMode = 'none';
    var table;
    function initCatalogDatatable() {
        if (table) return;
        loadPrintQueue();
        table = $('#tableMasterBarangZahir').DataTable({
        processing: true, serverSide: true, searching: true, ordering: false,
        pageLength: 10, lengthMenu: [[10, 25, 50], [10, 25, 50]], searchDelay: 450,
        ajax: {
            url: listUrl, type: 'GET',
            data: function (request) {
                request.search = request.search.value;
                request.qrcode_status = $('#filterQrStatus').val();
                request.refresh = refreshNextDraw ? '1' : '0';
                refreshNextDraw = false;
            },
            dataSrc: function (response) {
                $('#masterBarangError').hide().text('');
                if (response.widgets) {
                    $('#catalogTotal').text(Number(response.widgets.total || 0).toLocaleString('id-ID'));
                    $('#catalogQtyZero').text(Number(response.widgets.qty_zero || 0).toLocaleString('id-ID'));
                    $('#catalogQrDone').text(Number(response.widgets.generated || 0).toLocaleString('id-ID'));
                    $('#catalogQrPending').text(Number(response.widgets.pending || 0).toLocaleString('id-ID'));
                    readyCount = Number(response.widgets.generated || 0);
                    $('#allReadyCount').text(readyCount);
                    $('#printAllCards').prop('disabled', readyCount === 0);
                }
                if (response.stale) {
                    $('#catalogSyncStatus').text('Cache lama; Zahir belum terverifikasi');
                    $('#masterBarangError').text('Sumber Zahir belum terverifikasi. Klik Refresh untuk mengambil data terbaru.').show();
                } else {
                    $('#catalogSyncStatus').text(response.source && response.source.mode === 'imported' ? '' : (response.cached ? 'Data cache Zahir; belum diverifikasi saat ini' : 'Data Zahir terverifikasi'));
                }
                return response.data || [];
            },
            error: function (xhr) {
                var message = (xhr.responseJSON && xhr.responseJSON.error) || 'Koneksi data ZahirDigital gagal. Coba Refresh.';
                $('#masterBarangError').text(message).show();
                if (refreshBusy) {
                    refreshBusy = false;
                    $('#refreshMasterBarang').prop('disabled', false);
                    $('#catalogSyncStatus').text('Pembaruan Zahir gagal');
                    alertMessage('error', message);
                }
            }
        },
        columns: [
            {data: null, orderable: false, searchable: false, render: function (value, type, row) {
                if (type !== 'display') { return ''; }
                var ready = readyVariants(row);
                var checked = ready.length > 0 && ready.every(function (variant) { return !!selectedCards[cardKey(variant)]; });
                return '<input type="checkbox" class="select-stock-card" aria-label="Pilih kartu ' + esc(row.kode_barang) + '"' +
                    (checked ? ' checked' : '') + (ready.length ? '' : ' disabled title="Generate QR dahulu"') + '>';
            }},
            {data: 'kode_barang', render: $.fn.dataTable.render.text()},
            {data: 'nama_barang', render: $.fn.dataTable.render.text()},
            {data: 'expired_date', render: function (value, type) {
                if (!value) { return '-'; }
                if (type !== 'display') { return value; }
                return $.fn.dataTable.render.text().display(formatDate(value));
            }},
            {data: null, render: function (value, type, row) {
                if (type !== 'display') { return row.qrcode_status; }
                return row.variants.map(function (variant) {
                    if (variant.qrcode_recorded && variant.qrcode_status !== 'name_mismatch' && variant.qrcode_status !== 'unverified') {
                        var buttons = '<button type="button" class="btn btn-secondary btn-xs m-1" disabled>Sudah digenerate</button>';
                        if (isSuperadmin) {
                            buttons += '<button type="button" class="btn btn-outline-danger btn-xs delete-catalog-qr m-1" data-id="' + esc(variant.catalog_id) + '" data-expired="' + esc(variant.expired_date) + '"><i class="fas fa-trash-alt"></i> Hapus QR</button>' +
                                '<button type="button" class="btn btn-outline-warning btn-xs regenerate-catalog-qr m-1" data-id="' + esc(variant.catalog_id) + '" data-expired="' + esc(variant.expired_date) + '"><i class="fas fa-redo"></i> Generate Ulang</button>';
                        }
                        buttons += '<button type="button" class="btn btn-outline-info btn-xs resign-catalog-qr m-1" data-id="' + esc(variant.catalog_id) + '" data-expired="' + esc(variant.expired_date) + '" data-file="' + esc(variant.qrcode_file) + '">Resign QR</button>';
                        return buttons;
                    }
                    if (variant.qrcode_status === 'not_in_local' || variant.qrcode_status === 'name_mismatch' || variant.qrcode_status === 'unverified') {
                        return '<div class="text-muted small">' + esc(variant.kode_barang) + ': verifikasi data dahulu</div>';
                    }
                    return '<button type="button" class="btn btn-outline-primary btn-xs generate-catalog-qr m-1" data-id="' + esc(variant.catalog_id || '') + '" data-code="' + esc(variant.kode_barang) + '" data-name="' + esc(variant.nama_barang) + '" data-expired="' + esc(variant.expired_date) + '">Generate ' + esc(variant.kode_barang) + '</button>';
                }).join('');
            }},
            {data: 'qrcode_status', render: function (value, type, row) {
                if (type !== 'display') { return value; }
                return (row.variants || []).map(function (variant) {
                    var labels = {
                        generated: 'QR siap', not_generated: 'QR belum dibuat', file_missing: 'File QR hilang',
                        not_in_local: 'Barang lokal tidak ditemukan', name_mismatch: 'Nama barang berbeda', unverified: 'Sumber Zahir belum terverifikasi'
                    };
                    var badge = variant.qrcode_status === 'generated' ? 'success' : 'warning';
                    var reason = variant.qrcode_reason || '';
                    // Saring dan hilangkan keterangan Data Sesuai dan File QR Tersedia
                    if (/data\s*sesuai/i.test(reason) || /file\s*qr\s*tersedia/i.test(reason)) {
                        reason = '';
                    }
                    var reasonHtml = reason ? '<div class="small text-muted">' + esc(reason) + '</div>' : '';
                    return '<div class="mb-1"><span class="badge badge-' + badge + '">' + esc(variant.kode_barang) + ': ' + esc(labels[variant.qrcode_status] || 'Belum terverifikasi') + '</span>' + reasonHtml + '</div>';
                }).join('');
            }}
        ],
        drawCallback: function () {
            var matched = false;
            this.api().rows({page: 'current'}).every(function () {
                var row = this.data();
                var active = selectedRow && groupKey(row) === groupKey(selectedRow);
                $(this.node()).toggleClass('selected', !!active);
                if (active) { matched = true; renderPreview(row); }
            });
            if (!matched) {
                selectedRow = null; selectedVariant = null;
                $('#previewStockCard').html('<div class="text-muted text-center p-4">Pilih barang dari tabel atau antrian.</div>');
                $('#printStockCard').prop('disabled', true);
            }
            updateSelectionUi(this.api());
        },
        language: {
            search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data', infoEmpty: 'Belum ada data',
            zeroRecords: 'Data tidak ditemukan', processing: 'Memuat data...',
            paginate: {next: 'Berikutnya', previous: 'Sebelumnya'}
        }
    });
    }

    <?php if ($embedded): ?>
    if ($('#data-master-barang').hasClass('active') || window.location.hash === '#data-master-barang') {
        initCatalogDatatable();
    } else {
        $('a[data-toggle="tab"][href="#data-master-barang"]').one('shown.bs.tab', function () {
            initCatalogDatatable();
        });
    }
    <?php else: ?>
    initCatalogDatatable();
    <?php endif; ?>

    $('#filterQrStatus').on('change', function () {
        $('.catalog-stat[data-status]').removeClass('active');
        $('.catalog-stat[data-status="' + $(this).val() + '"]').addClass('active');
        table.ajax.reload();
    });
    $('.catalog-stat[data-status]').on('click', function () {
        var status = $(this).attr('data-status');
        $('#filterQrStatus').val(status);
        $('.catalog-stat[data-status]').removeClass('active');
        $(this).addClass('active');
        table.ajax.reload();
    });

    $('#tableMasterBarangZahir tbody').on('click', 'tr', function (event) {
        if ($(event.target).closest('a,button,input').length) { return; }
        var row = table.row(this).data();
        if (row) { renderPreview(row); table.rows({page:'current'}).nodes().to$().removeClass('selected'); $(this).addClass('selected'); }
    });

    $('#tableMasterBarangZahir tbody').on('change', '.select-stock-card', function () {
        var row = table.row($(this).closest('tr')).data();
        if (!row) { return; }
        var checked = this.checked;
        readyVariants(row).forEach(function (variant) {
            var key = cardKey(variant);
            if (checked) { selectedCards[key] = cardValues(variant); }
            else { delete selectedCards[key]; }
        });
        updateSelectionUi();
    });

    $('#selectPageCards').on('change', function () {
        var checked = this.checked;
        table.rows({page: 'current'}).every(function () {
            var row = this.data();
            if (!readyVariants(row).length) { return; }
            readyVariants(row).forEach(function (variant) {
                var key = cardKey(variant);
                if (checked) { selectedCards[key] = cardValues(variant); }
                else { delete selectedCards[key]; }
            });
            $(this.node()).find('.select-stock-card').prop('checked', checked);
        });
        updateSelectionUi();
    });

    // ==========================================
    // AKSI QR SINGLE (GENERATE, RESIGN, HAPUS, REGENERATE)
    // ==========================================
    $('#tableMasterBarangZahir tbody').on('click', '.generate-catalog-qr', function () {
        var button = $(this), id = button.attr('data-id'), code = button.attr('data-code');
        button.prop('disabled', true).text('Memproses...');
        $.ajax({
            url: '<?= base_url('admin/stockopname/master_barang/generate-qrcode') ?>',
            type: 'POST', dataType: 'json',
            data: secureQrData({
                action_token: actionToken, id: id, kode_barang: code,
                nama_barang: button.attr('data-name'), expired_date: button.attr('data-expired')
            })
        }).done(function (response) {
            if (response.csrf) { qrCsrf = response.csrf; }
            alertMessage(response.status ? 'success' : 'error', response.message || 'Gagal generate QR Code.');
            if (response.status) { table.ajax.reload(null, false); loadPrintQueue(); }
        }).fail(function (xhr) {
            alertMessage('error', (xhr.responseJSON && xhr.responseJSON.message) || 'Server gagal generate QR Code.');
        }).always(function () {
            button.prop('disabled', false).text('Generate QR');
        });
    });

    // Hapus QR Code (Khusus Superadmin)
    $('#tableMasterBarangZahir tbody').on('click', '.delete-catalog-qr', function () {
        if (!isSuperadmin) { return; }
        var button = $(this);
        var id = button.attr('data-id');
        var expired = button.attr('data-expired');
        if (!window.confirm('Hapus QR Code untuk barang ini? File fisik dan status QR akan dihapus.')) { return; }
        button.prop('disabled', true);
        $.post('<?= base_url('admin/stockopname/master_barang/delete-qrcode') ?>', secureQrData({
            action_token: actionToken, id: id, expired_date: expired
        }), function (res) {
            if (res.csrf) { qrCsrf = res.csrf; }
            alertMessage(res.status ? 'success' : 'error', res.message);
            if (res.status) {
                table.ajax.reload(null, false);
                loadPrintQueue();
            }
        }, 'json').fail(function () {
            alertMessage('error', 'Gagal menghubungi server untuk menghapus QR.');
        }).always(function () {
            button.prop('disabled', false);
        });
    });

    // Generate Ulang QR Code Tunggal (Khusus Superadmin)
    $('#tableMasterBarangZahir tbody').on('click', '.regenerate-catalog-qr', function () {
        if (!isSuperadmin) { return; }
        var button = $(this);
        var id = button.attr('data-id');
        var expired = button.attr('data-expired');
        if (!window.confirm('Generate ulang QR Code untuk barang ini? QR lama akan dihapus dan dibuat baru.')) { return; }
        button.prop('disabled', true).text('Memproses...');
        $.post('<?= base_url('admin/stockopname/master_barang/regenerate-qrcode') ?>', secureQrData({
            action_token: actionToken, id: id, expired_date: expired
        }), function (res) {
            if (res.csrf) { qrCsrf = res.csrf; }
            alertMessage(res.status ? 'success' : 'error', res.message);
            if (res.status) {
                table.ajax.reload(null, false);
                loadPrintQueue();
            }
        }, 'json').fail(function () {
            alertMessage('error', 'Gagal menghubungi server untuk generate ulang QR.');
        }).always(function () {
            button.prop('disabled', false).html('<i class="fas fa-redo"></i> Generate Ulang');
        });
    });

    // Resign QR Code
    $('#tableMasterBarangZahir tbody').on('click', '.resign-catalog-qr', function () {
        var button = $(this);
        var reason = window.prompt('Alasan Resign QR (5-500 karakter). Aset diterbitkan ulang; payload/label lama tetap sah selama referensi aktif.');
        if (reason === null) { return; }
        reason = reason.trim();
        if (reason.length < 5 || reason.length > 500) { alertMessage('error', 'Alasan wajib 5-500 karakter.'); return; }
        if (!window.confirm('Terbitkan ulang QR barang dan expired ini? Tindakan akan dicatat.')) { return; }
        button.prop('disabled', true);
        $.post('<?= base_url('admin/stockopname/master_barang/resign-qrcode') ?>', secureQrData({
            action_token: actionToken, id: button.attr('data-id'), expired_date: button.attr('data-expired'), expected_file: button.attr('data-file'), reason: reason
        }), function (response) {
            if (response.csrf) { qrCsrf = response.csrf; }
            alertMessage(response.status ? 'success' : 'error', response.message);
            if (response.status) { selectedCards = {}; table.ajax.reload(null, false); loadPrintQueue(); }
        }, 'json').fail(function () {
            alertMessage('error', 'Resign QR gagal. Muat ulang status sebelum mencoba kembali.');
        }).always(function () {
            button.prop('disabled', false);
        });
    });

    // ==========================================
    // BATCH GENERATE & REGENERATE
    // ==========================================
    var catalogBatchBusy = false;
    function runCatalogQrBatch(url, repair) {
        if (catalogBatchBusy) { return; }
        if (repair && !window.confirm('Pulihkan semua file QR yang hilang pada katalog aktif? QR yang masih tersedia akan dilewati.')) { return; }
        var offset = 0, fingerprint = '', counts = {success: 0, skipped: 0, failed: 0}, errors = [];
        catalogBatchBusy = true;
        $('#generateAllCatalogQr,#reassignMissingCatalogQr,#btnRegenerateAllCatalogQr').prop('disabled', true);
        function finish() {
            catalogBatchBusy = false;
            $('#generateAllCatalogQr,#reassignMissingCatalogQr,#btnRegenerateAllCatalogQr').prop('disabled', false);
            selectedCards = {};
            table.ajax.reload(null, false);
            loadPrintQueue();
        }
        function next() {
            $.post(url, secureQrData({offset: offset, action_token: actionToken, catalog_fingerprint: fingerprint}), null, 'json')
                .done(function (res) {
                    if (res.csrf) { qrCsrf = res.csrf; }
                    if (!res.status) { finish(); alertMessage('error', res.message); return; }
                    var data = res.data || {}, nextOffset = Number(data.next_offset || 0), total = Number(data.total || 0);
                    if (total > offset && nextOffset <= offset) { finish(); alertMessage('error', 'Proses tidak maju. Muat ulang katalog lalu coba kembali.'); return; }
                    Object.keys(counts).forEach(function (key) { counts[key] += Number((data.counts || {})[key] || 0); });
                    errors = errors.concat(data.errors || []);
                    fingerprint = data.catalog_fingerprint || fingerprint;
                    offset = nextOffset;
                    $('#generateAllProgress').text((repair ? 'Reassign: ' : '') + offset + ' / ' + total + ' | ' + (repair ? 'Dipulihkan: ' : 'Berhasil: ') + counts.success + ' | Dilewati: ' + counts.skipped + ' | Gagal: ' + counts.failed);
                    if (offset < total) { window.setTimeout(next, 0); }
                    else {
                        finish();
                        if (errors.length) { showGenerateErrors(errors, counts); }
                        else if (repair) { alertMessage('success', counts.success + ' file QR dipulihkan.'); }
                    }
                }).fail(function () {
                    finish();
                    alertMessage('error', 'Proses terputus. Jalankan kembali; QR yang sudah tersedia akan dilewati.');
                });
        }
        next();
    }

    // Generate Ulang Batch Khusus Superadmin (Batch maksimal 100 dengan progres 1/100..100/100)
    $('#btnRegenerateAllCatalogQr').on('click', function () {
        if (!isSuperadmin || catalogBatchBusy) { return; }
        if (!window.confirm('Generate ulang SEMUA QR Code katalog? Proses berjalan per batch (maks 100 per batch) dan QR lama akan diperbarui.')) { return; }
        var offset = 0, counts = {success: 0, skipped: 0, failed: 0}, errors = [];
        catalogBatchBusy = true;
        $('#generateAllCatalogQr,#reassignMissingCatalogQr,#btnRegenerateAllCatalogQr').prop('disabled', true);

        function finishRegenerate() {
            catalogBatchBusy = false;
            $('#generateAllCatalogQr,#reassignMissingCatalogQr,#btnRegenerateAllCatalogQr').prop('disabled', false);
            selectedCards = {};
            table.ajax.reload(null, false);
            loadPrintQueue();
        }

        function runBatch() {
            $.post('<?= base_url('admin/stockopname/master_barang/regenerate-all-qrcode') ?>', secureQrData({
                offset: offset, action_token: actionToken
            }), function (res) {
                if (res.csrf) { qrCsrf = res.csrf; }
                if (!res.status) {
                    finishRegenerate();
                    alertMessage('error', res.message || 'Gagal generate ulang.');
                    return;
                }
                var d = res.data || {};
                var batchSize = Number(d.batch_size || 0);
                var total = Number(d.total || 0);
                var nextOffset = Number(d.next_offset || 0);
                Object.keys(counts).forEach(function (k) { counts[k] += Number((d.counts || {})[k] || 0); });
                errors = errors.concat(d.errors || []);

                // Tampilkan progres batch sesuai jumlah yang benar-benar selesai: 1/N ... N/N
                var step = 0;
                var animTimer = setInterval(function () {
                    step++;
                    var current = Math.min(step, batchSize);
                    $('#generateAllProgress').text('Batch (' + current + '/' + batchSize + ') · Total: ' + nextOffset + '/' + total + ' | Sukses: ' + counts.success + ' | Gagal: ' + counts.failed);
                    if (step >= batchSize) {
                        clearInterval(animTimer);
                        offset = nextOffset;
                        if (offset < total && !d.is_last_batch) {
                            window.setTimeout(runBatch, 50);
                        } else {
                            finishRegenerate();
                            alertMessage('success', 'Generate ulang selesai! Sukses: ' + counts.success + ', Gagal: ' + counts.failed);
                        }
                    }
                }, Math.max(10, Math.floor(1200 / Math.max(1, batchSize))));

            }, 'json').fail(function () {
                finishRegenerate();
                alertMessage('error', 'Koneksi terputus saat memproses batch generate ulang.');
            });
        }

        runBatch();
    });

    $('#generateAllCatalogQr').on('click', function () { runCatalogQrBatch('<?= base_url('admin/stockopname/master_barang/generate-all-qrcode') ?>', false); });
    $('#reassignMissingCatalogQr').on('click', function () { runCatalogQrBatch('<?= base_url('admin/stockopname/master_barang/reassign-missing-qrcode') ?>', true); });

    function showGenerateErrors(errors, counts) {
        var unique = [];
        var seen = {};
        errors.forEach(function (message) {
            if (!seen[message]) { seen[message] = true; unique.push(message); }
        });
        var list = '<ol class="text-left mb-0 pl-4" style="max-height:320px;overflow-y:auto;overflow-wrap:anywhere;padding-right:12px">' +
            unique.map(function (message) { return '<li class="mb-2 pl-1">' + esc(message) + '</li>'; }).join('') + '</ol>';
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning', title: 'Hasil Generate QR Code', width: 680,
                html: '<p class="text-muted mb-3">Berhasil: ' + counts.success + ' · Dilewati: ' + counts.skipped + ' · Gagal: ' + counts.failed + '</p>' + list,
                confirmButtonText: 'Tutup'
            });
        } else {
            window.alert('Hasil Generate QR Code\nBerhasil: ' + counts.success + ' · Dilewati: ' + counts.skipped + ' · Gagal: ' + counts.failed + '\n\n' +
                unique.map(function (message, index) { return (index + 1) + '. ' + message; }).join('\n'));
        }
    }

    // ==========================================
    // CETAK DIRECT (SINGLE / SELECTED / ALL)
    // ==========================================
    $('#printStockCard').on('click', function () {
        if (selectedVariant && selectedVariant.qrcode_url) { submitPrint('selected', [cardValues(selectedVariant)]); }
    });
    $('#printStockCardWidget,#printSelectedCards').on('click', function () {
        var values = Object.keys(selectedCards).map(function (key) { return selectedCards[key]; });
        if (values.length) { submitPrint('selected', values); }
    });
    $('#printAllCards').on('click', function () {
        if (readyCount > 0) { submitPrint('all'); }
    });

    // Refresh data katalog
    function refreshCatalog(showAlert) {
        if (refreshBusy) { return; }
        refreshBusy = true;
        $('#refreshMasterBarang').prop('disabled', true);
        $('#catalogSyncStatus').text('Memperbarui data Zahir...');
        refreshNextDraw = true;
        table.ajax.reload(function (response) {
            refreshBusy = false;
            $('#refreshMasterBarang').prop('disabled', false);
            if (response && !response.stale && !response.cached) {
                $('#catalogSyncStatus').text(response.source && response.source.mode === 'imported' ? '' : 'Data Zahir terbaru siap');
                if (showAlert && !(response.source && response.source.mode === 'imported')) { alertMessage('success', 'Data ZahirDigital berhasil diperbarui.'); }
            } else {
                $('#catalogSyncStatus').text('Pembaruan Zahir gagal');
                if (showAlert) { alertMessage('error', (response && response.error) || 'Sumber Zahir belum terverifikasi. Coba Refresh kembali.'); }
            }
        }, false);
    }
    window.refreshStockopnameZahirCatalog = refreshCatalog;
    $('#refreshMasterBarang').on('click', function () { refreshCatalog(true); });
});
</script>
