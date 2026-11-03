<!-- view/content/logistik/ics/riwayat_barang_masuk.php -->
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

                <div class="row mb-2">
                    <div class="col-auto">
                        <a href="<?= base_url('data_lpb_zahir') ?>" class="btn btn-primary mb-3">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h3 class="card-title"><i class="fas fa-history mr-2"></i> Riwayat Barang Masuk</h3>
                    </div>
                    <div class="card-body">
                        <div class="container-fluid">

                            <form action="<?= base_url('riwayat_barang_masuk') ?>" method="post">
                                <div class="row mb-3">
                                    <div class="col-2">
                                        <label class="small">Dari Tanggal</label>
                                        <input type="date" class="form-control" name="date1" value="<?= $date1 ?? '' ?>">
                                    </div>
                                    <div class="col-2">
                                        <label class="small">Sampai Tanggal</label>
                                        <input type="date" class="form-control" name="date2" value="<?= $date2 ?? '' ?>">
                                    </div>
                                    <div class="col-2">
                                        <label class="small">&nbsp;</label>
                                        <button class="btn btn-success btn-block d-block">
                                            <i class="fas fa-search"></i> Tampil
                                        </button>
                                    </div>
                                    <?php if (!empty($riwayat)) : ?>
                                    <div class="col-auto ml-auto align-self-end">
                                        <button type="button" class="btn btn-warning" onclick="cetakSemua()">
                                            <i class="fas fa-print"></i> Cetak Semua
                                        </button>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </form>

                            <table class="table table-striped table-bordered table-hover table-sm" id="tabelRiwayat">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Tgl Input</th>
                                        <th>No PO</th>
                                        <th>Kode Supplier</th>
                                        <th>Nama Supplier</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th class="text-right">Qty Diterima</th>
                                        <th>Satuan</th>
                                        <th>No Lot</th>
                                        <th>Exp Date</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($riwayat)) : ?>
                                        <?php foreach ($riwayat as $row) : ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['create_at']    ?? '') ?></td>
                                                <td><?= htmlspecialchars($row['no_po']        ?? '') ?></td>
                                                <td><?= htmlspecialchars($row['kd_suplier']   ?? '') ?></td>
                                                <td><?= htmlspecialchars($row['nama_suplier'] ?? '-') ?></td>
                                                <td><?= htmlspecialchars($row['kd_barang']    ?? '') ?></td>
                                                <td><?= htmlspecialchars($row['nama_barang']  ?? '-') ?></td>
                                                <td class="text-right"><?= number_format($row['qty_diterima'] ?? 0) ?></td>
                                                <td><?= htmlspecialchars($row['satuan']       ?? '') ?></td>
                                                <td><?= htmlspecialchars($row['no_lot']       ?? '-') ?></td>
                                                <td><?= htmlspecialchars($row['exp_date']     ?? '-') ?></td>
                                                <td class="text-center">
                                                    <button
                                                        class="btn btn-sm btn-warning"
                                                        title="Cetak Bukti Penerimaan"
                                                        onclick="cetakSatu(<?= $row['id_detail_lpb'] ?>)">
                                                        <i class="fas fa-print"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="11" class="text-center text-muted">
                                                <i class="fas fa-inbox mr-1"></i> Tidak ada data
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

<!-- Area Cetak -->
<div id="areaCetak" style="display:none;"></div>

<style>
@media print {
    body > * { display: none !important; }
    #areaCetak { display: block !important; }

    .bukti-penerimaan {
        page-break-after: always;
        padding: 20px;
        font-family: Arial, sans-serif;
        font-size: 12px;
    }
    .bukti-penerimaan:last-child { page-break-after: avoid; }
    .bukti-header { text-align: center; margin-bottom: 15px; border-bottom: 2px solid #000; padding-bottom: 10px; }
    .bukti-header h4 { margin: 0; font-size: 16px; font-weight: bold; }
    .bukti-header p  { margin: 2px 0; font-size: 11px; }
    .bukti-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .bukti-table th,
    .bukti-table td { border: 1px solid #000; padding: 6px 8px; font-size: 11px; }
    .bukti-table th { background: #f0f0f0; font-weight: bold; }
    .ttd-area { margin-top: 30px; display: flex; justify-content: space-between; }
    .ttd-box  { text-align: center; width: 30%; }
    .ttd-box .ttd-line { margin-top: 50px; border-top: 1px solid #000; padding-top: 5px; }
}
</style>

<script>
$(document).ready(function () {
    $('#tabelRiwayat').DataTable({
        responsive  : true,
        autoWidth   : false,
        pageLength  : 25,
        order       : [[0, 'desc']],
        columnDefs  : [{ orderable: false, targets: -1 }],
        language: {
            search      : "Cari:",
            lengthMenu  : "Tampilkan _MENU_ data",
            info        : "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            zeroRecords : "Tidak ada data ditemukan",
            emptyTable  : "Tidak ada data tersedia",
            paginate    : { first: "Pertama", last: "Terakhir", next: "Berikutnya", previous: "Sebelumnya" }
        }
    });
});

var dataRiwayat = <?= json_encode($riwayat ?? []) ?>;

function buatHtmlBuktiSatu(row) {
    return `
    <div class="bukti-penerimaan">
        <div class="bukti-header">
            <h4>PT. KARISMA INDOARGO UNIVERSAL</h4>
            <p>BUKTI PENERIMAAN BARANG</p>
            <p>Tanggal Cetak: ${new Date().toLocaleDateString('id-ID', {day:'2-digit', month:'long', year:'numeric'})}</p>
        </div>
        <table class="bukti-table">
            <tr><th width="35%">No PO</th><td>${row.no_po || '-'}</td></tr>
            <tr><th>Kode Supplier</th><td>${row.kd_suplier || '-'}</td></tr>
            <tr><th>Nama Supplier</th><td>${row.nama_suplier || '-'}</td></tr>
            <tr><th>Kode Barang</th><td>${row.kd_barang || '-'}</td></tr>
            <tr><th>Nama Barang</th><td>${row.nama_barang || '-'}</td></tr>
            <tr><th>Qty Diterima</th><td>${Number(row.qty_diterima).toLocaleString('id-ID')} ${row.satuan || ''}</td></tr>
            <tr><th>No Lot</th><td>${row.no_lot || '-'}</td></tr>
            <tr><th>Exp Date</th><td>${row.exp_date || '-'}</td></tr>
            <tr><th>Tanggal Input</th><td>${row.create_at || '-'}</td></tr>
        </table>
        <div class="ttd-area">
            <div class="ttd-box"><div class="ttd-line">Diterima Oleh</div></div>
            <div class="ttd-box"><div class="ttd-line">Diketahui Oleh</div></div>
            <div class="ttd-box"><div class="ttd-line">Kepala Gudang</div></div>
        </div>
    </div>`;
}

function cetakSatu(id) {
    var row = dataRiwayat.find(function(r) { return r.id_detail_lpb == id; });
    if (!row) { alert('Data tidak ditemukan'); return; }
    document.getElementById('areaCetak').innerHTML = buatHtmlBuktiSatu(row);
    window.print();
}

function cetakSemua() {
    if (!dataRiwayat || dataRiwayat.length === 0) { alert('Tidak ada data'); return; }

    var html = `
    <div class="bukti-penerimaan">
        <div class="bukti-header">
            <h4>PT. KARISMA INDOARGO UNIVERSAL</h4>
            <p>DAFTAR PENERIMAAN BARANG</p>
            <p>Tanggal Cetak: ${new Date().toLocaleDateString('id-ID', {day:'2-digit', month:'long', year:'numeric'})}</p>
        </div>
        <table class="bukti-table" style="width:100%;border-collapse:collapse;margin-top:10px;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No PO</th>
                    <th>Kode Supplier</th>
                    <th>Nama Supplier</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Qty</th>
                    <th>Satuan</th>
                    <th>No Lot</th>
                    <th>Exp Date</th>
                    <th>Tgl Input</th>
                </tr>
            </thead>
            <tbody>`;

    dataRiwayat.forEach(function(row, i) {
        html += `<tr>
            <td>${i + 1}</td>
            <td>${row.no_po || '-'}</td>
            <td>${row.kd_suplier || '-'}</td>
            <td>${row.nama_suplier || '-'}</td>
            <td>${row.kd_barang || '-'}</td>
            <td>${row.nama_barang || '-'}</td>
            <td style="text-align:right;">${Number(row.qty_diterima).toLocaleString('id-ID')}</td>
            <td>${row.satuan || '-'}</td>
            <td>${row.no_lot || '-'}</td>
            <td>${row.exp_date || '-'}</td>
            <td>${row.create_at || '-'}</td>
        </tr>`;
    });

    html += `</tbody>
        </table>
        <div class="ttd-area">
            <div class="ttd-box"><div class="ttd-line">Diterima Oleh</div></div>
            <div class="ttd-box"><div class="ttd-line">Diketahui Oleh</div></div>
            <div class="ttd-box"><div class="ttd-line">Kepala Gudang</div></div>
        </div>
    </div>`;

    document.getElementById('areaCetak').innerHTML = html;
    window.print();
}
</script>
</body>