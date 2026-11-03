<?php foreach ($deliveri as $l) : ?>
    <div class="modal fade" id="hapusdetail<?= $l->kd_order ?>">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hapus Deliveri Order - <?= format_indo($l->tgl_jalan) ?> </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo form_open_multipart('hapus_detail_order'); ?>
                    <h3>DATA YANG TERHAPUS AKAN HILANG PERMANEN</h3>
                    <div class="form-group" hidden>
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">tanggal<span class="required">*</span></label>
                            <input class="form-control" type="text" id="id_isi" name="id_isi" value="<?= $l->kd_order ?>" hidden />
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