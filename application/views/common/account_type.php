<?php if($type == "add") : ?>
	<option value="SUPER ADMIN" <?php echo set_select('account_type', SUPER_ADMIN); ?>>Super Admin</option>
	<option value="ADMIN" <?php echo set_select('account_type', ADMIN); ?>>Admin</option>
	<option value="DRIVER" <?php echo set_select('account_type', DRIVER); ?>>Driver</option>
	<option value="MECHANIC" <?php echo set_select('account_type', MECHANIC); ?>>Mechanic</option>
	<option value="CUSTOMER" <?php echo set_select('account_type', CUSTOMER); ?>>Customer</option>
	<option value="WAREHOUSE" <?php echo set_select('account_type', WAREHOUSE); ?>>Warehouse</option>
	<option value="OUTSOURCE" <?php echo set_select('account_type', OUTSOURCE); ?>>Outsource</option>
<?php elseif($type == "update") : ?>
	<option value="SUPER ADMIN" <?php echo custom_set_select(false, 'SUPER ADMIN' , $result->account_type); ?>>Super Admin</option>
	<option value="ADMIN" <?php echo custom_set_select(false, 'ADMIN' , $result->account_type); ?>>Admin</option>
	<option value="DRIVER" <?php echo custom_set_select(false, 'DRIVER' , $result->account_type); ?>>Driver</option>
	<option value="MECHANIC" <?php echo custom_set_select(false, 'MECHANIC' , $result->account_type); ?>>Mechanic</option>
	<option value="CUSTOMER" <?php echo custom_set_select(false, 'CUSTOMER' , $result->account_type); ?>>Customer</option>
	<option value="WAREHOUSE" <?php echo custom_set_select(false, 'WAREHOUSE' , $result->account_type); ?>>Warehouse</option>
	<option value="OUTSOURCE" <?php echo custom_set_select(false, 'OUTSOURCE' , $result->account_type); ?>>Outsource</option>
<?php else : ?>
	<option value="SUPER ADMIN" <?php echo custom_set_select('account_type', SUPER_ADMIN); ?>>Super Admin</option>
	<option value="ADMIN" <?php echo custom_set_select('account_type', ADMIN); ?>>Admin</option>
	<option value="DRIVER" <?php echo custom_set_select('account_type', DRIVER); ?>>Driver</option>
	<option value="MECHANIC" <?php echo custom_set_select('account_type', MECHANIC); ?>>Mechanic</option>
	<option value="CUSTOMER" <?php echo custom_set_select('account_type', CUSTOMER); ?>>Customer</option>
	<option value="WAREHOUSE" <?php echo custom_set_select('account_type', WAREHOUSE); ?>>Warehouse</option>
	<option value="OUTSOURCE" <?php echo custom_set_select('account_type', OUTSOURCE); ?>>Outsource</option>
<?php endif; ?>