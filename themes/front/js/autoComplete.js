$('document').ready(function () {
    $('body').on('change', '.otheroption', function () {
        if ($(this).val() == "Other") {
            var dependent = $(this).attr('data-dependent');
            $('#' + dependent).show();
            $(this).hide();
        } else {
            var data_val = $(this).val();
            var base_url = $(this).attr('data-url');
            var dependentId = $(this).attr('data-select-dep');
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
                },
                cache: false,
                contentType: false,
                processData: false
            });
        }
    });

    $('body').on('click', '.openselect', function () {
        var selectId = $(this).attr('data-select');
        var otherText = $(this).attr('data-other');
        $('#' + otherText).hide();
        $('#' + selectId).val('');
        $('#' + selectId).show();
    });

    $("#sub_community_id").autocomplete({
        source: "/master/getSubCommunity",
        select: function (event, ui) {
            event.preventDefault();
            $("#sub_community_id").val(ui.item.value);
        }
    });

    $("#local_community_id").autocomplete({
        source: "/master/getLocalCommunity",
        select: function (event, ui) {
            event.preventDefault();
            $("#local_community_id").val(ui.item.value);
        }
    });

    $("#committee_id").autocomplete({
        source: "/master/getCommittee",
        select: function (event, ui) {
            event.preventDefault();
            $("#committee_id").val(ui.item.value);
        }
    });

    $("#designation_id").autocomplete({
        source: "/master/getDesignation",
        select: function (event, ui) {
            event.preventDefault();
            $("#designation_id").val(ui.item.value);
        }
    });

    $("#business_category_id").autocomplete({
        source: "/master/getBusinessCategory",
        select: function (event, ui) {
            event.preventDefault();
            $("#business_category_id").val(ui.item.value);
        }
    });

    $("#business_sub_category_id").autocomplete({
        source: "/master/getBusinessSubCategory",
        select: function (event, ui) {
            event.preventDefault();
            $("#business_sub_category_id").val(ui.item.value);
        }
    });

    $("#education_id").autocomplete({
        source: "/master/getEducations",
        select: function (event, ui) {
            event.preventDefault();
            $("#education_id").val(ui.item.value);
        }
    });


    $("#occupation_id").autocomplete({
        source: "/master/getOccupations",
        select: function (event, ui) {
            event.preventDefault();
            $("#occupation_id").val(ui.item.value);
        }
    });

    $("#current_activity_id").autocomplete({
        source: "/master/getActivity",
        select: function (event, ui) {
            event.preventDefault();
            $("#current_activity_id").val(ui.item.value);
        }
    });

    $("#gotra_id").autocomplete({
        source: "/master/getGotra",
        select: function (event, ui) {
            event.preventDefault();
            $("#gotra_id").val(ui.item.value);
        }
    });

    $("#native_place_id").autocomplete({
        source: "/master/getNatives",
        select: function (event, ui) {
            event.preventDefault();
            $("#native_place_id").val(ui.item.value);
        }
    });

    $("#mosaad_id").autocomplete({
        source: "/master/getMosaads",
        select: function (event, ui) {
            event.preventDefault();
            $("#mosaad_id").val(ui.item.value);
        }
    });

    $("#relation_id").autocomplete({
        source: "/master/getRelations",
        select: function (event, ui) {
            event.preventDefault();
            $("#relation_id").val(ui.item.value);
        }
    });

});