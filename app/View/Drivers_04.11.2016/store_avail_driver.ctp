<div class="page-content-wrapper">
	<div class="page-content">
		<h3 class="page-title">Assign Order</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i>
						<a href="<?php echo $siteUrl.'/store/Dashboards/index'; ?>">Home</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
						<a href="<?php echo $siteUrl.'/store/orders/order'; ?>">Order</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li><a href="#">Assign Order</a></li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box grey-cascade">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-globe"></i>Assign Order
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
									<th>Vehicle</th>
									<th>Status</th>
									<th>Distance</th>
									<th>ETA</th>
									<th>Assign</th>
								</tr>
							</thead>
							<tbody> <?php
								foreach ($availDrivers as $key => $value) { ?>

									<tr class="odd gradeX">
										<td><?php echo $key+1; ?></td>
										<td> <?php echo $value['Driver']['driver_name']. ' - '.$value['Driver']['driver_phone']; ?> </td>
										<td> <?php echo $value['Driver']['vehicle_name']; ?>  </td>
										<td><a class="buttonStatus" href="javascript:void(0);"><i class="fa fa-check"></i></a></td>
										<td> <?php echo $value['Driver']['distance']; ?> </td>
										<td> <?php echo $value['Driver']['reachtime']; ?> </td>
										<td align="center">

											<a href="javascript:void(0);" class="buttonAssign" id="assign<?php echo $value['Driver']['id'];?>" onclick="return assignOrder(<?php echo $orderId.','.$value['Driver']['id']; ?>);"><i class="fa fa-car"></i> Assign Order</a>
											
											<a class="btn btn-info" id="waiting<?php echo $value['Driver']['id'];?>" style="display: none;">Waiting for Acceptance</a>
											
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