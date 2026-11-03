<?php
// view/content/logistik/checker/dashboard.php
?>
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

                <style>
                    .badge-purple { background:#ede9fe !important; color:#5b21b6 !important; border:1px solid #c4b5fd; }
                    .badge-pink   { background:#fce7f3 !important; color:#9d174d !important; border:1px solid #f9a8d4; }
                    .badge-teal   { background:#ccfbf1 !important; color:#0f5132 !important; border:1px solid #6ee7b7; }
                    .badge-status {
                        display:inline-block; padding:3px 8px; border-radius:20px;
                        font-size:11px; font-weight:500; white-space:nowrap; line-height:1.5;
                    }
                    .semua-section-header {
                        font-size:13px; font-weight:600; color:#495057;
                        padding:8px 12px; background:#f8f9fa;
                        border-left:4px solid #6c757d; border-radius:4px;
                        margin-bottom:10px; margin-top:16px;
                    }
                    .semua-section-header.bongkar { border-left-color:#ffc107; }
                    .semua-section-header.lk      { border-left-color:#17a2b8; }
                    .semua-section-header.kk       { border-left-color:#28a745; }

                    .pintu-badge {
                        display:inline-flex; align-items:center; gap:3px;
                        background:#212529; color:#fff;
                        font-size:11px; font-weight:600;
                        padding:2px 8px; border-radius:4px;
                        white-space:nowrap; vertical-align:middle;
                    }
                    .pintu-badge.done-variant { background:#495057; }

                    .dash-item-card {
                        border-radius:6px;
                        padding:10px 12px;
                        margin-bottom:10px;
                    }
                    .dash-item-card .item-title {
                        font-size:14px; font-weight:700; color:#1a1a1a; margin-bottom:4px;
                    }
                    .dash-item-card .item-meta {
                        font-size:13px; font-weight:600; color:#212529; margin-bottom:2px;
                    }
                    .dash-item-card .item-time {
                        font-size:12px; color:#444; font-weight:500;
                    }
                    .dash-item-card .badge-durasi-live {
                        font-size:12px; font-weight:700; padding:2px 8px;
                    }
                    .dash-item-card .badge-persen {
                        font-size:13px; font-weight:700; padding:3px 10px;
                    }

                    /* Badge penyiapan barang */
                    .badge-siapkan {
                        display:inline-flex; align-items:center; gap:4px;
                        background:#7b1fa2; color:#fff;
                        font-size:11px; font-weight:700;
                        padding:3px 8px; border-radius:4px;
                        white-space:nowrap;
                    }

                    /* Badge / strip pause */
                    .pause-strip {
                        display:flex; align-items:center; gap:6px;
                        background:#fce4ec; border:1px solid #f48fb1;
                        border-radius:5px; padding:4px 10px;
                        margin-top:6px;
                        font-size:12px; font-weight:700; color:#c62828;
                    }
                    @keyframes blink-dash { 0%,100%{opacity:1} 50%{opacity:.3} }
                    .pause-strip i { animation: blink-dash 1.2s infinite; }

                    /* Garis kiri item yang di-pause */
                    .dash-item-card.is-paused { border-left: 4px solid #e91e63 !important; }
                    /* Garis kiri item penyiapan */
                    .dash-item-card.is-siapkan { border-left: 4px solid #7b1fa2 !important; }
                </style>

                <?php
                // ---- Helper status ----
                function isProses($s)  { return in_array($s, ['PROSES', 'PROSES_LOADING', 'PENYIAPAN_BARANG']); }
                function isDone($s)    { return $s === 'DONE'; }
                function isWait($s)    { return !isProses($s) && !isDone($s); }

                function statusLabel($s) {
                    $map = [
                        'MENUNGGU'         => 'Menunggu',
                        'PROSES'           => 'Proses bongkar',
                        'PENYIAPAN_BARANG' => 'Siapkan Barang',
                        'SIAP_LOADING'     => 'Siap Loading',
                        'DONE'             => 'Done',
                        'CETAK_DO'         => 'Cetak DO',
                        'DO_SELESAI'       => 'DO Selesai',
                        'PROSES_LOADING'   => 'Proses loading',
                    ];
                    return $map[$s] ?? str_replace('_', ' ', $s);
                }

                // ---- Hitung summary ----
                $total_pintu  = 5;
                $pintu_dipakai = [];

                $total_proses = 0;
                $total_done   = 0;

                foreach ($bongkaran as $b) {
                    if (isProses($b['status'])) {
                        $total_proses++;
                        if (!empty($b['pintu']) && !in_array($b['pintu'], $pintu_dipakai))
                            $pintu_dipakai[] = $b['pintu'];
                    }
                    if (isDone($b['status'])) $total_done++;
                }
                foreach ($list_lk as $lk) {
                    if (isProses($lk['status'])) {
                        $total_proses++;
                        if (!empty($lk['pintu']) && !in_array($lk['pintu'], $pintu_dipakai))
                            $pintu_dipakai[] = $lk['pintu'];
                    }
                    if (isDone($lk['status'])) $total_done++;
                }
                foreach ($list_kk as $kk) {
                    if (isProses($kk['status'])) {
                        $total_proses++;
                        if (!empty($kk['pintu']) && !in_array($kk['pintu'], $pintu_dipakai))
                            $pintu_dipakai[] = $kk['pintu'];
                    }
                    if (isDone($kk['status'])) $total_done++;
                }

                $pintu_aktif = count($pintu_dipakai);
                $nama_pintu  = ['A1','A2','A3','A4','A5','A6','B1','B2','B3','C'];
                ?>

                <!-- Header -->
                <div class="row align-items-center mb-3">
                    <div class="col">
                        <h4 class="mb-0">
                            <i class="fas fa-warehouse mr-2 text-primary"></i> Dashboard Warehouse
                        </h4>
                        <small class="text-muted"><?= date('l, d F Y — H:i') ?> WIB</small>
                    </div>
                    <div class="col-auto d-flex align-items-center">
                        <a href="<?= base_url('checker/arsip') ?>" class="btn btn-sm btn-secondary mr-2">
                            <i class="fas fa-archive mr-1"></i> Arsip
                        </a>
                        <a href="<?= base_url('checker') ?>" class="btn btn-sm btn-outline-secondary mr-2">
                            <i class="fas fa-list mr-1"></i> Halaman Kerja
                        </a>
                        <button class="btn btn-sm btn-outline-primary" onclick="location.reload()">
                            <i class="fas fa-sync-alt mr-1"></i> Refresh
                        </button>
                    </div>
                </div>

                <!-- Metric Cards -->
                <div class="row mb-3">
                    <div class="col-md-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?= $pintu_aktif ?> / <?= $total_pintu ?></h3>
                                <p>Pintu Aktif</p>
                            </div>
                            <div class="icon"><i class="fas fa-door-open"></i></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner"><h3><?= $total_proses ?></h3><p>Proses Berjalan</p></div>
                            <div class="icon"><i class="fas fa-spinner"></i></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner"><h3><?= $total_done ?></h3><p>Selesai Hari Ini</p></div>
                            <div class="icon"><i class="fas fa-check-double"></i></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner"><h3><?= count($bongkaran) + count($list_lk) + count($list_kk) ?></h3><p>Total Aktivitas</p></div>
                            <div class="icon"><i class="fas fa-tasks"></i></div>
                        </div>
                    </div>
                </div>

                <!-- Filter Tabs -->
                <ul class="nav nav-tabs mb-3" id="dashTab">
                    <li class="nav-item">
                        <a class="nav-link active" href="#tab-semua" data-toggle="tab">
                            <i class="fas fa-th-list mr-1"></i> Semua
                            <span class="badge badge-dark ml-1"><?= count($bongkaran) + count($list_lk) + count($list_kk) ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab-bongkaran" data-toggle="tab">
                            <i class="fas fa-dolly mr-1"></i> Bongkaran
                            <span class="badge badge-warning ml-1"><?= count($bongkaran) ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab-lk" data-toggle="tab">
                            <i class="fas fa-truck mr-1"></i> Loading LK
                            <span class="badge badge-info ml-1"><?= count($list_lk) ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab-kk" data-toggle="tab">
                            <i class="fas fa-truck-loading mr-1"></i> Loading KK
                            <span class="badge badge-success ml-1"><?= count($list_kk) ?></span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content">

                    <!-- TAB SEMUA -->
                    <div class="tab-pane fade show active" id="tab-semua">
                        <div class="semua-section-header bongkar">
                            <i class="fas fa-dolly mr-2"></i> Bongkaran
                            <span class="badge badge-warning ml-2"><?= count($bongkaran) ?></span>
                        </div>
                        <?php echo dashboardColumns(
                            array_filter($bongkaran, fn($r) => isWait($r['status'])),
                            array_filter($bongkaran, fn($r) => isProses($r['status'])),
                            array_filter($bongkaran, fn($r) => isDone($r['status'])),
                            'bongkar'
                        ); ?>

                        <div class="semua-section-header lk">
                            <i class="fas fa-truck mr-2"></i> Loading LK
                            <span class="badge badge-info ml-2"><?= count($list_lk) ?></span>
                        </div>
                        <?php echo dashboardColumns(
                            array_filter($list_lk, fn($r) => isWait($r['status'])),
                            array_filter($list_lk, fn($r) => isProses($r['status'])),
                            array_filter($list_lk, fn($r) => isDone($r['status'])),
                            'lk'
                        ); ?>

                        <div class="semua-section-header kk">
                            <i class="fas fa-truck-loading mr-2"></i> Loading KK
                            <span class="badge badge-success ml-2"><?= count($list_kk) ?></span>
                        </div>
                        <?php echo dashboardColumns(
                            array_filter($list_kk, fn($r) => isWait($r['status'])),
                            array_filter($list_kk, fn($r) => isProses($r['status'])),
                            array_filter($list_kk, fn($r) => isDone($r['status'])),
                            'kk'
                        ); ?>
                    </div>

                    <!-- TAB BONGKARAN -->
                    <div class="tab-pane fade" id="tab-bongkaran">
                        <?php echo dashboardColumns(
                            array_filter($bongkaran, fn($r) => isWait($r['status'])),
                            array_filter($bongkaran, fn($r) => isProses($r['status'])),
                            array_filter($bongkaran, fn($r) => isDone($r['status'])),
                            'bongkar'
                        ); ?>
                    </div>

                    <!-- TAB LOADING LK -->
                    <div class="tab-pane fade" id="tab-lk">
                        <?php echo dashboardColumns(
                            array_filter($list_lk, fn($r) => isWait($r['status'])),
                            array_filter($list_lk, fn($r) => isProses($r['status'])),
                            array_filter($list_lk, fn($r) => isDone($r['status'])),
                            'lk'
                        ); ?>
                    </div>

                    <!-- TAB LOADING KK -->
                    <div class="tab-pane fade" id="tab-kk">
                        <?php echo dashboardColumns(
                            array_filter($list_kk, fn($r) => isWait($r['status'])),
                            array_filter($list_kk, fn($r) => isProses($r['status'])),
                            array_filter($list_kk, fn($r) => isDone($r['status'])),
                            'kk'
                        ); ?>
                    </div>

                </div><!-- end tab-content -->

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

<?php
// ================================================================
// Nama pintu
// ================================================================
function namaPintu($pintu) {
    $map = [1=>'A1',2=>'A2',3=>'A3',4=>'A4',5=>'A5',6=>'A6',7=>'B1',8=>'B2',9=>'B3',10=>'C'];
    return $map[(int)$pintu] ?? 'P'.$pintu;
}

// ================================================================
// Helper: render 3 kolom (belum / proses / done)
// ================================================================
function dashboardColumns($wait, $proses, $done, $type)
{
    $color_wait   = ['bg'=>'#dee2e6','stroke'=>'#6c757d','dot'=>'#495057','dotIn'=>'#dee2e6'];
    $color_proses = ['bg'=>'#fff3cd','stroke'=>'#856404','dot'=>'#856404','dotIn'=>'#fff3cd'];
    $color_done   = ['bg'=>'#d1e7dd','stroke'=>'#198754','dot'=>'#0a3622','dotIn'=>'#d1e7dd'];

    if ($type === 'bongkar')      { $lbl_wait='Belum Bongkar';      $lbl_p='Proses Bongkar';     $lbl_done='Done Bongkar'; }
    elseif ($type === 'lk')       { $lbl_wait='Belum Loading LK';   $lbl_p='Proses Loading LK';  $lbl_done='Done Loading LK'; }
    else                          { $lbl_wait='Belum Loading KK';   $lbl_p='Proses Loading KK';  $lbl_done='Done Loading KK'; }

    ob_start();
    ?>
    <div class="row mb-3">

        <!-- ── Kolom Belum ── -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white py-2">
                    <h6 class="mb-0 font-weight-bold">
                        <i class="fas fa-circle mr-1"></i> <?= $lbl_wait ?>
                        <span class="badge badge-light text-dark float-right"><?= count($wait) ?></span>
                    </h6>
                </div>
                <div class="card-body p-2" style="min-height:120px">
                    <?php if (empty($wait)) : ?>
                        <p class="text-muted text-center mt-3 mb-0"><small>Tidak ada</small></p>
                    <?php else : foreach ($wait as $item) : ?>
                    <div class="card dash-item-card mb-2 border-left-secondary shadow-sm">
                        <div class="d-flex align-items-center">
                            <?= trukIconSVG($type, $color_wait) ?>
                            <div style="min-width:0; margin-left:10px">
                                <div class="item-title text-truncate">
                                    <?= htmlspecialchars($item['keterangan'] ?? ($item['kode_bongkar'] ?? '-')) ?>
                                </div>
                                <?php
                                $badge_map = [
                                    'MENUNGGU'         => 'badge-secondary',
                                    'CETAK_DO'         => 'badge-info',
                                    'DO_SELESAI'       => 'badge-warning',
                                    'SIAP_LOADING'     => 'badge-primary',
                                    'PENYIAPAN_BARANG' => 'badge-secondary',
                                ];
                                $badge_cls = $badge_map[$item['status']] ?? 'badge-secondary';
                                ?>
                                <span class="badge <?= $badge_cls ?> mt-1"><?= statusLabel($item['status']) ?></span>
                                <?php if ($item['status'] === 'DO_SELESAI' && !empty($item['waktu_do_selesai'])): ?>
                                    <div class="mt-1" style="font-size:11px; font-weight:600; color:#856404;">
                                        <i class="fas fa-file-alt mr-1"></i>DO: <?= date('d/m H:i', strtotime($item['waktu_do_selesai'])) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </div>

        <!-- ── Kolom Proses ── -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-warning py-2" style="color:#212529">
                    <h6 class="mb-0 font-weight-bold">
                        <i class="fas fa-play-circle mr-1"></i> <?= $lbl_p ?>
                        <span class="badge badge-dark float-right"><?= count($proses) ?></span>
                    </h6>
                </div>
                <div class="card-body p-2" style="min-height:120px">
                    <?php if (empty($proses)) : ?>
                        <p class="text-muted text-center mt-3 mb-0"><small>Tidak ada</small></p>
                    <?php else : foreach ($proses as $item) :

                        // ── deteksi status ──
                        $is_siapkan        = ($item['status'] === 'PENYIAPAN_BARANG');
                        $is_paused_loading = !empty($item['is_paused']);
                        $is_paused_siapkan = !empty($item['is_paused_siapkan']);
                        $any_paused        = $is_paused_loading || $is_paused_siapkan;

                        // progres: saat siapkan pakai progres_siapkan, saat loading pakai progres
                        $progres = $is_siapkan
                            ? (int)($item['progres_siapkan'] ?? 0)
                            : (int)($item['progres'] ?? 0);

                        // ── durasi live ──
                        // Saat penyiapan: hitung dari waktu_mulai_siapkan
                        // Saat loading   : hitung dari waktu_mulai
                        $durasi_live = '';
                        if ($is_siapkan && !empty($item['waktu_mulai_siapkan'])) {
                            $ref = $is_paused_siapkan && !empty($item['paused_at_siapkan'])
                                 ? strtotime($item['paused_at_siapkan']) : time();
                            $sel = max(0, $ref - strtotime($item['waktu_mulai_siapkan'])
                                       - (int)($item['total_pause_secs_siapkan'] ?? 0));
                            if ($sel > 0) {
                                $j = floor($sel/3600); $m = floor(($sel%3600)/60);
                                $durasi_live = $j > 0 ? "{$j}j {$m}m" : "{$m}m";
                            }
                        } elseif (!$is_siapkan && !empty($item['waktu_mulai'])) {
                            $ref = $is_paused_loading && !empty($item['paused_at'])
                                 ? strtotime($item['paused_at']) : time();
                            $sel = max(0, $ref - strtotime($item['waktu_mulai'])
                                       - (int)($item['total_pause_secs'] ?? 0));
                            if ($sel > 0) {
                                $j = floor($sel/3600); $m = floor(($sel%3600)/60);
                                $durasi_live = $j > 0 ? "{$j}j {$m}m" : "{$m}m";
                            }
                        }

                        // ── class card ──
                        $card_class = 'dash-item-card mb-2 shadow-sm';
                        if ($any_paused)   $card_class .= ' is-paused';
                        elseif ($is_siapkan) $card_class .= ' is-siapkan';
                        else               $card_class .= ' border-left-warning';
                    ?>
                    <div class="card <?= $card_class ?>">
                        <!-- Baris atas: ikon + nama + pintu + % -->
                        <div class="d-flex align-items-start mb-2">
                            <?= trukIconSVG($type, $any_paused ? ['bg'=>'#fce4ec','stroke'=>'#c62828','dot'=>'#c62828','dotIn'=>'#fce4ec']
                                                               : ($is_siapkan ? ['bg'=>'#ede7f6','stroke'=>'#7b1fa2','dot'=>'#7b1fa2','dotIn'=>'#ede7f6']
                                                                              : $color_proses)) ?>
                            <div style="min-width:0; flex:1; margin-left:10px">
                                <!-- nama item + pintu -->
                                <div class="d-flex align-items-center flex-wrap" style="gap:5px; margin-bottom:4px">
                                    <span class="item-title text-truncate">
                                        <?= htmlspecialchars($item['keterangan'] ?? ($item['kode_bongkar'] ?? '-')) ?>
                                    </span>
                                    <?php if (!empty($item['pintu'])): ?>
                                        <span class="pintu-badge">
                                            <i class="fas fa-door-open"></i> <?= namaPintu($item['pintu']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Badge fase: Siapkan Barang atau Proses Loading -->
                                <div class="mb-1">
                                    <?php if ($is_siapkan): ?>
                                        <span class="badge-siapkan">
                                            <i class="fas fa-boxes"></i> Siapkan Barang
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-warning" style="font-size:11px;">
                                            <i class="fas fa-truck mr-1"></i> Proses Loading
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Nama checker -->
                                <div class="item-meta">
                                    <i class="fas fa-user mr-1" style="color:#856404"></i>
                                    <?= htmlspecialchars($item['nm_checker'] ?? '-') ?>
                                </div>

                                <!-- Jam mulai + durasi -->
                                <div class="item-time">
                                    <?php
                                    $wkt_mulai_show = $is_siapkan
                                        ? ($item['waktu_mulai_siapkan'] ?? null)
                                        : ($item['waktu_mulai'] ?? null);
                                    if (!empty($wkt_mulai_show)): ?>
                                        <i class="fas fa-clock mr-1"></i>
                                        Mulai: <?= date('H:i', strtotime($wkt_mulai_show)) ?>
                                        <?php if ($durasi_live): ?>
                                            <span class="badge badge-warning badge-durasi-live ml-1">
                                                <?= $durasi_live ?>
                                            </span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>

                                <!-- Waktu DO selesai (hanya LK & KK) -->
                                <?php if ($type !== 'bongkar' && !empty($item['waktu_do_selesai'])): ?>
                                    <div class="item-time" style="color:#856404; font-weight:600;">
                                        <i class="fas fa-file-alt mr-1"></i>DO Selesai: <?= date('H:i', strtotime($item['waktu_do_selesai'])) ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Strip pause -->
                                <?php if ($is_paused_siapkan): ?>
                                    <div class="pause-strip">
                                        <i class="fas fa-pause-circle"></i>
                                        Penyiapan di-pause
                                    </div>
                                <?php elseif ($is_paused_loading): ?>
                                    <div class="pause-strip">
                                        <i class="fas fa-pause-circle"></i>
                                        Loading di-pause
                                    </div>
                                <?php endif; ?>
                            </div>
                            <!-- Persentase -->
                            <span class="badge <?= $any_paused ? 'badge-danger' : ($is_siapkan ? 'badge-secondary' : 'badge-warning') ?> badge-persen ml-2 align-self-start">
                                <?= $progres ?>%
                            </span>
                        </div>

                        <!-- Progress bar -->
                        <div class="progress" style="height:6px; border-radius:3px">
                            <div class="progress-bar <?= $any_paused ? 'bg-danger' : ($is_siapkan ? 'bg-purple' : 'bg-warning') ?>"
                                 style="width:<?= $progres ?>%; <?= $is_siapkan && !$any_paused ? 'background:#7b1fa2!important' : '' ?>"></div>
                        </div>
                    </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </div>

        <!-- ── Kolom Done ── -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white py-2">
                    <h6 class="mb-0 font-weight-bold">
                        <i class="fas fa-check-circle mr-1"></i> <?= $lbl_done ?>
                        <span class="badge badge-light text-dark float-right"><?= count($done) ?></span>
                    </h6>
                </div>
                <div class="card-body p-2" style="min-height:120px">
                    <?php if (empty($done)) : ?>
                        <p class="text-muted text-center mt-3 mb-0"><small>Tidak ada</small></p>
                    <?php else : foreach ($done as $item) :
                        $durasi = '';
                        if (!empty($item['waktu_mulai']) && !empty($item['waktu_selesai'])) {
                            $sel = strtotime($item['waktu_selesai']) - strtotime($item['waktu_mulai']);
                            $total_p = (int)($item['total_pause_secs'] ?? 0);
                            $sel = max(0, $sel - $total_p);
                            $j = floor($sel/3600); $m = floor(($sel%3600)/60);
                            $durasi = $j > 0 ? "{$j}j {$m}m" : "{$m}m";
                        }
                        // Durasi penyiapan (jika ada)
                        $durasi_siapkan = '';
                        if (!empty($item['waktu_mulai_siapkan']) && !empty($item['waktu_selesai_siapkan'])) {
                            $sel_s = strtotime($item['waktu_selesai_siapkan']) - strtotime($item['waktu_mulai_siapkan']);
                            $total_ps = (int)($item['total_pause_secs_siapkan'] ?? 0);
                            $sel_s = max(0, $sel_s - $total_ps);
                            $js = floor($sel_s/3600); $ms = floor(($sel_s%3600)/60);
                            $durasi_siapkan = $js > 0 ? "{$js}j {$ms}m" : "{$ms}m";
                        }
                    ?>
                    <div class="card dash-item-card mb-2 border-left-success shadow-sm">
                        <div class="d-flex align-items-start">
                            <?= trukIconSVG($type, $color_done) ?>
                            <div style="min-width:0; flex:1; margin-left:10px">
                                <!-- nama + pintu -->
                                <div class="d-flex align-items-center flex-wrap" style="gap:5px; margin-bottom:4px">
                                    <span class="item-title text-truncate">
                                        <?= htmlspecialchars($item['keterangan'] ?? ($item['kode_bongkar'] ?? '-')) ?>
                                    </span>
                                    <?php if (!empty($item['pintu'])): ?>
                                        <span class="pintu-badge done-variant">
                                            <i class="fas fa-door-open"></i> <?= namaPintu($item['pintu']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <!-- checker + durasi loading -->
                                <div class="item-meta">
                                    <i class="fas fa-user mr-1" style="color:#198754"></i>
                                    <?= htmlspecialchars($item['nm_checker'] ?? '-') ?>
                                    <?php if ($durasi): ?>
                                        <span class="badge badge-success ml-1" style="font-size:12px;font-weight:700;padding:2px 8px">
                                            <i class="fas fa-truck mr-1"></i><?= $durasi ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <!-- durasi penyiapan (jika ada) -->
                                <?php if ($durasi_siapkan): ?>
                                    <div class="item-time" style="color:#5b21b6; font-weight:600;">
                                        <i class="fas fa-boxes mr-1"></i>Siapkan: <?= $durasi_siapkan ?>
                                    </div>
                                <?php endif; ?>
                                <!-- Waktu DO Selesai (hanya LK & KK) -->
                                <?php if ($type !== 'bongkar' && !empty($item['waktu_do_selesai'])): ?>
                                    <div class="item-time" style="color:#856404; font-weight:600;">
                                        <i class="fas fa-file-alt mr-1"></i>DO: <?= date('H:i', strtotime($item['waktu_do_selesai'])) ?>
                                    </div>
                                <?php endif; ?>
                                <!-- waktu mulai → selesai loading -->
                                <div class="item-time">
                                    <i class="fas fa-clock mr-1"></i>
                                    <?= !empty($item['waktu_mulai'])   ? date('H:i', strtotime($item['waktu_mulai']))   : '-' ?>
                                    <?= !empty($item['waktu_selesai']) ? ' → ' . date('H:i', strtotime($item['waktu_selesai'])) : '' ?>
                                </div>
                                <!-- pernah pause indicator -->
                                <?php if (!empty($item['pernah_pause']) || !empty($item['pernah_pause_siapkan'])): ?>
                                    <div class="item-time" style="color:#c62828;">
                                        <i class="fas fa-pause-circle mr-1"></i>
                                        <?php if (!empty($item['pernah_pause_siapkan'])): ?>Pernah pause siapkan<?php endif; ?>
                                        <?php if (!empty($item['pernah_pause']) && !empty($item['pernah_pause_siapkan'])): ?> & <?php endif; ?>
                                        <?php if (!empty($item['pernah_pause'])): ?>Pernah pause loading<?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </div>

    </div>
    <?php
    return ob_get_clean();
}

// ── Helper: SVG ikon truk kecil ──
function trukIconSVG($type, $c)
{
    $lbl = strtoupper($type);
    return '<svg width="30" height="22" viewBox="0 0 56 40" xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0" style="margin-top:2px">
        <rect x="2" y="6" width="36" height="26" rx="3" fill="' . $c['bg'] . '" stroke="' . $c['stroke'] . '" stroke-width="2"/>
        <rect x="38" y="12" width="16" height="20" rx="2" fill="' . $c['bg'] . '" stroke="' . $c['stroke'] . '" stroke-width="2"/>
        <path d="M38 12 L48 12 L54 20 L54 32 L38 32 Z" fill="' . $c['bg'] . '" stroke="' . $c['stroke'] . '" stroke-width="2"/>
        <circle cx="12" cy="34" r="4" fill="' . $c['dot'] . '"/>
        <circle cx="44" cy="34" r="4" fill="' . $c['dot'] . '"/>
        <circle cx="12" cy="34" r="2" fill="' . $c['dotIn'] . '"/>
        <circle cx="44" cy="34" r="2" fill="' . $c['dotIn'] . '"/>
        <text x="20" y="23" font-size="9" fill="' . $c['stroke'] . '" font-weight="bold" text-anchor="middle">' . $lbl . '</text>
    </svg>';
}
?>
</body>