<div class="contain">
	<div class="contain">
		<h3 class="page-title">Editer Ville</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Accueil</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="<?php echo $siteUrl.'/admin/cities/index';?>">Gestion Ville</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Editer Ville</a>
				</li>
			</ul>
		</div>
		
		<div class="row">
				<div class="col-md-12">
					<!-- BEGIN PORTLET-->
					<div class="portlet box blue-hoki">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-user"></i> Editer Ville
							</div>
							<div class="tools">
								
							</div>
						</div>
						<div class="portlet-body form"><?php 
								echo $this->Form->create('City',array('class'=>"form-horizontal"));
									?>			
								<div class="form-body">
								<div class="form-group">
											<label class="col-md-3 control-label">Pays Select <span class="star">*</span></label>
											<div class="col-md-6 col-lg-4"><?php
												echo $this->Form->input('country_id',
														array('type'  => 'select',
															  'class' => 'form-control',
															  'options'=> array($country_list),
                                                             'onchange' => 'stateFillter();',
															  'empty' => 'Sélectionnez le pays',
											 				  'label'=> false)); ?>
											 </div>
										</div>
									<div class="form-group">
										<label class="col-md-3 control-label">Nom Departement <span class="star">*</span></label>
										<div class="col-md-6 col-lg-4"><?php
												echo $this->Form->input('state_id',
														array('type'  => 'select',
															  'class' => 'form-control',
															  'options'=> array($state_list),
															  'empty' => __('Select State'),
											 				  'label'=> false)); ?>
											 </div>
									</div>
									<div class="form-group">
												<label class="col-md-3 control-label">Nom Ville <span class="star">*</span></label>
												<div class="col-md-6 col-lg-4"><?php
													echo $this->Form->input('city_name',
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
																array('Class'=>'btn default')); ?>
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
