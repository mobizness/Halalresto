<div class="container searchshopContent">
	<div class="col-md-12">
		<div class="myorderTab">
			<h1 class="clearfix"> <?php echo __('Order Details Page', true); ?> 
				<span id='sidebar' class="pull-right text-right">
					<span>
						<a class="btn btn-warning" href="<?php echo $siteUrl.'/customer/Customers/pdf/'.$order_detail['Order']['id'];?>"><i class="fa fa-file-pdf-o"></i>&nbsp; <span class="hidden-xs hidden-sm"> <?php echo __('Download in PDF', true); ?></span> </a>

						<a class="btn btn-info" href="javascript:void(0);" onclick="documentPrints();"><i class="fa fa-print"></i>&nbsp; <span class="hidden-xs hidden-sm"> <?php echo __('Print Page', true); ?></span> </a>				
					</span>
					<a class="btn btn-default" href="<?php echo $siteUrl.'/customer/Customers/myaccount'; ?>"><i class="fa fa-angle-double-left"></i>&nbsp; <span class="hidden-xs hidden-sm"> <?php echo __('Back to Order History', true); ?></span></a>
				</span>
				
			</h1>

			<div class="orderTop clearfix">
				<div class="orderId"><span> <?php echo __('Order ID'); ?> :</span> <?php 
				echo $order_detail['Order']['ref_number'];?></div>
				<div class="orderviewDate pull-left-xs margin-t-10-xs"><span> <?php echo __('Order Date', true); ?> :</span>
				<?php echo date("Y/m/d H:i:s", strtotime($order_detail['Order']['created']));?>
				</div>
			</div>
			<div class="orderInfo clearfix">
				<div class="cardDetailHead"> <?php echo __('Order Info', true); ?></div>
				<ul class="col-md-6">
					
					<li><span class="col-md-4 col-sm-4 col-xs-12"> <?php echo __('Customer Email', true); ?></span> <span class="col-md-8 col-sm-8 col-xs-12 site-color"><i>:</i> <?php 
						echo $order_detail['Order']['customer_email'];?></span></li>
					<li><span class="col-md-4 col-sm-4 col-xs-12"> <?php echo __('Phone Number', true); ?></span> <span class="col-md-8 col-sm-8 col-xs-12 site-color"><i>:</i> <?php 
						echo $order_detail['Order']['customer_phone'];?></span></li> <?php

					$orderType = ($order_detail['Order']['order_type'] == 'Collection') ? 'Pickup' : $order_detail['Order']['order_type'];

					if ($order_detail['Order']['order_description']) { ?>

						 <li><span class="col-md-4 col-sm-4 col-xs-12"> <?php echo __('Order Description', true); ?> </span> <span class="col-md-8 col-sm-8 col-xs-12 site-color"><i>:</i> <?php 
					  		echo $order_detail['Order']['order_description']; ?></span></li> <?php 
					}

					 if ($order_detail['Order']['failed_reason']) {  ?>
						 <li>
							 <span class="col-md-4 col-sm-4 col-xs-12"> <?php echo __('Reason', true); ?> </span>
							 <span class="col-md-8 col-sm-8 col-xs-12 site-color"><i>:</i> <?php
								 echo $order_detail['Order']['failed_reason']; ?>
							 </span>
						 </li> <?php
					 } ?>

					<li><span class="col-md-4 col-sm-4 col-xs-12"><?php echo __($orderType).' Date'; ?> </span>
						<span class="col-md-8 col-sm-8 col-xs-12 site-color"><i>:</i> 
						<?php echo date("Y/m/d", strtotime($order_detail['Order']['delivery_date']));?>
                		</span>
                	</li>

					<li><span class="col-md-4 col-sm-4 col-xs-12"><?php echo __($orderType). ' '. __('Time'); ?></span>
						<span class="col-md-8 col-sm-8 col-xs-12 site-color"><i>:</i> <?php
							echo  ($order_detail['Order']['assoonas'] == 'Later') ? $order_detail['Order']['delivery_time'] : 'ASAP'; ?>
						</span>
					</li>

					<li><span class="col-md-4 col-sm-4 col-xs-12"> <?php echo __('Order Type', true); ?> </span> <span class="col-md-8 col-sm-8 col-xs-12 site-color"><i>:</i> <?php
							echo __($orderType); ?></span></li>

	                <li><span class="col-md-4 col-sm-4 col-xs-12"> <?php echo __('Address', true); ?></span> <span class="col-md-8 col-sm-8 col-xs-12 site-color"><i>:</i> <?php
							if($siteSetting['Sitesetting']['address_mode'] != 'Google') {
								$address = $order_detail['Order']['address'] . ', ';
								$address .= ($order_detail['Order']['landmark']) ? $order_detail['Order']['landmark'] . ', ' : '';
								$address .= $order_detail['Order']['location_name'] . ', ' . $order_detail['Order']['city_name'] . ', ' .
										$order_detail['Order']['state_name'] . '.';
							} else {
								$address = $order_detail['Order']['google_address'];
							}

						echo $address; ?></span>
					</li>

				</ul>
				<ul class="col-md-6"> <?php
					if ($order_detail['Order']['delivered_time']) { ?>
						<li><span class="col-md-4 col-sm-4 col-xs-12"> <?php echo __('Delivery Time', true); ?></span> <span class="col-md-8 col-sm-8 col-xs-12 site-color"><i>:</i> <?php 
							echo $order_detail['Order']['delivered_time'];?></span></li> <?php
					} ?>
					
					<li><span class="col-md-4 col-sm-4 col-xs-12"> <?php echo __('Payment Method', true); ?></span>
						<span class="col-md-8 col-sm-8 col-xs-12 site-color"><i>:</i> <?php 
							echo __($order_detail['Order']['payment_type']); ?></span>
					</li>
					<li><span class="col-md-4 col-sm-4 col-xs-12"> <?php echo __('Payment Status', true); ?></span> <span class="col-md-8 col-sm-8 col-xs-12 site-color"><i>:</i> <?php
					if($order_detail['Order']['payment_method'] == "unpaid"){
						echo __('Not Paid');
					} else {
						echo __($order_detail['Order']['payment_method']);
					} ?></span></li>

					<li><span class="col-md-4 col-sm-4 col-xs-12"> <?php echo __('Restaurant', true); ?></span> <span class="col-md-8 col-sm-8 col-xs-12 site-color"><i>:</i> <?php
					echo $order_detail['Store']['store_name'];?></span></li>

					<li><span class="col-md-4 col-sm-4 col-xs-12"> <?php echo __('Order Status', true); ?></span>
						<span class="col-md-8 col-sm-8 col-xs-12 site-color"><i>:</i>  <?php 
							echo ($order_detail['Order']['status'] == 'Collected') ? __('Picked up') : __($order_detail['Order']['status']); ?>
						</span>
					</li>
					<li><span class="col-md-4 col-sm-4 col-xs-12"> <?php echo __('Customer Name', true); ?></span>
						<span class="col-md-8 col-sm-8 col-xs-12 site-color"><i>:</i> <?php 
							echo $order_detail['Order']['customer_name'];?></span>
					</li>
				</ul>
				<div id="printSpace" style="display: none;"><br><br></div>
			</div>
			<div class="table-responsive">          
				<table class="table table-bordered">
					<thead>
						<tr>
							<th> <?php echo __('S.No', true); ?></th>
							<th class="text-left"> <?php echo __('Item Name', true); ?></th>
							<th> <?php echo __('Qty', true); ?></th>
							<th> <?php echo __('Price', true); ?></th>
							<th> <?php echo __('Total Price', true); ?></th>
						</tr>
					</thead>
					<tbody><?php 
					if (!empty($order_detail)) {
						$count = 1;
						foreach ($order_detail['ShoppingCart'] as $key => $value) { ?>
							<tr>
								<td><?php echo $count;?></td>
								<td  class="text-left"> <span class="menu_name"><?php 
										echo $value['product_name'];?>
									</span>
									<div class="addon_name whitenormal"><?php echo $value['subaddons_name'];
									if (!empty($value['product_description'])) { ?>
										<div class="margin-t-5 whitenormal"><?php echo $value['product_description']; ?></div></div> <?php
									} ?>
								</td>
								<td><?php echo $value['product_quantity'];?></td>
								<td><?php echo html_entity_decode($this->Number->currency( $value['product_price'], $siteCurrency));?></td>
								<td class="price"><?php
										echo html_entity_decode($this->Number->currency($value['product_total_price'], $siteCurrency)); ?>
									</td>
								
							</tr><?php
							$count++;
						} ?>
						<tr class="grandprice">
							<td class="text-right" colspan="4"> <?php echo __('Subtotal', true); ?></td>
							<td class="price"><?php
								echo html_entity_decode($this->Number->currency($order_detail['Order']['order_sub_total'], $siteCurrency)); ?>
							</td>
						</tr> <?php 

						if (isset($order_detail['Order']['offer_amount']) && $order_detail['Order']['offer_amount'] != 0) {?>
							<tr class="grandprice">
								<td class="text-right" colspan="4"> <?php echo __('Offer', true);
									echo ' ('.$order_detail['Order']['offer_percentage'].' %)'; ?></td>
								<td class="price"><?php
									echo html_entity_decode($this->Number->currency($order_detail['Order']['offer_amount'], $siteCurrency)); ?>
								</td>
							</tr> <?php
						}

						if (isset($order_detail['Order']['voucher_amount']) && $order_detail['Order']['voucher_amount'] != 0) {?>
							<tr class="grandprice">
								<td class="text-right" colspan="4"> <?php echo __('Voucher Discount', true);
									echo ($order_detail['Order']['voucher_percentage'] > 0) ? ' ('.$order_detail['Order']['voucher_percentage'].' %)' : ''; ?></td>
								<td class="price"><?php
									echo html_entity_decode($this->Number->currency($order_detail['Order']['voucher_amount'], $siteCurrency)); ?>
								</td>
							</tr> <?php
						}

						if ($order_detail['Order']['tax_amount'] != 0) {?>
							<tr class="grandprice">
								<td class="text-right" colspan="4"> <?php echo __('Tax', true);
									echo ' ('.$order_detail['Order']['tax_percentage'].' %)';
								 ?> </td>
								<td class="price"><?php
									echo html_entity_decode($this->Number->currency($order_detail['Order']['tax_amount'], $siteCurrency)); ?>
								</td>
									
							</tr><?php 
						}

						if ($order_detail['Order']['delivery_charge'] != 0 || $order_detail['Order']['voucher_code'] != '' && $order_detail['Order']['voucher_amount'] == 0) { ?>
							<tr class="grandprice">
								<td class="text-right" colspan="4"> <?php echo __('Delivery Charge', true); ?></td>
								<td class="price"><?php
									echo ($order_detail['Order']['delivery_charge'] != 0) ?
										html_entity_decode($this->Number->currency($order_detail['Order']['delivery_charge'], $siteCurrency)) :
										__('Free Delivery'); ?>
								</td>
							</tr> <?php 
						}

						if ($order_detail['Order']['tip_amount'] != 0) {?>
							<tr class="grandprice">
								<td class="text-right" colspan="4"> <?php echo __('Tips', true); ?>
								</td>
								<td class="price"><?php
									echo html_entity_decode($this->Number->currency($order_detail['Order']['tip_amount'], $siteCurrency)); ?>
								</td>
							</tr> <?php
						} ?>

						<tr class="grandprice">
							<td class="text-right" colspan="4"> <?php echo __('Total', true); ?></td>
							<td class="price"><?php
								echo html_entity_decode($this->Number->currency($order_detail['Order']['order_grand_total'], $siteCurrency)); ?>
							</td>
						</tr> <?php 
					} ?>						
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>