
<style type="text/css">
	#tyre_table td{
		text-align: center; 
		vertical-align: middle;
	}
</style>
<div class="row">
	<div class="col-lg-4 col-xs-12">
		<table width="100%" style="margin-bottom: 20px;">
			<tr>
				<th style="width: 100px;">Report #</th>
				<td><?php echo $result->report_id; ?></td>
			</tr>
			<tr>
				<th style="width: 100px;">Operator</th>
				<td><?php echo $result->operator; ?></td>
			</tr>								
			<tr>
				<th style="width: 100px;">Mileage In</th>
				<td><?php echo $result->mileage_in; ?></td>
			</tr>
			<tr>
				<th style="width: 100px;">Mileage Out</th>
				<td><?php echo $result->mileage_out; ?></td>
			</tr>
			<tr>
				<th style="width: 100px;">Reg. No.</th>
				<td><?php echo $result->registration_no; ?></td>
			</tr>
			<tr>
				<th style="width: 100px;">Fleet No.</th>
				<td><?php echo $result->fleet_no; ?></td>
			</tr>
			<tr>
				<th style="width: 100px;">Make & Type</th>
				<td><?php echo $result->make_type; ?></td>
			</tr>
		</table>
	</div>

	<div class="col-lg-8 col-xs-12">
		<div class="table-responsive">
			<table width="100%" class="table table-bordered">
				<thead>
					<tr>
						<th style="text-align: center;">Status</th>
						<th style="text-align: center;">Fixed By</th>
						<th style="text-align: center;">Comment</th>
						<th style="text-align: center;">Created</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($result->mechanic_status as $key => $value) : ?>
						<tr>
							<td style="text-align: center;"><?php echo $value->status; ?></td>
							<td><?php echo $value->name; ?></td>
							<td><?php echo $value->comment; ?></td>
							<td style="text-align: center; width: 135px;"><?php echo $value->created; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>

	<div class="col-lg-12 col-xs-12">
		<div class="table-responsive">
			<table width="100%" class="table table-bordered">
				<thead >
					<tr>
						<th style="width: 70px">IM No.</th>
						<th></th>
						<th style="width: 70px">Serviceable</th>
					</tr>
				</thead>
				<tbody>

					<?php foreach($checklist as $index => $value): ?>
					<tr>
						<td style="text-align: center;"><?php echo $index + 1; ?></td>
						<td><?php echo $value->checklist_index; ?></td>

						<?php if($value->value == "SERVICEABLE") : ?>
							<td style="text-align: center;"><?php echo "✔"; ?></td>
						<?php elseif($value->value == "DEFECT") : ?>
							<td style="text-align: center;"><?php echo "✖"; ?></td>
						<?php else: ?>
							<td style="text-align: center;"><?php echo "-"; ?></td>
						<?php endif; ?>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>

	<div class="col-lg-12 col-xs-12">
		<div class="table-responsive">
			<table width="100%" style="margin-bottom: 20px;" class="table table-bordered" id="tyre_table">
				    <tr>
				        <th colspan="12">TYRES</th>
				    </tr>
				    <tr>
				        <th colspan="6" style="text-align: left !important;">Tread Depth</th>
				        <th colspan="6" style="text-align: left !important;">Tyre Pressures</th>
				    </tr>
				    <tr>
				        <td rowspan="5"><strong>Front</strong></td>
				        <td rowspan="2" style="width: 90px;"><?php echo $result->thread_depth->b1; ?></td>
				        <td rowspan="2" style="width: 90px;"><?php echo $result->thread_depth->b3; ?></td>
				        <td><?php echo $result->thread_depth->mb1; ?></td>
				        <td><?php echo $result->thread_depth->mb5; ?></td>
				        <td style="width: 50px;"><strong>Spare</strong></td>
				        <td rowspan="5"><strong>Front</strong></td>
				        <td rowspan="2" style="width: 90px;"><?php echo $result->tyre_pressure->b1; ?></td>
				        <td rowspan="2" style="width: 90px;"><?php echo $result->tyre_pressure->b3; ?></td>
				        <td><?php echo $result->tyre_pressure->mb1; ?></td>
				        <td><?php echo $result->tyre_pressure->mb5; ?></td>
				        <td style="width: 50px;"><strong>Spare</strong></td>
				    </tr>
				    <tr>
				        <td><?php echo $result->thread_depth->mb2; ?></td>
				        <td><?php echo $result->thread_depth->mb6; ?></td>
				        <td rowspan="4"><?php echo ($result->thread_depth->spare_tyre == 'YES') ? "✔" : "✖"; ?></td>
				        <td><?php echo $result->tyre_pressure->mb2; ?></td>
				        <td><?php echo $result->tyre_pressure->mb6; ?></td>
				        <td rowspan="4"><?php echo ($result->tyre_pressure->spare_tyre == 'YES') ? "✔" : "✖"; ?></td>
				    </tr>
				    <tr>
				        <td colspan="4"></td>
				        <td colspan="4"></td>
				    </tr>
				    <tr>
				        <td rowspan="2" style="width: 90px;"><?php echo $result->thread_depth->b2; ?></td>
				        <td rowspan="2" style="width: 90px;"><?php echo $result->thread_depth->b4; ?></td>
				        <td><?php echo $result->thread_depth->mb3; ?></td>
				        <td><?php echo $result->thread_depth->mb7; ?></td>
				        <td rowspan="2"><?php echo $result->tyre_pressure->b2; ?></td>
				        <td rowspan="2"><?php echo $result->tyre_pressure->b4; ?></td>
				        <td><?php echo $result->tyre_pressure->mb3; ?></td>
				        <td><?php echo $result->tyre_pressure->mb7; ?></td>
				    </tr>
				    <tr>
				        <td><?php echo $result->thread_depth->mb4; ?></td>
				        <td><?php echo $result->thread_depth->mb8; ?></td>
				        <td><?php echo $result->tyre_pressure->mb4; ?></td>
				        <td><?php echo $result->tyre_pressure->mb8; ?></td>				        
				    </tr>
			</table>
		</div>
	</div>
</div>


<?php if($result->report_images) : ?>
	<div>
		<legend>Image</legend>
		<div class="list-unstyled clearfix">
			<div class="row">
				<?php foreach($result->report_images as $image): ?>
					<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
						<a href="<?php echo $image->images; ?>" data-sub-html="Report # <?php echo $result->report_id; ?>">
							<img class="img-responsive thumbnail" src="<?php echo $image->images; ?>" >
						</a>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
<?php endif;?>
