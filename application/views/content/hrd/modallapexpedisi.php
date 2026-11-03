<div class="modal fade" id="addexpedisi">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Laporan Expedisi</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo form_open_multipart('tambah_lap_expedisi'); ?>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Tanggal<span class="required">*</span></label>
                        <div class="col-sm-8"><input class="form-control" type="date" id="tanggal" name="tanggal" value="" /></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Jam Masuk<span class="required">*</span></label>
                        <div class="col-sm-8"><input class="form-control" type="text" id="jammasuk" name="jammasuk" value="" /></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Jam Keluar<span class="required">*</span></label>
                        <div class="col-sm-8"><input class="form-control" type="text" id="jamkeluar" name="jamkeluar" value="" /></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">No Pol<span class="required">*</span></label>
                        <div class="col-sm-8"><input class="form-control" type="text" id="nopol" name="nopol" value="" /></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Nama Driver<span class="required">*</span></label>
                        <div class="col-sm-8"><input class="form-control" type="text" id="namadriver" name="namadriver" value="" /></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">No Tlpn Driver<span class="required">*</span></label>
                        <div class="col-sm-8"><input class="form-control" type="text" id="notlpndriver" name="notlpndriver" value="" /></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Perusahaan Pengirim<span class="required">*</span></label>
                        <div class="col-sm-8"><input class="form-control" type="text" id="perusahaanpengirim" name="perusahaanpengirim" value="" /></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Nama Barang<span class="required">*</span></label>
                        <div class="col-sm-8"><input class="form-control" type="text" id="namabarang" name="namabarang" value="" /></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Jumlah Barang<span class="required">*</span></label>
                        <div class="col-sm-8"><input class="form-control" type="text" id="jumlahbarang" name="jumlahbarang" value="" /></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Keterangan<span class="required">*</span></label>
                        <div class="col-sm-8"><input class="form-control" type="text" id="keterangan" name="keterangan" value="" /></div>
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

<!-- MODAL EDIT -->
<?php foreach ($laporan as $l) : ?>
    <div class="modal fade" id="editexpedisi<?= $l->id ?>">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Laporan Expedisi</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo form_open_multipart('edit_lap_expedisi'); ?>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">tanggal<span class="required">*</span></label>
                            <div class="col-sm-8"><input class="form-control" type="date" id="tanggal" name="tanggal" value="<?= $l->tanggal ?>" />
                                <input class="form-control" type="text" id="id" name="id" value="<?= $l->id ?>" hidden />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Jam Masuk<span class="required">*</span></label>
                            <div class="col-sm-8"><input class="form-control" type="text" id="jammasuk" name="jammasuk" value="<?= $l->jammasuk ?>" /></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Jam Keluar<span class="required">*</span></label>
                            <div class="col-sm-8"><input class="form-control" type="text" id="jamkeluar" name="jamkeluar" value="<?= $l->jamkeluar ?>" /></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">No Pol<span class="required">*</span></label>
                            <div class="col-sm-8"><input class="form-control" type="text" id="nopol" name="nopol" value="<?= $l->nopol ?>" /></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Nama Driver<span class="required">*</span></label>
                            <div class="col-sm-8"><input class="form-control" type="text" id="namadriver" name="namadriver" value="<?= $l->namadriver  ?>" /></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">No Tlpn Driver<span class="required">*</span></label>
                            <div class="col-sm-8"><input class="form-control" type="text" id="notlpndriver" name="notlpndriver" value="<?= $l->notlpndriver ?>" /></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Perusahaan Pengirim<span class="required">*</span></label>
                            <div class="col-sm-8"><input class="form-control" type="text" id="perusahaanpengirim" name="perusahaanpengirim" value="<?= $l->perusahaanpengirim ?>" /></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Nama Barang<span class="required">*</span></label>
                            <div class="col-sm-8"><input class="form-control" type="text" id="namabarang" name="namabarang" value="<?= $l->namabarang ?>" /></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Jumlah Barang<span class="required">*</span></label>
                            <div class="col-sm-8"><input class="form-control" type="text" id="jumlahbarang" name="jumlahbarang" value="<?= $l->jumlahbarang ?>" /></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Keterangan<span class="required">*</span></label>
                            <div class="col-sm-8"><input class="form-control" type="text" id="keterangan" name="keterangan" value="<?= $l->keterangan ?>" /></div>
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
<!-- MODAL HAPUS -->
<?php foreach ($laporan as $l) : ?>
    <div class="modal fade" id="hapuslapexpedisi<?= $l->id ?>">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Apakah Anda </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo form_open_multipart('hapus_lap_expedisi'); ?>
                    <h3>DATA YANG TERHAPUS AKAN HILANG PERMANEN</h3>
                    <div class="form-group" hidden>
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">tanggal<span class="required">*</span></label>
                            <input class="form-control" type="text" id="id_isi" name="id_isi" value="<?= $l->id ?>" hidden />
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