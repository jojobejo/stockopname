<!-- view/content/logistik/checker/index.php -->

<!-- STYLE TAMBAHAN -->
<style>
#tabelLK thead.thead-dark th {
    background: #1565c0 !important; color: #fff !important; border-color: #0d47a1 !important;
}
#tabelKK thead.thead-dark th {
    background: #1b5e20 !important; color: #fff !important; border-color: #145214 !important;
}
tr.separator-label td {
    font-size:11px; font-weight:700; letter-spacing:.06em; text-transform:uppercase;
    padding:4px 12px !important;
    border-top:1px solid #aaa !important; border-bottom:1px solid #aaa !important;
}
tr.separator-label.sep-active  td { background:#fffde7; border-color:#f9a825 !important; color:#e65100; }
tr.separator-label.sep-pending td { background:#f3f3f3; border-color:#bbb    !important; color:#555;    }
tr.separator-label.sep-done    td { background:#e8f5e9; border-color:#43a047 !important; color:#1b5e20; }
tr.row-proses  { background:#fffde7 !important; }
tr.row-paused  { background:#fce4ec !important; }
tr.row-pending { background:#fafafa !important; }
.aksi-checker, .aksi-managerck {
    display:flex; flex-wrap:wrap; gap:6px; align-items:center; justify-content:center;
}
.aksi-checker .form-control, .aksi-managerck .form-control { width:70px !important; flex-shrink:0; }
.aksi-checker .btn, .aksi-managerck .btn { min-width:78px; white-space:nowrap; }
.badge-pintu {
    background:#343a40; color:#fff; font-size:11px;
    padding:2px 7px; border-radius:4px; white-space:nowrap;
}
.badge-pernah-pause {
    background:#e91e63; color:#fff; font-size:10px;
    padding:2px 5px; border-radius:3px; white-space:nowrap;
    display:inline-flex; align-items:center; gap:3px;
}
.btn-aksi-edit  { min-width:58px !important; }
.btn-aksi-hapus { min-width:58px !important; }
.btn-pause  { background:#ff6f00; color:#fff; border-color:#e65100; }
.btn-pause:hover  { background:#e65100; color:#fff; }
.btn-resume { background:#00897b; color:#fff; border-color:#00695c; }
.btn-resume:hover { background:#00695c; color:#fff; }
.btn-siapkan { background:#7b1fa2; color:#fff; border-color:#6a1b9a; }
.btn-siapkan:hover { background:#6a1b9a; color:#fff; }
@keyframes blink-pause { 0%,100%{opacity:1} 50%{opacity:.3} }
.pause-indicator { animation: blink-pause 1.2s infinite; color:#e91e63; font-size:10px; }

/* ── Wrapper aksi agar tombol Detail + aksi lain selaras ── */
.aksi-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}
.aksi-wrap .btn-detail {
    width: 100%;
    font-size: 11px;
    padding: 2px 8px;
    color: #495057;
    border-color: #ced4da;
}
.aksi-wrap .btn-detail:hover { background:#e9ecef; }
</style>

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

                <div class="row mb-3">
                    <?php if ($role === 'MANAGERWH') : ?>
                    <div class="col-auto">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modalBuatBongkaran">
                            <i class="fas fa-plus"></i> Buat Bongkaran
                        </button>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('checker/arsip') ?>" class="btn btn-secondary">
                            <i class="fas fa-archive"></i> Lihat Arsip
                        </a>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-danger" id="btnArchiveAll">
                            <i class="fas fa-box-archive mr-1"></i> Archive Aktivitas Hari Ini
                        </button>
                    </div>
                    <?php endif; ?>
                    <?php if (in_array($role, ['SALESCK','DIREKTURCK'])) : ?>
                    <div class="col-auto">
                        <a href="<?= base_url('checker/arsip') ?>" class="btn btn-secondary">
                            <i class="fas fa-archive mr-1"></i> Lihat Arsip
                        </a>
                    </div>
                    <?php endif; ?>
                    <?php if ($role === 'ADMLOG') : ?>
                    <div class="col-auto">
                        <button class="btn btn-info" data-toggle="modal" data-target="#modalTambahKK">
                            <i class="fas fa-plus"></i> Tambah Loading KK
                        </button>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-warning" data-toggle="modal" data-target="#modalTambahLK">
                            <i class="fas fa-plus"></i> Tambah Loading LK
                        </button>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('checker/arsip') ?>" class="btn btn-secondary">
                            <i class="fas fa-archive"></i> Lihat Arsip
                        </a>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- ============================================================
                    PANEL AKTIVITAS WAREHOUSE (ringkasan)
                ============================================================ -->
                <div class="card mb-3" style="border:none; box-shadow: 0 2px 12px rgba(0,0,0,0.10);">
                    <div class="card-header text-white" style="background: linear-gradient(135deg,#1a237e 0%,#1565c0 100%); border-radius:8px 8px 0 0;">
                        <h3 class="card-title font-weight-bold">
                            <i class="fas fa-broadcast-tower mr-2"></i>
                            Aktivitas Warehouse — <?= date('j/n/Y') ?>
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3" style="background:#fff;">
                        <?php
                        $nama_pintu_map = ['A1','A2','A3','A4','A5','A6','B1','B2','B3','C'];
                        $fn_pintu = fn($p) => isset($nama_pintu_map[($p??1)-1]) ? $nama_pintu_map[($p)-1] : 'P'.$p;
                        ?>
                        <div class="row">

                            <!-- Kolom Bongkaran -->
                            <div class="col-md-4 mb-2">
                                <div class="p-2 rounded h-100" style="background:#fff8e1; border-left:4px solid #f9a825;">
                                    <p class="mb-2" style="color:#e65100; font-weight:700; font-size:13px; text-transform:uppercase; letter-spacing:.04em;">
                                        <i class="fas fa-dolly mr-1"></i> Bongkaran
                                    </p>
                                    <?php if (empty($bongkaran)) : ?>
                                        <p class="mb-0" style="color:#aaa; font-size:12px; font-style:italic;">Tidak ada data</p>
                                    <?php endif; ?>
                                    <?php $no = 1; foreach ($bongkaran as $b) :
                                        $is_paused_b = !empty($b['is_paused']);
                                        if ($b['status'] === 'DONE')         { $dot = '#22c55e'; $label = 'done ✅'; }
                                        elseif ($is_paused_b)               { $dot = '#e91e63'; $label = 'di-pause ⏸️'; }
                                        elseif ($b['status'] === 'PROSES')   { $dot = '#f59e0b'; $label = 'proses ▶️'; }
                                        elseif ($b['status'] === 'MENUNGGU') { $dot = '#94a3b8'; $label = 'menunggu ⏳'; }
                                        else                                 { $dot = '#60a5fa'; $label = str_replace('_',' ',$b['status']); }
                                        $is_last_b = ($no === count($bongkaran));
                                    ?>
                                    <div class="d-flex align-items-center py-1"
                                        style="font-size:12px; <?= !$is_last_b ? 'border-bottom:1px solid #fde68a;' : '' ?>">
                                        <span style="width:8px;height:8px;border-radius:50%;background:<?= $dot ?>;flex-shrink:0;" class="mr-2"></span>
                                        <span class="font-weight-bold mr-1" style="color:#333; flex-shrink:0;"><?= $no++ ?>.</span>
                                        <span style="color:#444; flex:1; min-width:0;" class="text-truncate">
                                            <?= htmlspecialchars($b['keterangan'] ?? $b['kode_bongkar']) ?>
                                        </span>
                                        <?php if (!empty($b['pernah_pause'])) : ?>
                                            <span class="badge-pernah-pause mr-1"><i class="fas fa-pause"></i></span>
                                        <?php endif; ?>
                                        <?php if (!empty($b['pintu'])) : ?>
                                            <span style="font-size:10px; color:#555; flex-shrink:0; margin:0 3px;">
                                                <i class="fas fa-door-open" style="font-size:9px;"></i> <?= $fn_pintu($b['pintu']) ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if (!empty($b['nm_checker'])) : ?>
                                            <span style="font-size:11px; color:#888; flex-shrink:0; margin:0 4px;">
                                                <i class="fas fa-user" style="font-size:10px;"></i> <?= htmlspecialchars($b['nm_checker']) ?>
                                            </span>
                                        <?php endif; ?>
                                        <span class="font-weight-bold" style="color:<?= $dot ?>; flex-shrink:0;"><?= $label ?></span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Kolom Loading LK -->
                            <div class="col-md-4 mb-2">
                                <div class="p-2 rounded h-100" style="background:#e3f2fd; border-left:4px solid #1565c0;">
                                    <p class="mb-2" style="color:#1565c0; font-weight:700; font-size:13px; text-transform:uppercase; letter-spacing:.04em;">
                                        <i class="fas fa-truck mr-1"></i> Loading LK
                                    </p>
                                    <?php if (empty($list_lk)) : ?>
                                        <p class="mb-0" style="color:#aaa; font-size:12px; font-style:italic;">Tidak ada data</p>
                                    <?php endif; ?>
                                    <?php $no = 1; foreach ($list_lk as $lk) :
                                        $is_paused_lk = !empty($lk['is_paused']) || !empty($lk['is_paused_siapkan']);
                                        if ($is_paused_lk) {
                                            $lbl = ['dot'=>'#e91e63','teks'=>'di-pause ⏸️'];
                                        } else {
                                            $lbl = [
                                                'MENUNGGU'         => ['dot'=>'#94a3b8','teks'=>'menunggu ⏳'],
                                                'CETAK_DO'         => ['dot'=>'#a78bfa','teks'=>'cetak DO 🖨️'],
                                                'DO_SELESAI'       => ['dot'=>'#fb923c','teks'=>'DO selesai 📄'],
                                                'PENYIAPAN_BARANG' => ['dot'=>'#7b1fa2','teks'=>'siapkan barang 📦'],
                                                'SIAP_LOADING'     => ['dot'=>'#0288d1','teks'=>'siap loading 🟦'],
                                                'PROSES_LOADING'   => ['dot'=>'#f59e0b','teks'=>'proses loading ▶️'],
                                                'DONE'             => ['dot'=>'#22c55e','teks'=>'done ✅'],
                                            ][$lk['status']] ?? ['dot'=>'#aaa','teks'=>$lk['status']];
                                        }
                                        $is_last_lk = ($no === count($list_lk));
                                    ?>
                                    <div class="d-flex align-items-center py-1"
                                        style="font-size:12px; <?= !$is_last_lk ? 'border-bottom:1px solid #bfdbfe;' : '' ?>">
                                        <span style="width:8px;height:8px;border-radius:50%;background:<?= $lbl['dot'] ?>;flex-shrink:0;" class="mr-2"></span>
                                        <span class="font-weight-bold mr-1" style="color:#333; flex-shrink:0;"><?= $no++ ?>.</span>
                                        <span style="color:#444; flex:1; min-width:0;" class="text-truncate">
                                            <?= htmlspecialchars($lk['keterangan']) ?>
                                        </span>
                                        <?php if (!empty($lk['pernah_pause'])) : ?>
                                            <span class="badge-pernah-pause mr-1"><i class="fas fa-pause"></i></span>
                                        <?php endif; ?>
                                        <?php if (!empty($lk['pintu'])) : ?>
                                            <span style="font-size:10px; color:#555; flex-shrink:0; margin:0 3px;">
                                                <i class="fas fa-door-open" style="font-size:9px;"></i> <?= $fn_pintu($lk['pintu']) ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if (!empty($lk['nm_checker'])) : ?>
                                            <span style="font-size:11px; color:#888; flex-shrink:0; margin:0 4px;">
                                                <i class="fas fa-user" style="font-size:10px;"></i> <?= htmlspecialchars($lk['nm_checker']) ?>
                                            </span>
                                        <?php endif; ?>
                                        <span class="font-weight-bold" style="color:<?= $lbl['dot'] ?>; flex-shrink:0;"><?= $lbl['teks'] ?></span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Kolom Loading KK -->
                            <div class="col-md-4 mb-2">
                                <div class="p-2 rounded h-100" style="background:#e8f5e9; border-left:4px solid #1b5e20;">
                                    <p class="mb-2" style="color:#1b5e20; font-weight:700; font-size:13px; text-transform:uppercase; letter-spacing:.04em;">
                                        <i class="fas fa-truck-loading mr-1"></i> Loading KK
                                    </p>
                                    <?php if (empty($list_kk)) : ?>
                                        <p class="mb-0" style="color:#aaa; font-size:12px; font-style:italic;">Tidak ada data</p>
                                    <?php endif; ?>
                                    <?php $no = 1; foreach ($list_kk as $kk) :
                                        $is_paused_kk = !empty($kk['is_paused']) || !empty($kk['is_paused_siapkan']);
                                        if ($is_paused_kk) {
                                            $lbl = ['dot'=>'#e91e63','teks'=>'di-pause ⏸️'];
                                        } else {
                                            $lbl = [
                                                'MENUNGGU'         => ['dot'=>'#94a3b8','teks'=>'menunggu ⏳'],
                                                'CETAK_DO'         => ['dot'=>'#a78bfa','teks'=>'cetak DO 🖨️'],
                                                'DO_SELESAI'       => ['dot'=>'#fb923c','teks'=>'DO selesai 📄'],
                                                'PENYIAPAN_BARANG' => ['dot'=>'#7b1fa2','teks'=>'siapkan barang 📦'],
                                                'SIAP_LOADING'     => ['dot'=>'#0288d1','teks'=>'siap loading 🟦'],
                                                'PROSES_LOADING'   => ['dot'=>'#f59e0b','teks'=>'proses loading ▶️'],
                                                'DONE'             => ['dot'=>'#22c55e','teks'=>'done ✅'],
                                            ][$kk['status']] ?? ['dot'=>'#aaa','teks'=>$kk['status']];
                                        }
                                        $is_last_kk = ($no === count($list_kk));
                                    ?>
                                    <div class="d-flex align-items-center py-1"
                                        style="font-size:12px; <?= !$is_last_kk ? 'border-bottom:1px solid #bbf7d0;' : '' ?>">
                                        <span style="width:8px;height:8px;border-radius:50%;background:<?= $lbl['dot'] ?>;flex-shrink:0;" class="mr-2"></span>
                                        <span class="font-weight-bold mr-1" style="color:#333; flex-shrink:0;"><?= $no++ ?>.</span>
                                        <span style="color:#444; flex:1; min-width:0;" class="text-truncate">
                                            <?= htmlspecialchars($kk['keterangan']) ?>
                                        </span>
                                        <?php if (!empty($kk['pernah_pause'])) : ?>
                                            <span class="badge-pernah-pause mr-1"><i class="fas fa-pause"></i></span>
                                        <?php endif; ?>
                                        <?php if (!empty($kk['pintu'])) : ?>
                                            <span style="font-size:10px; color:#555; flex-shrink:0; margin:0 3px;">
                                                <i class="fas fa-door-open" style="font-size:9px;"></i> <?= $fn_pintu($kk['pintu']) ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if (!empty($kk['nm_checker'])) : ?>
                                            <span style="font-size:11px; color:#888; flex-shrink:0; margin:0 4px;">
                                                <i class="fas fa-user" style="font-size:10px;"></i> <?= htmlspecialchars($kk['nm_checker']) ?>
                                            </span>
                                        <?php endif; ?>
                                        <span class="font-weight-bold" style="color:<?= $lbl['dot'] ?>; flex-shrink:0;"><?= $lbl['teks'] ?></span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                        </div>

                        <!-- Footer ringkasan -->
                        <?php
                        $aktif_bongkar = array_filter($bongkaran, fn($r) => $r['status'] === 'PROSES');
                        $aktif_lk      = array_filter($list_lk,   fn($r) => in_array($r['status'], ['PROSES_LOADING','PENYIAPAN_BARANG']));
                        $aktif_kk      = array_filter($list_kk,   fn($r) => in_array($r['status'], ['PROSES_LOADING','PENYIAPAN_BARANG']));
                        $total_aktif   = count($aktif_bongkar) + count($aktif_lk) + count($aktif_kk);

                        $selesai_bongkar = array_filter($bongkaran, fn($r) => $r['status'] === 'DONE');
                        $selesai_lk      = array_filter($list_lk,   fn($r) => $r['status'] === 'DONE');
                        $selesai_kk      = array_filter($list_kk,   fn($r) => $r['status'] === 'DONE');
                        $total_selesai   = count($selesai_bongkar) + count($selesai_lk) + count($selesai_kk);

                        $pause_bongkar = array_filter($bongkaran, fn($r) => !empty($r['is_paused']));
                        $pause_lk      = array_filter($list_lk,   fn($r) => !empty($r['is_paused']) || !empty($r['is_paused_siapkan']));
                        $pause_kk      = array_filter($list_kk,   fn($r) => !empty($r['is_paused']) || !empty($r['is_paused_siapkan']));
                        $total_pause   = count($pause_bongkar) + count($pause_lk) + count($pause_kk);
                        ?>
                        <hr style="border-color:#e0e0e0; margin:10px 0 8px;">
                        <div class="d-flex justify-content-center align-items-center" style="gap:16px; flex-wrap:wrap;">
                            <span style="font-size:13px; font-weight:700; color:#1565c0;">
                                <i class="fas fa-warehouse mr-1"></i>
                                Aktif: <span style="font-size:16px; color:#e65100;"><?= $total_aktif ?></span> pintu berjalan
                            </span>
                            <?php if ($total_pause > 0) : ?>
                            <span style="font-size:13px; font-weight:700; color:#e91e63;">
                                <i class="fas fa-pause-circle mr-1"></i>
                                Pause: <span style="font-size:16px;"><?= $total_pause ?></span> aktivitas
                            </span>
                            <?php endif; ?>
                            <span style="font-size:13px; font-weight:700; color:#1b5e20;">
                                <i class="fas fa-check-circle mr-1"></i>
                                Selesai: <span style="font-size:16px; color:#22c55e;"><?= $total_selesai ?></span> aktivitas
                            </span>
                        </div>
                    </div>
                </div>


                <!-- ============================================================
                    TABEL BONGKARAN
                ============================================================ -->
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h3 class="card-title"><i class="fas fa-dolly mr-2"></i> Data Bongkaran</h3>
                    </div>
                    <div class="card-body table-responsive">
                        <?php
                        // Bongkaran tidak punya halaman detail, jadi kolom Aksi hanya untuk role operasional
                        $has_aksi_b = in_array($role, ['CHECKER','MANAGERCK','MANAGERWH','ADMLOG']);
                        $can_edit_b = ($role === 'MANAGERWH');
                        $colspan_b  = $has_aksi_b ? 12 : 11;
                        $b_aktif    = array_filter($bongkaran, fn($r) => in_array($r['status'], ['PROSES','PENYIAPAN_BARANG','CETAK_DO']));
                        $b_pending  = array_filter($bongkaran, fn($r) => $r['status'] === 'MENUNGGU');
                        $b_done     = array_filter($bongkaran, fn($r) => $r['status'] === 'DONE');

                        $renderBongkaran = function($b) use (&$no, $role, $nik, $my_active_id, $has_aksi_b, $can_edit_b, $fn_pintu) {
                            $is_done         = ($b['status'] === 'DONE');
                            $is_taken        = !empty($b['nik_checker']);
                            $is_my_job       = ($b['nik_checker'] === $nik);
                            $progres         = (int)($b['progres'] ?? 0);
                            $is_done_checker = ($b['status_checker'] === 'DONE');
                            $is_paused       = !empty($b['is_paused']);
                            $pernah_pause    = !empty($b['pernah_pause']);
                            $can_edit_row    = ($can_edit_b && $b['status'] === 'MENUNGGU');
                            $badge = ['MENUNGGU'=>'badge-secondary','PROSES'=>'badge-warning',
                                      'PENYIAPAN_BARANG'=>'badge-info','CETAK_DO'=>'badge-primary',
                                      'DONE'=>'badge-success'][$b['status']] ?? 'badge-secondary';
                            if ($is_done)                          $rc = 'table-success';
                            elseif ($is_paused)                    $rc = 'row-paused';
                            elseif ($b['status'] === 'MENUNGGU')   $rc = 'row-pending';
                            else                                   $rc = 'row-proses';

                            $durasi_html = '<small class="text-muted">-</small>';
                            if ($is_taken && !empty($b['waktu_mulai'])) {
                                $akhir = ((!empty($b['waktu_selesai']) && $is_done_checker) ? strtotime($b['waktu_selesai']) : time());
                                if ($is_paused && !empty($b['paused_at'])) $akhir = strtotime($b['paused_at']);
                                $bruto   = $akhir - strtotime($b['waktu_mulai']);
                                $total_p = (int)($b['total_pause_secs'] ?? 0);
                                $selisih = max(0, $bruto - $total_p);
                                if ($selisih > 0) {
                                    $j = floor($selisih / 3600); $m = floor(($selisih % 3600) / 60);
                                    $durasi_str = ($j > 0 ? $j . ' jam ' : '') . $m . ' menit';
                                    if (!$is_done_checker && !$is_paused)
                                        $durasi_html = '<small>' . $durasi_str . ' <span class="badge badge-warning" style="font-size:9px;">live</span></small>';
                                    elseif ($is_paused)
                                        $durasi_html = '<small>' . $durasi_str . ' <span class="pause-indicator"><i class="fas fa-pause"></i> pause</span></small>';
                                    else
                                        $durasi_html = '<small>' . $durasi_str . '</small>';
                                }
                            }
                        ?>
                        <tr class="<?= $rc ?>">
                            <td><?= $no++ ?></td>
                            <td><small><?= date('d/m/Y', strtotime($b['created_at'])) ?></small></td>
                            <td>
                                <small><?= htmlspecialchars($b['kode_bongkar']) ?></small>
                                <?php if ($pernah_pause) : ?>
                                    <span class="badge-pernah-pause ml-1" title="Pernah di-pause"><i class="fas fa-pause"></i></span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($b['keterangan'] ?? '-') ?></td>
                            <td><?= $is_taken ? htmlspecialchars($b['nm_checker']) : '<span class="text-muted">-</span>' ?></td>
                            <td class="text-center">
                                <?php if (!empty($b['pintu'])): ?>
                                    <span class="badge-pintu"><i class="fas fa-door-open mr-1"></i><?= $fn_pintu($b['pintu']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><small><?= $is_taken ? ($b['waktu_mulai'] ?? '-') : '-' ?></small></td>
                            <td><small><?= $is_done_checker ? ($b['waktu_selesai'] ?? '-') : '-' ?></small></td>
                            <td style="min-width:110px;">
                                <div class="progress" style="height:16px;">
                                    <div class="progress-bar <?= $progres==100?'bg-success':($is_paused?'bg-danger':'bg-warning') ?>" style="width:<?= $progres ?>%"><?= $progres ?>%</div>
                                </div>
                            </td>
                            <td class="text-center" style="min-width:110px;"><?= $durasi_html ?></td>
                            <td class="text-center">
                                <?php if ($is_paused) : ?>
                                    <span class="badge badge-danger"><i class="fas fa-pause mr-1"></i>PAUSE</span>
                                <?php else : ?>
                                    <span class="badge <?= $badge ?>"><?= str_replace('_',' ',$b['status']) ?></span>
                                <?php endif; ?>
                            </td>
                            <?php if ($has_aksi_b) : ?>
                            <td class="text-center" style="min-width:200px;">
                                <?php if ($role === 'CHECKER') : ?>
                                    <?php if (!$is_taken && $my_active_id === null) : ?>
                                        <button class="btn btn-sm btn-success btn-start" data-id="<?= $b['id'] ?>"><i class="fas fa-play"></i> Start</button>
                                    <?php elseif (!$is_taken && $my_active_id !== null) : ?>
                                        <span class="badge badge-secondary">Selesaikan job Anda dulu</span>
                                    <?php elseif ($is_my_job && $b['status_checker'] === 'PROSES') : ?>
                                        <?php if ($is_paused) : ?>
                                        <div class="aksi-checker">
                                            <button class="btn btn-sm btn-resume btn-resume-bongkaran" data-id="<?= $b['id'] ?>"><i class="fas fa-play mr-1"></i>Lanjut</button>
                                        </div>
                                        <?php else : ?>
                                        <div class="aksi-checker">
                                            <select class="form-control form-control-sm select-progres" data-id="<?= $b['id'] ?>">
                                                <?php foreach ([0,10,20,30,40,50,60,70,80,90] as $p) : ?><option value="<?= $p ?>" <?= $progres==$p?'selected':'' ?>><?= $p ?>%</option><?php endforeach; ?>
                                            </select>
                                            <button class="btn btn-sm btn-warning btn-update-progres" data-id="<?= $b['id'] ?>"><i class="fas fa-sync"></i> Update</button>
                                            <button class="btn btn-sm btn-pause btn-pause-bongkaran" data-id="<?= $b['id'] ?>"><i class="fas fa-pause"></i> Pause</button>
                                            <button class="btn btn-sm btn-primary btn-done" data-id="<?= $b['id'] ?>"><i class="fas fa-check"></i> Done</button>
                                        </div>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <span class="text-muted small"><i class="fas fa-lock mr-1"></i><?= htmlspecialchars($b['nm_checker'] ?? 'checker lain') ?></span>
                                    <?php endif; ?>
                                <?php elseif ($role === 'MANAGERCK') : ?>
                                    <?php if (!$is_taken) : ?>
                                        <button class="btn btn-sm btn-success btn-start-mck" data-id="<?= $b['id'] ?>" data-type="bongkaran"><i class="fas fa-play"></i> Start</button>
                                    <?php elseif ($b['status'] === 'PROSES') : ?>
                                        <?php if ($is_paused) : ?>
                                        <div class="aksi-managerck">
                                            <button class="btn btn-sm btn-resume btn-resume-bongkaran" data-id="<?= $b['id'] ?>"><i class="fas fa-play mr-1"></i>Lanjut</button>
                                        </div>
                                        <?php else : ?>
                                        <div class="aksi-managerck">
                                            <select class="form-control form-control-sm select-progres" data-id="<?= $b['id'] ?>">
                                                <?php foreach ([0,10,20,30,40,50,60,70,80,90] as $p) : ?><option value="<?= $p ?>" <?= $progres==$p?'selected':'' ?>><?= $p ?>%</option><?php endforeach; ?>
                                            </select>
                                            <button class="btn btn-sm btn-warning btn-update-progres" data-id="<?= $b['id'] ?>"><i class="fas fa-sync"></i> Update</button>
                                            <button class="btn btn-sm btn-pause btn-pause-bongkaran" data-id="<?= $b['id'] ?>"><i class="fas fa-pause"></i> Pause</button>
                                            <button class="btn btn-sm btn-primary btn-done" data-id="<?= $b['id'] ?>"><i class="fas fa-check"></i> Done</button>
                                        </div>
                                        <?php endif; ?>
                                    <?php else : ?><span class="text-muted small">—</span><?php endif; ?>
                                <?php elseif ($role === 'MANAGERWH') : ?>
                                    <?php if ($can_edit_row) : ?>
                                        <button class="btn btn-sm btn-warning btn-aksi-edit btn-edit-bongkaran mr-1"
                                            data-id="<?= $b['id'] ?>"
                                            data-ket="<?= htmlspecialchars($b['keterangan'] ?? '') ?>">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger btn-aksi-hapus btn-hapus-bongkaran"
                                            data-id="<?= $b['id'] ?>"
                                            data-ket="<?= htmlspecialchars($b['keterangan'] ?? $b['kode_bongkar']) ?>">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    <?php else : ?>
                                        <span class="text-muted small">—</span>
                                    <?php endif; ?>
                                <?php else : ?><span class="text-muted small">—</span><?php endif; ?>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php }; ?>

                        <table class="table table-bordered table-sm" id="tabelBongkaran">
                            <thead class="thead-dark text-center">
                                <tr>
                                    <th>#</th><th>Tgl Dibuat</th><th>Kode</th><th>Keterangan</th><th>Checker</th>
                                    <th>Pintu</th><th>Mulai</th><th>Selesai</th><th>Progres</th><th>Durasi </th><th>Status</th>
                                    <?php if ($has_aksi_b) : ?><th class="text-center">Aksi</th><?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $no=1; ?>
                            <?php if (!empty($b_aktif)) : ?>
                            <tr class="separator-label sep-active"><td colspan="<?= $colspan_b ?>"><i class="fas fa-circle-notch fa-spin mr-1"></i> Sedang Proses</td></tr>
                            <?php foreach ($b_aktif as $b) : $renderBongkaran($b); endforeach; ?>
                            <?php endif; ?>
                            <?php if (!empty($b_pending)) : ?>
                            <tr class="separator-label sep-pending"><td colspan="<?= $colspan_b ?>"><i class="fas fa-hourglass-half mr-1"></i> Menunggu / Belum Dikerjakan</td></tr>
                            <?php foreach ($b_pending as $b) : $renderBongkaran($b); endforeach; ?>
                            <?php endif; ?>
                            <?php if (!empty($b_done)) : ?>
                            <tr class="separator-label sep-done"><td colspan="<?= $colspan_b ?>"><i class="fas fa-check-circle mr-1"></i> Sudah Selesai / Done</td></tr>
                            <?php foreach ($b_done as $b) : $renderBongkaran($b); endforeach; ?>
                            <?php endif; ?>
                            <?php if (empty($bongkaran)) : ?>
                            <tr><td colspan="<?= $colspan_b ?>" class="text-center text-muted">Tidak ada data bongkaran</td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ============================================================
                    TABEL LOADING LK (header biru)
                ============================================================ -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title"><i class="fas fa-truck mr-2"></i> Loading LK</h3>
                    </div>
                    <div class="card-body table-responsive">
                        <?php
                        // Semua role bisa lihat detail LK — kolom Detail+Aksi selalu ada
                        $has_aksi_lk = true; // selalu tampil kolom aksi (minimal tombol Detail)
                        $can_ops_lk  = in_array($role, ['ADMLOG','CHECKER','MANAGERCK']); // yang bisa operasi
                        $can_edit_lk = ($role === 'ADMLOG');
                        $colspan_lk  = 13;
                        $lk_aktif    = array_filter($list_lk, fn($r) => in_array($r['status'], ['PROSES_LOADING','PENYIAPAN_BARANG','SIAP_LOADING']));
                        $lk_pending  = array_filter($list_lk, fn($r) => in_array($r['status'], ['MENUNGGU','CETAK_DO','DO_SELESAI']));
                        $lk_done     = array_filter($list_lk, fn($r) => $r['status'] === 'DONE');

                        $renderLK = function($lk) use (&$no, $role, $nik, $has_aksi_lk, $can_ops_lk, $can_edit_lk, $fn_pintu) {
                            $lk_done_s         = $lk['status'] === 'DONE';
                            $lk_progres        = (int)($lk['progres'] ?? 0);
                            $lk_started        = $lk['status'] === 'PROSES_LOADING';
                            $lk_siapkan        = $lk['status'] === 'PENYIAPAN_BARANG';
                            $lk_siap_loading   = $lk['status'] === 'SIAP_LOADING';
                            $is_paused         = !empty($lk['is_paused']);
                            $is_paused_siapkan = !empty($lk['is_paused_siapkan']);
                            $pernah_pause      = !empty($lk['pernah_pause']) || !empty($lk['pernah_pause_siapkan']);
                            $progres_siapkan   = (int)($lk['progres_siapkan'] ?? 0);
                            $can_edit_row      = ($can_edit_lk && in_array($lk['status'], ['MENUNGGU','CETAK_DO','DO_SELESAI']));
                            $badge_lk          = [
                                'CETAK_DO'         => 'badge-info',
                                'DO_SELESAI'       => 'badge-warning',
                                'PENYIAPAN_BARANG' => 'badge-secondary',
                                'SIAP_LOADING'     => 'badge-primary',
                                'PROSES_LOADING'   => 'badge-warning',
                                'DONE'             => 'badge-success',
                            ][$lk['status']] ?? 'badge-secondary';
                            $rc = $lk_done_s ? 'table-success'
                                : (($is_paused || $is_paused_siapkan) ? 'row-paused'
                                : (($lk_started || $lk_siapkan || $lk_siap_loading) ? 'row-proses'
                                : 'row-pending'));

                            $lk_durasi_html = '<small>-</small>';
                            if (!empty($lk['waktu_mulai'])) {
                                // Tentukan titik akhir hitung durasi
                                if ($lk_done_s && !empty($lk['waktu_selesai'])) {
                                    $akhir = strtotime($lk['waktu_selesai']);          // selesai → pakai waktu_selesai
                                } elseif ($is_paused && !empty($lk['paused_at'])) {
                                    $akhir = strtotime($lk['paused_at']);              // pause loading → beku di paused_at
                                } elseif ($is_paused_siapkan && !empty($lk['paused_at_siapkan'])) {
                                    $akhir = strtotime($lk['paused_at_siapkan']);      // pause siapkan → beku di paused_at_siapkan
                                } else {
                                    $akhir = time();                                    // live
                                }
                                $bruto   = max(0, $akhir - strtotime($lk['waktu_mulai']));
                                $total_p = (int)($lk['total_pause_secs'] ?? 0) + (int)($lk['total_pause_secs_siapkan'] ?? 0);
                                $sel     = max(0, $bruto - $total_p);
                                if ($sel > 0) {
                                    $j = floor($sel/3600); $m = floor(($sel%3600)/60);
                                    $lk_durasi = ($j>0?"{$j} jam ":"")."{$m} menit";
                                    if ($is_paused || $is_paused_siapkan)
                                        $lk_durasi_html = '<small>'.$lk_durasi.' <span class="pause-indicator"><i class="fas fa-pause"></i> pause</span></small>';
                                    elseif ($lk_done_s)
                                        $lk_durasi_html = '<small>'.$lk_durasi.'</small>';
                                    else
                                        $lk_durasi_html = '<small>'.$lk_durasi.' <span class="badge badge-warning" style="font-size:9px;">live</span></small>';
                                }
                            }
                        ?>
                        <tr class="<?= $rc ?>">
                            <td><?= $no++ ?></td>
                            <td>
                                <small><?= htmlspecialchars($lk['kode'] ?? '-') ?></small>
                                <?php if ($pernah_pause) : ?>
                                    <span class="badge-pernah-pause ml-1" title="Pernah di-pause"><i class="fas fa-pause"></i></span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($lk['keterangan']) ?></td>
                            <td><small><?= $lk['tgl'] ?></small></td>
                            <td><?= !empty($lk['nm_checker']) ? htmlspecialchars($lk['nm_checker']) : '<span class="text-muted">-</span>' ?></td>
                            <td class="text-center">
                                <?php if (!empty($lk['pintu'])): ?>
                                    <span class="badge-pintu"><i class="fas fa-door-open mr-1"></i><?= $fn_pintu($lk['pintu']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($lk['waktu_do_selesai'])): ?>
                                    <small class="text-warning font-weight-bold">
                                        <i class="fas fa-file-alt mr-1"></i><?= date('d/m H:i', strtotime($lk['waktu_do_selesai'])) ?>
                                    </small>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><small><?= !empty($lk['waktu_mulai']) ? date('d/m H:i', strtotime($lk['waktu_mulai'])) : '-' ?></small></td>
                            <td><small><?= !empty($lk['waktu_selesai']) ? date('d/m H:i', strtotime($lk['waktu_selesai'])) : '-' ?></small></td>
                            <td style="min-width:100px;">
                                <div class="progress" style="height:14px;">
                                    <div class="progress-bar <?= $lk_progres==100?'bg-success':($is_paused?'bg-danger':'bg-primary') ?>" style="width:<?= $lk_progres ?>%"><?= $lk_progres ?>%</div>
                                </div>
                            </td>
                            <td><?= $lk_durasi_html ?></td>
                            <td class="text-center">
                                <?php if ($is_paused_siapkan) : ?>
                                    <span class="badge badge-danger"><i class="fas fa-pause mr-1"></i>PAUSE SIAPKAN</span>
                                <?php elseif ($is_paused) : ?>
                                    <span class="badge badge-danger"><i class="fas fa-pause mr-1"></i>PAUSE</span>
                                <?php elseif ($role === 'ADMLOG' && in_array($lk['status'], ['CETAK_DO','DO_SELESAI'])): ?>
                                    <select class="form-control form-control-sm select-status-lk" data-id="<?= $lk['id'] ?>" style="min-width:120px;">
                                        <?php foreach (['CETAK_DO','DO_SELESAI'] as $s): ?><option value="<?= $s ?>" <?= $lk['status']===$s?'selected':'' ?>><?= str_replace('_',' ',$s) ?></option><?php endforeach; ?>
                                    </select>
                                <?php else: ?>
                                    <span class="badge <?= $badge_lk ?>"><?= str_replace('_',' ',$lk['status']) ?></span>
                                <?php endif; ?>
                            </td>
                            <!-- Kolom Aksi — semua role lihat tombol Detail, role operasional lihat aksi tambahan -->
                            <td class="text-center" style="min-width:200px;">
                                <div class="aksi-wrap">
                                    <?php
                                    $show_detail_lk = false;
                                    if ($role === 'ADMLOG') {
                                        $show_detail_lk = in_array($lk['status'], ['PROSES_LOADING','PENYIAPAN_BARANG','SIAP_LOADING','DONE']);
                                    } elseif (in_array($role, ['MANAGERCK','CHECKER'])) {
                                        $show_detail_lk = ($lk['status'] === 'DONE');
                                    } else {
                                        // role lain (MANAGERWH, SALESCK, DIREKTURCK, dll) tetap selalu tampil
                                        $show_detail_lk = true;
                                    }
                                    ?>
                                    <?php if ($show_detail_lk): ?>
                                    <a href="<?= base_url('checker/detail_lk/' . $lk['id']) ?>"
                                    class="btn btn-sm btn-outline-secondary btn-detail">
                                        <i class="fas fa-eye mr-1"></i> Detail
                                    </a>
                                    <?php endif; ?>

                                    <?php if ($can_ops_lk): ?>
                                    <?php if ($role === 'ADMLOG'): ?>
                                        <?php if ($can_edit_row): ?>
                                            <div style="display:flex; gap:4px; justify-content:center; align-items:center; flex-wrap:wrap;">
                                                <?php if (in_array($lk['status'], ['CETAK_DO','DO_SELESAI'])): ?>
                                                    <button class="btn btn-sm btn-info btn-simpan-lk" data-id="<?= $lk['id'] ?>">
                                                        <i class="fas fa-save"></i> Simpan
                                                    </button>
                                                <?php endif; ?>
                                                <button class="btn btn-sm btn-warning btn-edit-lk"
                                                    data-id="<?= $lk['id'] ?>"
                                                    data-ket="<?= htmlspecialchars($lk['keterangan']) ?>">
                                                    <i class="fas fa-pencil-alt"></i> Edit
                                                </button>
                                                <button class="btn btn-sm btn-danger btn-hapus-lk"
                                                    data-id="<?= $lk['id'] ?>"
                                                    data-ket="<?= htmlspecialchars($lk['keterangan']) ?>">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </div>
                                        <?php endif; ?>

                                    <?php elseif ($role === 'CHECKER'): ?>
                                        <?php if ($lk['status'] === 'DO_SELESAI' && !$is_paused_siapkan && !$is_paused): ?>
                                            <button class="btn btn-sm btn-siapkan btn-start-siapkan-lk" data-id="<?= $lk['id'] ?>">
                                                <i class="fas fa-boxes mr-1"></i> Siapkan Barang
                                            </button>
                                        <?php elseif ($lk_siapkan && $lk['nik_checker'] === $nik): ?>
                                            <?php if ($is_paused_siapkan) : ?>
                                                <button class="btn btn-sm btn-resume btn-resume-siapkan-lk" data-id="<?= $lk['id'] ?>">
                                                    <i class="fas fa-play mr-1"></i>Lanjut Siapkan
                                                </button>
                                            <?php else : ?>
                                                <div class="aksi-checker">
                                                    <button class="btn btn-sm btn-pause btn-pause-siapkan-lk" data-id="<?= $lk['id'] ?>">
                                                        <i class="fas fa-pause"></i> Pause
                                                    </button>
                                                    <button class="btn btn-sm btn-primary btn-done-siapkan-lk" data-id="<?= $lk['id'] ?>">
                                                        <i class="fas fa-check"></i> Selesai Siapkan
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        <?php elseif ($lk_siapkan): ?>
                                            <span class="text-muted small"><i class="fas fa-lock mr-1"></i><?= htmlspecialchars($lk['nm_checker'] ?? '') ?> sedang menyiapkan</span>
                                        <?php elseif ($lk_siap_loading): ?>
                                            <button class="btn btn-sm btn-success btn-start-loading-lk" data-id="<?= $lk['id'] ?>">
                                                <i class="fas fa-play mr-1"></i> Start Loading
                                            </button>
                                        <?php elseif ($lk_started && $lk['nik_checker'] === $nik): ?>
                                            <?php if ($is_paused) : ?>
                                                <button class="btn btn-sm btn-resume btn-resume-lk" data-id="<?= $lk['id'] ?>">
                                                    <i class="fas fa-play mr-1"></i>Lanjut Loading
                                                </button>
                                            <?php else : ?>
                                                <div class="aksi-checker">
                                                    <select class="form-control form-control-sm select-progres-lk">
                                                        <?php foreach ([0,10,20,30,40,50,60,70,80,90] as $p): ?><option value="<?= $p ?>" <?= $lk_progres==$p?'selected':'' ?>><?= $p ?>%</option><?php endforeach; ?>
                                                    </select>
                                                    <button class="btn btn-sm btn-warning btn-update-progres-lk" data-id="<?= $lk['id'] ?>"><i class="fas fa-sync"></i> Update</button>
                                                    <button class="btn btn-sm btn-pause btn-pause-lk" data-id="<?= $lk['id'] ?>"><i class="fas fa-pause"></i> Pause</button>
                                                    <button class="btn btn-sm btn-primary btn-done-lk" data-id="<?= $lk['id'] ?>"><i class="fas fa-check"></i> Done</button>
                                                </div>
                                            <?php endif; ?>
                                        <?php elseif ($lk_started): ?>
                                            <span class="text-muted small"><i class="fas fa-lock mr-1"></i><?= htmlspecialchars($lk['nm_checker'] ?? '') ?></span>
                                        <?php endif; ?>

                                    <?php elseif ($role === 'MANAGERCK'): ?>
                                        <?php if ($lk['status'] === 'DO_SELESAI' && !$is_paused_siapkan && !$is_paused): ?>
                                            <button class="btn btn-sm btn-siapkan btn-start-siapkan-mck" data-id="<?= $lk['id'] ?>" data-type="lk">
                                                <i class="fas fa-boxes mr-1"></i> Siapkan Barang
                                            </button>
                                        <?php elseif ($lk_siapkan): ?>
                                            <?php if ($is_paused_siapkan) : ?>
                                                <button class="btn btn-sm btn-resume btn-resume-siapkan-lk" data-id="<?= $lk['id'] ?>">
                                                    <i class="fas fa-play mr-1"></i>Lanjut Siapkan
                                                </button>
                                            <?php else : ?>
                                                <div class="aksi-managerck">
                                                    <button class="btn btn-sm btn-pause btn-pause-siapkan-lk" data-id="<?= $lk['id'] ?>">
                                                        <i class="fas fa-pause"></i> Pause
                                                    </button>
                                                    <button class="btn btn-sm btn-primary btn-done-siapkan-lk" data-id="<?= $lk['id'] ?>">
                                                        <i class="fas fa-check"></i> Selesai Siapkan
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        <?php elseif ($lk_siap_loading): ?>
                                            <button class="btn btn-sm btn-success btn-start-loading-mck" data-id="<?= $lk['id'] ?>" data-type="lk">
                                                <i class="fas fa-play mr-1"></i> Start Loading
                                            </button>
                                        <?php elseif ($lk_started): ?>
                                            <?php if ($is_paused) : ?>
                                                <button class="btn btn-sm btn-resume btn-resume-lk" data-id="<?= $lk['id'] ?>">
                                                    <i class="fas fa-play mr-1"></i>Lanjut Loading
                                                </button>
                                            <?php else : ?>
                                                <div class="aksi-managerck">
                                                    <select class="form-control form-control-sm select-progres-lk">
                                                        <?php foreach ([0,10,20,30,40,50,60,70,80,90] as $p): ?><option value="<?= $p ?>" <?= $lk_progres==$p?'selected':'' ?>><?= $p ?>%</option><?php endforeach; ?>
                                                    </select>
                                                    <button class="btn btn-sm btn-warning btn-update-progres-lk" data-id="<?= $lk['id'] ?>"><i class="fas fa-sync"></i> Update</button>
                                                    <button class="btn btn-sm btn-pause btn-pause-lk" data-id="<?= $lk['id'] ?>"><i class="fas fa-pause"></i> Pause</button>
                                                    <button class="btn btn-sm btn-primary btn-done-lk" data-id="<?= $lk['id'] ?>"><i class="fas fa-check"></i> Done</button>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; // end can_ops_lk roles ?>
                                    <?php endif; // end can_ops_lk ?>
                                </div>
                            </td>
                        </tr>
                        <?php }; ?>

                        <table class="table table-bordered table-sm" id="tabelLK">
                            <thead class="thead-dark text-center">
                                <tr>
                                    <th>#</th><th>Kode</th><th>Keterangan</th><th>Tgl</th><th>Checker</th>
                                    <th>Pintu</th><th>Wkt DO Selesai</th><th>Mulai</th><th>Selesai</th><th>Progres</th><th>Durasi </th><th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $no=1; ?>
                            <?php if (!empty($lk_aktif)): ?>
                            <tr class="separator-label sep-active"><td colspan="<?= $colspan_lk ?>"><i class="fas fa-circle-notch fa-spin mr-1"></i> Sedang Proses</td></tr>
                            <?php foreach ($lk_aktif as $lk): $renderLK($lk); endforeach; endif; ?>
                            <?php if (!empty($lk_pending)): ?>
                            <tr class="separator-label sep-pending"><td colspan="<?= $colspan_lk ?>"><i class="fas fa-hourglass-half mr-1"></i> Menunggu / Belum Dikerjakan</td></tr>
                            <?php foreach ($lk_pending as $lk): $renderLK($lk); endforeach; endif; ?>
                            <?php if (!empty($lk_done)): ?>
                            <tr class="separator-label sep-done"><td colspan="<?= $colspan_lk ?>"><i class="fas fa-check-circle mr-1"></i> Sudah Selesai / Done</td></tr>
                            <?php foreach ($lk_done as $lk): $renderLK($lk); endforeach; endif; ?>
                            <?php if (empty($list_lk)): ?><tr><td colspan="<?= $colspan_lk ?>" class="text-center text-muted">Tidak ada data Loading LK</td></tr><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ============================================================
                    TABEL LOADING KK (header hijau)
                ============================================================ -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h3 class="card-title"><i class="fas fa-truck-loading mr-2"></i> Loading KK</h3>
                    </div>
                    <div class="card-body table-responsive">
                        <?php
                        $has_aksi_kk = true;
                        $can_ops_kk  = in_array($role, ['ADMLOG','CHECKER','MANAGERCK']);
                        $can_edit_kk = ($role === 'ADMLOG');
                        $colspan_kk  = 13;
                        $kk_aktif    = array_filter($list_kk, fn($r) => in_array($r['status'], ['PROSES_LOADING','PENYIAPAN_BARANG','SIAP_LOADING']));
                        $kk_pending  = array_filter($list_kk, fn($r) => in_array($r['status'], ['MENUNGGU','CETAK_DO','DO_SELESAI']));
                        $kk_done     = array_filter($list_kk, fn($r) => $r['status'] === 'DONE');

                        $renderKK = function($kk) use (&$no, $role, $nik, $has_aksi_kk, $can_ops_kk, $can_edit_kk, $fn_pintu) {
                            $kk_done_s         = $kk['status'] === 'DONE';
                            $kk_progres        = (int)($kk['progres'] ?? 0);
                            $kk_started        = $kk['status'] === 'PROSES_LOADING';
                            $kk_siapkan        = $kk['status'] === 'PENYIAPAN_BARANG';
                            $kk_siap_loading   = $kk['status'] === 'SIAP_LOADING';
                            $is_paused         = !empty($kk['is_paused']);
                            $is_paused_siapkan = !empty($kk['is_paused_siapkan']);
                            $pernah_pause      = !empty($kk['pernah_pause']) || !empty($kk['pernah_pause_siapkan']);
                            $progres_siapkan   = (int)($kk['progres_siapkan'] ?? 0);
                            $can_edit_row      = ($can_edit_kk && in_array($kk['status'], ['MENUNGGU','CETAK_DO','DO_SELESAI']));
                            $badge_kk          = [
                                'CETAK_DO'         => 'badge-info',
                                'DO_SELESAI'       => 'badge-warning',
                                'PENYIAPAN_BARANG' => 'badge-secondary',
                                'SIAP_LOADING'     => 'badge-primary',
                                'PROSES_LOADING'   => 'badge-warning',
                                'DONE'             => 'badge-success',
                            ][$kk['status']] ?? 'badge-secondary';
                            $rc = $kk_done_s ? 'table-success'
                                : (($is_paused || $is_paused_siapkan) ? 'row-paused'
                                : (($kk_started || $kk_siapkan || $kk_siap_loading) ? 'row-proses'
                                : 'row-pending'));

                            $kk_durasi_html = '<small>-</small>';
            if (!empty($kk['waktu_mulai'])) {
                if ($kk_done_s && !empty($kk['waktu_selesai'])) {
                    $akhir = strtotime($kk['waktu_selesai']);
                } elseif ($is_paused && !empty($kk['paused_at'])) {
                    $akhir = strtotime($kk['paused_at']);
                } elseif ($is_paused_siapkan && !empty($kk['paused_at_siapkan'])) {
                    $akhir = strtotime($kk['paused_at_siapkan']);
                } else {
                    $akhir = time();
                }
                $bruto   = max(0, $akhir - strtotime($kk['waktu_mulai']));
                $total_p = (int)($kk['total_pause_secs'] ?? 0) + (int)($kk['total_pause_secs_siapkan'] ?? 0);
                $sel     = max(0, $bruto - $total_p);
                if ($sel > 0) {
                    $j = floor($sel/3600); $m = floor(($sel%3600)/60);
                    $kk_durasi = ($j>0?"{$j} jam ":"")."{$m} menit";
                    if ($is_paused || $is_paused_siapkan)
                        $kk_durasi_html = '<small>'.$kk_durasi.' <span class="pause-indicator"><i class="fas fa-pause"></i> pause</span></small>';
                    elseif ($kk_done_s)
                        $kk_durasi_html = '<small>'.$kk_durasi.'</small>';
                    else
                        $kk_durasi_html = '<small>'.$kk_durasi.' <span class="badge badge-warning" style="font-size:9px;">live</span></small>';
                }
            }
                        ?>
                        <tr class="<?= $rc ?>">
                            <td><?= $no++ ?></td>
                            <td>
                                <small><?= htmlspecialchars($kk['kode'] ?? '-') ?></small>
                                <?php if ($pernah_pause) : ?>
                                    <span class="badge-pernah-pause ml-1" title="Pernah di-pause"><i class="fas fa-pause"></i></span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($kk['keterangan']) ?></td>
                            <td><small><?= $kk['tgl'] ?></small></td>
                            <td><?= !empty($kk['nm_checker']) ? htmlspecialchars($kk['nm_checker']) : '<span class="text-muted">-</span>' ?></td>
                            <td class="text-center">
                                <?php if (!empty($kk['pintu'])): ?>
                                    <span class="badge-pintu"><i class="fas fa-door-open mr-1"></i><?= $fn_pintu($kk['pintu']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($kk['waktu_do_selesai'])): ?>
                                    <small class="text-warning font-weight-bold">
                                        <i class="fas fa-file-alt mr-1"></i><?= date('d/m H:i', strtotime($kk['waktu_do_selesai'])) ?>
                                    </small>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><small><?= !empty($kk['waktu_mulai']) ? date('d/m H:i', strtotime($kk['waktu_mulai'])) : '-' ?></small></td>
                            <td><small><?= !empty($kk['waktu_selesai']) ? date('d/m H:i', strtotime($kk['waktu_selesai'])) : '-' ?></small></td>
                            <td style="min-width:100px;">
                                <div class="progress" style="height:14px;">
                                    <div class="progress-bar <?= $kk_progres==100?'bg-success':($is_paused?'bg-danger':'bg-success') ?>" style="width:<?= $kk_progres ?>%"><?= $kk_progres ?>%</div>
                                </div>
                            </td>
                            <td><?= $kk_durasi_html ?></td>
                            <td class="text-center">
                                <?php if ($is_paused_siapkan) : ?>
                                    <span class="badge badge-danger"><i class="fas fa-pause mr-1"></i>PAUSE SIAPKAN</span>
                                <?php elseif ($is_paused) : ?>
                                    <span class="badge badge-danger"><i class="fas fa-pause mr-1"></i>PAUSE</span>
                                <?php elseif ($role === 'ADMLOG' && in_array($kk['status'], ['CETAK_DO','DO_SELESAI'])): ?>
                                    <select class="form-control form-control-sm select-status-kk" data-id="<?= $kk['id'] ?>" style="min-width:120px;">
                                        <?php foreach (['CETAK_DO','DO_SELESAI'] as $s): ?><option value="<?= $s ?>" <?= $kk['status']===$s?'selected':'' ?>><?= str_replace('_',' ',$s) ?></option><?php endforeach; ?>
                                    </select>
                                <?php else: ?>
                                    <span class="badge <?= $badge_kk ?>"><?= str_replace('_',' ',$kk['status']) ?></span>
                                <?php endif; ?>
                            </td>
                            <!-- Kolom Aksi — semua role lihat tombol Detail -->
                            <td class="text-center" style="min-width:200px;">
                                <div class="aksi-wrap">
                                    <!-- Tombol Detail — tampil sesuai role & status -->
                                    <?php
                                    $show_detail_kk = false;
                                    if ($role === 'ADMLOG') {
                                        $show_detail_kk = in_array($kk['status'], ['PROSES_LOADING','PENYIAPAN_BARANG','SIAP_LOADING','DONE']);
                                    } elseif (in_array($role, ['MANAGERCK','CHECKER'])) {
                                        $show_detail_kk = ($kk['status'] === 'DONE');
                                    } else {
                                        $show_detail_kk = true;
                                    }
                                    ?>
                                    <?php if ($show_detail_kk): ?>
                                    <a href="<?= base_url('checker/detail_kk/' . $kk['id']) ?>"
                                    class="btn btn-sm btn-outline-secondary btn-detail">
                                        <i class="fas fa-eye mr-1"></i> Detail
                                    </a>
                                    <?php endif; ?>

                                    <?php if ($can_ops_kk): ?>
                                    <?php if ($role === 'ADMLOG'): ?>
                                        <?php if ($can_edit_row): ?>
                                            <div style="display:flex; gap:4px; justify-content:center; align-items:center; flex-wrap:wrap;">
                                                <?php if (in_array($kk['status'], ['CETAK_DO','DO_SELESAI'])): ?>
                                                    <button class="btn btn-sm btn-info btn-simpan-kk" data-id="<?= $kk['id'] ?>">
                                                        <i class="fas fa-save"></i> Simpan
                                                    </button>
                                                <?php endif; ?>
                                                <button class="btn btn-sm btn-warning btn-edit-kk"
                                                    data-id="<?= $kk['id'] ?>"
                                                    data-ket="<?= htmlspecialchars($kk['keterangan']) ?>">
                                                    <i class="fas fa-pencil-alt"></i> Edit
                                                </button>
                                                <button class="btn btn-sm btn-danger btn-hapus-kk"
                                                    data-id="<?= $kk['id'] ?>"
                                                    data-ket="<?= htmlspecialchars($kk['keterangan']) ?>">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </div>
                                        <?php endif; ?>

                                    <?php elseif ($role === 'CHECKER'): ?>
                                        <?php if ($kk['status'] === 'DO_SELESAI' && !$is_paused_siapkan && !$is_paused): ?>
                                            <button class="btn btn-sm btn-siapkan btn-start-siapkan-kk" data-id="<?= $kk['id'] ?>">
                                                <i class="fas fa-boxes mr-1"></i> Siapkan Barang
                                            </button>
                                        <?php elseif ($kk_siapkan && $kk['nik_checker'] === $nik): ?>
                                            <?php if ($is_paused_siapkan) : ?>
                                                <button class="btn btn-sm btn-resume btn-resume-siapkan-kk" data-id="<?= $kk['id'] ?>">
                                                    <i class="fas fa-play mr-1"></i>Lanjut Siapkan
                                                </button>
                                            <?php else : ?>
                                                <div class="aksi-checker">
                                                    <button class="btn btn-sm btn-pause btn-pause-siapkan-kk" data-id="<?= $kk['id'] ?>">
                                                        <i class="fas fa-pause"></i> Pause
                                                    </button>
                                                    <button class="btn btn-sm btn-primary btn-done-siapkan-kk" data-id="<?= $kk['id'] ?>">
                                                        <i class="fas fa-check"></i> Selesai Siapkan
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        <?php elseif ($kk_siapkan): ?>
                                            <span class="text-muted small"><i class="fas fa-lock mr-1"></i><?= htmlspecialchars($kk['nm_checker'] ?? '') ?> sedang menyiapkan</span>
                                        <?php elseif ($kk_siap_loading): ?>
                                            <button class="btn btn-sm btn-success btn-start-loading-kk" data-id="<?= $kk['id'] ?>">
                                                <i class="fas fa-play mr-1"></i> Start Loading
                                            </button>
                                        <?php elseif ($kk_started && $kk['nik_checker'] === $nik): ?>
                                            <?php if ($is_paused) : ?>
                                                <button class="btn btn-sm btn-resume btn-resume-kk" data-id="<?= $kk['id'] ?>">
                                                    <i class="fas fa-play mr-1"></i>Lanjut Loading
                                                </button>
                                            <?php else : ?>
                                                <div class="aksi-checker">
                                                    <select class="form-control form-control-sm select-progres-kk">
                                                        <?php foreach ([0,10,20,30,40,50,60,70,80,90] as $p): ?><option value="<?= $p ?>" <?= $kk_progres==$p?'selected':'' ?>><?= $p ?>%</option><?php endforeach; ?>
                                                    </select>
                                                    <button class="btn btn-sm btn-warning btn-update-progres-kk" data-id="<?= $kk['id'] ?>"><i class="fas fa-sync"></i> Update</button>
                                                    <button class="btn btn-sm btn-pause btn-pause-kk" data-id="<?= $kk['id'] ?>"><i class="fas fa-pause"></i> Pause</button>
                                                    <button class="btn btn-sm btn-primary btn-done-kk" data-id="<?= $kk['id'] ?>"><i class="fas fa-check"></i> Done</button>
                                                </div>
                                            <?php endif; ?>
                                        <?php elseif ($kk_started): ?>
                                            <span class="text-muted small"><i class="fas fa-lock mr-1"></i><?= htmlspecialchars($kk['nm_checker'] ?? '') ?></span>
                                        <?php endif; ?>

                                    <?php elseif ($role === 'MANAGERCK'): ?>
                                        <?php if ($kk['status'] === 'DO_SELESAI' && !$is_paused_siapkan && !$is_paused): ?>
                                            <button class="btn btn-sm btn-siapkan btn-start-siapkan-mck" data-id="<?= $kk['id'] ?>" data-type="kk">
                                                <i class="fas fa-boxes mr-1"></i> Siapkan Barang
                                            </button>
                                        <?php elseif ($kk_siapkan): ?>
                                            <?php if ($is_paused_siapkan) : ?>
                                                <button class="btn btn-sm btn-resume btn-resume-siapkan-kk" data-id="<?= $kk['id'] ?>">
                                                    <i class="fas fa-play mr-1"></i>Lanjut Siapkan
                                                </button>
                                            <?php else : ?>
                                                <div class="aksi-managerck">
                                                    <button class="btn btn-sm btn-pause btn-pause-siapkan-kk" data-id="<?= $kk['id'] ?>">
                                                        <i class="fas fa-pause"></i> Pause
                                                    </button>
                                                    <button class="btn btn-sm btn-primary btn-done-siapkan-kk" data-id="<?= $kk['id'] ?>">
                                                        <i class="fas fa-check"></i> Selesai Siapkan
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        <?php elseif ($kk_siap_loading): ?>
                                            <button class="btn btn-sm btn-success btn-start-loading-mck" data-id="<?= $kk['id'] ?>" data-type="kk">
                                                <i class="fas fa-play mr-1"></i> Start Loading
                                            </button>
                                        <?php elseif ($kk_started): ?>
                                            <?php if ($is_paused) : ?>
                                                <button class="btn btn-sm btn-resume btn-resume-kk" data-id="<?= $kk['id'] ?>">
                                                    <i class="fas fa-play mr-1"></i>Lanjut Loading
                                                </button>
                                            <?php else : ?>
                                                <div class="aksi-managerck">
                                                    <select class="form-control form-control-sm select-progres-kk">
                                                        <?php foreach ([0,10,20,30,40,50,60,70,80,90] as $p): ?><option value="<?= $p ?>" <?= $kk_progres==$p?'selected':'' ?>><?= $p ?>%</option><?php endforeach; ?>
                                                    </select>
                                                    <button class="btn btn-sm btn-warning btn-update-progres-kk" data-id="<?= $kk['id'] ?>"><i class="fas fa-sync"></i> Update</button>
                                                    <button class="btn btn-sm btn-pause btn-pause-kk" data-id="<?= $kk['id'] ?>"><i class="fas fa-pause"></i> Pause</button>
                                                    <button class="btn btn-sm btn-primary btn-done-kk" data-id="<?= $kk['id'] ?>"><i class="fas fa-check"></i> Done</button>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php endif; // end can_ops_kk ?>
                                </div>
                            </td>
                        </tr>
                        <?php }; ?>

                        <table class="table table-bordered table-sm" id="tabelKK">
                            <thead class="thead-dark text-center">
                                <tr>
                                    <th>#</th><th>Kode</th><th>Keterangan</th><th>Tgl</th><th>Checker</th>
                                    <th>Pintu</th><th>Wkt DO Selesai</th><th>Mulai</th><th>Selesai</th><th>Progres</th><th>Durasi </th><th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $no=1; ?>
                            <?php if (!empty($kk_aktif)): ?>
                            <tr class="separator-label sep-active"><td colspan="<?= $colspan_kk ?>"><i class="fas fa-circle-notch fa-spin mr-1"></i> Sedang Proses</td></tr>
                            <?php foreach ($kk_aktif as $kk): $renderKK($kk); endforeach; endif; ?>
                            <?php if (!empty($kk_pending)): ?>
                            <tr class="separator-label sep-pending"><td colspan="<?= $colspan_kk ?>"><i class="fas fa-hourglass-half mr-1"></i> Menunggu / Belum Dikerjakan</td></tr>
                            <?php foreach ($kk_pending as $kk): $renderKK($kk); endforeach; endif; ?>
                            <?php if (!empty($kk_done)): ?>
                            <tr class="separator-label sep-done"><td colspan="<?= $colspan_kk ?>"><i class="fas fa-check-circle mr-1"></i> Sudah Selesai / Done</td></tr>
                            <?php foreach ($kk_done as $kk): $renderKK($kk); endforeach; endif; ?>
                            <?php if (empty($list_kk)): ?><tr><td colspan="<?= $colspan_kk ?>" class="text-center text-muted">Tidak ada data Loading KK</td></tr><?php endif; ?>
                            </tbody>
                        </table>
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

<!-- MODALS (tidak berubah, sama persis seperti sebelumnya) -->
<?php if ($role === 'MANAGERWH') : ?>
<div class="modal fade" id="modalBuatBongkaran" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title"><i class="fas fa-plus mr-2"></i> Buat Bongkaran Baru</h5>
            <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
            <div class="form-group"><label class="font-weight-bold">Kode</label><input type="text" class="form-control" value="<?= $kode_baru ?>" readonly></div>
            <div class="form-group"><label class="font-weight-bold">Keterangan <span class="text-danger">*</span></label><input type="text" id="inputKeterangan" class="form-control" placeholder="Contoh: NK 212 BTGT"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-primary" id="btnSimpanBongkaran"><i class="fas fa-save mr-1"></i> Simpan</button>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modalEditBongkaran" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header bg-warning">
            <h5 class="modal-title"><i class="fas fa-pencil-alt mr-2"></i> Edit Bongkaran</h5>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="editBongkaranId">
            <div class="form-group">
                <label class="font-weight-bold">Keterangan <span class="text-danger">*</span></label>
                <input type="text" id="editBongkaranKet" class="form-control" placeholder="Keterangan bongkaran">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-warning" id="btnSimpanEditBongkaran"><i class="fas fa-save mr-1"></i> Simpan</button>
        </div>
    </div></div>
</div>
<?php endif; ?>

<?php if ($role === 'ADMLOG') : ?>
<div class="modal fade" id="modalTambahKK" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header bg-success text-white">
            <h5 class="modal-title"><i class="fas fa-plus mr-2"></i> Tambah Loading KK</h5>
            <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
            <div class="form-group"><label class="font-weight-bold">Keterangan <span class="text-danger">*</span></label><input type="text" id="inputKeteranganKK" class="form-control" placeholder="Contoh: JBR"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-success" id="btnSimpanKK"><i class="fas fa-save mr-1"></i> Simpan</button>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modalTambahLK" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header bg-warning">
            <h5 class="modal-title"><i class="fas fa-plus mr-2"></i> Tambah Loading LK</h5>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
            <div class="form-group"><label class="font-weight-bold">Keterangan <span class="text-danger">*</span></label><input type="text" id="inputKeteranganLK" class="form-control" placeholder="Contoh: P-2"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-warning" id="btnSimpanLK"><i class="fas fa-save mr-1"></i> Simpan</button>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modalEditKK" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header bg-warning">
            <h5 class="modal-title"><i class="fas fa-pencil-alt mr-2"></i> Edit Loading KK</h5>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="editKKId">
            <div class="form-group"><label class="font-weight-bold">Keterangan <span class="text-danger">*</span></label><input type="text" id="editKKKet" class="form-control" placeholder="Keterangan loading KK"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-warning" id="btnSimpanEditKK"><i class="fas fa-save mr-1"></i> Simpan</button>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modalEditLK" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header bg-warning">
            <h5 class="modal-title"><i class="fas fa-pencil-alt mr-2"></i> Edit Loading LK</h5>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="editLKId">
            <div class="form-group"><label class="font-weight-bold">Keterangan <span class="text-danger">*</span></label><input type="text" id="editLKKet" class="form-control" placeholder="Keterangan loading LK"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-warning" id="btnSimpanEditLK"><i class="fas fa-save mr-1"></i> Simpan</button>
        </div>
    </div></div>
</div>
<?php endif; ?>

<?php if ($role === 'MANAGERCK') : ?>
<div class="modal fade" id="modalStartMCK" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header bg-success text-white">
            <h5 class="modal-title"><i class="fas fa-play mr-2"></i> Start — Pilih Checker & Pintu</h5>
            <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="mck_id">
            <input type="hidden" id="mck_type">
            <div class="form-group">
                <label class="font-weight-bold">Pilih Checker <span class="text-danger">*</span></label>
                <select id="mck_checker" class="form-control">
                    <option value="">-- Pilih Checker --</option>
                    <?php foreach ($list_checker as $ck) : ?>
                        <option value="<?= $ck['nik'] ?>" data-nama="<?= htmlspecialchars($ck['nm_karyawan']) ?>"><?= htmlspecialchars($ck['nm_karyawan']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group mb-0">
                <label class="font-weight-bold">Pilih Pintu <span class="text-danger">*</span></label>
                <select id="mck_pintu" class="form-control">
                    <option value="">-- Pilih Pintu --</option>
                    <option value="1">Pintu A1</option><option value="2">Pintu A2</option>
                    <option value="3">Pintu A3</option><option value="4">Pintu A4</option>
                    <option value="5">Pintu A5</option><option value="6">Pintu A6</option>
                    <option value="7">Pintu B1</option><option value="8">Pintu B2</option>
                    <option value="9">Pintu B3</option><option value="10">Pintu C</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-success" id="btnKonfirmasiStartMCK"><i class="fas fa-play mr-1"></i> Konfirmasi Start</button>
        </div>
    </div></div>
</div>
<?php endif; ?>

<?php if ($role === 'CHECKER') : ?>
<div class="modal fade" id="modalPilihPintu" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm"><div class="modal-content">
        <div class="modal-header bg-success text-white">
            <h5 class="modal-title"><i class="fas fa-door-open mr-2"></i> Pilih Pintu</h5>
            <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="pp_id">
            <input type="hidden" id="pp_type">
            <div class="form-group mb-0">
                <label class="font-weight-bold">Pintu mana? <span class="text-danger">*</span></label>
                <select id="pp_pintu" class="form-control">
                    <option value="">-- Pilih Pintu --</option>
                    <option value="1">Pintu A1</option><option value="2">Pintu A2</option>
                    <option value="3">Pintu A3</option><option value="4">Pintu A4</option>
                    <option value="5">Pintu A5</option><option value="6">Pintu A6</option>
                    <option value="7">Pintu B1</option><option value="8">Pintu B2</option>
                    <option value="9">Pintu B3</option><option value="10">Pintu C</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-success" id="btnKonfirmasiPintu">
                <i class="fas fa-play mr-1"></i> Start
            </button>
        </div>
    </div></div>
</div>
<?php endif; ?>

<script>
var BASE = '<?= base_url() ?>';
function ajaxPost(url, data, cb) {
    $.post(BASE + url, data, cb, 'json').fail(function(xhr){ alert('Error: ' + xhr.responseText.substring(0,200)); });
}

$(document).ready(function () {
    setTimeout(function(){ location.reload(); }, 30000);

    $('#btnSimpanBongkaran').on('click', function () {
        var ket = $('#inputKeterangan').val().trim();
        if (!ket) { alert('Keterangan wajib diisi'); return; }
        ajaxPost('checker/store', {keterangan: ket}, function(res){ alert(res.msg); if(res.status) location.reload(); });
    });
    $('#btnSimpanKK').on('click', function(){
        var ket = $('#inputKeteranganKK').val().trim();
        if (!ket) { alert('Keterangan wajib diisi'); return; }
        ajaxPost('checker/store_kk', {keterangan: ket}, function(res){ alert(res.msg); if(res.status) location.reload(); });
    });
    $('#btnSimpanLK').on('click', function(){
        var ket = $('#inputKeteranganLK').val().trim();
        if (!ket) { alert('Keterangan wajib diisi'); return; }
        ajaxPost('checker/store_lk', {keterangan: ket}, function(res){ alert(res.msg); if(res.status) location.reload(); });
    });
    $(document).on('click', '.btn-edit-bongkaran', function(){
        $('#editBongkaranId').val($(this).data('id'));
        $('#editBongkaranKet').val($(this).data('ket'));
        $('#modalEditBongkaran').modal('show');
    });
    $('#btnSimpanEditBongkaran').on('click', function(){
        var id = $('#editBongkaranId').val(), ket = $('#editBongkaranKet').val().trim();
        if (!ket) { alert('Keterangan wajib diisi'); return; }
        ajaxPost('checker/edit_bongkaran', {id: id, keterangan: ket}, function(res){
            $('#modalEditBongkaran').modal('hide'); alert(res.msg); if (res.status) location.reload();
        });
    });
    $(document).on('click', '.btn-hapus-bongkaran', function(){
        var id = $(this).data('id'), ket = $(this).data('ket');
        Swal.fire({ title:'Hapus Bongkaran?', html:'Bongkaran <b>'+ket+'</b> akan dihapus permanen.',
            icon:'warning', showCancelButton:true, confirmButtonColor:'#dc3545', cancelButtonColor:'#6c757d',
            confirmButtonText:'<i class="fas fa-trash mr-1"></i> Ya, Hapus', cancelButtonText:'Batal'
        }).then(function(r){ if(!r.isConfirmed) return;
            ajaxPost('checker/hapus_bongkaran', {id:id}, function(res){
                Swal.fire({icon:res.status?'success':'error',title:res.status?'Terhapus!':'Gagal',text:res.msg,timer:res.status?2000:0,showConfirmButton:!res.status})
                .then(function(){ if(res.status) location.reload(); });
            });
        });
    });
    $(document).on('click', '.btn-edit-kk', function(){
        $('#editKKId').val($(this).data('id')); $('#editKKKet').val($(this).data('ket')); $('#modalEditKK').modal('show');
    });
    $('#btnSimpanEditKK').on('click', function(){
        var id=$('#editKKId').val(), ket=$('#editKKKet').val().trim();
        if(!ket){alert('Keterangan wajib diisi');return;}
        ajaxPost('checker/edit_kk',{id:id,keterangan:ket},function(res){ $('#modalEditKK').modal('hide'); alert(res.msg); if(res.status) location.reload(); });
    });
    $(document).on('click', '.btn-hapus-kk', function(){
        var id=$(this).data('id'), ket=$(this).data('ket');
        Swal.fire({title:'Hapus Loading KK?',html:'Data <b>'+ket+'</b> akan dihapus permanen.',icon:'warning',showCancelButton:true,confirmButtonColor:'#dc3545',cancelButtonColor:'#6c757d',confirmButtonText:'<i class="fas fa-trash mr-1"></i> Ya, Hapus',cancelButtonText:'Batal'})
        .then(function(r){ if(!r.isConfirmed) return;
            ajaxPost('checker/hapus_kk',{id:id},function(res){
                Swal.fire({icon:res.status?'success':'error',title:res.status?'Terhapus!':'Gagal',text:res.msg,timer:res.status?2000:0,showConfirmButton:!res.status})
                .then(function(){ if(res.status) location.reload(); });
            });
        });
    });
    $(document).on('click', '.btn-edit-lk', function(){
        $('#editLKId').val($(this).data('id')); $('#editLKKet').val($(this).data('ket')); $('#modalEditLK').modal('show');
    });
    $('#btnSimpanEditLK').on('click', function(){
        var id=$('#editLKId').val(), ket=$('#editLKKet').val().trim();
        if(!ket){alert('Keterangan wajib diisi');return;}
        ajaxPost('checker/edit_lk',{id:id,keterangan:ket},function(res){ $('#modalEditLK').modal('hide'); alert(res.msg); if(res.status) location.reload(); });
    });
    $(document).on('click', '.btn-hapus-lk', function(){
        var id=$(this).data('id'), ket=$(this).data('ket');
        Swal.fire({title:'Hapus Loading LK?',html:'Data <b>'+ket+'</b> akan dihapus permanen.',icon:'warning',showCancelButton:true,confirmButtonColor:'#dc3545',cancelButtonColor:'#6c757d',confirmButtonText:'<i class="fas fa-trash mr-1"></i> Ya, Hapus',cancelButtonText:'Batal'})
        .then(function(r){ if(!r.isConfirmed) return;
            ajaxPost('checker/hapus_lk',{id:id},function(res){
                Swal.fire({icon:res.status?'success':'error',title:res.status?'Terhapus!':'Gagal',text:res.msg,timer:res.status?2000:0,showConfirmButton:!res.status})
                .then(function(){ if(res.status) location.reload(); });
            });
        });
    });
    $(document).on('click', '.btn-simpan-lk', function(){
        var id=$(this).data('id'), status=$(this).closest('tr').find('.select-status-lk').val();
        ajaxPost('checker/update_lk',{id:id,status:status},function(res){ alert(res.msg); if(res.status) location.reload(); });
    });
    $(document).on('click', '.btn-simpan-kk', function(){
        var id=$(this).data('id'), status=$(this).closest('tr').find('.select-status-kk').val();
        ajaxPost('checker/update_kk',{id:id,status:status},function(res){ alert(res.msg); if(res.status) location.reload(); });
    });
    $(document).on('click', '.btn-start', function () {
        $('#pp_id').val($(this).data('id')); $('#pp_type').val('bongkaran'); $('#pp_pintu').val('');
        $('#modalPilihPintu').modal('show');
    });
    $(document).on('click', '.btn-start-siapkan-lk', function () {
        $('#pp_id').val($(this).data('id')); $('#pp_type').val('lk_siapkan'); $('#pp_pintu').val('');
        $('#modalPilihPintu').modal('show');
    });
    $(document).on('click', '.btn-start-siapkan-kk', function () {
        $('#pp_id').val($(this).data('id')); $('#pp_type').val('kk_siapkan'); $('#pp_pintu').val('');
        $('#modalPilihPintu').modal('show');
    });
    $('#btnKonfirmasiPintu').on('click', function () {
        var id=$('#pp_id').val(), type=$('#pp_type').val(), pintu=$('#pp_pintu').val();
        if (!pintu) { alert('Pilih pintu terlebih dahulu'); return; }
        var urlMap = { bongkaran:'checker/start', kk:'checker/start_kk', lk:'checker/start_lk', kk_siapkan:'checker/start_siapkan_kk', lk_siapkan:'checker/start_siapkan_lk' };
        ajaxPost(urlMap[type], {id:id, pintu:pintu}, function(res){
            $('#modalPilihPintu').modal('hide'); alert(res.msg); if(res.status) location.reload();
        });
    });
    $(document).on('click', '.btn-start-siapkan-mck', function(){
        $('#mck_id').val($(this).data('id')); $('#mck_type').val($(this).data('type')+'_siapkan');
        $('#mck_checker').val(''); $('#mck_pintu').val('');
        $('#modalStartMCK').modal('show');
    });
    $('#btnKonfirmasiStartMCK').on('click', function(){
        var id=$('#mck_id').val(), type=$('#mck_type').val();
        var nik=$('#mck_checker').val(), nama=$('#mck_checker option:selected').data('nama');
        var pintu=$('#mck_pintu').val();
        if(!nik){alert('Pilih checker terlebih dahulu');return;}
        if(!pintu){alert('Pilih pintu terlebih dahulu');return;}
        var urlMap = { bongkaran:'checker/start', kk:'checker/start_kk', lk:'checker/start_lk', kk_siapkan:'checker/start_siapkan_kk', lk_siapkan:'checker/start_siapkan_lk' };
        ajaxPost(urlMap[type], {id:id, nik_checker:nik, nm_checker:nama, pintu:pintu}, function(res){
            $('#modalStartMCK').modal('hide'); alert(res.msg); if(res.status) location.reload();
        });
    });
    $(document).on('click', '.btn-update-progres', function(){
        var id=$(this).data('id');
        var progres=$(this).closest('.aksi-checker,.aksi-managerck').find('.select-progres').val();
        ajaxPost('checker/update_progres',{id:id,progres:progres},function(res){ alert(res.msg); if(res.status) location.reload(); });
    });
    $(document).on('click', '.btn-done', function(){
        if(!confirm('Tandai sebagai SELESAI?')) return;
        ajaxPost('checker/done',{id:$(this).data('id')},function(res){ alert(res.msg); if(res.status) location.reload(); });
    });
    $(document).on('click', '.btn-update-progres-lk', function(){
        var id=$(this).data('id');
        var progres=$(this).closest('.aksi-checker,.aksi-managerck').find('.select-progres-lk').val();
        ajaxPost('checker/update_progres_lk',{id:id,progres:progres},function(res){ alert(res.msg); if(res.status) location.reload(); });
    });
    $(document).on('click', '.btn-done-lk', function(){
        if(!confirm('Tandai Loading LK sebagai SELESAI?')) return;
        ajaxPost('checker/done_lk',{id:$(this).data('id')},function(res){ alert(res.msg); if(res.status) location.reload(); });
    });
    $(document).on('click', '.btn-update-progres-kk', function(){
        var id=$(this).data('id');
        var progres=$(this).closest('.aksi-checker,.aksi-managerck').find('.select-progres-kk').val();
        ajaxPost('checker/update_progres_kk',{id:id,progres:progres},function(res){ alert(res.msg); if(res.status) location.reload(); });
    });
    $(document).on('click', '.btn-done-kk', function(){
        if(!confirm('Tandai Loading KK sebagai SELESAI?')) return;
        ajaxPost('checker/done_kk',{id:$(this).data('id')},function(res){ alert(res.msg); if(res.status) location.reload(); });
    });
    $(document).on('click', '.btn-pause-bongkaran', function(){
        var id=$(this).data('id');
        Swal.fire({title:'Pause Bongkaran?',text:'Durasi akan berhenti sampai dilanjutkan kembali.',icon:'warning',showCancelButton:true,confirmButtonColor:'#e65100',cancelButtonColor:'#6c757d',confirmButtonText:'<i class="fas fa-pause mr-1"></i> Ya, Pause',cancelButtonText:'Batal'})
        .then(function(r){ if(!r.isConfirmed) return;
            ajaxPost('checker/pause',{id:id},function(res){
                Swal.fire({icon:res.status?'success':'error',title:res.status?'Di-pause!':'Gagal',text:res.msg,timer:res.status?1500:0,showConfirmButton:!res.status})
                .then(function(){ if(res.status) location.reload(); });
            });
        });
    });
    $(document).on('click', '.btn-resume-bongkaran', function(){
        ajaxPost('checker/resume',{id:$(this).data('id')},function(res){ alert(res.msg); if(res.status) location.reload(); });
    });
    $(document).on('click', '.btn-pause-lk', function(){
        var id=$(this).data('id');
        Swal.fire({title:'Pause Loading LK?',text:'Durasi akan berhenti sampai dilanjutkan kembali.',icon:'warning',showCancelButton:true,confirmButtonColor:'#e65100',cancelButtonColor:'#6c757d',confirmButtonText:'<i class="fas fa-pause mr-1"></i> Ya, Pause',cancelButtonText:'Batal'})
        .then(function(r){ if(!r.isConfirmed) return;
            ajaxPost('checker/pause_lk',{id:id},function(res){
                Swal.fire({icon:res.status?'success':'error',title:res.status?'Di-pause!':'Gagal',text:res.msg,timer:res.status?1500:0,showConfirmButton:!res.status})
                .then(function(){ if(res.status) location.reload(); });
            });
        });
    });
    $(document).on('click', '.btn-resume-lk', function(){
        ajaxPost('checker/resume_lk',{id:$(this).data('id')},function(res){ alert(res.msg); if(res.status) location.reload(); });
    });
    $(document).on('click', '.btn-pause-kk', function(){
        var id=$(this).data('id');
        Swal.fire({title:'Pause Loading KK?',text:'Durasi akan berhenti sampai dilanjutkan kembali.',icon:'warning',showCancelButton:true,confirmButtonColor:'#e65100',cancelButtonColor:'#6c757d',confirmButtonText:'<i class="fas fa-pause mr-1"></i> Ya, Pause',cancelButtonText:'Batal'})
        .then(function(r){ if(!r.isConfirmed) return;
            ajaxPost('checker/pause_kk',{id:id},function(res){
                Swal.fire({icon:res.status?'success':'error',title:res.status?'Di-pause!':'Gagal',text:res.msg,timer:res.status?1500:0,showConfirmButton:!res.status})
                .then(function(){ if(res.status) location.reload(); });
            });
        });
    });
    $(document).on('click', '.btn-resume-kk', function(){
        ajaxPost('checker/resume_kk',{id:$(this).data('id')},function(res){ alert(res.msg); if(res.status) location.reload(); });
    });
    $(document).on('click', '.btn-pause-siapkan-lk', function(){
        var id=$(this).data('id');
        Swal.fire({title:'Pause Penyiapan LK?',text:'Durasi penyiapan akan berhenti sampai dilanjutkan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#e65100',cancelButtonColor:'#6c757d',confirmButtonText:'<i class="fas fa-pause mr-1"></i> Ya, Pause',cancelButtonText:'Batal'})
        .then(function(r){ if(!r.isConfirmed) return;
            ajaxPost('checker/pause_siapkan_lk',{id:id},function(res){
                Swal.fire({icon:res.status?'success':'error',title:res.status?'Di-pause!':'Gagal',text:res.msg,timer:res.status?1500:0,showConfirmButton:!res.status})
                .then(function(){ if(res.status) location.reload(); });
            });
        });
    });
    $(document).on('click', '.btn-resume-siapkan-lk', function(){
        ajaxPost('checker/resume_siapkan_lk',{id:$(this).data('id')},function(res){ alert(res.msg); if(res.status) location.reload(); });
    });
    $(document).on('click', '.btn-done-siapkan-lk', function(){
        var id = $(this).data('id');
        Swal.fire({
            title: 'Selesai Penyiapan LK?',
            text: 'Durasi penyiapan akan dicatat dan loading langsung dimulai.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-check mr-1"></i> Ya, Selesai & Mulai Loading',
            cancelButtonText: 'Batal'
        }).then(function(r) {
            if (!r.isConfirmed) return;
            // Step 1: done siapkan
            ajaxPost('checker/done_siapkan_lk', {id: id}, function(res1) {
                if (!res1.status) { alert(res1.msg); return; }
                // Step 2: langsung start loading
                ajaxPost('checker/start_lk', {id: id}, function(res2) {
                    Swal.fire({
                        icon: res2.status ? 'success' : 'warning',
                        title: res2.status ? 'Loading Dimulai!' : 'Siapkan selesai',
                        text: res2.status ? 'Penyiapan selesai, loading LK sudah berjalan.' : res1.msg,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(function() { location.reload(); });
                });
            });
        });
    });
    $(document).on('click', '.btn-pause-siapkan-kk', function(){
        var id=$(this).data('id');
        Swal.fire({title:'Pause Penyiapan KK?',text:'Durasi penyiapan akan berhenti sampai dilanjutkan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#e65100',cancelButtonColor:'#6c757d',confirmButtonText:'<i class="fas fa-pause mr-1"></i> Ya, Pause',cancelButtonText:'Batal'})
        .then(function(r){ if(!r.isConfirmed) return;
            ajaxPost('checker/pause_siapkan_kk',{id:id},function(res){
                Swal.fire({icon:res.status?'success':'error',title:res.status?'Di-pause!':'Gagal',text:res.msg,timer:res.status?1500:0,showConfirmButton:!res.status})
                .then(function(){ if(res.status) location.reload(); });
            });
        });
    });
    $(document).on('click', '.btn-resume-siapkan-kk', function(){
        ajaxPost('checker/resume_siapkan_kk',{id:$(this).data('id')},function(res){ alert(res.msg); if(res.status) location.reload(); });
    });
    $(document).on('click', '.btn-done-siapkan-kk', function(){
        var id = $(this).data('id');
        Swal.fire({
            title: 'Selesai Penyiapan KK?',
            text: 'Durasi penyiapan akan dicatat dan loading langsung dimulai.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-check mr-1"></i> Ya, Selesai & Mulai Loading',
            cancelButtonText: 'Batal'
        }).then(function(r) {
            if (!r.isConfirmed) return;
            ajaxPost('checker/done_siapkan_kk', {id: id}, function(res1) {
                if (!res1.status) { alert(res1.msg); return; }
                ajaxPost('checker/start_kk', {id: id}, function(res2) {
                    Swal.fire({
                        icon: res2.status ? 'success' : 'warning',
                        title: res2.status ? 'Loading Dimulai!' : 'Siapkan selesai',
                        text: res2.status ? 'Penyiapan selesai, loading KK sudah berjalan.' : res1.msg,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(function() { location.reload(); });
                });
            });
        });
    });
    $('#btnArchiveAll').on('click', function(){
        Swal.fire({title:'Archive Aktivitas?',html:'Semua aktivitas yang sudah <b>DONE</b> akan diarsipkan.<br><small class="text-muted">Data yang belum DONE tidak akan terpengaruh.</small>',icon:'warning',showCancelButton:true,confirmButtonColor:'#dc3545',cancelButtonColor:'#6c757d',confirmButtonText:'<i class="fas fa-box-archive mr-1"></i> Ya, Archive Sekarang',cancelButtonText:'Batal'})
        .then(function(r){ if(!r.isConfirmed) return;
            ajaxPost('checker/archive_all_today',{},function(res){
                Swal.fire({icon:res.status?'success':'error',title:res.status?'Berhasil!':'Gagal',text:res.msg,timer:res.status?2500:0,showConfirmButton:!res.status})
                .then(function(){ if(res.status) location.reload(); });
            });
        });
    });
    $(document).on('click', '.btn-start-loading-lk', function () {
        var id=$(this).data('id');
        Swal.fire({title:'Mulai Loading LK?',text:'Checker dan pintu sama dengan saat penyiapan barang.',icon:'question',showCancelButton:true,confirmButtonColor:'#28a745',cancelButtonColor:'#6c757d',confirmButtonText:'<i class="fas fa-play mr-1"></i> Ya, Mulai',cancelButtonText:'Batal'})
        .then(function(r){ if(!r.isConfirmed) return;
            ajaxPost('checker/start_lk',{id:id},function(res){
                Swal.fire({icon:res.status?'success':'error',title:res.status?'Dimulai!':'Gagal',text:res.msg,timer:res.status?1500:0,showConfirmButton:!res.status})
                .then(function(){ if(res.status) location.reload(); });
            });
        });
    });
    $(document).on('click', '.btn-start-loading-kk', function () {
        var id=$(this).data('id');
        Swal.fire({title:'Mulai Loading KK?',text:'Checker dan pintu sama dengan saat penyiapan barang.',icon:'question',showCancelButton:true,confirmButtonColor:'#28a745',cancelButtonColor:'#6c757d',confirmButtonText:'<i class="fas fa-play mr-1"></i> Ya, Mulai',cancelButtonText:'Batal'})
        .then(function(r){ if(!r.isConfirmed) return;
            ajaxPost('checker/start_kk',{id:id},function(res){
                Swal.fire({icon:res.status?'success':'error',title:res.status?'Dimulai!':'Gagal',text:res.msg,timer:res.status?1500:0,showConfirmButton:!res.status})
                .then(function(){ if(res.status) location.reload(); });
            });
        });
    });
    $(document).on('click', '.btn-start-loading-mck', function () {
        var id=$(this).data('id'), type=$(this).data('type');
        Swal.fire({title:'Mulai Loading '+type.toUpperCase()+'?',text:'Checker dan pintu sama dengan saat penyiapan barang.',icon:'question',showCancelButton:true,confirmButtonColor:'#28a745',cancelButtonColor:'#6c757d',confirmButtonText:'<i class="fas fa-play mr-1"></i> Ya, Mulai',cancelButtonText:'Batal'})
        .then(function(r){ if(!r.isConfirmed) return;
            var url=(type==='lk')?'checker/start_lk':'checker/start_kk';
            ajaxPost(url,{id:id},function(res){
                Swal.fire({icon:res.status?'success':'error',title:res.status?'Dimulai!':'Gagal',text:res.msg,timer:res.status?1500:0,showConfirmButton:!res.status})
                .then(function(){ if(res.status) location.reload(); });
            });
        });
    });
});
</script>
</body>