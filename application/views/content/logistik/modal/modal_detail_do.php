<?php foreach ($kdo as $k) : ?>
    <div class="modal fade" id="edited_rute">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Edit Rute</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form action="<?= base_url('edited_rute_do') ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Regional DO</label>
                            <input type="text" name="kddo" id="kddo" class="form-control" value="<?= $k->kd_do ?>" readonly required hidden>
                            <input type="text" name="id" id="id" class="form-control" value="<?= $k->id ?>" readonly required hidden>
                            <input type="text" name="regional" id="regional" class="form-control" value="<?= $k->regional ?>" required>
                        </div>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
<?php endforeach; ?>