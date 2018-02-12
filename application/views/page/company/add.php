<div class="block-header">
	<h2>
		<ol class="breadcrumb" style="padding: 0px;">
			<li>
				<a href="<?php echo site_url('app/company'); ?>">
					Company
				</a>
			</li>
			<li class="active">
				  Add Company
			</li>
		</ol>
	</h2>
</div>

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Company</h2>
			</div>
			<div class="body">
				<form id="wizard_with_validation" action="<?php echo site_url('app/company/add') ?>" enctype="multipart/form-data" method="POST">
					<?php echo validation_errors(); ?>

					<h3>Company Information</h3>
					<fieldset>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" name="company_name" value="<?php echo set_value('company_name'); ?>">
								<label class="form-label">Company Name</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<textarea name="description" cols="30" rows="3" class="form-control no-resize"><?php echo set_value('description'); ?></textarea>
								<label class="form-label">Description</label>
							</div>
						</div>
					</fieldset>
					<a class="btn btn-danger waves-effect" href="<?php echo site_url('app/company'); ?>"><i class="material-icons" style="font-size: 17px;">block</i> CANCEL</a>
					<button class="btn btn-primary waves-effect pull-right" type="submit"><i class="material-icons md-18" style="font-size: 17px;">done_all</i> SUBMIT</button>
				</form>
			</div>
		</div>
	</div>
</div>