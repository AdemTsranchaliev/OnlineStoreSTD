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
$('#populatedPlace').blur(function () {
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






    var conceptName = $('#populatedPlace').find(":selected").text();
    if (conceptName == "Избери...") {
        $('#warning7').show();
        e.preventDefault();
    }

    var conceptName2 = $('#ademss').find(":selected").text();
    if (conceptName2 == "Избери...") {
        $('#warning3').show();
        e.preventDefault();
    }

    if (conceptName2 === "Вземане лично от офис на куриер") {
        if (!$.trim($('input#ademss3').val())) {
            $('#warning8').show();
            e.preventDefault();
        }
    }
    else if (conceptName === "Доставка с куриер до адрес") {
        if (!$.trim($('#billing_address_2').val())) {
            $('#warning6').show();
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
    var conceptName = $('#sizeSelected').find(":selected").text();
    $("#addToCart").attr("disabled", false);

    if (conceptName === "Избери...") {
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

$('#registration_form_name').blur(function () {

    if ($(this).val().length == 0) {

        $('#registerNameWarning').show();

    }
    else
    {
        $('#registerNameWarning').hide();
    }
});
$('#registration_form_surname').blur(function () {

    if ($(this).val().length == 0) {

        $('#registerSurnameWarning').show();

    }
    else
    {
        $('#registerSurnameWarning').hide();
    }
});
$('#registration_form_email').blur(function () {

    if ($(this).val().length == 0) {

        $('#registerEmailWarning').show();

    }
    else
    {
        $('#registerEmailWarning').hide();
        var strEmail = new RegExp('.+@.+');
        if (!strEmail.test($('input#registration_form_email').val())) {


            $('#registerEmailWarningInvalid').show();
        }
        else {
            $('#registerEmailWarningInvalid').hide();
        }
    }
});
$('#registration_form_plainPassword').blur(function () {

    if ($(this).val().length == 0) {
        $('#registerPasswordWarningValidation').hide();
        $('#registerPasswordWarning').show();

    }
    else
    {
        $('#registerPasswordWarning').hide();
        var strPassword = new RegExp("^(?=.*\\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$");
        if (!strPassword.test($('#registration_form_plainPassword').val())) {


            $('#registerPasswordWarningValidation').show();
        }
        else {
            $('#registerPasswordWarningValidation').hide();
        }
    }
});

$('#registerPasswordRepeat').blur(function () {

    if ($(this).val() === $('#registration_form_plainPassword').val())
    {
        $('#registerPasswordRepeatWarning').hide();
    }
    else
    {
        $('#registerPasswordRepeatWarning').show();
    }
});

$("#registrationForm").submit(function (e) {
    var checker=0;
    if (!$.trim($('#registration_form_name').val())) {
        $('#registerNameWarning').show();
        checker++;
    }
    if (!$.trim($('#registration_form_surname').val())) {
        $('#registerSurnameWarning').show();
        checker++;
    }
    if (!$.trim($('#registration_form_email').val())) {
        $('#registerEmailWarning').show();
        checker++;
    }
    if (!$.trim($('#registration_form_plainPassword').val())) {
        $('#registerPasswordWarning').show();
        checker++;
    }
    var strEmail2 = new RegExp('^(?=.*\\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$');
    if (!strEmail2.test($('#registration_form_plainPassword').val())) {

        checker++;
        $('#registerPasswordWarningValidation').show();
    }
    else {
        $('#registerPasswordWarningValidation').hide();
    }

    if ($('input#ship-to-different-address-checkbox').is(":checked") == false) {
        $('#agreeTermsWarning').show();
        checker++;
    }
    if ($('input#ship-to-different-address-checkbox').is(":checked") == true) {
        $('#agreeTermsWarning').hide();

    }
    if ($('#registerPasswordRepeat').val() === $('#registration_form_plainPassword').val())
    {

        $('#registerPasswordRepeatWarning').hide();
    }
    else
    {
        checker++;
        $('#registerPasswordRepeatWarning').show();
    }
    if (checker!=0)
    {
        e.preventDefault();
    }
    if ($('#emCheck').val() === "no") {
        e.preventDefault();
        $.ajax({
            url: '/checkIfMailExists',
            type: 'POST',
            dataType: 'html',
            data: {'email': $("#registration_form_email").val()},
            async: true,

            success: function (data, status) {
                if (data === "yes") {

                    $("#registerPasswordWarningExist").show();

                }
                else {
                    $("#registerPasswordWarningExist").hide();
                    $('#emCheck').val("yes");
                    $('#registrationForm').submit();
                }

            },
            error: function (xhr, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    }
})
;
$('#changeInfoName').blur(function () {

    if ($(this).val().length == 0) {

        $('#changeInfoNameWarning').show();

    }
    else
    {
        $('#changeInfoNameWarning').hide();
    }
});
$('#changeInfoSurname').blur(function () {

    if ($(this).val().length == 0) {

        $('#changeInfoSurnameWarning').show();

    }
    else
    {
        $('#changeInfoSurnameWarning').hide();
    }
});
//$('#changeInfoTown').blur(function () {
//
//    if ($(this).val().length == 0) {
//
//     //   $('#changeInfoTownWarning').show();
//
//    }
//    else
//    {
//        $('#changeInfoTownWarning').hide();
//    }
//});
$('#changeInfoEmail').blur(function () {

    if ($(this).val().length == 0) {

        $('#changeInfoEmailWarning').show();

    }
    else
    {
        $('#changeInfoEmailWarning').hide();
        var strEmail = new RegExp('.+@.+');
        if (!strEmail.test($('input#changeInfoName').val())) {

            $('#changeInfoEmailWarningInvalid').show();
        }
        else {
            $('#changeInfoEmailWarningInvalid').hide();
        }
    }
});

$('#changeInfoPhone').blur(function () {

    var strPhone = new RegExp('^((08)|(\\+3598))[789]\\d{7}$');
    if (!strPhone.test($('#changeInfoPhone').val()) && $.trim($('#changeInfoPhone').val())) {
        $('#changeInfoPhoneWarningInvalid').show();
    }
    else
    {
        $('#changeInfoPhoneWarningInvalid').hide();
    }

});

$("#changeInfo").submit(function (e) {
    if ($("#changeInfoName").val().length == 0) {

        $('#changeInfoNameWarning').show();
        e.preventDefault();

    }
    else
    {
        $('#changeInfoNameWarning').hide();
    }
    if ($("#changeInfoSurname").val().length == 0) {

        $('#changeInfoSurnameWarning').show();
        e.preventDefault();

    }
    else
    {
        $('#changeInfoSurnameWarning').hide();
    }
    var strPhone = new RegExp('^((08)|(\\+3598))[789]\\d{7}$');
    if (!strPhone.test($('#changeInfoPhone').val()) && $.trim($('#changeInfoPhone').val())) {
        $('#changeInfoPhoneWarningInvalid').show();
        e.preventDefault();
    }
    else
    {
        $('#changeInfoPhoneWarningInvalid').hide();
    }

});


$('#addProductName').blur(function () {

    if ($("#addProductName").val().length == 0) {

        $('#addProductNameWarning').show();


    }
    else
    {
        $('#addProductNameWarning').hide();
    }

});
$('#addProductModelNumber').blur(function () {

    if ($("#addProductModelNumber").val().length == 0) {

        $('#addProductModelNumberWarning').show();

    }
    else
    {
        $('#addProductModelNumberWarning').hide();
    }

});
$('#addProductPrice').blur(function () {

    if ($("#addProductPrice").val().length == 0) {

        $('#addProductPriceWarning').show();


    }
    else
    {
        $('#addProductPriceWarning').hide();
    }

});
$('#addProductColor').blur(function () {

    if ($("#addProductColor").val().length == 0) {

        $('#addProductColorWarning').show();


    }
    else
    {
        $('#addProductColorWarning').hide();
    }
});
$('#addProductCategory').blur(function () {

    if ($("#addProductCategory").find(":selected").text() == 'Изберете...') {

        $('#addProductCategoryWarning').show();


    }
    else
    {
        $('#addProductCategoryWarning').hide();
    }

});
$("#addModel").submit(function (e) {
    if ($("#addProductName").val().length == 0) {

        $('#addProductNameWarning').show();
        e.preventDefault();
    }
    else
    {
        $('#addProductNameWarning').hide();
    }
    if ($("#addProductModelNumber").val().length == 0) {

        $('#addProductModelNumberWarning').show();
        e.preventDefault();
    }
    else
    {
        $('#addProductModelNumberWarning').hide();
    }
    if ($("#addProductPrice").val().length == 0) {

        $('#addProductPriceWarning').show();
        e.preventDefault();

    }
    else
    {
        $('#addProductPriceWarning').hide();
    }
    if ($("#addProductPrice").val().length == 0) {

        $('#addProductPriceWarning').show();
        e.preventDefault();

    }
    else
    {
        $('#addProductPriceWarning').hide();
    }
    if ($("#addProductColor").val().length == 0) {

        $('#addProductColorWarning').show();
        e.preventDefault();

    }
    else
    {
        $('#addProductColorWarning').hide();
    }
    if ($("#addProductCategory").find(":selected").text() == 'Изберете...') {

        $('#addProductCategoryWarning').show();


    }
    else
    {
        $('#addProductCategoryWarning').hide();
    }

});
