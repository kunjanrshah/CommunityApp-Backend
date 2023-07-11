<section class="content-header">
    <h1>
        Edit User
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= base_url() ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?= base_url() ?>admin/users">Users</a></li>
        <li class="active">Edit</li>
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

                <form action="<?= base_url('admin/users/edit/' . $id) ?>" role="form" enctype="multipart/form-data" class="validateForm" method="POST">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4><?= ($member['head_id'] == "0" ? "Family" : "Member") ?> Details</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class='form-group'>
                                    <div class="kv-avatar center-block text-center" style="width:200px">
                                        <?php
                                        $url = "";
                                        if ($member['profile_pic'] != "default.jpg") {
                                            $url = base_url() . 'uploads/users/original/' . $member['profile_pic'];
                                        }
                                        ?>
                                        <input id="avatar-1" data-preview="<?= $url ?>" name="profile_pic" type="file" class="file-loading">
                                        <div class="help-block"><small>Select file < 3000 KB</small></div>
                                    </div>
                                    <p id="kv-avatar-errors-1" class="error" style="display:none"></p>
                                </div>
                            </div>
                            <dic class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label> <?= ($member['head_id'] == 0) ? 'Family Head Code' : 'Member Code' ?> </label>

                                        <div class="input-group">
                                            <input type="text" autocomplete="off" class="form-control" placeholder="<?= ($member['head_id'] == 0) ? 'Family Head Code' : 'Member Code' ?>" name="member_code" id="member_code" value="<?= $member['member_code'] ?>" dir="ltr" style="text-transform: uppercase;">
                                            <span class="input-group-addon"><?= $id ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($member['head_id'] == "0") { ?>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Sub Community<span class="text-danger">*</span></label>
                                            <select class="form-control dependent" data-url="<?= base_url('site/getLocalCommunity') ?>" data-dependent="local_community_id" id="sub_community_id" name="sub_community_id" required="required">
                                                <option value="">--Select--</option>
                                                <?php
                                                if (!empty($subCommunity)) {
                                                    foreach ($subCommunity as $list) {
                                                        $selected = "";
                                                        if ($list['id'] == $member['sub_community_id']) {
                                                            $selected = "selected";
                                                        }
                                                        echo '<option ' . $selected . ' value="' . $list['id'] . '">' . $list['name'] . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Local Community<span class="text-danger">*</span></label>
                                            <select class="form-control" id="local_community_id" name="local_community_id" required="required">
                                                <option value="">--Select--</option>
                                                <?php
                                                if (!empty($localCommunity)) {
                                                    foreach ($localCommunity as $list) {
                                                        $selected = "";
                                                        if ($list['id'] == $member['local_community_id']) {
                                                            $selected = "selected";
                                                        }
                                                        echo '<option ' . $selected . ' value="' . $list['id'] . '">' . $list['name'] . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" required="required" value="<?= $member['first_name'] ?>">
                                        </div>
                                    </div>
                                    <?php //if ($member['head_id'] == "0") { ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Last Name</label>
                                                <select name="sub_cast_id" id="sub_cast_id" class="form-control otheroption" data-dependent="sub_cast_div">
                                                    <option value="">--Select--</option>
                                                    <?php
                                                    if (!empty($subCasts)) {
                                                        foreach ($subCasts as $list) {
                                                            $selected = "";
                                                            if ($list['id'] == $member['sub_cast_id']) {
                                                                $selected = "selected";
                                                            }
                                                            echo "<option " . $selected . " value='" . $list['id'] . "'>" . $list['name'] . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                    <option value="Other">Other</option>
                                                </select>
                                                <div class="input-group" id="sub_cast_div" style="display:none">
                                                    <input type="text" autocomplete="off" class="form-control" placeholder="Sub Cast" id="sub_cast_other" name="sub_cast_other">
                                                    <span class="input-group-addon"><a href="javascript:void()" class="openselect text-danger" data-select="sub_cast_id" data-other="sub_cast_div"><i class="fa fa-close"></i></a></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php //} ?>
                                    <!-- <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Middle Name</label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?= $member['last_name'] ?>" placeholder="Middle Name" required="required">
                                        </div>
                                    </div> -->
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Father/Husband Name</label>
                                        <input type="text" class="form-control" id="father_name" name="father_name" placeholder="Father Name" value="<?= $member['father_name']?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Mother Name</label>
                                        <input type="text" class="form-control" id="mother_name" name="mother_name" placeholder="Mother Name" value="<?= $member['mother_name']?>">
                                    </div>
                                </div>
                                <?php if ($member['head_id'] != "0") { ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inputPassword4">Relation</label>
                                            <select name="relation_id" <?= ($member['head_id'] == "0") ? "disabled" : "" ?> id="relation_id" class="form-control otheroption" data-dependent="relation_div" required="required">
                                                <option value="">--Select--</option>
                                                <?php
                                                if (!empty($relations)) {
                                                    foreach ($relations as $list) {
                                                        $selected = "";
                                                        if ($member['relation_id'] == $list['id']) {
                                                            $selected = "selected";
                                                        }
                                                        echo "<option " . $selected . " value='" . $list['id'] . "'>" . $list['name'] . "</option>";
                                                    }
                                                }
                                                ?>
                                                <option value="Other">Other</option>
                                            </select>
                                            <div class="input-group" id="relation_div" style="display:none">
                                                <input type="text" placeholder="Relation" name="relation_other" id="relation_other" class="form-control" >
                                                <span class="input-group-addon"><a href="javascript:void()" class="openselect text-danger" data-select="relation_id" data-other="relation_div"><i class="fa fa-close"></i></a></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    }?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inputState">Mobile</label>
                                            <input type="number" class="form-control" name="mobile" id="mobile" value="<?= $member['mobile'] ?>" placeholder="Mobile" <?= ($member['head_id'] == 0) ? "required" : "" ?>>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control" id="email_address" name="email_address" value="<?= $member['email_address'] ?>" placeholder="Email">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Birth Date</label>
                                            <!--<input type="text" class="form-control datepicker" id="birth_date" name="birth_date" value="<?= ($member['birth_date'] != "") ? date('d/m/Y', strtotime($member['birth_date'])) : "" ?>" placeholder="Birth Date" autocomplete="off" readonly>-->
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" id="birth_date" name="birth_date" value="<?= ($member['birth_date'] != "") ? date('d/m/Y', strtotime($member['birth_date'])) : "" ?>" placeholder="Birth Date" autocomplete="off" >
                                                <span class="input-group-addon"><a href="javascript:void()" onclick="javascript:$('#birth_date').val('')"><i class="fa fa-close"></i></a></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inputPassword4">Gender</label>
                                            <select name="gender" id="gender" class="form-control">
                                                <option value="Male" <?= ($member['gender'] == "Male") ? "selected" : "" ?>>Male</option>
                                                <option value="Female" <?= ($member['gender'] == "Female") ? "selected" : "" ?>>Female</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($member['head_id'] != "0") { ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Is Live?</label>
                                            <select name="is_expired" id="is_expired" class="form-control">
                                                <option value="0" <?= ($member['is_expired'] == 0) ? "selected" : "" ?>>No</option>
                                                <option value="1" <?= ($member['is_expired'] == 1) ? "selected" : "" ?>>Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="expiredDate" style="display:<?= ($member['is_expired'] == 0) ? "block" : "none" ?>">
                                        <div class="form-group">
                                            <label>Expire Date</label>
                                            <!--<input type="text" class="form-control datepicker" id="expire_date" value="<?= ($member['expire_date'] != "") ? date('d/m/Y', strtotime($member['expire_date'])) : "" ?>" name="expire_date" placeholder="Expire Date" readonly>-->
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" id="expire_date" value="<?= ($member['expire_date'] != "") ? date('d/m/Y', strtotime($member['expire_date'])) : "" ?>" name="expire_date" placeholder="Expire Date" readonly>
                                                <span class="input-group-addon"><a href="javascript:void()" onclick="javascript:$('#expire_date').val('')"><i class="fa fa-close"></i></a></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }?>

                                <!-- <?php if ($member['head_id'] == "0") { ?>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Password</label>
                                                <div class="input-group">
                                                    <input type="password" autocomplete="off" class="form-control" placeholder="Enter Password" name="password" id="password" value="<?= $member['plain_password'];?>" minlength="6" maxlength="12" style="z-index:1">
                                                    <span class="input-group-addon" onclick="pass_hide_show()" style="cursor: pointer;" id="pass_hide_show"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                <?php }?> -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>PIN (Numeric)</label>
                                            <div class="input-group">
                                                <input type="password" autocomplete="off" class="form-control" placeholder="Enter Password" name="pin" id="password" value="<?= $member['plain_password'];?>" minlength="6" maxlength="12" style="z-index:1">
                                                <span class="input-group-addon" onclick="pass_hide_show()" style="cursor: pointer;" id="pass_hide_show"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
                                            </div>

                                        </div>
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
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="inputAddress">Permenant Address<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="address" name="address" value="<?= $member['address'] ?>" placeholder="Address" required="required">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="inputAddress">Local Address<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="local_address" name="local_address" value="<?= $member['local_address'] ?>" placeholder="Local Address">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="inputState">State<span class="text-danger">*</span></label>
                                    <select id="state_id" name="state_id" class="dependent form-control" data-url="<?= base_url('site/getCityByState') ?>" data-dependent="city_id" required="required">
                                        <option value="">--Select--</option>
                                        <?php
                                        if (!empty($states)) {
                                            foreach ($states as $state) {
                                                $selected = "";
                                                if ($state['id'] == $member['state_id']) {
                                                    $selected = "selected";
                                                }
                                                echo '<option ' . $selected . ' value="' . $state['id'] . '">' . $state['state'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="inputCity">City<span class="text-danger">*</span></label>
                                    <select name="city_id" id="city_id" class="form-control otheroption" data-dependent="city_div">
                                        <option value="">--Select--</option>
                                        <?php
                                        if (!empty($cities)) {
                                            foreach ($cities as $list) {
                                                $selected = "";
                                                if ($list['id'] == $member['city_id']) {
                                                    $selected = "selected";
                                                }
                                                echo "<option " . $selected . " value='" . $list['id'] . "'>" . $list['city'] . "</option>";
                                            }
                                        }
                                        ?>
                                        <option value="Other">Other</option>
                                    </select>
                                    <div class="input-group" id="city_div" style="display:none">
                                        <input type="text" autocomplete="off" class="form-control" placeholder="City" id="city_other" name="city_other">
                                        <span class="input-group-addon"><a href="javascript:void()" class="openselect text-danger" data-select="city_id" data-other="city_div"><i class="fa fa-close"></i></a></span>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Area</label>
                                    <input type="text" class="form-control" name="area" id="area" value="<?= $member['area'] ?>" placeholder="Area" required="required">
                                </div>

                                <div class="form-group col-md-3">
                                    <label>Pincode</label>
                                    <input type="number" class="form-control" name="pincode" id="pincode" value="<?= $member['pincode'] ?>" placeholder="Zip" required="required">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Is Rented</label>
                                        <select name="is_rented" class="form-control">
                                            <option value="0" <?= ($member['is_rented'] == 0) ? "selected" : "" ?>>No</option>
                                            <option value="1" <?= ($member['is_rented'] == 1) ? "selected" : "" ?>>Yes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="inputCity">Phone</label>
                                    <input type="number" class="form-control" name="phone" id="phone" value="<?= $member['phone'] ?>" placeholder="Phone" onKeyPress="if(this.value.length==15) return false;">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="inputAddress">Local Address</label>
                                    <input type="text" class="form-control" id="local_address" name="local_address" value="<?= $member['local_address'] ?>" placeholder="Local Address">
                                </div>
                            </div>
                        <?php } ?>
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Personal Details</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label>Native District</label>
                                <select name="distinct_id" id="distinct_id" class="form-control otheroption" data-url="<?= base_url('site/getNativePlaces') ?>" data-dependent="distinct_div" data-select-dep="native_place_id">
                                    <option value="">--Select--</option>
                                    <?php
                                    if (!empty($distincts)) {
                                        foreach ($distincts as $list) {
                                            $selected = "";
                                            if ($list['id'] == $member['distinct_id']) {
                                                $selected = "selected";
                                            }
                                            echo "<option " . $selected . " value='" . $list['id'] . "'>" . $list['name'] . "</option>";
                                        }
                                    }
                                    ?>
                                    <option value="Other">Other</option>
                                </select>
                                <div class="input-group" id="distinct_div" style="display:none">
                                    <input type="text" placeholder="District" name="distinct_other" id="distinct_other" class="form-control" >
                                    <span class="input-group-addon"><a href="javascript:void()" class="openselect text-danger" data-select="distinct_id" data-other="distinct_div"><i class="fa fa-close"></i></a></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Native Village</label>
                                    <select name="native_place_id" id="native_place_id" class="form-control otheroption" data-dependent="native_place_div">
                                        <option value="">--Select--</option>
                                        <?php
                                        if (!empty($nativePlaces)) {
                                            foreach ($nativePlaces as $list) {
                                                $selected = "";
                                                if ($list['id'] == $member['native_place_id']) {
                                                    $selected = "selected";
                                                }
                                                echo "<option " . $selected . " value='" . $list['id'] . "'>" . $list['native'] . "</option>";
                                            }
                                        }
                                        ?>
                                        <option value="Other">Other</option>
                                    </select>
                                    <div class="input-group" id="native_place_div" style="display:none">
                                        <input type="text" placeholder="Native Place" name="native_place_other" id="native_place_other" class="form-control" >
                                        <span class="input-group-addon"><a href="javascript:void()" class="openselect text-danger" data-select="native_place_id" data-other="native_place_div"><i class="fa fa-close"></i></a></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputEmail4">Education</label>
                                    <select name="education_id" id="education_id" class="form-control otheroption" data-dependent="education_div">
                                        <option value="">--Select--</option>
                                        <?php
                                        if (!empty($educations)) {
                                            foreach ($educations as $list) {
                                                $selected = "";
                                                if ($list['id'] == $member['education_id']) {
                                                    $selected = "selected";
                                                }
                                                echo "<option " . $selected . " value='" . $list['id'] . "'>" . $list['name'] . "</option>";
                                            }
                                        }
                                        ?>
                                        <option value="Other">Other</option>
                                    </select>
                                    <div class="input-group" id="education_div" style="display:none">
                                        <input type="text" autocomplete="off" class="form-control" placeholder="Education" id="education_other" name="education_other">
                                        <span class="input-group-addon"><a href="javascript:void()" class="openselect text-danger" data-select="education_id" data-other="education_div"><i class="fa fa-close"></i></a></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputState">Current Activity</label>
                                    <select name="current_activity_id" id="current_activity_id" class="form-control otheroption" data-dependent="current_activity_div">
                                        <option value="">--Select--</option>
                                        <?php
                                        if (!empty($currentActivities)) {
                                            foreach ($currentActivities as $list) {
                                                $selected = "";
                                                if ($list['id'] == $member['current_activity_id']) {
                                                    $selected = "selected";
                                                }
                                                echo "<option " . $selected . " value='" . $list['id'] . "'>" . $list['activity'] . "</option>";
                                            }
                                        }
                                        ?>
                                        <option value="Other">Other</option>
                                    </select>
                                    <div class="input-group" id="current_activity_div" style="display:none">
                                        <input type="text" placeholder="Current Activity" name="current_activity_other" id="current_activity_other" class="form-control" >
                                        <span class="input-group-addon"><a href="javascript:void()" class="openselect text-danger" data-select="current_activity_id" data-other="current_activity_div"><i class="fa fa-close"></i></a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Blood Group</label>
                                    <select class="form-control" name="blood_group">
                                        <option value="">--Select--</option>
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
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Is Blood Donor?</label>
                                    <select name="is_donor" class="form-control">
                                        <option value="0" <?= ($member['is_donor'] == 0) ? "selected" : "" ?>>No</option>
                                        <option value="1" <?= ($member['is_donor'] == 1) ? "selected" : "" ?>>Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Marital Status</label>
                                    <select id="marital_status" name="marital_status" class="form-control">
                                        <option value="">--Select--</option>
                                        <option value="Single" <?= ($member['marital_status'] == "Single") ? "selected" : "" ?>>Single</option>
                                        <option value="Engaged" <?= ($member['marital_status'] == "Engaged") ? "selected" : "" ?>>Engaged</option>
                                        <option value="Married" <?= ($member['marital_status'] == "Married") ? "selected" : "" ?>>Married</option>
                                        <option value="Widow" <?= ($member['marital_status'] == "Widow") ? "selected" : "" ?>>Widow</option>
                                        <option value="Widower" <?= ($member['marital_status'] == "Widower") ? "selected" : "" ?>>Widower</option>
                                        <option value="Seperated" <?= ($member['marital_status'] == "Seperated") ? "selected" : "" ?>>Seperated</option>
                                        <option value="Divorce" <?= ($member['marital_status'] == "Divorce") ? "selected" : "" ?>>Divorce</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 marriageDetails" style="display:<?= ($member['marital_status'] == "Married") ? "block" : "none" ?>">
                                <div class="form-group">
                                    <label>Marriage Date</label>
                                    <!--<input type="text" name="marriage_date" class="form-control datepicker" id="marriage_date" value="<?= ($member['marriage_date'] != "") ? date('d/m/Y', strtotime($member['marriage_date'])) : "" ?>" placeholder="Marriage Date" readonly>-->
                                    <div class="input-group">
                                        <input type="text" name="marriage_date" class="form-control datepicker" id="marriage_date" value="<?= ($member['marriage_date'] != "") ? date('d/m/Y', strtotime($member['marriage_date'])) : "" ?>" placeholder="Marriage Date" readonly>
                                        <span class="input-group-addon"><a href="javascript:void()" onclick="javascript:$('#marriage_date').val('')"><i class="fa fa-close"></i></a></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <h4>Professional Details</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class='form-group'>
                                    <div class="kv-avatar center-block text-center" style="width:200px">
                                        <?php
                                        $url = "";
                                        if ($member['business_logo'] != "") {
                                            $url = base_url() . 'uploads/logos/' . $member['business_logo'];
                                        }
                                        ?>
                                        <input id="avatar-2" name="business_logo" data-preview="<?= $url ?>" type="file" class="file-loading">
                                        <div class="help-block"><small>Select file < 3000 KB</small></div>
                                    </div>
                                    <p id="kv-avatar-errors-2" class="error"></p>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inputEmail4">Business Category</label>
                                            <select name="business_category_id" id="business_category_id" class="form-control otheroption" data-dependent="business_category_div" data-url="<?= base_url('site/getBusinessSubCategory') ?>" data-select-dep="business_sub_category_id">
                                                <option value="">--Select--</option>
                                                <?php
                                                if (!empty($businessCategories)) {
                                                    foreach ($businessCategories as $list) {
                                                        $selected = "";
                                                        if ($list['id'] == $member['business_category_id']) {
                                                            $selected = "selected";
                                                        }
                                                        echo "<option " . $selected . " value='" . $list['id'] . "'>" . $list['name'] . "</option>";
                                                    }
                                                }
                                                ?>
                                                <option value="Other">Other</option>
                                            </select>
                                            <div class="input-group" id="business_category_div" style="display:none">
                                                <input type="text" autocomplete="off" class="form-control" placeholder="Business Category" id="business_category_other" name="business_category_other">
                                                <span class="input-group-addon"><a href="javascript:void()" class="openselect text-danger" data-select="business_category_id" data-other="business_category_div"><i class="fa fa-close"></i></a></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inputPassword4">Business Sub Category</label>
                                            <select name="business_sub_category_id" id="business_sub_category_id" class="form-control otheroption" data-dependent="business_sub_category_div">
                                                <option value="">--Select--</option>
                                                <?php
                                                if (!empty($businessSubCategories)) {
                                                    foreach ($businessSubCategories as $list) {
                                                        $selected = "";
                                                        if ($list['id'] == $member['business_sub_category_id']) {
                                                            $selected = "selected";
                                                        }
                                                        echo "<option " . $selected . " value='" . $list['id'] . "'>" . $list['name'] . "</option>";
                                                    }
                                                }
                                                ?>
                                                <option value="Other">Other</option>
                                            </select>
                                            <div class="input-group" id="business_sub_category_div" style="display:none">
                                                <input type="text" autocomplete="off" class="form-control" placeholder="Business Sub Category" id="business_sub_category_other" name="business_sub_category_other">
                                                <span class="input-group-addon"><a href="javascript:void()" class="openselect text-danger" data-select="business_sub_category_id" data-other="business_sub_category_div"><i class="fa fa-close"></i></a></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Company Name</label>
                                            <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company Name" value="<?= $member['company_name'] ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <textarea name="business_address" placeholder="Address" id="business_address" class="form-control"><?= $member['business_address'] ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Website</label>
                                    <input type="text" class="form-control" id="http://www.google.com" name="website" placeholder="http://www.google.com" value="<?= $member['website'] ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputPassword4">Occupation</label>
                                    <select name="occupation_id" id="occupation_id" class="form-control otheroption" data-dependent="occupation_div">
                                        <option value="">--Select--</option>
                                        <?php
                                        if (!empty($occupations)) {
                                            foreach ($occupations as $list) {
                                                $selected = "";
                                                if ($list['id'] == $member['occupation_id']) {
                                                    $selected = "selected";
                                                }
                                                echo "<option " . $selected . " value='" . $list['id'] . "'>" . $list['occupation'] . "</option>";
                                            }
                                        }
                                        ?>
                                        <option value="Other">Other</option>
                                    </select>
                                    <div class="input-group" id="occupation_div" style="display:none">
                                        <input type="text" autocomplete="off" class="form-control" placeholder="Occupation" id="occupation_other" name="occupation_other">
                                        <span class="input-group-addon"><a href="javascript:void()" class="openselect text-danger" data-select="occupation_id" data-other="occupation_div"><i class="fa fa-close"></i></a></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Work Details</label>
                                    <textarea name="work_details" placeholder="Work Details" id="work_details" class="form-control"><?= $member['work_details'] ?></textarea>
                                </div>
                            </div>
                        </div>
                        <section id="matrimony-section" style="display:<?= ($member['marital_status'] == "Engaged" || $member['marital_status'] == "Married" || $member['marital_status'] == "") ? "none" : "block" ?>">
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
                                            <input type="radio" class="form-check-input" name="matrimony" value="No" <?= ($member['matrimony'] == "No") ? "checked" : "" ?>>No
                                        </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="matrimony" value="Yes" <?= ($member['matrimony'] == "Yes") ? "checked" : "" ?>>Yes
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div id="matrimony" style="display: <?= ($member['matrimony'] == "Yes") ? "block" : "none" ?>">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Birth Time</label>
                                            <div class="bootstrap-timepicker">
                                                <!--<div class="form-group">-->
                                                <!--    <div class="form-group">-->
                                                <!--        <input placeholder="Birth Time" name="birth_time"  type="text" value="<?= ($member['birth_time'] != "") ? date('h:i A', strtotime($member['birth_time'])) : "" ?>" class="timepicker form-control" autocomplete="off">-->
                                                <!--    </div>-->
                                                <!--</div>-->
                                                <div class="input-group">
                                                    <input placeholder="Birth Time" id="birth_time" readonly name="birth_time"  type="text" value="<?= ($member['birth_time'] != "") ? date('h:i A', strtotime($member['birth_time'])) : "" ?>" class="timepicker form-control" autocomplete="off">
                                                    <span class="input-group-addon"><a href="javascript:void()" onclick="javascript:$('#birth_time').val('')"><i class="fa fa-close"></i></a></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Birth Place</label>
                                            <input type="text" class="form-control" id="birth_place" name="birth_place" value="<?= $member['birth_place'] ?>" placeholder="Birth Place">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Height (Meters)</label>
                                            <input type="number" class="form-control" id="height" name="height" value="<?= $member['height'] ?>" placeholder="Height">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Weight (KG)</label>
                                            <input type="number" class="form-control" id="weight" name="weight" value="<?= $member['weight'] ?>" placeholder="Weight">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>About Me</label>
                                            <textarea name="about_me" placeholder="About Me" id="about_me" class="form-control"><?= $member['about_me'] ?></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Hobby</label>
                                            <textarea name="hobby" id="hobby" class="form-control" placeholder="Hobby"><?= $member['hobby'] ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Expectation</label>
                                            <textarea name="expectation" id="expectation" class="form-control" placeholder="Expectation"><?= $member['expectation'] ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Glasses?</label>
                                            <select name="is_spect" class="form-control">
                                                <option value="0" <?= ($member['is_spect'] == 0) ? "selected" : "" ?>>No</option>
                                                <option value="1" <?= ($member['is_spect'] == 1) ? "selected" : "" ?>>Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Facebook or other Social Profile URL</label>
                                            <input type="text" name="facebook_profile" class="form-control" value="<?= $member['facebook_profile'] ?>" id="facebook_profile" placeholder="Facebook Profile">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <button type="submit" class="btn btn-primary">Submit</button>
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
            } else {

                if($(this).val() == ""){
                    $('#matrimony-section').hide();
                }
                else{
                    $('#matrimony-section').show();
                }

                $('#marriage_date').val('');
                $('.marriageDetails').hide();
            }
        });

        // initialize with defaults
        if ($("#avatar-1").attr('data-preview') != "")
        {
            $("#avatar-1").fileinput({
                // uploadUrl: '#',
                initialPreview: [$("#avatar-1").attr('data-preview')],
                initialPreviewAsData: true,
                initialPreviewFileType: 'image',
                initialPreviewConfig: [<?= $imgConfig ?>],
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
                defaultPreviewContent: '<img src="<?= base_url() ?>uploads/users/original/default.jpg" alt="Your Avatar" style="width:160px">',
                layoutTemplates: {main2: '{preview} {remove} {browse}'},
                allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
                browseOnZoneClick: true,
            });
        } else
        {
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
                defaultPreviewContent: '<img src="<?= base_url() ?>uploads/users/original/default.jpg" alt="Your Avatar" style="width:160px">',
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
                maxFileSize: 3000,
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
                allowedFileExtensions: ["jpg", "jpeg", "png"],
                browseOnZoneClick: true,
            });
        } else
        {
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
        }
    })
</script>