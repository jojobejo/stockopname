<body class="hold-transition sidebar-mini sidebar-collapse">
  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="<?php echo base_url('assets/images/Karisma.png') ?>" alt="AdminLTELogo" height="150" width="300">
    </div>

    <?php $this->load->view('partial/main/navbar') ?>
    <?php $this->load->view('partial/main/sidebar') ?>
    <?php $this->load->view('content/hrd/modallapexpedisi') ?>


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
              <h3 class="card-title">LAPORAN EXPEDISI</h3>
            </div>
            <?php if ($this->session->userdata('akses_lv') == '1' && $this->session->userdata('departemen') == 'LOGISTIK') : ?>
            <?php elseif ($this->session->userdata('departemen') != 'LOGISTIK') : ?>
              <button type="button" class="btn btn-primary m-2 ml-3" data-toggle="modal" data-target="#addexpedisi">
                <i class="fas fa-pen"></i>
                Input Laporan Baru
              </button>
            <?php endif; ?>
            <div class="card-body">
              <table id="tb_lap_distribusi" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th hidden>id</th>
                    <th>Tanggal</th>
                    <th>Jam Keluar</th>
                    <th>Jam Masuk</th>
                    <th>No Pol</th>
                    <th>Nama Driver</th>
                    <th>No Tlpn Driver</th>
                    <th>Perusahaan Krm</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Barang</th>
                    <th>Keterangan</th>
                    <?php if ($this->session->userdata('akses_lv') == '1' && $this->session->userdata('departemen') == 'LOGISTIK') : ?>
                    <?php elseif ($this->session->userdata('departemen') != 'LOGISTIK') : ?>
                      <th>#</th>
                    <?php endif; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($laporan as $l) : ?>

                    <tr>
                      <th hidden><?= $l->id ?></th>
                      <th><?= $l->tanggal ?></th>
                      <th><?= $l->jamkeluar ?></th>
                      <th><?= $l->jammasuk ?></th>
                      <th><?= $l->nopol ?></th>
                      <th><?= $l->namadriver ?></th>
                      <th><?= $l->notlpndriver ?></th>
                      <th><?= $l->perusahaanpengirim ?></th>
                      <th><?= $l->namabarang ?></th>
                      <th><?= $l->jumlahbarang ?></th>
                      <th><?= $l->keterangan ?></th>
                      <?php if ($this->session->userdata('akses_lv') == '1' && $this->session->userdata('departemen') == 'LOGISTIK') : ?>
                      <?php elseif ($this->session->userdata('departemen') != 'LOGISTIK') : ?>
                        <th>
                          <div class="row">
                            <a href="#" class="btn btn-warning btn-sm " data-toggle="modal" data-target="#editexpedisi<?= $l->id ?>">
                              <i class="fa fa-solid fa-pencil-alt"></i>
                            </a>
                            <a href="#" class="btn btn-danger btn-sm " data-toggle="modal" data-target="#hapuslapexpedisi<?= $l->id ?>">
                              <i class="fa fa-solid fa-trash-alt"></i>
                            </a>
                          </div>
                        </th>
                      <?php endif; ?>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
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