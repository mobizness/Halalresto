<div class="contain">
    <div class="contain">
        <h3 class="page-title">Créer une lettre d'information</h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Accueil</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="<?php echo $siteUrl.'/admin/users/newsletter';?>">Gestion des lettres d'actualité</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="javascript:void(0);">Créer une lettre d'information</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PORTLET-->
                <div class="portlet box blue-hoki">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-user"></i> Envoyer un bulletin
                        </div>
                        <div class="tools">

                        </div>
                    </div>
                    <div class="portlet-body form"><?php 
                        echo $this->Form->create('Newsletter',array('class' =>"form-horizontal",
																 'type'  => 'file')); ?>			
                        <div class="form-body">
                            <label class="error" id="productError"></label>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Subject<span class="star">*</span></label>
                                <div class="col-md-6 col-lg-4"><?php
                                    echo $this->Form->input('Newsletter.subject',
                                                                            array('class' => 'form-control newssubject',
                                                                                'type' => 'text',
                                                                                      'label' => false)); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Body<span class="star">*</span></label>
                                <div class="col-md-6 col-lg-4"><?php
                                    echo $this->Form->input('Newsletter.body',
                                                                            array('class' => 'form-control newsbody',
                                                                                'type' => 'textarea',
                                                                                      'label' => false)); ?>
                                </div>
                            </div>


                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9"><?php
                                            echo $this->Form->button(__('<i class="fa fa-check"></i>'.__('Save')),
                                                                            array('class'=>'btn purple',
                                                                                      'onclick'=>'return validateNewsLetter();')); ?> <?php

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
