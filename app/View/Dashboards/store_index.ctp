<div class="page-content-wrapper">
	<div class="page-content">			
		<h3 class="page-title">Tableau de bord</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/store/dashboards/index'; ?>">Accueil</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="javascript:void(0);">Tableau de bord</a>
				</li>
			</ul>
			<!--<div class="page-toolbar">
				<div id="dashboard-report-range" class="tooltips btn btn-fit-height btn-sm green-haze btn-dashboard-daterange" data-container="body" data-placement="left" data-original-title="Change dashboard date range">
					<i class="icon-calendar"></i>
					&nbsp;&nbsp; <i class="fa fa-angle-down"></i>
				</div>
			</div>-->
		</div>
		<div class="row">
			<div class="col-xs-12">	
				<div class="outer_border">
					<ul class="nav nav-tabs bg_grey boder_align">
						<li class="active"><a data-toggle="tab" href="#today">Aujourd’hui<br></a>
						</li>
						<li>
							<a data-toggle="tab" href="#yesterday">hier</a>
						</li>
						<li>
							<a data-toggle="tab" href="#week">Les 7 derniers jours</a>
						</li>
						<li>
							<a data-toggle="tab" href="#month">Les 30 derniers jours</a>
						</li>
					</ul>
					<div class="col-sm-offset-1 col-sm-10 margin-top-20">

						<div class="tab-content">
							<div id="today" class="tab-pane fade in active">
								<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
									<a class="dashboard-stat dashboard-stat-light blue-soft" href="#">
									<div class="visual">
										<i class="fa fa-comments"></i>
									</div>
									<div class="details">
										<div class="number"><?php
											echo $this->Number->currency($dasboard_value['todaySubTotal'], $siteCurrency);?>
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
											echo $dasboard_value['todayOrderCount']; ?>
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
											echo $this->Number->currency($dasboard_value['yesterdaySubTotal'], $siteCurrency); ?>
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
											echo $dasboard_value['yesterdayCustomer'];
										?>
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
											echo $this->Number->currency($dasboard_value['weekSubTotal'], $siteCurrency); ?>
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
											echo $this->Number->currency($dasboard_value['monthSubTotal'], $siteCurrency);?>
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
										<i class="fa fa-shopping-cart"></i>
									</div>
									<div class="details">
										<div class="number"><?php
											echo $dasboard_value['monthOrderCount']; ?>
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
				</div>
			</div>
		</div>
	</div>
</div>