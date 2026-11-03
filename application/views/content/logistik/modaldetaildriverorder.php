<?php foreach ($detail as $d) : ?>
    <div class="modal fade" id="maddnotepending<?= $d->kd_deliveri ?><?= $d->kd_driver ?>">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Note Pending Driver</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo form_open_multipart('add_pending_driver'); ?>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Note Pending<span class="required">*</span></label>
                            <div class="col-sm-8"><input class="form-control" type="text" id="pnd_isi" name="pnd_isi" value="" /></div>
                            <input class="form-control" type="text" id="id_isi" name="id_isi" value="<?= $d->id ?>" readonly />
                            <input class="form-control" type="text" id="kd_deliv_isi" name="kd_deliv_isi" value="<?= $d->kd_deliveri ?>" hidden readonly />
                            <input class="form-control" type="text" id="tgl_isi" name="tgl_isi" value="<?= $d->tgl_jalan ?>" readonly />
                            <input class="form-control" type="text" id="driver_isi" name="driver_isi" value="<?= $d->kd_driver ?>" hidden readonly />
                            <input class="form-control" type="text" id="truk_isi" name="truk_isi" value="<?= $d->kd_truk ?>" readonly />
                            <input class="form-control" type="text" id="destinasi_isi" name="destinasi_isi" value="<?= $d->destinasi ?>" hidden readonly />
                            <input class="form-control" type="text" id="tko_isi" name="tko_isi" value="<?= $d->nm_toko ?>" readonly />
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