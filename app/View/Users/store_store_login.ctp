<div class="content"> <?php
	echo $this->Form->create('User', array('class' => 'login-form')); ?>
		<h3 class="form-title"><?php echo __('Login to your account');?></h3>
		<div class="alert alert-danger display-hide">
		
			<span>
			Enter any username and password. </span>
		</div>
		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label visible-ie8 visible-ie9"><?php echo __('Username')?></label>
			<div class="input-icon">
				<i class="fa fa-user"></i> <?php
					echo $this->Form->input('username',
										array('label' => false,
											  'placeholder' => __('Username'),
											  'class'=>'form-control placeholder-no-fix',
											  'autocomplete' => 'off',
											  'div' => false)); ?>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9"><?php echo __('Password')?></label>
			<div class="input-icon">
				<i class="fa fa-lock"></i> <?php
					echo $this->Form->input('password',
										array('label' => false,
											  'placeholder' => __('Password'),
											  'class'=>'form-control placeholder-no-fix',
											  'autocomplete' => 'off',
											  'div' => false)); ?> 
			</div>
		</div>
		<div class="form-actions">
			<label class='checkbox'> <?php
				echo $this->Form->hidden('token', array('value' => $token));
				echo $this->Form->input("rememberMe",
							array("type"=>"checkbox",
									'label'=>false,
									'div' =>false));
				echo __('Remember me', true); ?> </label> <?php
	    		echo $this->Form->submit(__('Login'),
	    				 array('class' => 'btn green-haze pull-right',
	    				 		'div'=>false)); ?>
			</button>
		</div>
		<div class="forget-password">
			<h4><?php echo __('Forget Password')?> ?</h4>
			<p>
				 <?php echo __('no worries, click')?> <a href="javascript:;" id="forget-password">
				<?php echo __('here');?> </a>
				<?php echo __('to reset your password')?>.
			</p>
		</div> <?php
		echo $this->Form->end(); ?>


	<?php
		echo $this->Form->create('Users', array('class' => 'forget-form','id'=>'forgetmail')); ?>
			<h3><?php echo __('Forget Password')?> ?</h3>
			<p>
				 Entrez votre adresse e-mail ci-dessous pour réinitialiser votre mot de passe.
			</p>
			<div class="form-group">
				<div class="input-icon">
					<i class="fa fa-envelope"></i> <?php

					echo $this->Form->input('email',
										array('label' => false,
											'placeholder' => 'Email',
											'class'=>'form-control placeholder-no-fix',
											'autocomplete' => 'off',
											'div' => false)); ?> 


				</div>
			</div>
			<div class="form-actions">
				<button type="button" id="back-btn" class="btn">
				<i class="m-icon-swapleft"></i> Retour </button>
				<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn green-haze pull-right', 'div' => false));?>
			</div> <?php
		echo $this->Form->end(); ?>
</div>