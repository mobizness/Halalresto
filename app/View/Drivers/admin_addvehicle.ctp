<div class="contain">
	<div class="contain">
		<h3 class="page-title">Info véhicule</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/admin/dashboards/index'; ?>">Accueil</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">dispatch</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Info véhicule</a>
				</li>
			</ul>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<!-- BEGIN PORTLET-->
				<div class="portlet box blue-hoki">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-user"></i> Info véhicule
						</div>
						<div class="tools">
							
						</div>
					</div>
					<div class="portlet-body form"> <?php
						echo $this->Form->create('Vehicle', array('class' => 'form-horizontal')); ?>
							<div class="form-body">
								<div class="form-group">
									<label class="col-md-3 control-label">Nom véhicule <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"> <?php
										echo $this->Form->input('vehicle_name',
													array('class' => 'form-control',
														  'label' => false)); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Nom model <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"> <?php
										echo $this->Form->input('model_name',
													array('class' => 'form-control',
														  'label' => false)); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Couleur <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"> <?php
										echo $this->Form->input('color',
													array('class' => 'form-control',
														  'label' => false)); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Année <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"> <?php
										echo $this->Form->input('year',
													array('class' => 'form-control',
														  'label' => false)); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Véhicule Immatriculation <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"> <?php
										echo $this->Form->input('vehicle_no',
													array('class' => 'form-control',
														  'label' => false)); ?>
									</div>
								</div> 
								<div class="form-group">
									<label class="col-md-3 control-label">Description </label>
									<div class="col-md-6 col-lg-4"> <?php
										echo $this->Form->input('description',
													array('class' => 'form-control',
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
															array('action' => 'index'),
															array('Class'=>'btn default')); ?>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
				<!-- END PORTLET-->
			</div>
		</div>
	</div>
</div>