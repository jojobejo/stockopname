<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="Logo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>

        <div class="content-wrapper">
            <div class="content-header">
                <section class="content">

                    <?php if ($this->session->flashdata('success')) : ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle mr-1"></i> <?= $this->session->flashdata('success') ?>
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('error')) : ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle mr-1"></i> <?= $this->session->flashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        </div>
                    <?php endif; ?>

                    <div class="row mb-2">
                        <div class="col-auto">
                            <a href="<?= base_url('ics/ics_diffrent') ?>" class="btn btn-md btn-primary mb-3">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                        <div class="col-auto">
                            <a href="<?= base_url('po_selesai') ?>" class="btn btn-md btn-success mb-3">
                                <i class="fas fa-check-double"></i> PO Selesai
                            </a>
                        </div>
                        <div class="col-auto">
                            <a href="<?= base_url('riwayat_barang_masuk') ?>" class="btn btn-md btn-info mb-3">
                                <i class="fas fa-history"></i> Riwayat Barang Masuk
                            </a>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-truck-loading mr-2"></i> Data PO - Barang Belum Terpenuhi</h3>
                        </div>
                        <div class="card-body">
                            <div class="container-fluid">

                                <div class="row mb-2">
                                    <div class="col-2">
                                        <button class="btn btn-success mb-3 btn-block" data-toggle="modal" data-target="#modalImportCSV">
                                            <i class="fas fa-file-csv"></i> Import CSV
                                        </button>
                                    </div>
                                    <div class="col-2">
                                        <a class="btn btn-secondary mb-3 btn-block" href="<?= base_url('data_lpb_zahir') ?>">
                                            <i class="fas fa-file-csv"></i> Data LPB
                                        </a>
                                    </div>
                                </div>

                                <form action="<?= base_url('data_lpb_zahir') ?>" method="post">
                                    <div class="row mb-3">
                                        <div class="col-2">
                                            <input type="date" class="form-control" name="date1" id="name1" value="<?= htmlspecialchars($this->input->post('date1') ?? '') ?>">
                                        </div>
                                        <div class="col-2">
                                            <input type="date" class="form-control" name="date2" id="name2" value="<?= htmlspecialchars($this->input->post('date2') ?? '') ?>">
                                        </div>
                                        <div class="col-2">
                                            <button class="btn btn-success btn-block">
                                                <i class="fas fa-search"></i> Tampil
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <?php
                                // ── Hitung last update global & statistik ──────────────
                                $last_update_global = null;
                                $total_po           = 0;
                                $total_sebagian     = 0;
                                $total_belum        = 0;

                                if (!empty($lpb)) :
                                    foreach ($lpb as $row) :
                                        $sisa = (int)($row['jumlah_barang'] ?? 0) - (int)($row['jumlah_barang_masuk'] ?? 0);
                                        if ($sisa <= 0) continue; // skip yang sudah selesai

                                        $total_po++;
                                        if ((int)($row['jumlah_barang_masuk'] ?? 0) > 0) $total_sebagian++;
                                        else $total_belum++;

                                        if (!empty($row['last_input'])) {
                                            if ($last_update_global === null || $row['last_input'] > $last_update_global) {
                                                $last_update_global = $row['last_input'];
                                            }
                                        }
                                    endforeach;
                                endif;
                                ?>

                                <!-- ── Info Bar: Last Update + Statistik ── -->
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <div class="info-box shadow-sm mb-0">
                                            <span class="info-box-icon bg-info"><i class="fas fa-clock"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Last Update Input</span>
                                                <span class="info-box-number" style="font-size:14px;">
                                                    <?= $last_update_global
                                                        ? date('d/m/Y H:i', strtotime($last_update_global))
                                                        : '<span class="text-muted">Belum ada input</span>' ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="info-box shadow-sm mb-0">
                                            <span class="info-box-icon bg-danger"><i class="fas fa-box"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Belum Datang</span>
                                                <span class="info-box-number"><?= $total_belum ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="info-box shadow-sm mb-0">
                                            <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Sebagian Masuk</span>
                                                <span class="info-box-number"><?= $total_sebagian ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="info-box shadow-sm mb-0">
                                            <span class="info-box-icon bg-secondary"><i class="fas fa-list"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Total PO Pending</span>
                                                <span class="info-box-number"><?= $total_po ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ── Legenda Warna ── -->
                                <div class="mb-2">
                                    <span class="badge badge-light border px-2 py-1 mr-1"><i class="fas fa-box mr-1"></i> Belum ada masuk</span>
                                    <span class="badge badge-warning px-2 py-1 mr-1"><i class="fas fa-clock mr-1"></i> Sebagian sudah masuk</span>
                                </div>

                                <table class="table table-bordered" id="tabelPO">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>No PO</th>
                                            <th>Tgl PO</th>
                                            <th>Kode Supplier</th>
                                            <th>Nama Supplier</th>
                                            <th class="text-center">Jml Barang</th>
                                            <th class="text-center">Barang Masuk</th>
                                            <th class="text-center">Status</th>
                                            <th>Tgl Input Terakhir</th>
                                            <th class="text-center" style="width:70px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ada_data = false;
                                        $processed_kd_po = []; // Array untuk tracking kd_po yang sudah ditampilkan

                                        if (!empty($lpb)) :
                                            // Urutkan berdasarkan last_input terbaru
                                            $sorted_lpb = $lpb;
                                            usort($sorted_lpb, function ($a, $b) {
                                                $date_a = strtotime($a['last_input'] ?? '1970-01-01');
                                                $date_b = strtotime($b['last_input'] ?? '1970-01-01');
                                                return $date_b - $date_a;
                                            });

                                            foreach ($sorted_lpb as $row) :
                                                $kd_po_current = $row['kd_po'] ?? '';

                                                // Skip jika kd_po sudah pernah ditampilkan (mencegah duplikasi)
                                                if (in_array($kd_po_current, $processed_kd_po)) {
                                                    continue;
                                                }

                                                $jumlah_barang       = (int)($row['jumlah_barang'] ?? 0);
                                                $jumlah_barang_masuk = (int)($row['jumlah_barang_masuk'] ?? 0);
                                                $sisa                = $jumlah_barang - $jumlah_barang_masuk;

                                                if ($sisa <= 0) continue;

                                                // Tandai kd_po sebagai sudah diproses
                                                $processed_kd_po[] = $kd_po_current;
                                                $ada_data = true;

                                                // Warna baris & badge status
                                                if ($jumlah_barang_masuk > 0) {
                                                    $row_class = 'table-warning';
                                                    $badge     = '<span class="badge badge-warning px-2 py-1">
                                                                <i class="fas fa-clock mr-1"></i> Sebagian
                                                            </span>';
                                                } else {
                                                    $row_class = '';
                                                    $badge     = '<span class="badge badge-light border px-2 py-1">
                                                                <i class="fas fa-box mr-1"></i> Belum Datang
                                                            </span>';
                                                }

                                                // Format tgl input terakhir
                                                $tgl_input = !empty($row['last_input'])
                                                    ? date('d/m/Y H:i', strtotime($row['last_input']))
                                                    : '-';
                                        ?>
                                                <tr class="<?= $row_class ?>" data-kd-po="<?= htmlspecialchars($kd_po_current) ?>">
                                                    <td><?= htmlspecialchars($row['no_po'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($row['tgl_transaksi'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($row['kd_suplier'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($row['nama_suplier'] ?? '-') ?></td>
                                                    <td class="text-center font-weight-bold"><?= $jumlah_barang ?></td>
                                                    <td class="text-center font-weight-bold <?= $jumlah_barang_masuk > 0 ? 'text-success' : 'text-secondary' ?>">
                                                        <?= $jumlah_barang_masuk ?>
                                                    </td>
                                                    <td class="text-center"><?= $badge ?></td>
                                                    <td class="text-center" data-order="<?= !empty($row['last_input']) ? strtotime($row['last_input']) : 0 ?>">
                                                        <?php if ($tgl_input !== '-') : ?>
                                                            <span class="text-info font-weight-bold">
                                                                <i class="fas fa-calendar-check mr-1"></i><?= $tgl_input ?>
                                                            </span>
                                                        <?php else : ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <button class="btn btn-sm btn-success btn-input-qty" title="Input Penerimaan" data-no-po="<?= htmlspecialchars($row['no_po'] ?? '') ?>" data-kd-po="<?= htmlspecialchars($kd_po_current) ?>" data-kd-suplier="<?= htmlspecialchars($row['kd_suplier'] ?? '') ?>" data-nama-suplier="<?= htmlspecialchars($row['nama_suplier'] ?? '') ?>" data-toggle="modal" data-target="#modalInputQty">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php
                                            endforeach;
                                        endif;
                                        if (!$ada_data) :
                                            ?>
                                            <tr>
                                                <td colspan="9" class="text-center text-success">
                                                    <i class="fas fa-check-circle mr-1"></i> Semua barang PO sudah terpenuhi
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>

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

    <!-- Modal dan script sama seperti sebelumnya, hanya tambahkan reload setelah submit -->
    <div class="modal fade" id="modalInputQty" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle mr-2"></i> Input Penerimaan Barang
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">

                    <!-- Info PO -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="font-weight-bold">No PO</label>
                            <input type="text" class="form-control-plaintext font-weight-bold text-primary" id="modal_no_po" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="font-weight-bold">Supplier</label>
                            <input type="text" class="form-control-plaintext text-muted" id="modal_nama_suplier" readonly>
                        </div>
                    </div>
                    <hr class="my-2">

                    <!-- Loading indicator -->
                    <div id="loadingBarang" class="text-center py-3" style="display:none;">
                        <i class="fas fa-spinner fa-spin fa-2x text-success"></i>
                        <p class="mt-2 text-muted">Memuat data barang...</p>
                    </div>

                    <!-- Form input per barang -->
                    <form id="formInputQty" action="<?= base_url('save_qty_diterima') ?>" method="post">
                        <input type="hidden" name="no_po" id="form_no_po">
                        <input type="hidden" name="kd_po" id="form_kd_po">

                        <div id="wrapperBarang">
                            <!-- Diisi dinamis via AJAX -->
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Batal
                    </button>
                    <button type="submit" form="formInputQty" class="btn btn-success">
                        <i class="fas fa-save mr-1"></i> Simpan Semua
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            // ── DataTables ────────────────────────────────────────────────
            var table = $('#tabelPO').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 25,
                order: [
                    [7, 'desc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: -1
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

            // Simpan options satuan sebagai array JS
            var listSatuan = [];
            <?php foreach ($list_satuan as $s) : ?>
                listSatuan.push('<?= addslashes($s['nm_satuan']) ?>');
            <?php endforeach; ?>

            function buildSatuanOptions() {
                var opts = '<option value="">-- Pilih --</option>';
                listSatuan.forEach(function(s) {
                    opts += '<option value="' + s + '">' + s + '</option>';
                });
                return opts;
            }

            $(document).on('click', '.btn-input-qty', function() {
                var noPo = $(this).data('no-po');
                var kdPo = $(this).data('kd-po');
                var kdSuplier = $(this).data('kd-suplier');
                var namaSuplier = $(this).data('nama-suplier');

                // Debugging
                console.log('=== DEBUG MODAL ===');
                console.log('No PO:', noPo);
                console.log('KD PO:', kdPo);
                console.log('KD Suplier:', kdSuplier);
                console.log('Nama Suplier:', namaSuplier);

                $('#modal_no_po').val(noPo);
                $('#modal_nama_suplier').val(namaSuplier);
                $('#form_no_po').val(noPo);
                $('#form_kd_po').val(kdPo);

                $('#wrapperBarang').html('');
                $('#loadingBarang').show();

                // Kirimkan juga KD_PO ke server
                $.getJSON('<?= base_url('get_barang_by_po') ?>', {
                    no_po: noPo,
                    kd_suplier: kdSuplier,
                    kd_po: kdPo
                }, function(data) {
                    console.log('Data barang yang diterima:', data);
                    $('#loadingBarang').hide();

                    if (!data || data.length === 0) {
                        $('#wrapperBarang').html(
                            '<div class="alert alert-success"><i class="fas fa-check-circle mr-1"></i> Semua barang dalam PO ini sudah terpenuhi.</div>'
                        );
                        return;
                    }

                    $('#wrapperBarang').html(buildFormBarang(data));
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error('Error AJAX:', textStatus, errorThrown);
                    $('#loadingBarang').hide();
                    $('#wrapperBarang').html(
                        '<div class="alert alert-danger"><i class="fas fa-exclamation-circle mr-1"></i> Gagal memuat data barang. Silakan coba lagi.</div>'
                    );
                });
            });

            // Build HTML form per barang
            function buildFormBarang(items) {
                var html = '';
                $.each(items, function(idx, item) {
                    html += `
            <div class="card card-outline card-success mb-3" data-barang-idx="${idx}" data-sisa="${item.sisa}">
                <div class="card-header py-2">
                    <strong class="text-success">${escStr(item.kd_barang)}</strong>
                    &mdash; ${escStr(item.nama_barang)}
                    <span class="badge badge-warning ml-2">Sisa: ${item.sisa} ${escStr(item.satuan)}</span>
                    <input type="hidden" name="rows[${idx}][kd_barang]" value="${escStr(item.kd_barang)}">
                    <input type="hidden" name="rows[${idx}][kd_po]" value="${escStr(item.kd_po)}">
                </div>
                <div class="card-body py-2">
                    <table class="table table-bordered table-sm mb-1">
                        <thead class="thead-light">
                            <tr>
                                <th style="width:150px;">Qty Diterima</th>
                                <th style="width:130px;">Satuan</th>
                                <th style="width:160px;">No Lot</th>
                                <th style="width:160px;">Exp Date</th>
                                <th style="width:50px;"></th>
                            </tr>
                        </thead>
                        <tbody class="body-sub-baris">
                            ${buildBarisSub(idx, 0, item.sisa)}
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-xs btn-info btn-tambah-sub"
                            data-idx="${idx}" data-sisa="${item.sisa}">
                        <i class="fas fa-plus"></i> Tambah Baris
                    </button>
                </div>
            </div>`;
                });
                return html;
            }

            function buildBarisSub(idx, subIdx, sisa) {
                return `
        <tr>
            <td><input type="number" class="form-control form-control-sm input-qty"
                       name="rows[${idx}][sub][${subIdx}][qty_diterima]"
                       min="1" max="${sisa}" placeholder="0"></td>
            <td>
                <select class="form-control form-control-sm select-satuan"
                        name="rows[${idx}][sub][${subIdx}][satuan]">
                    ${buildSatuanOptions()}
                </select>
            </td>
            <td><input type="text" class="form-control form-control-sm"
                       name="rows[${idx}][sub][${subIdx}][no_lot]" placeholder="No Lot"></td>
            <td><input type="date" class="form-control form-control-sm"
                       name="rows[${idx}][sub][${subIdx}][exp_date]"></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger btn-hapus-sub" disabled>
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>`;
            }

            function escStr(str) {
                return $('<div>').text(str || '').html();
            }

            // Event delegation untuk tombol tambah dan hapus sub baris
            $(document).on('click', '.btn-tambah-sub', function() {
                var idx = $(this).data('idx');
                var sisa = $(this).data('sisa');
                var tbody = $(this).closest('.card-body').find('.body-sub-baris');
                var subIdx = tbody.find('tr').length;

                tbody.append(buildBarisSub(idx, subIdx, sisa));
                updateHapusSub(tbody);
            });

            $(document).on('click', '.btn-hapus-sub', function() {
                var tbody = $(this).closest('tbody');
                $(this).closest('tr').remove();
                reindexSubBaris(tbody);
                updateHapusSub(tbody);
            });

            function updateHapusSub(tbody) {
                var btns = tbody.find('.btn-hapus-sub');
                btns.prop('disabled', btns.length === 1);
            }

            function reindexSubBaris(tbody) {
                var idx = tbody.closest('.card').data('barang-idx');
                tbody.find('tr').each(function(si) {
                    $(this).find('input, select').each(function() {
                        var name = $(this).attr('name');
                        if (name) {
                            $(this).attr('name', name.replace(/\[sub\]\[\d+\]/, '[sub][' + si + ']'));
                        }
                    });
                });
            }

            // Validasi submit
            $(document).on('submit', '#formInputQty', function(e) {
                var valid = true;
                var adaYangDiisi = false;

                $('.card[data-barang-idx]').each(function() {
                    var card = $(this);
                    var sisa = parseInt(card.data('sisa')) || 0;
                    var total = 0;
                    var barisDiisi = 0;

                    card.find('.input-qty').each(function() {
                        var qty = parseInt($(this).val()) || 0;
                        if (qty > 0) {
                            barisDiisi++;
                            total += qty;

                            var satuan = $(this).closest('tr').find('.select-satuan').val();
                            if (!satuan) {
                                valid = false;
                                var namaBarang = card.find('.card-header strong').text();
                                alert('Satuan wajib dipilih untuk barang "' + namaBarang + '" yang qty-nya diisi.');
                                return false;
                            }
                        }
                    });

                    if (barisDiisi > 0) adaYangDiisi = true;

                    if (total > sisa) {
                        valid = false;
                        var namaBarang = card.find('.card-header strong').text();
                        alert('Total qty untuk barang "' + namaBarang + '" (' + total + ') melebihi sisa (' + sisa + ').');
                        return false;
                    }
                });

                if (valid && !adaYangDiisi) {
                    valid = false;
                    alert('Minimal isi qty untuk 1 barang sebelum menyimpan.');
                }

                if (!valid) {
                    e.preventDefault();
                    return false;
                }

                // Reload halaman setelah submit
                setTimeout(function() {
                    location.reload();
                }, 500);
            });

            // Auto-hide alert
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 4000);
        });
    </script>
</body>