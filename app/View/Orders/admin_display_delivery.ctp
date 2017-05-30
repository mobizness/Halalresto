
<div class="contain">
    <div class="contain">
        <h3 class="page-title">Information sur la livraison </h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Accueil</a>
                    <i class="fa fa-angle-right"></i>
                </li>

                <li>
                    <a href="javascript:void(0);">Information sur la livraison </a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box grey-cascade">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-globe"></i>Information sur la livraison 
                        </div>
                        <div class="tools"> 

                        </div>
                    </div>
                    <div class="portlet-body">
	               		<?php echo $this->Form->create('DeliveryLocation',array('class' =>"form-horizontal", 'type'  => 'file')); 			
                                ?>

                        <div class="table-toolbar">

                            <div class="btn-group pull-right">  <?php
                            echo $this->Html->link('Ajouter Nouveau <i class="fa fa-plus"></i>',
                                                array('controller'=>'orders',
                                                           'action'=>'deliveryAdd'),
                                               	array('class'=>'btn green',
						'escape'=>false)); ?>
                            </div>
                            <span class="col-md-4 pull-right no-padding"> 
                                <label class="control-label col-sm-3"><?php echo __('Filter');?></label>
                                <span class="col-md-9"> 
                                    <?php
                                    echo $this->Form->input('DeliveryLocation.store_id',
                                    array('type'  => 'select',
                                    'class' => 'form-control',
                                    'options'=> array($stores),
                                    'empty' => __('Select Restaurant'),
                                    'default'=> $this->params['pass'][0],
                                    'label'=> false)); ?>
                                    <label class="error" id="storeProductError" generated="true" for="ProductStoreId"></label>
                                </span>

                            </span> 

                        </div>
                        <table class="table table-striped table-bordered table-hover checktable" id="sample_12">
                            <thead>
                                <tr>
                                    <th class="no-sort">S_no</th>
                                    <th>City</th>
                                    <th>Estimation temps de livraison</th>
                                    <th>Commande Minimum</th>
                                    <th>Frais de livraison</th>
                                    <th>Status</th>
                                    <th class="three no-sort">Changer d'état</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody><?php 
                        for ($i = 0; $i<sizeof($deliveryAreas); $i++) {?>
                                <tr class="odd gradeX">
                                    <td><?php echo ($i+1);?></td>
                                    <td><?php echo $deliveryAreas[$i]['DeliveryLocation']['city_id'];?></td>
                                    <td><?php echo $deliveryAreas[$i]['DeliveryLocation']['estimate_delivery_time'];?></td>
                                    <td><?php echo $deliveryAreas[$i]['DeliveryLocation']['minimum_order'];?></td>
                                    <td><?php echo $deliveryAreas[$i]['DeliveryLocation']['delivery_charge'];?></td>
                                    <td id="deliverystatus_<?php echo $deliveryAreas[$i]['DeliveryLocation']['id'];?>"><?php echo $deliveryAreas[$i]['DeliveryLocation']['status'] == "1" ? "Active" : "Deactivate";?></td>

                                    <td align="center"> <?php 
                                            if($deliveryAreas[$i]['DeliveryLocation']['status'] == 1) {?>
                                        <a title="Deactive" class="buttonStatus red_bck" href="javascript:void(0);" 
                                           onclick="deleteDeliveryInfo(<?php echo ( $deliveryAreas[$i]['DeliveryLocation']['id'] ); ?>, <?php echo $deliveryAreas[$i]['DeliveryLocation']['status'];?>, 'update');">
                                            <i class="">Deactivate</i><!-- deactive --></a>
                                            <?php } else if ($deliveryAreas[$i]['DeliveryLocation']['status'] == 2) {
                                            ?>
                                        <a title="Active" class="buttonStatus" href="javascript:void(0);" 
                                           onclick="deleteDeliveryInfo(<?php echo ( $deliveryAreas[$i]['DeliveryLocation']['id'] ); ?>, <?php echo $deliveryAreas[$i]['DeliveryLocation']['status'];?>, 'update');">
                                            <i class="">Active</i></a>
                                            <?php } else {?>
                                        <a title="Pending" class="buttonStatus yellow_bck" href="javascript:void(0);" 
                                           onclick="deleteDeliveryInfo(<?php echo ( $deliveryAreas[$i]['DeliveryLocation']['id'] ); ?>, <?php echo $deliveryAreas[$i]['DeliveryLocation']['status'];?>, 'update');">
                                            <i class="">Pending</i><!-- Pending --></a>
                                            <?php }?>
                                    </td>

                                    <td><?php
                                        echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',
                                        array('controller'=>'orders',
                                          'action'=>'deliveryDetail',
                                          ($deliveryAreas[$i]['DeliveryLocation']['id']),
                                          ),
                                        array('class'=>'buttonEdit',
                                        'escape'=>false));?>

                                        <a class="buttonAction" href="javascript:void(0);" onclick="deleteDeliveryInfo(<?php echo ( $deliveryAreas[$i]['DeliveryLocation']['id'] ); ?>, <?php echo $deliveryAreas[$i]['DeliveryLocation']['status'];?>, 'delete');"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr><?php 
                                       
                                        }?>
                            </tbody>
                        </table><?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>