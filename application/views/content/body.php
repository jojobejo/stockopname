<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
        </div>

        <?php $this->load->view('partial/main/navbar') ?>
        <?php $this->load->view('partial/main/sidebar') ?>
        <?php $this->load->view('content/modallaptamuhrd') ?>
        <!-- Content Wrapper. Contains page content -->

        <div class="content-wrapper">
            <?php if (
                $this->session->userdata('departemen') == 'KEUANGAN' && $this->session->userdata('username') == 'KIUTC3' ||
                $this->session->userdata('departemen') == 'KEUANGAN' && $this->session->userdata('username') == 'KIUTC2' ||
                $this->session->userdata('departemen') == 'KEUANGAN' && $this->session->userdata('username') == 'KIUTC1' ||
                $this->session->userdata('departemen') == 'KEUANGAN' && $this->session->userdata('username') == 'KIUTC4' ||
                $this->session->userdata('departemen') == 'KEUANGAN' && $this->session->userdata('username') == 'KIUTC5' ||
                $this->session->userdata('departemen') == 'KEUANGAN' && $this->session->userdata('username') == 'KIUTC6' ||
                $this->session->userdata('departemen') == 'KEUANGAN' && $this->session->userdata('username') == 'KIUTC7'
            ) : ?>
            <?php elseif ($this->session->userdata('departemen') == 'LOGISTIK' || $this->session->userdata('departemen') == 'IT') : ?>
                <!-- Content Header (Page header) -->
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->
                <section class="content">
                <?php elseif ($this->session->userdata('departemen') == 'SALES' || $this->session->userdata('departemen') == 'IT') : ?>
                    <!-- Content Header (Page header) -->
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- /.container-fluid -->
                    </div>
                    <!-- /.content-header -->
                    <section class="content">
                        <div class="container-fluid">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <h3 class="card-title font-weight-bold ">Tamu Masuk - Kantor</h3>
                                        <a href="<?= base_url('schedule_direktur'); ?>" class="btn btn-primary btn-sm ml-3"><i class="fa fa-calendar"></i><b style="text-transform:uppercase"> Schedule Direktur</b></a>
                                    </div>
                                </div>
                                <div class=" card-body">
                                    <table id="tb_list_order" class="table table-bordered table-striped" style="text-align: center;">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Tamu</th>
                                                <th>Perushaan</th>
                                                <th>Alamat</th>
                                                <th>Jumlah Personil</th>
                                                <th>Tujuan</th>
                                                <th>Jam Masuk</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            foreach ($tamu as $d) : ?>
                                                <tr>
                                                    <th><?= $no++ ?></th>
                                                    <th><?= $d->nama ?></th>
                                                    <th><?= $d->perusahaan ?></th>
                                                    <th><?= $d->alamat ?></th>
                                                    <th><?= $d->jumlahpersonil ?></th>
                                                    <th><?= $d->tujuan ?></th>
                                                    <th><?= $d->jammasuk ?></th>
                                                    <th><?= $d->keterangan ?></th>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </section>
            <?php endif; ?>


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
            setTimeout(function() {
                window.location.reload(1);
            }, 30000);
        </script>