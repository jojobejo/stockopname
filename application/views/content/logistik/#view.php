#view
<div class="card">
    <div class="card-body">
        <div class="row mt-3">
            <div class="col-md-4">
                <label>Rentang Tanggal</label>
                <input type="text" id="filter_tanggal_driver" class="form-control" placeholder="YYYY-MM-DD - YYYY-MM-DD">
            </div>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="tbl_driver_rute">
                <thead id="thead_rute"></thead>
                <tbody id="tbody_driver"></tbody>
            </table>
        </div>
    </div>
</div>

#controller