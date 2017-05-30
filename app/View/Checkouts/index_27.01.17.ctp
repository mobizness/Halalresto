<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleApiKey; ?>&v=3&sensor=false&amp;libraries=places,geometry"></script>
<div class="container searchshopContent shopcheckout"> <?php
	echo $this->Form->create('Order', array('id' => 'checkoutOrder',
											'controller' => 'checkouts',
											'action' => 'conformOrder'));
	echo $this->Form->input('stripetoken',array('type' => 'hidden','id' => 'stripetoken','value' => '')); 

	$storeMain = $storeDetails['Store']['id']; ?>

	<input type="hidden" id="addressmode" value=<?php echo $siteSetting['Sitesetting']['address_mode'];?>>

		<div class="checkout-tabs-bar-block">
			<a id="deliverAddress_tab" class="checkout-tabs active">
				<div class="checkout-tabs-icon address"></div>
				<div class="checkout-tabs-text"> <?php echo __('Address', true); ?></div>
			</a>
			<a id="reviewConform_tab" class="checkout-tabs">
				<div class="checkout-tabs-icon review"></div>
				<div class="checkout-tabs-text"><?php echo __('Review', true); ?></div>
			</a>
			<a id="payment_tab" class="checkout-tabs">
				<div class="checkout-tabs-icon payment"></div>
				<div class="checkout-tabs-text"><?php echo __('Payment & confirm', true); ?></div>
			</a>
		</div>
		<div class="addessBox">
			<div id="deliverAddress" class="col-md-12 col-xs-12">
				<div class="panel panel-default">
					<div class="reviewHead"> <?php 
						echo __('Review & Confirm');
						echo $this->Form->input('stores',
										array('type' => 'hidden',
											  'id' => 'checkout',
											  'value' => 'checkout')); ?></div>
					<div class="panel-body addressBg">
						<div class="panel-subheading">
							<h3 class="clearfix">
								<span class="pull-left"><?php echo __('Delivery Address', true); ?></span>
								<span class="pull-right">
									<a class="addnewAdrr" data-toggle="modal" data-target="#addDeliverAddress" href="javascript:void(0);">
										<i class="fa fa-plus"></i>
									</a>
								</span>
							</h3>
							<div id="locationError"></div>
							<div class="checkoutWrapper clearfix"> 
								<div class="row"> <?php
                                    foreach ($addresses as $keys => $values) { ?>
                                        <div class="col-md-6 col-sm-6">
                                            <label class="editAdrr <?php echo ($keys == 0) ? "active" : ''; ?>">
                                                <input type="radio" <?php if($keys == 0){ echo "checked=checked"; } ?> name="data[Order][delivery_id]" value="<?php echo $values['CustomerAddressBook']['id']; ?>" />
                                                <address>
                                                    <p class="font-new color_new"> <?php echo $values['CustomerAddressBook']['address_title']; ?></p> <?php
                                                    if ($siteSetting['Sitesetting']['address_mode'] != 'Google') {
                                                        echo '<p>' . $values['CustomerAddressBook']['address'] . ' ,' .
                                                                '<p>' . $values['CustomerAddressBook']['landmark'] . ' ,</p>' .
                                                                '<p>' . $customerArea[$values['CustomerAddressBook']['location_id']] . ' ,' .
                                                                $customerCity[$values['CustomerAddressBook']['city_id']] . ' ,</p>' .
                                                                '<p>' . $customerState[$values['CustomerAddressBook']['state_id']] . ' - ' .
                                                                $customerAreaCode[$values['CustomerAddressBook']['location_id']] . '</p>';
                                                    } else {
                                                        echo '<p>' . $values['CustomerAddressBook']['google_address'];
                                                    } ?>
                                                </address>
                                            </label>
                                        </div> <?php
                                    } ?>
		        				</div>							
							</div>
						</div>
						<div class="store_slotes">
							<div class="form-group clearfix">
								<label class="control-label col-md-12 text-left" for="customerNotes">
									<?php echo __('Any instructions for delivery (optional)') ; ?></label>
								<div class="col-md-12"> <?php
									echo $this->Form->input('order_description',
														array('class' => 'form-control address-customer-notes',
															  'placeholder' => __('Eg: Door bell is broken, please knock.'),
															  'label'=>false,
															  'rows' => 2)); ?>
								</div>
							</div>
						</div>
						<div class="store_slotes">
							<div class="form-group clearfix">
								<label class="control-label col-md-12 text-left" for="">Timing</label>
								<div class="col-md-4 text-center">
                                    <span class="switch_buton margin-b-20"> <?php
										if ($openCloseStatus != 'Close' && ($firstStatus == 'Open' || $secondStatus == 'Open')) {
											$option1 = array('Now' 	=> '');
										}
                                        $option2 = array('Later' => '');
                                        if ($openCloseStatus != 'Close' && ($firstStatus == 'Open' || $secondStatus == 'Open')) { ?>
                                            <label> <?php
                                                echo $this->Form->radio('assoonas',$option1,
                                                        array('checked'=>$option1,
                                                                'label'=>false,
                                                                'legend'=>false,
                                                                'name' => 'data[Order][assoonas]',
                                                                'hiddenField'=>false)); ?>
                                                <span><?php echo __('Now'); ?></span>
                                            </label> <?php
                                        } ?>
                                        <label>  <?php
                                            echo $this->Form->radio('assoonas',$option2,
                                                    array('checked'=>$option2,
                                                            'label'=>false,
                                                            'legend'=>false,
                                                            'name' => 'data[Order][assoonas]',
                                                            'checked' => 'checked',
                                                            'hiddenField'=>false)); ?>
                                            <span><?php echo __('Later'); ?></span>
                                        </label>
                                    </span>
								</div>
								<div id="showCalendar">
									<div class="col-sm-4">
										<div class="input-group"> <?php
                                            echo $this->Form->input('delivery_date', [
                                                    'class' => 'form-control address-customer-notes',
                                                    'label'=>false,
                                                    'placeholder' => $currentDate,
                                                    'type' => 'text',
                                                    'id' => 'latertime',
                                                    'name' => 'data[Order][delivery_date]',
                                                    'value' => $currentDate
                                                ]
                                            ); ?>
											<div class="input-group-addon"><i class="fa fa-calendar"></i> </div>
										</div>
									</div>                                   
									<div class="col-sm-4" >
										<select class="form-control" id="getDateTime" name="data[Order][delivery_time]">
											<?php echo $timeContent; ?>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div id="orderTypeCheck" class="contain clearfix">
							<div class="store_slotes">
								<div class="row">
									<div class="col-md-12 text-center">
										<span class="switch_buton margin-b-20"> <?php

											$option1 = array('Collection' 	=> '');
											$option2 = array('Delivery' 	=> '');

											if ($storeDetails['Store']['delivery'] == 'Yes' && $storeDetails['Store']['collection'] == 'Yes') { ?>

													<label> <?php

			                                          	echo $this->Form->radio('order_type'.$storeId,$option1,
	                                          							array('checked'=>$option1,
	                                          								'label'=>false,
	                                          								'legend'=>false,
	                                          								'name' => 'data[Order][orderType]',
	                                          								'onclick'=>'slotStore('.$storeId.')',
	                                          								'hiddenField'=>false)); ?>
	                                          								<span><?php echo __('Pickup'); ?></span>
		                                            </label>
		                                            <label>  <?php 
			                                           	echo $this->Form->radio('order_type'.$storeId,$option2,
		                                       							array('checked'=>$option2,
		                                       								'label'=>false,
		                                       								'legend'=>false,
		                                       								'name' => 'data[Order][orderType]',
		                                       								'onclick'=>'slotStore('.$storeId.')',
		                                       								'checked' => 'checked',
		                                       								'hiddenField'=>false)); ?>
		                                       								<span><?php echo __('Delivery'); ?></span>
					                                </label> <?php
				                            } elseif ($storeDetails['Store']['collection'] == 'Yes') { ?>
					                            	<label> <?php
			                                          	echo $this->Form->radio('order_type'.$storeId,$option1,
	                                          							array('checked'=>$option1,
																				'label'=>false,
	                                          									'legend'=>false,
	                                          									'name' => 'data[Order][orderType]',
	                                          									'onclick'=>'slotStore('.$storeId.')',
																				'checked' => 'checked',
	                                          									'hiddenField'=>false)); ?>
	                                          								<span><?php echo __('Pickup'); ?></span>
		                                            </label> <?php
		                                        } elseif ($storeDetails['Store']['delivery'] == 'Yes') { ?>

		                                        	<label>  <?php 
		                                           		echo $this->Form->radio('order_type'.$storeId,$option2,
	                                       							array('checked'=>$option2,
	                                       								'label'=>false,
	                                       								'legend'=>false,
	                                       								'name' => 'data[Order][orderType]',
	                                       								'onclick'=>'slotStore('.$storeId.')',
	                                       								'checked' => 'checked',
	                                       								'hiddenField'=>false)); ?>
	                                       								<span><?php echo __('Delivery'); ?></span>
					                                </label> <?php
				                            } ?>
			                            </span>
									</div>
								</div>
							</div>
						</div>	
						<div class="checkout-bottom checkoutbtm">
							<a onclick="checkoutpagintaion('#deliverAddress','#reviewConform');" class="btn btn-primary pull-right" id="closedTime"><?php echo __('Continue')?></a>
							<a class="btn btn-default pull-left" href="<?php echo $siteUrl.'/shop/'.$storeDetails['Store']['seo_url'].'/'.base64_encode($storeId); ?>"><?php echo __('Back to Restaurant', true); ?></a>
						</div>
					</div>
				</div>
			</div>
			<div id="payment" class="col-md-12 col-xs-12" style="display:none;">
				<div class="panel panel-default">
					<div class="panel-body addressBg">
						<div class="panel-subheading">
							<div id="paymentLoad"class="clearfix col-xs-6">		
									<h3 class="clearfix">
										<span class="pull-left"><?php echo __('Payment Details', true); ?> </span>	
										<span class="pull-right"> <?php echo $siteCurrency.' '; ?>
											<span class="pull-right payAmount"> </span></span>
									</h3>
									<div class="paymentWrapper clearfix">
										<div class="row">
											<div class="col-md-12">
												<div class="cardDetailHead">Select your Payment mode</div>
											</div> 
											<div class="col-md-4 col-sm-6 col-xs-12" onclick="showTip('Cash');">
												<label class="editpayment active" for="cod">
													<div class="card_info">
														<span class="editAdd contain truncate text-center">
															<img class="paymodeimg" alt="cod_icon" title="cod_icon" src="<?php echo $siteUrl.'/frontend/images/codimg.png'; ?>"><br>
															<input type="radio" id="cod" name="data[Order][paymentMethod]" value="cod" checked = "checked" onclick="walletCheck(); showCardDetails('cod');"/>
															Espece/Ticket Restaurant
														</span>		 
													</div>  
												</label>
											</div> 
											<div class="col-md-4 col-sm-6 col-xs-12">
												<label class="editpayment" for="card">
													<div class="card_info">
														<span class="editAdd contain truncate text-center">
															<img class="paymodeimg" alt="cod_icon" title="cod_icon" src="<?php echo $siteUrl.'/frontend/images/cardimg.png'; ?>"><br>
															<input type="radio" id="card" name="data[Order][paymentMethod]" value="showcard" onclick="return showCardDetails('card');"/>
															Carte de credit
														</span>
													</div>  
												</label>
											</div> 
											<!-- <div class="col-md-4 col-sm-6 col-xs-12">
												<label class="editpayment">
													<div class="card_info">
														<span class="editAdd contain truncate text-center">
															<img class="paymodeimg" alt="cod_icon" title="cod_icon" src="<?php echo $siteUrl.'/frontend/images/paypalimg.png'; ?>"><br>
															Paypal
														</span>
													</div>  
												</label>
											</div> --> 
										</div>
									</div>
									<label id="walletError"></label>
									<div class="paymentWrapper">
										<div class="row cardpay" style="display: none;">
											<div class="col-md-12">
												<div class="cardDetailHead"><?php echo __('Saved Card Details', true); ?>
												<span class="pull-right">
													<a class="addnewAdrr" data-toggle="modal" data-target="#addpayment" href="javascript:void(0);">
														<i class="fa fa-plus"></i> &nbsp;<?php echo __('Add Card', true); ?>
													</a>
												</span>
												</div>
											</div> <?php
											foreach ($stripeCards as $key => $card) { ?>
												<div class="col-md-4 col-sm-6 col-xs-12" onclick="showTip('Card');">
													<label class="editpayment">
														<input type="radio" name="data[Order][paymentMethod]" value="<?php echo $card['StripeCustomer']['id']; ?>" onclick="walletCheck();"/>
														<div class="card_info">
															<span class="editAdd contain truncate">
																<img style="height:24px;" alt="cod_icon" title="cod_icon" src="<?php echo $siteUrl.'/	frontend/images/debit_card.png'; ?>">
																<?php echo $card['StripeCustomer']['customer_name']; ?>
															</span>
															<p class="margin-t-20">XXXX XXXX XXXX <?php echo $card['StripeCustomer']['card_number']; ?> </p>	        							
														</div>  
													</label>
												</div> <?php
											} ?>
										</div>

										<div id="tipOption" style="display: none;">
											<div class="row">
												<div class="col-md-12">
													<div class="cardDetailHead">
														<div class="clearfix">
															<div class="tiphead">
																Pourboire
															</div>
															<div class="col-sm-3 input-group">

															<div class="input-group-addon"> <?php echo $siteCurrency; ?> </div>

															 <?php
																echo $this->Form->input('tip_percentage', [
																	'type' => 'text',
																	'label' => false,
																	'class' => "form-control",
																	'placeholder' => __('Price'),
																	'name' => 'data[Order][tip_percentage]',
																	'onkeypress' => 'return tipsCheck(event);',
																	'hiddenField' => false
																]); ?>
																
															</div>
														</div>
														<div id="tipError" class="error"></div>
													</div>
												</div>
											</div>
										</div>
										
										<!-- <div class="row">
											<div class="col-md-12"><div class="cardDetailHead"><?php echo __('Cash on delivery', true); ?></div></div>
											<div class="col-md-4 col-sm-6 col-xs-12" onclick="showTip('Cash');">
												<label class="editpayment active">
													<img style="height:24px;" alt="cod_icon" title="cod_icon" src="<?php echo $siteUrl.'/frontend/images/cod_icon.png'; ?>">
													<input type="radio" id="cod" name="data[Order][paymentMethod]" value="cod" checked = "checked" onclick="walletCheck();"/>
													<span class="editAdd "><?php echo __('Cash on delivery', true); ?></span>
												</label> 
											</div>
										</div> -->
										<div class="row">
											<div class="col-md-12">
												<div class="cardDetailHead"><?php echo __('Wallet', true);  ?>
													<input type="hidden" id="WalletAmount" value="<?php echo $customerDetails['Customer']['wallet_amount']; ?>">
												</div>
											</div>
											<div class="col-md-4 col-sm-6 col-xs-12" onclick="showTip('Wallet');">
												<label class="editpayment">
													<img style="height:24px;" alt="wallet" title="wallet" src="<?php echo $siteUrl.'/frontend/images/wallet.png'; ?>">
													<input type="radio" name="data[Order][paymentMethod]" value="wallet" onclick="walletCheck();">
													<span class="editAdd "><?php 
														echo __('Wallet', true);
														echo ' '.$siteCurrency.' '; ?>
														<span class="pointer" id="remainWalletAmount"><?php 
														echo $customerDetails['Customer']['wallet_amount']; ?></span></span>
												</label> 
											</div>
										</div>
										

										
									</div>	
								</div>
							<div class="paymentWrapper clearfix col-xs-6 cartlistcheck">
								<div class="cartlisthead">Cart Details</div>
								<ul class="itemtoplist">
									<li class="item_sno col-xs-1 no-padding">N°</li>
									<li class="item_name col-xs-4"><?php echo __('Item Name', true); ?></li>
									<li class="item_qty col-xs-2 no-padding "><?php echo __('Qty', true); ?></li>
									<li class="item_price col-xs-3"><?php echo __('Price', true); ?></li>
									<li class="item_pricetot col-xs-2 no-padding"><?php echo __('Total Price', true); ?></li>
								</ul>
								
									<?php
									foreach ($shopCart as $key => $value) {										
										$storeSubtotal	+= $value['ShoppingCart']['product_total_price']; ?>	
										<ul>									
											<li class="item_sno col-xs-1 no-padding"><?php echo $serialNo +=1; ?></li>
											<li class="item_name col-xs-4">

												<span class="menu_name"><?php echo $value['ShoppingCart']['product_name']; ?></span>
												<span class="addon_name whitenormal"><?php echo (!empty($value['ShoppingCart']['subaddons_name'])) ? '<br>('.$value['ShoppingCart']['subaddons_name'].')' : ''; ?></span>
												<?php if (!empty($value['ShoppingCart']['product_description'])) { ?>
													<div class="margin-t-5 whitenormal"><?php echo $value['ShoppingCart']['product_description']; ?></div> <?php
												}?>
												
											</li>
											<li class="item_qty col-xs-2 no-padding"><?php echo $value['ShoppingCart']['product_quantity']; ?></li>
											<li class="item_price col-xs-3"><?php echo html_entity_decode($this->Number->currency($value['ShoppingCart']['product_price'], $siteCurrency)); ?></li>
											<li class="item_pricetot col-xs-2 no-padding"><?php echo html_entity_decode($this->Number->currency($value['ShoppingCart']['product_total_price'], $siteCurrency)); ?></li>
										</ul>	
										<?php
									} ?>
								

								<ul class="">
									<li class="col-xs-6 no-padding  text-right"><?php echo __('Sub Total', true); ?></li>
									<li class="col-xs-6 text-right"><?php echo html_entity_decode($this->Number->currency($storeSubtotal, $siteCurrency)); ?></li>
								</ul> <?php
								if (isset($offerDetails['storeOffer']) && $offerDetails['storeOffer'] != 0) { ?>
									<ul class="itemtoplist">
										<li class="col-xs-6 no-padding text-right"><?php echo __('Offer'); ?> (<?php echo $offerDetails['offerPercentage'].'%'; ?>) </li>
										<li class="col-xs-6 text-right"><?php
												if ($offerDetails['storeOffer'] != 0) {												
													$storeSubtotal	-= $offerDetails['storeOffer'];
												}
												echo html_entity_decode($this->Number->currency($offerDetails['storeOffer'], $siteCurrency)); ?></li>
									</ul> <?php
								}

								if (isset($taxDetails['tax']) && $taxDetails['tax'] != 0) { ?>
									<ul class="itemtoplist">
										<li class="col-xs-6 no-padding text-right"><?php echo __('Tax'); ?> (<?php echo $taxDetails['tax'].'%'; ?>)</li>
										<li class="col-xs-6 text-right"><?php
											if (isset($taxDetails['tax']) && $taxDetails['tax'] != 0) {												
												$storeSubtotal	+= $taxDetails['taxAmount'];
											}
											echo html_entity_decode($this->Number->currency($taxDetails['taxAmount'], $siteCurrency)); ?></li>
									</ul> <?php
								}

								?>
								<div id="deliveryCharge"> <?php 
			                    if (isset($taxDetails['deliveryCharge']) && $taxDetails['deliveryCharge'] != 0) { ?>

			                        <ul class="itemtoplist">
										<li class="col-xs-6 no-padding text-right"><?php echo __('Delivery Charge'); ?></li>
										<li class="col-xs-6 text-right"><?php
			                                if (isset($taxDetails['deliveryCharge']) && $taxDetails['deliveryCharge'] != 0) {
			                                    $storeSubtotal	+= $taxDetails['deliveryCharge'];
			                                } ?>
			                                <span class="freeDelivery_<?php echo $storeMain ?>"> <?php
			                                echo html_entity_decode($this->Number->currency($taxDetails['deliveryCharge'], $siteCurrency)); ?></span>
			                                <span class="free_<?php echo $storeMain ?>" style="display: none"> <?php
			                                	echo __('Free Delivery'); ?> </span></li>
									</ul>  <?php
			                    } ?>
			                    </div>

			                    <div class="voucherShow_<?php echo $storeMain ?>" style="display: none">
	                    			<ul class="itemtoplist">
										<li class="col-xs-6 no-padding text-right"><?php echo __('Promo Discount', true); ?></li>
										<li class="col-xs-6 text-right"><?php echo $siteCurrency.' '; ?>
											<span id="payvoucherPrice_<?php echo $storeMain ?>"></span> <!-- <?php
											echo $this->Form->input('', 
															array('class'=>'voucherTotal_'.$storeMain,
								        							'type' => 'hidden',
								        							'label' => false,
								        							'div' => false)); ?> --></li>
									</ul>
			                    </div>

								<ul class="itemtoplist">
									<li class="col-xs-6 no-padding text-right"> <?php echo __('Total', true); ?></li>
									<li class="col-xs-6 text-right"> <?php 
										echo $siteCurrency.' '; ?>
										<span class="storeSubTotal_<?php echo $storeMain ?>"> <?php 
											echo number_format($storeSubtotal, 2); ?></span>
						        	</li>
								</ul> <?php
			                    if (isset($taxDetails['tipPercentage']) && $taxDetails['tipPercentage'] != 0) { ?>
			                        <ul class="itemtoplist">
			                            <li class="col-xs-6 no-padding text-right"> Pourboire </li>
			                            <li class="col-xs-6 text-right"> <?php
			                                $storeSubtotal	+= $taxDetails['tipPercentage'];
			                                echo html_entity_decode($this->Number->currency($taxDetails['tipPercentage'], $siteCurrency)); ?></li>
			                        </ul> <?php
			                    } ?>

							</div>
						</div>
						<div class="clearfix"></div>
						<div class="clearfix checkout-bottom checkoutbtm"> <?php
							echo $this->Form->button(__('Place Order'),
				                              array('class'=>'btn btn-primary pull-right',
				                              		'onclick' => 'return walletPay();')); ?>
							<a onclick="checkoutpagintaion('#payment','#reviewConform');" class="btn btn-default pull-left" > <?php
								echo __('Back to Review', true); ?></a>
						</div>
					</div>
				</div>
			</div>
			<div id="reviewConform" class="col-xs-12 col-md-12" style="display:none;">
				
			</div>
		</div> <?php
	echo $this->Form->end(); ?>
</div>


<div class="modal fade" id="addDeliverAddress">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"> 
					<?php echo __('Add New Deliver Address'); ?></h4>
			</div> <?php
			echo $this->Form->create("CustomerAddressBook",
                                  array("id"=>'AddCustomerAddressBook',
                                  		"url"=>array("controller"=>'checkouts',
                                  		'action' => 'customerBookAdd'))); ?>
				<div class="modal-body"> 
					<label  class="error checkAdderorr"></label>
					<div class="form-group clearfix">
						<label class="control-label col-md-4"><?php echo __('Address Title', true); ?></label>
						<div class="col-md-8"> <?php
							echo $this->Form->input('address_title',
									array('class'=>'form-control',
											'label'=>false,
											'value' => '')); ?>
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4"><?php echo __('Address Phone', true); ?></label>
						<div class="col-md-8"> <?php
							echo $this->Form->input('address_phone',
									array('class'=>'form-control',
											'label'=>false,
											'value' => '')); ?>
						</div>
					</div> <?php
                    if ($siteSetting['Sitesetting']['address_mode'] != 'Google') { ?>
                        <div class="form-group clearfix">
                            <label class="control-label col-md-4"><?php echo __('Street Address', true); ?></label>
                            <div class="col-md-8"> <?php
                                echo $this->Form->input('address',
                                    array('class'=>'form-control',
                                        'label'=>false,
                                        'type' => 'text',
                                        'value' => '')); ?>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="control-label col-md-4"><?php echo __('Apt/Suite/Building', true); ?></label>
                            <div class="col-md-8"> <?php
                                echo $this->Form->input('landmark',
                                        array('class'=>'form-control',
                                                'label'=>false,
                                                'value' => '')); ?>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label class="control-label col-md-4"><?php echo __('State', true); ?></label>
                            <div class="col-md-8"> <?php
                                echo $this->Form->input('state_id',
                                    array('type'  => 'select',
                                          'class' => 'form-control',
                                          'options'=> array($customerState),
                                          'onchange' => 'citiesList();',
                                          'empty' => __('Select State'),
                                          'label'=> false,
                                          'value' => '')); ?>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="control-label col-md-4"><?php echo __('city', true); ?></label>
                            <div class="col-md-8"> <?php
                                echo $this->Form->input('city_id',
                                    array('type'  => 'select',
                                          'class' => 'form-control',
                                          'onchange' => 'locationLists();',
                                          'empty' => __('Select City'),
                                          'label'=> false)); ?>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="control-label col-md-4"><?php echo __('Area/zipcode', true); ?></label>
                            <div class="col-md-8"> <?php
                                echo $this->Form->input('location_id',
                                        array('type'  => 'select',
                                              'class' => 'form-control',
                                              'empty' => __('Select Location'),
                                              'label'=> false));  ?>
                            </div>
                        </div> <?php
                    } else { ?>
                        <div class="form-group clearfix">
                            <label class="control-label col-md-4"><?php echo __('Address', true); ?></label>
                            <div class="col-md-8"> <?php
                                echo $this->Form->input('google_address',
                                    array('class'=>'form-control',
                                        'label'=>false,
                                        'type' => 'text',
                                        'value' => '',
                                        'onfocus' =>'initialize(this.id)')); ?>
                            </div>
                        </div> <?php
                    } ?>

					<div class="form-group clearfix">
						<div class="col-md-8 col-md-offset-4 signup-footer"> <?php 
							echo $this->Form->button(__('Submit'),
									array("label"=>false,
											"class"=>"btn btn-primary",
											'onclick' => 'return addAddressCheck();',
											"type"=>'submit')); ?>
						</div>
					</div>
				</div> <?php
			echo $this->Form->end(); ?>
		</div>
	</div>
</div>
<div class="modal fade" id="addpayment">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"><?php echo __('Add New Payment', true); ?></h4>
			</div>
			<div class="modal-body"> <?php
				echo $this->Form->create('User',
								array('class'  => 'form-horizontal paymentTab',
										'name' => 'StripeForm',
										'id'   => 'UserIndexForm',
										"url"=> array("controller" => 'checkouts',
                                  					 'action' 	   => 'customerCardAdd'))); ?>
				
				<div class="text-center">
					<label id="error" class="margin-b-15"></label>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4"><?php echo __('Name on Card', true); ?><span class="star">*</span> :</label>
					<div class="col-md-7">
						<?php
							echo $this->Form->input("Card.Name",
									array("type"=>"text",
											"label"=>false,
											'data-stripe' => 'name',
											"class"=>"form-control",
											'value' => '')); 
						?>
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-md-4"><?php echo __('Card Number', true); ?><span class="star">*</span>
						<span class="payment-cart-icons"></span> :
					</label>
					<div class="col-md-7">
						<div class="input-group cc merged input-group-card-number">
							<span class="input-group-addon lock"><i class="fa fa-lock"></i></span> <?php 
								echo $this->Form->input("Card.number",
										array("type"=>"text",
												"label"=>false,
												'data-stripe' => 'number',
												"class"=>"form-control intnumber",
												'height' => 40,
												'value' => '',
												'maxlength' => 16,
												'placeholder' => 'XXXX-XXXX-XXXX-XXXX')); ?>
							<span class="input-group-addon input-group-valid"><i class="fa fa-check"></i></span>
							<span class="input-group-addon input-group-card-icon"><i class="fa fa-credit-card"></i></span>
						</div>
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-md-4"><?php echo __('CVV', true); ?><span class="star">*</span>
						<img src="<?php echo $siteUrl; ?>/frontend/images/cvv.gif" class="cvv"> :
					</label>
					<div class="col-md-4">
						<div class="input-group cc merged input-group-card-number">
							<span class="input-group-addon lock"><i class="fa fa-lock"></i></span> 
								<?php 
									echo $this->Form->input("Card.cvv",	
										array("type"=>"password",
												"label"=>false,
												'data-stripe' => 'cvc',
												"class"=>"form-control",
												'value' => '')); 
								?>
						</div>
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-md-4"><?php echo __('Expiry Date', true); ?><span class="star">*</span> : </label>
					<div class="col-md-7">
						<div class="row">
							<div class="col-md-6 col-sm-6 col-xs-6">
								<?php
									$month  = array("1"=>"Jan",  "2"=>"Feb", "3"=>"Mar",
													"4"=>"Apr","5"=>"May", "6"=>'Jun',
													"7"=>"Jul", "8"=>"Aug", "9"=>"Sep",
													"10"=>"Oct", "11"=>"Nov", "12"=>"Dec");
									echo $this->Form->input("Card.expmonth",
												array("type"=>"select",
														"label"=>false,
														"options"=>$month,
														'data-stripe' => 'exp-month',
														"class"=>"form-control valid")); 
								?>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<?php
									$curyear    = date("Y");
									$endyear    = $curyear+20;
									$years  = array();
									for($i=$curyear;$i<=$endyear;$i++){
										
										$years[$i]=$i;
												
									}
									echo $this->Form->input("Card.expyear",
											array("type"=>"select",
													"label"=>false,
													"options"=>$years,
													'data-stripe' => 'exp-year',
													"class"=>"form-control valid"));
								?>
							</div>
						</div>

					</div>
				</div>

				
				<div class="form-group clearfix">						
					<div class="col-md-8 col-md-offset-4 paymentInfo">
						<?php echo __('The card will automatically be stored in your customer profile so that you can check out faster next time.', true); ?>
					</div>
				</div>
				<div class="form-group clearfix">						
					<div class="col-md-8 col-md-offset-4 paymentInfo">
						<input type="checkbox" class="savecard"> Mémoriser ma carte 
					</div>
				</div>
				<div class="form-group clearfix margin-t-25">						
					<div class="col-md-8 col-md-offset-4">
						<?php 
							echo $this->Form->button(__('Submit'),
								array("label"=>false,
										"id"=>"stripebtn",
										"class"=>"btn btn-primary",
										'onclick' => 'return saveCard();',
										"type"=>'submit')); 
						?>
					</div>
				</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

//Checkout Page Hide and Show Process
function checkoutpagintaion(page1,page2){
	
	/*var id = orderTypeCheck = '';
	var error = locationError = 0;
	$('.error').remove();
	
	$('#locationError').html('');

	var address = $('input[name="data[Order][delivery_id]"]:checked').val();

	if (page1 == '#deliverAddress') {
        var delivery = $('#OrderAssoonasLater').is(':checked');
       if (delivery) {
           var deliveryTime = $('#getDateTime').val();
           if(deliveryTime == "") {
               $('#getDateTime').after("<label class='error'> <?php echo __('Restaurant is closed'); ?></label>");
               error = 1;
               return false;
           }
       }
	}

    $("#orderTypeCheck input[type='radio']:checked").each(function() {
        orderTypeCheck += $(this).val()+',';
    });


	if (error != 1 ) {

		if (page1 == '#deliverAddress') {

			orderTypeChecks = orderTypeCheck.substr(0, orderTypeCheck.length-1);
			$.post(rp+'checkouts/deliveryLocation',{'id':address, 'orderTypes':orderTypeChecks}, function(response) {
				if (response != '') {
					$('#locationError').html(response);
				} else {
					$(page1).hide();
					$(page1+'_tab').removeClass('active');
					$(page2+'_tab').addClass('active');

					$(page2).show();
				}
			});
		} else {
            /*$("#tipOption input[type='radio']:checked").each(function() {
                tipOption += $(this).val()+',';
            });*/

			/*if (page1 == '#payment') {
                $('#tipError').html('');
				orderTypeCheck = orderTypeCheck.substr(0, orderTypeCheck.length-1);
				id = id.substr(0, id.length-1);

                var tipPercentage = $('#OrderTipPercentage').val();
                if (tipPercentage != '' && tipPercentage < 0) {
                    $('#tipError').html('Please enter valid percentage');
                    $('#OrderTipPercentage').focus();
                    return false;
                }

				$.post(
                    rp+'checkouts/orderReview',
                    {
                        'id':id,
                        'orderTypeCheck' : orderTypeCheck,
                        'tipPercentage' : tipPercentage,
						'addressId' : address
                    }, function(response) {
					$('#reviewConform').html(response);
				});
			};
			$(page1).hide();
			$(page1+'_tab').removeClass('active');
			$(page2+'_tab').addClass('active');
			$(page2).show();
		}
	}*/

	var id = '';
	var orderTypeCheck = '';
	var error = locationError = 0;
	$('#walletError').removeAttr('class');
	$('#walletError').html('');
	$('.error').remove();

	
	$('#locationError').html('');

	var address = $('input[name="data[Order][delivery_id]"]:checked').val();

	if (page1 == '#deliverAddress') {
		
		var delivery = $('#OrderAssoonasLater').is(':checked');
       	if (delivery) {
           	var deliveryTime = $('#getDateTime').val();
           	if(deliveryTime == "") {
               	$('#getDateTime').after("<label class='error'> <?php echo __('Restaurant is closed'); ?></label>");
               	error = 1;
               	return false;
           	}
       	}
		
		$("#orderTypeCheck input[type='radio']:checked").each(function() {
			orderTypeCheck += $(this).val()+','; 
		});
	}
	
	if (page1 == '#reviewConform') {
		var grandTotal = $('.orderGrandTotal').val();
		$('.payAmount').html(format(grandTotal));
	}

	if (error != 1 ) {

		if (page1 == '#deliverAddress') {

			orderTypeChecks = orderTypeCheck.substr(0, orderTypeCheck.length-1);

			$('.ui-loader').show();

			$.post(rp+'checkouts/deliveryLocation',{'id':address, 'orderTypes':orderTypeChecks}, function(response) {
				if (response != '') {
					$('#locationError').html(response);
					$('.ui-loader').hide();
				} else {
					if (page1 == '#deliverAddress') {
						
						orderTypeCheck 	= orderTypeCheck.substr(0, orderTypeCheck.length-1);
						id 				= id.substr(0, id.length-1);

						// $.post(rp+'checkouts/orderReview',{'id':id, 'orderTypeCheck' : orderTypeCheck}, function(response) {
						$.post(rp+'checkouts/orderReview',{'id':address, 'orderTypeCheck' : orderTypeCheck}, function(response) {
							$('#reviewConform').html(response);
							$(page1).hide();
							$(page1+'_tab').removeClass('active');
							$(page2+'_tab').addClass('active');
							$(page2).show();
							$('.ui-loader').hide();
						});
					};
					//$(page2).show();
				}
			});
			// $("#cod").attr('checked', true).trigger('click');

		} else {
			var addressmode = $('#addressmode').val();
			if(addressmode != 'Google') {
				$("#orderTypeCheck input[type='radio']:checked").each(function() {
					orderTypeCheck += $(this).val()+','; 
				});
				if(page2 == '#payment') {
					orderTypeChecks = orderTypeCheck.substr(0, orderTypeCheck.length-1);
					$.post(rp+'checkouts/deliveryLocation',{'id':address, 'orderTypes':orderTypeChecks}, function(response) {
						if (response != '') {
							$('#locationError').html(response);
							$('.ui-loader').hide();
						} else {
							if (page2 == '#payment') {
								
								orderTypeCheck 	= orderTypeCheck.substr(0, orderTypeCheck.length-1);
								id 				= id.substr(0, id.length-1);
								
									$.post(rp+'ajaxAction/index',{'id':address, 'orderTypeCheck' : orderTypeCheck,'Action' : 'delCharge'}, function(response) {
										// alert(response);
										$('#deliveryCharge').html(response);
										
									});
														
							};
							//$(page2).show();
						}
					});
					
				}
			}


			$(page1).hide();
			$(page1+'_tab').removeClass('active');
			$(page2+'_tab').addClass('active');
			$(page2).show();
		}
	}
}

function showTip(type) {
    if (type == 'Card') { 
        $('#tipOption').show();
    } else {
        $('#OrderTipPercentage').val('');
        $('#tipOption').hide();
    }

    return false;
}

function slotStore (id) {
	var type 		 = $('#dateType'+id).val();
	var orderTypeVal = ($('#OrderOrderType'+id+'Collection').prop("checked")) ? 'Collection' : 'Delivery';
	var orderType 	 = ($('#OrderOrderType'+id+'Collection').prop("checked")) ? '<?php echo __("Collection"); ?>' : '<?php echo __("Delivery"); ?>';
	$('#orderType_'+id).html(orderType);
}

// Voucher Check
function voucherCheck(storeId) {
	$('.enable_'+storeId).removeAttr('onclick');
    $('#couponMessage_'+storeId).html('');

    var voucherCode = $('#voucher_'+storeId).val();
	var orderType 	= $('input[name="data[Order][orderType]"]:checked').val();
	var addressId 	= $('input[name="data[Order][delivery_id]"]:checked').val();

    if (voucherCode != '') {
	    $.post(rp + 'checkouts/voucherCheck', {'voucherCode': voucherCode, 
	    										'storeId' 	: storeId,
	    										'addressId' : addressId,
	    										'orderType' : orderType}, function (response) {
	        
	        voucherResponse = response.split('||');
	        response 		= response.split('@@');

	        if (response[0] != 'Failed') {
	            $('#voucher_'+storeId).attr('readonly', true);
	            $('#vouchers_'+storeId).val(voucherCode);

	            if (voucherResponse[3] == 'freeDelivery') {
	            	 $('.freeDelivery_'+storeId).hide();
	            	 $('.free_'+storeId).show();
	            } else {
	            	// Voucher row Show
		            $('.voucherShow_'+storeId).show();

		            // Percentage or Price
		            if (voucherResponse[1] > 0) {
		            	$('#voucherPercentage_'+storeId).append('('+voucherResponse[1]+'%)');
		            }

		            voucherResponse[2] = parseFloat(voucherResponse[2]).toFixed(2);
		            $('#voucherPrice_'+storeId).html(format(voucherResponse[2]));
		            $('#payvoucherPrice_'+storeId).html(format(voucherResponse[2]));
	            }

	            // Store based total
	            var total 			= $('.storeTotal_'+storeId).val();
	            var nowStoreTotal 	= parseFloat(total) - parseFloat(voucherResponse[2]);
	            nowStoreTotal 	  	= parseFloat(nowStoreTotal).toFixed(2);

	            $('.storeTotal_'+storeId).val(nowStoreTotal);
	            $('.storeSubTotal_'+storeId).html(format(nowStoreTotal));

	            // Voucher Total
	            $('.voucherTotal_'+storeId).val(voucherResponse[2]);


	            // Grand Total
	            var orderGrandTotal = $('.orderGrandTotal').val();
	            var grandTotal 		= parseFloat(orderGrandTotal) - parseFloat(voucherResponse[2]);
	            grandTotal 			= parseFloat(grandTotal).toFixed(2);

	            $('.orderGrandTotal').val(grandTotal);
	            $('.grandTotal').html(format(grandTotal));


	            // Message
	            $('#couponType_'+storeId).html(response[2]);
	            $('#couponMark_'+storeId).html('');
	            $('#couponMark_'+storeId).append(
	            	'<span onclick="removeCoupon('+storeId+');" class="btn btn-danger btn-sm">'+
	            	'<i class="fa fa-times"></i></span>');
	            $('#couponMessage_'+storeId).append("<label class='alert-success'>"+ response[1]+"</label>");
	            $("#cod").attr('checked', true).trigger('click');

	        } else {
	        	$('#couponMessage_'+storeId).append("<label class='error'>"+ response[1]+"</label>");
	        	$('.enable_'+storeId).attr('onclick', 'voucherCheck('+storeId+')');
	        }
	    });
	} else {
		$('#couponMessage_'+storeId).append("<label class='error'> <?php echo __('Entrez le promo code svp'); ?></label>");
		$('.enable_'+storeId).attr('onclick', 'voucherCheck('+storeId+')');
	}
}

function removeCoupon(storeId) {
    $('#voucher_'+storeId).val('');
    $('#vouchers_'+storeId).val('');
    $('#voucher_'+storeId).attr('readonly', false);
    $('#couponMessage_'+storeId).html('');
    $('#couponMark_'+storeId).html('');
    $('#couponMark_'+storeId).append(
    	'<a class="btn btn-primary enable_'+storeId+'" onclick="voucherCheck('+storeId+');" href="javascript:void(0);"> Apply</a>');

    $('.voucherShow_'+storeId).hide();
    $('.free_'+storeId).hide();
    $('.freeDelivery_'+storeId).show();
    $('#voucherPercentage_'+storeId).html('');

    var voucherTotal = $('.voucherTotal_'+storeId).val();
    var grandTotal 	 = $('.orderGrandTotal').val();

    var grandTotal 		= parseFloat(grandTotal) + parseFloat(voucherTotal);
	grandTotal 			= parseFloat(grandTotal).toFixed(2);

	$('.orderGrandTotal').val(grandTotal);
	$('.grandTotal').html(grandTotal);

	var nowStoreTotal	= $('.storeTotal_'+storeId).val();
	var nowStoreTotal 	= parseFloat(nowStoreTotal) + parseFloat(voucherTotal);
	nowStoreTotal 	  	= parseFloat(nowStoreTotal).toFixed(2);
	$('.storeSubTotal_'+storeId).html(format(nowStoreTotal));

	$('.storeTotal_'+storeId).val(nowStoreTotal);
	$("#cod").attr('checked', true).trigger('click');
}

function walletCheck() {

	var grandTotal 	 = $('.orderGrandTotal').val();
	var walletAmount = $('#WalletAmount').val();
	$('#walletError').html('');
	$('#walletError').removeAttr('class');

	var paymentMethod = $('input[name="data[Order][paymentMethod]"]:checked').val();

	if (paymentMethod == 'wallet') {

		if (parseFloat(grandTotal) < parseFloat(walletAmount)) {
			var remainWallet = walletAmount - grandTotal;
			$('#walletError').addClass('alert-success');
			$('#walletError').html('<?php echo __('Montant restantdu portefeuille').' '.$siteCurrency.' '; ?>'+remainWallet);
			$('#remainWalletAmount').html(remainWallet);
			return true;

		} else {
			$('#walletError').addClass('error');
			$('#walletError').html('<?php echo __('Montant insuffisant dans le portefeuille, veuillez essayer un autre moyen de paiement'); ?>');
			return false;
		}
	} else {
		$('#remainWalletAmount').html(walletAmount);
		return true;
	}
}

function walletPay() {
	var paymentMethod = $('input[name="data[Order][paymentMethod]"]:checked').val();

	if($.trim(paymentMethod) == '' || $.trim(paymentMethod) == 'showcard') {
		$('#walletError').addClass('error');
		$('#walletError').html('<?php echo __('Please select any card'); ?>');
		return false;
	} else if (paymentMethod == 'wallet') {
		var checkWallet = walletCheck();
		if (!checkWallet) {
			return false;
		}
	}
}

function showCardDetails(val) {
	if(val == 'card') {
		$('.cardpay').show();
	} else {
		$('.cardpay').hide();
	}
}

</script>