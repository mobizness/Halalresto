
<div class="contain">
	<div class="contain">
		<h3 class="page-title">Editer Client</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
			<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Accueil</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="<?php echo $siteUrl.'/admin/Customers/index';?>">Client</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Editer Client</a>
				</li>
			</ul>
		</div>
		
		<div class="row">
				<div class="col-md-12">
					<!-- BEGIN PORTLET-->
					<div class="portlet box blue-hoki">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-user"></i> Editer Client
							</div>
							<div class="tools">
								
							</div>
						</div>
						<div class="portlet-body form"><?php 
						echo $this->Form->create('User',array('class'=>"form-horizontal"));?>
								<div class="form-body">
									<div class="form-group">
										<label class="col-md-3 control-label"> Prénom <span class="star">*</span></label>
										<div class="col-md-6 col-lg-4"><?php
													echo $this->Form->input('Customer.first_name',
															array('class'=>'form-control',
																	'label'=>false)); 
													?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">Nom <span class="star">*</span></label>
										<div class="col-md-6 col-lg-4"><?php
													echo $this->Form->input('Customer.last_name',
															array('class'=>'form-control',
																	'label'=>false)); 
													?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">Numéro de téléphone <span class="star">*</span></label>
										<div class="col-md-6 col-lg-4"><?php
													echo $this->Form->input('Customer.customer_phone',
															array('class'=>'form-control',
																	'label'=>false)); 
													?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">Email <span class="star">*</span></label>
										<div class="col-md-6 col-lg-4"><?php
													echo $this->Form->input('Customer.customer_email',
															array('class'=>'form-control',
																	'label'=>false)); 
													?>
										</div>
									</div>
								</div>
								<div class="form-actions">
									<div class="row">
										<div class="col-md-offset-3 col-md-9"><?php
											echo $this->Form->hidden('Customer.id');
											echo $this->Form->button(__('<i class="fa fa-check"></i>'.__('Submit')),
																array('class'=>'btn purple')); 
											echo $this->Html->link(__('Cancel'),
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
