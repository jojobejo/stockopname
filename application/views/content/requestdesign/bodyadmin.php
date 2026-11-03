<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>
        <?php $this->load->view('content/requestdesign/modalrequestdesign') ?>

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
                            <h3 class="card-title">Request Design</h3>
                        </div>
                        <div class="ml-2">
                            <button type="button" class="btn btn-primary m-2 ml-3" data-toggle="modal" data-target="#addInventaris">
                                <i class="fas fa-shopping-cart"></i>
                                Request Design
                            </button>
                        </div>
                        <div class="card-body">
                            <table id="tb_inventaris" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>Kode Inventaris</th>
                                        <th>PIC</th>
                                        <th>Departement</th>
                                        <th>Type Barang</th>
                                        <th>Deskripsi</th>
                                        <th>Tanggal Beli</th>
                                        <th>Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($datainventaris as $d) : ?>
                                        <tr>
                                            <th><?= $d->id ?></th>
                                            <th><?= $d->kdinventaris ?></th>
                                            <th><?= $d->kdpic ?></th>
                                            <th><?= $d->departement ?></th>
                                            <th><?= $d->typebarang ?></th>
                                            <th><?= $d->deskripsi ?></th>
                                            <th><?= $d->tglbeli ?></th>
                                            <th><?= $d->status ?></th>
                                            <th>
                                                <div class="row">
                          <a href="#" class="btn btn-warning btn-sm " data-toggle="modal" data-target="#editinventaris<?= $d->id ?>">
                            <i class="fa fa-solid fa-pencil-alt"></i>
                          </a>
                          <a href="#" class="btn btn-danger btn-sm " data-toggle="modal" data-target="#hapusinventaris<?= $d->id ?>">
                            <i class="fa fa-solid fa-trash-alt"></i>
                          </a>
                        </div></th>                                          
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