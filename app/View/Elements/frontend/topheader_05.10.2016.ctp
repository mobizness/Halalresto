<?php $controllerName = $actionName = "";
    if(isset($this->request->params['controller']) && $this->request->params['controller'] != "")
    	$controllerName = $this->request->params['controller'];
     if(isset($this->request->params['action']) && $this->request->params['action'] != "")
        $actionName = $this->request->params['action'];
?>
 <?php
 if ($this->request->params['controller'] == 'searches' && $this->request->params['action'] == 'index') {
 	echo '<div class="header indexheader static_header">';
 } else if ($this->request->params['controller'] == 'searches' && $this->request->params['action'] == 'storeitems') {
 	echo '<div class="header detailheader">';
 } else {
 	echo '<div class="header indexheader static_header">';
 } ?>
<div class="headerouterfix">
	<div class="container">
		<nav class="navbar navbar-default" role="navigation">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle  hidden-xs" data-toggle="collapse" data-target="#example-navbar-collapse">
					<img class="img-responsive-sm" style="width: 36px;" src="<?php echo $siteUrl.'/frontend/images/menu.png'; ?>">
				</button> <?php
				//if (empty($pluginConcept)) { ?>
					<a class="navbar-brand pluginLogo" href="<?php echo $siteUrl.'/searches'; ?>">
						<img src="<?php echo $siteUrl.'/siteicons/logo.png'; ?>">
					</a> <?php 
				//}
				
				if ($controllerName != 'checkouts') { ?>
					<ul class="visible-xs visible-sm visible-md topnavRight"> <?php

						if(!empty($loggedCheck) && ($loggedCheck['role_id'] == 4)){

							if (empty($fbPage)) { ?>
								<li> <a href="<?php echo $siteUrl.'/customer/users/userLogout'; ?>"><img alt="Logout" src="<?php echo $siteUrl.'/frontend/images/logout.png'; ?>" title="Logout"><br> <span>Logout</span></a> </li> <?php
							} ?>
							<li> <a href="<?php echo $siteUrl.'/customer/customers/myaccount'; ?>"><img alt="My Account" src="<?php echo $siteUrl.'/frontend/images/myaccount.png'; ?>" title="My Account"><br> <span><?php echo __('My Account'); ?> </span></a> </li> <?php 
						} else { ?>
							<li> <a href="<?php echo $siteUrl.'/customerlogin'; ?>"><img alt="Login" src="<?php echo $siteUrl.'/frontend/images/login.png'; ?>" title="Login"><br> <span>Sign In</span></a> </li>
							<li> <a href="<?php echo $siteUrl.'/signup'; ?>"><img alt="Signup" src="<?php echo $siteUrl.'/frontend/images/signup.png'; ?>" title="Signup"><br> <span>Signup</span></a></li>
							<?php 
						}

						$storeId = (isset($storeId)) ? $storeId : ''; ?>
						
						<li class="pluginResIcon" style="display: none;"> <a href="<?php echo $siteUrl.'/shop/restaurant/'.base64_encode($storeId); ?>"><img alt="Restaurant" src="<?php echo $siteUrl.'/frontend/images/Restaurant.png'; ?>" title="Restaurant"><br> <span>Restaurant</span></a></li> <?php

						if ($this->request->params['controller'] == 'searches' &&
							$this->request->params['action'] == 'stores' ||
							$this->request->params['action'] == 'searchByAddress') { ?>
							<li> <a class="changeLocation pointer" onclick="changeLocation();">
								<img src="<?php echo $siteUrl.'/frontend/images/map.png'; ?>"><br> <span>Location</span></a>
							</li> <?php
						} ?>	
					</ul> <?php	
				} ?>

				<?php
					if ($this->request->params['controller'] == 'searches' &&
						$this->request->params['action'] == 'storeitems') {
				?>
					<!-- <div class="title-categories">
						<img alt="categories" src="<?php echo $siteUrl.'/frontend/images/categories.png'; ?>" title="categories"><br> <span>Categories</span>
					</div>
					<div class="title-filter">
						<img alt="filter" src="<?php echo $siteUrl.'/frontend/images/filter.png'; ?>" title="filter"><br> <span>Filter</span>
					</div> -->
				<?php
					}
				?>				
			</div>
			<div class="collapse navbar-collapse" id="example-navbar-collapse">
			 <?php

				if ($controllerName != 'checkouts') { ?>

					<ul class="nav navbar-nav navbar-right hidden-sm hidden-md">
						<?php
							if ($this->request->params['controller'] == 'searches' &&
								$this->request->params['action'] == 'storeitems') {
						?>
						<li>								
							<a class="cartDropdown" href="javascript:void(0);"><i class="fa fa-shopping-cart"></i>
								<?php echo $siteCurrency; ?>
								<span class="cartTotal">0</span><span class="caret"></span></a>

							<div class="cart_notification hidden-xs hidden-sm" style="display:none;">
								<?php echo __('Menu added to cart successfully.', true); ?>
							</div>
							<div class="cart_failedNotification hidden-xs hidden-sm" style="display:none;">
								<?php echo __('Quantity Exceeded..!', true); ?>
							</div>
						</li>
					<?php

						}
						if ($this->request->params['controller'] == 'searches' &&
							$this->request->params['action'] == 'stores' ||
							$this->request->params['action'] == 'searchByAddress') { ?>
							<li> <a class="changeLocation pointer" onclick="changeLocation();">
								<i class="fa fa-map-marker"></i> <?php echo __('Change Location', true); ?></a></li> <?php
						}
						
						if(!empty($loggedCheck) && ($loggedCheck['role_id'] == 4)){ ?>

							<li> <a href="<?php echo $siteUrl.'/customer/customers/myaccount'; ?>"> <?php echo __('My Account', true); ?></a> </li> <?php
							if (empty($fbPage)) { ?>
								<li> <a href="<?php echo $siteUrl.'/customer/users/userLogout'; ?>"> <?php echo __('Logout', true); ?></a> </li> <?php 
							}
						} else { ?>
                            <li> <a href="<?php echo $siteUrl.'/signup'; ?>"> <?php echo __('Sign Up', true); ?></a></li>
                            <li> <a href="<?php echo $siteUrl.'/customerlogin'; ?>"> <?php echo __('Login', true); ?></a> </li> <?php
						} ?>	
						
					</ul> <?php
				} ?>
			</div>
		</nav>
		</div>
		</div>
		<div class="container">
		<?php if ($this->request->params['controller'] == 'searches' && $this->request->params['action'] == 'storeitems') { ?>
			<div class="searchicn">
				<img alt="<?php echo $storeDetails['Store']['store_name']; ?>" src="<?php echo $siteUrl.'/storelogos/'.$storeDetails['Store']['store_logo']; ?>" onerror="this.onerror=null;this.src='<?php echo $siteUrl."/frontend/images/no_store.jpg"; ?>'" width ="175px" height="110px">
			</div>
			<div class="text-center">				
				<span class="rating_star_big">
					<span class="rating_star_big_gold" style="width:<?php echo $storeDetails['Store']['rating']; ?>%"></span>	
				</span>
			</div>
			<div class="headrestname"> <?php echo $storeDetails['Store']['store_name']; ?> </div>
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
				<div class="headermenudel">Min.Order: <?php

                    $minimumOrder = ($siteSetting['Sitesetting']['address_mode'] == 'Google') ?
                        $storeDetails['Store']['minimum_order'] : $storeDetails['DeliveryLocation']['DeliveryLocation']['minimum_order'];

					echo $this->Number->currency($minimumOrder, $siteCurrency);
					?>
				</div>
				<div class="headermenudel">Del Time : <?php echo $storeDetails['Store']['estimate_time']; ?></div>
				<div class="headermenudel">Delivery Fee: <?php
                    $deliveryCharge = ($siteSetting['Sitesetting']['address_mode'] == 'Google') ?
                        $storeDetails['Store']['delivery_charge'] :
                        $storeDetails['DeliveryLocation']['DeliveryLocation']['delivery_charge'];

                    echo $this->Number->currency($deliveryCharge, $siteCurrency); ?>
                </div><?php
                if($siteSetting['Sitesetting']['address_mode'] == 'Google') { ?>
				<div class="headermenudel">Distance : <?php echo $storeDetails['Store']['distance']; ?> Miles</div><?php
                } ?>
                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#book_a_table">
				  Book a Table
				</button>
			</div><?php
		} ?>
		</div>
	</div>
</div>