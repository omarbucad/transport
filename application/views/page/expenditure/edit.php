<script type="text/javascript">
	$(document).ready(function(){
		var type = "<?php echo $this->input->post("paid_by"); ?> ";
		selectDropdown(type);

		if($(".custom-thumbnails").data("lightGallery")){
            $(".custom-thumbnails").data("lightGallery").destroy(true);
        }

		$('.custom-thumbnails').lightGallery({
	        thumbnail: true,
	        selector: 'a.c-thumbnails'
	    }); 
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
			case "1" :
				banktransfer.hide().find("input").prop("disabled" , true);
				chequeNumber.hide().find("input").prop("disabled" , true);
				_paidDate.show().find("input").prop("disabled" , false);
			break;
			case "2" :
				banktransfer.show().find("input").prop("disabled" , false);
				chequeNumber.hide().find("input").prop("disabled" , true);
				_paidDate.show().find("input").prop("disabled" , false);
			break;
			case "4" :
				banktransfer.hide().find("input").prop("disabled" , true);
				chequeNumber.show().find("input").prop("disabled" , false);
				_paidDate.show().find("input").prop("disabled" , false);
			break;
			case "5" :
				banktransfer.hide().find("input").prop("disabled" , true);
				chequeNumber.hide().find("input").prop("disabled" , true);
				_paidDate.hide().find("input").prop("disabled" , true);
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
		var vat = parseFloat($('#vat').val());
		var subtotal = parseFloat($('#subtotal').val());
		var discount = parseFloat($('#discount').val());

		var total_price = (vat + subtotal - discount);

		$('#vat').val(vat.toFixed(2));
		$('#total').val(total_price.toFixed(2));
	}

</script>

<div class="block-header">
	<h2>
		<ol class="breadcrumb" style="padding: 0px;">
			<li>
				<a href="<?php echo site_url('app/expenditures'); ?>">
					Expenditures
				</a>
			</li>
			<li class="active">
				  Add Expenditure
			</li>
		</ol>
	</h2>
</div>

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Add Expenditure</h2>
			</div>
			<div class="body">
				<form id="wizard_with_validation" action="<?php echo site_url('app/expenditures/edit/').$data->exp_id; ?>" enctype="multipart/form-data" method="POST">
					<?php echo validation_errors(); ?>

					<h3>Expenditure Information</h3>
					<fieldset>
						<div class="form-group form-float">
							<label>Category</label>
	                        <div class="form-line">
	                            <select class="form-control" name="category" value="<?php echo $data->category_id; ?>">
	                                <?php foreach($categoryList as $row) : ?>
	                                    <option value="<?php echo $row->category_id; ?>" <?php echo ($data->category_id == $row->category_id) ? "selected" : ""; ?>><?php echo $row->category_name; ?></option>
	                                <?php endforeach; ?>
	                            </select>
	                        </div>
						</div>
						<div class="row">
							<div class="col-md-6 col-xs-12">
								<div class="form-group form-float">
									<label>Payment Type</label>
			                        <div class="form-line">
			                            <select class="form-control" id="paid_by_select" name="paid_by" value="<?php echo $data->exp_type_id; ?>">
			                                <?php foreach($typeList as $row) : ?>
			                                    <option value="<?php echo $row->payment_type_id; ?>" <?php echo ($data->exp_type_id == $row->payment_type_id) ? "selected" : "" ; ?> ><?php echo $row->payment_type_name; ?></option>
			                                <?php endforeach; ?>
			                            </select>
			                        </div>
								</div>
							</div>
							<div class="col-md-6 col-xs-12">
								<div class="form-group form-float">
									<label>Status</label>
			                        <div class="">
			                            <select class="form-control" name="status" value="<?php echo $data->statuses[0]->status;?>" readonly="true">
			                               <option <?php echo ($data->statuses[0]->status == "UNPAID") ? "selected" : ""; ?> value="UNPAID">UNPAID</option>
			                               <option <?php echo ($data->statuses[0]->status == "PAID") ? "selected" : ""; ?> value="PAID">PAID</option>
			                            </select>
			                        </div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6 col-xs-12">
								<div class="form-group form-float">
									<div class="form-line">
										<input type="text"  class="form-control" name="invoice_number" value="<?php echo $data->invoice_number; ?>" >
										<label class="form-label">Invoice Number</label>
									</div>
									<div class="help-info">Optional</div>
								</div>
							</div>
							<div class="col-md-6 col-xs-12">
								<div class="form-group form-float">
									 <div class="form-line">
			                            <input type="text" class="datepicker form-control" name="invoice_date" value="<?php echo $data->invoice_date; ?>" >
			                            <label class="form-label">Invoice Date</label>
			                        </div>
								</div>
							</div>
						</div>
						<div class="form-group form-float" id="_chequeNumberDiv">
							<div class="form-line">
								<input type="text"  class="form-control" name="cheque_number" value="<?php echo $data->cheque_no; ?>" >
								<label class="form-label">Cheque No.</label>
							</div>
						</div>
						<div class="form-group form-float _paidDate">
							 <div class="form-line">
	                            <input type="text" class="datepicker form-control" id="_paidDate" name="paid_date" value="<?php echo $data->paid_date; ?>" >
	                            <label class="form-label">Paid Date</label>
	                        </div>
						</div>
						<div class="row">
							<div class="col-md-6 col-xs-12">
								<div class="form-group form-float">
									<div class="form-line">
										<input type="number" step="0.01" min="0" class="form-control" name="discount" id="discount"  onkeyup="autocompute();" value="<?php echo $data->discount_raw; ?>">
										<label class="form-label">Discount</label>
									</div>
								</div>
							</div>
							<div class="col-md-6 col-xs-12">
								<div class="form-group form-float">
									<div class="form-line">
										<input type="number" step="0.01" min="0" class="form-control" name="vat" id="vat"  onkeyup="autocompute();" value="<?php echo $data->vat_raw; ?>">
										<label class="form-label">VAT</label>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6 col-xs-12">
								<div class="form-group form-float">
									<div class="form-line">
										<input type="number" step="0.01" min="0" class="form-control" name="subtotal" id="subtotal"  onkeyup="autocompute();" value="<?php echo $data->subtotal_raw; ?>">
										<label class="form-label">Subtotal</label>
									</div>
								</div>
							</div>
							<div class="col-md-6 col-xs-12">
								<div class="form-group form-float">
									<div class="form-line">
										<input type="number" step="0.01" min="0" class="form-control" name="total" id="total" value="<?php echo $data->total_raw; ?>" readonly="true">
										<label class="form-label">Total</label>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" style="height:100px" name="notes" value="<?php echo $data->statuses[0]->notes; ?>">
								<label class="form-label">Notes</label>
							</div>
							<div class="help-info">Optional</div>
						</div>

						<div class="image-preview">
							<h3>Uploaded Images</h3>
							<fieldset>
								<?php foreach($data->statuses as $key => $row): ?>								
									<div class="custom-thumbnails">										
									<?php foreach($row->images as $k => $i) : ?>	
										<a href="<?php echo site_url('public/upload/expenditure/').$i->image_thumb;?>" class="c-thumbnails">
											<img src="<?php echo site_url('public/upload/expenditure/').$i->image_path.'/'.$i->image_name;?>"  style="width: 95px;height:auto;">
										</a>
									<?php endforeach; ?>
									</div>

								<?php endforeach; ?>					
							</fieldset>							
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
						<?php //print_r_die($data);?>

						<div class="form-group form-float">
							<h3>Expenditure Image</h3>
							<fieldset>
								<div class="form-group form-float">
									<div class="form-line">
										<input type="file" name="file[]" class="form-control" multiple="" accept="image/*">
									</div>
								</div>
							</fieldset>
						</div>
						
					</fieldset>

					<a class="btn btn-danger waves-effect" href="<?php echo site_url('app/expenditures'); ?>"><i class="material-icons" style="font-size: 16.5px;">block</i>  CANCEL</a>
					<button class="btn btn-primary waves-effect pull-right" type="submit"><i class="material-icons md-18" style="font-size: 17px;">done_all</i> SUBMIT</button>
				</form>
			</div>
		</div>
	</div>
</div>