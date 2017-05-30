
<div class="page-content-wrapper">
	<div class="page-content">
		<h3 class="page-title">Change Password</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<a href="<?php echo $siteUrl.'/store/dashboards/index';?>">Home</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Change Password</a>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<!-- BEGIN PORTLET-->
				<div class="portlet box blue-hoki">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-gift"></i> Change Password
						</div>
						<div class="tools">
							<a href="javascript:void(0);" class="collapse"></a>
							<a href="javascript:void(0);" class="reload"></a>
							<a href="javascript:void(0);" class="remove"></a>
						</div>
					</div>
					<div class="portlet-body form"><?php
							echo $this->Form->create('user', array('class' => 'form-horizontal')); ?>
							<div class="form-body">
								<!--<div class="form-group">
									<label class="col-md-3 control-label">Old Password</label>
									<div class="col-md-6 col-lg-4"><?php
											echo $this->Form->input('old_pass',
														array('class' => 'form-control',
															  'label' => false)); ?>			
									</div>
								</div>-->
								<div class="form-group">
									<label class="col-md-3 control-label">New Password <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"><?php
											echo $this->Form->input('new_pass',
														array('class' => 'form-control',
																'type' => 'password',
															  'label' => false)); ?>				
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Confirm Password <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"><?php
											echo $this->Form->input('confirm_pass',
														array('class' => 'form-control',
															'type' => 'password',
															  'label' => false)); ?>
									</div>
								</div>
							</div>
							<div class="form-actions">
								<div class="row">
									<div class="col-md-offset-3 col-md-9"> <?php
								  			echo $this->Form->button('<i class="fa fa-check"></i> Submit',
					                              				array('class'=>'btn purple')); ?> <?php
					                        echo $this->Html->link('Cancel',
																array('controller'=>'dashboards','action' => 'index'),
																array('Class'=>'btn default')); ?>
									</div>
								</div>
							</div><?php
							echo $this->Form->end(); ?>
					</div>
				</div>
				<!-- END PORTLET-->
			</div>
		</div>
	</div>
</div>
