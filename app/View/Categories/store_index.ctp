<div class="page-content-wrapper">
    <div class="page-content">	
        <h3 class="page-title">Gestion plats</h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo $siteUrl.'/store/dashboards/index';?>">Accueil</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="#">Gestion plats</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div id="identifier-store" class="col-md-4">
                <img src="<?php echo !empty($store_logo) ? $store_url : "";?>"><br>
                <p><b><?php echo !empty($store_name) ? $store_name : "";?></b></p>
            </div>
            <div class="col-md-offset-4 col-md-4 statusButtons">
                <label class="radio-inline statusBtn"><input type="radio" value="-1" name="status" <?php echo ($this->params['url']['status'] != "1" || $this->params['url']['status'] != "0") ? "checked" : ""; ?>>All</label> 
                <label class="radio-inline statusBtn"><input type="radio" value="1" name="status" <?php echo ($this->params['url']['status'] == "1") ? "checked" : ""; ?>>Active</label>
                <label class="radio-inline statusBtn"><input type="radio" value="0" name="status" <?php echo ($this->params['url']['status'] == "0") ? "checked" : ""; ?>>Deactive</label>
            </div>
        </div>

        <div class="row articles">
            <div class="col-md-12">
                <h3 class="page-title">Gestion plats</h3>
                <div class="portlet box grey-cascade">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-globe"></i>Gestion plats
                        </div>
                        <div class="tools">

                        </div>
                    </div>
                    <div class="portlet-body"><?php
						echo $this->Form->create('Commons', array('class'=>'form-horizontal',
							'controller'=>'Commons','action'=>'multipleSelect')); ?>
                        <div class="table-toolbar">

                            <div id="send" style="display:none" class="pull-left" >
                                <div class="pull-right" id="addnewbutton_toggle"> <?php
										echo $this->Form->hidden("Model",array('value'=>'Category',
											'name'=>'data[name]'));
										if (!empty($Category_list)) {
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
																array('controller'=>'Categories',
																	   'action'=>'add'),
																array('class'=>'btn green',
																		'escape'=>false)
															  );
										?>
                            </div>


                        </div>
                        <table class="gestionPlatTable table table-striped table-bordered table-hover checktable" id="sample_12">
                            <thead>
                                <tr>
                                    <th class="table-checkbox no-sort"><input type="checkbox" class="group-checkable test1 checktable" data-set="#sample_1 .checkboxes" /></th>
                                    <th class="one">Nom principale catégorie</th>
                                    <th class="two">N° catégorie</th>
                                    <th class="three no-sort">Statut</th>
                                    <th class="three no-sort">Changer d'état</th>
                                    <th class="four no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>	<?php 
									foreach ($Category_list as $key => $value){?>
                                <tr class="odd gradeX" id="record<?php echo $value['Category']['id'];?>">
                                    <td> <?php
										echo $this->Form->checkbox($value['Category']['id'],
											array('class'=>'checkboxes test' ,
												//'name'=>'value['Brand']['id']',
												'label'=>false,
												'hiddenField'=>false,
												'value'=> $value['Category']['id'])); ?> </td>

                                    <td><?php echo $value['Category']['category_name'];?></td>
                                    <td><?php echo $value['Category']['id'];?></td>
                                    <td><i id="status_category<?php echo $value['Category']['id'];?>"><?php echo $value['Category']['status'] == 0 ? "Deactive" : "Active"; ?></i></td>
                                    <td align="center"> <?php 
                                            if($value['Category']['status'] == 1) {?>
                                        <a title="Deactive" class="buttonStatus red_bck" href="javascript:void(0);" 
                                           onclick="statusChange(<?php echo $value['Category']['id'];?>, 'Category');">
                                            <i class="">Deactivate</i><!-- deactive --></a>
                                            <?php } else if ($value['Category']['status'] == 0) {
                                            ?>
                                        <a title="Active" class="buttonStatus" href="javascript:void(0);" 
                                           onclick="statusChange(<?php echo $value['Category']['id'];?>, 'Category');">
                                            <i class="">Active</i></a>
                                            <?php } else {?>
                                        <a title="Pending" class="buttonStatus yellow_bck" href="javascript:void(0);" 
                                           onclick="statusChange(<?php echo $value['Category']['id'];?>, 'Category');">
                                            <i class="">Pending</i><!-- Pending --></a>
                                            <?php }?>
                                    </td>
                                    <td align="center"><?php
										echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',
																array('controller'=>'Categories',
																	   'action'=>'edit',
																		$value['Category']['id']),
																array('class'=>'buttonEdit',
																		'escape'=>false)
															  );?>
                                        <a class="buttonAction" href="javascript:void(0);"
                                           onclick="deleteprocess(<?php echo $value['Category']['id'];?>, 'Category');" ><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr><?php }?>
                            </tbody>
                        </table><?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row articles">
          <?php  for($i=0; $i<sizeof($Category_listingsForAddOnAndProducts); $i++){ ?>
            <div class="col-md-12">
                <h3 class="page-title">Gestion Menu - <?php echo($Category_listingsForAddOnAndProducts[$i]['Category']['category_name']); ?></h3>
                <div class="portlet box grey-cascade">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-globe"></i>Gestion Menu
                        </div>
                        <div class="tools">
                            <a href="javascript:void(0);" class="collapse"></a>
                            <a href="javascript:void(0);" class="reload"></a>
                            <a href="javascript:void(0);" class="remove"></a>
                        </div>
                    </div>
                    <div class="portlet-body">
						<?php echo $this->Form->create('Commons', array('class'=>'form-horizontal',
							'controller'=>'Commons','action'=>'multipleSelect')); ?>
                        <div class="table-toolbar">

                            <div id="send" style="display:none" class="pull-left" >
                                <div class="pull-right" id="addnewbutton_toggle"> <?php
										echo $this->Form->hidden("Model",array('value'=>'Product',
											'name'=>'data[name]'));
										if (!empty($products_detail)) {
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

                            <div class="btn-group pull-right"><?php
										echo $this->Html->link('Ajouter Nouveau <i class="fa fa-plus"></i>',
														array('controller'=>'Products',
															   'action'=>'add?cid='.$Category_listingsForAddOnAndProducts[$i]['Category']['id'],
															   'store'=>true),
														array('class'=>'btn green',
																'escape'=>false)
													  );?>
                            </div>

                        </div>
                        <table class="gestionPlatTable table table-striped table-bordered table-hover checktable" id="sample_14">
                            <thead>
                                <tr>
                                    <th class="table-checkbox no-sort"><input type="checkbox" class="group-checkable test1 checktable" data-set="#sample_1 .checkboxes" /></th>
                                    <th class="one">Nom du plat</th>
                                    <th>La description</th>
                                    <th class="two">Principale Catégorie</th>
                                    <th class="three no-sort">Status</th>
                                    <th class="three no-sort">Changer d'état</th>
                                    <th class="four no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for($j=0; $j<sizeof($products_detail[$i]); $j++){ ?>
                                <tr class="odd gradeX" id="record<?php echo $products_detail[$i][$j]['Product']['id'];?>">
                                    <td> <?php
								echo $this->Form->checkbox($products_detail[$i][$j]['Product']['id'],
									array('class'=>'checkboxes test' ,
										'label'=>false,
										'hiddenField'=>false,
										'value'=> $products_detail[$i][$j]['Product']['id'])); ?> </td>
                                    <td><?php echo $products_detail[$i][$j]['Product']['product_name'];?></td>
                                    <td><?php echo $products_detail[$i][$j]['Product']['product_description'];?></td>
                                    <td><?php echo $products_detail[$i][$j]['MainCategory']['category_name'];?></td>
                                    <td><i id="status_ad<?php echo $products_detail[$i][$j]['Product']['id'];?>"><?php echo $products_detail[$i][$j]['Product']['status'] == 0 ? "Deactivate" : "Active"; ?></i></td>
                                    <td align="center"><?php 
                                    if($products_detail[$i][$j]['Product']['status'] == 1) {?>
                                        <a id="" title="Deactive" class="buttonStatus actdct red_bck deactive" href="javascript:void(0);" 
                                           onclick="statusChange(<?php echo $products_detail[$i][$j]['Product']['id'];?>, 'Product');">
                                            <i class="">Deactivate</i><!-- deactive --></a>
                                    <?php } else if($products_detail[$i][$j]['Product']['status'] == 0){
                                    ?>
                                        <a id="" title="Active" class="buttonStatus actdct active" href="javascript:void(0);" 
                                           onclick="statusChange(<?php echo $products_detail[$i][$j]['Product']['id'];?>, 'Product');">
                                            <i class="">Active</i></a>
                                    <?php } else {?>
                                        <a id="" title="Pending" class="buttonStatus actdct yellow_bck" href="javascript:void(0);" 
                                           onclick="statusChange(<?php echo $products_detail[$i][$j]['Product']['id'];?>, 'Product');">
                                            <i class="">Pending</i><!-- Pending --></a>
                                    <?php }?>
                                    </td>
                                    <td align="center">	<?php
								echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',
														array('controller'=>'Products',
															   'action'=>'edit',
																$products_detail[$i][$j]['Product']['id']),
														array('class'=>'buttonEdit',
																'escape'=>false));?>
                                        <a class="buttonAction" href="javascript:void(0);"
                                           onclick="deleteprocess(<?php echo $products_detail[$i][$j]['Product']['id'];?>, 'Product');" ><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr><?php }?>
                            </tbody>
                        </table><?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>
          <?php } ?>
        </div>
    </div>
</div>

