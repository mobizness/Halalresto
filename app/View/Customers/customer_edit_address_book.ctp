<div class="modal-dialog modal-md">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">
				<span aria-hidden="true">&times;</span>
			</button>
			<h4 class="modal-title"> <?php echo __('Edit Address', true); ?></h4>
		</div>
		<div class="modal-body"> <?php
        	echo $this->Form->create("CustomerAddressBook",
                            array("id"=>'EditCustomerAddressBook',
                                  "url"=>array("controller"=>'Customers','action'=>'editAddressBook')));?>
				<div class="form-group clearfix">
					<label class="control-label col-md-4 col-lg-3"> <?php echo __('Address Title', true); ?></label>
					<div class="col-md-7"><?php
										echo $this->Form->input('address_title',
												array('class'=>'form-control',
														'label'=>false)); ?>
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-md-4 col-lg-3"> <?php echo __('Street Address', true); ?></label>
					<div class="col-md-7"><?php
										echo $this->Form->input('address',
												array('class'=>'form-control',
														'label'=>false)); ?>
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-md-4 col-lg-3"> <?php echo __('Address Phone', true); ?></label>
					<div class="col-md-7"><?php
										echo $this->Form->input('address_phone',
												array('class'=>'form-control',
														'label'=>false)); ?>
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-md-4 col-lg-3"> <?php echo __('Apt/Suite/Building', true); ?></label>
					<div class="col-md-7"><?php
										echo $this->Form->input('landmark',
												array('class'=>'form-control',
														'label'=>false)); ?>
					</div>
				</div>
			
                <div class="form-group clearfix">
					<label class="control-label col-md-4 col-lg-3"> <?php echo __('State', true); ?></label>
					<div class="col-md-7"><?php
										echo $this->Form->input('state_id',
											array('type'  => 'select',
												  'class' => 'form-control',
												  'options'=> array($state_list),
                                                  'onchange' => 'cityFillters();',
												  'empty' => 'selete your state',
								 				  'label'=> false)); ?>
					</div>
				</div>
                <div class="form-group clearfix">
					<label class="control-label col-md-4 col-lg-3"> <?php echo __('city', true); ?></label>
					<div class="col-md-7"><?php
										echo $this->Form->input('city_id',
											array('type'  => 'select',
												  'class' => 'form-control',
												  'options'=> array($city_list),
                                                  'onchange' => 'locationFillters();',
												  'empty' => 'selete your city',
								 				  'label'=> false)); ?>
					</div>
				</div>
                <div class="form-group clearfix">
					<label class="control-label col-md-4 col-lg-3"> <?php echo __('Area/zipcode', true); ?></label>
					<div class="col-md-7"><?php
									echo $this->Form->input('location_id',
											array('type'  => 'select',
												  'class' => 'form-control',
												  'options'=> array($location_list),
												  'empty' => 'selete your Area/Zip',
								 				  'label'=> false)); 
                                     echo $this->Form->hidden('id'); ?>
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-md-4 col-lg-3">&nbsp;</label>
					<div class="col-md-7 signup-footer">
						<?php echo $this->form->end(__('Submit'));?>
					</div>
				</div>
			
		</div>
	</div>
</div>