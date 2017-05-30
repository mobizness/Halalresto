<div class="page-content-wrapper">
    <div class="page-content">
        <h3 class="page-title">Mes promo & réductions</h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo $siteUrl.'/store/dashboards/index'; ?>">Accueil</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="<?php echo $siteUrl.'/store/stores/index'; ?>">Mes promo & réductions</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="#">Gestion Deal</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h3 class="page-title">Gestion Deal</h3>
                <div class="portlet box grey-cascade">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-globe"></i>Gestion Deal
                        </div>
                        <div class="tools">

                        </div>
                    </div>
                    <div class="portlet-body"><?php
						echo $this->Form->create('Commons', array('class'=>'form-horizontal',
							'controller'=>'Commons','action'=>'multipleSelect')); ?>
                        <div class="table-toolbar">

                            <div id="send" style="display:none" class="pull-left">
                                <div class="pull-right" id="addnewbutton_toggle"> <?php
										echo $this->Form->hidden("Model",array('value'=>'Deal',
											'name'=>'data[name]'));
										if (!empty($deals)) {
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

                            <div class="btn-group pull-right"> <?php 
										echo $this->Html->link('Ajouter Nouveau <i class="fa fa-plus"></i>',
																array('controller'=>'deals',
																	   'action'=>'add'),
																array('class'=>'btn green',
																		'escape'=>false)); ?>
                                </a>
                            </div>

                        </div>
                        <table class="table table-striped table-bordered table-hover checktable sample_12" id="">
                            <thead>
                                <tr>
                                    <th class="table-checkbox no-sort"><input type="checkbox" class="group-checkable test1 checktable" data-set="#sample_1 .checkboxes" /></th>
                                    <th>Nom des Deals</th>
                                    <th>Nom Produit</th>
                                    <th>Date d’ajout</th>
                                    <th class="no-sort">Statut</th>
                                    <th class="no-sort">Changer d'état</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody> <?php
							
								foreach ($deals as $key => $value) { ?>
                                <tr class="odd gradeX" id="record<?php echo $value['Deal']['id'];?>">
                                    <td> <?php
											echo $this->Form->checkbox($value['Deal']['id'],
												array('class'=>'checkboxes test' ,
													//'name'=>'value['Brand']['id']',
													'label'=>false,
													'hiddenField'=>false,
													'value'=> $value['Deal']['id'])); ?> </td>
                                    <td><?php echo $value['Deal']['deal_name']; ?></td>
                                    <td><?php echo $value['MainProduct']['product_name']; ?></td>
                                    <td><?php echo date("Y/m/d H:i:s", strtotime($value['Deal']['created']));?></td>
                                    <td><i id="status_ad<?php echo $value['Deal']['id'];?>"><?php if($value['Deal']['status'] == 0) { echo "Deactivate"; } else if($value['Deal']['status'] == 1){ echo "Active"; } else{ echo "Pending"; } ?></i></td>
                                    <td align="center"> <?php 
	                                    if($value['Deal']['status'] == 1) {?>
                                        <a title="Deactive" class="buttonStatus red_bck"  href="javascript:void(0);" 
                                           onclick="statusChange(<?php echo $value['Deal']['id'];?>, 'Deal');">
                                            <i class="">Deactivate</i><!-- deactive--> </a>
		                                    <?php } else if($value['Deal']['status'] == 0) {
		                                    ?>
                                        <a title="Active" class="buttonStatus" href="javascript:void(0);" 
                                           onclick="statusChange(<?php echo $value['Deal']['id'];?>, 'Deal');">
                                            <i class="">Active</i></a>
		                                    <?php } else {?>
                                        <a title="Pending" class="buttonStatus yellow_bck" href="javascript:void(0);" 
                                           onclick="statusChange(<?php echo $value['Deal']['id'];?>, 'Deal');">
                                            <i class="">Pending</i><!-- Pending --></a>
		                                   <?php }?>
                                    </td>
                                    <td align="center"><?php
											echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',
																	array('controller'=>'deals',
																		   'action'=>'edit',
																			$value['Deal']['id']),
																	array('class'=>'buttonEdit',
																			'escape'=>false)
																  );?>
                                        <a class="buttonAction" href="javascript:void(0);"
                                           onclick="deleteprocess(<?php echo $value['Deal']['id'];?>, 'Deal');" ><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr> <?php
								} ?>
                            </tbody>
                        </table><?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h3 class="page-title">Gestion Offre</h3>
                <div class="portlet box grey-cascade">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-globe"></i>Gestion Offre
                        </div>
                        <div class="tools">

                        </div>
                    </div>
                    <div class="portlet-body"><?php
						echo $this->Form->create('Commons', array('class'=>'form-horizontal',
							'controller'=>'Commons','action'=>'multipleSelect')); ?>
                        <div class="table-toolbar">
                            <div id="send" style="display:none" class="pull-left">
                                <div class="pull-right" id="addnewbutton_toggle"> <?php
										echo $this->Form->hidden("Model",array('value'=>'Storeoffer',
											'name'=>'data[name]'));
										if (!empty($Storeoffer_list)) {
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


                            <div class="btn-group pull-right">
											<?php 
										echo $this->Html->link('Ajouter Nouveau <i class="fa fa-plus"></i>',
																array('controller'=>'Storeoffers',
																	   'action'=>'add'),
																array('class'=>'btn green',
																		'escape'=>false)
															  );
										?>
                            </div>
                        </div>

                        <table class="table table-striped table-bordered table-hover checktable sample_12" id="">
                            <thead>
                                <tr>
                                    <th class="table-checkbox no-sort"><input type="checkbox" class="group-checkable test1 checktable" data-set="#sample_1 .checkboxes" /></th>
                                    <th>Réduction %</th>
                                    <th>Prix de l’offre</th>
                                    <th>Du</th>
                                    <th>Au</th>
                                    <th>Date d’ajout</th>
                                    <th class="no-sort">Statut</th>
                                    <th class="no-sort">Changer d'état</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody><?php 
									foreach ($Storeoffer_list as $key =>$value){//echo "<pre>"; print_r($value);echo "</pre>";?>
                                <tr class="odd gradeX" id="record<?php echo $value['Storeoffer']['id'];?>">
                                    <td> <?php
											echo $this->Form->checkbox($value['Storeoffer']['id'],
												array('class'=>'checkboxes test' ,
													//'name'=>'value['Brand']['id']',
													'label'=>false,
													'hiddenField'=>false,
													'value'=> $value['Storeoffer']['id'])); ?> </td>
                                    <td><?php echo $value['Storeoffer']['offer_percentage'];?></td>
                                    <td><?php echo $value['Storeoffer']['offer_price'];?></td>
                                    <td><?php echo date("Y/m/d", strtotime($value['Storeoffer']['from_date']));?></td>
                                    <td><?php echo date("Y/m/d", strtotime($value['Storeoffer']['to_date']));?></td>
                                    <td><?php echo date("Y/m/d H:i:s", strtotime($value['Storeoffer']['updated']));?></td>
                                    <td><i id="status_ad<?php echo $value['Storeoffer']['id'];?>"><?php if($value['Storeoffer']['status'] == 0) { echo "Deactivate"; } else if($value['Storeoffer']['status'] == 1){ echo "Active"; } else{ echo "Pending"; } ?></i></td>
                                    <td align="center"> <?php 
                                            if($value['Storeoffer']['status'] == 1) {?>
                                        <a title="Deactive" class="buttonStatus red_bck" href="javascript:void(0);" 
                                           onclick="statusChange(<?php echo $value['Storeoffer']['id'];?>, 'Storeoffer');">
                                            <i class="">Deactivate</i><!-- deactive --></a>
                                            <?php } else if($value['Storeoffer']['status'] == 0){
                                            ?>
                                        <a title="Active" class="buttonStatus" href="javascript:void(0);" 
                                           onclick="statusChange(<?php echo $value['Storeoffer']['id'];?>, 'Storeoffer');">
                                            <i class="">Active</i></a>
                                            <?php } else {?>
                                        <a title="Pending" class="buttonStatus yellow_bck" href="javascript:void(0);" 
                                           onclick="statusChange(<?php echo $value['Storeoffer']['id'];?>, 'Storeoffer');">
                                            <i class="">Pending</i><!-- Pending --></a>
                                           <?php }?>
                                    </td>
                                    <td align="center"><?php
										echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',
																array('controller'=>'Storeoffers',
																	   'action'=>'edit',
																		$value['Storeoffer']['id']),
																array('class'=>'buttonEdit',
																		'escape'=>false)
															  );?>
                                        <a class="buttonAction" href="javascript:void(0);"
                                           onclick="deleteprocess(<?php echo $value['Storeoffer']['id'];?>, 'Storeoffer');" ><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr><?php }?>
                            </tbody>
                        </table><?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h3 class="page-title">Gestion Promo</h3>
                <div class="portlet box grey-cascade">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-globe"></i>Gestion Promo
                        </div>
                        <div class="tools">

                        </div>
                    </div>
                    <div class="portlet-body"><?php
						echo $this->Form->create('Commons', array('class'=>'form-horizontal',
							'controller'=>'Commons','action'=>'multipleSelect')); ?>
                        <div class="table-toolbar">

                            <div id="send" style="display:none" class="pull-left">
                                <div class="pull-right" id="addnewbutton_toggle"> <?php
										echo $this->Form->hidden("Model",array('value'=>'Voucher',
											'name'=>'data[name]'));
										if (!empty($Voucher_list)) {
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

                            <div class="btn-group pull-right">
											<?php 
										echo $this->Html->link('Ajouter Nouveau <i class="fa fa-plus"></i>',
																array('controller'=>'Vouchers',
																	   'action'=>'add'),
																array('class'=>'btn green',
																		'escape'=>false)
															  );
										?>
                            </div>

                        </div>
                        <table class="table table-striped table-bordered table-hover checktable" id="sample_12">
                            <thead>
                                <tr>
                                    <th class="table-checkbox"><input type="checkbox" class="group-checkable test1" data-set="#sample_1 .checkboxes" /></th>
                                    <th>Code promo</th>
                                    <th>Type d’utilisation</th>
                                    <th>Type de Code promo</th>
                                    <th>Offre</th>
                                    <th>Valide du</th>
                                    <th>Valide au</th>
                                    <th>Statut</th>
                                    <th>Changer d'état</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody><?php 			
                           
                                foreach($Voucher_list as $key => $value){
                                	if($value['Voucher']['offer_mode'] == 'free_delivery') {
										$offer_mode = 'Livraison gratuite';
									} elseif ($value['Voucher']['offer_mode'] == 'price') {
										$offer_mode = 'Prix';
									} elseif ($value['Voucher']['offer_mode'] == 'percentage') {
										$offer_mode = 'Pourcentage';
									} else {
										$offer_mode = 'free delivery';
									}?>
                                <tr class="odd gradeX" id="record<?php echo $value['Voucher']['id'];?>">
                                    <td> <?php
										echo $this->Form->checkbox($value['Voucher']['id'],
											array('class'=>'checkboxes test' ,
												'label'=>false,
												'hiddenField'=>false,
												'value'=> $value['Voucher']['id'])); ?> </td>
                                    <td><?php echo $value['Voucher']['voucher_code'];?></td>
                                    <td><?php echo ($value['Voucher']['type_offer'] == 'single') ? __('Single') : __('Multiple');?></td>
                                    <td><?php echo $offer_mode; ?></td>
                                    <td><?php
										if ($value['Voucher']['offer_mode'] != 'free_delivery') {
											echo ($value['Voucher']['offer_mode'] == 'price') ? 
												$this->Number->currency($value['Voucher']['offer_value'], $siteCurrency) : $value['Voucher']['offer_value']. ' %';
										} else {
											echo $value['Voucher']['free_delivery'];

										} ?></td>
                                    <td><?php echo date("Y/m/d", strtotime($value['Voucher']['from_date']));?></td>
                                    <td><?php echo date("Y/m/d", strtotime($value['Voucher']['to_date']));?></td>
                                    <td><i id="status_ad<?php echo $value['Voucher']['id'];?>"><?php if($value['Voucher']['status'] == 0) { echo "Deactivate"; } else if($value['Voucher']['status'] == 1){ echo "Active"; } else{ echo "Pending"; } ?></i></td>
                                    <td align="center"> <?php 
                                            if($value['Voucher']['status'] == 1) {?>
                                        <a title="Deactive" class="buttonStatus red_bck" href="javascript:void(0);" 
                                           onclick="statusChange(<?php echo $value['Voucher']['id'];?>, 'Voucher');">
                                            <i class="">Deactivate</i><!-- deactive --></a>
                                            <?php } else if($value['Voucher']['status'] == 0) {
                                            ?>
                                        <a title="active" class="buttonStatus" href="javascript:void(0);" 
                                           onclick="statusChange(<?php echo $value['Voucher']['id'];?>, 'Voucher');">
                                            <i class="">Active</i></a>
                                            <?php } else {?>
                                        <a title="Pending" class="buttonStatus yellow_bck" href="javascript:void(0);" 
                                           onclick="statusChange(<?php echo $value['Voucher']['id'];?>, 'Voucher');">
                                            <i class="">Pending</i><!-- Pending --></a>
                                            <?php }?>
                                    </td>
                                    <td align="center"><?php
										echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',
																array('controller'=>'Vouchers',
																	   'action'=>'edit',
																		$value['Voucher']['id']),
																array('class'=>'buttonEdit',
																		'escape'=>false)
															  );?>
                                        <a class="buttonAction" href="javascript:void(0);"
                                           onclick="deleteprocess(<?php echo $value['Voucher']['id'];?>, 'Voucher');" ><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr><?php 
								}?>
                            </tbody>
                        </table><?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>