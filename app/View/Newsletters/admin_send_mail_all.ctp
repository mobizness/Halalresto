<div class="contain">
	<div class="contain">
		<h3 class="page-title">Send Mail All User</h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Home</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="<?php echo $siteUrl.'/admin/Newsletters/index';?>">Management</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Send Mail All User</a>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<!-- BEGIN EXTRAS PORTLET-->
				<div class="portlet box blue-hoki">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-gift"></i>Send Mail All User
						</div>
					</div>
					<div class="portlet-body form"><?php 
						echo $this->Form->create('Newsletter',array('class'=>"form-horizontal"));
							?>			
							<div class="form-body">
								<div class="form-group">
									<label class="col-md-3 control-label">Subject <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"><?php 
                                        echo $this->Form->input('subject',
						 				array('label'=>false,
						 						)); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">To <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"><?php 
                                        echo $this->Form->input('to',
						 				array('label'=>false,
						 						)); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Content</label>
									<div class="col-md-9"><?php 
                                        echo $this->Form->input('content',
						 				array('label'=>false,
						 						)); ?>
									</div>
								</div>
							</div>
							<div class="form-actions">
								<div class="row">
									<div class="col-md-offset-3 col-md-9"><?php
										echo $this->Form->button(__('Send'),
														array('class'=>'btn purple')); ?> <?php
										echo $this->Html->link('Cancel',
														array('action' => 'index'),
														array('Class'=>'btn default')); ?>
									</div>
								</div>
							</div>								
					</div><?php 
						echo $this->Form->end();?>
				</div>
			</div>
		</div>
	</div>
</div>