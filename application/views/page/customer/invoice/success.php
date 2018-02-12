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
				Successfully Inserted New Data <small>Created New Invoice</small>
			<?php else : ?>
				Successfully Updated Invoice <small>Updated Invoice</small>
			<?php endif; ?>
		</h2>
	</div>
	<div class="body">
		<div class="row">
			<?php if($success_type == 'insert') : ?>
				<div class="col-lg-6 col-xs-12">

					<h4>Invoice Information</h4>
					<dl class="dl-horizontal">
						<dt>Invoice ID</dt>
						<dd><?php echo @$last_id; ?></dd>
						<dt>Paid Status</dt>
						<dd><?php echo $data["paid_by"]; ?></dd>
						<?php if($data['paid_by'] != "UNPAID") : ?>
							<dt>Paid Date</dt>
							<dd><?php echo $data["paid_date"]; ?></dd>
						<?php endif; ?>
						<dt>Invoice Date</dt>
						<dd><?php echo $data["invoice_date"]; ?></dd>
						<dt>Demurrage</dt>
						<dd><?php echo $data["demurrage"]; ?></dd>
						<dt>Job Cost</dt>
						<dd><?php echo $data["price"]; ?></dd>
						<dt>VAT</dt>
						<dd><?php echo $data["vat"]; ?></dd>
						<dt>Gross</dt>
						<dd><?php echo $data["total_price"]; ?></dd>
						<dt>Notes</dt>
						<dd><?php echo $data["notes"]; ?></dd>
					</dl>
				</div>
			<?php else : ?>

				<div class="col-lg-6 col-xs-12">
					<h4>Invoice Information</h4>
					<dl class="dl-horizontal">
						<dt>Invoice ID</dt>
						<dd><?php echo $data['invoice_id']; ?></dd>
						<dt>Paid Status</dt>
						<dd><?php echo $data["paid_by"]; ?></dd>
						<?php if($data['paid_by'] != "UNPAID") : ?>
							<dt>Paid Date</dt>
							<dd><?php echo $data["paid_date"]; ?></dd>
						<?php endif; ?>
						<dt>Invoice Date</dt>
						<dd><?php echo $data["invoice_date"]; ?></dd>
						<dt>Demurrage</dt>
						<dd><?php echo $data["demurrage"]; ?></dd>
						<dt>Job Cost</dt>
						<dd><?php echo $data["price"]; ?></dd>
						<dt>VAT</dt>
						<dd><?php echo $data["vat"]; ?></dd>
						<dt>Gross</dt>
						<dd><?php echo $data["total_price"]; ?></dd>
						<dt>Notes</dt>
						<dd><?php echo $data["notes"]; ?></dd>
					</dl>
				</div>

			<?php endif; ?>
		</div>
		<div class="row">
			<div class="col-lg-6 col-xs-6">
				<a href="<?php echo site_url("app/customer/invoices") ?>" class="btn btn-primary waves-effect">Close</a>
				<a href="<?php echo site_url("app/customer/add_invoice") ?>" class="btn btn-info waves-effect">Add New Invoice</a>
			</div>
			<div class="col-lg-6 col-xs-6 text-right">
				<button class="btn btn-danger waves-effect remove-data" data-href="<?php echo site_url("app/customer/remove_invoice"); ?>">Remove This Data</button>
			</div>
		</div>
		
	</div>
</div>