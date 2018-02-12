<script type="text/javascript">
	$(document).on('change' , '#account_type' , function(){
		var account_type = $(this).val();

		if(account_type == "<?php echo SUPER_ADMIN; ?>"){
			$('#store_info_div').css('display' , 'none');
			$('#store_info_div #store_select_multiple select').prop('disabled' , true);
			$('#store_info_div #store_select_single select').prop('disabled' , true);
			
			$('#customer_list_div').css("display" , 'none');

		}else if(account_type == "<?php echo DRIVER; ?>" || account_type == "<?php echo WAREHOUSE; ?>"){
	
			$('#store_info_div').css('display' , 'block');
			$('#store_info_div #store_select_single').css('display' , 'block');
			$('#store_info_div #store_select_multiple').css('display' , 'none');
			$('#store_info_div #store_select_multiple select').prop('disabled' , true);
			$('#store_info_div #store_select_single select').prop('disabled' , false);

			$('#customer_list_div').css("display" , 'none');

		}else if(account_type == "<?php echo CUSTOMER; ?>"){

			$('#store_info_div').css('display' , 'block');
			$('#store_info_div #store_select_single').css('display' , 'none');
			$('#store_info_div #store_select_multiple').css('display' , 'block');
			$('#store_info_div #store_select_multiple select').prop('disabled' , false);
			$('#store_info_div #store_select_single select').prop('disabled' , true);

			$('#customer_list_div').css("display" , 'block');
		}else{
			$('#store_info_div').css('display' , 'block');
			$('#store_info_div #store_select_single').css('display' , 'none');
			$('#store_info_div #store_select_multiple').css('display' , 'block');
			$('#store_info_div #store_select_multiple select').prop('disabled' , false);
			$('#store_info_div #store_select_single select').prop('disabled' , true);

			$('#customer_list_div').css("display" , 'none');
		}
	});
</script>

<div class="block-header">
	<h2>
		<ol class="breadcrumb" style="padding: 0px;">
			<li>
				<a href="<?php echo site_url('app/accounts'); ?>">
					Accounts
				</a>
			</li>
			<li class="active">
				  Create Account
			</li>
		</ol>
	</h2>
</div>

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Create your account</h2>
			</div>
			<div class="body">
				<form id="wizard_with_validation" action="<?php echo site_url('app/accounts/add') ?>" enctype="multipart/form-data" method="POST">
					<?php echo validation_errors(); ?>
					<h3>Account Information</h3>
					<fieldset>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" name="username" value="<?php echo set_value('username'); ?>" >
								<label class="form-label">Username*</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="password" class="form-control" name="password" value="<?php echo set_value('password'); ?>" id="password" >
								<label class="form-label">Password*</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="password" class="form-control" name="confirm" >
								<label class="form-label">Confirm Password*</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<select class="form-control show-tick" name="account_type" id="account_type" >
									<option value="" >-- Please select --</option>
									<?php $this->load->view('common/account_type' , array("type" => "add")); ?>
								</select>
							</div>
						</div>
						
					</fieldset>
					<div id="store_info_div" style="display: none">
						<h3>Store Information</h3>
						<fieldset>
							<div class="form-group form-float">
								<div class="form-line" style="display: none" id="store_select_multiple">
									<select class="form-control show-tick" name="store_id[]" multiple>
										<?php foreach($store_list as $row): ?>
											<option value="<?php echo $row->store_id; ?>"><?php echo $row->store_name; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="form-line" style="display: none" id="store_select_single">
									<select class="form-control show-tick" name="store_id[]">
										<?php foreach($store_list as $row): ?>
											<option value="<?php echo $row->store_id; ?>"><?php echo $row->store_name; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</fieldset>
					</div>
					<div id="customer_list_div" style="display: none;">
						<h3>Customer Information</h3>
						<fieldset>
							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control show-tick" name="customer_id">
										<?php foreach($customerList as $row): ?>
											<option value="<?php echo $row->customer_id; ?>"><?php echo $row->company_name; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</fieldset>
					</div>
					<h3>Profile Information</h3>
					<fieldset>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" name="name" class="form-control" value="<?php echo set_value('name'); ?>" >
								<label class="form-label">First Name*</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" name="surname" value="<?php echo set_value('surname'); ?>" class="form-control">
								<label class="form-label">Last Name</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="email" name="email" class="form-control" value="<?php echo set_value('email'); ?>" >
								<label class="form-label">Email*</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<textarea name="address" cols="30" rows="3" class="form-control no-resize"><?php echo set_value('address'); ?></textarea>
								<label class="form-label">Address</label>
							</div>
						</div>
					</fieldset>
					<h3>Profile Image</h3>
					<fieldset>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="file" name="file" class="form-control">
							</div>
						</div>
					</fieldset>
					<a class="btn btn-danger waves-effect" href="<?php echo site_url('app/accounts'); ?>"><i class="material-icons" style="font-size: 16.5px;">block</i> CANCEL</a>
					<button class="btn btn-primary waves-effect pull-right" type="submit"><i class="material-icons" style="font-size: 16.5px;">done_all</i> SUBMIT</button>
				</form>
			</div>
		</div>
	</div>
</div>