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
                email: true
            },
            mobile: {
                minlength: 10,
                digits: true
            },
            phone: {
                minlength: 10,
                digits: true
            },
            password: {
                noSpace: true,
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
        }
    });
    jQuery.validator.addMethod("lettersonly", function (value, element) {
        return this.optional(element) || /^[a-z]+$/i.test(value);
    }, "Only alphabetic characters allow");

    jQuery.validator.addMethod("noSpace", function (value, element) {
        return value.indexOf(" ") < 0 && value != "";
    }, "No space please and don't leave it empty");

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