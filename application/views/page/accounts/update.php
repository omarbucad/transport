<script type="text/javascript">
	$(document).on('change' , '#account_type' , function(){
		var account_type = $(this).val();
		if(account_type == "<?php echo SUPER_ADMIN; ?>"){
			$('#store_info_div').css('display' , 'none');
			$('#store_info_div #store_select_multiple select').prop('disabled' , true);
			$('#store_info_div #store_select_single select').prop('disabled' , true);
		}else if(account_type == "<?php echo DRIVER; ?>"){
			$('#store_info_div').css('display' , 'block');
			$('#store_info_div #store_select_single').css('display' , 'block');
			$('#store_info_div #store_select_multiple').css('display' , 'none');
			$('#store_info_div #store_select_multiple select').prop('disabled' , true);
			$('#store_info_div #store_select_single select').prop('disabled' , false);
		}else{
			$('#store_info_div').css('display' , 'block');
			$('#store_info_div #store_select_single').css('display' , 'none');
			$('#store_info_div #store_select_multiple').css('display' , 'block');
			$('#store_info_div #store_select_multiple select').prop('disabled' , false);
			$('#store_info_div #store_select_single select').prop('disabled' , true);
		}
	});
</script>
<div class="block-header">
	<h2>
		<ol class="breadcrumb" style="padding: 0px;">
			<li>
				<a href="<?php echo site_url('accounts'); ?>">
					Accounts
				</a>
			</li>
			<li class="active">
				  Update Account
			</li>
		</ol>
	</h2>
</div>

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Update account</h2>
			</div>
			<div class="body">
				<form id="wizard_with_validation" action="<?php echo site_url('app/accounts/updateAccount/'.$result->id) ?>" enctype="multipart/form-data" method="POST">
					<input type="hidden" name="id" value="<?php echo $result->id; ?>">
					<input type="hidden" name="oldEmail" value="<?php echo $result->email; ?>">

					<?php echo validation_errors(); ?>

					<h3>Profile Information</h3>
					<fieldset>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" name="name" class="form-control" value="<?php echo $result->name; ?>">
								<label class="form-label">First Name*</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" name="surname" value="<?php echo $result->surname; ?>" class="form-control">
								<label class="form-label">Last Name</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="email" name="email" class="form-control" value="<?php echo $result->email; ?>">
								<label class="form-label">Email*</label>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<textarea name="address"  rows="3" class="form-control no-resize"><?php echo $result->address; ?></textarea>
								<label class="form-label">Address</label>
							</div>
						</div>
					</fieldset>
					<h3>Account Information</h3>
					<fieldset>
						<div class="form-group form-float">
							<div class="form-line">
								<select class="form-control show-tick" name="account_type">
									<option value="" >-- Please select --</option>
									<?php $this->load->view('common/account_type' , array("type" => "update" , "result" => $result)); ?>
								</select>
							</div>
						</div>
						<div class="form-group form-float">
							<div class="form-line">
								<select class="form-control show-tick" name="status" required>
									<option value="1" <?php echo custom_set_select(false, 1 , $result->status); ?>>Active</option>
									<option value="0" <?php echo custom_set_select(false, 0 , $result->status); ?>>Inactive</option>
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