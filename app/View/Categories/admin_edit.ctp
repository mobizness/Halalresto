
<div class="contain">
	<div class="contain">
		<h3 class="page-title">Editer Catégorie</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Accueil</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="<?php echo $siteUrl.'/admin/Categories/index';?>">Gestion Catégorie</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Editer Catégorie</a>
				</li>
			</ul>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box blue-hoki">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-user"></i> Editer Catégorie
						</div>
						<div class="tools"></div>
					</div>
					<div class="portlet-body form"><?php 
								echo $this->Form->create('Category',array('class'=>"form-horizontal"));
									?>			
							<div class="form-body">
								<div class="form-group">
									<label class="col-md-3 control-label">Catégorie Name <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"><?php
													echo $this->Form->input('category_name',
															array('class'=>'form-control',
																	'label'=>false)); 
													echo $this->Form->hidden('id');?>
										</div>
								</div>
							</div>
							<div class="form-actions">
								<div class="row">
									<div class="col-md-offset-3 col-md-9"><?php
												echo $this->Form->button(__('<i class="fa fa-check"></i>'.__('Submit')),array('class'=>'btn purple')); 
												echo $this->Html->link(__('Cancel'),
																array('action' => 'index'),
																array('Class'=>'btn default')); ?>
									</div>
								</div>
							</div>
					</div><?php echo $this->Form->end();?>
				</div>
			</div>
		</div>
	</div>
</div>
