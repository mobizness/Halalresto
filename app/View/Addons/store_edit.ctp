<?php
/**
 * Created by Manikandan N.
 * User: admin6
 * Date: 5/12/16
 * Time: 2:47 PM
 */
?>
<div class="page-content-wrapper">
    <div class="page-content">
        <h3 class="page-title">Editer Articles</h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo $siteUrl.'/store/dashboards/index';?>">Accueil</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="<?php echo $siteUrl.'/store/addons/index';?>">Gestion Articles</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="javascript:void(0);">Editer Articles</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PORTLET-->
                <div class="portlet box blue-hoki">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-user"></i> Editer Articles
                        </div>
                        <div class="tools">

                        </div>
                    </div>
                    <div class="portlet-body form"><?php
                        echo $this->Form->create('Addons',array('class' =>"form-horizontal",
                            'type'  => 'file')); ?>
                        <?php echo $this->Form->hidden('Mainaddon.id'); ?>
                        <?php echo $this->Form->input('Mainaddon.store_id', [
                            'type'  => 'hidden',
                            'value' => $store_id
                        ]); ?>
                        <div class="form-body">
                            <label id="addonError" class="error"></label>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Nom Catégorie <span class="star">*</span></label>
                                <div class="col-md-6 col-lg-4"><?php
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
                                <label class="col-md-2 control-label">Nom Articles <span class="star">*</span></label>
                                <div class="col-md-6 col-lg-3">
                                    <?php echo $this->Form->input('Mainaddon.mainaddons_name', [
                                        'type'  => 'text',
                                        'class' => 'form-control',
                                        'label'=> false
                                    ]); ?>
                                </div>
                                <div class="col-md-6 col-lg-2">
                                    <?php echo $this->Form->input('Mainaddon.mainaddons_count', [
                                        'type'  => 'text',
                                        'class' => 'form-control',
                                        'label'=> false
                                    ]); ?>
                                </div>
                                <!--<div class="col-md-6 col-lg-3">
                                    <a href="javascript:;" onclick="addMainAddons()" class="btn btn-success">Add More Main Addons</a>
                                </div>-->
                                <div class="col-md-6 col-lg-2">
                                    <a href="javascript:;" onclick="addSubAddons()" class="btn btn-success">Ajouter Sous Articles</a>
                                </div>
                            </div> 
                            <script>var j = '<?php echo $key+1; ?>';</script>
                            
                            <?php
                            foreach ($this->request->data['Subaddon'] as $key => $val) { ?>
                                <div class="form-group" id="removeSubaddon_<?php echo $key; ?>">
                                    <div class="col-md-6 col-lg-3 col-md-offset-2">
                                        <?php echo $this->Form->input('Subaddon.subaddons_name', [
                                            'type'  => 'text',
                                            'class' => 'form-control',
                                            'name' => "data[Mainaddon][Subaddon][$key][subaddons_name]",
                                            'value' => $val['subaddons_name'],
                                            'placeholder' => 'Subaddon Name',
                                            'id' => ($key == 0) ? 'SubAddonName' : '',
                                            'label'=> false
                                        ]); ?>
                                    </div>
                                    <div class="col-md-6 col-lg-2">
                                        <?php echo $this->Form->input('Subaddon.subaddons_price', [
                                            'type'  => 'text',
                                            'class' => 'form-control',
                                            'name' => "data[Mainaddon][Subaddon][$key][subaddons_price]",
                                            'value' => $val['subaddons_price'],
                                            'placeholder' => 'Price',
                                            'id' => ($key == 0) ? 'SubAddonPrice' : '',
                                            'label'=> false
                                        ]); ?>
                                    </div> <?php
                                    if ($key != 0) { ?>

                                        <div class="col-md-6 col-lg-2">
                                            <a href="javascript:;" onclick="removeSubAddonsEdit(<?php echo $val['id'].','. $key; ?>);" class="btn btn-danger">X</a>
                                        </div> <?php
                                    } ?>
                                </div> <?php
                            } ?>
                            <div id="subAddonsList"></div>
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
