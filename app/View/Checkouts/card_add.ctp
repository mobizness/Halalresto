<div class="modal-dialog modal-md">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">
				<span aria-hidden="true">&times;</span>
			</button>
			<h4 class="modal-title"><?php echo __('Add New Payment', true); ?></h4>
		</div>
		<div class="modal-body"> <?php
			echo $this->Form->create('User',
							array('class'  => 'form-horizontal paymentTab',
									'name' => 'StripeForm',
									'id'   => 'UserIndexForm',
									"url"=> array("controller" => 'checkouts',
                              					 'action' 	   => 'customerCardAdd'))); ?>
			
			<div class="text-center">
				<label id="error" class="margin-b-15"></label>
			</div>

			<div class="form-group clearfix">
				<label class="control-label col-md-4"><?php echo __('Name on Card', true); ?><span class="star">*</span> :</label>
				<div class="col-md-7">
					<?php
						echo $this->Form->input("Card.Name",
								array("type"=>"text",
										"label"=>false,
										'data-stripe' => 'name',
										"class"=>"form-control",
										'value' => '')); 
					?>
				</div>
			</div>
			<div class="form-group clearfix">
				<label class="control-label col-md-4"><?php echo __('Card Number', true); ?><span class="star">*</span>
					<span class="payment-cart-icons"></span> :
				</label>
				<div class="col-md-7">
					<div class="input-group cc merged input-group-card-number">
						<span class="input-group-addon lock"><i class="fa fa-lock"></i></span> <?php 
							echo $this->Form->input("Card.number",
									array("type"=>"text",
											"label"=>false,
											'data-stripe' => 'number',
											"class"=>"form-control intnumber",
											'height' => 40,
											'value' => '',
											'maxlength' => 16,
											'placeholder' => 'XXXX-XXXX-XXXX-XXXX')); ?>
						<span class="input-group-addon input-group-valid"><i class="fa fa-check"></i></span>
						<span class="input-group-addon input-group-card-icon"><i class="fa fa-credit-card"></i></span>
					</div>
				</div>
			</div>
			<div class="form-group clearfix">
				<label class="control-label col-md-4"><?php echo __('CVV', true); ?><span class="star">*</span>
					<img src="<?php echo $siteUrl; ?>/frontend/images/cvv.gif" class="cvv"> :
				</label>
				<div class="col-md-4">
					<div class="input-group cc merged input-group-card-number">
						<span class="input-group-addon lock"><i class="fa fa-lock"></i></span> 
							<?php 
								echo $this->Form->input("Card.cvv",	
									array("type"=>"password",
											"label"=>false,
											'data-stripe' => 'cvc',
											"class"=>"form-control",
											'value' => '')); 
							?>
					</div>
				</div>
			</div>
			<div class="form-group clearfix">
				<label class="control-label col-md-4"><?php echo __('Expiry Date', true); ?><span class="star">*</span> : </label>
				<div class="col-md-7">
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-6">
							<?php
								$month  = array("1"=>"Jan",  "2"=>"Feb", "3"=>"Mar",
												"4"=>"Apr","5"=>"May", "6"=>'Jun',
												"7"=>"Jul", "8"=>"Aug", "9"=>"Sep",
												"10"=>"Oct", "11"=>"Nov", "12"=>"Dec");
								echo $this->Form->input("Card.expmonth",
											array("type"=>"select",
													"label"=>false,
													"options"=>$month,
													'data-stripe' => 'exp-month',
													"class"=>"form-control valid")); 
							?>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-6">
							<?php
								$curyear    = date("Y");
								$endyear    = $curyear+20;
								$years  = array();
								for($i=$curyear;$i<=$endyear;$i++){
									
									$years[$i]=$i;
											
								}
								echo $this->Form->input("Card.expyear",
										array("type"=>"select",
												"label"=>false,
												"options"=>$years,
												'data-stripe' => 'exp-year',
												"class"=>"form-control valid"));
							?>
						</div>
					</div>

				</div>
			</div>

			
			<div class="form-group clearfix">						
				<div class="col-md-8 col-md-offset-4 paymentInfo">
					<?php echo __('The card will automatically be stored in your customer profile so that you can check out faster next time.', true); ?>
				</div>
			</div>
			<div class="form-group clearfix">						
				<div class="col-md-8 col-md-offset-4 paymentInfo">
					<input type="checkbox" class="savecard"> Save this card for Checkout
				</div>
			</div>
			<div class="form-group clearfix margin-t-25">						
				<div class="col-md-8 col-md-offset-4">
					<?php 
						echo $this->Form->button(__('Submit'),
							array("label"=>false,
									"id"=>"stripebtn",
									"class"=>"btn btn-primary",
									'onclick' => 'return saveCard();',
									"type"=>'submit')); 
					?>
				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>