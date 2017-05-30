<div class="contain">
    <div class="contain">
        <h3 class="page-title">Gestion Restaurant</h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Accueil</a>
                    <i class="fa fa-angle-right"></i>
                </li>

                <li>
                    <a href="javascript:void(0);">Gestion Restaurant</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box grey-cascade">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-globe"></i>Gestion Restaurant
                        </div>
                        <div class="tools">

                        </div>
                    </div>
                    <div class="portlet-body"> <?php
						echo $this->Form->create('Commons', array('class'=>'form-horizontal',
							'controller'=>'Commons','action'=>'multipleSelect')); ?>
                        <div class="table-toolbar">

                            <div id="send" style="display:none" class="pull-left">
                                <div class="pull-right" id="addnewbutton_toggle"> <?php
										echo $this->Form->hidden("Model",array('value'=>'Store',
											'name'=>'data[name]'));
										if (!empty($stores)) {
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
											
										} ?>
                                </div>
                            </div>

                            <div class="btn-group pull-right">  <?php
										echo $this->Html->link('Ajouter Nouveau <i class="fa fa-plus"></i>',
																array('controller'=>'stores',
																		'action'=>'add'),
																array('class'=>'btn green',
																		'escape'=>false)); ?>
                            </div>

                        </div>
                        <table class="table table-striped table-bordered table-hover checktable" id="sample_12">
                            <thead>
                                <tr>
                                    <th class="table-checkbox no-sort"><input type="checkbox" class="group-checkable test1" data-set="#sample_1 .checkboxes" /></th>
                                    <th>Nom du Restaurant</th>
                                    <th>Is Featured?</th>   									<th>Numéro de téléphone</th>
 <?php
									if($siteSetting['Sitesetting']['address_mode'] != 'Google') { ?>
                                    <th>City</th> <?php

										if($siteSetting['Sitesetting']['search_by'] == 'zip') {
											echo '<th>Code postal</th>';
										} else {
											echo '<th>Nom de la région</th>';
										}
									} else { ?>
                                    <th>Adresse</th><?php
									} ?>
                                    <th>Identifiant</th>
                                    <th>Date d’ajout</th>
                                    <th class="no-sort">Options</th>
                                    <th class="no-sort">Statut</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody> <?php
                            
								foreach ($stores as $key => $value) {
									?> 
                                <tr class="odd gradeX">
                                    <td> <?php
											echo $this->Form->checkbox($value['Store']['id'],
												array('class'=>'checkboxes test' ,
													'label'=>false,
													'hiddenField'=>false,
													'value'=> $value['Store']['id'])); ?> </td>
                                    <td> <?php echo $value['Store']['store_name']; ?> </td>
                                    <td><?php echo $value['Store']['isFeatured'] == "0" ? "No" : "Yes"; ?> </td>
                                    <td> <?php echo $value['Store']['contact_phone']; ?> </td><?php
										if($siteSetting['Sitesetting']['address_mode'] != 'Google') { ?>
                                    <td> <?php echo $storesCity[$value['Store']['store_city']]; ?> 	 </td>
                                    <td> <?php echo $storeArea[$value['Store']['store_zip']]; ?> 	 </td><?php
										} else { ?>
                                    <td> <?php echo $value['Store']['address']; ?> 	 </td><?php
										} ?>
                                    <td> <?php echo $value['Store']['contact_email']; ?> </td>
                                    <td> <?php echo date("Y/m/d H:i:s", strtotime($value['Store']['created']));?>		 </td>


                                    <td> <?php

											echo $this->Html->link('Menu',
														array('controller'=>'products',
																'action'=>'index',
																$value['Store']['id']),
														array('escape'=>false));
											echo ' | ';
											echo $this->Html->link('Offre',
														array('controller'=>'Storeoffers',
																'action'=>'index',
																$value['Store']['id']),
														array('escape'=>false)); ?>


                                    <td align="center"> <?php 
                                            if($value['Store']['status'] == 0) {?>
                                        <a title="Deactive" class="buttonStatus red_bck" href="javascript:void(0);" 
                                           onclick="statusChange(<?php echo $value['Store']['id'];?>, 'Store');">
                                            <i class="fa fa-times"></i><!-- deactive --></a>
                                            <?php } else if($value['Store']['status'] == 1){
                                            ?>
                                        <a title="active" class="buttonStatus" href="javascript:void(0);" 
                                           onclick="statusChange(<?php echo $value['Store']['id'];?>, 'Store');">
                                            <i class="fa fa-check"></i></a>
                                            <?php } else {?>
                                        <a title="Pending" class="buttonStatus yellow_bck" href="javascript:void(0);" 
                                           onclick="statusChange(<?php echo $value['Store']['id'];?>, 'Store');">
                                            <i class="fa fa-exclamation"></i><!-- Pending --></a>
                                            <?php }?>
                                    </td>
                                    <td align="center"> <?php
											echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',
															array('controller'=>'stores',
																   'action'=>'edit',
																	$value['Store']['id']),
															array('class'=>'buttonEdit',
																		'escape'=>false)); ?>
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