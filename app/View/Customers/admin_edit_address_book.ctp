<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleApiKey; ?>&v=3&sensor=false&amp;libraries=places,geometry"></script>
<div class="contain">
	<div class="contain">
		<h3 class="page-title">Editer Book</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Accueil</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="<?php echo $siteUrl.'/admin/Customers/index';?>">Client</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#"> Editer Carnet d’adresse</a>
				</li>
			</ul>
		</div>
		
		<div class="row">
				<div class="col-md-12">
					<!-- BEGIN PORTLET-->
					<div class="portlet box blue-hoki">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-user"></i> Editer Carnet d’adresse
							</div>
							<div class="tools">
								
							</div>
						</div>
						<div class="portlet-body form"> <?php
                    echo $this->Form->create("CustomerAddressBook",
                                                array("id"=>'EditCustomerAddressBook',
                                                	"class"=>'form-horizontal',
                                                      "url"=>array("controller"=>'Customers','action'=>'editAddressBook')));?>
								<div class="form-body">
									<div class="form-group">
										<label class="col-md-3 control-label"><?php echo __('Address Title', true); ?> <span class="star">*</span></label>
										<div class="col-md-6 col-lg-4"><?php
													echo $this->Form->input('address_title',
															array('class'=>'form-control',
																	'label'=>false)); ?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label"> <?php echo __('Address Phone', true); ?> <span class="star">*</span></label>
										<div class="col-md-6 col-lg-4"><?php
													echo $this->Form->input('address_phone',
															array('class'=>'form-control',
																	'label'=>false)); ?>
										</div>
									</div> <?php
									if ($siteSetting['Sitesetting']['address_mode'] != 'Google') { ?>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label"><?php echo __('Street Address', true); ?> <span class="star">*</span></label>
                                            <div class="col-md-6 col-lg-4"><?php
                                                echo $this->Form->input('address',
                                                    array('class'=>'form-control',
                                                        'type' => 'text',
                                                        'label'=>false)); ?>
                                            </div>
                                        </div>
										<div class="form-group">
											<label class="col-md-3 control-label"><?php echo __('Apt/Suite/Building', true); ?><span class="star">*</span></label>
											<div class="col-md-6 col-lg-4"><?php
														echo $this->Form->input('landmark',
																array('class'=>'form-control',
																		'label'=>false)); ?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label"><?php echo __('State', true); ?> <span class="star">*</span></label>
											<div class="col-md-6 col-lg-4"><?php
														echo $this->Form->input('state_id',
															array('type'  => 'select',
																  'class' => 'form-control',
																  'options'=> array($state_list),
																  'onchange' => 'cityFillters();',
																  'empty' => __('Select state'),
																  'label'=> false)); ?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label"><?php echo __('city', true); ?> <span class="star">*</span></label>
											<div class="col-md-6 col-lg-4"><?php
														echo $this->Form->input('city_id',
															array('type'  => 'select',
																  'class' => 'form-control',
																  'options'=> array($city_list),
																  'onchange' => 'locationFillters();',
																  'empty' => 'Select city',
																  'label'=> false)); ?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label"><?php echo __('Area/zipcode', true); ?> <span class="star">*</span></label>
											<div class="col-md-6 col-lg-4"><?php
													echo $this->Form->input('location_id',
															array('type'  => 'select',
																  'class' => 'form-control',
																  'options'=> array($location_list),
																  'empty' => 'Select Area/Zip',
																  'label'=> false));
													 ?>
											</div>
										</div> <?php
									} else { ?>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Adresse <span class="star">*</span></label>
                                            <div class="col-md-6 col-lg-4"><?php
                                                echo $this->Form->input('google_address',
                                                    array('class'=>'form-control',
                                                        'type' => 'text',
                                                        'label'=>false,
                                                        'onfocus' =>'initialize(this.id)')); ?>
                                            </div>
                                        </div> <?php
                                    }?>
								</div>

								<div class="form-actions">
									<div class="row">
										<div class="col-md-offset-3 col-md-9"><?php
                                            echo $this->Form->hidden('id');
                                            echo $this->Form->hidden('ids',array('value'=>$this->request->data['Customer']['id']));
											echo $this->Form->button(__('<i class="fa fa-check"></i>Submit'),array('class'=>'btn purple')); 
												echo $this->Html->link('Cancel',
																array('action' => 'index'),
																array('Class'=>'btn default')); ?>
										</div>
									</div>
								</div>
						<?php echo $this->form->end();?>
						</div>
					</div>
					<!-- END PORTLET-->
				</div>
			</div>
	</div>
</div>
