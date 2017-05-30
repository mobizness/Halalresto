function citiesList() {
    var id = $('#StoreStoreState').val();
    $.post(rp + '/stores/locations', {'id': id, 'model': 'City'}, function (response) {
        $("#StoreStoreCity").html(response);
    });
}

function locationList() {
    var id = $('#StoreStoreCity').val();
    $.post(rp + '/stores/locations', {'id': id, 'model': 'Location'}, function (response) {
        $("#StoreStoreZip").html(response);
        $("#DeliveryLocationLocationId").html(response);
    });
}

function deliveryOptions() {
    if ($("#StoreDeliveryOptionYes").is(":checked")) {
        $("#deliveryOption").show();
    } else {
        $("#deliveryOption").hide();
    }
}


function emailOptions() {
    if ($("#StoreEmailOrderYes").is(":checked")) {
        $("#emailOption").show();
    } else {
        $("#emailOption").hide();
    }
}

function smsOptions() {
    if ($("#StoreSmsOptionYes").is(":checked")) {
        $("#smsOption").show();
    } else {
        $("#smsOption").hide();
    }
}

$(document).ready(function () {
    deliveryOptions();
    emailOptions();
    smsOptions();

var breadcrumb = $(".page-breadcrumb").html();
$(".page-breadcrumb").html(breadcrumb + "<li class='hotline-rest hidden-xs'><i class='fa fa-phone'></i><a href='tel:01 84 21 46 35'>01 84 21 46 35</a></li>");
    $("input[name='data[Store][delivery_option]']").click(function () {
        deliveryOptions();
    });
    $("input[name='data[Store][email_order]']").click(function () {
        emailOptions();
    });
    $("input[name='data[Store][sms_option]']").click(function () {
        smsOptions();
    });

    $(".slotTime").each(function () {

        var slotId = $(this).attr('id');
        var OpenTime = $('#' + slotId + '_opentime').val();
        var CloseTime = $('#' + slotId + '_closetime').val();

        $('#' + slotId + '_from').html(OpenTime);
        $('#' + slotId + '_to').html(CloseTime);

        var hours = Number(OpenTime.match(/^(\d+)/)[1]);
        var minutes = Number(OpenTime.match(/:(\d+)/)[1]);
        // var AMPM = OpenTime.match(/\s(.*)$/)[1];
        // if(AMPM == "PM" && hours<12) hours = hours+12;
        // if(AMPM == "AM" && hours==12) hours = hours-12;
        var sHours = hours.toString();
        var sMinutes = minutes.toString();
        if (hours < 10)
            sHours = "0" + sHours;
        if (minutes < 10)
            sMinutes = "0" + sMinutes;

        var open = parseFloat(sHours + "." + sMinutes) * 60;

        var hours = Number(CloseTime.match(/^(\d+)/)[1]);
        var minutes = Number(CloseTime.match(/:(\d+)/)[1]);
        // var AMPM = CloseTime.match(/\s(.*)$/)[1];
        // if(AMPM == "PM" && hours<12) hours = hours+12;
        // if(AMPM == "AM" && hours==12) hours = hours-12;
        var sHours = hours.toString();
        var sMinutes = minutes.toString();
        if (hours < 10)
            sHours = "0" + sHours;
        if (minutes < 10)
            sMinutes = "0" + sMinutes;

        var close = parseFloat(sHours + "." + sMinutes) * 60;


        $('#' + slotId).slider({
            range: true,
            min: 0,
            max: 1440,
            step: 15,
            values: [open, close],
            slide: function (e, ui) {
                var hours1 = Math.floor(ui.values[0] / 60);
                var minutes1 = ui.values[0] - (hours1 * 60);

                if (hours1.length == 1)
                    hours1 = '0' + hours1;
                if (minutes1.length == 1)
                    minutes1 = '0' + minutes1;
                if (minutes1 == 0)
                    minutes1 = '00';
                if (hours1 >= 12) {
                    if (hours1 == 12) {
                        hours1 = hours1;
                        minutes1 = minutes1;
                    } else {
                        hours1 = hours1; //hours1 = hours1 - 12;
                        minutes1 = minutes1;
                    }
                } else {
                    hours1 = hours1;
                    minutes1 = minutes1;
                }
                /*if (hours1 == 0) {
                 hours1 = 12;
                 minutes1 = minutes1;
                 }*/

                $('#' + slotId + '_from').html(hours1 + ':' + minutes1);
                $('#' + slotId + '_opentime').val(hours1 + ':' + minutes1);

                var hours2 = Math.floor(ui.values[1] / 60);
                var minutes2 = ui.values[1] - (hours2 * 60);

                if (hours2.length == 1)
                    hours2 = '0' + hours2;
                if (minutes2.length == 1)
                    minutes2 = '0' + minutes2;
                if (minutes2 == 0)
                    minutes2 = '00';
                if (hours2 >= 12) {
                    if (hours2 == 12) {
                        hours2 = hours2;
                        minutes2 = minutes2;
                    } else if (hours2 == 24) {
                        hours2 = 23;
                        minutes2 = "59 PM";
                    } else {
                        hours2 = hours2; //hours2 = hours2 - 12;
                        minutes2 = minutes2;
                    }
                } else {
                    hours2 = hours2;
                    minutes2 = minutes2;
                }

                $('#' + slotId + '_to').html(hours2 + ':' + minutes2);
                $('#' + slotId + '_closetime').val(hours2 + ':' + minutes2);
            }
        });
    });
});

function subAddons() {
    $("#subaddons_append").append('<div class="form-group"><div class="col-md-6 col-lg-3 col-md-offset-3"><input type="text" class="form-control" ></div><div class="col-md-6 col-lg-2"><a href="javascript:;" onclick="removesubAddons(this);" class="btn btn-danger">X</a></div></div>');
}

function removesubAddons(e) {
    $(e).parents(".form-group").remove();
}

function mainAddons() {
    $("#mainaddons_append").append('<div id="outerAddons"> <div class="form-group"><label class="col-md-3 control-label">Addons Name <span class="star">*</span></label><div class="col-md-6 col-lg-3"><input type="text" class="form-control" ></div><div class="col-md-6 col-lg-2"><input type="text" class="form-control" ></div><div class="col-md-6 col-lg-2"><a href="javascript:;" class="btn btn-success" onclick="mainsubAddons();">Add Sub Addons</a></div><div class="col-md-3 col-lg-2"><a href="javascript:;" onclick="removemainAddons(this);" class="btn btn-danger">X</a></div></div><div id="mainsubAddons_append"></div></div>');
}

function removemainAddons(e) {
    $(e).parents("#outerAddons").remove();
}

function mainsubAddons() {
    $("#mainsubAddons_append").append('<div class="form-group"><div class="col-md-6 col-lg-3 col-md-offset-3"><input type="text" class="form-control" ></div><div class="col-md-6 col-lg-2"><a href="javascript:;" onclick="removesubAddons(this);" class="btn btn-danger">X</a></div></div>');
}


function validateStoreEdit() {

    var StoreId = $('#StoreId').val();
    var StoreContactName = $.trim($("#StoreContactName").val());
    var StoreContactPhone = $.trim($("#StoreContactPhone").val());
    var StoreContactEmail = $.trim($("#StoreContactEmail").val());
    if (addressMode != 'Google') {
        var StoreStreetAddress = $.trim($("#StoreStreetAddress").val());
        var StoreStoreState = $.trim($("#StoreStoreState").val());
        var StoreStoreCity = $.trim($("#StoreStoreCity").val());
        var StoreStoreZip = $.trim($("#StoreStoreZip").val());
    } else {
        var StoreAddress = $.trim($("#StoreAddress").val());
    }
    var StoreStoreName = $.trim($("#StoreStoreName").val());
    var StoreStorePhone = $.trim($("#StoreStorePhone").val());
    var UserUsername = $.trim($("#UserUsername").val());
    var UserPassword = $.trim($("#UserPassword").val());

    var StoreTax = $.trim($("#StoreTax").val());
    var StoreOrderEmail = $.trim($("#StoreOrderEmail").val());
    var StoreSmsPhone = $.trim($("#StoreSmsPhone").val());
    var StoreCommission = $.trim($("#StoreCommission").val());
    var invoice_period = $.trim($("#StoreInvoicePeriod").val());
    var emailRegex = new RegExp(/^([\w\.\-]+)@([\w\-]+)((\.(\w){2,3})+)$/i);

    var StoreDeliveryOptionYes = $.trim($("#StoreDeliveryOptionYes").val());
    var DeliveryLocationLocationId = $.trim($("#DeliveryLocationLocationId").val());

    var StoreStoreLogo = $.trim($("#StoreStoreLogo").val());
    var StoreStoreBanner = $.trim($("#StoreStoreBanner").val());
    var extlogo = $('#StoreStoreLogo').val().split('.').pop().toLowerCase();
    var extban = $('#StoreStoreBanner').val().split('.').pop().toLowerCase();
    var StoreBannerImageLink = $.trim($("#StoreBannerImageLink").val());
    $("#contactError").html("");
    $("#shopError").html("");
    $("#meatError").html("");
    $("#deliveryError").html("");
    $("#orderError").html("");
    $("#commissionError").html("");

    if (StoreContactName == '') {
        $("[href=#contact]").trigger('click');
        $("#contactError").html("Entrez le nom du contact svp");
        $("#StoreContactName").focus();
        return false;
    } else if (StoreContactPhone == '') {
        $("[href=#contact]").trigger('click');
        $("#contactError").html("Entrez un numéro svp");
        $("#StoreContactPhone").focus();
        return false;
    } else if ((isNaN(StoreContactPhone))) {
        $("[href=#contact]").trigger('click');
        $("#contactError").html("Entrez un numéro de téléphone valide");
        $("#StoreContactPhone").focus();
        return false;
    } else if (StoreContactEmail == '') {
        $("[href=#contact]").trigger('click');
        $("#contactError").html("Entrez votre email svp");
        $("#StoreContactEmail").focus();
        return false;
    } else if (!emailRegex.test(StoreContactEmail)) {
        $("[href=#contact]").trigger('click');
        $("#contactError").html("Entrez une adresse email valide");
        $("#StoreContactEmail").focus();
        return false;
    } else if (addressMode != 'Google') {
        if (StoreStreetAddress == '') {
            $("[href=#contact]").trigger('click');
            $("#contactError").html("Please enter street address");
            $("#StoreStreetAddress").focus();
            return false;
        } else if (StoreStoreState == '') {
            $("[href=#contact]").trigger('click');
            $("#contactError").html("Sélectionnez le departement svp");
            $("#StoreStoreState").focus();
            return false;
        } else if (StoreStoreCity == '') {
            $("[href=#contact]").trigger('click');
            $("#contactError").html("Sélectionner la ville SVP");
            $("#StoreStoreCity").focus();
            return false;
        } else if (StoreStoreZip == '') {
            $("[href=#contact]").trigger('click');
            $("#contactError").html("Please select the zipcode/area name");
            $("#StoreStoreZip").focus();
            return false;
        }
    } else if (addressMode == 'Google' && StoreAddress == '') {
        $("[href=#contact]").trigger('click');
        $("#contactError").html("Entrez une adresse SVP");
        $("#StoreAddress").focus();
        return false;
    } else if (StoreStoreName == '') {
        $("[href=#shop]").trigger('click');
        $("#shopError").html("Entrez le nom du restaurant svp");
        $("#StoreStoreName").focus();
        return false;
    } else if ($('.meat_radio').val() == "No" && $.trim($("#meat-description").val()).length == 0) {
        $("[href=#meat]").trigger('click');
        $("#meatError").html("Entrez Description de l'endroit où vous obtenez votre viande");
        $("#StoreMeatDescription").focus();
        return false;
    } else if ($('.meat_radio').val() == "Yes" && $("#StoreMeatIssuerName").val() == "OTHER" && ($.trim($("#StoreMeatIssuerNameOther").val()).length == 0 || $.trim($("#StoreMeatIssuerNameOther").val()).length > 100)) {
        $("[href=#meat]").trigger('click');
        $("#meatError").html("Le nom d'un autre émetteur de certificat doit être compris entre 1 et 100");
        $("#StoreMeatDescription").focus();
        return false;
    } else if ($('.meat_radio').val() == "Yes" && ($.trim($("#StoreMeatIssuerName").val()).length == 0 || $.trim($("#StoreMeatIssuerName").val()).length > 100)) {
        $("[href=#meat]").trigger('click');
        $("#meatError").html("Entrez le nom du Certificateur");
        if ($.trim($("#StoreMeatIssuerName").val()).length > 100) {
            $("#meatError").html("Le nom du certificat doit être compris entre 1 et 100 caractères");
        }
        $("#StoreMeatIssuerName").focus();
        return false;
    } else if (StoreStorePhone == '') {
        $("[href=#shop]").trigger('click');
        $("#shopError").html("Entrer le numéro de téléphone du restaurant");
        $("#StoreStorePhone").focus();
        return false;
    } else if (StoreStoreLogo != '' && $.inArray(extlogo, ['gif', 'jpg', 'jpeg', 'png']) == -1) {
        $("#shopError").html("Please select the valid photo format (gif,jpg,jpeg,png)");
        $("#StoreStoreLogo").focus();
        return false;
    } else if (StoreStoreBanner != '' && $.inArray(extban, ['gif', 'jpg', 'jpeg', 'png', 'mp4']) == -1) {
        $("#shopError").html("Please select the valid photo/video format (gif,jpg,jpeg,png,mp4)");
        $("#StoreStoreBanner").focus();
        return false;
    } else if (extban == "mp4" && (($('#StoreStoreBanner')[0].files[0].size) / 1024 / 1024) > 4) {
        $("#shopError").html("Please upload a video of maximum 4MB in size.");
        $("#StoreStoreBanner").focus();
        return false;
    } else if (($("#StoreBannerImageLink").val() != '') && validateYouTubeUrl($("#StoreBannerImageLink").val())) {
        $("#shopError").html("Veuillez télécharger une URL de YouTube valide");
        $("#StoreBannerImageLink").focus();
        return false;
    } else if ((isNaN(StoreStorePhone))) {
        $("[href=#shop]").trigger('click');
        $("#shopError").html("Entrez un numéro de téléphone valide");
        $("#StoreStorePhone").focus();
        return false;
    } else if (StoreTax == '') {
        $("[href=#shop]").trigger('click');
        $("#shopError").html("Entrez le T.V.A svp");
        $("#StoreTax").focus();
        return false;
    } else if ((isNaN(StoreTax)) || StoreTax < 0) {
        $("[href=#shop]").trigger('click');
        $("#shopError").html("Entrez le T.V.A valide svp");
        $("#StoreTax").focus();
        return false;
    } else if (UserUsername == '') {
        $("[href=#shop]").trigger('click');
        $("#shopError").html("Entrez votre nom utilisateur svp");
        $("#UserUsername").focus();
        return false;
    } else if (!emailRegex.test(UserUsername)) {
        $("[href=#shop]").trigger('click');
        $("#shopError").html("Entrez une adresse email valide");
        $("#UserUsername").focus();
        return false;
    } else if (UserPassword == '' && StoreId == '') {
        $("[href=#shop]").trigger('click');
        $("#shopError").html("Entrez le mot de passe");
        $("#UserPassword").focus();
        return false;
    } else if (invoice_period == '') {
        $("[href=#invoice]").trigger('click');
        $("#invoiceError").html("Please select Invoice period");
        $("#StoreInvoicePeriod").focus();
        return false;
    } else if ($("#StoreEmailOrderYes").is(":checked")) {
        if (StoreOrderEmail == '') {
            $("[href=#order]").trigger('click');
            $("#orderError").html("Entrer l’email pour la réception des commandes svp");
            $("#StoreOrderEmail").focus();
            return false;
        } else if (!emailRegex.test(StoreOrderEmail)) {
            $("[href=#order]").trigger('click');
            $("#orderError").html("Entrez une adresse email valide");
            $("#StoreOrderEmail").focus();
            return false;
        }
    }

    if ($("#StoreSmsOptionYes").is(":checked")) {
        if (StoreSmsPhone == '') {
            $("[href=#order]").trigger('click');
            $("#orderError").html("Entrez votre numéro de téléphone SVP");
            $("#StoreSmsPhone").focus();
            return false;
        } else if ((isNaN(StoreSmsPhone))) {

            $("[href=#order]").trigger('click');
            $("#orderError").html("Entrez un numéro de téléphone valide");
            $("#StoreSmsPhone").focus();
            return false;
        }
    }

    if (StoreCommission == '') {
        $("[href=#commission]").trigger('click');
        $("#commissionError").html("Entrer la commission svp");
        $("#StoreCommission").focus();
        return false;
    } else if ((isNaN(StoreCommission))) {
        $("[href=#commission]").trigger('click');
        $("#commissionError").html("Entrer une commission valide");
        $("#StoreCommission").focus();
        return false;
    }
}


function validateYouTubeUrl(url)
{
    if (url != undefined || url != '') {
        var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
        var match = url.match(regExp);
        if (match && match[2].length == 11) {
            return false;
        } else {
            return true;
        }
    }
}

function showMap() {

    var StoreId = $('#StoreId').val();
    var Url = rp + '/AjaxAction';
    var Address = $('#StoreAddress').val();
    var distance = $('#StoreDeliveryDistance').val();

    if (Address == '') {
        $("[href=#contact]").trigger('click');
        $("#contactError").html("Entrez une adresse SVP");
        $("#StoreAddress").focus();
        return false;
    } else {
        var resName = $('#StoreStoreName').val();
        $.post(
                Url,
                {
                    'StoreId': StoreId,
                    'address': Address,
                    'resname': resName,
                    'distance': distance,
                    'Action': 'showMapEdit'
                },
                function (data) {
                    $('#googleMapShow').html(data);
                    return false;
                }
        );
        return false;
    }
}

var locationRow = (typeof j != 'undefined') ? j : 1;
var searchBy = $('#searchBy').val();
function appendDeliveryLocation() {
    $('.appendDeliveryLocation').append(
            '<div class="form-group" id="removeLocation_' + locationRow + '">' +
            '<div class="col-sm-9 col-sm-offset-3">' +
            '<div class="row">' +
            '<div class="col-sm-2">' +
            '<input type="text" class="form-control" name=data[deliveryLocation][' + locationRow + '][city_name]" id="deliveryCity_' + locationRow + '" onkeyup="getCityName(this.id);" placeholder="City">' +
            '</div>' +
            '<div class="col-sm-2">' +
            '<input type="text" class="form-control deliveryLocationName" name=data[deliveryLocation][' + locationRow + '][location_name]" id="deliveryLocation_' + locationRow + '" onkeyup="getLocationName(this.id, ' + locationRow + ');" placeholder="' + searchBy + '">' +
            '</div>' +
            '<div class="col-sm-2">' +
            '<input type="text" class="form-control" name=data[deliveryLocation][' + locationRow + '][minimum_order]" id="minimumOrder_' + locationRow + '" placeholder="Min order">' +
            '</div>' +
            '<div class="col-sm-2">' +
            '<input type="text" class="form-control" name=data[deliveryLocation][' + locationRow + '][delivery_charge]" id="deliveryCharge_' + locationRow + '" placeholder="Del Charge">' +
            '</div>' +
            '<div class="col-sm-2">' +
            '<a onclick="removeLocation(' + locationRow + ');" class="btn btn-danger">X</a>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>'
            );
    locationRow++;
}

function removeLocation(removeId) {
    $('#removeLocation_' + removeId).remove();
    return false;
}

function getCityName(fieldId) {
    var stateId = $('#StoreStoreState').val();
    if (stateId == '') {
        $("[href=#contact]").trigger('click');
        $("#contactError").html("Sélectionnez le departement svp");
        $("#StoreStoreState").focus();
    } else {
        $.post(
                rp + '/Stores/getCityName',
                {
                    'stateId': stateId
                },
                function (response) {
                    var cityName = response.split(',');
                    $('#' + fieldId).autocomplete({
                        source: cityName,
                    });
                    return false;
                }
        );
        return false;
    }
}

function getLocationName(fieldId, $cityFieldId) {
    var stateId = $('#StoreStoreState').val();
    var cityName = $('#deliveryCity_' + $cityFieldId).val();

    if (stateId == '') {
        $("[href=#contact]").trigger('click');
        $("#contactError").html("Sélectionnez le departement svp");
        $("#StoreStoreState").focus();
        return false;
    } else if (cityName == '') {
        $("#deliveryError").html("Please enter the city");
        $("#deliveryCity_" + $cityFieldId).focus();
        return false;
    } else {
        $.post(
                rp + '/Stores/getLocationName',
                {
                    'stateId': stateId,
                    'cityName': cityName
                },
                function (response) {
                    var LocationName = response.split(',');

                    $('#' + fieldId).autocomplete({
                        source: LocationName,
                        select: function (event, ui) {
                            checkLocationAlreadyExist(fieldId, ui.item.value);
                            return false;
                        }
                    });
                    return false;
                }
        );
        return false;
    }
}

function checkLocationAlreadyExist(fieldId, locationName) {
    var i = 0;
    $('.deliveryLocationName').each(
            function () {
                if (this.value == locationName) {
                    i++;
                }
            }
    );

    if (i > 0) {
        searchBy = $('#searchBy').val();
        $('#' + fieldId).val('');
        $("#deliveryError").html(searchBy + " already exist");
        return false;
    } else {
        $('#' + fieldId).val(locationName);
        $("#deliveryError").html('');
        return false;
    }
}