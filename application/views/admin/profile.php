<section class="content-header">
    <h1>
        Update Profile
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= base_url() ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">Profile</a></li>
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
                <form role="form" enctype="multipart/form-data" class="validateForm" method="post" action="<?= base_url() ?>admin/dashboard/updateProfile/<?= $user['id'] ?>">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="kv-avatar center-block text-center" style="width:200px">
                                    <?php
                                    $url = "";
                                    if ($user['profile_pic'] != "default.jpg") {
                                        $url = 'data-preview = "' . base_url() . 'uploads/users/original/' . $user['profile_pic'] . '"';
                                    }
                                    ?>
                                    <input id="avatar-1" <?= $url ?> name="profile_pic" type="file" class="file-loading">
                                    <div class="help-block"><small>Select file < 3000 KB</small></div>
                                </div>
                                <p id="kv-avatar-errors-1" class="error"></p>
                            </div>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="email">Email Address</label>
                                            <input type="email" class="form-control" id="email" name="email_address" value="<?= $user['email_address'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="pwd">Password</label>
                                            <input type="password" class="form-control" id="password" name="password" value="<?= $user['plain_password'] ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="fname">First Name<span class="kv-reqd">*</span></label>
                                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?= $user['first_name'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="lname">Middle Name<span class="kv-reqd">*</span></label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?= $user['last_name'] ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="fname">Date Of Birth<span class="kv-reqd">*</span></label>
                                            <input type="text" class="form-control datepicker" id="birth_date" name="birth_date" value="<?= date('d/m/Y', strtotime($user['birth_date'])) ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="lname">Mobile<span class="kv-reqd">*</span></label>
                                            <input type="text" class="form-control" id="mobile" name="mobile" value="<?= $user['mobile'] ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Gender<span class="kv-reqd">*</span></label>
                                            <select name="gender" class="select2 form-control">
                                                <option value="Male" <?= ($user['gender'] == "Male") ? "selected" : "" ?>>Male</option>
                                                <option value="Female" <?= ($user['gender'] == "Female") ? "selected" : "" ?>>Female</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-footer pull-right">
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
            initialPreview: [$("#avatar-1").attr('data-preview')],
            initialPreviewAsData: true,
            initialPreviewFileType: 'image',
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
            allowedFileExtensions: ["jpg", "jpeg", "png"]
        });
    })
</script>