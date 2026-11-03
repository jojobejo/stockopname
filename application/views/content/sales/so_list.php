<!-- views/content/sales/so_list.php -->
<body class="hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">

    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="<?= base_url('assets/images/Karisma.png') ?>"
             alt="Logo" height="150" width="300">
    </div>

    <?php $this->load->view('partial/main/navbar') ?>
    <?php $this->load->view('partial/main/sidebar') ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">
                            <i class="fas fa-file-invoice mr-2"></i> Sales Order
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item active">Sales Order</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                <!-- FLASH MESSAGE -->
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle mr-1"></i>
                        <?= $this->session->flashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <?= $this->session->flashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('warning')): ?>
                    <div class="alert alert-warning alert-dismissible fade show">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        <?= $this->session->flashdata('warning') ?>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>

                <!-- TOMBOL AKSI ATAS -->
                <div class="row mb-2">
                    <div class="col-auto">
                        <a href="<?= base_url('sales_order/create') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Buat SO Baru
                        </a>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('sales_order/activity_log') ?>" class="btn btn-info">
                            <i class="fas fa-history"></i> Activity Log
                        </a>
                    </div>
                </div>

                <!-- FILTER -->
                <div class="card">
                    <div class="card-header bg-light py-2">
                        <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter</h3>
                    </div>
                    <div class="card-body py-2">
                        <form action="<?= base_url('sales_order') ?>" method="post">
                            <div class="row">
                                <div class="col-md-2">
                                    <input type="date" class="form-control" name="date1"
                                        value="<?= htmlspecialchars($filter['date1'] ?? '') ?>">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" class="form-control" name="date2"
                                        value="<?= htmlspecialchars($filter['date2'] ?? '') ?>">
                                </div>
                                <div class="col-md-3">
                                    <select name="customer_id" class="form-control">
                                        <option value="">-- Semua Customer --</option>
                                        <?php foreach ($customers as $c): ?>
                                            <option value="<?= $c['id'] ?>"
                                                <?= ($filter['customer_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($c['nama_customer']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="status" class="form-control">
                                        <option value="">-- Semua Status --</option>
                                        <?php
                                        $status_list = [
                                            'draft'             => 'Draft',
                                            'in_progress'       => 'On Progress',
                                            'done'              => 'Done',
                                            'partial_delivered' => 'Partial Delivered',
                                            'completed'         => 'Completed',
                                            'cancelled'         => 'Cancelled',
                                        ];
                                        foreach ($status_list as $val => $lbl):
                                        ?>
                                            <option value="<?= $val ?>"
                                                <?= ($filter['status'] ?? '') === $val ? 'selected' : '' ?>>
                                                <?= $lbl ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-success btn-block">
                                        <i class="fas fa-search"></i> Tampil
                                    </button>
                                </div>
                                <div class="col-md-1">
                                    <a href="<?= base_url('sales_order') ?>" class="btn btn-secondary btn-block">
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- TABEL DATA -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title">
                            <i class="fas fa-list mr-2"></i> Daftar Sales Order
                        </h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover" id="tabelSO">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No SO</th>
                                    <th>Tanggal</th>
                                    <th>Customer</th>
                                    <th class="text-right">Item</th>
                                    <th class="text-right">Tonase (kg)</th>
                                    <th class="text-right">Kubikasi (m³)</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($so_list)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">
                                            <i class="fas fa-inbox mr-1"></i> Tidak ada data Sales Order
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php
                                    $badge_status = [
                                        'draft'             => 'secondary',
                                        'in_progress'       => 'primary',   
                                        'done'              => 'success',   
                                        'partial_delivered' => 'primary',
                                        'completed'         => 'success',
                                        'cancelled'         => 'danger',
                                    ];

                                    $label_status = [
                                        'draft'             => 'Draft',
                                        'in_progress'       => 'On Progress',   
                                        'done'              => 'Done',          
                                        'partial_delivered' => 'Partial Delivered',
                                        'completed'         => 'Completed',
                                        'cancelled'         => 'Cancelled',
                                    ];
                                    foreach ($so_list as $row):
                                        $badge = $badge_status[$row['status']] ?? 'secondary';
                                        $label = $label_status[$row['status']] ?? $row['status'];
                                    ?>
                                    <tr>
                                        <td>
                                            <a href="<?= base_url('sales_order/detail/' . $row['id_so']) ?>">
                                                <?= htmlspecialchars($row['no_so']) ?>
                                            </a>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($row['tanggal_transaksi'])) ?></td>
                                        <td><?= htmlspecialchars($row['customer_name']) ?></td>
                                        <td class="text-right"><?= number_format($row['jumlah_item']) ?></td>
                                        <td class="text-right"><?= number_format($row['total_tonase'], 3) ?></td>
                                        <td class="text-right"><?= number_format($row['total_kubikasi'], 5) ?></td>
                                        <td class="text-center">
                                            <span class="badge badge-<?= $badge ?>">
                                                <?= $label ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= base_url('sales_order/detail/' . $row['id_so']) ?>"
                                               class="btn btn-xs btn-info" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($row['status'] === 'draft'): ?>
                                            <a href="<?= base_url('sales_order/edit/' . $row['id_so']) ?>"
                                               class="btn btn-xs btn-warning" title="Edit">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </section>
    </div>

    <footer class="main-footer">
        <strong>Copyright &copy; 2022 <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block"><b>Version</b> 1.0</div>
    </footer>
    <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<script>
$(document).ready(function () {
    $('#tabelSO').DataTable({
        responsive:  true,
        autoWidth:   false,
        pageLength:  25,
        order:       [[0, 'desc']],
        columnDefs:  [{ orderable: false, targets: -1 }],
        language: {
            search:      "Cari:",
            lengthMenu:  "Tampilkan _MENU_ data",
            info:        "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            zeroRecords: "Tidak ada data ditemukan",
            emptyTable:  "Tidak ada data Sales Order",
            paginate: {
                first:    "Pertama",
                last:     "Terakhir",
                next:     "Berikutnya",
                previous: "Sebelumnya"
            }
        }
    });
});
</script>
</body>