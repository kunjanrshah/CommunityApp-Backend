<section class="content-header">
    <h1>
        Import User Data
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= base_url() ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?= base_url() ?>admin/users">Users</a></li>
        <li class="active">Import</li>
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
                    <h3 class="box-title">Import Data</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                <form autocomplete="off" role="form" enctype="multipart/form-data" class="validateForm" method="post" action="<?= base_url() ?>admin/users/import">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Select File for import (.csv)</label>
                                    <input type="file" name="importFile" id="importFile" required="required">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-footer">
                                    <a href="javascript:history.go(-1)" class="btn btn-default">Cancel</a>
                                    <button type="submit" class="submitbtn btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
</section>