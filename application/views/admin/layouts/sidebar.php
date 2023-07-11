<?php
$controller = str_replace(' ', '', $this->router->fetch_class());
$action = str_replace(' ', '', $this->router->fetch_method());
$userData = $this->session->userdata('backEndLogin');
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="<?= ($controller == "dashboard") ? "active" : "" ?>">
                <a href="<?= base_url() ?>admin/dashboard">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>

            <li class="<?= ($controller == "users") ? "active" : "" ?>">
                <a href="<?= base_url() ?>admin/users">
                    <i class="fa fa-users"></i> <span>Users</span>
                </a>
            </li>
            
            <li class="<?= ($controller == "users") ? "active" : "" ?>">
                <a href="<?= base_url() ?>admin/events">
                    <i class="fa fa-bars"></i> <span>Events</span>
                </a>
            </li>

            <?php if ($userData['role'] == "SUPERADMIN") { ?>
                <li class="treeview menu-open <?= ($controller == "cities" || $controller == "states" || $controller == "subCommunity" || $controller == "localCommunity" || $controller == "committees" || $controller == "designations" || $controller == "educations" || $controller == "relations" || $controller == "businessCategories" || $controller == "businessSubCategories" || $controller == "currentActivity") ? "active" : "" ?>">
                    <a href="#"><i class="fa fa-bars"></i> <span>Masters</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu" style="display:block">
                        <li class="<?= ($controller == "businessCategories") ? "active" : "" ?>">
                            <a href="<?= base_url() ?>admin/businessCategories">
                                <i class="fa fa-circle-o" aria-hidden="true"></i> <span>Business Category</span>
                            </a>
                        </li>
                        <li class="<?= ($controller == "businessSubCategories") ? "active" : "" ?>">
                            <a href="<?= base_url() ?>admin/businessSubCategories">
                                <i class="fa fa-circle-o" aria-hidden="true"></i> <span>Business Sub Category</span>
                            </a>
                        </li>
                        <li class="<?= ($controller == "cities") ? "active" : "" ?>">
                            <a href="<?= base_url() ?>admin/cities">
                                <i class="fa fa-circle-o" aria-hidden="true"></i> <span>Cities</span>
                            </a>
                        </li>
                        <li class="<?= ($controller == "committees") ? "active" : "" ?>">
                            <a href="<?= base_url() ?>admin/committees">
                                <i class="fa fa-circle-o" aria-hidden="true"></i> <span>Committees</span>
                            </a>
                        </li>
                        <li class="<?= ($controller == "currentActivity") ? "active" : "" ?>">
                            <a href="<?= base_url() ?>admin/currentActivity">
                                <i class="fa fa-circle-o" aria-hidden="true"></i> <span>Current Activity</span>
                            </a>
                        </li>
                        <li class="<?= ($controller == "designations") ? "active" : "" ?>">
                            <a href="<?= base_url() ?>admin/designations">
                                <i class="fa fa-circle-o" aria-hidden="true"></i> <span>Designations</span>
                            </a>
                        </li>
                        <li class="<?= ($controller == "distincts") ? "active" : "" ?>">
                            <a href="<?= base_url() ?>admin/districts">
                                <i class="fa fa-circle-o" aria-hidden="true"></i> <span>Districts</span>
                            </a>
                        </li>
                        <li class="<?= ($controller == "educations") ? "active" : "" ?>">
                            <a href="<?= base_url() ?>admin/educations">
                                <i class="fa fa-circle-o" aria-hidden="true"></i> <span>Educations</span>
                            </a>
                        </li>
                        <li class="<?= ($controller == "localCommunity") ? "active" : "" ?>">
                            <a href="<?= base_url() ?>admin/localCommunity">
                                <i class="fa fa-circle-o" aria-hidden="true"></i> <span>Local Community</span>
                            </a>
                        </li>
                        <li class="<?= ($controller == "nativePlaces") ? "active" : "" ?>">
                            <a href="<?= base_url() ?>admin/nativePlaces">
                                <i class="fa fa-circle-o" aria-hidden="true"></i> <span>Native Places</span>
                            </a>
                        </li>
                        <li class="<?= ($controller == "occupations") ? "active" : "" ?>">
                            <a href="<?= base_url() ?>admin/occupations">
                                <i class="fa fa-circle-o" aria-hidden="true"></i> <span>Occupations</span>
                            </a>
                        </li>
                        <li class="<?= ($controller == "relations") ? "active" : "" ?>">
                            <a href="<?= base_url() ?>admin/relations">
                                <i class="fa fa-circle-o" aria-hidden="true"></i> <span>Relations</span>
                            </a>
                        </li>
                        <li class="<?= ($controller == "states") ? "active" : "" ?>">
                            <a href="<?= base_url() ?>admin/states">
                                <i class="fa fa-circle-o" aria-hidden="true"></i> <span>States</span>
                            </a>
                        </li>
                        <li class="<?= ($controller == "subCommunity") ? "active" : "" ?>">
                            <a href="<?= base_url() ?>admin/subCommunity">
                                <i class="fa fa-circle-o" aria-hidden="true"></i> <span>Sub Community</span>
                            </a>
                        </li>
                        <li class="<?= ($controller == "subCasts") ? "active" : "" ?>">
                            <a href="<?= base_url() ?>admin/subCasts">
                                <i class="fa fa-circle-o" aria-hidden="true"></i> <span>Sub Casts</span>
                            </a>
                        </li>
                    </ul>
                </li>
            <?php } ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>