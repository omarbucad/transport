<div class="block-header">
	<h2>
		<ol class="breadcrumb" style="padding: 0px;">
			<li>
				<a href="<?php echo site_url('app/outsource'); ?>">
					Outsource Accounts List
				</a>
			</li>
			<li class="active">
				  Update Outsource
			</li>
		</ol>
	</h2>
</div>

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Outsource</h2>
			</div>
			<div class="body">
				<form id="wizard_with_validation" action="<?php echo site_url('app/outsource/update/'.$result->outsource_id) ?>" enctype="multipart/form-data" method="POST">
					<input type="hidden" name="outsource_id" value="<?php echo $result->outsource_id; ?>">
					<?php echo validation_errors(); ?>

					<h3>Outsource Information</h3><br>
					<fieldset>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" name="company_name" value="<?php echo $result->company_name ?>" >
								<label class="form-label">Company Name</label>
							</div>
						</div>


						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" name="billing_address" value="<?php echo $result->billing_address ?>" >
								<label class="form-label">Billing Address</label>
							</div>
						</div>

						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" name="account_no" value="<?php echo $result->account_no ?>" class="form-control">
								<label class="form-label">Account No.</label>
							</div>
						</div>

						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" name="registration_number" value="<?php echo $result->registration_number ?>" required>
								<label class="form-label">Registration Number</label>
							</div>
						</div>

						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" name="vat_number" value="<?php echo $result->vat_number ?>" required>
								<label class="form-label">VAT Number</label>
							</div>
						</div>


					</fieldset>
					<a class="btn btn-danger waves-effect" href="<?php echo site_url('app/outsource'); ?>"><i class="material-icons" style="font-size: 16.5px;">block</i> CANCEL</a>
					<button class="btn btn-primary waves-effect pull-right" type="submit"><i class="material-icons" style="font-size: 16.5px;">done_all</i> SUBMIT</button>
				</form>
			</div>
		</div>
	</div>
</div>