<div class="page-content-wrapper">
	<div class="page-content">
		<h3 class="page-title">Gestion Réserver une table</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i><a href="<?php echo $siteUrl.'/store/dashboards/index';?>">Accueil</a><i class="fa fa-angle-right"></i></li>

				<li><a href="#">Gestion Réserver une table</a></li>
			</ul>
		</div>
		<div class="alert alert-success" id="orderMessage" style="display:none;"> 
			Successfully book a table status changed</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box grey-cascade">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-globe"></i>Gestion Réserver une table
						</div>
						<div class="tools">									
						</div>
					</div>
					<div class="portlet-body">
						<div class="table-toolbar">
						</div>
						<table class="table table-striped table-bordered table-hover checktable" id="sample_12">
							<thead>
								<tr>
									<th>N°</th>
									<th>Réserver une table Id</th>
									<th>Nom Client</th>
									<th>Date Réservation</th>
									<th>Heure Réservation</th>
									<th>Téléphone</th>
									<th>Statut</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody><?php
                            
                            	$count = 1;
                                foreach($bookaTables as $key => $value) { ?>
									<tr id="orderList_<?php echo $value['BookaTable']['id']; ?>" class="odd gradeX">
										<td><?php echo $count; ?></td>
										<td><?php echo $value['BookaTable']['booking_id']; ?>	 </td>
										<td><?php echo $value['BookaTable']['customer_name']; ?> </td>
										<td><?php echo date("Y/m/d", strtotime($value['BookaTable']['booking_date']));?></td>
										<td><?php echo $value['BookaTable']['booking_time']; ?> </td>
										<td><?php echo $value['BookaTable']['booking_phone']; ?> </td>

										<td id="bookTableStatus_<?php echo $value['BookaTable']['id']; ?>" align="center"> <?php
											if ($value['BookaTable']['status'] == 'Pending') {

												echo $this->Form->input('bookStatus_'.$value['BookaTable']['id'],
																array('type'=>'select',
																	 'class'=>'form-control',
																	 'options'=> array($status),
																	 'onchange' => "bookaTableStatus(".$value['BookaTable']['id'].");",
																	 'label'=> false,
																	 'value' => $value['BookaTable']['status'])); ?>

												<div id="reason_<?php echo $value['BookaTable']['id']; ?>"></div> <?php
											} else {
												echo ($value['BookaTable']['status'] == 'Approved') ? 'Approuvé' : 'Annulé';
											} ?> </td>

										<td align="center">
											<a class="track_order buttonEdit" href="javascript:void(0);" data-target="#trackid" class=""  data-toggle="modal" onclick="return viewBookTableDetails(<?php echo $value['BookaTable']['id'];?>);"><i class="fa fa-search"></i></a>
										</td>
									</tr><?php $count++;
								} ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="trackid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
				<h4 class="modal-title center" id="myModalLabel">Réserver une table Details</h4>
			</div>
			<div class="modal-body" >
				<div id="bookaTable">

				</div>
			</div>
		</div>
	</div>
</div>