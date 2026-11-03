<?php
defined('BASEPATH') or exit('No direct script access allowed');
$nextCode = $next_kode_barang_system ?? 'KIUBR00001';
?>
<body class="hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view('partial/main/navbar') ?>
    <?php $this->load->view('partial/main/sidebar') ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid d-flex align-items-center justify-content-between">
                <h1 class="m-0"><?= html_escape($page_heading ?? 'Master Barang') ?></h1>
                <a href="<?= base_url('admin/stockopname/master_opname') ?>" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left"></i> Master Opname</a>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <style>
                    .catalog-panel{background:#fff;border:1px solid #e2e8f0;border-radius:8px;box-shadow:0 8px 22px rgba(16,24,40,.06)}
                    .catalog-panel .card-header{background:#fff;border-bottom:1px solid #e2e8f0;font-weight:700}.catalog-code{font-family:monospace;font-size:15px;font-weight:700}.catalog-table-wrap{max-height:570px;overflow:auto}
                </style>
                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <div class="card catalog-panel h-100">
                            <div class="card-header"><i class="fas fa-plus-circle mr-1"></i> Tambah Barang</div>
                            <form id="formMasterBarangCatalog" class="card-body">
                                <div class="form-group">
                                    <label>Kode barang</label>
                                    <input name="kd_barang" class="form-control" maxlength="25" required autofocus placeholder="Contoh: QPROD01">
                                </div>
                                <div class="form-group">
                                    <label>Kode barang system</label>
                                    <input class="form-control catalog-code" id="kodeBarangSystem" readonly value="<?= html_escape($nextCode) ?>">
                                    <small class="form-text text-muted">Dibuat otomatis saat disimpan berdasarkan kode terakhir.</small>
                                </div>
                                <div class="form-group">
                                    <label>Nama barang</label>
                                    <textarea name="nama_barang" class="form-control" rows="3" required></textarea>
                                </div>
                                <div class="form-row">
                                    <div class="col-4 form-group"><label>Panjang</label><input name="p" type="number" min="1" step="1" class="form-control" required></div>
                                    <div class="col-4 form-group"><label>Lebar</label><input name="l" type="number" min="1" step="1" class="form-control" required></div>
                                    <div class="col-4 form-group"><label>Tinggi</label><input name="t" type="number" min="1" step="1" class="form-control" required></div>
                                </div>
                                <div class="form-group">
                                    <label>Dimensi</label>
                                    <input class="form-control catalog-code" id="inputDimensiBarang" readonly value="0">
                                </div>
                                <button class="btn btn-primary btn-block" id="btnSimpanMasterBarang" type="submit"><i class="fas fa-save"></i> Simpan Master Barang</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-8 mb-3">
                        <div class="card catalog-panel h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>Data Master Barang Terbaru</span>
                                <div class="input-group input-group-sm" style="max-width:340px">
                                    <input type="search" class="form-control" id="searchKodeBarang" placeholder="Cari kode barang...">
                                    <div class="input-group-append"><button class="btn btn-outline-primary" type="button" id="btnCariKodeBarang"><i class="fas fa-search"></i> Cari</button><button class="btn btn-outline-secondary" type="button" id="btnRefreshCatalog" title="Reset pencarian"><i class="fas fa-sync-alt"></i></button></div>
                                </div>
                            </div>
                            <div class="catalog-table-wrap">
                                <table class="table table-sm table-hover mb-0">
                                    <thead><tr><th>Kode</th><th>Kode System</th><th>Nama Barang</th><th class="text-right">P</th><th class="text-right">L</th><th class="text-right">T</th><th class="text-right">Dimensi</th><th class="text-center">Aksi</th></tr></thead>
                                    <tbody id="masterBarangCatalogRows"><tr><td colspan="8" class="text-center text-muted p-4">Memuat data...</td></tr></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="modal fade" id="modalEditDimensiBarang" tabindex="-1" role="dialog" aria-labelledby="modalEditDimensiBarangLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form class="modal-content" id="formEditDimensiBarang">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditDimensiBarangLabel">Edit Master Barang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editBarangId">
                    <div class="form-group">
                        <label>Kode barang</label>
                        <input name="kd_barang" class="form-control catalog-code" id="editKodeBarang" maxlength="25" required>
                    </div>
                    <div class="form-group">
                        <label>Nama barang</label>
                        <textarea name="nama_barang" class="form-control" id="editNamaBarang" rows="2" required></textarea>
                    </div>
                    <div class="form-row">
                        <div class="col-4 form-group"><label>Panjang</label><input name="p" id="editPanjang" type="number" min="1" step="1" class="form-control" required></div>
                        <div class="col-4 form-group"><label>Lebar</label><input name="l" id="editLebar" type="number" min="1" step="1" class="form-control" required></div>
                        <div class="col-4 form-group"><label>Tinggi</label><input name="t" id="editTinggi" type="number" min="1" step="1" class="form-control" required></div>
                    </div>
                    <div class="form-group mb-0">
                        <label>Dimensi</label>
                        <input class="form-control catalog-code" id="editDimensiBarang" readonly value="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnUpdateDimensiBarang"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <footer class="main-footer"><strong>Copyright &copy; 2022 <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.</strong></footer>
</div>
<script>
window.addEventListener('load', function () {
    var catalogRows = {};
    function esc(value) { return String(value === null || value === undefined ? '' : value).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;'); }
    function notify(icon, message) { if (typeof Swal !== 'undefined') { Swal.fire({toast:true,position:'top-end',icon:icon,title:message,showConfirmButton:false,timer:2500}); } else { alert(message); } }
    function updateDimensi() {
        var p = parseInt($('#formMasterBarangCatalog [name="p"]').val(), 10) || 0;
        var l = parseInt($('#formMasterBarangCatalog [name="l"]').val(), 10) || 0;
        var t = parseInt($('#formMasterBarangCatalog [name="t"]').val(), 10) || 0;
        $('#inputDimensiBarang').val(p * l * t);
    }
    function updateEditDimensi() {
        var p = parseInt($('#editPanjang').val(), 10) || 0;
        var l = parseInt($('#editLebar').val(), 10) || 0;
        var t = parseInt($('#editTinggi').val(), 10) || 0;
        $('#editDimensiBarang').val(p * l * t);
    }
    function loadRows(resetSearch) {
        if (resetSearch) { $('#searchKodeBarang').val(''); }
        var kodeBarang = $.trim($('#searchKodeBarang').val());
        $('#masterBarangCatalogRows').html('<tr><td colspan="8" class="text-center text-muted p-4">Memuat data...</td></tr>');
        $.getJSON('<?= base_url('admin/stockopname/master_barang/list') ?>', {kd_barang: kodeBarang}, function (res) {
            if (!res.status) { notify('error', res.message || 'Gagal memuat data'); return; }
            var rows = res.data || [];
            catalogRows = {};
            if (!rows.length) { $('#masterBarangCatalogRows').html('<tr><td colspan="8" class="text-center text-muted p-4">Belum ada data.</td></tr>'); return; }
            $('#masterBarangCatalogRows').html(rows.map(function (row) {
                catalogRows[row.id] = row;
                return '<tr><td>' + esc(row.kd_barang) + '</td><td class="catalog-code">' + esc(row.kode_barang_system) + '</td><td>' + esc(row.nama_barang) + '</td><td class="text-right">' + esc(row.p) + '</td><td class="text-right">' + esc(row.l) + '</td><td class="text-right">' + esc(row.t) + '</td><td class="text-right catalog-code">' + esc(row.dimensi) + '</td><td class="text-center"><button type="button" class="btn btn-outline-primary btn-sm btn-edit-dimensi" data-id="' + esc(row.id) + '"><i class="fas fa-edit"></i> Edit</button></td></tr>';
            }).join(''));
        }).fail(function () { $('#masterBarangCatalogRows').html('<tr><td colspan="8" class="text-center text-danger p-4">Gagal memuat data.</td></tr>'); });
    }
    $('#formMasterBarangCatalog [name="p"], #formMasterBarangCatalog [name="l"], #formMasterBarangCatalog [name="t"]').on('input', updateDimensi);
    $('#editPanjang, #editLebar, #editTinggi').on('input', updateEditDimensi);
    $('#btnRefreshCatalog').on('click', function () { loadRows(true); });
    $('#btnCariKodeBarang').on('click', function () { loadRows(false); });
    $('#searchKodeBarang').on('keydown', function (event) { if (event.key === 'Enter') { event.preventDefault(); loadRows(false); } });
    $('#masterBarangCatalogRows').on('click', '.btn-edit-dimensi', function () {
        var row = catalogRows[$(this).data('id')];
        if (!row) { notify('error', 'Data barang tidak ditemukan.'); return; }
        $('#editBarangId').val(row.id);
        $('#editKodeBarang').val(row.kd_barang);
        $('#editNamaBarang').val(row.nama_barang);
        $('#editPanjang').val(row.p);
        $('#editLebar').val(row.l);
        $('#editTinggi').val(row.t);
        updateEditDimensi();
        $('#modalEditDimensiBarang').modal('show');
    });
    $('#formMasterBarangCatalog').on('submit', function (event) {
        event.preventDefault();
        var $button = $('#btnSimpanMasterBarang'), original = $button.html();
        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan');
        $.ajax({url:'<?= base_url('admin/stockopname/master_barang/create') ?>',type:'POST',dataType:'json',data:$(this).serialize()})
            .done(function (res) {
                if (!res.status) { notify('error', res.message || 'Gagal menyimpan data'); return; }
                $('#formMasterBarangCatalog')[0].reset();
                $('#kodeBarangSystem').val(res.data.next_kode_barang_system || '');
                updateDimensi();
                notify('success', res.message || 'Master barang berhasil disimpan');
                loadRows();
            })
            .fail(function (xhr) { notify('error', (xhr.responseJSON && xhr.responseJSON.message) || 'Server gagal menyimpan data'); })
            .always(function () { $button.prop('disabled', false).html(original); });
    });
    $('#formEditDimensiBarang').on('submit', function (event) {
        event.preventDefault();
        var $button = $('#btnUpdateDimensiBarang'), original = $button.html();
        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan');
        $.ajax({url:'<?= base_url('admin/stockopname/master_barang/update') ?>',type:'POST',dataType:'json',data:$(this).serialize()})
            .done(function (res) {
                if (!res.status) { notify('error', res.message || 'Gagal memperbarui data'); return; }
                $('#modalEditDimensiBarang').modal('hide');
                notify('success', res.message || 'Dimensi barang berhasil diperbarui');
                loadRows();
            })
            .fail(function (xhr) { notify('error', (xhr.responseJSON && xhr.responseJSON.message) || 'Server gagal memperbarui data'); })
            .always(function () { $button.prop('disabled', false).html(original); });
    });
    updateDimensi();
    loadRows();
});
</script>
