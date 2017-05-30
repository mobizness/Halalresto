<div class="page-content-wrapper">
	<div class="page-content">
		<h3 class="page-title">Add Menu</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/store/dashboards/index';?>">Home</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="<?php echo $siteUrl.'/store/products/index';?>">Manage Menu</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="javascript:void(0);">Add Menu</a>
				</li>
			</ul>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<!-- BEGIN PORTLET-->
				<div class="portlet box blue-hoki">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-user"></i> Add Menu
						</div>
						<div class="tools">
							
						</div>
					</div>
					<div class="portlet-body form"><?php 
						echo $this->Form->create('Product',array('class' =>"form-horizontal",
																 'type'  => 'file')); ?>
                        <input type="hidden" id="storeIdValue" value="<?php echo $store_id; ?>">
							<div class="form-body">
								<div class="form-group">
									<label class="col-md-3 control-label">Menu Name <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"><?php
										echo $this->Form->input('Product.product_name',
															array('class' => 'form-control',
																  'label' => false));?>
										<input type="hidden" id="storeId" value="<?php echo $store_id; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Category Name <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"><?php
										echo $this->Form->input('Product.category_id',
												array('type'  => 'select',
													  'class' => 'form-control',
													  'options'=> array($category_list),
                                                      'onchange' => "return getAddons();",
													  'empty' => 'Select Category',                     
									 				  'label'=> false)); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Menu Type <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4">
										<div class="radio-list">
											<label class="radio-inline"> <?php
												echo $this->Form->hidden('id');
												$option1 = array('veg'  => 'Veg');
												$option2 = array('nonveg'   => 'Non Veg');
												$option3 = array('other'   => 'Others');
												echo $this->Form->radio('Product.product_type',$option1,
														array('checked'=>$option1,
																'label'=>false,
																'legend'=>false,
																'checked' => 'checked',
																'hiddenField'=>false)); ?>
											</label>
											<label class="radio-inline"><?php
												echo $this->Form->radio('Product.product_type',$option2,
														array('checked'=>$option2,
																'label'=>false,
																'legend'=>false,
																'hiddenField'=>false)); ?>
											</label>
											<label class="radio-inline"><?php
												echo $this->Form->radio('Product.product_type',$option3,
														array('checked'=>$option3,
																'label'=>false,
																'legend'=>false,
																'hiddenField'=>false)); ?>
											</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Price Option <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4">
										<div class="radio-list">                                        
											<label class="radio-inline"> <?php 
                                                $option1 = array('single'  => 'single');
                                                $option2 = array('multiple'   => 'multiple');
	                                           	echo $this->Form->radio('Product.price_option',$option1,
	                                           							array('checked'=>$option1,
	                                           								'label'=>false,
	                                           								'legend'=>false,
                                             	                            'checked' => 'checked',
	                                           								'hiddenField'=>false)); ?>
                                            </label>
											<label class="radio-inline"><?php
                                            	echo $this->Form->radio('Product.price_option',$option2,
	                                           							array('checked'=>$option2,
	                                           								'label'=>false,
	                                           								'legend'=>false,
	                                           								'hiddenField'=>false)); ?>
                                             </label>
										</div>
									</div>
								</div>
								<div class="form-group"  id="single">
									<label class="col-md-3 control-label">Price <span class="star">*</span></label>
									<div class="col-md-8 col-lg-8">
										<div class="row margin-b-15">
											<div class="col-md-7">
												<div class="row">
													<!--<div class="col-md-6"><?php
/*														echo $this->Form->input('ProductDetail.sub_name',
																array('class'=>'form-control',
		                                                              'placeholder'=>'Product Name',
		                                                              'type' => 'text',
																	  'label'=>false)); */?>
													</div>-->
													<div class="col-md-3"><?php
														echo $this->Form->input('ProductDetail.orginal_price',
																array('class'=>'form-control singleValidate',
		                                                              'placeholder'=>'Price',
		                                                              'data-attr'=>'original price',
		                                                              'type' => 'text',
																	  'label'=>false)); ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group" id="multiple">
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-8 col-lg-8">
										<div class="row addPriceTop margin-b-15">
											<div class="col-lg-7">
												<div class="row">
													<div class="col-md-6"><?php
														echo $this->Form->input('ProductDetail.sub_name',
																array('class'=>'form-control multipleValidate',
	                                                                    'placeholder'=>'Product Name',
	                                                                    'data-attr'=>'product name',
	                                                                    'name' => 'data[ProductDetail][0][sub_name]',
																		'label'=>false)); ?>
													</div>
													<div class="col-md-3"><?php
														echo $this->Form->input('ProductDetail.orginal_price',
																array('class'=>'form-control multipleValidate',
		                                                                 'placeholder'=>'Price',
		                                                                 'type' => 'text',
		                                                                 'id' => 'ProductDetailOrginalPrice0',
		                                                                 'data-attr'=>'original price',
		                                                                 'name' => 'data[ProductDetail][0][orginal_price]',
																		 'label'=>false)); ?>
													</div>
												</div>
											</div>
										</div>
										<div id="moreOption"></div>
										<a class="addPrice btn green margin-t-10" href="javascript:void(0);" onclick="multipleOption();"><i class="fa fa-plus"></i> Add Price</a>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label">Addons <span class="star"></span></label>
									<div class="col-md-6 col-lg-4">
										<div class="radio-list">
											<label class="radio-inline"> <?php
												$addonYes = array('Yes' => 'Yes');
												$addonNo = array('No' => 'No');
												echo $this->Form->radio('Product.product_addons',$addonYes, [
														'checked' => $addonYes,
														'legend'=>false,
														'onchange' => 'return showAddons(this.value);',
														'hiddenField'=>false
												]); ?>
											</label>
											<label class="radio-inline"><?php
												echo $this->Form->radio('Product.product_addons',$addonNo, [
														'checked' => $addonNo,
														'legend'=>false,
														'checked' => 'checked',
														'onchange' => 'return showAddons(this.value);',
														'hiddenField'=>false
												]); ?>
											</label>
										</div>
									</div>
								</div>

								<div id="getShowAddons" class="col-xs-12"></div>

								<div class="form-group">
									<label class="col-md-3 control-label">Image <span class="star"></span></label>
									<div class="col-md-6 col-lg-4">
										<label for="ProductProductImage"> <?php
	                                        echo $this->Form->input("Product.product_image", 
	                                         				array("label"=>false,
					                                                "type"=>"file",
					                                                "name"=>"data[ProductImage]")); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Description</label>
									<div class="col-md-6 col-lg-4"><?php
										echo $this->Form->input('Product.product_description',
														array('class'=>'form-control',
																'label'=>false)); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Popular <span class="star"></span></label>
									<div class="col-md-6 col-lg-4">
										<div class="radio-list">
											<label class="radio-inline"> <?php
												echo $this->Form->hidden('id');
												$option1 = array('Yes'  => 'Yes');
												$option2 = array('No'   => 'No');
												echo $this->Form->radio('Product.popular_dish',$option1,
														array('checked'=>$option1,
																'label'=>false,
																'legend'=>false,
																'checked' => 'checked',
																'hiddenField'=>false)); ?>
											</label>
											<label class="radio-inline"><?php
												echo $this->Form->radio('Product.popular_dish',$option2,
														array('checked'=>$option2,
																'label'=>false,
																'legend'=>false,
																'hiddenField'=>false)); ?>
											</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Spicy <span class="star"></span></label>
									<div class="col-md-6 col-lg-4">
										<div class="radio-list">
											<label class="radio-inline"> <?php
												echo $this->Form->hidden('id');
												$option1 = array('Yes' => 'Yes');
												$option2 = array('No'  => 'No');
												echo $this->Form->radio('Product.spicy_dish',$option1,
														array('checked'=>$option1,
																'label'=>false,
																'legend'=>false,
																'checked' => 'checked',
																'hiddenField'=>false)); ?>
											</label>
											<label class="radio-inline"><?php
												echo $this->Form->radio('Product.spicy_dish',$option2,
														array('checked'=>$option2,
																'label'=>false,
																'legend'=>false,
																'hiddenField'=>false)); ?>
											</label>
										</div>
									</div>
								</div>
								<div class="form-actions">
									<div class="row">
										<div class="col-md-offset-3 col-md-9"><?php
											echo $this->Form->button(__('<i class="fa fa-check"></i>Save'),
															array('class'=>'btn purple',
																  'onclick'=>'return optionValidate();')); ?> <?php
											echo $this->Html->link('Cancel',
															array('action' => 'index'),
															array('Class'=>'btn default')); ?>
										</div>
									</div>
								</div>
							</div><?php
						echo $this->Form->end(); ?>
					</div>
					<!-- END PORTLET-->
				</div>
			</div>
		</div>
	</div>
</div>