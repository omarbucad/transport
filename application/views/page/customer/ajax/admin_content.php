<div class="row">
	<div class="col-xs-12 col-lg-6">
		<form>
			<dl>
				<dt>Job #</dt>
				<dd><?php echo $row->jobs_id; ?></dd>
				<dt>Customer Name</dt>
				<dd><a href="<?php echo site_url("app/customer/jobs/".$row->job_parent_id); ?>"><?php echo $row->company_name; ?></a></dd>
				<dt>Created By</dt>
				<dd><?php echo $row->created_by; ?></dd>
				<dt>Job Name</dt>
				<dd><?php echo $row->job_name; ?></dd>
				<div class="collapse" id="viewMoreCollapse_<?php echo $row->jobs_id; ?>">
					<dt>Date Created</dt>
					<dd><?php echo $row->created; ?></dd>  
					<dt>Telephone</dt>
					<dd><a href="tel:<?php echo $row->telephone; ?>"><?php echo ifNA($row->telephone); ?></a></dd>
					<dt>Purchase Order Number</dt>
					<dd><?php echo ifNA( $row->job_po_number); ?></dd>
					<dt>Destination</dt>
					<dd><?php echo $row->address; ?></dd>
					<dt>Destination Zip Code</dt>
					<dd><?php echo $row->zip_code; ?></dd>
					<dt>Load Site</dt>
					<dd><?php echo $row->load_site; ?></dd>
					<dt>Load Site Zip Code</dt>
					<dd><?php echo $row->zip_code_load_site; ?></dd>
					<dt>Job Notes</dt>
					<dd><?php echo $row->job_notes; ?></dd>
					<dt>Special Notes</dt>
					<dd><?php echo $row->notes; ?></dd>
					
					<?php if(isset($row->status_type["to_be_allocated"])) : ?>
						<dt>Price</dt>
						<dd><?php echo formatMoney($row->price); ?></dd>
						<dt>Outsource?</dt>
						<dd><?php echo ($row->with_outsource) ?  '<span class="font-italic col-green">Yes</span>' : '<span class="font-italic col-pink">No</span>'; ?></dd>
						<?php if($row->with_outsource) : ?>
							<dt>Outsource Company Name</dt>
							<dd><?php echo $row->outsource_name; ?></dd>
							<dt>Outsource Price</dt>
							<dd><?php echo formatMoney($row->outsource_price); ?></dd>
							<dt>Keep Price</dt>
							<dd><?php echo formatMoney($row->price - $row->outsource_price); ?></dd>
						<?php endif; ?>
					<?php endif; ?>
					<?php if(isset($row->status_type["allocated"])) : ?>
						<dt>Truck</dt>
						<dd><?php echo ($row->with_outsource) ? $row->outsource_truck : $row->vehicle_number; ?></dd>
						<dt>Driver</dt>
						<dd><?php echo ($row->with_outsource) ? $row->outsource_driver_name : $row->driver_name; ?></dd>
						<dt>Trailer</dt>
						<dd><?php echo $row->trailer_number; ?></dd>
						<dt>Load Trailer</dt>
						<dd><?php echo $row->load_trailer; ?></dd>
					<?php endif; ?>
					<?php if(isset($row->status_type["partially_complete"])) : ?>
						<dt>Map</dt>
						<dd class="font-italic col-green"><a href="javascript:void(0);" class="view_map" data-longitudeA="<?php echo $row->job_dates['A']->longitude; ?>" data-latitudeA="<?php echo $row->job_dates['A']->latitude; ?>" data-longitudeB="<?php echo $row->job_dates['B']->longitude; ?>" data-latitudeB="<?php echo $row->job_dates['B']->latitude; ?>" data-driver="<?php echo $row->driver_name; ?>">View Map</a></dd>
					<?php endif; ?>
					<?php if($row->images) : ?>
						<dt>Images</dt>
						<dd>
							<?php foreach($row->images as $k => $r) : ?>
								<a href="<?php echo $r->image; ?>" data-sub-html="<?php echo $row->job_name; ?>" class="c-thumbnails">
									<img class="img-responsive thumbnail lazy" src="<?php echo $r->image_thumb; ?>" >
								</a>
							<?php endforeach; ?>
						</dd>
					<?php endif; ?>
				</div>
			</dl>	
			<a class="view_more_btn" data-toggle="collapse" href="#viewMoreCollapse_<?php echo $row->jobs_id; ?>" aria-expanded="false" aria-controls="viewMoreCollapse_<?php echo $row->jobs_id; ?>">View More</a>
		</form>
	</div>
	<div class="col-xs-12 col-lg-6">
		<form class="to_be_allocated_form <?php echo ($row->new_type == "new") ? "" : "hide"; ?>">
			<input type="hidden" name="status_type" value="to_be_allocated">
			<input type="hidden" name="jobs_id">
			<dl>
				<dt>Loading Time</dt>
				<dd><?php echo $row->loading_time; ?></dd>
				<dt>Delivery Time</dt>
				<dd><?php echo $row->delivery_time; ?></dd>
				<dt>Type of Truck</dt>
				<dd><?php echo $row->type_of_truck; ?></dd>
				<?php if($row->type_of_truck == "ARTIC") : ?>
					<dt>Artic Type</dt>
					<dd><?php echo $row->arctic_type; ?></dd>
				<?php endif; ?>
				<dt>Price</dt>
				<dd><input type="number" class="form-control" step="0.01" name="price" value="<?php echo $row->price; ?>"></dd>
				<dt>&nbsp;</dt>
				<dd>
					<input id="with_outsource-dem<?php echo $row->jobs_id; ?>" class="checkbox-with_outsouce" type="checkbox" <?php echo ($row->with_outsource) ? 'checked=""' : "" ;?> name="with_outsource" value="1">
					<label for="with_outsource-dem<?php echo $row->jobs_id; ?>">Outsource?</label> 
				</dd>
				<dt class="outsource hide">Outsourced Company Name</dt>
				<dd class="outsource hide">
					<select class="form-control show-tick" name="outsource_company_name" data-live-search="true" required>
						<?php foreach($outsource_list as $list) : ?>
							<option value="<?php echo $list->id; ?>"><?php echo $list->company_name; ?></option>
						<?php endforeach; ?>
					</select>	
				</dd>
				<dt class="outsource hide">Outsource Price</dt>
				<dd class="outsource hide">
					<input type="number" class="form-control" step="0.01" name="outsource_price"  value="<?php echo $row->outsource_price; ?>">
				</dd>
			</dl>
			<div class="text-right">
				<a href="javascript:void(0);" data-id="<?php echo $row->jobs_id; ?>" data-type="first" class="btn btn-default btnUpdateJobs"><i class="material-icons" style="font-size: 16px;">update</i> Update</a>
				<a href="javascript:void(0);" data-loading-text="Loading..." data-id="<?php echo $row->jobs_id; ?>" data-type="to_be_allocated" class="btn btn-primary btnUpdate"><i class="material-icons" style="font-size: 16px;">done_all</i> Submit</a>
			</div>
		</form>
		
		<form class="allocated_form <?php echo ($row->new_type == "to_be_allocated") ? "" : "hide"; ?>">
			<input type="hidden" name="status_type" value="allocated">
			<input type="hidden" name="jobs_id">
			<dl>
				<dt>Book Time</dt>
				<dd class="created"><?php echo $row->status_type["new"]->created; ?></dd>
				<dt>Updated</dt>
				<dd class="updated"><?php echo (isset($row->status_type['to_be_allocated'])) ? $row->status_type["to_be_allocated"]->updated : $row->status_type["new"]->updated ; ?></dd>
				<dt>Updated By</dt>
				<dd class="updated_by"><?php echo (isset($row->status_type['to_be_allocated'])) ? $row->status_type["to_be_allocated"]->name : $row->status_type["new"]->name ; ?></dd>
				<div class="hide_on_outsourced <?php echo ($row->with_outsource) ? "hide" : "" ; ?>">
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
					<dt>Load Trailer</dt>
					<dd>
						<select class="form-control show-tick" name="load_trailer">
							<option value="">-</option>
							<option value="Loaded at Arena">Loaded at Arena</option>
							<option value="Loaded at FPMcCann">Loaded at FPMcCann</option>
							<option value="Loaded at Arena">Loaded at Arena</option>
							<option value="Load and Go">Load and Go</option>
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

				</div>
				<div class="show_on_outsourced <?php echo (!$row->with_outsource) ? "hide" : "" ; ?>">
					<dt>Outsourced Vehicle Registration Number</dt>
					<dd><input type="text" class="form-control" name="outsource_truck" placeholder="please type here..."></dd>
					<dt>Outsourced Driver Name</dt>
					<dd><input type="text" class="form-control" name="outsource_driver_name"  placeholder="please type here..." ></dd>
				</div>
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
				<dd class="font-italic col-green">
					<?php echo ($row->driver_notes) ? $row->driver_notes : "NA" ; ?>
				</dd>
			</dl>
			<div class="hide_on_outsourced <?php echo ($row->with_outsource) ? "hide" : "" ; ?>">
				<input id="acceptTerms-<?php echo $row->jobs_id; ?>" name="displayToDriver" type="checkbox" <?php echo ($row->show_to_driver) ? "checked" : ""; ?>>
				<label for="acceptTerms-<?php echo $row->jobs_id; ?>">Send notes to driver?</label> 
			</div>
			<div class="row">
				<div class="col-xs-12 col-lg-6 text-left">
					<a href="javascript:void(0);" data-loading-text="Loading..." data-id="<?php echo $row->jobs_id; ?>" class="btn btn-danger bck-status-btn" >Back to new</a>
				</div>
				<div class="col-xs-12 col-lg-6 text-right">
					<a href="javascript:void(0);" data-id="<?php echo $row->jobs_id; ?>" data-type="new" class="btn btn-default btnUpdateJobs"><i class="material-icons" style="font-size: 16px;">update</i> Update</a>
					<a href="javascript:void(0);" data-loading-text="Loading..." data-id="<?php echo $row->jobs_id; ?>" data-type="allocated" class="btn btn-primary btnUpdate" ><i class="material-icons" style="font-size: 16px;">done_all</i> Submit</a>
				</div>
			</div>
		</form>

		<form class="last_form <?php echo ($row->new_type == "complete" OR $row->new_type == "cancel" OR $row->new_type == "allocated" OR $row->new_type == "for_confirmation") ?  "" : "hide"; ?>">
			<input type="hidden" name="status_type" value="finished">
			<input type="hidden" name="jobs_id">
			<dl>
				<dt>Book Time</dt>
				<dd class="created"><?php echo $row->status_type["new"]->created; ?></dd>
				<dt>Updated</dt>
				<dd class="updated"><?php 
					
					if(isset($row->status_type['cancel'])){
						echo $row->status_type['cancel']->updated;
					}else if(isset($row->status_type['finished'])){
						echo $row->status_type['finished']->updated;
					}else if(isset($row->status_type['partially_finished'])){
						echo $row->status_type['partially_finished']->updated;
					}else if(isset($row->status_type['allocated'])){
						echo $row->status_type['allocated']->updated;
					}else{
						echo $row->status_type['new']->updated;
					}

					?></dd>
					<dt>Updated By</dt>
					<dd class="updated_by"><?php 
						
						if(isset($row->status_type['cancel'])){
							echo $row->status_type['cancel']->name;
						}else if(isset($row->status_type['finished'])){
							echo $row->status_type['finished']->name;
						}else if(isset($row->status_type['partially_finished'])){
							echo $row->status_type['partially_finished']->name;
						}else if(isset($row->status_type['allocated'])){
							echo $row->status_type['allocated']->name;
						}else{
							echo $row->status_type['new']->name;
						}

						?></dd>
						<?php if($row->new_type == "cancel") : ?>
							<dt>Notes</dt>
							<dd class="font-italic col-pink"><?php echo $row->cancel_notes; ?></dd>		
						<?php else : ?>
							<dt>Loading Time</dt>
							<dd><?php echo $row->loading_time; ?></dd>
							<dt>Delivery Time</dt>
							<dd><?php echo $row->delivery_time; ?></dd>
							<div class="hide_on_outsourced <?php echo ($row->with_outsource) ? "hide" : "" ; ?>">
								<dt>Time of Arrival ( Load Site ) </dt>
								<dd><?php echo $row->job_dates['A']->arrived_time; ?></dd>
								<dt>Loading Time ( Load Site ) </dt>
								<dd><?php echo $row->job_dates['A']->pick_up_time; ?></dd>
								<dt>Mileage ( Load Site ) </dt>
								<dd><?php echo $row->job_dates['A']->start_mileage; ?></dd>
								<dt>Time of Arrival ( Destination ) </dt>
								<dd><?php echo $row->job_dates['B']->arrived_time; ?></dd>
								<dt>Unloading Time ( Destination ) </dt>
								<dd><?php echo $row->job_dates['B']->pick_up_time; ?></dd>
								<dt>Mileage ( Destination ) </dt>
								<dd><?php echo $row->job_dates['B']->start_mileage; ?></dd>
								<dt>Delivery Notes</dt>
								<dd class="font-italic col-green"><?php echo $row->delivery_notes; ?></dd>
								<dt>Customer Name</dt>
								<dd><?php echo $row->signature_name; ?></dd>
							</div>
							<div class="show_on_outsourced <?php echo (!$row->with_outsource) ? "hide" : "" ; ?>">
								<dt>Delivery Notes</dt>
								<dd><input type="text" class="form-control" value="<?php echo $row->delivery_notes; ?>" name="delivery_notes"></dd>
								<dt>Customer Name</dt>
								<dd><?php echo $row->signature_name; ?></dd>
							</div>
							<dt>Purchase Order Number</dt>
							<dd><input type="text" class="form-control"  value="<?php echo $row->job_po_number?>" name="job_po_number"></dd>
							<dt>Demurrage</dt>
							<dd>
								<input type="number" class="form-control compute_demurrage" step="0.01" name="demurrage" value="<?php echo $row->demurrage?>" id="_<?php echo $row->jobs_id; ?>DEM" readonly>
								<div class="row <?php echo (isset($row->status_type["finished"])) ? "hide" : ""; ?>">
									<div class="col-xs-12 col-sm-6 " style="margin-bottom: 0px !important;">
										<input id="acceptTerms-dem<?php echo $row->jobs_id; ?>" class="checkbox-edit-demurrage" type="checkbox" name="inputDemurrage" data-inputid="_<?php echo $row->jobs_id; ?>DEM">
										<label for="acceptTerms-dem<?php echo $row->jobs_id; ?>">Edit Demurrage</label> 
									</div>
								</div>
							</dd>
							<dt>VAT</dt>
							<dd><input type="number" class="form-control compute_vat" step="0.01" value="<?php echo $row->vat?>" name="vat" ></dd>
							<dt>Total Price</dt>
							<dd><input type="number" class="form-control _total_price compute_total_price" step="0.01" value="<?php echo $row->total_price; ?>" data-value="<?php echo $row->price; ?>" name="total_price"></dd>	
						<?php endif; ?>
					</dl>

					<div class="row">
						<div class="text-left col-xs-12 col-lg-4 ">
							<a href="javascript:void(0);" data-id="<?php echo $row->driver_id; ?>" class="btn btn-info btn-driver-instructions <?php echo ($row->driver_last_job != "ALL_COMPLETE") ? "hide" : ""; ?>">Send Driver Instructions</a>
						</div>
						<div class="text-right col-xs-12 col-lg-8 <?php echo (isset($row->status_type["cancel"]) /**OR isset($row->status_type["finished"]) */ ) ? "hide" : ""; ?>">
							<a href="javascript:void(0);" data-id="<?php echo $row->jobs_id; ?>" data-type="to_be_allocated" class="btn btn-default btnUpdateJobs <?php echo (isset($row->status_type["partially_complete"])) ? "hide" : ""; ?>">Update</a>
							<button type="button" data-loading-text="Loading..." data-id="<?php echo $row->jobs_id; ?>" data-type="finished" class="btn btn-primary btnUpdate <?php echo (isset($row->status_type["finished"])) ? "hide" : ""; ?>" >Complete</button>
						</div>
					</div>
				</form>
			</div>
		</div>         