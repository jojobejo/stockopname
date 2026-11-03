<style>
    .select2-container .select2-selection--single {
        height: 38px;
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
                    <div class="container-fluid">
                        <div class="row align-items-start">
                            <?php if ($this->session->userdata('jobdesk') == 'ADMINICS') : ?>
                                <div class="col-12 col-sm-6 col-md-2">
                                    <a href="<?= base_url('admstocktracking'); ?>" class="btn btn-primary w-10"><i class="fas fa-home"></i></a>
                                </div>
                            <?php else : ?>
                                <div class="col-12 col-sm-6 col-md-2">
                                    <a href="<?= base_url('usropname_input'); ?>" class="btn btn-primary w-10"><i class="fas fa-tasks"></i> Histori Input</a>
                                </div>
                            <?php endif; ?>
                            <button type="button" class="btn btn-warning d-none" id="btn_toggle_input">
                                Gunakan Input Manual
                            </button>
                        </div>
                    </div>
                </section>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <form id="formOpname">
                        <div class="form-group">
                            <label for="nama_barang">Nama Barang</label>
                            <select id="nama_barang" name="nama_barang" class="form-control" style="width:100%"></select>
                        </div>

                        <div class="form-row d-none" id="dimensi_group" hidden>
                            <div class="form-group col-md-4">
                                <label>Panjang (cm)</label>
                                <input type="text" class="form-control" id="p" name="p" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Lebar (cm)</label>
                                <input type="text" class="form-control" id="l" name="l" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Tinggi (cm)</label>
                                <input type="text" class="form-control" id="t" name="t" readonly>
                            </div>
                        </div>

                        <div class="form-group d-none" id="exp_date_group">
                            <label for="exp_date">Expired Date</label>
                            <select id="exp_date" name="exp_date" class="form-control" required></select>
                        </div>

                        <div class="form-group d-none" id="qty_form">
                            <label for="qty_box">Qty Box</label>
                            <input type="number" class="form-control" id="qty_box" name="qty_box" value="0" required>

                            <label for="qty_pcs" class="mt-2">Qty Pcs</label>
                            <input type="number" class="form-control" id="qty_pcs" name="qty_pcs" value="0" required>

                            <button type="submit" class="btn btn-primary btn-block mt-3">Simpan</button>
                        </div>

                        <div id="manual_input_group" class="d-none">
                            <div class="form-group">
                                <label for="exp_date_manual">Expired Date (Manual)</label>
                                <input type="Text" class="form-control" id="exp_date_manual" name="exp_date_manual" placeholder="Gunakan Format dd/mm/yyyy">
                            </div>
                            <div class="form-group">
                                <label for="qty_box_manual">Qty Box</label>
                                <input type="number" class="form-control" id="qty_box_manual" name="qty_box_manual" value="0">
                            </div>
                            <div class="form-group">
                                <label for="qty_pcs_manual">Qty Pcs</label>
                                <input type="number" class="form-control" id="qty_pcs_manual" name="qty_pcs_manual" value="0">
                            </div>

                        </div>
                        <button type="button" class="btn btn-success btn-block mt-2 d-none" id="btn_submit_manual">
                            Simpan Request Opname
                        </button>

                    </form>
                    <div id="alertMsg" class="alert alert-success mt-3 d-none" role="alert"></div>
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
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
    <script>
        $(document).ready(function() {

            let isManualMode = false;

            $('#btn_toggle_input').on('click', function() {
                isManualMode = !isManualMode;

                if (isManualMode) {
                    $('#manual_input_group').removeClass('d-none');
                    $('#btn_submit_manual').removeClass('d-none');
                    $('#exp_date_group, #qty_form').addClass('d-none');
                    $(this).text('Gunakan Dropdown Exp Date');
                } else {
                    $('#manual_input_group').addClass('d-none');
                    $('#btn_submit_manual').addClass('d-none');
                    $('#exp_date_group, #qty_form').removeClass('d-none');
                    $(this).text('Gunakan Input Manual');
                }
            });



            $('#nama_barang').select2({
                placeholder: 'Cari nama barang...',
                ajax: {
                    url: '<?= base_url("searchbarang") ?>',
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

            $('#nama_barang').on('change', function() {
                const nama_barang = $(this).val();

                $('#btn_toggle_input').removeClass('d-none');

                $('#exp_date_group').removeClass('d-none');
                $('#qty_form').addClass('d-none');
                $('#exp_date').empty().append('<option value="">Loading...</option>');

                $.ajax({
                    url: '<?= base_url("search_get_exp_date") ?>',
                    type: 'POST',
                    data: {
                        nama_barang
                    },
                    dataType: 'json',
                    success: function(res) {
                        $('#exp_date').empty().append('<option value="">Pilih Expired Date</option>');
                        $.each(res.exp_dates, function(i, val) {
                            $('#exp_date').append('<option value="' + val.exp_date + '">' + val.exp_date + '</option>');
                        });

                        $('#exp_date_group').removeClass('d-none');

                        const d = res.dimensi;
                        if (d && d.p && d.l && d.t) {
                            $('#p').val(d.p);
                            $('#l').val(d.l);
                            $('#t').val(d.t);
                            $('#dimensi_group').removeClass('d-none');
                        } else {
                            $('#p, #l, #t').val('');
                            $('#dimensi_group').addClass('d-none');
                        }

                        $('#qty_form').addClass('d-none');
                        $('#info_boxpcs').text('');
                    }
                });
            });


            $('#exp_date').on('change', function() {
                $('#qty_form').removeClass('d-none');
            });



            $('#btn_submit_manual').on('click', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '<?= base_url("request_opname") ?>',
                    type: 'POST',
                    data: $('#formOpname').serialize(),
                    success: function(response) {
                        $('#btn_toggle_input').addClass('d-none');
                        $('#alertMsg').text("Data berhasil disimpan!").removeClass('d-none');
                        $('#formOpname')[0].reset();
                        $('#nama_barang').val(null).trigger('change');

                        $('#exp_date_group, #qty_form, #manual_input_group, #btn_submit_manual').addClass('d-none');
                        $('#btn_toggle_input').text('Gunakan Input Manual');
                        isManualMode = false;

                        setTimeout(() => $('#alertMsg').addClass('d-none'), 2000);
                    }
                });
            });


            $('#formOpname').on('submit', function(e) {
                e.preventDefault();

                let url = '<?= base_url("save_opname") ?>';
                if (isManualMode) {
                    url = '<?= base_url("request_opname") ?>';
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#alertMsg').text("Data berhasil disimpan!").removeClass('d-none');
                        $('#formOpname')[0].reset();
                        $('#nama_barang').val(null).trigger('change');
                        $('#exp_date').empty().append('<option value="">Pilih Expired Date</option>');
                        $('#exp_date_group').addClass('d-none');
                        $('#qty_form, #manual_input_group, #dimensi_group, #btn_submit_manual').addClass('d-none');
                        $('#btn_toggle_input').text('Gunakan Input Manual').addClass('d-none');
                        isManualMode = false;
                        setTimeout(() => $('#alertMsg').addClass('d-none'), 2000);
                    }
                });
            });
        });
    </script>