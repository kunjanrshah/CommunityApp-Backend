<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Event Shared To
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= base_url('admin/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a class="active">Events</a></li>
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
                                $('#dataTables').DataTable().destroy();
                                // showLoadingBar();
                                var ajaxUrl = "<?php echo base_url() ?>admin/events/get_shared_user_lists/<?= $id ?>";
                                createDataTable($('#dataTables'),ajaxUrl,"");
                            }

                        </script>
                        <div class="table-responsive">
                            <table id="dataTables" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>User Id</th>
                                    
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>City</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function(){
        userSearch();
    });
</script>
