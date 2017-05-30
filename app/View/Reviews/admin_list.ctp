<div class="contain">
	<div class="contain">	
		<h3 class="page-title">Gestion Reviews</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/admin/dashboards/index'; ?>">Accueil</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Gestion Reviews</a>
				</li>
			</ul>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box grey-cascade">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-globe"></i>Gestion Reviews
						</div>
						<div class="tools">
							
						</div>
					</div>
					<div class="portlet-body">
						<?php
						echo $this->Form->create('Commons', array('class'=>'form-horizontal',
							'controller'=>'Commons','action'=>'multipleSelect')); ?>
						<div class="table-toolbar">
							<div id="send" style="display:none" class="pull-left">
								<div class="pull-right" id="addnewbutton_toggle"> <?php
									echo $this->Form->hidden("Model",array('value'=>'Review',
										'name'=>'data[name]'));
									if (!empty($Review_list)) {
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
							<!--<div class="col-md-12">
								<div class="btn-group pull-left">
									<?php
											echo $this->Form->input('store_id',
													array('type'  => 'select',
														  'class' => 'form-control',
														  'options'=> array($store_list),
                                                         'onchange' => 'Fillter();',
														  'empty' => 'SelectStore',
										 				  'label'=> false)); ?>
								</div>
							</div>-->
						</div>
						<table class="table table-striped table-bordered table-hover checktable" id="sample_12">
							<thead>
								<tr>
									<th class="table-checkbox no-sort"><input type="checkbox" class="group-checkable test1" data-set="#sample_1 .checkboxes" /></th>
									<th>Nom du Restaurant</th>
									<th><?php echo __('Rating');?></th>
									<th>Message</th>
									<th class="no-sort">Statut</th>
									<th class="no-sort">Action</th>
								</tr>
							</thead>
							<tbody>
                            <?php 
                        		$count = 1;
								foreach ($Review_list as $key =>$value){?>
								<tr class="odd gradeX"
								 id="record<?php echo $value['Review']['id'];?>">
									<td> <?php
										echo $this->Form->checkbox($value['Review']['id'],
											array('class'=>'checkboxes test' ,
												'label'=>false,
												'hiddenField'=>false,
												'value'=> $value['Review']['id'])); ?> </td>
									<td><?php echo $value['Store']['store_name'];?></td>
									<td><?php echo $value['Review']['rating'];?></td>
									<td><?php echo $value['Review']['message'];?></td>
									<td align="center"> <?php 
                                    if($value['Review']['status'] == 0) {?>
                                        <a title="Deactive" class="buttonStatus red_bck" href="javascript:void(0);" 
                                        onclick="statusChange(<?php echo $value['Review']['id'];?>,'Review');">
                                     <i class="fa fa-times"></i><!-- deactive --></a>
                                    <?php } else if($value['Review']['status'] == 1) {
                                    ?>
                                        <a title="active" class="buttonStatus" href="javascript:void(0);" 
                                        onclick="statusChange(<?php echo $value['Review']['id'];?>,'Review');">
                                    <i class="fa fa-check"></i></a>
                                    <?php } else {?>
                                        <a title="Pending" class="buttonStatus yellow_bck" href="javascript:void(0);" 
                                        onclick="statusChange(<?php echo $value['Review']['id'];?>,'Review');">
                                    <i class="fa fa-exclamation"></i><!-- Pending --></a>
                                   <?php }?>
                                    </td>
									<td align="center">
										<a class="buttonAction" href="javascript:void(0);"
                                        onclick="deleteprocess(<?php echo $value['Review']['id'];?>,'Review');" ><i class="fa fa-trash-o"></i></a>
									</td>
								</tr><?php 
								$count++;
								}?>
							</tbody>
						</table><?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

