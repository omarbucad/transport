<script type="text/javascript">
	$(function () {
		$("img.lazy").lazyload({
		    threshold : 200,
		    effect : "fadeIn",
		    skip_invisible : true
		});

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
                 Gallery
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
                    VEHICLE GALLERY
                </h2>
            </div>
            <div class="body">
                <div id="aniimated-thumbnials" class="list-unstyled clearfix">
                    <?php foreach ($result as $key => $row) : ?>
                    	<div class="row">
	                		<div class="container-fluid">
	                			<h2>
	                				<?php echo $key; ?> - 
	                				<small><?php echo $row[0]->description; ?></small>
	                			</h2>
	                		</div>
	                		<?php foreach ($row as $data): ?>
	                			<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
		                            <a href="<?php echo $data->image; ?>" data-sub-html="<?php echo $data->vehicle_number.' - '.$data->description; ?>">
		                                <img class="img-responsive thumbnail lazy" src="<?php echo $data->image_thumb; ?>" >
		                            </a>
		                        </div>
	                		<?php endforeach; ?>	
	                	</div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- #END# Basic Examples -->