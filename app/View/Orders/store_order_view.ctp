<div class="page-content-wrapper">
    <div class="page-content">
        <h3 class="page-title">Commande</h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo $siteUrl.'/store/Dashboards/index'; ?>">Accueil</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="<?php echo $siteUrl.'/store/Orders/orderIndex'; ?>">Commande</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="#">Commande Details <?php echo $order_detail['Order']['ref_number'];?></a>
                </li>
            </ul>
            <div class="actions">
                <a href="javascript:void(0);" onclick="window.history.go(-1);" class="btn btn-default btn-circle">
                    <i class="fa fa-angle-left"></i>
                    <span class="hidden-480">
                        Retour </span>
                </a>									
            </div>

        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- Begin: life time stats -->
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-basket font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase">
                                Commande: <?php echo $order_detail['Order']['ref_number'];?></span>
                            <span class="caption-helper">
							<?php echo $order_detail['Order']['created'];?></span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-6 order_detail_left">
                                <table class="table table-striped table-bordered table-hover">
                                    <tbody><tr>
                                            <td valign="top" align="right"><label>Nom du restaurant &amp; Adresse</label></td>
                                            <td width="60%">
                                                <div class="address-detail">
                                                    <div class="resName"> <?php
                                                                                                $storeId = $order_detail['Store']['id'];
													echo $order_detail['Store']['store_name'] . " ($storeId) ";?>
                                                    </div> <?php
												if($siteSetting['Sitesetting']['address_mode'] != 'Google') {
													echo $order_detail['Store']['street_address'] . ', ' .
															$location[$order_detail['Store']['store_zip']] . ', ' .
															$cities[$order_detail['Store']['store_city']] . ', ' .
															$states[$order_detail['Store']['store_state']] . '.';
												} else {
													echo $order_detail['Store']['address'];
												}
												?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top" align="right"><label>Client Adresse </label></td>
                                            <td width="60%">
                                                <div class="address-detail"> <?php
												if($siteSetting['Sitesetting']['address_mode'] != 'Google') {
													echo $order_detail['Order']['address'] . ', ' .
															$order_detail['Order']['landmark'] . ', ' .
															$order_detail['Order']['location_name'] . ', ' .
															$order_detail['Order']['city_name'] . ', ' .
															$order_detail['Order']['state_name'] . '.';
												} else {
													echo $order_detail['Order']['google_address'];
												}

											?></div>	
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top" align="right"><label> Date de commande </label></td>
                                            <td width="60%">
                                                <div class="address-detail">
							<?php echo date("Y/m/d H:i", strtotime($order_detail['Order']['created']));?>	
                                            </td>
                                        </tr>

                                        <tr>
                                            <td valign="top" align="right"><label> <?php
												$orderType =
														($order_detail['Order']['order_type'] == 'Collection') ?
																'récupération' : ($order_detail['Order']['order_type'] == 'Delivery' ? 'livraison' : $order_detail['Order']['order_type']);
												echo 'Date de '.$orderType; ?></label></td>
                                            <td width="60%">
                                                <div class="address-detail">
											<?php echo date("Y/m/d", strtotime($order_detail['Order']['delivery_date']));?>	
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top" align="right"><label> <?php
                                                $orderType =
														($order_detail['Order']['order_type'] == 'Collection') ?
																'récupération' : ($order_detail['Order']['order_type'] == 'Delivery' ? 'livraison' : $order_detail['Order']['order_type']);
												echo 'Heure de '.$orderType; ?></label></td>
                                            <td width="60%">
                                                <div class="address-detail">
                                                <?php echo ($order_detail['Order']['assoonas'] == 'Later') ? $order_detail['Order']['delivery_time'] : 'ASAP';?></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top" align="right"><label>Client Nom </label></td>
                                            <td width="60%">
                                                <div class="address-detail">
											<?php echo $order_detail['Order']['customer_name'];?></div>	
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top" align="right"><label>Email Adresse</label></td>
                                            <td width="60%">
                                                <div class="address-detail">
											<?php echo $order_detail['Order']['customer_email'];?></div>	
                                            </td>
                                        </tr>								
                                    </tbody></table>
                            </div>
                            <div class="col-md-5 pull-right order_detail_right">
                                <table class="table table-striped table-bordered table-hover">
                                    <tbody><tr>
                                            <td valign="top" align="right"><label>Téléphone</label></td>
                                            <td>
                                                <div class="address-detail">
											<?php echo $order_detail['Order']['customer_phone'];?></div></div>	
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top" align="right"><label>Moyen de paiement</label></td>
                                            <td>
                                                <div class="address-detail">	
											<?php echo $order_detail['Order']['payment_type'] == "cod" ? "Espèces" : $order_detail['Order']['payment_type'];?></div>
                                            </td>						     				
                                        </tr>
                                        <tr>
                                            <td valign="top" align="right"><label>Paiement Statut</label></td>
                                            <td>
                                                <div class="address-detail">
												<?php if($order_detail['Order']['payment_method'] == 'unpaid'){
													echo 'Non Payé';  
												}
                                                                                                else if($order_detail['Order']['payment_method'] == 'paid'){
                                                                                                
                                                                                                echo 'Payé'; 
                                                                                                } else {
													echo $order_detail['Order']['payment_method'];
												}?></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top" align="right"><label>Statut de la commande</label></td>
                                            <td>
                                                <div class="address-detail"> <?php
                                                if($order_detail['Order']['status'] == 'Pending'){
                                                    $order_detail['Order']['status'] = "En attente";
                                                }else if($order_detail['Order']['status'] == 'Accepted'){
                                                    $order_detail['Order']['status'] = "Acceptée";
                                                    }else if($order_detail['Order']['status'] == 'Delivered'){
                                                    $order_detail['Order']['status'] = "Livré";
                                                    }
											echo ($order_detail['Order']['status'] == 'Collected') ?
												'Picked up' : $order_detail['Order']['status']; ?></div>	
                                            </td>
                                        </tr>

                                        <tr>
                                            <td valign="top" align="right"><label> <?php echo __('Order Type')?></label></td>
                                            <td>
                                                <div class="address-detail"><?php
                                                $orderType =
                                                    ($order_detail['Order']['order_type'] == 'Collection') ?
                                                        'A emporter' : 'Livraison';
											    echo $orderType; ?></div>
                                            </td>
                                        </tr>

									 <?php

									if ($order_detail['Order']['order_description']) {  ?>
                                        <tr>
                                            <td valign="top" align="right"><label>Instructions</label></td>
                                            <td>
                                                <div class="address-detail">
													<?php echo $order_detail['Order']['order_description'];?></div>	
                                            </td>
                                        </tr> <?php 
									}

									 if ($order_detail['Order']['failed_reason']) {  ?>
                                        <tr>
                                            <td valign="top" align="right"><label>Reason</label></td>
                                            <td>
                                                <div class="address-detail">
													 <?php echo $order_detail['Order']['failed_reason'];?></div>
                                            </td>
                                        </tr> <?php
									 }

									if ($order_detail['Driver']['driver_name']) {  ?>
                                        <tr>
                                            <td valign="top" align="right"><label>Nom coursier / Phone</label></td>
                                            <td>
                                                <div class="address-detail"> <?php 
													echo $order_detail['Driver']['driver_name'].' / '.
														$order_detail['Driver']['driver_phone']; ?></div>	
                                            </td>
                                        </tr> <?php
									} ?>

                                    </tbody></table>								
                            </div>
                            <div class="clear_both"></div>
                            <div class="order_detail_bottom col-sm-12">
                                <table border="1" class="table table-hover table-striped table-bordered no-margin">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Désignation</th>
                                            <th>Quantité</th>
                                            <th>Prix</th>
                                            <th>Total Prix (€)</th>
                                        </tr>

                                    </thead>
                                    <tbody><?php 
										if(!empty($order_detail)){
											$count = 1;
											foreach($order_detail['ShoppingCart'] as $key => $value) { ?>
                                        <tr>
                                            <td><?php echo $count;?></td>
                                            <td>
                                                <div style="clear:both;float:left;width:200px;">
														<?php echo $value['product_name'].'<br>'.$value['subaddons_name'];?>
                                                </div>
													<?php

													if (!empty($value['product_description'])) { ?>

                                                <span class="table-addon"><?php echo $value['product_description']; ?></span> <?php
													} ?>
                                            </td>
                                            <td class="price"><?php
													echo $value['product_quantity']; ?>
                                            </td>
                                            <td class="price"><?php
													echo html_entity_decode($this->Number->currency($value['product_price'], $siteCurrency)); ?>
                                            </td>
                                            <td class="price"><?php
													echo html_entity_decode($this->Number->currency($value['product_total_price'], $siteCurrency)); ?>
                                            </td>

                                        </tr><?php $count++;
											}?>
<!--                                        <tr>
                                            <td align="right" colspan="4">Sous-Total</td>
                                            <td class="price"><?php
													echo html_entity_decode($this->Number->currency($order_detail['Order']['order_sub_total'], $siteCurrency)); ?>
                                            </td>
                                        </tr>-->
                                            <?php 

											if ($order_detail['Order']['offer_amount'] != 0) {?>
                                        <tr class="grandprice">
                                            <td class="text-right" colspan="4">Offer <?php 
														echo '('.$order_detail['Order']['offer_percentage'].' %)'; ?> </td>
                                            <td class="price"><?php
														echo html_entity_decode($this->Number->currency($order_detail['Order']['offer_amount'], $siteCurrency)); ?>
                                            </td>
                                        </tr> <?php
											}

											if ($order_detail['Order']['voucher_amount'] != 0) { ?>
                                        <tr class="grandprice">
                                            <td class="text-right" colspan="4">Voucher Discount <?php 
														echo ($order_detail['Order']['voucher_percentage'] > 0) ? ' ('.$order_detail['Order']['voucher_percentage'].' %)' : ''; ?> </td>
                                            <td class="price"><?php
														echo html_entity_decode($this->Number->currency($order_detail['Order']['voucher_amount'], $siteCurrency)); ?>
                                            </td>
                                        </tr> <?php
											}

											if ($order_detail['Order']['tax_amount'] != 0) {?>
                                        <tr>
                                            <td align="right" colspan="4">T.V.A <?php 
														echo '('.$order_detail['Order']['tax_percentage'].' %)'; ?> </td>
                                            <td class="price"><?php
														echo html_entity_decode($this->Number->currency($order_detail['Order']['tax_amount'], $siteCurrency)); ?>
                                            </td>
                                        </tr><?php 
											}

											if ($order_detail['Order']['delivery_charge'] != 0 || 
													$order_detail['Order']['voucher_code'] != '' &&
													$order_detail['Order']['voucher_amount'] == 0) { ?>
                                        <tr>
                                            <td align="right" colspan="4">Frais de livraison</td>
                                            <td class="price"><?php
														echo ($order_detail['Order']['delivery_charge'] != 0) ?
															html_entity_decode($this->Number->currency($order_detail['Order']['delivery_charge'], $siteCurrency)) :
															'Free Delivery'; ?>
                                            </td>
                                        </tr> <?php 
											}

											if ($order_detail['Order']['tip_amount'] != 0) {?>
                                        <tr>
                                            <td align="right" colspan="4">Tips </td>
                                            <td class="price"><?php
													echo html_entity_decode($this->Number->currency($order_detail['Order']['tip_amount'], $siteCurrency)); ?>
                                            </td>
                                        </tr><?php
											} ?>
                                        <tr>
                                            <td align="right" colspan="4">Total</td>
                                            <td class="price"><?php
													echo html_entity_decode($this->Number->currency($order_detail['Order']['order_grand_total'], $siteCurrency)); ?>
                                            </td>


                                        </tr><?php 
										} else {
											echo "No Record Found";
										} ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-4">
                                <center><button  type="button" class="printBtn btn btn-success" onclick="PrintMenu()">Impress Complete Menu</button></center>
                            </div>
                            <div class="col-sm-4">
                                <center><button  type="button" class="printBtn btn btn-success" onclick="PrintDishes(0)">Impress Menu</button></center>
                            </div>
                            <div class="col-sm-4">
                                <center><button  type="button" class="printBtn btn btn-success" onclick="PrintElem()">Impress Invoice</button></center>
                            </div>
                        </div> 
                    </div>
                </div>
                <!-- End: life time stats -->
            </div>
        </div>
    </div>
</div>
<div  class="printReceipts" style="display:none;">
    <div class="row">
        <div class="order_detail_bottom col-sm-12" style="">
            <p id="jaay" style="clear:both;float:left;width:200px;"><?php echo $order_detail['Order']['customer_name'];?></p>
            <p  style="clear:both;float:left;width:200px;"><?php
                if($siteSetting['Sitesetting']['address_mode'] != 'Google') {
                        echo $order_detail['Order']['address'] . ', ' .
                                        $order_detail['Order']['landmark'] . ', ' .
                                        $order_detail['Order']['location_name'] . ', ' .
                                        $order_detail['Order']['city_name'] . ', ' .
                                        $order_detail['Order']['state_name'] . '.';
                } else {
                        echo $order_detail['Order']['google_address'];
                }

            ?></p>
            <p style="clear:both;float:left;width:200px;"><?php echo $order_detail['Order']['customer_phone'];?></p>
            <?php if(!empty($order_detail['Order']['order_description'])){?>
            <p style="clear:both;float:left;width:200px;"><?php echo $order_detail['Order']['order_description'];?></p>
            <?php }?>
            <p style="clear:both;float:left;width:200px;"><?php echo $order_detail['Order']['payment_type'] == "cod" ? "Espèces" : $order_detail['Order']['payment_type'];?></p>
            <p style="clear:both;float:left;width:200px;"><?php
            $orderType = ($order_detail['Order']['order_type'] == 'Collection') ?  'A emporter' : 'Livraison';
            echo $orderType; ?>
            </p>
            <p style="clear:both;float:left;width:200px;"><?php echo date("Y/m/d H:i", strtotime($order_detail['Order']['created']));?></p>
            <table class='table borderless'>
                <thead>
                    <tr>
                        <th>Quantité</th>
                        <th>Désignation</th>
                        <th>Prix</th>
                        <th>Total (€)</th>
                    </tr>

                </thead>
                <tbody><?php 
										if(!empty($order_detail)){
											$count = 1;
											foreach($order_detail['ShoppingCart'] as $key => $value) { ?>
                    <tr>
                        <td class="price"><?php
													echo $value['product_quantity']; ?>
                        </td>
                        <td>
                            <div style="clear:both;float:left;width:200px;">
														<?php echo $value['product_name'].'<br>'.$value['subaddons_name'];?>
                            </div>
													<?php

													if (!empty($value['product_description'])) { ?>

                            <span class="table-addon"><?php echo $value['product_description']; ?></span> <?php
													} ?>
                        </td>
                        <td class="price"><?php
													echo html_entity_decode($this->Number->currency($value['product_price'], $siteCurrency)); ?>
                        </td>
                        <td class="price"><?php
													echo html_entity_decode($this->Number->currency($value['product_total_price'], $siteCurrency)); ?>
                        </td>

                    </tr><?php $count++;
											}?>
    <!--                                        <tr>
                        <td align="right" colspan="4">Sous-Total</td>
                        <td class="price"><?php
													echo html_entity_decode($this->Number->currency($order_detail['Order']['order_sub_total'], $siteCurrency)); ?>
                        </td>
                    </tr>-->
                                            <?php 

											if ($order_detail['Order']['offer_amount'] != 0) {?>
                    <tr class="grandprice">
                        <td class="text-right" colspan="3">Offer <?php 
														echo '('.$order_detail['Order']['offer_percentage'].' %)'; ?> </td>
                        <td class="price"><?php
														echo html_entity_decode($this->Number->currency($order_detail['Order']['offer_amount'], $siteCurrency)); ?>
                        </td>
                    </tr> <?php
											}

											if ($order_detail['Order']['voucher_amount'] != 0) { ?>
                    <tr class="grandprice">
                        <td class="text-right" colspan="3">Voucher Discount <?php 
														echo ($order_detail['Order']['voucher_percentage'] > 0) ? ' ('.$order_detail['Order']['voucher_percentage'].' %)' : ''; ?> </td>
                        <td class="price"><?php
														echo html_entity_decode($this->Number->currency($order_detail['Order']['voucher_amount'], $siteCurrency)); ?>
                        </td>
                    </tr> <?php
											}

											if ($order_detail['Order']['tax_amount'] != 0) {?>
                    <tr>
                        <td align="right" colspan="3">T.V.A <?php 
														echo '('.$order_detail['Order']['tax_percentage'].' %)'; ?> </td>
                        <td class="price"><?php
														echo html_entity_decode($this->Number->currency($order_detail['Order']['tax_amount'], $siteCurrency)); ?>
                        </td>
                    </tr><?php 
											}

											if ($order_detail['Order']['delivery_charge'] != 0 || 
													$order_detail['Order']['voucher_code'] != '' &&
													$order_detail['Order']['voucher_amount'] == 0) { ?>
                    <tr>
                        <td align="right" colspan="3">Frais de livraison</td>
                        <td class="price"><?php
														echo ($order_detail['Order']['delivery_charge'] != 0) ?
															html_entity_decode($this->Number->currency($order_detail['Order']['delivery_charge'], $siteCurrency)) :
															'Free Delivery'; ?>
                        </td>
                    </tr> <?php 
											}

											if ($order_detail['Order']['tip_amount'] != 0) {?>
                    <tr>
                        <td align="right" colspan="3">Tips </td>
                        <td class="price"><?php
													echo html_entity_decode($this->Number->currency($order_detail['Order']['tip_amount'], $siteCurrency)); ?>
                        </td>
                    </tr><?php
											} ?>
                    <tr>
                        <td align="right" colspan="3">Total</td>
                        <td class="price"><?php
													echo html_entity_decode($this->Number->currency($order_detail['Order']['order_grand_total'], $siteCurrency)); ?>
                        </td>


                    </tr><?php 
										} else {
											echo "No Record Found";
	} ?>
                    <tr>
                        <td></td>
                        <td>Dont TVA</td>
                        <td>10.00%</td>
                        <td><?php echo round($order_detail['Order']['order_grand_total'] - ($order_detail['Order']['order_grand_total'] / 1.1),2); ?></td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>

<div  class="printMenu" style="display:none;">
    <div class="row">
        <div class="order_detail_bottom col-sm-12" style="">
            <p id="jaay" style="clear:both;float:left;width:200px;"><?php echo $order_detail['Order']['customer_name'];?></p>
            <p  style="clear:both;float:left;width:200px;"><?php
                if($siteSetting['Sitesetting']['address_mode'] != 'Google') {
                        echo $order_detail['Order']['address'] . ', ' .
                                        $order_detail['Order']['landmark'] . ', ' .
                                        $order_detail['Order']['location_name'] . ', ' .
                                        $order_detail['Order']['city_name'] . ', ' .
                                        $order_detail['Order']['state_name'] . '.';
                } else {
                        echo $order_detail['Order']['google_address'];
                }

            ?></p>
            <p style="clear:both;float:left;width:200px;"><?php echo $order_detail['Order']['customer_phone'];?></p>
            <?php if(!empty($order_detail['Order']['order_description'])){?>
            <p style="clear:both;float:left;width:200px;"><?php echo $order_detail['Order']['order_description'];?></p>
            <?php }?>
            <p style="clear:both;float:left;width:200px;"><?php echo $order_detail['Order']['payment_type'] == "cod" ? "Espèces" : $order_detail['Order']['payment_type'];?></p>
            <p style="clear:both;float:left;width:200px;"><?php
            $orderType = ($order_detail['Order']['order_type'] == 'Collection') ?  'A emporter' : 'Livraison';
            echo $orderType; ?>
            </p>
            <p style="clear:both;float:left;width:200px;"><?php echo date("Y/m/d H:i", strtotime($order_detail['Order']['created']));?></p>
            <table class='table borderless'>
                <thead>
                    <tr>
                        <th>Quantité</th>
                        <th>Désignation</th>
                    </tr>

                </thead>
                <tbody><?php 
                if(!empty($order_detail)){
                        $count = 1;
                        foreach($order_detail['ShoppingCart'] as $key => $value) { ?>
                    <tr>
                        <td class="price"><?php
                            echo $value['product_quantity']; ?>
                        </td>
                        <td>
                            <div style="clear:both;float:left;width:200px;">
                                <?php echo $value['product_name'].'<br>'.$value['subaddons_name'];?>
                            </div>

                            <?php

                            if (!empty($value['product_description'])) { ?>

                            <span class="table-addon"><?php echo $value['product_description']; ?></span> <?php
													} ?>
                        </td>

                    </tr><?php $count++;
			}
		} else {
											echo "No Record Found";
	} ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<div id="mainheadDishes" style="display:none;">
    <div >
        <p id="jaay" style="clear:both;float:left;width:200px;"><?php echo $order_detail['Order']['customer_name'];?></p>
        <p  style="clear:both;float:left;width:200px;"><?php
                if($siteSetting['Sitesetting']['address_mode'] != 'Google') {
                        echo $order_detail['Order']['address'] . ', ' .
                                        $order_detail['Order']['landmark'] . ', ' .
                                        $order_detail['Order']['location_name'] . ', ' .
                                        $order_detail['Order']['city_name'] . ', ' .
                                        $order_detail['Order']['state_name'] . '.';
                } else {
                        echo $order_detail['Order']['google_address'];
                }

            ?></p>
        <p style="clear:both;float:left;width:200px;"><?php echo $order_detail['Order']['customer_phone'];?></p>
            <?php if(!empty($order_detail['Order']['order_description'])){?>
        <p style="clear:both;float:left;width:200px;"><?php echo $order_detail['Order']['order_description'];?></p>
            <?php }?>
        <p style="clear:both;float:left;width:200px;"><?php echo $order_detail['Order']['payment_type'] == "cod" ? "Espèces" : $order_detail['Order']['payment_type'];?></p>
        <p style="clear:both;float:left;width:200px;"><?php
            $orderType = ($order_detail['Order']['order_type'] == 'Collection') ?  'A emporter' : 'Livraison';
            echo $orderType; ?>
        </p>
        <p style="clear:both;float:left;width:200px;"><?php echo date("Y/m/d H:i", strtotime($order_detail['Order']['created']));?></p>
    </div>
</div>

<table class='dishmenu table borderless' style="display:none;">
       <tbody><?php 
                if(!empty($order_detail)){
                        $count = 1;
                        foreach($order_detail['ShoppingCart'] as $key => $value) { ?>
        <tr>
            <td class="price"><?php
                            echo $value['product_quantity']; ?>
            </td>
            <td>
                <div style="clear:both;float:left;width:200px;">
                                <?php echo $value['product_name'].'<br>'.$value['subaddons_name'];?>
                </div>

                            <?php

                            if (!empty($value['product_description'])) { ?>

                <span class="table-addon"><?php echo $value['product_description']; ?></span> <?php
													} ?>
            </td>

        </tr><?php $count++;
			}
		} else {
											echo "No Record Found";
	} ?>
    </tbody>
</table>


