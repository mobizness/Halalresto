
setTimeout(function () {
    $('#flashMessage').fadeOut();
}, 3000);

setTimeout(function () {
    $(".img_loader").hide();
    $(".product__image").fadeIn();
}, 5000);


function filterResults() {

    var $filterCheckboxes = $('input[type="checkbox"]');
    var $filterRadios = $('input[type="radio"]');

    var selectedFilters = {};

    $filterCheckboxes.filter(':checked').each(function () {
        if (!selectedFilters.hasOwnProperty(this.name)) {
            selectedFilters[this.name] = [];
        }
        selectedFilters[this.name].push(this.value);
    });

    $filterRadios.filter(':checked').each(function () {
        if (!selectedFilters.hasOwnProperty(this.name)) {
            selectedFilters[this.name] = [];
        }
        selectedFilters[this.name].push(this.value);
    });

    // create a collection containing all of the filterable elements
    var filteredResults = $('.flower');

    // alert(filteredResults);

    // loop over the selected filter name -> (array) values pairs
    $.each(selectedFilters, function (name, filterValues) {

        // filter each .flower element
        filteredResults = filteredResults.filter(function () {

            var matched = false;
            var currentFilterValues = $(this).data('category').split(' ');

            // loop over each category value in the current .flower's data-category
            $.each(currentFilterValues, function (_, currentFilterValue) {

                // if the current category exists in the selected filters array
                // set matched to true, and stop looping. as we're ORing in each
                // set of filters, we only need to match once

                if ($.inArray(currentFilterValue, filterValues) != -1) {
                    console.log(filterValues);
                    matched = true;
                    return false;
                }
            });

            // if matched is true the current .flower element is returned
            return matched;

        });
    });
    $('.flower').hide().filter(filteredResults).show();
    var itemcount = $(".flower:visible").length;
    if (itemcount == 0) {
        $('#storeCount').html(itemcount);
        $('#nostore').show();
    } else {
        $('#nostore').hide();
        $('#storeCount').html(itemcount);
        $('.flower').hide().filter(filteredResults).show();
    }

}


// Cuisine Filter
function filterItems() {
    var colors = $(":radio:checked").map(function () {
        return this.value;
    }).get();
    var goodClasses = colors;
    if (goodClasses != '')
        $(".flower").hide().filter(goodClasses).show();
    else
        $(".flower").show();

}



$(document).ready(function () {

    if (controller == 'searches' && action == 'storeitems') {
        cart();
    } else if (controller == 'checkouts') {
        checkoutCart();
    }
    //Cuisine Filter
    $(":radio").change(filterResults);
    $(":checkbox").change(filterResults);

    $(".title-filter").click(function () {
        $(".searchMenuFormList").toggleClass("in");
    });

    var extensions = {
        "sFilterInput": "form-control inline-block margin-l-10 width-auto",
        "sLengthSelect": "form-control inline-block margin-l-10 margin-r-10 width-auto"
    }
    // Used when bJQueryUI is false
    $.extend($.fn.dataTableExt.oStdClasses, extensions);
    // Used when bJQueryUI is true
    //$.extend($.fn.dataTableExt.oJUIClasses, extensions);

    $('.datatable-common').dataTable({
        "bSort": false,
        columnDefs: [
            {
                "bSortable": false,
                "aTargets": ["no-sort"]
            }
        ],
        "order": [[0, 'desc']]
    });


    $('.selectpicker').selectpicker();

    var windowHeight = $(window).height();
    //$(".indexBnner").css("height", windowHeight + "px");
    if ($(window).width() > 767) {
        //$(".bannerrelative").css("min-height", (windowHeight - $(".indexheader").height()));
    } else {
        //$(".bannerrelative").css("min-height", windowHeight);
    }


    var menuDetailHeader = $(".detailheader").height();
    var menuRightHei = (windowHeight - menuDetailHeader);
    //$(".rightSideBar").css("min-height", menuRightHei + "px");

    $(".cartDropdown").click(function () {
        $("#cart-sidebar").toggleClass('in');
        $(".btn-cart-toggle span").toggleClass('fa-angle-double-right');
        $(".btn-cart-toggle span").toggleClass('fa-angle-double-left');
    });
    $(".btn-cart-toggle").click(function () {
        $("#cart-sidebar").toggleClass('in');
        $(".btn-cart-toggle span").toggleClass('fa-angle-double-right');
        $(".btn-cart-toggle span").toggleClass('fa-angle-double-left');
    });

    $(".menuclose_mobile").click(function () {
        $(".dropdown.menuDropdown").removeClass('open');
    });


    $('.maincategory li').on('click', function (e) {

        $(this).children('ul').toggle();
        $(this).siblings('li').find('ul').hide();

        if ($(this).children('ul').css('display') == 'block')
        {
            $('.maincategory li a').removeClass("active");
            $(this).children('ul li a').addClass("active");
        } else
        {
            $(this).children('ul li a').removeClass("active");
        }

        e.stopPropagation();
    });

    /* Cart height */
    var carthei = $(window).height() - $(".cart-checkout").outerHeight();
    //var cartheiMob = $(window).height() - ( $(".mobile_cart").outerHeight() );
    //var cartheiMob = $(window).height() ;

    if ($(window).width() > 767) {
        //alert(carthei);
        //$(".cart-items").css("height",carthei);
    } else {
        //$(".cart-items").css({"height":cartheiMob}); 
    }

    /* add note scirpt */
    $(".add-note").click(function () {
        $(this).next(".edit-special-instructions").removeClass('hide');
    });

    /* Cancel note scirpt */
    $(".cancelinst").click(function () {
        $(this).parent().addClass('hide');
    });

    $(".title-categories,.close_category,.subcategories li").click(function () {
        $(".category_mobile").toggleClass('open');
    });

    //$(".cart-items").click(function(){
    var windowHeight = $(window).height();

    /* My Account Tab Script */
    $(".myaccount-tabs a").click(function () {
        $(".myaccount-tabs a").removeClass("active");
        $(".myorderTab").hide();
        $(this).addClass("active");
        var activeTab = $(this).attr("id");
        $("#" + activeTab + "_content").show()
    });

    $(".profile-box .edit").click(function () {
        $(this).prev(".formLabel").hide();
        $(this).hide();
        $(this).next(".textbox").show();
        $(this).next().next(".lableclose").show();
    });

    $(".profile-box .lableclose").click(function () {
        $(this).prev().prev().prev(".formLabel").show();
        $(this).prev().prev(".edit").show();
        $(this).hide();
        $(this).prev(".textbox").hide();
    });

    $(".checkoutWrapper .editAdrr").click(function () {
        $(".checkoutWrapper .editAdrr").removeClass('active');
        $(this).addClass('active');

    });

    $(".paymentWrapper .editpayment").click(function () {
        $(".paymentWrapper .editpayment").removeClass('active');
        $(this).addClass('active');

    });

    $('.intnumber').keypress(function (event) {

        var key = window.event ? event.keyCode : event.which;

        if ($(this).val() == '') {
            if (key == 48) {
                return false;
            }
        }
        if (key == 8 || key == 37 || key == 39 || key == 0) {
            return true;
        } else if (key == 46) {
            return true;
        } else if (key >= 48 && key <= 57) {
            return true;
        } else if ((key >= 65 && key <= 90) || (key >= 97 && key <= 122))
        {
            return false;
        } else {
            return false;
        }
    });


});

function searchFormValidation() {
    if ($.trim($("#searchName").val()).length > 0 || $.trim($("#searchAddress").val()).length > 0) {
        $("#searchError").hide();
        return true;
    } else {
        $("#searchError").show();
        return false;
    }
    //return true or false;
}

function locationList() {
    var id = $('#city').val();
    $.post(rp + 'searches/locations', {'id': id, 'model': 'Location'}, function (response) {
        $("#location").html(response).selectpicker('refresh');
    });
}

function productDetails(id) {
    $.post(rp + 'searches/productdetails', {'id': id}, function (response) {
        $("#addCartPop").html(response);
        getMenuAddons(id);

        $("#quantity").TouchSpin({
            initval: 1,
            min: 1,
            max: 100

        });

    });
}

function variantDetails(id) {

    $.post(rp + 'searches/variantDetails', {'id': id}, function (response) {

        $("#productVariantDetails").html();
        $("#productVariantDetails").html(response);
        $("#quantity").TouchSpin({
            initval: 1,
            min: 1,
        });
        $("#addCartPop").modal('show');
    });
}

function addToCart(id, fieldId) {
    $.post(
            rp + 'searches/cartProduct',
            {
                'id': id,
                'quantity': '1'
            },
            function (response) {

                if (response == 'Success') {
                    cart();
                    $('.cart_notification').fadeIn();
                    setTimeout(function () {
                        $('.cart_notification').fadeOut();
                    }, 1000);

                } else {
                    $('.cart_failedNotification').fadeIn();
                    setTimeout(function () {
                        $('.cart_failedNotification').fadeOut();
                    }, 1000);
                }

                //var itemImg = $(this).parents(".product__inner").find('.product__image img').eq(0);
                var itemImg = $('[data-cart=' + fieldId + ']');
                flyToElement($(itemImg), $('.btn-cart-toggle'));

            }
    );

}

function variantCart($fieldId) {

    var id = $('[name=addon_ss]:checked').val();
    if (id == undefined) {
        id = $('#productAddonsSingle').val();
    }
    var ProductId = $('#ProductId').val();
    var quantity = $('#quantity').val();
    var subaddons = '';

    $(".addonsId").each(function () {
        if ($(this).attr("checked")) {
            subaddons += $(this).val() + ',';
        }
    });

    $.post(rp + 'searches/cartProduct', {'id': id, 'quantity': quantity, 'subaddons': subaddons,
        'ProductId': ProductId}, function (response) {
        if (response == 'Success') {
            cart();
            $('#addCartPop').modal('hide');
            $('.cart_notification').fadeIn();
            setTimeout(function () {
                $('.cart_notification').fadeOut();
            }, 1000);

        }
    });
    var itemImg = $('[data-cart=' + $fieldId + ']');
    flyToElement($(itemImg), $('.btn-cart-toggle'));
}


function checkAddonsCount(subAddonId, addonId, maxCount) {
    AddonCount = 0;
    $('.checkCount_' + addonId).each(function () {
        if ($(this).attr("checked")) {
            AddonCount++;
        }
    });
    if (maxCount < AddonCount) {
        $('#checkCount_' + addonId + '_' + subAddonId).prop("checked", false);
        alert('You can select maximum ' + maxCount + ' addons');
        return false;
    }
    return false;
}

function addnote(id) {
    $(id).hide();
    $(id).next(".edit-special-instructions").removeClass('hide');
}
function cancelnote(id) {
    $(id).parent(".edit-special-instructions").prev(".add-note").show();
    $(id).parent(".edit-special-instructions").addClass('hide');
}

function description(id) {
    var productDescription = $('#productDescription' + id).val();
    $.post(rp + 'searches/descriptionAdd', {'id': id, 'productDescription': productDescription}, function (response) {
        $("#" + id).parent(".edit-special-instructions").prev(".add-note").show();
        $("#" + id).parent(".edit-special-instructions").addClass('hide');
    });

}

function cart() {
    $.post(rp + 'searches/cart', {}, function (response) {
        var data = response.split("||@@||");

        $("#cartCount").html(data[0]);
        total = parseFloat(data[1]).toFixed(2);
        $(".cartTotal").html(format(total));

        $("#cartdetailswrapper").html(data[2]);
        $(".cart-items").mCustomScrollbar();

        var carthei = $(window).height() - (10 + $(".cart-checkout").outerHeight());
        //var cartheiMob = $(window).height();
        var cartheiMob = $(window).height() - ($(".mobile_cart").outerHeight());

        if ($(window).width() > 767) {
            //$(".cart-items").css("height",carthei);
        } else {
            //$(".cart-items").css({"height":cartheiMob}); 
        }
    });
}


var format = function (num) {
    var str = num.toString().replace("Mani", ""), parts = false, output = [], i = 1, formatted = null;
    if (str.indexOf(".") > 0) {
        parts = str.split(".");
        str = parts[0];
    }
    str = str.split("").reverse();
    for (var j = 0, len = str.length; j < len; j++) {
        if (str[j] != ",") {
            output.push(str[j]);
            if (i % 3 == 0 && j < (len - 1)) {
                output.push(",");
            }
            i++;
        }
    }
    formatted = output.reverse().join("");
    return("" + formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
};

function deleteCart(id) {
    $.post(rp + 'searches/deleteCart', {'id': id}, function (response) {
        cart();
    });
}

function qtyIncrement(id, myid) {
    $.post(rp + 'searches/qtyUpdate', {'id': id, 'type': 'increment'}, function (response) {
        $(myid).parents(".cartTr").addClass("heighlight");
        setTimeout(function () {
            $(".cartTr td").removeClass("heighlight");
            cart();
        }, 3000);
    });
}

function qtyDecrement(id, myid) {
    $.post(rp + 'searches/qtyUpdate', {'id': id, 'type': 'decrement'}, function (response) {
        $(myid).parents(".cartTr").addClass("heighlight");
        setTimeout(function () {
            $(".cartTr td").removeClass("heighlight");
            cart();
        }, 3000);
    });
}

function changeLocation() {
    $.post(rp + 'searches/changeLocation', {'location': 'location'}, function (response) {
        window.location.href = rp;
    });
}

function citiesList() {
    var id = $('#CustomerAddressBookStateId').val();
    $.post(rp + '/stores/locations', {'id': id, 'model': 'City'}, function (response) {
        $("#CustomerAddressBookCityId").html(response);
    });
}

function locationLists() {
    var id = $('#CustomerAddressBookCityId').val();
    $.post(rp + '/stores/locations', {'id': id, 'model': 'Location'}, function (response) {
        $("#StoreStoreZip").html(response);
        $("#CustomerAddressBookLocationId").html(response);
    });
}


$(window).load(function () {
    /* all available option parameters with their default values */
    if ($(window).width() > 767)
    {
        $(".cart-items").mCustomScrollbar({
            setWidth: false,
            setHeight: false,
            setTop: 0,
            setLeft: 0,
            axis: "y",
            scrollbarPosition: "inside",
            scrollInertia: 950,
            autoDraggerLength: true,
            autoHideScrollbar: false,
            autoExpandScrollbar: false,
            alwaysShowScrollbar: 0,
            snapAmount: null,
            snapOffset: 0,
            mouseWheel: {
                enable: true,
                scrollAmount: "auto",
                axis: "y",
                preventDefault: false,
                deltaFactor: "auto",
                normalizeDelta: false,
                invert: false,
                disableOver: ["select", "option", "keygen", "datalist", "textarea"]
            },
            scrollButtons: {
                enable: false,
                scrollType: "stepless",
                scrollAmount: "auto"
            },
            keyboard: {
                enable: true,
                scrollType: "stepless",
                scrollAmount: "auto"
            },
            contentTouchScroll: 25,
            advanced: {
                autoExpandHorizontalScroll: false,
                autoScrollOnFocus: "input,textarea,select,button,datalist,keygen,a[tabindex],area,object,[contenteditable='true']",
                updateOnContentResize: true,
                updateOnImageLoad: true,
                updateOnSelectorChange: false,
                releaseDraggableSelectors: false
            },
            theme: "light",
            callbacks: {
                onInit: false,
                onScrollStart: false,
                onScroll: false,
                onTotalScroll: false,
                onTotalScrollBack: false,
                whileScrolling: false,
                onTotalScrollOffset: 0,
                onTotalScrollBackOffset: 0,
                alwaysTriggerOffsets: true,
                onOverflowY: false,
                onOverflowX: false,
                onOverflowYNone: false,
                onOverflowXNone: false
            },
            live: false,
            liveSelector: null
        });

    }

});

function minOrderStore() {
    $.post(rp + '/searches/storeMinOrderCheck', function (response) {
        alert(response);
    });
    return false;
}

function cancelOrder(id) {
    $('#OrderId').val(id);
}

$(document).ready(function () {


    $("#restaurant-menu .maincategory li").click(function () {
        $("#restaurant-menu .maincategory li").removeClass('active');
        $(this).addClass('active');
    });

    var topPart = $('.header');
    var pos = topPart.offset();
    $(window).scroll(function () {
        if ($(this).scrollTop() > pos.top + 100 + topPart.height() && $("#cartdetailswrapper .hidden-xs ").length > 0) {
            $('#cart-sidebar').css('position', 'fixed');
            $('#cart-sidebar').css('width', '28%');
            $('#cart-sidebar').css('right', '7%');
            $('#cart-sidebar').css('bottom', '40%');
        } else if ($(this).scrollTop() <= pos.top + topPart.height()) {
            $('#cart-sidebar').css('position', 'relative');
            $('#cart-sidebar').css('width', '33.33%');
            $('#cart-sidebar').css('right', '0px');
            $('#cart-sidebar').css('bottom', '0px');
        }
    });


    //disabling past date from datepicker
    var nowDate = new Date();

    $('#latertime,#BookaTableBookingDate').datepicker({
        minDate: 0,
        maxDate: "+1d",
        format: "dd-mm-yyyy",
        startDate: nowDate,
        endDate: '+1d',
        numberOfMonths: 1,
        autoclose: true
    })
            .on('changeDate', function (ev) {
                getDateTime(ev.format());
            });
    $(window).scroll(function () {
        var scrollVal = $(this).scrollTop();
        if (scrollVal > 70) {
            $(".headerouterfix").addClass("fixed");
        } else {
            $(".headerouterfix").removeClass("fixed");
        }
    });

    $(".filterCls").click(function () {
        $(".cuisinefil").slideToggle();
    })

    $(".searchFilterResults").keyup(function () {
        // Retrieve the input field text and reset the count to zero
        var filter = $(this).val(), count = 0;
        var mainCatProduct = $('.mainCatProduct').length;
        for (var x = 0; x <= mainCatProduct; x++) {
            mainCat = 0;
            var productsCat = $('.mainCatProduct').children('.productsCat' + x).length;
            for (var y = 1; y <= productsCat; y++) {
                var showProduct = 0;
                // Loop through the comment list
                $(".searchresulttoshow" + x + y).each(function () {
                    // If the list item does not contain the text phrase fade it out
                    if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                        $(this).fadeOut();

                        // Show the list item if the phrase matches and increase the count by 1
                    } else {
                        showProduct = 1;
                        $(this).show();
                        count++;
                    }
                });
                if (showProduct == 1) {
                    mainCat = 1;
                    $(".searchresulttoshow" + x + y).parent('ul').prev('h5').show();
                } else {
                    $(".searchresulttoshow" + x + y).parent('ul').prev('h5').fadeOut();
                }
            }
            if (mainCat == 1) {
                $(".productsCat" + x).prev('h5').prev('header').show();
                console.log('Success');
            } else {
                $(".productsCat" + x).prev('h5').prev('header').fadeOut();
            }
        }
    });

    $("#OrderAssoonasNow").click(function () {
        $("#showCalendar").hide();
    });
    $("#OrderAssoonasLater").click(function () {
        $("#showCalendar").show();
    });
});

var win_height = $(window).height();
if ($(window).width() > 767) {
    equalheight = function (container) {
        var currentTallest = 0,
                currentRowStart = 0,
                rowDivs = new Array(),
                $el,
                topPosition = 0;
        $(container).each(function () {

            $el = $(this);
            $($el).height('auto')
            topPostion = $el.position().top;

            if (currentRowStart != topPostion) {
                for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
                    rowDivs[currentDiv].height(currentTallest);
                }
                rowDivs.length = 0; // empty the array
                currentRowStart = topPostion;
                currentTallest = $el.height();
                rowDivs.push($el);
            } else {
                rowDivs.push($el);
                currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
            }
            for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
                rowDivs[currentDiv].height(currentTallest);
            }
        });
    }

    $(window).resize(function () {
        equalheight('.equalHeight');
    });
}

$(window).load(function () {
    var count = 0;
    fillter();
    function fillter() {
        count++;
        var getvalue = $('#check').val();
        if ($.trim(getvalue) == '') {
            return false;

        } else {
            var splits = getvalue.split('_');
            var id = splits[0];
            var storeId = splits[1];
            $.post(rp + 'searches/filtterByCategory', {'id': id, 'storeId': storeId, 'count': count}, function (response) {
                $("#filtterByCategory").append(response);
                $('.remove_' + id).remove();
                fillter();
                if ($(window).width() > 767) {
                    equalheight('.equalHeight');
                }
            });
        }

    }
});

function categoriesProduct(id, subId, storeId) {
    $('.error').hide();
    var searchKey = $('#searchKey').val('');
    $('#messageError').hide();
    $.post(rp + 'searches/filtterByCategory', {'id': id, 'storeId': storeId, 'subId': subId, 'count': 1},
            function (response) {
                $("#filtterByCategory").html('');
                $("#filtterByCategory").append(response);
                if ($(window).width() > 767) {
                    equalheight('.equalHeight');
                }
            }
    );
}

function dealsProduct(storeId) {
    $('.error').hide();
    var searchKey = $('#searchKey').val('');
    $('#messageError').hide();
    $.post(rp + 'searches/dealProducts', {'storeId': storeId},
            function (response) {
                $("#filtterByCategory").html('');
                $("#filtterByCategory").append(response);
                if ($(window).width() > 767) {
                    equalheight('.equalHeight');
                }
            }
    );
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ')
            c = c.substring(1);
        if (c.indexOf(name) == 0)
            return c.substring(name.length, c.length);
    }
    return "";
}

function productSearch(event) {
    $('.error').hide();
    if (event.which == 13 || event.keyCode == 13) {
        searchProducts();
    }
}

function cuisineFilter() {

    var Address = $('#searchAddress').val();
    var i = 0;
    var cuisineid = new Array;
    $('[name=cuisine]:checked').each(function () {
        cuisineid += $(this).val() + ',';
        i++;
    });

    $.post(
            rp + 'ajaxAction/index',
            {
                'cuisineid': cuisineid,
                'address': Address,
                'Action': 'filterResult'
            },
            function (response) {
                $('#showFilterResult').html(response);
                return false;
            }
    );
}

function getDateTime(date) {
    var URL = rp + 'ajaxAction';
    $('#getDateTime').load(
            URL,
            {
                'date': date,
                'Action': 'getDateTime'
            },
            function (response) {
                if ($.trim(response) == '<option value="">Closed</option>') {
                    $('#closedTime').attr('disabled');
                    return false;
                }
            }
    );
    return false;
}



function getMenuAddons(productDetailId) {
    var productDetailId = $('[name=addon_ss]:checked').val();
    var ProductId = $('#ProductId').val();
    var URL = rp + 'ajaxAction';

    if (productDetailId == undefined) {
        productDetailId = $('#productAddonsSingle').val();
    }
    variantDetails(productDetailId);

    $('#loadMenuAddons').load(
            URL, {
                'productId': ProductId,
                'productDetailId': productDetailId,
                'Action': 'getMenuAddons'
            },
            function (data) {

            }
    );
    return false;
}


function orderPlace(event) {
    if (event.keyCode == 13) {
        event.preventDefault();
        return false;
    }
}

function tipsCheck(event) {
    var key = window.event ? event.keyCode : event.which;

    if (event.keyCode == 8 || event.keyCode == 46
            || event.keyCode == 37 || event.keyCode == 39) {
        return true;
    } else if (key < 48 || key > 57) {
        return false;
    } else
        return true;
}


$(window).resize(function () {
    if ($(window).width() > 767) {
        var windowHeight = $(window).height();
        //$(".bannerrelative").css("min-height", (windowHeight - $(".indexheader").height()));
    } else {
        var windowHeight = $(window).height();
        //$(".bannerrelative").css("min-height", windowHeight);
    }
});





addressHeight = function (addressHgt) {
    var currentTallest = 0,
            currentRowStart = 0,
            rowDivs = new Array(),
            $el,
            topPosition = 0;
    $(addressHgt).each(function () {

        $el = $(this);
        $($el).height('auto')
        topPostion = $el.position().top;

        if (currentRowStart != topPostion) {
            for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
                rowDivs[currentDiv].height(currentTallest);
            }
            rowDivs.length = 0; // empty the array
            currentRowStart = topPostion;
            currentTallest = $el.height();
            rowDivs.push($el);
        } else {
            rowDivs.push($el);
            currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
        }
        for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
            rowDivs[currentDiv].height(currentTallest);
        }
    });
}

$(window).resize(function () {
    addressHeight('.checkoutWrapper .editAdrr');
});

function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

$(document).ready(function () {


    $("#newsletterSubmit").click(function () {
        var email = $("#newsletterEmail").val();
        if (isEmail(email)) {
            $.post(rp + 'Searches/newslettersubscribe', {'email': email}, function (response) {
                if (response == "1") {
                    $("#newslettermsg").html("Vous venez de vous abonner avec succès à notre Newsletter");
                } else {
                    $("#newslettermsg").html("Vous êtes déjà abonné à notre lettre d'information");
                }
                $("#newslettermessage").click();
            });
        } else {
            $("#newslettermsg").html("Adresse e-mail invalide");
            $("#newslettermessage").click();
            //newslettermsg
        }
    });


    $(".menutab_ul li a").click(function () {
        $(".menutab_ul li a").removeClass('active');
        $(this).addClass('active');
        $(".menuWrapper").hide();
        $('#' + $(this).attr('data-href')).show();
    });
});

function showMap() {


    var Url = rp + '/AjaxAction';
    var StoreName = $('#StoreName').val();
    var Address = $('#StoreAddress').val();
    // alert(Address);


    if (Address == '') {
        $("[href=#contact]").trigger('click');
        $("#contactError").html("Please enter address");
        $("#StoreAddress").focus();
        return false;
    } else {
        $.post(
                Url,
                {
                    'address': Address,
                    'resname': StoreName,
                    'Action': 'showMap'
                },
                function (data) {
                    $('#mapShow').html(data);
                    return false;
                }
        );
        return false;
    }
}

function myFavourite(store_id, type) {

    $.post(rp + 'ajaxAction/index', {'store_id': store_id, 'type': type, 'Action': 'myFavourite'}, function (output) {
        if (type == 'add') {
            $('#addfav').hide();
            $('#remfav').show();
            return false
        } else if (type == 'remove') {
            $('#remfav').hide();
            $('#addfav').show();
            return false
        }
    });
    return false

}

function deleteFav(id) {
    $.post(rp + 'ajaxAction/index', {'id': id, 'Action': 'delFavourite'}, function (output) {
        $('#favrecord_' + id).hide();
    });
    return false
}

function deleteCheckoutCart(id) {
    var orderType = $('input[name="data[Order][orderType]"]:checked').val();
    var address = $('#selectaddress').val();
    $.post(rp + 'searches/deleteCart', {'id': id}, function (response) {
        $.post(rp + 'checkouts/deliveryLocation', {'id': address, 'orderTypes': orderType}, function (response) {
            if (response != 'fail') {
                checkoutCart(address);
            } else {
                checkoutCart();
            }
        });
        return false
    });
    return false
}

function checkoutQtyDec(id, myid) {
    var orderType = $('input[name="data[Order][orderType]"]:checked').val();
    var address = $('#selectaddress').val();
    $.post(rp + 'searches/qtyUpdate', {'id': id, 'type': 'decrement'}, function (response) {
        $.post(rp + 'checkouts/deliveryLocation', {'id': address, 'orderTypes': orderType}, function (response) {
            if (response != 'fail') {
                checkoutCart(address);
            } else {
                checkoutCart();
            }
        });
        return false
    });
    return false
}


function checkoutQtyInc(id, myid) {
    var orderType = $('input[name="data[Order][orderType]"]:checked').val();
    var address = $('#selectaddress').val();
    $.post(rp + 'searches/qtyUpdate', {'id': id, 'type': 'increment'}, function (response) {
        $.post(rp + 'checkouts/deliveryLocation', {'id': address, 'orderTypes': orderType}, function (response) {
            if (response != 'fail') {
                checkoutCart(address);
            } else {
                checkoutCart();
            }
        });
        return false
    });
    return false
}

function checkoutCart(addr) {

    var deltype = $.trim($("input[name='data[Order][orderType]']:checked").val());
    var storeId = $('#store_id').val();
    var voucherCode = $.trim($('#vouchers_' + storeId).val());

    $.post(rp + 'checkouts/cart', {'checkdeltype': deltype, 'google_address': addr}, function (response) {
        // alert(response);
        var data = response.split("||@@||");
        total = parseFloat(data[1]).toFixed(2);
        $(".cartsubtotal").html(format(total));
        $("#checkoutdetailswrapper").html(data[2]);
        //$(".cart-items").mCustomScrollbar();
    });

    if (voucherCode != '') {
        voucherCheck(storeId);
    }
    return false
}

function storeDelType(val) {
    if (val == 'Delivery') {
        $("#deliverytab").show();
        cart();
    } else {
        $("#deliverytab").hide();
        cart();
    }
}

function checkDelType(val) {
    var address = $('#selectaddress').val();
    var orderType = $('input[name="data[Order][orderType]"]:checked').val();
    if (val == 'Delivery') {
        $.post(rp + 'checkouts/deliveryLocation', {'id': address, 'orderTypes': orderType}, function (response) {
            if (response == 'fail') {
                $("#checkdeliverytab").hide();
                checkoutCart();
            } else {
                $("#checkdeliverytab").show();
                checkoutCart(address);
            }
        });
    } else {
        $("#checkdeliverytab").hide();
        checkoutCart();
    }
}

function addnewCard() {

    $('#error').html('');
    $('#error').removeClass('error');

    $('#already').hide();
    $('#newCard').show();
}

function alreadyCard() {

    $('#error').html('');
    $('#error').removeClass('error');

    $('#already').show();
    $('#newCard').hide();
}