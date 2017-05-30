<div class="contain">
	<div class="contain">
		<h3 class="page-title">Dispatch</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/admin/Dashboards/index'; ?>">Home</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Dispatch</a>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box grey-cascade">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-globe"></i>Manage Driver
						</div>
						<div class="tools">
							
						</div>
					</div>
					<div class="portlet-body"><?php
						echo $this->Form->create('Commons', array('class'=>'form-horizontal',
																	'controller'=>'Commons',
																	'action'=>'multipleSelect')); ?>
						<div class="table-toolbar">
							<div id="send" style="display:none" class="pull-left">
								<div class="pull-right" id="addnewbutton_toggle"> <?php
									echo $this->Form->hidden("Model",array('value'=>'Driver',
										'name'=>'data[name]'));
									if (!empty($drivers)) {
										echo $this->Form->submit(__('Active'),
											array('class'=>'btn btn-success btn-sm',
												'name'=> 'actions',
												'div'=>false,
												'onclick'=>'return recorddelete(this);'
											)); ?> <?php
										echo $this->Form->submit(__('Deactive'),
											array('class'=>'btn btn-warning btn-sm',
												'name'=> 'actions',
												'div'=>false,
												'onclick'=>'return recorddelete(this);'
											)); ?> <?php
										echo $this->Form->submit(__('Delete'),
											array('Class'=>'btn btn-danger btn-sm',
												'name'=> 'actions',
												'div'=>false,
												'onclick'=>'return recorddelete(this);'
											));
									} ?>
								</div>
							</div>
							
							<div class="btn-group pull-right"> <?php 
								echo $this->Html->link('Add Driver <i class="fa fa-plus"></i>',
															array('controller'=>'drivers',
																   'action'=>'add'),
															array('class'=>'btn green',
																	'escape'=>false)); ?>
							</div>
						</div>
						<table class="table table-striped table-bordered table-hover checktable" id="sample_12">
							<thead>
								<tr>
									<th class="table-checkbox"><input type="checkbox" class="group-checkable test1" data-set="#sample_1 .checkboxes" /></th>
									<th>Driver Id</th>
									<th>Driver Name</th>
									<th>Status</th>
									<th>Vehicle</th>
									<th>Added Date</th>
									<th>Phone Number</th>
									<th>Action</th>
									<th>Billing</th>

								</tr>
							</thead>
							<tbody> <?php
								foreach ($drivers as $key => $value) {  ?>
									<tr class="odd gradeX" id="record<?php echo $value['Driver']['id'];?>">
										<td> <?php
											echo $this->Form->checkbox($value['Driver']['id'],
												array('class'=>'checkboxes test' ,
													'label'=>false,
													'hiddenField'=>false,
													'value'=> $value['Driver']['id'])); ?> </td>
										<td><?php
											echo $this->Html->link($value['Driver']['driver_phone'],
																	array('controller'=>'drivers',
																		   'action'=>'edit',
																			$value['Driver']['id']),
																	array('escape'=>false));?>

										<td> <?php echo $value['Driver']['driver_name']; ?> </td>
                                        <td align="center"> <?php
                                            if($value['Driver']['status'] == 'Deactive') {?>
                                                    <a title="deactive" class="buttonStatus red_bck" href="javascript:void(0);"
                                                    onclick="statusChange(<?php echo $value['Driver']['id'];?>,'Driver');">
                                                <i class="fa fa-times"></i></a> <?php 
                                           	} else if($value['Driver']['status'] == 'Active') { ?>
                                                    <a title="active" class="buttonStatus" href="javascript:void(0);"
                                                    onclick="statusChange(<?php echo $value['Driver']['id'];?>,'Driver');">
                                                <i class="fa fa-check"></i></a> <?php 
                                            } ?>
                                        </td>
										<td> <?php

											if (!empty($value['Vehicle']['vehicle_name'])) {

												echo $this->Html->link($value['Vehicle']['vehicle_name'],
															array('controller'=>'drivers',
																   'action'=>'editvehicle',
																   $value['Driver']['id'],
																	$value['Vehicle']['id']),
															array('escape'=>false));
											} else {

												echo $this->Html->link('Add Vehicle',
															array('controller'=>'drivers',
																   'action'=>'addvehicle',
																	$value['Driver']['id']),
															array('escape'=>false));
											} ?>
										</td>
										<td> <?php echo $value['Driver']['created']; ?> </td>
										<td> <?php echo $value['Driver']['driver_phone']; ?> </td>
										<td align="center"> <?php

											if ($value['Driver']['is_logged'] == 1) { ?>
												<a data-toggle="tooltip"  data-placement="bottom" title="Logout" class="statusLog action-image" href="javascript:void(0);" id="<?php echo 'log'.$value['Driver']['id'];?>"><img src="<?php echo $siteUrl;?>/images/Logout.png" title="logout"/></a> <?php 
											} else { ?>

												<a class="buttonAction" href="javascript:void(0);"
                                        		onclick="deleteprocess(<?php echo $value['Driver']['id'];?>,'Driver');" ><i class="fa fa-trash-o"></i></a><?php
											} ?>

										</td>
										<td><?php
											echo $this->Html->link('Billing Detail',
												array('controller'=>'drivers',
													'action'=>'billingDetail',
													$value['Driver']['id']),
												array('escape'=>false));?></td>
									</tr> <?php
								} ?>
							</tbody>
						</table><?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>