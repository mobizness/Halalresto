
function PrintElem() {

    var mywindow = window.open('', 'Impress');
    mywindow.document.open('text/html');
    mywindow.document.write('<html><head><title>Le reçu</title>');
    mywindow.document.write('<link href="https://halal-resto.fr/assets/css/bootstrap.min.css " rel="stylesheet" type="text/css">');
    mywindow.document.write('<link href="https://halal-resto.fr/assets/css/common_new.css" rel="stylesheet" type="text/css">');
    mywindow.document.write('<style> #headings { left: 50px; position: relative;} body{width:25% !important; margin: 5px !important;} table{ font-size: 15px !important;} body{font-size: 15px !important;}</style>');
    mywindow.document.write('</head><body style="" >');
    mywindow.document.write('<div class="row" style="width:440px; margin:0 auto; font-size:15px !important;"><div id="headings" class="col-sm-12"><center><img style="width:20%;" src="https://halal-resto.fr/assets/images/receipt-logo.png" />');
    mywindow.document.write('<p>Ethique Food<br />12 place carnot<br />93110 Rosny sous bois<br />https://halal-resto.fr</p></center></div></div>');
    mywindow.document.write($(".printReceipts").html());
    mywindow.document.write('</body></html>');
    mywindow.document.close(); // necessary for IE >= 10

    var myDelay = setInterval(checkReadyState, 10);

    function checkReadyState() {
        if (mywindow.document.readyState == "complete") {
            clearInterval(myDelay);
            mywindow.focus(); // necessary for IE >= 10

            mywindow.print();
            mywindow.close();
        }
    }
    return true;
}

function PrintMenu() {

    var mywindow = window.open('', 'Impress');
    mywindow.document.open('text/html');
    mywindow.document.write('<html><head><title>Le reçu</title>');
    mywindow.document.write('<link href="https://halal-resto.fr/assets/css/bootstrap.min.css " rel="stylesheet" type="text/css">');
    mywindow.document.write('<link href="https://halal-resto.fr/assets/css/common_new.css" rel="stylesheet" type="text/css">');
    mywindow.document.write('<style> #headings { left: 50px; position: relative;} body{width:25% !important; margin: 5px !important;} table{ font-size: 15px !important;} body{font-size: 15px !important;}</style>');
    mywindow.document.write('</head><body style="" >');
    mywindow.document.write('<div class="row" style="width:440px; margin:0 auto; font-size:15px !important;"><div id="headings" class="col-sm-12"><center><img style="width:20%;" src="https://halal-resto.fr/assets/images/receipt-logo.png" />');
    mywindow.document.write('<p>Ethique Food<br />12 place carnot<br />93110 Rosny sous bois<br />https://halal-resto.fr</p></center></div></div>');
    mywindow.document.write($(".printMenu").html());
    mywindow.document.write('</body></html>');
    mywindow.document.close(); // necessary for IE >= 10

    var myDelay = setInterval(checkReadyState, 10);

    function checkReadyState() {
        if (mywindow.document.readyState == "complete") {
            clearInterval(myDelay);
            mywindow.focus(); // necessary for IE >= 10

            mywindow.print();
            mywindow.close();
        }
    }
    return true;
}

function PrintDishes(i) {
    if ($(".dishmenu tr").length > 0 && i < $(".dishmenu tr").length) {
        printDishesOneByOne($('.dishmenu tr').eq(i).html(), i);
    }
}

function printDishesOneByOne(tableRow, i) {
    
    var mywindow = window.open('', 'Impress');
    mywindow.document.open('text/html');
    mywindow.document.write('<html><head><title>Le reçu</title>');
    mywindow.document.write('<link href="https://halal-resto.fr/assets/css/bootstrap.min.css " rel="stylesheet" type="text/css">');
    mywindow.document.write('<link href="https://halal-resto.fr/assets/css/common_new.css" rel="stylesheet" type="text/css">');
    mywindow.document.write('<style> #headings { left: 50px; position: relative;} body{width:25% !important; margin: 5px !important;} table{ font-size: 15px !important;} body{font-size: 15px !important;}</style>');
    mywindow.document.write('</head><body style="" >');
    mywindow.document.write('<div class="row" style="width:440px; margin:0 auto; font-size:15px !important;"><div id="headings" class="col-sm-12"><center><img style="width:20%;" src="https://halal-resto.fr/assets/images/receipt-logo.png" />');
    mywindow.document.write('<p>Ethique Food<br />12 place carnot<br />93110 Rosny sous bois<br />https://halal-resto.fr</p></center></div></div>');
    mywindow.document.write('<div  class="printDishes"><div class="row"><div class="order_detail_bottom col-sm-12" style="">');
    mywindow.document.write($("#mainheadDishes").html());
    mywindow.document.write('<table class="dishmenu table borderless"><thead><tr><th>Quantité</th><th>Désignation</th></tr></thead><tbody><tr>');
    mywindow.document.write(tableRow);
    mywindow.document.write('</tr></tbody></table></div></div></div>');
    mywindow.document.write('</body></html>');
    mywindow.document.close(); // necessary for IE >= 10

    var myDelay = setInterval(checkReadyState, 10);

    function checkReadyState() {
        if (mywindow.document.readyState == "complete") {
            clearInterval(myDelay);
            mywindow.focus(); // necessary for IE >= 10
            mywindow.print();
            mywindow.close();
            i++;
            PrintDishes(i);
        }
    }

}


jQuery(document).ready(function () {
    var DriverStoreAddForm = jQuery("#DriverStoreAddForm").validate({
        rules: {
            "data[Driver][driver_name]": {
                required: true,
            },
            "data[Driver][driver_email]": {
                required: true,
                email: true,
            },
            "data[User][username]": {
                required: true,
                number: true,
            },
            "data[User][password]": {
                required: true,
            },
            "data[User][conformpassword]": {
                required: true,
                //equalsTo:'#UserPassword',
            },
            "data[Driver][address]": {
                required: true,
            },
            "data[Driver][license_no]": {
                required: true,
            },
        },
        messages: {
            "data[Driver][driver_name]": {
                required: "Veuillez entrer le nom du coursier",
            },
            "data[Driver][driver_email]": {
                required: "Entrez un email SVP",
            },
            "data[User][username]": {
                required: "Entrez votre numéro de téléphone SVP",
            },
            "data[User][password]": {
                required: "Entrez le mot de passe",
            },
            "data[User][conformpassword]": {
                required: "Confirmer le mot de passe SVP",
            },
            "data[Driver][address]": {
                required: "Entrez une adresse SVP",
            },
            "data[Driver][license_no]": {
                required: "Veuillez entrer le n° de permis",
            },
        }
    });

    var DriverStoreEditForm = jQuery("#DriverStoreEditForm").validate({
        rules: {
            "data[Driver][driver_name]": {
                required: true,
            },
            "data[Driver][driver_email]": {
                required: true,
                email: true,
            },
            "data[Driver][driver_phone]": {
                required: true,
                number: true
            },
            "data[Driver][address]": {
                required: true,
            },
            "data[Driver][license_no]": {
                required: true,
            },
        },
        messages: {
            "data[Driver][driver_name]": {
                required: "Veuillez entrer le nom du coursier",
            },
            "data[Driver][driver_email]": {
                required: "Entrez un email SVP",
            },
            "data[Driver][driver_phone]": {
                required: "Entrez votre numéro de téléphone SVP",
            },
            "data[Driver][address]": {
                required: "Entrez une adresse SVP",
            },
            "data[Driver][license_no]": {
                required: "Veuillez entrer le n° de permis",
            },
        }
    });



    var VehicleStoreAddvehicleForm = jQuery("#VehicleStoreAddvehicleForm").validate({
        rules: {
            "data[Vehicle][vehicle_name]": {
                required: true,
            },
            "data[Vehicle][model_name]": {
                required: true,
            },
            "data[Vehicle][color]": {
                required: true,
            },
            "data[Vehicle][year]": {
                required: true,
                number: true
            },
            "data[Vehicle][vehicle_no]": {
                required: true,
            },
        },
        messages: {
            "data[Vehicle][vehicle_name]": {
                required: "Entrez le nom du véhicule svp",
            },
            "data[Vehicle][model_name]": {
                required: "Entrez le svp modèle de véhicule",
            },
            "data[Vehicle][color]": {
                required: "Entrez le svp couleur de véhicule",
            },
            "data[Vehicle][year]": {
                required: "Entrez le svp année",
            },
            "data[Vehicle][vehicle_no]": {
                required: "Entrez le svp véhicule immatriculation",
            },
        }
    });



    var VehicleStoreEditvehicleForm = jQuery("#VehicleStoreEditVehicleForm").validate({
        rules: {
            "data[Vehicle][vehicle_name]": {
                required: true,
            },
            "data[Vehicle][model_name]": {
                required: true,
            },
            "data[Vehicle][color]": {
                required: true,
            },
            "data[Vehicle][year]": {
                required: true,
                number: true
            },
            "data[Vehicle][vehicle_no]": {
                required: true,
            },
        },
        messages: {
            "data[Vehicle][vehicle_name]": {
                required: "Entrez le nom du véhicule svp",
            },
            "data[Vehicle][model_name]": {
                required: "Entrez le svp modèle de véhicule",
            },
            "data[Vehicle][color]": {
                required: "Entrez le svp couleur de véhicule",
            },
            "data[Vehicle][year]": {
                required: "Entrez le svp année",
            },
            "data[Vehicle][vehicle_no]": {
                required: "Entrez le svp véhicule immatriculation",
            },
        }
    });

});


//Clear Console
function clearConsole() {
    if (window.console || window.console.firebug) {
        //console.clear();
    }
    setTimeout(function () {
        clearConsole();
    }, 1000)
}



//Update map when mouse enter and leave for map
function updateOrderMap() {

    $.post(rp + '/AjaxAction', {'Action': 'orderManage'}, function (response) {

        response = response.split('@@@@');
        if (response[0] != '') {
            var data = JSON.parse(response[0]);

            $.each(data, function (key, value) {

                var driverName = '<span class="tdnotassign">Pas encore affecté</span>';
                var orderId = data[key].Order.id;
                var status = '<span>' + data[key].Order.status + '</span>';

                if (data[key].Order.status != 'Accepted') {
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
                    $('#orderDetails' + orderId).remove();
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
    $.post(rp + '/store/orders/orderStatus', {'orderId': orderId, 'status': 'Accepted'}, function (response) {
        //$('#orderList_'+orderId).remove();
    });
}




//Assign Order
function assignOrder(ord, driver) {

    $('#assign' + driver).hide();
    $('#waiting' + driver).show();
    $.post(rp + '/drivers/assignOrder/' + ord + '/' + driver,
            function (response) {
                if (response == 1) {
                    window.location.href = rp + '/store/Orders/order';
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

function trackOrder(orderId) {
    $('#trackingContent').html('');
    $.post(rp + '/AjaxAction', {orderId, 'Action': 'OrderStatus'}, function (response) {

        $('#trackingContent').html(response);
    });
    return false;
}


$(document).ready(function () {
    $('#sample_12').dataTable({
        columnDefs: [
            {
                "bSortable": false,
                "aTargets": ["no-sort"]
            }
        ]
    });

    $('.sample_12').dataTable({
        columnDefs: [
            {
                "bSortable": false,
                "aTargets": ["no-sort"]
            }
        ]
    });

    $('#sample_11').dataTable({
        columnDefs: [
            {
                "bSortable": false,
                "aTargets": ["no-sort"]
            }
        ],
        order: [[3, 'asc']]
    });

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

$('.statusLog').on('click', function () {
    var id = $(this).attr('id');
    var driverId = id.replace('log', '')
    $.post(rp + '/MobileApi/request', {'action': 'DriverLogOut', 'from': 'site', 'driverid': driverId}, function (response) {
        location.reload();
        return false;
    });
});