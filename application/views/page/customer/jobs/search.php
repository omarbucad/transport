<div class="card">
    <div class="header bg-green">
        <h2>
            Search Accounts
        </h2>
    </div>
    <div class="body">
        <form action="<?php echo site_url("app/customer/jobs/"); ?>" method="get" >

            <input type="hidden" name="sort" value="<?php echo($this->input->get("sort")) ? $this->input->get("sort") : "DESC" ?>" id="_sort_jobs">
            <input type="hidden" name="sort_by" value="<?php echo ($this->input->get("sort_by")) ? $this->input->get("sort_by") : "jp.created" ?>" id="_sort_by_jobs">
            <input type="hidden" name="limit" value="<?php echo ($this->input->get("limit")) ? $this->input->get("limit") : "50" ?>" id="_limit_jobs">
            <input type="hidden" name="j_status" value="<?php echo($this->input->get("j_status")) ? $this->input->get("j_status") : "" ?>" id="_j_status">
            
            <div class="row">
                <div class="col-xs-12 col-lg-10">
                    <div class="row">
                        <div class="col-lg-4 col-xs-12">
                            <label>Job ID</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="transaction_number" value="<?php echo $this->input->get("transaction_number"); ?>">
                                </div>
                            </div>
                        </div>

                        <?php if($this->session->userdata("account_type") == SUPER_ADMIN) : ?>

                        <div class="col-lg-4 col-xs-12">
                            <label>Customer Name</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control _CustomerName" list="_CustomerName" name="customer_name" value="<?php echo $this->input->get("customer_name"); ?>">
                                    <datalist id="_CustomerName">
                                        <?php foreach($select['customer_name'] as $job_name => $r) : ?>
                                             <option value="<?php echo $r->company_name; ?>">   
                                        <?php endforeach; ?>
                                    </datalist>
                                </div>
                            </div>
                        </div>
                        <?php else : ?>

                            <div class="col-lg-4 col-xs-12">
                                <label>Status</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="status">
                                            <option value="all">-- All --</option>
                                            <option value="new" <?php echo custom_set_select('status', "new"); ?>>New</option>
                                            <option value="to_be_allocated" <?php echo custom_set_select('status', "to_be_allocated"); ?>>Truck To Be Allocated</option>
                                            <option value="allocated" <?php echo custom_set_select('status', "allocated"); ?>>Truck Allocated</option>
                                            <option value="finished" <?php echo custom_set_select('status', "finished"); ?>>Completed</option>
                                            <option value="cancel" <?php echo custom_set_select('status', "cancel"); ?>>Cancel</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        <?php endif; ?>

                        <div class="col-lg-4 col-xs-12">
                            <label for="name">Job Name</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" list="_jobName" name="job_name" value="<?php echo $this->input->get("job_name"); ?>">
                                    <datalist id="_jobName">
                                        <?php foreach($select['job_name'] as $job_name => $r) : ?>
                                             <option value="<?php echo $job_name; ?>">   
                                        <?php endforeach; ?>
                                    </datalist>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">

                        <div class="col-lg-4 col-xs-12">
                            <label for="name">Type of truck:</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <select class="form-control show-tick" name="type_of_truck">
                                        <option value="">-- Please select --</option>
                                        <?php foreach($type_of_truck as $row) : ?>
                                            <option value="<?php echo $row->truck_type; ?>" <?php echo custom_set_select('type_of_truck', $row->truck_type); ?> ><?php echo $row->truck_type; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-xs-12">
                            <label>Driver Name</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <select class="form-control show-tick" name="driver_name" data-live-search="true">
                                        <option value=""> - Select Driver - </option>
                                        <?php foreach($select["driver"] as $store_name => $driver) : ?>
                                            <optgroup label="<?php echo $store_name; ?>">
                                                <?php foreach($driver as $d) : ?>
                                                    <option value="<?php echo $d->id; ?>" <?php echo custom_set_select('driver_name', $d->id); ?>><?php echo $d->name; ?></option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-xs-12">
                            <label>Truck Registration Number</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <select class="form-control show-tick" name="truck_registration_number"  data-live-search="true">
                                        <option value=""> - Truck Registration Number - </option>
                                        <?php foreach($select["truck"] as $store_name => $truck) : ?>
                                            <?php foreach($truck as $type_of_truck => $t) : ?>
                                                <optgroup label="<?php echo $type_of_truck; ?>">
                                                    <?php foreach($t as $x) : ?>
                                                        <option value="<?php echo $x->id; ?>" <?php echo custom_set_select('truck_registration_number', $x->id); ?>><?php echo $x->vehicle_number; ?></option>
                                                    <?php endforeach; ?>
                                                </optgroup>  
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">


                        <div class="col-lg-4 col-xs-12">
                            <label>Loading Time <br> <small>From:</small></label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="datepicker form-control" name="loading_time_from" value="<?php echo $this->input->get("loading_time_from"); ?>" placeholder="Please choose a date...">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-xs-12">
                            <label> <br> <small>To:</small></label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="datepicker form-control" name="loading_time_to" value="<?php echo $this->input->get("loading_time_to"); ?>" placeholder="Please choose a date...">
                                </div>
                            </div>
                        </div>

                        <?php if($this->session->userdata("account_type") == SUPER_ADMIN) : ?>
                        <div class="col-lg-4 col-xs-12">
                            <label>&nbsp; <br> <small>Status:</small></label>
                            <div class="form-group">
                                <div class="form-line">
                                    <select class="form-control show-tick" name="status">
                                        <option value="all">-- All --</option>
                                        <option value="new" <?php echo custom_set_select('status', "new"); ?>>New</option>
                                        <option value="to_be_allocated" <?php echo custom_set_select('status', "to_be_allocated"); ?>>Truck To Be Allocated</option>
                                        <option value="allocated" <?php echo custom_set_select('status', "allocated"); ?>>Truck Allocated</option>
                                        <option value="finished" <?php echo custom_set_select('status', "finished"); ?>>Completed</option>
                                        <option value="cancel" <?php echo custom_set_select('status', "cancel"); ?>>Cancel</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-xs-12">
                            <label>Delivery Time <br><small>From:</small></label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="datepicker form-control" name="delivery_time_from" value="<?php echo $this->input->get("delivery_time_from"); ?>" placeholder="Please choose a date...">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-xs-12">
                            <label><br> <small>To:</small></label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="datepicker form-control" name="delivery_time_to" value="<?php echo $this->input->get("delivery_time_to"); ?>" placeholder="Please choose a date...">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-xs-12">
                            <label>Created <br><small>From:</small></label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="datepicker form-control" name="created_date_from" value="<?php echo $this->input->get("created_date_from"); ?>" placeholder="Please choose a date...">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-xs-12">
                            <label><br> <small>To:</small></label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="datepicker form-control" name="created_date_to" value="<?php echo $this->input->get("created_date_to"); ?>" placeholder="Please choose a date...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-lg-2">
                    <button class="btn btn-primary btn-block waves-effect" type="submit" name="submit" value="submit" id="jobs_search_form"><i class="material-icons" style="position: relative;font-size: 16.5px;">done</i> Apply Filter</button>
                </div>
            </div>

        </form>
    </div>
</div>

