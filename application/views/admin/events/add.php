<section class="content-header">
    <h1>
        Add Event
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= base_url() ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?= base_url() ?>admin/events">Events</a></li>
        <li class="active">Add</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <form role="form" enctype="multipart/form-data" class="validateForm" method="post" action="<?= base_url() ?>admin/events/add">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Event Title</label>
                                    <input type="text" class="form-control" name="title" placeholder="Event Title" required="required">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Event Description</label>
                                    <textarea name="description" class="form-control" required="required "></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Event Location</label>
                                    <input type="text" class="form-control" id="geocomplete" name="address" placeholder="Event Location" required="required">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Event Date</label>
                                    <input type="text" class="datepicker form-control" name="event_date" placeholder="Event Date" required="required">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="map_canvas"></div>
                            </div>
                        </div>
                        <input  name="lat" id="lat" type="hidden">
                        <input name="lng" id="lng" type="hidden">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Youtube Url - 1</label>
                                    <input type="text" class="form-control" name="youtube[]" placeholder="Youtube Url - 1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Youtube Url - 2</label>
                                    <input type="text" class="form-control" name="youtube[]" placeholder="Youtube Url - 2">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Youtube Url - 3</label>
                                    <input type="text" class="form-control" name="youtube[]" placeholder="Youtube Url - 3">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <a href="https://www.youtube.com/upload?redirect_to_classic=true" target="_blank" class="btn btn-primary"><i class="fa fa-upload"></i> Upload On YouTube</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="file" multiple="multiple" name="gallery[]" id="file-input-gallery">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <!-- /.box-body -->
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
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function () {

        $('.submitbtn').click(function () {
            if ($('.file-error-message').html() != "")
            {
                return false;
            } else
            {
                return true;
            }

        });

        $("#file-input-gallery").fileinput({
            showUpload: false,
            uploadUrl: '#', // you must set a valid URL here else you will get an error
            allowedFileTypes: ['image', 'video'],
            allowedFileExtensions: ['jpg', 'jpeg', 'png', 'gif', 'mp4'],
            uploadAsync: false,
            initialPreview: false,
            maxFileCount: 100,
            showBrowse: true,
            previewFileType: "image",
            browseOnZoneClick: true,
            overwriteInitial: false,
            //maxFileSize: 1000,
            maxFilesNum: 100,
            showRemove: true,
            removeClass: "btn btn-danger"
        });

    })
</script>