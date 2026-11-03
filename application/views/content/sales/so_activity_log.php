<!-- views/content/sales/so_activity_log.php -->
<?php
function aksi_badge($aksi) {
    $map = [
        'CREATE'      => ['success', 'fa-plus-circle',  'Buat SO'],
        'CREATE_NEGO' => ['warning', 'fa-tag',           'Buat SO (Nego)'],
        'UPDATE'      => ['primary', 'fa-edit',          'Update SO'],
        'CANCEL'      => ['danger',  'fa-times-circle',  'Batalkan SO'],
        'APPROVE'     => ['success', 'fa-check-circle',  'Approve Nego'],
        'REJECT'      => ['danger',  'fa-ban',           'Reject Nego'],
    ];
    $a   = strtoupper($aksi);
    $cfg = $map[$a] ?? ['secondary', 'fa-circle', $aksi];
    return '<span class="badge badge-'.$cfg[0].'">'
         . '<i class="fas '.$cfg[1].' mr-1"></i>'
         . htmlspecialchars($cfg[2])
         . '</span>';
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
                            <i class="fas fa-history mr-2"></i> Activity Log — Sales Order
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('sales_order') ?>">Sales Order</a></li>
                            <li class="breadcrumb-item active">Activity Log</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle mr-1"></i>
                        <?= $this->session->flashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= $this->session->flashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <a href="<?= base_url('sales_order') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <!-- FILTER -->
                <div class="card card-outline card-primary mb-3">
                    <div class="card-header py-2">
                        <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body py-2">
                        <form method="get" action="<?= base_url('sales_order/activity_log') ?>">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <label class="small mb-1">No SO</label>
                                    <input type="text" name="no_so" class="form-control form-control-sm"
                                        value="<?= htmlspecialchars($filter['no_so']) ?>"
                                        placeholder="Cari no SO...">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="small mb-1">Aksi</label>
                                    <select name="aksi" class="form-control form-control-sm">
                                        <option value="">-- Semua --</option>
                                        <?php
                                        $aksi_list = ['CREATE','CREATE_NEGO','UPDATE','CANCEL','APPROVE','REJECT'];
                                        foreach ($aksi_list as $a):
                                        ?>
                                        <option value="<?= $a ?>" <?= $filter['aksi'] === $a ? 'selected' : '' ?>>
                                            <?= $a ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="small mb-1">Tanggal</label>
                                    <input type="date" name="tanggal" class="form-control form-control-sm"
                                        value="<?= htmlspecialchars($filter['tanggal']) ?>">
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="small mb-1">Keyword</label>
                                    <input type="text" name="keyword" class="form-control form-control-sm"
                                        value="<?= htmlspecialchars($filter['keyword']) ?>"
                                        placeholder="Nama user, keterangan, no faktur...">
                                </div>
                                <div class="col-md-2 mb-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary btn-sm mr-1">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                    <a href="<?= base_url('sales_order/activity_log') ?>" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-undo"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- TABEL LOG -->
                <div class="card">
                    <div class="card-header py-2">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-list mr-1"></i>
                            Daftar Activity Log
                            <span class="badge badge-secondary ml-2"><?= number_format($total) ?> record</span>
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm mb-0" id="tabel-log">
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="width:40px" class="text-center">#</th>
                                        <th style="width:150px">Waktu</th>
                                        <th style="width:130px">No SO</th>
                                        <th style="width:130px">No Faktur</th>
                                        <th style="width:120px">Aksi</th>
                                        <th style="width:200px">Keterangan</th>
                                        <th>Detail Produk</th>
                                        <th style="width:130px">PIC</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($logs)): ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                                Tidak ada data activity log.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php
                                        $no = (($page - 1) * $per_page) + 1;
                                        foreach ($logs as $log):
                                        ?>
                                        <tr>
                                            <td class="text-center text-muted"><?= $no++ ?></td>
                                            <td><small><?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?></small></td>
                                            <td>
                                                <?php if (!empty($log['no_so'])): ?>
                                                    <a href="<?= base_url('sales_order/detail/'.rawurlencode($log['no_so'])) ?>">
                                                        <b><?= htmlspecialchars($log['no_so']) ?></b>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><small><?= htmlspecialchars($log['no_faktur'] ?? '-') ?></small></td>
                                            <td><?= aksi_badge($log['aksi']) ?></td>
                                            <td><small><?= htmlspecialchars($log['keterangan'] ?? '-') ?></small></td>
                                            <td>
                                                <?php if (!empty($log['detail_produk'])): ?>
                                                    <?php foreach (explode("\n", $log['detail_produk']) as $baris): ?>
                                                        <small class="d-block border-bottom pb-1 mb-1">
                                                            <i class="fas fa-box text-secondary mr-1"></i>
                                                            <?= htmlspecialchars($baris) ?>
                                                        </small>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <i class="fas fa-user text-muted mr-1"></i>
                                                <small><?= htmlspecialchars($log['dilakukan_oleh'] ?? '-') ?></small>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- PAGINATION -->
                    <?php if ($total > $per_page): ?>
                    <div class="card-footer">
                        <?php
                        $total_pages = ceil($total / $per_page);
                        $base_query  = http_build_query(array_filter($filter));
                        ?>
                        <nav>
                            <ul class="pagination pagination-sm mb-0 justify-content-center">
                                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?<?= $base_query ?>&page=<?= $page - 1 ?>">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                                <?php
                                $start = max(1, $page - 2);
                                $end   = min($total_pages, $page + 2);
                                for ($p = $start; $p <= $end; $p++):
                                ?>
                                <li class="page-item <?= $p == $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?<?= $base_query ?>&page=<?= $p ?>"><?= $p ?></a>
                                </li>
                                <?php endfor; ?>
                                <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?<?= $base_query ?>&page=<?= $page + 1 ?>">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                        <div class="text-center text-muted mt-1">
                            <small>
                                Halaman <?= $page ?> dari <?= $total_pages ?>
                                (<?= number_format($total) ?> total record)
                            </small>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

            </div>
        </section>
    </div>

    <footer class="main-footer">
        <strong>Copyright &copy; 2022 <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block"><b>Version</b> 1.0</div>
    </footer>
    <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<script>
$(document).ready(function() {
    $('#tabel-log').DataTable({
        responsive:  true,
        autoWidth:   false,
        pageLength:  20,
        order:       [[1, 'desc']],
        columnDefs:  [{ orderable: false, targets: [4, 7] }],
        language: {
            search:      "Cari:",
            lengthMenu:  "Tampilkan _MENU_ data",
            info:        "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            zeroRecords: "Tidak ada data",
            emptyTable:  "Tidak ada log",
            paginate: {
                first:    "Pertama",
                last:     "Terakhir",
                next:     "Berikutnya",
                previous: "Sebelumnya"
            }
        }
    });
});
</script>
</body>