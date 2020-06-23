<!-- Brand Logo -->
<a href="index3.html" class="brand-link">
  <img src="<?php echo base_url() . 'assets/'; ?>dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
  <span class="brand-text font-weight-light">AdminLTE 3</span>
</a>

<!-- Sidebar -->
<div class="sidebar">
  <!-- Sidebar user panel (optional) -->
  <div class="user-panel mt-3 pb-3 mb-3 d-flex">
    <div class="image">
      <img src="<?php echo base_url() . 'assets/'; ?>img/foto.jpg" class="img-circle elevation-2" alt="User Image">
    </div>
    <div class="info">
      <a href="#" class="d-block"><?php echo $this->session->userdata('first_name'); ?></a>
      <a href="#"><i class="fa fa-circle text-success text-sm"></i> Online</a>
    </div>
  </div>

  <!-- Sidebar Menu -->
  <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
      
      <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-copy"></i>
          <p>
            Users Managament
            <i class="fas fa-angle-left right"></i>
            <span class="badge badge-info right">2</span>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="<?php echo site_url('users'); ?>" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Users</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo site_url('groups'); ?>" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Groups</p>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-database"></i>
          <p>
            Master Data
            <i class="fas fa-angle-left right"></i>
            <span class="badge badge-info right">3</span>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="<?php echo site_url('mahasiswa'); ?>" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Mahasiswa</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo site_url('dosen'); ?>" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Dosen</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo site_url('tahunakademik'); ?>" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Tahun Akademik</p>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-header"></li>
      <li class="nav-item">
        <a href="<?php echo site_url('auth/logout'); ?>" class="nav-link">
          <i class="nav-icon fas fa-power-off"></i>
          <p>
            Logout
          </p>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->