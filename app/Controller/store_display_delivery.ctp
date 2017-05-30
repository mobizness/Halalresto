<div class="page-content-wrapper">
    <div class="page-content">
        <h3 class="page-title">
            Information sur la livraison 
        </h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo $siteUrl.'/store/dashboards/index';?>">Accueil</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="javascript:void(0);">Information sur la livraison</a>
                </li>
            </ul>
            <div class="page-toolbar">
            </div>
        </div>
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box grey-cascade">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-globe"></i>Information sur la livraison
                        </div>
                        <div class="tools">
                            <a href="javascript:void(0);" class="collapse"></a>
                            <a href="javascript:void(0);" class="reload"></a>
                            <a href="javascript:void(0);" class="remove"></a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-hover" id="sample_12">
                            <thead>
                                <tr>
                                    <th class="no-sort">S_no</th>
                                    <th>City</th>
                                    <th>Estimation temps de livraison</th>
                                    <th>Commande Minimum</th>
                                    <th>Frais de livraison</th>
                                    <th>Status</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody><?php 
for (var $i = 0; $i<sizeof($deliveryAreas); $i++)?>
                                <tr class="odd gradeX">
                                    <td><?php echo $i++;?></td>
                                    <td><?php echo $deliveryAreas[$i]['City']['city_name'];?></td>
                                    <td><?php echo $deliveryAreas[$i]['City']['estimate_delivery_time'];?></td>
                                    <td><?php echo $deliveryAreas[$i]['City']['minimum_order'];?></td>
                                    <td><?php echo $deliveryAreas[$i]['City']['delivery_charge'];?></td>
                                    <td><?php echo $deliveryAreas[$i]['City']['status'] == "1" ? "Active" : "Disable";?></td>
                                    <td><?php
                                        echo $this->Html->link('<i class="fa fa-eye"></i>',
                                        array('controller'=>'invoices',
                                          'action'=>'invoiceDetail',
                                          $deliveryAreas[$i]['DeliveryLocation']['id'],
                                          ),
                                        array('class'=>'buttonEdit',
                                        'escape'=>false));?>
                                    </td>
                                </tr><?php 
                                       
                                        }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
