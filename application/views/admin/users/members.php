<?php $userData = $this->session->userdata('backEndLogin'); ?>
<style type="text/css">
	.mb-10{
		margin-bottom: 10px;
	}
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?= $head['first_name'] . ' ' . $head['last_name'] ?>'s Members 
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= base_url('admin/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Members</li>
    </ol>
    <h4><a href="<?= base_url('admin/users')?>">Back to member list</a></h4>
    <a href="<?= base_url('site/add?head_id=' . $id) ?>" class="btn btn-primary pull-right mb-10"><i class="fa fa-plus"></i> Add Member</a>
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
                                        <th width="40"><input type="checkbox" class="Parent" name="checkAll" id="checkAll">&nbsp;<button type="submit" onclick="return confirm(<?= $this->config->item('delete_all_conf') ?>)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button></th>
                                        <th>Member Code</th>
                                        <th>Profile</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Relation</th>
                                        <?php if ($userData['role'] == "SUPERADMIN") { ?>
                                            <th>Change Role</th>
                                        <?php } ?>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($members)) {
                                        foreach ($members as $r) {
                                            ?>
                                            <tr>
                                                <td><input type="checkbox" name="id[]" class="Child" value="<?= $r['id'] ?>"></td>
                                                <td><?= $r['member_code'] . $r['id'] ?></td>
                                                <td><?= '<a href="' . base_url() . 'uploads/users/original/' . $r['profile_pic'] . '" class="fancybox"><img src="' . base_url() . 'uploads/users/thumb/' . $r['profile_pic'] . '" class="img-circle" height="50" width="50"></a>'; ?></td>
                                                <td><?= $r['first_name'] . ' ' . $r['last_name']; ?></td>
                                                <td><?= $r['email_address']; ?></td>
                                                <td><?= $r['mobile'] ?></td>
                                                <td><?= $r['relation'] ?></td>
                                                <?php if ($userData['role'] == "SUPERADMIN") { ?>
                                                    <td>
                                                        <?php
                                                        $changeRole = '<div class="dropdown">
                                                                <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">' . $r['role'] . '
                                                                <span class="caret"></span></button>
                                                                <ul class="dropdown-menu">
                                                                    <li><a href="' . base_url('admin/users/changeRole/' . $r['id'] . '?role=LOCAL_ADMIN&head_id=' . $id) . '">LOCAL_ADMIN</a></li>
                                                                    <li><a href="' . base_url('admin/users/changeRole/' . $r['id'] . '?role=SUB_ADMIN&head_id=' . $id) . '">SUB_ADMIN</a></li>
                                                                    <li><a href="' . base_url('admin/users/changeRole/' . $r['id'] . '?role=USER&head_id=' . $id) . '">USER</a></li>
                                                                </ul>
                                                              </div>';
                                                        echo $changeRole;
                                                        ?>
                                                    </td>
                                                <?php } ?>
                                                <td>
                                                    <?php
                                                    $action = '<a title="Edit" href="' . base_url() . 'admin/users/edit/' . $r['id'] . '?tab=members" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>&nbsp;';
                                                    $action .= '<a title="Delete" data-uname="'.$r['first_name'].'" href="javascript:void()" data-href="' . base_url() . 'admin/users/delete/' . $r['id'] . '?head_id=' . $id . '" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i></a>&nbsp;';
                                                    if ($r['status'] == 1) {
                                                        $action .= '<a title="Inactive" data-name="' . $r['first_name'] . '" href="javascript:void()" data-href="' . base_url() . 'admin/users/changeStatus/' . $r['id'] . '?status=0&head_id=' . $id . '" class="btn btn-danger btn-xs changeStatus"><i class="fa fa-ban"></i></a>';
                                                    } else {
                                                        $action .= '<a title="Active" data-name="' . $r['first_name'] . '" href="javascript:void()" data-href="' . base_url() . 'admin/users/changeStatus/' . $r['id'] . '?status=1&head_id=' . $id . '" class="btn btn-success btn-xs changeStatus"><i class="fa fa-check"></i></a>';
                                                    }
                                                    echo $action;
                                                    ?>
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