<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleApiKey; ?>&v=3&sensor=false&amp;libraries=places,geometry"></script> 
<?php $storeGrandtotal = 0; 
echo $this->Form->create('Order', array('controller' => 'checkouts',
											'id' => 'checkoutOrder',
											'name' => 'OrderForm',
											'action' => 'conformOrder')); 						
echo $this->Form->input('stores',array('type' => 'hidden','id' => 'checkout','value' => 'checkout'));
echo $this->Form->input('store_id',array('type' => 'hidden','id' => 'store_id','value' => $storeDetails['Store']['id'])); 
echo $this->Form->input('stripetoken',array('type' => 'hidden','id' => 'stripetoken','value' => '')); ?>

<section class="checkout_banner">
    <div class="container">
        <div class="col-sm-12 text-center margin-top-25-xs">
            <h1 class="check_head"><?php echo __('Carry out your order');?></h1>
        </div>
    </div>
</section>
<section class="contact_list">
    <div class="container">
        <div class="chklink_top">
            <div class="col-sm-6">
                <span class="process1 bold"><a href="#"><?php echo __('Your User Information');?></a></span>
                <span>></span>
                <span class="process2"><a href="#"><?php echo __('Payment');?></a></span>
                <span>></span>
                <span class="process3"><a href="#"><?php echo __('Confirmation');?></a></span>
            </div>
        </div>
    </div>
</section>
<section class="checkout_inner">
    <div class="container">
        <div class="col-md-2 no-padding-left">
            <!-- <div class="left_credit">
                    <h3><?php echo __('Wallet');?></h3>
                    
                    
                    <a href="#" data-toggle="modal" data-target="#myModal"><?php echo __('What is Wallet?');?></a>


                    <div class="modal fade" id="myModal" role="dialog">
                            <div class="modal-dialog">
                            
                              
                                    <div class="modal-content">
                                            <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                              <h4 class="modal-title"><?php echo __('What is Wallet?');?></h4>
                                            </div>
                                            <div class="modal-body">
                                              <p>Lorem ipsum is the dummy text</p>
                                            </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                    </div>
                            </div>
                    </div>
            </div> -->
            <div class="leftside_help bookleftside_help blh_new text-center mar-t-35">
                <h3><?php echo __('Need help');?></h3>
                <h1><?php echo $siteSetting['Sitesetting']['contact_phone'];?></h1>
                <!-- <p><?php echo __('All days');?> ml:8-23</p> -->
            </div>
        </div>



        <div class="col-md-6 no-padding">

            <div id="deliverAddress">

                <div class="col-sm-12 center_float">
                    <h3 class="check_form_head dine_head"><?php echo __('Your User Information');?></h3>
                    <div class="check_form">

                        <div class="text-center">
                            <label id="locationError" class="error"></label>
                        </div>
                        <div class="form-group check_fgroup">
                            <label class="control-label cl_font" for="fname"><?php echo __('First Name');?><span class="star">*</span></label>
								<?php
								// echo "<pre>";print_r($this->Session->read());echo "</pre>";
									echo $this->Form->input('Customer.first_name', 
												array('type' => 'text',
													  'label' => false,
													  'class'=> "form-control",
													  'placeholder'=> __('First Name'),
													  'value' => $customerDetails['Customer']['first_name']));?>
                        </div>
                        <div class="form-group check_fgroup">
                            <label class="control-label cl_font" for="lname"><?php echo __('Last Name');?><span class="star">*</span></label>

								<?php
									echo $this->Form->input('Customer.last_name', 
												array('type' => 'text',
													  'label' => false,
													  'class'=> "form-control",
													  'placeholder'=> __('Last Name'),
													  'value' => $customerDetails['Customer']['last_name']));?>
                        </div>
                        <div class="form-group check_fgroup">
                            <label class="control-label cl_font" for="ph-no"><?php echo __('Phone');?><span class="star">*</span></label>

								<?php
									echo $this->Form->input('Customer.customer_phone', 
												array('type' => 'text',
													  'label' => false,
													  'class'=> "form-control",
													  'placeholder'=> __('Phone'),
													  'value' => $customerDetails['Customer']['customer_phone']));?>
                        </div>
                        <div class="form-group check_fgroup">
                            <label class="control-label cl_font" for="email"><?php echo __('Email');?><span class="star">*</span></label>

								<?php
									echo $this->Form->input('Customer.customer_email', 
												array('type' => 'text',
													  'label' => false,
													  'class'=> "form-control",
													  'placeholder'=> __('Email'),
													  'value' => $customerDetails['Customer']['customer_email']));?>
                        </div>



                        <div class="form-group check_fgroup">
                            <label class="control-label cl_font col-sm-12 no-padding" for="address"><?php echo __('Address');?></label>
                            <div class="col-sm-7 no-padding">
								<?php
									if ($siteSetting['Sitesetting']['address_mode'] != 'Google') { 

										echo $this->Form->input('CustomerAddressBook.address', 
												array('type' => 'text',
													  'label' => false,
													  'class'=> "form-control",
													  'placeholder'=> __('Your entire address'),
													  'readonly' => true,
													  'value' => $addresses[0]['CustomerAddressBook']['address']));										
								    } else {
	                                	echo $this->Form->input('google_address',
		                                    array('type'  => 'select',
		                                          'class' => 'form-control',
		                                          'options'=> array($addresslist),
		                                          'id' => 'selectaddress',
		                                          'onchange' => 'return checkAddress('.$storeId.');',
		                                          'empty' => __('Select Address'),
		                                          'label'=> false));
							    	}?>
                            </div>

                            <span class="pull-right">
                                <a class="addnewAdrr" data-toggle="modal" data-target="#addDeliverAddress" href="javascript:void(0);">
											<?php echo __('Add Address');?>
                                </a>
                            </span>
                        </div>						
                        <div class="check_cont">
                            <div class="col-sm-12 no-padding checkcont_mar_left"><?php
										$option1 = array('Now' 	=> '');
                                        $option2 = array('Later' => '');
                                        if ($openCloseStatus != 'Close' && ($firstStatus == 'Open' || $secondStatus == 'Open')) { ?>
                                <label class="rdio_classnew radio_classreduce"> <?php
                                                echo $this->Form->radio('assoonas',$option1,
                                                        array('checked'=>$option1,
                                                                'label'=>false,
                                                                'legend'=>false,
                                                                'class'=>'hidden',
                                                                'name' => 'data[Order][assoonas]',
                                                                'hiddenField'=>false)); ?>
                                    <span class="rdimg font_later"><?php echo __('As soon as possible');?></span>
                                </label> <?php
                                        } ?>
                                <label class="rdio_classnew radio_classreduce">  <?php
                                            echo $this->Form->radio('assoonas',$option2,
                                                    array('checked'=>$option2,
                                                            'label'=>false,
                                                            'legend'=>false,
                                                            'class'=>'hidden',
                                                            'name' => 'data[Order][assoonas]',
                                                            'checked' => 'checked',
                                                            'hiddenField'=>false)); ?>
                                    <span class="rdimg font_later"><?php echo __('Later'); ?></span>
                                </label>
                            </div>
                        </div>						

                        <div id="showCalendar">
                            <div class="col-sm-6">
                                <label class="control-label cl_font" for="leverings"><?php echo __('Delivery Date');?></label>
                                <div class="input-group"> <?php
                                        echo $this->Form->input('', [
                                                'class' => 'form-control address-customer-notes',
                                                'label'=>false,
                                                'placeholder' => $currentDate,
                                                'type' => 'text',
                                                'id' => 'latertime',
                                                'name' => 'data[Order][delivery_date]',
                                                'value' => $currentDate
                                            ]
                                        ); ?>
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i> </div>
                                </div>
                            </div>
                            <div class="col-sm-6" >
                                <label class="control-label cl_font" for="levering"><?php echo __('Delivery Time');?></label>
                                <select class="form-control" id="getDateTime" name="data[Order][delivery_time]">
										<?php echo $timeContent; ?>
                                </select>
                            </div>
                        </div> 
                        <div class="form-group mar-t-35 check_fgroup">
                            <label class="control-label cl_font" for="comment"><?php echo __('Comments');?></label><?php
									echo $this->Form->input('order_description',
												array('class' => 'form-control address-customer-notes',
													  'placeholder' => __('Allergy information'),
													  'label'=>false,
													  'rows' => 2)); ?>
                        </div>						
                    </div>
                </div>
            </div>


            <div class="col-sm-12" id="paymentConfirm" style="display: none;">
                <h3 class="check_form_head dine_head"><?php echo __('Payment Method');?></h3>
                <div class="check_form">
                    <div class="form-group nr_fg nr_fg_three active">

                        <label class="checkpayhead" for="creditcard">
                            <input class="hidden" type="radio" name="data[Order][paymentMethod]" id="creditcard" value="creditcard" checked="checked" onclick="walletCheck(); showTip('Card');">
                            <i class="fa fa-credit-card"></i><?php echo __('credit card');?>
                        </label>

                        <div id="card_savenew" class="form-group nr_fg_three checkcardbg">
                            <label class="rdio_classnew" for="saved">
                                <input class="hidden" type="radio" id="saved" name="cardselect" value="saved" checked="checked" onclick="alreadyCard();"/>
                                <span class="rdimg"><?php echo __('Saved Card');?></span>
                            </label>
                            <label class="rdio_classnew" for="new">
                                <input class="hidden" type="radio" id="new" name="cardselect" value="new" onclick="addnewCard();"/>
                                <span class="rdimg"><?php echo __('New card');?></span>
                            </label>

					    <?php

					    echo $this->Form->input('paymentMethod',array('type' => 'hidden','name' => 'data[Order][stripepayment_id]','id' => 'stripepayment_id','value' => ''));?>&nbsp;
                            <div id="already" class="col-xs-7">

								<?php	
                                	echo $this->Form->input('payment',
	                                    array('type'  => 'select',
	                                          'class' => 'form-control',
	                                          'options'=> (!empty($stripeCards)) ? array($stripeCards) : '',
	                                          'id' => 'selectcard',
	                                          'name' => 'data[Order][paymentMethod]',
	                                          'empty' => __('Select Card'),
	                                          'label'=> false)); ?>

                            </div>&nbsp;
                            <div class="text-center">
                                <label id="error"></label>
                            </div>
                            <div id="newCard" style="display: none;">
                                <div class="form-group">
                                    <label class="control-label cl_font" for="navn"><?php echo __('Card name');?></label>
									<?php
											echo $this->Form->input("Card.Name",
													array("type"=>"text",
															"label"=>false,
															'data-stripe' => 'name',
															"class"=>"form-control",
															'placeholder' => __('First and last name (must match the card)'),
															'value' => '')); ?>
                                </div>
                                <div class="form-group">
                                    <label class="control-label cl_font" for="navn"><?php echo __('Card Number');?></label>

									<?php 
											echo $this->Form->input("Card.number",
												array("type"=>"text",
													"label"=>false,
													'data-stripe' => 'number',
													"class"=>"form-control intnumber",
													'height' => 40,
													'value' => '',
													'maxlength' => 16,
													'placeholder' => '....... 2342')); ?>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label cl_font" for="month"><?php echo __('Expire date');?></label>
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
																	'placeholder' => 'mm',
																	"class"=>"form-control valid")); 
											?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label cl_font" for="month"><?php echo __('Year');?></label><?php
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
															'placeholder' => 'yyyy',
															"class"=>"form-control valid"));?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label cl_font" for="ccv" style="font-size:13px"><?php echo __('safety');?></label>
										<?php 
													echo $this->Form->input("Card.cvv",	
														array("type"=>"password",
																"label"=>false,
																'data-stripe' => 'cvc',
																"class"=>"form-control",
																'placeholder' => 'CCV',
																'value' => '')); ?>


                                    </div>
                                </div>
                                <div class="form-group">						
                                    <div class="col-xs-12 paymentInfo">
                                        <input type="checkbox" class="savecard"> <?php echo __('Save card');?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>	
                    <div class="form-group nr_fg nr_fg_two">
                        <label class="checkpayhead" for="cod">
                            <div onclick="showTip('Cash');">
                                <input class="hidden" type="radio" name="data[Order][paymentMethod]" id="cod" value="cod" onclick="walletCheck();"><i class="fa fa-truck"></i><?php echo __('Cash on delivery');?>
                            </div>
                        </label>
                    </div>

                    <div class="form-group nr_fg nr_fg_two">
                        <label class="checkpayhead" for="paypal">
                            <div onclick="showTip('paypal');">
                                <input class="hidden" type="radio" name="data[Order][paymentMethod]" id="paypal" value="paypal" onclick="walletCheck();"><i class="fa fa-paypal"></i><?php echo __('Paypal');?>
                            </div>
                        </label>
                    </div>



                    <div class="form-group nr_fg nr_fg_three">
                        <label class="checkpayhead">
                            <input class="hidden" type="radio" name="data[Order][paymentMethod]" value="wallet" onclick="walletCheck(); showTip('Wallet');"><i class="fa fa-google-wallet"></i><?php echo __('Wallet');?>
                        </label>				    			
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label class="control-label cl_font" for="konto"><?php echo __('Account');?> (<?php echo $siteCurrency; ?>)</label>
                            <input type="text" class="form-control bg_eee" id="WalletAmount" placeholder="<?php echo $customerDetails['Customer']['wallet_amount'];?>" readonly="readonly" value="<?php echo $customerDetails['Customer']['wallet_amount'];?>">
                        </div>
                    </div>
                    <label id="walletError"></label>

                    <div id="tipOption">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="cardDetailHead">
                                    <div class="clearfix">
                                        <div class="tiphead">
                                            		<?php echo __('Tip', true); ?>
                                        </div>
                                        <div class="col-sm-3 input-group">

                                            <div class="input-group-addon"> <?php echo $siteCurrency; ?> </div>

                                                 <?php
	                                                echo $this->Form->input('tip_percentage', [
	                                                    'type' => 'text',
	                                                    'label' => false,
	                                                    'class' => "form-control",
														'placeholder' => __('Price'),
	                                                    'name' => 'data[Order][tip_percentage]',
	                                                    'hiddenField' => false
	                                                ]); ?>

                                        </div>
                                    </div>
                                    <div id="tipError" class="error"></div>
                                </div>
                            </div>
                        </div>
                    </div>			
                </div>
            </div>			
        </div>


        <div id="checkoutdetailswrapper">
            <div class="col-md-4 no-padding">

            </div>
        </div>

    </div>
</section><?php

echo $this->Form->end(); ?>


<div class="modal fade" id="addDeliverAddress">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"> 
					<?php echo __('Add New Deliver Address'); ?></h4>
            </div> <?php
			echo $this->Form->create("CustomerAddressBook",
                                  array("id"=>'AddCustomerAddressBook',
                                  		"url"=>array("controller"=>'checkouts',
                                  		'action' => 'customerBookAdd'))); ?>
            <div class="modal-body"> 
                <label  class="error checkAdderorr"></label>
                <div class="form-group clearfix">
                    <label class="control-label col-md-4"><?php echo __('Address Title', true); ?></label>
                    <div class="col-md-8"> <?php
							echo $this->Form->input('address_title',
									array('class'=>'form-control',
											'label'=>false,
											'value' => '')); ?>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <label class="control-label col-md-4"><?php echo __('Address Phone', true); ?></label>
                    <div class="col-md-8"> <?php
							echo $this->Form->input('address_phone',
									array('class'=>'form-control',
											'label'=>false,
											'value' => '')); ?>
                    </div>
                </div> <?php
                    if ($siteSetting['Sitesetting']['address_mode'] != 'Google') { ?>
                <div class="form-group clearfix">
                    <label class="control-label col-md-4"><?php echo __('Street Address', true); ?></label>
                    <div class="col-md-8"> <?php
                                echo $this->Form->input('address',
                                    array('class'=>'form-control',
                                        'label'=>false,
                                        'type' => 'text',
                                        'value' => '')); ?>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <label class="control-label col-md-4"><?php echo __('Apt/Suite/Building', true); ?></label>
                    <div class="col-md-8"> <?php
                                echo $this->Form->input('landmark',
                                        array('class'=>'form-control',
                                                'label'=>false,
                                                'value' => '')); ?>
                    </div>
                </div>

                <div class="form-group clearfix">
                    <label class="control-label col-md-4"><?php echo __('State', true); ?></label>
                    <div class="col-md-8"> <?php
                                echo $this->Form->input('state_id',
                                    array('type'  => 'select',
                                          'class' => 'form-control',
                                          'options'=> array($customerState),
                                          'onchange' => 'citiesList();',
                                          'empty' => __('Select State'),
                                          'label'=> false,
                                          'value' => '')); ?>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <label class="control-label col-md-4"><?php echo __('city', true); ?></label>
                    <div class="col-md-8"> <?php
                                echo $this->Form->input('city_id',
                                    array('type'  => 'select',
                                          'class' => 'form-control',
                                          'onchange' => 'locationLists();',
                                          'empty' => __('Select City'),
                                          'label'=> false)); ?>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <label class="control-label col-md-4"><?php echo __('Area/zipcode', true); ?></label>
                    <div class="col-md-8"> <?php
                                echo $this->Form->input('location_id',
                                        array('type'  => 'select',
                                              'class' => 'form-control',
                                              'empty' => __('Select Location'),
                                              'label'=> false));  ?>
                    </div>
                </div> <?php
                    } else { ?>
                <div class="form-group clearfix">
                    <label class="control-label col-md-4"><?php echo __('Address', true); ?></label>
                    <div class="col-md-8"> <?php
                                echo $this->Form->input('google_address',
                                    array('class'=>'form-control',
                                        'label'=>false,
                                        'type' => 'text',
                                        'value' => '',
                                        'onfocus' =>'initialize(this.id)')); ?>
                    </div>
                </div> <?php
                    } ?>

                <div class="form-group clearfix">
                    <div class="col-md-8 col-md-offset-4 signup-footer"> <?php 
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




<script type="text/javascript">

//Checkout Page Hide and Show Process
    function checkoutpagintaion(page1, page2) {

        var delivery = $('#OrderAssoonasLater').is(':checked');
        var deliveryTime = $('#getDateTime').val();

        var orderType = $('input[name="data[Order][orderType]"]:checked').val();
        var address = $('#selectaddress').val();

        var CustomerFirstName = $('#CustomerFirstName').val();
        var CustomerLastName = $('#CustomerLastName').val();
        var CustomerCustomerPhone = $('#CustomerCustomerPhone').val();
        var CustomerCustomerEmail = $('#CustomerCustomerEmail').val();

        if (page1 == '#deliverAddress') {

            if (CustomerFirstName == '') {
                $('#locationError').html('<?php echo __('Please enter firstname');?>');
                $('#CustomerFirstName').focus();
                return false
            } else if (CustomerLastName == '') {
                $('#locationError').html('<?php echo __('Please enter lastname'); ?>');
                $('#CustomerLastName').focus();
                return false
            } else if (CustomerCustomerPhone == '') {
                $('#locationError').html('<?php echo __('Please enter phone number');?>');
                $('#CustomerCustomerPhone').focus();
                return false
            } else if (CustomerCustomerEmail == '') {
                $('#locationError').html('<?php echo __('Please enter email');?>');
                $('#CustomerCustomerEmail').focus();
                return false
            } else {
                $.post(rp + 'checkouts/deliveryLocation', {'id': address, 'orderTypes': orderType}, function (response) {
                    if (response == 'fail') {
                        (address != '') ? $('#locationError').html('<?php echo __('This restaurant wont deliver to your adddress please choose another one');?>') : $('#locationError').html('<?php echo __('Please select address');?>');
                        $('#selectaddress').focus();
                        $('#checkdeliverytab').hide();
                        checkoutCart();
                        return false
                    } else {
                        $('#locationError').html('');
                        $('#checkdeliverytab').show();
                        if (delivery && deliveryTime == "") {
                            $('#locationError').html('<?php echo __('Restaurant is closed'); ?>');
                            $('#getDateTime').focus();
                            return false;
                        } else if(response == "0") {
                            $(page1).hide();
                            $(page2).show();
                            $('#pagebtn').hide();
                            $('#orderbtn').show();
                            $('#backlink').hide();
                            $('#backbtn').show();

                            $('.chk_step1').removeClass('active');
                            $('.chk_step2').addClass('active');

                            $('.process1').removeClass('bold');
                            $('.process2').addClass('bold');
                        }else{
                            $('#locationError').html('À cet endroit, €' + response + " Est la commande minimale que vous devez faire");
                            checkoutCart();
                            return false;
                        }
                    }
                });
            }

        } else if (page1 == '#paymentConfirm') {
            $(page1).hide();
            $(page2).show();
            $('#pagebtn').show();
            $('#orderbtn').hide();
            $('#backlink').show();
            $('#backbtn').hide();

            $('.chk_step2').removeClass('active');
            $('.chk_step1').addClass('active');

            $('.process2').removeClass('bold');
            $('.process1').addClass('bold');
        }

    }


    function submitOrder() {

        var paymentMethod = $('input[name="data[Order][paymentMethod]"]:checked').val();
        var cardtype = $('input[name="cardselect"]:checked').val();
        var selectcard = $('#selectcard').val();
        var grandTotal = $('.orderGrandTotal').val();
        var walletAmount = $('#WalletAmount').val();

        if (paymentMethod == 'creditcard') {
            if (cardtype == 'saved') {
                if (selectcard == '') {
                    $('#error').html("<?php echo __('Please select any card');?>");
                    $('#error').addClass('error');
                    return false;
                } else if (selectcard != '') {
                    $('#checkoutOrder').submit();
                    $("#orderbtn").attr('disabled', 'disabled');
                    return false
                }
            } else if (cardtype == 'new') {
                $('#already').hide();
                $('#newCard').show();
                saveCardDetails();
                return false
            }
        } else if (paymentMethod == 'cod') {
            $('#checkoutOrder').submit();
            $("#orderbtn").attr('disabled', 'disabled');
            return false
        } else if (paymentMethod == 'paypal') {
            $('#checkoutOrder').submit();
            $("#orderbtn").attr('disabled', 'disabled');
            return false
        } else if (paymentMethod == 'wallet') {
            if (parseFloat(grandTotal) < parseFloat(walletAmount)) {
                $('#checkoutOrder').submit();
                $("#orderbtn").attr('disabled', 'disabled');
                return true;
            } else {
                $('#walletError').addClass('error');
                $('#walletError').html('<?php echo __('Insuficient wallet amount please try another payment'); ?>');
                return false;
            }
        }
        return false
    }


    function voucherCheck(storeId) {
        $('.enable_' + storeId).removeAttr('onclick');
        $('#couponMessage_' + storeId).html('');
        var voucherCode = $('#voucher_' + storeId).val();

        var orderType = $('input[name="data[Order][orderType]"]:checked').val();
        var addressId = $('#selectaddress').val();

        if (voucherCode != '') {
            $.post(rp + 'checkouts/voucherCheck', {'voucherCode': voucherCode,
                'storeId': storeId,
                'addressId': addressId,
                'orderType': orderType}, function (response) {

                voucherResponse = response.split('||');
                response = response.split('@@');

                if (response[0] != 'Failed') {
                    $('#voucher_' + storeId).attr('readonly', true);
                    $('#vouchers_' + storeId).val(voucherCode);
                    $('#voucher_' + storeId).val(voucherCode);

                    if (voucherResponse[3] == 'freeDelivery') {
                        $('.freeDelivery_' + storeId).hide();
                        $('#checkdeliverytab').hide();
                        $('.free_' + storeId).show();
                    } else {
                        // Voucher row Show
                        $('.voucherShow_' + storeId).show();

                        // Percentage or Price
                        if (voucherResponse[1] > 0) {
                            $('#voucherper').append(' (' + voucherResponse[1] + '%)');
                            // $('#voucherPercentage_'+storeId).append('('+voucherResponse[1]+'%)');
                        }

                        voucherResponse[2] = parseFloat(voucherResponse[2]).toFixed(2);
                        $('#voucherPrice_' + storeId).html('<?php echo $siteCurrency;?>' + format(voucherResponse[2]));
                    }

                    // Store based total
                    var total = $('.storeTotal_' + storeId).val();
                    var nowStoreTotal = parseFloat(total) - parseFloat(voucherResponse[2]);
                    nowStoreTotal = parseFloat(nowStoreTotal).toFixed(2);

                    $('.storeTotal_' + storeId).val(nowStoreTotal);
                    $('.storeSubTotal_' + storeId).html('<?php echo $siteCurrency;?>' + format(nowStoreTotal));

                    // Voucher Total
                    $('.voucherTotal_' + storeId).val(voucherResponse[2]);
                    // Grand Total
                    var orderGrandTotal = $('.orderGrandTotal').val();
                    var grandTotal = parseFloat(orderGrandTotal) - parseFloat(voucherResponse[2]);
                    grandTotal = parseFloat(grandTotal).toFixed(2);

                    $('.orderGrandTotal').val(grandTotal);
                    $('.grandTotal').html(format(grandTotal));

                    // Message
                    $('#couponType_' + storeId).html(response[2]);
                    $('#couponMark_' + storeId).html('');
                    $('#couponMark_' + storeId).append(
                            '<span onclick="removeCoupon(' + storeId + ');" class="btn btn-danger btn-sm spacing_new">' +
                            '<i class="fa fa-times"></i></span>');
                    $('#couponMessage_' + storeId).append("<label class='alert-success'>" + response[1] + "</label>");

                } else {
                    $('#couponMessage_' + storeId).append("<label class='error'>" + response[1] + "</label>");
                    $('.enable_' + storeId).attr('onclick', 'voucherCheck(' + storeId + ')');
                }
            });
        } else {
            $('#couponMessage_' + storeId).append("<label class='error'> <?php echo __('Please enter voucher code'); ?></label>");
            $('.enable_' + storeId).attr('onclick', 'voucherCheck(' + storeId + ')');
        }
    }


    function removeCoupon(storeId) {

        var voucherCode = $('#voucher_' + storeId).val();

        var orderType = $('input[name="data[Order][orderType]"]:checked').val();
        var addressId = $('#selectaddress').val();

        $('#voucher_' + storeId).val('');
        $('#vouchers_' + storeId).val('');
        $('#voucher_' + storeId).attr('readonly', false);
        $('#couponMessage_' + storeId).html('');
        $('#couponMark_' + storeId).html('');
        $('#couponMark_' + storeId).append(
                '<a class="btn btn-primary spacing_new enable_' + storeId + '" onclick="voucherCheck(' + storeId + ');" href="javascript:void(0);"> <i class="fa fa-check"></i></a>');

        $('.voucherShow_' + storeId).hide();
        $('.free_' + storeId).hide();
        $('.freeDelivery_' + storeId).show();

        // $('#checkdeliverytab').show();

        $.post(rp + 'checkouts/voucherCheck', {'voucherCode': voucherCode,
            'storeId': storeId,
            'addressId': addressId,
            'orderType': orderType}, function (response) {

            voucherResponse = response.split('||');
            response = response.split('@@');

            if (voucherResponse[3] == 'freeDelivery') {
                if (voucherResponse[2] != '' && voucherResponse[2] != 0) {
                    $('#checkdeliverytab').show();
                } else {
                    $('.voucherTotal_' + storeId).val(0);
                    $('#checkdeliverytab').hide();
                }
            } else {
                $('#voucherper').html('<?php echo __('Gift Voucher');?>');
            }
        });

        $('#voucherPercentage_' + storeId).html('');

        var voucherTotal = $('.voucherTotal_' + storeId).val();
        var grandTotal = $('.orderGrandTotal').val();

        var grandTotal = parseFloat(grandTotal) + parseFloat(voucherTotal);
        grandTotal = parseFloat(grandTotal).toFixed(2);

        $('.orderGrandTotal').val(grandTotal);
        $('.grandTotal').html(grandTotal);

        var nowStoreTotal = $('.storeTotal_' + storeId).val();
        var nowStoreTotal = parseFloat(nowStoreTotal) + parseFloat(voucherTotal);
        nowStoreTotal = parseFloat(nowStoreTotal).toFixed(2);
        $('.storeSubTotal_' + storeId).html(format(nowStoreTotal));

        $('.storeTotal_' + storeId).val(nowStoreTotal);
    }

    function walletCheck() {

        var grandTotal = $('.orderGrandTotal').val();
        var walletAmount = $('#WalletAmount').val();
        $('#walletError').html('');
        $('#walletError').removeAttr('class');

        var paymentMethod = $('input[name="data[Order][paymentMethod]"]:checked').val();

        if (paymentMethod == 'cod') {
            $('#card_savenew').hide();
            return false
        } else if (paymentMethod == 'creditcard') {
            $('#card_savenew').show();
            return false
        } else if (paymentMethod == 'paypal') {
            $('#card_savenew').hide();
            return false
        } else if (paymentMethod == 'wallet') {

            if (parseFloat(grandTotal) < parseFloat(walletAmount)) {
                var remainWallet = walletAmount - grandTotal;
                /*$('#walletError').addClass('alert-success');
                 $('#walletError').html('<?php echo __('Remaining Wallet Amount').' '.$siteCurrency.' '; ?>'+remainWallet);
                 $('#remainWalletAmount').html(remainWallet);*/
                $('#card_savenew').hide();
                return true;

            } else {
                $('#walletError').addClass('error');
                $('#walletError').html('<?php echo __('Insuficient wallet amount please try another payment'); ?>');
                $('#card_savenew').hide();
                return false;
            }
        }
    }

    function showTip(type) {
        if (type == 'Card') {
            $('#tipOption').show();
        } else {
            $('#OrderTipPercentage').val('');
            $('#tipOption').hide();
        }

        return false;
    }

    function checkAddress(storeId) {

        var orderType = $('input[name="data[Order][orderType]"]:checked').val();
        var address = $('#selectaddress').val();

        // if(address != '') {
        $.post(rp + 'checkouts/deliveryLocation', {'id': address, 'orderTypes': orderType}, function (response) {
            if (response == 'fail') {

                (address != '') ? $('#locationError').html('<?php echo __('This restaurant wont deliver to your adddress please choose another one');?>') : $('#locationError').html('<?php echo __('Please select address');?>');
                $('#deliverycharge').hide();
                //deliverymoney
                checkoutCart();
            } else {
                cart();
                $('#locationError').html('');
                $('#deliverycharge').show();
                checkoutCart(address);
            }
        });
        return false
        /*} else {
         $('#checkdeliverytab').hide();
         return false
         }
         
         return false*/


    }

</script>