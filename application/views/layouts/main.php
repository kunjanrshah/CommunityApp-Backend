<!DOCTYPE html>
<html>
    <head>
        <title><?= APP_NAME ?> | <?= isset($page_title) ? $page_title : "Admin" ?></title>
        <?php $this->load->view('layouts/head'); ?>
    </head>
    <body>
        <div class="loadingBar"  style="display: none;" >
            <div class="loadingBarText">
                <i class="fa fa-spinner fa-spin fa-3x"></i>
            </div>
        </div>
        <div class="wrapper">
            <?php $this->load->view('layouts/header'); ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper" style="min-height: 552px;margin-bottom: 25px;">

                <div class="container">
                    <?php
                    if(isset($user['role'])){
                    ?>
                    <span class="mt-4 mb-3 pull-right">
                    <?php
                    if($user['role'] == 'SUPERADMIN' || $user['role'] == 'LOCAL_ADMIN' || $user['role'] == 'SUB_ADMIN'){

                        $user_ds = 'Supper Admin';

                        if($user['role'] == 'LOCAL_ADMIN'){

                            $user_ds = $user['local_community_name'];
                        }
                        else if($user['role'] == 'SUB_ADMIN'){
                            $user_ds = $user['sub_community_name'];
                        }

                    ?>
                        <a href="<?php echo base_url('admin/dashboard')?>">Go To <?php echo $user_ds;?> Dashboard</a> | 
                    <?php
                    }?>
                    <a href="<?php echo base_url('site/logout')?>">Logout</a></span>
                    <?php
                    }?>
                    <br>
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
                    <div class="alert alert-danger" id="alert-error" style="display: none">
                        <h5><i class="icon fa fa-ban"></i> <span class="ajax-error"></span></h5>
                    </div>
                    <?php $this->load->view($content); ?>
                </div>
            </div>
            <?php $this->load->view('layouts/footer'); ?>
        </div>
        <!-- ./wrapper -->
        <?php $this->load->view('layouts/scripts'); ?>
    </body>
</html>