
<div class="contain">
	<div class="contain">
		<h3 class="page-title">Facture</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Accueil</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="javascript:void(0);">Gestion Facture</a>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box grey-cascade">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-globe"></i>Gestion Facture
						</div>
						<div class="tools"></div>
					</div>
					<div class="portlet-body">
						<div class="table-toolbar">
						</div>
						<table class="table table-striped table-bordered table-hover" id="sample_12">
					<thead>
						<tr>
							<th class="no-sort">S_no</th>
							<th>N° facture</th>
							<th>Nom du Restaurant</th>
							<th>Du</th>
							<th>Au</th>
							<th>la période de facturation</th>
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
							<td><?php echo date("Y/m/d H:i:s", strtotime($value['Invoice']['start_date']));?></td>
							<td><?php echo date("Y/m/d H:i:s", strtotime($value['Invoice']['end_date']));?></td>
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
