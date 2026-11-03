<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>
        <?php $this->load->view('content/logistik/modallogistik') ?>


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
                            <h3 class="card-title">Truck Listing</h3>
                        </div>
                        <div class="ml-2">
                            <div class="row">
                                <a href="<?= base_url('opplat') ?>" class="btn btn-primary m-2 ml-3" data-toggle="modal" data-target="#maddplat">
                                    <i class="fas fa-truck"></i>
                                    Tambah Truk Baru
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="tb_plat" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">No Plat</th>
                                        <th style="text-align: center;">Kode Truk</th>
                                        <th style="width: 15%; text-align: center;">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dataplat as $d) : ?>
                                        <tr>
                                            <th style="text-align: center;"><?= $d->noplat ?></th>
                                            <th style="text-align: center;"><?= $d->nm_truk ?></th>
                                            <th style="text-align: center;">
                                                <a href="#" class="btn btn-warning btn-sm " data-toggle="modal" data-target="#meditplat<?= $d->id ?>">
                                                    <i class="fa fa-solid fa-pencil-alt"></i>
                                                </a>
                                                <a href="#" class="btn btn-danger btn-sm ml-2 " data-toggle="modal" data-target="#hapusnoplat<?= $d->id ?>">
                                                    <i class="fa fa-solid fa-trash-alt"></i>
                                                </a>
                                            </th>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card mt-2">
                        <div class="card-header">
                            <h3 class="card-title">Driver Listing</h3>
                        </div>
                        <div class="ml-2">
                            <div class="row">
                                <a class="btn btn-primary m-2 ml-3" data-toggle="modal" data-target="#adddriver">
                                    <i class="fas fa-user-cog"></i>
                                    Tambah Driver Baru
                                </a>
                                <a class="btn btn-primary m-2 ml-3" data-toggle="modal" data-target="#editnourut">
                                    <i class="fas fa-user-cog"></i>
                                    Setting Nomor Urut
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="tb_driver" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 5px;">No.Urut Hari Ini</th>
                                        <th>Nama Driver</th>
                                        <th>Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($driver as $d) : ?>
                                        <tr>
                                            <th style="width: 5px;"><?= $d->no_urut_hr_i ?></th>
                                            <th><?= $d->nama_driver ?></th>
                                            <th><?= $d->status ?></th>
                                            <th>
                                                <a href="#" class="btn btn-primary btn-sm " data-toggle="modal" data-target="#editdriver<?= $d->id ?>">
                                                    <i class="fa fa-solid fa-pencil-alt"></i>
                                                </a>
                                                <a href="#" class="btn btn-danger btn-sm ml-2 " data-toggle="modal" data-target="#hapusdriver<?= $d->id ?>">
                                                    <i class="fa fa-solid fa-trash-alt"></i>
                                                </a>
                                                <?php if ($d->status == 'ACTIVE') : ?>
                                                    <a href="<?= base_url('nonactivedriver/') . $d->kd_driver  ?>" class="btn btn-warning btn-sm ml-2 ">
                                                        <i class="fa fa-solid fa-ban"></i>
                                                    </a>
                                                <?php elseif ($d->status == 'NON-ACTIVE') : ?>
                                                    <a href="<?= base_url('activedrver/') . $d->kd_driver ?>" class="btn btn-success btn-sm ml-2">
                                                        <i class="fa fa-solid fa-user-check"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </th>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card mt-2">
                        <div class="card-header">
                            <h3 class="card-title">Helper Listing</h3>
                        </div>
                        <div class="ml-2">
                            <div class="row">
                                <a class="btn btn-primary m-2 ml-3" data-toggle="modal" data-target="#addhelper">
                                    <i class="fas fa-user-cog"></i>
                                    Tambah Helper Baru
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="tb_driver" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Driver</th>
                                        <th>Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($helper as $h) : ?>
                                        <tr>
                                            <th><?= $h->nama_helper ?></th>
                                            <th><?= $h->status ?></th>
                                            <th>
                                                <a href="#" class="btn btn-primary btn-sm " data-toggle="modal" data-target="#edithelper<?= $h->id ?>">
                                                    <i class="fa fa-solid fa-pencil-alt"></i>
                                                </a>
                                                <a href="#" class="btn btn-danger btn-sm ml-2 " data-toggle="modal" data-target="#hapushelper<?= $h->id ?>">
                                                    <i class="fa fa-solid fa-trash-alt"></i>
                                                </a>
                                                <?php if ($h->status == 'ACTIVE') : ?>
                                                    <a href="<?= base_url('nonactivehelper/') . $h->kd_helper  ?>" class="btn btn-warning btn-sm ml-2 ">
                                                        <i class="fa fa-solid fa-ban"></i>
                                                    </a>
                                                <?php elseif ($h->status == 'NON-ACTIVE') : ?>
                                                    <a href="<?= base_url('activehelper/') . $h->kd_helper ?>" class="btn btn-success btn-sm ml-2">
                                                        <i class="fa fa-solid fa-user-check"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </th>
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