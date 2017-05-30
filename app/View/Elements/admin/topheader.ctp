<?php

$controllerName = $actionName = "";
    
    if(isset($this->request->params['controller']) && $this->request->params['controller'] != "")
    $controllerName = $this->request->params['controller'];
                                                            
     if(isset($this->request->params['action']) && $this->request->params['action'] != "")
        $actionName = $this->request->params['action'];
?> 
<div class="page-header navbar navbar-fixed-top">
    <div class="page-header-inner">
        <div class="page-logo">
            <a href="<?php echo $siteUrl.'/admin/dashboards/index'; ?>"><?php echo $siteSetting['Sitesetting']['site_name']; ?></a>
            <div class="menu-toggler sidebar-toggler"></div>
        </div>
        <a href="javascript:void(0);" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"></a>

        <div class="page-top">
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                                    <?php if($loggedUser['role_id'] == 1) {?>
                    <li class="hidden-xs">

                        <a href="<?php echo $siteUrl.'/admin/Orders/index'; ?>">
                            <i class="fa fa-shopping-cart"></i>
                            <div class="logoutTxt">COMMANDES</div>
                        </a>

                    </li>
                    <li class="dropdown hidden-xs">

                        <a class="dropdown-toggle" data-close-others="true" data-toggle="dropdown" href="javascript:void(0);" aria-expanded="true">
                            <i class="fa fa-automobile"></i>
                            <div class="logoutTxt">Expedition</div>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="<?php if($controllerName == 'orders' && $actionName == 'admin_order'): ?>active<?php endif; ?>">
                                <a href="<?php echo $siteUrl.'/admin/orders/order'; ?>"> Gestion commande
                                </a>
                            </li>
                            <li class="<?php if($controllerName == 'drivers' && $actionName == 'admin_index'): ?>active<?php endif; ?>">
                                <a href="<?php echo $siteUrl.'/admin/drivers/index'; ?>"> Gérer les coursiers
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown hidden-xs">

                        <a class="dropdown-toggle" data-close-others="true" data-toggle="dropdown" href="javascript:void(0);" aria-expanded="true">
                            <i class="fa fa-cog"></i>
                            <div class="logoutTxt">REGLAGES</div>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="<?php if($controllerName == 'sitesettings' && $actionName == 'admin_index'): ?>active<?php endif; ?>">
                                <a href="<?php echo $siteUrl.'/admin/sitesettings/index'; ?>">Réglages site
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $siteUrl.'/admin/sitesettings/paymentSetting'; ?>">Réglages paiements
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown hidden-xs">
                        <a class="dropdown-toggle" data-close-others="true" data-toggle="dropdown" href="javascript:void(0);" aria-expanded="true">
                            <i class="fa fa-shopping-cart"></i>
                            <div class="logoutTxt">Restaurant</div>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo $siteUrl.'/admin/stores/index'; ?>"> Gestion Restaurant </a></li>
                            <li><a href="<?php echo $siteUrl.'/admin/products/index'; ?>"> Gestion Menu </a></li>
                            <li><a href="<?php echo $siteUrl.'/admin/Storeoffers/index'; ?>"> Offre Restaurant </a></li>
                            <li><a href="<?php echo $siteUrl.'/admin/deals/index'; ?>"> Deal Restaurant </a> </li>
                            <li><a href="<?php echo $siteUrl.'/admin/vouchers/index'; ?>"> Restaurant Code promo </a></li>
                            <li><a href="<?php echo $siteUrl.'/admin/Reviews/list'; ?>"> Restaurant Reviews </a></li>
                        </ul>
                    </li>
                                    <?php }?>
                    <li>
                        <a href="<?php echo $siteUrl.'/admin/users/adminLogout'; ?>">
                            <i class="fa fa-sign-out"></i>
                            <div class="logoutTxt">SE DECONNECTER</div>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
    </div>
</div>