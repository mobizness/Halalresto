<?php
/**
 * Created by Manikandan N.
 * User: admin6
 * Date: 5/12/16
 * Time: 2:47 PM
 */
?>
<div class="contain">
    <div class="contain">
        <h3 class="page-title">Ajouter Articles</h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Accueil</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="<?php echo $siteUrl.'/admin/addons/index';?>">Gestion Articles</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="javascript:void(0);">Ajouter Articles</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PORTLET-->
                <div class="portlet box blue-hoki">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-user"></i> Ajouter Articles
                        </div>
                        <div class="tools">

                        </div>
                    </div>
                    <div class="portlet-body form"><?php
                        echo $this->Form->create('Addons',array('class' =>"form-horizontal",
                            'type'  => 'file')); ?>
                        <?php echo $this->Form->hidden('Mainaddon.id'); ?>
                        <div id="addonError" class="alert alert-danger"></div>
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Restaurant<span class="star">*</span></label>
                                <div class="col-md-6 col-lg-5"> <?php
                                    echo $this->Form->input('Mainaddon.store_id', [
                                        'type'  => 'select',
                                        'class' => 'form-control',
                                        'options' => array($stores),
                                        'empty' => __('Select Restaurant'),
                                        'label' => false
                                        ]); ?>
                                </div>
                            </div> 
                            <div class="form-group">
                                <label class="col-md-2 control-label">Catégorie Nom <span class="star">*</span></label>
                                <div class="col-md-6 col-lg-5"><?php
                                    echo $this->Form->input('Mainaddon.category_id', [
                                        'type'  => 'select',
                                        'class' => 'form-control',
                                        'options'=> array($category_list),
                                        'empty' => 'Sélectionnez le Catégorie',
                                        'label'=> false
                                        ]); ?>
                                </div>
                            </div>
                            <div class="form-group" id="mainAddons">
                                <label class="col-md-2 control-label">Nom Articles<span class="star">*</span></label>
                                <div class="col-md-6 col-lg-3"> <?php
                                    echo $this->Form->input('Mainaddon.mainaddons_name', [
                                        'type'  => 'text',
                                        'class' => 'form-control',
                                        'placeholder' => 'Nom principal option',
                                        'label' => false
                                    ]); ?>
                                </div>
                                <div class="col-md-6 col-lg-2"> <?php
                                    echo $this->Form->input('Mainaddon.mainaddons_count', [
                                        'type'  => 'text',
                                        'class' => 'form-control',
                                        'placeholder' => 'Nombre',
                                        'label'=> false
                                    ]); ?>
                                </div>
                                <div class="col-md-6 col-lg-2">
                                    <a href="javascript:;" onclick="addSubAddons()" class="btn btn-success">Ajouter Sous Articles</a>
                                </div>
                                <!--<div class="col-md-6 col-lg-3">
                                    <a href="javascript:;" onclick="addMainAddons()" class="btn btn-success">Add More Main Addons</a>
                                </div>-->
                            </div>

                            <div id="subAddonsList">
                                <div class="form-group" id="removeSubaddon_<?php echo $key; ?>">
                                    <div class="col-md-6 col-lg-3 col-md-offset-3">
                                        <input type="text" id="SubAddonName" name="data[Mainaddon][Subaddon][0][subaddons_name]" class="form-control" placeholder="Sous article Nom">
                                    </div>
                                    <div class="col-md-6 col-lg-2">
                                        <input type="text" id="SubAddonPrice" name="data[Mainaddon][Subaddon][0][subaddons_price]" class="form-control" placeholder="Prix">
                                    </div>
                                </div>
                            </div>

                            <div id="mainaddonsList"></div>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9"><?php
                                        echo $this->Form->button(__('<i class="fa fa-check"></i>'.__('Save')),
                                            [
                                                'class'=>'btn purple',
                                                'onclick'=>'return addonsValidate();'
                                            ]); ?> <?php
                                        echo $this->Html->link(__('Cancel'),
                                            [
                                                'action' => 'index'
                                            ],
                                            [
                                                'Class'=>'btn default'
                                            ]); ?>
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
