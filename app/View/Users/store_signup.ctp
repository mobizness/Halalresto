<div class="container loginBg">
	<div class="row">
		<div class="col-md-12">
			<div class="loginInnerBg clearfix">
				<h4> <?php echo __('Create Account', true); ?></h4><?php
				echo $this->Form->create('User', array('class' => 'login-form')); ?>
					<div class="row">
						<div class="col-md-6">
							<div class="col-md-11">
								<div class="form-group">
									<label class="control-label"> <?php echo __('Nom du Restaurant', true); ?></label><?php
									echo $this->Form->input('Store.store_name',
											array('class'=>'form-control',
													'autocomplete' => 'off',
													'label' => false,
													'div' => false)); ?>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="col-md-11">
								<div class="form-group">
									<label class="control-label"> <?php echo __('Nom du Contact', true); ?></label><?php
									echo $this->Form->input('Store.contact_name',
														array('class'=>'form-control',
															  'autocomplete' => 'off',
				                                              'label' => false,
															  'div' => false)); ?> 
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="col-md-11">
								<div class="form-group">
									<label class="control-label"> <?php echo __('Phone Number', true); ?></label><?php
									echo $this->Form->input('Store.contact_phone',
											array('class'=>'form-control',
													'autocomplete' => 'off',
													'label' => false,
													'div' => false)); ?>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="col-md-11">
								<div class="form-group">
									<label class="control-label"> <?php echo __('Email', true); ?></label><?php
									echo $this->Form->input('User.username',
														array('class'=>'form-control',
															  'autocomplete' => 'off',
				                                              'label' => false,
															  'div' => false)); ?> 
								</div>
							</div>
						</div>
					</div>


					<div class="row">
						<div class="col-md-6">
							<div class="col-md-11">
								<div class="form-group">
									<label class="control-label"> <?php echo __('Password', true); ?></label><?php
									echo $this->Form->input('User.password',
											array('class'=>'form-control',
													'autocomplete' => 'off',
													'label' => false,
													'div' => false)); ?>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="col-md-11">
								<div class="form-group">
									<label class="control-label"> <?php echo __('Confirm Password', true); ?></label><?php
									echo $this->Form->input('confirm_password',
														array('class'=>'form-control',
															  'type' => 'password',
															  'autocomplete' => 'off',
				                                              'label' => false,
															  'div' => false)); ?> 
								</div>
							</div>
						</div>
					</div>

					<div class="signup-footer">
						<div class="col-md-6"> <?php 
							echo $this->Form->submit(__('Register')); ?>
						</div>
						<div class="col-md-6">
							<div class="margin-t-5 text-right">
								<a class="linkRight" href="<?php echo $siteUrl.'/restaurant'; ?>"> <?php echo __('Already User', true); ?> ?</a>

							</div>
						</div>
					</div> <?php
				echo $this->Form->end(); ?>
			</div>	
		</div>
	</div>
</div>	