<div class="row">
	<div class="col-lg-3 col-xs-12">
		<table width="100%">
			<tr>
				<th>Report #</th>
				<td><?php echo $result->id; ?></td>
			</tr>
			<tr>
				<th>Driver</th>
				<td><?php echo $result->name.' '.$result->surname; ?></td>
			</tr>								
			<tr>
				<th>Vehicle</th>
				<td><?php echo $result->vehicle_registration_number; ?></td>
			</tr>
			<tr>
				<th>Advisory</th>
				<td><?php echo $result->advisory; ?></td>
			</tr>
	</table>
</div>

<div class="col-lg-3 col-xs-12">
	<table width="100%">
		<tr>
			<th>Trailer Number</th>
			<td><?php echo $result->trailer_number; ?></td>
		</tr>
		<tr>
			<th>Start Mileage</th>
			<td><?php echo $result->start_mileage; ?></td>
		</tr>
		<tr>
			<th>Created</th>
			<td><?php echo $result->created; ?></td>
		</tr>
	</table>
</div>
<div class="col-lg-6 col-xs-12">
	<div class="table-responsive">
		<table width="100%">
			<tr>
				<th>Sufficient daily rest period?</th>
				<td><?php echo $result->daily_rest;?></td>
			</tr>
			<tr>
				<th>Sufficient weekly rest period?</th>
				<td><?php echo $result->weekly_rest;?></td>
			</tr>
		</table>
	</div>	
</div>

<div class="col-lg-6 col-xs-12">
	<div class="table-responsive">
		<table width="100%" class="table table-bordered">
			<thead>
				<tr>
					<th>Status</th>
					<th>Fixed By</th>
					<th>Comment</th>
					<th>Created</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($result->status_list as $row): ?>
					<tr>
						<td><?php echo $row->status; ?></td>
						<td><?php echo $row->name; ?></td>
						<td><?php echo $row->comment; ?></td>
						<td><?php echo $row->created; ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>


</div>

<div class="row">
	<div class="col-lg-12 col-xs-12">
		<h4>Checklist</h4>
			<div class="table-responsive">
			<table width="100%" class="table">
				<?php foreach($result->all_checklist as $key => $checklist) : ?>	
					<?php if($key % 2 == 0) : ?>
						<tr>
							<td class="<?php echo ($checklist['value'] == "DEFECT") ? "bg-red" : "";  ?>"><nobr><?php echo $checklist['checklist_index']; ?></nobr></td>
							<td class="<?php echo ($checklist['value'] == "DEFECT") ? "bg-red" : "";  ?>"><div class="badge bg-orange"><?php echo ($checklist['value'] == "NA") ? "NA" : $checklist['checklist_timer']; ?></div> </td>
						<?php else : ?>
							<td class="<?php echo ($checklist['value'] == "DEFECT") ? "bg-red" : "";  ?>"><nobr><?php echo $checklist['checklist_index']; ?></nobr></td>
							<td class="<?php echo ($checklist['value'] == "DEFECT") ? "bg-red" : "";  ?>"><div class="badge bg-orange"><?php echo ($checklist['value'] == "NA") ? "NA" : $checklist['checklist_timer']; ?></div> </td>
						</tr>
					<?php endif; ?>
				<?php endforeach; ?>
			</table>	
		</div>

	</div>
</div>

<?php if($result->images) : ?>
	<div>
		<legend>Image</legend>
		<div class="list-unstyled clearfix">
			<div class="row">
				<?php foreach($result->images as $image): ?>
					<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
						<a href="<?php echo $image->images; ?>" data-sub-html="Report # <?php echo $result->id; ?>">
							<img class="img-responsive thumbnail" src="<?php echo $image->images; ?>" >
						</a>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
<?php endif;?>