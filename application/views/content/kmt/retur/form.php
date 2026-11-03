<!-- views/content/kmt/retur/form.php -->
<body class="hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="<?= base_url('assets/images/Karisma.png') ?>" height="150" width="300">
    </div>
    <?php $this->load->view('partial/main/navbar') ?>
    <?php $this->load->view('partial/main/sidebar') ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">
                            <i class="fas fa-undo text-danger"></i> <?= $page_title ?>
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url('kmt/retur') ?>">Retur</a></li>
                            <li class="breadcrumb-item active"><?= isset($row) ? 'Edit' : 'Tambah' ?></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <?php $this->load->view('partial/main/alert') ?>
                <?php
                $is_edit = isset($row);
                $action  = $is_edit
                    ? base_url('kmt/retur/update/' . $row['id'])
                    : base_url('kmt/retur/simpan');
                $lv      = (int)$lv;
                $v       = fn($key, $default = '') => $is_edit
                    ? htmlspecialchars($row[$key] ?? $default)
                    : $default;
                ?>
                <form action="<?= $action ?>" method="POST">
                    <?= form_open($action) ?>

                    <div class="card card-outline card-danger">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-undo mr-1"></i>
                                Formulir <?= $is_edit ? 'Edit' : 'Tambah' ?> Retur
                            </h3>
                        </div>
                        <div class="card-body">

                            <!-- BARIS 1: Tanggal, No LPB, No Retur, Tgl Fak Retur -->
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Tanggal Retur <span class="text-danger">*</span></label>
                                        <input type="date" name="tanggal_retur"
                                               class="form-control form-control-sm"
                                               value="<?= $is_edit ? $row['tanggal_retur'] : date('Y-m-d') ?>"
                                               required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>No LPB</label>
                                        <input type="text" name="no_lpb"
                                               class="form-control form-control-sm"
                                               value="<?= $v('no_lpb') ?>"
                                               placeholder="Nomor LPB">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>No Retur</label>
                                        <input type="text" name="no_retur"
                                               class="form-control form-control-sm"
                                               value="<?= $v('no_retur') ?>"
                                               placeholder="Nomor retur">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Tgl Fak Retur</label>
                                        <input type="date" name="tgl_fak_retur"
                                               class="form-control form-control-sm"
                                               value="<?= $is_edit ? ($row['tgl_fak_retur'] ?? '') : '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>No. Faktur</label>
                                        <input type="text" name="no_faktur"
                                               class="form-control form-control-sm"
                                               value="<?= $v('no_faktur') ?>"
                                               placeholder="No. faktur penjualan">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>SC</label>
                                        <input type="text" name="sc"
                                               class="form-control form-control-sm"
                                               value="<?= $v('sc') ?>"
                                               placeholder="Nama SC">
                                    </div>
                                </div>
                            </div>

                            <!-- BARIS 2: Wilayah, Kode Toko, Nama Toko, Kota -->
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Wilayah (ABM) <span class="text-danger">*</span></label>
                                        <select name="id_wilayah"
                                                class="form-control form-control-sm"
                                                <?= $lv === 3 ? 'disabled' : '' ?> required>
                                            <option value="">-- Pilih --</option>
                                            <?php foreach ($wilayah_list as $w): ?>
                                            <option value="<?= $w['id'] ?>"
                                                <?= (($is_edit && $row['id_wilayah'] == $w['id'])
                                                    || (!$is_edit && $id_wilayah_user == $w['id']))
                                                    ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($w['nama_wilayah']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if ($lv === 3): ?>
                                        <input type="hidden" name="id_wilayah"
                                               value="<?= $is_edit ? $row['id_wilayah'] : $id_wilayah_user ?>">
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Kode Toko</label>
                                        <input type="text" name="kode_toko"
                                               class="form-control form-control-sm"
                                               value="<?= $v('kode_toko') ?>"
                                               placeholder="Kode toko">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nama Toko <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_toko"
                                               class="form-control form-control-sm"
                                               value="<?= $v('nama_toko') ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Kota</label>
                                        <input type="text" name="kota"
                                               class="form-control form-control-sm"
                                               value="<?= $v('kota') ?>"
                                               placeholder="Kota">
                                    </div>
                                </div>
                            </div>

                            <!-- BARIS 3: Produk, Qty, Unit, Harga DPP, Nilai Retur -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nama Barang / Produk <span class="text-danger">*</span></label>
                                        <input type="text" name="produk"
                                               class="form-control form-control-sm"
                                               value="<?= $v('produk') ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Quantity</label>
                                        <input type="number" step="0.01" name="quantity"
                                               class="form-control form-control-sm"
                                               value="<?= $is_edit ? $row['quantity'] : '' ?>"
                                               placeholder="0">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Unit</label>
                                        <input type="text" name="unit"
                                               class="form-control form-control-sm"
                                               value="<?= $v('unit') ?>"
                                               placeholder="Pack / Sak">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Harga DPP (Rp)</label>
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" name="harga_dpp"
                                                   class="form-control angka"
                                                   value="<?= $is_edit ? number_format($row['harga_dpp'] ?? 0, 0, ',', '.') : '' ?>"
                                                   placeholder="0">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Jumlah Retur (Rp) <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-danger text-white">Rp</span>
                                            </div>
                                            <input type="text" name="nilai_retur"
                                                   class="form-control angka"
                                                   value="<?= $is_edit ? number_format($row['nilai_retur'], 0, ',', '.') : '' ?>"
                                                   placeholder="0" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- BARIS 4: Keterangan (dropdown), Keterangan Detail, Kategori -->
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Keterangan <span class="text-danger">*</span></label>
                                        <select name="keterangan" id="selKeterangan"
                                                class="form-control form-control-sm" required>
                                            <option value="">-- Pilih --</option>
                                            <?php foreach (['Retur', 'Replacement'] as $ket): ?>
                                            <option value="<?= $ket ?>"
                                                <?= ($is_edit && ($row['keterangan'] ?? '') === $ket) ? 'selected' : '' ?>>
                                                <?= $ket ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Keterangan Detail</label>
                                        <input type="text" name="keterangan_detail"
                                               id="inputKetDetail"
                                               class="form-control form-control-sm"
                                               value="<?= $v('keterangan_detail') ?>"
                                               placeholder="Contoh: Barang Bermasalah Retur ke Pabrik">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Kategori</label>
                                        <select name="kategori" class="form-control form-control-sm">
                                            <option value="">-- Pilih --</option>
                                            <?php foreach (['Barang bermasalah', 'Replacement'] as $kat): ?>
                                            <option value="<?= $kat ?>"
                                                <?= ($is_edit && ($row['kategori'] ?? '') === $kat) ? 'selected' : '' ?>>
                                                <?= $kat ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.card-body -->
                    </div><!-- /.card -->

                    <div class="mb-4">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-save mr-1"></i>
                            <?= $is_edit ? 'Perbarui Data' : 'Simpan Retur' ?>
                        </button>
                        <a href="<?= base_url('kmt/retur') ?>" class="btn btn-secondary ml-2">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>

                </form>
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <strong>Copyright &copy; 2022
            <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.
        </strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block"><b>Version</b> 1.0</div>
    </footer>
    <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<script>
$(function () {
    // Format ribuan
    $('.angka').on('input', function () {
        var v = $(this).val().replace(/\D/g, '');
        $(this).val(v ? parseInt(v).toLocaleString('id-ID') : '');
    });

    // Kosongkan keterangan detail saat pilihan keterangan berubah
    $('#selKeterangan').on('change', function () {
        $('#inputKetDetail').val('').focus();
    });
});
</script>