
<div class="contain">
	<div class="contain">
		<h3 class="page-title">Manage AddressBook</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Home</a>
					<i class="fa fa-angle-right"></i>
				</li>
			
				<li>
					<a href="#">Manage AddressBook</a>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box grey-cascade">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-globe"></i>Managed AddressBook
						</div>
						<div class="tools">
							
						</div>
					</div>
					<div class="portlet-body"> 
						<table class="table table-striped table-bordered table-hover" id="sample_12">
							<thead>
								<tr>
									<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes"/></th>
									<th>Address Title</th>
									<th>Street Address</th>
									<th>Zip Code</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody><?php 
                            if (!empty($addressbook_list)) {
                            foreach($addressbook_list as $key => $value) { //echo "<pre>"; print_r($value);echo "</pre>";?>
								<tr id="record<?php echo  $value['CustomerAddressBook']['id'];?>">
                                    <td><input type="checkbox" class="checkboxes"
									 value="<?php echo $value['CustomerAddressBook']['id'];?>"/></td>
									<td><?php echo $value['CustomerAddressBook']['address_title']; ?></td>
									<td><?php echo $value['CustomerAddressBook']['address']; ?></td>
									<td><?php echo $value['Location']['zip_code']; ?></td>
									<td align="center">
                                        <a class="buttonStatus" href="javascript:void(0);" 
                                        onclick="statusChange(<?php echo $value['CustomerAddressBook']['id'];?>,'customeraddress');">
                                    <i class="fa fa-check"></i></a></td>
									<td align="center">
										<?php
										echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',
																array('controller'=>'Customers',
																	   'action'=>'editaddressbook',
																		$value['CustomerAddressBook']['id']),
																array('class'=>'buttonEdit',
																		'escape'=>false));?>
										<a class="buttonAction" href="javascript:void(0);"
                                        onclick="deleteprocess(<?php echo $value['CustomerAddressBook']['id'];?>,'customeraddress');" ><i class="fa fa-trash-o"></i></a>
		        						
									</td>
								</tr> <?php 
                                } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
