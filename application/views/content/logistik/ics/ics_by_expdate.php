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
                <section class="content">
                    <div class="row">
                        <?php if ($this->session->userdata('lv') == '1') : ?>
                            <div class="col-auto">
                                <a href="<?= base_url('ics/by_allbarang') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-boxes"></i> By All Barang</a>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('ics/by_expdate') ?>" class="btn btn-md btn-secondary w-100 mb-3"><i class="fas fa-calendar"></i> By Expired Date</a>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('ics/icsdo') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-minus-circle"></i> Data DO</a>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('ics/icspo') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-plus-circle"></i> Data LPB</a>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('ics/ics_diffrent') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-eye"></i> Show Diffrent</a>
                            </div>
                        <?php else : ?>
                            <div class="col-auto">
                                <a href="<?= base_url('ics/by_allbarang_ics/') . $tim ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-box"></i> Data All Barang</a>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('ics/by_expdate_ics/') . $tim ?>" class="btn btn-md btn-secondary w-100 mb-3"><i class="fas fa-calendar"></i> Data By Expired Date</a>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('ics/ics_diffrent') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-eye"></i> Show Diffrent</a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="container-fluid">
                                <table class="table table-bordered table-striped" id="tbics_erp_expdate">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="align-middle bg-info text-white text-center">#</th>
                                            <th colspan="2" class="bg-primary text-white text-center">NAMA</th>
                                            <th colspan="2" class="bg-info text-white text-center">Saldo Awal</th>
                                            <th colspan="2" class="bg-success text-white text-center">LPB</th>
                                            <th colspan="2" class="bg-danger text-white text-center">DO</th>
                                            <th colspan="2" class="bg-success text-white text-center">RETUR</th>
                                            <th colspan="2" class="bg-info text-white text-center">Sistem</th>
                                            <th colspan="2" class="bg-success text-white text-center">Fisik</th>
                                            <th rowspan="2" class="align-middle bg-info text-white text-center">Selisih</th>
                                            <th rowspan="2" class="align-middle bg-success text-white text-center">Status</th>
                                        </tr>
                                        <tr>
                                            <th class="bg-primary text-white">Nama Barang</th>
                                            <th class="bg-primary text-white">Date</th>
                                            <th class="bg-info text-white">Box</th>
                                            <th class="bg-info text-white">Pcs</th>
                                            <th class="bg-success text-white">Box</th>
                                            <th class="bg-success text-white">Pcs</th>
                                            <th class="bg-danger text-white">Box</th>
                                            <th class="bg-danger text-white">Pcs</th>
                                            <th class="bg-success text-white">Jual</th>
                                            <th class="bg-success text-white">Beli</th>
                                            <th class="bg-info text-white">Box</th>
                                            <th class="bg-info text-white">Pcs</th>
                                            <th class="bg-success text-white">Box</th>
                                            <th class="bg-success text-white">Pcs</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($barang_ics as $br) : ?>
                                            <tr>
                                                <td>
                                                    <a href="<?= base_url('ics/stock_by_kodebr/' . $br->kd)  ?>" target="__blank" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                                </td>
                                                <td><?= $br->nama_barang ?></td>
                                                <td><?= $br->exp_date ?></td>
                                                <td><?= $br->saldo_awal_box ?></td>
                                                <td><?= $br->saldo_awal_pcs ?></td>
                                                <td><?= $br->in_box ?></td>
                                                <td><?= $br->in_box ?></td>
                                                <td><?= $br->out_box ?></td>
                                                <td><?= $br->out_pcs ?></td>
                                                <td><?= $br->qty_rjual ?></td>
                                                <td><?= $br->qty_rbeli ?></td>
                                                <td><?= $br->saldo_akhir_box ?></td>
                                                <td><?= $br->saldo_akhir_pcs ?></td>
                                                <td><?= $br->fisik_box ?></td>
                                                <td><?= $br->fisik_pcs ?></td>
                                                <td><?= $br->qty_selisih ?></td>
                                                <!-- <td class="bg-info text-white"><?= $br->saldo_awal_box ?></td>
                                                    <td class="bg-info text-white"><?= $br->saldo_awal_pcs ?></td>
                                                    <td class="bg-success text-white"><?= $br->in_box ?></td>
                                                    <td class="bg-success text-white"><?= $br->in_box ?></td>
                                                    <td class="bg-danger text-white"><?= $br->out_box ?></td>
                                                    <td class="bg-danger text-white"><?= $br->out_pcs ?></td>
                                                    <td class="bg-info text-white"><?= $br->saldo_akhir_box ?></td>
                                                    <td class="bg-info text-white"><?= $br->saldo_akhir_pcs ?></td>
                                                    <td class="bg-success text-white"><?= $br->fisik_box ?></td>
                                                    <td class="bg-success text-white"><?= $br->fisik_pcs ?></td>
                                                    <td class="bg-info text-white"><?= $br->qty_selisih ?></td> -->
                                                <?php if ($br->status_kesesuaian == 'KLOP') : ?>
                                                    <td style="text-align: center;">
                                                        <a href="#" class="btn btn-sm btn-success"><i class="fas fa-check-circle"></i></a>
                                                    </td>
                                                <?php else : ?>
                                                    <td style="text-align: center;">
                                                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-times-circle"></i></a>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                            <!-- <tr>
                                                    <td><?= $br->nama_barang ?></td>
                                                    <td><?= $br->exp_date ?></td>
                                                    <td><?= $br->in_box ?></td>
                                                    <td><?= $br->in_box ?></td>
                                                    <td><?= $br->out_box ?></td>
                                                    <td><?= $br->out_pcs ?></td>
                                                    <td><?= $br->saldo_awal_box ?></td>
                                                    <td><?= $br->saldo_awal_pcs ?></td>
                                                    <td contenteditable="true" class="editable" data-id="<?= $br->nama_barang ?>|<?= $br->exp_date ?>" data-field="opname_box"><?= $br->opname_box ?></td>
                                                    <td contenteditable="true" class="editable" data-id="<?= $br->nama_barang ?>|<?= $br->exp_date ?>" data-field="opname_pcs"><?= $br->opname_pcs ?></td>
                                                    <td><?= $br->saldo_akhir_box ?></td>
                                                    <td><?= $br->saldo_akhir_pcs ?></td>
                                                    <td><?= $br->klop ?></td>
                                                    <td><?= $br->klop ?></td>
                                                </tr> -->
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="modal fade" id="modalAddOpname" tabindex="-1" role="dialog" aria-labelledby="modalAddOpnameLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form action="<?= base_url('ics/sv_opname') ?>" method="post">
                        <div class="modal-content">
                            <div class="modal-header bg-success">
                                <h5 class="modal-title" id="modalAddOpnameLabel"><i class="fas fa-box"></i> Input Data Opname</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" id="modal_id">
                                <input type="hidden" name="dimensi" id="modal_dimensi">
                                <input type="hidden" name="action" id="action_id" value="dashboard">

                                <div class="form-group">
                                    <label for="nama_barang">Nama Barang</label>
                                    <input type="text" name="nama_barang" id="modal_nama_barang" class="form-control" readonly required>
                                </div>
                                <div class="form-group">
                                    <label for="exp_date">Expired Date</label>
                                    <input type="text" name="exp_date" id="modal_exp_date" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="exp_date">Keterangan</label>
                                    <textarea class="form-control" name="keterangan_isi" id="modal_keterangan" placeholder="Tambahkan keterangan inputer" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="qty_box">Qty Box</label>
                                    <input type="number" name="qty_box" id="modal_qty_box" class="form-control" placeholder="0">
                                </div>
                                <div class="form-group">
                                    <label for="qty_pcs">Qty Pcs</label>
                                    <input type="number" name="qty_pcs" id="modal_qty_pcs" class="form-control" placeholder="0">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            </div>
                        </div>
                    </form>
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
        $(document).ready(function() {
            $('.btn-open-opname').on('click', function() {
                const rowId = $(this).data('id');

                $.ajax({
                    url: "<?= base_url('ics/get_detail_barang') ?>",
                    method: "POST",
                    data: {
                        id: rowId
                    },
                    dataType: "json",
                    success: function(data) {
                        $('#modal_id').val(data.id);
                        $('#modal_nama_barang').val(data.nama_barang);
                        $('#modal_exp_date').val(data.exp_date);
                        $('#modal_dimensi').val(data.dimensi);
                        $('#modal_qty_box').val('');
                        $('#modal_qty_pcs').val('');
                        $('#modalAddOpname').modal('show');
                    },
                    error: function() {
                        alert('Gagal mengambil data barang.');
                    }
                });
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('.editable').on('blur', function() {
                var data_id = $(this).data('id').split('|');
                var nama_barang = data_id[0];
                var exp_date = data_id[1];
                var field = $(this).data('field');
                var value = $(this).text();

                $.ajax({
                    url: "<?= base_url('ics/updateinline') ?>",
                    method: "POST",
                    data: {
                        nama_barang: nama_barang,
                        exp_date: exp_date,
                        field: field,
                        value: value
                    },
                    success: function(response) {
                        console.log(response);
                    },
                    error: function() {
                        alert('Gagal menyimpan data');
                    }
                });
            });
        });
    </script>

    <script>
        $('#btnSimpanOpname').on('click', function() {
            if (confirm("Apakah Anda yakin ingin menyimpan semua data ke Opname hari ini? Data akan disalin dari tb_ics.")) {
                $.ajax({
                    url: "<?= base_url('ics/simpan_opname') ?>",
                    type: "POST",
                    dataType: "json",
                    success: function(response) {
                        if (response.status === 'success') {
                            alert("Berhasil menyimpan data opname.");
                            location.reload();
                        } else {
                            alert("Gagal menyimpan data opname: " + response.message);
                        }
                    },
                    error: function() {
                        alert("Terjadi kesalahan saat menghubungi server.");
                    }
                });
            }
        });
    </script>