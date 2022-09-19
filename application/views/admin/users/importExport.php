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
                    <script type="text/javascript">

                        function userSearch() {
                            $('#ajaxDataTable').DataTable().destroy();
                            // showLoadingBar();
                            var ajaxUrl = "<?php echo base_url() ?>admin/users/data_list";
                            createDataTable($('#ajaxDataTable'), ajaxUrl, "");
                        }

                    </script>
                    <div class="table-responsive">
                        <table id="ajaxDataTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Profile</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>State</th>
                                    <th>City</th>
                                    <th>Area</th>
                                    <th>Members</th>
                                    <?php if ($userData['role'] == "SUPERADMIN") { ?>
                                        <th>Change Role</th>
                                    <?php } ?>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                    </div>
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
</script>