<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleApiKey; ?>&v=3&sensor=false&amp;libraries=places,geometry"></script>

<div class="page-content-wrapper">
    <div class="page-content">
        <h3 class="page-title">Ajouter Ville de livraison</h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo $siteUrl.'/store/dashboards/index';?>">Accueil</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="<?php echo $siteUrl.'/store/Categories/index';?>">Ajouter Ville de livraison</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="#">Ajouter Ville de livraison</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="portlet box blue-hoki">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-user"></i>Ajouter Ville de livraison
                        </div>
                        <div class="tools"></div>
                    </div>
                    <div class="portlet-body form">
                        <?php 
			    echo $this->Form->create('DeliveryLocation',array('class'=>"form-horizontal"));
                        ?>
                        <div class="form-body">

                            <div class="form-group">
                                <label class="col-md-3 control-label">Select Restaurant<span class="star">*</span></label>
                                <div class="col-md-6 col-lg-4">  

                                       <?php
                                    echo $this->Form->input('DeliveryLocation.store_id',
                                    array('type'  => 'select',
                                    'class' => 'form-control',
                                    'options'=> array($stores),
                                    'empty' => __('Select Restaurant'),
                                    'label'=> false)); 
                                    ?>
                                </div>

                            </div>
                        </div>

                        <div class="form-body">

                            <div class="form-group">
                                <label class="col-md-3 control-label">Ville <span class="star">*</span></label>
                                <div class="col-md-6 col-lg-4"> <?php
										echo $this->Form->input('city_id',
													array('class' => 'form-control',
														  'label' => false,
														  'type' => 'text',
														  'onfocus' =>'initialize(this.id)')); ?>
                                </div>

                            </div>
                        </div>
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Estimation temps de livraison<span class="star">*</span></label>
                                <div class="col-md-6 col-lg-4"><?php
													echo $this->Form->input('estimate_delivery_time',
															array('class'=>'form-control',
																	'label'=>false)); ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Commande Minimum<span class="star">*</span></label>
                                <div class="col-md-6 col-lg-4"><?php
													echo $this->Form->input('minimum_order',
															array('class'=>'form-control',
																	'label'=>false)); ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Frais de livraison<span class="star">*</span></label>
                                <div class="col-md-6 col-lg-4"><?php
													echo $this->Form->input('delivery_charge',
															array('class'=>'form-control',
																	'label'=>false)); ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9"><?php
												echo $this->Form->button(__('<i class="fa fa-check"></i>'.__('Submit')),array('class'=>'btn purple')); 
												echo $this->Html->link(__('Cancel'),
																array('action' => 'index'),
																array('Class'=>'btn default')); ?>
                                </div>
                            </div>
                        </div>

                    </div><?php echo $this->Form->end();?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    /*This example displays an address form, using the autocomplete feature
     of the Google Places API to help users fill in the information.*/

    var input = document.getElementById('DeliveryLocationCityId');
    google.maps.event.addDomListener(input, 'keydown', function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
        }
    });

    var placeSearch, autocomplete, autocomplete_new;
    var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name'
    };

    function initialize(id) {

        /*Create the autocomplete object, restricting the search
         to geographical location types.*/

        autocomplete = new google.maps.places.Autocomplete(
                /** @type {HTMLInputElement} */(document.getElementById(id)),
                {types: ['(cities)'], componentRestrictions: {country: "<?php echo $siteSetting['Country']['iso']; ?>"}}
        );

        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            fillInAddress();
        });

        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            fillInAddress();
        });
    }

    /*The START and END in square brackets define a snippet for our documentation:
     [START region_fillform]*/

    function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();
        /*Get each component of the address from the place details
         and fill the corresponding field on the form.*/

        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            if (componentForm[addressType]) {
                var val = place.address_components[i][componentForm[addressType]];
                /*alert(val);
                 document.getElementById(addressType).value = val;*/
            }
        }
    }

    /*[END region_fillform]
     
     [START region_geolocation]
     Bias the autocomplete object to the user's geographical location,
     as supplied by the browser's 'navigator.geolocation' object.*/
    function geolocate() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var geolocation = new google.maps.LatLng(
                        position.coords.latitude, position.coords.longitude);
                autocomplete.setBounds(new google.maps.LatLngBounds(geolocation,
                        geolocation));
            });
        }
    }
</script>