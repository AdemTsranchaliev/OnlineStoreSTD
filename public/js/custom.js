$("select")
    .change(function () {
        var conceptName = $('#ademss').find(":selected").text();
        if (conceptName == "Доставка с куриер до адрес") {
            $('#infoForAdress').show();
            $('#infoForOffice').hide();
        }
        else if (conceptName == "Вземане лично от офис на куриер") {
            $('#infoForAdress').hide();
            $('#infoForOffice').show();
        }
        else if (conceptName == "Избери...") {
            $('#infoForAdress').hide();
            $('#infoForOffice').hide();
        }
    })
    .trigger("change");
$('#billing_first_name').blur(function () {
    if ($(this).val().length < 1) {
        $('#warning1').show();
    }
    else {
        $('#warning1').hide();
    }

});
$('#billing_last_name').blur(function () {
    if ($(this).val().length < 1) {
        $('#warning2').show();
    }
    else {
        $('#warning2').hide();
    }

});
$('#ademss').blur(function () {
    if ($(this).val() == "Избери...") {
        $('#warning3').show();
    }
    else {
        $('#warning3').hide();
    }

});
$('#billing_city').blur(function () {
    if ($(this).val().length < 1) {
        $('#warning4').show();
    }
    else {
        $('#warning4').hide();
    }

});
$('#billing_postcode').blur(function () {
    if ($(this).val().length < 1) {
        $('#warning5').show();
    }
    else {
        $('#warning5').hide();
    }

});
$('#billing_address_2').blur(function () {
    if ($(this).val().length < 1) {
        $('#warning6').show();
    }
    else {
        $('#warning6').hide();
    }

});
$('#ademss2').blur(function () {
    if ($(this).val() == "Избери...") {
        $('#warning7').show();
    }
    else {
        $('#warning7').hide();
    }

});
$('#ademss3').blur(function () {
    if ($(this).val().length < 1) {
        $('#warning8').show();
    }
    else {
        $('#warning8').hide();
    }

});
$('#billing_phone').blur(function () {

    if ($(this).val().length == 0) {
        $('#warning9').show();
        $('#warning9Reg').hide();
    }
    else if (($(this).val().length > 0)) {
        var strPhone = new RegExp('^((08)|(\\+3598))[789]\\d{7}$');
        if (!strPhone.test($('input#billing_phone').val())) {
            $('#warning9').hide();
            $('#warning9Reg').show();

        }
        else {
            $('#warning9').hide();
            $('#warning9Reg').hide();
        }
    }


});


$('input#billing_email').blur(function () {
    if ($(this).val().length == 0) {
        $('#warning10').show();
        $('#warning10Reg').hide();
    }
    else if ($(this).val().length > 0) {
        var strEmail = new RegExp('.+@.+');
        if (!strEmail.test($('input#billing_email').val())) {

            $('#warning10').hide();
            $('#warning10Reg').show();
        }
        else {
            $('#warning10').hide();
            $('#warning10Reg').hide();
        }
    }
});


$("#checkOut").submit(function (e) {
    if (!$.trim($('input#billing_first_name').val())) {
        $('#warning1').show();
        e.preventDefault();
    }
    if (!$.trim($('input#billing_last_name').val())) {
        $('#warning2').show();
        e.preventDefault();
    }
    var conceptName = $('#ademss').find(":selected").text();
    if (conceptName == "Избери...") {
        $('#warning3').show();
        e.preventDefault();
    }

    if (conceptName == "Вземане лично от офис на куриер") {
        conceptName = $('input#ademss2').find(":selected").text();
        if (conceptName == "Избери...") {
            $('#warning7').show();
            e.preventDefault();
        }
        if (!$.trim($('input#ademss3').val())) {
            $('#warning8').show();
            e.preventDefault();
        }
    }
    else if (conceptName == "Доставка с куриер до адрес") {
        if (!$.trim($('input#billing_city').val())) {
            $('#warning4').show();
            e.preventDefault();
        }
        if (!$.trim($('input#billing_postcode').val())) {
            $('#warning5').show();
            e.preventDefault();
        }
        if (!$.trim($('input#billing_address_2').val())) {
            $('#warning6').show();
            $('#warning9Reg').hide();
            e.preventDefault();
        }
    }
    if (!$.trim($('input#billing_phone').val())) {
        $('#warning9').show();
        $('#warning9Reg').hide();
        e.preventDefault();
    }
    var strPhone = new RegExp('^((08)|(\\+3598))[789]\\d{7}$');
    if (!strPhone.test($('input#billing_phone').val()) && $.trim($('input#billing_phone').val())) {
        $('#warning9Reg').show();
        $('#warning9').hide();
        e.preventDefault();
    }
    if (!$.trim($('input#billing_email').val())) {
        $('#warning10').show();
        e.preventDefault();
    }
    var strEmail = new RegExp('.+@.+');
    if (!strEmail.test($('input#billing_email').val()) && $.trim($('input#billing_email').val()) && $.trim($('input#billing_email').val())) {
        $('#warning10Reg').show();
        $('#warning10').hide();
        e.preventDefault();
    }
    if ($('input#ship-to-different-address-checkbox').is(":checked") == false) {
        $('#warning11').show();
        e.preventDefault();
    }
    if ($('input#ship-to-different-address-checkbox').is(":checked") == true) {
        $('#warning11').hide();

    }
    $('#orderSize').blur(function () {
        var conceptName = $('#size__select').find(":selected").text();
        $('#orderSize').val(conceptName);
    });

})
;
$("#sendSubmitForBuying").submit(function (e) {
    var conceptName = $('#size__select').find(":selected").text();
    if (conceptName == "Избери...") {
        $('#warningSize').show();
        e.preventDefault();
    }

})
;
$(document).ready( function(){

    var gmapDiv = $("#google-map");
    var gmapMarker = gmapDiv.attr("data-address");

    gmapDiv.gmap3({
        zoom: 16,
        address: gmapMarker,
        oomControl: true,
        navigationControl: true,
        scrollwheel: false,
        styles: [
            {
                "featureType":"all",
                "elementType":"all",
                "stylers":[
                    { "saturation":"0" }
                ]
            }]
    })
        .marker({
            address: gmapMarker,
            icon: "img/map_pin.png"
        })
        .infowindow({
            content: "V Tytana St, Manila, Philippines"
        })
        .then(function (infowindow) {
            var map = this.get(0);
            var marker = this.get(1);
            marker.addListener('click', function() {
                infowindow.open(map, marker);
            });
        });
});