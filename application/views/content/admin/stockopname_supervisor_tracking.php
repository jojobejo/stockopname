<body class="hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view('partial/main/navbar') ?>
    <?php $this->load->view('partial/main/sidebar') ?>
    <?php $e = function ($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }; ?>
    <div class="content-wrapper" style="background:#f5f7fb">
        <section class="content-header">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <h1 class="m-0">Tracking Inputer - <?= $e($nama_wilayah ?? '-') ?></h1>
                <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('supervisi-opname') ?>"><i class="fas fa-arrow-left"></i> Supervisi Opname</a>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <form method="get" action="<?= base_url('supervisi-opname/tracking') ?>">
                            <div class="form-group mb-0">
                                <label for="filterWilayah" class="small font-weight-bold text-uppercase">Filter Wilayah Supervisi</label>
                                <select class="form-control" id="filterWilayah" name="wilayah" onchange="this.form.submit()">
                                    <?php foreach (($wilayah_rows ?? []) as $wilayahRow) : ?>
                                        <option value="<?= (int)$wilayahRow['id'] ?>" <?= (int)$wilayah_filter === (int)$wilayahRow['id'] ? 'selected' : '' ?>><?= $e($wilayahRow['nama_wilayah']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card shadow-sm">
                    <div class="card-header">
                        <strong>Perbandingan Input Tim 1 dan Tim 2 - <?= $e($nama_wilayah ?? '-') ?></strong>
                        <span class="float-right text-muted small"><span id="trackingTotal">0</span> data</span>
                    </div>
                    <div class="card-body">
                        <div class="form-row align-items-end mb-3">
                            <div class="form-group col-md-7">
                                <label for="searchTracking" class="small font-weight-bold text-uppercase">Cari Nama Barang / Kode Barang</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="searchTracking" placeholder="Ketik nama barang atau kode barang">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary" id="btnSearchTracking"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="statusTracking" class="small font-weight-bold text-uppercase">Status</label>
                                <select class="form-control" id="statusTracking">
                                    <option value="">Semua Status</option>
                                    <option value="SAMA">SAMA</option>
                                    <option value="RE-CHECK">RE-CHECK</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <button type="button" class="btn btn-outline-secondary btn-block" id="btnResetTracking"><i class="fas fa-sync-alt"></i></button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0" id="tableSupervisorTracking" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Expired</th>
                                        <th class="text-right">Qty Tim 1</th>
                                        <th class="text-right">Qty Tim 2</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<script>
$(function() {
    var trackingTable = $('#tableSupervisorTracking').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 10,
        lengthChange: false,
        searching: false,
        ordering: false,
        autoWidth: false,
        responsive: true,
        ajax: {
            url: "<?= base_url('supervisi-opname/tracking/list') ?>",
            type: "GET",
            data: function(d) {
                d.wilayah = "<?= (int)$wilayah_filter ?>";
                d.keyword = $.trim($('#searchTracking').val());
                d.status = $('#statusTracking').val();
            },
            dataSrc: function(json) {
                $('#trackingTotal').text((json.recordsFiltered || 0).toLocaleString('id-ID'));
                return json.data || [];
            }
        },
        columns: [
            {data: 'nama_barang'},
            {data: 'expired'},
            {data: 'qty_tim_1', className: 'text-right'},
            {data: 'qty_tim_2', className: 'text-right'},
            {data: 'status'}
        ],
        language: {
            processing: 'Memuat data...',
            zeroRecords: 'Data tidak ditemukan.',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
            infoEmpty: 'Menampilkan 0 data',
            infoFiltered: '',
            paginate: {
                previous: 'Sebelumnya',
                next: 'Berikutnya'
            }
        }
    });

    var searchTimer = null;
    $('#searchTracking').on('keyup', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function() {
            trackingTable.ajax.reload();
        }, 350);
    });

    $('#btnSearchTracking').on('click', function() {
        trackingTable.ajax.reload();
    });

    $('#statusTracking').on('change', function() {
        trackingTable.ajax.reload();
    });

    $('#btnResetTracking').on('click', function() {
        $('#searchTracking').val('');
        $('#statusTracking').val('');
        trackingTable.ajax.reload();
    });
});
</script>
