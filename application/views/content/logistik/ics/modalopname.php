<?php foreach ($data_ics as $itm) : ?>
    <div class="modal fade" id="muploadlog<?= $itm->id ?>">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Input Opname</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" action="<?= base_url('insertopname') ?>">
                        <?php date_default_timezone_set("Asia/Jakarta"); ?>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-3 control-label text-right" for="id_bar">Nama Barang<span class="required">*</span></label>
                                <div class="col-sm-8"><input class="form-control" type="text" id="nmbarang" name="nmbarang" value="<?= $itm->nama_barang ?>" readonly /></div>
                                <div class="col-sm-8"><input class="form-control" type="text" id="dimensi" name="dimensi" value="<?= $itm->dimensi ?>" hidden /></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-3 control-label text-right" for="id_bar">Tanggal Expired<span class="required">*</span></label>
                                <div class="col-sm-8"><input class="form-control" type="text" id="expdate" name="expdate" value="<?= $itm->exp_date ?>" readonly /></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-3 control-label text-right" for="id_bar">Qty Box<span class="required">*</span></label>
                                <div class="col-sm-8"><input class="form-control" type="text" id="qtybox" name="qtybox" value="" /></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-3 control-label text-right" for="id_bar">Qty Pcs<span class="required">*</span></label>
                                <div class="col-sm-8"><input class="form-control" type="text" id="qtypcs" name="qtypcs" value="" /></div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success btn-sm">Save</button>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
<?php endforeach; ?>