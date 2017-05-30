<div class="contain">
	<div class="contain">
		<h3 class="page-title">Editer Promo</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Accueil</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="<?php echo $siteUrl.'/admin/Vouchers/index';?>">Gestion Promo</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Editer Promo</a>
				</li>
			</ul>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<!-- BEGIN PORTLET-->
				<div class="portlet box blue-hoki">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-user"></i> Editer Promo
						</div>
						<div class="tools">
							
						</div>
					</div>
					<div class="portlet-body form"> <?php 
						echo $this->Form->create('Voucher', array('class' => 'form-horizontal',
																 'onsubmit' => 'return voucherAddEdit();')); ?>
							<div class="form-body">
								<label id="voucherError" class="error"></label>
								<div class="form-group">
									<label class="col-md-3 control-label">Nom du Restaurant <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"><?php
										echo $this->Form->input('Voucher.store_id',
												array('type'   => 'select',
													  'class'  => 'form-control',
													  'options'=> array($stores),
													  'empty'  => __('Select Restaurant'),
													  'label'  => false)); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Code promo <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"><?php
                    					echo $this->Form->input('Voucher.voucher_code',
                    										array('class'=>'form-control',
                    											  'autocomplete' => 'off',
                                                                  'label' => false,
                    											  'div' => false)); 
                                        echo $this->Form->hidden('Voucher.id');?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Type d’usage <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4">
										<div class="radio-list">
											<label class="radio-inline"><?php 
		                                        $option1 = array('single'  => __('Single'));
		                                        $option2 = array('multiple'   => __('Multiple'));
		                                       	echo $this->Form->radio('Voucher.type_offer',$option1,
		                                       					array('checked'=>$option1,
                                       								'label'=>false,
                                       								'legend'=>false,
                               										'hiddenField'=>false)); ?>
                                            </label>
											<label class="radio-inline"><?php
                                            	echo $this->Form->radio('Voucher.type_offer',$option2,
                                       							array('checked'=>$option1,
                                       								'label'=>false,
                                       								'legend'=>false,
                                       								'hiddenField'=>false)); ?>
                                             </label>
										</div>
									</div>
								</div>	
								<div class="form-group">
									<label class="col-md-3 control-label">Type d’offre <span class="star">*</span></label>
									<div class="col-md-8 col-lg-8">
										<div class="radio-list">
											<label class="radio-inline"><?php 
		                                        $option1 = array('price'  => 'Prix');
		                                        $option2 = array('percentage'   => 'Pourcentage');
		                                        $option3 = array('free_delivery'   => 'Livraison gratuite');
		                                       	echo $this->Form->radio('Voucher.offer_mode',$option1,
	                                       							array('checked'=>$option1,
	                                       								'label'=>false,
	                                       								'legend'=>false,
                                       									'hiddenField'=>false)); ?>
                                            </label>
											<label class="radio-inline"><?php
                                    			echo $this->Form->radio('Voucher.offer_mode',$option2,
                                       							array('checked'=>$option2,
                                       								'label'=>false,
                                       								'legend'=>false,
                                       								'hiddenField'=>false)); ?>
                                            </label>
                                            <label class="radio-inline"><?php
                                    			echo $this->Form->radio('Voucher.offer_mode',$option3,
                                       							array('checked'=>$option3,
                                       								'label'=>false,
                                       								'legend'=>false,
                                       								'hiddenField'=>false)); ?>
                                            </label>
                                            <div class="row">
	                                            <div class="col-md-4">
		                                            <div id="symbols" class="input-group">
		                                            	<div id="currencySymbol" class="input-group-addon"> <?php 
		                                            		echo $siteCurrency; ?>
		                                            	</div> <?php
			                    						echo $this->Form->input('Voucher.offer_value',
		                    										array('class'=>'form-control',
		                    											  'autocomplete' => 'off',
		                    											  'type' => 'text',
		                                                                  'label' => false,
		                    											  'div' => false)); ?>
		                    							<div id="percentageSymbol" class="input-group-addon">%</div>
		                    						</div>
	                    						</div>
                    						</div>
										</div>
									</div>
								</div>	
								<div class="form-group">
									<label class="control-label col-md-3">Période <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4">
										<div class="input-group input-medium date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
											<?php
                    					echo $this->Form->input('Voucher.from_date',
                    										array('class'=>'form-control',
                    											  'autocomplete' => 'off',
                                                                  'label' => false,
                                                                  'readonly' => true,
                    											  'div' => false)); ?>
											<span class="input-group-addon"> au </span><?php
                    					echo $this->Form->input('Voucher.to_date',
                    										array('class'=>'form-control',
                    											  'autocomplete' => 'off',
                                                                  'label' => false,
                                                                  'readonly' => true,
                    											  'div' => false)); 
                                       ?>
										</div>
										<!-- /input-group -->
									</div>
								</div>
							</div>
							<div class="form-actions">
								<div class="row">
									<div class="col-md-offset-3 col-md-9"><?php
										echo $this->Form->button(__('<i class="fa fa-check"></i>'.__('Submit')),array('class'=>'btn purple')); 
										echo $this->Html->link(__('Cancel'),
														array('action' => 'index'),
														array('Class'=>'btn default')); ?>
									</div>
								</div>
							</div> <?php
						echo $this->Form->end(); ?>
					</div>
				</div>
				<!-- END PORTLET-->
			</div>
		</div>
	</div>
</div>