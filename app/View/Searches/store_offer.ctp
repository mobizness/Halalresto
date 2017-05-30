<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header clearfix">
			<button type="button" class="close" data-dismiss="modal"> <span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title center" id="myModalLabel"> Offer Details </h4>
		</div>
		
		<div class="modal-body menuInner clearfix">
			
			<div class="offer_codes">
				
				<?php if (!empty($storeOffers)) { ?>
						<div class="offer_codes_img">
							<img alt="" src="<?php echo $siteUrl.'/frontend/images/store_offer.png'; ?>" title="">
							<span class="ribn-red">
								<span><?php echo  $storeOffers['Storeoffer']['offer_percentage'].'% OFF '; ?></span>
							</span>							
						</div>
						<h3>
							
							<?php  echo 'Offer Price  : '. $storeOffers['Storeoffer']['offer_price'] ; ?>
						</h3>
						<span class="margin-t-10 offer_price">
							<?php 
								echo 'Validity : '. $storeOffers['Storeoffer']['from_date'].' '; 
								echo ' To : '. $storeOffers['Storeoffer']['to_date'] ; 
							?>
						</span>

					<?php } 

					else {

					?>
						<div class="offer_codes_img">
							<img alt="" src="<?php echo $siteUrl.'/frontend/images/store_offer.png'; ?>" title="">
							<span class="ribn-red">
								<span>0% OFF</span>
							</span>							
						</div>
						<h3>
							<?php 
								echo 'Offer is not available today';
							?>
						</h3>
							
				<?php }  ?>
			</div>
			
		</div>
	</div>
</div>