<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="Karisma" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>

        <div class="content-wrapper env-page">
            <section class="content-header pb-2">
                <div class="container-fluid">
                    <div class="env-toolbar">
                        <div>
                            <h1 class="env-title">Form Laporan Lingkungan</h1>
                            <p class="env-subtitle mb-0">Kirim temuan area kantor secara cepat dengan bukti foto.</p>
                        </div>
                        <div class="btn-group env-nav">
                            <a href="<?= base_url('dashboard_penilaian') ?>" class="btn btn-sm btn-outline-dark"><i class="fas fa-chart-pie mr-1"></i> Dashboard</a>
                            <a href="<?= base_url('hrd/penilaian_lingkungan/monitoring') ?>" class="btn btn-sm btn-outline-dark"><i class="fas fa-desktop mr-1"></i> Monitoring</a>
                            <a href="<?= base_url('penilaian_lingkungan') ?>" class="btn btn-sm btn-dark active"><i class="fas fa-plus mr-1"></i> Form</a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card env-card">
                                <div class="card-header border-0 pb-0">
                                    <h3 class="card-title font-weight-bold">Input Laporan</h3>
                                </div>
                                <form id="issueForm" enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div id="formFeedback"></div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Lokasi</label>
                                                <input type="hidden" name="location_id" id="locationId" required>
                                                <button type="button" class="env-location-picker" id="openLocationPicker">
                                                    <span>
                                                        <i class="fas fa-map-marker-alt mr-2"></i>
                                                        <strong id="selectedLocationText">Pilih lokasi</strong>
                                                    </span>
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Waktu laporan</label>
                                                <input type="text" class="form-control" value="<?= date('Y-m-d H:i:s') ?>" disabled>
                                            </div>
                                        </div>

                                        <input type="hidden" name="rating_id" value="5">

                                        <div class="form-group">
                                            <label>Deskripsi Issue</label>
                                            <textarea class="form-control env-textarea" name="description" rows="5" maxlength="800" required placeholder="Contoh: Area pantry lantai 2 perlu dibersihkan, terdapat sampah menumpuk di sisi kanan."></textarea>
                                            <small class="form-text text-muted"><span id="descriptionCounter">0</span>/800 karakter</small>
                                        </div>

                                        <div class="form-group">
                                            <label>Bukti Foto</label>
                                            <label class="env-upload-zone" for="evidence">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                                <span>Pilih atau jatuhkan foto di sini</span>
                                                <small>JPG/PNG, maksimum 5MB per gambar, bisa lebih dari satu.</small>
                                            </label>
                                            <input type="file" id="evidence" name="evidence[]" accept="image/jpeg,image/png" class="d-none" multiple required>
                                            <div id="evidencePreview" class="env-preview-grid"></div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white text-right">
                                        <button type="reset" class="btn btn-light mr-2"><i class="fas fa-undo mr-1"></i> Reset</button>
                                        <button type="submit" class="btn btn-dark"><i class="fas fa-paper-plane mr-1"></i> Kirim Issue</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card env-card">
                                <div class="card-body">
                                    <div class="env-side-metric">
                                        <span><i class="fas fa-user"></i></span>
                                        <div>
                                            <small>Pelapor</small>
                                            <!-- <strong><?= htmlspecialchars($created_by ?? $this->session->userdata('username') ?? '-') ?></strong> -->
                                            <strong>Bram</strong>
                                        </div>
                                    </div>
                                    <div class="env-side-metric">
                                        <span><i class="fas fa-check-double"></i></span>
                                        <div>
                                            <small>Validasi</small>
                                            <strong>Lokasi, deskripsi, dan foto wajib diisi.</strong>
                                        </div>
                                    </div>
                                    <div class="env-side-metric">
                                        <span><i class="fas fa-bolt"></i></span>
                                        <div>
                                            <small>Proses</small>
                                            <strong>Laporan dikirim via AJAX tanpa reload halaman.</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="modal fade" id="locationPickerModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content env-modal">
                        <div class="modal-header">
                            <h5 class="modal-title">Pilih Lokasi</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" class="form-control" id="locationPickerSearch" placeholder="Cari lokasi...">
                            </div>
                            <div class="env-location-grid" id="locationPickerList">
                                <?php foreach ($lokasi as $loc) : ?>
                                    <button type="button" class="env-location-option" data-id="<?= $loc->id ?>" data-name="<?= htmlspecialchars($loc->name, ENT_QUOTES, 'UTF-8') ?>">
                                        <span><i class="fas fa-map-pin"></i></span>
                                        <strong><?= htmlspecialchars($loc->name) ?></strong>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                            <div class="text-center text-muted d-none" id="locationPickerEmpty">Lokasi tidak ditemukan.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="main-footer">
            <strong>Copyright &copy; 2022 <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block"><b>Version</b> 1.0</div>
        </footer>
        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>
