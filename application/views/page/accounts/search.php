<div class="card">
    <div class="header bg-green">
        <h2>
            Search Accounts
        </h2>
    </div>
    <div class="body">
        <form action="" method="get">
            <div class="row">
                <div class="col-lg-3">
                    <label for="name">Name</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="name" class="form-control" name="name" value="<?php echo @$_GET['name']; ?>" placeholder="Firstname or  Lastname">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <label for="account_type">Account</label>
                    <div class="form-group">
                        <div class="form-line">
                            <select class="form-control show-tick" name="account_type" id="account_type">
                                <option value="" >-- Please select --</option>
                                <?php $this->load->view('common/account_type' , array("type" => "search")); ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <label for="status">Status</label>
                    <div class="form-group">
                        <div class="form-line">
                            <select class="form-control show-tick" name="status" id="status">
                                <option value="" >-- ALL--</option>
                                <option value="active" <?php echo custom_set_select('status', 'active'); ?>>Active</option>
                                <option value="inactive" <?php echo custom_set_select('status', 'inactive'); ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary waves-effect" type="submit" name="submit"><i class="material-icons" style="position: relative;font-size: 16.5px;">done</i> SUBMIT</button>
        </form>
    </div>
</div>