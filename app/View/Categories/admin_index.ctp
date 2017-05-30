<div class="contain">
    <div class="contain">	
        <h3 class="page-title">Catégorie</h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Accueil</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="#">Gestion Catégorie</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="portlet box grey-cascade">
                    <div class="portlet-title">
                        <div class="caption"><i class="fa fa-globe"></i>Gestion Catégorie</div>
                    </div>
                    <div class="portlet-body"><?php
		        echo $this->Form->create('Commons', array('class'=>'form-horizontal',
		        										'controller'=>'Commons','action'=>'multipleSelect')); ?>
                        <div class="table-toolbar">

                            <div id="send" style="display:none" class="pull-left">
                                <div class="pull-right" id="addnewbutton_toggle"> <?php
			                           echo $this->Form->hidden("Model",array('value'=>'Category', 'name'=>'data[name]'));
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
//										echo $this->Html->link('Ajouter Nouveau <i class="fa fa-plus"></i>',
//																array('controller'=>'Categories',
//																	   'action'=>'add'),
//																array('class'=>'btn green',
//																		'escape'=>false)
//															  );
										?>
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
                        <table class="table table-striped table-bordered table-hover checktable" id="sample_12">
                            <thead>
                                <tr>
                                    <th class="table-checkbox no-sort"><input type="checkbox" class="group-checkable test1" data-set="#sample_1 .checkboxes"/></th>
                                    <th>Nom catégorie principale</th>
                                    <th>N° catégorie</th>
                                    <th>Restaurant Identifier</th>
                                    <th class="no-sort">Statut</th>
                                    <th class="no-sort">Changer d'état</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>	<?php 
								foreach ($Category_list as $key => $value){?>
                                <tr class="odd gradeX" id="record<?php echo $value['Category']['id'];?>"> 
                                    <td><?php
                                    echo $this->Form->checkbox($value['Category']['id'],
                                            array('class'=>'checkboxes test',
                                                    //'name'=>'value['Brand']['id']',
                                                    'label'=>false,
                                                    'hiddenField'=>false,
                                                    'value'=> $value['Category']['id'])); ?> </td>
                                    <td><?php echo $value['Category']['category_name'];?></td>
                                    <td><?php echo $value['Category']['id'];?></td>
                                    <td><?php echo $value['Store']['store_name'] . " (" . $value['Store']['id'] . ")";  ?></td>
                                    <td><i id="status_category<?php echo $value['Category']['id'];?>"><?php echo $value['Category']['status'] == 0 ? "Deactive" : "Active"; ?></i></td>
                                    <td align="center"> <?php 
                                            if($value['Category']['status'] == 1) {?>
                                        <a title="Deactive" class="buttonStatus red_bck" href="javascript:void(0);" 
                                           onclick="statusChange(<?php echo $value['Category']['id'];?>, 'Category');">
                                            <i class="">Deactivate</i><!-- deactive --></a>
                                            <?php } else if ($value['Category']['status'] == 0) {
                                            ?>
                                        <a title="active" class="buttonStatus" href="javascript:void(0);" 
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
