<?php $controllerName = $actionName = "";
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
						<span class="title">Dashboard</span>
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
							<a href="<?php echo $siteUrl.'/store/users/changePassword';?>"> Change Password
							</a>
						</li>
					</ul>
				</li>
				<li class="<?php if(strtolower($controllerName) == 'stores' && $actionName == 'store_edit'): ?>active<?php endif; ?>">
					<a href="<?php echo $siteUrl.'/store/Stores/edit';?>">
						<i class="fa fa-cog"></i>
						<span class="title">Settings</span>
					</a>
				</li>
				<?php $deals = $Categories = array('store_index', 'store_add', 'store_edit'); ?>
				<li class="<?php if(strtolower($controllerName) == 'categories' && in_array($actionName, $Categories)): ?>active<?php endif; ?>">
					<a href="<?php echo $siteUrl.'/store/Categories/index'; ?>">
						<i class="fa fa-bars"></i>
						<span class="title">Category</span>
					</a>
				</li>
				<?php $addons = array('store_index', 'store_add', 'store_edit'); ?>
				<li class="<?php if(strtolower($controllerName) == 'addons' && in_array($actionName, $addons)): ?>active<?php endif; ?>">
					<a href="<?php echo $siteUrl.'/store/Addons/index'; ?>">
						<i class="fa fa-puzzle-piece"></i>
						<span class="title">Addons</span>
					</a>
				</li>
				<?php $products = array('store_index', 'store_add', 'store_edit'); ?>
				<li class="<?php if(strtolower($controllerName) == 'products' && in_array($actionName, $products)): ?>active<?php endif; ?>">
					<a href="<?php echo $siteUrl.'/store/Products/index';?>">
						<i class="fa fa-briefcase"></i>
						<span class="title">Manage Menu</span>
					</a>
				</li><?php
				
				$ordersAction = array('store_orderIndex', 'store_orderView'); ?>

				<li class="<?php if(strtolower($controllerName) == 'orders' && in_array($actionName, $ordersAction)): ?>active<?php endif; ?>">
					<a href="<?php echo $siteUrl.'/store/Orders/orderIndex';?>">
						<i class="fa fa-shopping-cart"></i>
						<span class="title">Order</span>
					</a>
				</li> <?php

				/*//if($loggedUser['Store']['collection'] == 'Yes'){ ?>
					<li class="<?php if(strtolower($controllerName) == 'orders' && $actionName == 'store_collectionOrder'): ?>active<?php endif; ?>">

						<a href="<?php echo $siteUrl.'/store/Orders/collectionOrder';?>">
							<i class="fa fa-truck"></i>
							<span class="title">Pickup Order</span>
						</a>
					</li> <?php
				//}*/

				$invoiceAction = array('store_index', 'store_invoiceDetail');
				$reportsAction = array('store_index', 'store_reportOrderView'); ?>
				
				<li class="<?php if(strtolower($controllerName) == 'invoices' && in_array($actionName, $invoiceAction)): ?>active<?php endif; ?>">
					<a href="<?php echo $siteUrl.'/store/Invoices/index';?>">
						<i class="fa fa-list-alt"></i>
						<span class="title">Invoice</span>
					</a>
				</li>
				<li class="<?php if(strtolower($controllerName) == 'orders' && in_array($actionName, $reportsAction)): ?>active<?php endif; ?>">
					<a href="<?php echo $siteUrl.'/store/Orders/index'; ?>">
						<i class="fa fa-file-text-o"></i>
						<span class="title">Report</span>
					</a>
				</li>
				<li class="<?php if(strtolower($controllerName) == 'deals' && in_array($actionName, $deals)): ?>active<?php endif; ?>">
					<a href="<?php echo $siteUrl.'/store/deals/index';?>">
						<i class="fa fa-tags"></i>
						<span class="title">Deals</span>
					</a>
				</li>
				<li class="<?php if(strtolower($controllerName) == 'storeoffers' && in_array($actionName, $deals)): ?>active<?php endif; ?>">
					<a href="<?php echo $siteUrl.'/store/Storeoffers/index';?>">
						<i class="fa fa-money"></i>
						<span class="title">Offers</span>
					</a>
				</li>
				<li class="<?php if(strtolower($controllerName) == 'vouchers' && in_array($actionName, $deals)): ?>active<?php endif; ?>">
					<a href="<?php echo $siteUrl.'/store/vouchers/index';?>">
						<i class="fa fa-gift"></i>
						<span class="title">Voucher</span>
					</a>
				</li>
				
				<li class="<?php if(strtolower($controllerName) == 'bookatables'): ?>active<?php endif; ?>">
					<a href="<?php echo $siteUrl.'/store/bookaTables/index';?>">
						<i class="fa fa-gift"></i>
						<span class="title">Book Table</span>
					</a>
				</li>


			</ul>
			<!-- END SIDEBAR MENU -->
		</div>
	</div>
</div>