<?php

if(!empty($storeDetails['Store']['banner_image_link'])){
    if (strpos($storeDetails['Store']['banner_image_link'], '/watch?v=') !== false){
        $query_str = parse_url($storeDetails['Store']['banner_image_link'], PHP_URL_QUERY);
        parse_str($query_str, $query_params);
        $storeDetails['Store']['banner_image_link'] = $query_params["v"];
    }else{
        $storeDetails['Store']['banner_image_link'] =  basename($storeDetails['Store']['banner_image_link']);
    }
}

$controllerName = $actionName = "";
    if(isset($this->request->params['controller']) && $this->request->params['controller'] != "")
    	$controllerName = $this->request->params['controller'];
     if(isset($this->request->params['action']) && $this->request->params['action'] != "")
        $actionName = $this->request->params['action'];
?>
 <?php
 if ($this->request->params['controller'] == 'searches' && $this->request->params['action'] == 'index') {
 	echo '<div class="header indexheader static_header">';
 }else if ($this->request->params['controller'] == 'searches' && $this->request->params['action'] == 'storeitems') {
    if((!empty($storeDetails['Store']['store_banner'])) && (empty($storeDetails['Store']['banner_image_link']))){
        $format = explode(".", $storeDetails['Store']['store_banner']);
        if($format[1] == "mp4"){ ?>
<div class="header detailheader clearfix">
    <video id="youtube" controls autoplay loop>
        <source src="<?php echo $siteUrl.'/storebanner/original/'.$storeDetails['Store']['store_banner']; ?>" type="video/mp4">
    </video>   
        <?php
        }
        else{
     ?>
    <div class="header detailheader clearfix" <?php if(!empty($storeDetails['Store']['store_banner']) && empty($storeDetails['Store']['banner_image_link'])) { ?> style="background-size: cover;background-image:url(<?php echo $siteUrl.'/storebanner/original/'.$storeDetails['Store']['store_banner']; ?>);background-repeat: no-repeat;" <?php } ?>>	 
<?php
    }
}else if(!empty($storeDetails['Store']['banner_image_link'])){
     ?>
        <div class="header detailheader clearfix">
            <iframe id="youtube" src="https://www.youtube.com/embed/<?php echo $storeDetails['Store']['banner_image_link']; ?>?playlist=<?php echo $storeDetails['Store']['banner_image_link']; ?>&autoplay=1&loop=1" frameborder="0"></iframe>
 <?php }
 else{
    ?>
            <div class="header detailheader clearfix">
        <?php
 }
 
} else {
 	echo '<div class="header indexheader static_header">';
 } ?>
                <div class="btn-cart-toggle pull-right">&nbsp;</div>
                <div <?php if($this->request->params['action'] == 'storeitems') { ?>class="headerouterfix" style="margin-bottom:150px;"<?php }?>>
                    <div class="container">
                        <nav class="navbar navbar-default" role="navigation">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle  hidden-xs" data-toggle="collapse" data-target="#example-navbar-collapse">
                                    <img class="img-responsive-sm" style="width: 36px;" src="<?php echo $siteUrl.'/frontend/images/menu.png'; ?>">
                                </button> <?php
				//if (empty($pluginConcept)) { ?>
                                <a class="navbar-brand pluginLogo" href="<?php echo $siteUrl.'/searches'; ?>">
                                    <img src="<?php echo $siteUrl.'/siteicons/logo.png'; ?>">
                                </a> 
                                <span class="baricon"><i class="fa fa-bars"></i></span>
					<?php 


				//}
					
				if ($controllerName != 'checkouts') { ?>
                                <ul class="topnavRight mobilemenu">

					<?php 

					/*if ($this->request->params['controller'] == 'searches' &&
							$this->request->params['action'] == 'index') { ?>

						<li> <a href="<?php echo $siteUrl.'/apply'; ?>"><span><?php echo __('Delivery Boy', true); ?></span></a></li> <?php


					}*/


						if(!empty($loggedCheck) && ($loggedCheck['role_id'] == 4)){

							if (empty($fbPage)) { ?>
                                                                <li> <a href="<?php echo $siteUrl.'/customer/users/userLogout'; ?>"><!--<img alt="Logout" src="<?php echo $siteUrl.'/frontend/images/logout.png'; ?>" title="Logout">--> <span>Logout</span></a> </li> <?php
							} ?>
                                                        <li> <a href="<?php echo $siteUrl.'/customer/customers/myaccount'; ?>"><!--<img alt="My Account" src="<?php echo $siteUrl.'/frontend/images/myaccount.png'; ?>" title="My Account">--> <span><?php echo __('My Account'); ?> </span></a> </li> <?php 
						} else { ?>
                                                        <li> <a href="<?php echo $siteUrl.'/customerlogin'; ?>"><!--<img alt="Login" src="<?php echo $siteUrl.'/frontend/images/login.png'; ?>" title="Login">--> <span> Se connecter</span></a> </li>
                                                        <li> <a href="<?php echo $siteUrl.'/signup'; ?>"><!--<img alt="Signup" src="<?php echo $siteUrl.'/frontend/images/signup.png'; ?>" title="Signup">--> <span><?php echo __('Sign Up');?></span></a></li>
							<?php 
						}

						$storeId = (isset($storeId)) ? $storeId : ''; ?>

<!-- <li class="pluginResIcon" style="display: none;"> <a href="<?php echo $siteUrl.'/shop/restaurant/'.base64_encode($storeId); ?>"><img alt="Restaurant" src="<?php echo $siteUrl.'/frontend/images/Restaurant.png'; ?>" title="Restaurant"><br> <span>Restaurant</span></a></li> --> <?php

						if ($this->request->params['controller'] == 'searches' &&
							$this->request->params['action'] == 'stores' ||
							$this->request->params['action'] == 'searchByAddress') { ?>
                                    <li> <a class="changeLocation pointer" onclick="changeLocation();">
                                            <img src="<?php echo $siteUrl.'/frontend/images/map.png'; ?>" class="hidden-xs"><span>Location</span></a>
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
                                <?php if ($this->request->params['controller'] == 'searches' &&
							$this->request->params['action'] == 'index') { ?>
                                <span class="text-right header-social">
                                    <a class="btn btn-sm" target="_blank" href="https://www.facebook.com/HALAL-RESTO-174126233017569/"><img src="https://halal-resto.fr/frontend/images/hf.png"></a>
                                    <a class="btn btn-sm" target="_blank" href="https://accounts.google.com/"><img src="https://halal-resto.fr/frontend/images/hg.png"></a>
                                    <a class="btn btn-sm" target="_blank" href="https://www.instagram.com/halal_resto/"><img src="https://halal-resto.fr/frontend/images/hi.png"></a>
                                    <a class="btn btn-sm" target="_blank" href="https://twitter.com/halal_resto"><img src="https://halal-resto.fr/frontend/images/ht.png"></a>
                                    <a class="btn btn-sm" target="_blank" href="https://www.youtube.com/"><img src="https://halal-resto.fr/frontend/images/hy.png"></a>
                                </span>
                                                            <?php
						}?>

			 <?php

				if ($controllerName != 'checkouts') { ?>

                                <ul class="nav navbar-nav navbar-right"> <?php
						/*if ($this->request->params['controller'] == 'searches' &&
							$this->request->params['action'] == 'index') { ?>
							<li> <a class="delboy" href="<?php echo $siteUrl.'/apply'; ?>"><?php echo __('Delivery Boy', true); ?></a></li>
							<?php
						}*/
							if ($this->request->params['controller'] == 'searches' &&
								$this->request->params['action'] == 'storeitems') {
						?>

                                    <li>								
                                        <a class="cartDropdown" href="javascript:void(0);"><i class="fa fa-shopping-cart"></i>
                                            <span class="cartTotal">0</span><?php echo $siteCurrency; ?><span class="caret"></span></a>

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
                    <div class="container">
		<?php if ($this->request->params['controller'] == 'searches' && $this->request->params['action'] == 'storeitems') { ?>
                        <div class="searchicn">
                        </div>
                        <div class="text-center">				
                            <span class="rating_star_big">
                                <span class="rating_star_big_gold" style="width:<?php echo $storeDetails['Store']['rating']; ?>%"></span>	
                            </span>
                        </div>
                        <div class="headrestname"> <?php echo $storeDetails['Store']['store_name']; ?> </div>
                        <?php
		} ?>
                    </div>
                </div>
            </div>
