<div class="contain">
	<div class="contain">
		<h3 class="page-title">Affecter commande</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i><a href="<?php echo $siteUrl.'/admin/Dashboards/index'; ?>">
					Accueil</a>
				<i class="fa fa-angle-right"></i></li>
				<li><a href="<?php echo $siteUrl.'/admin/orders/order'; ?>">
					commande</a>
					<i class="fa fa-angle-right"></i></li>
				<li><a href="#">Affecter commande</a></li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box grey-cascade">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-globe"></i>Affecter commande
						</div>
						<div class="tools">									
						</div>
					</div>
					<div class="portlet-body">
						<div class="table-toolbar">
						</div>
						<table class="table table-striped table-bordered table-hover" id="sample_12">
							<thead>
								<tr>
									<th> S.No </th>
									<th>Driver</th>
									<th>Véhicule</th>
									<th>Statut</th>
									<th class="sorting_asc">Distance</th>
									<th>ETA</th>
									<th>Assign</th>
								</tr>
							</thead>
							<tbody> <?php
								foreach ($availDrivers as $key => $value) { ?>

									<tr class="odd gradeX">
										<td><?php echo $key+1; ?></td>
										<td> <?php echo $value['Driver']['driver_name']. ' - '.$value['Driver']['driver_phone']; ?> </td>
										<td> <?php echo $value['Driver']['vehicle_name']; ?> </td>
										<td><a class="buttonStatus" href="javascript:void(0);">
											<i class="fa fa-check"></i></a></td>
										<td> <?php echo (!empty($value['Driver']['distance'])) ? 
														$value['Driver']['distance'] : 'Hors zone'; ?> </td>
										<td> <?php echo $value['Driver']['reachtime']; ?> </td>
										<td align="center">

											<a class="buttonAssign" href="javascript:void(0);" id="assign<?php echo $value['Driver']['id'];?>" onclick="return assignOrder(<?php echo $orderId.','.$value['Driver']['id']; ?>);"><i class="fa fa-car"></i> Affecter commande</a>
											
											<a class="btn btn-info" id="waiting<?php echo $value['Driver']['id'];?>" style="display: none;">En attente d'acceptation</a>
											
										</td>
									</tr> <?php
								} ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>