<div class="contain">
	<div class="contain">
		<h3 class="page-title">Reporting</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/admin/dashboards/index'; ?>">Accueil</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Reporting</a>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box grey-cascade">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-globe"></i>Reporting
						</div>
						<div class="tools">
							
						</div>
					</div>
					<div class="portlet-body">
						<div class="table-toolbar">
							<span class="col-md-4 pull-right no-padding"> 
               					<label class="control-label col-sm-4">Coursiers</label>
								<span class="col-md-9"> <?php 
									echo $this->Form->input('Store.StoreDriver',
											array('type'  => 'select',
												  'class' => 'form-control',
												  'options'=> array($driverList),
												  'empty' => "Coursiers/Autres",
												  'id' => 'StoreDriver', 
												  'onchange' => 'storeOrders();',
								 				  'label'=> false,
								 				  'div' => false)); ?>
								</span>
							</span>


							<span class="col-md-4 pull-right no-padding"> 
               					<label class="control-label col-sm-4">Gamme</label>
								<span class="col-md-9"> <?php 
									echo $this->Form->input('Store.StoreRange',
											array('type'  => 'select',
												  'class' => 'form-control',
												  'options'=> array($range),
												  'id' => 'StoreRange', 
												  'onchange' => 'storeOrders();',
								 				  'label'=> false,
								 				  'div' => false)); ?>
								</span>
							
							</span>

							<span class="col-md-4 pull-right no-padding"> 
               					<label class="control-label col-sm-4">Restaurant</label>
								<span class="col-md-9"> <?php 
									echo $this->Form->input('Store.StoreOrder',
											array('type'  => 'select',
												  'class' => 'form-control',
												  'options'=> array($stores),
												  'empty' => 'Tous les restaurants',
												  'id' => 'StoreOrder', 
												  'onchange' => 'storeOrders();',
								 				  'label'=> false,
								 				  'div' => false));
									?>
									<label class="error" id="StoreOrderError" generated="true" for="ProductStoreId"></label>
								</span>
							
							</span>



						</div>
						<table class="table table-striped table-bordered table-hover checktable" id="sample_12">
							<thead>
								<tr>
									<th class="no-sort">S.No</th>
									<th>N° Commande</th>
									<th>Nom Client</th>
									<th>Prix</th>
									<th>Moyen de paiement</th>
									<th>Statut de la Commande</th>
									<th>Date de la Commande</th>
									<th>Date de Livraison</th>
									<th>Nom coursier / Phone</th>
									<th class="no-sort">Track</th>
								</tr>
							</thead>
							<tbody><?php 
								$count = 1;
								foreach ($order_list as $key => $value) { ?>
									<tr class="odd gradeX">
										<td><?php echo $count;?></td>
										<td><?php
											echo $this->Html->link($value['Order']['ref_number'],
																	array('controller'=>'Orders',
																		   'action'=>'reportOrderView',
																		   $value['Order']['id'])
																  );?></td>
										<td><?php echo $value['Order']['customer_name'];?></td>
										<td><?php echo $value['Order']['order_grand_total'];?></td>
										<td><?php echo ($value['Order']['payment_type'] == 'cod') ? 'paiement espèce': (($value['Order']['payment_type'] == 'Card') ? 'CB': $value['Order']['payment_type']);?></td>
										<td><?php echo ($value['Order']['status'] == 'Failed') ? 'Annulé' : (($value['Order']['status'] == 'Delivered') ? 'Livré' : $value['Order']['status']);?></td>
										<td><?php echo date("Y/m/d H:i:s", strtotime($value['Order']['created']));?></td>
										<td><?php echo date("Y/m/d", strtotime($value['Order']['delivery_date']));?></td>
										<td><?php echo $value['Driver']['driver_name'].' / '.$value['Driver']['driver_phone']; ?></td>
										<td> <?php
											if ($value['Order']['order_type'] != 'Collection') { ?>
												<a class="buttonAssign" href="javascript:void(0);"  onclick="return trackOrder(<?php echo $value['Order']['id']; ?>);"><i class="fa fa-search"></i></a> <?php
											} ?></td>
									</tr><?php $count ++;
								} ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="reportpopup" class="modal fade" >
  	<div role="document" class="modal-dialog modal-lg">
	    <div class="modal-content">
	      	<div class="modal-header">
	       		<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
	        	<h4 class="modal-title">Track Order</h4>
	      	</div>
	      	<div id="trackingContent" class="modal-body"> 
	    	</div>
		</div>
	</div>
</div>