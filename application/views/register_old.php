<!-- Page Heading/Breadcrumbs -->
<h1 class="mt-4 mb-3">Registration Form
</h1>

<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?= base_url('site') ?>">Home</a>
    </li>
    <li class="breadcrumb-item active">Register</li>
</ol>
<div class="content">
    <form action="<?= base_url('site/register') ?>" enctype="multipart/form-data" class="ajax-form validateForm" method="POST">
        <div class="row">
            <div class="col-md-12">
                <h4>Main Details</h4>
            </div>
        </div>
        <hr>
        <div class="form-row">
            <div class='form-group col-md-4'>
                <div class="kv-avatar center-block text-center" style="width:200px">
                    <input id="avatar-1" name="profile_pic" type="file" class="file-loading">
                    <div class="help-block"><small>Select file < 1500 KB</small></div>
                </div>
                <p id="kv-avatar-errors-1" class="error"></p>
            </div>
            <dic class="col-md-8">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Sub Community<span class="text-danger">*</span></label>
                        <select class="form-control dependent" data-url="<?= base_url('site/getLocalCommunity') ?>" data-dependent="local_community_id" id="sub_community_id" name="sub_community_id" required="required">
                            <option value="">--Select--</option>
                            <?php
                            if (!empty($subCommunity)) {
                                foreach ($subCommunity as $list) {
                                    echo '<option value="' . $list['id'] . '">' . $list['name'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Local Community<span class="text-danger">*</span></label>
                        <select class="form-control dependent" data-url="<?= base_url('site/getAdmins') ?>" data-dependent="adminLists" id="local_community_id" name="local_community_id" required="required">
                            <option value="">--Select--</option>
                        </select>
                    </div>
                </div>
                <span id="adminLists" style="display: none"></span>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="inputState">Mobile<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="mobile" id="mobile" placeholder="Mobile" required="required">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputPassword4">Password<span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required="required">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label>Email</label>
                        <input type="email" class="form-control" id="email_address" name="email_address" placeholder="Email">
                    </div>
                </div>

        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>First Name<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" required="required">
            </div>
            <div class="form-group col-md-3">
                <label>Last Name<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" required="required">
            </div>
            <div class="form-group col-md-3">
                <label>Sub Cast</label>
                <input type="text" class="form-control" id="sub_cast" name="sub_cast" placeholder="Sub Cast">
            </div>
            <div class="form-group col-md-3">
                <label for="inputPassword4">Gender</label>
                <select name="gender" id="gender" class="form-control">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Birth Date<span class="text-danger">*</span></label>
                <input type="text" class="form-control datepicker" id="birth_date" name="birth_date" placeholder="Birth Date" autocomplete="off" required="required">
            </div>
            <div class="form-group col-md-3">
                <label>Is Expired</label>
                <select name="is_expired" id="is_expired" class="form-control">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>
            <div class="form-group col-md-3" id="expiredDate" style="display: none">
                <label>Expire Date</label>
                <input type="text" class="form-control datepicker" id="expire_date" name="expire_date" placeholder="Expire Date" autocomplete="off">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h4>Contact Details</h4>
            </div>
        </div>
        <hr>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputAddress">Permenant Address<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="address" name="address" placeholder="Address" required="required">
            </div>
            <div class="form-group col-md-6">
                <label for="inputAddress">Local Address</label>
                <input type="text" class="form-control" id="local_address" name="local_address" placeholder="Local Address">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="inputState">State<span class="text-danger">*</span></label>
                <select id="state_id" name="state_id" class="dependent form-control" data-url="<?= base_url('site/getCityByState') ?>" data-dependent="city_id" required="required">
                    <option value="">--Select--</option>
                    <?php
                    if (!empty($states)) {
                        foreach ($states as $state) {
                            echo '<option value="' . $state['id'] . '">' . $state['state'] . '</option>';
                        }
                    }
                    ?>
                </select>

            </div>
            <div class="form-group col-md-3">
                <label for="inputCity">City<span class="text-danger">*</span></label>
                <select id="city_id" name="city_id" class="form-control" required="required">
                    <option value="">--Select--</option>
                    <?php
                    if (!empty($cities)) {
                        foreach ($cities as $city) {
                            echo '<option value="' . $city['id'] . '">' . $city['city'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>Area<span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="area" id="area" placeholder="Area" required="required">
            </div>
            <div class="form-group col-md-3">
                <label>Pincode<span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="pincode" id="pincode" placeholder="Zip" required="required">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="inputCity">Phone</label>
                <input type="number" class="form-control" name="phone" id="phone" placeholder="Phone">
            </div>
            <div class="form-group col-md-3">
                <label>Is Rented</label>
                <select name="is_rented" class="form-control">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h4>Personal Details</h4>
            </div>
        </div>
        <hr>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Native Place</label>
                <input type="text" class="form-control" id="native_place_id" name="native_place_id" placeholder="Native Place">
            </div>
            <div class="form-group col-md-3">
                <label for="inputState">Current Activity</label>
<!--                <select name="current_activity_id" id="current_activity_id" class="select2">
                    <option>--Select--</option>
                </select>-->
                <input type="text" placeholder="Current Activity" name="current_activity_id" id="current_activity_id" class="form-control">
            </div>
            <div class="form-group col-md-3">
                <label for="inputEmail4">Education</label>
                <input type="text" autocomplete="off" class="form-control" placeholder="Education" id="education_id" name="education_id">
            </div>
            <div class="form-group col-md-3">
                <label>Blood Group</label>
                <select class="form-control" name="blood_group">
                    <option>--Select--</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Is Blood Donor</label>
                <select name="is_donor" class="form-control">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>Marital Status</label>
                <select id="marital_status" name="marital_status" class="form-control">
                    <option>--Select--</option>
                    <option value="Single">Single</option>
                    <option value="Engaged">Engaged</option>
                    <option value="Married">Married</option>
                    <option value="Widow">Widow</option>
                    <option value="Widower">Widower</option>
                    <option value="Seperated">Seperated</option>
                    <option value="Divorce">Divorce</option>
                </select>
            </div>
            <div class="form-group col-md-3 marriageDetails" style="display: none">
                <label>Marriage Date</label>
                <input type="text" name="marriage_date" class="form-control datepicker" id="marriage_date" placeholder="Marriage Date" autocomplete="off">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h4>Professional Details</h4>
            </div>
        </div>
        <hr>
        <div class="form-row">
            <div class='form-group col-md-4'>
                <div class="kv-avatar center-block text-center" style="width:200px">
                    <input id="avatar-2" name="business_logo" type="file" class="file-loading">
                    <div class="help-block"><small>Select file < 1500 KB</small></div>
                </div>
                <p id="kv-avatar-errors-2" class="error"></p>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="inputEmail4">Business Category</label>
                        <input type="text" autocomplete="off" class="form-control" placeholder="Business Category" id="business_category_id" name="business_category_id">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputPassword4">Business Sub Category</label>
                        <input type="text" autocomplete="off" class="form-control" placeholder="Business Sub Category" id="business_sub_category_id" name="business_sub_category_id">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Company Name</label>
                        <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company Name">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Address</label>
                        <textarea name="business_address" placeholder="Address" id="business_address" class="form-control"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Website</label>
                <input type="text" class="form-control" id="website" name="website" placeholder="http://www.google.com">
            </div>
            <div class="form-group col-md-3">
                <label for="inputPassword4">Occupation</label>
                <input type="text" autocomplete="off" class="form-control" placeholder="Occupation" id="occupation_id" name="occupation_id">
            </div>
            <div class="form-group col-md-3">
                <label>Work Details</label>
                <textarea name="work_details" placeholder="Work Details" id="work_details" class="form-control"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h4>Matrimony Details</h4>
            </div>
        </div>
        <hr>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label class="control-label">Interested in Matrimony ?</label>
                <br>
                <div class="form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="matrimony" checked="checked" value="No">No
                    </label>
                </div>
                <div class="form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="matrimony" value="Yes">Yes
                    </label>
                </div>
            </div>
        </div>
        <div id="matrimony" style="display: none">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Birth Time</label>
                    <div class="bootstrap-timepicker">
                        <div class="form-group">
                            <div class="input-group">
                                <input placeholder="Birth Time" name="birth_time"  type="text" class="timepicker form-control" value="" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-3">
                    <label>Birth Place</label>
                    <input type="text" class="form-control" id="birth_place" name="birth_place" placeholder="Birth Place">
                </div>
                <div class="form-group col-md-3">
                    <label>Height</label>
                    <input type="text" class="form-control" id="height" name="height" placeholder="Height">
                </div>
                <div class="form-group col-md-3">
                    <label>Weight</label>
                    <input type="text" class="form-control" id="weight" name="weight" placeholder="Weight">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Is Spect</label>
                    <select name="is_spect" class="form-control">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Hobby</label>
                    <textarea name="hobby" id="hobby" class="form-control" placeholder="Hobby"></textarea>
                </div>
                <div class="form-group col-md-3">
                    <label>About Me</label>
                    <textarea name="about_me" placeholder="About Me" id="about_me" class="form-control"></textarea>
                </div>
                <div class="form-group col-md-3">
                    <label>Expectation</label>
                    <textarea name="expectation" id="expectation" class="form-control" placeholder="Expectation"></textarea>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Facebook Profile</label>
                    <input type="text" name="facebook_profile" class="form-control" id="facebook_profile" placeholder="Facebook Profile">
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <br>
</div>

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


        $('input[type=radio][name=matrimony]').change(function () {
            if (this.value == 'Yes') {
                $('#matrimony').show();
            } else {
                $('#matrimony').hide();
            }
        });

        // initialize with defaults

        $('#is_expired').on('change', function () {
            if ($(this).val() == "1") {
                $('#expiredDate').show();
            } else {
                $('#expire_date').val('');
                $('#expiredDate').hide();
            }
        });

        $('#marital_status').on('change', function () {
            if ($(this).val() == "Married") {
                $('.marriageDetails').show();
            } else {
                $('#marriage_date').val('');
                $('#mosaad_id').val('');
                $('.marriageDetails').hide();
            }
        });

        $("#avatar-1").fileinput({
            overwriteInitial: true,
            maxFileSize: 1500,
            showClose: false,
            showCaption: false,
            browseLabel: '',
            removeLabel: '',
            browseIcon: '<i class="fa fa-folder-open"></i>',
            removeIcon: '<i class="fa fa-remove"></i>',
            removeTitle: 'Cancel or reset changes',
            elErrorContainer: '#kv-avatar-errors-1',
            msgErrorClass: 'alert alert-block alert-danger',
            defaultPreviewContent: '<img src="<?= base_url() ?>uploads/default.jpg" alt="Your Avatar" style="width:160px">',
            layoutTemplates: {main2: '{preview} {remove} {browse}'},
            allowedFileExtensions: ["jpg", "png", "gif", "jpeg"]
        });

        $("#avatar-2").fileinput({
            overwriteInitial: true,
            maxFileSize: 1500,
            showClose: false,
            showCaption: false,
            browseLabel: '',
            removeLabel: '',
            browseIcon: '<i class="fa fa-folder-open"></i>',
            removeIcon: '<i class="fa fa-remove"></i>',
            removeTitle: 'Cancel or reset changes',
            elErrorContainer: '#kv-avatar-errors-2',
            msgErrorClass: 'alert alert-block alert-danger',
            defaultPreviewContent: '<img src="<?= base_url() ?>uploads/no-preview.jpg" alt="Your Avatar" style="width:160px">',
            layoutTemplates: {main2: '{preview} {remove} {browse}'},
            allowedFileExtensions: ["jpg", "png", "gif", "jpeg"]
        });
    })
</script>