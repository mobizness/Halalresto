
<div class="contain">
	<div class="contain">
		<h3 class="page-title">City</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/admin/dashboards/index'; ?>">Accueil</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Location</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Manage City</a>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box grey-cascade">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-globe"></i>Managed City
						</div>
						<div class="tools">
							
						</div>
					</div>
					<div class="portlet-body"><?php
						echo $this->Form->create('Commons', array('class'=>'form-horizontal',
							'controller'=>'Commons','action'=>'multipleSelect')); ?>
						<div class="table-toolbar">
							
								<div id="send" style="display:none" class="pull-left">
									<div class="pull-right" id="addnewbutton_toggle"> <?php
										echo $this->Form->hidden("Model",array('value'=>'City',
											'name'=>'data[name]'));
										if (!empty($city_detail)) {
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
								
									<div class="btn-group pull-right"><?php 
										echo $this->Html->link('Add New <i class="fa fa-plus"></i>',
																array('controller'=>'Cities',
																	   'action'=>'add'),
																array('class'=>'btn green',
																		'escape'=>false)
															  );
										?>
									</div>
								
						</div>
						<table class="table table-striped table-bordered table-hover checktable" id="sample_12">
							<thead>
								<tr>
									<th class="table-checkbox"><input type="checkbox" class="group-checkable test1" data-set="#sample_1 .checkboxes" /></th>
									<th>City Name</th>
									<th>State Name</th>
									<th>City Id</th>
									<th>Added Date</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<tr class="odd gradeX"><?php

										foreach ($city_detail as $key => $value) {	?>
									<td> <?php
										echo $this->Form->checkbox($value['City']['id'],
											array('class'=>'checkboxes test' ,
												'label'=>false,
												'hiddenField'=>false,
												'value'=> $value['City']['id'])); ?> </td>
									<td><?php
									echo $this->Html->link($value['City']['city_name'],
																array('controller'=>'Locations',
																	   'action'=>'index',
																	   $value['City']['id'])
															  );
									?></td>
									<td><?php echo $value['State']['state_name']; ?></td>
									<td><?php echo $value['City']['id']; ?></td>
									<td><?php echo $value['City']['updated']; ?></td>
									<td align="center"> <?php
										if($value['City']['status'] == 0) {?>
											<a title="Deactive" class="buttonStatus red_bck" href="javascript:void(0);"
											   onclick="statusChange(<?php echo $value['City']['id'];?>,'City');">
												<i class="fa fa-times"></i><!-- deactive --></a>
										<?php } else if($value['City']['status'] == 1){
											?>
											<a title="active" class="buttonStatus" href="javascript:void(0);"
											   onclick="statusChange(<?php echo $value['City']['id'];?>,'City');">
												<i class="fa fa-check"></i></a>
										<?php } else {?>
											<a title="Pending" class="buttonStatus yellow_bck" href="javascript:void(0);"
											   onclick="statusChange(<?php echo $value['City']['id'];?>,'City');">
												<i class="fa fa-exclamation"></i><!-- Pending --></a>
										<?php }?>
									</td>
									<td align="center"><?php
										echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',
																array('controller'=>'Cities',
																	   'action'=>'edit',
																		$value['City']['id']),
																array('class'=>'buttonEdit',
																		'escape'=>false)
															  );?>
										<a class="buttonAction" href="javascript:void(0);"
                                        onclick="deleteprocess(<?php echo $value['City']['id'];?>,'State');" ><i class="fa fa-trash-o"></i></a>
									</td>
								</tr><?php } ?>
							</tbody>
						</table><?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

