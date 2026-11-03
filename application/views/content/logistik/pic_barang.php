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
                <?php if ($this->session->userdata('jobdesk') == 'ADMINICS') : ?>
                    <section class="content">
                        <?php

                        $lv     = $this->session->userdata('lv');
                        $tim    = $this->session->userdata('tim');

                        $pic = $this->session->userdata('nama');

                        if ($lv == 1) {
                            $akses = 'admin';
                        } elseif ($lv == 2) {
                            $akses = 'ics';
                        }
                        ?>
                        <div class="row">
                            <?php if ($akses == 'admin') : ?>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/by_allbarang') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-box"></i> Data All Barang</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/by_expdate') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-calendar"></i> Data By Expired Date</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/icsdo') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-minus-circle"></i> Data DO</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/icspo') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-plus-circle"></i> Data PO</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/barangpic') ?>" class="btn btn-md btn-secondary w-100 mb-3"><i class="fas fa-eye"></i> Master Barang PIC</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/export_opname') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-file-export"></i> Export Result </a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/ics_diffrent') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-eye"></i> Show Diffrent</a>
                                </div>

                            <?php elseif ($akses == 'ics') : ?>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/by_allbarang_ics/') . $tim ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-box"></i> Data All Barang</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/by_expdate_ics/') . $tim ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-calendar"></i> Data By Expired Date</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/icsdo') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-minus-circle"></i> Data DO</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/icspo') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-plus-circle"></i> Data PO</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/ics_diffrent_ics/') . $tim ?>" class="btn btn-md btn-secondary w-100 mb-3"><i class="fas fa-eye"></i> Show Diffrent</a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="row">

                            <div class="col-8">
                                <table class="table table-bordered table-hover table-sm table-fixed-header" id="tbics_erp">
                                    <thead>
                                        <tr>
                                            <th>Nama Barang</th>
                                            <th>Expired Date</th>
                                            <th>PIC</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($piclist as $p) : ?>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-4">

                                <div class="card">
                                    <table class="table table-bordered table-hover table-sm table-fixed-header">
                                        <thead>
                                            <tr>
                                                <th>PIC</th>
                                                <th>Jumlah Barang</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>

                    </section>
                <?php endif; ?>
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