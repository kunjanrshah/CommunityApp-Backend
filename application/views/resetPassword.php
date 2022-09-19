<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>CleanNOW | Reset Password</title>
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
                        <form class="validateForm" action="<?= base_url() ?>welcome/resetPassword/<?= $token ?>?userType=<?= $userType ?>" method="POST">
                            <div class="modal-header">
                                <h3>Reset Password <span class="extra-title muted"></span></h3>
                            </div>
                            <div class="modal-body form-horizontal">
                                <div class="control-group">
                                    <label for="new_password" class="control-label">New Password</label>
                                    <div class="controls">
                                        <input class="form-control" type="password" name="password" id="password" required="required">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label for="confirm_password" class="control-label">Confirm Password</label>
                                    <div class="controls">
                                        <input class="form-control" type="password" name="password_confirm">
                                    </div>
                                </div>      
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" >Reset Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> 
        </div>

        <!-- jQuery 3.1.1 -->
        <script src="<?= base_url() . 'themes/adminLte' ?>/plugins/jQuery/jquery-3.1.1.min.js"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="<?= base_url() . 'themes/adminLte' ?>/bootstrap/js/bootstrap.min.js"></script>

        <script src="<?= base_url() . 'themes/adminLte/' ?>dist/js/jquery.validate.min.js"></script>

        <script src="<?= base_url() . 'themes/adminLte/' ?>dist/js/valid.js"></script>
    </body>
</html>