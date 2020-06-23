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
      <li class="nav-item">
        <a href="<?php echo site_url('dosen/profile'); ?>" class="nav-link">
          <i class="nav-icon fas fa-user-alt"></i>
          <p>
            Profile
          </p>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?php echo site_url('dosen/bimbingan'); ?>" class="nav-link">
         <i class="nav-icon fas fa-users"></i>
          <p>
            Daftar Bimbingan
          </p>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?php echo site_url('dosen/pesan'); ?>" class="nav-link">
          <i class="nav-icon fas fa-envelope"></i>
          <p>
            Pesan
          </p>
        </a>
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
<script type="text/javascript">
    var site_url1 = site_url() + 'notification/';

    var table;
    $(document).ready(function() {

        setInterval(notif, 5000);

    });

    function notif()
    {
        $.ajax({
            url: site_url1 + 'cek_notif_dosen/',
            cache: false,
            type: "POST",
            dataType: "json",
            success: function(data) {
                if (data.code == 0)
                {
                    $(document).Toasts('create', {
                        class: 'bg-info',
                        body: data.body,
                        title: data.title,
                          // subtitle: 'Subtitle',
                        icon: data.icon,
                          // autohide: true,
                          // delay: 3000,
                    })
                }        
            }
        });
    }
</script>