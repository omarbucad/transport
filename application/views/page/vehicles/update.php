<div class="block-header">
	<h2>
		<ol class="breadcrumb" style="padding: 0px;">
			<li>
				<a href="<?php echo site_url('app/vehicles'); ?>">
					Vehicle Number
				</a>
			</li>
			<li class="active">
				  Update Vehicle Number
			</li>
		</ol>
	</h2>
</div>

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Vehicle Number</h2>
			</div>
			<div class="body">
				<form id="wizard_with_validation" action="<?php echo site_url('app/vehicles/updateVehicle/'.$result->id) ?>" enctype="multipart/form-data" method="POST">
					<input type="hidden" name="id" value="<?php echo $result->id; ?>">
					<input type="hidden" name="old_vehicle_number" value="<?php echo $result->vehicle_number; ?>">
					<?php echo validation_errors(); ?>

					<h3>Vehicle Information</h3>
					<fieldset>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" name="vehicle_number" value="<?php echo $result->vehicle_number; ?>">
								<label class="form-label">Vehicle Number*</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" name="truck_make" value="<?php echo $result->truck_make; ?>" >
								<label class="form-label">Make*</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" name="truck_type" value="<?php echo $result->truck_type; ?>">
								<label class="form-label">Type*</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<textarea name="description" cols="30" rows="3" class="form-control no-resize"><?php echo $result->description; ?></textarea>
								<label class="form-label">Description</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<select class="form-control show-tick" name="status">
									<option value="1" <?php echo custom_set_select(false, 1 , $result->status); ?>>Active</option>
									<option value="0" <?php echo custom_set_select(false, 0 , $result->status); ?>>Inactive</option>
								</select>
							</div>
						</div>	
					</fieldset>
					<h3>Image</h3>
					<p class="help-block">Can Select Multiple Images</p>
					<fieldset>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="file" name="file[]" class="form-control" multiple="">
							</div>
						</div>
					</fieldset>
					<a class="btn btn-danger waves-effect" href="<?php echo site_url('app/vehicles'); ?>"><i class="material-icons" style="font-size: 16.5px;">block</i> CANCEL</a>
					<button class="btn btn-primary waves-effect pull-right" type="submit"><i class="material-icons" style="font-size: 16.5px;">done_all</i> SUBMIT</button>
				</form>
			</div>
		</div>
	</div>
</div>