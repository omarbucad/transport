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
				Successfully Inserted New Data <small>Created New Expenditure</small>
			<?php else : ?>
				Successfully Updated Invoice <small>Updated Expenditure</small>
			<?php endif; ?>
		</h2>
	</div>
	<div class="body">
		<div class="row">
			<?php if($success_type == 'insert') : ?>
				<div class="col-lg-6 col-xs-12">
					<h4>Expenditure Information</h4>
					<dl class="dl-horizontal">
						<dt>Expenditure #</dt>
						<dd><?php echo $result->exp_number; ?></dd>
						<dt>Invoice Date</dt>
						<dd><?php echo $result->invoice_date; ?></dd>
						<dt>User: </dt>
						<dd><?php echo $result->statuses[0]->name ." ".$result->statuses[0]->surname; ?></dd>
						<dt>Status</dt>
						<dd><?php echo $result->statuses[0]->status_lbl;?></dd>
						<dt>Paid Date</dt>
						<?php if($result->statuses[0]->status_lbl != "UNPAID") : ?>
							<dd><?php echo $result->statuses[0]->created; ?></dd>
						<?php endif; ?>
						
						<dt>Discount</dt>
						<dd><?php echo $result->discount; ?></dd>
						<dt>Subtotal</dt>
						<dd><?php echo $result->subtotal; ?></dd>
						<dt>VAT</dt>
						<dd><?php echo $result->vat; ?></dd>
						<dt>Gross</dt>
						<dd><?php echo $result->total; ?></dd>
						<dt>Notes</dt>
						<dd><?php echo $result->statuses[0]->notes; ?></dd>
					</dl>
				</div>
			<?php else : ?>

				<div class="col-lg-6 col-xs-12">
					<h4>Expenditure Information</h4>
					<dl class="dl-horizontal">
						<dt>Expenditure #</dt>
						<dd><?php echo $result->exp_number; ?></dd>
						<dt>User: </dt>
						<dt><?php echo $result->statuses[0]->updated_by; ?></dt>
						<dt>Status</dt>
						<dd></dd>
						<?php if($result->statuses[0]->status != "UNPAID") : ?>
							<dt>Paid Date</dt>
							<dd><?php echo $result->paid_date; ?></dd>
						<?php endif; ?>
						<dt>Invoice Date</dt>
						<dd><?php echo $result->invoice_date; ?></dd>
						<dt>Discount</dt>
						<dd><?php echo $result->discount; ?></dd>
						<dt>Subtotal</dt>
						<dd><?php echo $result->subtotal; ?></dd>
						<dt>VAT</dt>
						<dd><?php echo $result->vat; ?></dd>
						<dt>Gross</dt>
						<dd><?php echo $result->total; ?></dd>
						<dt>Notes</dt>
						<dd><?php echo $result->statuses[0]->notes; ?></dd>
					</dl>
				</div>

			<?php endif; ?>
		</div>
		<div class="row">
			<div class="col-lg-6 col-xs-6">
				<a href="<?php echo site_url("app/expenditures") ?>" class="btn btn-primary waves-effect">Close</a>
				<a href="<?php echo site_url("app/expenditures/add") ?>" class="btn btn-info waves-effect">Add New Expenditure</a>
			</div>
			<div class="col-lg-6 col-xs-6 text-right">
				<button class="btn btn-danger waves-effect remove-data" data-href="<?php echo site_url("app/expenditures/remove_expenditure"); ?>">Remove This Data</button>
			</div>
		</div>
		
	</div>
</div>