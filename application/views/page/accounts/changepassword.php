<div class="block-header">
	<h2>
		<ol class="breadcrumb" style="padding: 0px;">
			<li>
				<a href="<?php echo site_url('app/accounts'); ?>">
					Accounts
				</a>
			</li>
			<li class="active">
				  Change Password
			</li>
		</ol>
	</h2>
</div>

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Change Password</h2>
			</div>
			<div class="body">
				<form id="wizard_with_validation" action="<?php echo site_url('app/accounts/changepassword') ?>" enctype="multipart/form-data" method="POST">
					<?php echo validation_errors(); ?>
					<?php if($change_status) : ?>
						<div class="alert alert-success">
						  <strong>Success!</strong> Successfully Change your Password
						</div>
					<?php endif; ?>
					<div class="panel panel-primary">
						<div class="panel-heading">Password Guidelines</div>
						<div class="panel-body">
							<nav>
								<ul class="list-unstyled">
									<li>Don't share passwords with others</li>
									<li>Make passwords hard to guess</li>
									<li>Use an 8 character password</li>
									<li>Change passwords regularly</li>
									<li>Don't leave passwords where others can find them</li>
								</ul>
							</nav>
						</div>
					</div>
					<hr>
					<fieldset>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="password" class="form-control" name="password" value="<?php echo set_value('password'); ?>" id="password" required>
								<label class="form-label">Password*</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="password" class="form-control" name="confirm" required>
								<label class="form-label">Confirm Password*</label>
							</div>
						</div>

					</fieldset>

					<a class="btn btn-danger waves-effect" href="<?php echo site_url('app/accounts'); ?>"><i class="material-icons" style="font-size: 16.5px;">block</i> CANCEL</a>
					<button class="btn btn-primary waves-effect pull-right" type="submit"><i class="material-icons" style="font-size: 16.5px;">done_all</i> SUBMIT</button>
					
				</form>
			</div>
		</div>
	</div>
</div>