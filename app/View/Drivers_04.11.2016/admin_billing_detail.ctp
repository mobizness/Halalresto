<div class="contain">
    <div class="contain">
        <h3 class="page-title">Driver Billing</h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>

                    <a href="<?php echo $siteUrl.'/admin/Dashboards/index'; ?>">Home</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo $siteUrl.'/admin/Drivers/index'; ?>">Driver</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="#">Driver Billing</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box grey-cascade">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-globe"></i> Driver Billing
                        </div>
                        <div class="tools">

                        </div>
                    </div>
                    <div class="portlet-body"><?php
                        echo $this->Form->create('Drivers', array('class'=>'form-horizontal',
                                        "url"=> array("controller" => 'drivers',
                                                     'action'      => 'billingDetail',
                                                     $driverId))); ?>
                            <div class="table-toolbar">
                                <div class="contain">
                                    <div class="pull-right text-right">
                                        <label class="control-label pull-left">Date Range <span class="star">*</span></label>
                                        <div class="pull-left">
                                            <div class="input-group input-medium date-pickers input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
                                                <?php
                                                echo $this->Form->input('from_date',
                                                    array('class'=>'form-control',
                                                        'autocomplete' => 'off',
                                                        //'value'=> date('Y-m-d', time()),
                                                        'value' => $fromDate,
                                                        'readonly' => true,
                                                        'label' => false,
                                                        'div' => false));
                                                echo $this->Form->hidden('id',array(
                                                                                'value'=>$driverId));?>
                                                <span class="input-group-addon"> to </span>
                                                <?php
                                                echo $this->Form->input('to_date',
                                                    array('class'=>'form-control',
                                                        'autocomplete' => 'off',
                                                        //'value'=>date('Y-m-d', time()),
                                                        'value' => $toDate,
                                                        'readonly' => true,
                                                        'label' => false,
                                                        'div' => false)); ?>
                                            </div>                                        
                                        </div>
                                        <div class="pull-left padding-l-15">
                                        <?php echo $this->Form->button(__('<i class="fa fa-search"></i>Filtter'),array('class'=>'btn purple'));
                                                ?>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div> <?php 
                        echo $this->Form->end(); ?>
                        <table class="table table-striped table-bordered table-hover checktable" id="sample_12">
                            <thead>
                            <tr>
                                <!-- <th class="table-checkbox no-sort"><input type="checkbox" class="group-checkable test1" data-set="#sample_1 .checkboxes" /></th> -->
                                <th>Order Id</th>
                                <th>Date</th>
                                <th>Restaurant Name</th>
                                <th>Price</th>
                                <th>Distance</th>

                            </tr>
                            </thead>
                            <tbody> <?php
                            foreach ($order_detail as $key => $value) {?>
                                <tr class="odd gradeX" id="record<?php echo $value['Order']['id'];?>">
                                    <!-- <td> --> <?php
                                        /*echo $this->Form->checkbox($value['Order']['id'],
                                            array('class'=>'checkboxes test' ,
                                                'label'=>false,
                                                'hiddenField'=>false,
                                                'value'=> $value['Order']['id']));*/ ?> <!-- </td> -->
                                    <td><?php echo $value['Order']['ref_number'];?></td>
                                    <td><?php echo $value['Order']['updated'];?></td>
                                    <td><?php echo $value['Store']['store_name'];?></td>
                                    <td><?php echo html_entity_decode($this->Number->currency($value['Order']['order_grand_total'],$siteCurrency));?></td>
                                    <td><?php echo $value['Order']['distance'];?></td>
                                </tr> <?php
                            } ?>
                            <tr>
                                <td align="right" colspan="3">Total</td>
                                <td class="price"><?php
                                    echo html_entity_decode($this->Number->currency($total_orderprice, $siteCurrency)); ?>
                                </td>

                                <td class="price"><?php
                                    $distanceType = ($total_km != 1) ? ' km' : ' m';
                                    echo $total_km . $distanceType; ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>