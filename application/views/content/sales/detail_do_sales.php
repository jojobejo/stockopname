<!-- views/content/sales/detail_do_sales.php -->
<body class="hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view('partial/main/navbar') ?>
    <?php $this->load->view('partial/main/sidebar') ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Detail Delivery Order — Sales</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('sales_order/list_do') ?>">List DO Sales</a></li>
                            <li class="breadcrumb-item active">
                                <?= isset($kdo[0]) ? htmlspecialchars($kdo[0]->kd_do) : 'Detail DO' ?>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <?php foreach ($kdo as $k) : ?>
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>Detail Delivery Order — Sales View</h4>
                    </div>
                    <div class="card-body">

                        <!-- Info DO -->
                        <div class="row mb-4">
                            <!-- Kiri: Info + Progress -->
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr><td><strong>Kode DO</strong></td><td>: <?= $k->kd_do ?></td></tr>
                                    <tr><td><strong>Regional</strong></td><td>: <?= $k->regional ?></td></tr>
                                    <tr><td><strong>Total Faktur</strong></td><td>: <?= $k->totalfaktur ?></td></tr>
                                    <tr><td><strong>Total Barang</strong></td><td>: <?= $k->total_barang ?></td></tr>
                                </table>

                                <?php
                                $tonase   = (float)$k->total_tonase_faktur;
                                $kubikasi = (float)$k->total_kubikasi;
                                $max_ton  = 6;
                                $max_kub  = 9;

                                $pct_ton = min(100, round(($tonase   / $max_ton) * 100, 1));
                                $pct_kub = min(100, round(($kubikasi / $max_kub) * 100, 1));

                                $sisa_ton = max(0, round($max_ton - $tonase,   3));
                                $sisa_kub = max(0, round($max_kub - $kubikasi, 4));

                                $bar_ton = $pct_ton >= 100 ? 'danger' : ($pct_ton >= 80 ? 'warning' : 'success');
                                $bar_kub = $pct_kub >= 100 ? 'danger' : ($pct_kub >= 80 ? 'warning' : 'success');
                                ?>

                                <!-- TONASE -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span><strong><i class="fas fa-weight mr-1"></i>Tonase</strong></span>
                                        <span>
                                            <strong><?= $tonase ?></strong> / <?= $max_ton ?> TON
                                            <?php if ($pct_ton >= 100): ?>
                                                <span class="badge badge-danger ml-1">PENUH</span>
                                            <?php elseif ($pct_ton >= 80): ?>
                                                <span class="badge badge-warning ml-1">HAMPIR PENUH</span>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                    <div class="progress" style="height:20px;border-radius:4px;">
                                        <div class="progress-bar bg-<?= $bar_ton ?> progress-bar-striped"
                                            role="progressbar"
                                            style="width:<?= $pct_ton ?>%;font-size:12px;line-height:20px;"
                                            aria-valuenow="<?= $pct_ton ?>" aria-valuemin="0" aria-valuemax="100">
                                            <?= $pct_ton ?>%
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-1" style="font-size:12px;color:#6c757d;">
                                        <span>Terpakai: <?= $tonase ?> TON</span>
                                        <span>Sisa: <strong class="text-<?= $sisa_ton <= 0 ? 'danger' : 'success' ?>"><?= $sisa_ton ?> TON</strong></span>
                                    </div>
                                </div>

                                <!-- KUBIKASI -->
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span><strong><i class="fas fa-cube mr-1"></i>Kubikasi</strong></span>
                                        <span>
                                            <strong><?= $kubikasi ?></strong> / <?= $max_kub ?> m³
                                            <?php if ($pct_kub >= 100): ?>
                                                <span class="badge badge-danger ml-1">PENUH</span>
                                            <?php elseif ($pct_kub >= 80): ?>
                                                <span class="badge badge-warning ml-1">HAMPIR PENUH</span>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                    <div class="progress" style="height:20px;border-radius:4px;">
                                        <div class="progress-bar bg-<?= $bar_kub ?> progress-bar-striped"
                                            role="progressbar"
                                            style="width:<?= $pct_kub ?>%;font-size:12px;line-height:20px;"
                                            aria-valuenow="<?= $pct_kub ?>" aria-valuemin="0" aria-valuemax="100">
                                            <?= $pct_kub ?>%
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-1" style="font-size:12px;color:#6c757d;">
                                        <span>Terpakai: <?= $kubikasi ?> m³</span>
                                        <span>Sisa: <strong class="text-<?= $sisa_kub <= 0 ? 'danger' : 'success' ?>"><?= $sisa_kub ?> m³</strong></span>
                                    </div>
                                </div>
                            </div>

                            <!-- ✅ Kanan: Status konfirmasi + Tombol -->
                            <div class="col-md-6">
                                <?php
                                $confirm_status = $k->sales_confirm_status ?? 'pending';
                                if ($confirm_status === 'pending' || $confirm_status === null) {
                                    echo '<div class="alert alert-warning">
                                            <i class="fas fa-clock mr-1"></i>
                                            <strong>Menunggu Konfirmasi</strong><br>
                                        </div>';
                                } elseif ($confirm_status === 'siap') {
                                    echo '<div class="alert alert-success">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            <strong>Siap Loading</strong><br>
                                            <small>Dikonfirmasi oleh: <strong>' . htmlspecialchars($k->sales_confirm_by ?? '-') . '</strong></small><br>
                                            <small>Waktu: ' . ($k->sales_confirm_at ?? '-') . '</small>
                                        </div>';
                                } elseif ($confirm_status === 'belum_siap') {
                                    echo '<div class="alert alert-danger">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            <strong>Belum Siap Loading</strong><br>
                                            <small>Catatan: ' . htmlspecialchars($k->sales_confirm_note ?? '-') . '</small><br>
                                            <small>Dikonfirmasi oleh: <strong>' . htmlspecialchars($k->sales_confirm_by ?? '-') . '</strong></small>
                                        </div>';
                                }
                                ?>

                                <!-- ✅ Tombol konfirmasi — muncul jika pending ATAU belum_siap -->
                                <?php if (in_array($confirm_status, ['pending', 'belum_siap', null])) : ?>
                                <div class="card card-body bg-light">
                                    <h6 class="mb-3">
                                        <i class="fas fa-clipboard-check mr-1"></i>
                                        Konfirmasi Kesiapan Loading
                                    </h6>
                                    <div class="form-group mb-3">
                                        <label style="font-size:13px;">Catatan <span class="text-muted">(opsional)</span></label>
                                        <textarea id="confirm_note" class="form-control form-control-sm"
                                                rows="2"
                                                placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <button type="button"
                                                    class="btn btn-success btn-block btn-confirm-loading"
                                                    data-kd="<?= $k->kd_do ?>"
                                                    data-action="siap">
                                                <i class="fas fa-check mr-1"></i> Siap Loading
                                            </button>
                                        </div>
                                        <div class="col-6">
                                            <button type="button"
                                                    class="btn btn-danger btn-block btn-confirm-loading"
                                                    data-kd="<?= $k->kd_do ?>"
                                                    data-action="belum_siap">
                                                <i class="fas fa-times mr-1"></i> Belum Siap
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Tabel Detail Barang — VIEW ONLY -->
                        <table class="table table-bordered table-striped table-sm">
                            <thead style="background-color:#212529;color:white;">
                                <tr>
                                    <th>Nama Kios</th>
                                    <th>Regional</th>
                                    <th>Rute</th>
                                    <th>Kode Faktur</th>
                                    <th>Tgl Input</th>
                                    <th>Nama Barang</th>
                                    <th>No Lot</th>
                                    <th>Box</th>
                                    <th>Pcs</th>
                                    <th>Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $prev_faktur   = null;
                                $rowspan_count = [];
                                $printed       = [];

                                foreach ($data_list as $row) {
                                    if (!isset($rowspan_count[$row->kd_faktur])) {
                                        $rowspan_count[$row->kd_faktur] = 0;
                                    }
                                    $rowspan_count[$row->kd_faktur]++;
                                }

                                foreach ($data_list as $row) :
                                    $show = !in_array($row->kd_faktur, $printed);
                                    if ($show) $printed[] = $row->kd_faktur;
                                ?>
                                <tr>
                                    <?php if ($show) : ?>
                                        <td rowspan="<?= $rowspan_count[$row->kd_faktur] ?>"><?= $row->nama_kios ?></td>
                                        <td rowspan="<?= $rowspan_count[$row->kd_faktur] ?>"><?= $row->regional ?></td>
                                        <td rowspan="<?= $rowspan_count[$row->kd_faktur] ?>"><?= $row->kd_rute ?></td>
                                        <td rowspan="<?= $rowspan_count[$row->kd_faktur] ?>"><?= $row->kd_faktur ?></td>
                                        <td rowspan="<?= $rowspan_count[$row->kd_faktur] ?>"><?= $row->tgl_transaksi ?></td>
                                    <?php endif; ?>
                                    <td><?= $row->nm_barang ?></td>
                                    <td><?= $row->no_lot ?></td>
                                    <td><?= $row->qty_box ?></td>
                                    <td><?= $row->qty_pcs ?></td>
                                    <td><?= $row->qty ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <!-- Log Konfirmasi -->
                        <?php if (!empty($log_confirm)) : ?>
                        <div class="mt-3">
                            <h5>Riwayat Konfirmasi</h5>
                            <table class="table table-sm table-bordered">
                                <thead><tr><th>Waktu</th><th>Action</th><th>Oleh</th><th>Catatan</th></tr></thead>
                                <tbody>
                                    <?php foreach ($log_confirm as $log) : ?>
                                    <tr>
                                        <td><?= $log->confirm_at ?></td>
                                        <td>
                                            <?php if ($log->action === 'siap') : ?>
                                                <span class="badge badge-success">Siap Loading</span>
                                            <?php else : ?>
                                                <span class="badge badge-danger">Belum Siap Loading</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($log->confirm_by ?? '-') ?></td>
                                        <td><?= htmlspecialchars($log->note ?? '-') ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>

                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <strong>Copyright &copy; 2022 <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.</strong>
    </footer>
</div>

<script>
$(document).ready(function () {
    $('.btn-confirm-loading').on('click', function () {
        var kd_do  = $(this).data('kd');
        var action = $(this).data('action');
        var note   = $('#confirm_note').val();
        var label  = action === 'siap' ? 'Siap Loading' : 'Belum Siap Loading';

        if (!confirm('Konfirmasi: ' + label + ' untuk DO ' + kd_do + '?')) return;

        $.ajax({
            url: '<?= base_url("sales_order/confirm_loading") ?>',
            type: 'POST',
            data: { kd_do: kd_do, action: action, note: note },
            dataType: 'json',
            success: function (res) {
                if (res.msg === 'success') {
                    alert(res.message);
                    window.location.reload();
                } else {
                    alert('Error: ' + res.message);
                }
            },
            error: function () {
                alert('Terjadi kesalahan koneksi.');
            }
        });
    });
});
</script>