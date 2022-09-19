<!-- Page Heading/Breadcrumbs -->
<h1 class="mt-4 mb-3">Member Form</h1>
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?= base_url('site/members') ?>">Members</a>
    </li>
    <li class="breadcrumb-item active">Edit</li>
</ol>
<div class="content">
    <form action="<?= base_url('site/edit/' . $id) ?>" enctype="multipart/form-data" class="validateForm" method="POST">
        <div class="row">
            <div class="col-md-12">
                <h4>Main Details</h4>
            </div>
        </div>
        <hr>
        <div class="form-row">
            <div class='form-group col-md-4'>
                <div class="kv-avatar center-block text-center" style="width:200px">
                    <?php
                    $url = "";
                    if ($member['profile_pic'] != "default.jpg") {
                        $url = base_url() . 'uploads/users/thumb/' . $member['profile_pic'];
                    }
                    ?>
                    <input id="avatar-1" data-preview="<?= $url ?>" name="profile_pic" type="file" class="file-loading">
                    <div class="help-block"><small>Select file < 1500 KB</small></div>
                </div>
                <p id="kv-avatar-errors-1" class="error"></p>
            </div>
            <dic class="col-md-8">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" required="required" value="<?= $member['first_name'] ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Last Name</label>
                        <select name="last_name" id="last_name" class="form-control otheroption" data-dependent="sub_cast_div" required="required">
                            <option value="">--Select--</option>
                            <?php
                            if (!empty($subCasts)) {
                                foreach ($subCasts as $list) {
                                    $selected = ($member['last_name'] == $list['id']) ? 'selected' : '';

                                    echo "<option value='" . $list['id'] . "' ".$selected.">" . $list['name'] . "</option>";
                                }
                            }
                            ?>
                            <option value="Other">Add New</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <!-- <div class="form-group col-md-6">
                        <label>Sub Cast</label>
                        <input type="text" class="form-control" id="sub_cast" name="sub_cast" value="<?= $member['sub_cast'] ?>" placeholder="Sub Cast">
                    </div> -->
                    <div class="form-group col-md-6">
                        <label>Email</label>
                        <input type="email" class="form-control" id="email_address" name="email_address" value="<?= $member['email_address'] ?>" placeholder="Email" <?php echo ($member['head_id'] == '0') ? 'required="required"' : '';?>>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Is Expired</label>
                        <select name="is_expired" id="is_expired" class="form-control">
                            <option value="0" <?= ($member['is_expired'] == 0) ? "selected" : "" ?>>No</option>
                            <option value="1" <?= ($member['is_expired'] == 1) ? "selected" : "" ?>>Yes</option>
                        </select>

                    </div>
                    <div class="form-group col-md-6">
                        <label>Expire Date</label>
                        <input type="text" class="form-control datepicker" id="expire_date" value="<?= ($member['expire_date'] != "") ? date('Y/m/d', strtotime($member['expire_date'])) : "" ?>" name="expire_date" placeholder="Expire Date" readonly>

                        <?php 
                        if(($member['expire_date'] != "")){
                            echo '<script>setTimeout(function(){$("#expire_date").val("");},1000);</script>';
                        }    
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="inputPassword4">Gender</label>
                        <select name="gender" id="gender" class="form-control">
                            <option value="Male" <?= ($member['gender'] == "Male") ? "selected" : "" ?>>Male</option>
                            <option value="Female" <?= ($member['gender'] == "Female") ? "selected" : "" ?>>Female</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputPassword4">Relation</label>
                        <input type="text" <?= ($member['head_id'] == "0") ? "readonly" : "" ?> autocomplete="off" class="form-control" placeholder="Relation" id="relation_id" name="relation_id" value="<?= $member['relation'] ?>" required="required">
                    </div>
                </div>

            </dic>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h4>Contact Details</h4>
            </div>
        </div>
        <hr>
        <?php if ($member['head_id'] == "0") { ?>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="inputState">State</label>
                    <select id="state_id" name="state_id" class="form-control">
                        <option value="1">Gujarat</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="inputCity">City</label>
                    <select id="city_id" name="city_id" class="form-control" required="required">
                        <option value="">--Select--</option>
                        <?php
                        if (!empty($cities)) {
                            foreach ($cities as $city) {
                                $selected = "";
                                if ($member['city_id'] == $city['id']) {
                                    $selected = "selected";
                                }
                                echo '<option ' . $selected . ' value="' . $city['id'] . '">' . $city['city'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Area</label>
                    <input type="text" class="form-control" name="area" id="area" value="<?= $member['area'] ?>" placeholder="Area" required="required">
                </div>
                <div class="form-group col-md-3">
                    <label>Pincode</label>
                    <input type="number" class="form-control" name="pincode" id="pincode" value="<?= $member['pincode'] ?>" placeholder="Zip" required="required" onKeyPress="if(this.value.length==6) return false;">
                </div>
            </div>
        <?php } ?>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputState">Mobile</label>
                <input type="text" class="form-control" name="mobile" id="mobile" value="<?= $member['mobile'] ?>" placeholder="Mobile" <?php echo ($member['head_id'] == '0') ? 'required="required"' : '';?> onKeyPress="if(this.value.length==10) return false;">
            </div>
            <div class="form-group col-md-6">
                <label for="inputCity">Phone</label>
                <input type="text" class="form-control" name="phone" id="phone" value="<?= $member['phone'] ?>" placeholder="Phone">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h4>Personal Details</h4>
            </div>
        </div>
        <hr>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Sub Community</label>
                <input type="text" autocomplete="off" class="form-control" placeholder="Sub Community" id="sub_community_id" value="<?= $member['sub_community'] ?>" name="sub_community_id">
            </div>
            <div class="form-group col-md-4">
                <label>Local Community</label>
                <input type="text" autocomplete="off" class="form-control" placeholder="Local Community" id="local_community_id" value="<?= $member['local_community'] ?>" name="local_community_id">
            </div>
            <div class="form-group col-md-4">
                <label>Committee</label>
                <input type="text" autocomplete="off" class="form-control" placeholder="Committee" id="committee_id" value="<?= $member['committee'] ?>" name="committee_id">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Designation</label>
                <input type="text" autocomplete="off" class="form-control" placeholder="Designation" id="designation_id" name="designation_id" value="<?= $member['designation'] ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="inputEmail4">Education</label>
                <input type="text" autocomplete="off" class="form-control" placeholder="Education" id="education_id" name="education_id" value="<?= $member['education'] ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="inputState">Current Activity</label>
                <input type="text" placeholder="Current Activity" name="current_activity_id" id="current_activity_id" class="form-control" value="<?= $member['current_activity'] ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Blood Group</label>
                <select class="form-control" name="blood_group">
                    <option>--Select--</option>
                    <option value="A+" <?= ($member['blood_group'] == "A+") ? "selected" : "" ?>>A+</option>
                    <option value="A-" <?= ($member['blood_group'] == "A-") ? "selected" : "" ?>>A-</option>
                    <option value="B+" <?= ($member['blood_group'] == "B+") ? "selected" : "" ?>>B+</option>
                    <option value="B-" <?= ($member['blood_group'] == "B-") ? "selected" : "" ?>>B-</option>
                    <option value="AB+" <?= ($member['blood_group'] == "AB+") ? "selected" : "" ?>>AB+</option>
                    <option value="AB-" <?= ($member['blood_group'] == "AB-") ? "selected" : "" ?>>AB-</option>
                    <option value="O+" <?= ($member['blood_group'] == "O+") ? "selected" : "" ?>>O+</option>
                    <option value="O-" <?= ($member['blood_group'] == "O-") ? "selected" : "" ?>>O-</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>Native Place</label>
                <input type="text" class="form-control" id="native_place_id" name="native_place_id" placeholder="Native Place" value="<?= $member['native_place'] ?>">
            </div>
            <div class="form-group col-md-3">
                <label>Marital Status</label>
                <select id="marital_status" name="marital_status" class="form-control">
                    <option>--Select--</option>
                    <option value="Single" <?= ($member['marital_status'] == "Single") ? "selected" : "" ?>>Single</option>
                    <option value="Engaged" <?= ($member['marital_status'] == "Engaged") ? "selected" : "" ?>>Engaged</option>
                    <option value="Married" <?= ($member['marital_status'] == "Married") ? "selected" : "" ?>>Married</option>
                    <option value="Widow" <?= ($member['marital_status'] == "Widow") ? "selected" : "" ?>>Widow</option>
                    <option value="Widower" <?= ($member['marital_status'] == "Widower") ? "selected" : "" ?>>Widower</option>
                    <option value="Seperated" <?= ($member['marital_status'] == "Seperated") ? "selected" : "" ?>>Seperated</option>
                    <option value="Divorce" <?= ($member['marital_status'] == "Divorce") ? "selected" : "" ?>>Divorce</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>Marriage Date</label>
                <input type="text" name="marriage_date" class="form-control datepicker" id="marriage_date" value="<?= ($member['marriage_date'] != "") ? date('Y/m/d', strtotime($member['marriage_date'])) : "" ?>" placeholder="Marriage Date" readonly>
            </div>
        </div>
        <div class="form-row">
            <!-- <div class="form-group col-md-3">
                <label for="inputState">Mosaad</label>
                <input type="text" name="mosaad_id" class="form-control" id="mosaad_id" value="<?= $member['mosaad'] ?>" placeholder="Mosaad">
            </div> -->
            <div class="form-group col-md-3">
                <label>Gotra</label>
                <input type="text" name="gotra_id" class="form-control" id="gotra_id" value="<?= $member['gotra'] ?>" placeholder="Gotra">
            </div>
            <!-- <div class="form-group col-md-3">
                <label>Region</label>
                <input type="text" name="region" class="form-control" id="region" value="<?= $member['region'] ?>" placeholder="Region">
            </div> -->
            <!-- <div class="form-group col-md-3">
                <label>Is Rented</label>
                <select name="is_rented" class="form-control">
                    <option value="0" <?= ($member['is_rented'] == 0) ? "selected" : "" ?>>No</option>
                    <option value="1" <?= ($member['is_rented'] == 1) ? "selected" : "" ?>>Yes</option>
                </select>
            </div> -->
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Is Donor</label>
                <select name="is_donor" class="form-control">
                    <option value="0" <?= ($member['is_donor'] == 0) ? "selected" : "" ?>>No</option>
                    <option value="1" <?= ($member['is_donor'] == 1) ? "selected" : "" ?>>Yes</option>
                </select>
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
                    <?php
                    $url = "";
                    if ($member['business_logo'] != "") {
                        $url = base_url() . 'uploads/logos/' . $member['business_logo'];
                    }
                    ?>
                    <input id="avatar-2" name="business_logo" data-preview="<?= $url ?>" type="file" class="file-loading">
                    <div class="help-block"><small>Select file < 1500 KB</small></div>
                </div>
                <p id="kv-avatar-errors-2" class="error"></p>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="inputEmail4">Business Category</label>
                        <input type="text" autocomplete="off" class="form-control" placeholder="Business Category" id="business_category_id" name="business_category_id" value="<?= $member['business_category'] ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputPassword4">Business Sub Category</label>
                        <input type="text" autocomplete="off" class="form-control" placeholder="Business Sub Category" id="business_sub_category_id" name="business_sub_category_id" value="<?= $member['business_sub_category'] ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Company Name</label>
                        <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company Name" value="<?= $member['company_name'] ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Address</label>
                        <textarea name="business_address" placeholder="Address" id="business_address" class="form-control"><?= $member['business_address'] ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Website</label>
                <input type="text" class="form-control" id="website" name="website" placeholder="Website" value="<?= $member['website'] ?>">
            </div>
            <div class="form-group col-md-3">
                <label for="inputPassword4">Occupation</label>
                <input type="text" autocomplete="off" class="form-control" placeholder="Occupation" id="occupation_id" value="<?= $member['occupation'] ?>" name="occupation_id">
            </div>
            <div class="form-group col-md-3">
                <label>Work Details</label>
                <textarea name="work_details" placeholder="Work Details" id="work_details" class="form-control"><?= $member['work_details'] ?></textarea>
            </div>
        </div>
        <section id="matrimony-section" style="display: none;">
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
                        <label>Birth Date</label>
                        <input type="text" class="form-control datepicker" id="birth_date" name="birth_date" value="<?= ($member['birth_date'] != "") ? date('Y/m/d', strtotime($member['birth_date'])) : "" ?>" placeholder="Birth Date" readonly>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Birth Time</label>
                        <div class="bootstrap-timepicker">
                            <div class="form-group">
                                <div class="input-group">
                                    <input placeholder="Birth Time" name="birth_time"  type="text" value="<?= ($member['birth_time'] != "") ? date('h:i A', strtotime($member['birth_time'])) : "" ?>" class="timepicker form-control" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Birth Place</label>
                        <input type="text" class="form-control" id="birth_place" name="birth_place" value="<?= $member['birth_place'] ?>" placeholder="Birth Place">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Height (Meters)</label>
                        <input type="text" class="form-control" id="height" name="height" value="<?= $member['height'] ?>" placeholder="Height">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Weight (KG)</label>
                        <input type="text" class="form-control" id="weight" name="weight" value="<?= $member['weight'] ?>" placeholder="Weight">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Glasses?</label>
                        <select name="is_spect" class="form-control">
                            <option value="0" <?= ($member['is_spect'] == 0) ? "selected" : "" ?>>No</option>
                            <option value="1" <?= ($member['is_spect'] == 1) ? "selected" : "" ?>>Yes</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Hobby</label>
                        <textarea name="hobby" id="hobby" class="form-control" placeholder="Hobby"><?= $member['hobby'] ?></textarea>
                    </div>
                    <div class="form-group col-md-3">
                        <label>About Me</label>
                        <textarea name="about_me" placeholder="About Me" id="about_me" class="form-control"></textarea>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Expectation</label>
                        <textarea name="expectation" id="expectation" class="form-control" placeholder="Expectation"><?= $member['expectation'] ?></textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Facebook or other Social Profile URL</label>
                        <input type="text" name="facebook_profile" class="form-control" id="facebook_profile" placeholder="www.facebook.com" value="<?= $member['facebook_profile'] ?>">
                    </div>
                </div>
            </div>
        </section>
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
        if ($("#avatar-1").attr('data-preview') != "")
        {
            $("#avatar-1").fileinput({
                uploadUrl: '#',
                initialPreview: [$("#avatar-1").attr('data-preview')],
                initialPreviewAsData: true,
                initialPreviewFileType: 'image',
                initialPreviewConfig: [<?= $imgConfig ?>],
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
                defaultPreviewContent: '<img src="<?= base_url() ?>uploads/users/original/default.jpg" alt="Your Avatar" style="width:160px">',
                layoutTemplates: {main2: '{preview} {remove} {browse}'},
                allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
                browseOnZoneClick: true,
            });
        } else
        {
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
        }

        if ($("#avatar-2").attr('data-preview') != "")
        {
            $("#avatar-2").fileinput({
                uploadUrl: '#',
                initialPreview: [$("#avatar-2").attr('data-preview')],
                initialPreviewAsData: true,
                initialPreviewFileType: 'image',
                initialPreviewConfig: [<?= $logoConfig ?>],
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
                defaultPreviewContent: '<img src="<?= base_url() ?>uploads/no-preview.jpg" alt="Your Avatar" style="width:160px">',
                layoutTemplates: {main2: '{preview} {remove} {browse}'},
                allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
                browseOnZoneClick: true,
            });
        } else
        {
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
        }

        $('#marital_status').on('change', function () {
           
            if ($(this).val() == "Married") {
                $('.marriageDetails').show();
                $('#matrimony-section').hide();
            } else if ($(this).val() == "Engaged") {
                $('.marriageDetails').hide();
                $('#matrimony-section').hide();
            }
            else if($(this).val() == "") {
                $('#matrimony-section').hide();
                $('#marriage_date').val('');
                $('.marriageDetails').hide();
            }
            else {
                $('#matrimony-section').show();
                $('#marriage_date').val('');
                $('.marriageDetails').hide();
            }
        });
    });
</script>