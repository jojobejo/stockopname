<div class="modal fade" id="muploadlog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload Data Prepare DO</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h3>Upload CSV ke tb_pre_do</h3>
                <?php if ($this->session->flashdata('success')) : ?>
                    <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
                <?php elseif ($this->session->flashdata('error')) : ?>
                    <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                <?php endif; ?>

                <form action="<?= base_url('data_preview_do') ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Pilih File CSV</label>
                        <input type="file" name="file_csv" class="form-control" required>
                    </div>
                    <a href="<?= base_url('preview') ?>" class="btn btn-primary">Preview Data</a>
                    <button type="submit" class="btn btn-success">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updatecs">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Customer</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <?php if ($this->session->flashdata('success')) : ?>
                    <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
                <?php elseif ($this->session->flashdata('error')) : ?>
                    <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                <?php endif; ?>
                <form action="<?= site_url('custupdate') ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Pilih File CSV</label>
                        <input type="file" name="fileCSV" class="form-control" required accept=".csv">
                    </div>
                    <button type="submit" class="btn btn-primary">Import</button>
                </form>
            </div>

        </div>
    </div>
</div>