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
                <?php if ($this->session->userdata('jobdesk') == 'ADMINICS') :
                    $pic = $this->session->userdata('nama');
                ?>
                    <section class="content">
                        <div class="row">
                            <?php if ($pic == 'Admin ICS') : ?>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/by_allbarang') ?>" class="btn btn-md btn-secondary w-100 mb-3"><i class="fas fa-boxes"></i> By All Barang</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/by_expdate') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-calendar"></i> By Expired Date</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/icsdo') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-minus-circle"></i> Data DO</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/icspo') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-plus-circle"></i> Data PO</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/ics_diffrent') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-eye"></i> Show Diffrent</a>
                                </div>
                            <?php else : ?>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/by_allbarang_ics/') . $tim ?>" class="btn btn-md btn-secondary w-100 mb-3"><i class="fas fa-box"></i> Data All Barang</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/by_expdate_ics/') . $tim ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-calendar"></i> Data By Expired Date</a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/ics_diffrent') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-eye"></i> Show Diffrent</a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="container-fluid">
                                    <table class="table table-bordered table-striped" id="tbics_byallbarang">
                                        <thead>
                                            <tr>
                                                <!-- <th rowspan="2" class="align-middle bg-info text-white text-center">#</th> -->
                                                <th rowspan="2" class="align-middle bg-primary text-white text-center">Nama Barang</th>
                                                <th colspan="2" class="bg-info text-white text-center">Saldo Awal</th>
                                                <th colspan="2" class="bg-success text-white text-center">LPB</th>
                                                <th colspan="2" class="bg-danger text-white text-center">DO</th>
                                                <th colspan="2" class="bg-info text-white text-center">Sistem</th>
                                                <th colspan="2" class="bg-success text-white text-center">Fisik</th>
                                                <th rowspan="2" class="align-middle bg-info text-white text-center">Selisih</th>
                                                <th rowspan="2" class="align-middle bg-success text-white text-center">Status</th>
                                                <!-- <th rowspan="2" class="align-middle bg-info text-white text-center">#</th> -->
                                            </tr>
                                            <tr>
                                                <th class="bg-info text-white">Box</th>
                                                <th class="bg-info text-white">Pcs</th>
                                                <th class="bg-success text-white">Box</th>
                                                <th class="bg-success text-white">Pcs</th>
                                                <th class="bg-danger text-white">Box</th>
                                                <th class="bg-danger text-white">Pcs</th>
                                                <th class="bg-info text-white">Box</th>
                                                <th class="bg-info text-white">Pcs</th>
                                                <th class="bg-success text-white">Box</th>
                                                <th class="bg-success text-white">Pcs</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($barang_ics as $br) : ?>
                                                <tr>
                                                    <!-- <td>
                                                        <a href="<?= base_url('ics/ics_stock_controller/' . $br->id)  ?>" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                                    </td> -->
                                                    <td><?= $br->nama_barang ?></td>
                                                    <td><?= $br->saldo_awal_box ?></td>
                                                    <td><?= $br->saldo_awal_pcs ?></td>
                                                    <td><?= $br->in_box ?></td>
                                                    <td><?= $br->in_box ?></td>
                                                    <td><?= $br->out_box ?></td>
                                                    <td><?= $br->out_pcs ?></td>
                                                    <td><?= $br->saldo_akhir_box ?></td>
                                                    <td><?= $br->saldo_akhir_pcs ?></td>
                                                    <td><?= $br->fisik_box ?></td>
                                                    <td><?= $br->fisik_pcs ?></td>
                                                    <td><?= $br->qty_selisih ?></td>
                                                    <?php if ($br->status_kesesuaian == 'KLOP') : ?>
                                                        <td style="text-align: center;">
                                                            <a href="#" class="btn btn-sm btn-success"><i class="fas fa-check-circle"></i></a>
                                                        </td>
                                                    <?php else : ?>
                                                        <td style="text-align: center;">
                                                            <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-times-circle"></i></a>
                                                        </td>
                                                    <?php endif; ?>
                                                    <!-- <td>
                                                        <a href="#" class="btn btn-sm btn-primary btn-open-opname" data-id="<?= $br->id ?>"><i class="fas fa-plus-circle"></i></a>
                                                    </td> -->
                                                </tr>
                                            <?php endforeach; ?>
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