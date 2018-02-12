<tr>
	<td class="<?php echo ($row->new_type == "new") ? "success" : ""; ?>">
		<form>
			<dl>
				<dt>Transaction #</dt>
				<dd><a href="<?php echo site_url("app/customer/jobs/".$row->job_parent_id); ?>"><?php echo $row->job_parent_id; ?></a></dd>
				<dt>Customer Name</dt>
				<dd><a href="<?php echo site_url("app/customer/jobs/".$row->job_parent_id); ?>"><?php echo $row->company_name; ?></a></dd>
				<dt>Job Name</dt>
				<dd><a href="<?php echo site_url("app/customer/jobs/".$row->job_parent_id.'/'.urlencode($row->job_name)); ?>"><?php echo $row->job_name; ?></a></dd>
				<dt>Date Created</dt>
				<dd><?php echo $row->created; ?></dd>  
				<dt>Telephone</dt>
				<dd><a href="tel:<?php echo $row->telephone; ?>"><?php echo $row->telephone; ?></a></dd>
				<dt>Destination</dt>
				<dd><?php echo $row->address; ?></dd>
				<dt>Number of Trucks</dt>
				<dd><?php echo $row->number_of_truck; ?></dd>
				<dt>Special Notes</dt>
				<dd><?php echo $row->job_notes; ?></dd>
			</dl>	
			<div class="text-right <?php echo (isset($row->status_type["finished"]) OR isset($row->status_type["cancel"])) ? "hide" : ""; ?>">
				<a href="javascript:void(0);" data-id="<?php echo $row->jobs_id; ?>" data-type="cancel" class="btn btn-danger btn-block btnUpdate">Cancel</a>
			</div>
		</form>
	</td>
	<td class="<?php echo ($row->new_type == "to_be_allocated") ? "success" : ""; ?>">
		<form class="to_be_allocated_form">
			<input type="hidden" name="status_type" value="to_be_allocated">
			<input type="hidden" name="jobs_id">
			<dl>
				<dt>Created</dt>
				<dd class="created"><?php echo (isset($row->status_type["to_be_allocated"]->created)) ? $row->status_type["to_be_allocated"]->created : "NA" ; ?></dd>
				<dt>Updated</dt>
				<dd class="updated"><?php echo (isset($row->status_type["to_be_allocated"]->updated)) ? $row->status_type["to_be_allocated"]->updated : "NA" ; ?></dd>
				<dt>Updated By</dt>
				<dd class="updated_by"><?php echo (isset($row->status_type["to_be_allocated"]->name)) ? $row->status_type["to_be_allocated"]->name : "NA" ; ?></dd>
				<dt>Loading Time</dt>
				<dd><?php echo $row->loading_time; ?></dd>
				<dt>Delivery Time</dt>
				<dd><?php echo $row->delivery_time; ?></dd>
				<dt>Type of Truck</dt>
				<dd><?php echo $row->type_of_truck; ?></dd>
				<dt>Price</dt>
			  	<dd><input type="number" class="form-control" step="0.01" name="price" value="<?php echo $row->price; ?>"></dd>
			</dl>

			
			<div class="text-right <?php echo (isset($row->status_type["allocated"]) OR isset($row->status_type["cancel"])) ? "hide" : ""; ?>">
				<a href="javascript:void(0);" data-id="<?php echo $row->jobs_id; ?>" data-type="to_be_allocated" class="btn btn-primary btn-xs btnUpdate">Update</a>
			</div>
		</form>
	</td>
	<td class="<?php echo ($row->new_type == "allocated") ? "success" : ""; ?>">
		<form class="allocated_form">
			<input type="hidden" name="status_type" value="allocated">
			<input type="hidden" name="jobs_id">
			<dl>
				<dt>Created</dt>
				<dd class="created"><?php echo (isset($row->status_type["allocated"]->created)) ? $row->status_type["allocated"]->created : "NA"; ?></dd>
				<dt>Updated</dt>
				<dd class="updated"><?php echo (isset($row->status_type["allocated"]->updated)) ? $row->status_type["allocated"]->updated : "NA"; ?></dd>
				<dt>Updated By</dt>
				<dd class="updated_by"><?php echo (isset($row->status_type["allocated"]->name)) ? $row->status_type["allocated"]->name : "NA" ; ?></dd>
				<dt>Truck</dt>
				<dd>
					<select class="form-control show-tick" name="vehicle_number" id="_select_vehicle_number" data-live-search="true" required>
						<?php foreach($select["truck"] as $store_name => $truck) : ?>
							<optgroup label="<?php echo $store_name; ?>">
								<?php foreach($truck[$row->type_of_truck] as $t) : ?>
									<option value="<?php echo $t->id; ?>" <?php echo ($row->vehicle_id == $t->id) ? "selected" : ""; ?> ><?php echo $t->vehicle_number; ?></option>
								<?php endforeach; ?>
							</optgroup>   
						<?php endforeach; ?>
					</select>
				</dd>
				<dt>Driver</dt>
				<dd>
					<select class="form-control show-tick" name="driver" id="_select_driver" data-live-search="true" required>
						<option value=""> - Select Driver - </option>
						<?php foreach($select["driver"] as $store_name => $driver) : ?>
							<optgroup label="<?php echo $store_name; ?>">
								<?php foreach($driver as $d) : ?>
									<option value="<?php echo $d->id; ?>" <?php echo ($row->driver_id == $d->id) ? "selected" : ""; ?> ><?php echo $d->name; ?></option>
								<?php endforeach; ?>
							</optgroup>
						<?php endforeach; ?>
					</select>
				</dd>
				
				<dt>Trailer</dt>
				<dd>
					<select class="form-control show-tick" name="trailer_number" id="_select_trailer_number" data-live-search="true" required>
						<option value=""> - Select Trailer - </option>
						<?php foreach($select["trailer"] as $store_name => $trailer) : ?>
							<optgroup label="<?php echo $store_name; ?>">
								<?php foreach($trailer as $t) : ?>
									<option value="<?php echo $t->id; ?>" <?php echo ($row->trailer_id == $t->id) ? "selected" : ""; ?> ><?php echo $t->trailer_number; ?></option>
								<?php endforeach; ?>
							</optgroup>
						<?php endforeach; ?>
					</select>
				</dd>
				<dt>Driver Notes</dt>
				<dd>
					<?php echo $row->driver_notes; ?>
				</dd>
			</dl>
			<input id="acceptTerms-2" name="displayToDriver" type="checkbox" <?php echo ($row->show_to_driver) ? "checked" : ""; ?>>
	        <label for="acceptTerms-2">Display to Driver the notes?</label> 
	        <div class="text-right <?php echo (isset($row->status_type["finished"]) OR isset($row->status_type["cancel"])) ? "hide" : ""; ?>">
	        	<a href="javascript:void(0);" data-id="<?php echo $row->jobs_id; ?>" data-type="allocated" class="btn btn-primary btn-xs btnUpdate <?php echo (@$row->status_type["to_be_allocated"]) ? "" : "hide" ; ?>" >Update</a>
	        </div>
		</form>
	</td>
	<td class="<?php echo ($row->new_type == "finished") ? "primary" : ""; ?>">
		<form>
			<input type="hidden" name="status_type" value="finished">
			<input type="hidden" name="jobs_id">
			<dl>
				<dt>Created</dt>
				<dd class="created"><?php echo (isset($row->status_type["finished"]->created)) ? $row->status_type["finished"]->created : "NA"; ?></dd>
				<dt>Updated</dt>
				<dd class="updated"><?php echo (isset($row->status_type["finished"]->updated)) ? $row->status_type["finished"]->updated : "NA"; ?></dd>
				<dt>Updated By</dt>
				<dd class="updated_by"><?php echo (isset($row->status_type["finished"]->name)) ? $row->status_type["finished"]->name : "NA" ; ?></dd>
				<dt>Status</dt>
				<dd><?php echo $row->new; ?></dd>
				<dt>Time of Arrival</dt>
				<dd>
					<!-- <div class="row">
						<div class="col-xs-6" style="margin-bottom: 0px;">
							<input type="text" name="delivered_date" class="dtpicker form-control" value="<?php //echo $row->delivered_date?>">
						</div>
						<div class="col-xs-6" style="margin-bottom: 0px;">
							<input type="text" class="form-control time12" name="delivered_time" value="<?php //echo $row->delivered_time?>" placeholder="Ex: 11:59 pm">
						</div>
					</div> -->
					NA
				</dd>
				<dt>Unloading Time</dt>
				<dd>
					NA
				</dd>
				<dt>Demurrage</dt>
				<dd>
					<input type="number" class="form-control" step="0.01" name="demurrage" value="<?php echo $row->demurrage?>" id="_<?php echo $key; ?>DEM" readonly>
					<div class="row <?php echo (isset($row->status_type["finished"])) ? "hide" : ""; ?>">
						<div class="col-xs-12 col-sm-6">
							<input id="acceptTerms-dem<?php echo $key; ?>" class="checkbox-edit-demurrage" type="checkbox" name="inputDemurrage" data-inputid="_<?php echo $key; ?>DEM">
	        				<label for="acceptTerms-dem<?php echo $key; ?>">Edit Demurrage</label> 
						</div>
						<div class="col-xs-12 col-sm-6">
							<div class="text-right">
								<a href="javascript:void(0);" class="btn btn-xs btn-primary btn-compute-demurrage" style="margin-top: 2px;" data-inputid="_<?php echo $key; ?>DEM" data-date1="<?php echo $row->delivery_time; ?>">Compute Demurrage</a>
							</div>
						</div>
					</div>
				</dd>
				<dt>VAT</dt>
				<dd><input type="number" class="form-control" step="0.01" value="<?php echo $row->vat?>" name="vat" ></dd>
				<dt>Total Price</dt>
				<dd><input type="number" class="form-control" step="0.01" value="<?php echo $row->total_price; ?>" name="total_price" ></dd>
			</dl>

			<div class="text-right <?php echo (isset($row->status_type["finished"]) OR isset($row->status_type["cancel"]) ) ? "hide" : ""; ?>">
				<a href="javascript:void(0);" data-id="<?php echo $row->jobs_id; ?>" data-type="finished" class="btn btn-primary btn-xs btnUpdate <?php echo (isset($row->status_type["finished"])) ? "hide" : "" ; ?>" >Complete</a>
			</div>
		</form>
	</td>
</tr>
