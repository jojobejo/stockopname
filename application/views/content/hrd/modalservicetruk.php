<?php foreach ($truk as $l) : ?>
    <div class="modal fade" id="updatekmsekarang<?= $l->id ?>">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Input Data Km Sekarang</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo form_open_multipart('updatekmsekarang'); ?>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Km Sekarang<span class="required">*</span></label>
                            <div class="col-sm-8"><input class="form-control" type="number" id="kmnow" name="kmnow" value="<?= $l->km_sekarang ?>" />
                                <input class="form-control" type="text" id="id" name="id" value="<?= $l->id ?>" hidden />
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

<?php foreach ($truk as $l) : ?>
    <div class="modal fade" id="updatekmsebelum<?= $l->id ?>">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Input Data Km Sebelum</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo form_open_multipart('updatekmsebelum'); ?>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Km Sebelum<span class="required">*</span></label>
                            <div class="col-sm-8"><input class="form-control" type="number" id="kmnow" name="kmnow" value="<?= $l->km_sebelum ?>" />
                                <input class="form-control" type="text" id="id" name="id" value="<?= $l->id ?>" hidden />
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