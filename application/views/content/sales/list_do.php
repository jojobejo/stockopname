<!-- views/content/sales/list_do.php -->
<body class="hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view('partial/main/navbar') ?>
    <?php $this->load->view('partial/main/sidebar') ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">List Delivery Order — Sales</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item active">List DO Sales</li>
                        </ol>
                    </div>
                </div>
            </div>
            <?php
            // Hitung summary dari $listdo
            $total_do        = count($listdo);
            $total_pending   = 0;
            $total_siap      = 0;
            $total_blm_siap  = 0;

            foreach ($listdo as $i) {
                $confirm = $i->sales_confirm_status ?? 'pending';
                if ($confirm === 'siap')             $total_siap++;
                elseif ($confirm === 'belum_siap')   $total_blm_siap++;
                else                                 $total_pending++;
            }
            ?>

            <!-- SUMMARY CARDS -->
            <div class="row mb-3">
                <div class="col-6 col-sm-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-secondary elevation-1">
                            <i class="fas fa-truck"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total DO</span>
                            <span class="info-box-number"><?= $total_do ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-warning elevation-1">
                            <i class="fas fa-clock"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Menunggu Konfirmasi</span>
                            <span class="info-box-number"><?= $total_pending ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-success elevation-1">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Siap Loading</span>
                            <span class="info-box-number"><?= $total_siap ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-danger elevation-1">
                            <i class="fas fa-times-circle"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Belum Siap Loading</span>
                            <span class="info-box-number"><?= $total_blm_siap ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ALERT jika ada yang belum dikonfirmasi -->
            <?php if ($total_pending > 0) : ?>
            <?php endif; ?>

            <?php if ($total_blm_siap > 0) : ?>
            <div class="alert alert-danger alert-dismissible fade show py-2 mb-3">
                <i class="fas fa-times-circle mr-1"></i>
                Terdapat <strong><?= $total_blm_siap ?> DO</strong> yang ditandai <strong>Belum Siap Loading</strong> dan perlu ditinjau ulang.
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
            <?php endif; ?>
        </div>
        
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header py-2">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="card-title mb-0">
                                    <i class="fas fa-list mr-1"></i> Daftar Delivery Order
                                </h3>
                            </div>
                            <div class="col-auto">
                                <select id="filterKonfirmasi" class="form-control form-control-sm" style="min-width:180px;">
                                    <option value="">— Semua Status —</option>
                                    <option value="Menunggu Konfirmasi">Menunggu Konfirmasi</option>
                                    <option value="Siap Loading">Siap Loading</option>
                                    <option value="Belum Siap Loading">Belum Siap Loading</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tbListDoSales" class="table table-bordered table-striped">
                            <thead style="background-color:#212529;color:white;">
                                <tr>
                                    <th>Kode DO</th>
                                    <th>Tgl. Buat</th>
                                    <th>Rute</th>
                                    <th>Total Faktur</th>
                                    <th>Total Barang</th>
                                    <th>Status Konfirmasi</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($listdo as $i) :
                                    $confirm = $i->sales_confirm_status ?? 'pending';

                                    // Badge status
                                    if ($confirm === 'siap') {
                                        $badge = '<span style="display:inline-flex;align-items:center;font-size:11px;font-weight:700;color:#0a3d1f;background:#c3e6cb;padding:3px 10px;border-radius:20px;border:1px solid #82c99a;">
                                                    <span style="width:7px;height:7px;border-radius:50%;background:#1e7e34;display:inline-block;margin-right:5px;flex-shrink:0;"></span>Siap Loading
                                                </span>';
                                    } elseif ($confirm === 'belum_siap') {
                                        $badge = '<span style="display:inline-flex;align-items:center;font-size:11px;font-weight:700;color:#5c0a0a;background:#f5c6cb;padding:3px 10px;border-radius:20px;border:1px solid #e8909a;">
                                                    <span style="width:7px;height:7px;border-radius:50%;background:#c62828;display:inline-block;margin-right:5px;flex-shrink:0;"></span>Belum Siap
                                                </span>';
                                    } else {
                                        $badge = '<span style="display:inline-flex;align-items:center;font-size:11px;font-weight:700;color:#533400;background:#fde7aa;padding:3px 10px;border-radius:20px;border:1px solid #f5c76a;">
                                                    <span style="width:7px;height:7px;border-radius:50%;background:#d4820a;display:inline-block;margin-right:5px;flex-shrink:0;"></span>Menunggu
                                                </span>';
                                    }

                                    $icon_eye = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
                                    ?>
                                    <tr>
                                        <td><?= $i->kddo ?></td>
                                        <td><?= $i->createat ?></td>
                                        <td><?= $i->rute ?></td>
                                        <td class="text-center"><?= $i->totalfaktur ?></td>
                                        <td class="text-center"><?= $i->totalbarang ?></td>
                                        <td><?= $badge ?></td>
                                        <td class="text-center">
                                            <a href="<?= base_url('sales_order/detail_do/') . $i->kddo ?>"
                                            style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;background:#e8f4fd;color:#1a73e8;border-radius:8px;border:1px solid #b8d9f8;text-decoration:none;transition:all .15s ease;"
                                            aria-label="Detail DO"
                                            title="Lihat Detail">
                                                <?= $icon_eye ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <strong>Copyright &copy; 2022 <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.</strong>
    </footer>
</div>

<script>
$(document).ready(function () {
    var table = $('#tbListDoSales').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[1, 'desc']],
        language: {
            search:      "Cari:",
            lengthMenu:  "Tampilkan _MENU_ data",
            info:        "Menampilkan _START_–_END_ dari _TOTAL_ data",
            zeroRecords: "Tidak ada data ditemukan",
            paginate: {
                next:     "Berikutnya",
                previous: "Sebelumnya"
            }
        },
        columnDefs: [{ orderable: false, targets: -1 }]
    });

    // Filter by kolom Status Konfirmasi (kolom index 5)
    $('#filterKonfirmasi').on('change', function () {
        table.column(5).search($(this).val()).draw();
    });
});
function konfirmDO(kd_do, action) {
    var label = action === 'siap' ? 'Siap Loading' : 'Belum Siap Loading';
    if (!confirm('Konfirmasi: ' + label + ' untuk DO ' + kd_do + '?')) return;

    var note = '';
    if (action === 'belum_siap') {
        note = prompt('Catatan (opsional):') || '';
    }

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
}
</script>