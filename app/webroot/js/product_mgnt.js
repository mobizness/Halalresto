//Enable and Disable For single and Multiple Option
$(document).ready(function () {

    $("#ProductStoreId").change(function () {
        $("#ProductCategoryId").html("<option value=''>Sélectionnez le Catégorie</option>");
        if ($(this).val() != "") {
            var id = $(this).val();
            $.post(rp + '/Commons/fetchCategories', {'id': id}, function (response) {
                try {
                    var list = "<option value=''>Sélectionnez le Catégorie</option>";
                    var result = JSON.parse(response);
                    $.each(result, function (k, v) {
                        list += "<option value='" + k + "'>" + v + "</option>";
                    });
                    $("#ProductCategoryId").html(list);
                } catch (e) {

                }
            });
        }
    });

    productOptions();
    paymentShow();
    $("input[name='data[Product][price_option]']").click(function () {
        productOptions();
        getAddons();
    });

    $("input[name='data[Sitesetting][stripe_mode]']").click(function () {
        paymentShow();
    });

    $("input[name='data[Sitesetting][paypal_mode]']").click(function () {
        paypalShow();
    });

    getAddons();
});

function paymentSetting(val) {
    if (val == 'Stripe') {
        $('.stripeDiv').show();
        $('.paypalDiv').hide();
    } else {
        $('.paypalDiv').show();
        $('.stripeDiv').hide();
        paypalShow();
    }
}

//Hide Show Process Based On Radio Selection
function productOptions() {
    if ($("#ProductPriceOptionMultiple").is(":checked")) {
        $("#multiple").show();
        $("#single").hide();
    } else {
        $("#multiple").hide();
        $("#single").show();
    }
}

//Status Changes Process 
function statusChange(ids, models) {
    var id = ids;
    var model = models;
    $.post(rp + '/Commons/statusChanges', {'id': id, 'model': model}, function (response) {
        if (models == "Category" || models == "HalalProduct") {
            if (response == "1") {
                $("#status_category" + ids).text("Active");
            } else {
                $("#status_category" + ids).text("Deactivate");
            }
        }
    });
}
//Delete process
function deleteprocess(ids, models) {
    var check = confirm("Etes-vous sûr de vouloir supprimer");
    if ($.trim(check) == 'true') {
        var id = ids;
        var model = models;
        $.post(rp + '/Commons/deleteProcess', {'id': id, 'model': model}, function (response) {
            $("#record" + id).remove();
        });
    }
}


var i = (typeof j != 'undefined') ? j : 1;
var html = '';
var multipleAddon = '';

function multipleOption() {
    var AddonRow = i + 1;

    html = '<div id = "moreProuct' + i + '" class="row addPriceTop multipleMenu">' +
            '<div class="col-lg-7">' +
            '<div class="row">' +
            '<div class="col-md-6">' +
            '<div class="input text">' +
            '<input type="text" id="ProductDetailSubName" data-attr="product name" maxlength="100" placeholder="Menu Name" class="form-control multipleValidate" name="data[ProductDetail][' + i + '][sub_name]">' +
            '</div>' +
            '</div>' +
            '<div class="col-md-3">' +
            '<div class="input number">' +
            '<input type="text" id="ProductDetailOrginalPrice' + i + '" data-attr="original price" step="any" placeholder="Price" class="form-control multipleValidate" name="data[ProductDetail][' + i + '][orginal_price]">' +
            '</div>' +
            '</div>' +
            '<span class="ItemRemove" onclick="removeOption(' + i + ');"><i class="fa fa-times"></i></span>' +
            '</div>' +
            '</div>' +
            '<input type="hidden" id="multiValueArray_' + i + '" class="multiValueArray" value="' + i + '">' +
            '</div>';

    appendMultipleSubAddons(i);
    i++;
    $('#moreOption').append(html);

    html = '';
    return false;
}

function removeOption(id) {
    $('#moreProuct' + id).remove();
    $('.removeAppendAddon_' + id).remove();
}




function optionValidate() {

    var optionMultiple = $("#ProductPriceOptionMultiple").is(":checked");
    var ProductStoreId = $('#ProductStoreId').val();
    var productName = $('#ProductProductName').val();
    var categoryId = $('#ProductCategoryId').val();

    $("#productError").html("");


    if (ProductStoreId == '') {
        $("[href=#contact]").trigger('click');
        $("#productError").html("Sélectionnez le restaurant svp");
        $("#ProductStoreId").focus();
        return false;
    } else if (productName == '') {
        $("[href=#contact]").trigger('click');
        $("#productError").html("Entrez le nom du plat svp");
        $("#productName").focus();
        return false;
    } else if (categoryId == '') {
        $("[href=#contact]").trigger('click');
        $("#productError").html("Sélectionnez le Catégorie svp");
        $("#categoryId").focus();
        return false;
    }

    if (productName != '' && categoryId != '') {
        var error = 0;
        $('.AddError').remove();
        if (optionMultiple) {
            $(".multipleValidate[type = 'text']").each(function () {
                var attrs = $(this).attr('data-attr');
                var id = $(this).attr('id');
                if ($(this).val() == "") {
                    $(this).after('<span class="AddError"> Entrez le prix initial svp</span>');
                    error = 1;
                }
                if (attrs == "original price") {
                    if ($(this).val() < 0 || isNaN($(this).val())) {
                        $(this).after('<span class="AddError"> Entrez le prix initial svp</span>');
                        error = 1;
                    } else if ($(this).val() < 0 || isNaN($(this).val())) {
                        $(this).after('<span class="AddError"> Entrez le prix initial svp</span>');
                        error = 1;
                    }
                }
            });
        } else {
            $(".singleValidate[type = 'text']").each(function () {
                var attrs = $(this).attr('data-attr');
                var originalPrice = parseInt($('#ProductDetailOrginalPrice').val());
                if ($(this).val() == "") {
                    $(this).after('<span class="AddError"> Entrez le prix initial svp</span>');
                    error = 1;
                }
                if (attrs == "original price") {
                    if ($(this).val() < 0 || isNaN($(this).val())) {
                        $(this).after('<span class="AddError"> Entrez un prix valide svp</span>');
                        error = 1;
                    } else if ($(this).val() < 0 || isNaN($(this).val())) {
                        $(this).after('<span class="AddError"> Entrez un prix valide svp</span>');
                        error = 1;
                    }
                }
            });
        }
        if (error == 1) {
            return false;
        }
    }
}

//City Fillter Process
function cityFillters() {
    var id = $('#CustomerAddressBookStateId').val();
    $.post(rp + '/customer/customers/cityfillter', {'id': id}, function (response) {
        $("#CustomerAddressBookCityId").html(response);

    })
}

//Location Fillter Process
function locationFillters() {
    var id = $('#CustomerAddressBookCityId').val();
    $.post(rp + '/customer/customers/locationfillter', {'id': id}, function (response) {
        $("#CustomerAddressBookLocationId").html(response);

    })
}

$(document).ready(function () {
    $(".checktable th input[type='checkbox']").change(function () {
        if ($(this).prop("checked") == true) {
            $(".checktable td input[type='checkbox']").prop("checked", true);
            $(".checktable td input[type='checkbox']").parent().addClass("checked");
            $("#send").show();
        } else {
            $(".checktable td input[type='checkbox']").prop("checked", false);
            $(".checktable td input[type='checkbox']").parent().removeClass("checked");
            $("#send").hide();
        }
    });

    $(".checktable td input[type='checkbox']").change(function () {
        var length = $(".checktable tbody tr td input[type='checkbox']").length;
        var checklength = $(".checktable tbody tr td input[type='checkbox']:checked").length;

        if (length == checklength) {
            $(".checktable th input[type='checkbox']").prop("checked", true);
            $(".checktable th input[type='checkbox']").parent().addClass("checked");
            $("#send").show();
        } else if (checklength > 0) {
            $(".checktable th input[type='checkbox']").prop("checked", false);
            $(".checktable th input[type='checkbox']").parent().removeClass("checked");
            $("#send").show();
        } else {
            $(".checktable th input[type='checkbox']").prop("checked", false);
            $(".checktable th input[type='checkbox']").parent().removeClass("checked");
            $("#send").hide();
        }
    });

    $("#uniform-PaybalPaypal").on("click", function () {
        $(".paypalDiv").show();
        $(".stipeDiv").hide();
    });

    $("#uniform-StripeStripe").on("click", function () {
        $(".stipeDiv").show();
        $(".paypalDiv").hide();
    });

    symbolShow();
    $("input[name='data[Voucher][offer_mode]']").click(function () {
        symbolShow();
    });


});


function validateHalalProducts() {
    var ProductProductName = $("#HalalProductName").val();
    var isPrice = $("input:radio[name='data[HalalProduct][isPaid]']:checked").val();
    if (isPrice == "1") {
        var productPrice = $("#HalalProductPrice").val();
        if ($.trim(productPrice).length == 0 ||  !($.isNumeric(productPrice))) {
            $("#productsAddError").html("Entrez le prix du produit");
            return false;
        }
    } 
    if ($.trim(ProductProductName).length == 0) {
        $("#productsAddError").html("Écrivez le nom du produit");
        return false;
    } 
    return true;
}

function symbolShow() {
    if ($("#VoucherOfferModePrice").is(":checked")) {
        $("#symbols").show();
        $("#currencySymbol").show();
        $("#percentageSymbol").hide();
    } else if ($("#VoucherOfferModePercentage").is(":checked")) {
        $("#symbols").show();
        $("#currencySymbol").hide();
        $("#percentageSymbol").show();
    } else {
        $("#symbols").hide();
    }
}

//Editor Js file
jQuery(document).ready(function () {
    Metronic.init(); // init metronic core components
    Layout.init(); // init current layout
    Demo.init(); // init demo features
    ComponentsEditors.init();

    $('#StoreofferFromDate').datepicker({
        minDate: 0,
        maxDate: "+60D",
        numberOfMonths: 1,
        onSelect: function (selected) {
            $("#StoreofferToDate").datepicker("option", "minDate", selected)
        }
    });

    $('#StoreofferToDate').datepicker({
        minDate: 0,
        maxDate: "+60D",
        numberOfMonths: 1,
        onSelect: function (selected) {
            $("#StoreofferFromDate").datepicker("option", "maxDate", selected)
        }
    });

    $.datepicker.regional['fr'] = {clearText: 'Effacer', clearStatus: '',
        closeText: 'Fermer', closeStatus: 'Fermer sans modifier',
        prevText: '&lt;Préc', prevStatus: 'Voir le mois précédent',
        nextText: 'Suiv&gt;', nextStatus: 'Voir le mois suivant',
        currentText: 'Courant', currentStatus: 'Voir le mois courant',
        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        monthNamesShort: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun',
            'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
        monthStatus: 'Voir un autre mois', yearStatus: 'Voir un autre année',
        weekHeader: 'Sm', weekStatus: '',
        dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
        dayNamesShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
        dayNamesMin: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],
        dayStatus: 'Utiliser DD comme premier jour de la semaine', dateStatus: 'Choisir le DD, MM d',
        dateFormat: 'dd/mm/yy', firstDay: 0,
        initStatus: 'Choisir la date', isRTL: false};
    $.datepicker.setDefaults($.datepicker.regional['fr']);

    $('#DriversFromDate').datepicker({
        maxDate: 0,
        numberOfMonths: 1,
        onSelect: function (selected) {
            $("#DriversToDate").datepicker("option", "minDate", selected)
        }
    });

    $('#DriversToDate').datepicker({
        maxDate: 0,
        numberOfMonths: 1,
        onSelect: function (selected) {
            $("#DriversFromDate").datepicker("option", "maxDate", selected)
        }
    });

    $('#VoucherFromDate').datepicker({
        minDate: 0,
        maxDate: "+60D",
        numberOfMonths: 1,
        onSelect: function (selected) {
            $("#VoucherToDate").datepicker("option", "minDate", selected)
        }
    });

    $('#VoucherToDate').datepicker({
        minDate: 0,
        maxDate: "+60D",
        numberOfMonths: 1,
        onSelect: function (selected) {
            $("#VoucherFromDate").datepicker("option", "maxDate", selected)
        }
    });

    $("input:radio[name='data[HalalProduct][isPaid]']").change(function () {
        var isPaid = $("input:radio[name='data[HalalProduct][isPaid]']:checked").val();
        if (isPaid == "1") {
            $("#single").show();
        } else {
            $("#single").hide();
        }
    });
});

function productList() {
    var id = $('#DealStoreId').val();
    $.post(rp + '/admin/Deals/productList', {'id': id, 'model': 'mainProduct'}, function (response) {
        $("#DealMainProduct").html(response);
    });

    $.post(rp + '/admin/Deals/productList', {'id': id, 'model': 'subProduct'}, function (response) {
        $("#DealSubProduct").html(response);
    });
}

function orderStatus(orderId) {

    var status = $('#orderStatus_' + orderId).val();
    var type = $('#orderType_' + orderId).val();

    if (status != 'Failed' && status != 'Pending' && status != '') {
        $('.ui-loadercont').show();
        $.post(rp + '/admin/orders/orderStatus', {'orderId': orderId, 'status': status}, function (response) {
            $('.ui-loadercont').hide();
            $('#orderList_' + orderId).remove();
            var message = 'Cette commande passe au statut livré';
            if (status != 'Delivered') {
                // message = 'This order moves to ';
                message += (type == 'Delivery') ? 'Cette commande passe au service d’expédition' : 'Cette commande passe à la gestion des commandes à emporter';
            }

            $('#orderMessage').html(message);
            $('#orderMessage').show();
            setTimeout(function () {
                $('#orderMessage').fadeOut();
            }, 3000);
        });
    } else if (status == 'Failed') {
        html = '<textarea class="form-control margin-t-10 margin-b-10" id="failedReason_' + orderId + '" rows="4" cols="10"></textarea>' +
                '<input type="button" value="Envoyer" class="btn btn-default" onclick="return changeOrderStatus(' + orderId + ');">';
        $("#reason_" + orderId).append(html);
    } else {
        $("#reason_" + orderId).html('');
    }
}


function changeOrderStatus(orderId) {
    var reason = $('#failedReason_' + orderId).val();
    if (reason != '') {
        $('.ui-loadercont').show();
        $.post(rp + '/admin/orders/orderStatus', {'orderId': orderId, 'status': 'Failed', 'reason': reason}, function (response) {
            $('.ui-loadercont').hide();
            $('#orderList_' + orderId).remove();
            $('#orderMessage').html('Le statut de la commande a été changé avec succès');
            $('#orderMessage').show();
            setTimeout(function () {
                $('#orderMessage').fadeOut();
            }, 3000);
        });
    } else {
        alert("Veuillez indiquer la raison de l'échec de la commande");
    }
}

function deleteOrder(orderId) {
    var line = 'Are you sure want to delete order ?';
    if (confirm(line)) {
        $.post(rp + '/admin/orders/orderStatus', {'orderId': orderId, 'status': 'Deleted'}, function (response) {
            // $('#orderList_'+orderId).remove();
        });
    }
}

function deleteSubscribedUser(uid, action) {
    if (action == "delete") {
        var line = 'Are you sure want to delete this user from the newsletter subscription list ?';
        if (confirm(line)) {
            $.post(rp + '/Commons/deleteSubscribedUser', {'id': uid, action: "delete"}, function (response) {
                location.reload();
            });
        }
    }
}

function deleteContentWriter(cwId, action) {
    if (action == "delete") {
        var line = 'Are you sure want to delete this user ?';
        if (confirm(line)) {
            $.post(rp + '/Commons/deleteContentWriter', {'id': cwId, action: "delete"}, function (response) {
                location.reload();
            });
        }
    }
}

function deleteDeliveryInfo(deliveryId, status, action) {
    if (action == "delete") {
        var line = 'Are you sure want to delete this delivery information ?';
        if (confirm(line)) {
            $.post(rp + '/Commons/deliverInfoStatusChange', {'id': deliveryId, 'status': status, action: "delete"}, function (response) {
                location.reload();
            });
        }
    } else {
        $.post(rp + '/Commons/deliverInfoStatusChange', {'id': deliveryId, 'status': status, action: "update"}, function (response) {
            var res = response.split("|");
            if ($.trim(res[1]) == "1") {
                $("#deliverystatus_" + deliveryId).html("Active");
            } else {
                $("#deliverystatus_" + deliveryId).html("Deactivate");
            }
        });

    }
}

function Fillter() {
    var id = $('#store_id').val();
    window.location.href = rp + '/admin/Reviews/list/' + id;
    return false;

}
function validateEmail(email)
{
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}

function validateNewsLetter() {
    var NewsletterSubject = $.trim($("#NewsletterSubject").val());
    var NewsletterBody = $.trim($("#NewsletterBody").val());

    if (NewsletterBody.length != 0 && NewsletterSubject.length != 0) {
        $("#productError").html("");
        return true;
    } else {
        $("#productError").html("Remplissez le sujet et le corps pour envoyer un bulletin d'information");
        return false;
    }
}
function validateContentWriter() {
    var UserUsername = $.trim($("#UserUsername").val());
    var UserPassword = $.trim($("#UserPassword").val());

    if (validateEmail(UserUsername) && UserPassword.length >= 5) {
        $("#productError").html("");
        return true;
    } else {
        $("#productError").html("Assurez-vous d'avoir saisi une adresse électronique valide, et la longueur de votre mot de passe devrait comporter au minimum 5 caractères");
        return false;
    }
}


$(document).ready(function () {
//    $("#UserAdminAddcwForm").submit(function (event) {
//        event.preventDefault();
//        var UserUsername = $.trim($("#UserUsername").val());
//        var UserPassword = $.trim($("#UserPassword").val());
//
//        if (validateEmail(UserUsername) && UserPassword.length >= 5) {
//            $("#productError").html("");
//            $("#UserAdminAddcwForm").submit();
//        } else {
//            $("#productError").html("Assurez-vous d'avoir saisi une adresse électronique valide, et la longueur de votre mot de passe devrait comporter au minimum 5 caractères");
//        }
//
//    });

    $(".allcheck").on("click", function () {
        var id = $(this).attr('id');
        var classVal = $(this).attr('class');
        if ($('#' + id).children(".btn").hasClass("btn-success")) {
            $('#' + id).children(".btn").removeClass("btn-success").addClass("grey-cascade");
            $('#' + id).children().children(".glyphicon").removeClass("glyphicon-ok").addClass("glyphicon-remove");
        } else {
            $('#' + id).children(".btn").removeClass("grey-cascade").addClass("btn-success");
            $('#' + id).children().children(".glyphicon").removeClass("glyphicon-remove").addClass("glyphicon-ok");
        }
    });
});

function recorddelete(obj) {

    var line = 'Are you sure want to ' + obj.value + '?';
    if (confirm(line)) {
        window.location.href = rp + '/admin/Commons/multipleSelect';
    } else {
        return false;
    }
}

function paymentShow() {
    if ($("#SitesettingStripeModeLive").is(":checked")) {
        $("#Live").show();
        $("#Test").hide();
    } else {
        $("#Live").hide();
        $("#Test").show();
    }
}

function paypalShow() {
    if ($("#SitesettingPaypalModeLive").is(":checked")) {
        $("#payLive").show();
        $("#payTest").hide();
    } else {
        $("#payLive").hide();
        $("#payTest").show();
    }
}

function paymentSettingvalidate() {

    var SitesettingStripeSecretkey = $.trim($('#SitesettingStripeSecretkey').val());
    var SitesettingStripePublishkey = $.trim($('#SitesettingStripePublishkey').val());
    var SitesettingStripeSecretkeyTest = $.trim($('#SitesettingStripeSecretkeyTest').val());
    var SitesettingStripePublishkeyTest = $.trim($('#SitesettingStripePublishkeyTest').val());

    var SitesettingPaypalLiveUsername = $.trim($('#SitesettingPaypalLiveUsername').val());
    var SitesettingPaypalLivePassword = $.trim($('#SitesettingPaypalLivePassword').val());
    var SitesettingPaypalLiveSignature = $.trim($('#SitesettingPaypalLiveSignature').val());
    var SitesettingPaypalTestUsername = $.trim($('#SitesettingPaypalTestUsername').val());
    var SitesettingPaypalTestPassword = $.trim($('#SitesettingPaypalTestPassword').val());
    var SitesettingPaypalTestSignature = $.trim($('#SitesettingPaypalTestSignature').val());


    $("#paymentError").html("");

    if ($("#StripeStripe").is(":checked")) {
        if ($("#SitesettingStripeModeLive").is(":checked")) {

            if (SitesettingStripeSecretkey == '') {
                $("#paymentError").html("Please enter stripe secret key");
                $("#SitesettingStripeSecretkey").focus();
                return false;
            } else if (SitesettingStripePublishkey == '') {
                $("#paymentError").html("Please enter stripe publish key");
                $("#SitesettingStripePublishkey").focus();
                return false;
            }
        } else if ($("#SitesettingStripeModeTest").is(":checked")) {

            if (SitesettingStripeSecretkeyTest == '') {
                $("#paymentError").html("Please enter stripe secret key");
                $("#SitesettingStripeSecretkeyTest").focus();
                return false;
            } else if (SitesettingStripePublishkeyTest == '') {
                $("#paymentError").html("Please enter stripe publish key");
                $("#SitesettingStripePublishkeyTest").focus();
                return false;
            }
        }
    } else {
        if ($("#SitesettingPaypalModeLive").is(":checked")) {

            if (SitesettingPaypalLiveUsername == '') {
                $("#paymentError").html("Please enter username");
                $("#SitesettingPaypalLiveUsername").focus();
                return false;
            } else if (SitesettingPaypalLivePassword == '') {
                $("#paymentError").html("Please enter password");
                $("#SitesettingPaypalLivePassword").focus();
                return false;
            } else if (SitesettingPaypalLiveSignature == '') {
                $("#paymentError").html("Please enter signature");
                $("#SitesettingPaypalLiveSignature").focus();
                return false;
            }

        } else if ($("#SitesettingPaypalModeTest").is(":checked")) {

            if (SitesettingPaypalTestUsername == '') {
                $("#paymentError").html("Please enter username");
                $("#SitesettingPaypalTestUsername").focus();
                return false;
            } else if (SitesettingPaypalTestPassword == '') {
                $("#paymentError").html("Please enter password");
                $("#SitesettingPaypalTestPassword").focus();
                return false;
            } else if (SitesettingPaypalTestSignature == '') {
                $("#paymentError").html("Please enter signature");
                $("#SitesettingPaypalTestSignature").focus();
                return false;
            }
        }
    }

}

function deleteProductImage(deleteId) {

    $.post(rp + '/products/deleteProductImage/', {'id': deleteId}, function (response) {
        if (response == 'success') {
            $('#image' + deleteId).remove();
        }
    });
}

var subRow = (typeof j != 'undefined') ? j : 1;
function addSubAddons() {
    $('#subAddonsList').append(
            '<div class="form-group" id="removeSubaddon_' + subRow + '">' +
            '<div class="col-md-6 col-lg-3 col-md-offset-3">' +
            '<input type="text" class="form-control" name="data[Mainaddon][Subaddon][' + subRow + '][subaddons_name]" placeholder="Sous article Nom" >' +
            '</div>' +
            '<div class="col-md-6 col-lg-2">' +
            '<input type="text" class="form-control" name="data[Mainaddon][Subaddon][' + subRow + '][subaddons_price]" placeholder="Prix">' +
            '</div>' +
            '<div class="col-md-6 col-lg-2">' +
            '<a href="javascript:;" onclick="removeSubAddons(' + subRow + ');" class="btn btn-danger">X</a>' +
            '</div>' +
            '</div>'
            );
    subRow++;
}

var mainRow = 1;
function addMainAddons() {
    $('#mainaddonsList').append(
            '<div id="removeMainMore_' + mainRow + '">' +
            '<div class="form-group">' +
            '<label class="col-md-2 control-label">Addons Name <span class="star">*</span></label>' +
            '<div class="col-md-6 col-lg-3">' +
            '<input type="text" class="form-control" name="data[Mainaddon][' + mainRow + '][mainaddons_name]" placeholder="Mainaddon Name">' +
            '</div>' +
            '<div class="col-md-6 col-lg-2">' +
            '<input type="text" class="form-control" name="data[Mainaddon][' + mainRow + '][mainaddons_count]" placeholder="Count">' +
            '</div>' +
            '<div class="col-md-6 col-lg-2">' +
            '<a href="javascript:;" class="btn btn-success" onclick="mainSubAddons(' + mainRow + ');">Add Sub Addons</a>' +
            '</div>' +
            '<div class="col-md-6 col-lg-3">' +
            '<a href="javascript:;" onclick="removeMainAddons(' + mainRow + ');" class="btn btn-danger">X</a>' +
            '</div>' +
            '</div>' +
            '<div id="mainsubAddons_' + mainRow + '"></div>' +
            '</div>'
            );
    mainRow++;
}

var mainSubRow = 1;
function mainSubAddons(mainAddonId) {
    $('#mainsubAddons_' + mainAddonId).append(
            '<div class="form-group" id="removeMainSub_' + mainSubRow + '">' +
            '<div class="col-md-6 col-lg-3 col-md-offset-2">' +
            '<input type="text" class="form-control" name="data[Mainaddon][' + mainAddonId + '][Subaddon][' + mainSubRow + '][subaddons_name]" placeholder="Sous article Nom">' +
            '</div>' +
            '<div class="col-md-6 col-lg-2">' +
            '<input type="text" class="form-control" name="data[Mainaddon][' + mainAddonId + '][Subaddon][' + mainSubRow + '][subaddons_price]" placeholder="Prix" >' +
            '</div>' +
            '<div class="col-md-6 col-lg-2">' +
            '<a href="javascript:;" onclick="removeSubAddonsMore(' + mainSubRow + ');" class="btn btn-danger">X</a>' +
            '</div>' +
            '</div>'
            );
    mainSubRow++;
}

function removeMainAddons(id) {
    $('#removeMainMore_' + id).remove();
    return false;
}

function removeSubAddons(id) {
    $('#removeSubaddon_' + id).remove();
    return false;
}

function removeSubAddonsEdit(id, key) {
    var URL = rp + '/Addons/removeSubAddons';
    $.post(
            URL,
            {
                'id': id,
                'action': 'removeSubaddon'
            },
            function (response) {
                if ($.trim(response) == 'Success') {
                    $('#removeSubaddon_' + key).remove();
                    return false;
                } else {
                    alert('Not removed');
                    return false;
                }
            }
    );
    return false;
}

function removeSubAddonsMore(id) {
    $('#removeMainSub_' + id).remove();
    return false;
}

function addonsValidate() {
    var addonId = $('#MainaddonId').val();
    var storeId = $('#MainaddonStoreId').val();
    var categoryId = $('#MainaddonCategoryId').val();
    var mainAddonName = $('#MainaddonMainaddonsName').val();
    var mainAddonCount = $('#MainaddonMainaddonsCount').val();
    var SubAddonName = $('#SubAddonName').val();
    var SubAddonPrice = $('#SubAddonPrice').val();

    var URL = rp + '/Addons/checkAddonExist';

    if (storeId == '') {
        $('#addonError').html('Sélectionnez le restaurant svp');
        $('#MainaddonStoreId').focus();
        return false;
    } else if (categoryId == '') {
        $('#addonError').html('Sélectionnez le Catégorie svp');
        $('#MainaddonCategoryId').focus();
        return false;
    } else if (mainAddonName == '') {
        $('#addonError').html('Entrez le nom de l’article svp');
        $('#MainaddonMainaddonsName').focus();
        return false;
    } else if (mainAddonCount == '') {
        $('#addonError').html('Entrez le nombre d’articles svp');
        $('#MainaddonMainaddonsCount').focus();
        return false;
    } else if (isNaN(mainAddonCount) || mainAddonCount <= 0) {
        $('#addonError').html('Veuillez entrer un chiffre valide');
        $('#MainaddonMainaddonsCount').focus();
        return false;
    } else if (SubAddonName == '') {
        $('#addonError').html('Entrez le nom du sous article svp');
        $('#SubAddonName').focus();
        return false;
    } else if (isNaN(SubAddonPrice) || SubAddonPrice <= 0) {
        $('#addonError').html('Entrez un prix valide svp');
        $('#SubAddonPrice').focus();
        return false;
    } else {
        $.post(
                URL,
                {
                    'storeId': storeId,
                    'categoryId': categoryId,
                    'mainAddonName': mainAddonName,
                    'mainAddonid': addonId,
                    'action': 'checkAddonExist'
                },
                function (response) {
                    if ($.trim(response) == 'Exist') {
                        $('#addonError').html('Addon Name already exist');
                        $('#MainaddonMainaddonsName').focus();
                        return false;
                    } else {
                        if (addonId == '') {
                            $('#AddonsAdminAddForm').submit();
                        } else {
                            $('#AddonsAdminEditForm').submit();
                        }
                        return false;
                    }
                }
        );
        return false;
    }
}

function storeAddons() {
    var id = $('#StoreAddon').val();
    if (id != '') {
        window.location.href = rp + '/admin/addons/index/' + id;
    } else {
        $("#storeAddonError").html("Please select store");
    }
    return false;
}

function getAddons() {
    var addonsCheck = $('#ProductProductAddonsYes').is(':checked');
    var addons = (addonsCheck == true) ? 'Yes' : '';
    showAddons(addons);
}

function showAddons(addons) {

    var ProductId = $('#ProductId').val();
    var storeId = $('#ProductStoreId').val();
    var categoryId = $('#ProductCategoryId').val();
    var price = $('#ProductPriceOptionSingle').is(':checked');
    var priceOption = (price == true) ? 'single' : 'multiple';

    var productName = $('#ProductProductName').val();

    var URL = rp + '/AjaxAction/index';

    $('.error').html('');
    $("#productError").html("");

    if (storeId == '') {
        $('#productError').html('Sélectionnez le Catégorie svp');
        $('#ProductStoreId').focus();
        return false;
    } else if (productName == '') {
        $('#productError').html('Entrez le nom du plat svp');
        $('#productName').focus();
        return false;
    } else if (categoryId == '') {
        $('#productError').html('Sélectionnez le Catégorie svp');
        $('#ProductCategoryId').focus();
        return false;
    }

    if (addons == 'Yes') {
        var $menuLength = '';
        if (priceOption == 'multiple') {
            $menuLength = $('.addPriceTop').length;
        }
        $('#getShowAddons').load(
                URL,
                {
                    'productId': ProductId,
                    'storeId': storeId,
                    'categoryId': categoryId,
                    'priceOption': priceOption,
                    'menuLength': $menuLength,
                    'Action': 'getShowAddons'
                },
                function (response) {
                    if (priceOption == 'multiple') {
                        $('.multiValueArray').each(function () {
                            appendMultipleSubAddons(this.value);
                        });
                    }
                    $('#getShowAddons').show();
                    return false;
                }
        );
        return false;
    } else {
        $('#getShowAddons').hide();
        return false;
    }
}

function appendMultipleSubAddons(removeId) {

    var multipleLength = $('.multipleMenu').length;
    var i = 1;

    $('.productAddonsMenu').each(function () {
        var subaddonName = $(this).attr('id');
        multipleAddon = '<div class="col-md-3 col-lg-2 removeAppendAddon_' + removeId + '">' +
                '<input class="form-control singleValidate" type="text" name="' + subaddonName + '[]">' +
                '</div>';
        $('#appendMultiplePrice_' + i).append(multipleAddon);
        i++;
    });
}

function voucherAddEdit() {

    var VoucherStoreId = $('#VoucherStoreId').val();
    var VoucherVoucherCode = $('#VoucherVoucherCode').val();
    var VoucherFromDate = $('#VoucherFromDate').val();
    var VoucherToDate = $('#VoucherToDate').val();
    //var VoucherType 		= $('#VoucherTypeOfferSingle').val();
    var VoucherOfferValue = $('#VoucherOfferValue').val();
    var freeDelivery = $("#VoucherOfferModeFreeDelivery").is(":checked");

    $('#voucherError').html('');

    if (VoucherStoreId == '') {
        $("[href=#contact]").trigger('click');
        $("#voucherError").html("Sélectionnez le restaurant svp");
        $("#VoucherStoreId").focus();
        return false;
    } else if (VoucherVoucherCode == '') {
        $("[href=#contact]").trigger('click');
        $("#voucherError").html("Entrez le promo code svp");
        $("#VoucherVoucherCode").focus();
        return false;
    }
    if (!freeDelivery) {
        if (VoucherOfferValue == '') {
            $("[href=#contact]").trigger('click');
            $("#voucherError").html("Veuillez entrer une valeur");
            $("#VoucherOfferValue").focus();
            return false;
        } else if (isNaN(VoucherOfferValue) || VoucherOfferValue <= 0) {
            $("[href=#contact]").trigger('click');
            $("#voucherError").html("Veuillez entrer une valeur valide");
            $("#VoucherOfferValue").focus();
            return false;
        }
    }

    if (VoucherFromDate == '') {
        $("[href=#contact]").trigger('click');
        $("#voucherError").html("Sélectionner la date de début de validité du voucher svp");
        $("#VoucherFromDate").focus();
        return false;
    } else if (VoucherToDate == '') {
        $("[href=#contact]").trigger('click');
        $("#voucherError").html("Sélectionner la date de fin de validité du voucher svp");
        $("#VoucherToDate").focus();
        return false;
    }
}

function closemask() {
    $('.checkbox input:checked').each(function () {
        $(this).parents().children(".closed_mask").addClass("closed");
    });
    $('.checkbox input:unchecked').each(function () {
        $(this).parents().children(".closed_mask").removeClass("closed");
    });
}
;

$(document).ready(function () {
    closemask();
});