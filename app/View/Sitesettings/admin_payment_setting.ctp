<div class="contain">
	<div class="contain">
		<h3 class="page-title">Réglages paiements</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/admin/dashboards/index'; ?>">Accueil</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="<?php echo $siteUrl.'/admin/sitesettings/index'; ?>">Réglages</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Réglages paiements</a>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<!-- BEGIN PORTLET-->
				<div class="portlet box green">
					<?php 
						echo $this->Form->create('Sitesetting',array('class'=>"form-horizontal"));?>
					<div class="portlet-title no-padding">
						<ul class="portletList">
							<li>
								<a>
									<?php 
									$option1 = array('Stripe'   => '');
									$option2 = array('Paypal'  => '');
									echo $this->Form->radio('paymentsetting',$option1,
										array('checked'=>$option1,
											'checked'=>'checked',
											'label'=>false,
											'legend'=>false,
											'class'=>'hide',
											'onclick' => "paymentSetting('Stripe')",                      
											'hiddenField'=>false)); ?>
									<label for="SitesettingPaymentsettingStripe">Stripe</label>
								</a>
							</li>
							<li>
								<a>
									<?php
									echo $this->Form->radio('paymentsetting',$option2,
										array('checked'=>$option2,
											'label'=>false,
											'legend'=>false,			                           								
											'id'=>'paypal',
											'class'=>'hide',
											'onclick' => "paymentSetting('Paypal')", 
											'hiddenField'=>false)); ?>
									<label for="PaypalPaypal">Paypal</label>
								</a>
							</li>
						</ul>
					</div>
					<div class="clearfix"></div>
					<div class="portlet-body form whitebg">			
							<div class="form-body">								
								<div class="stripeDiv">
									<div class="form-group">
										<label class="col-md-3 control-label">Stripe Mode <span class="star">*</span></label>
										<div class="col-md-6 col-lg-4">		
											<div class="radio-list">			
												<label class="radio-inline"> <?php 
					                                    $option3 = array('Live'  => 'Mode Live');
					                                    $option4 = array('Test'   => 'Mode Test');
					                                   	echo $this->Form->radio('stripe_mode',$option3,
					                           							array('checked'=>$option3,
					                           								'label'=>false,
					                           								'checked'=>'checked',
					                           								'legend'=>false,                        
					                           								'hiddenField'=>false)); ?>
					                            </label>
												<label class="radio-inline"><?php
					                                echo $this->Form->radio('stripe_mode',$option4,
					                           							array('checked'=>$option4,
					                           								'label'=>false,
					                           								'legend'=>false,
					                           								'hiddenField'=>false)); 
					                           		echo $this->Form->hidden('id');?>
					                            </label>
					                        </div>							
										</div>
												
									</div>
									<div id="Test">
										<div class="form-group">
											<label class="col-md-3 control-label">Stripe Test Secret Key <span class="star">*</span></label>
											<div class="col-md-6 col-lg-4"><?php
	                    					echo $this->Form->input('stripe_secretkeyTest',
	                    										array('class'=>'form-control',
	                    											  'autocomplete' => 'off',
	                                                                  'label' => false,
	                    											  'div' => false)); ?>
											</div>
										</div>	
										<div class="form-group">
											<label class="col-md-3 control-label">Stripe Test Publish Key <span class="star">*</span></label>
											<div class="col-md-6 col-lg-4"><?php
	                    					echo $this->Form->input('stripe_publishkeyTest',
	                    										array('class'=>'form-control',
	                    											  'autocomplete' => 'off',
	                                                                  'label' => false,
	                    											  'div' => false)); ?>
											</div>
										</div>
									</div>

									<div id="Live">

										<div class="form-group">
											<label class="col-md-3 control-label">Stripe Live Secret Key <span class="star">*</span></label>
											<div class="col-md-6 col-lg-4"><?php
	                    					echo $this->Form->input('stripe_secretkey',
	                    										array('class'=>'form-control',
	                    											  'autocomplete' => 'off',
	                                                                  'label' => false,
	                    											  'div' => false)); ?>
											</div>
										</div>	
										<div class="form-group">
											<label class="col-md-3 control-label">Stripe Live Publish Key <span class="star">*</span></label>
											<div class="col-md-6 col-lg-4"><?php
	                    					echo $this->Form->input('stripe_publishkey',
	                    										array('class'=>'form-control',
	                    											  'autocomplete' => 'off',
	                                                                  'label' => false,
	                    											  'div' => false)); ?>
											</div>
										</div>
									</div>
								</div>

								<div class="paypalDiv" style="display: none;">
									<div class="form-group">
										<label class="col-md-3 control-label">Paypal Mode <span class="star">*</span></label>
										<div class="col-md-6 col-lg-4">		
											<div class="radio-list">			
												<label class="radio-inline"> <?php 
					                                    $option3 = array('Live'  => 'Mode Live');
					                                    $option4 = array('Test'   => 'Mode Test');
					                                   	echo $this->Form->radio('paypal_mode',$option3,
					                           							array('checked'=>$option3,
					                           								'label'=>false,
					                           								'checked'=>'checked',
					                           								'legend'=>false,                        
					                           								'hiddenField'=>false)); ?>
					                            </label>
												<label class="radio-inline"><?php
					                                echo $this->Form->radio('paypal_mode',$option4,
					                           							array('checked'=>$option4,
					                           								'label'=>false,
					                           								'legend'=>false,
					                           								'hiddenField'=>false)); 
					                           		echo $this->Form->hidden('id');?>
					                            </label>
					                        </div>							
										</div>
										<!-- <label id="paymentError" class="error"></lable> -->		
									</div>
									<div id="payTest">
										<div class="form-group">
											<label class="col-md-3 control-label">Paypal Username <span class="star">*</span></label>
											<div class="col-md-6 col-lg-4"><?php
	                    					echo $this->Form->input('paypal_test_username',
	                    										array('class'=>'form-control',
	                    											  'autocomplete' => 'off',
	                                                                  'label' => false,
	                    											  'div' => false)); ?>
											</div>
										</div>	
										<div class="form-group">
											<label class="col-md-3 control-label">Paypal Password <span class="star">*</span></label>
											<div class="col-md-6 col-lg-4"><?php
	                    					echo $this->Form->input('paypal_test_password',
	                    										array('class'=>'form-control',
	                    											  'autocomplete' => 'off',
	                                                                  'label' => false,
	                    											  'div' => false)); ?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">Paypal Signature <span class="star">*</span></label>
											<div class="col-md-6 col-lg-4"><?php
	                    					echo $this->Form->input('paypal_test_signature',
	                    										array('class'=>'form-control',
	                    											  'autocomplete' => 'off',
	                                                                  'label' => false,
	                    											  'div' => false)); ?>
											</div>
										</div>
									</div>

									<div id="payLive">

										<div class="form-group">
											<label class="col-md-3 control-label">Paypal Username <span class="star">*</span></label>
											<div class="col-md-6 col-lg-4"><?php
	                    					echo $this->Form->input('paypal_live_username',
	                    										array('class'=>'form-control',
	                    											  'autocomplete' => 'off',
	                                                                  'label' => false,
	                    											  'div' => false)); ?>
											</div>
										</div>	
										<div class="form-group">
											<label class="col-md-3 control-label">Paypal Password <span class="star">*</span></label>
											<div class="col-md-6 col-lg-4"><?php
	                    					echo $this->Form->input('paypal_live_password',
	                    										array('class'=>'form-control',
	                    											  'autocomplete' => 'off',
	                                                                  'label' => false,
	                    											  'div' => false)); ?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">Paypal Signature <span class="star">*</span></label>
											<div class="col-md-6 col-lg-4"><?php
	                    					echo $this->Form->input('paypal_live_signature',
	                    										array('class'=>'form-control',
	                    											  'autocomplete' => 'off',
	                                                                  'label' => false,
	                    											  'div' => false)); ?>
											</div>
										</div>

									</div>
								</div>
																		
							</div>
							<div class="form-actions">
								<div class="row">
									<div class="col-md-offset-3 col-md-9"><?php
											echo $this->Form->button(__('<i class="fa fa-check"></i>'.__('Submit')),array('class'=>'btn purple',
											'onclick' => 'return paymentSettingvalidate();')); ?>
									</div>
								</div>
							</div>							
					</div>
					<?php echo $this->Form->end();?>
				</div>
				<!-- END PORTLET-->
			</div>
		</div>
	</div>
</div>
