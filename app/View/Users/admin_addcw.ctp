<div class="contain">
    <div class="contain">
        <h3 class="page-title">Ajouter un éditeur de contenu</h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Accueil</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="<?php echo $siteUrl.'/admin/users/writerview';?>">Client de gestion</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="javascript:void(0);">Ajouter un éditeur de contenu</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PORTLET-->
                <div class="portlet box blue-hoki">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-user"></i> Ajouter un éditeur de contenu
                        </div>
                        <div class="tools">

                        </div>
                    </div>
                    <div class="portlet-body form"><?php 
                        echo $this->Form->create('User',array('class' =>"form-horizontal",
																 'type'  => 'file')); ?>			
                        <div class="form-body">
                            <label class="error" id="productError"></label>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Email Address<span class="star">*</span></label>
                                <div class="col-md-6 col-lg-4"><?php
                                    echo $this->Form->input('User.username',
                                                                            array('class' => 'form-control',
                                                                                      'label' => false)); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Password<span class="star">*</span></label>
                                <div class="col-md-6 col-lg-4"><?php
                                    echo $this->Form->input('User.password',
                                                                            array('class' => 'form-control',
                                                                                      'label' => false)); ?>
                                </div>
                            </div>


                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9"><?php
                                            echo $this->Form->button(__('<i class="fa fa-check"></i>'.__('Save')),
                                                                            array('class'=>'btn purple',
                                                                                      'onclick'=>'return validateContentWriter();')); ?> <?php

?>										
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
