<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Dashboard
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= base_url('admin/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <!-- Info boxes -->
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <!-- small box -->
            <a href="<?= base_url() ?>admin/users">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?= sizeof($users) ?></h3>
                        <p style="font-size: 18px;font-weight: 600;">Family Head</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="small-box-footer">
                        More info <i class="fa fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>    
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <!-- small box -->
            <a href="<?= base_url() ?>admin/businessCategories">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?= sizeof($businessCategories) ?></h3>
                        <p style="font-size: 18px;font-weight: 600;">Business Categories</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="small-box-footer">
                        More info <i class="fa fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <!-- small box -->
            <a href="<?= base_url() ?>admin/businessSubCategories">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?= sizeof($businessSubCategories)?></h3>
                        <p style="font-size: 18px;font-weight: 600;">Business SubCategories</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="small-box-footer">
                        More info <i class="fa fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <!-- small box -->
            <a href="<?= base_url() ?>admin/states" class="small-box-footer">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?= sizeof($states) ?></h3>
                        <p style="font-size: 18px;font-weight: 600;">States</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="small-box-footer">
                        More info <i class="fa fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <!-- small box -->
            <a href="<?= base_url()?>admin/cities">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?= sizeof($cities) ?></h3>
                        <p style="font-size: 18px;font-weight: 600;">Cities</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="small-box-footer">
                        More info <i class="fa fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>    
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <!-- small box -->
            <a href="<?= base_url()?>admin/committees">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?= sizeof($committee) ?></h3>
                        <p style="font-size: 18px;font-weight: 600;">Committees</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="small-box-footer">
                        More info <i class="fa fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>    
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <!-- small box -->
            <a href="<?= base_url()?>admin/currentActivity">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?= sizeof($currentactivity) ?></h3>
                        <p style="font-size: 18px;font-weight: 600;">Current Activity</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="small-box-footer">
                        More info <i class="fa fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <!-- small box -->
            <a href="<?= base_url()?>admin/designations">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?= sizeof($designation) ?></h3>
                        <p style="font-size: 18px;font-weight: 600;">Designation</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="small-box-footer">
                        More info <i class="fa fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <!-- small box -->
            <a href="<?= base_url()?>admin/districts">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?= sizeof($distincts) ?></h3>
                        <p style="font-size: 18px;font-weight: 600;">Districts</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="small-box-footer">
                        More info <i class="fa fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <!-- small box -->
            <a href="<?= base_url()?>admin/educations">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?= sizeof($educations) ?></h3>
                        <p style="font-size: 18px;font-weight: 600;">Educations</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="small-box-footer">
                        More info <i class="fa fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>    
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <!-- small box -->
            <a href="<?= base_url()?>admin/localCommunity">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?= sizeof($localCommunity) ?></h3>
                        <p style="font-size: 18px;font-weight: 600;">Local Community</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="small-box-footer">
                        More info <i class="fa fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>    
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <!-- small box -->
            <a href="<?= base_url()?>admin/nativePlaces">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?= sizeof($nativePlaces) ?></h3>
                        <p style="font-size: 18px;font-weight: 600;">Native Places</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="small-box-footer">
                        More info <i class="fa fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <!-- small box -->
            <a href="<?= base_url()?>admin/occupations">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?= sizeof($occupations) ?></h3>
                        <p style="font-size: 18px;font-weight: 600;">Occupations</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="small-box-footer">
                        More info <i class="fa fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>    
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <!-- small box -->
            <a href="<?= base_url()?>admin/relations">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?= sizeof($relations) ?></h3>
                        <p style="font-size: 18px;font-weight: 600;">Relations</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="small-box-footer">
                        More info <i class="fa fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <!-- small box -->
            <a href="<?= base_url()?>admin/subCommunity">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?= sizeof($subCommunity) ?></h3>
                        <p style="font-size: 18px;font-weight: 600;">Sub Community</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="small-box-footer">
                        More info <i class="fa fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>    
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <!-- small box -->
            <a href="<?= base_url()?>admin/subCasts">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?= sizeof($subCasts) ?></h3>
                        <p style="font-size: 18px;font-weight: 600;">Sub Casts</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="small-box-footer">
                        More info <i class="fa fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- /.row -->
</section>
<!-- /.content -->