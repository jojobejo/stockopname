<div class="modal fade" id="modalTmpMutasi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Edit Temporary Mutasi</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="tmp_id">

                <div class="form-group">
                    <label>Nama Barang</label>
                    <input type="text" id="tmp_nama_barang" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label>Expired Date</label>
                    <input type="date" id="tmp_exp_date" class="form-control">
                </div>

                <div class="form-group">
                    <label>Qty</label>
                    <input type="number" id="tmp_qty" class="form-control">
                </div>

                <div class="form-group">
                    <label>Satuan</label>
                    <select id="tmp_satuan_id" class="form-control">
                        <option value="1">Pcs</option>
                        <option value="2">Box</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="btnDeleteTmp" class="btn btn-danger">Delete</button>
                <button type="button" id="btnUpdateTmp" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>
</div>