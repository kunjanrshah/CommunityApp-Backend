<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?= APP_NAME ?> | Log in</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="<?= base_url() . 'themes/adminLte' ?>/bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?= base_url() . 'themes/adminLte' ?>/dist/css/AdminLTE.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="<?= base_url() . 'themes/adminLte' ?>/plugins/iCheck/square/blue.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

        <link rel="stylesheet" href="<?= base_url() . 'themes/adminLte/' ?>dist/css/custom.css">
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="##"><img src="<?= base_url('uploads/logo.png') ?>" height="150"></a>
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body">
                <h3 class="login-box-msg">Admin Login</h3>

                <form role="form" method="post" class="validateForm" action="<?= base_url() ?>admin/admin/validate_credentials">
                    <?php if ($this->session->flashdata("flash_message_error") != "") { ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                            <?= $this->session->flashdata("flash_message_error") ?>
                        </div>
                    <?php } ?>
                    <?php if ($this->session->flashdata("flash_message_success") != "") { ?>
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-check"></i> Alert!</h4>
                            <?= $this->session->flashdata("flash_message_success") ?>
                        </div>
                    <?php } ?>
                    <div class="form-group has-feedback">
                        <input type="number" name="mobile" class="form-control" placeholder="Mobile Number">
                        <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="password" class="form-control" placeholder="Password" required="required">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-xs-12 pull-right">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <br>

            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->

        <!-- jQuery 3.1.1 -->
        <script src="<?= base_url() . 'themes/adminLte' ?>/plugins/jQuery/jquery-3.1.1.min.js"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="<?= base_url() . 'themes/adminLte' ?>/bootstrap/js/bootstrap.min.js"></script>
        <!-- iCheck -->
        <script src="<?= base_url() . 'themes/adminLte' ?>/plugins/iCheck/icheck.min.js"></script>

        <script src="<?= base_url() . 'themes/adminLte/' ?>dist/js/jquery.validate.min.js"></script>

        <script src="<?= base_url() . 'themes/adminLte/' ?>dist/js/valid.js"></script>
        <script>
            $(function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
            });
        </script>
    </body>
</html>
