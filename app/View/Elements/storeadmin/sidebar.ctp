<?php

$controllerName = $actionName = "";
if(isset($this->request->params['controller']) && $this->request->params['controller'] != "")
	$controllerName = $this->request->params['controller'];
if(isset($this->request->params['action']) && $this->request->params['action'] != "")
	$actionName = $this->request->params['action'];
?>
<div class="page-container">
    <div class="page-sidebar-wrapper">
        <div class="page-sidebar navbar-collapse collapse">
            <!-- BEGIN SIDEBAR MENU -->
            <ul class="page-sidebar-menu page-sidebar-menu-hover-submenu " data-auto-scroll="true" data-slide-speed="200">
                <li class="<?php if(strtolower($controllerName) == 'dashboards' && $actionName == 'store_index'): ?>active<?php endif; ?>">
                    <a href="<?php echo $siteUrl.'/store/dashboards/index';?>">
                        <i class="fa fa-tachometer"></i>
                        <span class="title">Tableau de bord</span>
                        <span class="selected"></span>
                    </a>
                </li>
                <li class="<?php if(strtolower($controllerName) == 'users' && $actionName == 'store_changePassword'): ?>active<?php endif; ?>">
                    <a href="javascript:void(0);">
                        <i class="fa fa-male"></i>
                        <span class="title">Admin</span>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="<?php echo $siteUrl.'/store/users/changePassword';?>"> changer de mot de passe
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="<?php if(strtolower($controllerName) == 'stores' && $actionName == 'store_edit'): ?>active<?php endif; ?>">
                    <a href="<?php echo $siteUrl.'/store/Stores/edit';?>">
                        <i class="fa fa-cog"></i>
                        <span class="title">Réglages</span>
                    </a>
                </li>
				<?php $deals = $Categories = array('store_index', 'store_add', 'store_edit'); ?>
                <li class="<?php if(strtolower($controllerName) == 'categories' && in_array($actionName, $Categories)): ?>active<?php endif; ?>">
                    <a href="<?php echo $siteUrl.'/store/Categories/index'; ?>">
                        <i class="fa fa-bars"></i>
                        <span class="title">Gestion plats</span>
                    </a>
                </li>
                <!--				<?php $addons = array('store_index', 'store_add', 'store_edit'); ?>
                                                <li class="<?php if(strtolower($controllerName) == 'addons' && in_array($actionName, $addons)): ?>active<?php endif; ?>">
                                                        <a href="<?php echo $siteUrl.'/store/Addons/index'; ?>">
                                                                <i class="fa fa-puzzle-piece"></i>
                                                                <span class="title">Article</span>
                                                        </a>
                                                </li>-->
                <!--				<?php $products = array('store_index', 'store_add', 'store_edit'); ?>
                                                <li class="<?php if(strtolower($controllerName) == 'products' && in_array($actionName, $products)): ?>active<?php endif; ?>">
                                                        <a href="<?php echo $siteUrl.'/store/Products/index';?>">
                                                                <i class="fa fa-briefcase"></i>
                                                                <span class="title">Gestion Menu</span>
                                                        </a>
                                                </li>-->
                                   <?php
				if($loggedUser['Store']['dispatch'] == 'Yes') {
					$dispatchController = array('orders', 'drivers');
					$dispatchAction = array('store_order', 'store_availDriver', 'store_index', 'store_edit', 'store_add', 'store_editVehicle', 'store_addVehicle');
					$dispatchOrder = array('store_order');
					$dispatchDriver = array('store_availDriver', 'store_index', 'store_edit', 'store_add', 'store_editVehicle', 'store_addVehicle'); ?>

<!--				<li class="<?php if((in_array($actionName, $dispatchOrder)) || (strtolower($controllerName) == 'drivers' && in_array($actionName, $dispatchDriver))) : ?>active<?php endif; ?>">
                                        <a href="javascript:void(0);">
                                                <i class="fa fa-automobile"></i>
                                                <span class="title">Expédition</span>
                                                <span class="arrow"></span>
                                        </a>
                                        <ul class="sub-menu">
                                                <li>
                                                        <a href="<?php echo $siteUrl.'/store/orders/order'; ?>">
                                                                Gestion commande
                                                        </a>
                                                </li>
                                                <li>
                                                        <a href="<?php echo $siteUrl.'/store/drivers/index'; ?>">
                                                                Gérer les coursiers
                                                        </a>
                                                </li>
                                        </ul>
                                        </li>-->
                                            <?php
				}

				$ordersAction = array('store_orderIndex', 'store_orderView'); ?>

                <li id="commandes_store" class="<?php if(strtolower($controllerName) == 'orders' && in_array($actionName, $ordersAction)): ?>active<?php endif; ?>">
                    <a href="<?php echo $siteUrl.'/store/Orders/orderIndex';?>">
                        <i class="fa fa-shopping-cart"></i>
                        <span id="" class="title">Commandes</span>
                    </a>
                </li> 

                <li class="<?php if(strtolower($controllerName) == 'orders' && $actionName == 'store_displayDelivery'): ?>active<?php endif; ?>">
                    <a href="<?php echo $siteUrl.'/store/Orders/displayDelivery';?>">
                        <i class="fa fa-cogs"></i>
                        <span class="title">Info livraison</span>
                    </a>
                </li> 
                  <?php

				if($loggedUser['Store']['collection'] == 'Yes'){ ?>
                <li class="<?php if(strtolower($controllerName) == 'orders' && $actionName == 'store_collectionOrder'): ?>active<?php endif; ?>">

                    <a href="<?php echo $siteUrl.'/store/Orders/collectionOrder';?>">
                        <i class="fa fa-truck"></i>
                        <span class="title">Commandes acceptées</span>
                    </a>
                </li>
                                            <?php
				}

				$invoiceAction = array('store_index', 'store_invoiceDetail');
				$reportsAction = array('store_index', 'store_reportOrderView'); ?>

                <li class="<?php if(strtolower($controllerName) == 'invoices' && in_array($actionName, $invoiceAction)): ?>active<?php endif; ?>">
                    <a href="<?php echo $siteUrl.'/store/Invoices/index';?>">
                        <i class="fa fa-list-alt"></i>
                        <span class="title">Facture</span>
                    </a>
                </li>
                <li class="<?php if(strtolower($controllerName) == 'orders' && in_array($actionName, $reportsAction)): ?>active<?php endif; ?>">
                    <a href="<?php echo $siteUrl.'/store/Orders/index'; ?>">
                        <i class="fa fa-file-text-o"></i>
                        <span class="title">Reporting</span>
                    </a>
                </li>
                <li class="<?php if(strtolower($controllerName) == 'orders' && $actionName == "store_statistics"): ?>active<?php endif; ?>">
                    <a href="<?php echo $siteUrl.'/store/Orders/statistics'; ?>">
                        <i class="fa fa-bar-chart-o"></i>
                        <span class="title">Statistique</span>
                    </a>
                </li>
                <li class="<?php if(strtolower($controllerName) == 'deals' && in_array($actionName, $deals)): ?>active<?php endif; ?>">
                    <a href="<?php echo $siteUrl.'/store/deals/index';?>">
                        <i class="fa fa-tags"></i>
                        <span class="title">Mes promo & réductions</span>
                    </a>
                </li><!--
                <li class="<?php if(strtolower($controllerName) == 'storeoffers' && in_array($actionName, $deals)): ?>active<?php endif; ?>">
                        <a href="<?php echo $siteUrl.'/store/Storeoffers/index';?>">
                                <i class="fa fa-money"></i>
                                <span class="title">Offre</span>
                        </a>
                </li>-->
<!--				<li class="<?php if(strtolower($controllerName) == 'vouchers' && in_array($actionName, $deals)): ?>active<?php endif; ?>">
                        <a href="<?php echo $siteUrl.'/store/vouchers/index';?>">
                                <i class="fa fa-gift"></i>
                                <span class="title">Voucher</span>
                        </a>
                </li>
                -->
                <li class="<?php if(strtolower($controllerName) == 'bookatables'): ?>active<?php endif; ?>">
                    <a href="<?php echo $siteUrl.'/store/bookaTables/index';?>">
                        <i class="fa fa-gift"></i>
                        <span class="title"><?php echo __('Book a table'); ?></span>
                    </a>
                </li>


            </ul>
            <!-- END SIDEBAR MENU -->
        </div>
    </div>
</div>