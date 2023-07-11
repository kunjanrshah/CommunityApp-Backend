<?php
$userData = $this->session->userdata('backEndLogin');
?>
<header class="main-header">

    <!-- Logo -->
    <a href="<?= base_url() ?>admin/dashboard" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>M</b>G</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b><?= APP_NAME ?></b></span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= base_url() . 'uploads/users/thumb/' . $userData['profile_pic'] ?>" class="user-image" alt="User Image">
                        <span class="hidden-xs"><?= $userData['first_name'] . ' ' . $userData['last_name'] ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= base_url() . 'uploads/users/thumb/' . $userData['profile_pic'] ?>" class="img-circle" alt="User Image">

                            <p>
                                <?= $userData['first_name'] . ' ' . $userData['last_name'] . ' - ' . $userData['role'] ?> 
                                <small></small>
                            </p>
                        </li>

                        <li style="padding: 10px 0;text-align: center;">
                            <p><a href="<?php echo base_url('site/members')?>">Go To My  Dashboard</a></p>
                        </li>
                        <!-- Menu Body -->

                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?= base_url('admin/dashboard/profile') ?>" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="<?= base_url() ?>/admin/admin/logout" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>