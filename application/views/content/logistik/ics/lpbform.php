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
                        <form method="post" action="<?= site_url('stock/lpb/store') ?>">
                            <p>Kode Faktur<br>
                                <input type="text" name="kode_faktur">
                            </p>

                            <p>Gudang ID<br>
                                <input type="number" name="id_gudang">
                            </p>

                            <p>Kode Barang<br>
                                <input type="text" name="kode_barang_system">
                            </p>

                            <p>No Lot<br>
                                <input type="text" name="no_lot">
                            </p>

                            <p>Exp Date<br>
                                <input type="date" name="exp_date">
                            </p>

                            <p>Qty<br>
                                <input type="number" name="qty">
                            </p>

                            <button type="submit">Simpan</button>
                        </form>
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