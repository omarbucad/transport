<script type="text/javascript">
	$(function () {
	    $(document).on('click', '.remove-data' ,function () {
	    	var id = $(this).data('id');
	    	var href = $(this).data('href');
	        swal({
		        title: "Are you sure?",
		        text: "You will not be able to recover this inserted data!",
		        type: "warning",
		        showCancelButton: true,
		        confirmButtonColor: "#DD6B55",
		        confirmButtonText: "Yes, delete it!",
		        cancelButtonText: "No, cancel it!",
		        closeOnConfirm: false,
		        closeOnCancel: false
		    }, function (isConfirm) {
		        if (isConfirm) {
		            
		            $.ajax({
		            	url : href ,
		            	data : {id : id},
		            	method : 'post',
		            	success : function(response){
		            		swal("Deleted!", "Your Inserted Data has been deleted.", "success");
		            	}

		            });

		        } else {
		            swal("Cancelled", "Your Inserted Data is safe :)", "error");
		        }
		    });

	    });

	});
</script>
<div class="card">
	<div class="header bg-green">
		<h2>
			<?php if($success_type == 'insert') : ?>
				Successfully Inserted New Data <small>Added new Vehicle Number</small>
			<?php else : ?>
				Successfully Updated Data <small>Updated Vehicle Number</small>
			<?php endif;?>
		</h2>
	</div>
	<div class="body">
		<div class="row">
			<div class="col-lg-6 col-xs-12">
				<h4>Vehicle Information # <?php echo $last_id; ?></h4>
				<dl class="dl-horizontal">
					 <dt>Vehicle Number</dt>
					 <dd><?php echo @$data["vehicle_number"]; ?></dd>
					 <dt>Make</dt>
					 <dd><?php echo @$data["truck_make"]; ?></dd>
					 <dt>Type</dt>
					 <dd><?php echo @$data["truck_type"]; ?></dd>
					 <dt>Description</dt>
					 <dd><?php echo @$data["description"]; ?></dd>
				</dl>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6 col-xs-6">
				<a href="<?php echo site_url("app/vehicles") ?>" class="btn btn-primary waves-effect">Close</a>
				<a href="<?php echo site_url("app/vehicles/add") ?>" class="btn btn-info waves-effect">Add New Vehicle Number</a>
			</div>
			<div class="col-lg-6 col-xs-6 text-right">
				<button class="btn btn-danger waves-effect remove-data" data-id="<?php echo $last_id; ?>" data-href="<?php echo site_url("app/vehicles/removeVehicle"); ?>">Remove This Data</button>
			</div>
		</div>
		
	</div>
</div>