<?php
defined('BASEPATH') or exit('No direct script access allowed');
$summary = $summary ?? [];
$pending_rows = $pending_rows ?? [];
$keyword = $keyword ?? '';
$pending_mode = in_array(($pending_mode ?? ($summary['mode'] ?? 'add')), ['add', 'subtract'], true) ? ($pending_mode ?? ($summary['mode'] ?? 'add')) : 'add';
$pagination = $pagination ?? ['page' => 1, 'per_page' => 10, 'total_rows' => count($pending_rows), 'total_pages' => 1];
$so_e = function ($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
};
$formatDate = function ($value) {
    $value = trim((string)$value);
    if ($value === '' || $value === '0000-00-00') {
        return '-';
    }
    $timestamp = strtotime($value);
    return $timestamp ? date('d/m/Y', $timestamp) : $value;
};
$pageUrl = function ($page) use ($keyword) {
    $query = ['page' => (int)$page];
    if (trim((string)$keyword) !== '') {
        $query['keyword'] = trim((string)$keyword);
    }
    return base_url('admin/stockopname/monitoring/pending-opname?' . http_build_query($query));
};
?>
<body class="hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view('partial/main/navbar') ?>
    <?php $this->load->view('partial/main/sidebar') ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-7">
                        <h1 class="m-0">Detail Pending Opname</h1>
                    </div>
                    <div class="col-sm-5 text-sm-right mt-2 mt-sm-0">
                        <a href="<?= base_url('admin/stockopname/monitoring') ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Monitoring
                        </a>
                        <a href="<?= base_url('admin/stockopname/barang-pending') ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Kelola Pending
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <style>
                    .pending-detail{background:#fff;border:1px solid #e1e7ef;border-radius:8px;box-shadow:0 8px 22px rgba(16,24,40,.06)}
                    .pending-detail-header{padding:14px 16px;border-bottom:1px solid #e8edf3;display:flex;align-items:center;justify-content:space-between;gap:10px}
                    .pending-tools{display:flex;align-items:center;justify-content:flex-end;gap:8px;flex-wrap:wrap}
                    .pending-title{font-size:16px;font-weight:800;color:#1f2937;margin:0}
                    .pending-stat{background:#fff;border:1px solid #e1e7ef;border-radius:8px;padding:14px;min-height:96px}
                    .pending-stat span{display:block;color:#64748b;font-size:12px;text-transform:uppercase;font-weight:800}
                    .pending-stat strong{display:block;font-size:24px;color:#111827;line-height:1.1;margin-top:7px}
                    .pending-table-wrap{overflow:auto}.pending-code{font-family:monospace;font-weight:700}
                    .pending-action{white-space:nowrap}.btn-icon{width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;padding:0}.btn.btn-icon i{margin-right:0}
                    .pending-pagination{padding:12px 16px;border-top:1px solid #e8edf3;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
                    .table td,.table th{vertical-align:middle}.btn i{margin-right:5px}
                    @media(max-width:768px){.pending-detail-header{align-items:flex-start;flex-direction:column}.pending-tools{justify-content:flex-start;width:100%}.content-header h1{font-size:22px}}
                </style>
                <div class="row">
                    <div class="col-md-3 col-6 mb-3">
                        <div class="pending-stat"><span>Total Item</span><strong><?= number_format((int)($summary['total_item'] ?? 0), 0, ',', '.') ?></strong></div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="pending-stat"><span>Total Qty</span><strong><?= number_format((int)($summary['total_qty'] ?? 0), 0, ',', '.') ?></strong></div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="pending-stat"><span>Total PCS</span><strong><?= number_format((int)($summary['total_qty_pcs'] ?? 0), 0, ',', '.') ?></strong></div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="pending-stat"><span>Total Box</span><strong><?= number_format((int)($summary['total_qty_box'] ?? 0), 0, ',', '.') ?></strong></div>
                    </div>
                </div>
                <div class="pending-detail">
                    <div class="pending-detail-header">
                        <div>
                            <h2 class="pending-title">Data Barang Pending Opname</h2>
                            <div class="text-muted small">Hitungan masuk master berdasarkan nama barang dan expired date yang sama.</div>
                        </div>
                        <div class="pending-tools">
                            <form id="pendingModeForm" class="input-group input-group-sm" style="width:245px">
                                <select id="pendingMode" name="mode" class="form-control">
                                    <option value="add" <?= $pending_mode === 'add' ? 'selected' : '' ?>>Qty dasar + pending</option>
                                    <option value="subtract" <?= $pending_mode === 'subtract' ? 'selected' : '' ?>>Qty dasar - pending</option>
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="submit"><i class="fas fa-save"></i></button>
                                </div>
                            </form>
                            <form method="get" action="<?= base_url('admin/stockopname/monitoring/pending-opname') ?>" class="input-group input-group-sm" style="max-width:360px">
                                <input type="search" name="keyword" value="<?= $so_e($keyword) ?>" class="form-control" placeholder="Cari kode, nama, expired, lot">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i> Cari</button>
                                    <a href="<?= base_url('admin/stockopname/monitoring/pending-opname') ?>" class="btn btn-outline-secondary"><i class="fas fa-sync-alt"></i></a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="pending-table-wrap">
                        <table class="table table-sm table-hover table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Expired</th>
                                    <th class="text-right">Qty</th>
                                    <th class="text-right">PCS</th>
                                    <th class="text-right">Box</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($pending_rows)) : ?>
                                    <tr><td colspan="6" class="text-center text-muted p-4">Belum ada data pending opname.</td></tr>
                                <?php endif ?>
                                <?php foreach ($pending_rows as $row) : ?>
                                    <?php
                                        $targetKode = trim((string)($row['master_kode_barang'] ?? ''));
                                        $hasTarget = (int)($row['master_id'] ?? 0) > 0 && $targetKode !== '';
                                    ?>
                                    <tr data-row-id="<?= (int)($row['id'] ?? 0) ?>">
                                        <td><?= $so_e($row['nama_barang'] ?? '-') ?></td>
                                        <td><?= $so_e($formatDate($row['expired_date'] ?? '-')) ?></td>
                                        <td class="text-right font-weight-bold"><?= number_format((int)($row['qty'] ?? 0), 0, ',', '.') ?></td>
                                        <td class="text-right"><?= number_format((int)($row['qty_pcs'] ?? 0), 0, ',', '.') ?></td>
                                        <td class="text-right"><?= number_format((int)($row['qty_box'] ?? 0), 0, ',', '.') ?></td>
                                        <td class="text-center pending-action">
                                            <?php if ($hasTarget) : ?>
                                                <a href="<?= base_url('admin/stockopname/detail_input_opname?kode_barang=' . rawurlencode($targetKode)) ?>" class="btn btn-success btn-sm btn-icon" title="Lihat detail input opname">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            <?php else : ?>
                                                <button type="button" class="btn btn-secondary btn-sm btn-icon" disabled title="Master opname dengan nama dan expired date yang sama tidak ditemukan">
                                                    <i class="fas fa-eye-slash"></i>
                                                </button>
                                            <?php endif ?>
                                            <a href="<?= base_url('admin/stockopname/barang-pending?edit_id=' . (int)($row['id'] ?? 0)) ?>" class="btn btn-outline-primary btn-sm btn-icon" title="Update barang pending">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-icon btn-delete-pending" data-id="<?= (int)($row['id'] ?? 0) ?>" title="Delete barang pending">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                        $currentPage = (int)($pagination['page'] ?? 1);
                        $totalPages = (int)($pagination['total_pages'] ?? 1);
                        $totalRows = (int)($pagination['total_rows'] ?? 0);
                        $perPage = (int)($pagination['per_page'] ?? 10);
                        $fromRow = $totalRows > 0 ? (($currentPage - 1) * $perPage) + 1 : 0;
                        $toRow = min($totalRows, $currentPage * $perPage);
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($totalPages, $currentPage + 2);
                    ?>
                    <div class="pending-pagination">
                        <div class="text-muted small">Menampilkan <?= number_format($fromRow, 0, ',', '.') ?>-<?= number_format($toRow, 0, ',', '.') ?> dari <?= number_format($totalRows, 0, ',', '.') ?> data</div>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>"><a class="page-link" href="<?= $currentPage <= 1 ? '#' : $pageUrl($currentPage - 1) ?>"><i class="fas fa-chevron-left"></i></a></li>
                            <?php for ($i = $startPage; $i <= $endPage; $i++) : ?>
                                <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>"><a class="page-link" href="<?= $pageUrl($i) ?>"><?= $i ?></a></li>
                            <?php endfor ?>
                            <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>"><a class="page-link" href="<?= $currentPage >= $totalPages ? '#' : $pageUrl($currentPage + 1) ?>"><i class="fas fa-chevron-right"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <footer class="main-footer"><strong>Copyright &copy; 2022 <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.</strong></footer>
</div>
<script>
window.addEventListener('load', function () {
    function notify(icon, message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({toast:true,position:'top-end',icon:icon,title:message,showConfirmButton:false,timer:2600});
        } else {
            alert(message);
        }
    }
    function deletePending(id) {
        $.ajax({
            url: '<?= base_url('admin/stockopname/barang-pending/delete') ?>',
            type: 'POST',
            dataType: 'json',
            data: {id: id}
        }).done(function (res) {
            notify(res.status ? 'success' : 'error', res.message || 'Data diproses');
            if (res.status) {
                $('tr[data-row-id="' + id + '"]').remove();
                if ($('tr[data-row-id]').length === 0) {
                    $('.pending-table-wrap tbody').html('<tr><td colspan="6" class="text-center text-muted p-4">Belum ada data pending opname.</td></tr>');
                }
            }
        }).fail(function () {
            notify('error', 'Server gagal menghapus data pending');
        });
    }
    $('.btn-delete-pending').on('click', function () {
        var id = $(this).data('id');
        if (!id) { notify('error', 'ID barang pending tidak valid.'); return; }
        if (typeof Swal !== 'undefined') {
            Swal.fire({icon:'warning',title:'Hapus barang pending?',showCancelButton:true,confirmButtonText:'Hapus',cancelButtonText:'Batal'}).then(function (result) {
                if (result.isConfirmed) { deletePending(id); }
            });
            return;
        }
        if (confirm('Hapus barang pending?')) { deletePending(id); }
    });
    $('#pendingModeForm').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            url: '<?= base_url('admin/stockopname/pending-mode') ?>',
            type: 'POST',
            dataType: 'json',
            data: {mode: $('#pendingMode').val()}
        }).done(function (res) {
            notify(res.status ? 'success' : 'error', res.message || 'Mode pending diproses');
            if (res.status) {
                window.location.reload();
            }
        }).fail(function () {
            notify('error', 'Server gagal menyimpan mode pending');
        });
    });
});
</script>
