<div class="contain">
	<div class="contain">
		<h3 class="page-title">Editer Book</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Home</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="<?php echo $siteUrl.'/admin/Customers/index';?>">Customer</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#"> Editer Adress Book</a>
				</li>
			</ul>
		</div>
		
		<div class="row">
				<div class="col-md-12">
					<!-- BEGIN PORTLET-->
					<div class="portlet box blue-hoki">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-user"></i> Editer Adress Book
							</div>
							<div class="tools">
								
							</div>
						</div>
						<div class="portlet-body form"> <?php
                    echo $this->Form->create("CustomerAddressBook",
                                                array("id"=>'EditCustomerAddressBook',
                                                      "url"=>array("controller"=>'Customers','action'=>'editAddressBook')));?>
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
										<label class="col-md-3 control-label">Street Address <span class="star">*</span></label>
										<div class="col-md-6 col-lg-4"><?php
													echo $this->Form->input('address',
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
										<label class="col-md-3 control-label">State <span class="star">*</span></label>
										<div class="col-md-6 col-lg-4"><?php
													echo $this->Form->input('state_id',
														array('type'  => 'select',
															  'class' => 'form-control',
															  'options'=> array($state_list),
                                                              'onchange' => 'cityFillters();',
															  'empty' => 'selete your state',
											 				  'label'=> false)); ?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">City <span class="star">*</span></label>
										<div class="col-md-6 col-lg-4"><?php
													echo $this->Form->input('city_id',
														array('type'  => 'select',
															  'class' => 'form-control',
															  'options'=> array($city_list),
                                                              'onchange' => 'locationFillters();',
															  'empty' => 'selete your city',
											 				  'label'=> false)); ?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">Area <span class="star">*</span></label>
										<div class="col-md-6 col-lg-4"><?php
												echo $this->Form->input('location_id',
														array('type'  => 'select',
															  'class' => 'form-control',
															  'options'=> array($location_list),
															  'empty' => 'selete your Area/Zip',
											 				  'label'=> false)); 
                                                 echo $this->Form->hidden('id'); ?>
										</div>
									</div>
								</div>
								<div class="form-actions">
									<div class="row">
										<div class="col-md-offset-3 col-md-9"><?php
											echo $this->Form->button(__('<i class="fa fa-check"></i>Submit'),array('class'=>'btn purple')); 
												echo $this->Html->link('Cancel',
																array('action' => 'index'),
																array('Class'=>'btn default')); ?>
										</div>
									</div>
								</div>
						<?php echo $this->form->end();?>
						</div>
					</div>
					<!-- END PORTLET-->
				</div>
			</div>
	</div>
</div>
