<?php

$controllerName = $actionName = "";
    
    if(isset($this->request->params['controller']) && $this->request->params['controller'] != "")
    	$controllerName = $this->request->params['controller'];
                                                            
     if(isset($this->request->params['action']) && $this->request->params['action'] != "")
        $actionName = $this->request->params['action'];

    //exit();
?> 


<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu page-sidebar-menu-hover-submenu" data-auto-scroll="true" data-slide-speed="200">
            <?php if($loggedUser['role_id'] == 1) {?>
            <li class="<?php if($controllerName == 'dashboards' && $actionName == 'admin_index'): ?>start active<?php endif; ?>">
                <a href="<?php echo $siteUrl.'/admin/dashboards/index'; ?>">
                    <i class="fa fa-tachometer"></i>
                    <span class="title">Tableau de bord</span>
                    <span class="selected"></span>
                </a>
            </li>


                        <!-- <li class="<?php if($controllerName == 'users' && $actionName == 'changepassword'): ?>start active<?php endif; ?>">
                    <a href="<?php echo $siteUrl.'/admin/users/changePassword'; ?>">  
                        <i class="icon-home"></i>
                        <span class="title"><?php echo __("Dashboard", true); ?></span>
                        <span class="selected"></span>
                    </a>
                </li> -->


            <li class="<?php if($controllerName == 'users' && $actionName == 'admin_changePassword'): ?>start active<?php endif; ?>">
                <a href="javascript:void(0);">
                    <i class="fa fa-male"></i>
                    <span class="title">Admin</span>
                    <span class="arrow "></span>
                </a>
                <ul class="sub-menu">
                    <li class="<?php if($controllerName == 'users' && $actionName == 'admin_changepassword'): ?>active<?php endif; ?>">
                        <a href="<?php echo $siteUrl.'/admin/users/changePassword'; ?>">  
                            <i class="fa fa-key"></i>changer de mot de passe
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:void(0);">
                    <i class="fa fa-cog"></i>
                    <span class="title">Réglages</span>
                    <span class="arrow "></span>
                </a>
                <ul class="sub-menu">
                    <li class="<?php if($controllerName == 'sitesettings' && $actionName == 'admin_index'): ?>active<?php endif; ?>">
                        <a href="<?php echo $siteUrl.'/admin/sitesettings/index'; ?>">
                            <i class="fa fa-file-text-o"></i> Réglages site
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $siteUrl.'/admin/sitesettings/paymentSetting'; ?>">
                            <i class="fa fa-money"></i> Réglages paiements
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $siteUrl.'/admin/sitesettings/translation'; ?>">
                            <i class="fa fa-money"></i> Réglages traduction
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="<?php echo $siteUrl.'/admin/Cuisines/index'; ?>">
                    <i class="fa fa-database" aria-hidden="true"></i>
                    <span class="title">Spécialités culinaires</span>
                </a>
            </li>
            <?php }?>
            <!--			<li>
                                            <a href="<?php echo $siteUrl.'/admin/Addons/index'; ?>">
                                                    <i class="fa fa-plus-square-o" aria-hidden="true"></i>
                                                    <span class="title">Article</span>
                                                    <span class="arrow "></span>
                                            </a>
                                            
                                    </li>-->

            <li class="<?php if($controllerName == 'stores' && $actionName == 'admin_index'): ?>start active<?php endif; ?>">
                <a href="javascript:void(0);">
                    <i class="fa fa-university" aria-hidden="true"></i>
                    <span class="title">Restaurant</span>
                    <span class="arrow "></span>
                </a>
                <ul class="sub-menu">
                    <?php if($loggedUser['role_id'] != 2) {?>
                    <li class="<?php if($controllerName == 'stores' && $actionName == 'admin_index'): ?>active<?php endif; ?>">
                        <a href="<?php echo $siteUrl.'/admin/stores/index'; ?>">
                            <i class="fa fa-wrench"></i> Gestion Restaurant
                        </a>
                    </li>
                    <?php }?>
                    <li class="<?php if($controllerName == 'products' && $actionName == 'admin_index'): ?>active<?php endif; ?>">
                        <a href="<?php echo $siteUrl.'/admin/products/index'; ?>">
                            <i class="fa fa-wrench"></i> Gestion Menu
                        </a>
                    </li>
                     <?php if($loggedUser['role_id'] != 2) {?>
                    <li>
                        <a href="<?php echo $siteUrl.'/admin/Storeoffers/index'; ?>">
                            <i class="fa fa-user"></i> Offre Restaurant
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $siteUrl.'/admin/orders/displayDelivery'; ?>">
                            <i class="fa fa-gift"></i> Restaurant Info Livarison
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $siteUrl.'/admin/deals/index'; ?>">
                            <i class="fa fa-user"></i> Deal Restaurant
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo $siteUrl.'/admin/vouchers/index'; ?>">
                            <i class="fa fa-gift"></i> Restaurant Code promo
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo $siteUrl.'/admin/Reviews/list'; ?>">
                            <i class="fa fa-user"></i> Restaurant Reviews
                        </a>
                    </li>
                     <?php }?>
                </ul>
            </li>
 <?php if($loggedUser['role_id'] != 2) {?>
            <li>
                <a href="<?php echo $siteUrl.'/admin/Orders/index'; ?>">
                    <i class="fa fa-shopping-cart"></i>
                    <span class="title">Commandes</span>
                </a>
            </li>

            <!--			<li>
                                            <a href="<?php echo $siteUrl.'/admin/Orders/collectionOrders'; ?>">
                                                    <i class="fa fa-archive" aria-hidden="true"></i>
                                                    <span class="title">Gestion à emporter</span>
                                            </a>
                                    </li>-->

            <li class="<?php if($controllerName == 'orders' && $actionName == 'admin_order'): ?>start active<?php endif; ?>">
                <a href="javascript:void(0);">
                    <i class="fa fa-automobile"></i>
                    <span class="title">Expédition</span>
                    <span class="arrow "></span>
                </a>
                <ul class="sub-menu">
                    <li class="<?php if($controllerName == 'orders' && $actionName == 'admin_order'): ?>active<?php endif; ?>">
                        <a href="<?php echo $siteUrl.'/admin/orders/order'; ?>">
                            <i class="fa fa-wrench"></i>
                            Gestion commande
                        </a>
                    </li>
                    <li class="<?php if($controllerName == 'drivers' && $actionName == 'admin_index'): ?>active<?php endif; ?>">
                        <a href="<?php echo $siteUrl.'/admin/drivers/index'; ?>">
                            <i class="fa fa-wrench"></i>
                            Gérer les coursiers
                        </a>
                    </li>
                </ul>
            </li>
 <?php }?>
            <li>
                <a href="<?php echo $siteUrl.'/admin/Categories/index'; ?>">
                    <i class="fa fa-bars"></i>
                    <span class="title">Catégorie</span>
                </a>
            </li>
             <?php if($loggedUser['role_id'] != 2) {?>
            <li>
                <a href="javascript:void(0);">
                    <i class="fa fa-briefcase"></i>
                    <span class="title">Nous contacter</span>
                    <span class="arrow "></span>
                </a>
                <ul class="sub-menu">
                    <!-- <li>
                            <a href="<?php echo $siteUrl.'/admin/Newsletters/index'; ?>">
                                    <i class="fa fa-envelope-o"></i> Newsletter
                            </a>
                    </li> -->
                    <li>
                        <a href="<?php echo $siteUrl.'/admin/Contactuses/index'; ?>">
                            <i class="fa fa-envelope-o"></i> Contactez-nous
                        </a>
                    </li>
                </ul>
            </li>
            <li class="<?php if($controllerName == 'states' || $controllerName == 'states' || $controllerName == 'cities' || $controllerName == 'locations'): ?>start active<?php endif; ?>">
                <a href="javascript:void(0);">
                    <i class="fa fa-map-marker"></i>
                    <span class="title">Lieu</span>
                    <span class="arrow "></span>
                </a>
                <ul class="sub-menu">
                    <li class="<?php if($controllerName == 'countries'): ?>active<?php endif; ?>">
                        <a href="<?php echo $siteUrl.'/admin/countries/index'; ?>">
                            <i class="fa fa-map-o"></i> Pays
                        </a>
                    </li>
                    <li class="<?php if($controllerName == 'states'): ?>active<?php endif; ?>">
                        <a href="<?php echo $siteUrl.'/admin/states/index'; ?>">
                            <i class="fa fa-map-o"></i> Departement
                        </a>
                    </li>
                    <li class="<?php if($controllerName == 'cities'): ?>active<?php endif; ?>">
                        <a href="<?php echo $siteUrl.'/admin/cities/index'; ?>">
                            <i class="fa fa-map-o"></i> Ville
                        </a>
                    </li>
                    <li class="<?php if($controllerName == 'locations'): ?>active<?php endif; ?>">
                        <a href="<?php echo $siteUrl.'/admin/locations/index'; ?>">
                            <i class="fa fa-street-view"></i> Code postal
                        </a>
                    </li>
                </ul>
            </li>




            <li>
                <a href="<?php echo $siteUrl.'/admin/Customers/index'; ?>">
                    <i class="fa fa-user"></i>
                    <span class="title">Client</span>
                </a>
            </li>

            <li>
                <a href="<?php echo $siteUrl.'/admin/Invoices/index'; ?>">
                    <i class="fa fa-file-o"></i>
                    <span class="title">Facture</span>
                </a>
            </li>
            <li>
                <a href="<?php echo $siteUrl.'/admin/Orders/reportIndex'; ?>">
                    <i class="fa fa-file-text-o"></i>
                    <span class="title">Reporting</span>
                </a>
            </li>
            <li>
                <a href="<?php echo $siteUrl.'/admin/Users/writerview'; ?>">
                    <i class="fa fa-users"></i>
                    <span class="title">Add Content Writers</span>
                </a>
            </li>
            <li>
                <a href="javascript:void(0);">
                    <i class="fa fa-envelope-square"></i>
                    <span class="title">News Letters Info</span>
                </a>
                <ul class="sub-menu">
                    <li class="">
                        <a href="<?php echo $siteUrl.'/admin/Users/newsletter'; ?>">
                            <i class="fa fa-map-o"></i> News Letter Detail
                        </a>
                    </li>
                    <li >
                        <a href="<?php echo $siteUrl.'/admin/Users/userssubscribed'; ?>">
                            <i class="fa fa-map-o"></i> News Letter User's List
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:void(0);">
                    <i class="fa fa-bar-chart-o"></i>
                    <span class="title">Statistique</span>
                </a>
                <ul class="sub-menu">
                    <li class="">
                        <a href="<?php echo $siteUrl.'/admin/Stores/astatistics'; ?>">
                            <i class="fa fa-bar-chart-o"></i> Admin Statistique
                        </a>
                    </li>
                    <li class="">
                        <a href="<?php echo $siteUrl.'/admin/Stores/statistics'; ?>">
                            <i class="fa fa-bar-chart-o"></i> Restaurant Statistique
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="<?php echo $siteUrl.'/admin/Stores/halalproducts'; ?>">
                    <i class="fa fa-sun-o fa-spin"></i>
                    <span class="title">Halal produits</span>
                </a>
            </li>
             <?php }?>
        </ul>
    </div>
</div>