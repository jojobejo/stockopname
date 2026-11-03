<div class="modal fade" id="addkpi">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Data Karyawan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo form_open_multipart('add_kpi_baru'); ?>
                <div class="form-group" hidden>
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Buat KPI Baru<span class="required">*</span></label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" id="kd_isi" name="kd_isi" value="<?= $this->session->userdata('kduser') ?>" />
                            <input class="form-control" type="text" id="sub_isi" name="sub_isi" value="<?= $this->session->userdata('subdepartemen') ?>" />
                            <input class="form-control" type="text" id="dep_isi" name="dep_isi" value="<?= $this->session->userdata('departemen') ?>" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-3 control-label text-right" for="id_bar">Tanggal Pembuatan<span class="required">*</span></label>
                        <?php
                        $now = date('Y-m-d')
                        ?>
                        <div class="col-sm-8">
                            <input class="form-control" type="date" id="date_isi" name="date_isi" value="" />
                            <input class="form-control" type="text" id="date_isi1" name="date_isi1" value="<?= format_bulan($now) ?>" hidden />
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