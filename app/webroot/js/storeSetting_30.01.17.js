function citiesList() {
    var id = $('#StoreStoreState').val();
    $.post(rp + '/admin/stores/locations', {'id': id, 'model': 'City'}, function (response) {
        $("#StoreStoreCity").html(response);
    });
}

function locationList() {
    var id = $('#StoreStoreCity').val();
    $.post(rp + '/admin/stores/locations', {'id': id, 'model': 'Location'}, function (response) {
        $("#StoreStoreZip").html(response);
        $("#DeliveryLocationLocationId").html(response);
    });
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
    emailOptions();
    smsOptions();

    $("input[name='data[Store][email_order]']").click(function () {
        emailOptions();
    });
    $("input[name='data[Store][sms_option]']").click(function () {
        smsOptions();
    });
});

function validateStoreAddEdit() {

    var StoreId             = $('#StoreId').val();
    var StoreContactName    = $.trim($("#StoreContactName").val());
    var StoreContactPhone   = $.trim($("#StoreContactPhone").val());
    var StoreContactEmail   = $.trim($("#StoreContactEmail").val());
    if (addressMode != 'Google') {
        var StoreStreetAddress  = $.trim($("#StoreStreetAddress").val());
        var StoreStoreState     = $.trim($("#StoreStoreState").val());
        var StoreStoreCity      = $.trim($("#StoreStoreCity").val());
        var StoreStoreZip       = $.trim($("#StoreStoreZip").val());
    } else {
        var StoreAddress  = $.trim($("#StoreAddress").val());
    }
    var StoreStoreName      = $.trim($("#StoreStoreName").val());
    var StoreStorePhone     = $.trim($("#StoreStorePhone").val());
    var UserUsername        = $.trim($("#UserUsername").val());
    var UserPassword        = $.trim($("#UserPassword").val());
    var EstimateTime        = $.trim($("#StoreEstimateTime").val());
    if (addressMode == 'Google') {
        var StoreMinimumOrder = $.trim($("#StoreMinimumOrder").val());
        var DeliveryCharge = $.trim($("#StoreDeliveryCharge").val());
        var DeliveryDistance = $.trim($("#StoreDeliveryDistance").val());
    }
    var StoreTax            = $.trim($("#StoreTax").val());
    var StoreOrderEmail     = $.trim($("#StoreOrderEmail").val());
    var StoreSmsPhone       = $.trim($("#StoreSmsPhone").val());
    var StoreCommission     = $.trim($("#StoreCommission").val());
    var invoice_period      = $.trim($("#StoreInvoicePeriod").val());
    var emailRegex          = new RegExp(/^([\w\.\-]+)@([\w\-]+)((\.(\w){2,3})+)$/i);
    
    var StoreDeliveryOptionYes      = $.trim($("#StoreDeliveryOptionYes").val());
    //var DeliveryLocationLocationId  = $.trim($("#DeliveryLocationLocationId").val());

    $("#contactError").html("");
    $("#shopError").html("");
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
    }
    else if (addressMode != 'Google') {
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
    }
    else if (addressMode == 'Google' && StoreAddress == '') {
        $("[href=#contact]").trigger('click');
        $("#contactError").html("Entrez une adresse SVP");
        $("#StoreAddress").focus();
        return false;
    }
    else if (StoreStoreName == '') {
        $("[href=#shop]").trigger('click');
        $("#shopError").html("Entrez le nom du restaurant svp");
        $("#StoreStoreName").focus();
        return false;
    } else if (StoreStorePhone == '') {
        $("[href=#shop]").trigger('click');
        $("#shopError").html("Entrer le numéro de téléphone du restaurant");
        $("#StoreStorePhone").focus();
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
    } else if (EstimateTime == '') {
        $("[href=#delivery]").trigger('click');
        $("#deliveryError").html("Entrez le temps estimé svp");
        $("#StoreEstimateTime").focus();
        return false;
    }
    else if (addressMode == 'Google') {
        if (StoreMinimumOrder == '') {
            $("[href=#delivery]").trigger('click');
            $("#deliveryError").html("Entrez le montant minimum de la commande svp");
            $("#StoreMinimumOrder").focus();
            return false;
        } else if ((isNaN(StoreMinimumOrder))) {
            $("[href=#delivery]").trigger('click');
            $("#deliveryError").html("Please enter valid minimum order");
            $("#StoreMinimumOrder").focus();
            return false;
        } else if (DeliveryCharge == '') {
            $("[href=#delivery]").trigger('click');
            $("#deliveryError").html("Renseignez les frais de livraison svp");
            $("#StoreDeliveryCharge").focus();
            return false;
        } else if ((isNaN(DeliveryCharge)) || DeliveryCharge < 0) {
            $("[href=#delivery]").trigger('click');
            $("#deliveryError").html("Please enter valid delivery charge");
            $("#StoreDeliveryCharge").focus();
            return false;
        } else if (DeliveryDistance == '') {
            $("[href=#delivery]").trigger('click');
            $("#deliveryError").html("Entrez votre distance de livraison svp");
            $("#StoreDeliveryDistance").focus();
            return false;
        } else if ((isNaN(DeliveryDistance))) {
            $("[href=#delivery]").trigger('click');
            $("#deliveryError").html("Please enter valid delivery distance");
            $("#StoreDeliveryDistance").focus();
            return false;
        }
    }

    if ($("#StoreEmailOrderYes").is(":checked")) {
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

function showMap() {

    var StoreId  = $('#StoreId').val();
    var Url = rp+'/AjaxAction';
    var Address = $('#StoreAddress').val();
    var distance  = $('#StoreDeliveryDistance').val();

    if (Address == '') {
        $("[href=#contact]").trigger('click');
        $("#contactError").html("Entrez une adresse SVP");
        $("#StoreAddress").focus();
        return false;
    } else {
        if (!$.isNumeric(StoreId)) {
            $.post(
                Url,
                {
                    'address': Address,
                    'distance':distance,
                    'Action': 'showMapAdd'
                },
                function (data) {
                    $('#googleMapShow').html(data);
                    return false;
                }
            );
            return false;
        } else {
            var resName   = $('#StoreStoreName').val();
            $.post(
                Url,
                {
                    'StoreId':StoreId,
                    'address':Address,
                    'resname':resName,
                    'distance':distance,
                    'Action':'showMapEdit'
                },
                function(data) {
                    $('#googleMapShow').html(data);
                    return false;
                }
            );
            return false;
        }
    }
}

var locationRow = (typeof j != 'undefined') ? j : 1;
var searchBy = $('#searchBy').val();
function appendDeliveryLocation() {
    $('.appendDeliveryLocation').append(
        '<div class="form-group" id="removeLocation_'+locationRow+'">'+
            '<div class="col-sm-9 col-sm-offset-3">'+
                '<div class="row">'+
                    '<div class="col-sm-2">'+
                        '<input type="text" class="form-control" name=data[deliveryLocation]['+locationRow+'][city_name]" id="deliveryCity_'+locationRow+'" onkeyup="getCityName(this.id);" placeholder="City">'+
                    '</div>'+
                    '<div class="col-sm-2">'+
                        '<input type="text" class="form-control deliveryLocationName" name=data[deliveryLocation]['+locationRow+'][location_name]" id="deliveryLocation_'+locationRow+'" onkeyup="getLocationName(this.id, '+locationRow+');" placeholder="'+searchBy+'">'+
                    '</div>'+
                    '<div class="col-sm-2">'+
                        '<input type="text" class="form-control" name=data[deliveryLocation]['+locationRow+'][minimum_order]" id="minimumOrder_'+locationRow+'" placeholder="Min order">'+
                    '</div>'+
                    '<div class="col-sm-2">'+
                        '<input type="text" class="form-control" name=data[deliveryLocation]['+locationRow+'][delivery_charge]" id="deliveryCharge_'+locationRow+'" placeholder="Del Charge">'+
                    '</div>'+
                    '<div class="col-sm-2">'+
                        '<a onclick="removeLocation('+locationRow+');" class="btn btn-danger">X</a>'+
                    '</div>'+
                '</div>'+
            '</div>'+
        '</div>'
    );
    locationRow++;
}

function removeLocation(removeId) {
    $('#removeLocation_'+removeId).remove();
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
            rp+'/Stores/getCityName',
            {
                'stateId' : stateId
            },
            function(response) {
                var cityName = response.split(',');
                $('#'+fieldId).autocomplete({
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
    var cityName = $('#deliveryCity_'+$cityFieldId).val();

    if (stateId == '') {
        $("[href=#contact]").trigger('click');
        $("#contactError").html("Sélectionnez le departement svp");
        $("#StoreStoreState").focus();
        return false;
    } else if (cityName == '') {
        $("#deliveryError").html("Please enter the city");
        $("#deliveryCity_"+$cityFieldId).focus();
        return false;
    } else {
        $.post(
            rp+'/Stores/getLocationName',
            {
                'stateId' : stateId,
                'cityName' : cityName
            },
            function(response) {
                var LocationName = response.split(',');

                $('#'+fieldId).autocomplete({
                    source: LocationName,
                    select : function(event, ui) {
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
        function() {
            if (this.value == locationName) {
                i++;
            }
        }
    );

    if (i > 0) {
        searchBy = $('#searchBy').val();
        $('#'+fieldId).val('');
        $("#deliveryError").html(searchBy+" already exist");
        return false;
    } else {
        $('#'+fieldId).val(locationName);
        $("#deliveryError").html('');
        return false;
    }
}