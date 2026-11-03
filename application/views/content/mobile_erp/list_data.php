<section class="section-stack">
    <div class="mobile-card">
        <div class="card-title-row">
            <div>
                <span class="app-eyebrow">Data laporan</span>
                <h2><?= strtoupper(trim((string) $this->session->userdata('jobdesk'))) === 'KARYAWAN' ? 'Laporan Saya' : 'List Laporan' ?></h2>
            </div>
            <button class="btn btn-light btn-sm rounded-pill" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSearchCanvas">
                <i class="fas fa-sliders"></i>
            </button>
        </div>
        <div class="input-icon">
            <i class="fas fa-magnifying-glass"></i>
            <input type="search" class="form-control mobile-control" placeholder="Cari laporan">
        </div>
    </div>

    <div id="mobileIssueList" class="section-stack"></div>
</section>

<a href="<?= site_url('penilaian_lingkungan') ?>" class="fab" aria-label="Tambah data">
    <i class="fas fa-plus"></i>
</a>
