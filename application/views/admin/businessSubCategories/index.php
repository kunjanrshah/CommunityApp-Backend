<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?= $page_title ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= base_url('admin/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a class="active"><?= $page_title ?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><b><?= $form_title ?></b></h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                <form autocomplete="off" role="form" enctype="multipart/form-data" class="validateForm" method="post" action="<?= $action ?>">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="fname">Business Category<span class="kv-reqd">*</span></label>
                                    <select name="business_category_id" id="business_category_id" class="form-control" required="required">
                                        <option value="">--Select--</option>
                                        <?php
                                        if (!empty($businessCategories)) {
                                            foreach ($businessCategories as $data) {
                                                $selected = "";
                                                if (!empty($item) && $item['business_category_id'] == $data['id']) {
                                                    $selected = "selected";
                                                }
                                                ?>
                                                <option <?= $selected ?> value="<?= $data['id'] ?>"><?= $data['name'] ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="fname">Business Sub Category<span class="kv-reqd">*</span></label>
                                    <input type="text" class="form-control" name="name" value="<?= (!empty($item)) ? $item['name'] : "" ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <br>
                                <button type="submit" class="submitbtn btn btn-primary">Save</button>
                                <a href="<?= base_url('admin/' . $controller) ?>" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <!-- /.box-header -->
                <div class="box-body">
                    <form action="<?= base_url('admin/' . $controller . '/deleteMultiple') ?>" class="ajax-form" method="POST">
                        <script type="text/javascript">

                            function userSearch() {
                                $('#ajaxDataTable').DataTable().destroy();
                                // showLoadingBar();
                                var ajaxUrl = "<?php echo base_url() ?>admin/<?= $controller ?>/data_list";
                                createDataTable($('#ajaxDataTable'), ajaxUrl, "");
                            }

                        </script>
                        <div class="table-responsive">
                            <table id="ajaxDataTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="40"><input type="checkbox" class="Parent" name="checkAll" id="checkAll">&nbsp;<button type="submit" onclick="return confirm(<?= $this->config->item('delete_all_conf') ?>)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button></th>
                                        <th>Business Category</th>
                                        <th>Business Sub Category</th>
                                        <th>Updated at</th>
                                        <th>Actions</th>
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
</script>