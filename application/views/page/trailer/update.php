<div class="block-header">
	<h2>
		<ol class="breadcrumb" style="padding: 0px;">
			<li>
				<a href="<?php echo site_url('app/trailer'); ?>">
					Trailer Number
				</a>
			</li>
			<li class="active">
				  Update Trailer Number
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
				<form id="wizard_with_validation" action="<?php echo site_url('app/trailer/updateTrailer/'.$result->id) ?>" enctype="multipart/form-data" method="POST">
					<input type="hidden" name="id" value="<?php echo $result->id; ?>">
					<input type="hidden" name="old_trailer_number" value="<?php echo $result->trailer_number; ?>">
					<?php echo validation_errors(); ?>

					<h3>Trailer Information</h3>
					<fieldset>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" name="short_trailer_number" value="<?php echo $result->short_trailer_number; ?>">
								<label class="form-label">Short Trailer Number*</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" name="trailer_number" value="<?php echo $result->trailer_number; ?>">
								<label class="form-label">Trailer Number*</label>
							</div>
						</div>

						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" name="trailer_make" value="<?php echo $result->trailer_make; ?>">
								<label class="form-label">Trailer Make*</label>
							</div>
						</div>

						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" name="trailer_type" value="<?php echo $result->trailer_type; ?>">
								<label class="form-label">Trailer Type*</label>
							</div>
						</div>

						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" name="trailer_axle" value="<?php echo $result->trailer_axle; ?>">
								<label class="form-label">Trailer Axle*</label>
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
								<select class="form-control show-tick" name="status" required>
									<option value="1" <?php echo custom_set_select(false, 1 , $result->status); ?>>Active</option>
									<option value="0" <?php echo custom_set_select(false, 0 , $result->status); ?>>Inactive</option>
								</select>
							</div>
						</div>
					</fieldset>
					<a class="btn btn-danger waves-effect" href="<?php echo site_url('app/trailer'); ?>"><i class="material-icons" style="font-size: 16.5px;">block</i> CANCEL</a>
					<button class="btn btn-primary waves-effect pull-right" type="submit"><i class="material-icons" style="font-size: 16.5px;">done_all</i> SUBMIT</button>
				</form>
			</div>
		</div>
	</div>
</div>