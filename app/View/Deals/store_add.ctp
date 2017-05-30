<div class="page-content-wrapper">
	<div class="page-content">
		<h3 class="page-title">Ajouter Deal</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/store/dashboards/index'; ?>">Accueil</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="<?php echo $siteUrl.'/store/deals/index'; ?>">Gestion Deal</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Ajouter Deal</a>
				</li>
			</ul>
		</div>
		
		<div class="row">
				<div class="col-md-12">
					<!-- BEGIN PORTLET-->
					<div class="portlet box blue-hoki">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-user"></i> Ajouter Deal
							</div>
							<div class="tools">
								
							</div>
						</div>
						<div class="portlet-body form"> <?php
							echo $this->Form->create('Deal', array('class' => 'form-horizontal')); ?>
								<div class="form-body">
									
									<div class="form-group">
										<label class="col-md-3 control-label">Nom des Deals <span class="star">*</span></label>
										<div class="col-md-6 col-lg-4"> <?php
											echo $this->Form->input('deal_name',
														array('class' => 'form-control',
															  'label' => false)); ?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">Nom Produit <span class="star">*</span></label>
										<div class="col-md-6 col-lg-4"> <?php
											echo $this->Form->input('main_product',
													array('type'  => 'select',
														'options'	=> array($products),
														  'class' => 'form-control',
														  'empty' => 'Sélectionnez le produit',                     
										 				  'label' => false)); ?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">Product for Deal <span class="star">*</span></label>
										<div class="col-md-6 col-lg-4"> <?php
											echo $this->Form->input('sub_product',
													array('type'  => 'select',
														'options'	=> array($subproducts),
														  'class' => 'form-control',
														  'empty' => 'Sélectionnez le produit',                     
										 				  'label' => false)); ?>
										</div>
									</div>
								</div>
								<div class="form-actions">
									<div class="row">
										<div class="col-md-offset-3 col-md-9"> <?php
								  			echo $this->Form->button('<i class="fa fa-check"></i>'.__('Submit'),
					                              				array('class'=>'btn purple')); ?> <?php
					                        echo $this->Html->link(__('Cancel'),
																array('action' => 'index'),
																array('Class'=>'btn default')); ?>
										</div>
									</div>
								</div> <?php
							echo $this->Form->end(); ?>
						</div>
					</div>
					<!-- END PORTLET-->
				</div>
			</div>
	</div>
</div>