<div class="contain">
	<div class="contain">
		<h3 class="page-title">Managed AreaList</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/admin/dashboards/index'; ?>">Accueil</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Location</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Manage AreaList</a>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box grey-cascade">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-globe"></i>Managed AreaList
						</div>
						<div class="tools">
							
						</div>
					</div>
					<div class="portlet-body"><?php
						echo $this->Form->create('Commons', array('class'=>'form-horizontal',
							'controller'=>'Commons','action'=>'multipleSelect')); ?>
						<div id="send" style="display:none" class="pull-right">
							<div class="pull-right" id="addnewbutton_toggle"> <?php
								echo $this->Form->hidden("Model",array('value'=>'Location',
									'name'=>'data[name]'));
								if (!empty($city_detail)) {
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
						<table class="table table-striped table-bordered table-hover checktable" id="sample_12">
							<thead>
								<tr>
									<th class="table-checkbox"><input type="checkbox" class="group-checkable test1" data-set="#sample_1 .checkboxes" /></th>
									<th>Nom de la région</th>
									<th>Code postal</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody><?php 

								foreach ($city_detail as $key => $value) { ?>
								<tr class="odd gradeX">
									<td> <?php
										echo $this->Form->checkbox($value['Location']['id'],
											array('class'=>'checkboxes test' ,
												'label'=>false,
												'hiddenField'=>false,
												'value'=> $value['Location']['id'])); ?> </td>
									<td><?php echo $value['Location']['area_name'];?></td>
									<td><?php echo $value['Location']['zip_code'];?></td>
									<td align="center"> <?php 
                                            if($value['Location']['status'] == 0) {?>
                                                <a title="Deactive" class="buttonStatus red_bck" href="javascript:void(0);" 
                                                onclick="statusChange(<?php echo $value['Location']['id'];?>,'Location');">
                                             <i class="fa fa-times"></i><!-- deactive --></a>
                                            <?php } else if($value['Location']['status'] == 1){
                                            ?>
                                                <a title="active" class="buttonStatus" href="javascript:void(0);" 
                                                onclick="statusChange(<?php echo $value['Location']['id'];?>,'Location');">
                                            <i class="fa fa-check"></i></a>
                                            <?php } else {?>
                                                 <a title="Pending" class="buttonStatus yellow_bck" href="javascript:void(0);" 
                                                onclick="statusChange(<?php echo $value['Location']['id'];?>,'Location');">
                                            <i class="fa fa-exclamation"></i><!-- Pending --></a>
                                            <?php }?>
                                    </td>
								</tr><?php }?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>