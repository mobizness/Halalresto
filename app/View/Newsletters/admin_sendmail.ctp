	<div class="page-content-wrapper">
			<div class="page-content">
				<h3 class="page-title">Send Mail All User</h3>
				<div class="page-bar">
					<ul class="page-breadcrumb">
						<li>
							<i class="fa fa-home"></i>
							<a href="<?php echo $siteUrl.'/admin/dashboards/index'; ?>">Accueil</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="#">Management</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="#">Manage Newsletter</a>
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
							<div class="portlet-body form">
								<form class="form-horizontal">
									<div class="form-body">
										<div class="form-group">
											<label class="col-md-3 control-label">Subject <span class="star">*</span></label>
											<div class="col-md-6 col-lg-4">
												<input type="text" class="form-control">
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">To <span class="star">*</span></label>
											<div class="col-md-6 col-lg-4">
												<input type="text" class="form-control">
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">Content</label>
											<div class="col-md-9">
												<div name="summernote" id="summernote_1"></div>
											</div>
										</div>
									</div>
									<div class="form-actions">
										<div class="row">
											<div class="col-md-offset-3 col-md-9">
												<button type="submit" class="btn purple"><i class="fa fa-check"></i> Submit</button>
												<a href="newsletter.html" class="btn default">Cancel</a>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>