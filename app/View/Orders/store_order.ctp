<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleApiKey; ?>&sensor=false"> </script>
<div class="page-content-wrapper">
	<div class="page-content">
		<h3 class="page-title">Gestion commande</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i><a href="<?php echo $siteUrl.'/store/dashboards/index';?>">Accueil</a><i class="fa fa-angle-right"></i></li>

				<li><a href="#">Gestion commande</a></li>
			</ul>
		</div>
		<div class="alert alert-success" id="orderMessage" style="display:none;"></div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box grey-cascade">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-globe"></i>Gestion commande
						</div>
						<div class="tools">									
						</div>
					</div>
					<div class="portlet-body">
						<div class="table-toolbar">
							<!--<div class="row">
								<div class="col-md-12">
									<div class="btn-group pull-right">
										<a href="addnewUser.html" id="sample_editable_1_new" class="btn green">
											Add New <i class="fa fa-plus"></i>
										</a>
									</div>
								</div>
							</div>-->
						</div>
						<table class="table table-striped table-bordered table-hover checktable" id="sample_12">
							<thead>
								<tr>
									<th class="no-sort">S.No</th>
									<th>Date et Heure</th>
									<th class="maxcolumn no-sort">Informations sur la commande</th>
									<th>Prix</th>
									<th>Nom Coursier/ID </th>
									<th class="no-sort">Statut</th>
									<th class="no-sort" width="10%">Action</th>
								</tr>
							</thead>
							<tbody><?php 
	                            
	                            	$count = 1;
	                                foreach($orderList as $key => $value) {  ?>
										<tr id="orderDetails<?php echo $value['Order']['id']; ?>" class="odd gradeX">
											<td> <?php echo $count ;?></td>
											<td> <?php echo $value['Order']['created'];?></td>
											<td> <?php    
												echo $this->Html->link($value['Order']['ref_number'],
																	array('action' => 'orderView',
																			$value['Order']['id'],
																			'dispatch'),
																	array('class' => 'no-padding blue bold'));  ?>
												<br/> <?php
												if($siteSetting['Sitesetting']['address_mode'] != 'Google') {
													$storeAddress = $value['Store']['street_address'] . ', ' .
															$location[$value['Store']['store_zip']] . ', ' .
															$city[$value['Store']['store_city']] . ', ' .
															$states[$value['Store']['store_state']];

													$customerAddress = $value['Order']['address'] . ', ' .
															$value['Order']['location_name'] . ', ' .
															$value['Order']['city_name'] . ', ' .
															$value['Order']['state_name'];
												} else {
													$storeAddress = $value['Store']['address'];
													$customerAddress = $value['Order']['google_address'];
												} ?>

												<span class="address orange-location"><?php
													echo stripslashes($storeAddress); ?></span></br></br>
												<span class="address blue-location"><?php 
													echo stripslashes($customerAddress); ?></span>
											</td>
											<td> <?php 
												echo html_entity_decode($this->Number->currency($value['Order']['order_grand_total'], $siteCurrency)); ?></td>

											<td id="driver<?php echo $value['Order']['id']; ?>"> <?php
												if ($value['Driver']['driver_name']): ?>
													<span class="tddriver"> <?php 
														echo $value['Driver']['driver_name']; ?> 
													</span> <?php
												else:
													?> <span class="tdnotassign"> <?php echo 'Pas encore affecté'; ?> </span> <?php
												endif; ?>
											</td>

											<td align="center" id="status<?php echo $value['Order']['id']; ?>" class="order_status"><span><?php
												echo ($value['Order']['status'] == 'Collected') ?
													'Picked up' :  $value['Order']['status']; ?></span></td>

											<td align="center"><?php
												/*echo $this->Html->link('<i class="fa fa-search"></i>',
																		array('controller'=>'Orders',
																			   'action'=>'orderView',
																				$value['Order']['id']),
																		array('class'=>'buttonEdit',
																				'escape'=>false));*/ ?>

												<!-- <a class="buttonAction" href="javascript:void(0);"
		                                        onclick="deleteOrder(<?php echo $value['Order']['id'];?>);" ><i class="fa fa-trash-o"></i></a> -->


												<a id="icon<?php echo $value['Order']['id'];?>" class="<?php echo (!$value['Driver']['id']) ? 'buttonEdit' : ''; ?>" href="<?php echo $siteUrl;?>/store/drivers/availDriver/<?php echo $value['Order']['id']; ?>"> <?php  echo (!$value['Driver']['id']) ? '<i class="fa fa-car"></i>':''; ?></a>

												<span id="orderDisclaim<?php echo $value['Order']['id'] ?>"> <?php 
													if ($value['Order']['status'] != 'Accepted' && $value['Order']['status'] != 'Delivered') { ?>
						                                <a class="view_Order buttonEdit" href="javascript:void(0);" onclick="return disclaimOrder(<?php echo $value['Order']['id']; ?>);"><i class="fa fa-ban"></i></a> <?php
						                            } ?>
						                        </span>

												<a class="track_order buttonEdit" href="javascript:void(0);" data-target="#trackid" class=""  data-toggle="modal" onclick="return viewTrack(<?php echo $value['Order']['id'];?>);"><i class="fa fa-search"></i></a>
											</td>
										</tr><?php $count++;
									}?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="trackid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
				<h4 class="modal-title center" id="myModalLabel">Map</h4>
			</div>
			<div class="modal-body" >
				<div id="trackingDistance"> </div>
				<input type="hidden" id="trackOrderId" value=""/>
				<div id="initialmap">
					<?php 
	                  //echo $this->GoogleMap->map(); 
	                ?>
				</div>
				<div id="TrackingMap"></div>
			</div>
		</div>
	</div>
</div>


<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	trackings();
	updateOrderMap();
});

</script>