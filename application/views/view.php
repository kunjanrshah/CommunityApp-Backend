<!-- Page Heading/Breadcrumbs -->
<h1 class="mt-4 mb-3">Member Detail
</h1>

<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?= base_url('site/members') ?>">Members</a>
    </li>
    <li class="breadcrumb-item active">View</li>
</ol>
<div class="content">
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
                $url = base_url() . 'uploads/users/thumb/' . $member['profile_pic'];
                ?>
                <img src="<?= $url ?>" width="150" height="200" />
            </div>
            <p id="kv-avatar-errors-1" class="error"></p>
        </div>
        <dic class="col-md-8">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Sub Community</label>
                    <input type="text" autocomplete="off" class="form-control" placeholder="Sub Community" id="sub_community_id" value="<?= $member['sub_community'] ?>" name="sub_community_id" disabled="disabled">
                </div>
                <div class="form-group col-md-6">
                    <label>Local Community</label>
                    <input type="text" autocomplete="off" class="form-control" placeholder="Local Community" id="local_community_id" value="<?= $member['local_community'] ?>" name="local_community_id" disabled="disabled">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" required="required" value="<?= $member['first_name'] ?>" disabled="disabled">
                </div>
                <div class="form-group col-md-6">
                    <label>Middle Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?= $member['last_name'] ?>" placeholder="Middle Name" required="required" disabled="disabled">
                </div>
            </div>
            <div class="row">
                <?php if ($member['head_id'] == "0") { ?>
                    <div class="form-group col-md-6">
                        <label>Last Name</label>
                        <input type="text" class="form-control" id="sub_cast" name="sub_cast" value="<?= $member['sub_cast'] ?>" placeholder="Sub Cast" disabled="disabled">
                    </div>
                <?php } ?>
                <div class="form-group col-md-<?= ($member['head_id'] == "0") ? "6" : "12" ?>">
                    <label>Email</label>
                    <input type="email" class="form-control" id="email_address" name="email_address" value="<?= $member['email_address'] ?>" placeholder="Email" required="required" disabled="disabled">
                </div>
            </div>
        </dic>
    </div>
    <div class="form-row">
        <div class="col-md-12">
            <div class="row">
                <div class="form-group col-md-3">
                    <label>Birth Date</label>
                    <input type="text" class="form-control datepicker" id="birth_date" name="birth_date" value="<?= ($member['birth_date'] != "") ? date('d/m/Y', strtotime($member['birth_date'])) : "" ?>" placeholder="Birth Date" disabled="disabled">
                </div>
                <div class="form-group col-md-3">
                    <label for="inputPassword4">Gender</label>
                    <select name="gender" id="gender" class="form-control" disabled="disabled">
                        <option value="Male" <?= ($member['gender'] == "Male") ? "selected" : "" ?>>Male</option>
                        <option value="Female" <?= ($member['gender'] == "Female") ? "selected" : "" ?>>Female</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="inputState">Mobile</label>
                    <input type="text" class="form-control" name="mobile" id="mobile" value="<?= $member['mobile'] ?>" placeholder="Mobile" required="required" disabled="disabled">
                </div>
                <div class="form-group col-md-3">
                    <label for="inputPassword4">Relation</label>
                    <input type="text" autocomplete="off" class="form-control" placeholder="Relation" id="relation_id" name="relation_id" value="<?= $member['relation'] ?>" required="required" disabled="disabled">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-3">
                    <label>Is Live?</label>
                    <select name="is_expired" id="is_expired" class="form-control" disabled="disabled">
                        <option value="0" <?= ($member['is_expired'] == 0) ? "selected" : "" ?>>No</option>
                        <option value="1" <?= ($member['is_expired'] == 1) ? "selected" : "" ?>>Yes</option>
                    </select>
                </div>
                <?php if ($member['is_expired'] == 0) { ?>
                    <div class="form-group col-md-3">
                        <label>Expire Date</label>
                        <input type="text" class="form-control datepicker" id="expire_date" value="<?= ($member['expire_date'] != "") ? date('d/m/Y', strtotime($member['expire_date'])) : "" ?>" name="expire_date" placeholder="Expire Date" disabled="disabled">
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h4>Contact Details</h4>
        </div>
    </div>
    <hr>
    <?php if ($member['head_id'] == "0") { ?>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputAddress">Permenant Address</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="<?= $member['address'] ?>" disabled="disabled">
            </div>
            <div class="form-group col-md-6">
                <label for="inputAddress">Local Address</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="<?= $member['local_address'] ?>" disabled="disabled">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="inputState">State</label>
                <select id="state_id" name="state_id" class="form-control" disabled="disabled">
                    <option value="">--Select--</option>
                    <?php
                    if (!empty($states)) {
                        foreach ($states as $state) {
                            $selected = "";
                            if ($member['state_id'] == $state['id']) {
                                $selected = "selected";
                            }
                            echo '<option ' . $selected . ' value="' . $state['id'] . '">' . $state['state'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="inputCity">City</label>
                <select id="city_id" name="city_id" class="form-control" required="required" disabled="disabled">
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
                <input type="text" class="form-control" name="area" id="area" value="<?= $member['area'] ?>" placeholder="Area" required="required" disabled="disabled">
            </div>
            <div class="form-group col-md-3">
                <label>Pincode</label>
                <input type="text" class="form-control" name="pincode" id="pincode" value="<?= $member['pincode'] ?>" placeholder="Zip" required="required" disabled="disabled">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputCity">Phone</label>
                <input type="text" class="form-control" name="phone" id="phone" value="<?= $member['phone'] ?>" placeholder="Phone" disabled="disabled">
            </div>
        </div>
    <?php } else { ?>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputCity">Phone</label>
                <input type="text" class="form-control" name="phone" id="phone" value="<?= $member['phone'] ?>" placeholder="Phone" disabled="disabled">
            </div>
        </div>
        <div class="form-group col-md-6">
            <label for="inputAddress">Local Address</label>
            <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="<?= $member['local_address'] ?>" disabled="disabled">
        </div>
    <?php } ?>
    <div class="row">
        <div class="col-md-12">
            <h4>Personal Details</h4>
        </div>
    </div>
    <hr>
    <div class="form-row">
        <div class="form-group col-md-3">
            <label>Native District</label>
            <input type="text" class="form-control" id="distinct_id" name="distinct_id" placeholder="Distinct" value="<?= $member['distinct'] ?>" disabled="disabled">
        </div>
        <div class="form-group col-md-3">
            <label>Native Village</label>
            <input type="text" class="form-control" id="native_place_id" name="native_place_id" placeholder="Native Place" value="<?= $member['native_place'] ?>" disabled="disabled">
        </div>
        <div class="form-group col-md-3">
            <label for="inputState">Current Activity</label>
            <input type="text" placeholder="Current Activity" name="current_activity_id" id="current_activity_id" class="form-control" value="<?= $member['current_activity'] ?>" disabled="disabled">
        </div>
        <div class="form-group col-md-3">
            <label for="inputEmail4">Education</label>
            <input type="text" autocomplete="off" class="form-control" placeholder="Education" id="education_id" name="education_id" value="<?= $member['education'] ?>" disabled="disabled">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-3">
            <label>Blood Group</label>
            <select class="form-control" name="blood_group" disabled="disabled">
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
            <label>Is Blood Donor?</label>
            <select name="is_donor" class="form-control" disabled="disabled">
                <option value="0" <?= ($member['is_donor'] == 0) ? "selected" : "" ?>>No</option>
                <option value="1" <?= ($member['is_donor'] == 1) ? "selected" : "" ?>>Yes</option>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label>Marital Status</label>
            <select id="marital_status" name="marital_status" class="form-control" disabled="disabled">
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
            <input type="text" name="marriage_date" class="form-control datepicker" id="marriage_date" value="<?= ($member['marriage_date'] != "" && $member['marriage_date'] != "0000-00-00") ? date('d/m/Y', strtotime($member['marriage_date'])) : "" ?>" placeholder="Marriage Date" disabled="disabled">
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
                $url = base_url() . 'uploads/company-logo.jpg';
                if ($member['business_logo'] != "") {
                    $url = base_url() . 'uploads/logos/' . $member['business_logo'];
                }
                ?>
                <img src="<?= $url ?>" width="100" height="150" alt="Logo" />
            </div>
            <p id="kv-avatar-errors-2" class="error"></p>
        </div>
        <div class="col-md-8">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="inputEmail4">Business Category</label>
                    <input type="text" autocomplete="off" class="form-control" placeholder="Business Category" id="business_category_id" name="business_category_id" value="<?= $member['business_category'] ?>" disabled="disabled">
                </div>
                <div class="form-group col-md-6">
                    <label for="inputPassword4">Business Sub Category</label>
                    <input type="text" autocomplete="off" class="form-control" placeholder="Business Sub Category" id="business_sub_category_id" name="business_sub_category_id" value="<?= $member['business_sub_category'] ?>" disabled="disabled">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Company Name</label>
                    <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company Name" value="<?= $member['company_name'] ?>" disabled="disabled">
                </div>
                <div class="form-group col-md-6">
                    <label>Address</label>
                    <textarea name="business_address" placeholder="Address" id="business_address" class="form-control" disabled="disabled"><?= $member['business_address'] ?></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-3">
            <label>Website</label>
            <input type="text" class="form-control" id="website" name="website" placeholder="http://www.google.com" value="<?= $member['website'] ?>" disabled="disabled">
        </div>
        <div class="form-group col-md-3">
            <label for="inputPassword4">Occupation</label>
            <input type="text" autocomplete="off" class="form-control" placeholder="Occupation" id="occupation_id" value="<?= $member['occupation'] ?>" name="occupation_id" disabled="disabled">
        </div>
        <div class="form-group col-md-3">
            <label>Work Details</label>
            <textarea name="work_details" placeholder="Work Details" id="work_details" class="form-control" disabled="disabled"><?= $member['work_details'] ?></textarea>
        </div>
    </div>
    <?php if ($member['marital_status'] == "Engaged" || $member['marital_status'] == "Married") { ?>
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
                        <input type="radio" class="form-check-input" name="matrimony" value="No" <?= ($member['matrimony'] == "No") ? "checked" : "" ?> disabled="disabled">No
                    </label>
                </div>
                <div class="form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="matrimony" value="Yes" <?= ($member['matrimony'] == "Yes") ? "checked" : "" ?> disabled="disabled">Yes
                    </label>
                </div>
            </div>
        </div>
        <?php if ($member['matrimony'] == "Yes") { ?>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Birth Time</label>
                    <div class="bootstrap-timepicker">
                        <div class="form-group">
                            <div class="input-group">
                                <input placeholder="Birth Time" name="birth_time"  type="text" value="<?= ($member['birth_time'] != "") ? date('h:i A', strtotime($member['birth_time'])) : "" ?>" class="timepicker form-control" autocomplete="off" disabled="disabled">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-3">
                    <label>Birth Place</label>
                    <input type="text" class="form-control" id="birth_place" name="birth_place" value="<?= $member['birth_place'] ?>" placeholder="Birth Place" disabled="disabled">
                </div>
                <div class="form-group col-md-3">
                    <label>Weight (KG)</label>
                    <input type="text" class="form-control" id="weight" name="weight" value="<?= $member['weight'] ?>" placeholder="Weight" disabled="disabled">
                </div>
                <div class="form-group col-md-3">
                    <label>Height (Meters)</label>
                    <input type="text" class="form-control" id="height" name="height" value="<?= $member['height'] ?>" placeholder="Height" disabled="disabled">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Glasses?</label>
                    <select name="is_spect" class="form-control" disabled="disabled">
                        <option value="0" <?= ($member['is_spect'] == 0) ? "selected" : "" ?>>No</option>
                        <option value="1" <?= ($member['is_spect'] == 1) ? "selected" : "" ?>>Yes</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>About Me</label>
                    <textarea name="about_me" placeholder="About Me" id="about_me" class="form-control" disabled="disabled"><?= $member['about_me'] ?></textarea>
                </div>
                <div class="form-group col-md-3">
                    <label>Hobby</label>
                    <textarea name="hobby" id="hobby" class="form-control" placeholder="Hobby" disabled="disabled"><?= $member['hobby'] ?></textarea>
                </div>
                <div class="form-group col-md-3">
                    <label>Expectation</label>
                    <textarea name="expectation" id="expectation" class="form-control" placeholder="Expectation" disabled="disabled"><?= $member['expectation'] ?></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Facebook or other Social Profile URL</label>
                    <input type="text" name="facebook_profile" class="form-control" value="<?= $member['facebook_profile'] ?>" id="facebook_profile" placeholder="Facebook Profile" disabled="disabled">
                </div>
            </div>
            <?php
        }
    }
    ?>

    <br>
</div>