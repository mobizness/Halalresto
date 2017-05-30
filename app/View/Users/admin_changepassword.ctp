<div class="contain">
	<div class="contain">
		<h3 class="page-title">Change Password <small>change your password</small></h3>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="index.html">Home</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Admin</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Change Password</a>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<!-- BEGIN PORTLET-->
				<div class="portlet box blue-hoki">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-key"></i> Change Password
						</div>
						<div class="tools">
							
						</div>
					</div>
					<div class="portlet-body form">	<?php
          				echo $this->Form->create('User',array('class'=>'form-horizontal')); ?>
							<div class="form-body">
								<div class="form-group">
									<label class="col-md-3 control-label">Old Password</label>
									<div class="col-md-6 col-lg-4"> <?php
										echo $this->Form->input('oldpassword',
								                            array('class' => 'form-control',
								                                  'type'  => 'password',
								                                  'label' => false)); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">New Password</label>
									<div class="col-md-6 col-lg-4"> <?php
										echo $this->Form->input('newpassword',
								                            array('class' => 'form-control',
								                                  'type'  => 'password',
								                                  'id'	  => 'UserPassword',
								                                  'label' => false)); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Confirm Password</label>
									<div class="col-md-6 col-lg-4"> <?php
										echo $this->Form->input('retypepassword',
								                            array('class' => 'form-control',
								                                  'type'  => 'password',
								                                  'label' => false)); ?>
									</div>
								</div>
							</div>
							<div class="form-actions">
								<div class="row">
									<div class="col-md-offset-3 col-md-9"> <?php
						                  echo $this->Form->button('<i class="fa fa-check"></i> Submit',
						                              array('class'=>'btn purple')); ?>
										<button type="button" class="btn default">Cancel</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
				<!-- END PORTLET-->
			</div>
		</div>
	</div>
</div>