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
            <div class="card m-2">
                <div class="card-body">
                    <h3>Faktur Bintang Putri Karisma</h3>
                    <div class="row">
                        <div class="col-4">
                            <table id="tbfakturbintang" class="table table-bordered table-striped">
                                <thead style="background-color: #212529; color:white;">
                                    <tr>
                                        <td>Kode DO</td>
                                        <td>Rute</td>
                                        <td>Tanggal Transaksi</td>
                                        <td>#</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($fakturbintang as $fb) : ?>
                                        <tr>
                                            <td><?= $fb->kd_faktur ?></td>
                                            <td><?= $fb->kd_rute ?></td>
                                            <td><?= $fb->tgl_inputer ?></td>
                                            <td>
                                                <center>
                                                    <a href="javascript:void(0)" class="btn btn-primary btn-sm btn-cust-bintang" data-id="<?= $fb->id ?>">
                                                        <i class="fas fa-plus"></i>
                                                    </a>
                                                </center>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-8">
                            <div class="card card-outline card-primary">
                                <div class="card-body">

                                    <form id="formEditFaktur">
                                        <div class="form-group row">
                                            <label class="col-sm-12">Customer Lama</label>
                                            <div class="col-sm-12">
                                                <input class="form-control" id="nmcust" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row" id="wrap-select-customer" style="display:none">
                                            <label class="col-sm-12">Ganti Customer</label>
                                            <div class="col-sm-12">
                                                <select class="form-control select2" id="new_kd_customer" name="new_kd_customer" style="width:100%"></select>

                                                <input type="hidden" name="kdfaktur" id="kdfaktur">
                                                <input type="hidden" name="id_faktur" id="id_faktur">
                                                <input type="hidden" name="kdcust" id="kdcust">
                                            </div>
                                        </div>


                                        <button type="submit" class="btn btn-success btn-sm btn-block">
                                            Simpan Perubahan
                                        </button>
                                    </form>

                                </div>
                            </div>
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
            $(document).on('click', '.btn-cust-bintang', function() {
                const id = $(this).data('id');

                $.ajax({
                    url: "<?= base_url('get_fktur_bintang') ?>",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        id: id
                    },
                    success: function(res) {
                        if (res.status === 'ok') {
                            $('#nmcust').val(res.data.nama_customer);
                            $('#kdfaktur').val(res.data.kd_faktur);
                            $('#id_faktur').val(res.data.id);
                            $('#kdcust').val(res.data.kd_customer);

                            if (res.data.nama_customer) {
                                $('#wrap-select-customer').slideDown();
                            }

                            $('#new_kd_customer').val(null).trigger('change');
                        } else {
                            alert(res.message);
                        }
                    }
                });
            });

            $('#new_kd_customer').on('select2:select', function(e) {
                const data = e.params.data;
                $('#kdcust').val(data.id);
            });



            $(document).ready(function() {
                $('#wrap-select-customer').hide();

                $('.select2').select2({
                    theme: 'bootstrap4',
                    placeholder: 'Pilih customer baru',
                    allowClear: true,
                    ajax: {
                        url: "<?= base_url('get_customer_bintang') ?>",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                search: params.term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    }
                });
            });


            $('#formEditFaktur').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: "<?= base_url('update_customer_faktur') ?>",
                    type: "POST",
                    dataType: "JSON",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.status === 'ok') {
                            alert('Customer faktur berhasil diubah');
                            location.reload();
                        } else {
                            alert(res.message);
                        }
                    }
                });
            });
        </script>