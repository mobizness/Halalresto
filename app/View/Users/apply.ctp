<div class="loginBg">	
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-lg-6 col-lg-offset-3 col-md-offset-2">
				<div class="col-md-10 col-md-offset-1" >
					<div class="loginInnerBg clearfix">
						<h4> <?php echo __('Devenez Coursier, Postulez Maintenant', true); ?></h4><?php
						echo $this->Form->create('Apply', array('class' => 'login-form')); ?>
							<div class="form-group">
								<label class="control-label"> <?php echo __('First Name', true); ?> <span class="star">*</span></label><?php
								echo $this->Form->input('Customer.first_name',
													array('class'=>'form-control',
														  'autocomplete' => 'off',
			                                              'label' => false,
														  'div' => false)); ?> 
							</div>
							<div class="form-group">
								<label class="control-label"> <?php echo __('Last Name', true); ?> <span class="star">*</span></label><?php
								echo $this->Form->input('Customer.last_name',
													array('class'=>'form-control',
														  'autocomplete' => 'off',
			                                              'label' => false,
														  'div' => false)); ?> 
							</div>
							<div class="form-group">
								<label class="control-label"> <?php echo __('Email', true); ?> <span class="star">*</span></label><?php
								echo $this->Form->input('Customer.customer_email',
													array('class'=>'form-control',
														  'autocomplete' => 'off',
			                                              'label' => false,
														  'div' => false)); ?> 
							</div>
							<div class="form-group">
								<label class="control-label"> <?php echo __('Phone Number', true); ?> <span class="star">*</span></label><?php
								echo $this->Form->input('Customer.customer_phone',
													array('class'=>'form-control',
														  'autocomplete' => 'off',
			                                              'label' => false,
														  'div' => false)); ?> 
							</div>
							<div class="form-group">
								<label class="control-label"> Ville <span class="star">*</span></label><?php
								echo $this->Form->input('Customer.customer_city',
													array('type'=>'select',
														'class'=>'form-control',
														'options'=> array($cities),
														'empty' => __('Select City'),
			                                            'label' => false,
														'div' => false)); ?> 
							</div>
							<div class="signup-footer">
								<?php echo $this->Form->submit('Postulez'); ?>
							</div> <?php
						echo $this->Form->end(); ?>
					</div>	
				</div>
			</div>
		</div>
	</div>	
</div>