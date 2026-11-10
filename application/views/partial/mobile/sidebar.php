<div class="offcanvas offcanvas-end mobile-offcanvas" tabindex="-1" id="mobileSearchCanvas" aria-labelledby="mobileSearchCanvasLabel">
    <div class="offcanvas-header">
        <div>
            <span class="app-eyebrow">Stock Opname</span>
            <h2 class="offcanvas-title" id="mobileSearchCanvasLabel">Menu aplikasi</h2>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Tutup"></button>
    </div>
    <div class="offcanvas-body">
        <?php /* Tombol pasang aplikasi disembunyikan sementara untuk semua pengguna */ if (false): ?>
        <div class="mobile-card shadow-none border mb-3">
            <button class="btn btn-primary w-100 mobile-btn" type="button" data-install-app>
                <i class="fas fa-download me-2"></i><span data-install-label>Download aplikasi</span>
            </button>
            <small class="d-block mt-2 text-muted" data-device-label></small>
        </div>
        <?php endif; ?>
        <h3 class="h6 mb-3">Filter cepat</h3>
        <div class="mobile-card shadow-none border mb-3">
            <label class="form-label">Kata kunci</label>
            <div class="input-icon">
                <i class="fas fa-magnifying-glass"></i>
                <input type="search" class="form-control mobile-control" id="globalSearchInput" placeholder="Cari lokasi, status, laporan">
            </div>
        </div>
        <div class="mobile-card shadow-none border">
            <label class="form-label">Status</label>
            <select class="form-select mobile-control" id="globalStatusFilter">
                <option value="">Semua status</option>
                <option value="open">Open</option>
                <option value="progress">Proses</option>
                <option value="done">Selesai</option>
            </select>
            <button class="btn btn-primary w-100 mobile-btn mt-3" data-bs-dismiss="offcanvas" type="button">
                <i class="fas fa-filter me-2"></i>Terapkan Filter
            </button>
        </div>
    </div>
</div>