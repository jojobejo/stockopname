<!-- views/content/logistik/body.php -->
<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>

        <div class="content-wrapper">
            <?php $this->load->view('content/logistik/modal/modal_do_upload') ?>

            <?php if ($this->session->userdata('jobdesk') == 'LOGISTIK') : ?>

                <div class="content-header">
                    <div class="container-fluid">

                        <!-- ✅ BREADCRUMB -->
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark" style="font-size:1.3rem;">Delivery Order</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="<?= base_url('logistik') ?>">Home</a></li>
                                    <li class="breadcrumb-item"><a href="<?= base_url('logistik') ?>">Logistik</a></li>
                                    <li class="breadcrumb-item active">Delivery Order</li>
                                </ol>
                            </div>
                        </div>

                        <!-- ✅ ACTION BUTTONS -->
                        <div class="row">
                            <div class="col-auto">
                                <a href="#" class="btn btn-primary mb-2" data-toggle="modal" data-target="#muploadlog">
                                    <i class="fas fa-upload mr-1"></i>Update Data DO
                                </a>
                            </div>
                            <div class="col-auto">
                                <a href="https://10.10.10.12/zahirdigital/keuangan/export_do.php" class="btn btn-info mb-2">
                                    <i class="fas fa-sync mr-1"></i>Ambil Data Penjualan (TODAY)
                                </a>
                            </div>
                            <div class="col-auto">
                                <a href="#" class="btn btn-primary mb-2" data-toggle="modal" data-target="#updatecs">
                                    <i class="fas fa-user-edit mr-1"></i>Update Customer
                                </a>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('faktur_on_site') ?>" class="btn btn-success mb-2">
                                    <i class="fas fa-store mr-1"></i>Faktur Cash / On Site
                                </a>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('master_barang') ?>" class="btn btn-info mb-2">
                                    <i class="fas fa-box mr-1"></i>Master Barang
                                </a>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('master_customer') ?>" class="btn btn-primary mb-2">
                                    <i class="fas fa-users mr-1"></i>Master Customer
                                </a>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('create_do') ?>" class="btn btn-success mb-2">
                                    <i class="fas fa-plus mr-1"></i>Add Delivery Order
                                </a>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('faktur_bintang') ?>" class="btn btn-info mb-2">
                                    <i class="fas fa-star mr-1"></i>Faktur Bintang
                                </a>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('view_faktur_not_list') ?>" class="btn btn-warning mb-2">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Faktur Barang Belum Terdaftar
                                </a>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('tonase_report') ?>" class="btn btn-primary mb-2">
                                    <i class="fas fa-weight mr-1"></i>Tonase Rekap
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">

                        <?php if ($this->session->flashdata('msg')) : ?>
                            <div class="alert alert-info alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <?= $this->session->flashdata('msg'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-truck mr-2"></i>Delivery Order List
                                </h3>
                            </div>
                            <div class="card-body">

                                <!-- ✅ FILTER ROW -->
                                <div class="row mb-3">
                                    <div class="col-md-2 col-sm-6 mb-2">
                                        <label class="text-muted" style="font-size:12px; font-weight:600;">Filter Status</label>
                                        <select id="filterStatus" class="form-control form-control-sm">
                                            <option value="">— Semua Status —</option>
                                            <option value="1">Draft</option>
                                            <option value="2_pending">Menunggu Konfirmasi</option>
                                            <option value="2_belum_siap">Belum Siap Loading</option>
                                            <option value="3">On Delivery</option>
                                            <option value="4">On Site</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-6 mb-2">
                                        <label class="text-muted" style="font-size:12px; font-weight:600;">Filter Rute</label>
                                        <select id="filterRute" class="form-control form-control-sm">
                                            <option value="">— Semua Rute —</option>
                                            <?php
                                            $ruteList = array_unique(array_column((array)$listdo, 'rute'));
                                            foreach ($ruteList as $rute): ?>
                                                <option value="<?= htmlspecialchars($rute) ?>"><?= htmlspecialchars($rute) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-6 mb-2">
                                        <label class="text-muted" style="font-size:12px; font-weight:600;">Dari Tanggal</label>
                                        <input type="date" id="filterDateFrom" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-2 col-sm-6 mb-2">
                                        <label class="text-muted" style="font-size:12px; font-weight:600;">Sampai Tanggal</label>
                                        <input type="date" id="filterDateTo" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-2 col-sm-6 mb-2 d-flex align-items-end">
                                        <button class="btn btn-sm btn-info mr-1" onclick="applyDOFilter()">
                                            <i class="fas fa-search mr-1"></i>Cari
                                        </button>
                                        <button class="btn btn-sm btn-secondary" onclick="resetDOFilter()">
                                            <i class="fas fa-undo mr-1"></i>Reset
                                        </button>
                                    </div>
                                </div>

                                <!-- ✅ DATATABLE -->
                                <table id="tbDashboardLogistik" class="table table-bordered table-striped table-hover table-sm">
                                    <thead style="background-color: #212529; color:white;">
                                        <tr>
                                            <th style="width:40px">No</th>
                                            <th>Kode DO</th>
                                            <th>Tgl. Buat</th>
                                            <th>Rute</th>
                                            <th class="text-center">Total Faktur</th>
                                            <th class="text-center">Total Barang</th>
                                            <th>Status</th>
                                            <th style="width:80px">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($listdo as $i) :
                                            $status  = $i->status;
                                            $confirm = $i->sales_confirm_status ?? null;

                                            if ($status == '1') {
                                                $datasts     = '<span class="badge badge-warning">Draft</span>';
                                                $statusCode  = '1';
                                                $confirmCode = '';
                                            } elseif ($status == '2') {
                                                if ($confirm === 'pending' || $confirm === null) {
                                                    $datasts    = '<span class="badge badge-info">Menunggu Konfirmasi</span>
                                                                   <br><small class="text-danger"><i class="fas fa-exclamation-circle"></i> Sales belum konfirmasi</small>';
                                                    $statusCode  = '2';
                                                    $confirmCode = 'pending';
                                                } elseif ($confirm === 'belum_siap') {
                                                    $datasts    = '<span class="badge badge-danger">Belum Siap Loading</span>
                                                                   <br><small class="text-muted">Dikonfirmasi oleh: ' . htmlspecialchars($i->sales_confirm_by ?? '-') . '</small>';
                                                    $statusCode  = '2';
                                                    $confirmCode = 'belum_siap';
                                                } else {
                                                    $datasts     = '<span class="badge badge-info">Menunggu Konfirmasi</span>';
                                                    $statusCode  = '2';
                                                    $confirmCode = 'pending';
                                                }
                                            } elseif ($status == '3') {
                                                $datasts     = '<span class="badge badge-success">On Delivery</span>';
                                                $statusCode  = '3';
                                                $confirmCode = '';
                                            } elseif ($status == '4') {
                                                $datasts     = '<span class="badge badge-success">On Site</span>';
                                                $statusCode  = '4';
                                                $confirmCode = '';
                                            }
                                        ?>
                                            <tr
                                                data-status="<?= $statusCode ?>"
                                                data-confirm="<?= $confirmCode ?>"
                                                data-rute="<?= htmlspecialchars($i->rute) ?>"
                                                data-tgl="<?= date('Y-m-d', strtotime(str_replace('/', '-', $i->createat))) ?>">
                                                <td class="text-center rownum"></td>
                                                <td><strong><?= $i->kddo ?></strong></td>
                                                <td><?= $i->createat ?></td>
                                                <td><?= $i->rute ?></td>
                                                <td class="text-center"><?= $i->totalfaktur ?></td>
                                                <td class="text-center"><?= $i->totalbarang ?></td>
                                                <td><?= $datasts ?></td>
                                                <?php if ($i->status == '1') : ?>
                                                    <td>
                                                        <a href="<?= base_url('detail_do/') . $i->kddo ?>" class="btn btn-sm btn-info btn-block">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                <?php elseif ($i->status == '2') : ?>
                                                    <td>
                                                        <a href="<?= base_url('detail_do/') . $i->kddo ?>" class="btn btn-sm btn-info btn-block">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                <?php elseif ($i->status == '3') : ?>
                                                    <td>
                                                        <div class="row no-gutters">
                                                            <div class="col pr-1">
                                                                <a href="<?= base_url('detail_do/') . $i->kddo ?>" class="btn btn-sm btn-info btn-block">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            </div>
                                                            <div class="col">
                                                                <a href="<?= base_url('printdo/') . $i->kddo ?>" class="btn btn-sm btn-success btn-block">
                                                                    <i class="fas fa-print"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                <?php elseif ($i->status == '4') : ?>
                                                    <td>
                                                        <a href="<?= base_url('detail_do/') . $i->kddo ?>" class="btn btn-sm btn-info btn-block">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>

                    </div>
                </section>

            <?php elseif ($this->session->userdata('jobdesk') == 'ADMINICS') : ?>
                <!-- ... (isi ADMINICS tidak berubah) -->
            <?php elseif ($this->session->userdata('jobdesk') == 'STOCKOPNAME') : ?>
            <?php endif; ?>
        </div>

        <footer class="main-footer">
            <strong>Copyright &copy; 2022 <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0
            </div>
        </footer>

        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>

    <!-- ✅ SCRIPT DATATABLES + FILTER -->
    <script>
    $(document).ready(function () {

        var table = $('#tbDashboardLogistik').DataTable({
            responsive: true,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            columnDefs: [
                { orderable: false, targets: [0, 7] },
                { className: 'text-center', targets: [0, 4, 5, 7] }
            ],
            order: [[2, 'desc']],
            drawCallback: function () {
                // nomor urut manual
                var api = this.api();
                api.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }
        });

        // Filter tombol Cari
        window.applyDOFilter = function () {
            var sts       = $('#filterStatus').val();
            var rute      = $('#filterRute').val();
            var dateFrom  = $('#filterDateFrom').val();
            var dateTo    = $('#filterDateTo').val();

            $.fn.dataTable.ext.search = [];

            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                var row         = table.row(dataIndex).node();
                var rowStatus   = $(row).data('status')  ? String($(row).data('status'))  : '';
                var rowConfirm  = $(row).data('confirm') ? String($(row).data('confirm')) : '';
                var rowRute     = $(row).data('rute')    ? String($(row).data('rute'))    : '';
                var rowTgl      = $(row).data('tgl')     ? String($(row).data('tgl'))     : '';

                // Filter status
                if (sts) {
                    if (sts === '2_pending'    && !(rowStatus === '2' && rowConfirm === 'pending'))    return false;
                    if (sts === '2_belum_siap' && !(rowStatus === '2' && rowConfirm === 'belum_siap')) return false;
                    if (sts === '1' && rowStatus !== '1') return false;
                    if (sts === '3' && rowStatus !== '3') return false;
                    if (sts === '4' && rowStatus !== '4') return false;
                }

                // Filter rute
                if (rute && rowRute !== rute) return false;

                // Filter tanggal
                if (dateFrom && rowTgl < dateFrom) return false;
                if (dateTo   && rowTgl > dateTo)   return false;

                return true;
            });

            table.draw();
        };

        window.resetDOFilter = function () {
            $('#filterStatus').val('');
            $('#filterRute').val('');
            $('#filterDateFrom').val('');
            $('#filterDateTo').val('');
            $.fn.dataTable.ext.search = [];
            table.search('').draw();
        };

        // Enter di search DataTables sudah built-in
    });
    </script>