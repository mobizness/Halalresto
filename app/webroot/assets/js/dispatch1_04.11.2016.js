jQuery(document).ready(function() {
	var DriverStoreAddForm = jQuery("#DriverStoreAddForm").validate({
		rules: {
			"data[Driver][driver_name]": {
				required: true,
			},
			"data[Driver][driver_email]": {
				required: true,
				email:true,
			},
			"data[User][username]": {
				required: true,
				number:true,
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
				required: "Please enter the driver name",
			},
			"data[Driver][driver_email]": {
				required: "Please enter the email",
			},
			"data[User][username]": {
				required: "Please enter the Phone number",
			},
			"data[User][password]": {
				required: "Please enter the password",
			},
			"data[User][conformpassword]": {
				required: "Please enter the confirm password",
			},
			"data[Driver][address]": {
				required: "Please enter the address",
			},
			"data[Driver][license_no]": {
				required: "Please enter the license no",
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
				email:true,
			},
			"data[Driver][driver_phone]": {
				required: true,
				number : true
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
				required: "Please enter the driver name",
			},
			"data[Driver][driver_email]": {
				required: "Please enter the email",
			},
			"data[Driver][driver_phone]": {
				required: "Please enter the phone number",
			},
			"data[Driver][address]": {
				required: "Please enter the address",
			},
			"data[Driver][license_no]": {
				required: "Please enter the license no",
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
				number : true
			},
			"data[Vehicle][vehicle_no]": {
				required: true,
			},
		},
		messages: { 
			"data[Vehicle][vehicle_name]": {
				required: "Please enter the vehicle name",
			},
			"data[Vehicle][model_name]": {
				required: "Please enter the vehicle model",
			},
			"data[Vehicle][color]": {
				required: "Please enter the vehicle color",
			},
			"data[Vehicle][year]": {
				required: "Please enter the year",
			},
			"data[Vehicle][vehicle_no]": {
				required: "Please enter the vehicle no",
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
				number : true
			},
			"data[Vehicle][vehicle_no]": {
				required: true,
			},
		},
		messages: { 
			"data[Vehicle][vehicle_name]": {
				required: "Please enter the vehicle name",
			},
			"data[Vehicle][model_name]": {
				required: "Please enter the vehicle model",
			},
			"data[Vehicle][color]": {
				required: "Please enter the vehicle color",
			},
			"data[Vehicle][year]": {
				required: "Please enter the year",
			},
			"data[Vehicle][vehicle_no]": {
				required: "Please enter the vehicle no",
			},
		}
	});
	
});


//Clear Console
function clearConsole() {
    if(window.console || window.console.firebug) {
        //console.clear();
    }
    setTimeout(function() {
        clearConsole();
    }, 1000)
}



//Update map when mouse enter and leave for map
function updateOrderMap() {

    $.post(rp+'/AjaxAction',{'Action':'orderManage'}, function(response) {

    	response = response.split('@@@@');
        if (response[0] != '') {
            var data    = JSON.parse(response[0]);

            $.each(data, function(key, value) {

            	var driverName = '<span class="tdnotassign">Not Yet Assigned</span>';
                var orderId = data[key].Order.id;
                var status  = '<span>'+data[key].Order.status+'</span>';
                
                if (data[key].Order.status != 'Accepted') {
                	driverName = '<span class="tddriver">'+data[key].Driver.driver_name+'</span>';
                	$('#icon'+orderId).removeClass('buttonEdit');
                    $('#icon'+orderId).html('');
                    if (data[key].Order.status != 'Delivered') {
                        $('#orderDisclaim'+orderId).html('<a class="buttonEdit" href="javascript:void(0);" onclick="return disclaimOrder('+orderId+');"><i class="fa fa-ban"></i></a>');
                    }

                    if (data[key].Order.status == 'Collected') {
                    	$('#status'+orderId).html('Picked up');
                    } else {
                    	$('#status'+orderId).html(status);
                    }

                } else {
                    var icon = '<i class="fa fa-car"></i>';
                    $('#icon'+orderId).addClass('buttonEdit');
                    $('#icon'+orderId).html(icon);
                    $('#orderDisclaim'+orderId).html('');
                    $('#status'+orderId).html(status);
                }
                $('#driver'+orderId).html(driverName);
            });
        }


        if (response[1] != '') {
            var completeData    = JSON.parse(response[1]);
            $.each(completeData, function(key, value) {
                var orderId = completeData[key].Order.id;
                var status  = '<span>'+completeData[key].Order.status+'</span>';
                $('#status'+orderId).html(status);
                if (completeData[key].Order.status == 'Delivered') {
                    $('#orderDetails'+orderId).remove(); 
                }
            });
        }
        setTimeout(function() {
            updateOrderMap();
        }, 2000)
        return false;
    });
}


function disclaimOrder(orderId) {
	$.post(rp+'/store/orders/orderStatus',{'orderId':orderId, 'status':'Accepted'}, function(response) {
		//$('#orderList_'+orderId).remove();
	});
}




//Assign Order
function assignOrder(ord, driver) {
	
    $('#assign'+driver).hide();
    $('#waiting'+driver).show();
    $.post(rp+'/drivers/assignOrder/'+ord+'/'+driver,
        function(response) {
            if (response == 1) {
                window.location.href = rp+'/store/Orders/order';
                return false;
                
            }
        });
    return false;
}

function viewTrack(ordId) {

    $('#trackOrderId').val(ordId);
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


$(document).ready(function(){	
	$('#sample_12').dataTable( {
		columnDefs: [
		   { 
		   	 "bSortable" : false,
		   	 "aTargets" : [ "no-sort" ]
		   }
		]
	});

	$('#sample_11').dataTable( {
		columnDefs: [
			{
				"bSortable" : false,
				"aTargets" : [ "no-sort" ]
			}
		],
		order: [[ 3, 'asc' ]]
	});

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

});

$('.statusLog').on('click', function() {
    var id = $(this).attr('id');
    var driverId = id.replace('log', '')
    $.post(rp+'/MobileApi/request',{'action':'DriverLogOut', 'from':'site', 'driverid':driverId}, function(response) {
        location.reload();
        return false;
    });
});