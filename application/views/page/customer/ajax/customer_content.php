<div class="row">
								<div class="col-xs-12 col-lg-6">
									<form>
						 				<dl>
						 					<dt>Customer Name</dt>
						 					<dd><a href="<?php echo site_url("app/customer/jobs/".$row->job_parent_id); ?>"><?php echo $row->company_name; ?></a></dd>
						 					<dt>Job Name</dt>
						 					<dd><a href="<?php echo site_url("app/customer/jobs/".$row->job_parent_id.'/'.urlencode($row->job_name)); ?>"><?php echo $row->job_name; ?></a></dd>
						 					<div class="collapse" id="viewMoreCollapse_<?php echo $row->jobs_id; ?>">
						 						<dt>Date Created</dt>
							 					<dd><?php echo $row->created; ?></dd>  
							 					<dt>Telephone</dt>
							 					<dd><a href="tel:<?php echo $row->telephone; ?>"><?php echo $row->telephone; ?></a></dd>
							 					<dt>Purchase Order Number</dt>
								 				<dd><?php echo $row->job_po_number; ?></dd>
							 					<dt>Destination</dt>
							 					<dd><?php echo $row->address; ?></dd>
							 					<dt>Destination Zip Code</dt>
							 					<dd><?php echo $row->zip_code; ?></dd>
							 					<dt>Load Site</dt>
							 					<dd><?php echo $row->load_site; ?></dd>
							 					<dt>Load Site Zip Code</dt>
							 					<dd><?php echo $row->zip_code_load_site; ?></dd>
							 					<dt>Special Notes</dt>
							 					<dd><?php echo $row->job_notes; ?></dd>
							 					<?php if($this->session->userdata("special_account_type")) : ?>
							 						<dt>Division</dt>
							 						<dd><?php echo $row->division; ?></dd>
							 						<dt>Build/Dismantle/JTJ</dt>
							 						<dd><?php echo $row->build_dismantle; ?></dd>
							 						<dt>Vehicle Type</dt>
							 						<dd><?php echo $row->arctic_type; ?></dd>
							 					<?php endif; ?>
							 					<?php if(isset($row->status_type["allocated"])) : ?>
								 					<dt>Truck</dt>
													<dd><?php echo $row->vehicle_number; ?></dd>
													<dt>Driver</dt>
													<dd><?php echo $row->driver_name; ?></dd>
													<dt>Trailer</dt>
													<dd><?php echo $row->trailer_number; ?></dd>
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
						 					<dt>Book Time</dt>
						 					<dd class="created"><?php echo $row->status_type["new"]->created ; ?></dd>
						 					<dt>Updated</dt>
						 					<dd class="updated"><?php echo $row->status_type["new"]->updated ; ?></dd>
						 					<dt>Updated By</dt>
						 					<dd class="updated_by"><?php echo $row->status_type["new"]->name; ?></dd>
						 					<dt>Loading Time</dt>
						 					<dd><?php echo $row->loading_time; ?></dd>
						 					<dt>Delivery Time</dt>
						 					<dd><?php echo $row->delivery_time; ?></dd>
						 					<dt>Type of Truck</dt>
						 					<dd><?php echo $row->type_of_truck; ?></dd>
						 					<?php if($row->type_of_truck == "ARCTIC") : ?>
						 						<dt>Artic Type</dt>
						 						<dd><?php echo $row->arctic_type; ?></dd>
						 					<?php endif; ?>
						 				</dl>

						 				<div class="text-left ">
											<a href="javascript:void(0);" data-id="<?php echo $row->jobs_id; ?>" data-type="new_customer" class="btn btn-primary btnUpdateJobs">Update</a>
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
						 					<dt>Truck</dt>
						 					<dd><?php echo $row->vehicle_number; ?></dd>
						 					<dt>Driver</dt>
						 					<dd><?php echo $row->driver_name; ?></dd>
						 					<dt>Trailer</dt>
						 					<dd><?php echo $row->trailer_number; ?></dd>
						 					<dt>Driver Notes</dt>
						 					<dd class="font-italic col-green">
												<?php echo ($row->driver_notes) ? $row->driver_notes : "NA" ; ?>
											</dd>
						 				</dl>
						 			</form>
				
						 			<form class="last_form <?php echo ($row->new_type == "complete" OR $row->new_type == "cancel" OR $row->new_type == "allocated") ?  "" : "hide"; ?>">
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
						 						}else{
						 							echo $row->status_type['allocated']->updated;
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
						 						}else{
						 							echo $row->status_type['allocated']->name;
						 						}

						 					?></dd>
						 					<?php if($row->new_type == "cancel") : ?>
						 						<dt>Notes</dt>
						 						<dd class="font-italic col-pink"><?php echo $row->cancel_notes; ?></dd>			
						 					<?php endif; ?>
						 					<dt>Time of Arrival ( Load Site ) </dt>
						 					<dd><?php echo $row->job_dates['A']->arrived_time; ?></dd>
						 					<dt>Loading Time ( Load Site ) </dt>
						 					<dd><?php echo $row->job_dates['A']->pick_up_time; ?></dd>
						 					<dt>Time of Arrival ( Destination ) </dt>
						 					<dd><?php echo $row->job_dates['B']->arrived_time; ?></dd>
						 					<dt>Unloading Time ( Destination ) </dt>
						 					<dd><?php echo $row->job_dates['B']->pick_up_time; ?></dd>
						 				</dl>
						 			</form>
								</div>
							</div>  