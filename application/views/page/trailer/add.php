<div class="block-header">
	<h2>
		<ol class="breadcrumb" style="padding: 0px;">
			<li>
				<a href="<?php echo site_url('app/trailer'); ?>">
					Trailer Number
				</a>
			</li>
			<li class="active">
				  Add Trailer Number
			</li>
		</ol>
	</h2>
</div>

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Trailer Number</h2>
			</div>
			<div class="body">
				<form id="wizard_with_validation" action="<?php echo site_url('app/trailer/add') ?>" enctype="multipart/form-data" method="POST">
					<?php echo validation_errors(); ?>

					<h3>Trailer Information</h3>
					<fieldset>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" name="short_trailer_number" value="<?php echo set_value('short_trailer_number'); ?>">
								<label class="form-label">Short Trailer Number*</label>
							</div>
						</div>

						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" name="trailer_number" value="<?php echo set_value('trailer_number'); ?>">
								<label class="form-label">Trailer Number*</label>
							</div>
						</div>

						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" name="trailer_make" value="<?php echo set_value('trailer_make'); ?>">
								<label class="form-label">Make*</label>
							</div>
						</div>

						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" name="trailer_type" value="<?php echo set_value('trailer_type'); ?>">
								<label class="form-label">Type*</label>
							</div>
						</div>

						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" name="trailer_axle" value="<?php echo set_value('trailer_axle'); ?>">
								<label class="form-label">Axle*</label>
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
					<a class="btn btn-danger waves-effect" href="<?php echo site_url('app/trailer'); ?>"><i class="material-icons" style="font-size: 16.5px;">block</i> CANCEL</a>
					<button class="btn btn-primary waves-effect pull-right" type="submit"><i class="material-icons" style="font-size: 16.5px;">done_all</i> SUBMIT</button>
				</form>
			</div>
		</div>
	</div>
</div>