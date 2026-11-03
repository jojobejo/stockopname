<?php foreach ($data as $d) :
    if ($d->tgl_keluar == '')
        $d->tgl_keluar = '-';
    if ($d->tgl_masuk == '')
        $d->tgl_masuk = '-';
    if ($d->jm_keluar == '')
        $d->jm_keluar = '-';
    if ($d->km_keluar == '0')
        $d->km_keluar = '-';
    if ($d->jm_masuk == '')
        $d->jm_masuk = '-';
    if ($d->km_masuk == '0')
        $d->km_masuk = '-'; ?>
    <div class="modal fade" id="edittmp<?= $d->id_lap_dis ?>">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Data Laporan Distribusi</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo form_open_multipart('edit_laporan_tmp_dis'); ?>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Nama Driver<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" id="driver_isi" name="driver_isi" value="<?= $d->nama_driver ?>" readonly />
                                <input class="form-control" type="text" id="id_isi" name="id_isi" value="<?= $d->id_lap_dis ?>" readonly hidden />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Plat Nomor<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" id="plat_isi" name="plat_isi" value="<?= $d->noplat ?>" readonly />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">No Lambung<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" id="nmlambung" name="nmlambung" value="<?= $d->nm_truk ?>" readonly />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Tgl Masuk<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input type="date" id="tgl_masuk_i" name="tgl_masuk_i" value="<?= $d->tgl_masuk ?>" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Jam Masuk<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <?php if ($d->jm_masuk == '') : ?>
                                    <input class="form-control" type="text" id="jm_masuk" name="jm_masuk" value="-" />
                                <?php elseif ($d->jm_masuk == '-') : ?>
                                    <input class="form-control" type="text" id="jm_masuk" name="jm_masuk" value="-" />
                                <?php elseif ($d->jm_masuk != '-') : ?>
                                    <input class="form-control" type="text" id="jm_masuk" name="jm_masuk" value="<?= $d->jm_masuk ?>" />
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Kilometer Masuk<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" id="km_masuk" name="km_masuk" value="<?= $d->km_masuk ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Tgl Keluar<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input type="date" id="tgl_keluar" name="tgl_keluar" value="<?= $d->tgl_keluar ?>" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Jam Keluar<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" id="jm_keluar" name="jm_keluar" value="<?= $d->jm_keluar ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Kilometer Keluar <span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" id="km_keluar" name="km_keluar" value="<?= $d->km_keluar ?>" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
<?php endforeach; ?>

<?php foreach ($data as $d) :
    if ($d->tgl_keluar == '')
        $d->tgl_keluar = '-';
    if ($d->tgl_masuk == '')
        $d->tgl_masuk = '-';
    if ($d->jm_keluar == '')
        $d->jm_keluar = '-';
    if ($d->km_keluar == '0')
        $d->km_keluar = '-';
    if ($d->jm_masuk == '')
        $d->jm_masuk = '-';
    if ($d->km_masuk == '0')
        $d->km_masuk = '-'; ?>
    <div class="modal fade" id="insertlap<?= $d->id_lap_dis ?>">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Data Laporan Distribusi</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo form_open_multipart('insert_laporan_tmp_dis'); ?>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-8">
                                <input class="form-control" type="text" id="id_isi" name="id_isi" value="<?= $d->id_lap_dis ?>" readonly hidden />
                                <input class="form-control" type="text" id="driver_isi" name="driver_isi" value="<?= $d->nama_driver ?>" readonly hidden />
                                <input class="form-control" type="text" id="helper_isi" name="helper_isi" value="<?= $d->nama_helper ?>" readonly hidden />
                                <input class="form-control" type="text" id="nmlambung" name="nmlambung" value="<?= $d->nm_truk ?>" readonly hidden />
                                <input class="form-control" type="text" id="plat_isi" name="plat_isi" value="<?= $d->noplat ?>" readonly hidden />
                                <input class="form-control" type="text" id="destinasi_i" name="destinasi_i" value="<?= $d->destinasi ?>" readonly hidden />
                                <input type="date" id="tgl_masuk_i" name="tgl_masuk_i" value="<?= $d->tgl_masuk ?>" class="form-control" hidden>
                                <input class="form-control" type="text" id="jm_masuk" name="jm_masuk" value="<?= $d->jm_masuk ?>" hidden />
                                <input class="form-control" type="text" id="km_masuk" name="km_masuk" value="<?= $d->km_masuk ?>" hidden />
                                <input type="date" id="tgl_keluar" name="tgl_keluar" value="<?= $d->tgl_keluar ?>" class="form-control" hidden>
                                <input class="form-control" type="text" id="jm_keluar" name="jm_keluar" value="<?= $d->jm_keluar ?>" hidden />
                                <input class="form-control" type="text" id="km_keluar" name="km_keluar" value="<?= $d->km_keluar ?>" hidden />
                                <input class="form-control" type="text" id="keterangan" name="keterangan" value="<?= $d->keterangan ?>" hidden />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <h3>Anda akan menyimpan data laporan logistik , Data akan tersimpan pada database logistik , mohon periksa kembali data yang akan dinputkan </h3>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Periksa Kembali</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
<?php endforeach; ?>