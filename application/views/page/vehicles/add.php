<div class="block-header">
	<h2>
		<ol class="breadcrumb" style="padding: 0px;">
			<li>
				<a href="<?php echo site_url('app/vehicles/gallery'); ?>">
					Vehicle Number
				</a>
			</li>
			<li class="active">
				  Add Vehicle Number
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
				<form id="wizard_with_validation" action="<?php echo site_url('app/vehicles/add') ?>" enctype="multipart/form-data" method="POST">
					<?php echo validation_errors(); ?>

					<h3>Vehicle Information</h3>
					<fieldset>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" name="vehicle_number" value="<?php echo set_value('vehicle_number'); ?>">
								<label class="form-label">Vehicle Number*</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" name="truck_make" value="<?php echo set_value('truck_make'); ?>">
								<label class="form-label">Make*</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" name="truck_type" value="<?php echo set_value('truck_type'); ?>">
								<label class="form-label">Type*</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<textarea name="description" cols="30" rows="3" class="form-control no-resize"><?php echo set_value('description'); ?></textarea>
								<label class="form-label">Description</label>
							</div>
						</div>
                        <div class="form-group form-float">
                            <p><b>Store</b></p>
							<select class="form-control show-tick" name="store_id">
								    <?php foreach($store_list as $row): ?>
											<option value="<?php echo $row->store_id; ?>"><?php echo $row->store_name; ?></option>
								    <?php endforeach; ?>
				            </select>
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
					<a class="btn btn-danger waves-effect" href="<?php echo site_url('app/vehicles/gallery'); ?>"><i class="material-icons" style="font-size: 16.5px;">block</i> CANCEL</a>
					<button class="btn btn-primary waves-effect pull-right" type="submit"><i class="material-icons" style="font-size: 16.5px;">done_all</i> SUBMIT</button>
				</form>
			</div>
		</div>
	</div>
</div>