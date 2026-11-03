<!-- view/content/logistik/bongkaran/arsip.php -->

<style>
/* ── Warna thead Arsip LK (biru) ── */
#tabelArsipLK thead.thead-dark th {
    background: #1565c0 !important;
    color: #fff !important;
    border-color: #0d47a1 !important;
}
/* ── Warna thead Arsip KK (hijau) ── */
#tabelArsipKK thead.thead-dark th {
    background: #1b5e20 !important;
    color: #fff !important;
    border-color: #145214 !important;
}

/* ── Baris label pemisah kelompok ── */
tr.separator-label td {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    padding: 4px 12px !important;
    border-top: 2px dashed #aaa !important;
    border-bottom: 2px dashed #aaa !important;
}
tr.separator-label.sep-today  td { background:#e8f5e9; border-color:#43a047 !important; color:#1b5e20; }
tr.separator-label.sep-older  td { background:#f3f3f3; border-color:#bbb    !important; color:#555;    }

/* ── DataTables custom styling ── */
.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 4px 8px;
    margin-left: 6px;
}
.dataTables_wrapper .dataTables_length select {
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 3px 6px;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current,
.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
    background: #343a40 !important;
    border-color: #343a40 !important;
    color: #fff !important;
    border-radius: 4px;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #e9ecef !important;
    border-color: #dee2e6 !important;
    color: #212529 !important;
    border-radius: 4px;
}
/* Warna paginate aktif per tabel */
#tabelArsipLK_wrapper .dataTables_paginate .paginate_button.current,
#tabelArsipLK_wrapper .dataTables_paginate .paginate_button.current:hover {
    background: #1565c0 !important;
    border-color: #1565c0 !important;
    color: #fff !important;
}
#tabelArsipKK_wrapper .dataTables_paginate .paginate_button.current,
#tabelArsipKK_wrapper .dataTables_paginate .paginate_button.current:hover {
    background: #1b5e20 !important;
    border-color: #1b5e20 !important;
    color: #fff !important;
}

/* ── Filter bar kustom per card ── */
.filter-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
    padding: 10px 0 6px;
}
.filter-bar label {
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 0;
    color: #495057;
}
.filter-bar input[type="date"],
.filter-bar select {
    font-size: 12px;
    padding: 4px 8px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    height: 30px;
}
.filter-bar .btn-sm {
    height: 30px;
    font-size: 12px;
    padding: 0 10px;
}
.dataTables_wrapper .dataTables_info {
    font-size: 12px;
    color: #6c757d;
    padding-top: 8px;
}
.dataTables_wrapper .dataTables_filter {
    font-size: 12px;
}

/* Sembunyikan baris separator dari DataTables info count */
tr.separator-label { display: none; } /* disembunyikan di DT, ditampilkan manual */
</style>

<!-- DataTables CSS (CDN) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap4.min.css">

<body class="hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="Logo" height="150" width="300">
    </div>

    <?php $this->load->view('partial/main/navbar') ?>
    <?php $this->load->view('partial/main/sidebar') ?>

    <div class="content-wrapper">
        <div class="content-header">
            <section class="content">

                <!-- Tombol navigasi atas -->
                <div class="row mb-3 align-items-center">
                    <div class="col-auto">
                        <a href="<?= base_url('checker') ?>" class="btn btn-primary">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>
                    <div class="col-auto">
                        <span class="badge badge-success p-2" style="font-size:12px;">
                            <i class="fas fa-circle mr-1"></i> Hijau = Diarsipkan hari ini
                        </span>
                    </div>
                </div>

                <!-- ============================================================
                     ARSIP BONGKARAN
                ============================================================ -->
                <?php
                $today = date('Y-m-d');
                $ab_today = !empty($arsip_bongkar) ? array_filter($arsip_bongkar, fn($r) => date('Y-m-d', strtotime($r['archived_at'] ?? 'now')) === $today) : [];
                $ab_older = !empty($arsip_bongkar) ? array_filter($arsip_bongkar, fn($r) => date('Y-m-d', strtotime($r['archived_at'] ?? 'now')) !== $today) : [];
                ?>
                <div class="card">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0"><i class="fas fa-dolly mr-2"></i> Arsip Bongkaran</h3>
                        <span class="badge badge-light text-dark">
                            Total: <?= !empty($arsip_bongkar) ? count($arsip_bongkar) : 0 ?> record
                        </span>
                    </div>
                    <div class="card-body">

                        <!-- Filter bar Bongkaran -->
                        <div class="filter-bar" id="filterBongkaran">
                            <label>Tgl Arsip:</label>
                            <input type="date" id="filterTglArsipBongkar" placeholder="Dari">
                            <span style="font-size:12px;">s/d</span>
                            <input type="date" id="filterTglArsipBongkarEnd" placeholder="Sampai">
                            <label class="ml-2">Checker:</label>
                            <select id="filterCheckerBongkar">
                                <option value="">-- Semua --</option>
                                <?php if (!empty($arsip_bongkar)) :
                                    $checkers = array_unique(array_column($arsip_bongkar, 'nm_checker'));
                                    sort($checkers);
                                    foreach ($checkers as $c) : ?>
                                        <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
                                    <?php endforeach;
                                endif; ?>
                            </select>
                            <button class="btn btn-sm btn-secondary" onclick="resetFilterBongkar()">
                                <i class="fas fa-times mr-1"></i> Reset
                            </button>
                        </div>

                        <div class="table-responsive">
                        <table class="table table-bordered table-sm" id="tabelArsip">
                            <thead class="thead-dark text-center">
                                <tr>
                                    <th>#</th>
                                    <th>Tgl Dibuat</th>
                                    <th>Kode</th>
                                    <th>Keterangan</th>
                                    <th>Checker</th>
                                    <th>Mulai</th>
                                    <th>Selesai</th>
                                    <th>Durasi</th>
                                    <th>Diarsipkan Oleh</th>
                                    <th>Tgl Arsip</th>
                                    <th class="d-none">tgl_arsip_raw</th><!-- kolom tersembunyi untuk filter tanggal -->
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no = 1;

                            function durasiArsip($mulai, $selesai) {
                                if (empty($mulai) || empty($selesai)) return '-';
                                $sel = strtotime($selesai) - strtotime($mulai);
                                if ($sel <= 0) return '-';
                                $j = floor($sel/3600); $m = floor(($sel%3600)/60);
                                return $j > 0 ? "{$j} jam {$m} menit" : "{$m} menit";
                            }

                            function barisBongkarArsip($row, &$no, $row_class) { ?>
                            <tr class="<?= $row_class ?>">
                                <td><?= $no++ ?></td>
                                <td><small><?= !empty($row['created_at']) ? date('d/m/Y', strtotime($row['created_at'])) : '-' ?></small></td>
                                <td><small><?= htmlspecialchars($row['kode_bongkar']) ?></small></td>
                                <td><?= htmlspecialchars($row['keterangan'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['nm_checker'] ?? '-') ?></td>
                                <td><small><?= !empty($row['waktu_mulai'])   ? date('d/m/Y H:i', strtotime($row['waktu_mulai']))   : '-' ?></small></td>
                                <td><small><?= !empty($row['waktu_selesai']) ? date('d/m/Y H:i', strtotime($row['waktu_selesai'])) : '-' ?></small></td>
                                <td><small><?= durasiArsip($row['waktu_mulai'] ?? '', $row['waktu_selesai'] ?? '') ?></small></td>
                                <td><?= htmlspecialchars($row['archived_by'] ?? '-') ?></td>
                                <td><small><?= !empty($row['archived_at']) ? date('d/m/Y H:i', strtotime($row['archived_at'])) : '-' ?></small></td>
                                <td class="d-none"><?= !empty($row['archived_at']) ? date('Y-m-d', strtotime($row['archived_at'])) : '' ?></td>
                            </tr>
                            <?php }

                            if (empty($arsip_bongkar)) : ?>
                                <tr><td colspan="11" class="text-center text-muted"><i class="fas fa-inbox mr-1"></i> Belum ada arsip bongkaran</td></tr>
                            <?php else :
                                foreach ($arsip_bongkar as $row) :
                                    $rc = (date('Y-m-d', strtotime($row['archived_at'] ?? 'now')) === $today) ? 'table-success' : '';
                                    barisBongkarArsip($row, $no, $rc);
                                endforeach;
                            endif; ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>

                <!-- ============================================================
                     ARSIP LOADING LK  (thead biru)
                ============================================================ -->
                <?php
                $al_today = !empty($arsip_lk) ? array_filter($arsip_lk, fn($r) => date('Y-m-d', strtotime($r['archived_at'] ?? 'now')) === $today) : [];
                $al_older = !empty($arsip_lk) ? array_filter($arsip_lk, fn($r) => date('Y-m-d', strtotime($r['archived_at'] ?? 'now')) !== $today) : [];
                ?>
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0"><i class="fas fa-truck mr-2"></i> Arsip Loading LK</h3>
                        <span class="badge badge-light text-dark">
                            Total: <?= !empty($arsip_lk) ? count($arsip_lk) : 0 ?> record
                        </span>
                    </div>
                    <div class="card-body">

                        <!-- Filter bar LK -->
                        <div class="filter-bar">
                            <label>Tgl Arsip:</label>
                            <input type="date" id="filterTglArsipLK" placeholder="Dari">
                            <span style="font-size:12px;">s/d</span>
                            <input type="date" id="filterTglArsipLKEnd" placeholder="Sampai">
                            <label class="ml-2">Checker:</label>
                            <select id="filterCheckerLK">
                                <option value="">-- Semua --</option>
                                <?php if (!empty($arsip_lk)) :
                                    $checkersLK = array_unique(array_column($arsip_lk, 'nm_checker'));
                                    sort($checkersLK);
                                    foreach ($checkersLK as $c) : ?>
                                        <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
                                    <?php endforeach;
                                endif; ?>
                            </select>
                            <label class="ml-2">Status:</label>
                            <select id="filterStatusLK">
                                <option value="">-- Semua --</option>
                                <?php if (!empty($arsip_lk)) :
                                    $statusesLK = array_unique(array_column($arsip_lk, 'status'));
                                    sort($statusesLK);
                                    foreach ($statusesLK as $s) : ?>
                                        <option value="<?= htmlspecialchars(str_replace('_',' ',$s)) ?>"><?= htmlspecialchars(str_replace('_',' ',$s)) ?></option>
                                    <?php endforeach;
                                endif; ?>
                            </select>
                            <button class="btn btn-sm btn-secondary" onclick="resetFilterLK()">
                                <i class="fas fa-times mr-1"></i> Reset
                            </button>
                        </div>

                        <div class="table-responsive">
                        <table class="table table-bordered table-sm" id="tabelArsipLK">
                            <thead class="thead-dark text-center">
                                <tr>
                                    <th>#</th>
                                    <th>Kode</th>
                                    <th>Keterangan</th>
                                    <th>Tgl</th>
                                    <th>Checker</th>
                                    <th>Mulai</th>
                                    <th>Selesai</th>
                                    <th>Durasi</th>
                                    <th>Status</th>
                                    <th>Diarsipkan Oleh</th>
                                    <th>Tgl Arsip</th>
                                    <th class="d-none">tgl_arsip_raw</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no = 1;

                            function barisLoadingArsip($row, &$no, $row_class) {
                                $durasi = '-';
                                if (!empty($row['waktu_mulai']) && !empty($row['waktu_selesai'])) {
                                    $sel = strtotime($row['waktu_selesai']) - strtotime($row['waktu_mulai']);
                                    if ($sel > 0) {
                                        $j = floor($sel/3600); $m = floor(($sel%3600)/60);
                                        $durasi = $j > 0 ? "{$j} jam {$m} menit" : "{$m} menit";
                                    }
                                }
                            ?>
                            <tr class="<?= $row_class ?>">
                                <td><?= $no++ ?></td>
                                <td><small><?= htmlspecialchars($row['kode'] ?? '-') ?></small></td>
                                <td><?= htmlspecialchars($row['keterangan']) ?></td>
                                <td><small><?= $row['tgl'] ?></small></td>
                                <td><?= htmlspecialchars($row['nm_checker'] ?? '-') ?></td>
                                <td><small><?= !empty($row['waktu_mulai'])   ? date('d/m H:i', strtotime($row['waktu_mulai']))   : '-' ?></small></td>
                                <td><small><?= !empty($row['waktu_selesai']) ? date('d/m H:i', strtotime($row['waktu_selesai'])) : '-' ?></small></td>
                                <td><small><?= $durasi ?></small></td>
                                <td><span class="badge badge-success"><?= str_replace('_',' ',$row['status']) ?></span></td>
                                <td><?= htmlspecialchars($row['archived_by'] ?? '-') ?></td>
                                <td><small><?= !empty($row['archived_at']) ? date('d/m/Y H:i', strtotime($row['archived_at'])) : '-' ?></small></td>
                                <td class="d-none"><?= !empty($row['archived_at']) ? date('Y-m-d', strtotime($row['archived_at'])) : '' ?></td>
                            </tr>
                            <?php }

                            if (empty($arsip_lk)) : ?>
                                <tr><td colspan="12" class="text-center text-muted"><i class="fas fa-inbox mr-1"></i> Belum ada arsip Loading LK</td></tr>
                            <?php else :
                                foreach ($arsip_lk as $row) :
                                    $rc = (date('Y-m-d', strtotime($row['archived_at'] ?? 'now')) === $today) ? 'table-success' : '';
                                    barisLoadingArsip($row, $no, $rc);
                                endforeach;
                            endif; ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>

                <!-- ============================================================
                     ARSIP LOADING KK  (thead hijau)
                ============================================================ -->
                <?php
                $ak_today = !empty($arsip_kk) ? array_filter($arsip_kk, fn($r) => date('Y-m-d', strtotime($r['archived_at'] ?? 'now')) === $today) : [];
                $ak_older = !empty($arsip_kk) ? array_filter($arsip_kk, fn($r) => date('Y-m-d', strtotime($r['archived_at'] ?? 'now')) !== $today) : [];
                ?>
                <div class="card">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0"><i class="fas fa-truck-loading mr-2"></i> Arsip Loading KK</h3>
                        <span class="badge badge-light text-dark">
                            Total: <?= !empty($arsip_kk) ? count($arsip_kk) : 0 ?> record
                        </span>
                    </div>
                    <div class="card-body">

                        <!-- Filter bar KK -->
                        <div class="filter-bar">
                            <label>Tgl Arsip:</label>
                            <input type="date" id="filterTglArsipKK" placeholder="Dari">
                            <span style="font-size:12px;">s/d</span>
                            <input type="date" id="filterTglArsipKKEnd" placeholder="Sampai">
                            <label class="ml-2">Checker:</label>
                            <select id="filterCheckerKK">
                                <option value="">-- Semua --</option>
                                <?php if (!empty($arsip_kk)) :
                                    $checkersKK = array_unique(array_column($arsip_kk, 'nm_checker'));
                                    sort($checkersKK);
                                    foreach ($checkersKK as $c) : ?>
                                        <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
                                    <?php endforeach;
                                endif; ?>
                            </select>
                            <label class="ml-2">Status:</label>
                            <select id="filterStatusKK">
                                <option value="">-- Semua --</option>
                                <?php if (!empty($arsip_kk)) :
                                    $statusesKK = array_unique(array_column($arsip_kk, 'status'));
                                    sort($statusesKK);
                                    foreach ($statusesKK as $s) : ?>
                                        <option value="<?= htmlspecialchars(str_replace('_',' ',$s)) ?>"><?= htmlspecialchars(str_replace('_',' ',$s)) ?></option>
                                    <?php endforeach;
                                endif; ?>
                            </select>
                            <button class="btn btn-sm btn-secondary" onclick="resetFilterKK()">
                                <i class="fas fa-times mr-1"></i> Reset
                            </button>
                        </div>

                        <div class="table-responsive">
                        <table class="table table-bordered table-sm" id="tabelArsipKK">
                            <thead class="thead-dark text-center">
                                <tr>
                                    <th>#</th>
                                    <th>Kode</th>
                                    <th>Keterangan</th>
                                    <th>Tgl</th>
                                    <th>Checker</th>
                                    <th>Mulai</th>
                                    <th>Selesai</th>
                                    <th>Durasi</th>
                                    <th>Status</th>
                                    <th>Diarsipkan Oleh</th>
                                    <th>Tgl Arsip</th>
                                    <th class="d-none">tgl_arsip_raw</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no = 1;

                            if (empty($arsip_kk)) : ?>
                                <tr><td colspan="12" class="text-center text-muted"><i class="fas fa-inbox mr-1"></i> Belum ada arsip Loading KK</td></tr>
                            <?php else :
                                foreach ($arsip_kk as $row) :
                                    $rc = (date('Y-m-d', strtotime($row['archived_at'] ?? 'now')) === $today) ? 'table-success' : '';
                                    barisLoadingArsip($row, $no, $rc);
                                endforeach;
                            endif; ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>

            </section>
        </div>
    </div>

    <footer class="main-footer">
        <strong>Copyright &copy; 2022 <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block"><b>Version</b> 1.0</div>
    </footer>
    <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<!-- DataTables JS (CDN) -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
$(function () {

    // ── Kolom tersembunyi (tgl_arsip_raw) tidak ikut dicari oleh searchbox DT ──
    // Index kolom tgl_arsip_raw: Bongkaran=10, LK/KK=11

    /* =====================================================================
       Helper: Custom range filter untuk kolom tanggal (format Y-m-d)
    ===================================================================== */
    function addDateRangeFilter(tableId, colIndex) {
        $.fn.dataTable.ext.search.push(function (settings, data) {
            if (settings.nTable.id !== tableId) return true;
            var from = $('#filterTglArsip' + tableId.replace('tabelArsip','') + '').val();
            // nama id sudah diset spesifik di bawah; fungsi ini hanya blueprint,
            // implementasi spesifik di tiap inisialisasi
            return true;
        });
    }

    /* =====================================================================
       TABEL ARSIP BONGKARAN
    ===================================================================== */
    var dtBongkar = $('#tabelArsip').DataTable({
        pageLength  : 10,
        lengthMenu  : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
        order       : [[0, 'asc']],
        language    : dtLang(),
        columnDefs  : [
            { targets: [10], visible: false, searchable: false } // kolom raw date
        ],
        dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        // Warna baris tetap dipertahankan dari PHP (table-success)
        createdRow: function(row, data, idx) {
            // biarkan class dari PHP
        }
    });

    // Filter tanggal + checker Bongkaran
    $.fn.dataTable.ext.search.push(function (settings, data) {
        if (settings.nTable.id !== 'tabelArsip') return true;
        var from     = $('#filterTglArsipBongkar').val();
        var to       = $('#filterTglArsipBongkarEnd').val();
        var checker  = $('#filterCheckerBongkar').val().toLowerCase();
        var tglRaw   = data[10] || ''; // kolom tersembunyi Y-m-d
        var nmChecker= data[4].toLowerCase();

        if (from && tglRaw < from) return false;
        if (to   && tglRaw > to)   return false;
        if (checker && nmChecker.indexOf(checker) === -1) return false;
        return true;
    });

    $('#filterTglArsipBongkar, #filterTglArsipBongkarEnd').on('change', function () { dtBongkar.draw(); });
    $('#filterCheckerBongkar').on('change', function () { dtBongkar.draw(); });

    window.resetFilterBongkar = function () {
        $('#filterTglArsipBongkar, #filterTglArsipBongkarEnd').val('');
        $('#filterCheckerBongkar').val('');
        dtBongkar.search('').draw();
    };

    /* =====================================================================
       TABEL ARSIP LOADING LK
    ===================================================================== */
    var dtLK = $('#tabelArsipLK').DataTable({
        pageLength  : 10,
        lengthMenu  : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
        order       : [[0, 'asc']],
        language    : dtLang(),
        columnDefs  : [
            { targets: [11], visible: false, searchable: false }
        ],
        dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-5'i><'col-sm-7'p>>"
    });

    $.fn.dataTable.ext.search.push(function (settings, data) {
        if (settings.nTable.id !== 'tabelArsipLK') return true;
        var from    = $('#filterTglArsipLK').val();
        var to      = $('#filterTglArsipLKEnd').val();
        var checker = $('#filterCheckerLK').val().toLowerCase();
        var status  = $('#filterStatusLK').val().toLowerCase();
        var tglRaw  = data[11] || '';
        var nmChecker = data[4].toLowerCase();
        var sts       = data[8].toLowerCase();

        if (from && tglRaw < from) return false;
        if (to   && tglRaw > to)   return false;
        if (checker && nmChecker.indexOf(checker) === -1) return false;
        if (status  && sts.indexOf(status) === -1)        return false;
        return true;
    });

    $('#filterTglArsipLK, #filterTglArsipLKEnd').on('change', function () { dtLK.draw(); });
    $('#filterCheckerLK, #filterStatusLK').on('change', function () { dtLK.draw(); });

    window.resetFilterLK = function () {
        $('#filterTglArsipLK, #filterTglArsipLKEnd').val('');
        $('#filterCheckerLK, #filterStatusLK').val('');
        dtLK.search('').draw();
    };

    /* =====================================================================
       TABEL ARSIP LOADING KK
    ===================================================================== */
    var dtKK = $('#tabelArsipKK').DataTable({
        pageLength  : 10,
        lengthMenu  : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
        order       : [[0, 'asc']],
        language    : dtLang(),
        columnDefs  : [
            { targets: [11], visible: false, searchable: false }
        ],
        dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-5'i><'col-sm-7'p>>"
    });

    $.fn.dataTable.ext.search.push(function (settings, data) {
        if (settings.nTable.id !== 'tabelArsipKK') return true;
        var from    = $('#filterTglArsipKK').val();
        var to      = $('#filterTglArsipKKEnd').val();
        var checker = $('#filterCheckerKK').val().toLowerCase();
        var status  = $('#filterStatusKK').val().toLowerCase();
        var tglRaw  = data[11] || '';
        var nmChecker = data[4].toLowerCase();
        var sts       = data[8].toLowerCase();

        if (from && tglRaw < from) return false;
        if (to   && tglRaw > to)   return false;
        if (checker && nmChecker.indexOf(checker) === -1) return false;
        if (status  && sts.indexOf(status) === -1)        return false;
        return true;
    });

    $('#filterTglArsipKK, #filterTglArsipKKEnd').on('change', function () { dtKK.draw(); });
    $('#filterCheckerKK, #filterStatusKK').on('change', function () { dtKK.draw(); });

    window.resetFilterKK = function () {
        $('#filterTglArsipKK, #filterTglArsipKKEnd').val('');
        $('#filterCheckerKK, #filterStatusKK').val('');
        dtKK.search('').draw();
    };

    /* =====================================================================
       Bahasa Indonesia untuk DataTables
    ===================================================================== */
    function dtLang() {
        return {
            processing     : "Memproses...",
            search         : "Cari:",
            lengthMenu     : "Tampilkan _MENU_ data",
            info           : "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
            infoEmpty      : "Tidak ada data yang ditampilkan",
            infoFiltered   : "(difilter dari _MAX_ total data)",
            zeroRecords    : "Tidak ada data yang cocok",
            emptyTable     : "Belum ada data",
            paginate: {
                first    : "«",
                previous : "‹",
                next     : "›",
                last     : "»"
            }
        };
    }
});
</script>
</body>