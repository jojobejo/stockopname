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
                        <div class="card-body">
                            <table id="tbpricelist" class="table table-bordered table-striped">
                                <h3>Pricelist Online</h3>
                                <thead style="background-color: #212529; color:white;">
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th style="text-align: center;">Shopee</th>
                                        <th style="text-align: center;">Tiktok</th>
                                        <th style="text-align: center;">Karisma Online</th>
                                        <th style="text-align: center;">#</th>
                                        <th style="text-align: center;">#</th>
                                        <th style="text-align: center;">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pricelist as $pl) :
                                        $shope_sts  = $pl->sts_shope;
                                        $tiktok_sts = $pl->sts_tiktok;
                                        $online_sts = $pl->sts_online;
                                    ?>
                                        <tr>
                                            <td><?= $pl->nama_barang ?></td>
                                            <td style="text-align: center;">Rp. <?= number_format($pl->pl_shope) ?></td>
                                            <td style="text-align: center;">Rp. <?= number_format($pl->pl_tiktok) ?></td>
                                            <td style="text-align: center;">Rp. <?= number_format($pl->pl_online) ?></td>
                                            <?php if ($shope_sts == '1') : ?>
                                                <td style="text-align: center;"> <a href="#" class="btn btn-success btn-block"><i class="fas fa-check"></i></a></td>
                                            <?php else :  ?>
                                                <td style="text-align: center;"><a href="#" class="btn btn-danger btn-block"><i class="fas fa-times"></i></a></td>
                                            <?php endif; ?>
                                            <?php if ($tiktok_sts == '1') : ?>
                                                <td style="text-align: center;"><a href="#" class="btn btn-success btn-block"><i class="fas fa-check"></i></a></td>
                                            <?php else :  ?>
                                                <td style="text-align: center;"><a href="#" class="btn btn-danger btn-block"><i class="fas fa-times"></i></a></td>
                                            <?php endif; ?>
                                            <?php if ($online_sts == '1') : ?>
                                                <td style="text-align: center;"><a href="#" class="btn btn-success btn-block"><i class="fas fa-check"></i></a></td>
                                            <?php else :  ?>
                                                <td style="text-align: center;"><a href="#" class="btn btn-danger btn-block"><i class="fas fa-times"></i></a></td>
                                            <?php endif; ?>
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