<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>CleanNOW | Reset Password Success</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="<?= base_url() . 'themes/adminLte' ?>/bootstrap/css/bootstrap.min.css">
        <style>
            .error{
                color: darkred;
            }
            #password_modal{
                display: block;
                position: relative;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="modal" id="password_modal">
                        <div class="modal-header">
                            <h3>Reset Password Success <span class="extra-title muted"></span></h3>
                        </div>
                        <div class="modal-body form-horizontal">
                            <h4>Your password reset successfully now you can login with your new password.</h4>
                        </div>
                        <div class="modal-footer">

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="<?= base_url() . 'themes/adminLte' ?>/bootstrap/js/bootstrap.min.js"></script>

    </body>
</html>