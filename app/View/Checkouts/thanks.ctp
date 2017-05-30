

<section class="checkout_banner">
	<div class="container">
		<div class="col-sm-12 text-center">
			<h1 class="check_head"><?php echo __('Carry out your order');?></h1>
			<!-- <div class="test col-xs-12 no-padding">
				<div class="col-sm-4 check_steps text-left">
					<a href="#">1.<?php echo __('Your information');?></a>
				</div>
				<div class="col-sm-4 check_steps text-center">
					<a href="#">2.<?php echo __('Payment');?></a>
				</div>
				<div class="col-sm-4 check_steps text-right">
					<a href="#">3.<?php echo __('Confirmation');?></a>
				</div>
			</div> -->
		</div>
	</div>
</section>
<!-- <section class="contact_list">
	<div class="container container_btm">
		
		<div class="col-sm-6">
			
			<span><a href="#"><?php echo __('Carry out your order');?></a></span>
			<span>></span>
			<span><a href="#"><?php echo __('Payment');?></a></span>
			<span>></span>
			<span class="bold"><a href="#"><?php echo __('Confirmation');?></a></span>
		</div>
		
	</div>
</section> -->
<section class="checkout_inner checkout_innerthhanks">
	<div class="container">
		<div class="col-sm-3 no-padding-left">
		</div>

		<div class="col-sm-6">				

				<div class="col-sm-12 mar-t-35" id="thanks">
					<h1 class="final_head"><?php echo __('Order');?> - <?php echo $order_detail['Order']['ref_number'];?></h1> 
					<div class="bg_white bg_white_new">						
						<h3 class="text-center fsub_head"><?php echo __('Thank you for order');?></h3>
						
						<?php
							if (!empty($order_detail)) {
								$count = 1;?><?php
									foreach ($order_detail['ShoppingCart'] as $key => $value) { ?>
										<div class="text-center bgc_grey">

											<h5 class="col-sm-3 no-padding"><?php echo $value['product_quantity'];?> X </h5>

											<h5 class="col-sm-5 no-padding"><?php echo $value['product_name'];?><br>
											<?php echo $value['subaddons_name'];?><?php echo $value['product_description']; ?></h5>
											<h5 class="col-sm-4 no-padding"><?php
													echo html_entity_decode($this->Number->currency($value['product_total_price'], $siteCurrency)); ?>
											</h5>
										</div> <?php
											$count++;
									} ?>								

								<div class="text-center">
									<h5 class="col-sm-6 no-padding text-right col-xs-6"> <?php echo __('Subtotal', true); ?></h5>
									<h5 class="col-sm-6 no-padding col-xs-6"><?php
										echo html_entity_decode($this->Number->currency($order_detail['Order']['order_sub_total'], $siteCurrency)); ?>
									</h5>
								</div><?php 

									if (isset($order_detail['Order']['offer_amount']) && $order_detail['Order']['offer_amount'] != 0) {?>
										<div class="text-center">
											<h5 class="col-sm-6 no-padding text-right col-xs-6"> <?php echo __('Offer', true); ?></h5>
											<h5 class="col-sm-6 no-padding col-xs-6"><?php
												echo html_entity_decode($this->Number->currency($order_detail['Order']['offer_amount'], $siteCurrency)); ?>
											</h5>
										</div><?php
									}?>
								

								<?php

									if (isset($order_detail['Order']['voucher_amount']) && $order_detail['Order']['voucher_amount'] != 0) {?>
										<div class="text-center">
											<h5 class="col-sm-6 no-padding text-right col-xs-6"> <?php echo __('Voucher Discount', true); 
												echo ($order_detail['Order']['voucher_percentage'] > 0) ? ' ('.$order_detail['Order']['voucher_percentage'].' %)' : ''; ?></h5>
											<h5 class="col-sm-6 no-padding col-xs-6"><?php
												echo html_entity_decode($this->Number->currency($order_detail['Order']['voucher_amount'], $siteCurrency)); ?>
											</h5>
										</div> <?php
									}?>

								

								<?php

								if ($order_detail['Order']['tax_amount'] != 0) {?>
									<div class="text-center">
										<h5 class="col-sm-6 no-padding text-right col-xs-6"> <?php echo __('Tax', true);
												echo ' ('.$order_detail['Order']['tax_percentage'].' %)';
											 ?></h5>
										<h5 class="col-sm-6 no-padding col-xs-6"><?php
											echo html_entity_decode($this->Number->currency($order_detail['Order']['tax_amount'], $siteCurrency)); ?>
										</h5>
									</div><?php 
								}?>

								
								<?php

								if ($order_detail['Order']['delivery_charge'] != 0 || $order_detail['Order']['voucher_code'] != '' && $order_detail['Order']['voucher_amount'] == 0) { ?>
									<div class="text-center">
										<h5 class="col-sm-6 no-padding text-right col-xs-6"> <?php echo __('Delivery Charge', true); ?></h5>
										<h5 class="col-sm-6 no-padding col-xs-6"><?php
												echo ($order_detail['Order']['delivery_charge'] != 0) ?
													html_entity_decode($this->Number->currency($order_detail['Order']['delivery_charge'], $siteCurrency)) :
													__('Free Delivery'); ?>
										</h5>
									</div><?php 
								}?>

								
								<?php

									if ($order_detail['Order']['tip_amount'] != 0) {?>
										<div class="text-center">
											<h5 class="col-sm-6 no-padding text-right col-xs-6"> <?php echo __('Tips', true); ?></h5>
											<h5 class="col-sm-6 no-padding col-xs-6"><?php
												echo html_entity_decode($this->Number->currency($order_detail['Order']['tip_amount'], $siteCurrency)); ?>
											</h5>
										</div><?php
									} ?>
								
								<div class="text-center bgc_black">									
										<h5 class="col-sm-6 no-padding text-right col-xs-12"><?php echo __('Total', true); ?><span> <?php if(!empty($order_detail['Order']['delivery_charge']) && ($order_detail['Order']['delivery_charge'] != 0)) {?>(<?php echo __('with delivery');?>) <?php } ?></span></h5>
										<h5 class="col-sm-6 no-padding col-xs-12"><?php
											echo html_entity_decode($this->Number->currency($order_detail['Order']['order_grand_total'], $siteCurrency)); ?>
										</h5>
									
								</div><?php 
							} ?>
					</div>
				</div>
			
		</div>
		
	</div>
</section><?php

