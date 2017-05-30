<div class="contain">
	<div class="contain">
		<h3 class="page-title">Ajouter spécialités culinaires</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/admin/dashboards/index'; ?>">Accueil</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="<?php echo $siteUrl.'/admin/Cuisines/index'; ?>">Gestion spécialités culinaires</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Ajouter spécialités culinaires</a>
				</li>
			</ul>
		</div>
		
		<div class="row">
				<div class="col-md-12">
					<!-- BEGIN PORTLET-->
					<div class="portlet box blue-hoki">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-user"></i> Ajouter spécialités culinaires
							</div>
							<div class="tools">
								
							</div>
						</div>
						<div class="portlet-body form"><?php 
								echo $this->Form->create('Cuisine',array('class'=>"form-horizontal"));
									?>			
								<div class="form-body">
									<div class="form-group">
										<label class="col-md-3 control-label">Nom spécialités culinaires <span class="star">*</span></label>
										<div class="col-md-6 col-lg-4"><?php
													echo $this->Form->input('cuisine_name',
															array('class'=>'form-control',
																	'label'=>false)); ?>
										</div>
									</div>
								</div>
								<div class="form-actions">
									<div class="row">
										<div class="col-md-offset-3 col-md-9"><?php
												echo $this->Form->button(__('<i class="fa fa-check"></i>'.__('Submit')),array('class'=>'btn purple')); 
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

