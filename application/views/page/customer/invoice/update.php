<script type="text/javascript">
	$(document).ready(function(){
		$('#paid_by_select').trigger("change");
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
		var _paidDate = $("._paidDate");


		switch(type){
			case "BANK_TRANSFER" :
				banktransfer.show().find("input").prop("disabled" , false);
				chequeNumber.hide().find("input").prop("disabled" , true);
				_paidDate.show().find("input").prop("disabled" , false);
			break;
			case "PAID_BY_CHEQUE" :
				banktransfer.hide().find("input").prop("disabled" , true);
				chequeNumber.show().find("input").prop("disabled" , false);
				_paidDate.show().find("input").prop("disabled" , false);
			break;
			case "UNPAID" :
				banktransfer.hide().find("input").prop("disabled" , true);
				chequeNumber.hide().find("input").prop("disabled" , true);
				_paidDate.hide().find("input").prop("disabled" , true);
			break;	
			case "PETTYCASH" :
				banktransfer.hide().find("input").prop("disabled" , true);
				chequeNumber.hide().find("input").prop("disabled" , true);
				_paidDate.show().find("input").prop("disabled" , false);
			break;
			case "COD_DEPOSIT" :
				banktransfer.hide().find("input").prop("disabled" , true);
				chequeNumber.hide().find("input").prop("disabled" , true);	
				_paidDate.show().find("input").prop("disabled" , false);
			break;		
			default:
				banktransfer.hide().find("input").prop("disabled" , true);
				chequeNumber.hide().find("input").prop("disabled" , true);
				_paidDate.hide().find("input").prop("disabled" , true);
			break;
		}

	}

	$(document).on('submit' , '#form_validation' , function(){
		var $btn = $(this).find('#payInvoice');
		$btn.button('loading');
	});

	function autocompute(){
		var demurrage = parseFloat($('#demurrage').val());
		var price = parseFloat($('#price').val());
		
		var a = (demurrage + price);
		var vat = (a * 0.20);

		var total_price = (a + vat);

		$('#vat').val(vat.toFixed(2));
		$('#total_price').val(total_price.toFixed(2));
	}

</script>

<div class="block-header">
	<h2>
		<ol class="breadcrumb" style="padding: 0px;">
			<li>
				<a href="<?php echo site_url('app/customer/invoices'); ?>">
					Customer
				</a>
			</li>
			<li class="active">
				  Update Invoice
			</li>
		</ol>
	</h2>
</div>

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Update Invoice</h2>
			</div>
			<div class="body">
				<form id="wizard_with_validation" action="<?php echo site_url('app/customer/update_invoices') ?>" enctype="multipart/form-data" method="POST">
					<input type="hidden" name="invoice_id" value="<?php echo $result->invoice_id; ?>">
					<input type="hidden" name="merge" value="<?php echo $result->merge; ?>">
					<input type="hidden" name="job_id" value="<?php echo $result->job_id; ?>">
					<?php echo validation_errors(); ?>

					<h3>Invoice Information</h3>
					<fieldset>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text"  class="form-control" name="invoice_no" value="<?php echo $result->invoice_number; ?>" >
								<label class="form-label">Invoice No.</label>
							</div>
							<div class="help-info">Optional</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text"  class="form-control" name="job_no" value="<?php echo $result->job_number; ?>" >
								<label class="form-label">Job No.</label>
							</div>
							<div class="help-info">Optional</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text"  class="form-control" name="jmerge_name" value="<?php echo $result->job_name; ?>" >
								<label class="form-label">Job Name</label>
							</div>
							<div class="help-info">Optional</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text"  class="form-control" name="jpo_number" value="<?php echo $result->jpo_number; ?>" >
								<label class="form-label">Purchase Order number.</label>
							</div>
							<div class="help-info">Optional</div>
						</div>
						<div class="form-group form-float">
							<label>Customer Name</label>
	                        <div class="form-line">
	                            <select class="form-control" name="company_name">
	                                <?php foreach($customerList as $row) : ?>
	                                    <option value="<?php echo $row->customer_id; ?>" <?php echo ($result->customer_id == $row->customer_id) ? "selected" : ""; ?> ><?php echo $row->company_name; ?></option>
	                                <?php endforeach; ?>
	                            </select>
	                        </div>
						</div>

						<div class="form-group form-float">
							<label>Paid By</label>
							<div class="form-line">
								<select class="form-control show-tick" name="paid_by" id="paid_by_select" >
									<option value="UNPAID" <?php echo ($result->paid_by == "UNPAID") ? "selected" : ""; ?> >UNPAID</option>
									<option value="BANK_TRANSFER" <?php echo ($result->paid_by == "BANK_TRANSFER") ? "selected" : ""; ?> >BANK TRANSFER</option>
									<option value="PAID_BY_CHEQUE" <?php echo ($result->paid_by == "PAID_BY_CHEQUE") ? "selected" : ""; ?> >PAID BY CHEQUE</option>
									<option value="PETTY_CASH" <?php echo ($result->paid_by == "PETTY_CASH") ? "selected" : ""; ?> >CASH</option>
									<option value="DEBITCARD" <?php echo ($result->paid_by == "DEBITCARD") ? "selected" : ""; ?> >DEBIT CARD</option>
									<option value="COD_DEPOSIT" <?php echo ($result->paid_by == "COD_DEPOSIT") ? "selected" : ""; ?> >COD DEPOSIT</option>
								</select>
							</div>
						</div>


						<div class="form-group form-float" id="_chequeNumberDiv">
							<div class="form-line">
								<input type="text"  class="form-control" name="cheque_number" value="<?php echo $result->cheque_number; ?>" >
								<label class="form-label">Cheque No.</label>
							</div>
						</div>
						<div class="form-group form-float _paidDate">
							 <div class="form-line">
	                            <input type="text" class="datepicker form-control" id="_paidDate" name="paid_date" value="<?php echo $result->paid_date; ?>" >
	                            <label class="form-label">Paid Date</label>
	                        </div>
						</div>

						<div class="form-group form-float">
							 <div class="form-line">
	                            <input type="text" class="datepicker form-control" name="invoice_date" value="<?php echo $result->invoice_date; ?>" >
	                            <label class="form-label">Invoice Date</label>
	                        </div>
						</div>

						<div class="form-group form-float">
							<div class="form-line">
								<input type="number" step="0.01" min="0" class="form-control" name="demurrage" onkeyup="autocompute();" id="demurrage" value="<?php echo $result->demurrage; ?>">
								<label class="form-label">Demurrage</label>
							</div>
						</div>

						<div class="form-group form-float">
							<div class="form-line">
								<input type="number" step="0.01" min="0" class="form-control" name="price" onkeyup="autocompute();" id="price" value="<?php echo $result->price; ?>">
								<label class="form-label">Job Cost</label>
							</div>
						</div>

						<div class="form-group form-float">
							<div class="form-line">
								<input type="number" step="0.01" min="0" class="form-control" name="vat" id="vat" value="<?php echo $result->vat; ?>">
								<label class="form-label">VAT</label>
							</div>
						</div>

						<div class="form-group form-float">
							<div class="form-line">
								<input type="number" step="0.01" min="0" class="form-control" name="total_price" id="total_price" value="<?php echo $result->total_price; ?>">
								<label class="form-label">Gross</label>
							</div>
						</div>

						<div class="form-group form-float">
							<div class="form-line">
								<textarea class="form-control" style="max-height: 100px; " name="notes"><?php echo $result->notes; ?></textarea>
	
								<label class="form-label">Notes</label>
							</div>
							<div class="help-info">Optional</div>
						</div>

						<div class="form-group form-float" id="_bankTransferDiv">
							<h3>Bank Transfer Image</h3>
							<fieldset>
								<div class="form-group form-float">
									<div class="form-line">
										<input type="file" name="bank_slip[]" class="form-control" multiple="">
									</div>
								</div>
							</fieldset>
						</div>

						<div class="form-group form-float">
							<h3>Invoice Image</h3>
							<fieldset>
								<div class="form-group form-float">
									<div class="form-line">
										<input type="file" name="file[]" class="form-control" multiple="" accept="image/*">
									</div>
								</div>
							</fieldset>
						</div>
						
					</fieldset>

					<a class="btn btn-danger waves-effect" href="<?php echo site_url('app/customer/invoices'); ?>"><i class="material-icons" style="font-size: 16.5px;">block</i>  CANCEL</a>
					<button class="btn btn-primary waves-effect pull-right" type="submit"><i class="material-icons md-18" style="font-size: 17px;">done_all</i> SUBMIT</button>
				</form>
			</div>
		</div>
	</div>
</div>