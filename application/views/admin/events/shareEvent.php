<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Share Events
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= base_url('admin/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#" class="active">events</a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <!-- /.box-header -->
                <div class="box-body">
                    <form action="<?= base_url('admin/events/sendToUsers/'.$id) ?>" method="POST">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success pull-right" name="submit">Share</button>
                            </div>
                        </div>
                        <br>
                        <script type="text/javascript">

                            function userSearch() {
                                $('#dataTables').DataTable().destroy();
                                // showLoadingBar();
                                var ajaxUrl = "<?php echo base_url() ?>admin/events/get_user_lists";
                                createDataTable($('#dataTables'),ajaxUrl,"");
                            }

                        </script>
                        <div class="table-responsive">
                            <table id="dataTables" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" class="Parent" name="checkAll" id="checkAll"></th>
                                    <th>User ID</th>
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>City</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12 ">
                                <button type="submit" class="btn btn-success pull-right" name="submit">Share</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function(){

        userSearch();

        $(".Parent").click(function () {
            $(".Child").attr("checked", this.checked);
        });

        $(".Child").click(function(){

            if($(".Child").length == $(".Child:checked").length) {
                $(".Parent").attr("checked", "checked");
            } else {
                $(".Parent").removeAttr("checked");
            }
        });
    });
</script>