<style type="text/css">
table tbody tr td:last-child{
    display: inline-flex;
}    
</style>
<?php $userData = $this->session->userdata('backEndLogin'); ?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Family Head List 
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= base_url('admin/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a class="active">Family Head List</a></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <!-- /.box-header -->
                <div class="box-body">
                    <?php if ($userData['role'] == "SUPERADMIN") { ?>
                        <div class="row">
                            <div class="col-md-12 pull-right">
                                <form action="<?= base_url('admin/users/exportData') ?>" role="form" enctype="multipart/form-data" method="POST" id="form_filter">
                                    <div class="form-group col-md-2">
                                        <label for="inputCity">State</label>
                                        <select id="state_id" name="state_id" class="dependent form-control" data-url="<?= base_url('site/getCityByState') ?>" data-dependent="city_id">
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

                                    <div class="form-group col-md-2">
                                        <label for="inputCity">City</label>
                                        <select name="city_id" id="city_id" class="form-control" data-dependent="city_div">
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
                                        </select>
                                    </div>

                                    <div class="form-group col-md-2">
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
                                    <div class="form-group col-md-2">
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
                                </form>    
                                <div style="margin-top: 25px;">
                                    <a href="javascript:void(0);" onclick="export_data();" class="btn btn-success ">Export <i class="fa fa-file-excel-o"></i></a>&nbsp;
                                    <a href="<?= base_url('admin/users/import') ?>" class="btn btn-primary">Import <i class="fa fa-file-excel-o"></i></a>&nbsp;
                                </div>    
                            </div>
                        </div>
                    <?php } ?>
                    <br>
                    <hr>
                    <form action="<?= base_url('admin/' . $controller . '/deleteMultiple') ?>" class="ajax-form" method="POST">
                        <script type="text/javascript">

                            function userSearch() {
                                $('#ajaxDataTable').DataTable({"scrollX": true}).destroy();
                                // showLoadingBar();
                                var ajaxUrl = "<?php echo base_url() ?>admin/users/data_list";
                                createDataTable($('#ajaxDataTable'), ajaxUrl, "");
                            }

                        </script>
                        <div class="table-responsive" style="overflow: scroll;">
                            <table id="ajaxDataTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="40"><input type="checkbox" class="Parent" name="checkAll" id="checkAll">&nbsp;<button type="submit" onclick="return confirm('Are sure you want to delete selected '+$('.Child:checked').length+' records?')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button></th>
                                        <th width="40">Code</th>
                                        <th>Profile</th>
                                        <th>Action</th>
                                        <th>Members</th>
                                        <?php if ($userData['role'] == "SUPERADMIN") { ?>
                                            <th>Role</th>
                                        <?php } ?>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>City</th>
                                        <th>Gender</th>
                                        <th>Native</th>
                                        <th>Address</th>
                                        <th>Local Comm</th>
                                        <th>Sub Comm</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <!-- /.box-body -->

            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function () {
        userSearch();
        // $('#userDataTable').dataTable();
    });

    function export_data(){
        $("#form_filter").submit();
        $('.loadingBar').fadeOut('slow');
    }
</script>