<table widtd="100%;">
	<tr>
		<td style="text-align: left"><strong>Report #</strong></td>
		<td style="text-align: left"><?php echo $result->id; ?></td>
		<td style="text-align: left"><strong>Driver</strong></td>
		<td style="text-align: left"><?php echo $result->name.' '.$result->surname; ?></td>
	</tr>

	<tr>
		<td style="text-align: left"><strong>Vehicle</strong></td>
		<td style="text-align: left"><?php echo $result->vehicle_registration_number; ?></td>
		<td style="text-align: left"><strong><nobr>Trailer Number</nobr></strong></td>
		<td style="text-align: left"><?php echo $result->trailer_number; ?></td>
	</tr>

	<tr>
		<td style="text-align: left"><strong>Mileage</strong></td>
		<td style="text-align: left"><?php echo $result->start_mileage; ?></td>
		<td style="text-align: left"><strong><nobr>Created Report</nobr></strong></td>
		<td style="text-align: left"><?php echo $result->created; ?></td>
	</tr>

	<tr>
		<td style="text-align: left"><strong>Advisory</strong></td>
		<td style="text-align: left"><?php echo $result->advisory; ?></td>
		
	</tr>

	<tr>
		<td colspan="2" style="text-align: left;width: 50%;padding:10px 0px;"><strong><?php echo @$result->title; ?></strong></td>
		<td style="text-align: left;width: 25%;"></td>
		<td style="text-align: left;width: 25%;"></td>
	</tr>
	<tr>
		<?php if(@$result->title == "Moffet report Checklist") : ?>

			<?php for($x = 0 ; $x < 32 ; $x+=2) : ?>
				<tr>
					<td><?php echo $result->all_checklist[$x]['checklist_index']; ?></td>
					<td style="font-size: 20px;top: -2px;position: relative;">
						<?php if($result->all_checklist[$x]['value'] == "SERVICEABLE") :  ?>
							&#10003;
						<?php elseif($result->all_checklist[$x]['value'] == "NA") : ?>
							-
						<?php else : ?>
							&#10060;
						<?php endif; ?>
					</td>
					<td><?php echo $result->all_checklist[($x + 1)]['checklist_index']; ?></td>
					<td style="font-size: 20px;top: -2px;position: relative;">
						<?php if($result->all_checklist[($x + 1)]['value'] == "SERVICEABLE") :  ?>
							&#10003;
						<?php elseif($result->all_checklist[($x + 1)]['value'] == "NA") : ?>
							-
						<?php else : ?>
							&#10060;
						<?php endif; ?>
					</td>
				</tr>
			<?php endfor; ?>
			<td><?php echo $result->all_checklist[32]['checklist_index']; ?></td>
			<td style="font-size: 20px;top: -2px;position: relative;">
				<?php if($result->all_checklist[32]['value'] == "SERVICEABLE") :  ?>
					&#10003;
				<?php elseif($result->all_checklist[32]['value'] == "NA") : ?>
					-
				<?php else : ?>
					&#10060;
				<?php endif; ?>
			</td>
		<?php else : ?>
			<?php foreach($result->all_checklist as $row) :?>
				<tr>
					<td colspan="2"><?php echo $row['checklist_index']; ?></td>
					<td style="font-size: 20px;top: -2px;position: relative;">
						<?php if($row['value'] == "SERVICEABLE") :  ?>
							&#10003;
						<?php elseif($row['value'] == "NA") : ?>
							-
						<?php else : ?>
							&#10060;
						<?php endif; ?>
					</td>
					<td><?php //echo ($row['value'] == "NA") ? "NA" : $row['checklist_timer']; ?></td>
				</tr>
			<?php endforeach; ?>

		<?php endif; ?>
	</tr>
</table>