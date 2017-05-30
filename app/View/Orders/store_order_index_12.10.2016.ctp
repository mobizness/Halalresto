<div class="page-content-wrapper">
	<div class="page-content">
		<h3 class="page-title">Manage Order</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i><a href="<?php echo $siteUrl.'/store/dashboards/index';?>">Home</a><i class="fa fa-angle-right"></i></li>

				<li><a href="#">Manage Order</a></li>
			</ul>
		</div>
		<div class="alert alert-success" id="orderMessage" style="display:none;"></div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box grey-cascade">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-globe"></i>Manage Order
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
									<th>S.No</th>
									<th>Order Id</th>
									<th>Order Type</th>
									<th>Payment Type</th>
									<th>Price</th>
									<th>Order Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody><?php
                            
                            	$count = 1;
                                foreach($order_list as $key => $value) { ?>
									<tr id="orderList_<?php echo $value['Order']['id']; ?>" class="odd gradeX">
										<td><?php echo $count ;
											echo $this->Form->input('orderType_'.$value['Order']['id'],
															array('type'=>'hidden',
																'value' => $value['Order']['order_type'])); ?></td>
										<td><?php echo $value['Order']['ref_number'];?></td>
										<td><?php echo ($value['Order']['order_type'] == 'Collection')
											? 'Pickup' : $value['Order']['order_type']; ?></td>
										<td><?php echo $value['Order']['payment_type'];?></td>
										<td><?php echo $value['Order']['order_grand_total'];?></td>

										<td id="statusOrder_<?php echo $value['Order']['id']; ?>" align="center"> <?php
											$status = ($value['Order']['status'] == 'Pending') ? $pendingStatus : $acceptStatus;
											echo $this->Form->input('orderStatus_'.$value['Order']['id'],
												array('type'=>'select',
													 'class'=>'form-control',
													 'options'=> array($status),
													 'onchange' => "orderStatus(".$value['Order']['id'].");",
													 'label'=> false,
													 'value' => $value['Order']['status'])); ?>

											<div id="reason_<?php echo $value['Order']['id']; ?>"></div> </td>

										<td align="center"><?php
											echo $this->Html->link('<i class="fa fa-search"></i>',
																	array('controller'=>'Orders',
																		   'action'=>'orderView',
																			$value['Order']['id']),
																	array('class'=>'buttonEdit',
																			'escape'=>false));?>

											<a class="buttonAction" href="javascript:void(0);"
	                                        onclick="deleteOrder(<?php echo $value['Order']['id'];?>,'Order');" ><i class="fa fa-trash-o"></i></a>
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