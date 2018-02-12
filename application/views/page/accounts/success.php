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
					<h4>Account Information </h4>
					<dl class="dl-horizontal">
						 <dt>Username</dt>
						 <dd><?php echo $data["username"]; ?></dd>
						 <dt>Password</dt>
						 <dd><?php echo $data["password"]; ?></dd>
						 <dt>Account Type</dt>
						 <dd><?php echo $data["account_type"]; ?></dd>
					</dl>
					<h4>Personal Information</h4>
					<dl class="dl-horizontal">
						<dt>Name</dt>
						<dd><?php echo $data["name"].' '.$data["surname"]; ?></dd>
						<dt>Email</dt>
						<dd><?php echo $data["email"]; ?></dd>
						<dt>Address</dt>
						<dd><?php echo $data["address"]; ?></dd>
					</dl>
				</div>
			<?php else : ?>

				<div class="col-lg-6 col-xs-12">
					<h4>Account Information </h4>
					<dl class="dl-horizontal">
						 <dt>Account Type</dt>
						 <dd><?php echo $data["account_type"]; ?></dd>
					</dl>
					<h4>Personal Information</h4>
					<dl class="dl-horizontal">
						<dt>Name</dt>
						<dd><?php echo $data["name"].' '.$data["surname"]; ?></dd>
						<dt>Email</dt>
						<dd><?php echo $data["email"]; ?></dd>
						<dt>Address</dt>
						<dd><?php echo $data["address"]; ?></dd>
					</dl>
				</div>

			<?php endif; ?>
		</div>
		<div class="row">
			<div class="col-lg-6 col-xs-6">
				<a href="<?php echo site_url("app/accounts") ?>" class="btn btn-primary waves-effect">Close</a>
				<a href="<?php echo site_url("app/accounts/add") ?>" class="btn btn-info waves-effect">Add New Account</a>
			</div>
			<div class="col-lg-6 col-xs-6 text-right">
				<button class="btn btn-danger waves-effect remove-data" data-id="<?php echo $last_id; ?>" data-href="<?php echo site_url("app/accounts/removeAccount"); ?>">Remove This Data</button>
			</div>
		</div>
		
	</div>
</div>