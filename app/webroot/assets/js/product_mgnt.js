
function checkOrderCount() {
    var store = "store";
    $.post(rp + '/Commons/countNewOrders', {'id': store}, function (response) {
        if (response > 0) {
            if (!($("#commandes_store").hasClass("blink_me"))) {
                $("#commandes_store").addClass("blink_me");
            }
        } else {
            if ($("#commandes_store").hasClass("blink_me")) {
                $("#commandes_store").removeClass("blink_me");
            }
        }
    });
}

function meat_issuer_name_change() {
    if ($("#StoreMeatIssuerName").val() == "OTHER") {
        $("#meat_issuer_name_other").show();
    } else {
        $("#meat_issuer_name_other").hide();
    }
}
jQuery().ready(function () {

    meat_issuer_name_change();
    $("#StoreMeatIssuerName").change(function () {
        meat_issuer_name_change();
    });

    $('.meat_radio').change(function () {
        if (this.value == 'Yes') {
            $("#meat_image").show();
            $("#meat_issuer_name").show();
            $("#meat-description").hide();
        } else if (this.value == 'No') {
            $("#meat_image").hide();
            $("#meat_issuer_name").hide();
            $("#meat-description").show();
        }
    });


    checkOrderCount();

    setTimeout(function () {
        checkOrderCount();
    }, 2000);


    setTimeout(function () {
        $("#flashMessage").hide();
    }, 3000);

    var ProductStoreIndexForm = jQuery("#ProductStoreIndexForm").validate({
        rules: {
            "data[excel]": {
                required: true,
                type: 'xls'
            }
        },
        messages: {
            "data[excel]": {
                required: "Please select xls file",
            }
        }
    });

    var CategoryAddvalidator = jQuery("#CategoryStoreAddForm").validate({
        rules: {
            "data[Category][category_name]": {
                required: true,
            }
        },
        messages: {
            "data[Category][category_name]": {
                required: "Entrez le nom du Catégorie svp",
            }

        }
    });

    var CategoryEditvalidator = jQuery("#CategoryStoreEditForm").validate({
        rules: {
            "data[Category][category_name]": {
                required: true,
            }
        },
        messages: {
            "data[Category][category_name]": {
                required: "Entrez le nom du Catégorie svp",
            }

        }
    });

    var CuisineAddvalidator = jQuery("#CuisineStoreAddForm").validate({
        rules: {
            "data[Cuisine][cuisine_name]": {
                required: true,
            }
        },
        messages: {
            "data[Cuisine][cuisine_name]": {
                required: "Please Enter the Cuisine_name Name",
            }

        }
    });


    var CuisineEditvalidator = jQuery("#CuisineStoreEditForm").validate({
        rules: {
            "data[Cuisine][cuisine_name]": {
                required: true,
            }
        },
        messages: {
            "data[Cuisine][cuisine_name]": {
                required: "Please Enter the Cuisine_name Name",
            }

        }
    });

    var ProductAddvalidator = jQuery("#ProductStoreAddForm").validate({
        rules: {
            "data[Product][product_name]": {
                required: true,
            },
            "data[Product][category_id]": {
                required: true,
            },
            "data[product_image][]": {
                required: true,
            },
        },
        messages: {
            "data[Product][product_name]": {
                required: "Entrez le nom du produit svp",
            },
            "data[Product][category_id]": {
                required: "Sélectionnez le Catégorie svp",
            },
            "data[product_image][]": {
                required: "Please Select image",
            },
        }
    });

    var ProductEditvalidator = jQuery("#ProductStoreEditForm").validate({
        rules: {
            "data[Product][product_name]": {
                required: true,
            },
            "data[Product][category_id]": {
                required: true,
            },
            "data[product_image][]": {
                required: true,
            },
        },
        messages: {
            "data[Product][product_name]": {
                required: "Entrez le nom du produit svp",
            },
            "data[Product][category_id]": {
                required: "Sélectionnez le restaurant svp",
            },
            "data[product_image][]": {
                required: "Please Select image",
            },
        }
    });

    var StoreOfferAddvalidator = jQuery("#StoreofferStoreAddForm").validate({
        rules: {
            "data[Storeoffer][store_id]": {
                required: true,
            },
            "data[Storeoffer][offer_percentage]": {
                required: true,
                number: true,
                min: 1,
                max: 99,
            },
            "data[Storeoffer][offer_price]": {
                required: true,
                number: true,
                min: 1,
            },
            "data[Storeoffer][from_date]": {
                required: true,
            },
            "data[Storeoffer][to_date]": {
                required: true,
            }

        },
        messages: {
            "data[Storeoffer][store_id]": {
                required: "Please select restaurant Name",
            },
            "data[Storeoffer][offer_percentage]": {
                required: "Entrez le % de réduction svp",
            },
            "data[Storeoffer][offer_price]": {
                required: "Veuillez entrer le prix de vente",
            },
            "data[Storeoffer][from_date]": {
                required: "Entrez la date de début svp",
            },
            "data[Storeoffer][to_date]": {
                required: "Entrez la date de fin svp",
            }
        }

    });


    var StoreOfferEditvalidator = jQuery("#form-storeofferEdit").validate({
        rules: {
            "data[Storeoffer][store_id]": {
                required: true,
            },
            "data[Storeoffer][offer_percentage]": {
                required: true,
                number: true,
                min: 1,
                max: 99,
            },
            "data[Storeoffer][offer_price]": {
                required: true,
                number: true,
                min: 1,
            },
            "data[Storeoffer][from_date]": {
                required: true,
            },
            "data[Storeoffer][to_date]": {
                required: true,
            }

        },
        messages: {
            "data[Storeoffer][store_id]": {
                required: "Please select restaurant Name",
            },
            "data[Storeoffer][offer_percentage]": {
                required: "Entrez le % de réduction svp",
            },
            "data[Storeoffer][offer_price]": {
                required: "Veuillez entrer le prix de vente",
            },
            "data[Storeoffer][from_date]": {
                required: "Entrez la date de début svp",
            },
            "data[Storeoffer][to_date]": {
                required: "Entrez la date de fin svp",
            }
        }

    });

    var dealAddValidator = jQuery("#DealStoreAddForm").validate({
        rules: {
            "data[Deal][store_id]": {
                required: true,
            },
            "data[Deal][deal_name]": {
                required: true,
            },
            "data[Deal][main_product]": {
                required: true,
            },
            "data[Deal][sub_product]": {
                required: true,
            }

        },
        messages: {
            "data[Deal][store_id]": {
                required: "Please select restaurant",
            },
            "data[Deal][deal_name]": {
                required: "Entrez le deal nom svp",
            },
            "data[Deal][main_product]": {
                required: "Entrez le nom du produit svp",
            },
            "data[Deal][sub_product]": {
                required: "Entrez le nom du produit svp",
            }
        }

    });

    var dealAddValidator = jQuery("#DealStoreEditForm").validate({
        rules: {
            "data[Deal][store_id]": {
                required: true,
            },
            "data[Deal][deal_name]": {
                required: true,
            },
            "data[Deal][main_product]": {
                required: true,
            },
            "data[Deal][sub_product]": {
                required: true,
            }

        },
        messages: {
            "data[Deal][store_id]": {
                required: "Please select restaurant",
            },
            "data[Deal][deal_name]": {
                required: "Entrez le deal nom svp",
            },
            "data[Deal][main_product]": {
                required: "Entrez le nom du produit svp",
            },
            "data[Deal][sub_product]": {
                required: "Entrez le nom du produit svp",
            }
        }

    });


    var changepasswordValidator = jQuery("#userStoreChangePasswordForm").validate({
        rules: {
            "data[user][new_pass]": {
                required: true,
            },
            "data[user][confirm_pass]": {
                required: true,
                equalTo: '#userNewPass',
            }

        },
        messages: {
            "data[user][new_pass]": {
                required: "Entrez le mot de passe",
            },
            "data[user][confirm_pass]": {
                required: "Confirmez votre mot de passe svp",
            }
        }

    });

});

$(document).ready(function () {
    slotCheck();
    productOptions();
    $(".checktable .itemHead input[type='checkbox']").change(function () {
        if ($(this).prop("checked") == true) {
            $(".checktable .itemCont input[type='checkbox']").prop("checked", true);
            $(".checktable .itemCont input[type='checkbox']").parent().addClass("checked");
        } else {
            $(".checktable .itemCont input[type='checkbox']").prop("checked", false);
            $(".checktable .itemCont input[type='checkbox']").parent().removeClass("checked");
        }
    });

    $(".checktable .itemCont input[type='checkbox']").change(function () {
        slotCheck();
    });

    //Enable and Diable For single and Multiple Option
    $("input[name='data[Product][price_option]']").click(function () {
        productOptions();
        getAddons();
    });
    getAddons();
    symbolShow();
    $("input[name='data[Voucher][offer_mode]']").click(function () {
        symbolShow();
    });


});

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


function slotCheck() {
    var length = $(".checktable .itemCont input[type='checkbox']").length;
    var checklength = $(".checktable .itemCont input[type='checkbox']:checked").length;
    if (length == checklength) {
        $(".checktable .itemHead input[type='checkbox']").prop("checked", true);
        $(".checktable .itemHead input[type='checkbox']").parent().addClass("checked");
    } else {
        $(".checktable .itemHead input[type='checkbox']").prop("checked", false);
        $(".checktable .itemHead input[type='checkbox']").parent().removeClass("checked");
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
//original price and compare price checking
function valueCheck() {
    var original_price = $('#ProductDetailOrginalPrice').val();
    var compare_price = $('#ProductDetailComparePrice').val();
    if (parseInt(compare_price) > parseInt(original_price)) {
        alert("The Compare Price Should Be Lesserthen or Equal To  The Original Price");
        $('#ProductDetailComparePrice').val();
    }
}
//Status Changes Process 
function statusChange(ids, models) {
    var id = ids;
    var model = models;
    $.post(rp + '/Commons/statusChanges', {'id': id, 'model': model}, function (response) {
        if (models == "Category" || models == "Product") {
            if (getUrlParameter("status") == "0" || getUrlParameter("status") == "1") {
                location.reload();
            }
        }
        if (models == "Category") {
            location.reload();
        } else if (models == "Product") {
            if (response == "1") {
                $("#status_ad" + ids).text("Active");
            } else {
                $("#status_ad" + ids).text("Deactivate");
            }
        } else if (model == "Deal") {
            if (response == "1") {
                $("#status_ad" + ids).text("Active");
            } else {
                $("#status_ad" + ids).text("Deactivate");
            }
        } else if (model == "Storeoffer") {
            if (response == "1") {
                $("#status_ad" + ids).text("Active");
            } else {
                $("#status_ad" + ids).text("Deactivate");
            }
        } else if (model == "Voucher") {
            if (response == "1") {
                $("#status_ad" + ids).text("Active");
            } else {
                $("#status_ad" + ids).text("Deactivate");
            }
        }

    });
}

//get url parameter
var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};

//Delete process
function deleteprocess(ids, models) {
    var check = confirm("Etes-vous sûr de vouloir supprimer");
    if ($.trim(check) == 'true') {
        var id = ids;
        var model = models;
        $.post(rp + '/Commons/deleteProcess', {'id': id, 'model': model}, function (response) {
        });
        $("#record" + id).remove();
    }
}


var i = (typeof j != 'undefined') ? j : 1;
var html = '';

function multipleOption() {

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

function optionValidate(argument) {
    var optionMultiple = $("#ProductPriceOptionMultiple").is(":checked");

    var name = $('#ProductProductName').val();
    var cat = $('#ProductCategoryId').val();

    if (name != '' && cat != '') {

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
                        $(this).after('<span class="AddError"> Entrez un prix valide svp</span>');
                        error = 1;

                    } else if ($(this).val() < 0 || isNaN($(this).val())) {
                        $(this).after('<span class="AddError"> Entrez un prix valide svp</span>');
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

var k = 0;
function multipleimage() {
    k++;
    $("#multipleImage").append('<div id="Image' + k + '" class="margin-t-10"><input  class="inline-block" type="file" id="ProductProductImage' + k + '"  name="data[ProductImage][]" >' +
            '<a  class="inline-block" href="javascript:void(0);" onclick="return deleteImage(' + k + ');">Delete</a></div>');
}

function deleteImage(id) {
    $('#Image' + id).remove();
}

function deleteProductImage(deleteId) {

    $.post(rp + '/products/deleteProductImage/', {'id': deleteId}, function (response) {
        if (response == 'success') {
            $('#image' + deleteId).remove();
        }
    });
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

        } else {
            $(".checktable td input[type='checkbox']").prop("checked", false);
            $(".checktable td input[type='checkbox']").parent().removeClass("checked");
        }
    });

    $(".checktable td input[type='checkbox']").change(function () {
        var length = $(".checktable tbody tr td input[type='checkbox']").length;
        var checklength = $(".checktable tbody tr td input[type='checkbox']:checked").length;
        if (length == checklength) {
            $(".checktable th input[type='checkbox']").prop("checked", true);
            $(".checktable th input[type='checkbox']").parent().addClass("checked");
        } else {
            $(".checktable th input[type='checkbox']").prop("checked", false);
            $(".checktable th input[type='checkbox']").parent().removeClass("checked");
        }
    });

    $('#StoreofferFromDate').datepicker({
        minDate: 0,
        maxDate: "+60D",
        numberOfMonths: 1,
        onSelect: function (selected) {
            $("#StoreofferToDate").datepicker("option", "minDate", selected)
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

    $('#StoreofferToDate').datepicker({
        minDate: 0,
        maxDate: "+60D",
        numberOfMonths: 1,
        onSelect: function (selected) {
            $("#StoreofferFromDate").datepicker("option", "maxDate", selected);
        }
    });

    $('#VoucherFromDate').datepicker({
        minDate: 0,
        maxDate: "+60D",
        numberOfMonths: 1,
        onSelect: function (selected) {
            $("#VoucherToDate").datepicker("option", "minDate", selected);
        }
    });

    $('#VoucherToDate').datepicker({
        minDate: 0,
        maxDate: "+60D",
        numberOfMonths: 1,
        onSelect: function (selected) {
            $("#VoucherFromDate").datepicker("option", "maxDate", selected);
        }
    });

    $('#DriversFromDate').datepicker({
        maxDate: 0,
        numberOfMonths: 1,
        onSelect: function (selected) {
            $("#DriversToDate").datepicker("option", "minDate", selected);
        }
    });

    $('#DriversToDate').datepicker({
        maxDate: 0,
        numberOfMonths: 1,
        onSelect: function (selected) {
            $("#DriversFromDate").datepicker("option", "maxDate", selected);
        }
    });


//Statistics Start

    var currentTime = new Date()
    var month = currentTime.getMonth() + 1;
    var year = currentTime.getFullYear();
    city_sales_generate_graph(month + " " + year);
    average_budget_generate_graph(month + " " + year);
    most_requested_item_generate_graph(month + " " + year);
    time_more_money_generate_graph(month + " " + year);
    store_reservations_generate_graph(year);
    break_down_revenue_generate_graph(year);
    most_requested_delivery_item_generate_graph(month + " " + year);
    most_requested_collection_item_generate_graph(month + " " + year);
    total_orders_month_generate_graph(year);
    total_revenue_month_generate_graph(year);
    
    $(".city_sales").datepicker({
        dateFormat: 'm yy',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        onClose: function (dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).val($.datepicker.formatDate('m yy', new Date(year, month, 1)));
            var date = $(this).val();
            city_sales_generate_graph(date);
        }
    });

    $(".city_sales").focus(function () {
        $(".ui-datepicker-calendar").hide();
        $("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: $(this)
        });
    });

    $(".average_budget").datepicker({
        dateFormat: 'm yy',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        onClose: function (dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).val($.datepicker.formatDate('m yy', new Date(year, month, 1)));
            var date = $(this).val();
            average_budget_generate_graph(date);
        }
    });

    $(".average_budget").focus(function () {
        $(".ui-datepicker-calendar").hide();
        $("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: $(this)
        });
    });


    function average_budget(jsString, date) {

        Highcharts.chart('average_budget', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Average budget / Category'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: 0,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Average Budget'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: 'Orders at ' + date + ': <b>{point.y:.1f}€</b>'
            },
            series: [{
                    name: 'Average budget',
                    data: jsString,
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        format: '{point.y:.1f}', // one decimal
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }]
        });
    }
    function average_budget_generate_graph(date) {
        $.post(rp + '/Commons/storestatistics', {action: "average_budget", date: date, storeId: $("#storeId").val()}, function (response) {
            var js = JSON.parse(response);
            var jsString = [];
            for (var i = 0; i < js.length; i++) {

                var arr = [];
                arr.push("Average Pickup Budget");
                arr.push(parseFloat(js[i][0].collection_average));
                jsString.push(arr);

                var arr = [];
                arr.push("Average Delivery Budget");
                arr.push(parseFloat(js[i][0].delivery_average));
                jsString.push(arr);

                var arr = [];
                arr.push("Average Total Budget");
                arr.push(parseFloat(js[i][0].total));
                jsString.push(arr);
            }
            average_budget(jsString, date);
        });
    }


    function city_sales(jsString, date) {

        Highcharts.chart('city_sales', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Orders percentage per month'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Orders Percentage'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: 'Orders at ' + date + ': <b>{point.y:.1f}%</b>'
            },
            series: [{
                    name: 'Orders',
                    data: jsString,
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        format: '{point.y:.1f}', // one decimal
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }]
        });
    }

    function city_sales_generate_graph(date) {
        $.post(rp + '/Commons/storestatistics', {action: "rushtime", date: date, storeId: $("#storeId").val()}, function (response) {
            var js = JSON.parse(response);
            var jsString = [];
            for (var i = 0; i < js.length; i++) {
                var arr = [];
                arr.push(js[i][0].name);
                arr.push(parseFloat(js[i][0].y));
                jsString.push(arr);
            }
            city_sales(jsString, date);
        });
    }

    $(".most_requested_item").datepicker({
        dateFormat: 'm yy',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        onClose: function (dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).val($.datepicker.formatDate('m yy', new Date(year, month, 1)));
            var date = $(this).val();
            most_requested_item_generate_graph(date);
        }
    });

    $(".most_requested_item").focus(function () {
        $(".ui-datepicker-calendar").hide();
        $("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: $(this)
        });
    });

    function most_requested_item(jsString, date) {

        Highcharts.chart('most_requested_item', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Most requested items per month'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Most requested items'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: 'Most requested items at ' + date + ': <b>{point.y:.1f}</b>'
            },
            series: [{
                    name: 'most_request_items',
                    data: jsString,
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        format: '{point.y:.1f}', // one decimal
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }]
        });
    }
    function most_requested_item_generate_graph(date) {
        $.post(rp + '/Commons/storestatistics', {action: "most_requested_item", date: date, storeId: $("#storeId").val()}, function (response) {
            var js = JSON.parse(response);
            var jsString = [];
            for (var i = 0; i < js.length; i++) {
                var arr = [];
                arr.push(js[i]["shopping_carts"].name);
                arr.push(parseFloat(js[i][0].y));
                jsString.push(arr);
            }
            most_requested_item(jsString, date);
        });
    }

    $(".time_more_money").datepicker({
        dateFormat: 'm yy',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        onClose: function (dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).val($.datepicker.formatDate('m yy', new Date(year, month, 1)));
            var date = $(this).val();
            time_more_money_generate_graph(date);
        }
    });

    $(".time_more_money").focus(function () {
        $(".ui-datepicker-calendar").hide();
        $("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: $(this)
        });
    });

    function time_more_money(jsString, date) {

        Highcharts.chart('time_more_money', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Total revenue generated / day and hour'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Total revenue generated / day and hour'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: 'Total revenue generated on ' + date + ': <b>{point.y:.1f}€</b>'
            },
            series: [{
                    name: 'time_more_money',
                    data: jsString,
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        format: '{point.y:.1f}', // one decimal
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }]
        });
    }
    function time_more_money_generate_graph(date) {
        $.post(rp + '/Commons/storestatistics', {action: "time_more_money", date: date, storeId: $("#storeId").val()}, function (response) {

            var js = JSON.parse(response);
            var jsString = [];
            for (var i = 0; i < js.length; i++) {
                var arr = [];
                arr.push(js[i][0].name);
                arr.push(parseFloat(js[i][0].y));
                jsString.push(arr);
            }
            time_more_money(jsString, date);
        });
    }

    function store_reservations(monthsArr, date) {

        Highcharts.chart('store_reservations', {
            title: {
                text: 'Total Reservations / Month'
            },
            xAxis: {
                tickInterval: 1,
                name: 'Months'
            },
            yAxis: {
                type: 'reservations',
                minorTickInterval: 0.1,
                name: 'Total Reservations / month'
            },
            tooltip: {
                headerFormat: '<b>Total Reservations / Month</b><br />',
                pointFormat: 'Month = {point.x}, Total Reservations = {point.y}'
            },
            series: [{
                    data: monthsArr,
                    pointStart: 1
                }]
        });
    }
    function store_reservations_generate_graph(date) {
        var monthsArr = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $.post(rp + '/Commons/storestatistics', {action: "store_reservations", date: date, storeId: $("#storeId").val()}, function (response) {
            var js = JSON.parse(response);
            var jsString = [];
            for (var i = 0; i < js.length; i++) {
                monthsArr[parseInt(js[i][0].name) - 1] = parseFloat(js[i][0].y);
            }
            store_reservations(monthsArr, date);
        });
    }

    function break_down_revenue(jsString, date) {
        var js = JSON.parse(jsString);
        Highcharts.chart('break_down_revenue', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Total revenue generated by Pickup and Delivery'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    }
                }
            },
            series: [{
                    name: 'Total Revenue',
                    colorByPoint: true,
                    data: js
                }]
        });

    }

    function break_down_revenue_generate_graph(date) {

        $.post(rp + '/Commons/storestatistics', {action: "break_down_revenue", date: date, storeId: $("#storeId").val()}, function (response) {
            var arr = [];
            var jsString = "";
            var js = JSON.parse(response);
            for (var i = 0; i < js.length; i++) {
                var total = (parseFloat(js[0][0].delivery) + parseFloat(js[1][0].delivery));
                if (i == 0) {
                    jsString += '[{ "name":"Delivery" , "y":' + (parseFloat(js[i][0].delivery) / total) * 100 + ' },';
                } else {
                    jsString += ' { "name":"Pickup" , "y":' + (parseFloat(js[i][0].delivery) / total) * 100 + ' }]';
                }
            }
            break_down_revenue(jsString, date);
        });
    }


    //most requested delivery items
    $(".most_requested_delivery_item").datepicker({
        dateFormat: 'm yy',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        onClose: function (dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).val($.datepicker.formatDate('m yy', new Date(year, month, 1)));
            var date = $(this).val();
            most_requested_delivery_item_generate_graph(date);
        }
    });

    $(".most_requested_delivery_item").focus(function () {
        $(".ui-datepicker-calendar").hide();
        $("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: $(this)
        });
    });

    function most_requested_delivery_item(jsString, date) {

        Highcharts.chart('most_requested_delivery_item', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Most requested delivery items per month'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Most requested delivery items'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: 'Most requested delivery items at ' + date + ': <b>{point.y:.1f}</b>'
            },
            series: [{
                    name: 'most_request_items',
                    data: jsString,
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        format: '{point.y:.1f}', // one decimal
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }]
        });
    }
    function most_requested_delivery_item_generate_graph(date) {
        $.post(rp + '/Commons/storestatistics', {action: "most_requested_delivery_item", date: date, storeId: $("#storeId").val()}, function (response) {
            var js = JSON.parse(response);
            var jsString = [];
            for (var i = 0; i < js.length; i++) {
                var arr = [];
                arr.push(js[i]["sc"].name);
                arr.push(parseFloat(js[i][0].y));
                jsString.push(arr);
            }
            most_requested_delivery_item(jsString, date);
        });
    }

    //most request collection items
    $(".most_requested_collection_item").datepicker({
        dateFormat: 'm yy',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        onClose: function (dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).val($.datepicker.formatDate('m yy', new Date(year, month, 1)));
            var date = $(this).val();
            most_requested_collection_item_generate_graph(date);
        }
    });

    $(".most_requested_collection_item").focus(function () {
        $(".ui-datepicker-calendar").hide();
        $("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: $(this)
        });
    });

    function most_requested_collection_item(jsString, date) {

        Highcharts.chart('most_requested_collection_item', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Most requested Pick up items per month'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Most requested pickup items'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: 'Most requested pickup items on ' + date + ': <b>{point.y:.1f}</b>'
            },
            series: [{
                    name: 'most_request_collection_item',
                    data: jsString,
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        format: '{point.y:.1f}', // one decimal
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }]
        });
    }
    function most_requested_collection_item_generate_graph(date) {
        $.post(rp + '/Commons/storestatistics', {action: "most_requested_collection_item", date: date, storeId: $("#storeId").val()}, function (response) {
            var js = JSON.parse(response);
            var jsString = [];
            for (var i = 0; i < js.length; i++) {
                var arr = [];
                arr.push(js[i]["sc"].name);
                arr.push(parseFloat(js[i][0].y));
                jsString.push(arr);
            }
            most_requested_collection_item(jsString, date);
        });
    }

    function total_orders_month(monthsArr, date) {

        Highcharts.chart('total_orders_month', {
            title: {
                text: 'Total Orders / Month in ' + date
            },
            xAxis: {
                tickInterval: 1,
                name: 'Months'
            },
            yAxis: {
                type: 'Orders',
                minorTickInterval: 0.1,
                name: 'Total Orders / month'
            },
            tooltip: {
                headerFormat: '<b>Total Orders / Month</b><br />',
                pointFormat: 'Month = {point.x}, Total Orders = {point.y}'
            },
            series: [{
                    data: monthsArr,
                    pointStart: 1
                }]
        });
    }
    function total_orders_month_generate_graph(date) {
        var monthsArr = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $.post(rp + '/Commons/storestatistics', {action: "total_orders_month", date: date, storeId: $("#storeId").val()}, function (response) {
            var js = JSON.parse(response);
            var jsString = [];
            for (var i = 0; i < js.length; i++) {
                monthsArr[parseInt(js[i][0].name) - 1] = parseFloat(js[i][0].y);
            }
            total_orders_month(monthsArr, date);
        });
    }

    function total_revenue_month(monthsArr, date) {

        Highcharts.chart('total_revenue_month', {
            title: {
                text: 'Total Revenue / Month in ' + date
            },
            xAxis: {
                tickInterval: 1,
                name: 'Months'
            },
            yAxis: {
                type: 'Orders',
                minorTickInterval: 0.1,
                name: 'Total Orders / month'
            },
            tooltip: {
                headerFormat: '<b>Total Revenue / Month</b><br />',
                pointFormat: 'Month = {point.x}, Total Revenue = {point.y}€'
            },
            series: [{
                    data: monthsArr,
                    pointStart: 1
                }]
        });
    }
    function total_revenue_month_generate_graph(date) {
        var monthsArr = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $.post(rp + '/Commons/storestatistics', {action: "total_revenue_month", date: date, storeId: $("#storeId").val()}, function (response) {
            var js = JSON.parse(response);
            var jsString = [];
            for (var i = 0; i < js.length; i++) {
                monthsArr[parseInt(js[i][0].name) - 1] = parseFloat(js[i][0].y);
            }
            total_revenue_month(monthsArr, date);
        });
    }


//statisticss end
});



function productImageDelete() {

    var imagesProduct = $('#imagesProduct').val();
    var storeId = $('#storeId').val();
    imagesProduct = imagesProduct.substr(0, imagesProduct.length - 1);
    var imageArray = imagesProduct.split(",");
    var line = 'Are you sure want to change Store. if you lost prouct images ?';

    if (confirm(line)) {
        for (var i = 0; i < imageArray.length; i++) {
            deleteProductImage(imageArray[i]);
        }
        ;
    } else {
        $('#ProductStoreId').val(storeId);
        return false;
    }
}


function productList() {
    var id = $('#DealStoreId').val();
    $.post(rp + '/admin/Deals/productList', {'id': id, 'model': 'City'}, function (response) {
        $("#DealMainProduct").html(response);
        $("#DealSubProduct").html(response);

    });
}

function changeOrderStatusAsap(orderId) {
    var estimatedPickupTime = $("#estimatedTime_" + orderId).val();
    var type = $('#orderType_' + orderId).val();
    if ($.trim(estimatedPickupTime).length > 0) {
        $('.ui-loadercont').show();
        $.post(rp + '/store/orders/orderStatus', {'orderId': orderId, 'status': "Accepted_Asap", estimated_pickup_time: estimatedPickupTime}, function (response) {
            $('.ui-loadercont').hide();
            $('#orderList_' + orderId).remove();
            var message = 'Cette commande passe au statut livré';
            message += (type == 'Delivery') ? 'Cette commande passe au service d’expédition' : 'Cette commande passe à la gestion des commandes à emporter';
            $('#orderMessage').html(message);
            $('#orderMessage').show();
            setTimeout(function () {
                $('#orderMessage').fadeOut();
            }, 3000);
        });
    } else {
        alert("Entrez le délai de livraison estimé");
    }
}
function orderStatusAsap(orderId) {

    var status = $('#orderStatus_' + orderId).val();
    var type = $('#orderType_' + orderId).val();

    if (status != 'Failed' && status != 'Pending') {
        html = '<input type=text class="form-control margin-t-10 margin-b-10" placeholder="Entrez le temps de ramassage estimé" id="estimatedTime_' + orderId + '" ></input>' +
                '<input type="button" value="Envoyer" class="btn btn-default" onclick="return changeOrderStatusAsap(' + orderId + ');">';
        $("#reason_" + orderId).html("");
        $("#reason_" + orderId).append(html);

    } else if (status == 'Failed') {
        html = '<textarea class="form-control margin-t-10 margin-b-10" id="failedReason_' + orderId + '" rows="4" cols="10"></textarea>' +
                '<input type="button" value="Envoyer" class="btn btn-default" onclick="return changeOrderStatus(' + orderId + ');">';
        $("#reason_" + orderId).html("");
        $("#reason_" + orderId).append(html);
    } else {
        $("#reason_" + orderId).html('');
    }
}

function orderStatus(orderId) {

    var status = $('#orderStatus_' + orderId).val();
    var type = $('#orderType_' + orderId).val();

    if (status != 'Failed' && status != 'Pending') {
        $('.ui-loadercont').show();
        $.post(rp + '/store/orders/orderStatus', {'orderId': orderId, 'status': status}, function (response) {
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
        $("#reason_" + orderId).html("");
        $("#reason_" + orderId).append(html);
    } else {
        $("#reason_" + orderId).html('');
    }
}


function changeOrderStatus(orderId) {
    var reason = $('#failedReason_' + orderId).val();
    if (reason != '') {
        $('.ui-loadercont').show();
        $.post(rp + '/store/orders/orderStatus', {'orderId': orderId, 'status': 'Failed', 'reason': reason}, function (response) {
            $('.ui-loadercont').hide();
            $('#orderList_' + orderId).remove();
            $('#orderMessage').html('La commande a été anulée pour une raison');
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
        $.post(rp + '/store/orders/orderStatus', {'orderId': orderId, 'status': 'Deleted'}, function (response) {
            $('#orderList_' + orderId).remove();
        });
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
$(document).ready(function () {
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
    $(".test").on("click", function () {

        if ($(".checkboxes").is(":checked")) {
            $("#send").show();
        } else {
            $("#send").hide();
        }
    });

    var checkbox = 1;
    $(".test1").on("click", function () {
        if (checkbox == 0) {
            checkbox = 1;
            $("#send").hide();
        } else {
            checkbox = 0;
            $("#send").show();
        }
    });

    $('input[type="file"]').change(function () {
        $(".browse_btn").parent().next().empty();
        var f = this.files[0];
        var name = f.name;
        $(".browse_btn").parent().next().text(name);
    });

});
function recorddelete(obj) {

    var line = 'Are you sure want to ' + obj.value + '?';
    if (confirm(line)) {
        window.location.href = rp + '/store/Commons/multipleSelect';
    } else {
        return false;
    }
}

var subRow = (typeof j != 'undefined') ? j : 1;
function addSubAddons() {
    $('#subAddonsList').append(
            '<div class="form-group" id="removeSubaddon_' + subRow + '">' +
            '<div class="col-md-6 col-lg-3 col-md-offset-2">' +
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
    var storeId = $('#storeIdValue').val();
    var categoryId = $('#MainaddonCategoryId').val();
    var mainAddonName = $('#MainaddonMainaddonsName').val();
    var mainAddonCount = $('#MainaddonMainaddonsCount').val();
    var SubAddonName = $('#SubAddonName').val();
    var SubAddonPrice = $('#SubAddonPrice').val();

    var URL = rp + '/Addons/checkAddonExist';

    if (categoryId == '') {
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
                            $('#AddonsStoreAddForm').submit();
                        } else {
                            $('#AddonsStoreEditForm').submit();
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
    var storeId = $('#storeIdValue').val();
    var categoryId = $('#ProductCategoryId').val();
    var price = $('#ProductPriceOptionSingle').is(':checked');
    var priceOption = (price == true) ? 'single' : 'multiple';
    var URL = rp + '/AjaxAction/index';

    $('#categoryError').html('');

    if (categoryId == '') {
        $('#categoryError').html('Sélectionnez le Catégorie svp');
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
                        var multipleLength = $('.multipleMenu').length;
                        var j = 0;
                        for (j = 1; j <= multipleLength; j++) {
                            appendMultipleSubAddons(j);
                        }
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
    //var VoucherType       = $('#VoucherTypeOfferSingle').val();
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

function bookaTableStatus(bookId) {

    var status = $('#bookStatus_' + bookId).val();
    if (status != 'Cancel' && status != 'Pending') {
        $.post(rp + '/bookaTables/bookStatus', {'bookId': bookId, 'status': status}, function (response) {
            if (status == 'Approved') {
                status = 'Approuvé';
            }
            $('#orderMessage').show();
            $('#bookTableStatus_' + bookId).html(status);
            setTimeout(function () {
                $('#orderMessage').fadeOut();
            }, 3000);

        });
    } else if (status == 'Cancel') {
        html = '<textarea class="form-control margin-t-10 margin-b-10" id="failedReason_' + bookId + '" rows="4" cols="10"></textarea>' +
                '<input type="button" value="Submit" class="btn btn-default" onclick="return changeBookStatus(' + bookId + ');">';
        $("#reason_" + bookId).append(html);
    } else {
        $("#reason_" + bookId).html('');
    }
}

function changeBookStatus(bookId) {
    var reason = $('#failedReason_' + bookId).val();
    if (reason != '') {
        $.post(rp + '/bookaTables/bookStatus', {'bookId': bookId, 'status': 'Cancel', 'reason': reason}, function (response) {
            $('#orderMessage').show();
            $('#bookTableStatus_' + bookId).html('Annulé');
            setTimeout(function () {
                $('#orderMessage').fadeOut();
            }, 3000);
        });
    } else {
        alert('Veuillez saisir la raison de l’annulation de la réservation');
    }
}


function viewBookTableDetails(bookId) {
    $('#trackid').show();
    $('#bookaTable').load(rp + '/bookaTables/bookatableDetails', {'bookId': bookId}, function (response) {
    });
    return false;
}