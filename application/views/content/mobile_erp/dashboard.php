<section class="section-stack">
    <div class="mobile-card">
        <div class="card-title-row">
            <div>
                <span class="app-eyebrow">Hari ini</span>
                <h2>Ringkasan Operasional</h2>
            </div>
            <span class="status-badge status-progress">Live</span>
        </div>
        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-icon"><i class="fas fa-clipboard-check"></i></span>
                <small>Laporan Masuk</small>
                <strong><?= intval($stats['reports'] ?? 0) ?></strong>
            </div>
            <div class="stat-card">
                <span class="stat-icon"><i class="fas fa-clock"></i></span>
                <small>Pending</small>
                <strong><?= intval($stats['pending'] ?? 0) ?></strong>
            </div>
            <div class="stat-card">
                <span class="stat-icon"><i class="fas fa-check-circle"></i></span>
                <small>Selesai</small>
                <strong><?= intval($stats['done'] ?? 0) ?></strong>
            </div>
        </div>
    </div>

    <div class="mobile-card">
        <div class="card-title-row">
            <h2>Aktivitas Cepat</h2>
            <a href="<?= site_url('mobile-erp/list') ?>" class="text-decoration-none fw-bold">Lihat</a>
        </div>
        <div class="d-grid gap-2">
            <a href="<?= site_url('penilaian_lingkungan') ?>" class="btn btn-primary mobile-btn">
                <i class="fas fa-plus me-2"></i>Buat Laporan
            </a>
        </div>
    </div>
</section>

<a href="<?= site_url('penilaian_lingkungan') ?>" class="fab" aria-label="Tambah data">
    <i class="fas fa-plus"></i>
</a>
