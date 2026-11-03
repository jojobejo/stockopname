<style>
    .select2-container {
        width: 100% !important;
    }

    .select2-selection--single {
        height: calc(2.25rem + 2px);
        padding: .375rem .75rem;
    }

    .select2-selection__rendered {
        line-height: 1.5;
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
                <section class="content">
                    <div class="row mb-2">
                        <div class="col-4">
                            <a href="<?= base_url('ics/retur') ?>" class="btn btn-md  btn-primary"><i class="fas fa-home"></i></a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Nomor Faktur</label>
                                        <select name="select_faktur" id="select_faktur" class="form-control"></select>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Barang</label>
                                        <select name="select_barang" id="select_barang" class="form-control"></select>
                                    </div>
                                    <input name="kode_barang" id="kode_barang" class="form-control" value="" readonly hidden>
                                    <div class="form-group">
                                        <label>Expired Date</label>
                                        <select name="select_exp" id="select_exp" class="form-control"></select>
                                    </div>
                                    <div class="form-group">
                                        <label>Qty</label>
                                        <input type="number" name="qtyinput" id="qtyinput" class="form-control" value="">
                                    </div>
                                    <a href="#" class="btn btn-block btn-primary" id="btninputdata">Input Data</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="id_bar">No Ref : </label>
                                            <input class="form-control col-4 ml-4" type="text" id="nofresnsi" name="nofresnsi" value="<?php echo isset($kd_retur) ? $kd_retur : ''; ?>" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="id_bar">Tanggal : </label>
                                            <input class="form-control col-4 ml-4" type="date" id="tgl_transaksi" name="tgl_transaksi" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="id_bar">Keterangan : </label>
                                            <input class="form-control col-4 ml-4" type="text" id="keterangan_retur" name="keterangan_retur" value="" />
                                        </div>
                                    </div>

                                    <div class="col-auto mt-4">
                                        <table id="input_retur_penjualan" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No Faktur</th>
                                                    <th>Kode Barang</th>
                                                    <th>Nama Barang</th>
                                                    <th>Expired Date</th>
                                                    <th>LOT</th>
                                                    <th>Qty</th>
                                                    <th>#</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <a href="#" class="btn btn-success btn-block" id="rekamreturpembelian"> Rekam</a>
                                </div>

                            </div>
                        </div>

                    </div>


                </section>
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