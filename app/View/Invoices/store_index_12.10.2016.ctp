<div class="page-content-wrapper">
	<div class="page-content">
		<h3 class="page-title">
			Invoice 
		</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/store/dashboards/index';?>">Home</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="javascript:void(0);">Invoice Manage</a>
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
							<i class="fa fa-globe"></i>Invoice Manage
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
							<th>Invoice  Id</th>
							<th>Store Name</th>
							<th>From</th>
							<th>To</th>
							<th>Invoice Period</th>
							<th class="no-sort">Action</th>
						</tr>
					</thead>
					<tbody><?php 
					$count = 1;
					foreach($invoice_list as $key=>$value){?>
						<tr class="odd gradeX">
							<td><?php echo $count;?></td>
							<td><?php echo $value['Invoice']['ref_id'];?></td>
							<td><?php echo $value['Store']['store_name'];?></td>
							<td><?php echo $value['Invoice']['start_date'];?></td>
							<td><?php echo $value['Invoice']['end_date'];?></td>
							<td><?php echo $value['Store']['invoice_period'];?></td>	
							<td><?php
							echo $this->Html->link('<i class="fa fa-eye"></i>',
												array('controller'=>'invoices',
													   'action'=>'invoiceDetail',
													   $value['Store']['id'],
													   	$value['Invoice']['start_date'],
													   $value['Invoice']['end_date'],
													   $value['Invoice']['id'],),
												array('class'=>'buttonEdit',
														'escape'=>false));?>
							</td>
						</tr><?php 
						$count ++;
					}?>
					</tbody>
				</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
