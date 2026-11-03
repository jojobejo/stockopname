<?php
defined('BASEPATH') or exit('No direct script access allowed');
$pendingMasterOptions = $pending_master_options ?? [];
$kodeOptions = [];
foreach ($pendingMasterOptions as $option) {
    $kode = (string)($option['kode_barang'] ?? '');
    if ($kode !== '' && !isset($kodeOptions[$kode])) {
        $kodeOptions[$kode] = (string)($option['nama_barang'] ?? '');
    }
}
?>
<body class="hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view('partial/main/navbar') ?>
    <?php $this->load->view('partial/main/sidebar') ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid d-flex align-items-center justify-content-between">
                <h1 class="m-0"><?= html_escape($page_heading ?? 'Barang Pending') ?></h1>
                <div>
                    <a href="<?= base_url('admin/stockopname/barang-pending/export-csv') ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-file-csv"></i> Export CSV
                    </a>
                    <a href="<?= base_url('admin/stockopname/monitoring/pending-opname') ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Dashboard Pending
                    </a>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <style>
                    .pending-panel{background:#fff;border:1px solid #e2e8f0;border-radius:8px;box-shadow:0 8px 22px rgba(16,24,40,.06)}
                    .pending-panel .card-header{background:#fff;border-bottom:1px solid #e2e8f0;font-weight:700}
                    .pending-stat{border:1px solid #e2e8f0;border-radius:8px;background:#fff;padding:12px}
                    .pending-stat span{display:block;color:#64748b;font-size:12px}.pending-stat strong{font-size:20px}
                    .pending-code{font-family:monospace;font-weight:700}.pending-table-wrap{max-height:620px;overflow:auto}
                    .pending-pagination{padding:12px 16px;border-top:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
                    .pending-panel .select2-container{width:100%!important}
                </style>
                <div class="row mb-3">
                    <div class="col-md-3 mb-2"><div class="pending-stat"><span>Total Item</span><strong id="statTotalItem"><?= (int)($summary['total_item'] ?? 0) ?></strong></div></div>
                    <div class="col-md-3 mb-2"><div class="pending-stat"><span>Total Qty</span><strong id="statTotalQty"><?= (int)($summary['total_qty'] ?? 0) ?></strong></div></div>
                    <div class="col-md-3 mb-2"><div class="pending-stat"><span>Total Qty PCS</span><strong id="statTotalPcs"><?= (int)($summary['total_qty_pcs'] ?? 0) ?></strong></div></div>
                    <div class="col-md-3 mb-2"><div class="pending-stat"><span>Total Qty Box</span><strong id="statTotalBox"><?= (int)($summary['total_qty_box'] ?? 0) ?></strong></div></div>
                </div>
                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <div class="card pending-panel">
                            <div class="card-header"><i class="fas fa-box-open mr-1"></i> Input Barang Pending</div>
                            <form id="formBarangPending" class="card-body">
                                <input type="hidden" name="id" id="pendingId">
                                <input type="hidden" name="no_lot" id="noLot">
                                <div class="form-group">
                                    <label>Kode faktur</label>
                                    <input name="kd_do" id="kdDo" class="form-control pending-code" maxlength="100" required>
                                </div>
                                <div class="form-group">
                                    <label>Kode barang</label>
                                    <select name="kode_barang" id="kodeBarang" class="form-control pending-code" required>
                                        <option value="">Pilih kode barang</option>
                                        <?php foreach ($kodeOptions as $kode => $nama): ?>
                                            <option value="<?= html_escape($kode) ?>" data-kode="<?= html_escape($kode) ?>" data-nama="<?= html_escape($nama) ?>"><?= html_escape($kode . ' - ' . $nama) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Nama barang</label>
                                    <textarea name="nama_barang" id="namaBarang" class="form-control" rows="3" readonly required></textarea>
                                </div>
                                <div class="form-row">
                                    <div class="col-8 form-group">
                                        <label>Expired date</label>
                                        <select name="expired_date" id="expiredDate" class="form-control" required disabled>
                                            <option value="">Pilih kode barang dulu</option>
                                        </select>
                                    </div>
                                    <div class="col-4 form-group">
                                        <label>Dimensi</label>
                                        <input id="dimensi" type="number" min="0" step="1" class="form-control" value="0" readonly>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-4 form-group"><label>Qty PCS</label><input name="qty_pcs" id="qtyPcs" type="number" min="0" step="1" class="form-control" value="0" required></div>
                                    <div class="col-4 form-group"><label>Qty Box</label><input name="qty_box" id="qtyBox" type="number" min="0" step="1" class="form-control" value="0" required></div>
                                    <div class="col-4 form-group"><label>Qty</label><input name="qty" id="qty" type="number" min="0" step="1" class="form-control" value="0" readonly required></div>
                                </div>
                                <div class="d-flex">
                                    <button type="submit" id="btnSimpanPending" class="btn btn-primary flex-fill mr-2"><i class="fas fa-save"></i> Simpan</button>
                                    <button type="button" id="btnResetPending" class="btn btn-outline-secondary"><i class="fas fa-undo"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-8 mb-3">
                        <div class="card pending-panel">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>Data Pending</span>
                                <div class="input-group input-group-sm" style="max-width:360px">
                                    <input type="search" class="form-control" id="searchPending" placeholder="Cari barang pending">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary" type="button" id="btnCariPending"><i class="fas fa-search"></i></button>
                                        <button class="btn btn-outline-secondary" type="button" id="btnRefreshPending"><i class="fas fa-sync-alt"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="pending-table-wrap">
                                <table class="table table-sm table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Expired</th>
                                            <th class="text-right">Qty</th>
                                            <th class="text-right">Box</th>
                                            <th class="text-right">PCS</th>
                                            <th>Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="barangPendingRows"><tr><td colspan="8" class="text-center text-muted p-4">Memuat data...</td></tr></tbody>
                                </table>
                            </div>
                            <div class="pending-pagination">
                                <div class="text-muted small" id="pendingPageInfo">Menampilkan 0-0 dari 0 data</div>
                                <ul class="pagination pagination-sm mb-0" id="pendingPagination"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <footer class="main-footer"><strong>Copyright &copy; 2022 <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.</strong></footer>
</div>
<script>
window.addEventListener('load', function () {
    var pendingRows = {};
    var requestedEditId = '';
    try {
        requestedEditId = new URLSearchParams(window.location.search).get('edit_id') || '';
    } catch (error) {
        requestedEditId = '';
    }
    var masterRows = <?= json_encode($pendingMasterOptions, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?> || [];
    var masterByCode = {};
    var pendingPage = 1;
    masterRows.forEach(function (row) {
        var kode = String(row.kode_barang || '');
        if (!masterByCode[kode]) { masterByCode[kode] = []; }
        masterByCode[kode].push(row);
    });
    function esc(value) { return String(value === null || value === undefined ? '' : value).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;'); }
    function notify(icon, message) { if (typeof Swal !== 'undefined') { Swal.fire({toast:true,position:'top-end',icon:icon,title:message,showConfirmButton:false,timer:2600}); } else { alert(message); } }
    function toInt(value) { var parsed = parseInt(value, 10); return isNaN(parsed) || parsed < 0 ? 0 : parsed; }
    function formatDate(value) {
        value = String(value || '').trim();
        if (!value || value === '0000-00-00') { return '-'; }
        var match = value.match(/^(\d{4})-(\d{2})-(\d{2})/);
        return match ? match[3] + '/' + match[2] + '/' + match[1] : value;
    }
    function initKodeBarangSelect2() {
        if (!$.fn.select2) { return; }
        $('#kodeBarang').select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: 'Cari kode barang atau nama barang',
            allowClear: true,
            matcher: function (params, data) {
                var term = $.trim(params.term || '').toLowerCase();
                if (term === '') { return data; }
                if (!data.element) { return null; }

                var $option = $(data.element);
                var kode = String($option.data('kode') || '').toLowerCase();
                var nama = String($option.data('nama') || '').toLowerCase();
                var text = String(data.text || '').toLowerCase();
                return kode.indexOf(term) > -1 || nama.indexOf(term) > -1 || text.indexOf(term) > -1 ? data : null;
            }
        });
    }
    function refreshKodeBarangSelect2() {
        if ($.fn.select2 && $('#kodeBarang').data('select2')) {
            $('#kodeBarang').trigger('change.select2');
        }
    }
    function selectedMasterRow() {
        var kode = $('#kodeBarang').val(), expired = $('#expiredDate').val();
        var rows = masterByCode[kode] || [];
        for (var i = 0; i < rows.length; i++) {
            if (String(rows[i].expired_date || '') === String(expired || '')) { return rows[i]; }
        }
        return null;
    }
    function recalcQty() {
        var dimensi = toInt($('#dimensi').val());
        var qty = (toInt($('#qtyBox').val()) * dimensi) + toInt($('#qtyPcs').val());
        $('#qty').val(qty);
    }
    function setMasterFields() {
        var row = selectedMasterRow();
        if (!row) {
            var rows = masterByCode[$('#kodeBarang').val()] || [];
            row = rows.length ? rows[0] : null;
        }
        if (!row) {
            $('#namaBarang').val('');
            $('#noLot').val('');
            $('#dimensi').val(0);
            recalcQty();
            return null;
        }
        $('#namaBarang').val(row.nama_barang || '');
        $('#noLot').val(row.no_lot || '-');
        $('#dimensi').val(toInt(row.dimensi));
        recalcQty();
        return row;
    }
    function populateExpiredOptions(kode, selected) {
        var rows = masterByCode[kode] || [];
        var html = rows.length ? '<option value="">Pilih expired date</option>' : '<option value="">Tidak ada expired date</option>';
        rows.forEach(function (row) {
            var expired = String(row.expired_date || '');
            var label = expired + ' | Qty: ' + toInt(row.qty) + ' | Dimensi: ' + toInt(row.dimensi);
            html += '<option value="' + esc(expired) + '">' + esc(label) + '</option>';
        });
        $('#expiredDate').html(html).prop('disabled', !rows.length);
        if (selected) {
            if (!rows.some(function (row) { return String(row.expired_date || '') === String(selected); })) {
                $('#expiredDate').append('<option value="' + esc(selected) + '">' + esc(selected) + ' | data lama</option>');
            }
            $('#expiredDate').val(selected);
        }
        setMasterFields();
    }
    function resetForm() {
        $('#formBarangPending')[0].reset();
        $('#pendingId').val('');
        $('#noLot').val('');
        $('#qty,#qtyPcs,#qtyBox,#dimensi').val(0);
        $('#kodeBarang').val('');
        refreshKodeBarangSelect2();
        populateExpiredOptions('', '');
        $('#btnSimpanPending').html('<i class="fas fa-save"></i> Simpan');
    }
    function updateSummary(summary) {
        summary = summary || {};
        $('#statTotalItem').text(summary.total_item || 0);
        $('#statTotalQty').text(summary.total_qty || 0);
        $('#statTotalPcs').text(summary.total_qty_pcs || 0);
        $('#statTotalBox').text(summary.total_qty_box || 0);
    }
    function renderPagination(pagination) {
        pagination = pagination || {};
        var page = toInt(pagination.page) || 1;
        var perPage = toInt(pagination.per_page) || 10;
        var totalRows = toInt(pagination.total_rows);
        var totalPages = Math.max(1, toInt(pagination.total_pages) || 1);
        var fromRow = totalRows > 0 ? ((page - 1) * perPage) + 1 : 0;
        var toRow = Math.min(totalRows, page * perPage);
        var startPage = Math.max(1, page - 2);
        var endPage = Math.min(totalPages, page + 2);
        var html = '';

        $('#pendingPageInfo').text('Menampilkan ' + fromRow + '-' + toRow + ' dari ' + totalRows + ' data');
        html += '<li class="page-item ' + (page <= 1 ? 'disabled' : '') + '"><button type="button" class="page-link btn-page-pending" data-page="' + (page - 1) + '"><i class="fas fa-chevron-left"></i></button></li>';
        for (var i = startPage; i <= endPage; i++) {
            html += '<li class="page-item ' + (i === page ? 'active' : '') + '"><button type="button" class="page-link btn-page-pending" data-page="' + i + '">' + i + '</button></li>';
        }
        html += '<li class="page-item ' + (page >= totalPages ? 'disabled' : '') + '"><button type="button" class="page-link btn-page-pending" data-page="' + (page + 1) + '"><i class="fas fa-chevron-right"></i></button></li>';
        $('#pendingPagination').html(html);
    }
    function editPendingRow(id) {
        var row = pendingRows[id];
        if (!row) { notify('error', 'Data pending tidak ditemukan.'); return; }
        $('#pendingId').val(row.id);
        $('#kdDo').val(row.kd_do || '');
        if (!masterByCode[row.kode_barang] && $('#kodeBarang option[value="' + String(row.kode_barang).replace(/"/g, '\\"') + '"]').length === 0) {
            $('#kodeBarang').append('<option value="' + esc(row.kode_barang) + '" data-kode="' + esc(row.kode_barang) + '" data-nama="' + esc(row.nama_barang || '') + '">' + esc(row.kode_barang + ' - data lama') + '</option>');
        }
        $('#kodeBarang').val(row.kode_barang);
        refreshKodeBarangSelect2();
        $('#qtyPcs').val(row.qty_pcs);
        $('#qtyBox').val(row.qty_box);
        populateExpiredOptions(row.kode_barang, row.expired_date);
        if (!selectedMasterRow()) {
            $('#namaBarang').val(row.nama_barang);
            $('#noLot').val(row.no_lot);
            $('#qty').val(row.qty);
        }
        $('#btnSimpanPending').html('<i class="fas fa-save"></i> Update');
        window.scrollTo({top: 0, behavior: 'smooth'});
    }
    function loadPendingDetailForEdit(id) {
        $.ajax({
            url: '<?= base_url('admin/stockopname/barang-pending/detail') ?>',
            type: 'POST',
            dataType: 'json',
            data: {id: id}
        }).done(function (res) {
            if (!res.status || !res.data) { notify('error', res.message || 'Data pending tidak ditemukan.'); return; }
            pendingRows[res.data.id] = res.data;
            editPendingRow(res.data.id);
        }).fail(function () {
            notify('error', 'Server gagal memuat detail data pending');
        });
    }
    function loadRows(resetSearch, page) {
        if (resetSearch) { $('#searchPending').val(''); }
        pendingPage = page || pendingPage || 1;
        $('#barangPendingRows').html('<tr><td colspan="8" class="text-center text-muted p-4">Memuat data...</td></tr>');
        $.getJSON('<?= base_url('admin/stockopname/barang-pending/list') ?>', {keyword: $.trim($('#searchPending').val()), page: pendingPage}, function (res) {
            if (!res.status) { notify('error', res.message || 'Gagal memuat data'); return; }
            updateSummary(res.data.summary);
            renderPagination(res.data.pagination);
            var rows = res.data.rows || [];
            pendingRows = {};
            if (!rows.length) { $('#barangPendingRows').html('<tr><td colspan="8" class="text-center text-muted p-4">Belum ada data pending.</td></tr>'); return; }
            $('#barangPendingRows').html(rows.map(function (row) {
                pendingRows[row.id] = row;
                var badge = parseInt(row.master_id || 0, 10) > 0 ? '<span class="badge badge-success">Masuk master</span>' : '<span class="badge badge-warning">Belum ada master</span>';
                return '<tr>' +
                    '<td class="pending-code">' + esc(row.kode_barang) + '</td>' +
                    '<td>' + esc(row.nama_barang) + '</td>' +
                    '<td>' + esc(formatDate(row.expired_date)) + '</td>' +
                    '<td class="text-right">' + esc(row.qty) + '</td>' +
                    '<td class="text-right">' + esc(row.qty_box) + '</td>' +
                    '<td class="text-right">' + esc(row.qty_pcs) + '</td>' +
                    '<td>' + badge + '</td>' +
                    '<td class="text-center"><button type="button" class="btn btn-outline-primary btn-sm btn-edit-pending" data-id="' + esc(row.id) + '"><i class="fas fa-edit"></i></button> <button type="button" class="btn btn-outline-danger btn-sm btn-delete-pending" data-id="' + esc(row.id) + '"><i class="fas fa-trash"></i></button></td>' +
                    '</tr>';
            }).join(''));
            if (requestedEditId !== '' && pendingRows[requestedEditId]) {
                editPendingRow(requestedEditId);
                requestedEditId = '';
            } else if (requestedEditId !== '') {
                loadPendingDetailForEdit(requestedEditId);
                requestedEditId = '';
            }
        }).fail(function () {
            $('#barangPendingRows').html('<tr><td colspan="8" class="text-center text-danger p-4">Gagal memuat data pending.</td></tr>');
        });
    }
    $('#btnResetPending').on('click', resetForm);
    $('#btnRefreshPending').on('click', function () { loadRows(true, 1); });
    $('#btnCariPending').on('click', function () { loadRows(false, 1); });
    $('#searchPending').on('keydown', function (event) { if (event.key === 'Enter') { event.preventDefault(); loadRows(false, 1); } });
    $('#pendingPagination').on('click', '.btn-page-pending', function () {
        var page = toInt($(this).data('page'));
        if (!page || $(this).closest('.page-item').hasClass('disabled') || $(this).closest('.page-item').hasClass('active')) { return; }
        loadRows(false, page);
    });
    $('#barangPendingRows').on('click', '.btn-edit-pending', function () {
        editPendingRow($(this).data('id'));
    });
    $('#barangPendingRows').on('click', '.btn-delete-pending', function () {
        var id = $(this).data('id');
        var runDelete = function () {
            $.ajax({url:'<?= base_url('admin/stockopname/barang-pending/delete') ?>',type:'POST',dataType:'json',data:{id:id}})
                .done(function (res) { notify(res.status ? 'success' : 'error', res.message || 'Data diproses'); if (res.status) { resetForm(); loadRows(false, pendingPage); } })
                .fail(function () { notify('error', 'Server gagal menghapus data pending'); });
        };
        if (typeof Swal !== 'undefined') {
            Swal.fire({icon:'warning',title:'Hapus barang pending?',showCancelButton:true,confirmButtonText:'Hapus',cancelButtonText:'Batal'}).then(function (result) { if (result.isConfirmed) { runDelete(); } });
        } else if (confirm('Hapus barang pending?')) {
            runDelete();
        }
    });
    $('#formBarangPending').on('submit', function (event) {
        event.preventDefault();
        var $button = $('#btnSimpanPending'), original = $button.html();
        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan');
        $.ajax({url:'<?= base_url('admin/stockopname/barang-pending/save') ?>',type:'POST',dataType:'json',data:$(this).serialize()})
            .done(function (res) {
                if (!res.status) { notify('error', res.message || 'Gagal menyimpan data'); return; }
                notify('success', res.message || 'Barang pending berhasil disimpan');
                resetForm();
                loadRows(false, pendingPage);
            })
            .fail(function (xhr) { notify('error', (xhr.responseJSON && xhr.responseJSON.message) || 'Server gagal menyimpan data'); })
            .always(function () {
                $button.prop('disabled', false).html($('#pendingId').val() ? original : '<i class="fas fa-save"></i> Simpan');
            });
    });
    $('#kodeBarang').on('change', function () { populateExpiredOptions($(this).val(), ''); });
    $('#expiredDate').on('change', setMasterFields);
    $('#qtyPcs,#qtyBox').on('input', recalcQty);
    initKodeBarangSelect2();
    populateExpiredOptions('', '');
    loadRows(false, 1);
});
</script>
