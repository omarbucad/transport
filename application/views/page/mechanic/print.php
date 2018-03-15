
<style type="text/css">

	#tyre_table td{
		text-align: center; 
		vertical-align: middle;
	}
</style>
<div class="row">
	<div class="col-lg-5 col-xs-12">
		<table width="100%" style="margin-bottom: 20px; text-align: left;">
			<tr>
				<th style="width: 100px;">Report #</th>
				<td><?php echo $result->report_id; ?></td>
				<th style="width: 100px;">Operator</th>
				<td><?php echo $result->operator; ?></td>
			</tr>								
			<tr>
				<th style="width: 100px;">Mileage In</th>
				<td><?php echo $result->mileage_in; ?></td>
				<th style="width: 100px;">Mileage Out</th>
				<td><?php echo $result->mileage_out; ?></td>
			</tr>
			<tr>
				<th style="width: 100px;">Reg. No.</th>
				<td><?php echo $result->registration_no; ?></td>
				<th style="width: 100px;">Fleet No.</th>
				<td><?php echo $result->fleet_no; ?></td>
			</tr>
			<tr>
				<th style="width: 100px;">Make & Type</th>
				<td><?php echo $result->make_type; ?></td>
			</tr>
			<tr>
				<th style="width: 100px;">Created</th>
				<td><?php echo $result->created; ?></td>
			</tr>
		</table>
	</div>

	<div class="col-lg-8 col-xs-12" style="margin-bottom: 40px;">
		<div class="table-responsive">
			<table width="100%" class="table table-bordered" border="1" style="border-collapse: collapse;">
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
							<td style="text-align: center;width: 100px;"><?php echo $value->status; ?></td>
							<td style="padding-left: 5px;"><?php echo $value->name; ?></td>
							<td style="padding-left: 5px;"><?php echo $value->comment; ?></td>
							<td style="text-align: center; width: 135px;"><?php echo $value->created; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>

	<div class="col-lg-12 col-xs-12" style="margin-bottom: 40px;">
		<div class="table-responsive">
			<table width="100%" class="table table-bordered" border="1" style="border-collapse: collapse;">
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
						<td style="padding-left: 5px;"> <?php echo $value->checklist_index; ?></td>

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
		<div class="table-responsive" style="border: 1px solid black;">
			<table width="100%" style="margin-bottom: 20px;" class="table" id="tyre_table">
				    <tr>
				        <th colspan="12" style="text-align: left;padding-left: 5px; border-bottom: 1px solid black;">TYRES</th>
				    </tr>
				    <tr>
				        <th colspan="6" style="text-align: left !important;padding-left: 5px;">Tread Depth</th>
				        <th colspan="6" style="text-align: left !important;padding-left: 5px;">Tyre Pressures</th>
				    </tr>
				    <tr>
				        <td rowspan="5" style="width: 50px;"><strong>Front</strong></td>
				        <td rowspan="2" style="border: 1px solid #000; width:55px;"><?php echo $result->thread_depth->b1; ?></td>
				        <td rowspan="2" style="border: 1px solid #000; width:55px;"><?php echo $result->thread_depth->b3; ?></td>
				        <td style="border: 1px solid #000;"><?php echo $result->thread_depth->mb1; ?></td>
				        <td style="border: 1px solid #000;"><?php echo $result->thread_depth->mb5; ?></td>
				        <td style="width: 40px;"><strong>Spare</strong></td>
				        <td rowspan="5" style="width: 50px;"><strong>Front</strong></td>
				        <td rowspan="2" style="border: 1px solid #000; width:55px;"><?php echo $result->tyre_pressure->b1; ?></td>
				        <td rowspan="2" style="border: 1px solid #000; width:55px;"><?php echo $result->tyre_pressure->b3; ?></td>
				        <td style="border: 1px solid #000;"><?php echo $result->tyre_pressure->mb1; ?></td>
				        <td style="border: 1px solid #000;"><?php echo $result->tyre_pressure->mb5; ?></td>
				        <td style="width: 40px;"><strong>Spare</strong></td>
				    </tr>
				    <tr>
				        <td style="border: 1px solid #000;"><?php echo $result->thread_depth->mb2; ?></td>
				        <td style="border: 1px solid #000;"><?php echo $result->thread_depth->mb6; ?></td>
				        <td rowspan="4" style="border: 1px solid black; width:30px;"><?php echo ($result->thread_depth->spare_tyre == 'YES') ? "✔" : "✖"; ?></td>
				        <td style="border: 1px solid #000;"><?php echo $result->tyre_pressure->mb2; ?></td>
				        <td style="border: 1px solid #000;"><?php echo $result->tyre_pressure->mb6; ?></td>
				        <td rowspan="4" style="border: 1px solid black; width:30px;"><?php echo ($result->tyre_pressure->spare_tyre == 'YES') ? "✔" : "✖"; ?></td>
				    </tr>
				    <tr>
				        <td colspan="4" style="border: none !important;"></td>
				        <td colspan="4" style="border: none !important;"></td>
				    </tr>
				    <tr>
				        <td rowspan="2" style="border: 1px solid #000; width:55px;"><?php echo $result->thread_depth->b2; ?></td>
				        <td rowspan="2" style="border: 1px solid #000; width:55px;"><?php echo $result->thread_depth->b4; ?></td>
				        <td style="border: 1px solid #000;"><?php echo $result->thread_depth->mb3; ?></td>
				        <td style="border: 1px solid #000;"><?php echo $result->thread_depth->mb7; ?></td>
				        <td rowspan="2" style="border: 1px solid #000; width:55px;"><?php echo $result->tyre_pressure->b2; ?></td>
				        <td rowspan="2" style="border: 1px solid #000; width:55px;"><?php echo $result->tyre_pressure->b4; ?></td>
				        <td style="border: 1px solid #000;"><?php echo $result->tyre_pressure->mb3; ?></td>
				        <td style="border: 1px solid #000;"><?php echo $result->tyre_pressure->mb7; ?></td>
				    </tr>
				    <tr>
				        <td style="border: 1px solid #000;"><?php echo $result->thread_depth->mb4; ?></td>
				        <td style="border: 1px solid #000;"><?php echo $result->thread_depth->mb8; ?></td>
				        <td style="border: 1px solid #000;"><?php echo $result->tyre_pressure->mb4; ?></td>
				        <td style="border: 1px solid #000;"><?php echo $result->tyre_pressure->mb8; ?></td>				        
				    </tr>
			</table>
		</div>
	</div>
</div>
