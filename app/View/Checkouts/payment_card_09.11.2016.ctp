<div class="alert alert-success" id="cardMessage"><?php echo __('Votre carte a été ajoutée avec succès'); ?></div>
<div class="panel panel-default">
	<div class="panel-body addressBg">
		<div class="panel-subheading">
			<h3 class="clearfix">
				<span class="pull-left"><?php echo __('Payment Details', true); ?> </span>	
				<span class="pull-right"> <?php echo $siteCurrency.' '; ?>
				<span class="pull-right payAmount"> <?php echo ' '. $orderGrandTotal; ?> </span></span>
			</h3>
			<label id="walletError"></label>
			<div class="paymentWrapper clearfix">
            <div class="row">
                    <div class="col-md-12">
                        <div class="cardDetailHead"><?php echo __('Saved Card Details', true); ?>
                        <span class="pull-right">
                            <a class="addnewAdrr" data-toggle="modal" data-target="#addpayment" href="javascript:void(0);">
                                <i class="fa fa-plus"></i> &nbsp;<?php echo __('Add Card', true); ?>
                            </a>
                        </span>
                        </div>
                    </div> <?php
                    foreach ($stripeCards as $key => $card) { ?>
                        <div class="col-md-4 col-sm-6 col-xs-12" onclick="showTip('Card');">
                            <label class="editpayment">
                                <input type="radio" name="data[Order][paymentMethod]" value="<?php echo $card['StripeCustomer']['id']; ?>" onclick="walletCheck();"/>
                                <div class="card_info">
                                    <span class="editAdd contain truncate">
                                        <img style="height:24px;" alt="cod_icon" title="cod_icon" src="<?php echo $siteUrl.'/frontend/images/debit_card.png'; ?>">
                                        <?php echo $card['StripeCustomer']['customer_name']; ?>
                                    </span>
                                    <p class="margin-t-20">XXXX XXXX XXXX <?php echo $card['StripeCustomer']['card_number']; ?> </p>                                        
                                </div>  
                            </label>
                        </div> <?php
                    } ?>
                </div>
				<div class="row">
					<div class="col-md-12"><div class="cardDetailHead"><?php echo __('Cash on delivery', true); ?></div></div>
					<div class="col-md-4 col-sm-6 col-xs-12" onclick="showTip('Cash');">
						<label class="editpayment active">
							<img style="height:24px;" alt="cod_icon" title="cod_icon" src="<?php echo $siteUrl.'/frontend/images/cod_icon.png'; ?>">
    						<input type="radio" id="cod" name="data[Order][paymentMethod]" value="cod" checked = "checked" onclick="walletCheck();"/>
							<span class="editAdd "><?php echo __('Cash on delivery', true); ?></span>
    					</label> 
    				</div>
    			</div>
    			<div class="row">
                    <div class="col-md-12"><div class="cardDetailHead"><?php echo __('Wallet', true); ?></div></div>
                    <div class="col-md-4 col-sm-6 col-xs-12" onclick="showTip('Wallet');">
                        <input type="hidden" id="WalletAmount" value="<?php echo $customerDetails['Customer']['wallet_amount']; ?>">
						<label class="editpayment">
							<img style="height:24px;" alt="wallet" title="wallet" src="<?php echo $siteUrl.'/frontend/images/wallet.png'; ?>">
    						<input type="radio" name="data[Order][paymentMethod]" value="wallet" onclick="walletCheck();">
							<span class="editAdd "><?php 
								echo __('Wallet', true);
								echo ' '.$siteCurrency.' '; ?>
								<span id="remainWalletAmount"><?php 
								echo $customerDetails['Customer']['wallet_amount']; ?></span></span>
    					</label> 
    				</div>
    			</div>
				

                <div id="tipOption" style="display: none;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="cardDetailHead">
                            	<div class="clearfix">
                                	<div class="tiphead">
                                		<?php echo __('Tips', true); ?>
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

		<div class="checkout-bottom checkoutbtm"> <?php
			echo $this->Form->button(__('Place Order'),
                              array('class'=>'btn btn-primary pull-right',
                              		'onclick' => 'return walletPay();')); ?>
			<a onclick="checkoutpagintaion('#payment','#reviewConform');" class="btn btn-default pull-left" > <?php
				echo __('Back to Review', true); ?></a>
		</div>
	</div>
</div>


<script type="text/javascript">


setTimeout(function(){				
    $('#cardMessage').fadeOut();
},30000);

$(".paymentWrapper .editpayment").click(function() {
	$(".paymentWrapper .editpayment").removeClass('active');
	$(this).addClass('active');		
	
});

function showTip(type) {
    if (type == 'Card') {
        $('#tipOption').show();
    } else {
        $('#tip_percentage').val('');
        $('#tipOption').hide();
    }
    return false;
}

</script>