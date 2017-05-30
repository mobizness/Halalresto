<div class="contain">
	<div class="contain">
		<h3 class="page-title">Editer Pays</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Accueil</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="<?php echo $siteUrl.'/admin/countries/index';?>">Gestion Pays</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Editer Pays</a>
				</li>
			</ul>
		</div>
		
		<div class="row">
				<div class="col-md-12">
					<!-- BEGIN PORTLET-->
					<div class="portlet box blue-hoki">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-user"></i> Editer Pays
							</div>
							<div class="tools">
								
							</div>
						</div>
							<div class="portlet-body form"><?php 
								echo $this->Form->create('Country',array('class'=>"form-horizontal"));
									?>			
									<div class="form-body">
										<div class="form-group">
											<label class="col-md-3 control-label">Pays Name <span class="star">*</span></label>
											<div class="col-md-6 col-lg-4"><?php
												echo $this->Form->input('country_name',
																	array('class'=>'form-control',
																		  'label'=>false)); ?>
											 </div>
										</div>
										<div class="form-group">
												<label class="col-md-3 control-label">ISO<span class="star">*</span></label>
												<div class="col-md-6 col-lg-4"><?php
													echo $this->Form->input('iso',
															array('class'=>'form-control',
																	'label'=>false)); ?>
												</div>
										</div>
										<div class="form-group">
												<label class="col-md-3 control-label">Téléphone Code <span class="star">*</span></label>
												<div class="col-md-6 col-lg-4"><?php
													echo $this->Form->input('phone_code',
															array('class'=>'form-control',
																	'label'=>false)); ?>
												</div>
										</div>
										<div class="form-group">
												<label class="col-md-3 control-label">Nom Currency<span class="star">*</span></label>
												<div class="col-md-6 col-lg-4"><?php
													echo $this->Form->input('currency_name',
															array('class'=>'form-control',
																	'label'=>false)); ?>
												</div>
										</div>
										<div class="form-group">
												<label class="col-md-3 control-label">Devise Code<span class="star">*</span></label>
												<div class="col-md-6 col-lg-4"><?php
													echo $this->Form->input('currency_code',
															array('class'=>'form-control',
																	'label'=>false)); ?>
												</div>
										</div>
										<div class="form-group">
												<label class="col-md-3 control-label">Devise Symbole<span class="star">*</span></label>
												<div class="col-md-6 col-lg-4"><?php
													echo $this->Form->input('currency_symbol',
															array('class'=>'form-control',
																	'label'=>false));
													echo $this->Form->hidden('id');?>
												</div>
										</div>
									</div>
									<div class="form-actions">
										<div class="row">
											<div class="col-md-offset-3 col-md-9"> <?php
												echo $this->Form->button(__('Save'),
																array('class'=>'btn purple')); ?> <?php
												echo $this->Html->link(__('Cancel'),
																array('action' => 'index'),
																array('Class'=>'btn default'));
												 ?>
											</div>
										</div>
									</div>														
						</div><?php 
								echo $this->Form->end();?>
					</div>
					<!-- END PORTLET-->
				</div>
			</div>
	</div>
</div>
