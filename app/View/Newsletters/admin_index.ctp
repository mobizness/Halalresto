
<div class="contain">
	<div class="contain">
		<h3 class="page-title">Manage Newsletter</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Home</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Manage Newsletter</a>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box grey-cascade">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-globe"></i>Managed Newsletter
						</div>
						<div class="tools">
							
						</div>
					</div>
					<div class="portlet-body"> <?php
						echo $this->Form->create('Newsletter', array('class'=>'form-horizontal', 'action' =>'sendselectcustomer')); ?>
							<div class="table-toolbar">
								<div class="row">
									<div class="col-md-12">
										<div id="" class="btn-group pull-right"> <?php
											if (!empty($customer_list)) {

												echo $this->Html->link('Send mail to all users',
																		array('action' => 'sendall'),
																		array('Class'=>'btn blue'));
												echo $this->Form->button(__('Send mail to Selected users'),
																		array('class'=>'btn green',
																			  'id' => 'send',
																			  'style' => 'display:none'));

											} ?>
										</div>
									</div>
								</div>
							</div>
							<table class="table table-striped table-bordered table-hover checktable" id="sample_12">
								<thead>
									<tr>
										<th class="table-checkbox no-sort"><input id="test1" type="checkbox" class="group-checkable test1" data-set="#sample_1 .checkboxes"/></th>
										<th>User Name</th>
										<th>Email</th>
										<th>Contact Number</th>

									</tr>
								</thead>
								<tbody><?php
	                                foreach($customer_list as $key => $value) { ?>
								<tr class="odd gradeX" id="record<?php echo $value['Customer']['id']; ?>">

									<td> <?php
										echo $this->Form->checkbox($value['User']['id'],
											array('class' => 'checkboxes test',
												'name' => 'data[Newsletter][email][]',
												'label' => false,
												'hiddenField' => false,
												'id' => 'test',
												'value' => $value['Customer']['customer_email'])); ?> </td>
									<td><?php echo $value['Customer']['first_name']; ?></td>
									<td><?php echo $value['Customer']['customer_email']; ?></td>
									<td><?php echo $value['Customer']['customer_phone']; ?></td>

								</tr> <?php
									 }?>
								</tbody>
							</table> <?php
						echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
