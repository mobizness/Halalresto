<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleApiKey; ?>&v=3&sensor=false&amp;libraries=places,geometry"></script>
<div class="modal-dialog modal-md" >
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">
				<span aria-hidden="true">&times;</span>
			</button>
			<h4 class="modal-title"> <?php echo __('Edit Address', true); ?></h4>

		</div> <?php
        echo $this->Form->create("CustomerAddressBook",
                                    array("id"=>'EditCustomerAddressBook',
                                          "url"=>array("controller"=>'Customers','action'=>'editAddressBook')));?>
			<div class="modal-body"> 
				<label  class="error checkerorr"></label>
				<div class="form-group clearfix">
					<label class="control-label col-md-4 col-lg-3"><?php echo __('Address Title', true); ?></label>
					<div class="col-md-8 col-lg-9"><?php
						echo $this->Form->input('address_title',
								array('class'=>'form-control titleerorr',
									'id' => 'CustomerAddressBookAddressTitles',
										'label'=>false)); ?>
						<label class="error titleerorr"></label>
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-md-4 col-lg-3"> <?php echo __('Address Phone', true); ?></label>
					<div class="col-md-8 col-lg-9"><?php
						echo $this->Form->input('address_phone',
								array('class'=>'form-control',
										'id'=>'phone',
										'type'=>'text',
										'label'=>false)); ?>
						<label class="error phoneerorr"></label>
					</div>
				</div> <?php
				if ($siteSetting['Sitesetting']['address_mode'] != 'Google') { ?>
					<div class="form-group clearfix">
						<label class="control-label col-md-4 col-lg-3"> <?php echo __('Street Address', true); ?></label>
						<div class="col-md-8 col-lg-9"><?php
							echo $this->Form->input('address',
									array('class'=>'form-control',
											'id'=>'street',
											'type' => 'text',
											'label'=>false)); ?>
							<label class="error streeterorr"></label>
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4 col-lg-3"> <?php echo __('Apt/Suite/Building', true); ?></label>
						<div class="col-md-8 col-lg-9"><?php
							echo $this->Form->input('landmark',
									array('class'=>'form-control',
										'id'=>'build',
											'label'=>false)); ?>
							<label class="error builderorr"></label>
						</div>
					</div>

					<div class="form-group clearfix">
						<label class="control-label col-md-4 col-lg-3"> <?php echo __('State', true); ?></label>
						<div class="col-md-8 col-lg-9"><?php
							echo $this->Form->input('state_id',
								array('type'  => 'select',
									  'class' => 'form-control',
									  'id' => 'CustomerAddressBookStateIds',
									  'options'=> array($state_list),
									  'onchange' => 'cityFillter();',
									  'empty' => __('Select State'),
									  'label'=> false)); ?>
							<label class="error stateerorr"></label>
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4 col-lg-3"> <?php echo __('city', true); ?></label>
						<div class="col-md-8 col-lg-9"><?php
							echo $this->Form->input('city_id',
								array('type'  => 'select',
									  'class' => 'form-control',
									  'id' => 'CustomerAddressBookCityIds',
									  'options'=> array($city_list),
									  'onchange' => 'locationFillter();',
									  'empty' => __('Select City'),
									  'label'=> false)); ?>
							<label class="error cityerorr"></label>
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4 col-lg-3"> <?php echo __('Area/zipcode', true); ?></label>
						<div class="col-md-8 col-lg-9"><?php
							echo $this->Form->input('location_id',
									array('type'  => 'select',
										  'class' => 'form-control',
										  'id' => 'CustomerAddressBookLocationIds',
										  'options'=> array($location_list),
										  'empty' => __('Select Area/Zip'),
										  'label'=> false));

							 ?>
							<label class="error areaerorr"></label>
						</div>
					</div> <?php
				} else { ?>
					<div class="form-group clearfix">
						<label class="control-label col-md-4 col-lg-3"> <?php echo __('Address', true); ?></label>
						<div class="col-md-8 col-lg-9"><?php
							echo $this->Form->input('google_address',
									array('class'=>'form-control',
											'id'=>'googleAddress',
											'type' => 'text',
											'label'=>false,
											'onfocus' =>'initialize(this.id)')); ?>
							<label class="error addresserorr"></label>
						</div>
					</div> <?php
				} ?>
				<div class="form-group clearfix">
					<label class="control-label col-md-4 col-lg-3">&nbsp;</label>
					<div class="col-md-8 col-lg-9 signup-footer"> <?php
						echo $this->Form->hidden('id');
						echo $this->Form->button(__('Submit'),
								array("label"=>false,
										"class"=>"btn btn-primary",
										'onclick' => 'return validateAddress();',
										"type"=>'submit'));  ?>
					</div>
				</div>
			</div> <?php
		echo $this->Form->end(); ?>
	</div>
</div>