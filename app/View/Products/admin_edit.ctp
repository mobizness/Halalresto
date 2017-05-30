<div class="contain">
	<div class="contain">
		<h3 class="page-title">Editer Menu</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Accueil</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="<?php echo $siteUrl.'/admin/products/index';?>">Gestion Menu</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="javascript:void(0);">Editer Menu</a>
				</li>
			</ul>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<!-- BEGIN PORTLET-->
				<div class="portlet box blue-hoki">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-user"></i> Editer Menu
						</div>
						<div class="tools">
							
						</div>
					</div>
					<div class="portlet-body form"><?php 
						echo $this->Form->create('Product',array('class' =>"form-horizontal",
																 'type'  => 'file')); ?>
							<div class="form-body">
								<label class="error" id="productError"></label>
								<div class="form-group">
									<label class="col-md-3 control-label">Restaurant<span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"> <?php
										echo $this->Form->input('Product.store_id', [
                                            'type'  => 'select',
                                            'class' => 'form-control',
                                            'options'=> array($stores),
                                            'empty' => __('Select Restaurant'),
                                            'label'=> false
                                        ]); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Nom Menu<span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"><?php
										echo $this->Form->input('Product.product_name',
															array('class' => 'form-control',
																  'label' => false)); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Nom Catégorie <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"><?php
										echo $this->Form->input('Product.category_id', [
                                                'type'  => 'select',
                                                'class' => 'form-control',
                                                'options'=> array($category_list),
                                                'onchange' => "getAddons();",
                                                'empty' => 'Sélectionnez le Catégorie',
                                                'label'=> false
                                            ]
                                        ); ?>
                                        <label class="error" id="categoryError"></label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Type de menu <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4">
										<div class="radio-list">
											<label class="radio-inline"> <?php
												echo $this->Form->hidden('id');
												$option1 = array('veg'  => 'Veg');
												$option2 = array('nonveg'   => 'Non Veg');
												$option3 = array('other'   => "Autres");
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
									<label class="col-md-3 control-label">Prix Option <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4">
										<div class="radio-list">                                        
											<label class="radio-inline"> <?php 
												echo $this->Form->hidden('id');
                                                $option1 = array('single'  => 'Individuel');
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
									<label class="col-md-3 control-label">Prix <span class="star">*</span></label>
									<div class="col-md-8 col-lg-8">
										<div class="row"> <?php
											if ($this->request->data['Product']['price_option'] == 'single') { ?>
												<div class="col-lg-7">
													<div class="row">
														<div class="col-md-3"><?php
															echo $this->Form->input('ProductDetail.orginal_price',
																	array('class'=>'form-control singleValidate',
			                                                              'placeholder'=>'Prix',
			                                                              'type' => 'text',
			                                                              'value' => $getProductData['ProductDetail'][0]['orginal_price'],
			                                                              'data-attr'=>'original price',
																		  'label'=>false)); ?>
														</div>
													</div>
												</div><?php
											} else { ?>
												<div class="col-lg-7">
													<div class="row">
														<div class="col-md-3"><?php
															echo $this->Form->input('ProductDetail.orginal_price',
																	array('class'=>'form-control singleValidate',
			                                                              'placeholder'=>'Prix',
			                                                              'data-attr'=>'original price',
			                                                              'type' => 'text',
																		  'label'=>false)); ?>
														</div>
													</div>
												</div> <?php
											} ?>
										</div>
									</div>
								</div>
								<div class="form-group" id="multiple">
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-8 col-lg-8"> <?php

										if ($this->request->data['Product']['price_option'] == 'multiple') {
											
											foreach ($getProductData['ProductDetail'] as $key => $value) { ?>

												<div id="moreProuct<?php echo $key; ?>" class="row addPriceTop">
													<div class="col-lg-7">
														<div class="row">
															<div class="col-md-6"><?php
																echo $this->Form->input('ProductDetail.sub_name',
																		array('class'=>'form-control multipleValidate',
			                                                                    'placeholder'=>'Product Name',
			                                                                    'data-attr'=>'product name',
			                                                                    'name' => 'data[ProductDetail]['.$key.'][sub_name]',
			                                                                    'value' => $value['sub_name'],
																				'label'=>false)); ?>
															</div>
															<div class="col-md-3"><?php
																echo $this->Form->input('ProductDetail.orginal_price',
																		array('class'=>'form-control multipleValidate',
				                                                                 'placeholder'=>'Prix',
				                                                                 'type' => 'text',
				                                                                 'id' => 'ProductDetailOrginalPrice'.$key,
				                                                                 'data-attr'=>'original price',
				                                                                 'name' => 'data[ProductDetail]['.$key.'][orginal_price]',
				                                                                 'value' => $value['orginal_price'],
																				 'label'=>false)); ?>
															</div>
															<?php
																if ($key !=0) { ?>
																	<span onclick="removeOption(<?php echo $key; ?>);" class="ItemRemove"><i class="fa fa-times"></i></span> <?php
																} ?>
														</div>
													</div>
													
												</div> <?php
											} ?>
											<script>var j = '<?php echo $key+1; ?>';</script> <?php
										} else { ?>

											<div class="row addPriceTop multipleMenu">
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
			                                                                 'placeholder'=>'Prix',
			                                                                 'type' => 'text',
			                                                                 'id' => 'ProductDetailOrginalPrice0',
			                                                                 'data-attr'=>'original price',
			                                                                 'name' => 'data[ProductDetail][0][orginal_price]',
																			 'label'=>false)); ?>
														</div>
													</div>
												</div>
											</div> <?php

										} ?>

										<div id="moreOption"></div>
										<a class="addPrice btn green" href="javascript:void(0);" onclick="multipleOption();"><i class="fa fa-plus"></i> Add Prix</a>
									</div>
								</div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Articles <span class="star"></span></label>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="radio-list">
                                            <label class="radio-inline"> <?php
                                                $addonYes = array('Yes' => 'Oui');
                                                $addonNo = array('No' => 'Non');
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
                                                    'onchange' => 'return showAddons(this.value);',
                                                    'hiddenField'=>false
                                                ]); ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div id="getShowAddons"></div>
								<div class="form-group">
									<label class="col-md-3 control-label">Image <span class="star"></span></label>
									<div class="col-md-6 col-lg-4"><?php
										$images = '';
										$store_id = $getProductData['Product']['store_id'];
                                        $images = $getProductData['Product']['product_image']; ?>
                                        <div id="image<?php echo $value['id']; ?>">
                                        	<img onerror="this.onerror=null;this.src='<?php echo $siteUrl."/images/no-imge.jpg"; ?>'" src="<?php echo $this->webroot.'stores/'.$store_id."/products/carts/".$images; ?>" >
                                        <!--<a href="javascript:;" class="ItemRemove" onclick="deleteProductImage('<?php /*echo $value['id']; */?>');">
                                            <i class="fa fa-times"></i>
                                        </a>-->
                                        </div><?php


                                         echo $this->Form->input("Product.product_image", 
                                         				array("label"=>false,
				                                                "type"=>"file",
				                                                "name"=>"data[ProductImage]")); ?>
				                        <input type="hidden" id="storeId" value="<?php echo $store_id; ?>">
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
									<label class="col-md-3 control-label">Populaire <span class="star"></span></label>
									<div class="col-md-6 col-lg-4">
										<div class="radio-list">
											<label class="radio-inline"> <?php
												echo $this->Form->hidden('id');
												$option1 = array('Yes'  => 'Oui');
												$option2 = array('No'   => 'Non');
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
									<label class="col-md-3 control-label">Epicé <span class="star"></span></label>
									<div class="col-md-6 col-lg-4">
										<div class="radio-list">
											<label class="radio-inline"> <?php
												echo $this->Form->hidden('id');
												$option1 = array('Yes' => 'Oui');
												$option2 = array('No'  => 'Non');
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
											echo $this->Form->button(__('<i class="fa fa-check"></i>'.__('Save')),
															array('class'=>'btn purple',
																  'onclick'=>'return optionValidate();')); ?> <?php
											echo $this->Html->link(__('Cancel'),
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