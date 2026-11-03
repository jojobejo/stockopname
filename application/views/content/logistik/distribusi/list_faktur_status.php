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
                    <?php if ($this->session->userdata('jobdesk') == 'ADMINICS') : ?>
                        <div class="row">
                            <div class="col-auto">
                                <a href="<?= base_url('ics/ics_diffrent') ?>" class="btn btn-secondary mb-2">
                                    <i class="fas fa-arrow-left"></i> Dashboard
                                </a>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="row">
                            <div class="col-auto">
                                <a href="<?= base_url('logistik/distibusi') ?>" class="btn btn-secondary mb-2">
                                    <i class="fas fa-arrow-left"></i> Kembali ke Distribusi
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <section class="content">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Status Faktur</label>
                                <select id="filter_status_faktur" class="form-control">
                                    <option value="1">Belum Terkirim</option>
                                    <option value="3">Terkirim</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" id="btn_reload_status_faktur" class="btn btn-primary btn-block">
                                    <i class="fas fa-sync-alt"></i> Muat Ulang
                                </button>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <a href="<?= base_url('logistik/distibusi/list_do_status_2') ?>" class="btn btn-info btn-block">
                                    <i class="fas fa-truck"></i> List DO Done
                                </a>
                            </div>
                        </div>
                        <hr>

                        <table class="table table-bordered table-striped" id="tbl_faktur_status">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Input</th>
                                    <th>Kode Faktur</th>
                                    <th>Kode DO</th>
                                    <th>Kode Customer</th>
                                    <th>Kios</th>
                                    <th>Rute</th>
                                    <th>Nama Barang</th>
                                    <th>Qty</th>
                                    <th>Tgl Exp</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
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
