<?php foreach($result as $key => $row) : ?>
	<tr>
		<td>
			<div class="panel-group panel-append-here custom-thumbnails" role="tablist" aria-multiselectable="false" style="margin-bottom: 5px !important;">
				<div class="panel panel-col-<?php echo $row->new_color; ?> collapsePanelLength" id="_panel_<?php echo $row->jobs_id; ?>">
					<div class="panel-heading" role="tab" id="headingTwo_<?php echo $row->jobs_id; ?>">
						<h4 class="panel-title">
							<a  class="cParent collapsed" role="button" data-toggle="collapse" data-parent="#accordion_<?php echo $row->jobs_id; ?>" href="#collapseTwo_<?php echo $row->jobs_id; ?>"  data-loaded="false" data-id="<?php echo $row->jobs_id; ?>"  aria-expanded="false" aria-controls="collapseTwo_<?php echo $row->jobs_id; ?>">
								<div class="row">
									<div class="col-lg-6 col-xs-12" style="margin-bottom: 0px !important;">
										
										<div class="row">

												<div class="col-xs-12 col-lg-6 job-title" style="margin-bottom: 0px !important;display: inline-block;">
													
													<?php if(!isset($row->status_type["finished"]) OR !isset($row->status_type["cancel"])) : ?>
														<span class="label label-danger btnUpdate" title="Cancel the Job" data-id="<?php echo $row->jobs_id; ?>" data-type="cancel" style="position: relative;top: -2px !important;padding: 5px;" >Cancel</span>
													<?php else : ?>
														<span class="label bg-blue-grey text-capitalize legend_name" style="position: relative;top: -2px !important;padding: 5px 7px;">
										
														<?php 
															$str = str_replace("_", " ", $row->new_type);
															
															switch ($str) {
																case 'To Be Allocated':
																	echo "Truck To Be Allocated";
																	break;
																case 'Allocated':
																	echo "Truck Allocated";
																	break;
																default:
																	echo $str;
																	break;
															}

														?>
														</span> 
													<?php endif; ?>
													<span title="Click To View Job Information" >
														<small style="color:white;line-height: 20px;" >
															<i class="material-icons" style="float: none;top: 5px;left: 5px;position: relative;"><?php echo $row->new_icon;?></i>
															<span style="position: relative;top: -2px;"><?php echo $row->job_name; ?> <?php echo $row->job_number; ?></span>
														</small>
													</span>
												</div>
											

											<div class="col-xs-12 col-lg-6 button-row" style="margin-bottom: 0px !important;">
												<?php if(!isset($row->status_type['cancel'])) : ?>	
												<span class="label bg-blue-grey text-capitalize legend_name" style="position: relative;top: 0px !important; padding: 5px">
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
												

												<span class="<?php echo $row->status_job_class; ?>" title="<?php echo $row->status_message; ?>">
														<i class="material-icons"><?php echo $row->status_job_icon; ?></i>
												</span>

											</div>

										</div>
									</div>

									<div class="col-lg-6 col-xs-12 text-right date" style="margin-bottom: 0px !important;">
										<span><small style="color:white;">Pick Up Date : <strong><?php echo $row->loading_time; ?></strong></small></span> <div class="visible-xs visible-sm"></div>
										<span><small style="color:white;">Delivery Date : <strong><?php echo $row->delivery_time; ?></strong></small></span>
										<?php if($row->j_status == 0 OR $row->j_status == 1) : ?>
											<span>
												<button style="padding:0 12px;position:relative;font-size: 17px;font" class="btn btn-info j_status_btn" data-type="1" data-id="<?php echo $row->jobs_id; ?>">&#63;</button>  
											</span>
										<?php elseif($row->j_status == 2) : ?>
											<span>
												<button style="padding:3px 0 7px 7px;" class="btn btn-success j_status_btn" data-type="2" data-id="<?php echo $row->jobs_id; ?>"><i class="material-icons" style="position: relative;font-size: 16.5px;" >check</i></button>  
											</span>
										<?php else : ?>
											<span>
												<button style="padding:3px 0 7px 7px;" class="btn btn-danger j_status_btn" data-type="3" data-id="<?php echo $row->jobs_id; ?>"><i class="material-icons" style="position: relative;font-size: 16.5px;">clear</i></button>  
											</span>
										<?php endif; ?>
									</div>

								</div>
							</a>
						</h4>
					</div>
					<div id="collapseTwo_<?php echo $row->jobs_id; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo_<?php echo $row->jobs_id; ?>">
						<div class="panel-body">
							
						</div>
					</div>
				</div>
			</div>
		</td>
	</tr>
<?php endforeach; ?>