<?php 
    switch ($Action) {
        case 'HeaderCount':
    
            if (is_array($orders)):
                $orders = array_slice($orders,0,5);
    ?>
                <div class="row">
    <?php
                foreach ($orders as $nKey => $nValue):
    ?>
                        <div class="col-sm-12">
        	        		<div class="col-sm-4">
        	        		<?php echo $nValue['Order']['order_date'].' '.$nValue['Order']['order_time'] ?>
        	        		</div>
        	        		<div class="col-sm-4">
        	        		<?php echo $nValue['Order']['custom_order_id'] ?>
        	        		</div>
        	        		<div class="col-sm-4">
        	        		<?php echo stripslashes($nValue['Restaurant']['restaurant_name']) ?>
        	        		</div>
        	        	</div>
    <?php 
                endforeach;
    ?>
                </div>
    <?php
                echo '||@@||'.count($newOrders);
                $_SESSION['NewOrderCount'] = $newOrderCount;
            endif; 
        break;
        
        case 'TrackingOrder':
        
            echo $this->GoogleMap->map();
            
?>
            <div id="mapIcons"> <input id="trackId" type="hidden" value="<?php echo $orders[0]['Order']['id'] ?>">
<?php            
            
            $customerLatitude   = (isset($orders[0]['Order']['delivery_latitude'])) ? $orders[0]['Order']['delivery_latitude'] : '';
            $customerLongitude  = (isset($orders[0]['Order']['delivery_longitude'])) ? $orders[0]['Order']['delivery_longitude'] : '';
            
            $restaurantLatitude = (isset($orders[0]['Restaurant']['latitude'])) ? $orders[0]['Restaurant']['latitude'] : '';
            $restaurantLongitude = (isset($orders[0]['Restaurant']['longitude'])) ? $orders[0]['Restaurant']['longitude'] : '';
            
            #Customer
            echo $this->GoogleMap->addMarker("map_canvas", 1, array('latitude'=>$customerLatitude, 'longitude'=>$customerLongitude),array (
                    'markerIcon' => $siteUrl.'images/customer.png',
                    'windowText' => $orders[0]['Order']['customer_name'].'</br>'.$orders[0]['Order']['custom_order_id'].'</br>'.$orders[0]['Order']['order_date'].' '.$orders[0]['Order']['order_time'].'</br>'.html_entity_decode($this->Number->currency($orders[0]['Order']['total'],$siteCurrency))
                  )); 
            
            #Restaurant
            
            #if ($orders[0]['Statuses']['status'] != 'Picked up' && $orders[0]['Statuses']['status'] != 'On the way') {
                echo $this->GoogleMap->addMarker("map_canvas", 1, array('latitude'=>$restaurantLatitude, 'longitude'=>$restaurantLongitude),array (
                    'markerIcon' => $siteUrl.'images/restaurant.png',
                    'windowText' => stripslashes($orders[0]['Restaurant']['restaurant_name'])
                  ));
            #}
            
        
            if (isset($drivers)) {
                #Drivers      
                foreach ($drivers as $k => $v) {
                    echo $this->GoogleMap->addMarker("map_canvas", 1, array('latitude'=>$drivers[$k]['Drivertracking']['latitude'], 'longitude'=>$drivers[$k]['Drivertracking']['longitude']),array (
                        'markerIcon' => $siteUrl.'images/'.str_replace(' ','',strtolower($orders[0]['Statuses']['status'])).'.png',
                        'windowText' => $v['User']['firstname'].' '.$v['User']['name']
                      ));
                }
                 
            } else {
                echo $this->GoogleMap->addMarker("map_canvas", 1, array('latitude'=>$orders[0]['User']['Driver']['Drivertracking']['latitude'], 'longitude'=>$orders[0]['User']['Driver']['Drivertracking']['longitude']),array (
                    'markerIcon' => $siteUrl.'images/'.str_replace(' ','',strtolower($orders[0]['Statuses']['status'])).'.png',
                    'windowText' => $orders[0]['User']['firstname'].'</br>'.$orders[0]['Order']['custom_order_id'].'</br>'.$orders[0]['Order']['order_date'].' '.$orders[0]['Order']['order_time']
                  )); 
                  
                #Driver Direction
                if ($orders[0]['Statuses']['status'] == 'Accepted') {
                    echo $this->GoogleMap->getDirections("map_canvas", "directions1", array(
                        "from" => array("latitude" => $orders[0]['User']['Driver']['Drivertracking']['latitude'], "longitude" => $orders[0]['User']['Driver']['Drivertracking']['longitude']),
                        "to"   => array("latitude" => $restaurantLatitude, "longitude" => $restaurantLongitude)
                      ));
                } elseif ($orders[0]['Statuses']['status'] == 'Picked up' || $orders[0]['Statuses']['status'] == 'On the way') {
                    echo $this->GoogleMap->getDirections("map_canvas", "directions1", array(
                        "from" => array("latitude" => $orders[0]['User']['Driver']['Drivertracking']['latitude'], "longitude" => $orders[0]['User']['Driver']['Drivertracking']['longitude']),
                        "to"   => array("latitude" => $customerLatitude, "longitude" => $customerLongitude)
                      ));
                }
            }
            
            if ($orders['Order']['status'] != 'Delivered'):
?>
            <script>
            var trackId = $('#trackId').val();
                setTimeout(function() {
                    $.post(rp+'AjaxAction',{'OrderId':trackId,'Action':'LoadTrackingMap'}, function(response) {
                        deleteMarkers();
                        <?php if ($orders['Order']['status'] != 'Accepted'): ?>
                            directions1Display.setMap(null);
                            directions1Display.setPanel(null);
                        <?php endif; ?>
                        $('#mapIcons').html(response);
                        clearConsole();
                        return false;
                    });
                }, 5000);
            </script>
<?php
            endif;
?>
            </div>
<?php            
        break;
        
        case 'LoadTrackingMap':
        
            //echo "<pre>"; print_r($orders);
                                    
            $customerLatitude   = (isset($orders['Order']['destination_latitude'])) ? $orders['Order']['destination_latitude'] : '';
            $customerLongitude  = (isset($orders['Order']['destination_longitude'])) ? $orders['Order']['destination_longitude'] : '';
            
            $storeLatitude = (isset($orders['Order']['source_latitude'])) ? $orders['Order']['source_latitude'] : '';
            $storeLongitude = (isset($orders['Order']['source_longitude'])) ? $orders['Order']['source_longitude'] : '';
            
            #Customer
            echo $this->GoogleMap->addMarker("map_canvas", 1, array('latitude'=>$customerLatitude, 'longitude'=>$customerLongitude),array(
                    'markerIcon' => $siteUrl.'/images/customer.png',
                    'windowText' => $orders['Order']['customer_name'].'</br>'.$orders['Order']['ref_number'].'</br>'.$orders['Order']['delivery_date'].' '.$orders['Order']['created'].'</br>'.html_entity_decode($this->Number->currency($orders['Order']['order_grand_total'],$siteCurrency))
                  )); 
            
            #store
            
            #if ($orders['Statuses']['status'] != 'Picked up' && $orders['Statuses']['status'] != 'On the way') {
                echo $this->GoogleMap->addMarker("map_canvas", 1, array('latitude'=>$storeLatitude, 'longitude'=>$storeLongitude),array(
                    'markerIcon' => $siteUrl.'/images/store.png',
                    'windowText' => stripslashes($orders['Store']['store_name'])
                  ));
            #}
            
        
            if (isset($drivers)) {
                #Drivers      
                foreach ($drivers as $k => $v) {
                    echo $this->GoogleMap->addMarker("map_canvas", 1, array('latitude'=>$drivers[$k]['DriverTracking']['driver_latitude'], 'longitude'=>$drivers[$k]['DriverTracking']['driver_longitude']),array(
                        'markerIcon' => $siteUrl.'/images/'.str_replace(' ','',strtolower($orders['Order']['status'])).'.png',
                        'windowText' => $v['Driver']['driver_name']
                      ));
                }
                echo '||@@||';
            } else {
                echo $this->GoogleMap->addMarker("map_canvas", 1, array('latitude'=>$Driver['DriverTracking']['driver_latitude'], 'longitude'=>$Driver['DriverTracking']['driver_longitude']),array(
                    'markerIcon' => $siteUrl.'/images/'.str_replace(' ','',strtolower($orders['Order']['status'])).'.png',
                    'windowText' => $Driver['Driver']['driver_name'].'</br>'.$orders['Order']['ref_number'].'</br>'.$orders['Order']['delivery_date'].' '.$orders['Order']['created']
                  )); 
                  
                #Driver Direction
                if ($orders['Order']['status'] == 'Driver Accepted') {
                    echo $this->GoogleMap->getDirections("map_canvas", "directions1", array(
                        "from" => array("latitude" => $Driver['DriverTracking']['driver_latitude'], "longitude" => $Driver['DriverTracking']['driver_longitude']),
                        "to"   => array("latitude" => $storeLatitude, "longitude" => $storeLongitude)
                      ));
                    echo "<input type='hidden' name='direction' value='available'>";
                    echo '||@@||';
?>
                    <span>Aproximate Distance To Restaurant : <?php echo $orders['distance']['distanceText']; ?></span>
                    <span>Aproximate Time To Restaurant : <?php echo $orders['distance']['durationText'] ?></span>
<?php
                } elseif ($orders['Order']['status'] == 'Collected') {
                    echo $this->GoogleMap->getDirections("map_canvas", "directions1", array(
                        "from" => array("latitude" => $Driver['DriverTracking']['driver_latitude'], "longitude" => $Driver['DriverTracking']['driver_longitude']),
                        "to"   => array("latitude" => $customerLatitude, "longitude" => $customerLongitude)
                      ));
                    echo "<input type='hidden' name='direction' value='available'>";
                    echo '||@@||';
?>
                    <span>Aproximate Distance To Customer : <?php echo $orders['distance']['distanceText']; ?></span>
                    <span>Aproximate Time To Customer : <?php echo $orders['distance']['durationText'] ?></span>
<?php
                } elseif ($orders['Order']['status'] == 'Delivered') {
                    echo '||@@||';
                    echo 'This order was completed by '.$Driver['Driver']['driver_name'];
                }
            }
            
            
        break;
        
        case 'LoadDashboardMap':
            
            $rest = array();
            
            #Available Drivers
            if (is_array($drivers)):
                foreach ($drivers as $val):
                    $dLatitude  = $val['Drivertracking']['latitude'];
                    $dLongitude = $val['Drivertracking']['longitude'];
                    if ($dLatitude != '' && $dLongitude != ''):
                        echo $this->GoogleMap->addMarker("map_canvas", 1, array('latitude'=>$dLatitude, 'longitude'=>$dLongitude),array (
                            'markerIcon' => $siteName.'/images/new.png',
                            'windowText' => $val['User']['firstname'].'</br>'.$val['Vehicle']['vehicle_name'].' '.$val['Vehicle']['model_name']
                          ));
                    endif;
                endforeach;
            endif;
             
            if (is_array($orders)) {
                                               
                foreach ($orders as $key=>$value) {
                    
                    $rest[$orders[$key]['Restaurant']['id']] .= ($value['Statuses']['status'] != 'Delivered') ? '<br/><br/>'.$value['Order']['custom_order_id'].'</br>'.$value['Order']['order_date'].' '.$value['Order']['order_time'] : '';
                    
                    
                    $deliveryLatitude       = (isset($value['Order']['delivery_latitude'])) ? $value['Order']['delivery_latitude'] : '';
                    $deliveryLongitude      = (isset($value['Order']['delivery_longitude'])) ? $value['Order']['delivery_longitude'] : '';
                    
                    $restaurantLatitude     = (isset($value['Restaurant']['latitude'])) ? $value['Restaurant']['latitude'] : '';
                    $restaurantLongitude    = (isset($value['Restaurant']['longitude'])) ? $value['Restaurant']['longitude'] : '';
                    
                    $driverLatitude         = (isset($value['User']['Driver']['Drivertracking']['latitude'])) ? $value['User']['Driver']['Drivertracking']['latitude'] : '';
                    $driverLongitude        = (isset($value['User']['Driver']['Drivertracking']['longitude'])) ? $value['User']['Driver']['Drivertracking']['longitude'] : '';
                    
                    if ($value['Statuses']['status'] != 'Delivered') {
                        
                        #Customer
                        if ($deliveryLatitude != '' && $deliveryLongitude != '') {
                            echo $this->GoogleMap->addMarker("map_canvas", 1, array('latitude'=>$deliveryLatitude, 'longitude'=>$deliveryLongitude),array (
                                'markerIcon' => $siteUrl.'images/customer.png',
                                'windowText' => $value['Order']['custom_order_id'].'</br>'.$value['Order']['order_date'].' '.$value['Order']['order_time'].'</br>'.html_entity_decode($this->Number->currency($value['Order']['total'],$siteCurrency))
                              ));
                        }
                         
                        
                        #Restaurant
                        if ($restaurantLatitude != '' && $restaurantLongitude != '') {
                            echo $this->GoogleMap->addMarker("map_canvas", 1, array('latitude'=>$restaurantLatitude, 'longitude'=>$restaurantLongitude),array (
                                'markerIcon' => $siteUrl.'images/restaurant.png',
                                'windowText' => stripslashes($value['Restaurant']['restaurant_name']).$rest[$value['Restaurant']['id']]
                              ));
                        }
                        
                        
                        #Drivers  
                        if ($driverLatitude != '' && $driverLongitude != '') {
                            echo $this->GoogleMap->addMarker("map_canvas", 1, array('latitude'=>$driverLatitude, 'longitude'=>$driverLongitude),array (
                                'markerIcon' => $siteUrl.'images/'.str_replace(' ','',strtolower($value['Statuses']['status'])).'.png',
                                'windowText' => $value['Order']['custom_order_id'].'</br>'.$value['Order']['order_date'].' '.$value['Order']['order_time']
                              ));
                        }    
                         
                        
                        #Directions                        
                        /*if ($value['Statuses']['status'] == 'Accepted' && $driverLatitude != '' && $driverLongitude != '' && $restaurantLatitude != '' && $restaurantLongitude != '') {
                            echo $this->GoogleMap->getDirections("map_canvas", "directions1", array(
                                "from" => array("latitude" => $driverLatitude, "longitude" => $driverLongitude),
                                "to"   => array("latitude" => $restaurantLatitude, "longitude" => $restaurantLongitude)
                              ));
                        } elseif (($value['Statuses']['status'] == 'Picked up' || $value['Statuses']['status'] == 'On the way') && ($driverLatitude != '' && $driverLongitude != '' && $deliveryLatitude != '' && $deliveryLongitude != '')) {
                            echo $this->GoogleMap->getDirections("map_canvas", "directions1", array(
                                "from" => array("latitude" => $driverLatitude, "longitude" => $driverLongitude),
                                "to"   => array("latitude" => $deliveryLatitude, "longitude" => $deliveryLongitude)
                              ));
                        }*/
                          
                    }
       
                }
                
            }
        break;
        
        case 'OrderStatus':
        
            if (!empty($orderTrack)) {
                foreach ($orderTrack as $key => $value) { 

                    if (strtolower($value['Orderstatus']['status']) == 'reject') {
                        continue;
                    }
?>
                    <div class="popbox clearfix <?php echo strtolower(str_replace(' ','',$value['Orderstatus']['status'])) ?>">
                        <div class="col-lg-2 col-md-2 col-sm-3 box-left">
                           <span class="status"> <?php
								echo ($value['Orderstatus']['status'] == 'Collected') ? 'Picked up' : $value['Orderstatus']['status']; ?>
                            </span>
                        </div>
                        <div class="col-lg-7 col-md-7 col-sm-6 assignorder"> <?php
                            switch ($value['Orderstatus']['status']) {
                               /* case 'New':
                                    echo 'Order received to '.$value['Restaurant']['restaurant_name'];
                                break;
                                case 'Waiting':
                                    echo 'Order assigned to '.$value['User']['firstname'];
                                break;
                                case 'Accepted':
                                    echo stripslashes($value['User']['firstname']).' accepted this order';
                                break;*/
                                case 'Driver Accepted':
                                    echo stripslashes($value['Driver']['driver_name']).' accepted this order from '.$value['Order']['Store']['store_name'];
                                break;
                                case 'Collected':
                                    echo stripslashes($value['Driver']['driver_name']).' pickup order from '.$value['Order']['Store']['store_name'];
                                break;
                                case 'Delivered':
                                    echo stripslashes($value['Driver']['driver_name']).' delivered this order to '.stripslashes($value['Order']['customer_name']);
                                break;
                            }

                            ?>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 date"> <?php
                                echo $value['Orderstatus']['updated']; ?>
                        </div>
                    </div> <?php
                }
            }
            
        break; 
        
        case 'InitialTracking': 

            echo $this->GoogleMap->map();
        break;

        case 'showMapEdit';
            echo $this->GoogleMap->map();
            echo $this->GoogleMap->addMarker(
                "map_canvas",
                1,
                [
                    'latitude' => $latitude,
                    'longitude' => $longitude
                ],
                [
                    'markerIcon' => $siteUrl.'/images/store.png',
                    'windowText' => $ResName
                ]
            );
            echo $this->GoogleMap->addCircle(
                "map_canvas",
                "Circle0",
                [
                    "latitude"  => $latitude,
                    "longitude" => $longitude
                ],
                $distance,
                [
                    "fillColor"   => $color,
                    "fillOpacity" => 0.3
                ]
            );
            break;

        case 'showMapAdd';
            echo $this->GoogleMap->map();
            echo $this->GoogleMap->addMarker(
                "map_canvas",
                1,
                [
                    'latitude' => $latitude,
                    'longitude' => $longitude
                ],
                [
                    'markerIcon' => $siteUrl.'/images/store.png',
                    'windowText' => $ResName
                ]
            );
            if (!empty($distance)) {
                echo $this->GoogleMap->addCircle(
                    "map_canvas",
                    "Circle0",
                    [
                        "latitude" => $latitude,
                        "longitude" => $longitude
                    ],
                    $distance,
                    [
                        "fillColor" => $color,
                        "fillOpacity" => 0.3
                    ]
                );
            }
            break;

        case 'filterResult':
?>
            <div class="row">
                <div class="col-sm-6">
                    &nbsp;
                </div>
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-3 topth">Min. Order</div>
                        <div class="col-sm-3 topth">Delivery Time</div>
                        <div class="col-sm-3 topth" >Delivery Fee</div>
                        <div class="col-sm-3 topth">Distance</div>
                    </div>
                </div>
            </div> <?php
                foreach ($storeList as $key => $value) { ?>
                <div class="row">
                    <a href="<?php echo $siteUrl.'/shop/'.$value['Store']['seo_url'].'/'.base64_encode($value['Store']['id']); ?>" class="searchleftresult">
                    <div class="searchoutertable">
                        <div class="<?php echo ($value['Store']['status'] == 'Order') ? 'ribbon' : 'ribbon_red'; ?>">
                            <span> <?php
                                echo ($value['Store']['status'] == 'Order')
                                    ? 'Commander Maintenant' : 'PRE COMMANDE'; ?>
                            </span>
                        </div>
                        <div class="col-sm-6 padding-t-15 padding-b-15">
                            <div class="col-sm-3 no-padding">
                                <img width="120px" height="100px" alt="<?php echo $value['Store']['store_name']; ?>" src="<?php echo $siteUrl.'/storelogos/'.$value['Store']['store_logo']; ?>" onerror="this.onerror=null;this.src='<?php echo $siteUrl."/frontend/images/no_store.jpg"; ?>'">
                            </div>
                            <div class="col-sm-9 no-padding">
                                <span class="rest-name"> <?php echo $value['Store']['store_name']; ?> </span>
                                <span class="rest-addr"> <?php
                                    if ($siteSetting['Sitesetting']['address_mode'] != 'Google') {
                                        echo $value['Store']['street_address'] . ', ' .
                                            $storeArea[$value['Store']['store_zip']] . ', ' .
                                            $storeCity[$value['Store']['store_city']] . ', ' .
                                            $storeState[$value['Store']['store_state']];
                                    } else {
                                        echo $value['Store']['address'];
                                    }
                                    ?> </span>
                                <span class="rest-cui">
                                    <?php

                                    if ($value['StoreCuisine']['store_id'] == $value['Store']['id']) {
                                        echo $value['Cuisine']['cuisine_name'].',';
                                    }
                                    $cuisine = '';
                                     foreach ($value['StoreCuisine'] as $k => $v) {
                                         $cuisine .= $cuisineName[$v['cuisine_id']].', ';
                                     }
                                     echo trim($cuisine, ', ');
                                    ?>
                                </span>
                            </div>

                        </div>
                        <div class="col-sm-6 no-padding">
                            <div class="searchoutertable_inner">
                                <div class="col-sm-3 text-center celltable">
                                    <span class="minorder"> <?php
                                        echo $this->Number->currency($value['Store']['minimum_order'], $siteCurrency); ?> </span>
                                </div>
                                <div class="col-sm-3 text-center celltable">
                                    <span class="deli-time"><?php echo $value['Store']['estimate_time'] ?></span>
                                </div>
                                <div class="col-sm-3 text-center celltable">
                                    <span class="deli-fee"><?php
                                        echo $this->Number->currency($value['Store']['delivery_charge'], $siteCurrency); ?></span>
                                </div>
                                <div class="col-sm-3 text-center celltable">
                                    <span class="distance"><?php echo $value['Store']['distance'] ?> ml</span>
                                </div>
                            </div>    
                        </div>
                    </div>
                    </a>
                    </div>
                    <?php
                    }
                
                break;

        case 'getDateTime':
            echo $timeContent;
            break;

        case 'getShowAddons':
            $priceAppend = 1;
            $j = '';
            foreach ($addonsList as $keyword => $value) {
                if (!empty($value['Subaddon'][0]['id'])) { ?>
                    <div class="form-group">
                        <label class="col-md-3 control-label">&nbsp;</label>

                        <div class="col-md-9">
                            <div class="mainProductHead bold"><?php echo $value['Mainaddon']['mainaddons_name'] ?> <span class="caret"></span></div> <?php
                            echo $this->Form->input('Mainaddon.id', [
                                'type' => 'hidden',
                                'name' => 'data[ProductAddon][' . $keyword . '][mainaddons_id]',
                                'value' => $value['Mainaddon']['id']
                            ]);
                            $i = (!empty($j)) ? $j + 1 : 1;
                            foreach ($value['Subaddon'] as $key => $val) { ?>
                            <div class="col-xs-12 productAddonsMenu"
                                 id="data[ProductAddon][<?php echo $keyword; ?>][Subaddon][<?php echo $key; ?>][subaddons_price]">
                                <div class="row">
                                    <div class="col-md-3 col-lg-3"><?php
                                        echo $this->Form->input($val['subaddons_name'], [
                                            'class' => 'checkboxes test appendMultipleSubAddons',
                                            'type' => 'checkbox',
                                            'checked' => (in_array($val['id'], $selectedAddons)) ? true : false,
                                            'id' => 'data[ProductAddon][' . $keyword . '][Subaddon][' . $key . '][subaddons_id]',
                                            'name' => 'data[ProductAddon][' . $keyword . '][Subaddon][' . $key . '][subaddons_id]',
                                            'hiddenField' => false,
                                            'value' => $val['id']
                                        ]); ?>
                                    </div>

                                    <div class="appendMultiplePrice" id="appendMultiplePrice_<?php echo $i; ?>"> <?php
                                        if (!empty($productId)) {
                                            if ($priceOption == 'multiple') {
                                                $AddonList = AjaxActionController::getSubAddonsMultiplePrice($productId, $val['id'], 'multiple');
                                                if (!empty($AddonList)) {
                                                    foreach ($AddonList as $k => $v) { ?>
                                                        <div
                                                            class="col-md-3 col-lg-2 removeAppendAddon_<?php echo $k; ?>"><?php
                                                            echo $this->Form->input('', [
                                                                'class' => 'form-control singleValidate',
                                                                'placeholder' => 'Price',
                                                                'type' => 'text',
                                                                'name' => 'data[ProductAddon][' . $keyword . '][Subaddon][' . $key . '][subaddons_price][]',
                                                                'value' => $v['ProductAddon']['subaddons_price'],
                                                                'label' => false
                                                            ]); ?>
                                                        </div> <?php
                                                    }
                                                } else {
                                                    for ($menu = 1; $menu <= $menuLength; $menu++) { ?>
                                                        <div
                                                            class="col-md-3 col-lg-2 removeAppendAddon_<?php echo $menu - 1; ?>"><?php
                                                            echo $this->Form->input('', [
                                                                'class' => 'form-control singleValidate',
                                                                'placeholder' => 'Price',
                                                                'type' => 'text',
                                                                'name' => 'data[ProductAddon][' . $keyword . '][Subaddon][' . $key . '][subaddons_price][]',
                                                                'value' => $val['subaddons_price'],
                                                                'label' => false
                                                            ]); ?>
                                                        </div> <?php
                                                    }
                                                }
                                            } else {
                                                $AddonList = AjaxActionController::getSubAddonsMultiplePrice($productId, $val['id'], 'single');
                                                if (!empty($AddonList)) {
                                                    foreach ($AddonList as $singlekey => $singleValue) { ?>
                                                        <div class="col-md-3 col-lg-2"><?php
                                                            echo $this->Form->input('', [
                                                                'class' => 'form-control singleValidate',
                                                                'placeholder' => 'Price',
                                                                'type' => 'text',
                                                                'name' => 'data[ProductAddon][' . $keyword . '][Subaddon][' . $key . '][subaddons_price]',
                                                                'value' => $singleValue['ProductAddon']['subaddons_price'],
                                                                'label' => false
                                                            ]); ?>
                                                        </div> <?php
                                                    }
                                                } else { ?>
                                                    <div class="col-md-3 col-lg-2"><?php
                                                        echo $this->Form->input('', [
                                                            'class' => 'form-control singleValidate',
                                                            'placeholder' => 'Price',
                                                            'type' => 'text',
                                                            'name' => 'data[ProductAddon][' . $keyword . '][Subaddon][' . $key . '][subaddons_price]',
                                                            'value' => $val['subaddons_price'],
                                                            'label' => false
                                                        ]); ?>
                                                    </div> <?php
                                                }
                                            }
                                        } else {
                                            if ($priceOption == 'multiple') { ?>
                                                <div class="col-md-3 col-lg-2"><?php
                                                    echo $this->Form->input('', [
                                                        'class' => 'form-control singleValidate',
                                                        'placeholder' => 'Price',
                                                        'type' => 'text',
                                                        'name' => 'data[ProductAddon][' . $keyword . '][Subaddon][' . $key . '][subaddons_price][]',
                                                        'value' => $val['subaddons_price'],
                                                        'label' => false
                                                    ]); ?>
                                                </div> <?php
                                            } else { ?>
                                                <div class="col-md-3 col-lg-2"><?php
                                                    echo $this->Form->input('', [
                                                        'class' => 'form-control singleValidate',
                                                        'placeholder' => 'Price',
                                                        'type' => 'text',
                                                        'name' => 'data[ProductAddon][' . $keyword . '][Subaddon][' . $key . '][subaddons_price]',
                                                        'value' => $val['subaddons_price'],
                                                        'label' => false
                                                    ]); ?>
                                                </div> <?php
                                            }
                                        } ?>
                                    </div>
                                </div>
                                </div><?php
                                $j = $i++;
                            } ?>
                        </div>
                    </div> <?php
                }
            }
        break;

        case 'getMenuAddons':
            if (!empty($mainAddonArray)) {
                foreach ($mainAddonArray as $key => $val) {
                    $getSubAddonsList = AjaxActionController::getSubAddonsList(
                        $val['mainaddons_id'],
                        $productId,
                        $productDetailId
                    );
                    if ($val['mainaddons_count'] == 1) { ?>
                        <div class="col-sm-12 no-padding">
                            <h5 class="addcart_popup_head"><?php echo $val['mainaddons_name']; ?></h5> <?php
                            foreach ($getSubAddonsList as $skey => $sval) {
                                $getSubAddonsPrice = AjaxActionController::singleSubAddonsPrice(
                                    $val['mainaddons_id'],
                                    $sval['subaddons_id'],
                                    $productId,
                                    $productDetailId
                                );
                                foreach ($getSubAddonsPrice as $spkey => $spval) { ?>
                                    <div class="radio radio-inline">
                                        <input type="radio" name="addon_ss<?php echo $spval['Subaddon']['mainaddons_id']; ?>" value="<?php echo $getSubAddonsPrice[0]['ProductAddon']['subaddons_id']; ?>" id="addons<?php echo $spval['Subaddon']['id']; ?>" class="addonsId">
                                        <label for="addons<?php echo $spval['Subaddon']['id']; ?>"><?php
                                            echo $sval['subaddons_name'].' (';
                                            foreach ($getSubAddonsPrice as $spkey => $spval) {
                                                echo ($spval['ProductAddon']['subaddons_price'] != 0)
                                                    ? $spval['ProductAddon']['subaddons_price'] : 'Free';
                                            }
                                            echo ')'; ?>
                                        </label>
                                    </div>
                                <?php }
                            } ?>
                        </div> <?php
                    } else { ?>
                        <div class="col-sm-12 no-padding">
                        <input type="hidden" id="addonCount_<?php echo $key; ?>" value="<?php echo $val['mainaddons_count']; ?>">
                            <h5 class="addcart_popup_head"><?php echo $val['mainaddons_name']; ?></h5> <?php
                            foreach ($getSubAddonsList as $skey => $sval) {
                                $getSubAddonsPrice = AjaxActionController::singleSubAddonsPrice(
                                    $val['mainaddons_id'],
                                    $sval['subaddons_id'],
                                    $productId,
                                    $productDetailId
                                ); 

                                $subAddonPrice = ($getSubAddonsPrice[0]['ProductAddon']['subaddons_price'] != 0)
                                               ? html_entity_decode($this->Number->currency($getSubAddonsPrice[0]['ProductAddon']['subaddons_price'], $siteCurrency)) : 'Free';

                                $subAddonName = $sval['subaddons_name'].' ('.$subAddonPrice.')';

                                ?>
                                <div class="checkbox checkbox-inline"> <?php
                                    $mainAddonCount = $val['mainaddons_count'];
                                    echo $this->Form->input($subAddonName, [
                                        'class' => 'addonsId checkCount_'.$key,
                                        'name' => $skey,
                                        'hiddenField' => false,
                                        'type' => 'checkbox',
                                        'id' => 'checkCount_'.$key.'_'.$skey,
                                        'value' => $getSubAddonsPrice[0]['ProductAddon']['subaddons_id'],
                                        'label' => $subAddonName,
                                        'onchange' => "return checkAddonsCount($skey, $key, $mainAddonCount);",                                    
                                        'div'=>false
                                                        
                                    ]); ?>
                                </div> <?php
                            } ?>
                        </div><?php
                    }
                }
            }
        break;

        case 'walletCards';

            if(!empty($Stripe_detail)) {
                foreach ($Stripe_detail as $key => $value) { ?>
                    <div class="col-md-6 col-xs-12" id="<?php echo "card".$value['StripeCustomer']['id'];?>">
                        <label class="editpayment <?php echo ($key == 0) ? 'active' : ''; ?>" >
                            <input type="radio" value="<?php echo $value['StripeCustomer']['id'];?>" name="data[Customer][walletCard]" <?php echo ($key == 0) ? 'checked=checked' : false; ?> >
                            <div class="card_info">
                                <span class="editAdd contain truncate">
                                    <img style="height:24px;" alt="cod_icon" title="cod_icon" src="<?php echo $siteUrl.'/frontend/images/debit_card.png'; ?>">
                                    <?php echo $value['StripeCustomer']['customer_name'] ;?>
                                </span>
                                <p class="margin-t-20">XXXX XXXX XXXX <?php echo $value['StripeCustomer']['card_number'] ;?> </p>
                            </div>
                        </label>
                    </div> <?php
                }
            } else {
                echo 'No saved cards available';
            } ?>

            <script type="text/javascript">
                $(".paymentWrapper .editpayment").click(function() {
                    $(".paymentWrapper .editpayment").removeClass('active');
                    $(this).addClass('active');
                });

            </script> <?php

        break;
    }