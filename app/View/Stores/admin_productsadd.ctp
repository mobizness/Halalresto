<div class="contain">
    <div class="contain">
        <h3 class="page-title">Ajouter Halal Produits</h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Accueil</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="<?php echo $siteUrl.'/admin/products/index';?>">Halal Produits</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="javascript:void(0);">Halal Produits</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PORTLET-->
                <div class="portlet box blue-hoki">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-user"></i> Halal Produits
                        </div>
                        <div class="tools">

                        </div>
                    </div>
                    <div class="portlet-body form"><?php 
						echo $this->Form->create('Stores',array('class' =>"form-horizontal",
																 'type'  => 'file')); ?>			
                        <div class="form-body">
                            <label class="error" id="productsAddError"></label>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Nom Produits <span class="star">*</span></label>
                                <div class="col-md-6 col-lg-4"><?php
										echo $this->Form->input('HalalProduct.name',
															array('class' => 'form-control',
																  'label' => false)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">Prix Option <span class="star">*</span></label>
                                <div class="col-md-6 col-lg-4">
                                    <div class="radio-list">                                        
                                        <label class="radio-inline"> <?php 
                                                $option1 = array('1'  => 'Paid');
                                                $option2 = array('0' => 'Quote');
	                                           	echo $this->Form->radio('HalalProduct.isPaid',$option1,
	                                           							array('checked'=>$option1,
	                                           								'label'=>false,
	                                           								'legend'=>false,
                                             	                            'checked' => 'checked',
	                                           								'hiddenField'=>false)); ?>
                                        </label>
                                        <label class="radio-inline"><?php
                                            	echo $this->Form->radio('HalalProduct.isPaid',$option2,
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
                                    <div class="row">
                                        <div class="col-lg-7">
                                            <div class="row">
                                                <div class="col-md-3"><?php
														echo $this->Form->input('HalalProduct.price',
																array('class'=>'form-control singleValidate',
		                                                              'placeholder'=>'Prix',
		                                                              'data-attr'=>'original price',
		                                                              'type' => 'text',
																	  'label'=>false)); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="getShowAddons" class="col-xs-12"></div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Image <span class="star"></span></label>
                                <div class="col-md-6 col-lg-4"><?php
                                         echo $this->Form->input("HalalProduct.image", 
                                         				array("label"=>false,           
				                                                "type"=>"file",
				                                                "name"=>"data[image]")); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Description</label>
                                <div class="col-md-6 col-lg-4"><?php
										echo $this->Form->input('HalalProduct.description',
														array('class'=>'form-control',
																'label'=>false)); ?>
                                </div>
                            </div>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9"><?php
											echo $this->Form->button(__('<i class="fa fa-check"></i>'.__('Save')),
															array('class'=>'btn purple',
																  'onclick'=>'return validateHalalProducts();')); ?> <?php
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