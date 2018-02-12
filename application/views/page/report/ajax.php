<?php foreach ($result as $key => $res): ?>

	<div class="row clearfix append-last">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card ">
				<div class="header 
				<?php if($res->report_type == 'DEFECT'): ?>
					bg-red
				<?php elseif($res->report_type == 'SERVICEABLE') :?>
					bg-light-green
				<?php else: ?>
					bg-orange
				<?php endif;?>	
				">

					<?php if($res->report_type == 'DEFECT') : ?>
						<h2>Defect Report</h2>
					<?php elseif($res->report_type == 'SERVICEABLE'): ?>	
						<h2>Serviceable Report</h2>
					<?php else:?>
						<h2>Incomplete Report</h2>
					<?php endif; ?>

				</div>
				<div class="body">
				<div class="row">
						
					<div class="col-lg-6 col-xs-12">
						<table width="100%;" class="table-bordered">
							<tr>
								<th>Report #</th>
								<td><?php echo $res->id; ?></td>
							</tr>
							<tr>
								<th>Driver</th>
								<td><?php echo $res->name.' '.$res->surname; ?></td>
							</tr>								<tr>
								<th>Vehicle</th>
								<td><?php echo $res->vehicle_registration_number; ?></td>
							</tr>
							<tr>
								<th>Trailer Number</th>
								<td><?php echo $res->trailer_number; ?></td>
							</tr>
						</table>
					</div>

					<div class="col-lg-6 col-xs-12">
						<table width="100%;" class="table-bordered">
							<tr>
								<th>Start Mileage</th>
								<td><?php echo $res->start_mileage; ?></td>
								<?php if($res->report_type == 'SERVICEABLE') : ?>
								
							</tr>
							<tr>
								<th>End Mileage</th>
								<td><?php echo $res->end_mileage; ?></td>
							</tr>
							<tr>
								<th>Distance Travelled</th>
								<td><?php echo $res->mileage; ?></td>
								<?php endif; ?>
							</tr>
							<tr>
								<th>Created</th>
								<td><?php echo $res->created; ?></td>
							</tr>
						</table>
					</div>

				</div>

					<div class="row">
						<div class="col-lg-12 col-xs-12">
							<?php if($res->report_type == 'DEFECT') : ?>
								<table class="table table-bordered">
									<thead>
										<tr>
											<th>Status</th>
											<th>Description</th>
											<th>Created</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($res->status as $row): ?>
											<tr>
												<td><?php echo $row->status; ?></td>
												<td><?php echo $row->comment; ?></td>
												<td><?php echo $row->created; ?></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
								<?php if($res->status[0]->status != 3) : ?>
									<div class="text-right">
										<a href="javascript:void(0);" data-id="<?php echo $res->id; ?>" data-href="<?php echo site_url('app/reports/getReportById/'.$res->id) ?>" class="btn btn-info viewModal">Update</a>
									</div>
								<?php endif; ?>
								<br>
							<?php endif; ?>


								<div class="row">
									<div class="col-lg-12 col-xs-12">

										<h4>Checklist</h4>

										<table class="table">
										<?php foreach($res->all_checklist as $key => $checklist) : ?>	
												<?php if($key % 2 == 0) : ?>
													<tr>
														<td class="<?php echo ($checklist['value'] == "DEFECT") ? "bg-red" : "";  ?>"><?php echo $checklist['checklist_index']; ?></td>
														<td class="<?php echo ($checklist['value'] == "DEFECT") ? "bg-red" : "";  ?>"><div class="badge bg-orange"><?php echo $checklist['checklist_timer']; ?></div> </td>
													<?php else : ?>
														<td class="<?php echo ($checklist['value'] == "DEFECT") ? "bg-red" : "";  ?>"><?php echo $checklist['checklist_index']; ?></td>
														<td class="<?php echo ($checklist['value'] == "DEFECT") ? "bg-red" : "";  ?>"><div class="badge bg-orange"><?php echo $checklist['checklist_timer']; ?></div> </td>
													</tr>
												<?php endif; ?>
											<?php endforeach; ?>
										</table>	

									</div>
								</div>

							</div>
						</div>
					</div>
					<div>
						<?php if($res->images) : ?>
							<legend>Image</legend>
							<div class="list-unstyled clearfix aniimated-thumbnials">
								<div class="row">
									<?php foreach($res->images as $image): ?>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" style="display: inline-block;">
											<a href="<?php echo $image->images; ?>" data-sub-html="Report # <?php echo $res->id; ?>">
												<img class="img-responsive thumbnail lazy" src="<?php echo $image->image_thumb; ?>" >
											</a>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>

<?php endforeach; ?>