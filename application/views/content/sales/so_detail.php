<!-- views/content/sales/so_detail.php -->
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
                            <i class="fas fa-file-invoice mr-2"></i>
                            Detail Sales Order
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('sales_order') ?>">Sales Order</a></li>
                            <li class="breadcrumb-item active"><?= htmlspecialchars($so['no_so']) ?></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                <!-- FLASH MESSAGES -->
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle mr-1"></i>
                        <?= $this->session->flashdata('success') ?>
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
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <?= $this->session->flashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>

                <!-- TOMBOL ATAS -->
                <div class="row mb-3">
                    <div class="col-auto">
                        <a href="<?= base_url('sales_order') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                    <?php if ($so['status'] === 'draft'): ?>
                    <div class="col-auto">
                        <a href="<?= base_url('sales_order/edit/' . $so['id_so']) ?>" class="btn btn-warning">
                            <i class="fas fa-pencil-alt"></i> Edit SO
                        </a>
                    </div>
                    <?php endif; ?>
                    <?php if (!in_array($so['status'], ['completed', 'cancelled'])): ?>
                    <div class="col-auto">
                        <form method="post"
                              action="<?= base_url('sales_order/cancel/' . $so['id_so']) ?>"
                              onsubmit="return confirm('Yakin ingin membatalkan SO ini?')">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-ban"></i> Batalkan SO
                            </button>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- INFO HEADER -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white py-2">
                                <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Informasi SO</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <th class="pl-3" style="width:40%">No SO</th>
                                        <td><?= htmlspecialchars($so['no_so']) ?></td>
                                    </tr>
                                    <tr>
                                        <th class="pl-3">Tanggal</th>
                                        <td><?= date('d/m/Y', strtotime($so['tanggal_transaksi'])) ?></td>
                                    </tr>
                                    <tr>
                                        <th class="pl-3">Customer</th>
                                        <td><?= htmlspecialchars($so['customer_name']) ?></td>
                                    </tr>
                                    <tr>
                                        <th class="pl-3">Gudang</th>
                                        <td><?= htmlspecialchars($so['gudang_id']) ?></td>
                                    </tr>
                                    <tr>
                                        <th class="pl-3">Status</th>
                                        <td>
                                            <?php
                                            $badge = [
                                                'draft'             => 'secondary',
                                                'waiting_approval'  => 'warning',
                                                'approved'          => 'info',
                                                'partial_delivered' => 'primary',
                                                'completed'         => 'success',
                                                'cancelled'         => 'danger',
                                            ];
                                            $label = [
                                                'draft'             => 'Draft',
                                                'waiting_approval'  => 'Waiting Approval',
                                                'approved'          => 'Approved',
                                                'partial_delivered' => 'Partial Delivered',
                                                'completed'         => 'Completed',
                                                'cancelled'         => 'Cancelled',
                                            ];
                                            $b = $badge[$so['status']] ?? 'secondary';
                                            $l = $label[$so['status']] ?? $so['status'];
                                            ?>
                                            <span class="badge badge-<?= $b ?>"><?= $l ?></span>
                                        </td>
                                    </tr>
                                    <?php if (!empty($so['catatan'])): ?>
                                    <tr>
                                        <th class="pl-3">Catatan</th>
                                        <td><?= nl2br(htmlspecialchars($so['catatan'])) ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <th class="pl-3">Dibuat</th>
                                        <td><?= htmlspecialchars($so['create_by']) ?> — <?= $so['create_at'] ?></td>
                                    </tr>
                                    <?php if (!empty($so['update_by'])): ?>
                                    <tr>
                                        <th class="pl-3">Diupdate</th>
                                        <td><?= htmlspecialchars($so['update_by']) ?> — <?= $so['update_at'] ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-info text-white py-2">
                                <h3 class="card-title"><i class="fas fa-weight mr-1"></i> Tonase & Kubikasi</h3>
                            </div>
                            <div class="card-body">

                                <!-- Tonase -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span><b>Tonase:</b>
                                            <?= number_format($so['total_tonase'], 3) ?> kg
                                            <?php if ($so['batas_tonase']): ?>
                                                / <?= number_format($so['batas_tonase'], 3) ?> kg
                                            <?php endif; ?>
                                        </span>
                                        <?php if ($so['batas_tonase'] && $so['total_tonase'] > $so['batas_tonase']): ?>
                                            <span class="badge badge-danger">Melebihi!</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($so['batas_tonase'] > 0): ?>
                                    <div class="progress" style="height:12px">
                                        <?php $pct = min(($so['total_tonase'] / $so['batas_tonase']) * 100, 100); ?>
                                        <div class="progress-bar <?= $so['total_tonase'] > $so['batas_tonase'] ? 'bg-danger' : 'bg-success' ?>"
                                             style="width:<?= $pct ?>%"></div>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Kubikasi -->
                                <div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span><b>Kubikasi:</b>
                                            <?= number_format($so['total_kubikasi'], 5) ?> m³
                                            <?php if ($so['batas_kubikasi']): ?>
                                                / <?= number_format($so['batas_kubikasi'], 5) ?> m³
                                            <?php endif; ?>
                                        </span>
                                        <?php if ($so['batas_kubikasi'] && $so['total_kubikasi'] > $so['batas_kubikasi']): ?>
                                            <span class="badge badge-danger">Melebihi!</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($so['batas_kubikasi'] > 0): ?>
                                    <div class="progress" style="height:12px">
                                        <?php $pct = min(($so['total_kubikasi'] / $so['batas_kubikasi']) * 100, 100); ?>
                                        <div class="progress-bar <?= $so['total_kubikasi'] > $so['batas_kubikasi'] ? 'bg-danger' : 'bg-info' ?>"
                                             style="width:<?= $pct ?>%"></div>
                                    </div>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- DETAIL BARANG -->
                <div class="card">
                    <div class="card-header bg-success text-white py-2">
                        <h3 class="card-title">
                            <i class="fas fa-boxes mr-1"></i> Detail Barang
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm table-hover mb-0" id="tabelDetail">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama Barang</th>
                                        <th>Exp Date</th>
                                        <th>No Lot</th>
                                        <th class="text-right">Qty Order</th>
                                        <th class="text-right">Qty Terkirim</th>
                                        <th>Satuan</th>
                                        <th class="text-right">Harga Satuan</th>
                                        <th class="text-right">Pajak</th>
                                        <th class="text-right">Total</th>
                                        <th class="text-center">Ket</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $grand = 0;
                                    foreach ($details as $i => $d):
                                        $grand += $d['total_harga'];
                                    ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><small><?= htmlspecialchars($d['kd_barang']) ?></small></td>
                                        <td><?= htmlspecialchars($d['nama_barang']) ?></td>
                                        <td>
                                            <?= !empty($d['expired_date'])
                                                ? date('d/m/Y', strtotime($d['expired_date']))
                                                : '-' ?>
                                        </td>
                                        <td><?= htmlspecialchars($d['no_lot'] ?? '-') ?></td>
                                        <td class="text-right"><?= number_format($d['qty'], 2) ?></td>
                                        <td class="text-right"><?= number_format($d['qty_delivered'], 2) ?></td>
                                        <td><?= htmlspecialchars($d['satuan']) ?></td>
                                        <td class="text-right">
                                            Rp <?= number_format($d['hrg_satuan'], 2) ?>
                                            <?php if ($d['hrg_satuan'] < $d['hrg_pokok']): ?>
                                                <span class="badge badge-danger ml-1" title="Di bawah HPP">
                                                    <i class="fas fa-arrow-down"></i> HPP
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-right"><?= $d['pajak'] ?>%</td>
                                        <td class="text-right">Rp <?= number_format($d['total_harga'], 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light font-weight-bold">
                                        <td colspan="10" class="text-right">GRAND TOTAL</td>
                                        <td class="text-right">Rp <?= number_format($grand, 2) ?></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
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
</body>