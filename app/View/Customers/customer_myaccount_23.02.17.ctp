<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleApiKey; ?>&v=3&sensor=false&amp;libraries=places,geometry"></script>
<div class="container searchshopContent">
	<div class="clearfix customerMyAccount">
		<div class="myaccount-tabs col-md-2 col-lg-2 col-sm-12 col-xs-12">
			<a class="active" href="javascript:void(0);" id="orderhistory">
				<div class="orderHistory"></div>
				<div class="myaccount-label"> <?php echo __('Order History', true); ?></div>
			</a>
			<a href="javascript:void(0);" id="profile">
				<div class="profile"></div>
				<div class="myaccount-label"> <?php echo __('Profile', true); ?></div>
			</a>
			<a href="javascript:void(0);" id="wallet">
				<div class="address"></div>
				<div class="myaccount-label"> <?php echo __('Wallet', true); ?></div>
			</a>
			<a href="javascript:void(0);" id="password_change">
				<div class="address"></div>
				<div class="myaccount-label"> <?php echo __('Password', true); ?></div>
			</a>
			<a href="javascript:void(0);" id="address">
				<div class="address"></div>
				<div class="myaccount-label"> <?php echo __('Address Book', true); ?></div>
			</a>
			<!-- <a href="javascript:void(0);" id="favourite">
				<div class="address"></div>
				<div class="myaccount-label"> <?php echo __('My Favourite', true); ?></div>
			</a> -->
		</div>
		<div class="col-md-10 col-xs-12"> <?php
			$resLink = $siteUrl.'/shop/restaurant/'.base64_encode($storeId); ?>
			<a href="<?php echo $siteUrl; ?>" class="pull-right btn btn-primary siteLink"> <?php
				echo __('Continue Shopping', true); ?> </a>

			<!-- <a href="<?php echo $resLink; ?>" class="pull-right btn btn-primary pluginResLink" style="display: none;"> <?php
				echo __('Continue Shopping', true); ?> </a> -->

			<div class="myorderTab" id="orderhistory_content">
				<h1> <?php echo __('Order History', true); ?></h1>				
				<div class="table-responsive">
					<table class="table table-hover datatable-common" >
						<thead>
							<tr>
								<th class="hide">&nbsp;</th>
							</tr>
						</thead>
						<tbody>
							<?php if(!empty($order_detail)) { 
		                    	foreach($order_detail as $key => $value){  ?>
			                    	<tr>
										<td class="order_history"> 
											<div class="cust_ord_history_table">
												<div class="col-sm-4 col-md-4 col-xs-12">
													<span class="cust_order_historyid"><?php echo $value['Order']['ref_number'];?></span>
													<span class="cust_order_history"><?php echo __('Payment Type', true); ?>:<span class="cust_order_payment"><?php echo __($value['Order']['payment_type']);?></span></span>
													<span  class="cust_order_history"><?php echo __('Status', true); ?>:<span id="status<?php echo $value['Order']['id']; ?>" class="cust_order_payment"><?php 
													echo ($value['Order']['status'] == 'Collected') ?
														__('Picked up') :  __($value['Order']['status']);?></span></span>
												</div>
												<div class="col-sm-4 col-md-4 col-xs-12">
													<span class="cust_order_history"><?php echo __('Delivery Date', true); ?></span>
													<span class="history_date"><?php echo $value['Order']['delivery_date'];?></span>
													<span class="cust_order_history"><?php echo __('Order At', true); ?></span>
													<span class="history_date"><?php echo $value['Order']['created'];?></span>
												</div>
												<div class="col-sm-4 col-md-4 col-xs-12">
													<span  class="cust_order_details"><?php echo $value['Order']['order_grand_total'];?></span>
													<span  class="cust_order_details">
													<?php
													echo $this->Html->link('<i class="fa fa-search"></i> View Details',
																			array('controller'=>'Customers',
																				   'action'=>'orderView',
																					$value['Order']['id']),
																			array('class'=>'cust_view_details',
																					'escape'=>false));?>
													</span>		
													<div class="clearfix"></div>	
													<span class="pull-right ratings<?php echo $value['Order']['id']; ?>"> <?php

														if ($value['Order']['status'] == 'Collected') { ?>
															<span  class="cust_order_details"><a class="cust_view_details" data-target="#trackid" data-toggle="modal" onclick="return viewTrack(<?php echo $value['Order']['id']; ?>);" href="javascript:void(0);">Track Order</a></span> <?php

														}


														if(!empty($value['Review']['rating'])){
															$amount = $value['Review']['rating'] * 20;?>
															<span class="review_rating_outer">
																<span class="review_rating_grey"></span>
																<span class="review_rating_green" style="width:<?php echo $amount;?>%;"></span>
															</span> <?php 
														} else {
															if($value['Order']['status'] == 'Delivered'){?>
																<span  class="cust_order_details"><a class="cust_view_details" href="javascript:void(0);" onclick = "orderid(<?php echo$value['Order']['id'];?>)"data-toggle="modal" data-target="#reviewPopup"><?php 
																echo __('Review', true); ?></a></span><?php 
															}
														} ?> 
													</span>
												</div>
											</div>
										</td>
									</tr><?php
								}
		                    } ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="myorderTab" id="profile_content" style="display:none;">
				<h1> <?php echo __('Profile', true); ?></h1>
				<div class="row"> <?php
	                   echo $this->Form->create('Customer', array('class' => 'login-form','type'=>'file')); ?>
	                <div class="col-md-5">
	                	<div class="row">
	                		<div class="col-md-11">
		                		<div class="cardDetailHead"> <?php echo __('Basic Details', true); ?></div>
		                		<div class="form-group clearfix">
		                			<div class="col-md-12">
				                		<?php
					                        if(!empty($this->request->data['Customer']['image'])) {?>
					                            <img class="img-responsive customer_image"  src="<?php echo $this->webroot.'Customers/'.$this->request->data['Customer']['image']; ?>" >
					                       <?php } else {
					                                echo "Image non trouvé";
					                        }
					                                 echo $this->Form->input("Customer.image",
					                                 				array("label"=>false,
							                                                "type"=>"file",
							                                                'onchange' => 'showimage(event);',
							                                                "class"=>"form-control textbox margin-t-15",
							                                               ));

							                echo $this->Form->input('Customer.org_logo',
							                            array('class' => 'form-control',
							                            	  'type' => 'hidden',
							                                  'label' => false,
							                                  'value' => $this->request->data['Customer']['image']));
						                ?>
						            </div>
					            </div>
					            <div class="form-group profile-box clearfix">
									<label class="control-label col-md-12 text-left"> <?php echo __('First Name', true); ?></label>
									<div class="col-md-12">
										<div class="formLabel"><?php
			                    					echo $this->Form->input('Customer.first_name',
			                    										array('class'=>'form-control',
			                    											  'autocomplete' => 'off',
			                    											  'readonly' => true,
			                                                                  'label' => false,
			                    											  'div' => false)); ?> </div>
										<span class="edit"><i class="fa fa-edit"></i></span><?php
			                            echo $this->Form->input('Customer.first_name',
			                    										array('class'=>'form-control textbox',
			                    											  'autocomplete' => 'off',
			                                                                  'label' => false,
			                    											  'div' => false)); ?>
									<span class="lableclose"><i class="fa fa-times-circle"></i></span>
									</div>
								</div>

								<div class="form-group profile-box clearfix">
									<label class="control-label col-md-12 text-left"> <?php echo __('Last Name', true); ?></label>
									<div class="col-md-12">
										<div class="formLabel"><?php
	                    					echo $this->Form->input('Customer.last_name',
	                    										array('class'=>'form-control',
	                    											  'autocomplete' => 'off',
	                    											  'readonly' => true,
	                                                                  'label' => false,
	                    											  'div' => false)); ?> 
										</div>
										<span class="edit"><i class="fa fa-edit"></i></span><?php
			                            echo $this->Form->input('Customer.last_name',
			                    										array('class'=>'form-control textbox',
			                    											  'autocomplete' => 'off',
			                                                                  'label' => false,
			                    											  'div' => false)); ?>
									<span class="lableclose"><i class="fa fa-times-circle"></i></span>
									</div>
								</div>

								<!-- <div class="form-group profile-box clearfix">
									<label class="control-label col-md-12 text-left"> <?php echo __('Email', true); ?></label>
									<div class="col-md-12">
										<div class="formLabel"><?php
			                    					echo $this->Form->input('Customer.customer_email',
			                    										array('class'=>'form-control',
			                    											  'autocomplete' => 'off',
			                    											  'readonly' => true,
			                                                                  'label' => false,
			                    											  'div' => false)); ?></div>
										<span class="edit"><i class="fa fa-edit"></i></span> <?php
			                    					echo $this->Form->input('Customer.customer_email',
			                    										array('class'=>'form-control textbox',
			                    											  'autocomplete' => 'off',
			                                                                  'label' => false,
			                    											  'div' => false)); ?>
										<span class="lableclose"><i class="fa fa-times-circle"></i></span>
									</div>
								</div> -->

								<div class="form-group profile-box clearfix">
									<label class="control-label col-md-12 text-left"> <?php echo __('Phone Number', true); ?></label>
									<div class="col-md-12">
										<div class="formLabel"><?php
			                    					echo $this->Form->input('Customer.customer_phone',
			                    										array('class'=>'form-control',
			                    											  'autocomplete' => 'off',
			                    											  'readonly' => true,
			                                                                  'label' => false,
			                    											  'div' => false)); ?></div>
										<span class="edit"><i class="fa fa-edit"></i></span> <?php
			                    					echo $this->Form->input('Customer.customer_phone',
			                    										array('class'=>'form-control textbox',
			                    											  'autocomplete' => 'off',
			                                                                  'label' => false,
			                    											  'div' => false)); ?>
										<span class="lableclose"><i class="fa fa-times-circle"></i></span>
									</div>
								</div>
			                    <div class="form-group profile-box clearfix">
									<label class="control-label col-md-12 text-left"> <?php echo __('Do You Wanna Newsletter', true); ?></label>
									<div class="col-md-12">
										
										<label class="radio-inline"> <?php 
			                                    $option1 = array('Yes'  => __('Yes'));
			                                    $option2 = array('No'   => __('No'));
			                                   	echo $this->Form->radio('Customer.news_letter_option',$option1,
			                           							array('checked'=>$option1,
			                           								'label'=>false,
			                           								'legend'=>false,                        
			                           								'hiddenField'=>false)); ?>
			                            </label>
										<label class="radio-inline"><?php
			                                	echo $this->Form->radio('Customer.news_letter_option',$option2,
			                           							array('checked'=>$option2,
			                           								'label'=>false,
			                           								'legend'=>false,
			                           								'hiddenField'=>false)); ?>
			                            </label>
										
									</div>
								</div>
							</div>
						</div>
	                </div>					
					<div class="col-md-7 profile-right">
						
						<div class="paymentWrapper cardProfile clearfix">
							<div class="cardDetailHead"> <?php echo __('Saved Card Details'); ?>
								<span class="pull-right">
									<a href="javascript:void(0);" data-target="#addpayment" data-toggle="modal" class="addnewAdrr">
										<i class="fa fa-plus"></i>&nbsp; <?php echo __('Add Card', true); ?>
									</a>
								</span>
							</div><?php 
							if(!empty($Stripe_detail)){
								foreach ($Stripe_detail as $key => $value) {?>

									<div class="col-md-6 col-xs-12" id="<?php echo "card".$value['StripeCustomer']['id'];?>">

										<label class="editpayment" >
											<input type="radio" value="" name="">
											<div class="card_info">
												<span class="editAdd contain truncate">

													<img style="height:24px;" alt="cod_icon" title="cod_icon" src="<?php echo $siteUrl.'/	frontend/images/debit_card.png'; ?>">
													<?php echo $value['StripeCustomer']['customer_name'] ;?>													
												</span>
												<p class="margin-t-20">XXXX XXXX XXXX <?php echo $value['StripeCustomer']['card_number'] ;?> </p>
											</div>
											<a class="delete_card" href="javascript:void(0)" onclick="deletecard(<?php echo $value['StripeCustomer']['id']; ?>)">x</a>
										</label>
									</div>
									<?php
								}

							} ?>
							
							
						</div>
					</div>
					<div class="col-md-12 text-center">
	                    <?php echo $this->Form->button(__('Submit'),array('class'=>'btn btn-primary'));  ?>
	                    <?php echo $this->Form->end();?>
					</div>
						
				</div>
			</div>
			<div class="myorderTab" id="wallet_content" style="display:none;" >
				
				<div id="addMoney"> 
					<h1> <?php echo __('Wallet', true); ?>
						<a class="pull-right btn btn-primary marginRight15" onclick="walletPage('history');" href="javascript:void(0);"><?php echo __('Wallet History'); ?> </a>
					 </h1>
					<div class="row"> <?php
						echo $this->Form->create("CustomerWallet", array(
													'id'	=>'AddAmountWallet',
	                                        		'name'	=> 'StripeWalletForm',
	                                              	'url'	=> array("controller"=>'Customers',
	                                              					'action'	=>'addWalletAmount'))); ?>
								
							<div class="paymentWrapper cardProfile clearfix no-padding">
								<div class="row margin-t-15 margin-b-15 wallet_price">
									<div class="col-sm-4 wallet_amount">
										<label class="control-label"><?php echo __('Wallet Amount', true); ?> :</label> <?php 
								echo html_entity_decode($this->Number->currency($this->request->data['Customer']['wallet_amount'], $siteCurrency));  ?>
									</div>
									<div class="col-sm-4 form-horizontal">
										<div class="form-group form-inline margin-b-0 teenmobilenoMar mobilenoMar"> 
											<label class="control-label"><?php echo __('Amount'); ?> : </label>
											<div class="input-group">	
												<span class="input-group-addon"><?php echo $siteCurrency ?></span> <?php
												echo $this->Form->input('Customer.wallet_amount',
	                    										array('class'=>'form-control textbox intnumber',
	                    											  'autocomplete' => 'off',
	                                                                  'label' => false,
	                                                                  'type' => 'text',
	                                                                  'value' => '',
	                    											  'div' => false)); ?>
					                     	</div>
					                     	<label id="Amount" class="error"></label>
										</div>												
									</div>
									<div class="col-sm-4">
										<?php
											$cards = array('savedCard' => __('Saved Card'), 'newCard' => __('Credit/Debit Card'));

											echo $this->Form->input('cardType', 
																		array('type'  	=> 'select',
																			  'class' 	=> 'form-control',
																			  'options'	=> array($cards),
																			  'onchange' => 'cardSelection();',
															 				  'label'	 => false)); ?>
									</div>
								</div>
								<div class="row teenmobilenoMar">
									<div id="savedCards" class="col-sm-12 no-padding"> <?php 
										if(!empty($Stripe_detail)) {
											foreach ($Stripe_detail as $key => $value) { ?>
												<div class="col-md-4 col-sm-6 col-xs-12" id="<?php echo "card".$value['StripeCustomer']['id'];?>">
													<label class="editpayment <?php echo ($key == 0) ? 'active' : ''; ?>" >
														<input type="radio" value="<?php echo $value['StripeCustomer']['id'];?>" name="data[Customer][walletCard]" <?php echo ($key == 0) ? 'checked=checked' : false; ?> >
														<div class="card_info">
															<span class="editAdd contain truncate">
																<img style="height:24px;" alt="cod_icon" title="cod_icon" src="<?php echo $siteUrl.'/	frontend/images/debit_card.png'; ?>">
																<?php echo $value['StripeCustomer']['customer_name'] ;?>
															</span>
															<p class="margin-t-20">XXXX XXXX XXXX <?php echo $value['StripeCustomer']['card_number'] ;?> </p>
														</div>
													</label>
												</div> <?php
											}
										} else {
											echo __('No saved cards available');
										} ?>
									</div>
								</div>
								<div class="row teenmobilenoMar">
									<div class="col-xs-12" id="newCard" style="display: none;">
						                <div class="text-center">
											<label id="walletError" class="margin-b-15"></label>
										</div>

										<div class="form-group clearfix">
											<label class="control-label col-md-4"> <?php echo __('Name on Card', true); ?><span class="star">*</span> :</label>
											<div class="col-md-5"> <?php
												echo $this->Form->input("Card.Name",
															array("type"=>"text",
																	"label"=>false,
																	'id' => 'WalletCardName',
																	'data-stripe' => 'name',
																	"class"=>"form-control",
																	'value' => ''));  ?>
											</div>
										</div>
										<div class="form-group clearfix">
											<label class="control-label col-md-4"> <?php echo __('Card Number', true); ?><span class="star">*</span>
												<span class="payment-cart-icons"></span> :
											</label>
											<div id="paymentWallet"></div>
											<div class="col-md-5">
												<div class="input-group cc merged input-group-card-number">
													<span class="input-group-addon lock"><i class="fa fa-lock"></i></span> <?php 
														echo $this->Form->input("Card.number",
																array("type"=>"text",
																		"label"=>false,
																		'data-stripe' => 'number',
																		'id' => 'WalletCardNumber',
																		"class"=>"form-control intnumber",
																		'height' => 40,
																		'maxlength' => 16,
																		'value' => '',
																		'placeholder' => 'XXXX-XXXX-XXXX-XXXX')); ?>
													<span class="input-group-addon input-group-valid"><i class="fa fa-check"></i></span>
													<span class="input-group-addon input-group-card-icon"><i class="fa fa-credit-card"></i></span>
												</div>
											</div>
										</div>

										<div class="form-group clearfix">
											<label class="control-label col-md-4"> <?php echo __('CVV', true); ?><span class="star">*</span>
												<img src="<?php echo $siteUrl; ?>/frontend/images/cvv.gif" class="cvv"> :
											</label>
											<div class="col-md-4">
												<div class="input-group cc merged input-group-card-number">
													<span class="input-group-addon lock"><i class="fa fa-lock"></i></span> <?php 
														echo $this->Form->input("Card.cvv",	
																	array("type"=>"password",
																			"label"=>false,
																			'id' => 'WalletCardCvv',
																			'data-stripe' => 'cvc',
																			"class"=>"form-control",
																			'value' => ''));  ?>
												</div>
											</div>
										</div>
										<div class="form-group clearfix">
											<label class="control-label col-md-4"> <?php echo __('Expiry Date', true); ?><span class="star">*</span> : </label>
											<div class="col-md-5">
												<div class="row">
													<div class="col-md-6 col-xs-6"> <?php
														
														echo $this->Form->input("Card.expmonth",
																	array("type"=>"select",
																			"label"=>false,
																			"options"=>$month,
																			'data-stripe' => 'exp-month',
																			"class"=>"form-control valid"));  ?>
													</div>
													<div class="col-md-6 col-xs-6"> <?php
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
																		"class"=>"form-control valid"));  ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group clearfix">
				                    <div class="text-center"> <?php 
				                    	echo $this->Form->button(__('Submit'),
	                    										array('class'=>'btn btn-primary',
	                    												'id' => 'paymentBtn',
	                    												'onclick' => 'return walletCard();'));  ?>
				                    </div>
				                </div>
							</div> <?php
						echo $this->Form->end(); ?>
					</div>
				</div>

				<div id="historyWallet" style="display: none">
					<h1> <?php echo __('Wallet History', true); ?>
						<a class="pull-right btn btn-primary" onclick="walletPage('money');" href="javascript:void(0);"><i class="fa fa-plus"></i>&nbsp; <?php echo __('Add Money'); ?> </a>
					 </h1>
					<div class="table-responsive">
						<table class="table table-hover datatable-common">
							<thead>
								<tr>     
									<th> <?php echo __('S.no', true); ?></th>
									<th> <?php echo __('Purpose', true); ?></th>
									<th> <?php echo __('Date/Time', true); ?></th>
									<th> <?php echo __('Amount', true); ?></th>
									<th> <?php echo __('Transaction details', true); ?></th>
								</tr>
							</thead>
							<tbody><?php
			                    foreach($walletHistory as $key => $value) { ?>
									<tr>
										<td> <?php echo $key+1; ?> </td>
										<td> <?php echo $value['WalletHistory']['purpose']; ?> </td>
										<td> <?php echo $value['WalletHistory']['created']; ?> </td>
										<td> <?php 
											echo html_entity_decode($this->Number->currency($value['WalletHistory']['amount'], $siteCurrency)); ?> </td>
										<td> <?php echo $value['WalletHistory']['transaction_details']; ?> </td>
									</tr> <?php 
		                        } ?>
							</tbody>
						</table>
					</div>
				</div>

			</div>


			
			<div class="myorderTab" id="password_change_content" style="display:none;">

				<!-- User Email Change -->
				<h1> <?php echo __('Change User Email', true); ?></h1><?php
				echo $this->Form->create('Customer', array('class' => 'form-horizontal col-md-8',
															'controller'=>'Customers',
															'action'=>'changeCustomerEmail',
															'onsubmit' => 'return changeCustomerEmail();' )); ?>
					<div class="cardDetailHead"> <?php echo __('User Email', true); ?></div>
					<div class="form-group margin-t-25">
						<label class="control-label col-md-4"> <?php echo __('Current User Email', true); ?></label>
						<div class="col-md-8"><?php
							echo $this->request->data['User']['username'];  ?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"> <?php echo __('New User Email', true); ?></label>
						<div class="col-md-8"><?php
							echo $this->Form->input('Customer.customer_email',
								array('class'=>'form-control',
									'autocomplete' => 'off',
									'label' => false,
									'value' => false,
									'div' => false)); ?>
									<label class="error" id="userMailError"></label>
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-8 col-md-offset-4"> <?php
							echo $this->Form->button(__('Update'), array('class'=>'btn btn-primary')); ?>
						</div>					
					</div> <?php 
				echo $this->Form->end();?>



				<!-- Change Password -->
				<h1> <?php echo __('Password', true); ?></h1><?php
				echo $this->Form->create('Customer', array('class' => 'form-horizontal col-md-8','controller'=>'Customers','action'=>'changePassword')); ?>
					<div class="cardDetailHead"> <?php echo __('Change Password', true); ?></div>
				<label class="error passerror"></label>
					<div class="form-group margin-t-25">
						<label class="control-label col-md-4"> <?php echo __('Current Password', true); ?></label>
						<div class="col-md-8"><?php
							echo $this->Form->input('User.oldpassword',
								array('class'=>'form-control',
									'autocomplete' => 'off',
									'type'=>'password',
									'onBlur'=>'checking()',
									'label' => false,
									'div' => false)); ?>

						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"> <?php echo __('New Password', true); ?></label>
						<div class="col-md-8"><?php
							echo $this->Form->input('User.newpassword',
								array('class'=>'form-control',
									'autocomplete' => 'off',
									'type'=>'password',
									'label' => false,
									'div' => false)); ?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"> <?php echo __('Confirm Password', true); ?></label>
						<div class="col-md-8"><?php
							echo $this->Form->input('User.confirmpassword',
								array('class'=>'form-control',
									'autocomplete' => 'off',
									'type'=>'password',
									'label' => false,
									'div' => false)); ?>
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-md-8 col-md-offset-4"> <?php
							echo $this->Form->button(__('Update'), array('class'=>'btn btn-primary')); ?>
						</div>					
					</div>
				<?php echo $this->Form->end();?>
				
			</div>
			<div class="myorderTab" id="address_content" style="display:none;">
				<h1> <?php echo __('Address Book', true); ?><a class="pull-right btn btn-primary" href="javascript:void(0);" data-target="#addBookAddress" data-toggle="modal"><i class="fa fa-plus"></i>&nbsp; <?php echo __('Add new'); ?></a></h1>
				<div class="table-responsive">
					<table class="table table-hover datatable-common">
						<thead>
							<tr>     
								<th class="text-left"> <?php echo __('Address Title', true); ?></th> <?php
								if ($siteSetting['Sitesetting']['address_mode'] != 'Google') { ?>
									<th class="text-left"> <?php echo __('Street Address', true); ?></th>
									<th> <?php echo __('Zip code', true); ?></th> <?php
								} else { ?>
									<th class="text-left"> <?php echo __('Address', true); ?></th> <?php
								} ?>
								<th> <?php echo __('Status', true); ?></th>
								<th class="no-sort"> <?php echo __('Action', true); ?></th>
							</tr>
						</thead>
						<tbody><?php 
	                    if (!empty($addressBook)) {
		                    foreach($addressBook as $key => $value) { ?>
								<tr id="record<?php echo  $value['CustomerAddressBook']['id'];?>">
									<td class="text-left"><?php echo $value['CustomerAddressBook']['address_title']; ?></td> <?php
                                    if ($siteSetting['Sitesetting']['address_mode'] != 'Google') { ?>
                                        <td class="text-left"><?php echo $value['CustomerAddressBook']['address']; ?></td>
                                        <td><?php echo $value['Location']['zip_code']; ?></td> <?php
                                    } else { ?>
                                        <td class="text-left"> <?php echo $value['CustomerAddressBook']['google_address']; ?></td> <?php
                                    } ?>
		                            <td align="center"> <?php 
		                                    if($value['CustomerAddressBook']['status'] == 0) {?>
		                                        <a title="Deactive" class="buttonStatus red_bck" href="javascript:void(0);" 
		                                        onclick="statusChange(<?php echo $value['CustomerAddressBook']['id'];?>,'customeraddress');">
		                                    <i class="fa fa-times"></i><!-- deactive --></a>
		                                    <?php } else {
		                                    ?>
		                                        <a title="active" class="buttonStatus" href="javascript:void(0);" 
		                                        onclick="statusChange(<?php echo $value['CustomerAddressBook']['id'];?>,'customeraddress');">
		                                    <i class="fa fa-check"></i></a>
		                                    <?php }?>
		                            </td>
									<td align="center">
										<a href="javascript:void(0);"  id="edit" 
		                                            onclick="customerAddressBookEdit(<?php echo $value['CustomerAddressBook']['id'];?>)"><i class="fa fa-edit"></i></a> / 
										<a href="javascript:void(0);" onclick="customerdelete(<?php echo $value['CustomerAddressBook']['id'];?>,'customeraddress')"><i class="fa fa-trash"></i></a>
									</td>
								</tr> <?php 
	                        }
	                    }  ?>
						</tbody>
					</table>
				</div>
			</div>

			<!-- <div class="myorderTab" id="favourite_content" style="display:none;">
				<h1> <?php echo __('Favourites', true); ?></h1>
				<div class="table-responsive">
					<table class="table table-hover datatable-common">
						<thead>
							<tr>     
								<th> <?php echo __('Store Name', true); ?></th> 
								<th> <?php echo __('Added date', true); ?></th>
								<th> <?php echo __('Action', true); ?></th>
							</tr>
						</thead>
						<tbody><?php 
			            if (!empty($fav_detail)) {
			                foreach($fav_detail as $key => $value) { ?>
								<tr id="favrecord_<?php echo  $value['MyFavourite']['id'];?>">
									<td class="text-left"><a href="<?php echo $siteUrl.'/shop/'.$value['Store']['seo_url'].'/'.base64_encode($value['Store']['id']);?>"><?php echo $value['Store']['store_name']; ?></a></td> 
									<td class="text-left"><?php echo $value['MyFavourite']['created']; ?></td>
									<td align="center">
										<a href="javascript:void(0);" onclick="deleteFav(<?php echo $value['MyFavourite']['id'];?>)">X</a>										
									</td>
								</tr> <?php 
			                }
			            }  ?>
						</tbody>
					</table>
				</div>
			</div> -->
		</div>
	</div>
</div>

<div class="modal fade" id="reviewPopup">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"> <?php echo __('Review', true); ?></h4>
			</div>
			<div class="modal-body">
				
	            <div class="form-group clearfix">
					<label class="col-sm-4 control-label text-right" > <?php echo __('Rating', true); ?></label><?php 
					echo $this->Form->create('review',array('class'=>"form-horizontal"));?>
					<div class="col-sm-7 margin-t-5">
						<div class="stars inline-block"><?php 
							$option1 = array('1'  => '1');
		                    $option2 = array('2'   => '2');
		                    $option3 = array('3'   => '3');
		                    $option4 = array('4'   => '4');
		                    $option5 = array('5'   => '5');
		                    
		                    echo $this->Form->radio('rating',$option1,
		                                    array('label' => array('class' => 'star-1'),
		                                      
		                                      'legend'=>false,
		                                      'class'=>'star-1',                        
		                                      'hiddenField'=>false));
							echo $this->Form->radio('rating',$option2,
		                                    array('label' => array('class' => 'star-2'),
		                                      
		                                      'legend'=>false,
		                                      'class'=>'star-2', 
		                                      'hiddenField'=>false));
							echo $this->Form->radio('rating',$option3,
		                                    array('label' => array('class' => 'star-3'),
		                                      
		                                      'legend'=>false,
		                                      'class'=>'star-3', 
		                                      'checked' =>'checked',
		                                      'hiddenField'=>false));
							echo $this->Form->radio('rating',$option4,
		                                    array('label' => array('class' => 'star-4'),
		                                      
		                                      'legend'=>false,
		                                      'class'=>'star-4', 
		                                      'hiddenField'=>false));
							echo $this->Form->radio('rating',$option5,
		                                    array('label' => array('class' => 'star-5'),
		                                      
		                                      'legend'=>false,
		                                      'class'=>'star-5', 
		                                      'hiddenField'=>false));
		                     echo $this->Form->hidden('id');?>

		                     <span></span>
						</div>
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-sm-4 text-right"> <?php echo __('Message', true); ?></label> 
					<div class="col-sm-7 margin-t-5"><?php
					 echo $this->Form->input('message',
                          array('class'=>'form-control',
                              'label'=>false));?>
					</div>
				</div>
				<div class="form-group clearfix">
                    <div class="col-sm-offset-4 col-sm-7">
                        <?php echo $this->Form->button(__('<i class="fa fa-check"></i>'.__('Submit')),array('class'=>'btn purple'));  ?>
                    </div>
                </div>
				
				<?php echo $this->Form->end();?>

			</div>
		</div>
	</div>
</div>			
		
<div class="modal fade" id="addBookAddress">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"> <?php echo __('Add Address', true); ?></h4>
			</div> <?php
            echo $this->Form->create("CustomerAddressBook",
                                        array("id"=>'AddCustomerAddressBook',
                                              "url"=>array("controller"=>'Customers','action'=>'addAddressBook')));?>
				<div class="modal-body"> 
					<label  class="error checkAdderorr"></label>
					<div class="form-group clearfix">
						<label class="control-label col-md-4"> <?php echo __('Address Title', true); ?></label>
						<div class="col-md-7"><?php
											echo $this->Form->input('address_title',
													array('class'=>'form-control',
															'label'=>false)); ?>
						</div>
					</div>
                    <div class="form-group clearfix">
                        <label class="control-label col-md-4"> <?php echo __('Address Phone', true); ?></label>

                        <div class="col-md-7"><?php
                            echo $this->Form->input('address_phone',
                                array('class' => 'form-control',
                                    'label' => false)); ?>
                        </div>
                    </div> <?php
                    if ($siteSetting['Sitesetting']['address_mode'] != 'Google') { ?>
                        <div class="form-group clearfix">
                            <label class="control-label col-md-4"> <?php echo __('Street Address', true); ?></label>

                            <div class="col-md-7"><?php
                                echo $this->Form->input('address',
                                    array('class' => 'form-control',
                                        'type' => 'text',
                                        'label' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="control-label col-md-4"> <?php echo __('Apt/Suite/Building', true); ?></label>

                            <div class="col-md-7"><?php
                                echo $this->Form->input('landmark',
                                    array('class' => 'form-control',
                                        'label' => false)); ?>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label class="control-label col-md-4"> <?php echo __('State', true); ?></label>

                            <div class="col-md-7"><?php
                                echo $this->Form->input('state_id',
                                    array('type' => 'select',
                                        'class' => 'form-control',
                                        'options' => array($state_list),
                                        'onchange' => 'cityFillters();',
                                        'empty' => __('Select State'),
                                        'label' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="control-label col-md-4"> <?php echo __('city', true); ?></label>

                            <div class="col-md-7"><?php
                                echo $this->Form->input('city_id',
                                    array('type' => 'select',
                                        'class' => 'form-control',
                                        'onchange' => 'locationFillters();',
                                        'empty' => __('Select City'),
                                        'label' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="control-label col-md-4"> <?php echo __('Area/zipcode', true); ?></label>

                            <div class="col-md-7"><?php
                                echo $this->Form->input('location_id',
                                    array('type' => 'select',
                                        'class' => 'form-control',
                                        'empty' => __('Select Area/Zip'),
                                        'label' => false)); ?>
                            </div>
                        </div> <?php
                    } else { ?>
                        <div class="form-group clearfix">
                            <label class="control-label col-md-4"> <?php echo __('Address', true); ?></label>

                            <div class="col-md-7"><?php
                                echo $this->Form->input('google_address',
                                    array('class' => 'form-control',
                                        'type' => 'text',
                                        'label' => false,
                                        'onfocus' =>'initialize(this.id)')); ?>
                            </div>
                        </div> <?php
                    } ?>
					<div class="form-group clearfix"> 
						<label class="control-label col-md-4">&nbsp;</label>
						<div class="col-md-7 signup-footer"> <?php 
							echo $this->Form->button(__('Submit'),
									array("label"=>false,
											"class"=>"btn btn-primary",
											'onclick' => 'return addAddressCheck();',
											"type"=>'submit')); ?>
						</div>
					</div>
				</div> <?php
			echo $this->Form->end(); ?>
		</div>
	</div>
</div>
		
<div class="modal fade" id="editBookAddress">

</div>
		
<div class="modal fade" id="addpayment">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"> <?php echo __('Add New Payment', true); ?></h4>
			</div>
			<div class="modal-body"> <?php
				echo $this->Form->create('User',
							array('class'  => 'form-horizontal paymentTab',
									'name' => 'StripeForm',
									'id'   => 'UserIndexForm',
									"url"=> array("controller" => 'customers',
                              					 'action' 	   => 'customerCardAdd'))); ?>
                <div class="text-center">
					<label id="error" class="margin-b-15"></label>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4"> <?php echo __('Name on Card', true); ?><span class="star">*</span> :</label>
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
					<label class="control-label col-md-4"> <?php echo __('Card Number', true); ?><span class="star">*</span>
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
												'maxlength' => 16,
												'value' => '',
												'placeholder' => 'XXXX-XXXX-XXXX-XXXX')); ?>
							<span class="input-group-addon input-group-valid"><i class="fa fa-check"></i></span>
							<span class="input-group-addon input-group-card-icon"><i class="fa fa-credit-card"></i></span>
						</div>
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4"> <?php echo __('CVV', true); ?><span class="star">*</span>
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
					<label class="control-label col-md-4"> <?php echo __('Expiry Date', true); ?><span class="star">*</span> : </label>
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
</div>



<div class="modal fade" id="trackid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button data-dismiss="modal" class="close" type="button">
					<span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title center" id="myModalLabel"><?php echo __('Track Order'); ?></h4>
			</div>
			<div class="modal-body" >
				<div id="trackingDistance"> </div>
				<input type="hidden" id="trackOrderId" value=""/>
				<div id="initialmap">
					<?php 
	                  //echo $this->GoogleMap->map(); 
	                ?>
				</div>
				<div id="TrackingMap"></div>
			</div>
		</div>
	</div>
</div>