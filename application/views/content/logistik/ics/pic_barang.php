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
            <h2 class="m-2">Barang - PIC</h2>

            <div class="row m-2">
                <div class="col-8">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered table-hover table-sm" id="tbics_erpss">
                                <thead class="thead-dark">
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
                                            <td><?= $p->nama_barang ?></td>
                                            <td><?= $p->exp_date ?></td>
                                            <td><?= $p->barang_pic ?></td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary btn-edit-pic" data-ids="<?= $p->daftar_id ?>" data-namabarang="<?= $p->nama_barang ?>" data-exp="<?= $p->exp_date ?>" data-lokasi="<?= $p->barang_pic ?>" data-kdbarang="<?= $p->kd_barang ?>">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

                <!-- TABLE KANAN -->
                <div class="col-4">
                    <div class="card">
                        <div class="card-body">

                            <table class="table table-bordered table-hover table-sm" id="tbfilterpic">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>PIC</th>
                                        <th>Jumlah Barang</th>
                                        <th>Filter</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($itemtotal as $i) : ?>
                                        <tr>
                                            <td><?= $i->barang_pic ?></td>
                                            <td><?= $i->total_barang ?></td>
                                            <td>
                                                <a href="<?= base_url('ics/barangpic/' . $i->barang_pic) ?>" class="btn btn-info btn-block btn-sm">
                                                    <i class="fas fa-filter"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

            </div>

            <!-- Modal Ubah Lokasi -->
            <div class="modal fade" id="modalEditPIC" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Ubah Lokasi Barang</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form method="post" action="<?= base_url('ics/update_pic_lokasi'); ?>">
                            <div class="modal-body">

                                <input type="hidden" name="id" id="edit_id">
                                <input type="hidden" name="list_id" id="edit_list_id">
                                <input type="hidden" name="kd_barang" id="kd_barang">
                                <input type="hidden" name="expdate" id="expdate">

                                <div class="form-group">
                                    <label>Nama Barang</label>
                                    <input type="text" id="edit_nama_barang" class="form-control" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Lokasi</label>
                                    <select name="lokasi" id="edit_lokasi" class="form-control">
                                        <option value="0">0</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                        <option value="E">E</option>
                                    </select>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            </div>
                        </form>

                    </div>
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

    <script>
        $('#btnSavePIC').on('click', function() {

            $.ajax({
                url: base_url('ics/update_pic_lokasi'),
                type: 'POST',
                dataType: 'json',
                data: {
                    list_id: $('#edit_list_id').val(),
                    kd_barang: $('#kd_barang').val(),
                    expdate: $('#expdate').val(),
                    lokasi: $('#edit_lokasi').val()
                },
                success: function(res) {
                    if (res.status) {
                        $('#modalEditPIC').modal('hide');
                        location.reload();
                    } else {
                        alert(res.msg);
                    }
                }
            });

        });
    </script>