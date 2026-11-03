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

                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <?php foreach ($get_barang as $nm) :
                                $dimensi = $nm->p * $nm->l * $nm->t;
                            ?>
                                <h5 class="card-title">Detail Inputer Barang - <b><?= $nm->nama_barang ?></b></h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-auto">
                                    <a href="<?= base_url('ics/ics_diffrent') ?>" class="btn btn-md btn-primary w-100 mb-3"><i class="fas fa-arrow-left"></i></a>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-md btn-success" data-toggle="modal" data-target="#modal_insert_new_exp">
                                        <i class="fas fa-plus"></i> Add Expired Baru
                                    </button>
                                </div>
                            </div>

                            <div class="col-auto">
                                <div class="form-group" style="position: relative;background: #fff;">
                                    <h5 class="card-title mt-2 mb-2"><b>Compare By Expired Date</b></h5>
                                    <table id="tb_exp_form" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Expired</th>
                                                <th>Qty</th>
                                                <th>DO</th>
                                                <th>LPB</th>
                                                <th>Retur Jual</th>
                                                <th>Retur Beli</th>
                                                <th>Qty All</th>
                                                <th>Fisik Qty</th>
                                                <th>Selisih</th>
                                                <th>Fisik BOX</th>
                                                <th>Fisik PCS</th>
                                                <th>PIC</th>
                                                <th>Wilayah</th>
                                                <th>Kordinat</th>
                                                <th>Status</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($list_stock_by_exp as $br) : ?>
                                                <tr>
                                                    <td><?= $br->expired ?></td>
                                                    <td><?= $br->qty ?></td>
                                                    <td><?= $br->do ?></td>
                                                    <td><?= $br->po ?></td>
                                                    <td><?= $br->qty_rjual ?></td>
                                                    <td><?= $br->qty_rbeli ?></td>
                                                    <td><?= $br->qty_all ?></td>
                                                    <td><?= $br->ics ?></td>
                                                    <td><?= $br->selisih ?></td>
                                                    <td><?= $br->qty_box ?></td>
                                                    <td><?= $br->qty_pcs ?></td>
                                                    <td><?= $br->PIC ?></td>
                                                    <td>
                                                        <?php if ($br->id_gudang == 0) : ?>
                                                            <button class="btn btn-sm btn-warning btn-update-wilayah w-100" data-toggle="modal" data-target="#modalUpdateGudang" data-id="<?= $br->id ?>">
                                                                <i class="fas fa-warehouse"></i> Update Gudang
                                                            </button>
                                                        <?php else : ?>
                                                            <?= $br->nama_gudang ?>
                                                        <?php endif; ?>
                                                    </td>

                                                    <td>
                                                        <?php if ($br->nama_wilayah == '' || empty($br->nama_wilayah)) : ?>
                                                            <button class="btn btn-sm btn-warning btn-update-kordinat w-100" data-toggle="modal" data-target="#modalUpdateWilayah" data-id="<?= $br->id ?>" data-gudang="<?= $br->id_gudang ?>">
                                                                <i class="fas fa-map-marker-alt"></i> Update Wilayah
                                                            </button>

                                                        <?php else : ?>
                                                            <?= $br->nama_wilayah ?>
                                                        <?php endif; ?>
                                                    </td>

                                                    <?php if ($br->status == '1') : ?>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <button class="btn btn-md btn-success btn-add-opname w-100" data-toggle="modal" data-target="#modalAddOpname<?= $br->opname_id ?>" data-exp="<?= $br->expired ?>" data-nama="<?= $nm->nama_barang ?>" data-id="<?= $br->opname_id ?>" data-kdbarang="<?= $br->kd_barang ?>" data-dimensi="<?= $br->dimensi ?>"><i class="fas fa-plus"></i></button>
                                                                </div>
                                                                <div class="col-6">
                                                                    <button class="btn btn-sm btn-info view-detail w-100" data-exp="<?= $br->expired ?>" data-nama="<?= $nm->nama_barang ?>" data-kode="<?= $nm->kd_barang ?>">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="#" class="btn btn-sm btn-success w-100"><i class="fas fa-check"></i></a>
                                                        </td>
                                                    <?php else : ?>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <button class="btn btn-md btn-success btn-add-opname w-100" data-toggle="modal" data-target="#modalAddOpname<?= $br->opname_id ?>" data-exp="<?= $br->expired ?>" data-nama="<?= $nm->nama_barang ?>" data-id="<?= $br->opname_id ?>" data-kdbarang="<?= $br->kd_barang ?>" data-dimensi="<?= $br->dimensi ?>"><i class="fas fa-plus"></i></button>
                                                                </div>
                                                                <div class="col-6">
                                                                    <button class="btn btn-sm btn-info view-detail w-100" data-exp="<?= $br->expired ?>" data-nama="<?= $nm->nama_barang ?>" data-kode="<?= $nm->kd_barang ?>">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="#" class="btn btn-sm btn-danger w-100"><i class="fas fa-times"></i></a>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3" id="card_detail" style="display: none;">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="card-title">Detail Data</h5>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-auto">
                                    <a href="#" class="btn btn-primary" id="btnShowDO">DO</a>
                                </div>
                                <div class="col-auto">
                                    <a href="#" class="btn btn-primary" id="btnShowLPB">LPB</a>
                                </div>
                                <div class="col-auto">
                                    <a href="#" class="btn btn-primary" id="btnShowretur">Retur</a>
                                </div>
                                <div class="col-auto">
                                    <a href="#" class="btn btn-primary" id="btnShowLogInpt">Log Input</a>
                                </div>
                            </div>

                            <div id="data_log_do" style="display: none;">
                                <table class="table table-bordered table-sm mb-2" id="tbdo_track_barang" style="display: none;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Faktur</th>
                                            <th>Tgl Transaksi</th>
                                            <th>Nama Customer</th>
                                            <th>Nama Kios</th>
                                            <th>Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <div id="data_log_retur" style="display: none;">
                                <table class="table table-bordered table-sm mb-2" id="tbretur_track_barang" style="display: none;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Faktur</th>
                                            <th>Tgl Transaksi</th>
                                            <th>Nama Barang</th>
                                            <th>Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <div id="data_log_lpb" style="display: none;">
                                <table class="table table-bordered table-sm" id="tblpb_track_barang">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Faktur</th>
                                            <th>Tgl Transaksi</th>
                                            <th>Qty</th>
                                            <th>Input At</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <div id="data_log_inpt" style="display: none;">
                                <table class="table table-bordered table-sm" id="tblog_track_barang">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Qty</th>
                                            <th>Keterangan</th>
                                            <th>User Input</th>
                                            <th>Waktu Input</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <?php foreach ($list_stock_by_exp as $br) : ?>
                        <div class="modal fade" id="modalAddOpname<?= $br->opname_id ?>" tabindex="-1" role="dialog" aria-labelledby="modalAddOpnameLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form action="<?= base_url('ics/sv_opname') ?>" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success">
                                            <h5 class="modal-title"><i class="fas fa-box"></i> Input Data Opname</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="nama_barang" value="<?= $nm->nama_barang ?>">
                                            <input type="hidden" name="id" value="<?= $br->opname_id ?>">
                                            <input type="hidden" name="kdbarang" value="<?= $br->kd_barang ?>">
                                            <input type="hidden" name="dimensi" value="<?= $br->dimensi ?>">
                                            <?php if ($br->opname_id == '') : ?>
                                                <input type="hidden" name="action" value="newopname">
                                            <?php else : ?>
                                                <input type="hidden" name="action" value="formbyexp">
                                            <?php endif; ?>
                                            <div class="form-group">
                                                <label for="exp_date">Expired Date</label>
                                                <input type="text" name="exp_date" class="form-control" value="<?= $br->expired ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="keterangan_isi">Keterangan</label>
                                                <textarea class="form-control" name="keterangan_isi" required placeholder="Tambahkan keterangan inputer"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="qty_box">Qty Box</label>
                                                <input type="number" name="qty_box" class="form-control" value="<?= $br->qty_box ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="qty_pcs">Qty Pcs</label>
                                                <input type="number" name="qty_pcs" class="form-control" value="<?= $br->qty_pcs ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="qty_pcs">Kalkulator</label>
                                                <div class="row">
                                                    <div class="col-auto">
                                                        <input type="text" class="form-control mathInput" placeholder="contoh: 10+5*4+10">
                                                    </div>
                                                    <div class="col-auto">
                                                        <a href="#" class="btn btn-success font-control btnmaths">Calculate</a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="result" style="margin-top:15px; font-weight:bold; color:blue;"></div>
                                            <div id="result" style="margin-top:15px; font-weight:bold; color:blue;"></div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <!-- Modal Insert New Expired -->
                    <div class="modal fade" id="modal_insert_new_exp" tabindex="-1" role="dialog" aria-labelledby="modalInsertNewExpLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form action="<?= base_url('ics/sv_opname') ?>" method="post">
                                <div class="modal-content">
                                    <div class="modal-header bg-success">
                                        <h5 class="modal-title" id="modalInsertNewExpLabel"><i class="fas fa-plus-circle"></i> Input Saldo Awal</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Nama Barang -->
                                        <div class="form-group" hidden>
                                            <label for="nama_barang">Nama Barang</label>
                                            <input type="text" name="nama_barang" class="form-control" value="<?= $nm->nama_barang ?>" required readonly>
                                            <input type="text" name="dimensi" class="form-control" value="<?= $dimensi ?>" required readonly>
                                            <input type="text" name="action" class="form-control" value="new_expired" required readonly>
                                            <input type="text" name="kdbarang" class="form-control" value="<?= $nm->kd_barang ?>" required readonly>
                                            <input type="text" name="keterangan_isi" class="form-control" value="input_expired_baru" required readonly>
                                        </div>
                                        <!-- Expired Date -->
                                        <div class="form-group">
                                            <label for="exp_date">Expired Date</label>
                                            <input type="date" name="exp_date" class="form-control" required>
                                        </div>
                                        <!-- Qty Box -->
                                        <div class="form-group" hidden>
                                            <label for="qty_box">Qty Box</label>
                                            <input type="number" name="qty_box" class="form-control" placeholder="0" value="0" required>
                                        </div>
                                        <!-- Qty Pcs -->
                                        <div class="form-group" hidden>
                                            <label for="qty_pcs">Qty Pcs</label>
                                            <input type="number" name="qty_pcs" class="form-control" placeholder="0" value="0" required>
                                        </div>
                                        <!-- Waktu Input -->
                                        <input type="hidden" name="input_at" value="<?= date('d/m/Y') ?>">
                                        <input type="hidden" name="create_at" value="<?= date('Y-m-d H:i:s') ?>">
                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Modal Setting Wilayah -->
                    <div class="modal fade" id="modalUpdateGudang">
                        <div class="modal-dialog">
                            <form id="formUpdateGudang">
                                <div class="modal-content">
                                    <div class="modal-header bg-warning">
                                        <h5 class="modal-title">Update Gudang</h5>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="opname_id" id="opname_id_gudang">

                                        <div class="form-group">
                                            <label>Gudang</label>
                                            <select name="id_gudang" class="form-control" required>
                                                <option value="">-- Pilih Gudang --</option>
                                                <?php foreach ($list_gudang as $g) : ?>
                                                    <option value="<?= $g->id_gudang ?>">
                                                        <?= $g->nama_gudang ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Modal Setting Wilayah Kordinat -->
                    <!-- Modal Setting Wilayah Gudang -->
                    <div class="modal fade" id="modalUpdateWilayah">
                        <div class="modal-dialog">
                            <form id="formUpdateWilayah">
                                <div class="modal-content">
                                    <div class="modal-header bg-warning">
                                        <h5 class="modal-title">Update Wilayah Gudang</h5>
                                    </div>
                                    <div class="modal-body">

                                        <!-- hidden, jangan sok tampil -->
                                        <input type="hidden" name="opname_id" id="opname_id_wilayah">
                                        <input type="hidden" id="selected_gudang_id">

                                        <div class="form-group">
                                            <label>Wilayah</label>
                                            <select name="id_wilayah" id="select_wilayah" class="form-control" required>
                                                <option value="">-- Pilih Wilayah --</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>




                </section>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2022 <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0
        </div>
    </footer>
    <aside class="control-sidebar control-sidebar-dark">
    </aside>
    </div>

    <script>
        $(document).ready(function() {

            $('.view-detail').on('click', function() {
                $('.view-detail').removeClass('btn-secondary').addClass('btn-info');
                $(this).removeClass('btn-info').addClass('btn-secondary');
            });

            $('#btnShowDO, #btnShowLPB, #btnShowretur, #btnShowLogInpt').on('click', function() {
                $('#btnShowDO, #btnShowLPB, #btnShowretur, #btnShowLogInpt').removeClass('btn-secondary').addClass('btn-primary');
                $(this).removeClass('btn-primary').addClass('btn-secondary');
            });

            $('#btnShowDO').click(function() {
                $('#data_log_do').show();
                $('#tbdo_track_barang').show();
                $('#data_log_lpb, #data_log_retur, #data_log_inpt').hide();
            });

            $('#btnShowLPB').click(function() {
                $('#data_log_lpb').show();
                $('#tblpb_track_barang').show();
                $('#data_log_do, #data_log_retur, #data_log_inpt').hide();
            });

            $('#btnShowretur').click(function() {
                $('#data_log_retur').show();
                $('#tbretur_track_barang').show();
                $('#data_log_do, #data_log_lpb, #data_log_inpt').hide();
            });

            $('#btnShowLogInpt').click(function() {
                $('#data_log_inpt').show();
                $('#tblog_track_barang').show();
                $('#data_log_do, #data_log_lpb, #data_log_retur').hide();
            });

            $('.btn-update-wilayah').on('click', function() {
                let opname_id = $(this).data('id');
                $('#opname_id_gudang').val(opname_id);
            });

            $('#formUpdateGudang').on('submit', function(e) {
                e.preventDefault();

                console.log($(this).serialize());

                $.ajax({
                    url: "<?= base_url('ics/update_gudang'); ?>",
                    type: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(res) {
                        if (res.status) {
                            location.reload();
                        } else {
                            alert(res.message);
                        }
                    }
                });

            });

            $('#formUpdateWilayah').on('submit', function(e) {
                e.preventDefault();

                console.log($(this).serialize());

                $.ajax({
                    url: "<?= base_url('ics/update_wilayah'); ?>",
                    type: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(res) {
                        if (res.status) {
                            location.reload();
                        } else {
                            alert(res.message);
                        }
                    }
                });

            });

            $('.btn-update-kordinat').on('click', function() {

                let saldo_id = $(this).data('id');
                let idGudang = $(this).data('gudang');

                console.log('ID GUDANG:', idGudang);

                $('#opname_id_wilayah').val(saldo_id);
                $('#selected_gudang_id').val(idGudang);

                $('#select_wilayah').html('<option value="">Loading...</option>');

                if (!idGudang || idGudang == 0) {
                    $('#select_wilayah').html('<option value="">Gudang belum ditentukan</option>');
                    return;
                }

                $.ajax({
                    url: "<?= base_url('ics/get_wilayah_by_gudang'); ?>",
                    type: 'POST',
                    data: {
                        id_gudang: idGudang
                    },
                    dataType: 'json',
                    success: function(res) {

                        let opt = '<option value="">-- Pilih Wilayah --</option>';

                        if (res.length > 0) {
                            $.each(res, function(i, v) {
                                opt += `<option value="${v.id_wilayah}">${v.nama_wilayah}</option>`;
                            });
                        } else {
                            opt += '<option value="">Wilayah tidak tersedia</option>';
                        }

                        $('#select_wilayah').html(opt);
                    }
                });
            });


        });
    </script>

    <script>
        $(document).ready(function() {
            $('.view-detail').click(function() {
                const nama_barang = $(this).data('nama');
                const exp_date = $(this).data('exp');
                const kd_barang = $(this).data('kode');

                $.ajax({
                    url: "<?= base_url('ics/get_detail_by_exp') ?>",
                    type: "POST",
                    data: {
                        nama_barang: nama_barang,
                        exp_date: exp_date,
                        kd_barang: kd_barang
                    },
                    dataType: "json",
                    success: function(res) {
                        $('#card_detail').show();

                        $('#tbdo_track_barang tbody').html('');
                        $('#tbretur_track_barang tbody').html('');
                        $('#tblpb_track_barang tbody').html('');
                        $('#tblog_track_barang tbody').html('');

                        let do_html = '';
                        $.each(res.data_do, function(i, item) {
                            do_html += `<tr>
                            <td>${i + 1}</td>
                            <td>${item.kd_faktur}</td>
                            <td>${item.tgl_transaksi}</td>
                            <td>${item.nm_customer}</td>
                            <td>${item.nm_kios}</td>  
                            <td>${item.qty}</td>
                        </tr>`;
                        });
                        $('#tbdo_track_barang tbody').html(do_html);

                        let po_html = '';
                        $.each(res.data_po, function(i, item) {
                            po_html += `<tr>
                            <td>${i + 1}</td>
                            <td>${item.kd_faktur_lpb}</td>
                            <td>${item.tgl_transaksi}</td>
                            <td>${item.qty}</td>
                            <td>${item.input_at}</td>
                        </tr>`;
                        });
                        $('#tblpb_track_barang tbody').html(po_html);

                        let retur_html = '';
                        $.each(res.data_retur, function(i, item) {
                            retur_html += `<tr>
                            <td>${i + 1}</td>
                            <td>${item.kd_faktur || '-'}</td>
                            <td>${item.tgl_transaksi || '-'}</td>
                            <td>${item.nama_barang || '-'}</td>
                            <td>${item.qty || 0}</td>
                        </tr>`;
                        });
                        $('#tbretur_track_barang tbody').html(retur_html);

                        let log_html = '';
                        $.each(res.data_log, function(i, item) {
                            log_html += `<tr>
                            <td>${i + 1}</td>
                            <td>${item.qty}</td>
                            <td>${item.keterangan}</td>
                            <td>${item.inputer}</td>
                            <td>${item.tgl_input}</td>
                        </tr>`;
                        });
                        $('#tblog_track_barang tbody').html(log_html);
                    }

                });
            });
        });





        $('.btn-add-opname').click(function() {
            const exp_date = $(this).data('exp');
            const nama_barang = $(this).data('nama');
            const id_barang = $(this).data('id');
            const kdbarang = $(this).data('kdbarang');
            const dimensi = $(this).data('dimensi');

            $('#modal_exp_date').val(exp_date);
            $('#modal_nama_barang').val(nama_barang);
            $('#modal_id_barang').val(id_barang);
            $('#modal_kdbarang').val(kdbarang);
            $('#modal_dimensi').val(dimensi);
        });

        $('.btn-update-wilayah').on('click', function() {
            $('#opname_id_gudang').val($(this).data('id'));
        });
    </script>

    <script>
        $(document).ready(function() {
            $('input[name="qty_box"], input[name="qty_pcs"]').on('input', function() {
                let qty_box = parseInt($('input[name="qty_box"]').val()) || 0;
                let qty_pcs = parseInt($('input[name="qty_pcs"]').val()) || 0;
                let total = qty_box + qty_pcs;
                $('#qty_total_input').val(total);
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".btnmaths").forEach(function(btn) {
                btn.addEventListener("click", function(e) {
                    e.preventDefault();
                    let parent = btn.closest(".modal-body"); // cari parent modal
                    let input = parent.querySelector(".mathInput").value.trim();
                    let resultBox = parent.querySelector(".result");

                    if (input === "") {
                        resultBox.innerHTML = "Masukkan ekspresi matematika!";
                        return;
                    }

                    // validasi hanya boleh angka, operator + - * / ( )
                    let validPattern = /^[0-9+\-*/().\s]+$/;
                    if (!validPattern.test(input)) {
                        resultBox.innerHTML = "Input hanya boleh angka dan operator + - * / ( )";
                        return;
                    }

                    try {
                        let result = Function("return " + input)();
                        resultBox.innerHTML = "= " + result;
                    } catch (err) {
                        resultBox.innerHTML = "Ekspresi tidak valid!";
                    }
                });
            });
        });
    </script>