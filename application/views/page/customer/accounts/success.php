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
				Successfully Inserted New Data <small>Created New Account</small>
			<?php else : ?>
				Successfully Updated Account <small>Updated Account</small>
			<?php endif; ?>
		</h2>
	</div>
	<div class="body">
		<div class="row">
			<?php if($success_type == 'insert') : ?>
				<div class="col-lg-6 col-xs-12">

					<h4>Company Information</h4>
					<dl class="dl-horizontal">
						<dt>Company Name</dt>
						<dd><?php echo $data["company_name"]; ?></dd>
						<dt>Registration Number</dt>
						<dd><?php echo $data["registration_number"]; ?></dd>
						<dt>VAT Number</dt>
						<dd><?php echo $data["vat_number"]; ?></dd>
						<dt>Billing Address</dt>
						<dd><?php echo nl2br($data["billing_address"]); ?></dd>
					</dl>
				</div>
			<?php else : ?>

				<div class="col-lg-6 col-xs-12">
					<h4>Company Information</h4>
					<dl class="dl-horizontal">
						<dt>Company Name</dt>
						<dd><?php echo @$data["company_name"]; ?></dd>
						<dd><?php echo @$data["registration_number"]; ?></dd>
						<dd><?php echo @$data["vat_number"]; ?></dd>
						<dd><?php echo nl2br(@$data["billing_address"]); ?></dd>
					</dl>
				</div>

			<?php endif; ?>
		</div>
		<div class="row">
			<div class="col-lg-6 col-xs-6">
				<a href="<?php echo site_url("app/customer/accounts") ?>" class="btn btn-primary waves-effect">Close</a>
				<a href="<?php echo site_url("app/customer/add") ?>" class="btn btn-info waves-effect">Add New Account</a>
			</div>
			<div class="col-lg-6 col-xs-6 text-right">
				<button class="btn btn-danger waves-effect remove-data" data-id="<?php echo $last_id; ?>" data-href="<?php echo site_url("app/customer/remove"); ?>">Remove This Data</button>
			</div>
		</div>
		
	</div>
</div>