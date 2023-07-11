<?php
$mainRole = "CUSTOMER";
$text = "Customer";
if ($role == 'user') {
    $mainRole = "ADMIN";
    $text = "Admin User";
}
// echo "string";die;
?>
<section class="content-header">
    <h1>
        Add <?= $text ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= base_url() ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?= base_url() ?>admin/users"><?= $text ?></a></li>
        <li class="active">Add</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Personal Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                <form autocomplete="off" role="form" enctype="multipart/form-data" class="validateForm" method="post" action="<?= base_url() ?>admin/users/add?role=<?= $role ?>">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="kv-avatar center-block text-center" style="width:200px">
                                    <input id="avatar-1" name="profilePic" type="file" class="file-loading">
                                    <div class="help-block"><small>Select file < 1500 KB</small></div>
                                </div>
                                <p id="kv-avatar-errors-1" class="error"></p>
                            </div>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label for="email">Email Address<span class="kv-reqd">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                            <input autocomplete="off" type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="pwd">Password<span class="kv-reqd">*</span></label>
                                            <input autocomplete="off" type="password" class="form-control" id="password" name="password" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="fname">First Name<span class="kv-reqd">*</span></label>
                                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="lname">Last Name<span class="kv-reqd">*</span></label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- <div class="col-sm-6">
                                        <label for="fname">Date Of Birth<span class="kv-reqd">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input type="text" class="form-control datepicker" id="dob" name="dob" required readonly>
                                        </div>
                                    </div> -->
                                    <div class="col-sm-6">
                                        <label for="fname">Date Of Birth</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input type="text" class="form-control datepicker" id="dob" name="dob" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="lname">Mobile<span class="kv-reqd">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                            <input type="text" class="form-control" id="mobile" name="mobile" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Gender<span class="kv-reqd">*</span></label>
                                            <select name="gender" class="select2 form-control">
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Role<span class="kv-reqd">*</span></label>
                                            <input type="text" class="form-control" id="role" name="role" value="<?= $mainRole ?>" readonly="readonly">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-footer">
                                    <a href="javascript:history.go(-1)" class="btn btn-default">Cancel</a>
                                    <button type="submit" class="submitbtn btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
</section>

<script type="text/javascript">
    $(document).ready(function () {

        $('.submitbtn').click(function () {
            if ($('.file-error-message').html() != "")
            {
                return false;
            } else
            {
                return true;
            }

        });

        // initialize with defaults
        $("#avatar-1").fileinput({
            // uploadUrl: '#',
            overwriteInitial: true,
            maxFileSize: 1500,
            showClose: false,
            showCaption: false,
            browseLabel: '',
            removeLabel: '',
            browseIcon: '<i class="glyphicon glyphicon-folder-open"></i>',
            removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
            removeTitle: 'Cancel or reset changes',
            elErrorContainer: '#kv-avatar-errors-1',
            defaultPreviewContent: '<img src="<?= base_url() ?>uploads/users/original/default.jpg" alt="Your Avatar" style="width:160px">',
            layoutTemplates: {main2: '{preview} {remove} {browse}'},
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
            browseOnZoneClick: true,
        });
    })
</script>