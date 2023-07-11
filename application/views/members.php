<!-- Page Heading/Breadcrumbs -->
<h3 class="mt-4 mb-3">
    <?= $user['first_name'] . ' ' . $user['last_name'] ?>'s family members (<?= count($members) ?>)
</h3>

<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?= base_url() ?>">Home</a>
    </li>
    <li class="breadcrumb-item active">Members</li>
</ol>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php if (count($members) < 21) { ?>
                <a href="<?= base_url() ?>site/add" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add Member</a>
            <?php } else { ?>
                <p class="text-danger text-right">Sorry, you can't add more member maximum 20 members allowed.</p>
            <?php } ?>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Profile</th>
                        <th>Full Name</th>
                        <th>Code</th>
                        <th>Email Id</th>
                        <th>Mobile</th>
                        <th>Relation</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($members)) {
                        foreach ($members as $data) {
                            $data['profile_pic'] = ($data['profile_pic'])?:'noimage.png';
                            echo '<tr>';
                            echo '<td><img src="' . base_url('uploads/users/thumb/' . $data['profile_pic']) . '" width="75" height="75" class="img-circle"></td>';
                            echo '<td>' . $data['first_name'] . ' ' . $data['last_name'] . '</td>';
                            echo '<td>' . $data['member_code'].$data['id'].'</td>';
                            echo '<td>' . $data['email_address'] . '</td>';
                            echo '<td>' . $data['mobile'] . '</td>';
                            echo '<td>' . $data['relation'] . '</td>';
                            $dltConfirm = "return confirm('Are you sure you want to delete \'".$data['first_name']."\' record?')";
                            if ($data['head_id'] == "0") {
                                $dltConfirm = "return confirm('Are you sure you want to delete family head once you are delete family head than under that family head all members deleted?')";
                                echo '<td><a href="' . base_url('site/edit/' . $data['id']) . '" class="btn btn-primary btn-sm">Edit</a></td>';
                            }
                            else{
                                echo '<td><a href="' . base_url('site/edit/' . $data['id']) . '" class="btn btn-primary btn-sm">Edit</a>&nbsp;&nbsp;<a href="' . base_url('site/delete/' . $data['id']) . '" onclick="' . $dltConfirm . '" class="btn btn-danger btn-sm">Delete</a></td>';
                            }
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>