//Update map when mouse enter and leave for map
function updateOrderMap() {

    $.post(rp + '/AjaxAction', {'Action': 'orderManage'}, function (response) {
        clearConsole();
        response = response.split('@@@@');
        if (response[0] != '') {
            var data = JSON.parse(response[0]);

            $.each(data, function (key, value) {

                var driverName = '<span class="tdnotassign">Pas encore affecté</span>';
                var orderId = data[key].Order.id;
                var status = data[key].Order.status;

                if (data[key].Order.status != 'Accepted' && data[key].Order.status != 'Accepté') {
                    driverName = '<span class="tddriver">' + data[key].Driver.driver_name + '</span>';
                    $('#icon' + orderId).removeClass('buttonEdit');
                    $('#icon' + orderId).html('');
                    if (data[key].Order.status != 'Delivered') {
                        $('#orderDisclaim' + orderId).html('<a class="buttonEdit" href="javascript:void(0);" onclick="return disclaimOrder(' + orderId + ');"><i class="fa fa-ban"></i></a>');
                    }

                    if (data[key].Order.status == 'Collected') {
                        $('#status' + orderId).html('Picked up');
                    } else {
                        $('#status' + orderId).html(status);
                    }

                } else {
                    var icon = '<i class="fa fa-car"></i>';
                    $('#icon' + orderId).addClass('buttonEdit');
                    $('#icon' + orderId).html(icon);
                    $('#orderDisclaim' + orderId).html('');
                    $('#status' + orderId).html(status);
                }
                $('#driver' + orderId).html(driverName);
            });
        }


        if (response[1] != '') {
            var completeData = JSON.parse(response[1]);
            $.each(completeData, function (key, value) {
                var orderId = completeData[key].Order.id;
                var status = '<span>' + completeData[key].Order.status + '</span>';
                $('#status' + orderId).html(status);
                if (completeData[key].Order.status == 'Delivered') {
                    $('#orderList_' + orderId).remove();
                }
            });
        }
        setTimeout(function () {
            updateOrderMap();
        }, 2000)
        return false;
    });
}


function disclaimOrder(orderId) {
    $.post(rp + '/admin/orders/orderStatus', {'orderId': orderId, 'status': 'Accepted'}, function (response) {
        clearConsole();
    });
}

//Assign Order
function assignOrder(ord, driver) {

    $('#assign' + driver).hide();
    $('#waiting' + driver).show();
    $.post(rp + '/drivers/assignOrder/' + ord + '/' + driver,
            function (response) {
                if (response == 1) {
                    window.location.href = rp + '/admin/Orders/order';
                    return false;

                }
            });
    return false;
}

function viewTrack(ordId) {

    $('#trackOrderId').val(ordId);
    $('#trackid').show();
    $('#initialmap').html('');
    $('#initialmap').load(rp + '/AjaxAction', {'Action': 'InitialTracking'}, function (response) {
        //alert(response);
    });
    return false;
}

function trackings() {
    var ordId = $('#trackOrderId').val();

    if (ordId != '' && $('#trackid:hidden').length == 0) {
        $.post(rp + '/AjaxAction', {'OrderId': ordId, 'Action': 'LoadTrackingMap'}, function (response) {
            clearConsole();
            removeMapIcons();
            var result = response.split('||@@||');
            $('#TrackingMap').html(result[0]);
            //$('#trackingDistance').html(result[1]);
        });
    }
    setTimeout(function () {
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

//Clear Console
function clearConsole() {
    if (window.console || window.console.firebug) {
        //console.clear();
    }

    setTimeout(function () {
        clearConsole();
    }, 1000)
}

$('.statusLog').on('click', function () {
    var id = $(this).attr('id');
    var driverId = id.replace('log', '')
    $.post(rp + '/MobileApi/request', {'action': 'DriverLogOut', 'from': 'site', 'driverid': driverId}, function (response) {
        location.reload();
        return false;
    });
});



$(document).ready(function () {
    /*trackings();
     updateOrderMap();*/
    $(".table").on('click', '.buttonStatus', function () {
        if ($(this).hasClass('red_bck')) {
            $(this).removeClass('red_bck');
            //$(this).children("i").removeClass('fa-times').addClass("fa-check");
            $(this).children("i").text("Active");
            $(this).attr("title", "active");
        } else if ($(this).hasClass('yellow_bck')) {
            $(this).removeClass('yellow_bck');
            //$(this).children("i").removeClass('fa-exclamation').addClass("fa-check");
            $(this).children("i").text("Active");
            $(this).attr("title", "Pending");
        } else {
            $(this).addClass('red_bck');
            //$(this).children("i").removeClass('fa-check').addClass("fa-times");
            $(this).children("i").text("Deactivate");
            $(this).attr("title", "Deactive");
        }
    });
});

function trackOrder(orderId) {
    $('.ui-loadercont').show();
    $('#trackingContent').html('');
    $.post(rp + '/AjaxAction/index', {'orderId': orderId, 'Action': 'OrderStatus'}, function (response) {
        $('#trackingContent').html(response);
        $('.ui-loadercont').hide();
        $('#reportpopup').modal('show');
    });
    return false;
}

function refundPayment(orderId) {
    var check = confirm("Etes vous sur de vouloir le remboursement?");
    if ($.trim(check) == 'true') {
        $('.ui-loadercont').show();
        $.post(rp + '/admin/orders/refund/' + orderId, {'orderId': orderId, 'Action': 'OrderStatus'}, function (response) {
            $('.ui-loadercont').hide();
            if (response == 'Success') {
                var message = 'Votre remboursement a bien été effectué';
                $('#orderMessage').html(message);
                $('#orderMessage').show();
                setTimeout(function () {
                    $('#orderMessage').fadeOut();
                    location.reload();
                }, 3000);

            } else if (response != '') {
                alert(response);
            } else {
                location.reload();
            }
        });
    }
}


$(document).ready(function () {
    $("#adddeli_area").click(function () {
        $("#deliveryAppend").append('<div class="form-group"><div class="col-sm-9 col-sm-offset-3"><div class="row"><div class="col-sm-3"><input type="text" class="form-control"></div><div class="col-sm-3"><input type="text" class="form-control"></div><div class="col-sm-3"><input type="text" class="form-control"></div><div class="col-sm-3"><div class="btn btn-danger" onclick="removedeliArea(this);" >Remove</div></div></div></div></div>')
    });
});

function removedeliArea(e) {
    $(e).parents(".form-group").remove();
}