<div class="col-sm-3 no-padding-right">
	<h1 class="cartcheckhead"><?php echo __('Your basket');?></h1>	
		<?php
		foreach($storeCart as $key => $value) {
			$storeGrandtotal	+= $value['ShoppingCart']['product_total_price'];?>
				<ul class="check_right">
				<li class="quantity_chk"><?php
					if ($value['ShoppingCart']['product_quantity'] < 100) { ?>
							<i class="fa fa-caret-up" onclick="checkoutQtyInc(<?php echo $value['ShoppingCart']['id']; ?>,this)"></i><br>
					<?php } else { ?> 
							<i class="fa fa-caret-up"></i><br>
					<?php }
					if ($value['ShoppingCart']['product_quantity'] != 1) { ?>
							<i class="fa fa-caret-down" onclick="checkoutQtyDec(<?php echo $value['ShoppingCart']['id']; ?>,this)"></i>
					<?php } else { ?>
							<i class="fa fa-caret-down" <?php if(count($storeCart) > 1) {?>onclick="deleteCheckoutCart(<?php echo $value['ShoppingCart']['id']; ?>)" <?php } ?>></i>
					<?php }
					
					 ?>
				</li>

				<li class="item_chk"> 

				<?php echo $value['ShoppingCart']['product_quantity']; ?> x <?php echo $value['ShoppingCart']['product_name']; ?><br><?php echo $value['ShoppingCart']['subaddons_name']; ?><br><?php echo $value['ShoppingCart']['product_description']; ?></li>
				
				<li class="check_out"><span class="new-price-cell"><?php echo html_entity_decode($this->Number->currency($value['ShoppingCart']['product_total_price'], $siteCurrency)); ?></span>
				<?php if(count($storeCart) > 1) {?>
					<span class="new-remove-cell"><a class="remove-item pointer" onclick="deleteCheckoutCart(<?php echo $value['ShoppingCart']['id']; ?>)">×</a></span>
				<?php } else { ?>
					<span class="new-remove-cell"><?php echo html_entity_decode($this->Number->currency($value['ShoppingCart']['product_total_price'], $siteCurrency)); ?></span>
				<?php } ?>
				</li>
				</ul>
		<?php }?>				
	
    <div class="form-group form_group_new newcadd text-center"> <?php  
     	$option1 = array('Collection' 	=> '');
		$option2 = array('Delivery' 	=> ''); 
     	if ($storeCart[0]['Store']['delivery'] == 'Yes' && $storeCart[0]['Store']['collection'] == 'Yes') {?>
				<label class="rdio_classnew radio_classreduce"> <?php
		          	echo $this->Form->radio('order_type'.$storeId,$option1,
		  							array('checked'=>$option1,
		  								'label'=>false,
		  								'legend'=>false,
		  								'class'=>'hidden',
		  								'name' => 'data[Order][orderType]',
		  								'onclick' => 'return checkDelType(this.value)',
		  								'checked' => ($this->Session->read('deliveryType') == 'Collection') ? true : false,
		  								'hiddenField'=>false)); ?>
					<span class="rdimg"><?php echo __('Pickup');?></span>
		        </label>
		        <label class="rdio_classnew radio_classreduce">  <?php 
		           	echo $this->Form->radio('order_type'.$storeId,$option2,
		   							array('checked'=>$option2,
		   								'label'=>false,
		   								'legend'=>false,
		   								'class'=>'hidden',
		   								'name' => 'data[Order][orderType]',
		   								'onclick' => 'return checkDelType(this.value)',
		   								'checked' => ($this->Session->read('deliveryType') == 'Delivery') ? true : false,
		   								'hiddenField'=>false)); ?>
		   			<span class="rdimg"><?php echo __('Delivery');?></span>
		        </label> <?php
		}elseif ($storeDetails['Store']['collection'] == 'Yes') { ?>
        	<label> <?php
              	echo $this->Form->radio('order_type'.$storeId,$option1,
      							array('checked'=>$option1,
										'label'=>false,
      									'legend'=>false,
      									'class'=>'hidden',
      									'name' => 'data[Order][orderType]',
										'checked' => 'checked',
      									'hiddenField'=>false)); ?>
				<span class="rdimg"><?php echo __('Pickup'); ?></span>
            </label> <?php
        } elseif ($storeDetails['Store']['delivery'] == 'Yes') { ?>

        	<label>  <?php 
           		echo $this->Form->radio('order_type'.$storeId,$option2,
   							array('checked'=>$option2,
   								'label'=>false,
   								'legend'=>false,
   								'class'=>'hidden',
   								'name' => 'data[Order][orderType]',
   								'checked' => 'checked',
   								'hiddenField'=>false)); ?>
				<span class="rdimg"><?php echo __('Delivery'); ?></span>
            </label> <?php
        } ?>
    </div>

    <div class="bg_white">
	    <div class="order_reviewForm clearfix"> 
			<div class="form-group pull-right">
				<div class="col-sm-9 col-xs-9 font_minsize no-padding-left"> <?php 
					echo $this->Form->input('',
								array('class'=>'form-control',
									  'autocomplete' => 'off',
									  'type' => 'text',
									  'placeholder' => 'XXXX',
									  'id' => 'voucher_'.$storeDetails['Store']['id'],
	                                  'label' => __('Voucher'),
	                                  'onkeypress' => 'orderPlace(event);',
									  'div' => false)); ?>
						  	<span id="couponMessage_<?php echo $storeDetails['Store']['id']; ?>">
						  		<!-- <label class="error"></label> -->
						  	</span>
				</div>
				<div class="col-sm-3 col-xs-3 no-padding">
					<span id="couponMark_<?php echo $storeDetails['Store']['id']; ?>">
						<a class="btn btn-primary spacing_new enable_<?php echo $storeDetails['Store']['id']; ?>" onclick="voucherCheck(<?php echo $storeDetails['Store']['id']; ?>);" href="javascript:void(0);"><i class="fa fa-check" aria-hidden="true"></i></a>
					</span>
				</div>
			</div> 
		</div> 	

		<?php
		echo $this->Form->input('', 
				array('class'=>'form-control voucherCodes',
						'autocomplete' => 'off',
						'type' => 'hidden',
						'name' => 'data[Order][voucher_code]',
						'id' => 'vouchers_'.$storeDetails['Store']['id'],
						'label' => false,
						'div' => false)); ?>
	    
	</div>

    <div class="ch_fgnew">
    
    	<div class="col-sm-12">
     		<div class="pull-left"><?php echo __('Subtotal');?></div>
			<div class="pull-right"><?php echo html_entity_decode($this->Number->currency($cartTotal, $siteCurrency)); ?></div>
		</div>
		<div class="clearfix"></div>

		
		<div id="checkdeliverytab" <?php if($this->Session->read('deliveryType') != 'Delivery' || $this->request->data['google_address'] == '') {?> style="display: none;" <?php } ?>><?php 

			if ($this->Session->read('deliveryType') == 'Delivery' && $value['Store']['delivery_charge'] != 0) {
				 	
			 	if($this->request->data['google_address'] != '') {
	            	$storeGrandtotal	+= $value['Store']['delivery_charge'];
	            } ?>
	            <div class="col-sm-12">
					<div class="pull-left"><?php echo __('Delivery Fee');?></div>
					<div class="pull-right"><?php echo html_entity_decode($this->Number->currency($value['Store']['delivery_charge'], $siteCurrency)); ?></div>
				</div><?php 
			}  ?>

		</div>
		

		<div class="clearfix"></div>

		<?php if (isset($offerDetails['storeOffer']) && $offerDetails['storeOffer'] != 0) { 
			if ($offerDetails['storeOffer'] != 0) {							
				$storeGrandtotal	-= $offerDetails['storeOffer'];
			}?>
			<div class="col-sm-12">
				<div class="pull-left"><?php echo __('Offer'); ?> (<?php echo $offerDetails['offerPercentage'].'%'; ?>) </div>

				<div class="pull-right"><?php					
			echo html_entity_decode($this->Number->currency($offerDetails['storeOffer'], $siteCurrency)); ?></div>
			</div>

	 	<?php	}?>

	 	<div class="clearfix"></div>

	 	<div class="voucherShow_<?php echo $storeDetails['Store']['id'] ?>" style="display: none">
	 		<div class="col-sm-12">
		 		<div class="pull-left" id="voucherper">Voucher</div>	 		
				<div class="pull-right">
					<span id="voucherPercentage_<?php echo $storeDetails['Store']['id'] ?>"></span>
					<span id="voucherPrice_<?php echo $storeDetails['Store']['id'] ?>"></span>
				</div>
			</div>
				<?php
					echo $this->Form->input('', 
									array('class'=>'voucherTotal_'.$storeDetails['Store']['id'],
		        							'type' => 'hidden',
		        							'label' => false,
		        							'div' => false)); ?>
		</div>

		<?php if ($value['Store']['tax'] != 0) {

			$tax_amount = $cartTotal * ($value['Store']['tax']/100);

			$storeGrandtotal	+= $tax_amount;?>
			<div class="col-sm-12">
			
				<div class="pull-left"> <?php echo __('Tax', true);
						echo ' ('.$value['Store']['tax'].' %)';
					 ?></div>
				<div class="pull-right"><?php
					echo html_entity_decode($this->Number->currency($tax_amount, $siteCurrency)); ?>
				</div>
			</div>
			<?php 
		}?>


		<div class="clearfix"></div>
		<div class="col-sm-12">
			<div class="pull-left tot_bold">Total</div>
			<div class="pull-right tot_bold storeSubTotal_<?php echo $storeDetails['Store']['id'];?>"><?php
					echo html_entity_decode($this->Number->currency($storeGrandtotal, $siteCurrency)); ?></div>
		</div>

		<?php 
				echo $this->Form->input('', 
							array('class'=>'storeTotal_'.$storeDetails['Store']['id'],
        							'type' => 'hidden',
        							'label' => false,
        							'value' => $storeGrandtotal,
        							'div' => false)); ?>

		<?php
			echo $this->Form->input('', 
							array('class'=>'orderGrandTotal',
        							'type' => 'hidden',
        							'label' => false,
        							'value' => $storeGrandtotal,
        							'div' => false)); ?>

		<div class="col-sm-12 text-center"><!-- <?php
		echo $this->Form->button('Place Order',
				                              array('class'=>'btn btn-primary pull-right',
				                              		'onclick' => 'return walletPay();')); ?> -->
			<button type="button" id="pagebtn" class="btn btn-sm btn-default margin-t-15 kassen_btn" onclick="checkoutpagintaion('#deliverAddress','#paymentConfirm');"><?php echo __('Checkout');?></button>

			<button type="button" id="orderbtn" class="btn btn-sm btn-default margin-t-15 kassen_btn" style="display: none;" onclick="return submitOrder();"><?php echo __('Checkout');?></button>

			<a class="btn btn-default margin-t-15 tilfo_btn" href="<?php echo $siteUrl.'/shop/'.$storeDetails['Store']['seo_url'].'/'.base64_encode($storeId); ?>" id="backlink"><?php echo __('Back to menu');?></a>

			<button type="button" id="backbtn" class="btn btn-default margin-t-15 tilfo_btn" style="display: none;" onclick="return checkoutpagintaion('#paymentConfirm','#deliverAddress');"><?php echo __('Back to menu');?></button>
		</div>
	</div>
</div>