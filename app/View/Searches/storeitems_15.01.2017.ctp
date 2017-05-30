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


<div class="menuWrapper">
	<div class="leftsideBar equalHeight">
		<div class="leftsideBar_scroller">
			<h1> <?php echo __('Categories', true); ?> <span></span></h1>
			<ul class="maincategory">
                <li>
                    <a href="javascript:void(0);" onclick="categoriesProduct(''<?php echo ',0,'.$storeId;?>);">
                        <?php echo __('All', true); ?>
                    </a>
                </li>
				 <?php
				if (!empty($dealProduct)) { ?>
					<li>
						<a href="javascript:void(0);" onclick="dealsProduct(<?php echo $storeId;?>);">
							<?php echo __('Deals', true); ?> 
						</a>
					</li> <?php 
				} ?>
			<?php
				$categoryCount = 0;
				foreach ($mainCategoryList as $key => $value) { 
					$categoryCount = $key+1; ?>
					<li>
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
	
	<div class="rightSideBar equalHeight">
		<?php 
		// Store Offer
		if (!empty($storeOffers)) { ?>
			<div class="menuoffer"><?php 
				echo $storeOffers['Storeoffer']['offer_percentage'].''.__('today on orders over'); 
				echo ' '. html_entity_decode($this->Number->currency($storeOffers['Storeoffer']['offer_price'], $siteCurrency)); ?>
			</div> <?php 
		} ?>

		<div class="searchMenudet searchMenuForm">
			<input type="search" onkeypress="productSearch(event);" id="searchKey" class="searchInput" placeholder="<?php echo __("I’m looking for"); ?>" >
			<a href="javascript:;" class="searchMenuFormClick" onclick="searchProducts();"></a>
		</div>


        <div id="filtterByCategory"> <?php
        	if (!empty($dealProducts)) { ?>
	            <div class="products-category mainCatProduct" id="Deal" >
	                <header class="products-header">
	                    <h4 class="category-name">
	                        <span> <?php echo __('Deals', true); ?></span>
	                    </h4>
	                </header>
	                <h5> </h5> <?php
	                $main = $count = 0; ?>
	                <div class="products-category mainCatProduct">
	                    <ul class="products productsCat<?php echo $count; ?>"> <?php
	                        foreach ($dealProducts as $key => $value) {
	                            $nextValue = $key+1; ?><?php

	                            $imageSrc = $siteUrl.'/stores/'.$value['Deal']['store_id'].'/products/home/'.$value['MainProduct']['product_image'];
	                            $imageSrcSub = $siteUrl.'/stores/'.$value['Deal']['store_id'].'/products/scrollimg/'.$value['SubProduct']['product_image']; ?>
	                            <li class="product searchresulttoshow searchresulttoshow<?php echo $count; ?>">
	                                <div class="product__inner">
	                                    <figure class="product__image cell-table" onclick="productDetails(<?php echo $value['MainProduct']['id']; ?>);">
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
	                                    <div class="product__detail cell-table col-xs-9">
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
	                                                    echo html_entity_decode($this->Number->currency($value['MainProduct']['ProductDetail'][0]['orginal_price'], $siteCurrency)); ?>
	                                                </span>
	                                                <div class="product__detail-action">
	                                                    <a href="javascript:void(0);" rel="nofollow" class="button add_to_cart_button"> <?php
	                                                        if ($value['MainProduct']['price_option'] == 'single' &&
	                                                                            $value['MainProduct']['product_addons'] == 'No') { ?>
	                                                            <div class="add_btn"><i onclick="addToCart(<?php echo $value['MainProduct']['ProductDetail'][0]['id'].','.$key; ?>);" class="fa fa-plus plushide"></i></div> <?php
	                                                        } else { ?>
	                                                            <div class="add_btn"><i onclick="productDetails(<?php echo $value['MainProduct']['id']; ?>);" class="fa fa-plus plushide"></i></div> <?php
	                                                        } ?>
	                                                    </a>
	                                                </div>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                            </li><?php
	                        } ?>
	                    </ul>
	                </div>
	            </div> <?php
            } ?>
        </div>
		<div id="messageError" style="display:none">
			<h1><center>Il n’y a pas de menu</center></h1>
		</div>

		<div id="cart-sidebar">
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
		</div>
		<div class="modal fade" id="addCartPop"> </div>
	</div>
</div>





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