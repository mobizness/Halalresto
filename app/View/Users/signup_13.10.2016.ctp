<div class="loginBg">	
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-lg-6 col-lg-offset-3 col-md-offset-2">
				<div class="col-md-10 col-md-offset-1" >
					<div class="loginInnerBg clearfix">
						<h4> <?php echo __('Create Account', true); ?></h4><?php
						echo $this->Form->create('User', array('class' => 'login-form')); ?>
							<div class="form-group">
								<label class="control-label"> <?php echo __('First Name', true); ?></label><?php
								echo $this->Form->input('Customer.first_name',
													array('class'=>'form-control',
														  'autocomplete' => 'off',
			                                              'label' => false,
														  'div' => false)); ?> 
							</div>
							<div class="form-group">
								<label class="control-label"> <?php echo __('Last Name', true); ?></label><?php
								echo $this->Form->input('Customer.last_name',
													array('class'=>'form-control',
														  'autocomplete' => 'off',
			                                              'label' => false,
														  'div' => false)); ?> 
							</div>
							<div class="form-group">
								<label class="control-label"> <?php echo __('Email', true); ?></label><?php
								echo $this->Form->input('Customer.customer_email',
													array('class'=>'form-control',
														  'autocomplete' => 'off',
			                                              'label' => false,
														  'div' => false)); ?> 
							</div>
							<div class="form-group">
								<label class="control-label"> <?php echo __('Password', true); ?></label><?php
								echo $this->Form->input('User.password',
													array('class'=>'form-control',
														  'autocomplete' => 'off',
			                                              'label' => false,
														  'div' => false)); ?> 
							</div>
							<div class="form-group">
								<label class="control-label"> <?php echo __('Confirm Password', true); ?></label><?php
								echo $this->Form->input('confir_password',
													array('class'=>'form-control',
														  'type' => 'password',
														  'autocomplete' => 'off',
			                                              'label' => false,
														  'div' => false)); ?> 
							</div>
							<div class="form-group">
								<label class="control-label"> <?php echo __('Phone Number', true); ?></label><?php
								echo $this->Form->input('Customer.customer_phone',
													array('class'=>'form-control',
														  'autocomplete' => 'off',
			                                              'label' => false,
														  'div' => false)); ?> 
							</div>
							<div class="signup-footer">
								<?php echo $this->Form->submit(__('Register')); ?>
								<div class="newuser">
									Already User ? <a href="<?php echo $siteUrl.'/customerlogin'; ?>"><?php echo __('Sign In', true); ?></a>
								</div>
							</div> <?php
						echo $this->Form->end(); ?>
					</div>	
				</div>
			</div>
		</div>
	</div>	
</div>