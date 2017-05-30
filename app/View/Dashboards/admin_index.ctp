<!-- BEGIN CONTENT -->
<div class="contain">
	<div class="contain">			
		<h3 class="page-title">Tableau de bord</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="javascript:void(0);">Accueil</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="javascript:void(0);">Tableau de bord</a>
				</li>
			</ul>
		</div>
		<div class="outerdashbox">
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#today">Aujourd’hui <br>
					<small> <?php //echo $todayDate; ?>  </small></a>
				</li>
				<li> <a data-toggle="tab" href="#yesterday">hier</a> </li>
				<li> <a data-toggle="tab" href="#week">Les 7 derniers jours</a> </li>
				<li> <a data-toggle="tab" href="#month">Les 30 derniers jours</a> </li>
			</ul>

			<div class="tab-content">
                <div id="today" class="tab-pane fade in active">
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <a class="dashboard-stat dashboard-stat-light blue-soft" href="#">
                        <div class="visual">
                            <i class="fa fa-comments"></i>
                        </div>
                        <div class="details">
                            <div class="number"> <?php
                                echo $this->Number->currency($dasboard_value['todaySubTotal'], $siteCurrency);?>
                            </div>
                            <div class="desc"> Total Vente </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <a class="dashboard-stat dashboard-stat-light red-soft" href="#">
                        <div class="visual">
                            <i class="fa fa-trophy"></i>
                        </div>
                        <div class="details">
                            <div class="number"> <?php
                                echo $dasboard_value['todayOrderCount']; ?>
                            </div>
                            <div class="desc"> Commandes </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <a class="dashboard-stat dashboard-stat-light green-soft" href="#">
                        <div class="visual">
                            <i class="fa fa-shopping-cart"></i>
                        </div>
                        <div class="details">
                            <div class="number"><?php
                                echo $dasboard_value['todayCustomer']; ?>
                            </div>
                            <div class="desc">
                                 Nouveau client
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
                <div id="yesterday" class="tab-pane fade">
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <a class="dashboard-stat dashboard-stat-light blue-soft" href="#">
                        <div class="visual">
                            <i class="fa fa-comments"></i>
                        </div>
                        <div class="details">
                            <div class="number"><?php
                                echo $this->Number->currency($dasboard_value['yesterdaySubTotal'], $siteCurrency);?>
                            </div>
                            <div class="desc">
                                 Total Vente
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <a class="dashboard-stat dashboard-stat-light red-soft" href="#">
                        <div class="visual">
                            <i class="fa fa-trophy"></i>
                        </div>
                        <div class="details">
                            <div class="number"> <?php
                                echo $dasboard_value['yesterdayOrderCount']; ?>
                            </div>
                            <div class="desc">
                                 Commandes
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <a class="dashboard-stat dashboard-stat-light green-soft" href="#">
                        <div class="visual">
                            <i class="fa fa-shopping-cart"></i>
                        </div>
                        <div class="details">
                            <div class="number"><?php
                                echo $dasboard_value['yesterdayCustomer']; ?>
                            </div>
                            <div class="desc">
                                 Nouveau client
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
                <div id="week" class="tab-pane fade">
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <a class="dashboard-stat dashboard-stat-light blue-soft" href="#">
                        <div class="visual">
                            <i class="fa fa-comments"></i>
                        </div>
                        <div class="details">
                            <div class="number"><?php
                                echo $this->Number->currency($dasboard_value['weekSubTotal'], $siteCurrency);?>
                            </div>
                            <div class="desc">
                                 Total Vente
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <a class="dashboard-stat dashboard-stat-light red-soft" href="#">
                        <div class="visual">
                            <i class="fa fa-trophy"></i>
                        </div>
                        <div class="details">
                            <div class="number"><?php
                                echo $dasboard_value['weekOrderCount']; ?>
                            </div>
                            <div class="desc">
                                 Commandes
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <a class="dashboard-stat dashboard-stat-light green-soft" href="#">
                        <div class="visual">
                            <i class="fa fa-shopping-cart"></i>
                        </div>
                        <div class="details">
                            <div class="number"><?php
                                echo $dasboard_value['weekCustomer']; ?>
                            </div>
                            <div class="desc">
                                 Nouveau client
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
                <div id="month" class="tab-pane fade">
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <a class="dashboard-stat dashboard-stat-light blue-soft" href="#">
                        <div class="visual">
                            <i class="fa fa-comments"></i>
                        </div>
                        <div class="details">
                            <div class="number"><?php
                                echo $this->Number->currency($dasboard_value['monthSubTotal'], $siteCurrency); ?>
                            </div>
                            <div class="desc">
                                 Total Vente
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <a class="dashboard-stat dashboard-stat-light red-soft" href="#">
                        <div class="visual">
                            <i class="fa fa-trophy"></i>
                        </div>
                        <div class="details">
                            <div class="number"><?php
                                echo $dasboard_value['monthOrderCount']; ?>
                            </div>
                            <div class="desc">
                                 Commandes
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <a class="dashboard-stat dashboard-stat-light green-soft" href="#">
                        <div class="visual">
                            <i class="fa fa-users"></i>
                        </div>
                        <div class="details">
                            <div class="number"><?php
                                echo $dasboard_value['monthCustomer']; ?>
                            </div>
                            <div class="desc">
                                 Nouveau client
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-4">
				<div class="outerdashbox">
					<h4>Toutes les commandes</h4>
					<div class="dashprice col-sm-12"><?php
                        echo $dasboard_value['order_count']; ?>
                    </div>
					<div class="dashsubname col-sm-12">Commande</div>
                    <div class="dashprice col-sm-12"><?php
                        echo $this->Number->currency($dasboard_value['order_price'], $siteCurrency);?>
                    </div>
                    <div class="dashsubname col-sm-12">Prix commande</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="outerdashbox">
					<h4>Info Utilisateur</h4>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class=" dashprice col-sm-12"><?php echo $dasboard_value['totalUsers']; ?></div>
        					<div class="dashsubname col-sm-12">Nombre Utilisateur</div>                    
                            <div class="dashprice col-sm-12"><?php echo $dasboard_value['activeUsers']; ?></div>
                            <div class="dashsubname col-sm-12">Utilisateurs Actifs</div>                        
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">                            
                            <div class="dashprice col-sm-12"> <?php echo $dasboard_value['deactiveUsers']; ?></div>
                            <div class="dashsubname col-sm-12">Utilisateurs inactifs</div>                            
                            <div class="dashprice col-sm-12"><?php echo $dasboard_value['pendingUsers']; ?></div>
                            <div class="dashsubname col-sm-12">Utilisateurs en attente</div>
                        </div>
                    </div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="outerdashbox">
					<h4>Info Restaurant</h4>					
                    <div class="col-sm-6">
                        <div class="row">
                            <div class=" dashprice col-sm-12"><?php echo $dasboard_value['totalRestaurants']; ?></div>
                            <div class="dashsubname col-sm-12">Nombre Restaurant</div>                    
                            <div class="dashprice col-sm-12"><?php echo $dasboard_value['activeRestaurants']; ?></div>
                            <div class="dashsubname col-sm-12">Restaurants Actifs</div>                        
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">                            
                            <div class="dashprice col-sm-12"> <?php echo $dasboard_value['deactiveRestaurants']; ?></div>
                            <div class="dashsubname col-sm-12">Restaurant inactifs</div>                            
                            <div class="dashprice col-sm-12"><?php echo $dasboard_value['pendingRestaurants']; ?></div>
                            <div class="dashsubname col-sm-12">Restaurant en attente</div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>