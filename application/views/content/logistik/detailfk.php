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
                <div class="container-fluid">
                    <div class="row">
                        <a href="<?= base_url('create_do') ?>" class="btn btn-primary mb-2 ml-2"><i class="fas fa-arrow-circle-left"></i></a>
                        <?php foreach ($customer as $c) :
                            $status_faktur = $c->data_sts;
                            $status_upload = $c->upload_sts;
                        ?>
                            <h3 class="ml-4" style="font-weight: bold; font-size: xx-large;"><?= $c->nama_kios ?> || <?= $c->regional ?></h3>

                            <?php if ($status_faktur == 1) : ?>
                                <h3 class="ml-4"><span class="badge badge-secondary">NOT IN DRAFT</span></h3>
                            <?php elseif ($status_faktur == 2) : ?>
                                <h3 class="ml-4"><span class="badge badge-success">ON DRAFT LIST</span></h3>
                            <?php elseif ($status_faktur == 4) : ?>
                                <a href="<?= base_url('detail_fk_pnd/' . $kdfaktur) ?>" class="btn btn">
                                    <h3 class="ml-4"><span class="badge badge-warning">FAKTUR PENDING</span></h3>
                                </a>
                            <?php endif; ?>
                            <div hidden>
                                <?php if ($status_upload == '1') : ?>
                                    <h3 class="ml-4"><span class="badge badge-info">PAGI</span></h3>
                                <?php else : ?>
                                    <h3 class="ml-4"><span class="badge badge-dark">SORE</span></h3>
                                <?php endif; ?>
                            </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-body">

                            <?php if ($status_faktur == 4) : ?>
                                <div class="row">
                                    <div class="col-4">
                                        <a href="" id="btnMaster" class="btn btn-info btn-block mb-2">Faktur Master</a>
                                    </div>
                                    <div class="col-4">
                                        <a href="" id="btnPending" class="btn btn-info btn-block mb-2">Detail Faktur Pending</a>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <table id="detbarang" class="table table-striped">
                                <thead style="background-color: #212529; color:white;">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Barang</th>
                                        <th>QTY</th>
                                        <th>Gram</th>
                                        <th>Kilo</th>
                                        <th>Total Berat Barang</th>
                                        <th>Satuan</th>
                                        <th>No-Lot</th>
                                        <th>Exp Date</th>
                                        <?php if ($status_faktur == '4') : ?>
                                            <th>#</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($detail_fk as $det) : ?>
                                        <tr data-id="<?= $det->id ?>">
                                            <td><?= $det->kd_barang ?></td>
                                            <td><?= $det->nama_barang ?></td>
                                            <td><?= $det->qty ?></td>
                                            <td><?= number_format($det->gr_berat, 3)  ?></td>
                                            <td><?= number_format($det->convert_kg, 3) ?></td>
                                            <td><?= number_format($det->total_berat, 3) ?></td>
                                            <td><?= $det->satuan ?></td>
                                            <td><?= $det->no_lot ?></td>
                                            <td><?= $det->tgl_exp ?></td>
                                            <?php if ($status_faktur == '2') : ?>
                                                <?php if ($det->barang_sts == '1') : ?>
                                                    <td>
                                                        <h3><span class="badge badge-success w-100"><i class="fas fa-certificate"></i></span></a></h3>
                                                    </td>
                                                <?php elseif ($det->barang_sts == '3') : ?>
                                                    <td colspan="2">
                                                        <h3><a><span class="badge badge-warning w-100"><i class="fas fa-pause-circle"></i></span></a></h3>
                                                    </td>
                                                <?php elseif ($det->barang_sts == '2') : ?>
                                                    <td colspan="2">
                                                        <h3><span class="badge badge-success w-100"><i class="fas fa-certificate"></i></span></a></h3>
                                                    </td>
                                                <?php elseif ($det->barang_sts == '4') : ?>
                                                    <td colspan="2">
                                                        <h3><span class="badge badge-success w-100"><i class="fas fa-certificate"></i></span></a></h3>
                                                    </td>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <?php if ($det->barang_sts == '4') : ?>
                                                    <td colspan="2">
                                                        <h3><span class="badge badge-success w-100"><i class="fas fa-certificate"></i></span></a></h3>
                                                    </td>
                                                <?php elseif ($det->barang_sts == '3') : ?>
                                                    <td colspan="2">
                                                        <h3><a href="<?= base_url('pnd_br_detpo/') . $det->id . '/' . $kdfaktur . '/' . 'revert' ?>"><span class="badge badge-warning w-100"><i class="fas fa-pause-circle"></i></span></a></h3>
                                                    </td>
                                                <?php elseif ($det->barang_sts == '2') : ?>
                                                    <td colspan="2">
                                                        <h3><span class="badge badge-success w-100"><i class="fas fa-certificate"></i></span></a></h3>
                                                    </td>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>

                                    <!-- BARIS EDIT TABEL -->

                                    <tr id="editRow" style="display: none;">
                                        <td colspan="7">

                                            <form id="editForm">
                                                <div class="row">
                                                    <input type="hidden" id="id" name="id" readonly>
                                                    <div class="col-md-2">
                                                        <input type="text" id="edit_nama" name="nm_barang" class="form-control" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" id="edit_qty" name="qty" class="form-control">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" id="edit_satuan" name="satuan" class="form-control" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" id="edit_no_lot" name="no_lot" class="form-control" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" id="edit_exp" name="tgl_exp" class="form-control" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="submit" class="btn btn-success">Simpan</button>
                                                        <button type="button" class="btn btn-danger" id="cancelEdit">Batal</button>
                                                    </div>
                                                </div>
                                            </form>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-striped" id="tbfakturmaster">
                                <thead style="background-color: #212529; color:white;">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Barang</th>
                                        <th>QTY</th>
                                        <th>Gram</th>
                                        <th>Kilo</th>
                                        <th>Total Berat (Kg)</th>
                                        <th>Satuan</th>
                                        <th>No Lot</th>
                                        <th>Expired Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($master_faktur as $mk) : ?>
                                        <tr>
                                            <td><?= $mk->kd_barang ?></td>
                                            <td><?= $mk->nama_barang ?></td>
                                            <td><?= $mk->qty ?></td>
                                            <td><?= number_format($mk->gr_berat, 3)  ?></td>
                                            <td><?= number_format($mk->convert_kg, 3) ?></td>
                                            <td><?= $mk->total_berat ?></td>
                                            <td><?= $mk->satuan ?></td>
                                            <td><?= $mk->no_lot ?></td>
                                            <td><?= $mk->tgl_exp ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                            <?php if ($status_faktur == '1') : ?>
                                <div class="row">
                                    <div class="col-4">
                                        <a href="<?= base_url('insert_tmp/') . $kdfaktur . '/' . 'onsite' ?>" class="btn btn-info btn-block mt-4 mb-2">Input On Site</a>
                                    </div>
                                    <div class="col-4">
                                        <a href="<?= base_url('insert_tmp/') . $kdfaktur . '/' . 'formlist_pending' ?>" class="btn btn-warning btn-block mt-4 mb-2">Input to Pending</a>
                                    </div>
                                    <div class="col-4">
                                        <a href="<?= base_url('insert_tmp/') . $kdfaktur . '/' . 'formdetail' ?>" class="btn btn-success btn-block mt-4 mb-2">Input To Draft</a>
                                    </div>
                                </div>
                            <?php elseif ($status_faktur == '4') : ?>
                            <?php else : ?>
                                <a href="<?= base_url('revert_do/') . $kdfaktur . '/' . 'revertdetail' ?>" class="btn btn-warning btn-block mt-4 mb-2">Revert DO</a>
                            <?php endif; ?>

                        <?php endforeach; ?>
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

    <script>
        $(document).ready(function() {
            // default: tampilkan detail faktur, sembunyikan master
            $("#tbfakturmaster").hide();

            // default: tombol detail aktif
            $("#btnPending").removeClass("btn-info").addClass("btn-secondary");
            $("#btnMaster").removeClass("btn-secondary").addClass("btn-info");
        });

        // tombol master
        $("#btnMaster").on("click", function(e) {
            e.preventDefault();

            $("#detbarang").hide();
            $("#tbfakturmaster").show();

            // ubah warna tombol
            $("#btnMaster").removeClass("btn-info").addClass("btn-secondary");
            $("#btnPending").removeClass("btn-secondary").addClass("btn-info");
        });

        // tombol detail pending
        $("#btnPending").on("click", function(e) {
            e.preventDefault();

            $("#tbfakturmaster").hide();
            $("#detbarang").show();

            // ubah warna tombol
            $("#btnPending").removeClass("btn-info").addClass("btn-secondary");
            $("#btnMaster").removeClass("btn-secondary").addClass("btn-info");
        });


        $(document).ready(function() {

            $(".btn-edit").on("click", function(e) {
                e.preventDefault();

                var row = $(this).closest("tr");
                var id = row.data("id");

                $.ajax({
                    url: "<?= base_url('get_barang') ?>",
                    type: "POST",
                    data: {
                        id: id
                    },
                    dataType: "json",
                    success: function(data) {
                        $("#id").val(data.id);
                        $("#edit_nama").val(data.nm_barang);
                        $("#edit_qty").val(data.qty);
                        $("#edit_satuan").val(data.satuan);
                        $("#edit_no_lot").val(data.no_lot);
                        $("#edit_exp").val(data.tgl_exp);
                        $("#editRow").insertAfter(row).show();
                    }
                });
            });

            $("#cancelEdit").on("click", function() {
                $("#editRow").hide();
            });


            $("#editForm").on("submit", function(e) {
                e.preventDefault();

                $.ajax({
                    url: "<?= base_url('update_barang') ?>",
                    type: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(response) {
                        if (response.status === "success") {
                            alert("Data berhasil diperbarui!");
                            location.reload();
                        } else {
                            alert("Terjadi kesalahan, silakan coba lagi.");
                        }
                    },
                    error: function() {
                        alert("Gagal memperbarui data.");
                    }
                });
            });

        });

        $("#btnMaster").on("click", function(e) {
            e.preventDefault();
            $("#detbarang").hide();
            $("#tbfakturmaster").show();
        });

        $("#btnPending").on("click", function(e) {
            e.preventDefault();
            $("#tbfakturmaster").hide();
            $("#detbarang").show();
        });
    </script>