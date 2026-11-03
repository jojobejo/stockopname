<!-- content/logistik/ics/detail_po.php -->

<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">
        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>

        <div class="content-wrapper">
            <div class="content-header">
                <section class="content">
                    <style>
                        .detail-po-hero {
                            background: linear-gradient(135deg, #243cff 0%, #3558ff 52%, #7f92ff 100%);
                            color: #fff;
                            border-radius: 16px;
                            padding: 22px 24px;
                            box-shadow: 0 18px 38px rgba(36, 60, 255, 0.22);
                            position: relative;
                            overflow: hidden;
                        }

                        .detail-po-hero::after {
                            content: '';
                            position: absolute;
                            inset: auto -60px -60px auto;
                            width: 180px;
                            height: 180px;
                            background: rgba(255, 255, 255, 0.08);
                            border-radius: 50%;
                        }

                        .mini-stat {
                            background: #fff;
                            border-radius: 14px;
                            padding: 14px 16px;
                            border: 1px solid #dbe4ff;
                            box-shadow: 0 10px 24px rgba(36, 60, 255, 0.08);
                            height: 100%;
                        }

                        .mini-stat-link {
                            display: block;
                            text-decoration: none !important;
                            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
                        }

                        .mini-stat-link:hover {
                            transform: translateY(-2px);
                        }

                        .mini-stat-link .mini-stat {
                            cursor: pointer;
                        }

                        .mini-stat-link:hover .mini-stat {
                            border-color: #91a2ff;
                            box-shadow: 0 18px 32px rgba(36, 60, 255, 0.16);
                        }

                        .mini-stat .label {
                            font-size: 12px;
                            font-weight: 700;
                            letter-spacing: 0.08em;
                            text-transform: uppercase;
                            color: #64748b;
                        }

                        .mini-stat .value {
                            font-size: 26px;
                            font-weight: 800;
                            color: #0f172a;
                            line-height: 1.1;
                            margin-top: 6px;
                        }

                        .btn-round-action {
                            width: 38px;
                            height: 38px;
                            border-radius: 999px;
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            box-shadow: 0 10px 18px rgba(36, 60, 255, 0.22);
                        }

                        .table thead.thead-emerald th {
                            background: #243cff;
                            color: #fff;
                            border-color: #243cff;
                        }

                        .draft-shell {
                            border-radius: 16px;
                            overflow: hidden;
                            border: 1px solid #dbe4ff;
                            box-shadow: 0 12px 32px rgba(36, 60, 255, 0.08);
                        }

                        .draft-shell .card-header {
                            background: linear-gradient(135deg, #243cff 0%, #4b63ff 100%);
                            color: #fff;
                        }

                        .modal-rows-table td,
                        .modal-rows-table th {
                            vertical-align: middle;
                        }

                        .sticky-modal-footer {
                            border-top: 1px solid #e2e8f0;
                            background: #f8fafc;
                        }

                        .badge-soft {
                            background: #eef1ff;
                            color: #243cff;
                            border: 1px solid #c6d0ff;
                            font-weight: 700;
                        }

                        .summary-empty {
                            border: 1px dashed #cbd5e1;
                            border-radius: 14px;
                            padding: 28px 16px;
                            text-align: center;
                            color: #64748b;
                            background: #f8fafc;
                        }

                        .draft-header-box {
                            border: 1px solid #dbe4ff;
                            border-radius: 16px;
                            padding: 18px;
                            background: linear-gradient(180deg, #f5f7ff 0%, #ffffff 100%);
                            margin-bottom: 18px;
                        }

                        .draft-summary-stat {
                            background: #f6f8ff;
                            border: 1px solid #dbe4ff;
                            border-radius: 14px;
                            padding: 14px 16px;
                            height: 100%;
                        }

                        .draft-summary-stat .label {
                            font-size: 12px;
                            font-weight: 700;
                            letter-spacing: 0.06em;
                            text-transform: uppercase;
                            color: #64748b;
                        }

                        .draft-summary-stat .value {
                            font-size: 24px;
                            font-weight: 800;
                            color: #0f172a;
                            margin-top: 4px;
                            line-height: 1.1;
                        }

                        .draft-actions {
                            display: flex;
                            gap: 10px;
                            justify-content: flex-end;
                            flex-wrap: wrap;
                            margin-top: 18px;
                        }
                    </style>

                    <div class="row mb-3">
                        <div class="col-auto">
                            <a href="<?= base_url('ics/icspo') ?>" class="btn btn-primary">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <div class="detail-po-hero mb-4">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <h3 class="mb-1 font-weight-bold">
                                    Detail Penerimaan PO
                                </h3>
                                <p class="mb-0">
                                    Kelola draft penerimaan per barang langsung dari baris PO. Klik tombol plus hijau untuk input lot dan expired date
                                </p>
                            </div>
                            <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                                <div class="h5 mb-1">No PO</div>
                                <div class="h3 font-weight-bold mb-0"><?= htmlspecialchars($no_po) ?></div>
                            </div>
                        </div>
                    </div>

                    <?php
                    $totalOrder = 0;
                    $totalReceived = 0;
                    $totalLpbRecord = 0;
                    foreach ($detail as $row) {
                        $totalOrder += (float) ($row['qty_kecil'] ?? 0);
                        $totalReceived += (float) ($row['qty_kecil_diterima'] ?? 0);
                        $totalLpbRecord += (int) ($row['total_lpb_record'] ?? 0);
                    }
                    ?>

                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <div class="mini-stat">
                                <div class="label">Qty Order Kecil</div>
                                <div class="value"><?= number_format($totalOrder, 0, ',', '.') ?></div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="mini-stat">
                                <div class="label">Qty Diterima Kecil</div>
                                <div class="value"><?= number_format($totalReceived, 0, ',', '.') ?></div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="<?= base_url('ics/detail_record_lpb?kd_po=' . urlencode($detail[0]['kd_po'] ?? '') . '&no_po=' . urlencode($no_po ?? '') . '&kd_suplier=' . urlencode($kd_suplier ?? '')) ?>" class="mini-stat-link" target="_blank">
                                <div class="mini-stat">
                                    <div class="label">Total Record LPB</div>
                                    <div class="value"><?= number_format($totalLpbRecord, 0, ',', '.') ?></div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="card draft-shell mb-4">
                        <div class="card-header">
                            <h3 class="card-title mb-0">
                                <i class="fas fa-boxes mr-2"></i> Detail Barang PO
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover" id="tabelDetailPo">
                                <thead class="thead-emerald">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Kode Barang</th>
                                        <th class="text-center">Nama Barang</th>
                                        <th class="text-center">Qty Order</th>
                                        <th class="text-center">Qty Diterima</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Draft Temp</th>
                                        <th class="text-center">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($detail)) : ?>
                                        <?php foreach ($detail as $i => $row) : ?>
                                            <tr id="po-row-<?= htmlspecialchars($row['kd_po'] ?? '') ?>-<?= htmlspecialchars($row['kd_barang'] ?? '') ?>">
                                                <td class="text-center"><?= $i + 1 ?></td>
                                                <td><?= htmlspecialchars($row['kd_barang'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($row['nama_barang'] ?? '-') ?></td>
                                                <td class="text-center"><?= number_format((float) ($row['qty_kecil'] ?? 0), 0, ',', '.') ?></td>
                                                <td class="text-center"><?= number_format((float) ($row['qty_kecil_diterima'] ?? 0), 0, ',', '.') ?></td>
                                                <td class="text-center">
                                                    <?php
                                                    $statusBarang = strtoupper((string) ($row['status_barang'] ?? 'BELUM'));
                                                    $badgeClass = 'secondary';
                                                    if ($statusBarang === 'FULL') {
                                                        $badgeClass = 'success';
                                                    } elseif ($statusBarang === 'PARTIAL') {
                                                        $badgeClass = 'warning';
                                                    } elseif ($statusBarang === 'BELUM') {
                                                        $badgeClass = 'danger';
                                                    }
                                                    ?>
                                                    <span class="badge badge-<?= $badgeClass ?> px-3 py-2"><?= htmlspecialchars($statusBarang) ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-soft js-draft-badge" data-kd-po="<?= htmlspecialchars($row['kd_po'] ?? '') ?>" data-kd-barang="<?= htmlspecialchars($row['kd_barang'] ?? '') ?>">
                                                        0 baris
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-success btn-round-action js-open-modal" title="Tambah draft penerimaan" data-kd-po="<?= htmlspecialchars($row['kd_po'] ?? '') ?>" data-kd-suplier="<?= htmlspecialchars($kd_suplier ?? '') ?>" data-kd-barang="<?= htmlspecialchars($row['kd_barang'] ?? '') ?>" data-nama-barang="<?= htmlspecialchars($row['nama_barang'] ?? '-') ?>" data-no-po="<?= htmlspecialchars($no_po) ?>" data-satuan="<?= htmlspecialchars($row['satuan'] ?? '') ?>" data-sisa="<?= htmlspecialchars((string) ($row['qty_kecil_sisa'] ?? 0)) ?>" data-toggle="modal" data-target="#modalTmpPoReceived">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">
                                                <i class="fas fa-inbox mr-1"></i> Belum ada barang diterima untuk PO ini
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card draft-shell">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">
                                <i class="fas fa-clipboard-list mr-2"></i> Draft Temporary Penerimaan
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="draft-header-box">
                                <div class="row" id="pre_po_data">
                                    <div class="col-lg-3 col-md-6 mb-3" id="pre_po_date">
                                        <label class="font-weight-bold">Nomor SJ</label>
                                        <input type="text" class="form-control" id="final_nosj" placeholder="Input nomor SJ">
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-3">
                                        <label class="font-weight-bold">Tanggal SJ</label>
                                        <input type="date" class="form-control" id="final_tgl_sj">
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-3" hidden>
                                        <label class="font-weight-bold">Nomor PO</label>
                                        <input type="text" class="form-control" id="final_no_po" value="<?= htmlspecialchars($no_po) ?>" readonly>
                                        <input type="hidden" id="final_kd_po" value="<?= htmlspecialchars($kd_po ?? '') ?>">
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-3" hidden>
                                        <label class="font-weight-bold">Invoice <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="final_invoice" value="-" readonly>
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-3">
                                        <label class="font-weight-bold">Gudang <span class="text-danger">*</span></label>
                                        <select class="form-control" id="final_gudang_id">
                                            <option value="">-- Pilih Gudang --</option>
                                            <?php foreach (($list_gudang ?? []) as $gudang) : ?>
                                                <option value="<?= htmlspecialchars($gudang['id_gudang']) ?>"><?= htmlspecialchars($gudang['nama_gudang']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-3">
                                        <label class="font-weight-bold">Keterangan</label>
                                        <input type="text" class="form-control" id="final_keterangan" placeholder="Catatan penerimaan">
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-4 mb-3">
                                        <div class="draft-summary-stat">
                                            <div class="label">Total Qty Draft</div>
                                            <div class="value" id="summaryTotalQty">0</div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <div class="draft-summary-stat">
                                            <div class="label">Jumlah Lot</div>
                                            <div class="value" id="summaryTotalLot">0</div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <div class="draft-summary-stat">
                                            <div class="label">Baris Draft</div>
                                            <div class="value" id="summaryTotalRows">0</div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div id="tmpSummaryState" class="summary-empty">
                                <i class="fas fa-layer-group fa-2x mb-2"></i>
                                <div>Belum ada draft penerimaan untuk PO ini.</div>
                            </div>

                            <div class="table-responsive" id="tmpSummaryWrapper" style="display:none;">
                                <table class="table table-bordered table-hover mt-1" id="detail_transaksi_po">
                                    <thead class="thead-emerald">
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th class="text-center">Qty Diterima</th>
                                            <th class="text-center">Satuan</th>
                                            <th>No Lot</th>
                                            <th class="text-center">Expired Date</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <div class="draft-actions">
                                <button type="button" class="btn btn-outline-secondary" id="btnResetFinalForm">
                                    <i class="fas fa-undo mr-1"></i> Reset
                                </button>
                                <button type="button" class="btn btn-success" id="btnSubmitFinalLpb">
                                    <i class="fas fa-save mr-1"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <div class="modal fade" id="modalTmpPoReceived" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-plus-circle mr-2"></i> Draft Penerimaan Barang
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form id="formTmpPoReceived">

                        <div class="modal-body">
                            <input type="hidden" name="kd_po" id="tmp_kd_po">
                            <input type="hidden" name="kd_suplier" id="tmp_kd_suplier">
                            <input type="hidden" name="kd_barang" id="tmp_kd_barang">

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="font-weight-bold">No PO</label>
                                    <input type="text" class="form-control" id="tmp_no_po" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="font-weight-bold">Kode Barang</label>
                                    <input type="text" class="form-control" id="tmp_display_kd_barang" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="font-weight-bold">Qty Kecil Sisa</label>
                                    <input type="text" class="form-control" id="tmp_qty_sisa" readonly>
                                </div>
                            </div>

                            <div class="alert alert-light border mb-3">
                                <strong id="tmp_nama_barang">-</strong><br>
                                <small class="text-muted">Input bisa lebih dari satu baris lot untuk barang yang sama. Draft ini tersimpan di temporary table.</small>
                            </div>

                            <div id="tmpModalLoader" class="text-center py-4" style="display:none;">
                                <i class="fas fa-spinner fa-spin fa-2x text-success"></i>
                                <div class="mt-2 text-muted">Memuat draft penerimaan...</div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered modal-rows-table" id="tableModalTmpReceived">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="min-width:150px;">Kd Barang</th>
                                            <th style="min-width:140px;">Qty Diterima</th>
                                            <th style="min-width:150px;">Satuan</th>
                                            <th style="min-width:180px;">No Lot</th>
                                            <th style="min-width:180px;">Expired Date</th>
                                            <th style="width:70px;" class="text-center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tmpRowsBody"></tbody>
                                </table>
                            </div>

                            <button type="button" class="btn btn-outline-success" id="btnTambahBarisTmp">
                                <i class="fas fa-plus mr-1"></i> Tambah Baris Baru
                            </button>
                        </div>

                        <div class="modal-footer sticky-modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times mr-1"></i> Tutup
                            </button>
                            <button type="submit" class="btn btn-success" id="btnSimpanTmp">
                                <i class="fas fa-save mr-1"></i> Simpan Draft
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <footer class="main-footer">
            <strong>Copyright &copy; 2022 <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.</strong>
            All rights reserved.
        </footer>
        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>

    <script>
        $(function() {
            var listSatuan = <?= json_encode(array_values(array_map(function ($item) {
                                    return $item['nm_satuan'];
                                }, $list_satuan ?? []))) ?>;

            var currentItem = {
                kd_po: '',
                kd_suplier: '',
                kd_barang: '',
                nama_barang: '',
                no_po: '',
                satuan_default: '',
                qty_kecil_sisa: 0
            };
            var isSubmittingFinal = false;
            var defaultFinalForm = {
                no_po: '<?= htmlspecialchars($no_po, ENT_QUOTES) ?>',
                nosj: '',
                tgl_sj: '',
                no_invoice: '-',
                gudang_id: '',
                keterangan: ''
            };

            $('#tabelDetailPo').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 25,
                order: [
                    [0, 'asc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: [5, 6, 7]
                }],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    zeroRecords: "Tidak ada data ditemukan",
                    emptyTable: "Tidak ada data tersedia",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    }
                }
            });

            function escHtml(str) {
                return $('<div>').text(str || '').html();
            }

            function formatNumber(value) {
                return new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2
                }).format(parseFloat(value) || 0);
            }

            function buildSatuanOptions(selectedValue) {
                var html = '<option value="">-- Pilih Satuan --</option>';

                $.each(listSatuan, function(_, item) {
                    var selected = item === selectedValue ? 'selected' : '';
                    html += '<option value="' + escHtml(item) + '" ' + selected + '>' + escHtml(item) + '</option>';
                });

                return html;
            }

            function buildRow(rowData) {
                var selectedSatuan = rowData && rowData.satuan ? rowData.satuan : currentItem.satuan_default;
                var qtyValue = rowData && rowData.qty_diterima ? rowData.qty_diterima : '';
                var noLotValue = rowData && rowData.no_lot ? rowData.no_lot : '';
                var expiredValue = rowData && rowData.expired_date ? rowData.expired_date : '';

                return '' +
                    '<tr>' +
                    '<td><input type="text" class="form-control form-control-sm bg-light js-kd-barang-row" value="' + escHtml(currentItem.kd_barang) + '" readonly></td>' +
                    '<td><input type="number" step="0.01" min="0" class="form-control form-control-sm js-qty-row" value="' + escHtml(qtyValue) + '" placeholder="0"></td>' +
                    '<td><select class="form-control form-control-sm js-satuan-row">' + buildSatuanOptions(selectedSatuan) + '</select></td>' +
                    '<td><input type="text" class="form-control form-control-sm js-lot-row" value="' + escHtml(noLotValue) + '" placeholder="Nomor lot"></td>' +
                    '<td><input type="date" class="form-control form-control-sm js-exp-row" value="' + escHtml(expiredValue) + '"></td>' +
                    '<td class="text-center"><button type="button" class="btn btn-sm btn-danger js-hapus-row"><i class="fas fa-trash"></i></button></td>' +
                    '</tr>';
            }

            function ensureAtLeastOneRow() {
                if ($('#tmpRowsBody tr').length === 0) {
                    $('#tmpRowsBody').append(buildRow());
                }
                syncDeleteButtons();
            }

            function syncDeleteButtons() {
                var totalRows = $('#tmpRowsBody tr').length;
                $('#tmpRowsBody .js-hapus-row').prop('disabled', totalRows === 1);
            }

            function getPayloadRows() {
                var rows = [];

                $('#tmpRowsBody tr').each(function() {
                    rows.push({
                        qty_diterima: $(this).find('.js-qty-row').val(),
                        satuan: $(this).find('.js-satuan-row').val(),
                        no_lot: $(this).find('.js-lot-row').val(),
                        expired_date: $(this).find('.js-exp-row').val()
                    });
                });

                return rows;
            }

            function fillModalHeader(item) {
                $('#tmp_kd_po').val(item.kd_po);
                $('#tmp_kd_suplier').val(item.kd_suplier);
                $('#tmp_kd_barang').val(item.kd_barang);
                $('#tmp_no_po').val(item.no_po);
                $('#tmp_display_kd_barang').val(item.kd_barang);
                $('#tmp_nama_barang').text(item.nama_barang || '-');
                $('#tmp_qty_sisa').val(formatNumber(item.qty_kecil_sisa) + ' pcs');
            }

            function renderModalRows(rows) {
                $('#tmpRowsBody').empty();

                if (rows && rows.length) {
                    $.each(rows, function(_, row) {
                        $('#tmpRowsBody').append(buildRow(row));
                    });
                }

                ensureAtLeastOneRow();
            }

            function renderSummaryTable(rows) {
                var tbody = $('#detail_transaksi_po tbody');
                tbody.empty();

                if (!rows || rows.length === 0) {
                    $('#tmpSummaryWrapper').hide();
                    $('#tmpSummaryState').show();
                    $('.js-draft-badge').text('0 baris');
                    updateSummaryStats([]);
                    return;
                }

                $.each(rows, function(index, row) {
                    tbody.append(
                        '<tr>' +
                        '<td class="text-center">' + (index + 1) + '</td>' +
                        '<td>' + escHtml(row.kd_barang) + '</td>' +
                        '<td>' + escHtml(row.nama_barang) + '</td>' +
                        '<td class="text-center">' + escHtml(row.qty_diterima) + '</td>' +
                        '<td class="text-center">' + escHtml(row.satuan) + '</td>' +
                        '<td>' + escHtml(row.no_lot || '-') + '</td>' +
                        '<td class="text-center">' + escHtml(row.expired_date || '-') + '</td>' +
                        '<td class="text-center">' +
                        '<button type="button" class="btn btn-sm btn-outline-danger js-delete-summary-row" data-id="' + escHtml(row.id_tmp_recieved) + '" title="Hapus baris draft">' +
                        '<i class="fas fa-trash"></i>' +
                        '</button>' +
                        '</td>' +
                        '</tr>'
                    );
                });

                $('#tmpSummaryState').hide();
                $('#tmpSummaryWrapper').show();
                updateSummaryStats(rows || []);
            }

            function updateDraftBadges(rows) {
                var countMap = {};

                $.each(rows || [], function(_, row) {
                    var key = (row.kd_po || '') + '||' + (row.kd_barang || '');
                    countMap[key] = (countMap[key] || 0) + 1;
                });

                $('.js-draft-badge').each(function() {
                    var key = ($(this).data('kd-po') || '') + '||' + ($(this).data('kd-barang') || '');
                    var total = countMap[key] || 0;
                    $(this).text(total + ' baris');
                });
            }

            function updateSummaryStats(rows) {
                var totalQty = 0;
                var totalRows = rows ? rows.length : 0;
                var totalLot = 0;

                $.each(rows || [], function(_, row) {
                    totalQty += parseFloat(row.qty_diterima) || 0;
                    if ((row.no_lot || '').toString().trim() !== '') {
                        totalLot++;
                    }
                });

                $('#summaryTotalQty').text(formatNumber(totalQty));
                $('#summaryTotalLot').text(formatNumber(totalLot));
                $('#summaryTotalRows').text(formatNumber(totalRows));
            }

            function resetFinalForm() {
                $('#final_no_po').val(defaultFinalForm.no_po);
                $('#final_nosj').val(defaultFinalForm.nosj);
                $('#final_tgl_sj').val(defaultFinalForm.tgl_sj);
                $('#final_invoice').val(defaultFinalForm.no_invoice);
                $('#final_gudang_id').val(defaultFinalForm.gudang_id);
                $('#final_keterangan').val(defaultFinalForm.keterangan);
            }

            function handleAjaxError(xhr, fallbackMessage) {
                var message = fallbackMessage;

                if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                Swal.fire('Gagal', message, 'error');
            }

            function reloadSummaryTable() {
                $.ajax({
                    url: '<?= base_url('ics/ajax_get_tmp_po_received_summary') ?>',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        no_po: '<?= htmlspecialchars($no_po, ENT_QUOTES) ?>',
                        kd_suplier: '<?= htmlspecialchars($kd_suplier ?? '', ENT_QUOTES) ?>'
                    },
                    success: function(res) {
                        if (res.status !== 'success') {
                            Swal.fire('Gagal', res.message || 'Data temporary tidak dapat dimuat.', 'error');
                            return;
                        }

                        renderSummaryTable(res.rows || []);
                        updateDraftBadges(res.rows || []);
                    },
                    error: function() {
                        Swal.fire('Gagal', 'Server tidak merespons saat memuat draft temporary.', 'error');
                    }
                });
            }

            $(document).on('click', '.js-open-modal', function() {
                currentItem = {
                    kd_po: $(this).data('kd-po') || '',
                    kd_suplier: $(this).data('kd-suplier') || '',
                    kd_barang: $(this).data('kd-barang') || '',
                    nama_barang: $(this).data('nama-barang') || '',
                    no_po: $(this).data('no-po') || '',
                    satuan_default: $(this).data('satuan') || '',
                    qty_kecil_sisa: parseFloat($(this).data('sisa')) || 0
                };

                fillModalHeader(currentItem);
                $('#tmpRowsBody').empty();
                $('#tmpModalLoader').show();

                $.ajax({
                    url: '<?= base_url('ics/ajax_get_tmp_po_received_item') ?>',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        kd_po: currentItem.kd_po,
                        kd_barang: currentItem.kd_barang
                    },
                    success: function(res) {
                        $('#tmpModalLoader').hide();

                        if (res.status !== 'success') {
                            Swal.fire('Gagal', res.message || 'Draft barang tidak dapat dimuat.', 'error');
                            renderModalRows([]);
                            return;
                        }

                        renderModalRows(res.rows || []);
                    },
                    error: function() {
                        $('#tmpModalLoader').hide();
                        renderModalRows([]);
                        Swal.fire('Gagal', 'Terjadi kesalahan saat mengambil draft barang.', 'error');
                    }
                });
            });

            $('#btnTambahBarisTmp').on('click', function() {
                $('#tmpRowsBody').append(buildRow());
                syncDeleteButtons();
            });

            $(document).on('click', '.js-hapus-row', function() {
                $(this).closest('tr').remove();
                ensureAtLeastOneRow();
            });

            $('#btnReloadTmpTable').on('click', function() {
                reloadSummaryTable();
            });

            $('#btnResetFinalForm').on('click', function() {
                resetFinalForm();
                Swal.fire('Reset Form', 'Field header draft berhasil dikosongkan.', 'success');
            });

            $(document).on('click', '.js-delete-summary-row', function() {
                var idTmpReceived = parseInt($(this).data('id'), 10) || 0;

                if (!idTmpReceived) {
                    Swal.fire('Gagal', 'ID draft tidak valid.', 'error');
                    return;
                }

                Swal.fire({
                    title: 'Hapus Baris Draft?',
                    text: 'Baris draft temporary ini akan dihapus.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (!result.isConfirmed) {
                        return;
                    }

                    $.ajax({
                        url: '<?= base_url('ics/ajax_delete_tmp_po_received_row') ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id_tmp_recieved: idTmpReceived,
                            no_po: $('#final_no_po').val(),
                            kd_suplier: '<?= htmlspecialchars($kd_suplier ?? '', ENT_QUOTES) ?>'
                        },
                        success: function(res) {
                            if (res.status !== 'success') {
                                Swal.fire('Gagal', res.message || 'Baris draft gagal dihapus.', 'error');
                                return;
                            }

                            reloadSummaryTable();

                            Swal.fire('Berhasil', res.message || 'Baris draft berhasil dihapus.', 'success');
                        },
                        error: function(xhr) {
                            handleAjaxError(xhr, 'Terjadi kesalahan saat menghapus baris draft.');
                        }
                    });
                });
            });

            $('#formTmpPoReceived').on('submit', function(e) {
                e.preventDefault();

                var payloadRows = getPayloadRows();
                var totalQty = 0;
                var hasAnyQty = false;
                var valid = true;

                $.each(payloadRows, function(_, row) {
                    var qty = parseFloat(row.qty_diterima) || 0;

                    if (qty > 0) {
                        hasAnyQty = true;
                        totalQty += qty;

                        if (!row.satuan) {
                            Swal.fire('Validasi', 'Satuan wajib dipilih untuk setiap baris yang memiliki qty.', 'warning');
                            valid = false;
                            return false;
                        }
                    }
                });

                if (!valid) {
                    return;
                }

                if (totalQty > currentItem.qty_kecil_sisa) {
                    Swal.fire(
                        'Qty Melebihi Sisa',
                        'Total qty draft (' + totalQty + ' pcs) melebihi qty kecil sisa barang (' + currentItem.qty_kecil_sisa + ' pcs).',
                        'warning'
                    );
                    return;
                }

                $('#btnSimpanTmp').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');

                $.ajax({
                    url: '<?= base_url('ics/ajax_save_tmp_po_received') ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        kd_po: $('#tmp_kd_po').val(),
                        kd_suplier: $('#tmp_kd_suplier').val(),
                        kd_barang: $('#tmp_kd_barang').val(),
                        rows: payloadRows
                    },
                    success: function(res) {
                        if (res.status !== 'success') {
                            Swal.fire('Gagal', res.message || 'Draft gagal disimpan.', 'error');
                            return;
                        }

                        renderModalRows(res.rows || []);
                        reloadSummaryTable();

                        Swal.fire(
                            'Berhasil',
                            hasAnyQty ? 'Draft penerimaan berhasil disimpan.' : 'Draft untuk barang ini berhasil dikosongkan.',
                            'success'
                        ).then(function() {
                            $('#modalTmpPoReceived').modal('hide');
                        });
                    },
                    error: function(xhr) {
                        handleAjaxError(xhr, 'Terjadi kesalahan saat menyimpan draft.');
                    },
                    complete: function() {
                        $('#btnSimpanTmp').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan Draft');
                    }
                });
            });

            $('#btnSubmitFinalLpb').on('click', function() {
                if (isSubmittingFinal) {
                    return;
                }

                $('#final_invoice').val('-');
                var invoice = '-';
                var nomorSj = $.trim($('#final_nosj').val());
                var tanggalSj = $('#final_tgl_sj').val();
                var gudangId = $('#final_gudang_id').val();
                var keterangan = $.trim($('#final_keterangan').val());

                if (!invoice) {
                    Swal.fire('Validasi', 'Nomor invoice wajib diisi.', 'warning');
                    $('#final_invoice').focus();
                    return;
                }

                if (!gudangId) {
                    Swal.fire('Validasi', 'Silakan pilih gudang tujuan penerimaan.', 'warning');
                    $('#final_gudang_id').focus();
                    return;
                }

                isSubmittingFinal = true;
                $('#btnSubmitFinalLpb')
                    .prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');

                $.ajax({
                    url: '<?= base_url('ics/ajax_finalize_tmp_po_received') ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        no_po: $('#final_no_po').val(),
                        kd_po: $('#final_kd_po').val(),
                        kd_suplier: '<?= htmlspecialchars($kd_suplier ?? '', ENT_QUOTES) ?>',
                        nosj: nomorSj,
                        tgl_sj: tanggalSj,
                        no_invoice: invoice,
                        gudang_id: gudangId,
                        keterangan: keterangan
                    },
                    success: function(res) {
                        if (res.status !== 'success') {
                            Swal.fire('Gagal', res.message || 'Simpan final gagal.', 'error');
                            return;
                        }

                        resetFinalForm();
                        reloadSummaryTable();

                        Swal.fire({
                            title: 'Berhasil',
                            text: res.message || 'Penerimaan berhasil disimpan.',
                            icon: 'success'
                        });
                    },
                    error: function(xhr) {
                        handleAjaxError(xhr, 'Terjadi kesalahan saat menyimpan penerimaan final.');
                    },
                    complete: function() {
                        isSubmittingFinal = false;
                        $('#btnSubmitFinalLpb')
                            .prop('disabled', false)
                            .html('<i class="fas fa-save mr-1"></i> Simpan');
                    }
                });
            });

            $('#modalTmpPoReceived').on('hidden.bs.modal', function() {
                $('#formTmpPoReceived')[0].reset();
                $('#tmpRowsBody').empty();
            });

            resetFinalForm();
            reloadSummaryTable();
        });
    </script>
</body>
