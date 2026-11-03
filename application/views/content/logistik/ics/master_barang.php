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
                <?php if ($this->session->userdata('jobdesk') == 'ADMINICS') : ?>
                    <section class="content">
                        <div class="card">
                            <div class="card-body">
                                <div class="container-fluid">
                                    <table class="table table-bordered table-striped" id="tb_masterbr_ics">
                                        <thead>
                                            <tr>
                                                <th>Kode</th>
                                                <th>Nama Barang</th>
                                                <th>Bahan Aktif</th>
                                                <th>Satuan</th>
                                                <th>Dimensi</th>
                                                <th>Tonase</th>
                                                <th>Kubikasi</th>
                                                <th>Total Data</th>
                                                <!-- <th>Qty Minimal</th> -->
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($mbarang as $br) :
                                                $dimensi = $br->p * $br->l * $br->t;
                                            ?>
                                                <tr>
                                                    <td><?= $br->kd_system ?></td>
                                                    <td><?= $br->nm_barang ?></td>
                                                    <td><?= $br->bhn_aktif ?></td>
                                                    <td><?= $br->satuan ?></td>
                                                    <td><?= $dimensi ?></td>
                                                    <td><?= $br->berat ?></td>
                                                    <td><?= $br->kubikasi ?></td>
                                                    <td><?= $br->jumlah_barang ?></td>
                                                    <!-- <td><?= $br->qty_min ?></td> -->

                                                    <td>
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <a href="#" class="btn btn-sm btn-warning btn-open-mbarang btn-block w-100" data-id="<?= $br->id ?>"><i class="fas fa-pen "></i></a>
                                                            </div>
                                                            <div class="col-6">
                                                                <a href="<?= base_url('ics/stock_by_kodebr/') . $br->kd_system ?>" class="btn btn-sm btn-info btn-open-mbarang btn-block w-100"><i class="fas fa-eye"></i></a>
                                                            </div>
                                                        </div>
                                                    </td>

                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>
            </div>

            <?php if ($this->session->userdata('jobdesk') == 'ADMINICS') : ?>
                <div class="modal fade" id="modalAddOpname" tabindex="-1" role="dialog" aria-labelledby="modalAddOpnameLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form action="<?= base_url('ics/save_edit_mbarang') ?>" method="post">
                            <div class="modal-content">
                                <div class="modal-header bg-success">
                                    <h5 class="modal-title" id="modalAddOpnameLabel"><i class="fas fa-box"></i> Input Data Master Barang</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="modal_nama_barang">Nama Barang</label>
                                        <input type="text" name="modal_nama_barang" id="modal_nama_barang" class="form-control" readonly required>
                                        <input type="hidden" name="modal_id" id="modal_id" class="form-control" readonly required hidden>
                                    </div>
                                    <div class="form-group">
                                        <label for="kode_barang">Kode Barang</label>
                                        <input type="text" name="modal_kode_barang" id="modal_kode_barang" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="bahan_aktif">Bahan Aktif</label>
                                        <input type="text" name="modal_bahan_aktif" id="modal_bahan_aktif" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="satuan">Satuan</label>
                                        <input type="text" name="modal_satuan" id="modal_satuan" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="berat">Berat</label>
                                        <input type="text" name="modal_berat" id="modal_berat" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="kubikasi">Kubikasi</label>
                                        <input type="text" name="modal_kubikasi" id="modal_kubikasi" class="form-control" required>
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

            <?php elseif ($this->session->userdata('jobdesk') == 'LOGISTIK') : ?>
                <section class="content">
                    <div class="card">
                        <div class="card-body">
                            <div class="container-fluid">
                                <table class="table table-bordered table-striped" id="tb_masterbr_ics">
                                    <thead>
                                        <tr>
                                            <th>Nama Barang</th>
                                            <th>Bahan Aktif</th>
                                            <th>Satuan</th>
                                            <th>Dimensi</th>
                                            <th>Berat</th>
                                            <th>Kubikasi</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($mbarang as $br) :
                                        ?>
                                            <tr>
                                                <td><?= $br->nama_barang ?></td>
                                                <td><?= $br->bahan_aktif ?></td>
                                                <td><?= $br->satuan ?></td>
                                                <td><?= $br->dimensi ?></td>
                                                <td><?= $br->berat ?></td>
                                                <td><?= $br->kubikasi ?></td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-warning btn-open-mbarang" data-id="<?= $br->id ?>"><i class="fas fa-pen "></i></a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

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
            $('.btn-open-mbarang').on('click', function() {
                const rowId = $(this).data('id');

                $.ajax({
                    url: "<?= base_url('ics/get_detail_mbarang') ?>",
                    method: "POST",
                    data: {
                        id: rowId
                    },
                    dataType: "json",
                    success: function(data) {
                        if (!data) {
                            alert("Data tidak ditemukan.");
                            return;
                        }

                        $('#modal_id').val(data.id);
                        $('#modal_nama_barang').val(data.nama_barang);
                        $('#modal_kode_barang').val(data.kode_barang);
                        $('#modal_bahan_aktif').val(data.bahan_aktif);
                        $('#modal_satuan').val(data.satuan);
                        $('#modal_berat').val(data.berat);
                        $('#modal_kubikasi').val(data.kubikasi);
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
    <!-- 
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
    </script> -->