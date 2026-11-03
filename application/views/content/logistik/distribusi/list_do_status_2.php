<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-auto">
                            <a href="<?= base_url('logistik/distibusi/list_faktur_status') ?>" class="btn btn-secondary mb-2">
                                <i class="fas fa-arrow-left"></i> Kembali ke List Faktur Status
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="card">
                    <div class="card-body">
                        <h4>List Delivery Order Status </h4>

                        <table class="table table-bordered table-striped" id="tbl_do_status_2">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode DO</th>
                                    <th>Tgl Buat</th>
                                    <th>Tgl Pengiriman</th>
                                    <th>No Lambung</th>
                                    <th>Rute</th>
                                    <th>Total Faktur</th>
                                    <th>Total Barang</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($listdo_status2)) : ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($listdo_status2 as $row) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $row->kddo ?></td>
                                            <td><?= $row->createat ?></td>
                                            <td><?= !empty($row->tglkirim) ? $row->tglkirim : '-' ?></td>
                                            <td><?= !empty($row->nopol) ? $row->nopol : '-' ?></td>
                                            <td><?= !empty($row->rute) ? $row->rute : '-' ?></td>
                                            <td><?= (int) $row->totalfaktur ?></td>
                                            <td><?= (int) $row->totalbarang ?></td>
                                            <td><span class="badge badge-success">DONE</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">Data DO status tidak ditemukan</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>

        <footer class="main-footer">
            <strong>Copyright &copy; 2022 <a href="https://kiu.co.id">PT.KARISMA INDOARGO UNIVERSAL</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0
            </div>
        </footer>

        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>
</body>

<script>
    $(function() {
        $('#tbl_do_status_2').DataTable({
            paging: true,
            lengthChange: false,
            searching: true,
            ordering: true,
            info: true,
            autoWidth: false,
            responsive: true
        });
    });
</script>