function smtpDetails () {
	if ($("#SitesettingMailOptionSMTP").is(":checked")) {
		$("#smtp").show();
	} else {
		$("#smtp").hide();
	}
}

$(document).ready(function(){
	smtpDetails();
	offlineDetails();
	$("input[name='data[Sitesetting][mail_option]']").click(function() {
		smtpDetails();
	});
	$("input[name='data[Sitesetting][offline_status]']").click(function() {
		offlineDetails();
	});

	$(".otherLanguage").click(function(){
		$("#others").slideToggle(500);

	});

	setTimeout(function(){
		$('#flashMessage').fadeOut();
	},3000);


	$(".slotTime").each(function(){
		var slotId = $(this).attr('id');
		var OpenTime = $('#'+slotId+'_opentime').val();
		var CloseTime = $('#'+slotId+'_closetime').val();

		$('#'+slotId+'_from').html(OpenTime);
		$('#'+slotId+'_to').html(CloseTime);

		var hours = Number(OpenTime.match(/^(\d+)/)[1]);
		var minutes = Number(OpenTime.match(/:(\d+)/)[1]);
		// var AMPM = OpenTime.match(/\s(.*)$/)[1];
		// if(hours<12) hours = hours+12;
		// if(hours==12) hours = hours-12;
		var sHours = hours.toString();
		var sMinutes = minutes.toString();
		if(hours<10) sHours = "0" + sHours;
		if(minutes<10) sMinutes = "0" + sMinutes;

		var open = parseFloat(sHours + "." + sMinutes) * 60;

		var hours = Number(CloseTime.match(/^(\d+)/)[1]);
		var minutes = Number(CloseTime.match(/:(\d+)/)[1]);
		// var AMPM = CloseTime.match(/\s(.*)$/)[1];
		// if(hours<12) hours = hours+12;
		// if(hours==12) hours = hours-12;
		var sHours = hours.toString();
		var sMinutes = minutes.toString();
		if(hours<10) sHours = "0" + sHours;
		if(minutes<10) sMinutes = "0" + sMinutes;

		var close = parseFloat(sHours + "." + sMinutes) * 60;


		$('#'+slotId).slider({
			range: true,
			min: 0,
			max: 1440,
			step: 15,
			values: [open, close],
			slide: function (e, ui) {
				var hours1 = Math.floor(ui.values[0] / 60);
				var minutes1 = ui.values[0] - (hours1 * 60);

				if (hours1.length == 1) hours1 = '0' + hours1;
				if (minutes1.length == 1) minutes1 = '0' + minutes1;
				if (minutes1 == 0) minutes1 = '00';
				if (hours1 >= 12) {
					if (hours1 == 12) {
						hours1 = hours1;
						minutes1 = minutes1;
					} else {
						hours1 = hours1; // hours1 = hours1 - 12;
						minutes1 = minutes1;
					}
				} else {
					hours1 = hours1;
					minutes1 = minutes1;
				}
				/*if (hours1 == 0) {
					hours1 = 0;
					minutes1 = minutes1;
				}*/

				$('#'+slotId+'_from').html(hours1 + ':' + minutes1);
				$('#'+slotId+'_opentime').val(hours1 + ':' + minutes1);

				var hours2 = Math.floor(ui.values[1] / 60);
				var minutes2 = ui.values[1] - (hours2 * 60);


				if (hours2.length == 1) hours2 = '0' + hours2;
				if (minutes2.length == 1) minutes2 = '0' + minutes2;
				if (minutes2 == 0) minutes2 = '00';
				if (hours2 >= 12) {
					if (hours2 == 12) {
						hours2 = hours2;
						minutes2 = minutes2;
					} else if (hours2 == 24) {
						hours2 = 23;
						minutes2 = "59";
					} else {
						hours2 = hours2;
						minutes2 = minutes2;
					}
				} else {
					hours2 = hours2;
					minutes2 = minutes2;
				}

				$('#'+slotId+'_to').html(hours2 + ':' + minutes2);
				$('#'+slotId+'_closetime').val(hours2 + ':' + minutes2);
			}
		});
	});

	$(".slotTimeAdd").each(function(){
		var slotId = $(this).attr('id');

		$('#'+slotId).slider({
			range: true,
			min: 0,
			max: 1440,
			step: 15,
			values: [600, 720],
			slide: function (e, ui) {
				var hours1 = Math.floor(ui.values[0] / 60);
				var minutes1 = ui.values[0] - (hours1 * 60);

				if (hours1.length == 1) hours1 = '0' + hours1;
				if (minutes1.length == 1) minutes1 = '0' + minutes1;
				if (minutes1 == 0) minutes1 = '00';
				if (hours1 >= 12) {
					if (hours1 == 12) {
						hours1 = hours1;
						minutes1 = minutes1 + " PM";
					} else {
						hours1 = hours1 - 12;
						minutes1 = minutes1 + " PM";
					}
				} else {
					hours1 = hours1;
					minutes1 = minutes1 + " AM";
				}
				if (hours1 == 0) {
					hours1 = 12;
					minutes1 = minutes1;
				}

				$('#'+slotId+'_from').html(hours1 + ':' + minutes1);
				$('#'+slotId+'_opentime').val(hours1 + ':' + minutes1);

				var hours2 = Math.floor(ui.values[1] / 60);
				var minutes2 = ui.values[1] - (hours2 * 60);

				if (hours2.length == 1) hours2 = '0' + hours2;
				if (minutes2.length == 1) minutes2 = '0' + minutes2;
				if (minutes2 == 0) minutes2 = '00';
				if (hours2 >= 12) {
					if (hours2 == 12) {
						hours2 = hours2;
						minutes2 = minutes2 + " PM";
					} else if (hours2 == 24) {
						hours2 = 11;
						minutes2 = "59 PM";
					} else {
						hours2 = hours2 - 12;
						minutes2 = minutes2 + " PM";
					}
				} else {
					hours2 = hours2;
					minutes2 = minutes2 + " AM";
				}

				$('#'+slotId+'_to').html(hours2 + ':' + minutes2);
				$('#'+slotId+'_closetime').val(hours2 + ':' + minutes2);
			}
		});
	});
});



function offlineDetails () {
	if ($("#SitesettingOfflineStatusYes").is(":checked")) {
		$("#offlineReason").show();
	} else {
		$("#offlineReason").hide();
	}
}



function validate () {

	var SitesettingSiteName 		= $.trim($("#SitesettingSiteName").val());
	var SitesettingAdminName 		= $.trim($("#SitesettingAdminName").val());
	var SitesettingAdminEmail 		= $.trim($("#SitesettingAdminEmail").val());
	var SitesettingContactUsEmail 	= $.trim($("#SitesettingContactUsEmail").val());
	var SitesettingInvoiceEmail 	= $.trim($("#SitesettingInvoiceEmail").val());
	var SitesettingContactPhone 	= $.trim($("#SitesettingContactPhone").val());
	var SitesettingOrderEmail 		= $.trim($("#SitesettingOrderEmail").val());

	var SitesettingSiteAddress		= $.trim($("#SitesettingSiteAddress").val());
	var SitesettingSiteCountry 		= $.trim($("#SitesettingSiteCountry").val());
	var SitesettingSiteState 		= $.trim($("#SitesettingSiteState").val());
	var SitesettingSiteCity 		= $.trim($("#SitesettingSiteCity").val());
	var SitesettingSiteZip 			= $.trim($("#SitesettingSiteZip").val());

	var SitesettingSmtpHost 		= $.trim($("#SitesettingSmtpHost").val());
	var SitesettingSmtpPort 		= $.trim($("#SitesettingSmtpPort").val());
	var SitesettingSmtpUsername 	= $.trim($("#SitesettingSmtpUsername").val());
	var SitesettingSmtpPassword 	= $.trim($("#SitesettingSmtpPassword").val());

	var SitesettingVatNo 			= $.trim($("#SitesettingVatNo").val());
	var SitesettingVatPercent 		= $.trim($("#SitesettingVatPercent").val());
	var SitesettingCardFee 			= $.trim($("#SitesettingCardFee").val());
	var SitesettingInvoiceDuration 	= $.trim($("#SitesettingInvoiceDuration").val());

	var SitesettingSmsToken			= $.trim($("#SitesettingSmsToken").val());
	var SitesettingSmsId 			= $.trim($("#SitesettingSmsId").val());
	var SitesettingSmsSourceNumber	= $.trim($("#SitesettingSmsSourceNumber").val());

	var SitesettingOtherLanguage	= $.trim($('#SitesettingOtherLanguage').val());

	var Sitesettingmailchimpkey		= $.trim($("#SitesettingMailchimpKey").val());
	var Sitesettingmailchimplist	= $.trim($("#SitesettingMailchimpListId").val());

	var Sitesettingfacbookapi		= $.trim($("#SitesettingFacebookApiId").val());
	var Sitesettingfacbooksecret	= $.trim($("#SitesettingFacebookSecretKey").val());

	var Sitesettinggoogleapi		= $.trim($("#SitesettingGoogleApiId").val());
	var Sitesettinggooglesecret  	= $.trim($("#SitesettingGoogleSecretKey").val());

	var SitesettingPusherKey  		= $.trim($("#SitesettingPusherKey").val());
	var SitesettingPusherSecret  	= $.trim($("#SitesettingPusherSecret").val());
	var SitesettingPusherId  		= $.trim($("#SitesettingPusherId").val());

	if(SitesettingSiteName == ''){
		$("[href=#site]").trigger('click');
		$("#siteError").html("Please enter site name");
		$("#SitesettingSiteName").focus();
		return false;
	} else if(SitesettingAdminName == ''){
		$("[href=#contact]").trigger('click');
		$("#contactError").html("Please enter admin name");
		$("#SitesettingAdminName").focus();
		return false;
	} else if(SitesettingAdminEmail == ''){
		$("[href=#contact]").trigger('click');
		$("#contactError").html("Please enter admin email");
		$("#SitesettingAdminEmail").focus();
		return false;
	} else if(SitesettingContactUsEmail == ''){
		$("[href=#contact]").trigger('click');
		$("#contactError").html("Please enter contact us email");
		$("#SitesettingContactUsEmail").focus();
		return false;
	} else if(SitesettingInvoiceEmail == ''){
		$("[href=#contact]").trigger('click');
		$("#contactError").html("Please enter invoice email");
		$("#SitesettingInvoiceEmail").focus();
		return false;
	} else if(SitesettingContactPhone == ''){
		$("[href=#contact]").trigger('click');
		$("#contactError").html("Please enter site contact phone");
		$("#SitesettingContactPhone").focus();
		return false;
	} else if(SitesettingOrderEmail == ''){
		$("[href=#contact]").trigger('click');
		$("#contactError").html("Please enter order email");
		$("#SitesettingOrderEmail").focus();
		return false;
	} else if(SitesettingSiteAddress == ''){
		$("[href=#location]").trigger('click');
		$("#locationError").html("Please enter site address");
		$("#SitesettingSiteAddress").focus();
		return false;
	} else if(SitesettingSiteCountry == ''){
        $("[href=#location]").trigger('click');
		$("#locationError").html("Please select the country");
		$("#SitesettingSiteCountry").focus();
		return false;
	} else if(SitesettingSiteState == ''){
        $("[href=#location]").trigger('click');
		$("#locationError").html("Please select the state");
		$("#SitesettingSiteState").focus();
		return false;
	} else if(SitesettingSiteCity == ''){
        $("[href=#location]").trigger('click');
		$("#locationError").html("Please select the city");
		$("#SitesettingSiteCity").focus();
		return false;
	} else if(SitesettingSiteZip == ''){
        $("[href=#location]").trigger('click');
		$("#locationError").html("Please select zipcode/area name");
		$("#SitesettingSiteZip").focus();
		return false;
	} else if ($("#SitesettingMailOptionSMTP").is(":checked")) {

		if(SitesettingSmtpHost == ''){
			$("[href=#mail]").trigger('click');
			$("#mailError").html("Please enter smtp host");
			$("#SitesettingSmtpHost").focus();
			return false;
		} else if(SitesettingSmtpPort == ''){
			$("[href=#mail]").trigger('click');
			$("#mailError").html("Please enter smtp port");
			$("#SitesettingSmtpPort").focus();
			return false;
		} else if(SitesettingSmtpUsername == ''){
			$("[href=#mail]").trigger('click');
			$("#mailError").html("Please enter smtp username");
			$("#SitesettingSmtpUsername").focus();
			return false;
		} else if(SitesettingSmtpPassword == ''){
			$("[href=#mail]").trigger('click');
			$("#mailError").html("Please enter smtp password");
			$("#SitesettingSmtpPassword").focus();
			return false;
		}
	} else if(SitesettingVatNo == ''){
		$("[href=#invoice]").trigger('click');
		$("#invoiceError").html("Please enter VAT no");
		$("#SitesettingVatNo").focus();
		return false;
	} else if(SitesettingVatPercent == ''){
        $("[href=#invoice]").trigger('click');
		$("#invoiceError").html("Please enter VAT percentage");
		$("#SitesettingVatPercent").focus();
		return false;
	} else if(SitesettingCardFee == ''){
		$("#invoiceError").html("Please enter card fee");
		$("#SitesettingCardFee").focus();
		return false;
	} else if(SitesettingInvoiceDuration == ''){
		$("[href=#invoice]").trigger('click');
		$("#invoiceError").html("Please select invoice time period");
		$("#SitesettingInvoiceDuration").focus();
		return false;
	} else if(SitesettingSmsToken == ''){
		$("[href=#sms]").trigger('click');
		$("#smsError").html("Please enter sms token id");
		$("#SitesettingSmsToken").focus();
		return false;
	} else if(SitesettingSmsId == ''){
		$("[href=#sms]").trigger('click');
		$("#smsError").html("Please enter sms auth id");
		$("#SitesettingSmsId").focus();
		return false;
	} else if(SitesettingSmsSourceNumber == ''){
		$("[href=#sms]").trigger('click');
		$("#smsError").html("Please enter sms source number");
		$("#SitesettingSmsSourceNumber").focus();
		return false;
	} else if ($('#others').is(':visible') && SitesettingOtherLanguage == '') {

		$("[href=#Language]").trigger('click');
		$("#languageError").html("Please enter other language");
		$("#SitesettingOtherLanguage").focus();
		return false;
	} else if(Sitesettingmailchimpkey == ''){
		$("[href=#mailchimp]").trigger('click');
		$("#mailchimpError").html("Please enter mailchimp key");
		$("#Sitesettingmailchimpkey").focus();
		return false;
	} else if(Sitesettingmailchimplist == ''){
		$("[href=#mailchimp]").trigger('click');
		$("#mailchimpError").html("Please enter mailchimp list");
		$("#Sitesettingmailchimplist").focus();
		return false;
	} else if(Sitesettingfacbookapi == ''){
		$("[href=#facebook]").trigger('click');
		$("#facebookError").html("Please enter facebook api key");
		$("#Sitesettingfacbookapi").focus();
		return false;
	} else if(Sitesettingfacbooksecret == ''){
		$("[href=#facebook]").trigger('click');
		$("#facebookError").html("Please enter facebook secret key");
		$("#Sitesettingfacbooksecret").focus();
		return false;
	}else if(Sitesettinggoogleapi == ''){
		$("[href=#google]").trigger('click');
		$("#googleError").html("Please enter google api key");
		$("#Sitesettinggoogleapi").focus();
		return false;
	} else if(Sitesettinggooglesecret == ''){
		$("[href=#google]").trigger('click');
		$("#googleError").html("Please enter google secret key");
		$("#Sitesettinggooglesecret").focus();
		return false;
	} else if(SitesettingPusherKey == ''){
		$("[href=#pusher]").trigger('click');
		$("#pusherError").html("Please enter pusher key");
		$("#SitesettingPusherKey").focus();
		return false;
	} else if(SitesettingPusherSecret == ''){
		$("[href=#pusher]").trigger('click');
		$("#pusherError").html("Please enter pusher secret key");
		$("#SitesettingPusherSecret").focus();
		return false;
	} else if(SitesettingPusherId == ''){
		$("[href=#pusher]").trigger('click');
		$("#pusherError").html("Please enter pusher app id");
		$("#SitesettingPusherId").focus();
		return false;
	}
}

function save () {
	//alert('You cannot access payment setting at the moment');
	//return false;
}

function storeProducts() {
	var id = $('#Storeproduct').val();
	if (id != '') {
		window.location.href = rp+'/admin/products/index/'+id;
	} else {
		$("#storeProductError").html("Please select restaurant");
	}
	return false;
}

function storeOrders() {
	var id = $('#StoreOrder').val();
	var StoreRange = $('#StoreRange').val();
	id = (id != '') ? id : 0;
	StoreRange = (StoreRange != '') ? StoreRange : 0;
	window.location.href = rp+'/admin/orders/reportIndex/'+id+'/'+StoreRange;
}

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

function importValidate() {

	var ProductStoreId 	= $.trim($("#ProductStoreId").val());
	var excel 			= $.trim($("#excel").val());
	var error = 0;

	if(ProductStoreId == ''){
		error = 1;
		$("#storeError").html("Please select restaurant");
	}

	if(excel == '') {
		error = 1;
		$("#excelError").html("Please select xls file");
	}

	if (error == 1) {
		return false;
	}
}