<div class="panel panel-default myorderTab">
	<div class="panel-body addressBg">
		<div class="panel-subheading">
			<h3> <?php echo __('Review & Confirm', true); ?></h3>
		</div> <?php
			$serialNo = $storeSubtotal = 0;

			$storeMain = $storeDetails['Store']['id']; ?>
		<div class="storeinfohead"><span><?php echo __('Restaurant Name'); ?> :</span>  <?php echo $storeDetails['Store']['store_name']; ?> </div>
		<div class="order_reviewForm clearfix"> 
			<div class="form-group pull-right">
				<div class="col-sm-9 col-xs-9"> <?php 
					echo $this->Form->input('',
								array('class'=>'form-control',
									  'autocomplete' => 'off',
									  'type' => 'text',
									  'placeholder' => 'Entrer code promo',
									  'id' => 'voucher_'.$storeMain,
	                                  'label' => false,
	                                  'onkeypress' => 'orderPlace(event);',
									  'div' => false)); ?>
						  	<span id="couponMessage_<?php echo $storeMain; ?>">
						  		<label class="error"></label>
						  	</span>
				</div>
				<span id="couponMark_<?php echo $storeMain; ?>" class="col-sm-3 col-xs-3">
					<a class="btn btn-primary enable_<?php echo $storeMain; ?>" onclick="voucherCheck(<?php echo $storeMain; ?>);" href="javascript:void(0);"> Ajouter</a>
				</span>
			</div> 
		</div> <?php
		echo $this->Form->input('', 
				array('class'=>'form-control voucherCodes',
						'autocomplete' => 'off',
						'type' => 'hidden',
						'name' => 'data[Order][voucher_code]',
						'id' => 'vouchers_'.$storeMain,
						'label' => false,
						'div' => false)); ?>
		<div class="table-responsive">
			<table class="table table-hover table-bordered">
				<thead>
					<tr>
						<th> N°</th>
						<th class="text-left"> <?php echo __('Item Name', true); ?></th>
						<th> <?php echo __('Qty', true); ?></th>
						<th> <?php echo __('Price', true); ?></th>
						<th> <?php echo __('Total Price', true); ?></th>
					</tr>
				</thead>
				<tbody><?php
					foreach ($shopCart as $key => $value) {
						
						$storeSubtotal	+= $value['ShoppingCart']['product_total_price']; ?>
						<tr>
							<td><?php echo $serialNo +=1; ?></td>
							<td class="text-left"><span class="menu_name"><?php echo $value['ShoppingCart']['product_name']; ?></span>
								<span class="addon_name whitenormal"><?php echo (!empty($value['ShoppingCart']['subaddons_name'])) ? '<br>('.$value['ShoppingCart']['subaddons_name'].')' : ''; ?></span>
								<?php if (!empty($value['ShoppingCart']['product_description'])) { ?>
									<div class="margin-t-5 whitenormal"><?php echo $value['ShoppingCart']['product_description']; ?></div> <?php
								}?></td>
							<td><?php echo $value['ShoppingCart']['product_quantity']; ?></td>
							<td><?php echo html_entity_decode($this->Number->currency($value['ShoppingCart']['product_price'], $siteCurrency)); ?></td>
							<td><?php echo html_entity_decode($this->Number->currency($value['ShoppingCart']['product_total_price'], $siteCurrency)); ?></td>
						</tr> <?php
					} ?>

					<tr class="toatlprice">
						<td colspan="4" class="text-right"> <?php echo __('Sub Total', true); ?></td>
						<td> <?php
							echo html_entity_decode($this->Number->currency($storeSubtotal, $siteCurrency)); ?></td>
					</tr> <?php


					if (isset($offerDetails['storeOffer']) && $offerDetails['storeOffer'] != 0) { ?>
						<tr class="grandprice">
							<td colspan="4" class="text-right"><?php //echo $offerDetails['store_name']; ?> 	<?php echo __('Offer'); ?> (<?php echo $offerDetails['offerPercentage'].'%'; ?>) </td>
							
							<td> <?php
								if ($offerDetails['storeOffer'] != 0) {
									
									$storeSubtotal	-= $offerDetails['storeOffer'];
								}
								echo html_entity_decode($this->Number->currency($offerDetails['storeOffer'], $siteCurrency)); ?></td>
						</tr> <?php
					}

					if (isset($taxDetails['tax']) && $taxDetails['tax'] != 0) { ?>
						<tr class="grandprice">
							<td colspan="4" class="text-right"> <?php 
								echo __('Tax'); ?>
								(<?php echo $taxDetails['tax'].'%'; ?>) </td>
							<td> <?php
								if (isset($taxDetails['tax']) && $taxDetails['tax'] != 0) {
									
									$storeSubtotal	+= $taxDetails['taxAmount'];
								}
								echo html_entity_decode($this->Number->currency($taxDetails['taxAmount'], $siteCurrency)); ?></td>
						</tr> <?php
					}
					
                    if (isset($taxDetails['deliveryCharge']) && $taxDetails['deliveryCharge'] != 0) { ?>
                        <tr class="grandprice">
                            <td colspan="4" class="text-right"> <?php
                                echo __('Delivery Charge'); ?>
                            </td>
                            <td> <?php
                                if (isset($taxDetails['deliveryCharge']) && $taxDetails['deliveryCharge'] != 0) {

                                    $storeSubtotal	+= $taxDetails['deliveryCharge'];
                                } ?>
                                <span class="freeDelivery_<?php echo $storeMain ?>"> <?php
                                echo html_entity_decode($this->Number->currency($taxDetails['deliveryCharge'], $siteCurrency)); ?></span>
                                <span class="free_<?php echo $storeMain ?>" style="display: none"> <?php
                                	echo __('Free Delivery'); ?> </span>
							</td>

                        </tr> <?php
                    } ?>


                    <tr class="grandprice voucherShow_<?php echo $storeMain ?>" style="display: none">
						<td colspan="4" class="text-right">Promo Discount
							<span id="voucherPercentage_<?php echo $storeMain ?>"></span>
						</td>
						<td> <?php echo $siteCurrency.' '; ?>
							<span id="voucherPrice_<?php echo $storeMain ?>"></span> <?php
							echo $this->Form->input('', 
											array('class'=>'voucherTotal_'.$storeMain,
				        							'type' => 'hidden',
				        							'label' => false,
				        							'div' => false)); ?>
						</td>
					</tr>
                    <tr class="grandprice">
						<td colspan="4" class="text-right"> <?php echo __('Total', true); ?></td>
						<td> <?php 
							echo $siteCurrency.' '; ?>

							<span class="storeSubTotal_<?php echo $storeMain ?>"> <?php 
								echo number_format($storeSubtotal, 2); ?></span> <?php
								echo $this->Form->input('', 
											array('class'=>'storeTotal_'.$storeMain,
				        							'type' => 'hidden',
				        							'label' => false,
				        							'value' => $storeSubtotal,
				        							'div' => false)); ?>
			        	</td>
					</tr> <?php
                    if (isset($taxDetails['tipPercentage']) && $taxDetails['tipPercentage'] != 0) { ?>
                        <tr class="grandprice">
                            <td colspan="4" class="text-right"> Pourboire </td>
                            <td> <?php
                                /*if (isset($taxDetails['tipPercentage']) && $taxDetails['tipPercentage'] != 0) {
                                    $tipAmount = ($storeSubtotal * $taxDetails['tipPercentage']) / 100;
                                    $storeSubtotal	+= $tipAmount;
                                }*/

                                $storeSubtotal	+= $taxDetails['tipPercentage'];
                                echo html_entity_decode($this->Number->currency($taxDetails['tipPercentage'], $siteCurrency)); ?></td>
                        </tr> <?php
                    } ?>
				</tbody>
			</table>
		</div>
		<div class="grand_outer">
			<span class="grand_left">  <?php echo __('Grand Total', true); ?></span>
			<span class="grand_right"> <?php echo $siteCurrency.' '; ?>
			<span class="grandTotal"> <?php 
			echo number_format($storeSubtotal, 2); ?></span> <?php
			echo $this->Form->input('', 
							array('class'=>'orderGrandTotal',
        							'type' => 'hidden',
        							'label' => false,
        							'value' => $storeSubtotal,
        							'div' => false)); ?></span>
		</div>
		<div class="checkout-bottom checkoutbtm">
			<a onclick="checkoutpagintaion('#reviewConform','#payment');" class="btn btn-primary pull-right"><?php echo __('Continue', true); ?></a>
			<a onclick="checkoutpagintaion('#reviewConform','#deliverAddress');" class="btn btn-default pull-left"><?php echo __('Back to Address', true); ?></a>
		</div>
	</div>
</div>