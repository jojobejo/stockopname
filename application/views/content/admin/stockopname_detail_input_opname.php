<body class="hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="<?= base_url('assets/images/Karisma.png') ?>" alt="Karisma Logo" height="150" width="300">
    </div>
    <?php $this->load->view('partial/main/navbar') ?>
    <?php $this->load->view('partial/main/sidebar') ?>

    <?php
    $e = function ($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); };
    $format_expired_date = function ($value) {
        $value = trim((string)$value);
        if ($value === '') {
            return '-';
        }

        $source = substr($value, 0, 10);
        $date = DateTime::createFromFormat('!Y-m-d', $source);
        return $date && $date->format('Y-m-d') === $source ? $date->format('d/m/Y') : $value;
    };
    $input_rows = $input_rows ?? [];
    $master_items = $master_items ?? [];
    $master_item_options = $master_item_options ?? [];
    $pending_totals = $pending_totals ?? [];
    $recycle_rows = $recycle_rows ?? [];
    $request_rows = $request_rows ?? [];
    $edit_logs = $edit_logs ?? [];
    $compare = $compare ?? [];
    $nama_barang = $compare['nama_barang'] ?? ($master_item_options[0]['nama_barang'] ?? ($master_items[0]['nama_barang'] ?? ($input_rows[0]['nama_barang'] ?? '-')));
    $dimensi_barang = (int)($master_item_options[0]['dimensi'] ?? ($input_rows[0]['dimensi'] ?? 0));
    $qty_buku = (int)($compare['qty_buku'] ?? array_sum(array_column($master_items, 'qty')));
    $qty_tim_1 = (int)($compare['qty_tim_1'] ?? 0);
    $qty_tim_2 = (int)($compare['qty_tim_2'] ?? 0);
    $selisih_tim_1 = $qty_tim_1 - $qty_buku;
    $selisih_tim_2 = $qty_tim_2 - $qty_buku;
    $status = $compare['status_opname'] ?? 're_check';
    $status_label = ['all_match' => 'All Match', 'tim_1' => 'Tim 1 Match', 'tim_2' => 'Tim 2 Match', 're_check' => 'Re-Check'][$status] ?? 'Re-Check';
    $expired_key = function ($expired) { return trim((string)$expired); };
    $pending_key = function ($nama_barang, $expired) { return trim((string)$nama_barang) . '|' . trim((string)$expired); };
    $stock_by_expired = [];
    $stock_tab_counts = ['main' => 0, 'zero' => 0, 'all' => 0];
    foreach ($master_items as $row) {
        $key = $expired_key($row['expired_date'] ?? '');
        $row_book_qty = (int)($row['qty_buku'] ?? $row['qty'] ?? 0);
        $has_master_stock = (int)($row['master_id'] ?? $row['id'] ?? 0) > 0;
        $stock_by_expired[$key] = ($stock_by_expired[$key] ?? 0) + $row_book_qty;
        $stock_tab_counts['all']++;
        if ($has_master_stock && $row_book_qty > 0) {
            $stock_tab_counts['main']++;
        } elseif ($has_master_stock && $row_book_qty === 0) {
            $stock_tab_counts['zero']++;
        }
    }
    $source_label = function ($value) {
        $map = ['manual' => 'Manual Request', 'request' => 'Manual Request', 'manual input' => 'Manual Input', 'manual_input' => 'Manual Input', 'scan_qrcode' => 'Scan QRCode', 'request master item' => 'Request Master Item', 'manual opname request' => 'Manual Opname Request', 'master data request opname' => 'Master Data Request Opname', 'adjustment' => 'Adjustment', 'repost' => 'Repost', 'system' => 'System'];
        $parts = array_filter(array_map('trim', explode(',', strtolower((string)$value))));
        return implode(', ', array_map(function ($part) use ($map) { return $map[$part] ?? ucwords(str_replace('_', ' ', $part)); }, $parts)) ?: '-';
    };
    ?>

    <div class="content-wrapper so-detail-page">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-8">
                        <h1 class="m-0">Detail Input Opname</h1>
                        <div class="so-muted mt-1"><?= $e($kode_barang ?? '-') ?> - <?= $e($nama_barang) ?></div>
                    </div>
                    <div class="col-sm-4 text-sm-right mt-2 mt-sm-0">
                        <a href="<?= base_url('admin/stockopname/monitoring') ?>" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left"></i> Monitoring</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid pb-4">
                <style>
                    .so-detail-page{background:#f5f7fb}.so-muted{color:#64748b;font-size:12px}.so-panel{background:#fff;border:1px solid #e1e7ef;border-radius:8px;box-shadow:0 8px 22px rgba(16,24,40,.06);overflow:hidden}.so-panel-header{padding:14px 16px;border-bottom:1px solid #e8edf3;display:flex;align-items:center;justify-content:space-between;gap:10px}.so-title{font-weight:800;color:#1f2937;margin:0;font-size:16px}.so-header-actions{display:flex;align-items:center;gap:8px;flex-wrap:wrap}.so-summary{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:12px;margin-bottom:16px}.so-stat{background:#fff;border:1px solid #e1e7ef;border-radius:8px;padding:14px;box-shadow:0 8px 22px rgba(16,24,40,.05)}.so-stat.is-clickable{cursor:pointer;transition:border-color .15s,box-shadow .15s,transform .15s}.so-stat.is-clickable:hover{border-color:#60a5fa;box-shadow:0 10px 26px rgba(37,99,235,.14);transform:translateY(-1px)}.so-stat-label{font-size:11px;text-transform:uppercase;color:#64748b;font-weight:800}.so-stat-value{font-size:24px;font-weight:850;color:#111827;line-height:1.1;margin-top:7px}.so-stat-detail{font-size:12px;color:#334155;font-weight:700;line-height:1.35;margin-top:9px}.so-code{font-family:monospace;font-size:12px;background:#f8fafc;border:1px solid #dbe5ef;border-radius:6px;padding:4px 7px}.so-badge{display:inline-flex;border-radius:999px;padding:4px 9px;font-size:12px;font-weight:800;background:#fee2e2;color:#991b1b}.so-badge.all_match{background:#dcfce7;color:#166534}.so-badge.tim_1{background:#dbeafe;color:#1d4ed8}.so-badge.tim_2{background:#ede9fe;color:#6d28d9}.so-layout{display:grid;grid-template-columns:minmax(360px,40%) minmax(0,60%);gap:14px}.so-stack{display:grid;gap:14px;align-content:start}.so-bottom{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:14px}.so-empty{color:#64748b;text-align:center;padding:28px 12px}.so-table{font-size:12px;white-space:nowrap;margin:0}.so-table th{background:#f8fafc;text-align:center}.so-table td,.so-table th{vertical-align:middle;padding:.42rem}.so-cell-main{font-weight:800;color:#1f2937}.so-cell-sub{font-size:11px;color:#64748b;margin-top:2px}.so-action-btn{width:30px;height:30px;padding:0;display:inline-flex;align-items:center;justify-content:center}.so-action-btn i{margin:0}.so-input-row{cursor:pointer}.so-input-row.is-selected{background:#eff6ff;box-shadow:inset 3px 0 #2563eb}.so-log-list{display:grid;gap:8px;max-height:360px;overflow:auto}.so-log-item{border:1px solid #e1e7ef;border-radius:8px;background:#f8fafc;padding:10px}.so-log-title{font-size:13px;font-weight:800}.so-log-meta{font-size:11px;color:#64748b;margin-top:3px}.so-edit-modal{border:0;border-radius:8px;overflow:hidden}.so-edit-modal .modal-header{background:#1f2937;color:#fff}.so-edit-modal .close{color:#fff;text-shadow:none}.so-field-card{background:#fff;border:1px solid #e1e7ef;border-radius:8px;padding:12px;height:100%}.so-field-card label{font-size:11px;text-transform:uppercase;font-weight:850;color:#64748b}.so-field-card.is-editable{border-color:#93c5fd;background:#eff6ff}.so-field-card.is-editable .form-control{font-size:22px;font-weight:850}.so-modal-context{background:#f8fafc;border:1px solid #e1e7ef;border-radius:8px;padding:12px;margin-bottom:14px}.so-loading{opacity:.55;pointer-events:none}.so-toast{position:fixed;right:20px;top:70px;z-index:9999;min-width:280px;max-width:420px;padding:12px 16px;border-radius:8px;color:#fff;box-shadow:0 10px 30px rgba(0,0,0,.2);display:none}.so-toast.success{background:#15803d}.so-toast.error{background:#b91c1c}.so-lot-status{display:inline-flex;padding:3px 7px;border-radius:999px;font-size:10px;font-weight:850;background:#e2e8f0;color:#475569}.so-lot-status.done{background:#dcfce7;color:#166534}.so-lot-status.partial{background:#dbeafe;color:#1d4ed8}.so-lot-status.diff{background:#fee2e2;color:#991b1b}.so-team-tabs{display:flex;border-bottom:1px solid #e1e7ef;background:#f8fafc;padding:0 14px}.so-team-tab{border:0;background:transparent;padding:11px 18px;font-weight:850;color:#64748b;border-bottom:3px solid transparent}.so-team-tab.is-active{color:#2563eb;border-bottom-color:#2563eb;background:#fff}.so-filter-empty{display:none}
                    .so-control-locked{pointer-events:none;background:#f8fafc!important;color:#475569}.so-field-card.is-locked{border-color:#dbe5ef;background:#f8fafc}.so-stock-tabs{display:flex;align-items:center;gap:6px;flex-wrap:wrap}.so-stock-tab{border:1px solid #dbe5ef;background:#fff;color:#475569;border-radius:999px;padding:5px 10px;font-size:11px;font-weight:850}.so-stock-tab.is-active{border-color:#2563eb;background:#eff6ff;color:#1d4ed8}
                    @media(max-width:992px){.so-summary{grid-template-columns:repeat(2,1fr)}.so-layout,.so-bottom{grid-template-columns:1fr}}@media(max-width:576px){.so-summary{grid-template-columns:1fr}.so-panel-header{align-items:flex-start;flex-direction:column}}
                </style>
                <div id="soToast" class="so-toast"></div>

                <div id="soDynamicContent">
                    <div class="so-summary">
                        <div class="so-stat is-clickable" id="cardKodeBarang" title="Edit dimensi barang"><div class="so-stat-label">Kode Barang</div><div class="so-stat-value"><span class="so-code"><?= $e($kode_barang) ?></span></div><div class="so-stat-detail"><?= $e($nama_barang) ?><br>Dimensi <?= number_format($dimensi_barang, 0, ',', '.') ?></div></div>
                        <div class="so-stat"><div class="so-stat-label">Stock Buku</div><div class="so-stat-value"><?= number_format($qty_buku, 0, ',', '.') ?></div></div>
                        <div class="so-stat"><div class="so-stat-label">Qty Tim 1</div><div class="so-stat-value"><?= number_format($qty_tim_1, 0, ',', '.') ?></div><div class="so-stat-detail">Selisih <?= number_format($selisih_tim_1, 0, ',', '.') ?></div></div>
                        <div class="so-stat"><div class="so-stat-label">Qty Tim 2</div><div class="so-stat-value"><?= number_format($qty_tim_2, 0, ',', '.') ?></div><div class="so-stat-detail">Selisih <?= number_format($selisih_tim_2, 0, ',', '.') ?></div></div>
                        <div class="so-stat"><div class="so-stat-label">Status</div><div class="so-stat-value"><span class="so-badge <?= $e($status) ?>"><?= $e($status_label) ?></span></div></div>
                    </div>

                    <div class="so-layout">
                        <div class="so-stack">
                            <div class="so-panel">
                                <div class="so-panel-header">
                                    <h2 class="so-title">Stock Buku Per Expired Date</h2>
                                    <div class="so-stock-tabs" id="stockBookTabs">
                                        <button type="button" class="so-stock-tab is-active js-stock-tab" data-stock-tab="main">Utama <span class="js-stock-count" data-count-tab="main"><?= (int)$stock_tab_counts['main'] ?></span></button>
                                        <button type="button" class="so-stock-tab js-stock-tab" data-stock-tab="zero">Stock Buku 0 <span class="js-stock-count" data-count-tab="zero"><?= (int)$stock_tab_counts['zero'] ?></span></button>
                                        <button type="button" class="so-stock-tab js-stock-tab" data-stock-tab="all">Semua <span class="js-stock-count" data-count-tab="all"><?= (int)$stock_tab_counts['all'] ?></span></button>
                                        <button type="button" class="btn btn-outline-primary btn-sm js-bulk-check-stock" title="Centang semua item pada tab aktif"><i class="fas fa-check-square"></i> Centang Semua</button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm js-bulk-uncheck-stock" title="Kosongkan centang pada tab aktif"><i class="far fa-square"></i></button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <?php if (!$master_items) : ?><div class="so-empty">Data stock buku tidak ditemukan.</div><?php else : ?>
                                    <table class="table table-bordered table-hover so-table">
                                        <thead><tr><th>Expired Date</th><th>Qty Buku</th><th>Pending</th><th>Tim 1</th><th>Tim 2</th><th>Status</th><th>Pilih</th><th>Hapus</th></tr></thead>
                                        <tbody><?php foreach ($master_items as $row) :
                                            $book_qty = (int)($row['qty_buku'] ?? $row['qty'] ?? 0);
                                            $pending_row = $pending_totals[$pending_key($row['nama_barang'] ?? $nama_barang, $row['expired_date'] ?? '')] ?? ['qty' => 0];
                                            $pending_qty = (int)($pending_row['qty'] ?? 0);
                                            $team_1_qty = (int)($row['qty_tim_1'] ?? 0);
                                            $team_2_qty = (int)($row['qty_tim_2'] ?? 0);
                                            $team_1_has_input = (int)($row['input_tim_1'] ?? 0) > 0;
                                            $team_2_has_input = (int)($row['input_tim_2'] ?? 0) > 0;
                                            $team_1_ok = $team_1_has_input && $team_1_qty === $book_qty;
                                            $team_2_ok = $team_2_has_input && $team_2_qty === $book_qty;
                                            $has_master_stock = (int)($row['master_id'] ?? $row['id'] ?? 0) > 0;
                                            if ($has_master_stock && $book_qty > 0) { $stock_tab = 'main'; }
                                            elseif ($has_master_stock && $book_qty === 0) { $stock_tab = 'zero'; }
                                            else { $stock_tab = 'other'; }
                                            if ($team_1_ok && $team_2_ok) { $lot_status = 'All Match'; $lot_status_class = 'done'; }
                                            elseif ($team_1_ok) { $lot_status = 'Tim 1'; $lot_status_class = 'partial'; }
                                            elseif ($team_2_ok) { $lot_status = 'Tim 2'; $lot_status_class = 'partial'; }
                                            else { $lot_status = 'Not Match'; $lot_status_class = 'diff'; }
                                            $filter_key = $expired_key($row['expired_date'] ?? '');
                                        ?>
                                            <tr class="js-stock-book-row" data-stock-tab="<?= $e($stock_tab) ?>"><td><?= $e($format_expired_date($row['expired_date'] ?? '')) ?></td><td class="text-right"><?= number_format($book_qty, 0, ',', '.') ?></td><td class="text-right"><?= number_format($pending_qty, 0, ',', '.') ?></td><td class="text-right"><?= number_format($team_1_qty, 0, ',', '.') ?></td><td class="text-right"><?= number_format($team_2_qty, 0, ',', '.') ?></td><td class="text-center"><span class="so-lot-status <?= $lot_status_class ?>"><?= $lot_status ?></span></td><td class="text-center"><input type="checkbox" class="js-lot-filter" data-key="<?= $e($filter_key) ?>" title="Tampilkan hasil input expired date ini"></td><td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm so-action-btn js-delete-master-item" data-expired="<?= $e($row['expired_date'] ?? '') ?>" title="Hapus stock buku"><i class="fas fa-trash"></i></button></td></tr>
                                        <?php endforeach ?></tbody>
                                    </table><?php endif ?>
                                    <div class="so-empty so-filter-empty" id="stockBookFilterEmpty">Data tidak ditemukan untuk tab ini.</div>
                                </div>
                            </div>

                            <div class="so-panel">
                                <div class="so-panel-header"><h2 class="so-title">Request Item</h2><span class="so-muted"><?= count($request_rows) ?> group</span></div>
                                <div class="table-responsive">
                                    <?php if (!$request_rows) : ?><div class="so-empty">Belum ada request item untuk barang ini.</div><?php else : ?>
                                    <table class="table table-bordered table-hover so-table js-request-table">
                                        <thead><tr><th>Expired Date</th><th>Qty</th><th>Qty PCS</th><th>Qty Box</th><th>Input By</th><th>Input Source</th><th>#</th></tr></thead>
                                        <tbody><?php foreach ($request_rows as $index => $row) : ?>
                                            <?php $request_json = htmlspecialchars(json_encode($row, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8'); ?>
                                            <tr class="js-request-row" data-index="<?= $index ?>"><td><?= $e($format_expired_date($row['expired_date'] ?? '')) ?></td><td class="text-right"><?= number_format((int)$row['qty'], 0, ',', '.') ?></td><td class="text-right"><?= number_format((int)$row['qty_pcs'], 0, ',', '.') ?></td><td class="text-right"><?= number_format((int)$row['qty_box'], 0, ',', '.') ?></td><td><?= $e($row['input_by']) ?></td><td><?= $e($source_label($row['input_source'] ?? 'manual')) ?></td><td class="text-center"><div class="d-flex justify-content-center" style="gap:5px"><button type="button" class="btn btn-outline-success btn-sm so-action-btn js-add-request" data-row="<?= $request_json ?>" title="Tambah ke hasil opname"><i class="fas fa-plus"></i></button><button type="button" class="btn btn-outline-danger btn-sm so-action-btn js-delete-request" data-row="<?= $request_json ?>" title="Hapus request item"><i class="fas fa-trash"></i></button></div></td></tr>
                                        <?php endforeach ?></tbody>
                                    </table>
                                    <div class="p-2 text-center js-request-pagination"></div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>

                        <div class="so-panel">
                            <div class="so-panel-header">
                                <div><h2 class="so-title">Data Hasil Input Opname</h2><div class="so-muted mt-1">Data input per tim dan expired date.</div></div>
                                <div class="so-header-actions"><span class="so-muted"><?= count($input_rows) ?> input</span><button type="button" class="btn btn-primary btn-sm" id="btnInputOpname"><i class="fas fa-plus"></i> Input Opname</button></div>
                            </div>
                            <div class="so-team-tabs"><button type="button" class="so-team-tab is-active js-team-tab" data-team="1">Tim 1</button><button type="button" class="so-team-tab js-team-tab" data-team="2">Tim 2</button></div>
                            <div class="table-responsive">
                                <?php if (!$input_rows) : ?><div class="so-empty">Belum ada hasil input opname.</div><?php else : ?>
                                <table class="table table-bordered table-hover so-table">
                                    <thead><tr><th>Input</th><th>Expired Date</th><th>Qty Buku</th><th>Qty</th><th>Qty Box</th><th>Qty PCS</th><th>#</th></tr></thead>
                                    <tbody><?php foreach ($input_rows as $row) :
                                        $row_json = htmlspecialchars(json_encode($row, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
                                        $book = $stock_by_expired[$expired_key($row['expired_date'] ?? '')] ?? 0;
                                    ?>
                                        <tr class="so-input-row js-select-opname" data-row="<?= $row_json ?>" data-team="<?= (int)($row['tim_opname'] ?? 0) ?>" data-key="<?= $e($expired_key($row['expired_date'] ?? '')) ?>">
                                            <td><div class="so-cell-main"><?= $e($row['input_by'] ?? '-') ?></div><div class="so-cell-sub">Tim <?= $e($row['tim_opname'] ?? '-') ?> | <?= $e($row['created_at'] ?? $row['input_at'] ?? '-') ?></div></td>
                                            <td><div class="so-cell-main"><?= $e($format_expired_date($row['expired_date'] ?? '')) ?></div></td>
                                            <td class="text-right"><?= number_format($book, 0, ',', '.') ?></td><td class="text-right font-weight-bold"><?= number_format((int)$row['qty'], 0, ',', '.') ?></td><td class="text-right"><?= number_format((int)$row['qty_box'], 0, ',', '.') ?></td><td class="text-right"><?= number_format((int)$row['qty_pcs'], 0, ',', '.') ?></td>
                                            <td class="text-center"><div class="d-flex justify-content-center" style="gap:5px"><button type="button" class="btn btn-outline-primary btn-sm so-action-btn js-edit-opname" title="Edit Qty"><i class="fas fa-edit"></i></button><button type="button" class="btn btn-outline-danger btn-sm so-action-btn js-delete-opname" title="Delete"><i class="fas fa-trash"></i></button></div></td>
                                        </tr>
                                    <?php endforeach ?></tbody>
                                </table><div class="p-2 text-center js-input-pagination"></div><div class="so-empty so-filter-empty" id="inputFilterEmpty">Pilih expired date pada Stock Buku untuk menampilkan data tim.</div><?php endif ?>
                            </div>
                        </div>
                    </div>

                    <div class="so-bottom">
                        <div class="so-panel">
                            <div class="so-panel-header"><h2 class="so-title">Recycle Bin Input Opname</h2><span class="so-muted"><?= count($recycle_rows) ?> data</span></div>
                            <div class="table-responsive">
                                <?php if (!$recycle_rows) : ?><div class="so-empty">Recycle bin kosong.</div><?php else : ?>
                                <table class="table table-bordered table-hover so-table">
                                    <thead><tr><th>Input</th><th>Expired Date</th><th>Qty</th><th>Qty Box</th><th>Qty PCS</th><th>#</th></tr></thead>
                                    <tbody><?php foreach ($recycle_rows as $row) : ?>
                                        <tr><td><div class="so-cell-main"><?= $e($row['input_by']) ?></div><div class="so-cell-sub">Dihapus <?= $e($row['deleted_by']) ?> | <?= $e($row['deleted_at']) ?></div></td><td><div class="so-cell-main"><?= $e($format_expired_date($row['expired_date'] ?? '')) ?></div></td><td class="text-right"><?= number_format((int)$row['qty'], 0, ',', '.') ?></td><td class="text-right"><?= number_format((int)$row['qty_box'], 0, ',', '.') ?></td><td class="text-right"><?= number_format((int)$row['qty_pcs'], 0, ',', '.') ?></td><td class="text-center"><button type="button" class="btn btn-outline-success btn-sm so-action-btn js-repost-opname" data-id="<?= (int)$row['id'] ?>" title="Repost"><i class="fas fa-undo"></i></button></td></tr>
                                    <?php endforeach ?></tbody>
                                </table><?php endif ?>
                            </div>
                        </div>

                        <div class="so-panel">
                            <div class="so-panel-header"><h2 class="so-title">Log Perubahan Input Opname</h2><span class="so-muted"><?= count($edit_logs) ?> aktivitas terakhir</span></div>
                            <div class="p-3">
                                <?php if (!$edit_logs) : ?><div class="so-empty">Belum ada perubahan data input opname.</div><?php else : ?>
                                <div class="so-log-list"><?php foreach ($edit_logs as $log) :
                                    $action = $log['action_type'] ?? $log['action'] ?? 'EDIT_QTY';
                                    $fields = json_decode((string)($log['changed_fields'] ?? '[]'), true);
                                    $actor = $log['created_by'] ?? $log['changed_by'] ?? '-';
                                    $time = $log['created_at'] ?? $log['changed_at'] ?? '-';
                                ?>
                                    <div class="so-log-item"><div class="so-log-title"><?= $e($action) ?> #<?= $e($log['opname_id'] ?? '-') ?> oleh <?= $e($actor) ?></div><div class="so-log-meta"><?= $e($log['description'] ?? ('Field: ' . (is_array($fields) ? implode(', ', $fields) : '-'))) ?> | <?= $e($time) ?> | IP <?= $e($log['ip_address'] ?? '-') ?></div></div>
                                <?php endforeach ?></div><?php endif ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="modalEditOpname" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form class="modal-content so-edit-modal" id="formEditOpname">
                <div class="modal-header"><h5 class="modal-title" id="editModalTitle">Edit Qty Input Opname</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="editOpnameAlert"></div>
                    <input type="hidden" name="id" id="edit_id"><input type="hidden" name="kode_barang" value="<?= $e($kode_barang) ?>"><input type="hidden" name="action_type" id="edit_action_type" value="EDIT_QTY">
                    <div class="so-modal-context"><strong id="edit_nama_barang">-</strong><div class="so-muted"><span id="edit_expired_text">-</span> | Dimensi <span id="edit_dimensi_text">0</span></div></div>
                    <div class="row">
                        <div class="col-md-4"><div class="so-field-card mb-3"><label>Qty Total</label><input type="number" class="form-control" id="edit_qty" readonly></div></div>
                        <div class="col-md-4"><div class="so-field-card is-editable mb-3"><label>Qty Box</label><input type="number" class="form-control" name="qty_box" id="edit_qty_box" min="0" step="1" required></div></div>
                        <div class="col-md-4"><div class="so-field-card is-editable mb-3"><label>Qty PCS</label><input type="number" class="form-control" name="qty_pcs" id="edit_qty_pcs" min="0" step="1" required></div></div>
                    </div>
                    <div class="so-muted">Qty Total dihitung otomatis: Qty Box x Dimensi + Qty PCS.</div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary" id="btnSaveEditOpname"><i class="fas fa-save"></i> Simpan</button></div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalAddRequest" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form class="modal-content so-edit-modal" id="formAddRequest">
                <div class="modal-header"><h5 class="modal-title">Input Request Item</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="addRequestAlert"></div>
                    <input type="hidden" name="manual_master_id" id="request_manual_master_id">
                    <input type="hidden" name="kode_barang" id="request_kode_barang">
                    <input type="hidden" name="expired_date" id="request_expired_date">
                    <div class="so-modal-context"><strong id="request_nama_barang">-</strong><div class="so-muted"><span id="request_expired_text">-</span> | Dimensi <span id="request_dimensi_text">0</span></div></div>
                    <div class="row">
                        <div class="col-md-3"><div class="so-field-card is-locked mb-3"><label>Tim Opname</label><input type="hidden" name="tim_opname" id="request_tim_opname_value"><select class="form-control so-control-locked" id="request_tim_opname" disabled><option value="1">Tim 1</option><option value="2">Tim 2</option></select></div></div>
                        <div class="col-md-3"><div class="so-field-card mb-3"><label>Qty Total</label><input type="number" class="form-control" id="request_qty" readonly></div></div>
                        <div class="col-md-3"><div class="so-field-card is-locked mb-3"><label>Qty Box</label><input type="number" class="form-control" name="qty_box" id="request_qty_box" min="0" step="1" readonly></div></div>
                        <div class="col-md-3"><div class="so-field-card is-locked mb-3"><label>Qty PCS</label><input type="number" class="form-control" name="qty_pcs" id="request_qty_pcs" min="0" step="1" readonly></div></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button><button type="submit" class="btn btn-success" id="btnSaveRequest"><i class="fas fa-plus"></i> Tambah ke Opname</button></div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalInputOpname" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form class="modal-content so-edit-modal" id="formInputOpnameDetail">
                <div class="modal-header"><h5 class="modal-title">Input Opname</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="inputOpnameAlert"></div>
                    <input type="hidden" name="kode_barang" value="<?= $e($kode_barang) ?>">
                    <div class="so-modal-context"><strong><?= $e($nama_barang) ?></strong><div class="so-muted"><?= $e($kode_barang) ?> | Dimensi <span id="input_dimensi_text"><?= number_format($dimensi_barang, 0, ',', '.') ?></span></div></div>
                    <div class="row">
                        <div class="col-md-6"><div class="so-field-card mb-3"><label>Expired Date</label><select class="form-control" name="master_id" id="input_master_id" required></select></div></div>
                        <div class="col-md-3"><div class="so-field-card mb-3"><label>Tim Opname</label><select class="form-control" name="tim_opname" id="input_tim_opname" required><option value="1">Tim 1</option><option value="2">Tim 2</option></select></div></div>
                        <div class="col-md-3"><div class="so-field-card mb-3"><label>Qty Total</label><input type="number" class="form-control" id="input_qty" readonly></div></div>
                        <div class="col-md-6"><div class="so-field-card is-editable mb-3"><label>Qty Box</label><input type="number" class="form-control" name="qty_box" id="input_qty_box" min="0" step="1" required></div></div>
                        <div class="col-md-6"><div class="so-field-card is-editable mb-3"><label>Qty PCS</label><input type="number" class="form-control" name="qty_pcs" id="input_qty_pcs" min="0" step="1" required></div></div>
                        <div class="col-md-12"><div class="so-field-card is-editable mb-3"><label>Keterangan</label><input type="text" class="form-control" name="keterangan" id="input_keterangan" maxlength="80" required></div></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary" id="btnSaveInputOpname"><i class="fas fa-save"></i> Simpan Input</button></div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalEditBarang" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form class="modal-content so-edit-modal" id="formEditBarang">
                <div class="modal-header"><h5 class="modal-title">Edit Data Barang</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="editBarangAlert"></div>
                    <input type="hidden" name="kode_barang" value="<?= $e($kode_barang) ?>">
                    <div class="so-modal-context"><strong><?= $e($nama_barang) ?></strong><div class="so-muted"><?= $e($kode_barang) ?></div></div>
                    <div class="so-field-card is-editable">
                        <label>Dimensi</label>
                        <input type="number" class="form-control" name="dimensi" id="barang_dimensi" min="0" step="1" value="<?= (int)$dimensi_barang ?>" required>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary" id="btnSaveBarang"><i class="fas fa-save"></i> Simpan</button></div>
            </form>
        </div>
    </div>

    <footer class="main-footer"><strong>Copyright &copy; 2022 <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.</strong> All rights reserved.</footer>
</div>

<script>
$(function () {
    var urls = {
        update: '<?= base_url('admin/stockopname/detail_input_opname/update') ?>',
        remove: '<?= base_url('admin/stockopname/detail_input_opname/delete') ?>',
        repost: '<?= base_url('admin/stockopname/detail_input_opname/repost') ?>'
        ,addRequest: '<?= base_url('admin/stockopname/detail_input_opname/add_request') ?>',
        deleteRequest: '<?= base_url('admin/stockopname/detail_input_opname/delete_request') ?>',
        addInput: '<?= base_url('admin/stockopname/detail_input_opname/add_input') ?>',
        deleteMasterItem: '<?= base_url('admin/stockopname/detail_input_opname/delete_master_item') ?>',
        updateDimensi: '<?= base_url('admin/stockopname/detail_input_opname/update_dimensi') ?>'
    };
    var pageKodeBarang = <?= json_encode((string)$kode_barang, JSON_UNESCAPED_UNICODE) ?>;
    var pageNamaBarang = <?= json_encode((string)$nama_barang, JSON_UNESCAPED_UNICODE) ?>;
    var productDimensi = <?= (int)$dimensi_barang ?>;
    var masterItemOptions = <?= json_encode(array_values($master_item_options), JSON_UNESCAPED_UNICODE) ?>;
    var selectedRow = null;
    var currentDimensi = 0;
    var requestDimensi = 0;
    var inputDimensi = productDimensi;
    var activeTeam = 1;
    var activeStockBookTab = 'main';
    var selectedLotKeys = {};
    var inputCurrentPage = 1;
    var inputPageSize = 10;

    function toast(message, success) {
        $('#soToast').stop(true, true).removeClass('success error').addClass(success ? 'success' : 'error').text(message).fadeIn(150).delay(2600).fadeOut(250);
    }
    function rowData(element) {
        try { return JSON.parse($(element).closest('.js-select-opname').attr('data-row') || '{}'); } catch (e) { return {}; }
    }
    function refreshWidgets(message) {
        $('#soDynamicContent').addClass('so-loading');
        $.get(window.location.href, { ajax_refresh: 1 }).done(function (html) {
            var fresh = $('<div>').append($.parseHTML(html)).find('#soDynamicContent').html();
            if (!fresh) { toast('Data tersimpan, tetapi widget gagal dimuat ulang.', false); return; }
            $('#soDynamicContent').html(fresh);
            selectedRow = null;
            initRequestPagination();
            restoreOpnameFilters();
            restoreStockBookTab();
            toast(message, true);
        }).fail(function () { toast('Data tersimpan, tetapi refresh widget gagal.', false); }).always(function () { $('#soDynamicContent').removeClass('so-loading'); });
    }
    function applyStockBookTab() {
        var visible = 0;
        $('.js-stock-book-row').each(function () {
            var tab = String($(this).attr('data-stock-tab') || '');
            var show = activeStockBookTab === 'all' || tab === activeStockBookTab;
            $(this).toggle(show);
            if (show) visible++;
        });
        $('#stockBookFilterEmpty').toggle(visible === 0);
    }
    function restoreStockBookTab() {
        $('.js-stock-tab').removeClass('is-active').filter('[data-stock-tab="' + activeStockBookTab + '"]').addClass('is-active');
        if (!$('.js-stock-tab.is-active').length) {
            activeStockBookTab = 'main';
            $('.js-stock-tab[data-stock-tab="main"]').addClass('is-active');
        }
        applyStockBookTab();
    }
    function applyOpnameFilters() {
        var checkedKeys = Object.keys(selectedLotKeys).filter(function (key) { return selectedLotKeys[key]; });
        var matchedRows = $();
        $('.js-select-opname').each(function () {
            var show = String($(this).attr('data-team')) === String(activeTeam) && checkedKeys.indexOf(String($(this).attr('data-key'))) !== -1;
            if (show) matchedRows = matchedRows.add(this);
        });
        $('.js-select-opname').hide();
        var total = matchedRows.length;
        var totalPages = Math.max(1, Math.ceil(total / inputPageSize));
        inputCurrentPage = Math.max(1, Math.min(inputCurrentPage, totalPages));
        matchedRows.slice((inputCurrentPage - 1) * inputPageSize, inputCurrentPage * inputPageSize).show();
        renderInputPagination(total, totalPages);
        $('#inputFilterEmpty').toggle(total === 0).text(
            checkedKeys.length ? 'Belum ada data opname Tim ' + activeTeam + ' untuk expired date yang dipilih.' : 'Pilih expired date pada Stock Buku untuk menampilkan data Tim ' + activeTeam + '.'
        );
    }
    function renderInputPagination(total, totalPages) {
        var holder = $('.js-input-pagination').empty();
        if (total <= inputPageSize) return;
        for (var i = 1; i <= totalPages; i++) {
            holder.append('<button type="button" class="btn btn-sm mr-1 ' + (i === inputCurrentPage ? 'btn-primary' : 'btn-outline-secondary') + ' js-input-page" data-page="' + i + '">' + i + '</button>');
        }
        holder.append('<span class="so-muted ml-2">Halaman ' + inputCurrentPage + ' dari ' + totalPages + ' | ' + total + ' data</span>');
    }
    function restoreOpnameFilters() {
        $('.js-lot-filter').each(function () {
            $(this).prop('checked', !!selectedLotKeys[String($(this).attr('data-key'))]);
        });
        $('.js-team-tab').removeClass('is-active').filter('[data-team="' + activeTeam + '"]').addClass('is-active');
        applyOpnameFilters();
    }
    function formatExpiredDate(value) {
        if (!value) return '-';
        var match = String(value).match(/^(\d{4})-(\d{2})-(\d{2})(?:[ T].*)?$/);
        return match ? match[3] + '/' + match[2] + '/' + match[1] : value;
    }
    function openEditor(row, actionType) {
        var box = parseInt(row.qty_box || 0, 10) || 0, pcs = parseInt(row.qty_pcs || 0, 10) || 0, qty = parseInt(row.qty || 0, 10) || 0;
        currentDimensi = parseInt(row.dimensi || 0, 10) || 0;
        if (currentDimensi <= 0 && box > 0) currentDimensi = Math.max(0, Math.floor((qty - pcs) / box));
        $('#edit_id').val(row.id || '');
        $('#edit_action_type').val(actionType);
        $('#editModalTitle').text(actionType === 'ADJUSTMENT' ? 'Adjustment Opname' : 'Edit Qty Input Opname');
        $('#edit_nama_barang').text(row.nama_barang || '-');
        $('#edit_expired_text').text(formatExpiredDate(row.expired_date)); $('#edit_dimensi_text').text(currentDimensi);
        $('#edit_qty_box').val(box); $('#edit_qty_pcs').val(pcs); $('#edit_qty').val(qty);
        $('#editOpnameAlert').addClass('d-none').text('');
        $('#modalEditOpname').modal('show');
    }
    function formatLotOption(row) {
        return formatExpiredDate(row.expired_date) + ' | Dimensi ' + (parseInt(row.dimensi || 0, 10) || 0);
    }
    function selectedInputMaster() {
        var id = String($('#input_master_id').val() || '');
        for (var i = 0; i < masterItemOptions.length; i++) {
            if (String(masterItemOptions[i].id || '') === id) return masterItemOptions[i];
        }
        return null;
    }
    function updateInputQtyTotal() {
        var box = parseInt($('#input_qty_box').val() || 0, 10) || 0, pcs = parseInt($('#input_qty_pcs').val() || 0, 10) || 0;
        $('#input_qty').val((box * inputDimensi) + pcs);
    }
    function openInputOpnameModal() {
        var select = $('#input_master_id').empty();
        if (!masterItemOptions.length) {
            select.append(new Option('Master barang belum tersedia', '', true, true));
        } else {
            $.each(masterItemOptions, function (_, row) {
                select.append(new Option(formatLotOption(row), row.id, false, false));
            });
            select.val(String(masterItemOptions[0].id || ''));
        }
        $('#input_tim_opname').val(String(activeTeam));
        $('#input_qty_box,#input_qty_pcs').val(0);
        $('#input_keterangan').val('');
        var row = selectedInputMaster();
        inputDimensi = parseInt((row && row.dimensi) || productDimensi || 0, 10) || 0;
        $('#input_dimensi_text').text(inputDimensi.toLocaleString('id-ID'));
        updateInputQtyTotal();
        $('#inputOpnameAlert').addClass('d-none').text('');
        $('#modalInputOpname').modal('show');
    }
    function initRequestPagination() {
        var rows = $('.js-request-row'), pageSize = 10, pages = Math.ceil(rows.length / pageSize);
        function showPage(page) {
            rows.hide().slice((page - 1) * pageSize, page * pageSize).show();
            $('.js-request-page').removeClass('btn-primary').addClass('btn-outline-secondary').filter('[data-page="' + page + '"]').removeClass('btn-outline-secondary').addClass('btn-primary');
        }
        var holder = $('.js-request-pagination').empty();
        if (pages > 1) for (var i = 1; i <= pages; i++) holder.append('<button type="button" class="btn btn-sm btn-outline-secondary js-request-page mr-1" data-page="' + i + '">' + i + '</button>');
        showPage(1);
    }
    initRequestPagination();
    restoreStockBookTab();
    applyOpnameFilters();

    $(document).on('click', '.js-stock-tab', function () {
        activeStockBookTab = String($(this).attr('data-stock-tab') || 'main');
        restoreStockBookTab();
    });
    $(document).on('click', '.js-bulk-check-stock', function () {
        var total = 0;
        $('.js-stock-book-row:visible .js-lot-filter').each(function () {
            var key = String($(this).attr('data-key') || '');
            if (key) selectedLotKeys[key] = true;
            total++;
        });
        inputCurrentPage = 1;
        restoreOpnameFilters();
        toast(total ? 'Semua item pada tab aktif sudah dicentang.' : 'Tidak ada item yang bisa dicentang pada tab ini.', !!total);
    });
    $(document).on('click', '.js-bulk-uncheck-stock', function () {
        var total = 0;
        $('.js-stock-book-row:visible .js-lot-filter').each(function () {
            var key = String($(this).attr('data-key') || '');
            if (key) selectedLotKeys[key] = false;
            total++;
        });
        inputCurrentPage = 1;
        restoreOpnameFilters();
        toast(total ? 'Centang pada tab aktif sudah dikosongkan.' : 'Tidak ada item yang bisa dikosongkan pada tab ini.', !!total);
    });
    $(document).on('change', '.js-lot-filter', function () {
        selectedLotKeys[String($(this).attr('data-key'))] = $(this).is(':checked');
        inputCurrentPage = 1;
        applyOpnameFilters();
    });
    $(document).on('click', '.js-delete-master-item', function () {
        var expired = $(this).attr('data-expired') || '';
        if (!window.confirm('Hapus stock buku expired ' + formatExpiredDate(expired) + ' beserta data opname terkait?')) return;
        $.post(urls.deleteMasterItem, {kode_barang: pageKodeBarang, expired_date: expired}, null, 'json').done(function (res) {
            if (!res || !res.status) { toast((res && res.message) || 'Hapus stock buku gagal.', false); return; }
            refreshWidgets(res.message || 'Data stock buku berhasil dihapus.');
        }).fail(function () { toast('Terjadi gangguan saat hapus stock buku.', false); });
    });
    $(document).on('click', '.js-team-tab', function () {
        activeTeam = parseInt($(this).attr('data-team'), 10) || 1;
        $('.js-team-tab').removeClass('is-active'); $(this).addClass('is-active');
        selectedRow = null; $('.js-select-opname').removeClass('is-selected');
        inputCurrentPage = 1;
        applyOpnameFilters();
    });
    $(document).on('click', '.js-input-page', function () {
        inputCurrentPage = parseInt($(this).attr('data-page'), 10) || 1;
        selectedRow = null; $('.js-select-opname').removeClass('is-selected');
        applyOpnameFilters();
    });

    $(document).on('click', '.js-select-opname', function (event) {
        if ($(event.target).closest('button').length) return;
        $('.js-select-opname').removeClass('is-selected'); $(this).addClass('is-selected'); selectedRow = rowData(this);
    });
    $(document).on('click', '.js-edit-opname', function () { openEditor(rowData(this), 'EDIT_QTY'); });
    $(document).on('click', '#btnInputOpname', function () { openInputOpnameModal(); });
    $(document).on('click', '#btnAdjustment', function () {
        if (!selectedRow) { toast('Pilih satu baris input opname terlebih dahulu.', false); return; }
        openEditor(selectedRow, 'ADJUSTMENT');
    });
    $(document).on('input', '#edit_qty_box, #edit_qty_pcs', function () {
        var box = parseInt($('#edit_qty_box').val() || 0, 10) || 0, pcs = parseInt($('#edit_qty_pcs').val() || 0, 10) || 0;
        $('#edit_qty').val((box * currentDimensi) + pcs);
    });
    $(document).on('submit', '#formEditOpname', function (event) {
        event.preventDefault();
        var button = $('#btnSaveEditOpname').prop('disabled', true), alertBox = $('#editOpnameAlert').addClass('d-none');
        $.post(urls.update, $(this).serialize(), null, 'json').done(function (res) {
            if (!res || !res.status) { alertBox.removeClass('d-none').text((res && res.message) || 'Gagal menyimpan perubahan.'); return; }
            $('#modalEditOpname').modal('hide'); refreshWidgets(res.message || 'Perubahan berhasil disimpan.');
        }).fail(function () { alertBox.removeClass('d-none').text('Terjadi gangguan saat menyimpan perubahan.'); }).always(function () { button.prop('disabled', false); });
    });
    $(document).on('change', '#input_master_id', function () {
        var row = selectedInputMaster();
        inputDimensi = parseInt((row && row.dimensi) || productDimensi || 0, 10) || 0;
        $('#input_dimensi_text').text(inputDimensi.toLocaleString('id-ID'));
        updateInputQtyTotal();
    });
    $(document).on('input', '#input_qty_box, #input_qty_pcs', function () { updateInputQtyTotal(); });
    $(document).on('submit', '#formInputOpnameDetail', function (event) {
        event.preventDefault();
        var button = $('#btnSaveInputOpname').prop('disabled', true), alertBox = $('#inputOpnameAlert').addClass('d-none');
        var row = selectedInputMaster();
        var filterKey = row ? (row.expired_date || '') : '';
        activeTeam = parseInt($('#input_tim_opname').val(), 10) || activeTeam;
        $.post(urls.addInput, $(this).serialize(), null, 'json').done(function (res) {
            if (!res || !res.status) { alertBox.removeClass('d-none').text((res && res.message) || 'Gagal menyimpan input opname.'); return; }
            if (filterKey) selectedLotKeys[filterKey] = true;
            $('#modalInputOpname').modal('hide'); refreshWidgets(res.message || 'Input opname berhasil ditambahkan.');
        }).fail(function () { alertBox.removeClass('d-none').text('Terjadi gangguan saat menyimpan input opname.'); }).always(function () { button.prop('disabled', false); });
    });
    $(document).on('click', '.js-delete-opname', function () {
        var row = rowData(this);
        if (!window.confirm('Hapus input opname ini dari database?')) return;
        $.post(urls.remove, {id: row.id, kode_barang: pageKodeBarang}, null, 'json').done(function (res) {
            if (!res || !res.status) { toast((res && res.message) || 'Delete gagal.', false); return; }
            refreshWidgets(res.message);
        }).fail(function () { toast('Terjadi gangguan saat delete input opname.', false); });
    });
    $(document).on('click', '.js-repost-opname', function () {
        var id = $(this).attr('data-id');
        if (!window.confirm('Kembalikan data ini ke hasil input opname?')) return;
        $.post(urls.repost, {id: id, kode_barang: pageKodeBarang}, null, 'json').done(function (res) {
            if (!res || !res.status) { toast((res && res.message) || 'Repost gagal.', false); return; }
            refreshWidgets(res.message);
        }).fail(function () { toast('Terjadi gangguan saat repost input opname.', false); });
    });
    $(document).on('click', '.js-add-request', function () {
        var row = {};
        try { row = JSON.parse($(this).attr('data-row') || '{}'); } catch (e) { row = {}; }
        var box = parseInt(row.qty_box || 0, 10) || 0, pcs = parseInt(row.qty_pcs || 0, 10) || 0, qty = parseInt(row.qty || 0, 10) || 0;
        requestDimensi = box > 0 ? Math.max(0, Math.floor((qty - pcs) / box)) : 0;
        if (requestDimensi <= 0) requestDimensi = parseInt(row.dimensi || 0, 10) || 0;
        var requestTeam = parseInt(row.tim_opname || 0, 10) || activeTeam;
        $('#request_manual_master_id').val(row.manual_master_id || row.id || '');
        $('#request_kode_barang').val(row.kode_barang || pageKodeBarang);
        $('#request_expired_date').val(row.expired_date || '');
        $('#request_nama_barang').text(row.nama_barang || pageKodeBarang);
        $('#request_expired_text').text(formatExpiredDate(row.expired_date)); $('#request_dimensi_text').text(requestDimensi);
        $('#request_tim_opname,#request_tim_opname_value').val(String(requestTeam));
        $('#request_qty_box').val(box); $('#request_qty_pcs').val(pcs); $('#request_qty').val(qty);
        $('#request_tim_opname').prop('disabled', true).attr('aria-disabled', 'true');
        $('#request_qty_box,#request_qty_pcs').prop('readonly', true).closest('.so-field-card').removeClass('is-editable').addClass('is-locked');
        $('#addRequestAlert').addClass('d-none').text('');
        $('#modalAddRequest').modal('show');
    });
    $(document).on('click', '.js-delete-request', function () {
        var row = {};
        try { row = JSON.parse($(this).attr('data-row') || '{}'); } catch (e) { row = {}; }
        if (!window.confirm('Hapus request item ini dari database?')) return;
        $.post(urls.deleteRequest, {
            manual_master_id: row.manual_master_id || row.id || '',
            kode_barang: row.kode_barang || pageKodeBarang,
            expired_date: row.expired_date || '',
        }, null, 'json').done(function (res) {
            if (!res || !res.status) { toast((res && res.message) || 'Hapus request item gagal.', false); return; }
            refreshWidgets(res.message || 'Request item berhasil dihapus.');
        }).fail(function () { toast('Terjadi gangguan saat hapus request item.', false); });
    });
    $(document).on('input', '#request_qty_box, #request_qty_pcs', function () {
        var box = parseInt($('#request_qty_box').val() || 0, 10) || 0, pcs = parseInt($('#request_qty_pcs').val() || 0, 10) || 0;
        $('#request_qty').val((box * requestDimensi) + pcs);
    });
    $(document).on('submit', '#formAddRequest', function (event) {
        event.preventDefault();
        var button = $('#btnSaveRequest').prop('disabled', true), alertBox = $('#addRequestAlert').addClass('d-none');
        var filterKey = $('#request_expired_date').val();
        activeTeam = parseInt($('#request_tim_opname_value').val(), 10) || 1;
        $.post(urls.addRequest, $(this).serialize(), null, 'json').done(function (res) {
            if (!res || !res.status) { alertBox.removeClass('d-none').text((res && res.message) || 'Gagal menambahkan request item.'); return; }
            selectedLotKeys[filterKey] = true;
            $('#modalAddRequest').modal('hide'); refreshWidgets(res.message);
        }).fail(function () { alertBox.removeClass('d-none').text('Terjadi gangguan saat menyimpan request item.'); }).always(function () { button.prop('disabled', false); });
    });
    $(document).on('click', '.js-request-page', function () {
        var page = parseInt($(this).attr('data-page'), 10) || 1, rows = $('.js-request-row'), size = 10;
        rows.hide().slice((page - 1) * size, page * size).show();
        $('.js-request-page').removeClass('btn-primary').addClass('btn-outline-secondary');
        $(this).removeClass('btn-outline-secondary').addClass('btn-primary');
    });
    $(document).on('click', '#cardKodeBarang', function () {
        $('#barang_dimensi').val(productDimensi);
        $('#editBarangAlert').addClass('d-none').text('');
        $('#modalEditBarang').modal('show');
    });
    $(document).on('submit', '#formEditBarang', function (event) {
        event.preventDefault();
        var button = $('#btnSaveBarang').prop('disabled', true), alertBox = $('#editBarangAlert').addClass('d-none');
        $.post(urls.updateDimensi, $(this).serialize(), null, 'json').done(function (res) {
            if (!res || !res.status) { alertBox.removeClass('d-none').text((res && res.message) || 'Gagal menyimpan dimensi barang.'); return; }
            productDimensi = parseInt((res.data && res.data.dimensi) || $('#barang_dimensi').val() || 0, 10) || 0;
            inputDimensi = productDimensi;
            $.each(masterItemOptions, function (_, row) { row.dimensi = productDimensi; });
            $('#modalEditBarang').modal('hide');
            refreshWidgets(res.message || 'Dimensi barang berhasil diperbarui.');
        }).fail(function () { alertBox.removeClass('d-none').text('Terjadi gangguan saat menyimpan dimensi barang.'); }).always(function () { button.prop('disabled', false); });
    });
});
</script>
