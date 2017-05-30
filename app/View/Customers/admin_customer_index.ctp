
<div class="contain">
	<div class="contain">
		<h3 class="page-title">Gestion Carnet d’adresse</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Accueil</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					
					<a href="<?php echo $siteUrl.'/admin/customers/index';?>">Client</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Gestion Carnet d’adresse</a>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box grey-cascade">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-globe"></i>Gestion Carnet d’adresse
						</div>
						<div class="tools">
							
						</div>
					</div>
					<div class="portlet-body"> <?php
						echo $this->Form->create('Commons', array('class'=>'form-horizontal',
							'controller'=>'Commons','action'=>'multipleSelect')); ?>
						<div class="table-toolbar">
							<div class="row">
								<div id="send" style="display:none"  class="pull-left">
									<div class="pull-right" id="addnewbutton_toggle"> <?php
										echo $this->Form->hidden("Model",array('value'=>'CustomerAddressBook',
											'name'=>'data[name]'));
										if (!empty($addressbook_list)) {
											echo $this->Form->submit(__('Active'),
												array('class'=>'btn btn-success btn-sm',
													'name'=> 'actions',
													'div'=>false,
													'onclick'=>'return recorddelete(this);'
												)); ?> <?php
											echo $this->Form->submit(__('Deactive'),
												array('class'=>'btn btn-warning btn-sm',
													'name'=> 'actions',
													'div'=>false,
													'onclick'=>'return recorddelete(this);'
												)); ?> <?php
											echo $this->Form->submit(__('Delete'),
												array('Class'=>'btn btn-danger btn-sm',
													'name'=> 'actions',
													'div'=>false,
													'onclick'=>'return recorddelete(this);'
												));
										} ?>
									</div>
								</div>

							</div>
						</div>
						<table class="table table-striped table-bordered table-hover checktable" id="sample_12">
							<thead>
								<tr>
									<th class="table-checkbox"><input type="checkbox" class="group-checkable test1" data-set="#sample_1 .checkboxes" /></th>
									<th>N° Rue</th> <?php
									if ($siteSetting['Sitesetting']['address_mode'] != 'Google') { ?>
										<th>Street Address</th>
										<th>Zip Code</th> <?php
									} else { ?>
										<th>Adresse</th> <?php
									}
									?>
									<th>Statut</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody><?php 
                            
                            foreach($addressbook_list as $key => $value) { ?>
								<tr id="record<?php echo  $value['CustomerAddressBook']['id'];?>">
									<td> <?php
										echo $this->Form->checkbox($value['CustomerAddressBook']['id'],
											array('class'=>'checkboxes test' ,
												'label'=>false,
												'hiddenField'=>false,
												'value'=> $value['CustomerAddressBook']['id'])); ?> </td>
									<td><?php echo $value['CustomerAddressBook']['address_title']; ?></td> <?php
									if ($siteSetting['Sitesetting']['address_mode'] != 'Google') { ?>
										<td><?php echo $value['CustomerAddressBook']['address']; ?></td>
										<td><?php echo $value['Location']['zip_code']; ?></td> <?php
									} else { ?>
										<td><?php echo $value['CustomerAddressBook']['google_address']; ?></td> <?php
									} ?>
									<td align="center"> <?php 
                                            if($value['CustomerAddressBook']['status'] == 0) {?>
                                                <a title="Deactive" class="buttonStatus red_bck" href="javascript:void(0);" 
                                                onclick="statusChange(<?php echo $value['CustomerAddressBook']['id'];?>,'customeraddress');">
                                            <i class="fa fa-times"></i><!-- deactive --></a>
                                            <?php } else if($value['CustomerAddressBook']['status'] == 1){
                                            ?>
                                                <a title="active" class="buttonStatus" href="javascript:void(0);" 
                                                onclick="statusChange(<?php echo $value['CustomerAddressBook']['id'];?>,'customeraddress');">
                                            <i class="fa fa-check"></i></a>
                                            <?php } else {?>
                                                <a title="Pending" class="buttonStatus yellow_bck" href="javascript:void(0);" 
                                                onclick="statusChange(<?php echo $value['CustomerAddressBook']['id'];?>,'customeraddress');">
                                            <i class="fa fa-exclamation"></i><!-- Pending --></a>
                                           <?php  }?>
                                    </td>
									<td align="center">
										<?php
										echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',
																array('controller'=>'Customers',
																	   'action'=>'editAddressBook',
																		$value['CustomerAddressBook']['id']),
																array('class'=>'buttonEdit',
																		'escape'=>false));?>
										<a class="buttonAction" href="javascript:void(0);"
                                        onclick="deleteprocess(<?php echo $value['CustomerAddressBook']['id'];?>,'customeraddress');" ><i class="fa fa-trash-o"></i></a>
		        						
									</td>
								</tr> <?php 
                                }?>
							</tbody>
						</table><?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
