<div class="cart-items"> <?php
	$main = $enable = $minOrder = 0;
	foreach ($storeCart as $key => $value) {

		$nextValue = $key+1;
		if ($value['ShoppingCart']['store_id'] != $main) { 
			$main = $value['ShoppingCart']['store_id']; ?>
    <div class="cartItemsInner">

        <table class="table">
            <tbody> <?php }  ?>
            <div class="cartTr newcarttr">
                <div class="qty newqty"> <?php

								if ($value['ShoppingCart']['product_quantity'] < 100) { ?>
                    <a class="change-qty qty-inc pointer" onclick="qtyIncrement(<?php echo $value['ShoppingCart']['id']; ?>, this)" >
                        <i class="fa fa-caret-up"></i>
                    </a> <?php
								} else { ?>

                    <a class="change-qty qty-inc" >
                        <i class="fa fa-caret-up"></i>
                    </a> <?php
								} ?>


                    <div><?php echo $value['ShoppingCart']['product_quantity']; ?> x </div> <?php

								if ($value['ShoppingCart']['product_quantity'] != 1) { ?>
                    <a class="change-qty qty-dec pointer" onclick="qtyDecrement(<?php echo $value['ShoppingCart']['id']; ?>, this)">
                        <i class="fa fa-caret-down"></i>
                    </a> <?php
								} else { ?>
                    <a class="change-qty qty-dec">
                        <i class="fa fa-caret-down"></i>
                    </a> <?php

								} ?>

                </div>

							<?php $imageSrc = $siteUrl.'/stores/'.$value['ShoppingCart']['store_id'].'/products/carts/'.$value['ShoppingCart']['product_image']; ?>

                <!--							<div class="image newimage">
                                                                                <img src="<?php echo $imageSrc; ?>" alt="<?php echo $value['ShoppingCart']['product_name']; ?>" title="<?php echo $value['ShoppingCart']['product_name']; ?>" onerror="this.onerror=null;this.src='<?php echo $siteUrl."/images/no-imge.jpg"; ?>'">
                                                                        </div>-->


                <div class="name newname">
                    <span class="item-clickable"><?php echo $value['ShoppingCart']['product_name']; ?></span>
                    <br><?php echo $value['ShoppingCart']['subaddons_name']; ?>
                    <div class=" col-xs-12 no-padding">
                            <!-- <span class="pull-left font-13"> item size 1 </span> -->

                        <a class="add-note pull-right pointer btn btn-warning btn-xs" onclick="addnote(this)"><i class="icon-pencil"></i> <?php echo __('add note', true); ?> </a>


                        <div class="edit-special-instructions hide"> <?php
										echo $this->Form->input('productDescription',
								                            array('class' => 'special-instructions-box form-control',
								                            	  //'type'  => 'textarea',
								                            		'cols' => 2,
								                            		'row' => 3,
								                            		'id' => 'productDescription'.$value['ShoppingCart']['id'],
								                                  	'label' => false,
								                                  	'value' => $value['ShoppingCart']['product_description'])); ?>

                            <a id="<?php echo $value['ShoppingCart']['id']; ?>" class="btn btn-xs btn-primary btn-save-note" onclick="description(<?php echo $value['ShoppingCart']['id']; ?>)"><?php echo __('Save', true); ?></a>
                            <button class="btn btn-cancel-note btn-xs cancelinst" onclick="cancelnote(this)"><?php echo __('Cancel', true); ?></button>
                        </div>
                        <div class="gifload"></div>
                    </div>

                </div>
                <div class="price newprice">
                    <div class="">
                        <span class="price-cell"><?php echo number_format((float)$value['ShoppingCart']['product_total_price'], 2, '.', '') . "&euro;"; ?>
                        </span>
                        <span class="remove-cell"><a class="remove-item pointer" onclick="deleteCart(<?php echo $value['ShoppingCart']['id']; ?>)">×</a></span>
                    </div>
                </div>
            </div> <?php

		if (!isset($storeCart[$nextValue]['ShoppingCart']['store_id']) || 
				  $storeCart[$nextValue]['ShoppingCart']['store_id'] != $main) {  ?>

            </tbody>
        </table>
        <div class="warehouse-cart">

            <div class="warehouse-cart-header clearfix"><?php
						foreach ($storeProduct as $keys => $values) {
							if ($values['ShoppingCart']['store_id'] == $main) {
								$productCount = $values[0]['productCount'];
								$productTotal = $values[0]['productTotal'];
	                            $minimum_order = ($siteSetting['Sitesetting']['address_mode'] == 'Google') ?
	                                $value['Store']['minimum_order'] : $minimumOrder;

								if ($values[0]['productTotal'] < $minimum_order) {

									$minOrder = 1;
								}
							}

						} ?> 
                <div class="col-md-8 text-left">						
                    <span class="u-textTruncate warehouse-name truncate relative">
<!--                        <span class="storeitem_counts"><?php echo $productCount; ?></span>-->
								<?php //  echo $value['Store']['store_name'];

								 ?>
                        Total
                    </span>
                    <span style="font-size:10px;">(TVA Incl.)</span>
                </div>
                <div class="col-md-4 text-right no-padding-l">
                    <span class="error price-well">
								<?php echo number_format((float)$productTotal, 2, '.', '') . "&euro;"; ?>
                    </span>
                </div>
            </div> <?php

					if ($minOrder != 0) {
						$minOrder = 0;?>
            <div class="warehouse-header-min"> <?php
	                    $mustOrder = ($siteSetting['Sitesetting']['address_mode'] == 'Google') ?
	                        $value['Store']['minimum_order'] : $minimumOrder;
						echo __('Minimum Order').' '. number_format((float)$mustOrder, 2, '.', '') . "&euro;";

						$enable = 1; ?>
            </div><?php
					} ?>				
        </div>
    </div> <?php
		}
	} ?>
</div> <?php
if (!empty($storeCart)) { ?>
<div class="cart-checkout hidden-xs"> <?php

		if (isset($loggedUser['role_id']) && $loggedUser['role_id'] == 4) { ?>
    <a class="btn-checkout <?php echo ($enable != 0) ? 'opacity_5' : '';?>" href="<?php echo ($enable == 0) ? $siteUrl.'/checkouts/index' : 'javascript:void(0)';?>"> <span class="hidden-xs"> <?php echo __('Checkout', true); ?></span>  ( <?php echo number_format((float)$cartTotal,2, '.', '') . "&euro;"; ?> ) </a> <?php
		} else { ?>
    <a class="btn-checkout <?php echo ($enable != 0) ? 'opacity_5' : '';?>" href="<?php echo ($enable == 0) ? $siteUrl.'/customer/users/customerlogin?page=checkout' : 'javascript:void(0)'; ?>"> <span class="hidden-xs"> <?php echo __('Checkout', true); ?></span>  <?php //echo number_format((float)$cartTotal,2, '.', '') . "&euro;"; ?>  <span class="visible-xs pull-right"><i class="fa fa-long-arrow-right"></i></span></a> <?php
		} ?>

</div> 
<div class="mobile_cart">
    <div class="">
        <span class="pull-left">
            <div class="mobile_cart_price" href="javascript:void(0);" >
                <span class="cartTotal">
                    Total : <?php echo number_format((float)$cartTotal,2, '.', '') . "&euro;"; ?>
                </span>
            </div>
        </span>
        <span class="pull-right mobile_btn-checkout">
				<?php

				if (isset($loggedUser['role_id']) && $loggedUser['role_id'] == 4) { ?>
            <a class="checkout_arrow <?php echo ($enable != 0) ? 'opacity_5' : '';?>" href="<?php echo ($enable == 0) ? $siteUrl.'/checkouts/index' : 'javascript:void(0)';?>"><i class="fa fa-long-arrow-right"></i></a> <?php
				} else { ?>
            <a class="checkout_arrow <?php echo ($enable != 0) ? 'opacity_5' : '';?>" href="<?php echo ($enable == 0) ? $siteUrl.'/customer/users/customerlogin?page=checkout' : 'javascript:void(0)'; ?>"><i class="fa fa-long-arrow-right"></i></a> <?php
				} ?>

        </span>
    </div>	
</div>


	<?php
} else { ?>

<div class="cart-checkout">
    <a class="btn-checkout"> <?php echo __('Cart is Empty', true); ?></a>
</div> <?php

} ?>
