<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>
        <?php $this->load->view('content/user/modalUser') ?>

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
                            <h3 class="card-title">Data All User</h3>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary m-2 ml-3" data-toggle="modal" data-target="#modalAddUser">
                                <i class="fas fa-plus"></i>
                                Tambah User Baru
                            </button>
                        </div>
                        <div class="card-body">
                            <table id="tb_user" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama User</th>
                                        <th>Username</th>
                                        <th>Departemen</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($alluser as $u) : ?>
                                        <tr>
                                            <th><?= $u->nama_user ?></th>
                                            <th><?= $u->username ?></th>
                                            <th><?= $u->departemen ?></th>
                                            <th>
                                                <a href="#" class="btn btn-warning btn-sm " data-toggle="modal" data-target="#edituser<?= $u->id ?>">
                                                    <i class="fa fa-solid fa-pencil-alt"></i>
                                                </a>
                                                <a href="#" class="btn btn-danger btn-sm " data-toggle="modal" data-target="#hapususer<?= $u->id ?>">
                                                    <i class="fa fa-solid fa-trash-alt"></i>
                                                </a>
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