<?php foreach ($detail as $d) : ?>
    <div class="modal fade" id="editdetail<?= $d->id ?>">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo form_open_multipart('editdetaildriver'); ?>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">No Urut Jalan<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" id="no_jalan_i" name="no_jalan_i" value="<?= $d->norut ?>" />
                                <input class="form-control" type="text" id="id_i" name="id_i" value="<?= $d->id ?>" readonly hidden />
                                <input class="form-control" type="text" id="kddriver" name="kddriver" value="<?= $d->kd_driver ?>" readonly hidden />
                                <input class="form-control" type="text" id="kdorder" name="kdorder" value="<?= $d->kd_deliveri ?>" readonly hidden />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Nama Driver<span class="required">*</span></label>
                            <div class="col-sm-8"><input class="form-control" type="text" id="driver_i" name="driver_i" value="<?= $d->nama_driver ?>" readonly /></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Helper<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <select name="helper_i" id="helper_i" class="form-control">
                                    <option value="<?= $d->kd_helper ?>"><?= $d->nama_helper ?></option>
                                    <option value="-">-</option>
                                    <?php foreach ($helper as $h) : ?>
                                        <option value="<?= $h->kd_helper ?>"><?= $h->nama_helper ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Kode Truk<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <select type="text" id="kd_truk_i" name="kd_truk_i" value="" class="form-control select2-allow-clear">
                                    <option value="<?= $d->kd_truk ?>"><?= $d->kd_truk ?></option>
                                    <option value="-">-</option>
                                    <?php foreach ($kdtruk as $k) : ?>
                                        <option value="<?= $k->nm_truk ?>"><?= $k->nm_truk ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Destinasi<span class="required">*</span></label>
                            <div class="col-sm-8"><input class="form-control" type="text" id="destinasi_i" name="destinasi_i" value="<?= $d->destinasi ?>" /></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Jumlah Kios<span class="required">*</span></label>
                            <div class="col-sm-8"><input class="form-control" type="text" id="jml_kios_i" name="jml_kios_i" value="<?= $d->jml_kios ?>" /></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Tonase<span class="required">*</span></label>
                            <div class="col-sm-8"><input class="form-control" type="text" id="tonase_i" name="tonase_i" value="<?= $d->tonase ?>" /></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Kubikasi<span class="required">*</span></label>
                            <div class="col-sm-8"><input class="form-control" type="text" id="kubikasi_i" name="kubikasi_i" value="<?= $d->kubikasi ?>" /></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Status<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <select name="sts_isi" id="sts_isi" class="form-control">
                                    <option value="<?= $d->sts_driver ?>"><?= $d->sts_driver ?></option>
                                    <option value="READY">READY</option>
                                    <option value="PENDING">PENDING</option>
                                    <option value="WAITING">WAITING</option>
                                    <option value="ON THE ROAD">ON THE ROAD</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Keterangan<span class="required">*</span></label>
                            <div class="col-sm-8"><input class="form-control" type="text" id="keterangan_i" name="keterangan_i" value="<?= $d->keterangan ?>" /></div>
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