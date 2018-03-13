<!DOCTYPE>
<html>
<head>
	<title></title>
	<style type="text/css">
		.invoice-box{
			max-width:100%;
			margin-top:5px;
		}
		td{
			padding:2px;
		}
	</style>
</head>
<body>

	<div class="invoice-box">
		<table style="width:97%;">

			<tr>
				<td style="width:25%"></td>
				<td style="width:25%"></td>
				<td style="width:25%"></td>
				<td style="width:25%"></td>
			</tr>

			<tr>
				<th colspan="4" style="text-align: center;">BURGESS & WALKER TRANSPORT LIMITED</th>
			</tr>

			<tr>
				<td style="text-align: left"><strong>Report #</strong></td>
				<td style="text-align: left"><?php echo $id; ?></td>
				<td style="text-align: left"><strong>Driver</strong></td>
				<td style="text-align: left"><?php echo $driver_name; ?></td>
			</tr>

			<tr>
				<td style="text-align: left"><strong>Vehicle</strong></td>
				<td style="text-align: left"><?php echo $vehicle_registration_number; ?></td>
				<td style="text-align: left"><strong>Trailer Number</strong></td>
				<td style="text-align: left"><?php echo $trailer_number; ?></td>
			</tr>

			<tr>
				<td style="text-align: left"><strong>Mileage</strong></td>
				<td style="text-align: left"><?php echo $start_mileage; ?></td>
				<td style="text-align: left"><strong>Advisory</strong></td>
				<td style="text-align: left"><?php echo $advisory; ?></td>
			</tr>

			<tr>
				

				<td style="text-align: left"><strong>Created Report</strong></td>
				<td style="text-align: left" colspan="3"><?php echo $start_date; ?></td>
				
			</tr>

			<tr>
				<td colspan="4" style="text-align: left;width: 50%;padding:10px 0px;"><strong><?php echo $checklist_type; ?></strong></td>
			</tr>


			<?php foreach($checklist as $row) :?>
					<tr>
						<td colspan="2"><?php echo $row['checklist_index']; ?></td>
						<td colspan="2" style="font-size: 20px;top: -2px;position: relative;">
							<?php if($row['value'] == "SERVICEABLE") :  ?>
								<img src="<?php echo base_url("public/images/c.png"); ?>">
							<?php elseif($row['value'] == "NA") : ?>
								-
							<?php else : ?>
								<img src="<?php echo base_url("public/images/x.png"); ?>">
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>

		</table>

	</div>

</body>
</html>
