<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $embedded = !empty($embedded); ?>
<?php if (!$embedded): ?>
<body class="hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view('partial/main/navbar') ?>
    <?php $this->load->view('partial/main/sidebar') ?>
    <div class="content-wrapper">
        <section class="content-header"><div class="container-fluid d-flex align-items-center justify-content-between">
            <h1 class="m-0"><?= html_escape($page_heading ?? 'Master Barang') ?></h1>
            <a href="<?= base_url('admin/stockopname/master_opname') ?>" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left"></i> Master Opname</a>
        </div></section>
        <section class="content"><div class="container-fluid">
<?php else: ?>
        <section class="content pt-3"><div class="container-fluid">
<?php endif; ?>
            <div class="row catalog-stats">
                <div class="col-12 col-md-6 col-xl-3 mb-3"><button type="button" class="catalog-stat" data-status=""><span>Total Data</span><strong id="catalogTotal">0</strong><small>Kelompok nama dan expired date</small></button></div>
                <div class="col-12 col-md-6 col-xl-3 mb-3"><a class="catalog-stat" href="<?= base_url('admin/stockopname/master_opname/qty-zero') ?>"><span>Total Barang Qty 0</span><strong id="catalogQtyZero">0</strong><small>Master opname lokal · lihat data qty 0</small></a></div>
                <div class="col-12 col-md-6 col-xl-3 mb-3"><button type="button" class="catalog-stat" data-status="generated"><span>QR Code Selesai</span><strong id="catalogQrDone">0</strong><small>Jumlah barang siap · filter selesai</small></button></div>
                <div class="col-12 col-md-6 col-xl-3 mb-3"><button type="button" class="catalog-stat" data-status="not_generated"><span>QR Code Pending</span><strong id="catalogQrPending">0</strong><small>Jumlah barang pending · filter pending</small></button></div>
                <div class="col-12 col-md-6 col-xl-3 mb-3"><button type="button" class="catalog-print" id="printStockCardWidget" disabled><i class="fas fa-id-card"></i><strong>Print Kartu Stock</strong><small>Cetak kartu barang terpilih</small></button></div>
            </div>
            <div class="row"><div class="col-lg-8 mb-3"><div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Data barang dan expired date dari ZahirDigital</span>
                <div><small id="catalogSyncStatus" class="text-muted mr-2"></small><button type="button" class="btn btn-outline-primary btn-sm" id="refreshMasterBarang"><i class="fas fa-sync-alt"></i> Refresh</button></div>
            </div>
            <div class="card-body">
                <div id="masterBarangError" class="alert alert-danger" role="alert" style="display:none"></div>
                <p class="text-muted">Hanya barang dengan total Qty Lot lebih dari 0. Tanda * pada nama dihapus. Satu baris untuk setiap nama barang dan expired date; bila terdapat beberapa kode, kode berawalan Q diprioritaskan untuk dimensi dan QR.</p>
                <div class="form-group d-flex align-items-center flex-wrap" style="gap:8px">
                    <button type="button" class="btn btn-primary btn-sm" id="generateAllCatalogQr">Generate All QR Code</button>
                    <span id="generateAllProgress" role="status" class="small text-muted"></span>
                    <label for="filterQrStatus" class="mb-0">Status QR</label>
                    <select id="filterQrStatus" class="form-control form-control-sm" style="max-width:210px">
                        <option value="">Semua</option><option value="generated">Sudah digenerate</option><option value="not_generated">Belum digenerate</option>
                    </select>
                    <button type="button" class="btn btn-success btn-sm" id="printSelectedCards" disabled>Cetak Tercentang (<span id="selectedCardCount">0</span>)</button>
                    <button type="button" class="btn btn-primary btn-sm" id="printAllCards" disabled>Cetak Semua QR Siap (<span id="allReadyCount">0</span>)</button>
                </div>
                <div class="table-responsive"><table id="tableMasterBarangZahir" class="table table-bordered table-striped table-hover w-100">
                    <thead><tr><th><input type="checkbox" id="selectPageCards" title="Centang semua QR siap di halaman ini" aria-label="Centang semua QR siap di halaman ini"></th><th>Kode Barang</th><th>Nama Barang</th><th>Expired Date</th><th>P</th><th>L</th><th>T</th><th>Dimensi</th><th>Aksi QR</th><th>Status QR</th></tr></thead><tbody></tbody>
                </table></div>
            </div>
        </div></div><div class="col-lg-4 mb-3"><div class="card h-100" style="position:sticky;top:12px">
            <div class="card-header d-flex justify-content-between align-items-center"><span>Preview Cetak Kartu Stock</span><button type="button" class="btn btn-success btn-sm" id="printStockCard" disabled><i class="fas fa-print"></i> Cetak</button></div>
            <div class="card-body"><p class="text-muted small">Pilih baris barang untuk melihat kartu. Scan QR menampilkan nama barang dan expired date.</p>
                <div id="previewStockCard" class="stock-card-preview"><div class="text-muted text-center p-4">Pilih barang dari tabel.</div></div>
            </div>
        </div></div></div></div>
<?php if (!$embedded): ?>
        </section>
    </div>
    <footer class="main-footer"><strong>Copyright &copy; 2022 <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.</strong></footer>
</div>
<?php else: ?>
        </div></section>
<?php endif; ?>
<style>
    .catalog-stat{width:100%;min-height:112px;display:flex;flex-direction:column;align-items:flex-start;text-align:left;padding:16px;border:1px solid #dfe5ec;border-left:4px solid #0f766e;border-radius:8px;background:#fff;color:#172033;text-decoration:none;cursor:pointer}
    .catalog-stat:hover,.catalog-stat.active{border-color:#0f766e;color:#172033;text-decoration:none;box-shadow:0 0 0 3px rgba(15,118,110,.12)}
    .catalog-stat span{font-size:11px;font-weight:800;text-transform:uppercase;color:#64748b}.catalog-stat strong{font-size:28px;line-height:1.15;margin-top:8px}.catalog-stat small{font-size:11px;color:#64748b;margin-top:5px}
    .catalog-print{width:100%;min-height:112px;display:flex;flex-direction:column;align-items:flex-start;text-align:left;padding:16px;border:0;border-left:4px solid #0759b7;border-radius:8px;background:#0878ef;color:#fff;cursor:pointer}.catalog-print:disabled{opacity:.65;cursor:not-allowed}.catalog-print i{font-size:18px}.catalog-print strong{font-size:15px;margin-top:8px}.catalog-print small{font-size:11px;margin-top:6px}
    #tableMasterBarangZahir tbody tr{cursor:pointer}#tableMasterBarangZahir tbody tr.selected{background:#e8f4ff}
    .stock-card-preview{width:100%;max-width:96mm;margin:0 auto;border:1px solid #111827;background:#fff;color:#111827;text-align:center}
    .stock-card-preview .stock-card-name{padding:18px 10px 8px;font-size:17px;font-weight:700;word-break:break-word}
    .stock-card-preview .stock-card-qr{height:340px;display:flex;align-items:center;justify-content:center;margin-bottom:24px}
    .stock-card-preview .stock-card-qr img{width:320px;height:320px;object-fit:contain}
    .stock-card-preview .stock-card-foot{border-top:1px solid #111827;padding:12px;font-size:14px;font-weight:700}
    @media print{body *{visibility:hidden!important}#previewStockCard,#previewStockCard *{visibility:visible!important}#previewStockCard label{display:none!important}#previewStockCard{position:absolute;left:0;top:0;width:96mm;max-width:none}}
</style>
<script>
window.addEventListener('load', function () {
    var refreshBusy = false;
    var refreshNextDraw = false;
    var selectedRow = null;
    var selectedVariant = null;
    var selectedCards = {};
    var readyCount = 0;
    var listUrl = '<?= base_url('admin/stockopname/master_barang/list') ?>';
    var printUrl = '<?= base_url('admin/stockopname/master_barang/print-kartu-stock') ?>';
    var actionToken = '<?= html_escape($master_action_token ?? '') ?>';
    $('#generateAllCatalogQr').on('click', function () {
        var button = $(this), offset = 0, counts = {success: 0, skipped: 0, failed: 0}, errors = [];
        button.prop('disabled', true);
        function next() {
            $.post('<?= base_url('admin/stockopname/master_barang/generate-all-qrcode') ?>', {offset: offset, action_token: actionToken}, null, 'json')
                .done(function (res) {
                    if (!res.status) { alertMessage('error', res.message); button.prop('disabled', false); return; }
                    var data = res.data || {};
                    Object.keys(counts).forEach(function (key) { counts[key] += Number((data.counts || {})[key] || 0); });
                    errors = errors.concat(data.errors || []);
                    offset = Number(data.next_offset || 0);
                    $('#generateAllProgress').text(offset + ' / ' + data.total + ' · Berhasil: ' + counts.success + ' · Dilewati: ' + counts.skipped + ' · Gagal: ' + counts.failed);
                    if (offset < Number(data.total || 0)) { window.setTimeout(next, 0); }
                    else { button.prop('disabled', false); table.ajax.reload(null, false); if (errors.length) { showGenerateErrors(errors, counts); } }
                }).fail(function () { button.prop('disabled', false); alertMessage('error', 'Batch terputus. Jalankan kembali; QR tersimpan akan dilewati.'); });
        }
        next();
    });
    function alertMessage(icon, message) {
        if (typeof Swal !== 'undefined') { Swal.fire({icon: icon, text: message}); }
        else { window.alert(message); }
    }
    function showGenerateErrors(errors, counts) {
        var unique = [];
        var seen = {};
        errors.forEach(function (message) {
            if (!seen[message]) { seen[message] = true; unique.push(message); }
        });
        var list = '<ol class="text-left mb-0 pl-4" style="max-height:320px;overflow-y:auto;overflow-wrap:anywhere;padding-right:12px">' +
            unique.map(function (message) { return '<li class="mb-2 pl-1">' + esc(message) + '</li>'; }).join('') + '</ol>';
        if (typeof Swal !== 'undefined') {
            Swal.fire({icon: 'warning', title: 'Hasil Generate QR Code', width: 680,
                html: '<p class="text-muted mb-3">Berhasil: ' + counts.success + ' · Dilewati: ' + counts.skipped + ' · Gagal: ' + counts.failed + '</p>' + list,
                confirmButtonText: 'Tutup'});
        } else {
            window.alert('Hasil Generate QR Code\nBerhasil: ' + counts.success + ' · Dilewati: ' + counts.skipped + ' · Gagal: ' + counts.failed + '\n\n' +
                unique.map(function (message, index) { return (index + 1) + '. ' + message; }).join('\n'));
        }
    }
    function esc(value) { return $('<div>').text(value === null || value === undefined ? '' : String(value)).html(); }
    function displayNumber(value) { return value === null || value === undefined ? '-' : esc(value); }
    function formatDate(value) { var p = String(value || '').split('-'); return p.length === 3 ? p[2] + '/' + p[1] + '/' + p[0] : (value || '-'); }
    function cardValues(row) { return [row.kode_barang, row.nama_barang, row.expired_date]; }
    function cardKey(row) { return JSON.stringify(cardValues(row)); }
    function readyVariants(row) { return (row.variants || []).filter(function (variant) { return !!variant.qrcode_url; }); }
    function groupKey(row) { return JSON.stringify([row.nama_barang, row.expired_date]); }
    function updateSelectionUi(api) {
        api = api || table;
        var count = Object.keys(selectedCards).length;
        $('#selectedCardCount').text(count);
        $('#printSelectedCards,#printStockCardWidget').prop('disabled', count === 0);
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
        $('#previewStockCard').html((row.variants.length > 1 ? '<label class="d-block p-2 mb-0">Kode barang <select id="previewCardCode" class="form-control form-control-sm">' + options + '</select></label>' : '') +
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
    $.fn.dataTable.ext.errMode = 'none';
    var table = $('#tableMasterBarangZahir').DataTable({
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
                    $('#catalogSyncStatus').text(response.cached ? 'Data cache Zahir; belum diverifikasi saat ini' : 'Data Zahir terverifikasi');
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
                var parts = value.split('-');
                var formatted = parts.length === 3 ? parts[2] + '/' + parts[1] + '/' + parts[0] : value;
                return $.fn.dataTable.render.text().display(formatted);
            }},
            {data: 'p', render: displayNumber}, {data: 'l', render: displayNumber},
            {data: 't', render: displayNumber}, {data: 'dimensi', render: displayNumber},
            {data: null, render: function (value, type, row) {
                if (type !== 'display') { return row.qrcode_status; }
                return row.variants.map(function (variant) {
                    if (variant.qrcode_url) { return '<div class="text-muted small">' + esc(variant.kode_barang) + ': siap</div>'; }
                    if (variant.qrcode_status === 'not_in_local' || variant.qrcode_status === 'name_mismatch' || variant.qrcode_status === 'unverified') { return '<div class="text-muted small">' + esc(variant.kode_barang) + ': verifikasi data dahulu</div>'; }
                    return '<button type="button" class="btn btn-outline-primary btn-xs generate-catalog-qr m-1" data-id="' + esc(variant.catalog_id || '') + '" data-code="' + esc(variant.kode_barang) + '" data-name="' + esc(variant.nama_barang) + '" data-expired="' + esc(variant.expired_date) + '">Generate ' + esc(variant.kode_barang) + '</button>';
                }).join('');
            }},
            {data: 'qrcode_status', render: function (value, type, row) {
                if (type !== 'display') { return value; }
                return (row.variants || []).map(function (variant) {
                    var labels = {generated: 'QR siap', not_generated: 'QR belum dibuat', file_missing: 'File QR hilang',
                        not_in_local: 'Barang lokal tidak ditemukan', name_mismatch: 'Nama barang berbeda', unverified: 'Sumber Zahir belum terverifikasi'};
                    var badge = variant.qrcode_status === 'generated' ? 'success' : 'warning';
                    return '<div class="mb-2"><span class="badge badge-' + badge + '">' + esc(variant.kode_barang) + ': ' + esc(labels[variant.qrcode_status] || 'Belum terverifikasi') + '</span>' +
                        '<div class="small text-muted">' + esc(variant.qrcode_reason || '') + '</div></div>';
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
            if (!matched) { selectedRow = null; selectedVariant = null; $('#previewStockCard').html('<div class="text-muted text-center p-4">Pilih barang dari tabel.</div>'); $('#printStockCard').prop('disabled', true); }
            updateSelectionUi(this.api());
        },
        language: {
            search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data', infoEmpty: 'Belum ada data',
            zeroRecords: 'Data tidak ditemukan', processing: 'Memuat data...',
            paginate: {next: 'Berikutnya', previous: 'Sebelumnya'}
        }
    });
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
    $('#tableMasterBarangZahir tbody').on('click', '.generate-catalog-qr', function () {
        var button = $(this), id = button.attr('data-id'), code = button.attr('data-code');
        button.prop('disabled', true).text('Memproses...');
        $.ajax({url: '<?= base_url('admin/stockopname/master_barang/generate-qrcode') ?>', type: 'POST', dataType: 'json', data: {
            id: id, kode_barang: code, nama_barang: button.attr('data-name'), expired_date: button.attr('data-expired')
        }})
            .done(function (response) {
                alertMessage(response.status ? 'success' : 'error', response.message || 'Gagal generate QR Code.');
                if (response.status) { table.ajax.reload(null, false); }
            })
            .fail(function (xhr) { alertMessage('error', (xhr.responseJSON && xhr.responseJSON.message) || 'Server gagal generate QR Code.'); })
            .always(function () { button.prop('disabled', false).text('Generate QR'); });
    });
    $('#printStockCard').on('click', function () { if (selectedVariant && selectedVariant.qrcode_url) { submitPrint('selected', [cardValues(selectedVariant)]); } });
    $('#printStockCardWidget,#printSelectedCards').on('click', function () {
        var values = Object.keys(selectedCards).map(function (key) { return selectedCards[key]; });
        if (values.length) { submitPrint('selected', values); }
    });
    $('#printAllCards').on('click', function () { if (readyCount > 0) { submitPrint('all'); } });
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
                $('#catalogSyncStatus').text('Data Zahir terbaru siap');
                if (showAlert) { alertMessage('success', 'Data ZahirDigital berhasil diperbarui.'); }
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
