<form id="mobileIssueForm" class="section-stack" enctype="multipart/form-data">
    <div class="mobile-card">
        <div class="card-title-row">
            <div>
                <span class="app-eyebrow">Form input</span>
                <h2>Laporan Lingkungan</h2>
            </div>
            <span class="status-badge status-muted"><?= date('d M H:i') ?></span>
        </div>

        <input type="hidden" name="rating_id" value="5">

        <div class="mb-3">
            <label class="form-label fw-bold">Lokasi</label>
            <select class="form-select mobile-select2" name="location_id" required data-placeholder="Cari lokasi kerja" data-ajax-url="<?= site_url('hrd/penilaian_lingkungan/locations') ?>">
                <option value=""></option>
                <?php foreach (($lokasi ?? []) as $loc) : ?>
                    <option value="<?= $loc->id ?>"><?= htmlspecialchars($loc->name, ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback">Lokasi wajib dipilih.</div>
        </div>

        <div class="form-floating mb-3">
            <textarea class="form-control mobile-control" id="description" name="description" maxlength="800" required placeholder="Deskripsi issue"></textarea>
            <label for="description">Deskripsi issue</label>
            <div class="d-flex justify-content-between mt-2">
                <small class="text-muted-soft">Jelaskan temuan secara singkat dan jelas.</small>
                <small class="text-muted-soft"><span id="descriptionCount">0</span>/800</small>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label fw-bold">Bukti Foto</label>
            <label class="upload-zone" for="evidence">
                <span>
                    <i class="fas fa-cloud-arrow-up d-block mb-2"></i>
                    <strong>Upload foto temuan</strong>
                    <small class="d-block">JPG/PNG, maksimal 5MB per file</small>
                </span>
            </label>
            <input type="file" id="evidence" name="evidence[]" accept="image/jpeg,image/png" class="d-none" multiple required>
            <div id="evidencePreview" class="preview-grid"></div>
        </div>
    </div>

    <div class="mobile-card">
        <div class="d-flex align-items-start gap-3">
            <span class="stat-icon"><i class="far fa-user"></i></span>
            <div>
                <strong>Pelapor</strong>
                <p class="text-muted-soft mb-0"><?= htmlspecialchars($created_by ?: 'User Operasional', ENT_QUOTES, 'UTF-8') ?></p>
            </div>
        </div>
    </div>

    <div class="sticky-submit">
        <button type="submit" class="btn btn-primary mobile-btn w-100">
            <i class="fas fa-paper-plane me-2"></i>Kirim Laporan
        </button>
    </div>
</form>
