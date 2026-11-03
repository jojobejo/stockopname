<div class="modal fade" id="addInventaris">
    <div class="modal-dialog modal-lg"> 
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Inventaris Baru</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo form_open_multipart('addinventaris'); ?>
                <div class="form-group">
                <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Kode Inventaris<span class="required">*</span></label>
                        <div class="col-sm-8"><input class="form-control" type="text" id="kd_invetaris" name="kd_invetaris" value="" /></div>                        
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Kode PIC<span class="required">*</span></label>
                        <div class="col-sm-8"><input class="form-control" type="text" id="kd_pic" name="kd_pic" value="" /></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Departement<span class="required">*</span></label>
                        <div class="col-sm-8"><input class="form-control" type="text" id="dept" name="dept" value="" /></div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Type Barang<span class="required">*</span></label>
                        <div class="col-sm-8">
                            <select name="type_brg" id="type_brg" class="form-control">
                                <option value="PC">PROCESSOR</option>
                                <option value="RAM">RAM</option>
                                <option value="HARDISK">HARDISK</option>
                                <option value="POWERSUPPLY">POWERSUPPLY</option>
                                <option value="VGA">VGA</option>
                                <option value="PRINTER">PRINTER</option>
                                <option value="OTHER">OTHER</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Descripsi<span class="required">*</span></label>
                        <div class="col-sm-8"><input class="form-control" type="text" id="Desc" name="Desc" value="" /></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Tanggal Beli<span class="required">*</span></label>
                        <div class="col-sm-8"><input class="form-control" type="date" id="tgl_beli" name="tgl_beli" value="" /></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Status<span class="required">*</span></label>
                        <div class="col-sm-8">
                            <select name="stat" id="stat" class="form-control">
                                <option value="Aktif">Aktif</option>
                                <option value="Non Aktif">Non Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">History<span class="required">*</span></label>
                        <div class="col-sm-8"><input class="form-control" type="text" id="hist" name="hist" value="" /></div>
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

<?php foreach ($datainventaris as $d) : ?>
<div class="modal fade" id="editinventaris<?= $d->id ?>">
    <div class="modal-dialog modal-lg"> 
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pembelian Inventaris Baru</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo form_open_multipart('editinventaris'); ?>                
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Kode Inventaris<span class="required">*</span></label>
                        <div class="col-sm-8"><input class="form-control" type="text" id="kd_invetaris" name="kd_invetaris" value="<?= $d->kdinventaris ?>"readonly/></div>
                        <div class="col-sm-8"><input class="form-control" type="text" id="id_invetaris" name="id_invetaris" value="<?= $d->id ?>"readonly hidden /></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">PIC<span class="required">*</span></label>
                        <div class="col-sm-8"><input class="form-control" type="text" id="kd_pic" name="kd_pic" value="<?= $d->kdpic ?>" /></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Departement<span class="required">*</span></label>
                        <div class="col-sm-8"><input class="form-control" type="text" id="dept" name="dept" value="<?= $d->departement ?>" /></div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Type Barang<span class="required">*</span></label>
                        <div class="col-sm-8">
                            <select name="kat_isi" id="kat_isi" class="form-control">
                                <option value="PC">PROCESSOR</option>
                                <option value="RAM">RAM</option>
                                <option value="HARDISK">HARDISK</option>
                                <option value="POWERSUPPLY">POWERSUPPLY</option>
                                <option value="VGA">VGA</option>
                                <option value="PRINTER">PRINTER</option>
                                <option value="OTHER">OTHER</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Descripsi<span class="required">*</span></label>
                        <div class="col-sm-8"><input class="form-control" type="text" id="Desc" name="Desc" value="<?= $d->deskripsi ?>" /></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Tanggal Beli<span class="required">*</span></label>
                        <div class="col-sm-8"><input class="form-control" type="date" id="tgl_beli" name="tgl_beli" value="<?= $d->tglbeli ?>" /></div>
                    </div>
                </div>                
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Status<span class="required">*</span></label>
                        <div class="col-sm-8">
                            <select name="kat_isi" id="kat_isi" class="form-control">
                                <option value="Aktif">Aktif</option>
                                <option value="Non Aktif">Non Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">History<span class="required">*</span></label>
                        <div class="col-sm-8"><input class="form-control" type="text" id="hist" name="hist" value="<?= $d->history ?>" /></div>
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

<?php foreach ($datainventaris as $d) : ?>
    <div class="modal fade" id="hapusinventaris<?= $d->id ?>">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hapus Inventaris <?= $d->kdinventaris ?> </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo form_open_multipart('hapusinventaris1'); ?>
                    <h3>DATA YANG TERHAPUS AKAN HILANG PERMANEN</h3>
                    <div class="form-group" hidden >
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">kode inventaris<span class="required">*</span></label>
                            <div class="col-sm-8"><input class="form-control" type="text" id="id_invetaris" name="id_invetaris" value="<?= $d->id ?>" /></div>
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
