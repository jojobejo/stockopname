<section class="section-stack">
    <div class="mobile-card text-center">
        <div class="avatar-btn mx-auto mb-3" style="height:76px;width:76px;border-radius:26px;font-size:1.2rem;">
            <?= htmlspecialchars(substr($this->session->userdata('username') ?: 'KU', 0, 2), ENT_QUOTES, 'UTF-8') ?>
        </div>
        <h2 class="mobile-section-title"><?= htmlspecialchars($this->session->userdata('username') ?: 'User Karisma', ENT_QUOTES, 'UTF-8') ?></h2>
        <p class="text-muted-soft mb-0">Operasional Lapangan</p>
    </div>

    <div class="mobile-card">
        <div class="card-title-row">
            <h2>Produktivitas</h2>
            <span class="status-badge status-done">Aktif</span>
        </div>
        <div class="stats-grid">
            <div class="stat-card stat-card-wide">
                <span class="stat-icon"><i class="fas fa-paper-plane"></i></span>
                <small>Dikirim</small>
                <strong><?= intval($profile_stats['reports'] ?? 0) ?></strong>
            </div>
        </div>
    </div>

    <div class="mobile-card">
        <div class="d-grid gap-2">
            <a href="<?= site_url('logout') ?>" class="btn btn-outline-danger mobile-btn">
                <i class="fas fa-right-from-bracket me-2"></i>Logout
            </a>
        </div>
    </div>
</section>
