//customerAddressBookEdit process
function customerAddressBookEdit(id) {
    $.post(rp + 'customer/customers/editaddressbook', {'id': id}, function (response) {
        $('#editBookAddress').html(response);
        $('#editBookAddress').modal('show');
    });
}
//City Fillter Process
function cityFillters() {
    var id = $('#CustomerAddressBookStateId').val();
    $.post(rp + '/customer/customers/cityFillter', {'id': id}, function (response) {
        $("#CustomerAddressBookCityId").html(response);

    })
}
//Location Fillter Process
function locationFillters() {
    var id = $('#CustomerAddressBookCityId').val();
    $.post(rp + '/customer/customers/locationFillter', {'id': id}, function (response) {
        $("#CustomerAddressBookLocationId").html(response);

    })
}
//City Fillter Process
function cityFillter() {
    var id = $('#CustomerAddressBookStateIds').val();
    $.post(rp + '/customer/customers/cityFillter', {'id': id}, function (response) {
        $("#CustomerAddressBookCityIds").html(response);

    })
}
//Location Fillter Process
function locationFillter() {
    var id = $('#CustomerAddressBookCityIds').val();
    $.post(rp + '/customer/customers/locationFillter', {'id': id}, function (response) {
        $("#CustomerAddressBookLocationIds").html(response);

    })
}
//customer delete action
function customerdelete(id, model) {
    $.post(rp+'customer/Customers/deleteaddress',{'id':id,'model':model}, function(response) {
        $("#record"+id).remove();
    });
}

//Status Change
function statusChange(id, model) {
    $.post(rp+'customer/Customers/addressbookStatus',{'id':id,'model':model},function(response) {
    })
}

// delete card
function deletecard(id) {
    $.post(rp + 'customer/Customers/deletecard', {'id': id}, function (response) {
        $("#card" + id).remove();
    });
    $.post(rp+'AjaxAction',{'Action':'walletCards'}, function(response) {
        $("#savedCards").html(response);
    });
}

//OrderInvoice Details Print Format 
function documentPrints() {

    $('#sidebar').hide();
    $('#footer').hide();
    $('#printSpace').show();

    window.print();

    $('#printSpace').hide();
    $('#sidebar').show();
    $('#footer').show();
}

function pdfdownload(id) {
    $.post(rp + 'customer/Customers/downloadiInvoice', {'id': id}, function (response) {
    });
}
function orderid(id) {
    $('#reviewId').val(id);

}


var showimage = function(event) {

    var files = event.target.files;
    f = files[0];
    console.log(f);
    if (!f.type.match('image.*')) {
        alert('It is not Image');
        $('#CustomerImage').val('');
    }
};


$(document).ready(function () {
    $(".table").on('click', '.buttonStatus', function () {
        if ($(this).hasClass('red_bck')) {
            $(this).removeClass('red_bck');
            $(this).children("i").removeClass('fa-times').addClass("fa-check");
            $(this).attr("title","active");
        }
        else if ($(this).hasClass('yellow_bck')) {
            $(this).removeClass('yellow_bck');
            $(this).children("i").removeClass('fa-exclamation').addClass("fa-check");
            $(this).attr("title","Pending");
        }
        else {
            $(this).addClass('red_bck');
            $(this).children("i").removeClass('fa-check').addClass("fa-times");
            $(this).attr("title","Deactive");
        }

    });
    $("#forgetPage").click(function () {
        $("#forgetsmail").show();
        $("#login").hide();

    });
    $('#loginPage').click(function () {
        $("#forgetsmail").hide();
        $("#login").show();
    })
});

//Clear Console
function clearConsole() {
    if(window.console || window.console.firebug) {
        // console.clear();
    }
    setTimeout(function() {
        clearConsole();
    }, 500)
}


function updateOrderMap() {

    $.post(rp+'AjaxAction',{'Action':'customerOrderManage'}, function(response) {
        response = response.split('@@@@');

        if (response[0] != '') {
            var data    = JSON.parse(response[0]);
            $.each(data, function(key, value) {

                if (value == 'Collected') {
                    $('#status'+key).html('Picked up');
                    $('.ratings'+key).html('<a data-target="#trackid" data-toggle="modal" onclick="return viewTrack('+key+');" href="javascript:void(0);">Track Order</a>');
                } else {
                    $('.ratings'+key).html('');
                    $('#status'+key).html(value);
                }

                
            });
        }

        if (response[1] != '') {
            var completeData = JSON.parse(response[1]);
            $.each(completeData, function(key, value) {
                orderId = completeData[key].Order.id;
                $('#status'+orderId).html(completeData[key].Order.status);
                if (completeData[key].Review.id == null) {
                    $('.ratings'+orderId).html('<a data-target="#reviewPopup" data-toggle="modal" onclick="orderid('+orderId+')" href="javascript:void(0);">Review</a>');
                }
            });
        }

        setTimeout(function() {
            updateOrderMap();
        }, 2000)
        return false;
    });
}

function viewTrack(ordId) {

    $('#trackOrderId').val(ordId);

    trackings();

    $('#trackid').show();
    $('#initialmap').html('');
    $('#initialmap').load(rp+'/AjaxAction', {'Action' : 'InitialTracking'}, function(response) {
        //alert(response);
    });
    return false;
}

function trackings() {
    var ordId = $('#trackOrderId').val(); 
    
    if (ordId != '' && $('#trackid:hidden').length == 0) {
        $.post(rp+'/AjaxAction',{'OrderId':ordId,'Action':'LoadTrackingMap'}, function(response) {
            clearConsole();
            removeMapIcons();
            var result = response.split('||@@||');
            $('#TrackingMap').html(result[0]);
            //$('#trackingDistance').html(result[1]);
        });
    }
    setTimeout(function() {
        trackings();
    }, 4000);
    return false;
}

//Remove all icons from map
function removeMapIcons() {
    deleteMarkers();
    if ($('[name=direction]').val() == 'available') {
        directions1Display.setMap(null);
        directions1Display.setPanel(null);
    }
}

//Delete all marker
function deleteMarkers() {
  for (var i = 0; i < markers.length; i++) {
    markers[i].setMap(null);
  }

}

function trackOrder(orderId) {
    $('#trackingContent').html('');
    $.post(rp+'/AjaxAction', {orderId, 'Action' : 'OrderStatus'}, function(response) {
        
        $('#trackingContent').html(response);
    });
    return false;
}

// Myaccount Card selection
function cardSelection() {
    var CustomerWalletCardType = $('#CustomerWalletCardType').val();
    if (CustomerWalletCardType == 'savedCard') {
        $('#newCard').hide();
        $('#savedCards').show();
    } else {
        $('#newCard').show();
        $('#savedCards').hide();
    }
}

function walletPage(opt) {
    if (opt == 'money') {
        $('#addMoney').show();
        $('#historyWallet').hide();
    } else {
        $('#addMoney').hide();
        $('#historyWallet').show();
    }
}

