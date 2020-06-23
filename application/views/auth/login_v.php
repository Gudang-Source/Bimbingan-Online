<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title>Administrator | Log in</title>
   <!-- Tell the browser to be responsive to screen width -->
   <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
   <!-- Bootstrap 3.3.5 -->
   <link rel="stylesheet" href="<?php echo base_url() . 'assets/'; ?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
   <!-- Font Awesome -->
   <link rel="stylesheet" href="<?php echo base_url() . 'assets/'; ?>plugins/fontawesome-free/css/all.min.css">
   <!-- Ionicons -->
   <link rel="stylesheet" href="<?php echo base_url() . 'assets/'; ?>plugins/ionicons-2.0.1/css/ionicons.min.css">
   <!-- Theme style -->
   <link rel="stylesheet" href="<?php echo base_url() . 'assets/'; ?>dist/css/adminlte.min.css">
   <!-- iCheck -->
   <link rel="stylesheet" href="<?php echo base_url() . 'assets/'; ?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">

   <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
   <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
   <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
</head>

<body class="hold-transition login-page">
   <div class="login-box">
      <div class="login-logo">
         <a href="<?php echo site_url('auth/login'); ?>"><b>Login</b> Administrator</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
         <p class="login-box-msg">
            <?php
            if ($this->session->flashdata('message')) {
               echo notifications('error', $message);
            }
            if (validation_errors()) {
               echo notifications('warning', validation_errors('<p>', '</p>'));
            }
            ?>
         </p>
         <form action="<?php echo site_url('auth/login'); ?>" method="post">
            <div class="form-group has-feedback">
               <?php echo lang('login_identity_label', 'identity'); ?>
               <?php echo form_input($identity); ?>
               <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
               <?php echo lang('login_password_label', 'password'); ?>
               <?php echo form_input($password); ?>
               <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
               <label>Captcha </label>
               <p><?php echo $image; ?></p>
            </div>
            <div class="row">
               <div class="col-xs-8">
                  <?php echo form_input($captcha); ?>
               </div>
            </div>
            <div class="row">
               <div class="col-xs-8">
                  <div class="checkbox icheck">
                     <label>
                        <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"') . ' Remember Me'; ?>
                     </label>
                  </div>
               </div><!-- /.col -->
               <div class="col-4">
                  <button type="submit" class="btn btn-primary btn-block float-right"><?php echo lang('login_submit_btn'); ?></button>
               </div><!-- /.col -->
            </div>
            <a href="forgot_password"><?php echo lang('login_forgot_password'); ?>
         </form>
      </div><!-- /.login-box-body -->
   </div><!-- /.login-box -->

   <!-- jQuery 2.1.4 -->
   <script src="<?php echo base_url() . 'assets/'; ?>plugins/jquery/jquery.min.js"></script>
   <!-- Bootstrap 3.3.5 -->
   <link rel="stylesheet" href="<?php echo base_url() . 'assets/'; ?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
   <!-- iCheck -->
   <link rel="stylesheet" href="<?php echo base_url() . 'assets/'; ?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
   <script>
      $(function() {
         $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
         });
      });
   </script>
</body>

</html>