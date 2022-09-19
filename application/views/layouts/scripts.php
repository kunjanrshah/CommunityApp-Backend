<!-- Bootstrap core JavaScript -->
<script src="<?= base_url('themes/front/') ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- piexif.min.js is only needed if you wish to resize images before upload to restore exif data.

This must be loaded before fileinput.min.js -->
<script src="<?= base_url() . 'themes/adminLte/' ?>plugins/bootstrap-fileinput/js/plugins/piexif.min.js" type="text/javascript"></script>
<!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview.
     This must be loaded before fileinput.min.js -->
<script src="<?= base_url() . 'themes/adminLte/' ?>plugins/bootstrap-fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files.
     This must be loaded before fileinput.min.js -->
<!-- bootstrap time picker -->
<script src="<?= base_url() . 'themes/adminLte/' ?>plugins/timepicker/bootstrap-timepicker.min.js"></script>

<script src="<?= base_url() . 'themes/adminLte/' ?>plugins/bootstrap-fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
<!-- the main fileinput plugin file -->
<script src="<?= base_url() . 'themes/adminLte/' ?>plugins/bootstrap-fileinput/js/fileinput.js"></script>
<script src="<?= base_url('themes/front/') ?>js/autoComplete.js"></script>

<!-- Bootbox alert -->
<script src="<?= base_url() . 'themes/adminLte/' ?>plugins/bootbox/bootbox.min.js"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>-->
<script src="<?= base_url() . 'themes/adminLte/' ?>dist/js/moment.min.js"></script>

<script src="<?= base_url() . 'themes/adminLte/' ?>dist/js/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

<script src="<?= base_url() . 'themes/front/' ?>js/valid.js"></script>

<script type="text/javascript">
    $('document').ready(function () {
        setTimeout(function(){
            $(".alert").slideUp(500);    
        },30000);
        
        $('#first_name, #last_name').keydown(function(e) {
            if (e.keyCode == 32) {
                return false;
            }
        });

        //Timepicker
        $(".timepicker").timepicker({
            showInputs: false,
            defaultTime:false
        });
		
		$('#birth_date').datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true, //this option for allowing user to select month
            changeYear: true, //this option for allowing user to select from year range
            yearRange: '1900:' + (new Date).getFullYear(),
            maxDate: '0'
        });

        //Date picker
        $('.datepicker').datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true, //this option for allowing user to select month
            changeYear: true, //this option for allowing user to select from year range
            yearRange: '1945:' + (new Date).getFullYear(),
            maxDate: '0'
        });
        
        $('body').on('change', '.dependent', function () {
            var data_val = $(this).val();
            var base_url = $(this).attr('data-url');
            var dependentId = $(this).attr('data-dependent');
            var action = base_url + '?key=' + data_val;

            $.ajax({
                url: action,
                dataType: 'json',
                type: 'POST',
                success: function (response) {

                    var result = response;

                    if (result.errorcode == "1") {

                        $('#' + dependentId).html(result.html);
                        $('#' + dependentId).show();

                    } else
                    {
                        $('#ErrorMsg').html(result.message_error);
                        $('#ErrorMsg').show();
                    }
                    setTimeout(function(){
                        $(".alert").slideUp(500);    
                    },30000);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
		
		$('body').on('change', '.dependentAdmin', function () {
            var data_val = $(this).val();
            var base_url = $(this).attr('data-url');
            var dependentId = $(this).attr('data-dependent');
            var action = base_url + '?key=' + data_val;

            $.ajax({
                url: action,
                dataType: 'json',
                type: 'POST',
                success: function (response) {

                    var result = response;

                    if (result.errorcode == "1") {

                        $('#' + dependentId).html(result.html);
                        $('#' + dependentId).show();

                    } else
                    {
                        $('#ErrorMsg').html(result.message_error);
                        $('#ErrorMsg').show();
                    }

                    setTimeout(function(){
                        $(".alert").slideUp(500);    
                    },30000);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });

        $('body').on('submit', '.ajax-form', function (e) {
            showLoadingBar();
            e.preventDefault();
            var formdata = new FormData($(this)[0]);
            var action = $(this).attr('action');

            $.ajax({
                url: action,
                dataType: 'json',
                data: formdata,
                type: 'POST',
                success: function (response) {

                    var result = response;
                    var msg = result.msg;

                    if (result.errorcode == "1") {

                        var action = result.action;

                        if (action == "RENDER")
                        {
                            if (result.footer == "1")
                            {
                                $('body').addClass('body-bg');
                                $('.footer').show();
                            } else
                            {
                                $('body').removeClass('body-bg');
                                $('.footer').hide();
                            }
                            var html = result.html;
                            document.title = result.page_title;
                            //$("#main-content").hide("slide", { direction: "left" }, 1000);
                            $("#main-content").html(html);
                        } else if (action == "REDIRECT")
                        {
                            var redirectUrl = result.url;
                            window.location.href = redirectUrl;
                        } else
                        {
                        }

                        if (msg !== "" && typeof msg !== "undefined")
                        {
                            $('.alertTextSuccess').html(msg);
                            $('.alert-success').show();
                            setTimeout(function () {
                                $('.alert-success').fadeOut('slow');
                            }, 3000); // <-- time in milliseconds
                        }

                    } else
                    {
                        bootbox.alert(msg);
                    }
                    setTimeout(function(){
                        $(".alert").slideUp(500);    
                    },30000);
                    hideLoadingBar();
                },
                cache: false,
                contentType: false,
                processData: false
            });

        });

        $('#pass_hide_show').click(function(){
            if ($('input#password').attr("type") === "password") {
                $('input#password').attr("type","text");
                $('#pass_hide_show i').removeClass( "fa-eye-slash" );
                $('#pass_hide_show i').addClass( "fa-eye" );
              } else {
                $('input#password').attr("type","password");
                $('#pass_hide_show i').addClass( "fa-eye-slash" );
                $('#pass_hide_show i').removeClass( "fa-eye" );
              }
        });
    });

    window.onbeforeunload = function (e) {
        showLoadingBar();
    }

    $(window).load(function () {
        hideLoadingBar();
    });

    $window.focus(function(){
        hideLoadingBar();
    });
    function showLoadingBar()
    {
        $('.loadingBar').fadeIn('slow');
    }

    function hideLoadingBar()
    {
        //setTimeout(function () {
            $('.loadingBar').fadeOut('slow');
        //}, 1000);
    }
</script>