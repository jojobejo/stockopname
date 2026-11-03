<?php foreach ($kpi as $k) : ?>
    <div class="modal fade" id="EditModalIndikator<?= $k->id ?>" tabindex="-1" aria-labelledby="EditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="exampleModalLabel">Edit Point Indikator : </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php echo form_open_multipart('indikator_kpi'); ?>
                    <input hidden value="edit_indikator_kpi" type="text" name="action" id="action">
                    <input hidden value="<?= $this->session->userdata('kduser') ?>" type="text" name="kduser" id="kduser">
                    <input hidden value="<?= $k->id ?>" type="text" name="id" id="id">
                    <div class="input-group mb-3">
                        <span style="color : #343A40;" class="input-group-text  fw-bold" id="whatindikator">Tujuan :</span>
                        <input type="text" class="form-control" name="whatindikator" id="whatindikator" placeholder="Keterangan" aria-label="What Indikator" aria-describedby="whatindikator" value="<?= $k->nm_poin ?>">
                    </div>
                    <div class="input-group mb-3">
                        <span style="color : #343A40;" class="input-group-text fw-bold" id="nilaiindi">Bobot :</span>
                        <input type="number" class="form-control" name="nilaiindi" id="nilaiindi" placeholder="Nilai" aria-label="Hasil KPI" aria-describedby="hasil" value="<?= $k->bobot_poin ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="whatindi_add" class="btn btn-primary">Tambah</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<?php foreach ($kpi as $k) : ?>
    <div class="modal fade" id="HapusModalindikator<?= $k->id ?>" tabindex="-1" aria-labelledby="EditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="exampleModalLabel">Edit Point Indikator : </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php echo form_open_multipart('indikator_kpi'); ?>
                    <input hidden value="edit_indikator_kpi" type="text" name="action" id="action">
                    <input hidden value="<?= $this->session->userdata('kduser') ?>" type="text" name="kduser" id="kduser">
                    <input hidden value="<?= $k->id ?>" type="text" name="id" id="id">
                    <div class="input-group mb-3">
                        <span style="color : #343A40;" class="input-group-text  fw-bold" id="whatindikator">Tujuan :</span>
                        <input type="text" class="form-control" name="whatindikator" id="whatindikator" placeholder="Keterangan" aria-label="What Indikator" aria-describedby="whatindikator" value="<?= $k->nm_poin ?>">
                    </div>
                    <div class="input-group mb-3">
                        <span style="color : #343A40;" class="input-group-text fw-bold" id="nilaiindi">Bobot :</span>
                        <input type="number" class="form-control" name="nilaiindi" id="nilaiindi" placeholder="Nilai" aria-label="Hasil KPI" aria-describedby="hasil" value="<?= $k->bobot_poin ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="whatindi_add" class="btn btn-primary">Tambah</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<!-- MODAL POINT -->
<div class="modal fade" id="addwhatindi" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="exampleModalLabel">Tambah Point Indikator : </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php echo form_open_multipart('indikator_kpi'); ?>
                <input hidden value="add_indikator_kpi" type="text" name="action" id="action">
                <input hidden value="<?= $this->session->userdata('kduser') ?>" type="text" name="kduser" id="kduser">
                <div class="input-group mb-3">
                    <span style="color : #343A40;" class="input-group-text  fw-bold" id="whatindikator">Tujuan :</span>
                    <input type="text" class="form-control" name="whatindikator" id="whatindikator" placeholder="Keterangan" aria-label="What Indikator" aria-describedby="whatindikator">
                </div>
                <div class="input-group mb-3">
                    <span style="color : #343A40;" class="input-group-text fw-bold" id="nilaiindi">Bobot :</span>
                    <input type="number" class="form-control" name="nilaiindi" id="nilaiindi" placeholder="Nilai" aria-label="Hasil KPI" aria-describedby="hasil">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="whatindi_add" class="btn btn-primary">Tambah</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>