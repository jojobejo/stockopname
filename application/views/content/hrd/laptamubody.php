<body class="hold-transition sidebar-mini sidebar-collapse">
  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
    </div>

    <?php $this->load->view('partial/main/navbar') ?>
    <?php $this->load->view('partial/main/sidebar') ?>
    <?php $this->load->view('content/hrd/modallaptamuhrd') ?>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
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
              <?php if ($this->session->userdata('akses_lv') == '2' && $this->session->userdata('departemen') == 'SALES') : ?>
                <h3 class="card-title">LAPORAN TAMU</h3>
            </div>
            <div class="ml-2">

              <div class="card-body">
                <table id="tb_lap_distribusi" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th hidden>id</th>
                      <th>Tanggal</th>
                      <th>Nama</th>
                      <th>Perusahaan</th>
                      <th>Alamat</th>
                      <th>Jumlah Personil</th>
                      <th>Tujuan</th>
                      <th>Jam Masuk</th>
                      <th>Jam Keluar</th>
                      <th>Keterangan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($laporan as $l) : ?>

                      <tr>
                        <th hidden><?= $l->id ?></th>
                        <th><?= $l->tanggal ?></th>
                        <th><?= $l->nama ?></th>
                        <th><?= $l->perusahaan ?></th>
                        <th><?= $l->alamat ?></th>
                        <th><?= $l->jumlahpersonil ?></th>
                        <th><?= $l->tujuan ?></th>
                        <th><?= $l->jammasuk ?></th>
                        <th><?= $l->jamkeluar ?></th>
                        <th><?= $l->keterangan ?></th>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>

              <?php elseif ($this->session->userdata('akses_lv') == '2' && $this->session->userdata('departemen') != 'SALES') : ?>
                <h3 class="card-title">LAPORAN HRD TAMU</h3>
              </div>
              <div class="ml-2">
                <a href="<?= base_url('hrd_add_tamu') ?>" type="button" class="btn btn-primary">
                  <i class="fas fa-pen"></i>
                  Input Tamu Masuk
                </a>
              </div>
              <div class="card-body">
                <table id="tb_lap_distribusi" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th hidden>id</th>
                      <th>Tanggal</th>
                      <th>Nama</th>
                      <th>Perusahaan</th>
                      <th>Alamat</th>
                      <th>Jumlah Personil</th>
                      <th>Tujuan</th>
                      <th>Jam Masuk</th>
                      <th>Jam Keluar</th>
                      <th>Keterangan</th>
                      <th>#</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($laporan as $l) : ?>

                      <tr>
                        <th hidden><?= $l->id ?></th>
                        <th><?= $l->tanggal ?></th>
                        <th><?= $l->nama ?></th>
                        <th><?= $l->perusahaan ?></th>
                        <th><?= $l->alamat ?></th>
                        <th><?= $l->jumlahpersonil ?></th>
                        <th><?= $l->tujuan ?></th>
                        <th><?= $l->jammasuk ?></th>
                        <th><?= $l->jamkeluar ?></th>
                        <th><?= $l->keterangan ?></th>
                        <th>
                          <div class="row">
                            <a href="#" class="btn btn-warning btn-sm " data-toggle="modal" data-target="#edittamu<?= $l->id ?>">
                              <i class="fa fa-solid fa-pencil-alt"></i>
                            </a>
                            <a href="#" class="btn btn-danger btn-sm " data-toggle="modal" data-target="#hapuslaptamu<?= $l->id ?>">
                              <i class="fa fa-solid fa-trash-alt"></i>
                            </a>
                          </div>
                        </th>
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