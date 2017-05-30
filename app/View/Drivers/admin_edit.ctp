<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleApiKey; ?>&v=3&sensor=false&amp;libraries=places,geometry"></script>
<div class="contain">
	<div class="contain">
		<h3 class="page-title">Editer Expédition</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/admin/Dashboards/index'; ?>">Accueil</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="#">Editer Expédition</a>
				</li>
			</ul>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<!-- BEGIN PORTLET-->
				<div class="portlet box blue-hoki">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-user"></i> Editer Expédition
						</div>
						<div class="tools">
							
						</div>
					</div>
					<div class="portlet-body form"> <?php
						echo $this->Form->create('Driver', array('class' => 'form-horizontal')); ?>
							<div class="form-body">
								<div class="form-group">
									<label class="col-md-3 control-label">Nom <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4">  <?php
										echo $this->Form->input('driver_name',
													array('class' => 'form-control',
														  'label' => false)); ?>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-md-3 control-label">Email <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"> <?php
										echo $this->Form->input('driver_email',
													array('class' => 'form-control',
														  'label' => false)); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label"><?php echo __('Phone Number', true); ?> <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"> <?php
										echo $this->Form->input('driver_phone',
													array('class' => 'form-control',
														  'label' => false)); ?>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-md-3 control-label">Adresse <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"> <?php
										echo $this->Form->input('address',
													array('class' => 'form-control',
														  'label' => false,
														  'type' => 'text',
														  'onfocus' =>'initialize(this.id)')); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">N° Permis <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"> <?php
										echo $this->Form->input('license_no',
													array('class' => 'form-control',
														  'label' => false)); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Genre <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4">
										<div class="radio-list">
											<label class="radio-inline"> <?php  
	                                          $option1 = array('M'  => 'Homme');
	                                          $option2 = array('F'   => 'Femme'); 
            
	                                          echo $this->Form->radio('gender',$option1,
                                      							array('checked'=>$option1,
                                      								  'label'=>false,
                                      								  'legend'=>false,
                                      								  'checked' => 'checked',
                                      								  'hiddenField'=>false)); ?> 
                                            </label>
                                            <label class="radio-inline">  <?php 
	                                           echo $this->Form->radio('gender',$option2,
                                       							array('checked'=>$option2,
                                       								  'label'=>false,
                                       								  'legend'=>false,
                                       								  'hiddenField'=>false)); ?>  
			                                </label>
										</div>	
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Description </label>
									<div class="col-md-6 col-lg-4"> <?php
										echo $this->Form->input('driver_description',
													array('class' => 'form-control',
														  'label' => false));
										echo $this->Form->hidden('id');
										echo $this->Form->hidden('User.id'); ?>
									</div>
								</div>
							</div>
							<div class="form-actions">
								<div class="row">
									<div class="col-md-offset-3 col-md-9"> <?php
								  			echo $this->Form->button('<i class="fa fa-check"></i> '.__('Submit'),
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
			</div>
		</div>
	</div>
</div>
