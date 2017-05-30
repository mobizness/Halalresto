function statesList() {
	var id = $('#SitesettingSiteCountry').val();
	$.post('locations',{'id':id, 'model':'State'}, function(response) {
		$("#SitesettingSiteState").html(response);
	});
}




function citiesList() {
	var id = $('#SitesettingSiteState').val();
	$.post('locations',{'id':id, 'model':'City'}, function(response) {
		$("#SitesettingSiteCity").html(response);
	});
}

function locationList() {
	var id = $('#SitesettingSiteCity').val();
	$.post('locations',{'id':id, 'model':'Location'}, function(response) {
		$("#SitesettingSiteZip").html(response);
	});
}

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
		$("#contactError").html("Entrer l’email de l’admin svp");
		$("#SitesettingAdminEmail").focus();
		return false;		
	} else if(SitesettingContactUsEmail == ''){
        $("[href=#contact]").trigger('click');
		$("#contactError").html("Entrer l’email du contactez-nous svp");
		$("#SitesettingContactUsEmail").focus();
		return false;		
	} else if(SitesettingInvoiceEmail == ''){
        $("[href=#contact]").trigger('click');
		$("#contactError").html("Entrer l’email de facturation svp");
		$("#SitesettingInvoiceEmail").focus();
		return false;		
	} else if(SitesettingContactPhone == ''){
        $("[href=#contact]").trigger('click');
		$("#contactError").html("Please enter site contact phone");
		$("#SitesettingContactPhone").focus();
		return false;		
	} else if(SitesettingOrderEmail == ''){
        $("[href=#contact]").trigger('click');
		$("#contactError").html("Entrer l’email pour la réception des commandes svp");
		$("#SitesettingOrderEmail").focus();
		return false;		
	} else if(SitesettingSiteAddress == ''){
        $("[href=#location]").trigger('click');
		$("#locationError").html("Entrer l’adresse du site svp");
		$("#SitesettingSiteAddress").focus();
		return false;		
	} else if(SitesettingSiteCountry == ''){
        $("[href=#location]").trigger('click');
		$("#locationError").html("Please enter site country");
		$("#SitesettingSiteCountry").focus();
		return false;		
	} else if(SitesettingSiteState == ''){
        $("[href=#location]").trigger('click');
		$("#locationError").html("Please enter site state");
		$("#SitesettingSiteState").focus();
		return false;		
	} else if(SitesettingSiteCity == ''){
        $("[href=#location]").trigger('click');
		$("#locationError").html("Please enter site city");
		$("#SitesettingSiteCity").focus();
		return false;		
	} else if(SitesettingSiteZip == ''){
        $("[href=#location]").trigger('click');
		$("#locationError").html("Please enter site zipcode/area name");
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
		$("#invoiceError").html("Please enter VAT");
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
	}

}