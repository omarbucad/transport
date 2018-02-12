<script type="text/javascript">
    $(function () {
        $('.dt').DataTable({
            responsive: true,
            "aaSorting": [],
            "sDom": 'rt'
        });
    });

    $(document).on('change' , '#store_select' , function(){
    	var url = "<?php echo site_url('app/dashboard/index/'); ?>";
    	var store_id = $(this).val();

    	window.location.replace(url+store_id);
    });
</script>


<?php if($driver_license_notif['count'] > 0) : ?>
	<div style="margin-bottom: 50px;">
		<div class="col-lg-12 bg-red" style="padding: 10px;">
			<a href="<?php echo site_url("app/vehicles/driver/").$driver_license_notif['query']; ?>" style="color:white;font-weight: bold;padding: 10px;text-decoration: none;"><?php echo $driver_license_notif['count']; ?> Driver License needs attention</a>
		</div>
	</div>
<?php endif; ?>

<div class="block-header">
	<div class="row">
		<div class="col-xs-6 col-md-9 col-lg-9">
			<h2 class="text-uppercase"><?php echo $this->session->userdata('company_name'); ?> DASHBOARD</h2> 
		</div>
		<div class="col-xs-6 col-md-3 col-lg-3">
			<select class="form-control" id="store_select">
				<?php foreach($store_list as $row): ?>
					<option value="<?php echo $row->store_id; ?>" <?php echo ($row->store_id == $store_selected_id) ? "selected='selected'" : false ; ?> ><?php echo $row->store_name; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
</div>

<?php if($show_dashboard) : ?>
	<?php /* ?>
	<div class="row">
	    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
	        <p class="help-block"><?php echo @$boxResult['first_box']['date']; ?></p>
	        <div class="info-box-3 bg-brown">
	            <div class="icon">
	                <span class="chart chart-line"><?php echo @$boxResult["first_box"]["chart"]; ?></span>
	            </div>
	            <div class="content">
	                <div class="text">SALES</div>
	                <div class="number"><?php echo @$boxResult["first_box"]["total"]; ?></div>
	            </div>
	        </div>
	    </div>
	    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
	        <p class="help-block"><?php echo @$boxResult['second_box']['date']; ?></p>
	        <div class="info-box-3 bg-grey">
	            <div class="icon">
	                <span class="chart chart-line"><?php echo @$boxResult["second_box"]["chart"]; ?></span>
	            </div>
	            <div class="content">
	                <div class="text">SALES</div>
	                <div class="number"><?php echo @$boxResult["second_box"]["total"]; ?></div>
	            </div>
	        </div>
	    </div>
	    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
	        <p class="help-block"><?php echo @$boxResult['third_box']['date']; ?></p>
	        <div class="info-box-3 bg-green">
	            <div class="icon">
	                <div class="chart chart-line"><?php echo @$boxResult["third_box"]["chart"]; ?></div>
	            </div>
	            <div class="content">
	            <div class="text">SALES</div>
	                <div class="number"><?php echo @$boxResult["third_box"]["total"]; ?></div>
	            </div>
	        </div>
	    </div>
	</div>
	
	<?php */ ?>
	<div class="row clearfix">
	    <!-- COLLAPSE 1 -->
		<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" >
			<div class="info-box-2 bg-light-green hover-zoom-effect" data-toggle="collapse" data-target="#collapse1">
				<div class="icon">
					<i class="material-icons">local_shipping</i>
				</div>
				<div class="content">
					<div class="text text-uppercase">Active Trucks</div>
					<div class="number count-to" data-from="0" data-to="<?php echo $activeTruck; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $activeTruck; ?></div>
				</div>
			</div>
			<div class="row clearfix">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 collapse" id="collapse1">
					<div class="card">
						<div class="header bg-light-green">
							<h2>
								Active Trucks
								<small>Daily reports that is completed </small>
							</h2>
						</div>
						<div class="body table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>Driver</th>
										<th><nobr>Vehicle Number</nobr></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($activeTruckList as $key => $row) : ?>
										<tr>
											<td><?php echo $row->name.' '.$row->surname; ?></td>
											<td><a href="<?php echo site_url('app/reports/vehicles/'.$row->vehicle_number); ?>"><?php echo $row->vehicle_number; ?></a></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	    <!-- END COLLAPSE 1-->
	    <!-- COLLAPSE 2-->
		<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" >
			<div class="info-box-2 bg-orange hover-zoom-effect" data-toggle="collapse" data-target="#collapse2">
				<div class="icon">
					<i class="material-icons">create</i>
				</div>
				<div class="content">
					<div class="text text-uppercase">Incomplete Report</div>
					<div class="number count-to" data-from="0" data-to="<?php echo $incompleteTruck; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $incompleteTruck; ?></div>
				</div>
			</div>
			<div class="row clearfix">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 collapse" id="collapse2">
					<div class="card">
						<div class="header bg-orange">
							<h2>
								Incomplete Report
								<small>Daily reports that is generated but not completed </small>
							</h2>
						</div>
						<div class="body table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>Driver</th>
										<th><nobr>Vehicle Number</nobr></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($incompleteTruckList as $key => $row) : ?>
										<tr>
											<td><?php echo $row->name.' '.$row->surname; ?></td>
											<td><a href="<?php echo site_url('app/reports/vehicles/'.$row->vehicle_number); ?>"><?php echo $row->vehicle_number; ?></a></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	    <!-- END COLLAPSE 2-->
	    <!-- COLLAPSE 3-->
		<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" >
			<div class="info-box-2 bg-deep-orange hover-zoom-effect" data-toggle="collapse" data-target="#collapse3">
				<div class="icon">
					<i class="material-icons">report_problem</i>
				</div>
				<div class="content">
					<div class="text text-uppercase">Defective Trucks</div>
					<div class="number count-to" data-from="0" data-to="<?php echo $defectTruck; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $defectTruck; ?></div>
				</div>
			</div>
			<div class="row clearfix">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 collapse" id="collapse3">
					<div class="card ">
						<div class="header bg-deep-orange">
							<h2>
								Defective Trucks
								<small>Number of Defect reports</small>
							</h2>
						</div>
						<div class="body table-responsive ">
							<table class="table">
								<thead>
									<tr>
										<th><nobr>Report #</nobr></th>
										<th><nobr>Vehicle Number</nobr></th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($defectTruckList as $key => $row) : ?>
										<tr>
											<th scope="row"><?php echo $row->id; ?></th>
											<td><a href="<?php echo site_url('app/reports/vehicles/'.$row->vehicle_number); ?>"><?php echo $row->vehicle_number; ?></a></td>
											<td><?php echo $row->status; ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	  
	</div>

	<div class="block-header">
		<h2 class="text-uppercase"><?php echo $this->session->userdata('company_name'); ?> JOBS</h2> 
	</div>

	<?php $this->load->view($include_page) ?>

<?php else : ?>
	<div class="row clearfix">
		<div class="col-lg-12 col-md-12 col-sm-6 col-xs-12">
			<div class="card">
				<div class="header bg-orange">
					<h2>
						Warning
					</h2>

				</div>
				<div class="body">
					You have no enabled or registered Company Information <a href="<?php echo site_url("app/company"); ?>">Click here</a> to add Company.
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>


