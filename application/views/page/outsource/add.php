<div class="block-header">
	<h2>
		<ol class="breadcrumb" style="padding: 0px;">
			<li>
				<a href="<?php echo site_url('app/outsource/'); ?>">
					Outsource
				</a>
			</li>
			<li class="active">
				  Create Account
			</li>
		</ol>
	</h2>
</div>

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Create your account</h2>
			</div>
			<div class="body">
				<form id="wizard_with_validation" action="<?php echo site_url('app/outsource/add') ?>" enctype="multipart/form-data" method="POST">
					<?php echo validation_errors(); ?>
					<h3>Account Information</h3>
					<fieldset>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" name="username" value="<?php echo set_value('username'); ?>">
								<label class="form-label">Username</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="password" class="form-control" name="password" value="<?php echo set_value('password'); ?>" id="password" >
								<label class="form-label">Password</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="password" class="form-control" name="confirm">
								<label class="form-label">Confirm Password</label>
							</div>
						</div>
					</fieldset>

					<h3>Profile Information</h3>
					<fieldset>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" name="name" class="form-control" value="<?php echo set_value('name'); ?>">
								<label class="form-label">First Name</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" name="surname" value="<?php echo set_value('surname'); ?>" class="form-control">
								<label class="form-label">Last Name</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="email" name="email" class="form-control" value="<?php echo set_value('email'); ?>">
								<label class="form-label">Email</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<textarea name="address" cols="30" rows="3" class="form-control no-resize"><?php echo set_value('address'); ?></textarea>
								<label class="form-label">Address</label>
							</div>
						</div>
					</fieldset>

					<h3>Company Information</h3>
					<fieldset>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" name="company_name" class="form-control" value="<?php echo set_value('company_name'); ?>" >
								<label class="form-label">Company Name</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" name="registration_number" value="<?php echo set_value('registration_number'); ?>" class="form-control">
								<label class="form-label">Registration Number</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" name="vat_number" class="form-control" value="<?php echo set_value('vat_number'); ?>" >
								<label class="form-label">VAT Registration</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" name="account_no" value="<?php echo set_value('account_no'); ?>" class="form-control">
								<label class="form-label">Account No.</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<textarea name="billing_address" cols="30" rows="3" class="form-control no-resize"><?php echo set_value('billing_address'); ?></textarea>
								<label class="form-label">Billing Address</label>
							</div>
						</div>
					</fieldset>
					<h3>Profile Image</h3>
					<fieldset>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="file" name="file" class="form-control">
							</div>
						</div>
					</fieldset>
					<a class="btn btn-danger waves-effect" href="<?php echo site_url('app/customer/accounts'); ?>"><i class="material-icons" style="font-size: 16.5px;">block</i> CANCEL</a>
					<button class="btn btn-primary waves-effect pull-right" type="submit"><i class="material-icons" style="font-size: 16.5px;">done_all</i> SUBMIT</button>
				</form>
			</div>
		</div>
	</div>
</div>