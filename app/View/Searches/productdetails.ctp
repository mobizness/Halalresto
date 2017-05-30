<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header menuCartHeader clearfix">
			<button type="button" class="close" data-dismiss="modal">
				<span aria-hidden="true">&times;</span>
			</button>
			<h4> <?php
				if(!empty($productDetails['Deal']['id']) && $productDetails['Deal']['status'] != 0) {
					echo $productDetails['Product']['product_name'].' + '.$productDetails['Deal']['SubProduct']['product_name'];
				} else {
					echo $productDetails['Product']['product_name'];
				} ?>	
			</h4>
		</div>
		
		<div class="modal-body menuInner clearfix">
			<div class="col-md-6"> 
				<div class="row">
					 <div class="cartImg"> <?php
					$imageName = (isset($productDetails['Product']['product_image'])) ? $productDetails['Product']['product_image'] : '';
	                $imageSrc = $siteUrl.'/stores/'.$productDetails['Product']['store_id'].'/products/home/'.$imageName; ?>

	                <img data-cart="<?php echo $productDetails['Product']['id']; ?>" src="<?php echo $imageSrc; ?>" onerror="this.onerror=null;this.src='<?php echo $siteUrl."/images/noimage.png"; ?>'" alt="<?php echo $productDetails['Product']['product_name']; ?>" title="<?php echo $productDetails['Product']['product_name']; ?>">

					 </div>
				 </div>
			</div> <?php 
				echo $this->Form->input('', [
		                'type'  => 'hidden',
		                'id' => 'ProductId',
		                'value' => $productDetails['Product']['id'],
		                'label' => false
		            ]); ?>
			<div class="col-md-6 detailPopCont"> <?php
				if ($productDetails['Product']['price_option'] != 'single') {  ?>
					  <?php

				} else {
					echo $this->Form->input('',
										array('class' => 'form-control',
											  'type'  => 'hidden',
											  'id' => 'productAddons',
											  'value' => $productDetails['ProductDetail'][0]['id'],
											  'label' => false));
				} ?>
				<div id="productVariantDetails">
					<div class="detailPopCont_top">
						<div class="price_height">
							<h5 class="addcart_popup_head"><?php echo __('Menu Price'); ?> :</h5><h2><?php
								echo html_entity_decode($this->Number->currency($productDetails['ProductDetail'][0]['orginal_price'], $siteCurrency)); ?></h2>
						</div>
						<div class="row">
							<div class="col-md-8">
								<input class="form-control text-center" id="quantity" type="text" value="">
							</div>
						</div>
						<div class="col-xs-12 text-center">
                            <button type="submit" onclick="variantCart(<?php echo $productDetails['Product']['id']; ?>);" class="btn btn-primary margin-t-25"><?php echo __('Add To Cart'); ?> </button>
                        </div>
					</div>
				</div>
			</div>
            <input id="productAddonsSingle" class="addonsId" type="hidden" value="<?php echo $productDetails['ProductDetail'][0]['id']; ?>"> <?php

            if ($productDetails['Product']['price_option'] == 'multiple') { ?>
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-sm-12 margin-top-15">
                            <div class="row"><?php
                                foreach ($productDetails['ProductDetail'] as $key => $value) { ?>
                                    <div class="radio radio-inline">
                                        <input id="productAddons_<?php echo $value['id']; ?>" onclick="getMenuAddons(<?php echo $value['id']; ?>);" <?php echo ($key == 0) ? 'checked' : ''; ?> class="addonsId" type="radio" value="<?php echo $value['id']; ?>" name="addon_ss">
                                        <label for="productAddons_<?php echo $value['id']; ?>"><?php echo $value['sub_name']; ?> </label>
                                    </div><?php
                                } ?>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="row">
                                <div id="loadMenuAddons"></div>
                            </div>
                        </div>
                    </div>
                </div><?php
            }

            if ($productDetails['Product']['price_option'] == 'single' && $productDetails['Product']['product_addons'] == 'Yes') { ?>
	            <div class="col-xs-12">
	                <div class="row">
	                    <div class="col-sm-12 margin-top-15">
	                        <div class="row"><?php
	                            foreach ($productDetails['ProductDetail'] as $key => $value) { ?>
	                                <div class="">
	                                <input id="productAddons_<?php echo $value['id']; ?>" onclick="getMenuAddons(<?php echo $value['id']; ?>);" <?php echo ($key == 0) ? 'checked' : ''; ?> class="addonsId" type="hidden" value="<?php echo $value['id']; ?>" name="addon_ss">
	                                <label for="productAddons_<?php echo $value['id']; ?>"><?php //echo $value['sub_name']; ?> </label>
	                                </div><?php
	                            } ?>
	                        </div>
	                    </div>
	                    <div class="col-sm-12">
	                        <div class="row">
	                            <div id="loadMenuAddons"></div>
	                        </div>
	                    </div>
	                </div>
	            </div><?php
            } ?>

            <div class="col-xs-12 text-center">
                <button type="submit" onclick="variantCart(<?php echo $productDetails['Product']['id']; ?>);" class="btn btn-primary margin-t-25"><?php echo __('Add To Cart'); ?> </button>
            </div>
		</div>
	</div>
</div>

<script>
	  var sync1 = $("#sync1");
	  var sync2 = $("#sync2");

	  sync1.owlCarousel({
	    singleItem : true,
	    slideSpeed : 1000,
	    navigation: true,
	    pagination:false,
	    navigationText: ["",""],
	    afterAction : syncPosition,
	    responsiveRefreshRate : 200,
	  });

	  sync2.owlCarousel({
	    items : 3,
	    itemsDesktop      : [1199,3],
	    itemsDesktopSmall     : [979,2],
	    itemsTablet       : [768,1],
	    itemsMobile       : [479,1],
	    pagination:false,
	    responsiveRefreshRate : 100,
	    afterInit : function(el){
	      el.find(".owl-item").eq(0).addClass("synced");
	    }
	  });

	  function syncPosition(el){
	    var current = this.currentItem;
	    $("#sync2")
	      .find(".owl-item")
	      .removeClass("synced")
	      .eq(current)
	      .addClass("synced")
	    if($("#sync2").data("owlCarousel") !== undefined){
	      center(current)
	    }

	  }

	  $("#sync2").on("click", ".owl-item", function(e){
	    e.preventDefault();
	    var number = $(this).data("owlItem");
	    sync1.trigger("owl.goTo",number);
	  });

	  function center(number){
	    var sync2visible = sync2.data("owlCarousel").owl.visibleItems;

	    var num = number;
	    var found = false;
	    for(var i in sync2visible){
	      if(num === sync2visible[i]){
	        var found = true;
	      }
	    }

	    if(found===false){
	      if(num>sync2visible[sync2visible.length-1]){
	        sync2.trigger("owl.goTo", num - sync2visible.length+2)
	      }else{
	        if(num - 1 === -1){
	          num = 0;
	        }
	        sync2.trigger("owl.goTo", num);
	      }
	    } else if(num === sync2visible[sync2visible.length-1]){
	      sync2.trigger("owl.goTo", sync2visible[1])
	    } else if(num === sync2visible[0]){
	      sync2.trigger("owl.goTo", num-1)
	    }
	  }

</script>