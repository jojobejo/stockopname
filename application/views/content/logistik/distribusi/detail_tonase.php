<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-auto">
                            <a href="<?= base_url('logistik/distibusi') ?>" class="btn btn-secondary mb-2">
                                <i class="fas fa-arrow-left"></i> Kembali ke Distribusi
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Tonase Rute: <strong><?= htmlspecialchars($rute, ENT_QUOTES, 'UTF-8') ?></strong></h3>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="tab_detail_tonase" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tab-terkirim-link" data-toggle="tab" href="#tab_terkirim" role="tab" aria-controls="tab_terkirim" aria-selected="true">
                                    Faktur Terkirim <span class="badge badge-success ml-1" id="count_terkirim">0</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-pending-link" data-toggle="tab" href="#tab_pending" role="tab" aria-controls="tab_pending" aria-selected="false">
                                    Faktur Pending / Belum Terkirim <span class="badge badge-warning ml-1" id="count_pending">0</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content pt-3">
                            <div class="tab-pane fade show active" id="tab_terkirim" role="tabpanel" aria-labelledby="tab-terkirim-link">
                                <table class="table table-bordered table-striped" id="tbl_tonase_terkirim">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Faktur</th>
                                            <th>Kode Customer</th>
                                            <th>Total Barang</th>
                                            <th>Total Tonase</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="tab_pending" role="tabpanel" aria-labelledby="tab-pending-link">
                                <table class="table table-bordered table-striped" id="tbl_tonase_pending">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Faktur</th>
                                            <th>Kode Customer</th>
                                            <th>Total Barang</th>
                                            <th>Total Tonase</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
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
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0
            </div>
        </footer>

        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>
</body>
