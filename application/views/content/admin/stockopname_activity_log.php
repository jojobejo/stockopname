<body class="hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="Karisma Logo" height="150" width="300">
    </div>

    <?php $this->load->view('partial/main/navbar') ?>
    <?php $this->load->view('partial/main/sidebar') ?>

    <?php
    $so_e = function ($value) {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    };
    $activity_logs = $activity_logs ?? [];
    $activity_compare_rows = $activity_compare_rows ?? [];
    $selected_wilayah = $selected_wilayah ?? '';
    $selected_tim = (int)($selected_tim ?? 0);
    $wilayah_options = $wilayah_options ?? [];
    $format_expired_date = function ($value) {
        $value = trim((string)$value);
        if ($value === '') {
            return '-';
        }

        $source = substr($value, 0, 10);
        $date = DateTime::createFromFormat('!Y-m-d', $source);
        return $date && $date->format('Y-m-d') === $source ? $date->format('d/m/Y') : $value;
    };
    ?>

    <div class="content-wrapper so-activity-page">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-7">
                        <h1 class="m-0"><?= $selected_wilayah !== '' ? 'Detail Input Opname Wilayah ' . $so_e($selected_wilayah) : 'Log Aktifitas Stock Opname' ?><?= $selected_tim > 0 ? ' - Tim ' . $selected_tim : '' ?></h1>
                    </div>
                    <div class="col-sm-5 text-sm-right mt-2 mt-sm-0">
                        <a href="<?= base_url('admin/stockopname/monitoring') ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Monitoring
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <style>
                    .so-activity-page{background:#f5f7fb}
                    .sa-panel{background:#fff;border:1px solid #e1e7ef;border-radius:8px;box-shadow:0 8px 22px rgba(16,24,40,.06)}
                    .sa-panel-header{padding:14px 16px;border-bottom:1px solid #e8edf3;display:flex;align-items:center;justify-content:space-between;gap:12px}
                    .sa-title{font-weight:800;color:#1f2937;margin:0;font-size:16px}
                    .sa-muted{color:#64748b;font-size:12px}
                    .sa-filter{display:grid;grid-template-columns:minmax(180px,260px) minmax(120px,150px) auto auto;gap:8px;align-items:center}
                    .sa-code{font-family:monospace;font-size:12px;background:#f8fafc;border:1px solid #dbe5ef;border-radius:6px;padding:4px 7px;color:#334155;white-space:nowrap}
                    .sa-badge{display:inline-flex;align-items:center;border-radius:999px;padding:4px 9px;font-size:12px;font-weight:800;background:#eef2ff;color:#3730a3}
                    .sa-team{display:inline-flex;align-items:center;border-radius:999px;padding:4px 9px;font-size:12px;font-weight:800}.sa-team-1{background:#dcfce7;color:#166534}.sa-team-2{background:#fef3c7;color:#92400e}
                    .sa-tabs{display:flex;gap:8px;padding:12px 16px 0}.sa-tab{border:1px solid #dbe5ef;background:#fff;border-radius:6px;padding:7px 11px;font-size:12px;font-weight:800;color:#475569}.sa-tab.active{background:#2563eb;border-color:#2563eb;color:#fff}.sa-tab-content{display:none}.sa-tab-content.active{display:block}.sa-status{display:inline-flex;border-radius:999px;padding:4px 9px;font-size:11px;font-weight:850}.sa-status.same{background:#dcfce7;color:#166534}.sa-status.recheck{background:#fee2e2;color:#991b1b}
                    .table td,.table th{vertical-align:middle}.btn i{margin-right:5px}
                    @media(max-width:768px){.content-header h1{font-size:22px}.sa-panel-header{align-items:flex-start;flex-direction:column}.sa-filter{grid-template-columns:1fr;width:100%}}
                </style>

                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="sa-panel">
                            <div class="sa-panel-header">
                                <div>
                                    <h2 class="sa-title"><?= $selected_wilayah !== '' ? 'Data Input Wilayah ' . $so_e($selected_wilayah) : 'Daftar Log Aktifitas' ?></h2>
                                    <div class="sa-muted"><?= number_format(count($activity_logs), 0, ',', '.') ?> data input tercatat<?= $selected_wilayah !== '' ? ' di wilayah ' . $so_e($selected_wilayah) : '' ?><?= $selected_tim > 0 ? ' oleh Tim ' . $selected_tim : '' ?></div>
                                </div>
                                <form method="get" action="<?= base_url('admin/stockopname/monitoring/activity-log') ?>" class="sa-filter">
                                    <input list="wilayahList" type="text" name="wilayah" value="<?= $so_e($selected_wilayah) ?>" class="form-control form-control-sm" placeholder="Filter wilayah">
                                    <datalist id="wilayahList">
                                        <?php foreach ($wilayah_options as $row) : ?>
                                            <?php $wilayah = $row['wilayah'] ?? ''; ?>
                                            <?php if (trim((string)$wilayah) !== '') : ?>
                                                <option value="<?= $so_e($wilayah) ?>"></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </datalist>
                                    <select name="tim" class="form-control form-control-sm">
                                        <option value="">Semua Tim</option>
                                        <option value="1" <?= $selected_tim === 1 ? 'selected' : '' ?>>Tim 1</option>
                                        <option value="2" <?= $selected_tim === 2 ? 'selected' : '' ?>>Tim 2</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <a href="<?= base_url('admin/stockopname/monitoring/activity-log') ?>" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-times"></i> Reset
                                    </a>
                                </form>
                            </div>
                            <div class="sa-tabs">
                                <button type="button" class="sa-tab active" data-tab="activity">Log Aktivitas</button>
                                <button type="button" class="sa-tab" data-tab="compare">Compare Data Input Tim</button>
                            </div>
                            <div class="sa-tab-content active" data-panel="activity"><div class="table-responsive p-3">
                                <table class="table table-sm table-bordered table-hover w-100">
                                    <thead>
                                        <tr>
                                            <th>Waktu</th>
                                            <th>Kode</th>
                                            <th>Nama Barang</th>
                                            <th>Exp Date</th>
                                            <th class="text-right">Pcs</th>
                                            <th class="text-right">Box</th>
                                            <th class="text-right">Qty</th>
                                            <th>Input By</th>
                                            <th>Wilayah</th>
                                            <th>Tim</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($activity_logs)) : ?>
                                            <tr>
                                                <td colspan="10" class="text-center text-muted py-4">Belum ada log aktifitas untuk filter ini.</td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php foreach ($activity_logs as $row) : ?>
                                            <tr>
                                                <td><?= $so_e($row['created_at'] ?? $row['input_at'] ?? '-') ?></td>
                                                <td><span class="sa-code"><?= $so_e($row['kode_barang'] ?? '-') ?></span></td>
                                                <td><?= $so_e($row['nama_barang'] ?? '-') ?></td>
                                                <td><?= $so_e($format_expired_date($row['expired_date'] ?? '')) ?></td>
                                                <td class="text-right"><?= number_format((int)($row['qty_pcs'] ?? 0), 0, ',', '.') ?></td>
                                                <td class="text-right"><?= number_format((int)($row['qty_box'] ?? 0), 0, ',', '.') ?></td>
                                                <td class="text-right font-weight-bold"><?= number_format((int)($row['qty'] ?? 0), 0, ',', '.') ?></td>
                                                <td><?= $so_e($row['input_by'] ?? '-') ?></td>
                                                <td><span class="sa-badge"><?= $so_e($row['wilayah'] ?? '-') ?></span></td>
                                                <?php $tim = (int)($row['tim_opname'] ?? 0); ?>
                                                <td><span class="sa-team sa-team-<?= $tim === 2 ? '2' : '1' ?>">Tim <?= $tim > 0 ? $tim : '-' ?></span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div></div>
                            <div class="sa-tab-content" data-panel="compare"><div class="table-responsive p-3">
                                <?php if ($selected_wilayah === '') : ?>
                                    <div class="text-center text-muted py-4">Pilih wilayah terlebih dahulu untuk membandingkan input Tim 1 dan Tim 2.</div>
                                <?php else : ?>
                                <table class="table table-sm table-bordered table-hover w-100">
                                    <thead><tr><th>Kode</th><th>Nama Barang</th><th>Exp Date</th><th class="text-right">Qty Tim 1</th><th class="text-right">Qty Tim 2</th><th>Inputer Tim 1</th><th>Inputer Tim 2</th><th>Status</th></tr></thead>
                                    <tbody>
                                        <?php if (empty($activity_compare_rows)) : ?><tr><td colspan="8" class="text-center text-muted py-4">Belum ada data input untuk wilayah ini.</td></tr><?php endif; ?>
                                        <?php foreach ($activity_compare_rows as $row) : ?>
                                            <?php $same = ($row['status_compare'] ?? '') === 'DATA SAMA'; ?>
                                            <tr><td><span class="sa-code"><?= $so_e($row['kode_barang'] ?? '-') ?></span></td><td><?= $so_e($row['nama_barang'] ?? '-') ?></td><td><?= $so_e($format_expired_date($row['expired_date'] ?? '')) ?></td><td class="text-right font-weight-bold"><?= number_format((int)($row['qty_tim_1'] ?? 0), 0, ',', '.') ?></td><td class="text-right font-weight-bold"><?= number_format((int)($row['qty_tim_2'] ?? 0), 0, ',', '.') ?></td><td><?= $so_e($row['inputer_tim_1'] ?? '-') ?></td><td><?= $so_e($row['inputer_tim_2'] ?? '-') ?></td><td><span class="sa-status <?= $same ? 'same' : 'recheck' ?>"><?= $same ? 'DATA SAMA' : 'RE-CHECK' ?></span></td></tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php endif; ?>
                            </div></div>
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
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.sa-tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            var target = tab.getAttribute('data-tab');
            document.querySelectorAll('.sa-tab').forEach(function (item) { item.classList.toggle('active', item === tab); });
            document.querySelectorAll('.sa-tab-content').forEach(function (panel) { panel.classList.toggle('active', panel.getAttribute('data-panel') === target); });
        });
    });
});
</script>
