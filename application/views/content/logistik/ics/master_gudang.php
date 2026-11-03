<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>

        <div class="modal fade" id="modalGudang">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="formGudang">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Gudang</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div class="modal-body">
                            <div class="form-group">
                                <label>Nama Gudang</label>
                                <input type="text" name="nama_gudang" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Tipe Gudang</label>
                                <select name="tipe" class="form-control" required>
                                    <option value="">- Pilih -</option>
                                    <option value="INDUK">Gudang Induk</option>
                                    <option value="ECERAN">Gudang Eceran</option>
                                    <option value="EXPIRED">Gudang Expired</option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="content-wrapper">
            <div class="row m-2">
                <h2 class="m-2">Master Gudang</h2>
                <a href="" class="btn btn-primary mt-2 ml-3" data-toggle="modal" data-target="#modalGudang">Tambah Gudang</a>
            </div>

            <div class="card m-3">
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="tableGudang" width="100%">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama Gudang</th>
                                <th>Tipe</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
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