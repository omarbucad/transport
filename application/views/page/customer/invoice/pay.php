<?php if(!$this->input->get() OR !$invoice['result']) : ?>
	<p>No Selected Invoice Please go back in the <a href="<?php echo site_url("app/customer/invoices"); ?>">invoice page</a> and select atleast 1 invoices to continue</p>
<?php else : ?>
<script type="text/javascript">
	$(document).ready(function(){
		var type = "<?php echo $this->input->post("paid_by"); ?> ";
		selectDropdown(type);
	});
	
	$(document).on('change' , '#paid_by_select' , function(){
		var type = $(this).val();
		
		selectDropdown(type);
	});

	$(document).on('change' , '#_paidDate' , function(){
		$(this).focus();
	});

	function selectDropdown(type){
		var banktransfer = $("#_bankTransferDiv");
		var chequeNumber = $("#_chequeNumberDiv");

		switch(type){
			case "BANK_TRANSFER" :
				banktransfer.show().find("input").prop("disabled" , false);
				chequeNumber.hide().find("input").prop("disabled" , true);
			break;
			case "PAID_BY_CHEQUE" :
				banktransfer.hide().find("input").prop("disabled" , true);
				chequeNumber.show().find("input").prop("disabled" , false);
			break;
			case "PETTY_CASH" :
				banktransfer.hide().find("input").prop("disabled" , true);
				chequeNumber.hide().find("input").prop("disabled" , true);
			break;
			default:
				banktransfer.show().find("input").prop("disabled" , false);
				chequeNumber.hide().find("input").prop("disabled" , true);
			break;
		}

	}

	$(document).on('submit' , '#form_validation' , function(){
		var $btn = $(this).find('#payInvoice');
		$btn.button('loading');
	});

	 // $(document).ready(function() {
  //     $('#payInvoice').click(function() {
  //       var $btn = $(this);
  //       $btn.button('loading');

  //     });
  //   });

</script>

<!-- Basic Examples -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    INVOICE LIST
                </h2>
            </div>
            <div class="body">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Job #</th>
                            <th>Job Name</th>
                            <th>Paid By</th>
                            <th>Invoice Date</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-right">Total Price</th>
                            <th><?php echo $invoice['total']; ?></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    	<?php foreach($invoice['result'] as $row) :  ?>
                    		<tr>
                    			<td><?php echo $row->invoice_id; ?></td>
                    			<td><?php echo $row->job_id; ?></td>
                    			<td><?php echo $row->job_name; ?></td>
                    			<td><?php echo $row->paid_by; ?></td>
                    			<td><?php echo $row->invoice_date; ?></td>
                    			<td><?php echo $row->total_price; ?></td>
                    		</tr>
                    	<?php endforeach; ?>
                    </tbody>
                </table>

                <form id="form_validation" action="<?php echo site_url('app/customer/pay_invoices/'.$request_parameters) ?>" enctype="multipart/form-data" method="POST">
					<?php echo validation_errors(); ?>
					<fieldset>
						<input type="hidden" name="total_price" value="<?php echo $invoice['total_price_raw']; ?>">
						<div class="form-group form-float">
							<label>Paid By</label>
							<div class="form-line">
								<select class="form-control show-tick" name="paid_by" id="paid_by_select"  required>
									<option value="BANK_TRANSFER" >BANK TRANSFER</option>
									<option value="PAID_BY_CHEQUE" >PAID BY CHEQUE</option>
									<option value="PETTY_CASH" >PETTY CASH</option>
								</select>
							</div>
						</div>
						<div class="form-group form-float" id="_chequeNumberDiv">
							<div class="form-line">
								<input type="text"  class="form-control" name="cheque_number" value="<?php echo set_value("cheque_number"); ?>" required>
								<label class="form-label">Cheque No.</label>
							</div>
						</div>
						<div class="form-group form-float">
							 <div class="form-line">
	                            <input type="text" class="datepicker form-control" id="_paidDate" name="paid_date" value="<?php echo set_value("paid_date"); ?>" required>
	                            <label class="form-label">Paid Date</label>
	                        </div>
	                         <div class="help-info">Choose a Date</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<textarea name="notes" cols="30" rows="3" class="form-control no-resize"><?php echo set_value("notes"); ?></textarea>
								<label class="form-label">Notes</label>
							</div>
						</div>

					</fieldset>
					<div id="_bankTransferDiv">
						<h3>Bank Transfer Image</h3>
						<fieldset>
							<div class="form-group form-float">
								<div class="form-line">
									<input type="file" name="file[]" class="form-control" multiple="" required="">
								</div>
							</div>
						</fieldset>
					</div>
					<a class="btn btn-danger waves-effect" href="<?php echo site_url('app/customer/invoices'); ?>"><i class="material-icons" style="font-size: 16.5px;">block</i> CANCEL</a>
					<button class="btn btn-primary waves-effect pull-right" type="submit" id="payInvoice"><i class="material-icons" style="font-size: 16.5px;">done_all</i> SUBMIT</button>
				</form>
            </div>
        </div>
    </div>
</div>
<!-- #END# Basic Examples -->

<?php endif; ?>