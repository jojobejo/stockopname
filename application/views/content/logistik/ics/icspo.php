<!-- icspo.php -->
<?php
$lastSyncTime = !empty($last_sync['sync_time']) ? $last_sync['sync_time'] : '-';
$lastSyncInserted = isset($last_sync['inserted']) ? (int) $last_sync['inserted'] : 0;
$lastSyncUpdated = isset($last_sync['updated']) ? (int) $last_sync['updated'] : 0;
$lastSyncSkipped = isset($last_sync['skipped']) ? (int) $last_sync['skipped'] : 0;
$isAdminPo = !empty($is_admin_po);
$canSyncPo = !empty($can_sync_po);
$showLpbActions = !$isAdminPo && $this->session->userdata('lv') == '1' && $this->session->userdata('jobdesk') != 'ADMINICS';
?>
<style>
    .sync-summary-card {
        border: 0;
        border-radius: 12px;
        box-shadow: 0 10px 24px rgba(13, 110, 253, 0.08);
    }

    .sync-stat-box {
        border-radius: 10px;
        padding: 16px;
        color: #fff;
        min-height: 100%;
    }

    .sync-stat-box h4 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
    }

    .sync-stat-box p {
        margin: 4px 0 0;
        font-size: 13px;
        opacity: .9;
    }

    .sync-stat-primary {
        background: linear-gradient(135deg, #0d6efd 0%, #4f8cff 100%);
    }

    .sync-stat-success {
        background: linear-gradient(135deg, #198754 0%, #38c172 100%);
    }

    .sync-stat-warning {
        background: linear-gradient(135deg, #fd7e14 0%, #f6ad55 100%);
    }

    .sync-stat-dark {
        background: linear-gradient(135deg, #343a40 0%, #4b5563 100%);
    }

    .sync-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        justify-content: space-between;
    }

    .sync-meta {
        font-size: 13px;
        color: #6c757d;
    }

    .sync-meta strong {
        color: #212529;
    }

    .table-sync-result td,
    .table-sync-result th {
        vertical-align: middle;
        white-space: nowrap;
    }

    .po-progress-wrap {
        min-width: 150px;
        max-width: 170px;
        margin: 0 auto;
    }

    .po-progress-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .po-progress-track {
        width: 100%;
        height: 9px;
        border-radius: 999px;
        overflow: hidden;
        background: #e9ecef;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.08);
    }

    .po-progress-fill {
        height: 100%;
        border-radius: 999px;
        transition: width .3s ease;
    }

    .po-progress-fill.is-danger {
        background: linear-gradient(90deg, #dc3545 0%, #ff6b6b 100%);
    }

    .po-progress-fill.is-warning {
        background: linear-gradient(90deg, #f39c12 0%, #f6c23e 100%);
    }

    .po-progress-fill.is-success {
        background: linear-gradient(90deg, #28a745 0%, #5ad67d 100%);
    }

    .po-progress-note {
        margin-top: 4px;
        font-size: 10px;
        color: #6c757d;
    }
</style>

<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>

        <div class="content-wrapper">
            <div class="content-header">
                <section class="content">
                    <?php if (!$isAdminPo && $this->session->userdata('lv') == '1' && $this->session->userdata('jobdesk') != 'ADMINLOGLPB') : ?>
                        <div class="row">
                            <div class="col-auto">
                                <a href="<?= base_url('ics/ics_diffrent') ?>" class="btn btn-md btn-primary w-100 mb-3">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('ics/icsdo') ?>" class="btn btn-md btn-primary w-100 mb-3">
                                    <i class="fas fa-minus-circle"></i> Data DO
                                </a>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('ics/icspo') ?>" class="btn btn-md btn-secondary w-100 mb-3">
                                    <i class="fas fa-plus-circle"></i> Data LPB
                                </a>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('ics/retur') ?>" class="btn btn-md btn-primary w-100 mb-3">
                                    <i class="fas fa-plus-circle"></i> Data Retur
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title">
                                <i class="fas fa-plus-circle mr-2"></i> <?= $isAdminPo ? 'Data PO Invoice Pending' : 'Data LPB (Laporan Penerimaan Barang)' ?>
                            </h3>
                            <?php if ($canSyncPo) : ?>
                                <div class="sync-meta text-md-right">
                                    <div>Waktu sync terakhir: <strong id="sync-last-time"><?= htmlspecialchars($lastSyncTime) ?></strong></div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="card-body">
                            <?php if ($canSyncPo) : ?>
                            <div class="container-fluid px-0" hidden>
                                <div class="row mb-3" id="header-title-sync">
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="sync-stat-box sync-stat-primary">
                                            <h4 id="sync-total-inserted"><?= $lastSyncInserted ?></h4>
                                            <p>Data baru tersimpan</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="sync-stat-box sync-stat-success">
                                            <h4 id="sync-total-updated"><?= $lastSyncUpdated ?></h4>
                                            <p>Data berhasil diperbarui</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="sync-stat-box sync-stat-warning">
                                            <h4 id="sync-total-skipped"><?= $lastSyncSkipped ?></h4>
                                            <p>Data dilewati</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="sync-stat-box sync-stat-dark">
                                            <h4 id="sync-total-rows"><?= count($sync_rows) ?></h4>
                                            <p>Baris ditampilkan</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="container-fluid px-0">
                                <form action="<?= base_url('ics/icspo') ?>" method="post">
                                    <div class="row mb-3">
                                        <div class="col-md-2 col-sm-6 mb-2">
                                            <input type="date" class="form-control" name="date1" value="<?= $date1 ?? '' ?>">
                                        </div>
                                        <div class="col-md-2 col-sm-6 mb-2">
                                            <input type="date" class="form-control" name="date2" value="<?= $date2 ?? '' ?>">
                                        </div>
                                        <div class="col-md-2 col-sm-6 mb-2">
                                            <button class="btn btn-primary btn-block">
                                                <i class="fas fa-search"></i> Tampil
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <?php if ($canSyncPo) : ?>
                                    <div class="row mb-3" id="button-row-lpb">
                                        <?php if ($showLpbActions) : ?>
                                        <div class="col-md-2 col-sm-6 mb-2">
                                            <a class="btn btn-success btn-block" href="<?= base_url('data_lpb_zahir') ?>">
                                                <i class="fas fa-file-csv"></i> Data LPB
                                            </a>
                                        </div>
                                        <div class="col-md-2 col-sm-6 mb-2">
                                            <a class="btn btn-success btn-block" href="<?= base_url('ics/retur') ?>">
                                                <i class="fas fa-undo"></i> Data Retur
                                            </a>
                                        </div>
                                        <?php endif; ?>
                                        <div class="col-md-2 col-sm-6 mb-2">
                                            <button class="btn btn-primary btn-block" id="btn-sync-po">
                                                <i class="fas fa-sync"></i> Sync PO
                                            </button>
                                        </div>
                                    </div>
                                    <div class="alert d-none" id="sync-alert"></div>
                                <?php elseif ($this->session->userdata('lv') == '2') : ?>
                                    <div class="row mb-3">
                                        <div class="col-md-2 col-sm-6 mb-2">
                                            <button class="btn btn-success btn-block" data-toggle="modal" data-target="#modalImportCSV">
                                                <i class="fas fa-file-csv"></i> Import CSV
                                            </button>
                                        </div>
                                        <div class="col-md-2 col-sm-6 mb-2">
                                            <a class="btn btn-success btn-block" href="<?= base_url('ics/retur') ?>">
                                                <i class="fas fa-undo"></i> Retur
                                            </a>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="modalImportCSV" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <form action="<?= base_url('ics/import_csv') ?>" method="post" enctype="multipart/form-data">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-success">
                                                        <h5 class="modal-title">Import Data PO dari CSV</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Pilih File CSV</label>
                                                            <input type="file" name="file_csv" class="form-control" required accept=".csv">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success">Import</button>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="idtb_ics_po">
                                        <thead class="thead-dark text-center">
                                            <?php if ($isAdminPo) : ?>
                                            <tr>
                                                <th>No PO</th>
                                                <th>Tgl Transaksi</th>
                                                <th>Nama Supplier</th>
                                                <th class="text-center">Progress</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center" style="width:90px;">#</th>
                                            </tr>
                                            <?php else : ?>
                                            <tr>
                                                <th>No PO</th>
                                                <th>Tgl Transaksi</th>
                                                <th>Kode Supplier</th>
                                                <th>Nama Supplier</th>
                                                <th class="text-center">Total Barang Order</th>
                                                <th class="text-center">Total Barang Diterima</th>
                                                <th class="text-center">Progress</th>
                                                <th class="text-center">Input Terakhir</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center" style="width:90px;">#</th>
                                            </tr>
                                            <?php endif; ?>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($lpb)) : ?>
                                                <?php foreach ($lpb as $row) :
                                                    $jumlah_barang_order    = (int) ($row['total_barang_order'] ?? 0);
                                                    $jumlah_barang_diterima = (int) ($row['total_barang_diterima'] ?? 0);
                                                    $jumlah_qty_order       = (float) ($row['total_qty_order'] ?? 0);
                                                    $jumlah_qty_diterima    = (float) ($row['total_qty_diterima'] ?? 0);
                                                    $status                 = strtolower(trim((string) ($row['status'] ?? '')));
                                                    $progressRaw            = (float) ($row['progress_persen'] ?? 0);
                                                    $progress               = max(0, min(100, $progressRaw));
                                                    $progressText           = rtrim(rtrim(number_format($progress, 2, '.', ''), '0'), '.');

                                                    if (floor($jumlah_qty_order) == $jumlah_qty_order) {
                                                        $jumlah_qty_order = (int) $jumlah_qty_order;
                                                    }

                                                    if (floor($jumlah_qty_diterima) == $jumlah_qty_diterima) {
                                                        $jumlah_qty_diterima = (int) $jumlah_qty_diterima;
                                                    }

                                                    if ($status === 'done') {
                                                        $rowClass = 'table-success';
                                                        $progressClass = 'is-success';
                                                        $badge = '<span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i> Done</span>';
                                                    } elseif ($status === 'partial') {
                                                        $rowClass = 'table-warning';
                                                        $progressClass = 'is-warning';
                                                        $badge = '<span class="badge badge-warning px-2 py-1"><i class="fas fa-clock mr-1"></i> Partial</span>';
                                                    } else {
                                                        $rowClass = '';
                                                        $progressClass = 'is-danger';
                                                        $badge = '<span class="badge badge-danger px-2 py-1"><i class="fas fa-times mr-1"></i> Belum</span>';
                                                    }
                                                ?>
                                                    <?php if ($isAdminPo) : ?>
                                                    <tr class="<?= $rowClass ?>">
                                                        <td><?= htmlspecialchars($row['no_po'] ?? '') ?></td>
                                                        <td><?= htmlspecialchars($row['tgl_transaksi'] ?? '') ?></td>
                                                        <td><?= htmlspecialchars($row['nm_suplier'] ?? '-') ?></td>
                                                        <td>
                                                            <div class="po-progress-wrap">
                                                                <div class="po-progress-label">
                                                                    <span><?= $progressText ?>%</span>
                                                                    <span><?= htmlspecialchars(ucfirst($status)) ?></span>
                                                                </div>
                                                                <div class="po-progress-track">
                                                                    <div class="po-progress-fill <?= $progressClass ?>" style="width: <?= $progress ?>%;"></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center"><?= $badge ?></td>
                                                        <td class="text-center">
                                                            <a href="<?= base_url('ics/detail_record_lpb?kd_po=' . urlencode($row['kd_po'] ?? '') . '&no_po=' . urlencode($row['no_po'] ?? '') . '&kd_suplier=' . urlencode($row['kdsupp'] ?? '')) ?>" class="btn btn-info btn-sm">
                                                                <i class="fas fa-list"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php else : ?>
                                                    <tr class="<?= $rowClass ?>">
                                                        <td><?= htmlspecialchars($row['no_po'] ?? '') ?></td>
                                                        <td><?= htmlspecialchars($row['tgl_transaksi'] ?? '') ?></td>
                                                        <td><?= htmlspecialchars($row['kdsupp'] ?? '') ?></td>
                                                        <td><?= htmlspecialchars($row['nm_suplier'] ?? '-') ?></td>
                                                        <td class="text-center font-weight-bold"><?= $jumlah_barang_order ?></td>
                                                        <td class="text-center font-weight-bold <?= $jumlah_barang_diterima > 0 ? 'text-success' : 'text-danger' ?>">
                                                            <?= $jumlah_barang_diterima ?>
                                                        </td>
                                                        <td>
                                                            <div class="po-progress-wrap">
                                                                <div class="po-progress-label">
                                                                    <span><?= $progressText ?>%</span>
                                                                    <span><?= $jumlah_qty_diterima ?> / <?= $jumlah_qty_order ?></span>
                                                                </div>
                                                                <div class="po-progress-track">
                                                                    <div class="po-progress-fill <?= $progressClass ?>" style="width: <?= $progress ?>%;"></div>
                                                                </div>
                                                                <div class="po-progress-note">Berdasarkan qty diterima</div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center"><?= htmlspecialchars($row['input_terakhir'] ?? '-') ?></td>
                                                        <td class="text-center"><?= $badge ?></td>
                                                        <td class="text-center">
                                                            <a href="<?= base_url('ics/detail_po?no_po=' . urlencode($row['no_po']) . '&kd_suplier=' . urlencode($row['kdsupp'] ?? '')) ?>" class="btn btn-info btn-sm" target="_blank">
                                                                <i class="fas fa-eye"></i> Detail
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <tr>
                                                    <td colspan="<?= $isAdminPo ? 6 : 10 ?>" class="text-center text-muted">
                                                        <i class="fas fa-inbox mr-1"></i> Tidak ada data
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <footer class="main-footer">
            <strong>Copyright &copy; 2022 <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block"><b>Version</b> 1.0</div>
        </footer>
        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>

    <script>
        $(document).ready(function() {
            $('#idtb_ics_po').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 25,
                order: [
                    [0, 'desc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: -1
                }],
                language: {
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                    zeroRecords: 'Tidak ada data ditemukan',
                    emptyTable: 'Belum ada data LPB',
                    paginate: {
                        first: 'Pertama',
                        last: 'Terakhir',
                        next: 'Berikutnya',
                        previous: 'Sebelumnya'
                    }
                }
            });

            function escapeHtml(text) {
                return $('<div>').text(text === null || text === undefined ? '' : text).html();
            }

            function formatNumber(value) {
                var parsed = parseFloat(value || 0);
                return parsed.toLocaleString('id-ID');
            }

            function renderStatusBadge(status) {
                if (parseInt(status, 10) === 2) {
                    return '<span class="badge badge-success">Sudah Difakturkan</span>';
                }

                return '<span class="badge badge-warning">Belum Difakturkan</span>';
            }

            function renderSyncRows(rows) {
                var $tbody = $('#sync-result-body');
                $tbody.empty();

                if (!rows || !rows.length) {
                    $tbody.append('<tr><td colspan="11" class="text-center text-muted">Belum ada data hasil sinkronisasi.</td></tr>');
                    return;
                }

                $.each(rows, function(index, row) {
                    $tbody.append(
                        '<tr>' +
                        '<td>' + escapeHtml(row.no_po) + '</td>' +
                        '<td>' + escapeHtml(row.kd_po) + '</td>' +
                        '<td>' + escapeHtml(row.tgl_transaksi) + '</td>' +
                        '<td>' + escapeHtml(row.kd_suplier) + '</td>' +
                        '<td>' + escapeHtml(row.kd_barang) + '</td>' +
                        '<td>' + escapeHtml(row.satuan) + '</td>' +
                        '<td class="text-right">' + formatNumber(row.qty) + '</td>' +
                        '<td class="text-right">' + formatNumber(row.hrg_satuan) + '</td>' +
                        '<td class="text-right">' + formatNumber(row.harga_total) + '</td>' +
                        '<td class="text-center">' + renderStatusBadge(row.status) + '</td>' +
                        '<td>' + escapeHtml(row.create_at) + '</td>' +
                        '</tr>'
                    );
                });
            }

            function showSyncAlert(type, message) {
                var $alert = $('#sync-alert');
                $alert
                    .removeClass('d-none alert-success alert-danger alert-warning')
                    .addClass('alert-' + type)
                    .html(message);
            }

            function setButtonLoading(isLoading) {
                var $button = $('#btn-sync-po');

                if (isLoading) {
                    $button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-1"></span> Sinkronisasi berjalan...');
                    return;
                }

                $button.prop('disabled', false).html('<i class="fas fa-sync-alt mr-1"></i> Sinkronisasi PO ERP');
            }

            $('#btn-sync-po').on('click', function(e) {
                e.preventDefault();
                showSyncAlert('warning', 'Mengambil data Purchase Order dari API kiu_po...');
                setButtonLoading(true);

                $.ajax({
                    url: '<?= $sync_api_url ?>',
                    type: 'POST',
                    dataType: 'json',
                    timeout: 120000,
                    success: function(response) {
                        if (response.status) {
                            $('#sync-total-inserted').text(response.inserted || 0);
                            $('#sync-total-updated').text(response.updated || 0);
                            $('#sync-total-skipped').text(response.skipped || 0);
                            $('#sync-total-rows').text(response.rows ? response.rows.length : 0);
                            $('#sync-last-time').text(response.sync_time || '-');
                            renderSyncRows(response.rows || []);

                            showSyncAlert(
                                'success',
                                (response.message || 'Sinkronisasi berhasil') +
                                ' | Inserted: <strong>' + (response.inserted || 0) + '</strong>' +
                                ' | Updated: <strong>' + (response.updated || 0) + '</strong>' +
                                ' | Skipped: <strong>' + (response.skipped || 0) + '</strong>' +
                                ' | Histori diskon: <strong>' + (response.discount_rows || 0) + '</strong>' +
                                ' | Invoice adjustment: <strong>' + (response.invoice_adjustment_rows || 0) + '</strong>'
                            );
                        } else {
                            showSyncAlert('danger', response.message || 'Gagal sinkronisasi');
                        }
                    },
                    error: function(xhr) {
                        var message = 'Gagal sinkronisasi';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        showSyncAlert('danger', message);
                    },
                    complete: function() {
                        setButtonLoading(false);
                    }
                });
            });
        });
    </script>
</body>
