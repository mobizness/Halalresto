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
									echo $this->Form->button(__('Submit'),
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
							<div class="col-lg-8 col-md-8 col-sm-8 no-padding-right">
								<div class="shop-select">  <?php
									echo $this->Form->input('address',
											array('type'=>'text',
													'class'=>'form-control',
													'id' => 'searchAddress',
													'placeholder' => 'Enter your location',
													'empty' => __('Enter your address'),
													'label'=> false,
													'onfocus' =>'initialize(this.id)')); ?>
									<div id="searchError" class="form-error" style="display: none;"></div>
								</div> 
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4">	
								<div class="shop-button"> <?php
									echo $this->Form->button(__('Submit'),
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
		Add your Restaurant <a href="<?php echo $siteUrl.'/restaurantSignup'; ?>" class="signuptext">Signup</a>
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
<div class="foodbanner hidden-xs">
  <div class="container">
    <div class="row">
      <div class="col-md-5">
        <h5>Online Ordering for your customers</h5>
		<p>Bring your restaurant online and allow your customers to pre-order takeaways and deliveries for their next meal.</p>
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
				<h5>Add your Restaurant</h5>
				<p>Join the thousands of other restaurants who benefit from having their menus on <?php
					echo $siteSetting['Sitesetting']['site_name']; ?> </p>
			</div>
			<div class="col-lg-3 col-md-4">
				<a href="<?php echo $siteUrl.'/restaurantSignup'; ?>" class="signuptext">Signup</a>
			</div>
		</div>
	</div>
</div>
<section class="contact_us_section relative">
	<div class="col-sm-8 col-sm-offset-2 col-xs-2">
		<a href="<?php echo $siteUrl.'/contactUs'; ?>"><button type="button" class="btn btn-default btn-lg cntct_btn ">contact us</button></a>
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