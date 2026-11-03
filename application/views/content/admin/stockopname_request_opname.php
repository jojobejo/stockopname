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
    $so_dt = function ($value) {
        $value = trim((string)$value);
        if ($value === '' || $value === '-') {
            return '-';
        }
        $timestamp = strtotime($value);
        return $timestamp ? date('d/m/Y H:i', $timestamp) : $value;
    };
    $so_date = function ($value) {
        $value = trim((string)$value);
        if ($value === '' || $value === '-') {
            return '-';
        }
        $timestamp = strtotime($value);
        return $timestamp ? date('d/m/Y', $timestamp) : $value;
    };
    $request_logs = $request_logs ?? [];
    $filters = $filters ?? ['tim' => 0, 'wilayah' => '', 'input_by' => ''];
    $request_tim_options = $request_tim_options ?? [1, 2];
    $request_wilayah_options = $request_wilayah_options ?? [];
    $request_inputer_options = $request_inputer_options ?? [];
    $request_active_tab = $request_active_tab ?? 'pending';
    $request_pending_count = (int)($request_pending_count ?? 0);
    $request_affirmed_count = (int)($request_affirmed_count ?? 0);
    $request_table_colspan = $request_active_tab === 'pending' ? 12 : 10;
    ?>

    <div class="content-wrapper so-request-page">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-7">
                        <h1 class="m-0">Request Opname User</h1>
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
                    .so-request-page{background:#f5f7fb}
                    .sr-panel{background:#fff;border:1px solid #e1e7ef;border-radius:8px;box-shadow:0 8px 22px rgba(16,24,40,.06)}
                    .sr-panel-header{padding:14px 16px;border-bottom:1px solid #e8edf3;display:flex;align-items:center;justify-content:space-between;gap:12px}
                    .sr-title{font-weight:800;color:#1f2937;margin:0;font-size:16px}
                    .sr-muted{color:#64748b;font-size:12px}
                    .sr-code{font-family:monospace;font-size:12px;background:#f8fafc;border:1px solid #dbe5ef;border-radius:6px;padding:4px 7px;color:#334155;white-space:nowrap}
                    .sr-badge{display:inline-flex;align-items:center;border-radius:999px;padding:4px 9px;font-size:12px;font-weight:800;background:#fffbeb;color:#92400e}
                    .sr-tabs{display:flex;gap:8px;flex-wrap:wrap;padding:14px 16px 0}
                    .sr-tab{border:1px solid #dbe5ef;border-bottom:none;border-radius:10px 10px 0 0;padding:9px 14px;font-weight:800;font-size:13px;background:#f8fafc;color:#475569;text-decoration:none}
                    .sr-tab.active{background:#fff;color:#0f172a}
                    .sr-tab .count{display:inline-flex;min-width:24px;height:24px;align-items:center;justify-content:center;border-radius:999px;margin-left:6px;background:#e2e8f0;color:#334155;font-size:12px}
                    .sr-tab.active .count{background:#dbeafe;color:#1d4ed8}
                    .sr-filter-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px}
                    .sr-filter-card{padding:16px}
                    .sr-label{display:block;font-size:12px;font-weight:700;color:#475569;margin-bottom:6px}
                    .sr-select{height:38px;border:1px solid #dbe5ef;border-radius:8px}
                    .sr-bulk-bar{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;padding:12px 16px;border-top:1px solid #e8edf3;background:#f8fafc}
                    .sr-check{width:16px;height:16px}
                    .table td,.table th{vertical-align:middle}.btn i{margin-right:5px}
                    @media(max-width:992px){.sr-filter-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
                    @media(max-width:768px){.content-header h1{font-size:22px}.sr-panel-header{align-items:flex-start;flex-direction:column}.sr-filter-grid{grid-template-columns:1fr}}
                </style>

                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="sr-panel">
                            <div class="sr-tabs">
                                <a href="<?= base_url('admin/stockopname/monitoring/request-opname?tab=pending' . ($filters['tim'] ? '&tim=' . (int)$filters['tim'] : '') . ($filters['wilayah'] !== '' ? '&wilayah=' . urlencode((string)$filters['wilayah']) : '') . ($filters['input_by'] !== '' ? '&input_by=' . urlencode((string)$filters['input_by']) : '')) ?>" class="sr-tab <?= $request_active_tab === 'pending' ? 'active' : '' ?>">
                                    Request Masuk <span class="count"><?= number_format($request_pending_count, 0, ',', '.') ?></span>
                                </a>
                                <a href="<?= base_url('admin/stockopname/monitoring/request-opname?tab=affirmed' . ($filters['tim'] ? '&tim=' . (int)$filters['tim'] : '') . ($filters['wilayah'] !== '' ? '&wilayah=' . urlencode((string)$filters['wilayah']) : '') . ($filters['input_by'] !== '' ? '&input_by=' . urlencode((string)$filters['input_by']) : '')) ?>" class="sr-tab <?= $request_active_tab === 'affirmed' ? 'active' : '' ?>">
                                    Sudah Diafirmasi <span class="count"><?= number_format($request_affirmed_count, 0, ',', '.') ?></span>
                                </a>
                            </div>
                            <div class="sr-panel-header">
                                <div>
                                    <h2 class="sr-title"><?= $request_active_tab === 'affirmed' ? 'Daftar Request Opname yang Sudah Diafirmasi' : 'Daftar Request Opname' ?></h2>
                                    <div class="sr-muted"><?= number_format(count($request_logs), 0, ',', '.') ?> data <?= $request_active_tab === 'affirmed' ? 'sudah diafirmasi' : 'request' ?> ditampilkan</div>
                                </div>
                                <span class="sr-badge"><?= $request_active_tab === 'affirmed' ? 'DONE / Afirmasi' : 'Request Master Item' ?></span>
                            </div>
                            <div class="sr-filter-card border-bottom">
                                <form method="get">
                                    <input type="hidden" name="tab" value="<?= $so_e($request_active_tab) ?>">
                                    <div class="sr-filter-grid">
                                        <div>
                                            <label class="sr-label">Filter Tim</label>
                                            <select name="tim" class="form-control sr-select">
                                                <option value="">Semua Tim</option>
                                                <?php foreach ($request_tim_options as $timOption) : ?>
                                                    <option value="<?= (int)$timOption ?>" <?= (int)($filters['tim'] ?? 0) === (int)$timOption ? 'selected' : '' ?>>Tim <?= (int)$timOption ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="sr-label">Filter Wilayah</label>
                                            <select name="wilayah" class="form-control sr-select">
                                                <option value="">Semua Wilayah</option>
                                                <?php foreach ($request_wilayah_options as $wilayahOption) : ?>
                                                    <option value="<?= $so_e($wilayahOption) ?>" <?= (string)($filters['wilayah'] ?? '') === (string)$wilayahOption ? 'selected' : '' ?>><?= $so_e($wilayahOption) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="sr-label">Filter Inputer</label>
                                            <select name="input_by" class="form-control sr-select">
                                                <option value="">Semua Inputer</option>
                                                <?php foreach ($request_inputer_options as $inputerOption) : ?>
                                                    <option value="<?= $so_e($inputerOption) ?>" <?= (string)($filters['input_by'] ?? '') === (string)$inputerOption ? 'selected' : '' ?>><?= $so_e($inputerOption) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="d-flex align-items-end">
                                            <div class="w-100 d-flex" style="gap:8px;">
                                                <button type="submit" class="btn btn-primary btn-sm flex-fill"><i class="fas fa-filter"></i> Terapkan</button>
                                                <a href="<?= base_url('admin/stockopname/monitoring/request-opname?tab=' . $so_e($request_active_tab)) ?>" class="btn btn-outline-secondary btn-sm flex-fill"><i class="fas fa-undo"></i> Reset</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive p-3">
                                <form id="requestAffirmForm">
                                <table class="table table-sm table-bordered table-hover w-100">
                                    <thead>
                                        <tr>
                                            <?php if ($request_active_tab === 'pending') : ?>
                                                <th class="text-center" style="width:40px"><input type="checkbox" id="checkAllRequest" class="sr-check"></th>
                                            <?php endif; ?>
                                            <th>Waktu</th>
                                            <th>Nama Barang</th>
                                            <th>Exp Date Request</th>
                                            <th class="text-center">Pcs</th>
                                            <th class="text-center">Box</th>
                                            <th class="text-center">Qty</th>
                                            <th>Wilayah</th>
                                            <?php if ($request_active_tab === 'pending') : ?>
                                                <th>Tim</th>
                                            <?php endif; ?>
                                            <th>Requested By</th>
                                            <th>Afirmasi</th>
                                            <th>Reviewed By</th>
                                            <th>Reviewed At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($request_logs)) : ?>
                                            <tr>
                                                <td colspan="<?= $request_table_colspan ?>" class="text-center text-muted py-4">Belum ada data pada tab ini.</td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php foreach ($request_logs as $row) : ?>
                                            <tr>
                                                <?php if ($request_active_tab === 'pending') : ?>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="request_ids[]" value="<?= (int)($row['id'] ?? 0) ?>" class="sr-check js-request-check">
                                                    </td>
                                                <?php endif; ?>
                                                <td><?= $so_dt($row['requested_at'] ?? $row['created_at'] ?? '-') ?></td>
                                                <td><?= $so_e($row['nama_barang'] ?? '-') ?></td>
                                                <td><?= $so_date($row['expired_date'] ?? '-') ?></td>
                                                <td class="text-center"><?= number_format((int)($row['qty_pcs'] ?? 0), 0, ',', '.') ?></td>
                                                <td class="text-center"><?= number_format((int)($row['qty_box'] ?? 0), 0, ',', '.') ?></td>
                                                <td class="text-center font-weight-bold"><?= number_format((int)($row['qty'] ?? 0), 0, ',', '.') ?></td>
                                                <td class="text-center"><span class="sr-badge"><?= $so_e($row['wilayah'] ?? '-') ?></span></td>
                                                <?php if ($request_active_tab === 'pending') : ?>
                                                    <td><?= $so_e($row['tim_opname'] ?? '-') ?></td>
                                                <?php endif; ?>
                                                <td><?= $so_e($row['requested_by'] ?? '-') ?></td>
                                                <td>
                                                    <?php if ($request_active_tab === 'pending') : ?>
                                                        <button type="button" class="btn btn-success btn-sm js-affirm-single" data-id="<?= (int)($row['id'] ?? 0) ?>">
                                                            <i class="fas fa-check-circle"></i> Afirmasi
                                                        </button>
                                                    <?php else : ?>
                                                        <span class="sr-badge" style="background:#dcfce7;color:#166534;">Sudah diafirmasi</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= $so_e($row['reviewed_by'] ?? '-') ?></td>
                                                <td><?= $so_dt($row['reviewed_at'] ?? '-') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                </form>
                            </div>
                            <?php if ($request_active_tab === 'pending') : ?>
                                <div class="sr-bulk-bar">
                                    <div class="sr-muted"><span id="requestSelectedCount">0</span> data dipilih untuk afirmasi request opname</div>
                                    <button type="button" class="btn btn-success btn-sm" id="btnBulkAffirmRequest"><i class="fas fa-check-circle"></i> Bulk Afirmasi Request</button>
                                </div>
                            <?php endif; ?>
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
            $('#requestSelectedCount').text($('.js-request-check:checked').length);
        }

        function postAffirmRequest(payload, button) {
            $.post('<?= base_url('admin/stockopname/ajax-affirm-request-opname-bulk') ?>', payload, null, 'json')
                .done(function (res) {
                    if (!res || !res.status) {
                        alert((res && res.message) || 'Afirmasi request gagal.');
                        return;
                    }
                    alert(res.message || 'Afirmasi request berhasil.');
                    window.location.reload();
                })
                .fail(function () {
                    alert('Terjadi gangguan saat memproses afirmasi request.');
                })
                .always(function () {
                    if (button) {
                        button.prop('disabled', false);
                    }
                });
        }

        <?php if ($request_active_tab === 'pending') : ?>
        $(document).on('change', '#checkAllRequest', function () {
            $('.js-request-check').prop('checked', $(this).is(':checked'));
            updateSelectedCount();
        });

        $(document).on('change', '.js-request-check', function () {
            var total = $('.js-request-check').length;
            var checked = $('.js-request-check:checked').length;
            $('#checkAllRequest').prop('checked', total > 0 && total === checked);
            updateSelectedCount();
        });

        $(document).on('click', '.js-affirm-single', function () {
            var button = $(this);
            var requestId = button.data('id') || 0;
            if (!requestId) {
                alert('Data request tidak valid.');
                return;
            }
            if (!window.confirm('Afirmasi request ini ke hasil opname?')) {
                return;
            }
            button.prop('disabled', true);
            postAffirmRequest({request_ids: [requestId]}, button);
        });

        $(document).on('click', '#btnBulkAffirmRequest', function () {
            var checked = $('.js-request-check:checked');
            var button = $(this);
            if (!checked.length) {
                alert('Pilih minimal satu data request opname.');
                return;
            }
            if (!window.confirm('Afirmasi semua data request opname yang dipilih ke hasil opname?')) {
                return;
            }
            button.prop('disabled', true);
            postAffirmRequest($('#requestAffirmForm').serialize(), button);
        });
        <?php endif; ?>
    })(jQuery);
</script>
