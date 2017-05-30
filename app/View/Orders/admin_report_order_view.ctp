<div class="contain">
	<div class="contain">
		<h3 class="page-title">ReportView</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/admin/Dashboards/index'; ?>">Accueil</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="<?php echo $siteUrl.'/admin/Orders/reportIndex'; ?>">Reporting</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Order Details <?php echo $orders_list['Order']['ref_number'];?></a>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<!-- Begin: life time stats -->
				<div class="portlet light">
					<div style="display:none;" id="orderMessage" class="alert alert-success"></div>
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-basket font-green-sharp"></i>
							<span class="caption-subject font-green-sharp bold uppercase">
							Order: <?php echo $orders_list['Order']['ref_number'];?></span>
							<span class="caption-helper">
							<?php echo $orders_list['Order']['created'];?></span>
						</div>
						<div class="actions"><?php
							if (in_array($orders_list['Order']['payment_type'], $payments) && $orders_list['Order']['payment_method'] == 'paid' && $orders_list['Order']['status'] != 'Delivered') { ?>
								<a href="javascript:void(0);" onclick="refundPayment(<?php echo $orders_list['Order']['id']; ?>);" class="btn btn-danger btn-circle">
								<span class="hidden-480">
								<?php echo __('Refund');?> </span> <?php
							} ?>
							<a href="<?php echo $siteUrl.'/admin/Orders/reportIndex'; ?>" class="btn btn-default btn-circle">
							<i class="fa fa-angle-left"></i>
							<span class="hidden-480">
							Retour </span>
							</a>									
						</div>
					</div>
					<div class="portlet-body">
						<div class="row">
							<div class="col-md-6 order_detail_left">
								<div class="table-scrollable">
									<table class="table table-striped table-bordered table-hover">
										<tbody>
										<tr>
											<td valign="top" align="right"><label>Restaurant Name &amp; Address</label></td>
											<td width="60%">
												<div class="address-detail">
													<div class="resName"> <?php
														echo $orders_list['Store']['store_name'];?>
													</div> <?php
													if($siteSetting['Sitesetting']['address_mode'] != 'Google') {
														echo $orders_list['Store']['street_address'] . ', ' .
																$location[$orders_list['Store']['store_zip']] . ', ' .
																$cities[$orders_list['Store']['store_city']] . ', ' .
																$states[$orders_list['Store']['store_state']] . '.';
													} else {
														echo $orders_list['Store']['address'];
													}
													?>
												</div>
											</td>
										</tr>
										<tr>
											<td valign="top" align="right"><label>Customer Address </label></td>
											<td width="60%">
												<div class="address-detail"> <?php
													if($siteSetting['Sitesetting']['address_mode'] != 'Google') {
														echo $orders_list['Order']['address'] . ', ' .
																$orders_list['Order']['landmark'] . ', ' .
																$orders_list['Order']['location_name'] . ', ' .
																$orders_list['Order']['city_name'] . ', ' .
																$orders_list['Order']['state_name'] . '.';
													} else {
														echo $orders_list['Order']['google_address'];
													}
													?>
												</div>	
											</td>
										</tr>
										<tr>
											<td valign="top" align="right"><label> <?php
													$orderType =
															($orders_list['Order']['order_type'] == 'Collection') ?
																	'Pickup' : $orders_list['Order']['order_type'];
													echo $orderType.' Date'; ?></label></td>
											<td width="60%">
												<div class="address-detail"> <?php
													echo $orders_list['Order']['delivery_date']; ?></div>	
											</td>
										</tr>
                                        <tr>
                                            <td valign="top" align="right"><label> <?php
													$orderType =
															($orders_list['Order']['order_type'] == 'Collection') ?
																	'Pickup' : $orders_list['Order']['order_type'];
                                                    echo $orderType.' Time'; ?></label></td>
                                            <td width="60%">
                                                <div class="address-detail"> <?php
                                                    echo ($orders_list['Order']['assoonas'] == 'Later') ? $orders_list['Order']['delivery_time'] : 'ASAP'; ?></div>
                                            </td>
                                        </tr>
										<tr>
											<td valign="top" align="right"><label>Customer Name </label></td>
											<td width="60%">
												<div class="address-detail">
												<?php echo $orders_list['Order']['customer_name'];?></div>	
											</td>
										</tr>
										<tr>
											<td valign="top" align="right"><label>E-mail Address</label></td>
											<td width="60%">
												<div class="address-detail">
												<?php echo $orders_list['Order']['customer_email'];?></div>	
											</td>
										</tr>								
									</tbody>
									</table>
								</div>
							</div>
							<div class="col-md-5 pull-right order_detail_right">
								<div class="table-scrollable">
									<table class="table table-striped table-bordered table-hover">
										<tbody>
											<tr>
												<td valign="top" align="right"><label>Phone no</label></td>
												<td>
													<div class="address-detail">
													<?php echo $orders_list['Order']['customer_phone'];?></div></div>	
												</td>
											</tr>
											<tr>
												<td valign="top" align="right"><label>Payment type</label></td>
												<td>
													<div class="address-detail"> <?php
														echo $orders_list['Order']['payment_type'];?></div>
												</td>						     				
											</tr>
											<tr>
												<td valign="top" align="right"><label>Payment Status</label></td>
												<td>
													<div class="address-detail">
														<?php
														if($orders_list['Order']['payment_method'] == "unpaid") {
															echo "Not Paid";
														} else {
															echo $orders_list['Order']['payment_method'];
														}?></div>
												</td>
											</tr>
											<tr>
												<td valign="top" align="right"><label>Order Status</label></td>
												<td>
													<div class="address-detail"> <?php
														echo $orders_list['Order']['status'];?></div>
												</td>
											</tr>

											<tr>
												<td valign="top" align="right"><label> Order Type</label></td>
												<td>
													<div class="address-detail"><?php
	                                                    $orderType =
	                                                        ($orders_list['Order']['order_type'] == 'Collection') ?
	                                                            'Pickup' : $orders_list['Order']['order_type'];
														echo $orderType;?></div>
												</td>
											</tr> <?php 
											if (!empty($orders_list['StripeRefund']['refund_amount'])) { ?>
												<tr>
													<td valign="top" align="right"><label> Refund Id / Refund Amount</label></td>
													<td>
														<div class="address-detail">
															<?php 
															echo $orders_list['StripeRefund']['refund_id']. ' / ';
															echo html_entity_decode($this->Number->currency($orders_list['StripeRefund']['refund_amount'], $siteCurrency)); ?></div>
													</td>
												</tr> <?php
											}
											if ($orders_list['Order']['order_description']) {  ?>
												<tr>
													<td valign="top" align="right"><label>Instructions</label></td>
													<td>
														<div class="address-detail">
															<?php echo $orders_list['Order']['order_description'];?></div>
													</td>
												</tr> <?php
											}

	                                        if ($orders_list['Order']['failed_reason']) {  ?>
												<tr>
													<td valign="top" align="right"><label>Reason</label></td>
													<td>
														<div class="address-detail">
															<?php echo $orders_list['Order']['failed_reason'];?></div>
													</td>
												</tr> <?php 
											}
											if ($orders_list['Driver']['driver_name']) {  ?>
												<tr>
													<td valign="top" align="right"><label>Nom coursier / Phone</label></td>
													<td>
														<div class="address-detail"> <?php 
															echo $orders_list['Driver']['driver_name'].' / '.
																$orders_list['Driver']['driver_phone'].'('.$orders_list['Order']['distance'].')'; ?></div>
													</td>
												</tr> <?php
											} ?>
										</tbody>
									</table>
								</div>								
							</div>
							<div class="clear_both"></div>
							<div class="order_detail_bottom col-sm-12">
								<div class="table-scrollable">
									<table border="1" class="table table-hover table-striped table-bordered no-margin">
										<thead>
										<tr>
											<th>S.No</th>
											<th>Menu Name</th>
											<th>Quantity</th>
											<th>Price</th>
											<th>Total Price </th>
										</tr>
							
										</thead>
										<tbody><?php 
											if(!empty($orders_list)){
												$count = 1;
												foreach($orders_list['ShoppingCart'] as $key => $value){?>
													<tr>
													<td><?php echo $count;?></td>
													<td>
														<span class="col-md-12 no-padding"> <?php
															echo $value['product_name'].'<br>'.$value['subaddons_name']; ?>
															<br><?php
															if (!empty($value['product_description'])) { ?>
																<span class="table-addon colorgray"><?php echo $value['product_description']; ?></span> <?php
															} ?>

														</span>
													</td>
													<td class="price"><?php
														echo $value['product_quantity']; ?>
													</td>
													<td class="price"><?php
														echo html_entity_decode($this->Number->currency($value['product_price'], $siteCurrency)); ?>
													</td>
													<td class="price"><?php
														echo html_entity_decode($this->Number->currency($value['product_total_price'], $siteCurrency)); ?>
													</td>

													</tr><?php $count++;
												} ?>
												<tr>
													<td align="right" colspan="4">Subtotal</td>
													<td class="price"><?php
														echo html_entity_decode($this->Number->currency($orders_list['Order']['order_sub_total'], $siteCurrency)); ?>
													</td>
												</tr><?php 

												if ($orders_list['Order']['offer_amount'] != 0) {?>
													<tr class="grandprice">
														<td class="text-right" colspan="4">Offer <?php 
															echo '('.$orders_list['Order']['offer_percentage'].' %)'; ?> </td>
														<td class="price"><?php
															echo html_entity_decode($this->Number->currency($orders_list['Order']['offer_amount'], $siteCurrency)); ?>
														</td>
													</tr> <?php
												}
												if ($orders_list['Order']['voucher_amount'] != 0) {?>
													<tr class="grandprice">
														<td class="text-right" colspan="4">Voucher Discount <?php 
															echo ($orders_list['Order']['voucher_percentage'] > 0) ? ' ('.$orders_list['Order']['voucher_percentage'].' %)' : ''; ?> </td>
														<td class="price"><?php
															echo html_entity_decode($this->Number->currency($orders_list['Order']['voucher_amount'], $siteCurrency)); ?>
														</td>
													</tr> <?php
												}
												if ($orders_list['Order']['tax_amount'] != 0) {?>
													<tr>
														<td align="right" colspan="4">Tax <?php 
															echo '('.$orders_list['Order']['tax_percentage'].' %)'; ?> </td>
														<td class="price"><?php
															echo html_entity_decode($this->Number->currency($orders_list['Order']['tax_amount'], $siteCurrency)); ?>
														</td>	
													</tr><?php 
												}
												if ($orders_list['Order']['delivery_charge'] != 0 || $orders_list['Order']['voucher_code'] != '' && $orders_list['Order']['voucher_amount'] == 0) { ?>
													<tr>
														<td align="right" colspan="4">Delivery Charge</td>
														<td class="price"><?php
															echo ($orders_list['Order']['delivery_charge'] != 0) ?
																html_entity_decode($this->Number->currency($orders_list['Order']['delivery_charge'], $siteCurrency)) :
																__('Free Delivery'); ?>
														</td>
													</tr> <?php 
												}
												if ($orders_list['Order']['tip_amount'] != 0) {?>
													<tr>
														<td align="right" colspan="4"> Tips </td>
														<td class="price"><?php
															echo html_entity_decode($this->Number->currency($orders_list['Order']['tip_amount'], $siteCurrency)); ?>
														</td>
													</tr> <?php
												} ?>
												<tr>
													<td align="right" colspan="4">Total</td>
													<td class="price"><?php
														echo html_entity_decode($this->Number->currency($orders_list['Order']['order_grand_total'], $siteCurrency)); ?>
													</td>
												</tr><?php 
											} else {
												echo "No Record Found";
											}?>
										</tbody>
									</table>
								</div> <?php
								if ($orders_list['Order']['status'] == 'Delivered') { ?>
									<center><img src="<?php echo $siteUrl.'/OrderProof/Order_signature'.$orders_list['Order']['id'].'.png';?>" onerror="this.onerror=null;this.src='<?php echo $siteUrl."/images/No-Signature.jpg"; ?>'"></center>  <?php
									
								} ?>
							</div>
						</div> 
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
