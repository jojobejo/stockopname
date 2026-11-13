  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-2">
    <!-- Brand Logo -->
    <a href="<?php echo base_url('dashboard') ?>" class="brand-link">
      <img src="<?php echo base_url("assets/images/Karisma.png") ?>" style="width: 50px; height: 30px;" alt="Karisma Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Halo , <?= $this->session->userdata('nama') ?><br></span>
    </a>

    <?php 
    $allowed_roles = ['DIREKTURCK', 'ADMLOG', 'MANAGERWH', 'SALESCK', 'MANAGERCK'];
    $CI =& get_instance();
    $masterUsername = strtolower(trim((string) $CI->session->userdata('username')));
    $jobdeskSession = strtoupper(trim((string) $CI->session->userdata('jobdesk')));
    $isSuperadmin = ($masterUsername === 'superadmin' || $jobdeskSession === 'SUPERADMIN');
    $hasMasterUserManagementAccess = $isSuperadmin;
    $hasFullMasterUserAccess = $isSuperadmin;
    $dynamicSidebarTree = [];
    if (isset($CI->db) && $CI->db->table_exists('tb_menu')) {
      $CI->load->model('master/M_Menu', 'sidebarMenuModel');
      $dynamicSidebarTree = $CI->sidebarMenuModel->sidebar_tree($CI->session->userdata('akses_lv_id') ?: $CI->session->userdata('lv'));
    }

    $activeSidebarPath = trim($CI->uri->uri_string(), '/');
    $renderDynamicMenu = function ($menus) use (&$renderDynamicMenu, $activeSidebarPath) {
      foreach ($menus as $menu) {
        $hasChildren = !empty($menu['children']);
        $url = $hasChildren ? '#' : base_url($menu['url']);
        ?>
        <li class="nav-item <?= $hasChildren ? 'has-treeview' : '' ?>">
          <a href="<?= $url ?>" class="nav-link <?= (!$hasChildren && trim((string)$menu['url'], '/') === $activeSidebarPath) ? 'active' : '' ?>">
            <i class="nav-icon <?= html_escape($menu['icon'] ?: 'fas fa-circle') ?>"></i>
            <p>
              <?= html_escape($menu['nama_menu']) ?>
              <?php if ($hasChildren) : ?><i class="right fas fa-angle-left"></i><?php endif; ?>
            </p>
          </a>
          <?php if ($hasChildren) : ?>
            <ul class="nav nav-treeview">
              <?php $renderDynamicMenu($menu['children']); ?>
            </ul>
          <?php endif; ?>
        </li>
        <?php
      }
    };
    ?>

    <?php if ($hasMasterUserManagementAccess) : ?>
      <div class="sidebar pb-0">
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-user-shield"></i>
                <p>
                  Master User
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= base_url('master/user-management') ?>" class="nav-link">
                    <i class="fas fa-users nav-icon"></i>
                    <p>User Management</p>
                  </a>
                </li>
                <?php if ($hasFullMasterUserAccess) : ?>
                <li class="nav-item">
                  <a href="<?= base_url('master/jobdesk') ?>" class="nav-link">
                    <i class="fas fa-briefcase nav-icon"></i>
                    <p>Jobdesk</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('master/akses-level') ?>" class="nav-link">
                    <i class="fas fa-key nav-icon"></i>
                    <p>Akses Level</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('master/menu') ?>" class="nav-link">
                    <i class="fas fa-bars nav-icon"></i>
                    <p>Menu</p>
                  </a>
                </li>
                <?php endif; ?>
              </ul>
            </li>
          </ul>
        </nav>
      </div>
    <?php endif; ?>

    <!-- Sidebar -->
    <?php if (!empty($dynamicSidebarTree)) : ?>
      <div class="sidebar">
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <?php $renderDynamicMenu($dynamicSidebarTree); ?>
            <li class="nav-item">
              <a href="<?php echo base_url('logout') ?>" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>Log Out</p>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    <?php elseif ($isSuperadmin) : ?>
      <div class="sidebar">
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="<?= base_url('dashboard') ?>" class="nav-link <?= $activeSidebarPath === 'dashboard' ? 'active' : '' ?>">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard Utama</p>
              </a>
            </li>
            <li class="nav-item has-treeview <?= strpos($activeSidebarPath, 'admin/stockopname') === 0 || strpos($activeSidebarPath, 'stockopname') === 0 || strpos($activeSidebarPath, 'supervisi-opname') === 0 ? 'menu-open' : '' ?>">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-boxes"></i>
                <p>Stockopname <i class="right fas fa-angle-left"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= base_url('admin/stockopname') ?>" class="nav-link <?= $activeSidebarPath === 'admin/stockopname' ? 'active' : '' ?>">
                    <i class="fas fa-home nav-icon"></i><p>Dashboard Stockopname</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('admin/stockopname/monitoring') ?>" class="nav-link <?= $activeSidebarPath === 'admin/stockopname/monitoring' ? 'active' : '' ?>">
                    <i class="fas fa-desktop nav-icon"></i><p>Monitoring</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('admin/stockopname/master_opname') ?>" class="nav-link <?= $activeSidebarPath === 'admin/stockopname/master_opname' ? 'active' : '' ?>">
                    <i class="fas fa-layer-group nav-icon"></i><p>Master Opname &amp; Barang</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('admin/stockopname/barang-pending') ?>" class="nav-link <?= $activeSidebarPath === 'admin/stockopname/barang-pending' ? 'active' : '' ?>">
                    <i class="fas fa-hourglass-half nav-icon"></i><p>Barang Pending</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('admin/stockopname/input') ?>" class="nav-link <?= $activeSidebarPath === 'admin/stockopname/input' ? 'active' : '' ?>">
                    <i class="fas fa-mobile-alt nav-icon"></i><p>Input Opname</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('supervisi-opname') ?>" class="nav-link <?= $activeSidebarPath === 'supervisi-opname' ? 'active' : '' ?>">
                    <i class="fas fa-clipboard-check nav-icon"></i><p>Supervisi Opname</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('logistik') ?>" class="nav-link <?= $activeSidebarPath === 'logistik' ? 'active' : '' ?>">
                <i class="nav-icon fas fa-truck"></i><p>Logistik &amp; DO</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('ics/ics_diffrent') ?>" class="nav-link <?= strpos($activeSidebarPath, 'ics') === 0 ? 'active' : '' ?>">
                <i class="nav-icon fas fa-warehouse"></i><p>Logistik ICS</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('checker') ?>" class="nav-link <?= strpos($activeSidebarPath, 'checker') === 0 ? 'active' : '' ?>">
                <i class="nav-icon fas fa-clipboard-list"></i><p>Warehouse Checker</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('logistik/distibusi') ?>" class="nav-link <?= strpos($activeSidebarPath, 'logistik/distibusi') === 0 ? 'active' : '' ?>">
                <i class="nav-icon fas fa-route"></i><p>Distribusi</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('sales_order') ?>" class="nav-link <?= strpos($activeSidebarPath, 'sales_order') === 0 ? 'active' : '' ?>">
                <i class="nav-icon fas fa-shopping-cart"></i><p>Sales Order</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('keuangan') ?>" class="nav-link <?= $activeSidebarPath === 'keuangan' ? 'active' : '' ?>">
                <i class="nav-icon fas fa-chart-line"></i><p>Keuangan &amp; Daily Stock</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('hrd/penilaian_lingkungan/admin') ?>" class="nav-link <?= strpos($activeSidebarPath, 'hrd/penilaian_lingkungan') === 0 ? 'active' : '' ?>">
                <i class="nav-icon fas fa-building"></i><p>Penilaian Lingkungan</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('schedule_direktur') ?>" class="nav-link <?= $activeSidebarPath === 'schedule_direktur' ? 'active' : '' ?>">
                <i class="nav-icon fas fa-calendar-alt"></i><p>Schedule Direktur</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('logout') ?>" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i><p>Log Out</p>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    <?php elseif ($this->session->userdata('lv') == '1' && $this->session->userdata('jobdesk') == 'ADMINKEU') : ?>
      <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="<?php echo base_url('keuangan') ?>" class="nav-link">
                <i class="nav-icon fas fa-quran"></i>
                <p>
                  Daily Stock Product
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('logout') ?>" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>
                  Log Out
                </p>
              </a>
            </li>
        </nav>
        <!-- /.sidebar-menu -->
      </div>

    <?php elseif ($this->session->userdata('lv') == '1' && $this->session->userdata('jobdesk') == 'ADMINGA') : ?>
      <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="<?php echo base_url('schedule_direktur') ?>" class="nav-link">
                <i class="nav-icon fas fa-book"></i>
                <p>
                  Jadwal Tamu
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('logout') ?>" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>
                  Log Out
                </p>
              </a>
            </li>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
    <?php elseif ($this->session->userdata('lv') == '5' && $this->session->userdata('jobdesk') == 'DIREKTUR') : ?>
      <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="<?php echo base_url('keuangan') ?>" class="nav-link">
                <i class="nav-icon fas fa-book"></i>
                <p>
                  Daily Stock Product
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('schedule_direktur') ?>" class="nav-link">
                <i class="nav-icon fas fa-user-friends"></i>
                <p>
                  Jadwal Tamu
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('logout') ?>" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>
                  Log Out
                </p>
              </a>
            </li>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
    <?php elseif ($this->session->userdata('lv') == '1' && $this->session->userdata('jobdesk') == 'LOGISTIK') : ?>
      <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="<?php echo base_url('logistik') ?>" class="nav-link">
                <i class="nav-icon fas fa-home"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('create_do') ?>" class="nav-link">
                <i class="nav-icon fas fa-pen-fancy"></i>
                <p>
                  Create DO
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('logout') ?>" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>
                  Log Out
                </p>
              </a>
            </li>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
    <?php elseif ($this->session->userdata('lv') == '1' && $this->session->userdata('jobdesk') == 'SC') : ?>
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          <!-- Sales Order -->
          <li class="nav-item">
            <a href="<?php echo base_url('sales_order') ?>" class="nav-link">
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>Sales Order</p>
            </a>
          </li>

          <!-- Delivery Order -->
          <li class="nav-item">
            <a href="<?php echo base_url('sales_order/list_do') ?>" class="nav-link">
              <i class="nav-icon fas fa-truck"></i>
              <p>List Delivery Order</p>
            </a>
          </li>

          <!-- Logout -->
          <li class="nav-item">
            <a href="<?php echo base_url('logout') ?>" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Log Out</p>
            </a>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <?php elseif ($this->session->userdata('lv') == '1' && $this->session->userdata('jobdesk') == 'ADMINICS') : ?>
      <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="<?php echo base_url('ics/ics_diffrent') ?>" class="nav-link">
                <i class="nav-icon fas fa-home"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('ics/master_barang') ?>" class="nav-link">
                <i class="nav-icon fas fa-database"></i>
                <p>
                  Master Barang
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('logout') ?>" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>
                  Log Out
                </p>
              </a>
            </li>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
    <?php elseif (in_array($jobdeskSession, ['STOCKOPNAME', 'ADMIN_STOCKOPNAME', 'ADMIN_STOCKOPNAME_LOGISTIK'], true) || in_array($masterUsername, ['admin', 'stockopname'], true)) : ?>
      <?php
      $isAdminStockopnameLogistikMenu = ($jobdeskSession === 'ADMIN_STOCKOPNAME_LOGISTIK' || $masterUsername === 'stockopname');
      $isAdminStockopnameMenu = ($jobdeskSession === 'ADMIN_STOCKOPNAME' || ($masterUsername === 'admin' && !$isAdminStockopnameLogistikMenu));
      ?>
      <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <?php if ($isAdminStockopnameMenu || $isAdminStockopnameLogistikMenu) : ?>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/stockopname') ?>" class="nav-link <?= $activeSidebarPath === 'admin/stockopname' ? 'active' : '' ?>">
                <i class="nav-icon fas fa-home"></i>
                <p>Dashboard</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/stockopname/monitoring') ?>" class="nav-link <?= $activeSidebarPath === 'admin/stockopname/monitoring' ? 'active' : '' ?>">
                <i class="nav-icon fas fa-desktop"></i>
                <p>Monitoring Opname</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/stockopname/master_opname') ?>" class="nav-link <?= $activeSidebarPath === 'admin/stockopname/master_opname' ? 'active' : '' ?>">
                <i class="nav-icon fas fa-boxes"></i>
                <p><?= $isAdminStockopnameLogistikMenu ? 'Master Barang' : 'Master Opname' ?></p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/stockopname/barang-pending') ?>" class="nav-link <?= $activeSidebarPath === 'admin/stockopname/barang-pending' ? 'active' : '' ?>">
                <i class="nav-icon fas fa-hourglass-half"></i>
                <p>Barang Pending</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('stockopname/input') ?>" class="nav-link <?= ($activeSidebarPath === 'stockopname/input' || $activeSidebarPath === 'admin/stockopname/input') ? 'active' : '' ?>">
                <i class="nav-icon fas fa-mobile-alt"></i>
                <p>Input Opname</p>
              </a>
            </li>
            <?php else : ?>
            <li class="nav-item">
              <a href="<?php echo base_url('stockopname/input') ?>" class="nav-link">
                <i class="nav-icon fas fa-mobile-alt"></i>
                <p>Input Opname</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('stockopname/history-input') ?>" class="nav-link">
                <i class="nav-icon fas fa-history"></i>
                <p>Histori Input</p>
              </a>
            </li>
            <?php endif; ?>
            <li class="nav-item">
              <a href="<?php echo base_url('logout') ?>" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>Log Out</p>
              </a>
            </li>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
    <?php elseif ($this->session->userdata('lv') == '1' && $this->session->userdata('jobdesk') == 'SUPERVISIOR_OPNAME') : ?>
      <div class="sidebar">
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="<?php echo base_url('supervisi-opname') ?>" class="nav-link">
                <i class="nav-icon fas fa-clipboard-check"></i><p>Supervisi Opname</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('supervisi-opname/tracking') ?>" class="nav-link">
                <i class="nav-icon fas fa-map-marked-alt"></i><p>Tracking Inputer Wilayah</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('logout') ?>" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i><p>Log Out</p>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    <?php elseif ($this->session->userdata('lv') == '1' && $this->session->userdata('jobdesk') == 'ADMINKEUTC') : ?>
      <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="<?php echo base_url('keuangan') ?>" class="nav-link">
                <i class="nav-icon fas fa-home"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('logout') ?>" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>
                  Log Out
                </p>
              </a>
            </li>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <?php elseif ($this->session->userdata('lv') == '1' && in_array($this->session->userdata('jobdesk'), $allowed_roles)) : ?>
      <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="<?php echo base_url('checker/dashboard') ?>" class="nav-link">
                <i class="nav-icon fas fa-home"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('checker') ?>" class="nav-link">
                <i class="nav-icon fas fa-warehouse"></i>
                <p>
                  Warehouse Activity
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('logout') ?>" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>
                  Log Out
                </p>
              </a>
            </li>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
    <?php else : ?>
      <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="<?php echo base_url('logout') ?>" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>
                  Log Out
                </p>
              </a>
            </li>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
    <?php endif; ?>

    <?php /* Menu Pasang Aplikasi disembunyikan sementara untuk semua pengguna */ if (false): ?>
    <div class="sidebar">
      <nav class="mt-2" aria-label="Pemasangan aplikasi">
        <ul class="nav nav-pills nav-sidebar flex-column">
          <li class="nav-item">
            <button type="button" class="nav-link text-left w-100 border-0" data-install-app>
              <i class="nav-icon fas fa-download"></i>
              <p data-install-label>Download aplikasi</p>
            </button>
          </li>
        </ul>
        <small class="d-block px-3 text-muted" data-device-label></small>
      </nav>
    </div>
    <?php endif; ?>

    <!-- /.sidebar -->
  </aside>
