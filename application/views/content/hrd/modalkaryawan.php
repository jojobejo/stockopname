<?php foreach ($karyawan as $k) : ?>
    <div class="modal fade" id="editkaryawan<?= $k->id ?>">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Data Karyawan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo form_open_multipart('edit_karyawan'); ?>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">NIK<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" id="id" name="id" value="<?= $k->id ?>" hidden />
                                <input class="form-control" type="text" id="nik_isi" name="nik_isi" value="<?= $k->nik ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Nama Karyawan<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" id="nm_isi" name="nm_isi" value="<?= $k->nama_lengkap ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Nama Karyawan<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <select name="departemen_i" id="departemen_i" class="form-control">
                                    <option value="<?= $k->departemen ?>"><?= $k->departemen ?></option>
                                    <option value="HRD GA">HRD GA</option>
                                    <option value="KEUANGAN">KEUANGAN</option>
                                    <option value="LOGISTIK">LOGISTIK</option>
                                    <option value="SALES">SALES</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Alamat Karyawan<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <textarea name="alamat_isi" id="alamat_isi" cols="30" rows="10" class="form-control"><?= $k->alamat ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Tgl Lahir<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input class="form-control" type="date" id="tgl_isi" name="tgl_isi" value="<?= $k->tgl_lahir ?>" />
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
    </div>
<?php endforeach; ?>

<div class="modal fade" id="addkaryawan">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Data Karyawan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo form_open_multipart('add_karyawan'); ?>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">NIK<span class="required">*</span></label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" id="nik_isi" name="nik_isi" value="" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Nama Karyawan<span class="required">*</span></label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" id="nm_isi" name="nm_isi" value="" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Nama Karyawan<span class="required">*</span></label>
                        <div class="col-sm-8">
                            <select name="departemen_i" id="departemen_i" class="form-control">
                                <option value="HRD GA">HRD GA</option>
                                <option value="KEUANGAN">KEUANGAN</option>
                                <option value="LOGISTIK">LOGISTIK</option>
                                <option value="SALES">SALES</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Alamat Karyawan<span class="required">*</span></label>
                        <div class="col-sm-8">
                            <textarea name="alamat_isi" id="alamat_isi" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Tgl Lahir<span class="required">*</span></label>
                        <div class="col-sm-8">
                            <input class="form-control" type="date" id="tgl_isi" name="tgl_isi" value="" />
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
</div>