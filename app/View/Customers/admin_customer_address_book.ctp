<div class="contain">
	<div class="contain">
		<h3 class="page-title">Address Book</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Accueil</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Customer</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Address Book</a>
				</li>
			</ul>
		</div>
		
		<div class="row">
				<div class="col-md-12">
					<!-- BEGIN PORTLET-->
					<div class="portlet box blue-hoki">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-user"></i> Adress Book
							</div>
							<div class="tools">
								
							</div>
						</div>
						<div class="portlet-body form"><?php 
								echo $this->Form->create('CustomerAddressBooks',array('class'=>"form-horizontal"));
									?>			
								<div class="form-body">
									<div class="form-group">
										<label class="col-md-3 control-label">Adress Title <span class="star">*</span></label>
										<div class="col-md-6 col-lg-4"><?php
													echo $this->Form->input('address_title',
															array('class'=>'form-control',
																	'label'=>false)); ?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">Phone Number <span class="star">*</span></label>
										<div class="col-md-6 col-lg-4"><?php
													echo $this->Form->input('address_phone',
															array('class'=>'form-control',
																	'label'=>false)); ?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">Apt/Suite/Building</label>
										<div class="col-md-6 col-lg-4"><?php
													echo $this->Form->input('landmark',
															array('class'=>'form-control',
																	'label'=>false)); ?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">Street Address <span class="star">*</span></label>
										<div class="col-md-6 col-lg-4"><?php
													echo $this->Form->input('address',
															array('class'=>'form-control',
																	'label'=>false)); ?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">City <span class="star">*</span></label>
										<div class="col-md-6 col-lg-4"><?php
													echo $this->Form->input('city_id',
														array('type'  => 'select',
															  'class' => 'form-control',
															  'options'=> array($city_list),
															  'empty' => 'Area/Zipcode',
											 				  'label'=> false)); ?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">Area/Zipcode <span class="star">*</span></label>
										<div class="col-md-6 col-lg-4"><?php
												echo $this->Form->input('location_id',
														array('type'  => 'select',
															  'class' => 'form-control',
															  'options'=> array($location_list),
															  'empty' => 'Area/Zipcode',
											 				  'label'=> false)); 
											 	echo $this->Form->hidden('id');?>
										</div>
										</div>
									</div>
								</div>
								<div class="form-actions">
									<div class="row">
										<div class="col-md-offset-3 col-md-9"> <?php
												echo $this->Form->button(__('Save'),
																array('class'=>'btn purple')); ?> <?php
												echo $this->Html->link('Cancel',
																array('action' => 'index'),
																array('Class'=>'btn default')); ?>
											</div>
									</div>
								</div>
							<?php echo $this->Form->end();?>
						</div>
					</div>
					<!-- END PORTLET-->
				</div>
			</div>
	</div>
</div>
