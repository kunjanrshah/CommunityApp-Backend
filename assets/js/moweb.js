$('.carousel').carousel({
    interval: 5000 //changes the speed
});

$(document).ready(function () {
    $('#dropdownMenu1').click(function () {
        var hidden = $('.sideoff-off');
        var hidden1 = $('.overlay');
        if (hidden.hasClass('visible')) {
            hidden.animate({"right": "-1000px"}, 500).removeClass('visible');
            // $(".overlay").css('display', 'none');
        } else {
            hidden.animate({"right": "0px"}, 500).addClass('visible');
            //$(".overlay").css('display','block');
            //hidden1.fadeIn(500);
        }
    });
});
$(document).ready(function () {
    $('#dropdownMenu2').click(function () {
        var hidden = $('.sideoff-off');
        var hidden1 = $('.overlay');
        if (hidden.hasClass('visible')) {
            hidden.animate({"right": "-1000px"}, 500).removeClass('visible');
            // $(".overlay").css('display', 'none');
        } else {
            hidden.animate({"right": "0px"}, 500).addClass('visible');
            //$(".overlay").css('display','block');
            // hidden1.fadeIn(500);
        }
    });
});
$(document).ready(function () {
    $('#dropdownMenu3').click(function () {
        var hidden = $('.sideoff-off');
        var hidden1 = $('.overlay');
        if (hidden.hasClass('visible')) {
            hidden.animate({"right": "-1000px"}, 500).removeClass('visible');
            // $(".overlay").css('display', 'none');
        } else {
            hidden.animate({"right": "0px"}, 500).addClass('visible');
            //$(".overlay").css('display','block');
            //hidden1.fadeIn(500);
        }
    });
});

function closeOverlay()
{
    var hidden = $('.sideoff-off');
    var hidden1 = $('.overlay');
    hidden.animate({"right": "-1000px"}, 500).removeClass('visible');
    //hidden1.animate({"right":"0px"}, "slow");
    hidden1.fadeOut(500);
    //$(".overlay").css('display','none');
}

