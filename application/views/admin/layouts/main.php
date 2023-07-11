<!DOCTYPE html>
<html>
    <head>
        <title><?= APP_NAME ?> | <?= isset($page_title) ? $page_title : "Admin" ?></title>
        <?php $this->load->view('admin/layouts/head'); ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
        <div class="loadingBar"  style="display: none;" >
            <div class="loadingBarText">
                <i class="fa fa-spinner fa-spin fa-3x"></i>
            </div>
        </div>
        <div class="wrapper">
            <?php $this->load->view('admin/layouts/header'); ?>
            <?php $this->load->view('admin/layouts/sidebar'); ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <?php if ($this->session->flashdata("flash_message_error") != "") { ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fa fa-ban"></i> <?= $this->session->flashdata("flash_message_error") ?></h5>

                    </div>
                <?php } ?>
                <?php if ($this->session->flashdata("flash_message_success") != "") { ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fa fa-check"></i> <?= $this->session->flashdata("flash_message_success") ?></h5>

                    </div>
                <?php } ?>

                <?php $this->load->view($content); ?>
            </div>
            <?php $this->load->view('admin/layouts/footer'); ?>
        </div>
        <!-- ./wrapper -->
        <?php $this->load->view('admin/layouts/modelHtml'); ?>
        <?php $this->load->view('admin/layouts/scripts'); ?>
    </body>
</html>    








