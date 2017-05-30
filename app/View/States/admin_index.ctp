<div class="contain">
	<div class="contain">
		<h3 class="page-title">Departement</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Accueil</a>
					<i class="fa fa-angle-right"></i>
				</li>
				
				<li>
					<a href="#">Gestion Departement</a>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box grey-cascade">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-globe"></i>Gestion Departement
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
										echo $this->Form->hidden("Model",array('value'=>'State',
											'name'=>'data[name]'));
										if (!empty($state_list)) {
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
																array('controller'=>'States',
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
									<th class="table-checkbox no-sort"><input type="checkbox" class="group-checkable test1" data-set="#sample_1 .checkboxes" /></th>
									<th>Nom Departement</th>
									<th>Date d'ajout</th>
									<th class="no-sort">Statut</th>
									<th class="no-sort">Action</th>
								</tr>
							</thead>
							<tbody><?php 
														
								foreach ($state_list as $key => $value) {?>
								<tr class="odd gradeX" id="record<?php echo $value['State']['id'];?>">
									<td> <?php
										echo $this->Form->checkbox($value['State']['id'],
											array('class'=>'checkboxes test' ,
												'label'=>false,
												'hiddenField'=>false,
												'value'=> $value['State']['id'])); ?> </td>
									<td><?php
									echo $this->Html->link($value['State']['state_name'],
																array('controller'=>'Cities',
																	   'action'=>'index',
																	   $value['State']['id'])
															  );?></td>
									<td><?php echo date("Y/m/d", strtotime($value['State']['updated']));?></td>
									<td align="center"> <?php 
                                            if($value['State']['status'] == 0) {?>
                                                <a title="Deactive" class="buttonStatus red_bck" href="javascript:void(0);" 
                                                onclick="statusChange(<?php echo $value['State']['id'];?>,'State');">
                                            <i class="fa fa-times"></i><!-- deactive --></a>
                                            <?php } else if($value['State']['status'] == 1){
                                            ?>
                                                <a title="active" class="buttonStatus" href="javascript:void(0);" 
                                                onclick="statusChange(<?php echo $value['State']['id'];?>,'State');">
                                            <i class="fa fa-check"></i></a>
                                            <?php } else {?>
                                                    <a title="Pending" class="buttonStatus yellow_bck" href="javascript:void(0);" 
                                                onclick="statusChange(<?php echo $value['State']['id'];?>,'State');">
                                            <i class="fa fa-exclamation"></i><!-- Pending --></a>
                                            <?php }?>
                                    </td>
									<td align="center"><?php
										echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',
																array('controller'=>'states',
																	   'action'=>'edit',
																		$value['State']['id']),
																array('class'=>'buttonEdit',
																		'escape'=>false));?>
                                    <a class="buttonAction" href="javascript:void(0);"
                                        onclick="deleteprocess(<?php echo $value['State']['id'];?>,'State');" ><i class="fa fa-trash-o"></i></a>
									</td>
								</tr><?php 
								} ?>
							</tbody>
						</table><?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>