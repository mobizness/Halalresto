<div class="mobile_cart">
    <div class="">
        <span class="pull-left">
            <div class="mobile_cart_price" href="javascript:void(0);" >

				<?php echo $siteCurrency; ?><span class="cartTotal">0</span>

                <div class="cart_notification" style="display:none;">
					<?php echo __('1 Item added to cart successfully.', true); ?>
                </div>
                <div class="cart_failedNotification" style="display:none;">
					<?php echo __('Quantity Exceeded..!', true); ?>
                </div>
            </div>
        </span>
        <span class="pull-right viewCart_mobile">
            <a class="checkout_arrow view relative" href="javascript:void(0);" ><i class="fa fa-shopping-cart white"></i><span class="price_count" id="cartCount">0</span></a>			
        </span>
    </div>	
</div>

<section class="newmenu_tab">
    <div class="container">
        <div class="headeraddr"> <?php
                if ($siteSetting['Sitesetting']['address_mode'] != 'Google') {
                    echo $storeDetails['Store']['street_address'] . ', ' .
                        $storeArea[$storeDetails['Store']['store_zip']] . ', ' .
                        $storeCity[$storeDetails['Store']['store_city']] . ', ' .
                        $storeState[$storeDetails['Store']['store_state']];
                } else {
                    echo $storeDetails['Store']['address'];
                }
                ?>
        </div>
        <div class="headercuis">
                <?php
                $cuisines = '';
                foreach ($storeDetails['Cuisines'] as $k => $v) {
                    $cuisines .= $v['Cuisine']['cuisine_name'].', ';
                }
                echo trim($cuisines, ', ');
                ?>
        </div>
        <div class="col-sm-12 text-center">
            <div class="headermenudel"><?php echo __('Min.Order');?>: <?php

                    $minimumOrder = ($siteSetting['Sitesetting']['address_mode'] == 'Google') ?
                        $storeDetails['Store']['minimum_order'] : $storeDetails['DeliveryLocation']['DeliveryLocation']['minimum_order'];

echo number_format((float)$minimumOrder, 2, '.', '') . "&euro;";
?>
            </div>
            <div class="headermenudel"><?php echo __('Del Time');?> : <?php echo $storeDetails['Store']['estimate_time']; ?></div>
            <div class="headermenudel"><?php echo __('Delivery Fee');?>: <?php
                    $deliveryCharge = ($siteSetting['Sitesetting']['address_mode'] == 'Google') ?
                        $storeDetails['Store']['delivery_charge'] :
                        $storeDetails['DeliveryLocation']['DeliveryLocation']['delivery_charge'];

                    echo number_format((float)$deliveryCharge, 2, '.', '') . "&euro;"; ?>
            </div>
            <!-- <?php if($storeDetails['Store']['bookatable'] == 'Yes') { ?>
            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#book_a_table">
 <?php echo __('Book a table'); ?>
                            </button>
<?php } ?> -->
        </div>
        <ul class="nav nav-tabs nav-justified menu-cart menutab_ul">
            <li class="alltab_content"><a data-href="restaurant-menu" id="showmenu" class="active">
                    <i class="fa fa-book cart-icons" aria-hidden="true"></i>
                    Menu</a></li>
            <li class="alltab_content"><a data-href="restaurant-info" id="showinfo" onclick="return showMap()";>
                    <i class="fa fa-info-circle cart-icons" aria-hidden="true"></i>
                    Info</a></li>
			<?php if(!empty($voucherDetails)) { ?>
            <li class="alltab_content"><a data-href="restaurant-voucher"><img src="<?php echo $siteUrl.'/frontend/images/mrabat.png'; ?>">Rabatkoder</a></li>
			<?php } ?>
			<?php if($storeDetails['Store']['bookatable'] == 'Yes') { ?>
            <li class="alltab_content"><a data-href="restaurant-bookatable" id="showbook">
                    <i class="fa fa-calendar cart-icons" aria-hidden="true"></i>
                <?php echo __('Bookatable');?></a></li>
			<?php } ?>
       <!-- <li class="alltab_content" ><a data-href="restaurant-favourite" id="showreview"><img src="<?php echo $siteUrl.'/frontend/images/mlove.png'; ?>"><?php echo __('Add to favorite');?></a></li> -->

            <li class="alltab_content" ><a data-href="restaurant-reviews" id="showreview">
                    <i class="fa fa-heartbeat cart-icons" aria-hidden="true"></i>
                <?php echo __('Reviews');?></a></li>
        </ul>
    </div>
</section>

<section class="newmenuitems_cont">
    <div class="container">
        <div class="menuWrapper clearfix" id="restaurant-menu" style="background: #f5f5f5;">
            <div class="col-sm-8" style="background: #fff;">
                <div class="left col-sm-3 no-padding col-xs-12">
                    <div class="leftsideBar_scroller">
                        <div class="searchicn">
                            <img alt="<?php echo $storeDetails['Store']['store_name']; ?>" src="<?php echo $siteUrl.'/storelogos/'.$storeDetails['Store']['store_logo']; ?>" onerror="this.onerror=null;this.src='<?php echo $siteUrl."/frontend/images/no_store.jpg"; ?>'" width ="175px" height="110px">
                        </div>
                        <h3> <?php echo __('Categories', true); ?> <span></span></h3>
                        <ul class="maincategory" style="text-align: right">
                            <li class="list-group-item">
                                <a href="javascript:void(0);" onclick="categoriesProduct(''<?php echo ',0,'.$storeId;?>);">
		                        <?php echo __('All', true); ?>
                                </a>
                            </li>
						 <?php
						if (!empty($dealProduct)) { ?>
                            <li class="list-group-item">
                                <a href="javascript:void(0);" onclick="dealsProduct(<?php echo $storeId;?>);">
									<?php echo __('Deals', true); ?> 
                                </a>
                            </li> <?php 
						} ?>
					<?php
						$categoryCount = 0;
						foreach ($mainCategoryList as $key => $value) { 
							$categoryCount = $key+1; ?>
                            <li class="list-group-item">
                                <a href="javascript:void(0);" class="mainMenu" onclick="categoriesProduct(<?php echo $value['Category']['id'].',0,'.$storeId;?>);"><?php
									echo $value['Category']['category_name']; ?>
                                </a><?php
								echo $this->Form->hidden('check' ,array('value'=>$value['Category']['id'].'_'.$storeId,
									'class'=>'remove_'.$value['Category']['id']));

								echo $this->Form->hidden('' ,array('value'=>$value['Category']['id'].'_'.$storeId,
																	'id' => 'check'.$key)); ?>
                            </li>  <?php
						}
						echo $this->Form->hidden('', array('value'=> $categoryCount,
															'id' => 'countCategory')); ?>

                        </ul>
                    </div>
                </div>

                <div class="rightSideBar col-sm-9 col-xs-12" style="background: #fff;">

                    		<?php 
				// Store Offer
				if (!empty($storeOffers)) { ?>
                    <div class="menuoffer"><?php 
						echo $storeOffers['Storeoffer']['offer_percentage'].''.__('today on orders over'); 
						echo ' '. (number_format((float)$storeOffers['Storeoffer']['offer_price'], 2, '.', '') . "&euro;"); ?>
                    </div> <?php 
				} ?>

                    <div class="searchMenudet searchMenuForm">
                        <input type="search" onkeypress="productSearch(event);" id="searchKey" class="searchInput" placeholder="<?php echo __("I’m looking for"); ?>" >
                        <a href="javascript:;" class="searchMenuFormClick" onclick="searchProducts();"><i class="search-cart fa fa-search fa-2x" aria-hidden="true"></i></a>
                    </div>


                    <div id="filtterByCategory">  
                         <?php
		        	if (!empty($dealProducts)) { ?>
                        <div class="products-category mainCatProduct" id="Deal">
                            <header class="products-header">
                                <h4 class="category-name" style="text-align:center">
                                    <span> <?php echo __('Deals', true); ?></span>
                                </h4>
                            </header>
                             <?php
			                $main = $count = 0; ?>
                            <div class="products-category mainCatProduct">
                                <ul class="products productsCat<?php echo $count; ?>"> <?php
			                        foreach ($dealProducts as $key => $value) {
			                            $nextValue = $key+1; ?><?php

			                            $imageSrc = $siteUrl.'/stores/'.$value['Deal']['store_id'].'/products/home/'.$value['MainProduct']['product_image'];
			                            $imageSrcSub = $siteUrl.'/stores/'.$value['Deal']['store_id'].'/products/scrollimg/'.$value['SubProduct']['product_image']; ?>                              
                                    <li class="product searchresulttoshow searchresulttoshow<?php echo $count; ?>">
                                        <figure class="product__image col-xs-3" onclick="productDetails(<?php echo $value['MainProduct']['id']; ?>);">
                                            <span class="ribn-red onsale"><span><?php echo $value['Deal']['deal_name']; ?></span></span>
                                            <img data-cart="<?php echo $key; ?>" width="80px" src="<?php echo $imageSrc; ?>" onerror="this.onerror=null;this.src='<?php echo $siteUrl."/images/no-imge.jpg"; ?>'" alt="<?php echo $value['MainProduct']['product_name']; ?>" title="<?php echo $value['MainProduct']['product_name']; ?>" >
                                            <figcaption hidden>
                                                <div class="product-addon">
                                                    <a href="javascript:void(0);" class="yith-wcqv-button"><span></span><i class="fa fa-plus"></i></a>
                                                </div>
                                            </figcaption>
                                            <span class="free_section">
                                                <h5>Free</h5>
                                                <img width="50" class="image-lazy image-loaded" onerror="this.onerror=null;this.src='<?php echo $siteUrl."/images/no-imge.jpg"; ?>'" src="<?php echo $imageSrcSub; ?>" alt="<?php echo $value['SubProduct']['product_name']; ?>" title="<?php echo $value['SubProduct']['product_name']; ?>">
                                            </span>
                                        </figure>
                                        <div class="product__detail col-xs-9">
                                            <div class="product__detail_inner">
                                                <div class="top-section cell-table col-sm-12 col-md-9">
                                                    <h2 class="product__detail-title"><a href="javascript:void(0);"><?php echo $value['MainProduct']['product_name'].'+'.$value['SubProduct']['product_name']; ?></a>
                                                        <div class="pull-right"><?php
																	if ($value['MainProduct']['spicy_dish'] == 'Yes') { ?>
                                                            <span class="spicy_food"></span> <?php
																	}
																	if ($value['MainProduct']['popular_dish'] == 'Yes') { ?>
                                                            <span class="popular_food"></span> <?php
																	}
																	if ($value['MainProduct']['product_type'] == 'veg') { ?>
                                                            <span class="veg_food"></span> <?php
																	}
																	if ($value['MainProduct']['product_type'] == 'nonveg') { ?>
                                                            <span class="non_veg_food"></span> <?php
																	} ?>
                                                        </div>
                                                    </h2>
                                                    <div class="product__detail-category">
                                                        <a href="javascript:void(0);" rel="tag"><?php echo $value['MainProduct']['product_description']; ?></a>
                                                    </div>
                                                    <div class="clear"></div>
                                                </div>
                                                <div class="bottom-section cell-table col-sm-12 col-md-3">
                                                    <span class="price product__detail-price"> <?php
                                                    
			                                                    echo (number_format((float)$value['MainProduct']['ProductDetail'][0]['orginal_price'], 2, '.', '') . "&euro;"); ?>
                                                    </span>
                                                    <div class="product__detail-action">
                                                        <a href="javascript:void(0);" rel="nofollow" class="button add_to_cart_button"> <?php
			                                                        if ($value['MainProduct']['price_option'] == 'single' &&
			                                                                            $value['MainProduct']['product_addons'] == 'No') { ?>
                                                            <div class="add_btn"><i onclick="addToCart(<?php echo $value['MainProduct']['ProductDetail'][0]['id'].','.$key; ?>);" class="fa fa-plus green"></i></div> <?php
			                                                        } else { ?>
                                                            <div class="add_btn"><i onclick="productDetails(<?php echo $value['MainProduct']['id']; ?>);" class="fa fa-plus green"></i></div> <?php
			                                                        } ?>
                                                        </a>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </li> 
                                    <?php
			                        } ?>
                                </ul>
                            </div>
                        </div>  
                                <?php }?>
                        <div class="clr"></div> 
                    </div>


                    <div id="messageError" style="display:none">
                        <h1><center>Il n’y a pas de menu</center></h1>
                    </div>

                    <!-- <div id="cart-sidebar">
                            <a class="mobile_cart_close" href="javascript:void(0);"><i class="fa fa-chevron-left"></i></a>
                            <div class="cart-sidebar-overlay"></div>
                            <header>
                                    <div class="btn-cart-toggle">
                                            <span class="fa fa-angle-double-left"></span>
                                    </div>
                            </header>
                            <section>
                                    <div class="cart-wrapper" id="cartdetailswrapper">
                                            
                                    </div>
                            </section>
                    </div> -->
                    <div class="modal fade" id="addCartPop"> </div>
                </div>
            </div>
            <section class="col-sm-4 col-xs-12" id="cart-sidebar" style="background: rgb(245, 245, 245) none repeat scroll 0% 0%; position: relative; width: 33.33%; right: 0px; bottom: 0px;">
                <div class="cart-wrapper col-sm-12 no-padding" id="cartdetailswrapper">
                </div>
            </section>
        </div>

        <!---END-->
        <div class="menuWrapper clearfix" id="restaurant-info" style="display:none;">
            <div class="col-sm-4 no-padding-left">
                <div class="info_totleft">
                    <div class="noof_days">
                        <h5 class="dayshead info_dayshead col-sm-12"><?php echo __('Opening Hours');?></h5>
                        <h5 class="col-sm-6 col-md-6 col-lg-6 col-xs-6"><?php echo __('Monday');?></h5>
			    		<?php if($storeDetails['StoreTiming']['monday_status'] == 'Open') {?>
                        <h5 class="col-sm-6 col-md-6 col-lg-6 col-xs-6 text-right"><?php echo $storeDetails['StoreTiming']['monday_firstopen_time'].'-'.$storeDetails['StoreTiming']['monday_firstclose_time'];?> <br><?php echo $storeDetails['StoreTiming']['monday_secondopen_time'].'-'.$storeDetails['StoreTiming']['monday_secondclose_time'];?></h5>
			    		<?php } else { ?>				    	
                        <button type="button" class="btn btn-default lukket_btn margin-bottom-10 pull-right lukket_new col-xs-6"><?php echo __('Closed');?></button>
				    	<?php }?>

                        <h5 class="col-sm-6 col-md-6 col-lg-6 col-xs-6"><?php echo __('Tuesday');?></h5>
				    	<?php if($storeDetails['StoreTiming']['tuesday_status'] == 'Open') {?>
                        <h5 class="col-sm-6 col-md-6 col-lg-6 col-xs-6 text-right"><?php echo $storeDetails['StoreTiming']['tuesday_firstopen_time'].'-'.$storeDetails['StoreTiming']['tuesday_firstclose_time'];?> <br><?php echo $storeDetails['StoreTiming']['tuesday_secondopen_time'].'-'.$storeDetails['StoreTiming']['tuesday_secondclose_time'];?></h5>
			    		<?php } else { ?>				    	
                        <button type="button" class="btn btn-default lukket_btn margin-bottom-10 pull-right lukket_new col-xs-6"><?php echo __('Closed');?></button>
				    	<?php }?>

                        <h5 class="col-sm-6 col-md-6 col-lg-6 col-xs-6"><?php echo __('Wednesday');?></h5>
				    	<?php if($storeDetails['StoreTiming']['wednesday_status'] == 'Open') {?>
                        <h5 class="col-sm-6 col-md-6 col-lg-6 col-xs-6 text-right"><?php echo $storeDetails['StoreTiming']['wednesday_firstopen_time'].'-'.$storeDetails['StoreTiming']['wednesday_firstclose_time'];?><br> <?php echo $storeDetails['StoreTiming']['wednesday_secondopen_time'].'-'.$storeDetails['StoreTiming']['wednesday_secondclose_time'];?></h5>
			    		<?php } else { ?>				    	
                        <button type="button" class="btn btn-default lukket_btn margin-bottom-10 pull-right lukket_new col-xs-6"><?php echo __('Closed');?></button>
				    	<?php }?>

                        <h5 class="col-sm-6 col-lg-6 col-md-6 col-xs-6"><?php echo __('Thursday');?></h5>
				    	<?php if($storeDetails['StoreTiming']['thursday_status'] == 'Open') {?>
                        <h5 class="col-sm-6 col-md-6 col-lg-6 col-xs-6 text-right"><?php echo $storeDetails['StoreTiming']['thursday_firstopen_time'].'-'.$storeDetails['StoreTiming']['thursday_firstclose_time'];?><br> <?php echo $storeDetails['StoreTiming']['thursday_secondopen_time'].'-'.$storeDetails['StoreTiming']['thursday_secondclose_time'];?></h5>
			    		<?php } else { ?>				    	
                        <button type="button" class="btn btn-default lukket_btn margin-bottom-10 pull-right lukket_new col-xs-6"><?php echo __('Closed');?></button>
				    	<?php }?>

                        <h5 class="col-sm-6 col-md-6 col-lg-6 col-xs-6"><?php echo __('Friday');?></h5>
				    	<?php if($storeDetails['StoreTiming']['friday_status'] == 'Open') {?>
                        <h5 class="col-sm-6 col-md-6 col-lg-6 col-xs-6 text-right"><?php echo $storeDetails['StoreTiming']['friday_firstopen_time'].'-'.$storeDetails['StoreTiming']['friday_firstclose_time'];?><br> <?php echo $storeDetails['StoreTiming']['friday_secondopen_time'].'-'.$storeDetails['StoreTiming']['friday_secondclose_time'];?></h5>
			    		<?php } else { ?>				    	
                        <button type="button" class="btn btn-default lukket_btn margin-bottom-10 pull-right lukket_new col-xs-6"><?php echo __('Closed');?></button>
				    	<?php }?>

                        <h5 class="col-sm-6 col-md-6 col-lg-6 col-xs-6 margin-bottom-10"><?php echo __('Saturday');?></h5>
				    	<?php if($storeDetails['StoreTiming']['saturday_status'] == 'Open') {?>
                        <h5 class="col-sm-6 col-md-6 col-lg-6 col-xs-6 text-right"><?php echo $storeDetails['StoreTiming']['saturday_firstopen_time'].'-'.$storeDetails['StoreTiming']['saturday_firstclose_time'];?><br> <?php echo $storeDetails['StoreTiming']['saturday_secondopen_time'].'-'.$storeDetails['StoreTiming']['saturday_secondclose_time'];?></h5>
			    		<?php } else { ?>				    	
                        <button type="button" class="btn btn-default lukket_btn margin-bottom-10 pull-right lukket_new col-xs-6"><?php echo __('Closed');?></button>
				    	<?php }?>

                        <h5 class="col-sm-6 col-md-6 col-lg-6 col-xs-6 margin-bottom-10"><?php echo __('Sunday');?></h5>
				    	<?php if($storeDetails['StoreTiming']['sunday_status'] == 'Open') {?>
                        <h5 class="col-sm-6 col-md-6 col-lg-6 text-right col-xs-6"><?php echo $storeDetails['StoreTiming']['sunday_firstopen_time'].'-'.$storeDetails['StoreTiming']['sunday_firstclose_time'];?><br> <?php echo $storeDetails['StoreTiming']['sunday_secondopen_time'].'-'.$storeDetails['StoreTiming']['sunday_secondclose_time'];?></h5>
			    		<?php } else { ?>				    	
                        <button type="button" class="btn btn-default lukket_btn margin-bottom-10 pull-right lukket_new col-xs-6"><?php echo __('Closed');?></button>
				    	<?php }?> 
                    </div>

                    <div class="col-sm-12 text-center no-padding">
                        <div class="leftside_help bookleftside_help">
                            <h3><?php echo __('Need help');?></h3>
                            <h1><?php echo $siteSetting['Sitesetting']['contact_phone'];?></h1>
                            <p><?php echo __('All days');?> </p>
                        </div>

                        <div class="leftside_help bookleftside_help">
                            <?php 
                            
                            $meatInfo = $storeDetails['Store']['meat'];
                            
                            ?>
                            <h3>Halal Meat Certificate Information: <?php echo !empty($meatInfo) && $meatInfo == "Yes" ? "Nous utilisons de la viande certifiée" : "Nous n'utilisons pas de viande certifiée"; ?></h3>
                            <?php if($meatInfo == "Yes"){
                                if(!empty($storeDetails['Store']['meat_certificate'])){?>
                            <img class="img-responsive img_fields" src="https://halal-resto.fr/storemeatcertificates/<?php echo $storeDetails['Store']['meat_certificate']; ?>">
                                <?php }?>
                            <p>Certificateur(s): <?php echo $storeDetails['Store']['meat_issuer_name'];?></p>
                            <?php }else{?>
                            <p><?php echo $storeDetails['Store']['meat_description']; ?></p>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-8 no-padding-right info_right">
                <div class="info_totright">
                    <div class="col-sm-12 info_right_head no-padding">
                        <h3>Information</h3>
                    </div>
                    <div class="info_right_bg">						
                        <div id="mapShow"></div>						
                    </div>
                </div>
            </div>
        </div>

        <div class="menuWrapper clearfix" id="restaurant-voucher" style="display:none;">
            <table>
                <thead>
                    <tr>
                        <td>voucher Code</td>
                        <td>Voucher Price</td>
                        <td>Type of Use</td>
                        <td>Validity</td>
                    </tr>
                </thead>
                <tbody>
					<?php foreach($voucherDetails as $key => $voucher) {?>
                    <tr>
                        <td><?php echo $voucher['Voucher']['voucher_code']?></td>
                        <td><?php
								if ($voucher['Voucher']['offer_mode'] != 'free_delivery') {
									echo ($voucher['Voucher']['offer_mode'] == 'price') ? 
										number_format((float)$voucher['Voucher']['offer_value'], 2, '.', '') . "&euro;" : $voucher['Voucher']['offer_value']. ' %';
								} else {
									echo 'Free delivery';

								} ?></td>
                        <td><?php echo $voucher['Voucher']['type_offer']?></td>
                        <td>From : <?php echo $voucher['Voucher']['from_date']?> - To : <?php echo $voucher['Voucher']['to_date']?></td>
                    </tr>
					<?php } ?>
                </tbody>
            </table>
        </div>

        <!-- <div class="menuWrapper clearfix" id="restaurant-favourite" style="display:none;">

                <div id="addfav" class="col-sm-12 text-center" <?php if(!empty($favList)) { ?> style="display: none;" <?php } ?>>
				<?php if(!empty($loggedCheck) && ($loggedCheck['role_id'] == 4)){ ?>
                                <a class="add_fav" href="javascript:void(0);" onclick="return myFavourite(<?php echo $storeDetails['Store']['id'];?>,'add');"><img src="<?php echo $siteUrl.'/frontend/images/mlove.png'; ?>"><?php echo __('Add to favorite');?></a>
				<?php } else {?>
                                <a class="add_fav" href="<?php echo $siteUrl.'/customer/users/customerlogin';?>"><img src="<?php echo $siteUrl.'/frontend/images/mlove.png'; ?>"><?php echo __('Add to favorite');?></a>
				<?php }?>
                </div>
        
                <div id="remfav" class="col-sm-12 text-center" <?php if(empty($favList)) { ?> style="display: none;" <?php } ?>>
				<?php if(!empty($loggedCheck) && ($loggedCheck['role_id'] == 4)){ ?>
                                <a href="javascript:void(0);" onclick="return myFavourite(<?php echo $storeDetails['Store']['id'];?>,'remove');">Remove from favourite</a>
				<?php } else {?>
                                <a href="<?php echo $siteUrl.'/customer/users/customerlogin';?>">Remove from favourite</a>
				<?php }?>
                </div>	
                
        </div> -->

        <div class="menuWrapper clearfix" id="restaurant-reviews" style="display:none;">		
            <div class="col-sm-12">
                <div id="storereviews">				
					<?php 
					if (array_filter($storeDetails['Review'])) {
						foreach ($storeDetails['Review'] as $key => $value) { 
							if($value['status'] == 1) { ?>	
                    <div class="margin-top-btm margin_tb_new">
                        <div class="col-sm-12 col-xs-12">
                            <div class="col-sm-6 col-xs-8 no-padding">
                                <p class="r_custname r_custname_new"><?php echo $value['customer_name'];?></p>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12">
                            <p class="margin-top-20 store_desc"><?php echo $value['message'];?></p>
                        </div>

                        <div class="text-justify margin-top-14 col-sm-12 no-padding col-xs-12 no-padding-xs">		
                            <div class="col-sm-2 col-xs-12 no-padding-xs">		
                                <span class="rating_star_big pull-left">
                                    <span class="rating_star_big_gold" style="width:<?php echo (!empty($value['rating'])) ?  $value['rating']*20 : 0; ?>%"></span>	
                                </span>
                            </div>
                            <div class="col-sm-10 col-xs-12 no-padding-xs">
                                <div class="col-sm-12 col-lg-6 col-md-6 no-padding-xs pull-right order_date col-xs-12">Posted on : <?php echo $value['created'];?></div>
                            </div>
                        </div>						
                    </div><?php 
							}
					 	}
					} else {
						echo __('No Reviews Found');	 
					} ?>
                </div>
            </div>



        </div>

        <div class="menuWrapper clearfix" id="restaurant-bookatable" style="display:none;">
			<?php
				echo $this->Form->create('BookaTable', array('class' => 'bookRequest form-horizontal')); ?>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-7">						
                    <div id="bookError" class="error alert alert-danger" style="display: none"></div>
                    <div class="bookaTableSuccess alert alert-success" style="display: none"> <?php echo __('Your booking request sent successfully');?></div>
                </div>
            </div>


            <div class="form-group">
                <label for="guestCount" class="col-sm-4 control-label name">
							<?php echo __('Guest Count'); ?> </label>
                <div class="col-sm-7"> <?php
							echo $this->Form->input('store_id', 
										array('type' => 'hidden',
											  'label' => false,
											  'value' => $storeId));
							echo $this->Form->input('guest_count',
										array('type'  => 'select',
											'class'   => 'form-control bookRequest',
											'options' => array($guests),
											'empty'   => __('Select Guest'),
											'label'   => false)); ?>
                </div>
            </div>

            <div class="form-group">
                <label for="bookingDate" class="col-sm-4 control-label name">
							<?php echo __('Date'); ?> </label>
                <div class="col-sm-7 no-padding">	
                    <div class="col-sm-6"> 
                        <label for="BookaTableBookingDate" class="input-group"> <?php
								echo $this->Form->input('booking_date',
												array('class' => 'form-control bookRequest',
												      'label'   => false,
												      'readonly'=>true)); ?>

                            <div class="input-group-addon"><i class="fa fa-calendar"></i> </div>
                        </label>	
                    </div>
                    <div class="col-sm-6"> <?php
								echo $this->Form->input('booking_time',
											array('type'  => 'select',
												'class'   => 'form-control bookRequest',
												'options' => array($times),
												'empty'   => __('Select Time'),
												'label'   => false)); ?>
                    </div>
                </div>
            </div>

            <!-- <div class="form-group">
                    <label for="bookingTime" class="col-sm-4 control-label name">
							<?php echo __('Time'); ?> </label>
                    
            </div> -->

            <div class="form-group">
                <label for="customerName" class="col-sm-4 control-label name">
							<?php echo __('Name'); ?> </label>
                <div class="col-sm-7"> <?php
							echo $this->Form->input('customer_name',
											array('class' => 'form-control bookRequest',
												  'label'   => false)); ?>
                </div>
            </div>

            <div class="form-group">
                <label for="bookingEmail" class="col-sm-4 control-label name">
							<?php echo __('Email'); ?> </label>
                <div class="col-sm-7"> <?php
							echo $this->Form->input('booking_email',
											array('class' => 'form-control bookRequest',
												  'label'   => false)); ?>
                </div>
            </div>

            <div class="form-group">
                <label for="bookingPhone" class="col-sm-4 control-label name">
							<?php echo __('Phone Number'); ?></label>
                <div class="col-sm-7"> <?php
							echo $this->Form->input('booking_phone',
											array('class' => 'form-control bookRequest',
												  'label'   => false)); ?>
                </div>
            </div>


            <div class="form-group">
                <label for="bookingInstruction" class="col-sm-4 control-label name">
							<?php echo __('Your Instructions'); ?></label>
                <div class="col-sm-7"> <?php
							echo $this->Form->input('booking_instruction',
											array('class' => 'form-control bookRequest',
											      'label' => false,
											      'type'  => 'textarea')); ?>
                </div>
            </div>


            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button> -->
                <a href="javascript:history.go(0)" class="btn btn-default">Fermer</a> <?php

			        	echo $this->Form->button('<i class="fa fa-check"></i>'.__('Submit'),
				                              			array('class'=>'btn btn-primary',
				                              				'onclick' => 'return validateBook();'));  ?>
            </div>
	      		<?php echo $this->Form->end(); ?>
        </div>

    </div>
</section>




<!-- Modal -->
<div class="modal fade" id="book_a_table" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Book a table'); ?> </h4>
            </div>
            <div class="modal-body"> <?php
				echo $this->Form->create('BookaTable', array('class' => 'bookRequest form-horizontal')); ?>
                <div class="form-group">
                    <div class="col-sm-offset-4 col-sm-7">						
                        <div id="bookError" class="error alert alert-danger" style="display: none"></div>
                        <div class="bookaTableSuccess alert alert-success" style="display: none"> <?php echo __('Your booking request sent successfully');?></div>
                    </div>
                </div>


                <div class="form-group">
                    <label for="guestCount" class="col-sm-4 control-label name">
							<?php echo __('Guest Count'); ?> </label>
                    <div class="col-sm-7"> <?php
							echo $this->Form->input('store_id', 
										array('type' => 'hidden',
											  'label' => false,
											  'value' => $storeId));
							echo $this->Form->input('guest_count',
										array('type'  => 'select',
											'class'   => 'form-control bookRequest',
											'options' => array($guests),
											'empty'   => __('Select Guest'),
											'label'   => false)); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="bookingDate" class="col-sm-4 control-label name">
							<?php echo __('Date'); ?> </label>
                    <div class="col-sm-7 no-padding">	
                        <div class="col-sm-6"> 
                            <label for="BookaTableBookingDate" class="input-group"> <?php
								echo $this->Form->input('booking_date',
												array('class' => 'form-control bookRequest',
												      'label'   => false,
												      'readonly'=>true)); ?>

                                <div class="input-group-addon"><i class="fa fa-calendar"></i> </div>
                            </label>	
                        </div>
                        <div class="col-sm-6"> <?php
								echo $this->Form->input('booking_time',
											array('type'  => 'select',
												'class'   => 'form-control bookRequest',
												'options' => array($times),
												'empty'   => __('Select Time'),
												'label'   => false)); ?>
                        </div>
                    </div>
                </div>

                <!-- <div class="form-group">
                        <label for="bookingTime" class="col-sm-4 control-label name">
							<?php echo __('Time'); ?> </label>
                        
                </div> -->

                <div class="form-group">
                    <label for="customerName" class="col-sm-4 control-label name">
							<?php echo __('Name'); ?> </label>
                    <div class="col-sm-7"> <?php
							echo $this->Form->input('customer_name',
											array('class' => 'form-control bookRequest',
												  'label'   => false)); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="bookingEmail" class="col-sm-4 control-label name">
							<?php echo __('Email'); ?> </label>
                    <div class="col-sm-7"> <?php
							echo $this->Form->input('booking_email',
											array('class' => 'form-control bookRequest',
												  'label'   => false)); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="bookingPhone" class="col-sm-4 control-label name">
							<?php echo __('Phone Number'); ?></label>
                    <div class="col-sm-7"> <?php
							echo $this->Form->input('booking_phone',
											array('class' => 'form-control bookRequest',
												  'label'   => false)); ?>
                    </div>
                </div>


                <div class="form-group">
                    <label for="bookingInstruction" class="col-sm-4 control-label name">
							<?php echo __('Your Instructions'); ?></label>
                    <div class="col-sm-7"> <?php
							echo $this->Form->input('booking_instruction',
											array('class' => 'form-control bookRequest',
											      'label' => false,
											      'type'  => 'textarea')); ?>
                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button> <?php

			        	echo $this->Form->button('<i class="fa fa-check"></i>'.__('Submit'),
				                              			array('class'=>'btn btn-primary',
				                              				'onclick' => 'return validateBook();'));  ?>
                </div>
	      		<?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>

<?php
		echo $this->Form->input('StoreId', 
					array('type' => 'hidden',
						  'label' => false,
						  'id' => 'StoreId',
						  'value' => $storeId));
		echo $this->Form->input('StoreName', 
					array('type' => 'hidden',
						  'label' => false,
						  'id' => 'StoreName',
						  'value' => $storeDetails['Store']['store_name']));
	  	echo $this->Form->input('StoreAddress', 
					array('type' => 'hidden',
						  'label' => false,
						  'id' => 'StoreAddress',
						  'value' => $storeDetails['Store']['address']));?>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleApiKey; ?>&v=3&sensor=false&amp;libraries=places,geometry"></script>
