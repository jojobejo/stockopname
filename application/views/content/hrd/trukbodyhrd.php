<style>
    .borderless td,
    .borderless th {
        border: none;
    }
</style>

<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>
        <?php $this->load->view('content/hrd/modalservicetruk') ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Kilometer Kendaraan</h3>
                        </div>
                        <div class="ml-2">
                            <button type="button" class="btn btn-primary m-2 ml-3" data-toggle="modal" data-target="#addexpedisi">
                                <i class="fas fa-pen"></i>
                                Input Data Baru
                            </button>
                        </div>
                        <div class="card-body">
                            <table class="table borderless" id="tb_service_truk">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">#</th>
                                        <th style="text-align: center;">Truk Info</th>
                                        <th style="text-align: center;">Last Update</th>
                                        <th style="text-align: center;" class="ml-3">KM Sekarang</th>
                                        <th style="text-align: center;" class="ml-3">KM Sebelum</th>
                                        <th style="text-align: center;">Oli Mesin</th>
                                        <th style="text-align: center;">Oli Gardan</th>
                                        <th style="text-align: center;">Oli Transmisi</th>
                                        <th style="text-align: center;">Filter Oli </th>
                                        <th style="text-align: center;">Filter Up</th>
                                        <th style="text-align: center;">Filter Down</th>
                                        <th style="text-align: center;">Filter Udara</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($truk as $t) :

                                        $kmnow      = $t->km_sekarang;
                                        $kmsb       = $t->km_sebelum;
                                        $g_oli_m    = ($kmsb + 5000) - $kmnow;
                                        $g_oli_g    = ($kmsb + 20000) - $kmnow;
                                        $g_oli_t    = ($kmsb + 20000) - $kmnow;
                                        $g_oli_fo   = ($kmsb + 20000) - $kmnow;
                                        $g_oli_sa   = ($kmsb + 15000) - $kmnow;
                                        $g_oli_sb   = ($kmsb + 15000) - $kmnow;
                                        $g_oli_fu   = ($kmsb + 30000) - $kmnow;

                                        $s_oli_m    = ($g_oli_m < 300) ? "btn-warning" : "btn-secondary";
                                        $s_oli_g    = ($g_oli_g < 300) ? "btn-warning" : "btn-secondary";
                                        $s_oli_t    = ($g_oli_t < 300) ? "btn-warning" : "btn-secondary";
                                        $s_oli_fo   = ($g_oli_fo < 300) ? "btn-warning" : "btn-secondary";
                                        $s_oli_sa   = ($g_oli_sa < 300) ? "btn-warning" : "btn-secondary";
                                        $s_oli_sb   = ($g_oli_sb < 300) ? "btn-warning" : "btn-secondary";
                                        $s_oli_fu   = ($g_oli_fu < 300) ? "btn-warning" : "btn-secondary";

                                    ?>
                                        <tr>
                                            <td>
                                                <a href="<?= base_url('') ?>"><img src="<?php echo base_url("assets/images/truck1.png") ?>" style="width: 50px; height: 50px;" class="brand-image img-circle"></a>
                                            </td>
                                            <td style="text-align: center;">
                                                <a><?= $t->no_pol ?> </a>
                                                <br>
                                                <a><?= $t->kd_truk ?></a>
                                            </td>
                                            <td style="text-align: center;">
                                                <a style="font-size: medium;" class="btn bg-lightblue btn-sm"><?= format_tgl($t->update_at) ?></a>
                                            </td>
                                            <td style="text-align: center;">
                                                <a class="btn btn-primary btn-md ml-3" data-toggle="modal" data-target="#updatekmsekarang<?= $t->id ?>"><?= number_format($t->km_sekarang) ?></a>
                                            </td>
                                            <td style="text-align: center;">
                                                <a class="btn btn-info btn-md ml-3" data-toggle="modal" data-target="#updatekmsebelum<?= $t->id ?>"><?= number_format($t->km_sebelum) ?></a>
                                            </td>
                                            <td style="text-align: center;">
                                                <button class="btn <?= $s_oli_m ?> btn-md ml-3"><?= number_format($g_oli_m) ?></button>
                                            </td>
                                            <td style="text-align: center;">
                                                <button class="btn <?= $s_oli_g ?> btn-md ml-3"><?= number_format($g_oli_g) ?></button>
                                            </td>
                                            <td style="text-align: center;">
                                                <button class="btn <?= $s_oli_t ?> btn-md ml-3"><?= number_format($g_oli_t) ?></button>
                                            </td>
                                            <td style="text-align: center;">
                                                <button class="btn <?= $s_oli_fo ?> btn-md ml-3"><?= number_format($g_oli_fo) ?></button>
                                            </td>
                                            <td style="text-align: center;">
                                                <button class="btn <?= $s_oli_sa ?> btn-md ml-3"><?= number_format($g_oli_sa) ?></button>
                                            </td>
                                            <td style="text-align: center;">
                                                <button class="btn <?= $s_oli_sb ?> btn-md ml-3"><?= number_format($g_oli_sb) ?></button>
                                            </td>
                                            <td style="text-align: center;">
                                                <button class="btn <?= $s_oli_fu ?> btn-md ml-3"><?= number_format($g_oli_fu) ?></button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
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