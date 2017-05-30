jQuery().ready(function() {

	var StateAddvalidator = jQuery("#StateAdminAddForm").validate({
		rules: {
			"data[State][state_name]": {
				required: true,
			},
			"data[State][country_id]":{
				required: true,
			}
		},
		messages: { 
			"data[State][state_name]": {
				required: "Sélectionner un département",
			},
			"data[State][country_id]": {
				required: "Sélectionnez le pays svp",
			}

		}
	});


	var StateEditvalidator = jQuery("#StateAdminEditForm").validate({
		rules: {
			"data[State][state_name]": {
				required: true,
			},
			"data[State][country_id]":{
				required: true,
			}
		},
		messages: { 
			"data[State][state_name]": {
				required: "Sélectionner un département",
			},
			"data[State][country_id]": {
				required: "Sélectionnez le pays svp",
			}

		}
	});

	var CountryAddvalidator = jQuery("#CountryAdminAddForm").validate({
		rules: {
			"data[Country][country_name]": {
				required: true,
			},
			"data[Country][iso]":{
				required: true,
			},
			"data[Country][phone_code]":{
				required: true,
				number:true,
			},
			"data[Country][currency_name]":{
				required: true,
			},
			"data[Country][currency_code]":{
				required: true,
			},
			"data[Country][currency_symbol]":{
				required: true,
			}

		},
		messages: { 
			"data[Country][country_name]": {
				required: "Entrez le nom de la pays svp",
			},
			"data[Country][iso]": {
				required: "Entrez le ISO svp",
			},
			"data[Country][phone_code]":{
				required: "Veuillez entrer l’indicatif téléphonique",
			},
			"data[Country][currency_name]":{
				required: "Entrer le nom de la devise svp",
			},
			"data[Country][currency_code]":{
				required: "Enrer le code de la devise svp",
			},
			"data[Country][currency_symbol]":{
				required: "Entrer le symbole de la devise svp",
			}

		}
	});

	var CountryEditvalidator = jQuery("#CountryAdminEditForm").validate({
		rules: {
			"data[Country][country_name]": {
				required: true,
			},
			"data[Country][iso]":{
				required: true,
			},
			"data[Country][phone_code]":{
				required: true,
				number:true,
			},
			"data[Country][currency_name]":{
				required: true,
			},
			"data[Country][currency_code]":{
				required: true,
			},
			"data[Country][currency_symbol]":{
				required: true,
			}

		},
		messages: { 
			"data[Country][country_name]": {
				required: "Entrez le nom de la pays svp",
			},
			"data[Country][iso]": {
				required: "Entrez le ISO svp",
			},
			"data[Country][phone_code]":{
				required: "Veuillez entrer l’indicatif téléphonique",
			},
			"data[Country][currency_name]":{
				required: "Entrer le nom de la devise svp",
			},
			"data[Country][currency_code]":{
				required: "Enrer le code de la devise svp",
			},
			"data[Country][currency_symbol]":{
				required: "Entrer le symbole de la devise svp",
			}

		}
	});
	var CityAddvalidator = jQuery("#CityAdminAddForm").validate({
		rules: {
			"data[City][state_id]": {
				required: true,
			},
			"data[City][city_name]":{
				required: true,
			}
		},
		messages: { 
			"data[City][state_id]": {
				required: "Sélectionner un département",
			},
			"data[City][city_name]": {
				required: "Sélectionner la ville SVP",
			}

		}
	});
	var CityEditvalidator = jQuery("#CityAdminEditForm").validate({
		rules: {
			"data[City][state_id]": {
				required: true,
			},
			"data[City][city_name]":{
				required: true,
			}
		},
		messages: { 
			"data[City][state_id]": {
				required: "Sélectionner un département",
			},
			"data[City][city_name]": {
				required: "Sélectionner la ville SVP",
			}

		}
	});

	var LocationAddvalidator = jQuery("#LocationAdminAddForm").validate({
		rules: {
				"data[Location][state_id]": {
					required: true,
					},
				"data[Location][city_id]":{
					required: true,
				},
				"data[Location][area_name]":{
					required: true,
				},
				"data[Location][zip_code]":{
					required: true,
					number:true,
				}	
			},
		messages: { 
			"data[Location][state_id]": {
				required: "Sélectionner un département",
			},
			"data[Location][city_id]": {
				required: "Sélectionner la ville SVP",
			},
			"data[Location][area_name]":{
					required: "Entrez votre région svp",
			},
			"data[Location][zip_code]":{
					required: "Entrez votre code postal svp",
			}
		}
	});

	var LocationEditvalidator = jQuery("#LocationAdminEditForm").validate({
		rules: {
				"data[Location][state_id]": {
					required: true,
					},
				"data[Location][city_id]":{
					required: true,
				},
				"data[Location][area_name]":{
					required: true,
				},
				"data[Location][zip_code]":{
					required: true,
					number:true,
				}	
			},
		messages: { 
			"data[Location][state_id]": {
				required: "Sélectionner un département",
			},
			"data[Location][city_id]": {
				required: "Sélectionner la ville SVP",
			},
			"data[Location][area_name]":{
					required: "Entrez votre région svp",
			},
			"data[Location][zip_code]":{
					required: "Entrez votre code postal svp",
			}
		}
	});
});
//State Fillter Process
function stateFillter() {
    var id = $('#CityCountryId').val();
    $.post(rp+'/admin/Cities/stateFillter',{'id':id}, function(response) {
        $("#CityStateId").html(response);
       
	})
}
//function cityFillters() {
//	var id = $('#SitesettingSiteState').val();
//	$.post(rp+'/admin/States/cityList',{'id':id}, function(response) {
//		$("#CityStateId").html(response);
//
//	})
//}
function stateFillters() {
	var id = $('#SitesettingSiteCountry').val();
	$.post(rp+'/admin/Cities/stateFillter',{'id':id}, function(response) {
		$("#SitesettingSiteState").html(response);

	})
}
//City Fillter Process
function cityFillters() {
	var id = $('#SitesettingSiteState').val();
	$.post(rp+'/admin/Locations/cityFillter',{'id':id}, function(response) {
		$("#SitesettingSiteCity").html(response);

	})
}

function cityFillter() {
    var id = $('#LocationStateId').val();
    $.post(rp+'/admin/Locations/cityFillter',{'id':id}, function(response) {
        $("#LocationCityId").html(response);
       
	})
}

function locationLists() {
	var id = $('#SitesettingSiteCity').val();
	$.post(rp+'/admin/Locations/locationFillter',{'id':id}, function(response) {
		$("#SitesettingSiteZip").html(response);

	})
}

//Check Box Selection
$(document).ready(function(){
   $(".checktable1 th input[type='checkbox']").change(function(){
        if($(this).prop("checked") == true){
            $(".checktable1 td input[type='checkbox']").prop("checked",true);
            $(".checktable1 td input[type='checkbox']").parent().addClass("checked");
            
        }
        else{
            $(".checktable1 td input[type='checkbox']").prop("checked",false);
            $(".checktable1 td input[type='checkbox']").parent().removeClass("checked");
        }
   }); 
   $(".checktable1 td input[type='checkbox']").change(function(){
        var length = $(".checktable1 tbody tr td input[type='checkbox']").length;
        var checklength = $(".checktable1 tbody tr td input[type='checkbox']:checked").length;
        if(length == checklength){
            $(".checktable1 th input[type='checkbox']").prop("checked",true);
            $(".checktable1 th input[type='checkbox']").parent().addClass("checked");
        }
        else{
            $(".checktable1 th input[type='checkbox']").prop("checked",false);
            $(".checktable1 th input[type='checkbox']").parent().removeClass("checked");
        }
   });
});