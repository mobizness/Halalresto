<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleApiKey; ?>&v=3&sensor=false&amp;libraries=places,geometry"></script>
<div class="container indexBnner bannerrelative">
	<div class="col-sm-12 main-content clearfix">
		<div class="grocery-box col-md-12 col-lg-10 col-sm-12 col-lg-offset-1">
			<h1><?php echo __('On-Demand, 24/7', true); ?></h1>
			<p> <?php echo __('Get the best of your city delivered in minutes', true); ?></p> <?php 
				if ($siteSetting['Sitesetting']['address_mode'] != 'Google') {
					echo $this->Form->create('Search', array('class' => 'shop-form')) ; ?>
					<div class="col-lg-8 col-md-10 col-lg-offset-2 col-md-offset-1 col-sm-12 middleForm">	
						<div class="row">
							<div class="col-lg-4 col-md-4 col-sm-4 no-padding-right">	
								<div class="shop-select"> <?php
									if (!empty($cityList)) {
										echo $this->Form->input('city',
													array('type'=>'select',
															'class'=>'selectpicker',
															'options'=> array($cityList),
															'onchange' => 'locationList();',
															'id' => 'city',
															'empty' => __('Select City'),
															'label'=> false));
									} else {
										echo $this->Form->input('city',
													array('type'=>'select',
															'class'=>'selectpicker',
															'id' => 'city',
															'empty' =>  __('Select City'),
															'label'=> false));
									} ?>
									<div id="searchError" class="form-error" style="display: none;"></div>
								</div>			
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4 no-padding-right">
								<div class="shop-select"> <?php
									echo $this->Form->input('area',
												array('type'=>'select',
														'class'=>'selectpicker',
														'id' => 'location',
														'empty' => __('Select Area / Zipcode'),
														'label'=> false)); ?>
								</div>
								<div id="locationError" class="form-error" style="display: none;"></div>			
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4">	
								<div class="shop-button"> <?php
								$lang = ($siteLang == 'deu') ? 'Trouver des restaurants' : 'Submit';
									echo $this->Form->button(__($lang),
													  array('class'=>'btn indexbutton',
															'onclick' => 'return locationStore();')); ?>
								</div>
							</div>	
						</div>
						<div class="alredyaccount">Already Account ? <a href="<?php echo $siteUrl.'/customerlogin'; ?>">Sign In</a></div>
					</div>
					<?php
				} else { 
					echo $this->Form->create('Search', array('class' => 'shop-form',
															 'id' => 'SearchSearchByAddressForm'
													//'controller' => 'searches',
													//'action' => 'searchByAddress'
													 )); ?>
					<div class="col-lg-8 col-md-10 col-lg-offset-2 col-md-offset-1 col-sm-12 middleForm">	
						<!-- <label class="form-label">Enter Location</label> -->
						<div class="row">
							<div class="col-lg-7 col-md-7 col-sm-7 no-padding-right">
								<i class="barsearch"></i>
								<div class="shop-select">  <?php
									echo $this->Form->input('address',
											array('type'=>'text',
													'class'=>'form-control inputbarsearch',
													'id' => 'searchAddress',
													'placeholder' => __('Enter your location', true),
													'empty' => __('Enter your address'),
													'label'=> false,
													'onfocus' =>'initialize(this.id)')); ?>
									<div id="searchError" class="form-error" style="display: none;"></div>
								</div> 
							</div>
							<div class="col-lg-5 col-md-5 col-sm-5">	
								<div class="shop-button"> <?php
								$lang = ($siteLang == 'deu') ? 'Trouver des restaurants' : 'Submit';

									echo $this->Form->button(__($lang),
													  array('class'=>'btn indexbutton',
															'onclick' => 'return locationStore();')); ?>
								
								</div>
							</div>	
						</div>
						<div class="alredyaccount">Already Account ? <a href="<?php echo $siteUrl.'/customerlogin'; ?>">Sign In</a></div>
					</div> <?php
				}

			echo $this->Form->end(); ?>
			<!-- <div class="pincode"> <?php echo __('For Example : City &rarr; Chennai , Area &rarr;MMDA.', true); ?></div> -->
		</div>		
	</div>
	<div class="expandsignup hidden-sm hidden-md hidden-lg">
		<?php echo __('Add your Restaurant', true); ?> <a href="<?php echo $siteUrl.'/restaurantSignup'; ?>" class="signuptext"><?php echo __('Sign Up');?></a>
	</div>
</div>

<div class="hiw_section hidden-xs" id="hiw_section">
		<div class="container">
			<h2>  
				<?php echo __('How it works?', true); ?>
	        </h2>

			<div class="row panels">
			   <div class="col-sm-6 col-md-3 panel-item">
			      <div class="panel panel-image">
			         <div class="panel-icon">
			           <i class="search-icn"></i>
			         </div>
		            <h3 class="panel-title">  <?php echo __('Search', true); ?></h3>
		            <div>
		               <p><?php echo __('Find Your Local restaurants', true); ?></p>
		            </div>
			      </div>
			   </div>
			   <div class="col-sm-6 col-md-3 panel-item">
			      <div class="panel panel-image">
			        <div class="panel-icon">
			            <i class="search-rest"></i>
			        </div>
		            <h3 class="panel-title"> <?php echo __('Search Restaurant', true); ?> </h3>
		            <p>  <?php echo __('Choose Your favourite Menu', true); ?></p>
			      </div>
			   </div>
			   <div class="col-sm-6 col-md-3 panel-item">
			      <div class="panel panel-image">
			        <div class="panel-icon">
			           <i class="place-order"></i>
			        </div>
		            <h3 class="panel-title"> <?php echo __('Place order', true); ?></h3>
		            <p>  <?php echo __('And Pay by Card Or Cash', true); ?></p>
			      </div>
			   </div>
			   <div class="col-sm-6 col-md-3 panel-item">
				    <div class="panel panel-image">
				        <div class="panel-icon">
				           <i class="deliver-icn"></i>
				        </div>
			            <h3 class="panel-title">  <?php echo __('Delivery', true); ?></h3>
			            <p>  <?php echo __('And enjoy with your food', true); ?></p>
				    </div>
			   </div>
			</div>
		</div>
		
</div>



<!--<div class="container">
	<div class="row">
		<?php foreach ($storeOffers as $key => $value) {?>
			<div class="col-sm-3">
				<div class="storeoffer">
					<a href="<?php echo $siteUrl.'/shop/'.$value['Store']['seo_url'].'/'.base64_encode($value['Store']['id']); ?>">
					    <h4><?php echo $storeOffers[$key]['Storeoffer']['offer_percentage']?> % offer on</h4>
					    <h3><?php echo $storeOffers[$key]['Store']['store_name']?></h3>
					</a> 
				</div>
			</div>
		<?php } ?>
	</div>
</div>-->
<?php if (!empty($storeOffers)) {?>
<div class="hiw_section hidden-xs">
	<h2>  
		<?php echo __('Les Offres de la semaine', true); ?>
    </h2>
</div>
<div class="newcontent">
	<div class="container">
		<div class="row">
		<?php foreach ($storeOffers as $key => $value) {?>
			<div class="col-sm-4">
				<div class="newborder">

					<?php $imageSrc = $siteUrl.'/stores/'.$value['Store']['id'].'/products/home/'.$storeOffers[$key]['Store']['product_image'];?>
					<a href="<?php echo $siteUrl.'/shop/'.$value['Store']['seo_url'].'/'.base64_encode($value['Store']['id']); ?>">
						<img class="newhalalimage" src="<?php echo $imageSrc; ?>" onerror="this.onerror=null;this.src='<?php echo $siteUrl."/frontend/images/no_store.jpg"; ?>'">
					</a>
					<div class="content">
					
						
						<img class="col-sm-3 col-xs-5" src="<?php echo $siteUrl.'/storelogos/'.$value['Store']['store_logo']; ?>"  width="2px" height="60px" style="margin-top:15px; margin-right: 20px" onerror="this.onerror=null;this.src='<?php echo $siteUrl."/images/no-imge.jpg"; ?>'">
						<p class="margin-left-31"><h3><?php echo $storeOffers[$key]['Store']['store_name']?></h3></p>
						
						<span class="rating_star">
							<span class="rating_star_gold" style="width:<?php echo $storeOffers[$key]['Store']['rating']; ?>%"></span>
						</span>
						<p class="margin-left-31"><?php echo $storeOffers[$key]['Store']['address']?></p>
						<?php if($storeOffers[$key]['Storeoffer']['offer_description'] != '') { ?>
						<p class="margin-left-31"><?php echo $storeOffers[$key]['Storeoffer']['offer_description']?></p>
						<?php } else {?>
						<p class="margin-left-31">&nbsp;</p>
						<?php }?>
					</div> 
				</div>
			</div>
		<?php } ?>
		</div>
	</div>
</div>
<?php }?>


<div class="foodbanner hidden-xs">
  <div class="container">
    <div class="row">
      <div class="col-md-5">
        <h5><?php echo __('Online Ordering for your customers', true); ?></h5>
      </div>
      <div class="col-md-7 hide">
      	<img alt="ipad" title="ipad" src="<?php echo $siteUrl.'/frontend/images/ipad.png'; ?>">
      	<img class="mobileimg" alt="iphone_mobile" title="iphone_mobile" src="<?php echo $siteUrl.'/frontend/images/iphone_mobile.png'; ?>">
      </div>
    </div>
  </div>
</div>
<div class="expandsignup hidden-xs">
	<div class="container">
		<div class="row">
			<div class="col-lg-9 col-md-8">
				<h5><?php echo __('Add your Restaurant', true); ?></h5>
				<p><?php echo __('Join the thousands of other restaurants who benefit from having their menus on');?> <?php
					echo $siteSetting['Sitesetting']['site_name']; ?> </p>
			</div>
			<div class="col-lg-3 col-md-4">
				<a href="<?php echo $siteUrl.'/restaurantSignup'; ?>" class="signuptext"><?php echo __('Sign Up');?></a>
			</div>
		</div>
	</div>
</div>
<section class="contact_us_section relative">
<h5 class="col-md-4 col-md-offset-1">HALAL RESTO a développé une solution spéciale pour les entreprises.</h5>
	<div class="col-sm-8 col-sm-offset-2 col-xs-2">


		<a href="<?php echo $siteUrl.'/contactUs'; ?>">
		<button type="button" class="btn btn-default btn-lg cntct_btn ">En savoir plus</button></a>
	</div>
</section>	
<script type="text/javascript">
function locationStore () {
	$("#searchError").hide();
	$("#locationError").hide();

	if (addressMode != 'Google') {
		var city 			= $.trim($("#city").val());
		var location 		= $.trim($("#location").val());

		if(city == ''){
			$("#searchError").show();
			$("#searchError").html("<?php echo __('Please select city'); ?>");
			$("#city").focus();
			return false;
		}

		if (location == '') {
			$("#locationError").show();
			$("#locationError").html("<?php echo __('Please select area/zip'); ?>");
			$("#location").focus();
			return false;
		}

	} else {
		var Address  = $.trim($("#searchAddress").val());
		if(Address == ''){
			$("#searchError").show();
			$("#searchError").html("<?php echo __('Please enter address'); ?>");
			$("#searchAddress").focus();
			return false;
		}
	}
}

</script>