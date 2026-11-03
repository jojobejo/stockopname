<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <img src="<?= base_url('assets/images/karisma.png') ?>" style="width: 330px; height: 110px;" alt="">
    </div>
    <div class="card-body">
      <form action="<?= base_url('process') ?>" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Inputkan Username" id="user_isi" name="user_isi">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Inputkan Password" id="pass_isi" name="pass_isi">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
        </div>
      </form>
      <?php if ($this->session->flashdata("gagal")) : ?>
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
          <?php echo "<center>" . $this->session->flashdata("gagal") . "</center>" ?>
        </div>
      <?php elseif ($this->session->flashdata("logout")) : ?>
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
          <?php echo "<center>" . $this->session->flashdata("logout") . "</center>" ?>
        </div>
      <?php elseif ($this->session->flashdata("edited")) : ?>
        <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
          <?php echo "<center>" . $this->session->flashdata("edited") . "</center>" ?>
        </div>
      <?php endif ?>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>