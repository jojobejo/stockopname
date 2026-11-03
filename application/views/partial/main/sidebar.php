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
    $hasMasterUserManagementAccess = (strtolower((string) $CI->session->userdata('username')) === 'admin');
    $dynamicSidebarTree = [];
    if (isset($CI->db) && $CI->db->table_exists('tb_menu')) {
      $CI->load->model('master/M_Menu', 'sidebarMenuModel');
      $dynamicSidebarTree = $CI->sidebarMenuModel->sidebar_tree($CI->session->userdata('akses_lv_id') ?: $CI->session->userdata('lv'));
    }

    $renderDynamicMenu = function ($menus) use (&$renderDynamicMenu) {
      foreach ($menus as $menu) {
        $hasChildren = !empty($menu['children']);
        $url = $hasChildren ? '#' : base_url($menu['url']);
        ?>
        <li class="nav-item <?= $hasChildren ? 'has-treeview' : '' ?>">
          <a href="<?= $url ?>" class="nav-link">
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
    <?php elseif ($this->session->userdata('lv') == '1' && $this->session->userdata('jobdesk') == 'STOCKOPNAME') : ?>
      <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="<?php echo base_url('s') ?>" class="nav-link">
                <i class="nav-icon fas fa-home"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('stockopname/input') ?>" class="nav-link">
                <i class="nav-icon fas fa-mobile-alt"></i>
                <p>
                  Input Opname
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

    <!-- /.sidebar -->
  </aside>
