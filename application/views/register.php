<!-- Page Heading/Breadcrumbs -->
<h1 class="mt-4 mb-3">Family Head Registration Form
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
                <h4>Family Details</h4>
            </div>
        </div>
        <hr>
        <div class="form-row"> 
            <div class='form-group col-md-4'>
                <div class="kv-avatar center-block text-center" style="width:200px">
                    <input id="avatar-1" name="profile_pic" type="file" class="file-loading">
                    <div class="help-block"><small>Select file < 3000 KB</small></div>
                </div>
                <p id="kv-avatar-errors-1" class="error"></p>
            </div>
            <div class="col-md-8">
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
                        <select class="form-control dependentAdmin" data-url="<?= base_url('site/getAdmins') ?>" data-dependent="adminLists" id="local_community_id" name="local_community_id" required="required">
                            <option value="">--Select--</option>
                        </select>
                    </div>
                </div>
                <span id="adminLists" style="display: none"></span>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="inputState">Mobile<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="mobile" id="mobile" placeholder="Mobile" required="required" onKeyPress="if(this.value.length==10) return false;">
                    </div>
                    <!-- <div class="form-group col-md-6">
                        <label>Password<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" autocomplete="off" class="form-control" placeholder="Enter Password" name="password" id="password" value="" minlength="6" maxlength="12" required="required">
                            <span class="input-group-addon" id="pass_hide_show" style="cursor: pointer;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
                        </div>
                        <label id="password-error" class="error" for="password"></label>
                    </div> -->
                    <div class="form-group col-md-6">
                        <label>PIN<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" autocomplete="off" class="form-control" placeholder="Enter Password" name="pin" id="password" value="" minlength="6" maxlength="12" required="required">
                            <span class="input-group-addon" id="pass_hide_show" style="cursor: pointer;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
                        </div>
                        <label id="password-error" class="error" for="password"></label>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label>Email<span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email_address" name="email_address" placeholder="Email" autocomplete="off">
                    </div>
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
                <select name="sub_cast_id" id="sub_cast_id" class="form-control otheroption" data-dependent="sub_cast_div" required="required">
                    <option value="">--Select--</option>
                    <?php
                    if (!empty($subCasts)) {
                        foreach ($subCasts as $list) {
                            echo "<option value='" . $list['id'] . "'>" . $list['name'] . "</option>";
                        }
                    }
                    ?>
                    <option value="Other">Add New</option>
                </select>
                <!--<div class="input-group" id="sub_cast_div" style="display:none">-->
                <!--    <input type="text" autocomplete="off" class="form-control" placeholder="Sub Cast" id="sub_cast_other" name="sub_cast_other">-->
                <!--    <div class="input-group-prepend">-->
                <!--        <div class="input-group-text"><a href="javascript:void()" class="openselect text-danger" data-select="sub_cast_id" data-other="sub_cast_div"><i class="fa fa-close"></i></a></div>-->
                <!--    </div>-->
                <!--</div>-->
                <div class="input-group" id="sub_cast_div" style="display:none">
                    <input type="text" autocomplete="off" class="form-control" placeholder="Sub Cast" id="sub_cast_other" name="sub_cast_other">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><a href="javascript:void()" class="openselect text-danger" data-select="sub_cast_id" data-other="sub_cast_div"><i class="fa fa-close"></i></a></div>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label for="inputPassword4">Gender<span class="text-danger">*</span></label>
                <select name="gender" id="gender" class="form-control" required="required">
                    <option value="">--Select--</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Father/Husband Name</label>
                <input type="text" class="form-control" id="father_name" name="father_name" placeholder="Father Name">
            </div>
            <div class="form-group col-md-3">
                <label>Mother Name</label>
                        <input type="text" class="form-control" id="mother_name" name="mother_name" placeholder="Mother Name">
            </div>
            
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Birth Date</label>
                <!-- <label>Birth Date<span class="text-danger">*</span></label> -->
                <!--<input type="text" class="form-control datepicker" id="birth_date" name="birth_date" placeholder="Birth Date" autocomplete="off" required="required" readonly>-->
                <div class="input-group">
                    <input type="text" class="form-control datepicker" id="birth_date" name="birth_date" placeholder="Birth Date" autocomplete="off" readonly>
                    <span class="input-group-addon"><a href="javascript:void()" onclick="javascript:$('#birth_date').val('')"><i class="fa fa-close"></i></a></span>
                </div>
            </div>
            <div class="form-group col-md-3" style="display: none">
                <label>Is Live?</label>
                <select name="is_expired" id="is_expired" class="form-control">
                    <option value="0">No</option>
                    <option value="1" selected="selected">Yes</option>
                </select>
            </div>
            <div class="form-group col-md-3" id="expiredDate" style="display: none">
                <label>Expire Date</label>
                <!--<input type="text" class="form-control datepicker" id="expire_date" name="expire_date" placeholder="Expire Date" autocomplete="off" readonly>-->
                <div class="input-group">
                    <input placeholder="Birth Time" name="birth_time" id="expire_date"  type="text" class="timepicker form-control" value="" autocomplete="off">
                    <span class="input-group-addon"><a href="javascript:void()" onclick="javascript:$('#expire_date').val('')"><i class="fa fa-close"></i></a></span>
                </div>
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
                <select name="city_id" id="city_id" class="form-control otheroption" data-dependent="city_div" required="required">
                    <option value="">--Select--</option>
                    <?php
                    if (!empty($cities)) {
                        foreach ($cities as $list) {
                            echo "<option value='" . $list['id'] . "'>" . $list['city'] . "</option>";
                        }
                    }
                    ?>
                    <option value="Other">Add New</option>
                </select>
                <div class="input-group" id="city_div" style="display:none">
                    <input type="text" autocomplete="off" class="form-control" placeholder="City" id="city_other" name="city_other">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><a href="javascript:void()" class="openselect text-danger" data-select="city_id" data-other="city_div"><i class="fa fa-close"></i></a></div>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label>Area<span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="area" id="area" placeholder="Area" required="required">
            </div>
            <div class="form-group col-md-3">
                <label>Pincode<span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="pincode" id="pincode" placeholder="Zip" required="required" onKeyPress="if(this.value.length==6) return false;" minlength="6">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="inputCity">Phone</label>
                <input type="number" class="form-control" name="phone" id="phone" placeholder="Phone" onKeyPress="if(this.value.length==15) return false;">
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
                <label>Native District</label>
                <select name="distinct_id" id="distinct_id" class="form-control otheroption" data-url="<?= base_url('site/getNativePlaces') ?>" data-dependent="distinct_div" data-select-dep="native_place_id">
                    <option value="">--Select--</option>
                    <?php
                    if (!empty($distincts)) {
                        foreach ($distincts as $list) {
                            echo "<option value='" . $list['id'] . "'>" . $list['name'] . "</option>";
                        }
                    }
                    ?>
                    <option value="Other">Add New</option>
                </select>
                <div class="input-group" id="distinct_div" style="display:none">
                    <input type="text" placeholder="District" name="distinct_other" id="distinct_other" class="form-control" >
                    <div class="input-group-prepend">
                        <div class="input-group-text"><a href="javascript:void()" class="openselect text-danger" data-select="distinct_id" data-other="distinct_div"><i class="fa fa-close"></i></a></div>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label>Native Village</label>
                <select name="native_place_id" id="native_place_id" class="form-control otheroption" data-dependent="native_place_div">
                    <option value="">--Select--</option>
                    <option value="Other">Add New</option>
                </select>
                <div class="input-group" id="native_place_div" style="display:none">
                    <input type="text" placeholder="Native Place" name="native_place_other" id="native_place_other" class="form-control" >
                    <div class="input-group-prepend">
                        <div class="input-group-text"><a href="javascript:void()" class="openselect text-danger" data-select="native_place_id" data-other="native_place_div"><i class="fa fa-close"></i></a></div>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label for="inputState">Current Activity</label>
                <select name="current_activity_id" id="current_activity_id" class="form-control otheroption" data-dependent="current_activity_div">
                    <option value="">--Select--</option>
                    <?php
                    if (!empty($currentActivities)) {
                        foreach ($currentActivities as $list) {
                            echo "<option value='" . $list['id'] . "'>" . $list['activity'] . "</option>";
                        }
                    }
                    ?>
                    <option value="Other">Add New</option>
                </select>
                <div class="input-group" id="current_activity_div" style="display:none">
                    <input type="text" placeholder="Current Activity" name="current_activity_other" id="current_activity_other" class="form-control" >
                    <div class="input-group-prepend">
                        <div class="input-group-text"><a href="javascript:void()" class="openselect text-danger" data-select="current_activity_id" data-other="current_activity_div"><i class="fa fa-close"></i></a></div>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label for="inputEmail4">Education</label>
                <select name="education_id" id="education_id" class="form-control otheroption" data-dependent="education_div">
                    <option value="">--Select--</option>
                    <?php
                    if (!empty($educations)) {
                        foreach ($educations as $list) {
                            echo "<option value='" . $list['id'] . "'>" . $list['name'] . "</option>";
                        }
                    }
                    ?>
                    <option value="Other">Add New</option>
                </select>
                <div class="input-group" id="education_div" style="display:none">
                    <input type="text" autocomplete="off" class="form-control" placeholder="Education" id="education_other" name="education_other">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><a href="javascript:void()" class="openselect text-danger" data-select="education_id" data-other="education_div"><i class="fa fa-close"></i></a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Blood Group</label>
                <select class="form-control" name="blood_group">
                    <option value="">--Select--</option>
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
                    <option value="">--Select--</option>
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
                <!--<input type="text" name="marriage_date" class="form-control datepicker" id="marriage_date" placeholder="Marriage Date" autocomplete="off" readonly>-->
                <div class="input-group">
                    <input type="text" name="marriage_date" class="form-control datepicker" id="marriage_date" placeholder="Marriage Date" autocomplete="off" readonly>
                    <span class="input-group-addon"><a href="javascript:void()" onclick="javascript:$('#marriage_date').val('')"><i class="fa fa-close"></i></a></span>
                </div>
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
                    <div class="help-block"><small>Select file < 3000 KB</small></div>
                </div>
                <p id="kv-avatar-errors-2" class="error"></p>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="inputEmail4">Business Category</label>
                        <select name="business_category_id" id="business_category_id" class="form-control otheroption" data-dependent="business_category_div" data-url="<?= base_url('site/getBusinessSubCategory') ?>" data-select-dep="business_sub_category_id">
                            <option value="">--Select--</option>
                            <?php
                            if (!empty($businessCategories)) {
                                foreach ($businessCategories as $list) {
                                    echo "<option value='" . $list['id'] . "'>" . $list['name'] . "</option>";
                                }
                            }
                            ?>
                            <option value="Other">Add New</option>
                        </select>
                        <div class="input-group" id="business_category_div" style="display:none">
                            <input type="text" autocomplete="off" class="form-control" placeholder="Business Category" id="business_category_other" name="business_category_other">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><a href="javascript:void()" class="openselect text-danger" data-select="business_category_id" data-other="business_category_div"><i class="fa fa-close"></i></a></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputPassword4">Business Sub Category</label>
                        <select name="business_sub_category_id" id="business_sub_category_id" class="form-control otheroption" data-dependent="business_sub_category_div">
                            <option value="">--Select--</option>
                            <option value="Other">Add New</option>
                        </select>
                        <div class="input-group" id="business_sub_category_div" style="display:none">
                            <input type="text" autocomplete="off" class="form-control" placeholder="Business Sub Category" id="business_sub_category_other" name="business_sub_category_other">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><a href="javascript:void()" class="openselect text-danger" data-select="business_sub_category_id" data-other="business_sub_category_div"><i class="fa fa-close"></i></a></div>
                            </div>
                        </div>
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
                <select name="occupation_id" id="occupation_id" class="form-control otheroption" data-dependent="occupation_div">
                    <option value="">--Select--</option>
                    <?php
                    if (!empty($occupations)) {
                        foreach ($occupations as $list) {
                            echo "<option value='" . $list['id'] . "'>" . $list['occupation'] . "</option>";
                        }
                    }
                    ?>
                    <option value="Other">Add New</option>
                </select>
                <div class="input-group" id="occupation_div" style="display:none">
                    <input type="text" autocomplete="off" class="form-control" placeholder="Occupation" id="occupation_other" name="occupation_other">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><a href="javascript:void()" class="openselect text-danger" data-select="occupation_id" data-other="occupation_div"><i class="fa fa-close"></i></a></div>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label>Work Details</label>
                <textarea name="work_details" placeholder="Work Details" id="work_details" class="form-control"></textarea>
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
                        <label>Birth Time</label>
                        <div class="bootstrap-timepicker">
                            <!--<div class="form-group">-->
                            <!--    <div class="input-group">-->
                            <!--        <input placeholder="Birth Time" name="birth_time"  type="text" class="timepicker form-control" value="" autocomplete="off">-->
                            <!--    </div>-->
                            <!--</div>-->
                            <div class="input-group">
                    <input placeholder="Birth Time" name="birth_time" id="birth_time"  type="text" class="timepicker form-control" value="" readonly autocomplete="off">
                    <span class="input-group-addon"><a href="javascript:void()" onclick="javascript:$('#birth_time').val('')"><i class="fa fa-close"></i></a></span>
                </div>
                            
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Birth Place</label>
                        <input type="text" class="form-control" id="birth_place" name="birth_place" placeholder="Birth Place">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Height (Meters)</label>
                        <input type="number" class="form-control" id="height" name="height" placeholder="Height">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Weight (KG)</label>
                        <input type="number" class="form-control" id="weight" name="weight" placeholder="Weight">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Glasses?</label>
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
                        <label>Facebook or other Social Profile URL</label>
                        <input type="text" name="facebook_profile" class="form-control" id="facebook_profile" placeholder="http://www.facebook.com">
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

        $('#is_expired').on('change', function () {
            if ($(this).val() == "0") {
                $('#expiredDate').show();
            } else {
                $('#expire_date').val('');
                $('#expiredDate').hide();
            }
        });

        $('#marital_status').on('change', function () {
            if ($(this).val() == "Married") {
                $('.marriageDetails').show();
                $('#matrimony-section').hide();
            } else if ($(this).val() == "Engaged") {
                $('.marriageDetails').hide();
                $('#matrimony-section').hide();
            } else if($(this).val() == "") {
                $('#matrimony-section').hide();
                $('#marriage_date').val('');
                $('.marriageDetails').hide();
            } else {
                $('#matrimony-section').show();
                $('#marriage_date').val('');
                $('.marriageDetails').hide();
            }
        });

        $("#avatar-1").fileinput({
            overwriteInitial: true,
            maxFileSize: 3000,
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
            allowedFileExtensions: ["jpg", "png", "jpeg"]
        });

        $("#avatar-2").fileinput({
            overwriteInitial: true,
            maxFileSize: 3000,
            showClose: false,
            showCaption: false,
            browseLabel: '',
            removeLabel: '',
            browseIcon: '<i class="fa fa-folder-open"></i>',
            removeIcon: '<i class="fa fa-remove"></i>',
            removeTitle: 'Cancel or reset changes',
            elErrorContainer: '#kv-avatar-errors-2',
            msgErrorClass: 'alert alert-block alert-danger',
            defaultPreviewContent: '<img src="<?= base_url() ?>uploads/company-logo.jpg" alt="Your Avatar" style="width:160px">',
            layoutTemplates: {main2: '{preview} {remove} {browse}'},
            allowedFileExtensions: ["jpg", "png", "jpeg"]
        });
    })
</script>