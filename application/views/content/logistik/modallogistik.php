<div class="modal fade" id="maddplat">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Penambahan Nomor Plat Baru</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo form_open_multipart('addplat'); ?>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Nomor Plat<span class="required">*</span></label>
                        <div class="col-sm-8"><input class="form-control" type="text" id="plat_isi" name="plat_isi" value="" /></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Kode Plat<span class="required">*</span></label>
                        <div class="col-sm-8"><input class="form-control" type="text" id="kd_plat_isi" name="kd_plat_isi" value="" /></div>
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

<!-- EDIT NO PLAT -->
<?php foreach ($dataplat as $d) : ?>
    <div class="modal fade" id="meditplat<?= $d->id ?>">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Nomor Plat</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo form_open_multipart('editplat'); ?>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Nomor Plat<span class="required">*</span></label>
                            <div class="col-sm-8"><input class="form-control" type="text" id="plat_isi" name="plat_isi" value="<?= $d->noplat ?>" /></div>
                            <div class="col-sm-8"><input class="form-control" type="text" id="id_isi" name="id_isi" value="<?= $d->id ?>" readonly hidden /></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Kode Plat<span class="required">*</span></label>
                            <div class="col-sm-8"><input class="form-control" type="text" id="kd_plat_isi" name="kd_plat_isi" value="<?= $d->nm_truk ?>" /></div>
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
    <!-- HAPUS NO PLAT -->
    <div class="modal fade" id="hapusnoplat<?= $d->id ?>">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hapus Nomor Plat</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo form_open_multipart('hapusplat'); ?>
                    <div class="form-group">
                        <div class="row">
                            <h3>Anda akan menghapus data no plat ini , data yang terhapus tidak akan tampil lagi </h3>
                            <div class="col-sm-8"><input class="form-control" type="text" id="id_isi" name="id_isi" value="<?= $d->id ?>" readonly hidden /></div>
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
<!-- ADD DRIVER -->
<div class="modal fade" id="adddriver">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Driver Baru</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo form_open_multipart('tambahdriver'); ?>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Nama Driver<span class="required">*</span></label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" id="driver_isi" name="driver_isi" value="" />
                            <input class="form-control" type="text" id="kd_driver" name="kd_driver" value="<?= $kd_driver ?>" readonly hidden />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Status<span class="required">*</span></label>
                        <div class="col-sm-8">
                            <select name="sts_select" id="sts_select" class="form-control">
                                <option value="ACTIVE">ACTIVE</option>
                                <option value="NON-ACTIVE">NON ACTIVE</option>
                            </select>
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

<!-- EDIT DRIVER -->
<?php foreach ($driver as $d) : ?>
    <div class="modal fade" id="editdriver<?= $d->id ?>">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Driver - <?= $d->nama_driver ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo form_open_multipart('editdriver'); ?>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Nama Driver<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" id="driver_isi" name="driver_isi" value="<?= $d->nama_driver ?>" />
                                <input class="form-control" type="text" id="kd_driver" name="kd_driver" value="<?= $d->kd_driver ?>" readonly hidden />
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
<div class="modal fade" id="editnourut">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Setting No Urut</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo form_open_multipart('editnorut'); ?>
                <?php foreach ($driverurut as $dr) : ?>
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <input class="form-control" type="text" id="driver_isi[]" name="driver_isi[]" value="<?= $dr->nama_driver ?>" readonly />
                                <input class="form-control" type="text" id="id_isi[]" name="id_isi[]" value="<?= $dr->id ?>" readonly hidden />
                            </div>
                            <div class="col">
                                <input class="form-control" type="text" id="norut[]" name="norut[]" value="<?= $dr->no_urut_hr_i ?>" />
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
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
<?php foreach ($driver as $d) : ?>
    <!-- HAPUS DRIVER -->
    <div class="modal fade" id="hapusdriver<?= $d->id ?>">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hapus Driver</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo form_open_multipart('hapusdriver'); ?>
                    <div class="form-group">
                        <h3>Anda akan menghapus data driver , data yang terhapus secara permanen</h3>
                        <input class="form-control" type="text" id="id_isi" name="id_isi" value="<?= $d->id ?>" readonly hidden />
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
<div class="modal fade" id="addhelper">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Helper Baru</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo form_open_multipart('tambahhelper'); ?>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Nama Driver<span class="required">*</span></label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" id="driver_isi" name="driver_isi" value="" />
                            <input class="form-control" type="text" id="kd_driver" name="kd_driver" value="<?= $kd_helper ?>" readonly hidden />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Status<span class="required">*</span></label>
                        <div class="col-sm-8">
                            <select name="sts_select" id="sts_select" class="form-control">
                                <option value="ACTIVE">ACTIVE</option>
                                <option value="NON-ACTIVE">NON ACTIVE</option>
                            </select>
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
<?php foreach ($helper as $h) : ?>
    <div class="modal fade" id="edithelper<?= $h->id ?>">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edi Helper </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo form_open_multipart('edithelper'); ?>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label text-right" for="id_bar">Nama Helper<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" id="driver_isi" name="driver_isi" value="<?= $h->nama_helper ?>" />
                                <input class="form-control" type="text" id="kd_driver" name="kd_driver" value="<?= $h->kd_helper ?>" readonly hidden/>
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

    <!-- HAPUS DRIVER -->
    <div class="modal fade" id="hapushelper<?= $h->id ?>">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hapus Driver</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo form_open_multipart('hapushelper'); ?>
                    <div class="form-group">
                        <h3>Anda akan menghapus data driver , data yang terhapus secara permanen</h3>
                        <input class="form-control" type="text" id="id_isi" name="id_isi" value="<?= $h->id ?>" readonly hidden />
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