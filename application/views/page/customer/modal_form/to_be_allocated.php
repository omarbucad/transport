
<form>
	<input type="hidden" name="jobs_id" value="<?php echo $jobs_id; ?>">
	<input type="hidden" name="status_id" value="<?php echo $result->status_id; ?>">
	<input type="hidden" name="type" value="to_be_allocated">
	<input type="hidden" name="with_outsource" value="<?php echo $result->with_outsource; ?>">
	<fieldset>
		<?php if($this->session->userdata("account_type") == SUPER_ADMIN) : ?>
		<div class="form-group form-float">
			<div class="form-line">
				<select class="form-control" name="data[customer_id]">
					<?php foreach($customerList as $row) : ?>
						<option value="<?php echo $row->customer_id; ?>" <?php echo ($row->customer_id == $result->customer_id) ? "selected" : ""; ?> ><?php echo $row->company_name; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="help-info">Customer</div>
		</div>
		<?php endif; ?>
		<div class="form-group form-float">
			<div class="form-line focused">
				<input type="text" class="form-control" name="data[job_name]" value="<?php echo $result->job_name; ?>">
				<label class="form-label">Job Name</label>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-6">
				<div class="form-group form-float">
					<div class="form-line focused">
						<input type="text" name="data[loading][date]"  class="dtpicker form-control _loading_date" value="<?php echo ( $result->loading_d != "NA" ) ? $result->loading_d : "" ; ?>" required>
						<label class="form-label">Loading Date</label>
					</div>
				</div>
			</div>
			<div class="col-xs-6">
				<div class="form-group form-float">
					<div class="form-line focused">
						<input type="text" class="form-control time12 _loading_time" name="data[loading][time]" value="<?php echo ($result->loading_t != "NA") ? $result->loading_t : ""; ?>" placeholder="Ex: 11:59 pm" required>
					</div>
					<div class="help-info">Time</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-6">
				<div class="form-group form-float">
					<div class="form-line focused">
						<input type="text" name="data[delivery][date]"  class="dtpicker form-control _delivery_date" value="<?php echo ($result->delivery_d != "NA") ? $result->delivery_d : ""; ?>" required>
						<label class="form-label">Delivery Date</label>
					</div>
				</div> 
			</div>
			<div class="col-xs-6">
				<div class="form-group form-float">
					<div class="form-line focused">
					<input type="text" class="form-control time12 _delivery_time" name="data[delivery][time]" value="<?php echo ($result->delivery_t != "NA") ? $result->delivery_t : ""; ?>" placeholder="Ex: 11:59 pm" required>
					</div>
					<div class="help-info">Time</div>
				</div>
			</div>
		</div> 

		<div class="form-group form-float">
			<div class="form-line focused">
				<input type="text" class="form-control" name="data[telephone]" value="<?php echo $result->telephone; ?>">
				<label class="form-label">Telephone</label>
			</div>
		</div>

		<div class="form-group form-float">
			<div class="form-line focused">
				<textarea cols="30" rows="3" name="data[driver_notes]" class="form-control no-resize _driver_notes"><?php echo $result->driver_notes; ?></textarea>
				<label class="form-label">Note For the Driver</label>
			</div>
			<div class="help-info">Optional</div>
		</div>
		<div class="form-group form-float">
			<div class="form-line focused">
				<textarea name="data[load_site]" cols="30" rows="3" class="form-control no-resize" required> <?php echo $result->load_site; ?> </textarea>
				<label class="form-label">Load Site</label>
			</div>
		</div>
		<div class="form-group form-float">
			<div class="form-line focused">
				<input type="text" class="form-control" name="data[zip_code_load_site]" value="<?php echo $result->zip_code_load_site; ?>" required>
				<label class="form-label">Load Site Zip Code</label>
			</div>
		</div>     
		<div class="form-group form-float">
			<div class="form-line focused">
				<textarea name="data[address]" cols="30" rows="3" class="form-control no-resize" required><?php echo $result->address; ?></textarea>
				<label class="form-label">Destination</label>
			</div>
		</div>
		<div class="form-group form-float">
			<div class="form-line focused">
				<input type="text" class="form-control" name="data[zip_code]" value="<?php echo $result->zip_code; ?>" required>
				<label class="form-label">Destination Zip Code</label>
			</div>
		</div>
		<div class="form-group form-float">
			<div class="form-line focused">
				<input type="text" class="form-control" name="data[site_contact]" value="<?php echo $result->site_contact; ?>" required>
				<label class="form-label">Site Contact</label>
			</div>
		</div>
		<div class="form-group form-float">
			<div class="form-line focused">
				<textarea name="data[notes]" cols="30" rows="3" class="form-control no-resize" required><?php echo $result->job_notes; ?></textarea>
				<label class="form-label">Special Note for the Job</label>
			</div>
		</div>
		
		<div class="form-group form-float ">
			<div class="form-line focused">
				<input type="number" class="form-control" step="0.01" name="data[price]" value="<?php echo $result->price; ?>">
				<label class="form-label">Price</label>
			</div>
		</div>
		<?php if($result->with_outsource) : ?>

		<div class="form-group form-float ">
			<div class="form-line focused">
				<select class="form-control show-tick" name="data[outsource_company_name]" data-live-search="true" required>
					<?php foreach($outsource_list as $list) : ?>
						<option value="<?php echo $list->id; ?>" <?php echo ($list->id == $result->outsource_company_name) ? "selected" : ""; ?>  ><?php echo $list->company_name; ?></option>
					<?php endforeach; ?>
				</select>
				<div class="help-info">Outsource Company Name</div>
			</div>
		</div>

		<div class="form-group form-float ">
			<div class="form-line focused">
				<input type="number" class="form-control" step="0.01" name="data[outsource_price]" value="<?php echo $result->outsource_price; ?>">
				<label class="form-label">Outsource Price</label>
			</div>
		</div>

		<?php endif; ?>

		<div class="form-group form-float">
			<div class="form-line focused">
			<input type="text" class="form-control" name="data[job_number]" value="<?php echo $result->job_number; ?>" required>
				<label class="form-label">Job Number</label>
			</div>
		</div>
		<?php if($result->with_outsource) : ?>
			<div class="form-group form-float">
				<div class="form-line focused">
					<input type="text" name="data[outsource_truck]" class="form-control" value="<?php echo $result->outsource_truck; ?>">
				</div>
				<div class="help-info">Truck</div>
			</div>
			<div class="form-group form-float">
				<div class="form-line focused">
					<input type="text" name="data[outsource_driver_name]" class="form-control" value="<?php echo $result->outsource_driver_name; ?>">
				</div>
				<div class="help-info">Driver</div>
			</div>
		<?php else : ?>
			
			<input type="hidden" name="old[vehicle_id]" value="<?php echo $result->vehicle_id; ?>">
			<input type="hidden" name="old[trailer_id]" value="<?php echo $result->trailer_id; ?>">
			<input type="hidden" name="old[driver_id]" value="<?php echo $result->driver_id; ?>">

			<div class="form-group form-float">
				<div class="form-line focused">
					<select class="form-control show-tick" name="data[vehicle_id]" data-live-search="true" required>
						<?php foreach($select["truck"] as $store_name => $truck) : ?>
							<optgroup label="<?php echo $store_name; ?>">
								<?php foreach($truck[$result->type_of_truck] as $t) : ?>
									<option value="<?php echo $t->id; ?>" <?php echo ($result->vehicle_id == $t->id) ? "selected" : ""; ?> ><?php echo $t->vehicle_number; ?></option>
								<?php endforeach; ?>
							</optgroup>   
						<?php endforeach; ?>
					</select>
				</div>
				<div class="help-info">Truck</div>
			</div>
			<div class="form-group form-float">
				<div class="form-line focused">
					<select class="form-control show-tick" name="data[driver_id]"  data-live-search="true" required>
						<option value=""> - Select Driver - </option>
						<?php foreach($select["driver"] as $store_name => $driver) : ?>
							<optgroup label="<?php echo $store_name; ?>">
								<?php foreach($driver as $d) : ?>
									<option value="<?php echo $d->id; ?>" <?php echo ($result->driver_id == $d->id) ? "selected" : ""; ?> ><?php echo $d->name; ?></option>
								<?php endforeach; ?>
							</optgroup>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="help-info">Driver</div>
			</div>
		<?php endif; ?>

		<div class="form-group form-float">
			<div class="form-line focused">
				<select class="form-control show-tick" name="data[trailer_id]" data-live-search="true" required>
					<option value=""> - Select Trailer - </option>
					<?php foreach($select["trailer"] as $store_name => $trailer) : ?>
						<optgroup label="<?php echo $store_name; ?>">
							<?php foreach($trailer as $t) : ?>
								<option value="<?php echo $t->id; ?>" <?php echo ($result->trailer_id == $t->id) ? "selected" : ""; ?> ><?php echo $t->trailer_number; ?></option>
							<?php endforeach; ?>
						</optgroup>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="help-info">Trailer</div>
		</div>

		<div class="form-group form-float">
			<div class="form-line focused">
				<select class="form-control show-tick" name="data[load_trailer]">
					<option value="">-</option>
					<option value="Loaded at Arena" <?php echo ($result->load_trailer == "Loaded at Arena") ? "selected" : ""; ?> >Loaded at Arena</option>
					<option value="Loaded at FPMcCann" <?php echo ($result->load_trailer == "Loaded at FPMcCann") ? "selected" : ""; ?> >Loaded at FPMcCann</option>
					<option value="Loaded at Arena" <?php echo ($result->load_trailer == "Loaded at Arena") ? "selected" : ""; ?> >Loaded at Arena</option>
					<option value="Load and Go" <?php echo ($result->load_trailer == "Load and Go") ? "selected" : ""; ?> >Load and Go</option>
				</select>
			</div>
			<div class="help-info">Load Trailer</div>
		</div>


	</fieldset>
</form>