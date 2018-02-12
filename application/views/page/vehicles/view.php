<script type="text/javascript">
	$(function () {
	    $(document).on('click', '.remove-data' ,function () {
	    	var id = $(this).data('id');
	    	var href = $(this).data('href');
	    	var $me = $(this);
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
		            		$me.closest('tr').remove();
		            	}

		            });

		        } else {
		            swal("Cancelled", "Your Inserted Data is safe :)", "error");
		        }
		    });

	    });

	});
</script>

    
<div class="block-header">
    <h2>
        <ol class="breadcrumb" style="padding: 0px;">
            <li>
                <a href="<?php echo site_url('app/dashboard/index/4'); ?>">
                    Dashboard
                </a>
            </li>
            <li class="active">
                Vehicles
            </li>
            <li class="active">
                  Vehicle Number
            </li>
        </ol>
    </h2>
</div>  

<div class="block-header">
    <h2>
        <a role="button" class="btn btn-success" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="material-icons" style="position: relative;font-size: 16.5px;">search</i> Search</a>
        <a href="<?php echo site_url("app/vehicles/add"); ?>" class="btn btn-primary"><i class="material-icons" style="position: relative;font-size: 16.5px;">add_circle_outline</i> Add Vehicle Number</a>
    </h2>
</div>

<div style="margin-bottom: 20px;">
    <div class="collapse" id="collapseExample">
        <?php $this->load->view('page/vehicles/search') ?>
    </div>
</div>

<!-- Basic Examples -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    VEHICLE NUMBER LIST
                </h2>
            </div>
            <div class="body table-responsive">
               <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <thead>
                        <tr>
                            <th>Vehicle Number</th>
                            <th>Make</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Vehicle Number</th>
                            <th>Make</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach ($result as $key => $row): ?>
                            <tr>
                                <td><?php echo $row->vehicle_number; ?></td>
                                <td><?php echo $row->truck_make; ?></td>
                            	<td><?php echo $row->truck_type; ?></td>
                            	<td><?php echo $row->description; ?></td>
                            	<td><?php echo $row->status; ?></td>
                            	<td>
                            		<nobr>
                                        <a href="<?php echo site_url("app/vehicles/updateVehicle/".$row->id) ?>" class="btn btn-xs btn-info waves-effect"><i class="material-icons" style="font-size: 16px;">update</i> Update</a>
                                        
                                        <a href="Javascript:void(0);" class="btn btn-xs btn-danger waves-effect remove-data" data-id="<?php echo $row->id; ?>" data-href="<?php echo site_url("app/vehicles/removeVehicle"); ?>" data-name="<?php echo $row->vehicle_number ?>"><i class="material-icons" style="font-size: 16px;">cancel</i> Remove</a>
                                    </nobr>
                            	</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- #END# Basic Examples -->