
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