<!-- view/content/logistik/checker/detail.php -->
<style>
@import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap');

:root {
    --c-bg        : #f0f2f5;
    --c-card      : #ffffff;
    --c-border    : #e2e6ea;
    --c-text      : #1a2332;
    --c-muted     : #6c757d;
    --c-blue      : #1565c0;
    --c-blue-lt   : #e3f2fd;
    --c-green     : #1b5e20;
    --c-green-lt  : #e8f5e9;
    --c-amber     : #e65100;
    --c-amber-lt  : #fff8e1;
    --c-purple    : #6a1b9a;
    --c-purple-lt : #f3e5f5;
    --c-red       : #c62828;
    --c-red-lt    : #ffebee;
    --c-teal      : #00695c;
    --c-teal-lt   : #e0f2f1;
    --r           : 10px;
    --shadow      : 0 2px 12px rgba(0,0,0,.07);
}

/* ── Reset font: semua pakai IBM Plex Sans, termasuk angka ── */
* {
    font-family: 'IBM Plex Sans', sans-serif;
    box-sizing: border-box;
}

.detail-wrap {
    background: var(--c-bg);
    padding: 16px 0 24px;
}

/* ── Header ── */
.detail-header {
    background: linear-gradient(135deg, #0d2757 0%, #1565c0 60%, #1976d2 100%);
    border-radius: var(--r);
    padding: 16px 22px;
    color: #fff;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
    box-shadow: var(--shadow);
}
.detail-header .dh-left h2 {
    font-size: 17px;
    font-weight: 700;
    margin: 0 0 2px;
}
.detail-header .dh-left p {
    margin: 0;
    font-size: 12px;
    opacity: .75;
}
.detail-header .dh-right {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
}

/* ── Status badge ── */
.status-hero {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 700;
    padding: 5px 13px;
    border-radius: 20px;
    letter-spacing: .04em;
    text-transform: uppercase;
}
.s-cetak_do         { background:#e3f2fd; color:#1565c0; }
.s-do_selesai       { background:#fff8e1; color:#e65100; }
.s-penyiapan_barang { background:#f3e5f5; color:#6a1b9a; }
.s-siap_loading     { background:#e0f2f1; color:#00695c; }
.s-proses_loading   { background:#fff3e0; color:#e65100; }
.s-done             { background:#e8f5e9; color:#1b5e20; }
.s-pause            { background:#ffebee; color:#c62828; }

/* ── Layout utama: 2 kolom ── */
.detail-main {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    align-items: start;
}

/* ── Kolom kanan: stack vertikal ── */
.col-right {
    display: grid;
    gap: 14px;
}

/* ── Section card ── */
.sc {
    background: var(--c-card);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    border: 1px solid var(--c-border);
    overflow: hidden;
    margin-bottom: 14px;
}
.sc:last-child { margin-bottom: 0; }

.sc-head {
    padding: 10px 18px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 7px;
    border-bottom: 1px solid var(--c-border);
}
.sc-head.blue   { background: var(--c-blue-lt);   color: var(--c-blue);   }
.sc-head.amber  { background: var(--c-amber-lt);  color: var(--c-amber);  }
.sc-head.orange { background: #fff3e0;             color: #e65100;         }
.sc-head.dark   { background: #1a2332;             color: #fff;            }

/* ── Info grid ── */
.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
}
.info-cell {
    padding: 11px 16px;
    border-right: 1px solid var(--c-border);
    border-bottom: 1px solid var(--c-border);
}
.info-cell:nth-child(2n) { border-right: none; }
.info-cell.span2 { grid-column: span 2; border-right: none; }
.info-cell:last-child,
.info-cell.no-border-b { border-bottom: none; }

.ic-label {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--c-muted);
    margin-bottom: 3px;
}
.ic-val {
    font-size: 13px;
    font-weight: 600;
    color: var(--c-text);
    line-height: 1.3;
}
.ic-val.muted {
    color: var(--c-muted);
    font-weight: 400;
    font-size: 12px;
}
.ic-val small {
    display: block;
    font-size: 10px;
    color: var(--c-muted);
    font-weight: 400;
    margin-top: 1px;
}

/* ── Live dot ── */
.live-dot {
    display: inline-block;
    width: 6px; height: 6px;
    border-radius: 50%;
    background: #f59e0b;
    margin-left: 4px;
    animation: blink-live 1s infinite;
    vertical-align: middle;
}
@keyframes blink-live { 0%,100%{opacity:1} 50%{opacity:.2} }

/* ── Timeline ── */
.tl-wrap {
    padding: 16px 18px 16px 22px;
}
.timeline {
    position: relative;
    padding-left: 26px;
}
.timeline::before {
    content: '';
    position: absolute;
    left: 9px; top: 8px; bottom: 8px;
    width: 2px;
    background: var(--c-border);
}
.tl-item {
    position: relative;
    margin-bottom: 16px;
}
.tl-item:last-child { margin-bottom: 0; }
.tl-dot {
    position: absolute;
    left: -21px; top: 3px;
    width: 14px; height: 14px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px currentColor;
}
.tl-dot.done    { background: #22c55e; color: #22c55e; }
.tl-dot.active  { background: #f59e0b; color: #f59e0b; animation: blink-live 1.2s infinite; }
.tl-dot.pause   { background: #e91e63; color: #e91e63; }
.tl-dot.pending { background: #94a3b8; color: #94a3b8; }
.tl-title {
    font-size: 12px;
    font-weight: 700;
    color: var(--c-text);
    margin-bottom: 1px;
}
.tl-meta {
    font-size: 11px;
    color: var(--c-muted);
}
.tl-dur {
    display: inline-block;
    margin-top: 3px;
    font-size: 10px;
    font-weight: 700;
    padding: 1px 7px;
    border-radius: 4px;
    background: var(--c-blue-lt);
    color: var(--c-blue);
}
.tl-dur.purple { background: var(--c-purple-lt); color: var(--c-purple); }
.tl-dur.green  { background: var(--c-green-lt);  color: var(--c-green);  }

/* ── Durasi ringkasan ── */
.dur-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
}
.dur-c {
    padding: 14px 16px;
    border-right: 1px solid var(--c-border);
    text-align: center;
}
.dur-c:last-child { border-right: none; background: #f8fffe; }
.dc-label {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--c-muted);
    margin-bottom: 5px;
}
.dc-val {
    font-size: 15px;
    font-weight: 700;
    line-height: 1.1;
    margin-bottom: 3px;
}
.dc-val.purple { color: var(--c-purple); }
.dc-val.blue   { color: var(--c-blue);   }
.dc-val.green  { color: var(--c-green);  }
.dc-val.amber  { color: var(--c-amber);  }
.dc-sub {
    font-size: 10px;
    color: var(--c-muted);
}

/* ── Pause table ── */
.pause-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}
.pause-table th {
    background: #f8f9fa;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: var(--c-muted);
    padding: 8px 14px;
    border-bottom: 1px solid var(--c-border);
    text-align: left;
}
.pause-table td {
    padding: 8px 14px;
    border-bottom: 1px solid var(--c-border);
    color: var(--c-text);
}
.pause-table tr:last-child td { border-bottom: none; }
.pause-table tr:hover td { background: #fafafa; }

/* ── Badges ── */
.badge-pintu-lg {
    background: #1a2332;
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 5px;
}
.badge-type {
    font-size: 10px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 4px;
    letter-spacing: .06em;
}
.badge-type.lk { background: #e3f2fd; color: #1565c0; }
.badge-type.kk { background: #e8f5e9; color: #1b5e20; }
.badge-pp {
    background: #e91e63;
    color: #fff;
    font-size: 10px;
    padding: 2px 7px;
    border-radius: 3px;
    font-weight: 700;
}

/* ── Back btn ── */
.btn-back {
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.3);
    color: #fff;
    padding: 6px 14px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: background .2s;
}
.btn-back:hover { background: rgba(255,255,255,.25); color: #fff; text-decoration: none; }

/* ── Responsive: 1 kolom di layar kecil ── */
@media (max-width: 900px) {
    .detail-main { grid-template-columns: 1fr; }
}
</style>

<?php
// ── Helper ──────────────────────────────────────────────────
$nama_pintu_map = ['A1','A2','A3','A4','A5','A6','B1','B2','B3','C'];
$fn_pintu = fn($p) => isset($nama_pintu_map[($p??1)-1]) ? $nama_pintu_map[$p-1] : 'P'.$p;

$fn_durasi = function($mulai, $akhir = null, $pause_secs = 0) {
    if (empty($mulai)) return null;
    $ts_mulai = is_numeric($mulai) ? $mulai : strtotime($mulai);
    $ts_akhir = $akhir ? (is_numeric($akhir) ? $akhir : strtotime($akhir)) : time();
    $bruto   = max(0, $ts_akhir - $ts_mulai);
    $efektif = max(0, $bruto - (int)$pause_secs);
    if ($efektif <= 0) return '0 menit';
    $j = floor($efektif / 3600);
    $m = floor(($efektif % 3600) / 60);
    $s = $efektif % 60;
    $out = '';
    if ($j) $out .= $j . ' jam ';
    if ($m || $j) $out .= $m . ' mnt ';
    $out .= $s . ' dtk';
    return trim($out);
};

$fn_fmt_dt = fn($dt) => $dt ? date('d/m/Y H:i:s', strtotime($dt)) : '-';
$fn_fmt_d  = fn($dt) => $dt ? date('d/m/Y', strtotime($dt)) : '-';

// ── Flag status ──────────────────────────────────────────────
$is_paused         = !empty($row['is_paused']);
$is_paused_siapkan = !empty($row['is_paused_siapkan']);
$is_done           = $row['status'] === 'DONE';
$is_siapkan        = $row['status'] === 'PENYIAPAN_BARANG';
$is_siap_loading   = $row['status'] === 'SIAP_LOADING';
$is_loading        = $row['status'] === 'PROSES_LOADING';

// Status display
$status_map = [
    'CETAK_DO'         => ['label'=>'Cetak DO',         'cls'=>'s-cetak_do',         'icon'=>'fa-print'],
    'DO_SELESAI'       => ['label'=>'DO Selesai',        'cls'=>'s-do_selesai',        'icon'=>'fa-file-alt'],
    'PENYIAPAN_BARANG' => ['label'=>'Penyiapan Barang',  'cls'=>'s-penyiapan_barang',  'icon'=>'fa-boxes'],
    'SIAP_LOADING'     => ['label'=>'Siap Loading',      'cls'=>'s-siap_loading',      'icon'=>'fa-check-circle'],
    'PROSES_LOADING'   => ['label'=>'Proses Loading',    'cls'=>'s-proses_loading',    'icon'=>'fa-truck'],
    'DONE'             => ['label'=>'Done ✓',            'cls'=>'s-done',              'icon'=>'fa-check-double'],
];
$cur_status = $status_map[$row['status']] ?? ['label'=>$row['status'],'cls'=>'s-cetak_do','icon'=>'fa-circle'];
if ($is_paused || $is_paused_siapkan) {
    $cur_status = ['label'=>'Di-Pause', 'cls'=>'s-pause', 'icon'=>'fa-pause'];
}

// ── Hitung durasi ────────────────────────────────────────────
/*
 * LOGIKA PAUSE:
 * - Saat PAUSE: $akhir = paused_at (waktu mulai pause sesi ini)
 *               $pause_secs = total_pause_secs (sesi-sesi pause SEBELUMNYA saja)
 *               Hasil = durasi efektif yang sudah berjalan sebelum pause ini
 * - Saat BERJALAN: $akhir = time() (live), $pause_secs = total_pause_secs
 * - Saat SELESAI: $akhir = waktu_selesai, $pause_secs = total_pause_secs (sudah final)
 *
 * PENTING: total_pause_secs di DB hanya terupdate saat resume (setelah sesi pause selesai).
 * Sesi pause yang SEDANG BERJALAN belum masuk ke total_pause_secs.
 * Karena $akhir kita set ke paused_at (bukan time()), maka:
 *   bruto = paused_at - mulai → tidak termasuk waktu sejak pause
 *   efektif = bruto - total_pause_secs_sebelumnya → sudah benar
 */

// Durasi siapkan
$dur_siapkan_str = '-';
if (!empty($row['waktu_mulai_siapkan'])) {
    if (!empty($row['waktu_selesai_siapkan'])) {
        // Fase siapkan sudah selesai → pakai waktu_selesai_siapkan
        $akhir_s = $row['waktu_selesai_siapkan'];
    } elseif ($is_paused_siapkan && !empty($row['paused_at_siapkan'])) {
        // Sedang di-pause → beku di saat pause dimulai, jangan pakai time()
        $akhir_s = $row['paused_at_siapkan'];
    } else {
        // Sedang berjalan live
        $akhir_s = null; // fn_durasi akan pakai time()
    }
    $dur_siapkan_str = $fn_durasi(
        $row['waktu_mulai_siapkan'],
        $akhir_s,
        $row['total_pause_secs_siapkan'] ?? 0
    );
}

// Durasi loading (hanya fase loading, mulai dari selesai_siapkan)
$dur_loading_str = '-';
$mulai_loading   = !empty($row['waktu_selesai_siapkan']) ? $row['waktu_selesai_siapkan'] : null;
if ($mulai_loading && $is_loading || ($mulai_loading && $is_done)) {
    if ($is_done && !empty($row['waktu_selesai'])) {
        $akhir_l = $row['waktu_selesai'];
    } elseif ($is_paused && !empty($row['paused_at'])) {
        // Pause loading → beku di paused_at, jangan pakai time()
        $akhir_l = $row['paused_at'];
    } else {
        $akhir_l = null; // live
    }
    $dur_loading_str = $fn_durasi($mulai_loading, $akhir_l, $row['total_pause_secs'] ?? 0);
}

// Durasi total (dari mulai siapkan → selesai loading)
$dur_total_str = '-';
$is_live       = false;
if (!empty($row['waktu_mulai'])) {
    if ($is_done && !empty($row['waktu_selesai'])) {
        $akhir_t = $row['waktu_selesai'];
    } elseif ($is_paused && !empty($row['paused_at'])) {
        // Pause loading → beku
        $akhir_t = $row['paused_at'];
    } elseif ($is_paused_siapkan && !empty($row['paused_at_siapkan'])) {
        // Pause siapkan → beku
        $akhir_t = $row['paused_at_siapkan'];
    } else {
        $akhir_t = null;
        $is_live = true;
    }
    $total_pause_all = ((int)($row['total_pause_secs'] ?? 0))
                     + ((int)($row['total_pause_secs_siapkan'] ?? 0));
    $dur_total_str = $fn_durasi($row['waktu_mulai'], $akhir_t, $total_pause_all);
}
?>

<body class="hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="<?= base_url('assets/images/Karisma.png') ?>" alt="Logo" height="150" width="300">
    </div>
    <?php $this->load->view('partial/main/navbar') ?>
    <?php $this->load->view('partial/main/sidebar') ?>

    <div class="content-wrapper">
        <div class="content-header">
            <section class="content">
                <div class="detail-wrap">

                    <!-- ══ HEADER ══ -->
                    <div class="detail-header">
                        <div class="dh-left">
                            <h2>
                                <i class="fas fa-<?= $type === 'lk' ? 'truck' : 'truck-loading' ?> mr-2"></i>
                                Detail Loading <?= strtoupper($type) ?>
                                <span class="badge-type <?= $type ?> ml-2"><?= strtoupper($type) ?></span>
                            </h2>
                            <p>
                                <?= htmlspecialchars($row['kode'] ?? '-') ?> &nbsp;·&nbsp;
                                <?= htmlspecialchars($row['keterangan']) ?> &nbsp;·&nbsp;
                                <?= $fn_fmt_d($row['tgl']) ?>
                            </p>
                        </div>
                        <div class="dh-right">
                            <span class="status-hero <?= $cur_status['cls'] ?>">
                                <i class="fas <?= $cur_status['icon'] ?>"></i>
                                <?= $cur_status['label'] ?>
                                <?php if ($is_live && !$is_done): ?>
                                    <span class="live-dot"></span>
                                <?php endif; ?>
                            </span>
                            <a href="<?= base_url('checker') ?>" class="btn-back">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <!-- ══ LAYOUT 2 KOLOM ══ -->
                    <div class="detail-main">

                        <!-- ═ KOLOM KIRI: Info Umum + Timeline ═ -->
                        <div>

                            <!-- Info Umum -->
                            <div class="sc">
                                <div class="sc-head blue">
                                    <i class="fas fa-info-circle"></i> Informasi Umum
                                </div>
                                <div class="info-grid">
                                    <div class="info-cell">
                                        <div class="ic-label">Tgl Dibuat</div>
                                        <div class="ic-val"><?= $fn_fmt_d($row['created_at'] ?? $row['tgl']) ?></div>
                                    </div>
                                    <div class="info-cell">
                                        <div class="ic-label">Kode</div>
                                        <div class="ic-val"><?= htmlspecialchars($row['kode'] ?? '-') ?></div>
                                    </div>
                                    <div class="info-cell span2">
                                        <div class="ic-label">Keterangan</div>
                                        <div class="ic-val" style="font-size:14px;">
                                            <?= htmlspecialchars($row['keterangan']) ?>
                                        </div>
                                    </div>
                                    <div class="info-cell">
                                        <div class="ic-label">Checker</div>
                                        <div class="ic-val">
                                            <?php if (!empty($row['nm_checker'])): ?>
                                                <i class="fas fa-user-circle mr-1" style="color:var(--c-blue);"></i>
                                                <?= htmlspecialchars($row['nm_checker']) ?>
                                            <?php else: ?>
                                                <span class="muted">Belum ditugaskan</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="info-cell">
                                        <div class="ic-label">Pintu</div>
                                        <div class="ic-val">
                                            <?php if (!empty($row['pintu'])): ?>
                                                <span class="badge-pintu-lg">
                                                    <i class="fas fa-door-open mr-1"></i><?= $fn_pintu($row['pintu']) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="muted">-</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="info-cell">
                                        <div class="ic-label">Wkt DO Selesai</div>
                                        <div class="ic-val" style="font-size:12px;">
                                            <?= !empty($row['waktu_do_selesai']) ? $fn_fmt_dt($row['waktu_do_selesai']) : '<span class="muted">-</span>' ?>
                                        </div>
                                    </div>
                                    <div class="info-cell no-border-b">
                                        <div class="ic-label">Pernah Di-Pause</div>
                                        <div class="ic-val">
                                            <?php if (!empty($row['pernah_pause']) || !empty($row['pernah_pause_siapkan'])): ?>
                                                <span class="badge-pp"><i class="fas fa-pause mr-1"></i>Ya</span>
                                            <?php else: ?>
                                                <span class="muted">Tidak</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Timeline -->
                            <div class="sc">
                                <div class="sc-head dark">
                                    <i class="fas fa-stream"></i> Alur Waktu Proses
                                </div>
                                <div class="tl-wrap">
                                    <div class="timeline">

                                        <!-- DO Selesai -->
                                        <div class="tl-item">
                                            <div class="tl-dot <?= !empty($row['waktu_do_selesai']) ? 'done' : 'pending' ?>"></div>
                                            <div class="tl-title">DO Selesai</div>
                                            <div class="tl-meta">
                                                <?= !empty($row['waktu_do_selesai']) ? $fn_fmt_dt($row['waktu_do_selesai']) : 'Belum' ?>
                                            </div>
                                        </div>

                                        <!-- Mulai Siapkan -->
                                        <div class="tl-item">
                                            <div class="tl-dot <?= !empty($row['waktu_mulai_siapkan']) ? ($is_siapkan ? ($is_paused_siapkan ? 'pause' : 'active') : 'done') : 'pending' ?>"></div>
                                            <div class="tl-title">Mulai Penyiapan Barang</div>
                                            <div class="tl-meta">
                                                <?= !empty($row['waktu_mulai_siapkan']) ? $fn_fmt_dt($row['waktu_mulai_siapkan']) : 'Belum' ?>
                                            </div>
                                            <?php if (!empty($row['waktu_mulai_siapkan']) && empty($row['waktu_selesai_siapkan'])): ?>
                                                <span class="tl-dur purple">
                                                    <?= $dur_siapkan_str ?>
                                                    <?php if ($is_siapkan && !$is_paused_siapkan): ?>
                                                        <span class="live-dot"></span>
                                                    <?php endif; ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Selesai Siapkan -->
                                        <div class="tl-item">
                                            <div class="tl-dot <?= !empty($row['waktu_selesai_siapkan']) ? 'done' : 'pending' ?>"></div>
                                            <div class="tl-title">Selesai Penyiapan Barang</div>
                                            <div class="tl-meta">
                                                <?= !empty($row['waktu_selesai_siapkan']) ? $fn_fmt_dt($row['waktu_selesai_siapkan']) : 'Belum' ?>
                                            </div>
                                            <?php if (!empty($row['waktu_selesai_siapkan'])): ?>
                                                <span class="tl-dur purple">Durasi siapkan: <?= $dur_siapkan_str ?></span>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Mulai Loading -->
                                        <div class="tl-item">
                                            <div class="tl-dot <?= !empty($row['waktu_selesai_siapkan']) ? ($is_loading ? ($is_paused ? 'pause' : 'active') : ($is_done ? 'done' : 'pending')) : 'pending' ?>"></div>
                                            <div class="tl-title">Mulai Loading</div>
                                            <div class="tl-meta">
                                                <?= !empty($row['waktu_selesai_siapkan']) ? $fn_fmt_dt($row['waktu_selesai_siapkan']) : 'Belum' ?>
                                            </div>
                                        </div>

                                        <!-- Selesai Loading -->
                                        <div class="tl-item">
                                            <div class="tl-dot <?= $is_done ? 'done' : 'pending' ?>"></div>
                                            <div class="tl-title">Selesai Loading (Done)</div>
                                            <div class="tl-meta">
                                                <?= $is_done && !empty($row['waktu_selesai']) ? $fn_fmt_dt($row['waktu_selesai']) : 'Belum' ?>
                                            </div>
                                            <?php if ($is_done): ?>
                                                <span class="tl-dur green">Durasi loading: <?= $dur_loading_str ?></span>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div><!-- /kolom kiri -->

                        <!-- ═ KOLOM KANAN: Rincian Waktu + Durasi + Pause ═ -->
                        <div class="col-right">

                            <!-- Rincian Waktu -->
                            <div class="sc">
                                <div class="sc-head blue">
                                    <i class="fas fa-clock"></i> Rincian Waktu
                                </div>
                                <div class="info-grid">
                                    <div class="info-cell">
                                        <div class="ic-label">Mulai Siapkan</div>
                                        <div class="ic-val" style="font-size:12px;">
                                            <?= !empty($row['waktu_mulai_siapkan']) ? $fn_fmt_dt($row['waktu_mulai_siapkan']) : '<span class="muted">-</span>' ?>
                                        </div>
                                    </div>
                                    <div class="info-cell">
                                        <div class="ic-label">Selesai Siapkan</div>
                                        <div class="ic-val" style="font-size:12px;">
                                            <?= !empty($row['waktu_selesai_siapkan']) ? $fn_fmt_dt($row['waktu_selesai_siapkan']) : '<span class="muted">-</span>' ?>
                                        </div>
                                    </div>
                                    <div class="info-cell">
                                        <div class="ic-label">Mulai Loading</div>
                                        <div class="ic-val" style="font-size:12px;">
                                            <?= !empty($row['waktu_selesai_siapkan']) ? $fn_fmt_dt($row['waktu_selesai_siapkan']) : '<span class="muted">-</span>' ?>
                                        </div>
                                    </div>
                                    <div class="info-cell">
                                        <div class="ic-label">Selesai Loading</div>
                                        <div class="ic-val" style="font-size:12px;">
                                            <?= ($is_done && !empty($row['waktu_selesai'])) ? $fn_fmt_dt($row['waktu_selesai']) : '<span class="muted">-</span>' ?>
                                        </div>
                                    </div>
                                    <div class="info-cell">
                                        <div class="ic-label">Mulai Total</div>
                                        <div class="ic-val" style="font-size:12px; color:var(--c-blue);">
                                            <?= !empty($row['waktu_mulai']) ? $fn_fmt_dt($row['waktu_mulai']) : '<span class="muted">-</span>' ?>
                                            <small>(dari siapkan barang)</small>
                                        </div>
                                    </div>
                                    <div class="info-cell no-border-b">
                                        <div class="ic-label">Selesai Total</div>
                                        <div class="ic-val" style="font-size:12px;">
                                            <?php if ($is_done && !empty($row['waktu_selesai'])): ?>
                                                <span style="color:var(--c-green);">
                                                    <?= $fn_fmt_dt($row['waktu_selesai']) ?>
                                                    <small>(selesai loading)</small>
                                                </span>
                                            <?php elseif ($is_live): ?>
                                                <span style="color:var(--c-amber);">
                                                    Sedang berjalan <span class="live-dot"></span>
                                                </span>
                                            <?php else: ?>
                                                <span class="muted">-</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ringkasan Durasi -->
                            <div class="sc">
                                <div class="sc-head amber">
                                    <i class="fas fa-stopwatch"></i> Ringkasan Durasi Efektif
                                    <small style="font-weight:400;text-transform:none;letter-spacing:0;margin-left:4px;opacity:.8;">(dikurangi pause)</small>
                                </div>
                                <div class="dur-row">
                                    <div class="dur-c">
                                        <div class="dc-label"><i class="fas fa-boxes mr-1"></i>Siapkan</div>
                                        <div class="dc-val purple">
                                            <?= $dur_siapkan_str !== '-' ? $dur_siapkan_str : '<span style="color:#94a3b8;font-size:13px;">-</span>' ?>
                                        </div>
                                        <div class="dc-sub">Pause: <?= gmdate('H:i:s', (int)($row['total_pause_secs_siapkan'] ?? 0)) ?></div>
                                    </div>
                                    <div class="dur-c">
                                        <div class="dc-label"><i class="fas fa-truck mr-1"></i>Loading</div>
                                        <div class="dc-val blue">
                                            <?= $dur_loading_str !== '-' ? $dur_loading_str : '<span style="color:#94a3b8;font-size:13px;">-</span>' ?>
                                            <?php if ($is_live && $is_loading): ?><span class="live-dot"></span><?php endif; ?>
                                        </div>
                                        <div class="dc-sub">Pause: <?= gmdate('H:i:s', (int)($row['total_pause_secs'] ?? 0)) ?></div>
                                    </div>
                                    <div class="dur-c">
                                        <div class="dc-label" style="color:var(--c-teal);"><i class="fas fa-hourglass-end mr-1"></i>Total</div>
                                        <div class="dc-val <?= $is_done ? 'green' : 'amber' ?>">
                                            <?php if ($dur_total_str !== '-'): ?>
                                                <?= $dur_total_str ?>
                                                <?php if ($is_live): ?><span class="live-dot"></span><?php endif; ?>
                                            <?php else: ?>
                                                <span style="color:#94a3b8;font-size:13px;">-</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="dc-sub">Total pause: <?= gmdate('H:i:s', (int)($row['total_pause_secs'] ?? 0) + (int)($row['total_pause_secs_siapkan'] ?? 0)) ?></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Riwayat Pause (tampil hanya jika pernah pause) -->
                            <?php if (!empty($row['pernah_pause']) || !empty($row['pernah_pause_siapkan'])): ?>
                            <?php if (!empty($pause_history)): ?>
                            <div class="sc">
                                <div class="sc-head orange">
                                    <i class="fas fa-pause-circle"></i> Riwayat Pause
                                </div>
                                <table class="pause-table">
                                    <thead>
                                        <tr>
                                            <th>Fase</th>
                                            <th>Mulai Pause</th>
                                            <th>Selesai Pause</th>
                                            <th>Durasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pause_history as $ph): ?>
                                        <tr>
                                            <td style="color:var(--c-<?= ($ph['fase'] ?? '') === 'siapkan' ? 'purple' : 'blue' ?>);font-weight:600;">
                                                <?= ucfirst($ph['fase'] ?? '-') ?>
                                            </td>
                                            <td><?= $fn_fmt_dt($ph['paused_at'] ?? '') ?></td>
                                            <td><?= !empty($ph['resumed_at']) ? $fn_fmt_dt($ph['resumed_at']) : '<span style="color:#f59e0b;">Sedang pause</span>' ?></td>
                                            <td><?= !empty($ph['durasi_secs']) ? gmdate('H:i:s', $ph['durasi_secs']) : '-' ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php endif; ?>
                            <?php endif; ?>

                        </div><!-- /kolom kanan -->

                    </div><!-- /detail-main -->

                </div><!-- /detail-wrap -->
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

<?php if ($is_live): ?>
<script>
setTimeout(function(){ location.reload(); }, 30000);
</script>
<?php endif; ?>
</body>