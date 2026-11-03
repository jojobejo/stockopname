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
                        <div class="col-auto">
                            <a href="<?= base_url('ics/mutasi_barang') ?>" class="btn btn-md btn-primary">Dashboard Mutasi</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">

                            <div class="card">
                                <div class="card-body">

                                    <div class="form-group">
                                        <label>Gudang Aktif</label>
                                        <input type="text" id="gudang_aktif" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Barang</label>
                                        <select name="select_barang" id="select_barang" class="form-control"></select>
                                    </div>
                                    <div class="form-group">
                                        <label>Expired Date</label>
                                        <select name="select_exp" id="select_exp" class="form-control"></select>
                                    </div>
                                    <div class="form-group">
                                        <label>Qty Gudang</label>
                                        <input type="number" name="qtygudang" id="qtygudang" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Qty Input</label>
                                        <input type="number" name="qtyinput" id="qtyinput" class="form-control" value="">
                                    </div>
                                    <div class="form-group">
                                        <label>Satuan</label>
                                        <select name="satuan_id" id="satuan_id" class="form-control">
                                            <option value="1"> Pcs</option>
                                            <option value="2"> Box</option>
                                        </select>
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
                                            <input class="form-control col-4 ml-4" type="text" id="nofresnsi" name="nofresnsi" value="<?= $ref_mutasi ?>" readonly />
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
                                            <input class="form-control col-4 ml-4" type="text" id="keterangan_mutasi" name="keterangan_mutasi" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="row">
                                                    <label for="id_bar">Dari Gudang : </label>
                                                    <select name="fromgdg" id="fromgdg" class="form-control col-4 ml-4">
                                                        <?php foreach ($gudang as $gdg) : ?>
                                                            <option value="<?= $gdg->id_gudang ?>"><?= $gdg->nama_gudang ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="row">
                                                    <label for="id_bar">Ke Gudang : </label>
                                                    <select name="tujuangdg" id="tujuangdg" class="form-control col-4 ml-4">
                                                        <?php foreach ($gudang as $gdg) : ?>
                                                            <option value="<?= $gdg->id_gudang ?>"><?= $gdg->nama_gudang ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="modalTmpMutasi" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalTitle">Edit Temporary Mutasi</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>

                                                <div class="modal-body">
                                                    <input type="hidden" id="tmp_id">

                                                    <div class="form-group">
                                                        <label>Nama Barang</label>
                                                        <input type="text" id="tmp_nama_barang" class="form-control" readonly>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Expired Date</label>
                                                        <input type="text" id="tmp_exp_date" class="form-control" readonly>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Qty</label>
                                                        <input type="number" id="tmp_qty" class="form-control">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Satuan</label>
                                                        <select id="tmp_satuan_id" class="form-control">
                                                            <option value="1">Pcs</option>
                                                            <option value="2">Box</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" id="btnDeleteTmp" class="btn btn-danger">Delete</button>
                                                    <button type="button" id="btnUpdateTmp" class="btn btn-primary">Update</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-auto mt-4">
                                        <table id="input_tmp_mutasi" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Nama Barang</th>
                                                    <th>Expired Date</th>
                                                    <th>Qty</th>
                                                    <th>Satuan</th>
                                                    <th>#</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <a href="#" class="btn btn-success btn-block" id="rekammutasi"> Rekam</a>
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

    <script>
        $(document).ready(function() {

            const dropdownParent = $('.content-wrapper');
            loadTmpMutasi();


            // INIT BARANG (berdasarkan gudang)
            function initSelectBarang(id_gudang) {
                $('#select_barang').val(null).trigger('change');

                $('#select_barang').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    dropdownParent: dropdownParent,
                    placeholder: 'Pilih Barang',
                    allowClear: true,
                    ajax: {
                        url: '<?= base_url("ics/ajax_barang_by_gudang") ?>',
                        dataType: 'json',
                        delay: 250,
                        data: params => ({
                            term: params.term,
                            id_gudang: id_gudang
                        }),
                        processResults: data => ({
                            results: data
                        })
                    }
                });
            }

            // INIT EXPIRED (berdasarkan gudang + barang)
            function initSelectExp(id_gudang, nama_barang) {
                $('#select_exp').val(null).trigger('change');

                $('#select_exp').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    dropdownParent: dropdownParent,
                    placeholder: 'Pilih Expired Date',
                    allowClear: true,
                    ajax: {
                        url: '<?= base_url("ics/ajax_exp_by_gudang_barang") ?>',
                        dataType: 'json',
                        delay: 250,
                        data: () => ({
                            id_gudang: id_gudang,
                            nama_barang: nama_barang
                        }),
                        processResults: data => ({
                            results: data
                        })
                    }
                });
            }

            $(document).ready(function() {
                $('#fromgdg').val(2).trigger('change');
            });


            // GUDANG DIPILIH
            $('#fromgdg').on('change', function() {
                let id_gudang = $(this).val();
                let nama_gudang = $('#fromgdg option:selected').text();

                $('#gudang_aktif').val(nama_gudang);
                $('#qtygudang').val('');
                $('#select_exp').val(null).trigger('change');

                // reset tujuan gudang
                $('#tujuangdg option').prop('disabled', false).show();

                if (id_gudang) {
                    // sembunyikan gudang yang sama
                    $('#tujuangdg option[value="' + id_gudang + '"]')
                        .prop('disabled', true)
                        .hide();

                    // reset pilihan jika sama
                    $('#tujuangdg').val(null);

                    initSelectBarang(id_gudang);
                }
            });


            // $('#fromgdg').on('change', function() {
            //     let id_gudang = $(this).val();
            //     let nama_gudang = $('#fromgdg option:selected').text();

            //     $('#gudang_aktif').val(nama_gudang);
            //     $('#qtygudang').val('');
            //     $('#select_exp').val(null).trigger('change');

            //     if (id_gudang) {
            //         initSelectBarang(id_gudang);
            //     }
            // });

            // BARANG LOAD EXPIRED
            $('#select_barang').on('change', function() {
                let nama_barang = $(this).val();
                let id_gudang = $('#fromgdg').val();

                $('#qtygudang').val('');

                if (nama_barang && id_gudang) {
                    initSelectExp(id_gudang, nama_barang);
                }
            });

            // EXPIRED AUTO FILL QTY
            $('#select_exp').on('change', function() {

                let expired_date = $(this).val();
                let nama_barang = $('#select_barang').val();
                let id_gudang = $('#fromgdg').val();

                $('#qtygudang').val('');

                if (!expired_date || !nama_barang || !id_gudang) return;

                $.getJSON('<?= base_url("ics/ajax_get_qty_gudang") ?>', {
                    id_gudang: id_gudang,
                    nama_barang: nama_barang,
                    expired_date: expired_date,
                }, function(res) {
                    $('#qtygudang').val(res.qty ?? 0);
                });

            });

            $('#btninputdata').on('click', function(e) {
                e.preventDefault();

                $.post('<?= base_url("ics/ajax_add_tmp_mutasi") ?>', {
                    nama_barang: $('#select_barang').val(),
                    exp_date: $('#select_exp').val(),
                    qty: $('#qtyinput').val(),
                    satuan_id: $('#satuan_id').val()
                }, function(res) {
                    const r = JSON.parse(res);
                    if (r.status) {
                        loadTmpMutasi();
                        $('#qtyinput').val('');
                    } else {
                        alert(r.msg);
                    }
                });
            });



        });
    </script>
    <script>
        loadTmpMutasi();

        function loadTmpMutasi() {
            $.getJSON('<?= base_url("ics/ajax_list_tmp_mutasi") ?>', function(data) {
                let html = '';

                if (!data.length) {
                    html = `<tr><td colspan="5" class="text-center text-muted">Belum ada data</td></tr>`;
                } else {
                    data.forEach(r => {
                        html += `
                <tr>
                    <td>${r.nama_barang}</td>
                    <td>${r.exp_date}</td>
                    <td>${r.qty}</td>
                    <td>${r.satuan_id == 1 ? 'Pcs' : 'Box'}</td>
                    <td>
                        <button class="btn btn-sm btn-warning btn-edit"
                            data-id="${r.id}"
                            data-nama="${r.nama_barang}"
                            data-exp="${r.exp_date}"
                            data-qty="${r.qty}"
                            data-satuan="${r.satuan_id}">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                    </td>
                </tr>`;
                    });
                }

                $('#input_tmp_mutasi tbody').html(html);
            });
        }

        $(document).on('click', '.btn-edit', function() {
            $('#tmp_id').val($(this).data('id'));
            $('#tmp_nama_barang').val($(this).data('nama'));
            $('#tmp_exp_date').val($(this).data('exp'));
            $('#tmp_qty').val($(this).data('qty'));
            $('#tmp_satuan_id').val($(this).data('satuan'));

            $('#modalTmpMutasi').modal('show');
        });

        $('#btnUpdateTmp').on('click', function() {
            $.post('<?= base_url("ics/ajax_update_tmp_mutasi") ?>', {
                id: $('#tmp_id').val(),
                exp_date: $('#tmp_exp_date').val(),
                qty: $('#tmp_qty').val(),
                satuan_id: $('#tmp_satuan_id').val()
            }, function(res) {

                let r;
                try {
                    r = typeof res === 'object' ? res : JSON.parse(res);
                } catch (e) {
                    alert('Response tidak valid');
                    return;
                }

                if (r.status) {
                    $('#modalTmpMutasi').modal('hide');
                    loadTmpMutasi();
                } else {
                    alert(r.msg ?? 'Data tidak valid');
                }
            }, 'json');
        });


        $('#btnDeleteTmp').on('click', function() {
            if (!confirm('Hapus data ini?')) return;

            $.post('<?= base_url("ics/ajax_delete_tmp_mutasi") ?>', {
                id: $('#tmp_id').val()
            }, function() {
                $('#modalTmpMutasi').modal('hide');
                loadTmpMutasi();
            });
        });
    </script>

    <script>
        $('#rekammutasi').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                url: '<?= base_url("ics/ajax_rekam_mutasi") ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    nofresnsi: $('#nofresnsi').val(),
                    tgl_transaksi: $('#tgl_transaksi').val(),
                    keterangan_mutasi: $('#keterangan_mutasi').val(),
                    fromgdg: $('#fromgdg').val(),
                    tujuangdg: $('#tujuangdg').val()
                },
                success: function(res) {
                    if (res.status) {
                        alert(res.msg + '\nNo Ref: ' + res.noreff);
                        location.reload();
                    } else {
                        alert(res.msg);
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan sistem');
                }
            });
        });
    </script>