<div class="contain">
    <div class="contain">
        <h3 class="page-title">Manage Orders</h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><i class="fa fa-home"></i><a href="<?php echo $siteUrl.'/admin/dashboards/index'; ?>">Home</a><i class="fa fa-angle-right"></i></li>
                <li><a href="javascript:void(0);">Manage Orders</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box grey-cascade">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-globe"></i>Manage Orders
                        </div>
                        <div class="tools">
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-toolbar">
                            <!--<div class="row">
                                <div class="col-md-12">
                                    <div class="btn-group pull-right">
                                        <a href="addnewUser.html" id="sample_editable_1_new" class="btn green">
                                            Add New <i class="fa fa-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>-->
                        </div>
                        <table class="table table-striped table-bordered table-hover checktable" id="sample_12">
                            <thead>
                            <tr>
                                <th class="no-sort">S.No</th>
                                <th>Order Id</th>
                                <th>Restaurant Name</th>
                                <th>Payment Type</th>
                                <th>Price</th>
                                <th>Failed Reason</th>
                                <th class="no-sort">Order Status</th>
                                <th class="no-sort">Action</th>
                            </tr>
                            </thead>
                            <tbody><?php

                            $count = 1;
                            foreach($order_list as $key => $value) { ?>
                            <tr id="orderList_<?php echo $value['Order']['id']; ?>" class="odd gradeX">
                                <td><?php echo $count ;?></td>
                                <td><?php echo $value['Order']['ref_number'];?></td>
                                <td><?php echo $value['Store']['store_name'];?></td>
                                <td><?php echo $value['Order']['payment_type'];?></td>

                                <td><?php
                                    echo html_entity_decode($this->Number->currency($value['Order']['order_grand_total'], $siteCurrency)); ?></td>
                                <td><?php echo $value['Order']['failed_reason'];?></td>
                                <td align="center"> <?php
                                    echo $this->Form->input('orderStatus_'.$value['Order']['id'],
                                        array('type'=>'select',
                                            'class'=>'form-control',
                                            'options'=> array($status),
                                            'onchange' => "orderStatus(".$value['Order']['id'].");",
                                            'label'=> false,
                                            'value' => $value['Order']['status'])); ?>

                                    <div class="contain" id="reason_<?php echo $value['Order']['id']; ?>"></div> </td>

                                <td align="center"><?php
                                    echo $this->Html->link('<i class="fa fa-search"></i>',
                                        array('controller'=>'Orders',
                                            'action'=>'orderView',
                                            $value['Order']['id']),
                                        array('class'=>'buttonEdit',
                                            'escape'=>false));?>

                                    <a class="buttonAction" href="javascript:void(0);"
                                       onclick="deleteOrder(<?php echo $value['Order']['id'];?>);" ><i class="fa fa-trash-o"></i></a>
                                </td>
                                </tr><?php $count++;
                            }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
