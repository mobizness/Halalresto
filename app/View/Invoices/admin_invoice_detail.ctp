<div class="contain">
	<div class="contain" id="invoice_print">
		<h3 class="page-title"> Invoice </h3>
		<div class="page-bar">
			<ul class="page-breadcrumb hidden-print">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Home</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="<?php echo $siteUrl.'/admin/invoices/index';?>">Invoice Manage</a>
				</li>
			</ul>
			<div class="page-toolbar">
			</div>
		</div>
		<!-- END PAGE HEADER-->
		<!-- BEGIN PAGE CONTENT-->
		<div class="portlet light">
			<div class="portlet-body">
				<div class="invoice">
					<div class="row invoice-logo">
						<div class="col-xs-6 invoice-logo-space">
							
						</div>
						<div class="col-xs-6">
							<p><?php echo $invoice_detail['Invoice']['ref_id']; ?> </p>
						</div>						
					</div>
					Cretated :<?php echo $invoice_detail['Invoice']['created']; ?></br>
							
					Period :<?php 
						echo $invoice_detail['Invoice']['start_date'].' to '.
							 $invoice_detail['Invoice']['end_date'];  ?>
					<hr>
					<div class="row">
						<div class="col-md-4 col-xs-12">
							<h3>Client:</h3>
							<ul class="list-unstyled">
								<li> <?php echo $invoice_detail['Store']['contact_name']; ?> </li>
								<li> <?php echo $invoice_detail['Store']['store_name']; ?> </li>
								<li> <?php
									if($siteSetting['Sitesetting']['address_mode'] != 'Google') {
										echo $area_list['Location']['area_name'] . ',' .
												$area_list['City']['city_name'] . '-' .
												$area_list['Location']['zip_code'] . ',' .
												$area_list['State']['state_name'] . ',' .
												$state_list['Country']['country_name'];
									} else {
										echo $invoice_detail['Store']['address'];
									}?>
								</li>
								
							</ul>
						</div>
						<div class="col-md-4 col-xs-12">
							<h3>About:</h3>
							<ul class="list-unstyled">
								<li>
									 <?php echo $siteSetting['Sitesetting']['site_name']; ?>
								</li>
								
							</ul>
						</div>
						<div class="col-md-4 col-xs-12 invoice-payment">
							<h3>Payment Details:</h3>
							<ul class="list-unstyled">
								<li>
									<strong>V.A.T Reg #:</strong> <?php
									echo $site_detail['Sitesetting']['vat_no'];
									?>
								</li>
								
							</ul>
						</div>
					</div>
					<div class="row">
						<div style="display:block; width:100%; vertical-align:top; margin-top:15px;margin-bottom:10px;">
							<div style="clear:both;"></div>
							<table width="100%" align="center"  style="font:13px Arial;">
								<tr style="border-bottom:1px solid #000; font-size:18px;">
									<th width="70%" style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; font-weight:bold;">
										<table width="100%" align="center">
											<tr>
												<td width="70%">Invoice breakdown</td>
												<td width="30%" style="text-align:right;"></td>
											</tr>
										</table>
									</th>
									<th width="30%" style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; text-align:right; font-weight:bold;">Amount</th>
								</tr>
								<tr>
									<td width="70%" style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px;">
										<table width="100%" align="center">

											<tr>
												<td width="70%">Customers paid cash for</td>
												<td width="30%" style="text-align:right;"> <?php
													echo $invoice_detail['Invoice']['cod_count']; ?> orders</td>
											</tr>
										</table>
									</td>
									<td width="30%" style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; text-align:right;"> <?php
										echo html_entity_decode($this->Number->currency($invoice_detail['Invoice']['cod_price'], $siteCurrency)); ?></td>
								</tr>
								<tr>
									<td width="70%" style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px;">
										<table width="100%" align="center">
											<tr>
												<td width="70%">Customers prepaid online with card for </td>
												<td width="30%" style="text-align:right;"> <?php
													echo $invoice_detail['Invoice']['card_count']; ?> orders</td>
											</tr>
										</table>
									</td>
									<td width="30%" style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; text-align:right;"> <?php
										echo html_entity_decode($this->Number->currency($invoice_detail['Invoice']['card_price'], $siteCurrency)); ?> </td>
								</tr>
								<tr>
									<td width="70%" style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px;">
										<table width="100%" align="center">
											<tr>
												<td width="70%">Customers prepaid online with wallet for </td>
												<td width="30%" style="text-align:right;"> <?php
													echo $invoice_detail['Invoice']['wallet_count']; ?> orders</td>
											</tr>
										</table>
									</td>
									<td width="30%" style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; text-align:right;"> <?php
										echo html_entity_decode($this->Number->currency($invoice_detail['Invoice']['wallet_price'], $siteCurrency)); ?> </td>
								</tr>
								<tr>
									<td width="70%" style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px;">
										<table width="100%" align="center">
											<tr>
												<td width="70%">Total value for</td>
												<td width="30%" style="text-align:right;"> <?php
													echo $invoice_detail['Invoice']['total_order']; ?> orders</td>
											</tr>
										</table>
									</td>
									<td width="30%" style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; text-align:right;"> <?php
										echo html_entity_decode($this->Number->currency($invoice_detail['Invoice']['subtotal'], $siteCurrency)); ?></td>
								</tr>
							</table>
							<table width="100%" align="center" style="font:13px Arial;">
								<tr>
									<td width="85%" style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; text-align:right; border-top:1px solid #ddd9c3;">
										Paid Gross Sale (Including Tax, Delivery charge, Tips) :</td>
									<td width="15%" style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; text-align:right; border-top:1px solid #ddd9c3;"> <?php
										echo html_entity_decode($this->Number->currency($invoice_detail['Invoice']['gross_sale_amount'], $siteCurrency)); ?> </td>
								</tr>

								<tr>
									<td width="85%" style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; text-align:right; border-top:1px solid #ddd9c3;">
										Commission(<?php echo $invoice_detail['Invoice']['store_commission'];?>%) :</td>
									<td width="15%" style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; text-align:right; border-top:1px solid #ddd9c3;"> <?php
										echo html_entity_decode($this->Number->currency($invoice_detail['Invoice']['commision'], $siteCurrency)); ?> </td>
								</tr>
			                    <tr>
									<td width="85%" style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; text-align:right;" > Total Card Fee :</td>
									<td width="15%" style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; text-align:right; "> <?php
										echo html_entity_decode($this->Number->currency($invoice_detail['Invoice']['cardfee_total'], $siteCurrency)); ?> </td>
								</tr>
			                    <tr>
									<td width="85%" style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; text-align:right;" >Total Commission with Card fee :</td>
									<td width="15%" style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; text-align:right; border-bottom:1px solid #ddd9c3;"> <?php
										echo html_entity_decode($this->Number->currency($invoice_detail['Invoice']['commissionTotal'], $siteCurrency)); ?> </td>
								</tr>
								<tr>
									<td width="85%" style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; text-align:right; border-top:1px solid #ddd9c3;">
										VAT (<?php echo $invoice_detail['Invoice']['tax'];?>%) :</td>
									<td width="15%" style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; text-align:right;"> <?php
										echo html_entity_decode($this->Number->currency($invoice_detail['Invoice']['commision_tax'], $siteCurrency)); ?> </td>
								</tr>
			                    <tr>
									<td width="85%" style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; text-align:right;">Total Commission from Restaurant :</td>
									<td width="15%" style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; text-align:right;"> <?php
										echo html_entity_decode($this->Number->currency($invoice_detail['Invoice']['commisionGrand'], $siteCurrency)); ?></td>
								</tr>
								<tr>
									<td width="85%" style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; text-align:right; border-top:1px solid #ddd9c3;">
										Restaurant Owned ( <?php
                                    	echo html_entity_decode($this->Number->currency($invoice_detail['Invoice']['gross_sale_amount'], $siteCurrency)).' - '.html_entity_decode($this->Number->currency($invoice_detail['Invoice']['commisionGrand'], $siteCurrency)); ?> )  : </td>
									<td width="15%" style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; text-align:right; border-top:1px solid #ddd9c3;"> <?php
										if ($invoice_detail['Invoice']['store_owned_total'] < 0)
	                                    	echo '- ';
	                                    echo html_entity_decode($this->Number->currency($invoice_detail['Invoice']['store_owned_total'], $siteCurrency)); ?> </td>
								</tr>
								<tr height="1"><td>&nbsp;</td><td>&nbsp;</td></tr>
								<tr>
									<td width="85%" style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; text-align:right; border-top:1px solid #ddd9c3; font-weight:bold;"></td>
									<td width="15%" style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; text-align:right; border-top:1px solid #ddd9c3; font-weight:bold;"> </td>
								</tr>
							</table>
						</div>

						<div class="col-xs-8 invoice-block col-xs-offset-3">
                            <a  onclick="PrintElem('#invoice_print')" class="btn btn-lg blue hidden-print margin-bottom-5 btn btn-info">
                                Print <i class="fa fa-print"></i>
                            </a>
                            <a target="_blank" href="<?php echo $siteUrl.'/Invoices/invoicePdf/'.$invoice_detail['Invoice']['id'];?>"
                               class="btn btn-lg blue hidden-print margin-bottom-5">
                                DownloadPDF <i class="fa fa-file-pdf-o"></i>
                            </a>
                        </div>
						<div class="portlet-body">
							<div class="table-toolbar"> </div> <?php
							$main = '';
							$count = 1;
							foreach($order_detail as $key => $value) {
								$nextValue = $key+1;
								if ($value['Order']['payment_type'] != $main) {
									$main = $value['Order']['payment_type']; ?>

										<h4><?php 
											echo ($value['Order']['payment_type'] == 'cod') ? 'Cash' : $value['Order']['payment_type'].' Paid'; ?></h4>
										<table class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
													<th>S.no</th>
													<th>Order Id</th>
			                                        <th>Subtotal</th>
													<th>Tax</th>
			                                        <th>Delivery Charge</th>
			                                        <th>Tips</th>
													<th>Commision</th>
												</tr>
											</thead>
											<tbody> <?php
								 } ?>
									 			<tr class="odd gradeX">
		                                            <td><?php echo $count; ?></td>
		                                            <td><?php echo $value['Order']['ref_number']; ?></td>
		                                            <td><?php
		                                                echo html_entity_decode($this->Number->currency($value['Order']['order_sub_total'], $siteCurrency)); ?>
		                                            </td>
		                                            <td><?php echo ($value['Order']['tax_amount'] != 0) ? html_entity_decode($this->Number->currency($value['Order']['tax_amount'], $siteCurrency)) : $siteCurrency.' 0.00';?>
		                                            </td>
		                                            <td><?php echo ($value['Order']['delivery_charge'] != 0) ? html_entity_decode($this->Number->currency($value['Order']['delivery_charge'], $siteCurrency)) : $siteCurrency.' 0.00';?>
		                                            </td>
		                                            <td><?php echo ($value['Order']['tip_amount'] != 0) ? html_entity_decode($this->Number->currency($value['Order']['tip_amount'], $siteCurrency)) : $siteCurrency .' 0.00';?>
		                                            </td>
		                                            <td><?php
		                                                $commision = $value['Order']['order_sub_total'] * ($invoice_detail['Invoice']['store_commission'] / 100);
		                                                echo html_entity_decode($this->Number->currency($commision, $siteCurrency));
		                                                ?>
		                                            </td>
												</tr><?php
	                                            $count++;
								if (!isset($order_detail[$nextValue]['Order']['payment_type']) || 
                						$order_detail[$nextValue]['Order']['payment_type'] != $main) {
                						$count = 1;  ?>
											</tbody>
										</table> <?php
								}
							} ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<script type="text/javascript">

    function PrintElem(elem) {
        Popup($(elem).html());
    }

    function Popup(data) {
    	console.log(rp);
        var mywindow = window.open('', 'my div', '');
        mywindow.document.write('<html><head><title>Invoice</title>');
       	mywindow.document.write('<link rel="stylesheet" type="text/css" href="'+rp+'/css/bootstrap.min.css" /><link rel="stylesheet" type="text/css" href="'+rp+'/css/components.css" />');
        mywindow.document.write('<style type="text/css">*{font:13px Arial!important;}</style >');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10

        mywindow.print();
        mywindow.close();

        return true;
    }

</script>