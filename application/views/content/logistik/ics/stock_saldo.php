<style>
    .table-fixed-header thead th {
        position: sticky;
        top: 0;
        background-color: #343a40;
        /* warna sesuai thead-dark */
        color: white;
        z-index: 10;
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
                            <a href="<?= base_url('logistik/stock') ?>" class="btn btn-md btn-secondary w-100 mb-3"><i class="fas fa-box"></i>Stock Saldo</a>
                        </div>
                        <div class="col-auto">
                            <a href="<?= base_url('logistik/lpb') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-calendar"></i>LPB</a>
                        </div>
                    </div>

                    <div class="card">
                        <h3>Saldo Stock</h3>

                        <table border="1" cellpadding="6">
                            <tr>
                                <th>Barang</th>
                                <th>Gudang</th>
                                <th>Lot</th>
                                <th>Exp</th>
                                <th>Saldo</th>
                            </tr>

                            <?php foreach ($saldo as $s) : ?>
                                <tr>
                                    <td><?= $s->kode_barang_system ?></td>
                                    <td><?= $s->id_gudang ?></td>
                                    <td><?= $s->no_lot ?></td>
                                    <td><?= $s->exp_date ?></td>
                                    <td><?= $s->saldo_akhir ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
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