$(document).ready(function () {

    $(".validateForm").validate({
        rules: {
            website: {
                url: true
            },
            facebook_profile: {
                url: true
            },
            email_address: {
                email: true,
            },
            password: {
                noSpace: true,
            },
            pin: {
                digits: true,
                noSpace: true,
            },
            password_confirm: {
                equalTo: "#password"
            },
            mobile: {
                digits: true,
                minlength: 10
            },
            phone: {
                digits: true,
                minlength: 10
            },
            pincode: {
                digits: true,
                minlength: 6
            }
        },
        messages: {
            phone: {
                minlength: "Please enter valid phone number.",
                digits: "Only number allowed."
            },
            mobile: {
                minlength: "Please enter valid phone number.",
                digits: "Only number allowed."
            }
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") == "pin") {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

    $(".validateEditForm").validate({
        rules: {
            website: {
                url: true
            },
            facebook_profile: {
                url: true
            },
            email_address: {
                email: true,
            },
            password: {
                noSpace: true,
            },
            pin: {
                digits: true,
                // noSpace: true,
            },
            password_confirm: {
                equalTo: "#password"
            },
            mobile: {
                digits: true,
                minlength: 10
            },
            phone: {
                digits: true,
                minlength: 10
            },
            pincode: {
                digits: true,
                minlength: 6
            }
        },
        messages: {
            phone: {
                minlength: "Please enter valid phone number.",
                digits: "Only number allowed."
            },
            mobile: {
                minlength: "Please enter valid phone number.",
                digits: "Only number allowed."
            }
        },
        submitHandler: function(form) {
          if(confirm("Are you sure edit this details?")){
            form.submit();
          }
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") == "pin") {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

    jQuery.validator.addMethod("lettersonly", function (value, element) {
        return this.optional(element) || /^[a-z]+$/i.test(value);
    }, "Only alphabetic characters allow");

    jQuery.validator.addMethod("noSpace", function (value, element) {
        return value.indexOf(" ") < 0 && value != "";
    }, "No space please and don't leave it empty");

    jQuery.validator.addMethod('validUrl', function (value, element) {
        var url = $.validator.methods.url.bind(this);
        return url(value, element) || url('http://' + value, element);
    }, 'Please enter a valid URL');
});

function compareTime() {
    //start time
    var start_time = $("#eventsStartTime").val();
//end time
    var end_time = $("#eventsEndTime").val();
//convert both time into timestamp
    var stt = new Date("November 13, 2013 " + start_time);
    stt = stt.getTime();
    var endt = new Date("November 13, 2013 " + end_time);
    endt = endt.getTime();
//by this you can see time stamp value in console via firebug
    console.log("Time1: " + stt + " Time2: " + endt);
    if (stt > endt) {
        if ($('.timecomp').length < 1) {
            $("#eventsEndTime").after('<span class="error timecomp"><br>End-time must be bigger then Start-time.</span>');
        }
        return false;
    } else
    {
        if ($('.timecomp').length > 0) {
            $('.timecomp').remove();
        }
        return true;
    }
}