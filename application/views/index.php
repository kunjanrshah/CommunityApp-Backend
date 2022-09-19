<p class="mt-4 mb-3 text-center"><img src="<?php echo base_url()?>uploads/logo.png" style="width:150px;"></p>
<!-- Page Heading/Breadcrumbs -->
<h1 class="mt-4 mb-3 text-center">
    WELCOME TO <?= APP_NAME ?> PORTAL
</h1>
<h4 class="mt-4 mb-3 text-center">Sign in with family head mobile number</h4>

<div class="content"> 
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4"> 
            <form action="#" class="validateForm" method="POST">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <input type="number" autocomplete="off" class="form-control" placeholder="Family Head Mobile" id="mobile" name="mobile" required="required" onKeyPress="if(this.value.length==10) return false;">
                    </div>
                    <div class="form-group col-md-12">
                        <!-- <input type="password" autocomplete="off" class="form-control" placeholder="Enter Password" id="password" name="password" required="required" maxlength="12" minlength="6"> -->

                        <div class="input-group">
                            <input type="password" autocomplete="off" class="form-control" placeholder="Enter Password" name="password" id="password" value="" minlength="6" required="required" maxlength="12">
                            <span class="input-group-addon" id="pass_hide_show" style="cursor: pointer;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
                        </div>
                        <label id="password-error" class="error" for="password"></label>
                    </div>
                    <div class="form-group col-md-12 text-center">
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </div>
                </div>
                <p class="text-danger" style="line-height: 30px;">
                    <span style="font-size: 18px;">Register your family</span>
                    <a href="<?= base_url('site/register') ?>">click here.</a>
                    <a href="javascript:void(0)" target="_blank" style="float: right;"><i class="fa fa-youtube-play fa-2x" style="color: #FF0000;"></i></a>
                </p>
                <p class="text-center"><a href="<?= base_url('site/forgotpassword') ?>">Forgot password</a></p>
            </form>
        </div>
        <div class="col-md-4"></div>
    </div>
</div>