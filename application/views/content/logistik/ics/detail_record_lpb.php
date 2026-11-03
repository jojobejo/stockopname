<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">
        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>

        <div class="content-wrapper">
            <div class="content-header">
                <section class="content">
                    <style>
                        .lpb-hero {
                            background: linear-gradient(135deg, #243cff 0%, #3854ff 55%, #91a2ff 100%);
                            border-radius: 20px;
                            color: #fff;
                            padding: 24px;
                            box-shadow: 0 22px 44px rgba(36, 60, 255, 0.2);
                            overflow: hidden;
                            position: relative;
                        }

                        .lpb-hero::before,
                        .lpb-hero::after {
                            content: '';
                            position: absolute;
                            border-radius: 999px;
                            background: rgba(255, 255, 255, 0.08);
                        }

                        .lpb-hero::before {
                            width: 180px;
                            height: 180px;
                            top: -80px;
                            right: -40px;
                        }

                        .lpb-hero::after {
                            width: 120px;
                            height: 120px;
                            bottom: -40px;
                            left: -20px;
                        }

                        .lpb-stat-card,
                        .lpb-panel {
                            background: #fff;
                            border: 1px solid #dbe4ff;
                            border-radius: 18px;
                            box-shadow: 0 14px 32px rgba(36, 60, 255, 0.08);
                        }

                        .lpb-stat-card {
                            padding: 18px;
                            height: 100%;
                        }

                        .lpb-stat-label {
                            color: #64748b;
                            font-size: 12px;
                            font-weight: 700;
                            letter-spacing: 0.08em;
                            text-transform: uppercase;
                        }

                        .lpb-stat-value {
                            color: #0f172a;
                            font-size: 28px;
                            font-weight: 800;
                            line-height: 1.1;
                            margin-top: 8px;
                        }

                        .lpb-panel-header {
                            padding: 18px 20px;
                            border-bottom: 1px solid #e2e8f0;
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            gap: 12px;
                            flex-wrap: wrap;
                        }

                        .lpb-panel-body {
                            padding: 18px 20px 20px;
                        }

                        .lpb-list-item {
                            border: 1px solid #e2e8f0;
                            border-radius: 16px;
                            padding: 14px 16px;
                            cursor: pointer;
                            transition: all 0.18s ease;
                            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
                        }

                        .lpb-list-item:hover {
                            transform: translateY(-1px);
                            border-color: #91a2ff;
                            box-shadow: 0 10px 22px rgba(36, 60, 255, 0.12);
                        }

                        .lpb-list-item.active {
                            border-color: #243cff;
                            background: linear-gradient(135deg, #eef1ff 0%, #f7f8ff 100%);
                            box-shadow: 0 12px 24px rgba(36, 60, 255, 0.15);
                        }

                        .lpb-list-meta {
                            display: flex;
                            gap: 8px;
                            flex-wrap: wrap;
                            margin-top: 8px;
                        }

                        .lpb-chip {
                            display: inline-flex;
                            align-items: center;
                            gap: 6px;
                            background: #eef1ff;
                            color: #243cff;
                            border-radius: 999px;
                            padding: 4px 10px;
                            font-size: 12px;
                            font-weight: 700;
                        }

                        .lpb-chip.green {
                            background: #e8edff;
                            color: #3049ff;
                        }

                        .lpb-chip.slate {
                            background: #f3f5ff;
                            color: #46557e;
                        }

                        .lpb-detail-grid {
                            display: grid;
                            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
                            gap: 12px;
                            margin-bottom: 18px;
                        }

                        .lpb-detail-box {
                            border: 1px solid #dbe4ff;
                            border-radius: 14px;
                            padding: 14px 16px;
                            background: #f6f8ff;
                        }

                        .lpb-detail-box .label {
                            font-size: 12px;
                            color: #64748b;
                            text-transform: uppercase;
                            letter-spacing: 0.06em;
                            font-weight: 700;
                        }

                        .lpb-detail-box .value {
                            margin-top: 6px;
                            font-size: 16px;
                            font-weight: 700;
                            color: #0f172a;
                            word-break: break-word;
                        }

                        .lpb-empty-state,
                        .lpb-loading-state {
                            border: 1px dashed #cbd5e1;
                            border-radius: 16px;
                            padding: 32px 18px;
                            text-align: center;
                            color: #64748b;
                            background: #f8fafc;
                        }

                        .lpb-table thead th {
                            background: #243cff;
                            color: #fff;
                            border-color: #243cff;
                            vertical-align: middle;
                        }

                        .lpb-table tfoot th {
                            background: #f3f6ff;
                            border-color: #dbe4ff;
                            color: #0f172a;
                            vertical-align: middle;
                        }

                        #modalHistoryLpb .table th,
                        #modalHistoryLpb .table td {
                            white-space: nowrap;
                        }

                        #modalHistoryLpb .table td.history-diskon-keterangan {
                            min-width: 360px;
                            white-space: normal;
                        }

                    </style>

                    <div class="row mb-3">
                        <div class="col-auto">
                            <a href="<?= !empty($is_admin_po) ? base_url('ics/icspo') : base_url('ics/detail_po?no_po=' . urlencode($no_po ?? '') . '&kd_suplier=' . urlencode($kd_suplier ?? '')) ?>" class="btn btn-primary">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Detail PO
                            </a>
                        </div>
                    </div>

                    <div class="lpb-hero mb-4">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <h3 class="mb-1 font-weight-bold">Record Semua Data LPB</h3>
                            </div>
                            <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                                <button type="button" class="btn btn-success mb-2" id="btnPrintAllLpb">
                                    <i class="fas fa-print mr-1"></i> Cetak Semua Faktur LPB
                                </button>
                                <br>
                                <div class="h3 font-weight-bold mb-1"><?= htmlspecialchars($no_po ?? '-') ?></div>
                                <div class="small">No PO: <?= htmlspecialchars($kd_po ?? '-') ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4" id="recapStatsRow">
                        <div class="col-md-4 mb-3">
                            <div class="lpb-stat-card">
                                <div class="lpb-stat-label">Total LPB</div>
                                <div class="lpb-stat-value" id="statTotalLpb">0</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="lpb-stat-card">
                                <div class="lpb-stat-label">Total Item Terekam</div>
                                <div class="lpb-stat-value" id="statTotalItem">0</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="lpb-stat-card">
                                <div class="lpb-stat-label">Total Qty Diterima (PCS)</div>
                                <div class="lpb-stat-value" id="statTotalQty">0</div>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($is_admin_po)) : ?>
                    <div class="lpb-panel mb-4" id="prePoAdjustmentPanel">
                        <div class="lpb-panel-header">
                            <div>
                                <h3 class="card-title mb-0 font-weight-bold">LPB Invoice & Adjustment Harga</h3>
                            </div>
                            <div class="d-flex align-items-center" style="gap:8px; flex-wrap:wrap;">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="btnHistoryInvoiceAll">
                                    <i class="fas fa-file-invoice mr-1"></i> History Invoice
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm" id="btnHistoryDiskonAll">
                                    <i class="fas fa-tags mr-1"></i> History Diskon
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" id="btnHistoryAdjustmentAll">
                                    <i class="fas fa-history mr-1"></i> History Adjustment
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="btnReloadPrePoAdjustment">
                                    <i class="fas fa-sync-alt mr-1"></i> Refresh
                                </button>
                            </div>
                        </div>
                        <div class="lpb-panel-body">
                            <div id="prePoAdjustmentLoading" class="lpb-loading-state">
                                <i class="fas fa-spinner fa-spin fa-2x text-primary mb-2"></i>
                                <div>Memuat data PRE PO...</div>
                            </div>
                            <div id="prePoAdjustmentContainer" style="display:none;"></div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-lg-4 mb-4">
                            <div class="lpb-panel h-100">
                                <div class="lpb-panel-header">
                                    <div>
                                        <h3 class="card-title mb-0 font-weight-bold">Daftar LPB</h3>
                                    </div>
                                    <button type="button" class="btn btn-outline-success btn-sm" id="btnReloadLpbPage">
                                        <i class="fas fa-sync-alt mr-1"></i> Refresh
                                    </button>
                                </div>
                                <div class="lpb-panel-body">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="lpbSearchInput" placeholder="Cari invoice / gudang / keterangan">
                                    </div>

                                    <div id="lpbListLoading" class="lpb-loading-state">
                                        <i class="fas fa-spinner fa-spin fa-2x text-success mb-2"></i>
                                        <div>Memuat daftar LPB...</div>
                                    </div>

                                    <div id="lpbListEmpty" class="lpb-empty-state" style="display:none;">
                                        <i class="fas fa-inbox fa-2x mb-2"></i>
                                        <div>Belum ada record LPB untuk KD PO ini.</div>
                                    </div>

                                    <div id="lpbListWrap" style="display:none;">
                                        <div id="lpbListContainer" class="d-flex flex-column" style="gap:12px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8 mb-4">
                            <div class="lpb-panel h-100">
                                <div class="lpb-panel-header">
                                    <div>
                                        <h3 class="card-title mb-0 font-weight-bold">Detail LPB</h3>
                                    </div>
                                    <div class="d-flex align-items-center" style="gap:10px;">
                                        <?php if (!empty($is_admin_po)) : ?>
                                        <button type="button" class="btn btn-primary btn-sm" id="btnUpdateInvoice">
                                            <i class="fas fa-file-invoice mr-1"></i> Update Invoice
                                        </button>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-outline-success btn-sm" id="btnPrintSelectedLpb">
                                            <i class="fas fa-print mr-1"></i> Cetak Faktur LPB
                                        </button>
                                        <div class="text-muted small" id="selectedLpbText">Belum ada LPB dipilih</div>
                                    </div>
                                </div>
                                <div class="lpb-panel-body">
                                    <div id="lpbDetailLoading" class="lpb-loading-state" style="display:none;">
                                        <i class="fas fa-spinner fa-spin fa-2x text-success mb-2"></i>
                                        <div>Memuat detail LPB...</div>
                                    </div>

                                    <div id="lpbDetailEmpty" class="lpb-empty-state">
                                        <i class="fas fa-receipt fa-2x mb-2"></i>
                                        <div>Pilih salah satu LPB di panel kiri untuk melihat detailnya.</div>
                                    </div>

                                    <div id="lpbDetailWrap" style="display:none;">
                                        <div class="lpb-detail-grid" id="lpbDetailHeaderGrid"></div>

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover lpb-table" id="lpbDetailTable">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">No</th>
                                                        <th>Kode Barang</th>
                                                        <th>Nama Barang</th>
                                                        <th class="text-center">Qty Diterima (PCS)</th>
                                                        <th>No Lot</th>
                                                        <th class="text-center">Expired Date</th>
                                                        <th class="text-center">Input At</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
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
        </footer>
        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>

    <?php if (!empty($is_admin_po)) : ?>
    <div class="modal fade" id="modalAdjustmentHarga" tabindex="-1" role="dialog" aria-labelledby="modalAdjustmentHargaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="formAdjustmentHarga" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAdjustmentHargaLabel">Adjustment Harga</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="adjKdBarang" name="kd_barang">
                    <div class="form-group">
                        <label>Barang</label>
                        <input type="text" class="form-control" id="adjNamaBarang" readonly>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Harga Lama</label>
                                <input type="text" class="form-control" id="adjHargaLamaText" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Harga Baru</label>
                                <input type="number" min="0" step="1" class="form-control" id="adjHargaBaru" name="harga_satuan_baru" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Qty</label>
                                <input type="number" class="form-control" id="adjQty" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Total Baru</label>
                                <input type="text" class="form-control" id="adjTotalBaruText" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label>Alasan Adjustment</label>
                        <textarea class="form-control" id="adjAlasan" name="alasan" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning" id="btnSubmitAdjustment">
                        <i class="fas fa-save mr-1"></i> Simpan Adjustment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalHistoryLpb" tabindex="-1" role="dialog" aria-labelledby="modalHistoryLpbLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalHistoryLpbLabel">History</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="historyLpbLoading" class="lpb-loading-state" style="display:none;">
                        <i class="fas fa-spinner fa-spin fa-2x text-primary mb-2"></i>
                        <div>Memuat history...</div>
                    </div>
                    <div id="historyLpbContent"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalUpdateInvoice" tabindex="-1" role="dialog" aria-labelledby="modalUpdateInvoiceLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formUpdateInvoice" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUpdateInvoiceLabel">Update Invoice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="invoiceIdLpb" name="id_lpb">
                    <div class="form-group">
                        <label>No Invoice</label>
                        <input type="text" class="form-control" id="invoiceNo" name="no_invoice" required>
                    </div>
                    <div class="form-group">
                        <label>No Surat Jalan</label>
                        <input type="text" class="form-control" id="invoiceNosj" name="nosj" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Surat Jalan</label>
                        <input type="date" class="form-control" id="invoiceTglSj" name="tgl_sj" required>
                    </div>
                    <div class="form-group mb-0">
                        <label>Keterangan</label>
                        <textarea class="form-control" id="invoiceKeterangan" name="keterangan" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmitInvoice">
                        <i class="fas fa-save mr-1"></i> Simpan Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <script>
        $(function() {
            var kdPo = '<?= htmlspecialchars($kd_po ?? '', ENT_QUOTES) ?>';
            var canManagePoInvoice = <?= !empty($is_admin_po) ? 'true' : 'false' ?>;
            var allRows = [];
            var selectedIdLpb = 0;
            var selectedHeader = null;
            var isSubmittingAdjustment = false;
            var isSubmittingInvoice = false;

            function escHtml(value) {
                return $('<div>').text(value == null ? '' : value).html();
            }

            function formatNumber(value) {
                return new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2
                }).format(parseFloat(value) || 0);
            }

            function formatRupiah(value) {
                return 'Rp ' + new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(parseFloat(value) || 0);
            }

            function loadPrePoAdjustment() {
                if (!canManagePoInvoice) {
                    return;
                }

                $('#prePoAdjustmentLoading').show();
                $('#prePoAdjustmentContainer').hide().empty();

                $.ajax({
                    url: '<?= base_url('ics/ajax_get_pre_po_adjustment') ?>',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        kd_po: kdPo
                    },
                    success: function(res) {
                        $('#prePoAdjustmentLoading').hide();
                        if (res.status !== 'success') {
                            Swal.fire('Gagal', res.message || 'Data PRE PO tidak dapat dimuat.', 'error');
                            return;
                        }

                        $('#prePoAdjustmentContainer').html(res.html || '').show();
                    },
                    error: function() {
                        $('#prePoAdjustmentLoading').hide();
                        Swal.fire('Gagal', 'Terjadi kesalahan saat mengambil data PRE PO.', 'error');
                    }
                });
            }

            function refreshPrePoAdjustmentHtml(html) {
                if (html) {
                    $('#prePoAdjustmentContainer').html(html).show();
                    $('#prePoAdjustmentLoading').hide();
                    return;
                }

                loadPrePoAdjustment();
            }

            function updateAdjustmentTotal() {
                var qty = parseFloat($('#adjQty').val()) || 0;
                var hargaBaru = parseFloat($('#adjHargaBaru').val()) || 0;
                $('#adjTotalBaruText').val(formatRupiah(qty * hargaBaru));
            }

            function updateStats(rows) {
                var totalItem = 0;
                var totalQty = 0;

                $.each(rows || [], function(_, row) {
                    totalItem += parseFloat(row.total_item) || 0;
                    totalQty += parseFloat(row.total_qty) || 0;
                });

                $('#statTotalLpb').text(formatNumber((rows || []).length));
                $('#statTotalItem').text(formatNumber(totalItem));
                $('#statTotalQty').text(formatNumber(totalQty));
            }

            function buildListItem(row) {
                var invoice = row.no_invoice ? row.no_invoice : 'Tanpa Invoice';
                var gudang = row.nama_gudang ? row.nama_gudang : '-';
                var keterangan = row.keterangan ? row.keterangan : 'Tidak ada keterangan';

                return '' +
                    '<div class="lpb-list-item js-lpb-item" data-id="' + escHtml(row.id_lpb) + '" data-search="' + escHtml((invoice + ' ' + gudang + ' ' + keterangan).toLowerCase()) + '">' +
                    '<div class="d-flex justify-content-between align-items-start">' +
                    '<div>' +
                    '<div class="font-weight-bold text-dark">LPB #' + escHtml(row.id_lpb) + '</div>' +
                    '<div class="text-muted small">' + escHtml(invoice) + '</div>' +
                    '</div>' +
                    '<span class="badge badge-success">Qty ' + escHtml(formatNumber(row.total_qty)) + '</span>' +
                    '</div>' +
                    '<div class="lpb-list-meta">' +
                    '<span class="lpb-chip"><i class="fas fa-boxes"></i> ' + escHtml(formatNumber(row.total_item)) + ' item</span>' +
                    '<span class="lpb-chip green"><i class="fas fa-warehouse"></i> ' + escHtml(gudang) + '</span>' +
                    '<span class="lpb-chip slate"><i class="fas fa-clock"></i> ' + escHtml(row.input_at || '-') + '</span>' +
                    '</div>' +
                    '<div class="text-muted small mt-2">' + escHtml(keterangan) + '</div>' +
                    '</div>';
            }

            function renderList(rows) {
                var container = $('#lpbListContainer');
                container.empty();

                if (!rows || rows.length === 0) {
                    $('#lpbListLoading').hide();
                    $('#lpbListWrap').hide();
                    $('#lpbListEmpty').show();
                    resetDetailState();
                    updateStats([]);
                    return;
                }

                $.each(rows, function(_, row) {
                    container.append(buildListItem(row));
                });

                $('#lpbListLoading').hide();
                $('#lpbListEmpty').hide();
                $('#lpbListWrap').show();
                updateStats(rows);

                var targetId = selectedIdLpb || rows[0].id_lpb;
                selectListItem(targetId);
                loadDetail(targetId);
            }

            function resetDetailState() {
                selectedIdLpb = 0;
                selectedHeader = null;
                $('#selectedLpbText').text('Belum ada LPB dipilih');
                $('#lpbDetailLoading').hide();
                $('#lpbDetailWrap').hide();
                $('#lpbDetailEmpty').show();
                $('#lpbDetailHeaderGrid').empty();
                $('#lpbDetailTable tbody').empty();
            }

            function selectListItem(idLpb) {
                selectedIdLpb = parseInt(idLpb, 10) || 0;
                $('.js-lpb-item').removeClass('active');
                $('.js-lpb-item[data-id="' + selectedIdLpb + '"]').addClass('active');
            }

            function renderDetailHeader(header) {
                var html = '';
                var boxes = [{
                        label: 'ID LPB',
                        value: header.id_lpb || '-'
                    },
                    {
                        label: 'Nomor SJ',
                        value: header.nosj || '-'
                    },
                    {
                        label: 'Tanggal SJ',
                        value: header.tgl_sj || '-'
                    },
                    {
                        label: 'No Invoice',
                        value: header.no_invoice || '-'
                    },
                    {
                        label: 'Gudang',
                        value: header.nama_gudang || '-'
                    },
                    {
                        label: 'Total Item',
                        value: formatNumber(header.total_item || 0)
                    },
                    {
                        label: 'Total Baris',
                        value: formatNumber(header.total_baris || 0)
                    },
                    {
                        label: 'Total Qty (PCS)',
                        value: formatNumber(header.total_qty || 0)
                    },
                    {
                        label: 'Input At',
                        value: header.input_at || '-'
                    },
                    {
                        label: 'Keterangan',
                        value: header.keterangan || '-'
                    }
                ];

                $.each(boxes, function(_, box) {
                    html += '' +
                        '<div class="lpb-detail-box">' +
                        '<div class="label">' + escHtml(box.label) + '</div>' +
                        '<div class="value">' + escHtml(box.value) + '</div>' +
                        '</div>';
                });

                $('#lpbDetailHeaderGrid').html(html);
            }

            function renderDetailTable(rows) {
                var tbody = $('#lpbDetailTable tbody');
                tbody.empty();

                if (!rows || rows.length === 0) {
                    tbody.html('<tr><td colspan="7" class="text-center text-muted">Detail LPB kosong.</td></tr>');
                    return;
                }

                $.each(rows, function(index, row) {
                    tbody.append(
                        '<tr>' +
                        '<td class="text-center">' + (index + 1) + '</td>' +
                        '<td>' + escHtml(row.kd_barang || '-') + '</td>' +
                        '<td>' + escHtml(row.nama_barang || '-') + '</td>' +
                        '<td class="text-center">' + escHtml(formatNumber(row.qty_diterima || 0)) + '</td>' +
                        '<td>' + escHtml(row.no_lot || '-') + '</td>' +
                        '<td class="text-center">' + escHtml(row.expired_date || '-') + '</td>' +
                        '<td class="text-center">' + escHtml(row.input_at || '-') + '</td>' +
                        '</tr>'
                    );
                });
            }

            function loadDetail(idLpb) {
                if (!idLpb) {
                    resetDetailState();
                    return;
                }

                $('#lpbDetailEmpty').hide();
                $('#lpbDetailWrap').hide();
                $('#lpbDetailLoading').show();
                $('#selectedLpbText').text('Memuat LPB #' + idLpb + ' ...');

                $.ajax({
                    url: '<?= base_url('ics/ajax_get_lpb_record_detail') ?>',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        id_lpb: idLpb
                    },
                    success: function(res) {
                        if (res.status !== 'success') {
                            Swal.fire('Gagal', res.message || 'Detail LPB tidak dapat dimuat.', 'error');
                            resetDetailState();
                            return;
                        }

                        selectedHeader = res.header || {};
                        renderDetailHeader(selectedHeader);
                        renderDetailTable(res.rows || []);
                        $('#selectedLpbText').text('LPB #' + (res.header.id_lpb || idLpb));
                        $('#lpbDetailLoading').hide();
                        $('#lpbDetailEmpty').hide();
                        $('#lpbDetailWrap').show();
                    },
                    error: function() {
                        resetDetailState();
                        Swal.fire('Gagal', 'Terjadi kesalahan saat mengambil detail LPB.', 'error');
                    }
                });
            }

            function loadList() {
                $('#lpbListLoading').show();
                $('#lpbListEmpty').hide();
                $('#lpbListWrap').hide();

                $.ajax({
                    url: '<?= base_url('ics/ajax_get_lpb_records_by_kd_po') ?>',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        kd_po: kdPo
                    },
                    success: function(res) {
                        if (res.status !== 'success') {
                            $('#lpbListLoading').hide();
                            $('#lpbListEmpty').show();
                            updateStats([]);
                            Swal.fire('Gagal', res.message || 'Daftar LPB tidak dapat dimuat.', 'error');
                            return;
                        }

                        allRows = res.rows || [];
                        renderList(allRows);
                        applySearch();
                    },
                    error: function() {
                        $('#lpbListLoading').hide();
                        $('#lpbListEmpty').show();
                        updateStats([]);
                        Swal.fire('Gagal', 'Terjadi kesalahan saat mengambil daftar LPB.', 'error');
                    }
                });
            }

            function printSelectedLpb() {
                if (!selectedIdLpb) {
                    Swal.fire('Validasi', 'Silakan pilih LPB yang ingin dicetak terlebih dahulu.', 'warning');
                    return;
                }

                window.open('<?= base_url('ics/print_lpb_record/') ?>' + selectedIdLpb, '_blank');
            }

            function printAllLpb() {
                window.open(
                    '<?= base_url('ics/print_lpb_records_all') ?>?kd_po=' + encodeURIComponent(kdPo) + '&no_po=' + encodeURIComponent('<?= htmlspecialchars($no_po ?? '', ENT_QUOTES) ?>'),
                    '_blank'
                );
            }

            function openUpdateInvoiceModal() {
                if (!selectedIdLpb || !selectedHeader) {
                    Swal.fire('Validasi', 'Silakan pilih LPB yang ingin di-update terlebih dahulu.', 'warning');
                    return;
                }

                $('#invoiceIdLpb').val(selectedHeader.id_lpb || selectedIdLpb);
                $('#invoiceNo').val(selectedHeader.no_invoice || '');
                $('#invoiceNosj').val(selectedHeader.nosj || '');
                $('#invoiceTglSj').val(selectedHeader.tgl_sj || '');
                $('#invoiceKeterangan').val(selectedHeader.keterangan || '');
                $('#modalUpdateInvoice').modal('show');
            }

            function loadHistory(title, url, data) {
                $('#modalHistoryLpbLabel').text(title);
                $('#historyLpbContent').empty();
                $('#historyLpbLoading').show();
                $('#modalHistoryLpb').modal('show');

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    data: data,
                    success: function(res) {
                        $('#historyLpbLoading').hide();
                        if (res.status !== 'success') {
                            Swal.fire('Gagal', res.message || 'History tidak dapat dimuat.', 'error');
                            return;
                        }

                        $('#historyLpbContent').html(res.html || '');
                    },
                    error: function() {
                        $('#historyLpbLoading').hide();
                        Swal.fire('Gagal', 'Terjadi kesalahan saat mengambil history.', 'error');
                    }
                });
            }

            function applySearch() {
                var keyword = ($('#lpbSearchInput').val() || '').toLowerCase();
                var visibleCount = 0;

                $('.js-lpb-item').each(function() {
                    var haystack = ($(this).data('search') || '').toString();
                    var matched = keyword === '' || haystack.indexOf(keyword) !== -1;
                    $(this).toggle(matched);
                    if (matched) {
                        visibleCount++;
                    }
                });

                if (visibleCount === 0 && allRows.length > 0) {
                    $('#lpbListWrap').show();
                    $('#lpbListEmpty').hide();
                }
            }

            $(document).on('click', '.js-lpb-item', function() {
                var idLpb = $(this).data('id');
                selectListItem(idLpb);
                loadDetail(idLpb);
            });

            $(document).on('click', '.js-open-adjustment', function() {
                if (!canManagePoInvoice) {
                    return;
                }

                var btn = $(this);
                var kdBarang = btn.attr('data-kd-barang') || '';
                var namaBarang = btn.attr('data-nama-barang') || '-';

                $('#adjKdBarang').val(kdBarang);
                $('#adjNamaBarang').val(kdBarang + ' - ' + namaBarang);
                $('#adjQty').val(btn.data('qty') || 0);
                $('#adjHargaLamaText').val(formatRupiah(btn.data('harga-satuan') || 0));
                $('#adjHargaBaru').val(btn.data('harga-satuan') || 0);
                $('#adjAlasan').val('');
                updateAdjustmentTotal();
                $('#modalAdjustmentHarga').modal('show');
            });

            $('#btnHistoryInvoiceAll').on('click', function() {
                if (!canManagePoInvoice) {
                    return;
                }

                loadHistory(
                    'History Invoice',
                    '<?= base_url('ics/ajax_history_invoice') ?>', {
                        kd_po: kdPo
                    }
                );
            });

            $('#btnHistoryDiskonAll').on('click', function() {
                if (!canManagePoInvoice) {
                    return;
                }

                loadHistory(
                    'History Diskon',
                    '<?= base_url('ics/ajax_history_diskon') ?>', {
                        kd_po: kdPo
                    }
                );
            });

            $('#btnHistoryAdjustmentAll').on('click', function() {
                if (!canManagePoInvoice) {
                    return;
                }

                loadHistory(
                    'History Adjustment',
                    '<?= base_url('ics/ajax_history_adjustment') ?>', {
                        kd_po: kdPo
                    }
                );
            });

            $('#adjHargaBaru').on('input', function() {
                updateAdjustmentTotal();
            });

            $('#formAdjustmentHarga').on('submit', function(e) {
                e.preventDefault();
                if (!canManagePoInvoice) {
                    return;
                }

                if (isSubmittingAdjustment) {
                    return;
                }

                if (!$.trim($('#adjAlasan').val())) {
                    Swal.fire('Validasi', 'Alasan adjustment wajib diisi.', 'warning');
                    return;
                }

                isSubmittingAdjustment = true;
                $('#btnSubmitAdjustment').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');

                $.ajax({
                    url: '<?= base_url('ics/ajax_submit_adjustment') ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        kd_po: kdPo,
                        kd_barang: $('#adjKdBarang').val(),
                        harga_satuan_baru: $('#adjHargaBaru').val(),
                        alasan: $('#adjAlasan').val()
                    },
                    success: function(res) {
                        if (res.status !== 'success') {
                            Swal.fire('Gagal', res.message || 'Adjustment harga gagal disimpan.', 'error');
                            return;
                        }

                        $('#modalAdjustmentHarga').modal('hide');
                        refreshPrePoAdjustmentHtml(res.html || '');
                        Swal.fire('Berhasil', res.message || 'Adjustment harga berhasil disimpan.', 'success');
                    },
                    error: function() {
                        Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan adjustment harga.', 'error');
                    },
                    complete: function() {
                        isSubmittingAdjustment = false;
                        $('#btnSubmitAdjustment').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan Adjustment');
                    }
                });
            });

            $('#lpbSearchInput').on('input', function() {
                applySearch();
            });

            $('#btnReloadLpbPage').on('click', function() {
                loadList();
            });

            $('#btnReloadPrePoAdjustment').on('click', function() {
                if (!canManagePoInvoice) {
                    return;
                }

                loadPrePoAdjustment();
            });

            $('#btnUpdateInvoice').on('click', function() {
                if (!canManagePoInvoice) {
                    return;
                }

                openUpdateInvoiceModal();
            });

            $('#formUpdateInvoice').on('submit', function(e) {
                e.preventDefault();
                if (!canManagePoInvoice) {
                    return;
                }

                if (isSubmittingInvoice) {
                    return;
                }

                isSubmittingInvoice = true;
                $('#btnSubmitInvoice').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');

                $.ajax({
                    url: '<?= base_url('ics/ajax_update_invoice') ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id_lpb: $('#invoiceIdLpb').val(),
                        no_invoice: $('#invoiceNo').val(),
                        nosj: $('#invoiceNosj').val(),
                        tgl_sj: $('#invoiceTglSj').val(),
                        keterangan: $('#invoiceKeterangan').val()
                    },
                    success: function(res) {
                        if (res.status !== 'success') {
                            Swal.fire('Gagal', res.message || 'Update invoice gagal disimpan.', 'error');
                            return;
                        }

                        $('#modalUpdateInvoice').modal('hide');
                        Swal.fire('Berhasil', res.message || 'Invoice LPB berhasil diperbarui.', 'success');
                        loadList();
                        if (selectedIdLpb) {
                            loadDetail(selectedIdLpb);
                        }
                        loadPrePoAdjustment();
                    },
                    error: function() {
                        Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan invoice LPB.', 'error');
                    },
                    complete: function() {
                        isSubmittingInvoice = false;
                        $('#btnSubmitInvoice').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan Invoice');
                    }
                });
            });

            $('#btnPrintSelectedLpb').on('click', function() {
                printSelectedLpb();
            });

            $('#btnPrintAllLpb').on('click', function() {
                printAllLpb();
            });

            if (canManagePoInvoice) {
                loadPrePoAdjustment();
            }
            loadList();
        });
    </script>
</body>
