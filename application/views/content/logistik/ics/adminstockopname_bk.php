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

                    <div class="row">
                        <div class="col-auto">
                            <a href="<?= base_url('trackingtim/1') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-tasks"></i> Tracking Stock TIM 1</a>
                        </div>
                        <div class="col-auto">
                            <a href="<?= base_url('trackingtim/2') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-tasks"></i> Tracking Stock TIM 2</a>
                        </div>
                        <div class="col-auto">
                            <a href="<?= base_url('stockopname') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-tasks"></i> Stock Opname</a>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="container-fluid">
                                <h4>Dashboard Stock Opname</h4>
                                <h5 class="mt-2 mb-2">Compare Tim - FEFO</h5>
                                <table class="table table-bordered table-sm" id="tb_dash_fefo">
                                    <thead>
                                        <tr>
                                            <th>Nama Barang</th>
                                            <th>Expired Date</th>
                                            <th>TIM 1</th>
                                            <th>TIM 2</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($fefo as $row) : ?>
                                            <tr>
                                                <td><?= $row->nm_barang ?></td>
                                                <td><?= $row->exp_date ?></td>
                                                <td><?= $row->qty_fisik_tim1 ?></td>
                                                <td><?= $row->qty_fisik_tim2 ?></td>
                                                <td><span class="badge <?= $row->status == 'MATCH' ? 'bg-success' : 'bg-danger' ?>">
                                                        <?= $row->status ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mt-2">Compare Tim - AllBarang</h5>
                            <table class="table table-bordered table-sm" id="tb_dash_allbarang">

                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>TIM 1</th>
                                        <th>TIM 2</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($allbarang as $row) : ?>
                                        <tr>
                                            <td><?= $row->nm_barang ?></td>
                                            <td><?= $row->qty_fisik_tim1 ?></td>
                                            <td><?= $row->qty_fisik_tim2 ?></td>
                                            <td>
                                                <span class="badge <?= $row->status == 'MATCH' ? 'bg-success' : 'bg-danger' ?>">
                                                    <?= $row->status ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
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
        // FEFO Pie Chart
        var fefoPieCtx = document.getElementById('fefoPieChart').getContext('2d');
        new Chart(fefoPieCtx, {
            type: 'pie',
            data: {
                labels: ['MATCH', 'NOT MATCH'],
                datasets: [{
                    label: 'Statistik FEFO',
                    data: [<?= $stat_fefo['match'] ?>, <?= $stat_fefo['not_match'] ?>],
                    backgroundColor: ['#28a745', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // All Barang Pie Chart
        var allBarangPieCtx = document.getElementById('allBarangPieChart').getContext('2d');
        new Chart(allBarangPieCtx, {
            type: 'pie',
            data: {
                labels: [
                    <?php foreach ($allbarang as $row) : ?> "<?= $row->nama_barang ?>",
                    <?php endforeach ?>
                ],
                datasets: [{
                    label: 'Qty Fisik',
                    data: [
                        <?php foreach ($allbarang as $row) : ?>
                            <?= $row->qty_fisik ?>,
                        <?php endforeach ?>
                    ],
                    backgroundColor: [
                        '#007bff', '#28a745', '#ffc107', '#dc3545', '#6610f2',
                        '#20c997', '#e83e8c', '#fd7e14', '#6c757d', '#17a2b8'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>