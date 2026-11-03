<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="content-header">
                <section class="content">

                    <div class="card">
                        <div class="card-body">
                            <div class="container-fluid">
                                <?php if ($this->session->userdata('jobdesk') == 'ADMINLOGLPB') : ?>
                                    <div class="row">
                                        <div class="col-auto">
                                            <a class="btn btn-primary mb-3" href="<?= base_url('ics/icspo') ?>">
                                                <i class="fas fa-home"></i>
                                            </a>
                                        </div>
                                        <div class="col-auto">
                                            <a class="btn btn-secondary mb-3 " href="<?= base_url('ics/retur/penjualan') ?>">
                                                <i class="fas fa-file-csv"></i> Retur
                                            </a>
                                        </div>
                                        <div class="col-auto">
                                            <a class="btn btn-success mb-3 " href="<?= base_url('ics/retur/penjualan') ?>">
                                                <i class="fas fa-file-csv"></i> Retur Penjualan
                                            </a>
                                        </div>
                                        <div class="col-auto">
                                            <a class="btn btn-success mb-3 " href="<?= base_url('ics/retur/pembelian') ?>">
                                                <i class="fas fa-file-csv"></i> Retur Pembelian
                                            </a>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="row">
                                        <div class="col-auto">
                                            <a class="btn btn-primary mb-3" href="<?= base_url('ics/icspo') ?>">
                                                <i class="fas fa-home"></i>
                                            </a>
                                        </div>
                                        <div class="col-auto">
                                            <a class="btn btn-secondary mb-3 " href="<?= base_url('ics/retur/penjualan') ?>">
                                                <i class="fas fa-file-csv"></i> Retur
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <table class="table table-bordered" id="tb_retur_all">
                                    <thead class="bg-primary text-white text-center">
                                        <tr>
                                            <th>Tanggal Retur</th>
                                            <th>Retur</th>
                                            <th>Note Retur</th>
                                            <th class="text-small text-nowrap">Total Barang</th>
                                            <th class="text-small text-nowrap">Status</th>
                                            <th class="text-small text-nowrap">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($retur_all)) : ?>
                                            <?php foreach ($retur_all as $row) : ?>
                                                <tr>
                                                    <td>
                                                        <?php
                                                        if ($row->input_at) {
                                                            $ts = strtotime($row->input_at);
                                                            $hari = [
                                                                'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
                                                            ];
                                                            $bulan = [
                                                                1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                                                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                                                            ];
                                                            $hariNama = $hari[(int)date('w', $ts)];
                                                            $bulanNama = $bulan[(int)date('n', $ts)];
                                                            echo $hariNama . ', ' . date('d', $ts) . ' ' . $bulanNama . ' ' . date('Y', $ts);
                                                        }
                                                        ?>
                                                    </td>
                                                    <?php if ($this->session->userdata('jobdesk') == 'ADMINLOGLPB') : ?>
                                                        <td>
                                                            <?php if ((int)$row->type_retur === 1) : ?>
                                                                <a class="btn btn-sm btn-info" href="<?= base_url('ics/retur/pembelian') ?>" title="Retur Pembelian">
                                                                    <i class="fas fa-truck-loading"></i> Retur Pembelian
                                                                </a>
                                                            <?php elseif ((int)$row->type_retur === 2) : ?>
                                                                <a class="btn btn-sm btn-success" href="<?= base_url('ics/retur/penjualan') ?>" title="Retur Penjualan">
                                                                    <i class="fas fa-shopping-cart"></i> Retur Penjualan
                                                                </a>
                                                            <?php else : ?>
                                                                <span class="btn btn-sm btn-secondary" title="Retur">
                                                                    <i class="fas fa-exchange-alt"></i>
                                                                </span>
                                                            <?php endif; ?>
                                                        </td>
                                                    <?php else : ?>
                                                        <td>
                                                            <?php if ((int)$row->type_retur === 1) : ?>
                                                                <a class="btn btn-sm btn-info" href="#" title="Retur Pembelian">
                                                                    <i class="fas fa-truck-loading"></i> Retur Pembelian
                                                                </a>
                                                            <?php elseif ((int)$row->type_retur === 2) : ?>
                                                                <a class="btn btn-sm btn-success" href="#" title="Retur Penjualan">
                                                                    <i class="fas fa-shopping-cart"></i> Retur Penjualan
                                                                </a>
                                                            <?php else : ?>
                                                                <span class="btn btn-sm btn-secondary" title="Retur">
                                                                    <i class="fas fa-exchange-alt"></i>
                                                                </span>
                                                            <?php endif; ?>
                                                        </td>
                                                    <?php endif; ?>

                                                    <td><?= $row->keterangan ?></td>
                                                    <td class="text-center"><?= (int)$row->total_barang ?></td>
                                                    <td class="text-center">
                                                        <?php if ((string)$row->status === '1') : ?>
                                                            <span class="badge badge-success">Done</span>
                                                        <?php elseif ((string)$row->status === '2') : ?>
                                                            <span class="badge badge-warning">Pending</span>
                                                        <?php else : ?>
                                                            <span class="badge badge-secondary"><?= $row->status ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <a class="btn btn-sm btn-primary" href="<?= base_url('ics/retur/detail_retur/' . $row->kd_retur) ?>">
                                                            Detail
                                                        </a>
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
        </div>

        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2022 <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0
            </div>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <script>
        $(function() {
            $('#tb_retur_all').DataTable({
                pageLength: 25,
                order: [
                    [0, 'desc']
                ],
                columnDefs: [{
                        targets: [3, 4, 5],
                        width: '1%',
                        className: 'text-center text-nowrap'
                    },
                    {
                        targets: [1],
                        width: '1%',
                        className: 'text-center text-nowrap'
                    }
                ]
            });
        });
    </script>