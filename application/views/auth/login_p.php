<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login V1</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="images/icons/favicon.ico" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/'; ?>login/vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/'; ?>login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/'; ?>login/vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/'; ?>login/vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/'; ?>login/vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/'; ?>login/css/util.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/'; ?>login/css/main.css">
    <!--===============================================================================================-->
    <style>
        .back {
            background-color: #174B4C;
        }

        .text-white {
            color: white;
        }

        .text-grey {
            color: #f7f7f7;
        }
    </style>
</head>

<body>

    <div class="limiter">
        <div class="container-login100">

            <div class="wrap-login100 back">
                <div class="login100-pic js-tilt" data-tilt>
                    <img src="<?php echo base_url() . 'assets/'; ?>login/images/logo.png" alt="IMG">
                </div>

                <form class="login100-form validate-form" action="<?php echo site_url('auth/login'); ?>" method="post">
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
                    <span class="login100-form-title text-white">
                        Member Login
                    </span>

                    <div class="wrap-input100 validate-input text-white" data-validate="Valid email is required: coba@mail.com">
                        <?php echo lang('login_identity_label', 'identity'); ?>
                        <?php echo form_input($identity); ?>
                    </div>

                    <div class="wrap-input100 validate-input text-white" data-validate="Password is required">
                        <?php echo lang('login_password_label', 'password'); ?>
                        <?php echo form_input($password); ?>
                    </div>

                    <div class="wrap-input100 validate-input text-white">
                        <label>Captcha </label>
                        <p><?php echo $image; ?></p>
                    </div>

                    <div class="wrap-input100 validate-input">
                        <?php echo form_input($captcha); ?>
                    </div>

                    <div class="container-login100-form-btn">

                        <button type="submit" class="btn btn-success form-control"><?php echo lang('login_submit_btn'); ?></button>
                    </div>

                    <div class="text-center p-t-12">
                        <span class="txt1 text-white">
                            Forgot
                        </span>
                        <a class="txt2 text-grey" href="#">
                            Username / Password?
                        </a>
                    </div>

                    <div class="text-center p-t-136">
                        <a class="txt2 text-grey" href="#">
                            Create your Account
                            <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>




    <!--===============================================================================================-->
    <script src="<?php echo base_url() . 'assets/'; ?>login/vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
    <script src="<?php echo base_url() . 'assets/'; ?>login/vendor/bootstrap/js/popper.js"></script>
    <script src="<?php echo base_url() . 'assets/'; ?>login/vendor/bootstrap/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
    <script src="<?php echo base_url() . 'assets/'; ?>login/vendor/select2/select2.min.js"></script>
    <!--===============================================================================================-->
    <script src="<?php echo base_url() . 'assets/'; ?>login/vendor/tilt/tilt.jquery.min.js"></script>
    <script>
        $('.js-tilt').tilt({
            scale: 1.1
        });
    </script>
    <script src="<?php echo base_url() . 'assets/'; ?>login/js/main.js"></script>

</body>

</html>