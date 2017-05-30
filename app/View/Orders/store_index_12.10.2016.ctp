<div class="page-content-wrapper">
	<div class="page-content">
		<h3 class="page-title">Report</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/store/dashboards/index';?>">Home</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Report</a>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box grey-cascade">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-globe"></i>Report
						</div>
						<div class="tools">
							
						</div>
					</div>
					<div class="portlet-body">
						<div class="table-toolbar">
						</div>
						<table class="table table-striped table-bordered table-hover checktable" id="sample_12">
							<thead>
								<tr>
									<th class="no-sort">S.No</th>
									<th>Order Id</th>
									<th>Customer Name</th>
									<th>Price</th>
									<th>Payment Type</th>
									<th>Order Status</th>
									<th>Ordered Date</th>
									<th>Delivered Date</th>
								</tr>
							</thead>
							<tbody><?php 
							
								$count = 1;
								foreach ($order_list as $key => $value) { ?>
									<tr class="odd gradeX">
										<td><?php echo $count;?></td>
										<td><?php
										echo $this->Html->link($value['Order']['ref_number'],
																	array('controller'=>'Orders',
																		   'action'=>'reportOrderView',
																		   $value['Order']['id'])
																  );?></td>
										<td><?php echo $value['Order']['customer_name'];?></td>
										<td><?php echo $value['Order']['order_grand_total'];?></td>
										<td><?php echo $value['Order']['payment_type'];?></td>
										<td><?php echo $value['Order']['status'];?></td>
										<td><?php echo $value['Order']['created'];?></td>

										<td><?php echo $value['Order']['delivery_date']; ?></td>
									</tr><?php $count ++;
								}?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="reportpopup" class="modal fade" >
  <div role="document" class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Track Order</h4>
      </div>
      <div id="trackingContent" class="modal-body">                    
      	<div class="popbox clearfix new">
            <div class="col-lg-2 col-md-2 col-sm-3 box-left">
               <span class="status">New</span>
            </div>
            <div class="col-lg-7 col-md-7 col-sm-6 assignorder"> Order received to Banana Leaf Multi cuisine Restaurant</div>
            <div class="col-lg-3 col-md-3 col-sm-3 date">2015-10-28 04:59:37                        </div>
        </div>                     
        <div class="popbox clearfix accepted">
            <div class="col-lg-2 col-md-2 col-sm-3 box-left">
               <span class="status">
                     Accepted                            </span>
            </div>
            <div class="col-lg-7 col-md-7 col-sm-6 assignorder"> Rajaram accepted this order                        </div>
            <div class="col-lg-3 col-md-3 col-sm-3 date">
                2015-10-28 05:03:29                        </div>
        </div>                     <div class="popbox clearfix pickedup">
        <div class="col-lg-2 col-md-2 col-sm-3 box-left">
            <span class="status">
                     Picked up                            </span>
        </div>
        <div class="col-lg-7 col-md-7 col-sm-6 assignorder"> Rajaram picked this order from Banana Leaf Multi cuisine Restaurant                        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 date">
                2015-10-28 05:03:53                        </div>
        </div>                     
        <div class="popbox clearfix ontheway">
            <div class="col-lg-2 col-md-2 col-sm-3 box-left">
               <span class="status">
                     On the way                            </span>
            </div>
            <div class="col-lg-7 col-md-7 col-sm-6 assignorder"> Rajaram on the way to deliver                        </div>
            <div class="col-lg-3 col-md-3 col-sm-3 date">
                2015-10-28 05:05:28                        </div>
        </div>                     
        <div class="popbox clearfix delivered">
            <div class="col-lg-2 col-md-2 col-sm-3 box-left">
               <span class="status">
                     Delivered                            </span>
            </div>
            <div class="col-lg-7 col-md-7 col-sm-6 assignorder"> Rajaram deleivered this order to Rajaram.R                        </div>
            <div class="col-lg-3 col-md-3 col-sm-3 date">
                2015-10-28 13:13:13                        </div>
        </div> 
    </div>
</div>
</div>
</div>


