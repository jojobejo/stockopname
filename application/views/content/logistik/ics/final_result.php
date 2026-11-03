<style>
    .small-chart {
        width: 50% !important;
        height: auto;
        margin: auto;
        display: block;
    }
</style>

<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>

        <div class="content-wrapper">
            <div class="content-header">
                <section class="content">

                    <div class="row">
                        <div class="col-auto">
                            <a href="<?= base_url('data_final_input_opname') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-book-open"></i> Data Final Input</a>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h3 style="text-align: center;">FINAL STOCK OPNAME RESULT 2025</h3>
                            <div class="row mt-5">
                                <div class="col-md-6">
                                    <h3 style="text-align: center;">FINAL ALL BARANG</h3>
                                    <canvas id="pieChartTim1" class="small-chart"></canvas>
                                    <div class="text-center">
                                        <span class="mx-2">All Barang : <?= $stat_t1['total_barang'] ?></span>
                                        <span class="mx-2">Total Match : <?= $stat_t1['total_match'] ?></span>
                                        <span class="mx-2">Total Not : <?= $stat_t1['total_notmatch'] ?></span>
                                    </div>
                                    <div class="text-center">
                                        <span class="mx-2">Persentase Match : <?= number_format($stat_t1['persen_match'])  ?> %</span>
                                    </div>
                                    <div class="text-center">
                                        <span class="mx-2">Persentase NotMatch : <?= number_format($stat_t1['persen_notmatch']) ?> %</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h3 style="text-align: center;">FINAL FEFO</h3>
                                    <canvas id="pieChartTim1exp" class="small-chart"></canvas>
                                    <div class="text-center">
                                        <span class="mx-2">All Barang : <?= $statexp_t1['total_barang'] ?></span>
                                        <span class="mx-2">Total Match : <?= $statexp_t1['total_match'] ?></span>
                                        <span class="mx-2">Total Not : <?= $statexp_t1['total_notmatch'] ?></span>
                                    </div>
                                    <div class="text-center">
                                        <span class="mx-2">Persentase Match : <?= number_format($statexp_t1['persen_match']) ?> %</span>
                                    </div>
                                    <div class="text-center">
                                        <span class="mx-2">Persentase NotMatch : <?= number_format($statexp_t1['persen_notmatch']) ?> %</span>
                                    </div>
                                </div>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Pie Chart TIM 1
        const dataTim1 = {
            labels: ['MATCH', 'NOT MATCH'],
            datasets: [{
                data: [<?= $stat_t1['persen_match'] ?>, <?= $stat_t1['persen_notmatch'] ?>],
                backgroundColor: ['#3DAF57', '#dc3545'],
            }]
        };

        const configTim1 = {
            type: 'pie',
            data: dataTim1,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        };

        new Chart(document.getElementById('pieChartTim1'), configTim1);

        // BY EXPIRED DATE
        const dataTim1exp = {
            labels: ['MATCH', 'NOT MATCH'],
            datasets: [{
                data: [<?= $statexp_t1['persen_match'] ?>, <?= $statexp_t1['persen_notmatch'] ?>],
                backgroundColor: ['#3DAF57', '#dc3545'],
            }]
        };

        const configTim1exp = {
            type: 'pie',
            data: dataTim1exp,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        };
        new Chart(document.getElementById('pieChartTim1exp'), configTim1exp);
    </script>