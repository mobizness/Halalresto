<div class="outersec searchshopContent">
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-md-11">
                <div class="innerresult">
                    <span class="">affichage des restaurants in <?php echo $serachAddress; ?></span>
                    <span class="pull-right rest_count"> <?php echo count($storeList); ?> Restaurants</span>
                </div>
            </div> <?php
            if (!empty($storeList)) { ?>
                <div class="col-sm-2 col-md-1">
                    <a href="javascript:;" class="filterCls"><?php echo __('Filter'); ?> </a>
                </div> <?php
            } ?>
            <div class="col-sm-12">
                <div class="cuisinefil">
                    <h4>Spécialités culinaires</h4> <?php
                    foreach ($getStoreCuisine as $key => $val) {

                        $cuisines   = preg_replace('/\s+/', '', $val['Cuisine']['cuisine_name']);
                        $cuisines   =  str_replace("'", '',$cuisines); ?>
                        <div class="col-sm-3">
                            <div class="checkbox checkbox-inline">
                                <input type="hidden" name="address"
                                       id="searchAddress" value="<?php echo $serachAddress; ?>">
                                <input type="checkbox" class="cuisineId" name="cuisine"
                                       id="cuisine_<?php echo $key; ?>" value=".<?php echo $cuisines; ?>" >
                                <label for="cuisine_<?php echo $key; ?>"><?php echo $val['Cuisine']['cuisine_name']; ?> <span class="pull-right margin-left-15">(<?php echo $val[0]['cuisineCount']; ?>)</span></label>
                            </div>
                        </div> <?php
                    } ?>
                </div>
            </div>
        </div>  
        <div class="col-sm-12 margin-t-20" id="showFilterResult">
            <div class="row">
                <!-- <div class="col-sm-6">
                    &nbsp;
                </div>
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-3 topth">
                            Min. Order
                        </div>
                        <div class="col-sm-3 topth">
                            Delivery Time
                        </div>
                        <div class="col-sm-3 topth" >
                            Delivery Fee
                        </div><?php
                        if ($siteSetting['Sitesetting']['address_mode'] == 'Google') { ?>
                            <div class="col-sm-3 topth">
                                Distance
                            </div> <?php
                        } ?>
                    </div>
                </div> --> <?php

            if (!empty($storeList)) {
                foreach ($storeList as $key => $value) { 
                    $cuisine = $actCuisine = '';
                    foreach ($value['StoreCuisine'] as $k => $v) {
                        $cuisine    .= $cuisineName[$v['cuisine_id']].', ';
                        $cuisineNames = preg_replace('/\s+/', '', $cuisineName[$v['cuisine_id']]);
                        $actCuisine .=  str_replace("'", '',$cuisineNames).' ';

                    } ?>
                    <a href="<?php echo $siteUrl.'/shop/'.$value['Store']['seo_url'].'/'.base64_encode($value['Store']['id']); ?>" class="searchleftresult">
                        <div class="searchoutertable item <?php echo $actCuisine; ?>">
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
                                    <span class="rest-cui"> <?php
                                        echo trim($cuisine, ', '); ?>
                                    </span>
                                    <span class="rating_star">
                                        <span class="rating_star_gold" style="width:<?php echo $value['Store']['rating']; ?>%"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-6 no-padding">
                                <div class="searchoutertable_inner">
                                    <div class=" col-sm-3 text-center celltable">
                                        <div class="searchLableTxt"><?php echo __('Min.Order');?></div>
                                        <div class="minorder"> <?php
                                            if ($siteSetting['Sitesetting']['address_mode'] == 'Google') {
                                                echo ($value['Store']['minimum_order'] != 0) ? $this->Number->currency($value['Store']['minimum_order'], $siteCurrency) : '0.00';
                                            } else {
                                                echo (!empty($value['Store']['DeliveryLocation'])) ? $this->Number->currency($value['Store']['DeliveryLocation']['minimum_order'], $siteCurrency) : '-';
                                            } ?>
                                        </div>
                                    </div>
                                    <div class=" col-sm-3 text-center celltable">
                                        <div class="searchLableTxt"><?php echo __('Del Time');?></div>
                                        <div class="deli-time"><?php echo (!empty($value['Store']['estimate_time'])) ? $value['Store']['estimate_time'] : 'No delivery time' ?></div>
                                    </div>
                                    <div class=" col-sm-3 text-center celltable">
                                        <div class="searchLableTxt"><?php echo __('Delivery Fee');?></div>
                                        <div class="deli-fee"><?php
                                            if ($siteSetting['Sitesetting']['address_mode'] == 'Google') {
                                                echo ($value['Store']['delivery_charge'] != 0) ? $this->Number->currency($value['Store']['delivery_charge'], $siteCurrency) : '0.00';
                                            } else {
                                                echo (!empty($value['Store']['DeliveryLocation'])) ? $this->Number->currency($value['Store']['DeliveryLocation']['delivery_charge'], $siteCurrency) : '-';
                                            } ?>
                                        </div>
                                    </div> <?php
                                    if ($siteSetting['Sitesetting']['address_mode'] == 'Google') { ?>
                                        <div class=" col-sm-3 text-center celltable">
                                            <div class="searchLableTxt"><?php echo __('Distance');?></div>
                                            <div class="distance"><?php
                                                echo (!empty($value['Store']['distance'])) ? $value['Store']['distance']: '0.00' ?> ml</div>
                                        </div> <?php
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </a> <?php
                }
            } else { ?>
                <div align="center" class="alert alert-danger col-xs-12">No restaurant avaliable on this location</div> <?php
            } ?>
            </div>
        </div>
    </div>
</div>