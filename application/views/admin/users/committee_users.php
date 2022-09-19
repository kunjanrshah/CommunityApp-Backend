<?php $userData = $this->session->userdata('backEndLogin');?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?= $head['first_name'] . ' ' . $head['last_name'] ?>'s Members 
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= base_url('admin/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Members</li>
    </ol>
    <h4>
        <a href="<?= base_url('admin/users')?>">Back to member list</a>
        <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#AddMemberModal">Add Member</button>
    </h4>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <!-- /.box-header -->
                <div class="box-body">
                    <form action="<?= base_url('admin/users/deleteMultiple?head_id=' . $id) ?>" class="ajax-form" method="POST">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <!-- <th width="40"><input type="checkbox" class="Parent" name="checkAll" id="checkAll">&nbsp;<button type="submit" onclick="return confirm(<?= $this->config->item('delete_all_conf') ?>)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button></th> -->
                                        <th>Name</th>
                                        <th>Committee Name</th>
                                        <th>Designation</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($members)) {
                                        foreach ($members as $r) {
                                            ?>
                                            <tr>
                                                <!-- <td><input type="checkbox" name="id[]" class="Child" value="<?= $r['id'] ?>"></td> -->
                                                <td><?= $r['first_name'] . ' ' . $r['last_name']; ?></td>
                                                <td><?= $r['committee_name']; ?></td>
                                                <td><?= $r['designation']; ?></td>
                                                <td><?= $r['email_address']; ?></td>
                                                <td><?= $r['mobile'] ?></td>
                                                <td><?= $r['start_date'] ?></td>
                                                <td><?= $r['end_date'] ?></td>
                                                <td>
                                                    <a title="Edit" href="javascript:void(0);" class="btn btn-primary btn-xs" onclick="edit_user_profile(<?= $r['id'];?>)"><i class="fa fa-edit"></i></a>&nbsp;
                                                    <a title="Delete" data-uname="<?= $r['first_name'] . ' ' . $r['last_name']; ?>" href="javascript:void(0)" data-href="<?php echo base_url('admin/committeeusers/delete/'.$r['id'])?>" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i></a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
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

<!-- Modal -->
<div class="modal fade" id="AddMemberModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="<?= base_url('admin/committeeusers/add/' . $id) ?>" role="form" enctype="multipart/form-data" class="validateForm" method="POST">
          <div class="modal-header">
            <h3 class="modal-title" id="exampleModalLabel">Add Commmittee Member</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Member</label>
                    <select class="form-control" id="user_id" name="user_id" required="required" autocomplete="off">
                        <option value="">--Select--</option>
                        <?php
                        if(is_array($head_members) && count($head_members) > 0){
                            foreach ($head_members as $item_head_members) {

                                echo '<option value="'.$item_head_members['id'].'">'.$item_head_members['user_name'].'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Committee</label>
                    <select class="form-control" id="committee_id" name="committee_id" required="required">
                        <option value="">--Select--</option>
                        <?php
                        if(is_array($committee) && count($committee) > 0){
                            foreach ($committee as $item_committee) {

                                echo '<option value="'.$item_committee['id'].'">'.$item_committee['name'].'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Designation</label>
                    <select class="form-control" id="designation_id" name="designation_id" required="required">
                        <option value="">--Select--</option>
                        <?php
                        if(is_array($designations) && count($designations) > 0){
                            foreach ($designations as $designation) {

                                echo '<option value="'.$designation['id'].'">'.$designation['name'].'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="text" class="form-control datepicker" id="committee_start_date" name="start_date" value="" placeholder="Start Date" autocomplete="off" required="required" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>End Date</label>
                    <input type="text" class="form-control datepicker" id="committee_end_date" name="end_date" value="" placeholder="End Date" autocomplete="off" required="required" readonly>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="submit" value="submit">Add</button>
          </div>
        </form>  
    </div>
  </div>
</div>

<div class="modal fade" id="UpdateMemberModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="<?php echo base_url('admin/committeeusers/edit/'.$id)?>" id="update_form" role="form" enctype="multipart/form-data" class="validateForm" method="POST">
          <div class="modal-header">
            <h3 class="modal-title" id="exampleModalLabel">Update Commmittee Member</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <input type="hidden" name="hidden_id" id="hidden_id" value="">
          <div class="modal-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Member</label>
                    <select class="form-control" id="update_user_id" name="user_id" required="required" autocomplete="off">
                        <option value="">--Select--</option>
                        <?php
                        if(is_array($head_members) && count($head_members) > 0){
                            foreach ($head_members as $item_head_members) {
                                echo '<option value="'.$item_head_members['id'].'">'.$item_head_members['user_name'].'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Committee</label>
                    <select class="form-control" id="update_committee_id" name="committee_id" required="required">
                        <option value="">--Select--</option>
                        <?php
                        if(is_array($committee) && count($committee) > 0){
                            foreach ($committee as $item_committee) {
                                echo '<option value="'.$item_committee['id'].'">'.$item_committee['name'].'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Designation</label>
                    <select class="form-control" id="designation_id" name="designation_id" required="required">
                        <option value="">--Select--</option>
                        <?php
                        if(is_array($designations) && count($designations) > 0){
                            foreach ($designations as $designation) {
                                echo '<option value="'.$designation['id'].'">'.$designation['name'].'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="text" class="form-control datepicker" id="update_committee_start_date" name="start_date" value="" placeholder="Start Date" autocomplete="off" required="required" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>End Date</label>
                    <input type="text" class="form-control datepicker" id="update_committee_end_date" name="end_date" value="" placeholder="End Date" autocomplete="off" required="required" readonly>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="submit" value="submit">Update</button>
          </div>
        </form>  
    </div>
  </div>
</div>

<script type="text/javascript">
function edit_user_profile(id){
    $.ajax({
        url: '<?php echo base_url()."admin/committeeusers/get_record/";?>'+id,
        dataType: 'json',
        type: 'POST',
        success: function (response) {

            $('#hidden_id').val(response.id);
            $('#update_user_id').val(response.user_id);
            $('#update_committee_id').val(response.committee_id);
            $('#update_designation_id').val(response.designation_id);
            $('#update_committee_start_date').val(response.start_date);
            $('#update_committee_end_date').val(response.end_date);
            $('#UpdateMemberModal').modal('show');
        }
    });
} 
</script>