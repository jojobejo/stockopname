<!-- views/content/logistik/body_detaildo.php -->
<style>
    table {
        font-size: 14px;
        white-space: nowrap;
    }

    th,
    td {
        vertical-align: middle;
        text-align: center;
    }

    .table thead th {
        background-color: #343a40;
        color: #fff;
    }
</style>

<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>

        <!-- Content Wrapper. Contains page content -->
        <?php foreach ($dostatus as $d) : ?>

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row">
                            <?php if ($this->session->userdata('jobdesk') == 'ADMINKEUTC') : ?>
                                <a href="<?= base_url('keuangan') ?>" class="btn btn-primary mb-2 ml-2"><i class="fas fa-arrow-circle-left"></i></a>
                            <?php elseif ($this->session->userdata('jobdesk') == 'LOGISTIK') : ?>
                                <a href="<?= base_url('logistik') ?>" class="btn btn-primary mb-2 ml-2"><i class="fas fa-arrow-circle-left"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <section class="content">

                    <div class="container-fluid">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h3 class="card-title">Rencana Pengiriman Barang</h3>
                            </div>

                            <div class="card-body">
                                <?php if ($this->session->userdata('jobdesk') == 'LOGISTIK') : ?>
                                    <div class="row mb-4">
                                        <div class="col-auto">
                                            <h2>Detail Orders</h2>
                                        </div>
                                        <div class="col-auto">
                                            <?php if ($d->status == '1') : ?>
                                                <div class="row">
                                                    <div class="col-auto">
                                                        <a href="#" class="btn btn-warning">DRAFT</a>
                                                    </div>
                                                    <div class="col-auto">
                                                        <?php foreach ($kdo as $k) : ?>
                                                            <a href="<?= base_url('list_faktur/') . $k->kd_do ?>" class="btn btn-info">
                                                                <i class="fas fa-plus"></i> Tambah Faktur
                                                            </a>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>

                                            <?php elseif ($d->status == '2' && ($d->sales_confirm_status === 'belum_siap')) : ?>
                                                <div class="row">
                                                    <div class="col-auto">
                                                        <span class="btn btn-danger disabled">
                                                            <i class="fas fa-times-circle"></i> Belum Siap Loading
                                                        </span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <small class="text-muted">
                                                            Dikonfirmasi oleh: <strong><?= htmlspecialchars($d->sales_confirm_by ?? '-') ?></strong>
                                                            <?php if (!empty($d->sales_confirm_note)) : ?>
                                                                &mdash; Catatan: <em><?= htmlspecialchars($d->sales_confirm_note) ?></em>
                                                            <?php endif; ?>
                                                        </small>
                                                    </div>
                                                    <div class="col-auto">
                                                        <button type="button" class="btn btn-warning" id="btnunpost" data-kd="<?= $d->kd_do ?>">
                                                            <i class="fas fa-redo"></i> REPOST
                                                        </button>
                                                    </div>
                                                </div>

                                            <?php elseif ($d->status == '2') : ?>
                                                <div class="row">
                                                    <div class="col-auto">
                                                        <span class="btn btn-info disabled">
                                                            <i class="fas fa-clock"></i> Menunggu Konfirmasi Sales
                                                        </span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <button type="button" class="btn btn-warning" id="btnunpost" data-kd="<?= $d->kd_do ?>">
                                                            <i class="fas fa-redo"></i> REPOST
                                                        </button>
                                                    </div>
                                                </div>

                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php elseif ($this->session->userdata('jobdesk') == 'ADMINKEUTC') : ?>
                                <?php endif; ?>
                                <?php foreach ($kdo as $k) : ?>
                                    <input type="hidden" id="do_isi" name="do_isi" value="<?= $k->kd_do ?>">
                                    <?php $this->load->view('content/logistik/modal/modal_detail_do'); ?>
                                    <div class="mb-2 d-flex">
                                        <div class="me-3 fw-semibold" style="width: 180px;">Kode Faktur</div>
                                        <div>: <?= $k->kd_do ?></div>
                                        <?php foreach ($dostatus as $ds) : $date = date('d/m/Y') ?>
                                            <div class="col-auto" hidden>
                                                <input type="text" class="form-control" value="<?= $ds->driver ?>" name="print_driver" id="print_driver">
                                                <input type="text" class="form-control" value="<?= $date ?>" name="print_tgl" id="print_tgl">
                                                <input type="text" class="form-control" value="<?= $ds->nolambung ?>" name="print_plat" id="print_plat">
                                                <input type="text" class="form-control" value="<?= $ds->status ?>" name="print_status" id="print_status">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <div class="mb-2 d-flex">
                                        <div class="me-3 fw-semibold" style="width: 180px;">Regional Pengiriman</div>
                                        <div>: <?= $k->regional ?></div>
                                        <div><a href="#" data-toggle="modal" data-target="#edited_rute" class="btn btn-warning btn-sm ml-2"><i class="fas fa-pencil-alt"></i></a></div>
                                    </div>
                                    <div class="mb-2 d-flex">
                                        <div class="me-3 fw-semibold" style="width: 180px;">Total Customer</div>
                                        <div>: <?= $k->totalfaktur ?></div>
                                    </div>
                                    <div class="mb-2 d-flex">
                                        <div class="me-3 fw-semibold" style="width: 180px;">Total Barang</div>
                                        <div>: <?= $k->total_barang ?></div>
                                    </div>
                                    <div class="mb-2 d-flex">
                                        <div class="me-3 fw-semibold" style="width: 180px;">Total Tonase</div>
                                        <div>: <?= $k->total_tonase_faktur . ' (TON)' ?></div>
                                    </div>
                                    <div class="mb-2 d-flex">
                                        <div class="me-3 fw-semibold" style="width: 180px;">Total Kubikasi</div>
                                        <div>: <?= $k->total_kubikasi . ' (m³)' ?></div>
                                    </div>

                                    <!-- FORM START -->
                                    <?php if ($this->session->userdata('jobdesk') == 'LOGISTIK') : ?>
                                        <?php if ($d->status == '1' || ($d->status == '2' && $d->sales_confirm_status === 'belum_siap')) : ?>
                                            <div class="row mb-2">
                                                <div class="col-md" hidden>
                                                    <input type="text" class="form-control" value="<?= $k->kd_do ?>" name="do_isi" id="do_isi" readonly>
                                                </div>
                                                <div class="col-md">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                        </div>
                                                        <input type="date" class="form-control" placeholder="Tanggal Kirim"
                                                            value="<?= $d->tgl_pengiriman ?? '' ?>"
                                                            name="tgl_isi" id="tgl_isi">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-md">
                                                    <div class="d-flex align-items-center flex-wrap gap-3 pt-2">
                                                        <span class="me-3 fw-semibold">Pilih Pengiriman:</span>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="jenis_pengiriman"
                                                                id="pengiriman_kantor" value="expedisi_kantor" checked>
                                                            <label class="form-check-label" for="pengiriman_kantor">Expedisi Kantor</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="jenis_pengiriman"
                                                                id="pengiriman_luar" value="expedisi_luar">
                                                            <label class="form-check-label" for="pengiriman_luar">Expedisi Luar</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-md" id="select_driver_wrapper">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-truck"></i></span>
                                                        </div>
                                                        <select name="driver_isi" id="driver_isi" class="form-control" required>
                                                            <option value="" selected disabled>-- Pilih Driver --</option>
                                                            <?php foreach ($driver as $driver) : ?>
                                                                <option value="<?= $driver->kd_driver ?>"
                                                                    <?= ($d->driver == $driver->kd_driver) ? 'selected' : '' ?>>
                                                                    <?= $driver->nama_driver ?>
                                                                </option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md" id="select_truck_wrapper">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-clipboard"></i></span>
                                                        </div>
                                                        <select name="truck_isi" id="truck_isi" class="form-control" required>
                                                            <option value="" selected disabled>-- Pilih Kendaraan --</option>
                                                            <?php foreach ($truck as $truck) : ?>
                                                                <option value="<?= $truck->id ?>"
                                                                    <?= ($d->nolambung == $truck->id) ? 'selected' : '' ?>>
                                                                    <?= $truck->nm_truk ?>
                                                                </option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md d-none" id="input_driver_luar_wrapper">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" name="driver_luar_isi" id="driver_luar_isi"
                                                            placeholder="Nama Driver">
                                                    </div>
                                                </div>

                                                <div class="col-md d-none" id="input_truck_luar_wrapper">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-truck-moving"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" name="truck_luar_isi" id="truck_luar_isi"
                                                            placeholder="No Lambung Truk">
                                                    </div>
                                                </div>
                                            </div>

                                        <?php else : ?>
                                            {{-- Status lain: tampilkan info saja --}}
                                        <?php endif; ?>
                                    <?php elseif ($this->session->userdata('jobdesk') == 'ADMINKEUTC') : ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <!-- END FORM -->
                                <?php if ($this->session->userdata('jobdesk') == 'LOGISTIK') : ?>
                                    <table class="table table-bordered" id="tb_checker_do">
                                        <thead>
                                            <tr>
                                                <?php if ($d->status == '1') : ?>
                                                    <th rowspan="2">#</th>
                                                <?php elseif ($d->status == '2') : ?>
                                                <?php endif; ?>
                                                <th colspan="2">Data Kios</th>
                                                <th rowspan="2">Rute</th>
                                                <th colspan="2">TTB</th>
                                                <!-- <th rowspan="2">No</th> -->
                                                <th rowspan="2">NOTE FAKTUR</th>
                                                <th rowspan="2">Nama Barang</th>
                                                <th rowspan="2">No Lot</th>
                                                <th colspan="2">Qty</th>
                                                <th rowspan="2">Qty</th>
                                            </tr>
                                            <tr>
                                                <th>Nama Kios</th>
                                                <th>Regional</th>
                                                <th>Kode Faktur</th>
                                                <th>Tgl Input</th>
                                                <th>Besar</th>
                                                <th>Kecil</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $prev_norut = null;
                                            $rowspan_count = [];
                                            $norut_counter = 1;

                                            foreach ($data_list as $row) {
                                                if (!isset($rowspan_count[$row->kd_faktur])) {
                                                    $rowspan_count[$row->kd_faktur] = 0;
                                                }
                                                $rowspan_count[$row->kd_faktur]++;
                                            }

                                            $printed_faktur = [];
                                            foreach ($data_list as $row) :
                                                $show_faktur_info = !in_array($row->kd_faktur, $printed_faktur);
                                                if ($show_faktur_info) {
                                                    $printed_faktur[] = $row->kd_faktur;
                                                    $norut_counter = 1;
                                                }
                                            ?>
                                                <tr>
                                                    <?php if ($show_faktur_info) :
                                                        if ($row->telp1 == NULL || "0") {
                                                            $telp1 = "-";
                                                        } else {
                                                            $telp1 = $row->telp1;
                                                        }

                                                        if ($row->telp2 == NULL || "0") {
                                                            $telp2 = "-";
                                                        } else {
                                                            $telp2 = $row->telp2;
                                                        }
                                                    ?>
                                                        <?php if ($d->status == '1') : ?>
                                                            <td rowspan="<?= $rowspan_count[$row->kd_faktur] ?>">
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <a href="<?= base_url('cancel_fk/' . $row->kd_faktur . '/' . $row->kd_do) ?>" class="btn btn-sm btn-block btn-danger"><i class="fas fa-times-circle"></i></a>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <button type="button" class="btn btn-sm btn-block btn-primary btn-edit-note-faktur" data-toggle="modal" data-target="#modal_note_faktur" data-kd_faktur="<?= htmlspecialchars($row->kd_faktur, ENT_QUOTES, 'UTF-8') ?>" data-note_faktur="<?= htmlspecialchars($row->note_faktur, ENT_QUOTES, 'UTF-8') ?>">
                                                                            <i class="fas fa-envelope"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        <?php elseif ($d->status == '2') : ?>
                                                        <?php endif; ?>
                                                        <td rowspan="<?= $rowspan_count[$row->kd_faktur] ?>"><?= $row->nama_kios ?><br><?= "(" . $telp1 . "/" . $telp2 . ")" ?></td>
                                                        <td rowspan="<?= $rowspan_count[$row->kd_faktur] ?>"><?= $row->regional ?></td>
                                                        <td rowspan="<?= $rowspan_count[$row->kd_faktur] ?>"><?= $row->kd_rute ?></td>
                                                        <td rowspan="<?= $rowspan_count[$row->kd_faktur] ?>"><?= $row->kd_faktur ?></td>
                                                        <td rowspan="<?= $rowspan_count[$row->kd_faktur] ?>"><?= $row->tgl_transaksi ?></td>
                                                        <td rowspan="<?= $rowspan_count[$row->kd_faktur] ?>"><?= $row->note_faktur != '' ? $row->note_faktur : '-' ?></td>

                                                    <?php endif; ?>
                                                    <!-- <td><?= $norut_counter++ ?></td> -->
                                                    <td><?= $row->nm_barang ?></td>
                                                    <td><?= $row->no_lot ?> - <?= $row->tgl_exp ?></td>
                                                    <td><?= $row->qty_box ?></td>
                                                    <td><?= $row->qty_pcs ?></td>
                                                    <td><?= $row->qty ?></td>

                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <?php foreach ($kdo as $k) : ?>
                                        <div class="row">
                                            <?php if ($d->status == '1' || ($d->status == '2' && $d->sales_confirm_status === 'belum_siap')) : ?>

                                                <div class="col">
                                                    <button type="button" class="btn btn-success w-100 mt-3" id="draftpost">
                                                        <i class="fas fa-check-double"></i>
                                                        <?= ($d->status == '2') ? 'Rekam Ulang & Kirim ke Sales' : 'Rekam Draft Order' ?>
                                                    </button>
                                                </div>
                                                <div class="col">
                                                    <button type="button" class="btn btn-info btn-block mt-3" id="btnPrintOrder" data-kd="<?= $k->kd_do ?>">
                                                        <i class="fas fa-print"></i> Print Order
                                                    </button>
                                                </div>
                                                <div class="col">
                                                    <button type="button" class="btn btn-primary btn-block mt-3" id="btnPrintRegis" data-kd="<?= $k->kd_do ?>">
                                                        <i class="fas fa-print"></i> Print Register
                                                    </button>
                                                </div>
                                                <div class="col">
                                                    <button type="button" class="btn btn-warning btn-block mt-3" id="btnPrintChecker" data-kd="<?= $k->kd_do ?>">
                                                        <i class="fas fa-print"></i> Print Checker
                                                    </button>
                                                </div>

                                            <?php else : ?>
                                                <div class="col">
                                                    <button type="button" class="btn btn-info btn-block mt-3" id="btnPrintOrder1" data-kd="<?= $k->kd_do ?>">
                                                        <i class="fas fa-print"></i> Print Order
                                                    </button>
                                                </div>
                                                <div class="col">
                                                    <button type="button" class="btn btn-primary btn-block mt-3" id="btnPrintRegis1" data-kd="<?= $k->kd_do ?>">
                                                        <i class="fas fa-print"></i> Print Register
                                                    </button>
                                                </div>
                                                <div class="col">
                                                    <button type="button" class="btn btn-warning btn-block mt-3" id="btnPrintChecker1" data-kd="<?= $k->kd_do ?>">
                                                        <i class="fas fa-print"></i> Print Checker
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                    <!-- LOGISTIK END -->

                                    <!-- TC START -->
                                <?php elseif ($this->session->userdata('jobdesk') == 'ADMINKEUTC') : ?>
                                    <table class="table table-bordered" id="tb_checker_do">
                                        <thead>
                                            <tr>
                                                <th colspan="2">Data Kios</th>
                                                <th rowspan="2">Rute</th>
                                                <th colspan="3">TTB</th>
                                                <th rowspan="2">Cash / Tempo</th>
                                            </tr>
                                            <tr>
                                                <th>Nama Kios</th>
                                                <th>Regional</th>
                                                <th>Kode Faktur</th>
                                                <th>Tgl Input</th>
                                                <th>Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $prev_norut = null;
                                            $rowspan_count = [];
                                            $norut_counter = 1;

                                            foreach ($datatc as $row) {
                                                if (!isset($rowspan_count[$row->kd_faktur])) {
                                                    $rowspan_count[$row->kd_faktur] = 0;
                                                }
                                                $rowspan_count[$row->kd_faktur]++;
                                            }

                                            $printed_faktur = [];
                                            foreach ($datatc as $row) :
                                                $show_faktur_info = !in_array($row->kd_faktur, $printed_faktur);
                                                if ($show_faktur_info) {
                                                    $printed_faktur[] = $row->kd_faktur;
                                                }
                                                if ($row->jtempo == '0') {
                                                    $tempo = '<span class="badge badge-primary">Cash</span>';
                                                } else if ($row->jtempo == '30') {
                                                    $tempo = '<span class="badge badge-warning">' . htmlspecialchars($row->jtempo) . '</span>';
                                                } else {
                                                    $tempo = '<span class="badge badge-success">' . htmlspecialchars($row->jtempo) . '</span>';
                                                }
                                            ?>
                                                <tr>
                                                    <?php if ($show_faktur_info) : ?>
                                                        <td rowspan="<?= $rowspan_count[$row->kd_faktur] ?>"><?= $row->nama_kios ?></td>
                                                        <td rowspan="<?= $rowspan_count[$row->kd_faktur] ?>"><?= $row->regional ?></td>
                                                        <td rowspan="<?= $rowspan_count[$row->kd_faktur] ?>"><?= $row->kd_rute ?></td>
                                                        <td rowspan="<?= $rowspan_count[$row->kd_faktur] ?>"><?= $row->kd_faktur ?></td>
                                                        <td rowspan="<?= $rowspan_count[$row->kd_faktur] ?>"><?= $row->tgl_transaksi ?></td>
                                                    <?php endif; ?>
                                                    <td><?= format_rupiah($row->nominal_p) ?></td>
                                                    <td><?= $tempo ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>

                </section>
            </div>
        <?php endforeach; ?>

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

    <div class="modal fade" id="modal_note_faktur" tabindex="-1" role="dialog" aria-labelledby="modalNoteFakturLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="<?= current_url(); ?>" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalNoteFakturLabel">Update Note Faktur</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="kd_faktur" id="modal_kd_faktur">
                        <div class="form-group">
                            <label for="modal_note_faktur_input">Note Faktur</label>
                            <input type="text" class="form-control" name="note_faktur" id="modal_note_faktur_input" placeholder="Input note faktur" autocomplete="off">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function getJenisPengiriman() {
            return $("input[name='jenis_pengiriman']:checked").val() || "expedisi_kantor";
        }

        function isExpedisiLuar() {
            return getJenisPengiriman() === "expedisi_luar";
        }

        function getDriverValue() {
            if (isExpedisiLuar()) {
                return ($("#driver_luar_isi").val() || "").trim();
            }
            return $("#driver_isi").val();
        }

        function getTruckValue() {
            if (isExpedisiLuar()) {
                return ($("#truck_luar_isi").val() || "").trim();
            }
            return $("#truck_isi").val();
        }

        function togglePengirimanFields() {
            var luar = isExpedisiLuar();

            if (luar) {
                $("#select_driver_wrapper, #select_truck_wrapper").addClass("d-none");
                $("#input_driver_luar_wrapper, #input_truck_luar_wrapper").removeClass("d-none");
                $("#driver_isi, #truck_isi").prop("required", false);
                $("#driver_luar_isi, #truck_luar_isi").prop("required", true);
            } else {
                $("#select_driver_wrapper, #select_truck_wrapper").removeClass("d-none");
                $("#input_driver_luar_wrapper, #input_truck_luar_wrapper").addClass("d-none");
                $("#driver_isi, #truck_isi").prop("required", true);
                $("#driver_luar_isi, #truck_luar_isi").prop("required", false);
            }
        }

        function resetFieldBorder() {
            $(".form-control").css("border", "");
        }

        function validatePengirimanInput(tgl_krim, driver, platno) {
            var valid = true;
            var luar = isExpedisiLuar();

            if (!tgl_krim) {
                $("#tgl_isi").css("border", "2px solid red");
                valid = false;
            }

            if (!driver) {
                if (luar) {
                    $("#driver_luar_isi").css("border", "2px solid red");
                } else {
                    $("#driver_isi").css("border", "2px solid red");
                }
                valid = false;
            }

            if (!platno) {
                if (luar) {
                    $("#truck_luar_isi").css("border", "2px solid red");
                } else {
                    $("#truck_isi").css("border", "2px solid red");
                }
                valid = false;
            }

            return valid;
        }

        $(document).ready(function() {
            togglePengirimanFields();
            $("input[name='jenis_pengiriman']").on("change", togglePengirimanFields);

            $("#modal_note_faktur").on("show.bs.modal", function(event) {
                var button = $(event.relatedTarget);
                var kdFaktur = button.data("kd_faktur") || "";
                var noteFaktur = button.data("note_faktur") || "";

                $("#modal_kd_faktur").val(kdFaktur);
                $("#modal_note_faktur_input").val(noteFaktur);
            });

            $("#draftpost").on('click', function() {
                var kd_do = $("#do_isi").val().trim();
                var tgl_krim = $("#tgl_isi").val();
                var platno = getTruckValue();
                var driver = getDriverValue();
                var jenis_pengiriman = getJenisPengiriman();

                resetFieldBorder();

                if (!validatePengirimanInput(tgl_krim, driver, platno)) {
                    alert("Lengkapi semua field terlebih dahulu.");
                    return;
                }

                $.ajax({
                    url: "<?= base_url('rekam_order_check') ?>",
                    type: "POST",
                    data: {
                        kd_do: kd_do,
                        tgl_krim: tgl_krim,
                        platno: platno,
                        driver: driver,
                        jenis_pengiriman: jenis_pengiriman
                    },
                    dataType: "JSON",
                    success: function(data) {
                        console.log(data);
                        if (data.msg === "success") {
                            alert('Data berhasil direkam');
                            window.location.href = "<?= base_url('detail_do/') ?>" + kd_do;
                        } else {
                            alert(data.message || 'Ada kesalahan data');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Terjadi kesalahan: ' + error);
                    }
                });
            });
        });

        $("#btnunpost").on('click', function() {
            var kd_do = $(this).data('kd');

            $.ajax({
                url: "<?= base_url('do/repost_status') ?>",
                type: "POST",
                data: {
                    kd_do: kd_do,
                    status: "1"
                },
                dataType: "JSON",
                success: function(response) {
                    if (response.msg === "success") {
                        alert("Data berhasil di-repost!");
                        window.location.href = "<?= base_url('detail_do/') ?>" + kd_do;
                    } else {
                        alert(response.message || "Gagal mengubah status");
                    }
                },
                error: function(xhr, status, error) {
                    alert("Terjadi kesalahan: " + error);
                }
            });

            $.ajax({
                url: "<?= base_url('do/delete_ics_do') ?>",
                type: 'POST',
                data: {
                    kd_do: kd_do
                },
                dataType: 'json',
                success: function(response) {
                    if (response.msg === 'success') {
                        alert('Data berhasil dihapus');
                    } else {
                        alert('Gagal: ' + response.message);
                    }
                }
            });
        });



        $("#btnPrintOrder1").on('click', function() {
            var kd_do = $(this).data('kd');
            var status = $("#print_status").val();
            var plat = $("#print_plat").val();
            var tgl = $("#print_tgl").val();
            var drive = $("#print_driver").val();

            var printUrl = "<?= base_url('print_do/') ?>" + kd_do +
                "?tgl_kirim=" + encodeURIComponent(tgl) +
                "&driver=" + encodeURIComponent(drive) +
                "&plat=" + encodeURIComponent(plat);
            window.open(printUrl, "_blank");
        });

        $("#btnPrintOrder").on('click', function() {
            var kd_do = $(this).data('kd');
            var tgl_krim = $("#tgl_isi").val();
            var driver = getDriverValue();
            var platno = getTruckValue();

            resetFieldBorder();

            if (!validatePengirimanInput(tgl_krim, driver, platno)) {
                alert("Lengkapi semua field terlebih dahulu sebelum print.");
                return;
            }

            var printUrl = "<?= base_url('print_do/') ?>" + kd_do +
                "?tgl_kirim=" + encodeURIComponent(tgl_krim) +
                "&driver=" + encodeURIComponent(driver) +
                "&plat=" + encodeURIComponent(platno);

            window.open(printUrl, "_blank");
        });


        $("#btnPrintRegis").on('click', function() {
            var kd_do = $(this).data('kd');
            var tgl_krim = $("#tgl_isi").val();
            var driver = getDriverValue();
            var platno = getTruckValue();

            resetFieldBorder();
            if (!validatePengirimanInput(tgl_krim, driver, platno)) {
                alert("Lengkapi semua field terlebih dahulu sebelum print.");
                return;
            }
            var printUrl = "<?= base_url('print_regis/') ?>" + kd_do +
                "?tgl_kirim=" + encodeURIComponent(tgl_krim) +
                "&driver=" + encodeURIComponent(driver) +
                "&plat=" + encodeURIComponent(platno);
            window.open(printUrl, "_blank");
        });

        $("#btnPrintRegis1").on('click', function() {
            var kd_do = $(this).data('kd');
            var status = $("#print_status").val();
            var plat = $("#print_plat").val();
            var tgl = $("#print_tgl").val();
            var drive = $("#print_driver").val();

            var printUrl = "<?= base_url('print_regis/') ?>" + kd_do +
                "?tgl_kirim=" + encodeURIComponent(tgl) +
                "&driver=" + encodeURIComponent(drive) +
                "&plat=" + encodeURIComponent(plat);
            window.open(printUrl, "_blank");
        });

        $("#btnPrintChecker").on('click', function() {
            var kd_do = $(this).data('kd');
            var tgl_krim = $("#tgl_isi").val();
            var driver = getDriverValue();
            var platno = getTruckValue();

            resetFieldBorder();
            if (!validatePengirimanInput(tgl_krim, driver, platno)) {
                alert("Lengkapi semua field terlebih dahulu sebelum print.");
                return;
            }
            var printUrl = "<?= base_url('print_checker/') ?>" + kd_do +
                "?tgl_kirim=" + encodeURIComponent(tgl_krim) +
                "&driver=" + encodeURIComponent(driver) +
                "&plat=" + encodeURIComponent(platno);
            window.open(printUrl, "_blank");
        });

        $("#btnPrintChecker1").on('click', function() {
            var kd_do = $(this).data('kd');
            var status = $("#print_status").val();
            var plat = $("#print_plat").val();
            var tgl = $("#print_tgl").val();
            var drive = $("#print_driver").val();

            var printUrl = "<?= base_url('print_checker/') ?>" + kd_do +
                "?tgl_kirim=" + encodeURIComponent(tgl) +
                "&driver=" + encodeURIComponent(drive) +
                "&plat=" + encodeURIComponent(plat);
            window.open(printUrl, "_blank");

        });
    </script>