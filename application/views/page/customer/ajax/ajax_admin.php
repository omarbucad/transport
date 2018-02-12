<?php foreach($result as $key => $row) : ?>
	<tr>
		<td>
			<div class="panel-group panel-append-here custom-thumbnails" role="tablist" aria-multiselectable="false" style="margin-bottom: 5px !important;">
				<div class="panel panel-col-<?php echo $row->new_color; ?> collapsePanelLength" id="_panel_<?php echo $row->jobs_id; ?>">
					<div class="panel-heading" role="tab" id="headingTwo_<?php echo $row->jobs_id; ?>">
						<h4 class="panel-title">
							<a class="cParent collapsed" role="button" data-toggle="collapse" data-parent="#accordion_<?php echo $row->jobs_id; ?>" href="#collapseTwo_<?php echo $row->jobs_id; ?>" data-loaded="false" data-id="<?php echo $row->jobs_id; ?>" aria-expanded="false" aria-controls="collapseTwo_<?php echo $row->jobs_id; ?>">
								<div class="row">
									<div class="col-lg-6 col-xs-12" style="margin-bottom: 0px !important;">
										<div class="row">
												<div class="col-xs-12 col-lg-6 job-title" style="margin-bottom: 0px !important;display: inline-block;">
													<?php if($row->ready_to_go == 6 OR isset($row->status_type["finished"]) AND $row->confirmed_cancel == 1) : ?>
														<span class="label bg-blue-grey text-capitalize legend_name not_click" style="position: relative;top: -2px !important;padding: 5px;margin-right: 5px;">
															Cancel
														</span> 
													<?php elseif(!isset($row->status_type["cancel"])) : ?>
														<span class="label label-danger btnUpdate not_click" title="Cancel the Job" data-id="<?php echo $row->jobs_id; ?>" data-type="cancel" style="position: relative;top: -2px !important;padding: 5px;margin-right: 5px;" >Cancel</span>
													<?php elseif(isset($row->status_type['cancel']) AND $row->confirmed_cancel == 0) : ?>
														<span class="label bg-light-blue btnUpdate not_click" data-type="cancel" title="Confirm the Job" data-id="<?php echo $row->jobs_id; ?>" style="position: relative;top: -2px !important;padding: 5px;" >Confirm Cancel</span>		
													<?php else : ?>
														<span class="label bg-blue-grey text-capitalize legend_name" style="position: relative;top: -2px !important;padding: 5px 7px;">
															<?php 
															$str = str_replace("_", " ", $row->new_type);

															switch ($str) {
																case 'to be allocated':
																	echo "Truck To Be Allocated";
																break;
																case 'allocated':
																	echo "Truck Allocated";
																break;
																default:
																	echo $str;
																break;
															}

															?>
														</span> 
													<?php endif; ?>
													<span title="Click To View Job Information" class="cChild">
														<small style="color:white;line-height: 20px;" >
															<i class="material-icons" style="float: none;top: 5px;left: 5px;position: relative;"><?php echo $row->new_icon;?></i>
															<span style="position: relative;top: -2px;"><?php echo $row->job_name; ?> <?php echo $row->job_number; ?></span>
														</small>
													</span>
												</div>
											

											<div class="col-xs-12 col-lg-6 button-row" style="margin-bottom: 0px !important;">
												<?php if(!isset($row->status_type['cancel'])) : ?>	
												<span class="label bg-blue-grey text-capitalize legend_name" style="position: relative;top: 0px !important;padding: 5px;">
													<?php 
													if($row->with_outsource){
														echo $row->outsource_name;
													}else{
														$str = str_replace("_", " ", $row->new_type);

														switch ($str) {
															case 'to be allocated':
																echo "Truck To Be Allocated ".$row->driver_status;
															break;
															case 'allocated':
																echo "Truck Allocated " .$row->driver_status;
															break;
															default:
																echo $str.$row->driver_status;
															break;
														}
													}
													?>
												</span> 
												<?php endif; ?>

												<span class="<?php echo $row->status_job_class; ?>" data-select="<?php echo $row->ready_to_go; ?>" data-jobid="<?php echo $row->jobs_id; ?>" title="<?php echo $row->status_message; ?>">
													<i class="material-icons not_click"><?php echo $row->status_job_icon; ?></i>
												</span>

											</div>

										</div>
									</div>
									<div class="col-lg-6 col-xs-12 text-right date" style="margin-bottom: 0px !important;">
										<span><small style="color:white;">Pick Up Date : <strong><?php echo $row->loading_time; ?></strong></small></span> <div class="visible-xs visible-sm"></div>
										<span><small style="color:white;">Delivery Date : <strong><?php echo $row->delivery_time; ?></strong></small></span>
										
										<span class="<?php echo ($row->j_status == 0) ? "" : "hide"; ?>">
											<span>
												<button style="padding:0 12px;position:relative;font-size: 17px;font" class="btn btn-info j_status_btn" data-type="1" data-id="<?php echo $row->jobs_id; ?>">&#63;</button>  
											</span>
										</span>
										<span class="<?php echo ($row->j_status > 0) ? "" : "hide"; ?>">
											<?php if($row->j_status == 1 OR $row->j_status == 0) : ?>
												<span>
													<button style="padding:3px 0 7px 7px;" class="btn btn-success j_status_btn" data-load="true" data-type="2" data-id="<?php echo $row->jobs_id; ?>"><i class="material-icons" style="position: relative;font-size: 16.5px;" >check</i></button>  
												</span>
												<span>
													<button style="padding:3px 0 7px 7px;" class="btn btn-danger j_status_btn" data-load="true" data-type="3" data-id="<?php echo $row->jobs_id; ?>"><i class="material-icons" style="position: relative;font-size: 16.5px;">clear</i></button>  
												</span>

											<?php else : ?>
												<span class="<?php echo ($row->j_status == 2 ) ? "" : "hide"; ?>">
													<button style="padding:3px 0 7px 7px;" class="btn btn-success j_status_btn" data-load="true" data-type="2" data-id="<?php echo $row->jobs_id; ?>"><i class="material-icons" style="position: relative;font-size: 16.5px;" >check</i></button>  
												</span>
												<span class="<?php echo ($row->j_status == 3) ? "" : "hide"; ?>">
													<button style="padding:3px 0 7px 7px;" class="btn btn-danger j_status_btn" data-load="true" data-type="3" data-id="<?php echo $row->jobs_id; ?>"><i class="material-icons" style="position: relative;font-size: 16.5px;">clear</i></button>  
												</span>
											<?php endif; ?>
										</span>

									           
									</div>
								</div>
							</a>
						</h4>
					</div>
					<div id="collapseTwo_<?php echo $row->jobs_id; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo_<?php echo $row->jobs_id; ?>" >
						<div class="panel-body">
							
								        
						</div>
					</div>
				</div>
			</div>
		</td>
	</tr>
<?php endforeach; ?>