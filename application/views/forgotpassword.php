<p class="mt-4 mb-3 text-center"><img src="<?php echo base_url()?>uploads/logo.png" style="width:150px;"></p>
<!-- Page Heading/Breadcrumbs -->
<h1 class="mt-4 mb-3 text-center">
    WELCOME TO <?= APP_NAME ?> PORTAL
</h1>
<h4 class="mt-4 mb-3 text-center">Forgot Password</h4>

<div class="content">
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4 text-center"> 
            <form action="#" class="validateForm" method="POST">
                <div class="form-row">
                    <div class="form-group col-md-12 text-left pwd-control mobileshow">
                        <input type="number" autocomplete="off" class="form-control" onKeyPress="if(this.value.length==11) return false;" placeholder="Enter your mobile no" id="mobile_no" name="mobile_no" required="required">
                    </div>
                    <div class="form-group col-md-12 text-left pwd-control emailshow" style="display: none;">
                        <input type="email" autocomplete="off" class="form-control" placeholder="Enter your email" id="email" name="email" required="required">
                    </div>
                    <div class="form-group col-md-12 text-left mobilelink" style="display: none;">
                        <input type="hidden" id="request_type" value="email">
                        <center><a id="change_password" href="javascript:;">OR <BR>Password By Mobile No.</a></center>
                    </div>
                    <div class="form-group col-md-12 text-left emaillink">
                        <input type="hidden" id="request_type" value="mobile no">
                        <center><a id="change_password" href="javascript:;">OR <BR>Password By Email</a></center>
                    </div>
                    <div class="form-group col-md-12 text-center">
                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        <br>
                        <p><a href="<?php echo base_url();?>" class="text-danger">Back to Home</a></p>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-4"></div>
    </div>
</div>
<script type="text/javascript">
    $(document).on('click','.mobilelink #change_password,.emaillink #change_password',function(){
        if ($(this).parent().parent().hasClass('mobilelink')) {
            $('.mobilelink').hide();
            $('.emaillink').show();
            $('.mobileshow').show();
            $('.emailshow').hide();
        }else{
            $('.mobilelink').show();
            $('.emaillink').hide();
            $('.mobileshow').hide();
            $('.emailshow').show();
        }
    });
</script>