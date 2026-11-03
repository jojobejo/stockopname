<!-- views/content/sales/so_form.php -->
<?php
    $is_edit   = !empty($so);
    $id_so_val = $is_edit ? ($so['no_so'] ?? '') : ($no_so ?? '');
    $action    = $is_edit
        ? base_url('sales_order/update/' . $so['id_so'])
        : base_url('sales_order/store');

    $batas_ton = isset($batas_tonase)   ? $batas_tonase   : 6;
    $batas_kub = isset($batas_kubikasi) ? $batas_kubikasi : 9;

    function escAttr($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
    function escJs($v)   {
        return str_replace(['\\','\'',"\r","\n","\t"], ['\\\\',"\\'", '','',''], (string)$v);
    }
?>
<body class="hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">

    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="<?= base_url('assets/images/Karisma.png') ?>"
             alt="Logo" height="150" width="300">
    </div>

    <?php $this->load->view('partial/main/navbar') ?>
    <?php $this->load->view('partial/main/sidebar') ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">
                            <i class="fas fa-<?= $is_edit ? 'pencil-alt' : 'plus-circle' ?> mr-2"></i>
                            <?= $is_edit ? 'Edit Sales Order' : 'Buat Sales Order Baru' ?>
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('sales_order') ?>">Sales Order</a></li>
                            <li class="breadcrumb-item active"><?= $is_edit ? 'Edit' : 'Buat' ?></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                <div class="mb-3">
                    <a href="<?= base_url('sales_order') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= $this->session->flashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('warning')): ?>
                    <div class="alert alert-warning alert-dismissible fade show">
                        <?= $this->session->flashdata('warning') ?>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>

                <form action="<?= $action ?>" method="post" id="form-so">

                    <div class="row">
                        <!-- ============================================================
                             INFORMASI SO
                        ============================================================ -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-primary text-white py-2">
                                    <h3 class="card-title">
                                        <i class="fas fa-info-circle mr-1"></i> Informasi SO
                                    </h3>
                                </div>
                                <div class="card-body">

                                    <div class="form-group row mb-2">
                                        <label class="col-sm-4 col-form-label">No SO <span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="no_so"
                                                value="<?= escAttr($id_so_val) ?>"
                                                placeholder="Nomor SO" required>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-2">
                                        <label class="col-sm-4 col-form-label">No Faktur</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="no_faktur"
                                                value="<?= $is_edit ? escAttr($so['no_faktur'] ?? '') : escAttr($no_faktur ?? '') ?>"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-2">
                                        <label class="col-sm-4 col-form-label">Tanggal <span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control" name="tanggal" required
                                                value="<?= $is_edit ? escAttr($so['tanggal_transaksi']) : date('Y-m-d') ?>">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-2">
                                        <label class="col-sm-4 col-form-label">Customer <span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input type="text" id="customer_display" class="form-control"
                                                    placeholder="-- Pilih Customer --"
                                                    value="<?= $is_edit ? escAttr($so['customer_name']) : '' ?>"
                                                    readonly style="background:#fff;cursor:pointer">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-primary"
                                                            id="btn-pilih-customer">
                                                        <i class="fas fa-search"></i> Pilih
                                                    </button>
                                                </div>
                                            </div>
                                            <input type="hidden" name="customer_id" id="customer_id"
                                                value="<?= $is_edit ? escAttr($so['kd_customer'] ?? '') : '' ?>">
                                            <input type="hidden" name="customer_name" id="customer_name"
                                                value="<?= $is_edit ? escAttr($so['customer_name']) : '' ?>">
                                            <div id="customer_validation" class="text-danger small mt-1"
                                                 style="display:none">
                                                <i class="fas fa-exclamation-circle"></i> Pilih customer terlebih dahulu.
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Gudang — diisi otomatis dari barang yang dipilih -->
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-4 col-form-label">Gudang</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="gudang_display"
                                                value="<?= $is_edit ? escAttr($so['gudang_id'] ?? '') : escAttr($gudang_id ?? '') ?>"
                                                readonly placeholder="-- otomatis dari barang --">
                                            <input type="hidden" name="gudang_id" id="gudang_id_input"
                                                value="<?= $is_edit ? escAttr($so['gudang_id'] ?? '') : escAttr($gudang_id ?? '') ?>">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-2">
                                        <label class="col-sm-4 col-form-label">Catatan</label>
                                        <div class="col-sm-8">
                                            <textarea name="catatan" class="form-control" rows="2"
                                                ><?= $is_edit ? htmlspecialchars($so['catatan'] ?? '') : '' ?></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- ============================================================
                             TONASE & KUBIKASI
                        ============================================================ -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-info text-white py-2">
                                    <h3 class="card-title">
                                        <i class="fas fa-weight mr-1"></i> Tonase &amp; Kubikasi
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <div class="info-box mb-0">
                                                <span class="info-box-icon bg-success" style="min-height:50px">
                                                    <i class="fas fa-truck"></i>
                                                </span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Batas Tonase</span>
                                                    <span class="info-box-number"><?= $batas_ton ?> ton</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-box mb-0">
                                                <span class="info-box-icon bg-info" style="min-height:50px">
                                                    <i class="fas fa-cube"></i>
                                                </span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Batas Kubikasi</span>
                                                    <span class="info-box-number"><?= $batas_kub ?> m³</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small><b>Tonase:</b> <span id="lbl-tonase">0,000</span> ton</small>
                                            <small class="text-muted">Maks <?= $batas_ton ?> ton</small>
                                        </div>
                                        <div class="progress" style="height:12px">
                                            <div class="progress-bar bg-success" id="tonase-bar"
                                                 role="progressbar" style="width:0%"></div>
                                        </div>
                                        <small id="lbl-tonase-warn" class="text-danger d-none font-weight-bold">
                                            <i class="fas fa-exclamation-triangle"></i> Melebihi batas tonase!
                                        </small>
                                    </div>

                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small><b>Kubikasi:</b> <span id="lbl-kubikasi">0,00000</span> m³</small>
                                            <small class="text-muted">Maks <?= $batas_kub ?> m³</small>
                                        </div>
                                        <div class="progress" style="height:12px">
                                            <div class="progress-bar bg-info" id="kubikasi-bar"
                                                 role="progressbar" style="width:0%"></div>
                                        </div>
                                        <small id="lbl-kubikasi-warn" class="text-danger d-none font-weight-bold">
                                            <i class="fas fa-exclamation-triangle"></i> Melebihi batas kubikasi!
                                        </small>
                                    </div>

                                    <div class="mt-3 p-2 bg-light rounded border small">
                                        Total pcs: <b id="lbl-total-kecil">0</b>
                                        &nbsp;|&nbsp; Box: <b id="lbl-total-box">0</b>
                                        &nbsp;+&nbsp; Eceran: <b id="lbl-total-ecer">0</b> pcs
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================================
                        TABEL ITEM BARANG
                        Kolom: Barang | Expired/Lot | Qty Box | +Eceran | =Total Pcs
                                Satuan | Harga/Pcs + Approver | Disc% | Stl Disc | Pajak | Subtotal | Aksi
                        Total kolom = 12 → colspan footer = 10
                    ============================================================ -->
                    <div class="card">
                        <div class="card-header bg-success text-white py-2 d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">
                                <i class="fas fa-boxes mr-1"></i> Item Barang
                            </h3>
                            <button type="button" class="btn btn-light btn-sm" id="btn-add-row">
                                <i class="fas fa-plus"></i> Tambah Barang
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm mb-0" id="tbl-item">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th style="min-width:220px">Barang</th>
                                            <th style="min-width:155px">Expired / Lot</th>
                                            <th style="width:85px"  class="text-center">Qty Box</th>
                                            <th style="width:85px"  class="text-center">+Eceran</th>
                                            <th style="width:80px"  class="text-center">=Total Pcs</th>
                                            <th style="width:60px">Satuan</th>
                                            <th style="width:160px">Harga/Pcs</th>
                                            <th style="width:65px"  class="text-center">Disc%</th>
                                            <th style="width:110px" class="text-right">Stl&nbsp;Disc</th>
                                            <th style="width:85px">Pajak</th>
                                            <th style="width:115px" class="text-right">Subtotal</th>
                                            <th style="width:36px"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="item-body">
                                        <!-- baris diisi JS -->
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light font-weight-bold">
                                            <td colspan="10" class="text-right">GRAND TOTAL</td>
                                            <td class="text-right" id="total-grand">0</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mb-4">
                        <a href="<?= base_url('sales_order') ?>" class="btn btn-secondary mr-2">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary" id="btn-submit">
                            <i class="fas fa-save"></i> Simpan SO
                        </button>
                    </div>

                </form>
            </div>
        </section>
    </div><!-- /.content-wrapper -->

    <footer class="main-footer">
        <strong>Copyright &copy; 2022
            <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.
        </strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block"><b>Version</b> 1.0</div>
    </footer>
    <aside class="control-sidebar control-sidebar-dark"></aside>
</div><!-- /.wrapper -->

<!-- ==================================================================
     MODAL: Pilih Barang
================================================================== -->
<div class="modal fade" id="modal-stock" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title"><i class="fas fa-search mr-1"></i> Pilih Barang</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" id="stock-search" class="form-control mb-2"
                    placeholder="Cari kode atau nama barang...">
                <div style="max-height:420px;overflow-y:auto">
                    <table class="table table-bordered table-sm table-hover mb-0">
                        <thead class="thead-dark sticky-top">
                            <tr>
                                <th>Nama Barang</th>
                                <th>Exp Date</th>
                                <th>No Lot</th>
                                <th class="text-right">Avail Box</th>
                                <th class="text-right">Sisa Ecer</th>
                                <th class="text-right">Total Pcs</th>
                                <th class="text-center">Isi/Box</th>
                                <th>Satuan</th>
                                <th class="text-right">Berat/pcs</th>
                                <th class="text-right">Kubik/pcs</th>
                                <th>Gudang</th>
                                <th class="text-center">Pilih</th>
                            </tr>
                        </thead>
                        <tbody id="stock-body">
                            <tr>
                                <td colspan="12" class="text-center text-muted py-3">
                                    <i class="fas fa-spinner fa-spin mr-1"></i> Memuat...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==================================================================
     MODAL: Pilih Customer
================================================================== -->
<div class="modal fade" id="modal-customer" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title"><i class="fas fa-users mr-1"></i> Pilih Customer</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" id="customer-search" class="form-control mb-2"
                    placeholder="Cari nama customer...">
                <div style="max-height:450px;overflow-y:auto">
                    <table class="table table-bordered table-sm table-hover mb-0">
                        <thead class="thead-dark sticky-top">
                            <tr>
                                <th>Nama Customer</th>
                                <th>Nama Kios</th>
                                <th>Alamat</th>
                                <th style="width:60px" class="text-center">Pilih</th>
                            </tr>
                        </thead>
                        <tbody id="customer-body"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
/* ====================================================================
   SO FORM — Karisma ERP
   Kolom tabel (12 total):
     0  Barang
     1  Expired/Lot
     2  Qty Box
     3  +Eceran
     4  =Total Pcs
     5  Satuan
     6  Harga/Pcs
     7  Disc%
     8  Stl Disc
     9  Pajak (dropdown)
     10 Subtotal
     11 Aksi (hapus)

   Rumus:
     qtyKecil  = (qtyBox × isi) + qtySat
     subBefore = hrg × qtyKecil
     subDisc   = subBefore × (1 − disc/100)
     subtotal  = subDisc  × (1 + pajak/100)
   ==================================================================== */

var BASE_URL       = '<?= base_url() ?>';
var BATAS_TONASE   = <?= (float)$batas_ton ?>;
var BATAS_KUBIKASI = <?= (float)$batas_kub ?>;

/* ---------- Data edit ---------- */
<?php
$edit_details_safe = [];
if ($is_edit && !empty($details)) {
    foreach ($details as $d) {
        $safe = [];
        foreach ($d as $k => $v) {
            $safe[$k] = is_string($v) ? mb_convert_encoding($v,'UTF-8','UTF-8') : $v;
        }
        $edit_details_safe[] = $safe;
    }
}
?>
var EDIT_DETAILS = <?= json_encode(array_values($edit_details_safe),
    JSON_HEX_QUOT|JSON_HEX_APOS|JSON_UNESCAPED_UNICODE) ?>;

/* ---------- Data customer ---------- */
<?php
$customers_safe = [];
foreach ($customers as $c) {
    $cs = [];
    foreach ($c as $k => $v) {
        $cs[$k] = is_string($v) ? mb_convert_encoding($v,'UTF-8','UTF-8') : $v;
    }
    $customers_safe[] = $cs;
}
?>
var CUSTOMERS = <?= json_encode(array_values($customers_safe),
    JSON_HEX_QUOT|JSON_HEX_APOS|JSON_UNESCAPED_UNICODE) ?>;

/* ---------- Daftar pajak dari tb_set_tax ---------- */
<?php
$tax_safe = [];
foreach (($tax_list ?? []) as $t) {
    $tax_safe[] = ['id' => (int)$t['id_tax'], 'nm' => (float)$t['nm_tax']];
}
// Urutkan ascending agar tampil rapi
usort($tax_safe, function($a,$b){ return $a['nm'] <=> $b['nm']; });
?>
var TAX_LIST = <?= json_encode($tax_safe, JSON_UNESCAPED_UNICODE) ?>;

/* ---------- State ---------- */
var currentRowIdx = null;
var stockCache    = [];
var rowIdx        = 0;

<?php
$approver_safe = [];
foreach (($approver_list ?? []) as $a) {
    $approver_safe[] = [
        'nm'       => mb_convert_encoding($a['nm_karyawan'] ?? '', 'UTF-8', 'UTF-8'),
        'jobdesk'  => mb_convert_encoding($a['jobdesk']     ?? '', 'UTF-8', 'UTF-8'),
    ];
}
?>
var APPROVER_LIST = <?= json_encode($approver_safe, JSON_HEX_QUOT|JSON_HEX_APOS|JSON_UNESCAPED_UNICODE) ?>;
/* ================================================================
   UTILS
================================================================ */
function fmtNum(n, dec) {
    if (dec === undefined) dec = 2;
    return (parseFloat(n)||0).toLocaleString('id-ID',
        {minimumFractionDigits:dec, maximumFractionDigits:dec});
}
function formatTgl(ymd) {
    if (!ymd) return '-';
    var p = String(ymd).split('-');
    return p.length === 3 ? p[2]+'/'+p[1]+'/'+p[0] : ymd;
}
function isExpiringSoon(ymd) {
    if (!ymd) return false;
    return (new Date(ymd) - new Date()) / 86400000 <= 30;
}
function esc(v) {
    return String(v||'')
        .replace(/&/g,'&amp;').replace(/"/g,'&quot;')
        .replace(/'/g,'&#39;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}
function getIsi(idx) {
    var el = document.getElementById('isi_'+idx);
    var v  = el ? parseInt(el.value) : 1;
    return (v > 0) ? v : 1;
}

/* ================================================================
   BUAT BARIS TABEL ITEM
================================================================ */
function buatBaris(idx, d) {
    d = d || {};

    /* --- ambil nilai dari data --- */
    var kd     = d.kd_barang    || '';
    var nm     = d.nama_barang  || '';
    var exp    = d.expired_date || '';
    var lot    = d.no_lot       || '';
    var sat    = d.satuan       || '';
    var hrg    = parseFloat(d.hrg_satuan  || 0);
    var pk     = parseFloat(d.hrg_pokok   || 0);
    var disc   = parseFloat(d.disc        || 0);   // ← gunakan 'disc', bukan 'disc_pct'
    var pajak  = parseFloat(d.pajak       || 0);
    var akun   = d.kode_akun    || '';
    var beratG = parseFloat(d.berat_gram  || 0);
    var kubikM = parseFloat(d.kubikasi_m3 || 0);
    var isi    = parseInt(d.isi_per_box   || 1); if (isi < 1) isi = 1;
    var gudang = d.gudang_id || d.gudang || '';

    /* --- qty --- */
    var qtyKecilDB = parseFloat(d.qty        || 0);
    var qtyBox     = parseFloat(d.qty_box    || 0);
    var qtySat     = parseFloat(d.qty_satuan || 0);
    if (!d.qty_box && qtyKecilDB > 0) {
        qtyBox = Math.floor(qtyKecilDB / isi);
        qtySat = qtyKecilDB % isi;
    }

    /* --- stok --- */
    var av     = parseFloat(d.available_stock || 0);
    var avBox  = Math.floor(av / isi);
    var avEcer = Math.floor(av % isi);

    /* --- kalkulasi --- */
    var qtyKecil  = (qtyBox * isi) + qtySat;
    var subBefore = hrg * qtyKecil;
    var subDisc   = subBefore * (1 - disc / 100);
    var sub       = subDisc   * (1 + pajak / 100);

    /* --- expired warning --- */
    var expSoon = exp && isExpiringSoon(exp);

    /* --- option expired (mode edit: satu opsi pre-selected) --- */
    var expOption = exp
        ? '<option value="'+esc(exp)+'"'
          +' data-lot="'+esc(lot)+'"'
          +' data-av="'+av+'"'
          +' data-ton="'+beratG+'"'
          +' data-kub="'+kubikM+'"'
          +' data-isi="'+isi+'"'
          +' data-gudang="'+esc(String(gudang))+'"'
          +' selected>'
          + esc(formatTgl(exp))
          + (lot ? ' | Lot: '+esc(lot) : '')
          + (expSoon ? ' ⚠' : '')
          +'</option>'
        : '';

    /* ---- bangun HTML baris ---- */
    var h = '<tr id="row-'+idx+'" data-idx="'+idx+'">';

    /* 0. Barang */
    h += '<td>';
    h += '<input type="hidden" name="produk_id[]"   value="'+esc(kd)+'">';
    h += '<input type="hidden" name="kd_barang[]"   id="kd_'+idx+'"  value="'+esc(kd)+'">';
    h += '<input type="hidden" name="nama_barang[]" id="nm_'+idx+'"  value="'+esc(nm)+'">';
    h += '<input type="hidden" name="satuan[]"      id="sat_'+idx+'" value="'+esc(sat)+'">';
    h += '<input type="hidden" name="hrg_pokok[]"   id="pk_'+idx+'"  value="'+pk+'">';
    h += '<input type="hidden" name="kode_akun[]"   value="'+esc(akun)+'">';
    h += '<input type="hidden" name="berat_gram[]"  id="bg_'+idx+'"  value="'+beratG+'">';
    h += '<input type="hidden" name="kubikasi_m3[]" id="km_'+idx+'"  value="'+kubikM+'">';
    h += '<input type="hidden" name="isi_per_box[]" id="isi_'+idx+'" value="'+isi+'">';
    h += '<div class="d-flex align-items-center">';
    h +=   '<div class="flex-grow-1">';
    h +=     '<small class="text-muted" id="kdlbl_'+idx+'">'+(kd||'&mdash;')+'</small><br>';
    h +=     '<span id="nmlbl_'+idx+'">'+(nm ? esc(nm) : '&mdash;')+'</span>';
    h +=   '</div>';
    h +=   '<button type="button" class="btn btn-xs btn-outline-primary ml-1 btn-pick"'
         +  ' data-idx="'+idx+'">'
         +  '<i class="fas fa-search"></i> Pilih'
         + '</button>';
    h += '</div>';
    h += '<div class="mt-1 small">';
    h +=   '<span class="text-success font-weight-bold">';
    h +=     'Avail: <span id="avail-box_'+idx+'" data-avail-box="'+avBox+'">'+fmtNum(avBox,0)+'</span> box';
    h +=   '</span>';
    h +=   ' + <span class="text-info">';
    h +=     '<span id="avail-ecer_'+idx+'" data-avail-ecer="'+avEcer+'" data-avail-total="'+av+'">'
           + fmtNum(avEcer,0)+'</span> pcs ecer';
    h +=   '</span>';
    h += '</div>';
    h += '</td>';

    /* 1. Expired / Lot */
    h += '<td>';
    h += '<select name="expired_date[]" id="exp_'+idx+'"'
    + ' class="form-control form-control-sm mb-1'+(expSoon?' border-danger text-danger':'')+'"'
    + ' required>';
    h += '<option value="">-- Pilih barang --</option>';
    h += expOption;
    h += '</select>';
    h += '<input type="hidden" name="no_lot[]" id="lot_'+idx+'" value="'+esc(lot)+'">';
    // No Lot ditampilkan sebagai input readonly agar jelas terlihat
    h += '<div class="input-group input-group-sm mt-1">';
    h +=   '<div class="input-group-prepend">';
    h +=     '<span class="input-group-text py-0 px-1" style="font-size:10px">Lot</span>';
    h +=   '</div>';
    h +=   '<input type="text" id="lotlbl_'+idx+'" class="form-control form-control-sm py-0"'
        + ' value="'+esc(lot)+'" readonly placeholder="-" style="font-size:11px">';
    h += '</div>';
    h += '<div id="expwarn_'+idx+'" class="mt-1">'
    + (expSoon ? '<small class="text-danger"><i class="fas fa-exclamation-triangle"></i> &lt;30 hr</small>' : '')
    + '</div>';
    h += '</td>';

    /* 2. Qty Box */
    h += '<td>';
    h += '<input type="number" step="1" min="0" name="qty_box[]" id="qtybox_'+idx+'"'
       + ' class="form-control form-control-sm text-center" value="'+qtyBox+'">';
    h += '<small class="text-muted d-block text-center">maks'
       + ' <span id="maxbox_'+idx+'">'+fmtNum(avBox,0)+'</span></small>';
    h += '</td>';

    /* 3. +Eceran */
    h += '<td>';
    h += '<input type="number" step="1" min="0" name="qty_satuan[]" id="qtyecer_'+idx+'"'
       + ' class="form-control form-control-sm text-center" value="'+qtySat+'">';
    h += '<small class="text-muted d-block text-center">maks'
       + ' <span id="maxecer_'+idx+'">'+fmtNum(avEcer,0)+'</span></small>';
    h += '</td>';

    /* 4. =Total Pcs */
    h += '<td class="text-center align-middle">';
    h += '<b id="qtylbl_'+idx+'">'+fmtNum(qtyKecil,0)+'</b>';
    h += '<br><small class="text-muted">pcs</small>';
    h += '</td>';

    /* 5. Satuan */
    h += '<td>';
    h += '<input type="text" id="satlbl_'+idx+'" class="form-control form-control-sm"'
       + ' value="'+esc(sat)+'" readonly>';
    h += '</td>';

    /* 6. Harga/Pcs + Approver (muncul jika harga != HPP) */
    var approveBy = d.approve_by || d.approve_by_item || '';
    var hargaClass = (hrg > 0 && pk > 0 && Math.abs(hrg - pk) > 0.001) ? 'text-danger' : '';
    h += '<td>';
    h += '<input type="number" step="0.01" min="0" name="hrg_satuan[]" id="hrg_'+idx+'"'
       + ' class="form-control form-control-sm ' + hargaClass + '" value="'+(hrg||'')+'" required>';
    h += '<div id="hrgwarn_'+idx+'" class="mt-1"></div>';
    /* Dropdown approver — tersembunyi, muncul jika harga != HPP */
    h += '<div id="approver-wrap_'+idx+'" style="display:none" class="mt-1">';
    
    h += '<select name="approve_by[]" id="approve_by_'+idx+'"'
       + ' class="form-control form-control-sm">';
    h += '<option value="">-- Pilih Approver --</option>';
    /* Isi dari APPROVER_LIST yang dikirim PHP */
    APPROVER_LIST.forEach(function(a) {
        var label = a.nm + (a.jobdesk ? ' — '+a.jobdesk : '');
        var selected = (a.nm === approveBy) ? ' selected' : '';
        h += '<option value="'+esc(a.nm)+'"'+selected+'>'+esc(label)+'</option>';
    });
    h += '</select>';
    h += '</div>';
    h += '</td>';

    /* 7. Disc% */
    h += '<td>';
    h += '<input type="number" step="0.01" min="0" max="100" name="disc[]" id="disc_'+idx+'"'
       + ' class="form-control form-control-sm text-center" value="'+disc+'">';
    h += '</td>';

    /* 8. Subtotal setelah disc */
    h += '<td class="text-right align-middle">';
    h += '<small class="text-muted d-block" style="font-size:10px">stl disc</small>';
    h += '<b id="subdisc_'+idx+'">'+fmtNum(subDisc)+'</b>';
    h += '</td>';

    /* 9. Pajak dropdown */
    h += '<td>';
    h += '<select name="pajak[]" id="pjk_'+idx+'" class="form-control form-control-sm">';
    TAX_LIST.forEach(function(t) {
        var sel = (Math.abs(t.nm - pajak) < 0.001) ? ' selected' : '';
        h += '<option value="'+t.nm+'"'+sel+'>'+t.nm+'%</option>';
    });
    h += '</select>';
    h += '</td>';

    /* 10. Subtotal akhir */
    h += '<td class="text-right align-middle"><b id="sub_'+idx+'">'+fmtNum(sub)+'</b></td>';

    /* 11. Hapus */
    h += '<td class="text-center align-middle">';
    h += '<button type="button" class="btn btn-xs btn-danger btn-remove" data-idx="'+idx+'">';
    h += '<i class="fas fa-trash"></i></button>';
    h += '</td>';

    h += '</tr>';
    return h;
}

/* ================================================================
   TAMBAH & BIND BARIS
================================================================ */
function tambahBaris(d) {
    document.getElementById('item-body')
        .insertAdjacentHTML('beforeend', buatBaris(rowIdx, d || {}));
    bindBaris(rowIdx);
    rowIdx++;
}

function bindBaris(idx) {
    /* Semua input numerik yang mempengaruhi kalkulasi */
    ['hrg_','qtybox_','qtyecer_','disc_','pjk_'].forEach(function(f) {
        var el = document.getElementById(f+idx);
        if (!el) return;
        el.addEventListener('input',  function(){ hitungBaris(idx); });
        el.addEventListener('change', function(){ hitungBaris(idx); });
    });

    /* Validasi qty box ≤ avail box */
    var elBox = document.getElementById('qtybox_'+idx);
    if (elBox) {
        elBox.addEventListener('change', function() {
            var elE   = document.getElementById('avail-ecer_'+idx);
            var avTot = elE ? parseFloat(elE.dataset.availTotal||0) : 0;
            var isi   = getIsi(idx);
            var maxB  = Math.floor(avTot / isi);
            var v     = parseInt(this.value) || 0;
            if (v > maxB) {
                this.value = maxB;
                hitungBaris(idx);
                alert('Qty box melebihi stok! Maks ' + maxB + ' box.');
            }
        });
    }

    /* Validasi qty eceran ≤ sisa stok setelah box */
    var elEcer = document.getElementById('qtyecer_'+idx);
    if (elEcer) {
        elEcer.addEventListener('change', function() {
            var elE   = document.getElementById('avail-ecer_'+idx);
            var avTot = elE ? parseFloat(elE.dataset.availTotal||0) : 0;
            var isi   = getIsi(idx);
            var qBox  = parseInt((document.getElementById('qtybox_'+idx)||{value:0}).value) || 0;
            var sisa  = avTot - (qBox * isi);
            var v     = parseInt(this.value) || 0;
            if (v > sisa) {
                this.value = Math.max(0, Math.floor(sisa));
                hitungBaris(idx);
                alert(sisa <= 0
                    ? 'Stok habis untuk qty box ini.'
                    : 'Maks '+Math.floor(sisa)+' pcs eceran.');
            }
        });
    }

    /* Ganti expired date → update dimensi, stok, gudang, warning */
    var sel = document.getElementById('exp_'+idx);
    if (sel) {
        sel.addEventListener('change', function() {
            var opt = this.options[this.selectedIndex];
            if (!opt || !opt.value) return;

            var av     = parseFloat(opt.dataset.av  || 0);
            var lot    = opt.dataset.lot || '';
            var bg     = parseFloat(opt.dataset.ton || 0);
            var km     = parseFloat(opt.dataset.kub || 0);
            var isi    = parseInt(opt.dataset.isi   || 1); if (isi < 1) isi = 1;
            var gudang = opt.dataset.gudang || '';

            document.getElementById('bg_' +idx).value = bg;
            document.getElementById('km_' +idx).value = km;
            document.getElementById('isi_'+idx).value = isi;
            document.getElementById('lot_'+idx).value = lot;
            var elLotLbl = document.getElementById('lotlbl_'+idx);
            if (elLotLbl) elLotLbl.value = lot || '';

            /* Gudang → update di Informasi SO */
            document.getElementById('gudang_display').value  = gudang || '-';
            document.getElementById('gudang_id_input').value = gudang || '';

            /* Update avail */
            var avBox  = Math.floor(av / isi);
            var avEcer = Math.floor(av % isi);
            var elB = document.getElementById('avail-box_'+idx);
            var elE = document.getElementById('avail-ecer_'+idx);
            if (elB) {
                elB.textContent = fmtNum(avBox, 0);
                elB.dataset.availBox = avBox;
            }
            if (elE) {
                elE.textContent = fmtNum(avEcer, 0);
                elE.dataset.availEcer = avEcer;
                elE.dataset.availTotal = av;
            }
            var mxB = document.getElementById('maxbox_'+idx);
            var mxE = document.getElementById('maxecer_'+idx);
            if (mxB) mxB.textContent = fmtNum(avBox, 0);
            if (mxE) mxE.textContent = fmtNum(avEcer, 0)+' pcs';

            /* Warna expired */
            var expWarn = document.getElementById('expwarn_'+idx);
            var soon    = opt.value && isExpiringSoon(opt.value);
            if (expWarn) {
                expWarn.innerHTML = soon
                    ? '<small class="text-danger"><i class="fas fa-exclamation-triangle"></i> &lt;30 hr</small>'
                    : '';
            }
            if (soon) {
                sel.classList.add('border-danger','text-danger');
            } else {
                sel.classList.remove('border-danger','text-danger');
            }

            hitungBaris(idx);
        });
    }
}

/* ================================================================
   KALKULASI BARIS
================================================================ */
function hitungBaris(idx) {
    function val(id) { var e = document.getElementById(id); return e ? parseFloat(e.value)||0 : 0; }

    var hrg    = val('hrg_'+idx);
    var qBox   = val('qtybox_'+idx);
    var qSat   = val('qtyecer_'+idx);
    var disc   = val('disc_'+idx);
    var pjk    = val('pjk_'+idx);
    var pk     = val('pk_'+idx);
    var bg     = val('bg_'+idx);
    var km     = val('km_'+idx);
    var isi    = getIsi(idx);

    var elHrg = document.getElementById('hrg_'+idx);
    if (elHrg) {
        var isNego = (hrg > 0 && pk > 0 && Math.abs(hrg - pk) > 0.001);
        if (isNego) {
            elHrg.classList.add('text-danger');
        } else {
            elHrg.classList.remove('text-danger');
        }
    }

    var qKecil    = (qBox * isi) + qSat;
    var subBefore = hrg * qKecil;
    var subDisc   = subBefore * (1 - disc / 100);
    var tot       = subDisc   * (1 + pjk / 100);

    /* tampil total pcs */
    var elQ = document.getElementById('qtylbl_'+idx);
    if (elQ) elQ.textContent = fmtNum(qKecil, 0);

    /* subtotal setelah disc */
    var elSD = document.getElementById('subdisc_'+idx);
    if (elSD) elSD.textContent = fmtNum(subDisc);

    /* subtotal akhir */
    var elS = document.getElementById('sub_'+idx);
    if (elS) elS.textContent = fmtNum(tot);

    /* peringatan harga != HPP + tampilkan/sembunyikan approver */
    var wEl        = document.getElementById('hrgwarn_'+idx);
    var approverWrap = document.getElementById('approver-wrap_'+idx);
    var approverSel  = document.getElementById('approve_by_'+idx);
    var isNego = (hrg > 0 && pk > 0 && Math.abs(hrg - pk) > 0.001);

    if (wEl) {
        wEl.innerHTML = isNego
            ? '<span class="badge badge-warning"><i class="fas fa-exclamation-triangle"></i>'
              + ' Perlu Persetujuan</span>'
            : '';
    }
    if (approverWrap) {
        approverWrap.style.display = isNego ? '' : 'none';
        if (approverSel) approverSel.required = isNego;
        if (!isNego && approverSel) approverSel.value = '';
    }

    hitungGrand();
    hitungTK();
    cekNegoGlobal();
}

/* ================================================================
   GRAND TOTAL
================================================================ */
function hitungGrand() {
    var grand = 0;
    document.querySelectorAll('#item-body tr').forEach(function(tr) {
        var i   = tr.dataset.idx;
        var hrg  = parseFloat((document.getElementById('hrg_'+i)||{value:0}).value)||0;
        var qB   = parseFloat((document.getElementById('qtybox_'+i)||{value:0}).value)||0;
        var qE   = parseFloat((document.getElementById('qtyecer_'+i)||{value:0}).value)||0;
        var disc = parseFloat((document.getElementById('disc_'+i)||{value:0}).value)||0;
        var pjk  = parseFloat((document.getElementById('pjk_'+i)||{value:0}).value)||0;
        var isi  = getIsi(i);
        var qK   = (qB * isi) + qE;
        var sD   = hrg * qK * (1 - disc / 100);
        grand   += sD * (1 + pjk / 100);
    });
    document.getElementById('total-grand').textContent = fmtNum(grand);
}

/* ================================================================
   TOTAL TONASE & KUBIKASI
================================================================ */
function hitungTK() {
    var totTon = 0, totKub = 0, totKecil = 0, totBox = 0, totEcer = 0;
    document.querySelectorAll('#item-body tr').forEach(function(tr) {
        var i  = tr.dataset.idx;
        var qB = parseFloat((document.getElementById('qtybox_'+i)||{value:0}).value)||0;
        var qE = parseFloat((document.getElementById('qtyecer_'+i)||{value:0}).value)||0;
        var isi= getIsi(i);
        var bg = parseFloat((document.getElementById('bg_'+i)||{value:0}).value)||0;
        var km = parseFloat((document.getElementById('km_'+i)||{value:0}).value)||0;
        var qK = (qB * isi) + qE;
        totTon   += qK * bg / 1000000;
        totKub   += qK * km;
        totKecil += qK;
        totBox   += qB;
        totEcer  += qE;
    });

    document.getElementById('lbl-tonase').textContent      = fmtNum(totTon,   3);
    document.getElementById('lbl-kubikasi').textContent    = fmtNum(totKub,   5);
    document.getElementById('lbl-total-kecil').textContent = fmtNum(totKecil, 0);
    document.getElementById('lbl-total-box').textContent   = fmtNum(totBox,   0);
    document.getElementById('lbl-total-ecer').textContent  = fmtNum(totEcer,  0);

    /* progress tonase */
    var pT = BATAS_TONASE > 0 ? Math.min(totTon / BATAS_TONASE * 100, 100) : 0;
    var bT = document.getElementById('tonase-bar');
    if (bT) {
        bT.style.width = pT.toFixed(2)+'%';
        bT.className   = 'progress-bar '+(totTon > BATAS_TONASE ? 'bg-danger' : 'bg-success');
    }
    var wT = document.getElementById('lbl-tonase-warn');
    if (wT) wT.classList.toggle('d-none', totTon <= BATAS_TONASE);

    /* progress kubikasi */
    var pK = BATAS_KUBIKASI > 0 ? Math.min(totKub / BATAS_KUBIKASI * 100, 100) : 0;
    var bK = document.getElementById('kubikasi-bar');
    if (bK) {
        bK.style.width = pK.toFixed(2)+'%';
        bK.className   = 'progress-bar '+(totKub > BATAS_KUBIKASI ? 'bg-danger' : 'bg-info');
    }
    var wK = document.getElementById('lbl-kubikasi-warn');
    if (wK) wK.classList.toggle('d-none', totKub <= BATAS_KUBIKASI);
}

/* ================================================================
   CEK NEGO GLOBAL — tampilkan notif ringkasan
================================================================ */
function cekNegoGlobal() {
    var negoItems = [];
    document.querySelectorAll('#item-body tr').forEach(function(tr) {
        var i   = tr.dataset.idx;
        var hrg = parseFloat((document.getElementById('hrg_'+i)||{value:0}).value)||0;
        var pk  = parseFloat((document.getElementById('pk_' +i)||{value:0}).value)||0;
        var nm  = (document.getElementById('nm_'+i)||{value:''}).value || '';
        if (hrg > 0 && pk > 0 && Math.abs(hrg - pk) > 0.001) {
            negoItems.push(nm
                + ' (HPP: '+fmtNum(pk)
                + ', Input: '+fmtNum(hrg)+')');
        }
    });

    var notifNego = document.getElementById('notif-nego');
    var listNego  = document.getElementById('nego-detail-list');

    if (negoItems.length > 0) {
        if (notifNego) notifNego.classList.remove('d-none');
        if (listNego) {
            listNego.innerHTML = negoItems.map(function(s){
                return '<li><i class="fas fa-arrow-right mr-1"></i>'+esc(s)+'</li>';
            }).join('');
        }
    } else {
        if (notifNego) notifNego.classList.add('d-none');
        if (listNego)  listNego.innerHTML = '';
    }
}

/* ================================================================
   LOAD STOK (AJAX)
================================================================ */
function loadStock() {
    document.getElementById('stock-body').innerHTML =
        '<tr><td colspan="12" class="text-center py-3">'
        + '<i class="fas fa-spinner fa-spin mr-1"></i> Memuat...</td></tr>';

    fetch(BASE_URL + 'sales_order/get_stock')
        .then(function(r) {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        })
        .then(function(res) {
            if (res.status !== 'ok') throw new Error(res.message || 'Error server');
            stockCache = res.data || [];
            renderStock(stockCache);
        })
        .catch(function(err) {
            document.getElementById('stock-body').innerHTML =
                '<tr><td colspan="12" class="text-center text-danger py-3">'
                + '<i class="fas fa-exclamation-triangle mr-1"></i>'
                + esc(err.message) + '</td></tr>';
        });
}

function renderStock(data) {
    var q = (document.getElementById('stock-search').value || '').toLowerCase();
    var filtered = q
        ? data.filter(function(d) {
            // Handle both 'kd_barang' and 'kode_barang' field names
            var kd = d.kd_barang || d.kode_barang || '';
            return String(kd).toLowerCase().indexOf(q) >= 0
                || String(d.nama_barang||'').toLowerCase().indexOf(q) >= 0;
          })
        : data;

    if (!filtered.length) {
        document.getElementById('stock-body').innerHTML =
            '<tr><td colspan="12" class="text-center text-muted py-3">'
            + '<i class="fas fa-inbox mr-1"></i> Tidak ada stok</td></tr>';
        return;
    }

    var html = '', lastKd = null;
    filtered.forEach(function(d) {
        // Handle both 'kd_barang' and 'kode_barang' field names
        var kd = d.kd_barang || d.kode_barang || '';
        var isNew   = (kd !== lastKd);
        lastKd      = kd;
        var isi     = parseInt(d.isi_per_box || 1);
        var avTotal = parseFloat(d.available_stock || 0);
        var avBox   = parseInt(d.available_box  || 0);
        var avEcer  = parseInt(d.available_ecer || 0);
        var beratKg = (parseFloat(d.berat_gram  || 0) / 1000).toFixed(3);
        var kubStr  = parseFloat(d.kubikasi_m3  || 0).toFixed(6);

        html += '<tr class="'+(isNew ? 'table-light' : '')+'">';
        html += '<td>';
        html += '<small class="text-muted d-block">'+esc(kd)+'</small>';
        html += isNew
            ? '<b>'+esc(d.nama_barang)+'</b>'
            : '<span class="text-muted">&#x21B3;</span> '+esc(d.nama_barang);
        html += '</td>';
        // Handle both 'exp_date' and 'expired_date' field names
        var expDate = d.exp_date || d.expired_date || '';
        html += '<td>'+(expDate
            ? '<span class="badge '+(isExpiringSoon(expDate)?'badge-warning':'badge-success')+'">'
              + formatTgl(expDate)+'</span>'
            : '-')+'</td>';
        html += '<td>'+(d.no_lot || '-')+'</td>';
        html += '<td class="text-right"><b class="text-success">'+fmtNum(avBox, 0)+' box</b></td>';
        html += '<td class="text-right"><span class="text-info">'+fmtNum(avEcer, 0)+' pcs</span></td>';
        html += '<td class="text-right text-muted"><small>'+fmtNum(avTotal, 0)+' pcs</small></td>';
        html += '<td class="text-center"><span class="badge badge-secondary">'+isi+'</span></td>';
        html += '<td>'+esc(d.satuan || '')+'</td>';
        html += '<td class="text-right"><small>'+beratKg+' kg</small></td>';
        html += '<td class="text-right"><small>'+kubStr+' m³</small></td>';
        // Handle both 'gudang' and 'gudang_id' field names
        var gudang = d.gudang || d.gudang_id || '-';
        html += '<td><small class="text-muted">'+esc(String(gudang))+'</small></td>';
        html += '<td class="text-center">';
        html += '<button type="button" class="btn btn-xs btn-primary btn-pick-stock"';
        html += ' data-kd="'+esc(kd)+'"';
        html += ' data-nm="'+esc(d.nama_barang||'')+'"';
        html += ' data-exp="'+esc(expDate)+'"';
        html += ' data-lot="'+esc(d.no_lot||'')+'"';
        html += ' data-sat="'+esc(d.satuan||'')+'"';
        html += ' data-av="'+avTotal+'"';
        html += ' data-ton="'+(parseFloat(d.berat_gram)||0)+'"';
        html += ' data-kub="'+(parseFloat(d.kubikasi_m3)||0)+'"';
        html += ' data-isi="'+isi+'"';                         /* ← PENTING */
        html += ' data-gudang="'+esc(String(gudang))+'"';
        html += ' data-pk="'+(parseFloat(d.hpp||d.hpp)||0)+'">';
        html += '<i class="fas fa-check"></i> Pilih</button>';
        html += '</td></tr>';
    });
    document.getElementById('stock-body').innerHTML = html;
}

/* ================================================================
   RENDER CUSTOMER MODAL
================================================================ */
function renderCustomers(q) {
    q = (q || '').toLowerCase();
    var filtered = q
        ? CUSTOMERS.filter(function(c) {
            return String(c.nama_customer||'').toLowerCase().indexOf(q) >= 0;
          })
        : CUSTOMERS;

    if (!filtered.length) {
        document.getElementById('customer-body').innerHTML =
            '<tr><td colspan="4" class="text-center text-muted">Tidak ada customer.</td></tr>';
        return;
    }
    var html = '';
    filtered.forEach(function(c) {
        html += '<tr>';
        html += '<td><b>'+esc(c.nama_customer||'')+'</b></td>';
        html += '<td><small>'+esc(c.nama_kios||'-')+'</small></td>';
        html += '<td><small>'+esc(c.alamat_kios||'-')+'</small></td>';
        html += '<td class="text-center">';
        html += '<button type="button" class="btn btn-xs btn-primary btn-pick-customer"';
        html += ' data-kd="'+esc(c.kd_customer)+'" data-nama="'+esc(c.nama_customer)+'">';
        html += '<i class="fas fa-check"></i></button>';
        html += '</td></tr>';
    });
    document.getElementById('customer-body').innerHTML = html;
}

/* ================================================================
   EVENTS
================================================================ */

/* Tabel item: hapus baris & buka modal pilih barang */
document.getElementById('item-body').addEventListener('click', function(e) {
    var r = e.target.closest('.btn-remove');
    if (r) {
        r.closest('tr').remove();
        hitungGrand();
        hitungTK();
        return;
    }
    var p = e.target.closest('.btn-pick');
    if (p) {
        currentRowIdx = parseInt(p.dataset.idx);
        loadStock();
        $('#modal-stock').modal('show');
    }
});

/* Modal barang: pilih satu baris */
document.getElementById('stock-body').addEventListener('click', function(e) {
    var btn = e.target.closest('.btn-pick-stock');
    if (!btn) return;

    var i  = currentRowIdx;
    var kd = btn.dataset.kd;

    /* Set hidden inputs barang */
    document.getElementById('kd_'    +i).value       = kd;
    document.getElementById('nm_'    +i).value       = btn.dataset.nm;
    document.getElementById('kdlbl_' +i).textContent = kd;
    document.getElementById('nmlbl_' +i).textContent = btn.dataset.nm;
    document.getElementById('pk_'    +i).value       = btn.dataset.pk;
    document.getElementById('sat_'   +i).value       = btn.dataset.sat;
    document.getElementById('satlbl_'+i).value       = btn.dataset.sat;

    /* isi_per_box dari data-isi tombol */
    var isiDef = parseInt(btn.dataset.isi || 1);
    if (isiDef < 1) isiDef = 1;
    document.getElementById('isi_'+i).value = isiDef;

    /* Auto-fill harga dari HPP */
    var hpp = parseFloat(btn.dataset.pk || 0);
    if (hpp > 0) {
        var elHrg = document.getElementById('hrg_'+i);
        if (elHrg) elHrg.value = hpp;
    }

    /* Gudang dari barang */
    var gudang = btn.dataset.gudang || '';
    document.getElementById('gudang_display').value  = gudang || '-';
    document.getElementById('gudang_id_input').value = gudang || '';

    /* Isi dropdown expired date dari semua baris stok barang ini */
    /* Handle both 'kd_barang' (from tberp_stock_batch) and 'kode_barang' (from v_available_stock) */
    var rows = stockCache.filter(function(s) {
        return (s.kd_barang || s.kode_barang) === kd;
    });
    var sel  = document.getElementById('exp_'+i);
    sel.innerHTML = '<option value="">-- Pilih Expired Date --</option>';
    rows.forEach(function(s) {
        var opt    = document.createElement('option');
        /* Handle both 'exp_date' and 'expired_date' field names */
        var expDate = s.exp_date || s.expired_date || '';
        opt.value  = expDate;
        var isiS   = parseInt(s.isi_per_box || 1);
        var avBox  = Math.floor(parseFloat(s.available_stock||0) / isiS);
        var avEcer = Math.floor(parseFloat(s.available_stock||0) % isiS);
        opt.textContent = formatTgl(expDate)
            + (s.no_lot ? ' | Lot: '+s.no_lot : '')
            + ' ['+fmtNum(avBox,0)+' box + '+fmtNum(avEcer,0)+' pcs]';
        opt.dataset.ton    = parseFloat(s.berat_gram    || 0);
        opt.dataset.kub    = parseFloat(s.kubikasi_m3   || 0);
        opt.dataset.av     = parseFloat(s.available_stock || 0);
        opt.dataset.lot    = s.no_lot || '';
        opt.dataset.isi    = parseInt(s.isi_per_box || 1);
        opt.dataset.gudang = s.gudang || s.gudang_id || '';
        if (expDate === btn.dataset.exp) opt.selected = true;
        sel.appendChild(opt);
    });

    /* Auto-pilih jika hanya satu expired */
    if (rows.length === 1) sel.selectedIndex = 1;
    sel.dispatchEvent(new Event('change'));   /* trigger update avail & gudang */

    /* Update no lot dari option yang terpilih */
    var selOpt = document.getElementById('exp_'+i);
    if (selOpt && selOpt.selectedIndex > 0) {
        var pickedOpt = selOpt.options[selOpt.selectedIndex];
        var elLot = document.getElementById('lot_'+i);
        var elLotLbl = document.getElementById('lotlbl_'+i);
        if (elLot) elLot.value = pickedOpt.dataset.lot || '';
        if (elLotLbl) elLotLbl.value = pickedOpt.dataset.lot || '';
    }
    
    /* Reset qty */
    document.getElementById('qtybox_' +i).value = 0;
    document.getElementById('qtyecer_'+i).value = 0;
    hitungBaris(i);

    $('#modal-stock').modal('hide');
});

/* Buka modal customer */
document.getElementById('btn-pilih-customer').addEventListener('click', function() {
    document.getElementById('customer-search').value = '';
    renderCustomers('');
    $('#modal-customer').modal('show');
    setTimeout(function() {
        document.getElementById('customer-search').focus();
    }, 400);
});

/* Cari customer */
document.getElementById('customer-search').addEventListener('input', function() {
    renderCustomers(this.value);
});

/* Pilih customer */
document.getElementById('customer-body').addEventListener('click', function(e) {
    var btn = e.target.closest('.btn-pick-customer');
    if (!btn) return;
    document.getElementById('customer_id').value      = btn.dataset.kd;
    document.getElementById('customer_name').value    = btn.dataset.nama;
    document.getElementById('customer_display').value = btn.dataset.nama;
    document.getElementById('customer_validation').style.display = 'none';
    $('#modal-customer').modal('hide');
});

/* Cari barang */
document.getElementById('stock-search').addEventListener('input', function() {
    renderStock(stockCache);
});

/* Tambah baris kosong */
document.getElementById('btn-add-row').addEventListener('click', function() {
    tambahBaris({});
});

/* Validasi submit */
document.getElementById('form-so').addEventListener('submit', function(e) {
    if (!document.getElementById('customer_id').value) {
        e.preventDefault();
        document.getElementById('customer_validation').style.display = 'block';
        document.getElementById('btn-pilih-customer').focus();
        return;
    }
    var rows = document.querySelectorAll('#item-body tr');
    if (!rows.length) {
        e.preventDefault();
        alert('Minimal 1 item barang harus ditambahkan!');
        return;
    }
    var errs = [];
    rows.forEach(function(tr) {
        var i      = tr.dataset.idx;
        var qBox   = parseFloat((document.getElementById('qtybox_' +i)||{value:0}).value)||0;
        var qEcer  = parseFloat((document.getElementById('qtyecer_'+i)||{value:0}).value)||0;
        var isi    = getIsi(i);
        var nm     = (document.getElementById('nm_'+i)||{value:'(barang)'}).value || '(barang)';
        var elE    = document.getElementById('avail-ecer_'+i);
        var avTot  = elE ? parseFloat(elE.dataset.availTotal||0) : 0;
        var qKecil = (qBox * isi) + qEcer;

        if (qBox <= 0 && qEcer <= 0)
            errs.push(nm + ': qty box dan eceran keduanya 0');
        else if (qBox > Math.floor(avTot / isi))
            errs.push(nm + ': qty box '+qBox+' melebihi stok '+Math.floor(avTot/isi)+' box');
        else if (qKecil > avTot)
            errs.push(nm + ': total '+qKecil+' pcs melebihi stok '+avTot+' pcs');
    });
    if (errs.length) {
        e.preventDefault();
        alert('Peringatan:\n\u2022 ' + errs.join('\n\u2022 '));
    }
});

/* ================================================================
   INIT
================================================================ */
if (EDIT_DETAILS.length) {
    EDIT_DETAILS.forEach(function(d) { tambahBaris(d); });
    hitungGrand();
    hitungTK();

    /* Load stok untuk update avail & expired options */
    fetch(BASE_URL + 'sales_order/get_stock')
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.status !== 'ok') return;
            stockCache = res.data || [];

            document.querySelectorAll('#item-body tr').forEach(function(tr) {
                var i      = tr.dataset.idx;
                var kd     = (document.getElementById('kd_' +i)||{value:''}).value;
                var expVal = (document.getElementById('exp_'+i)||{}).value || '';
                if (!kd) return;

                /* Rebuild dropdown expired - handle both field name formats */
                var rows = stockCache.filter(function(s){
                    return (s.kd_barang || s.kode_barang) === kd;
                });
                var sel  = document.getElementById('exp_'+i);
                if (sel && rows.length) {
                    sel.innerHTML = '<option value="">-- Pilih Expired Date --</option>';
                    rows.forEach(function(s) {
                        var opt    = document.createElement('option');
                        /* Handle both exp_date and expired_date */
                        var expDate = s.exp_date || s.expired_date || '';
                        opt.value  = expDate;
                        var isiS   = parseInt(s.isi_per_box || 1);
                        var avBox  = Math.floor(parseFloat(s.available_stock||0) / isiS);
                        var avEcer = Math.floor(parseFloat(s.available_stock||0) % isiS);
                        opt.textContent = formatTgl(expDate)
                            + (s.no_lot ? ' | Lot: '+s.no_lot : '')
                            + ' ['+fmtNum(avBox,0)+' box + '+fmtNum(avEcer,0)+' pcs]';
                        opt.dataset.ton    = parseFloat(s.berat_gram    || 0);
                        opt.dataset.kub    = parseFloat(s.kubikasi_m3   || 0);
                        opt.dataset.av     = parseFloat(s.available_stock || 0);
                        opt.dataset.lot    = s.no_lot || '';
                        opt.dataset.isi    = parseInt(s.isi_per_box || 1);
                        opt.dataset.gudang = s.gudang || s.gudang_id || '';
                        if (expDate === expVal) opt.selected = true;
                        sel.appendChild(opt);
                    });
                }

                /* Update avail dari match - handle both field name formats */
                var match = stockCache.filter(function(s){
                    var sKd = s.kd_barang || s.kode_barang || '';
                    var sExp = s.exp_date || s.expired_date || '';
                    return sKd === kd && sExp === expVal;
                });
                var stok = match.length ? match[0] : null;
                if (stok) {
                    var isi    = parseInt(stok.isi_per_box || 1);
                    var av     = parseFloat(stok.available_stock || 0);
                    var avBox  = Math.floor(av / isi);
                    var avEcer = Math.floor(av % isi);

                    var elB = document.getElementById('avail-box_'+i);
                    var elE = document.getElementById('avail-ecer_'+i);
                    if (elB) { elB.textContent = fmtNum(avBox,0); elB.dataset.availBox = avBox; }
                    if (elE) { elE.textContent = fmtNum(avEcer,0); elE.dataset.availEcer = avEcer; elE.dataset.availTotal = av; }

                    var mxB = document.getElementById('maxbox_'+i);
                    var mxE = document.getElementById('maxecer_'+i);
                    if (mxB) mxB.textContent = fmtNum(avBox, 0);
                    if (mxE) mxE.textContent = fmtNum(avEcer, 0)+' pcs';

                    document.getElementById('isi_'+i).value = isi;
                    document.getElementById('bg_' +i).value = stok.berat_gram  || 0;
                    document.getElementById('km_' +i).value = stok.kubikasi_m3 || 0;

                    /* Update gudang di Informasi SO */
                    var gudang = stok.gudang || stok.gudang_id || '-';
                    document.getElementById('gudang_display').value  = gudang;
                    document.getElementById('gudang_id_input').value = gudang;
                }

                hitungBaris(i);
            });
        })
        .catch(function(err) {
            console.warn('Gagal load stok untuk edit:', err);
        });

} else {
    tambahBaris({});
    hitungGrand();
    hitungTK();
    cekNegoGlobal(); 
}
</script>
</body>