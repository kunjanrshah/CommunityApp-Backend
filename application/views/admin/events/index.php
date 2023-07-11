<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Events
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= base_url('admin/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a class="active">Events</a></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <a href="<?= base_url() ?>admin/events/add" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add Event</a>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <!-- /.box-header -->
                <div class="box-body">
                    <script type="text/javascript">

                        function userSearch() {
                            $('#eventDataTable').DataTable().destroy();
                           // showLoadingBar();
                            var ajaxUrl = "<?php echo base_url() ?>admin/events/events_list";
                            createDataTable($('#eventDataTable'),ajaxUrl,"");
                        }

                    </script>
                    <div class="table-responsive">
                        <table id="eventDataTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Event Date</th>
                                    <th>Event Title</th>
                                    <th>Description</th>
                                    <th>Locations</th>
                                    
                                    <th>Share Event</th>
                                    <th>View Shared Events</th>
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
<!-- /.content-wrapper -->
<script>
    $(document).ready(function() {
        userSearch();
        // $('#userDataTable').dataTable();
    } );
</script>