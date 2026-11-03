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
    $manual_logs = $manual_logs ?? [];
    $filters = $filters ?? ['tim' => 0, 'wilayah' => '', 'input_by' => ''];
    $manual_tim_options = $manual_tim_options ?? [1, 2];
    $manual_wilayah_options = $manual_wilayah_options ?? [];
    $manual_inputer_options = $manual_inputer_options ?? [];
    ?>

    <div class="content-wrapper so-manual-page">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-7">
                        <h1 class="m-0">Input Manual Opname User</h1>
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
                    .so-manual-page{background:#f5f7fb}
                    .sm-panel{background:#fff;border:1px solid #e1e7ef;border-radius:8px;box-shadow:0 8px 22px rgba(16,24,40,.06)}
                    .sm-panel-header{padding:14px 16px;border-bottom:1px solid #e8edf3;display:flex;align-items:center;justify-content:space-between;gap:12px}
                    .sm-title{font-weight:800;color:#1f2937;margin:0;font-size:16px}
                    .sm-muted{color:#64748b;font-size:12px}
                    .sm-code{font-family:monospace;font-size:12px;background:#f8fafc;border:1px solid #dbe5ef;border-radius:6px;padding:4px 7px;color:#334155;white-space:nowrap}
                    .sm-badge{display:inline-flex;align-items:center;border-radius:999px;padding:4px 9px;font-size:12px;font-weight:800;background:#dcfce7;color:#166534}
                    .sm-filter-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px}
                    .sm-filter-card{padding:16px}
                    .sm-label{display:block;font-size:12px;font-weight:700;color:#475569;margin-bottom:6px}
                    .sm-select{height:38px;border:1px solid #dbe5ef;border-radius:8px}
                    .sm-bulk-bar{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;padding:12px 16px;border-top:1px solid #e8edf3;background:#f8fafc}
                    .sm-check{width:16px;height:16px}
                    .sm-affirm-col{min-width:120px}
                    .table td,.table th{vertical-align:middle}.btn i{margin-right:5px}
                    @media(max-width:992px){.sm-filter-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
                    @media(max-width:768px){.content-header h1{font-size:22px}.sm-panel-header{align-items:flex-start;flex-direction:column}.sm-filter-grid{grid-template-columns:1fr}}
                </style>

                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="sm-panel">
                            <div class="sm-panel-header">
                                <div>
                                    <h2 class="sm-title">Daftar Input Manual Opname</h2>
                                    <div class="sm-muted"><?= number_format(count($manual_logs), 0, ',', '.') ?> data input manual ditampilkan</div>
                                </div>
                                <span class="sm-badge">Manual Input</span>
                            </div>
                            <div class="sm-filter-card border-bottom">
                                <form method="get">
                                    <div class="sm-filter-grid">
                                        <div>
                                            <label class="sm-label">Filter Tim</label>
                                            <select name="tim" class="form-control sm-select">
                                                <option value="">Semua Tim</option>
                                                <?php foreach ($manual_tim_options as $timOption) : ?>
                                                    <option value="<?= (int)$timOption ?>" <?= (int)($filters['tim'] ?? 0) === (int)$timOption ? 'selected' : '' ?>>Tim <?= (int)$timOption ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="sm-label">Filter Wilayah</label>
                                            <select name="wilayah" class="form-control sm-select">
                                                <option value="">Semua Wilayah</option>
                                                <?php foreach ($manual_wilayah_options as $wilayahOption) : ?>
                                                    <option value="<?= $so_e($wilayahOption) ?>" <?= (string)($filters['wilayah'] ?? '') === (string)$wilayahOption ? 'selected' : '' ?>><?= $so_e($wilayahOption) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="sm-label">Filter Inputer</label>
                                            <select name="input_by" class="form-control sm-select">
                                                <option value="">Semua Inputer</option>
                                                <?php foreach ($manual_inputer_options as $inputerOption) : ?>
                                                    <option value="<?= $so_e($inputerOption) ?>" <?= (string)($filters['input_by'] ?? '') === (string)$inputerOption ? 'selected' : '' ?>><?= $so_e($inputerOption) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="d-flex align-items-end">
                                            <div class="w-100 d-flex" style="gap:8px;">
                                                <button type="submit" class="btn btn-primary btn-sm flex-fill"><i class="fas fa-filter"></i> Terapkan</button>
                                                <a href="<?= base_url('admin/stockopname/monitoring/manual-opname') ?>" class="btn btn-outline-secondary btn-sm flex-fill"><i class="fas fa-undo"></i> Reset</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive p-3">
                                <form id="manualAffirmForm">
                                <table class="table table-sm table-bordered table-hover w-100">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width:40px"><input type="checkbox" id="checkAllManual" class="sm-check"></th>
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
                                            <th class="sm-affirm-col">Afirmasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($manual_logs)) : ?>
                                            <tr>
                                                <td colspan="12" class="text-center text-muted py-4">Belum ada input manual opname.</td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php foreach ($manual_logs as $row) : ?>
                                            <tr>
                                                <td class="text-center">
                                                    <input type="checkbox" name="manual_master_ids[]" value="<?= (int)($row['manual_master_id'] ?? 0) ?>" class="sm-check js-manual-check">
                                                </td>
                                                <td><?= $so_e($row['input_at'] ?? $row['created_at'] ?? '-') ?></td>
                                                <td><span class="sm-code"><?= $so_e($row['kode_barang'] ?? '-') ?></span></td>
                                                <td><?= $so_e($row['nama_barang'] ?? '-') ?></td>
                                                <td><?= $so_e($row['expired_date'] ?? '-') ?></td>
                                                <td class="text-right"><?= number_format((int)($row['qty_pcs'] ?? 0), 0, ',', '.') ?></td>
                                                <td class="text-right"><?= number_format((int)($row['qty_box'] ?? 0), 0, ',', '.') ?></td>
                                                <td class="text-right font-weight-bold"><?= number_format((int)($row['qty'] ?? 0), 0, ',', '.') ?></td>
                                                <td><?= $so_e($row['input_by'] ?? '-') ?></td>
                                                <td><span class="sm-badge"><?= $so_e($row['wilayah'] ?? '-') ?></span></td>
                                                <td><?= $so_e($row['tim_opname'] ?? '-') ?></td>
                                                <td><span class="badge badge-warning">Siap diafirmasi</span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                </form>
                            </div>
                            <div class="sm-bulk-bar">
                                <div class="sm-muted"><span id="manualSelectedCount">0</span> data dipilih untuk afirmasi manual input</div>
                                <button type="button" class="btn btn-success btn-sm" id="btnBulkAffirmManual"><i class="fas fa-check-circle"></i> Bulk Afirmasi Manual Input</button>
                            </div>
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
    (function ($) {
        function updateSelectedCount() {
            var total = $('.js-manual-check:checked').length;
            $('#manualSelectedCount').text(total);
        }

        $(document).on('change', '#checkAllManual', function () {
            $('.js-manual-check').prop('checked', $(this).is(':checked'));
            updateSelectedCount();
        });

        $(document).on('change', '.js-manual-check', function () {
            var total = $('.js-manual-check').length;
            var checked = $('.js-manual-check:checked').length;
            $('#checkAllManual').prop('checked', total > 0 && total === checked);
            updateSelectedCount();
        });

        $(document).on('click', '#btnBulkAffirmManual', function () {
            var checked = $('.js-manual-check:checked');
            if (!checked.length) {
                alert('Pilih minimal satu data manual input.');
                return;
            }
            if (!window.confirm('Afirmasi semua data manual input yang dipilih ke hasil opname?')) {
                return;
            }

            var button = $(this).prop('disabled', true);
            $.post('<?= base_url('admin/stockopname/ajax-affirm-manual-opname-bulk') ?>', $('#manualAffirmForm').serialize(), null, 'json')
                .done(function (res) {
                    if (!res || !res.status) {
                        alert((res && res.message) || 'Bulk afirmasi gagal.');
                        return;
                    }
                    alert(res.message || 'Bulk afirmasi berhasil.');
                    window.location.reload();
                })
                .fail(function () {
                    alert('Terjadi gangguan saat memproses bulk afirmasi.');
                })
                .always(function () {
                    button.prop('disabled', false);
                });
        });
    })(jQuery);
</script>
