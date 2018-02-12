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
				  Add Invoice
			</li>
		</ol>
	</h2>
</div>

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Add Invoice</h2>
			</div>
			<div class="body">
				<form id="wizard_with_validation" action="<?php echo site_url('app/customer/add_invoice') ?>" enctype="multipart/form-data" method="POST">
					<?php echo validation_errors(); ?>

					<h3>Invoice Information</h3>
					<fieldset>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text"  class="form-control" name="invoice_no" value="<?php echo set_value("invoice_no"); ?>" >
								<label class="form-label">Invoice No.</label>
							</div>
							<div class="help-info">Optional</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text"  class="form-control" name="job_no" value="<?php echo set_value("job_no"); ?>" >
								<label class="form-label">Job No.</label>
							</div>
							<div class="help-info">Optional</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text"  class="form-control" name="jpo_number" value="<?php echo set_value("jpo_number"); ?>" >
								<label class="form-label">Purchase Order number.</label>
							</div>
							<div class="help-info">Optional</div>
						</div>
						<div class="form-group form-float">
							<label>Customer Name</label>
	                        <div class="form-line">
	                            <select class="form-control" name="company_name">
	                                <?php foreach($customerList as $row) : ?>
	                                    <option value="<?php echo $row->customer_id; ?>"><?php echo $row->company_name; ?></option>
	                                <?php endforeach; ?>
	                            </select>
	                        </div>
						</div>

						<div class="form-group form-float">
							<label>Paid By</label>
							<div class="form-line">
								<select class="form-control show-tick" name="paid_by" id="paid_by_select" >
									<option value="UNPAID" selected="selected">UNPAID</option>
									<option value="BANK_TRANSFER" >BANK TRANSFER</option>
									<option value="PAID_BY_CHEQUE" >PAID BY CHEQUE</option>
									<option value="PETTY_CASH" >CASH</option>
									<option value="DEBITCARD" >DEBIT CARD</option>
									<option value="COD_DEPOSIT" >COD DEPOSIT</option>
								</select>
							</div>
						</div>


						<div class="form-group form-float" id="_chequeNumberDiv">
							<div class="form-line">
								<input type="text"  class="form-control" name="cheque_number" value="<?php echo set_value("cheque_number"); ?>" >
								<label class="form-label">Cheque No.</label>
							</div>
						</div>
						<div class="form-group form-float _paidDate">
							 <div class="form-line">
	                            <input type="text" class="datepicker form-control" id="_paidDate" name="paid_date" value="<?php echo set_value("paid_date"); ?>" >
	                            <label class="form-label">Paid Date</label>
	                        </div>
						</div>

						<div class="form-group form-float">
							 <div class="form-line">
	                            <input type="text" class="datepicker form-control" name="invoice_date" value="<?php echo set_value("invoice_date"); ?>" >
	                            <label class="form-label">Invoice Date</label>
	                        </div>
						</div>

						<div class="form-group form-float">
							<div class="form-line">
								<input type="number" step="0.01" min="0" class="form-control" name="demurrage" onkeyup="autocompute();" id="demurrage" value="<?php echo set_value('demurrage'); ?>">
								<label class="form-label">Demurrage</label>
							</div>
						</div>

						<div class="form-group form-float">
							<div class="form-line">
								<input type="number" step="0.01" min="0" class="form-control" name="price" id="price" onkeyup="autocompute();" value="<?php echo set_value('price'); ?>">
								<label class="form-label">Job Cost</label>
							</div>
						</div>

						<div class="form-group form-float">
							<div class="form-line focused">
								<input type="number" step="0.01" min="0" class="form-control" name="vat" id="vat" value="<?php echo set_value('vat'); ?>">
								<label class="form-label">VAT</label>
							</div>
						</div>

						<div class="form-group form-float">
							<div class="form-line focused">
								<input type="number" step="0.01" min="0" class="form-control" name="total_price" id="total_price" value="<?php echo set_value('total_price'); ?>">
								<label class="form-label">Gross</label>
							</div>
						</div>

						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" style="height:100px" name="notes" value="<?php echo set_value('notes'); ?>">
								<label class="form-label">Notes</label>
							</div>
							<div class="help-info">Optional</div>
						</div>

						<div class="form-group form-float" id="_bankTransferDiv">
							<h3>Bank Transfer Image</h3>
							<fieldset>
								<div class="form-group form-float">
									<div class="form-line">
										<input type="file" name="bank_slip[]" class="form-control" multiple="" required="">
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