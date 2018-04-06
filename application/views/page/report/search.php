<div class="card">
    <div class="header bg-red">
        <h2>
            Search Accounts
        </h2>
    </div>
    <div class="body">
        <?php if($this->uri->segment(3) == "daily") : ?>
             <form action="<?php echo site_url('app/reports/'.$this->uri->segment(3)); ?>/" method="get">
                <div class="row clearfix">
                    <div class="col-lg-4">
                        <label for="trailer_number">From</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="datepicker form-control" name="date_from" value="<?php echo $this->input->get('date_from') ?>" placeholder="Please choose a date...">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="trailer_number">To</label>
                            <div class="form-line"> 
                                <input type="text" class="datepicker form-control" name="date_to" value="<?php echo $this->input->get('date_to') ?>" placeholder="Please choose a date...">
                            </div>
                        </div>
                    </div>
                     <div class="col-lg-4">
                        <label for="name">Driver</label>
                        <div class="form-group">
                            <div class="form-line">
                                <select class="form-control show-tick" name="name" id="name">
                                    <option value="" >-- Please select --</option>
                                    <?php foreach($driver_list as $key => $row) : ?>
                                         <option value="<?php echo $row->id; ?>" <?php echo custom_set_select('name',  $row->id); ?>><?php echo  $row->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <label for="trailer_number">Trailer Number</label>
                        <div class="form-group">
                            <div class="form-line">
                                <select class="form-control show-tick" name="trailer_number" id="trailer_number">
                                    <option value="" >-- Please select --</option>
                                    <?php foreach($trailer_number_list as $key => $row) : ?>
                                         <option value="<?php echo $row['trailer_number']; ?>" <?php echo custom_set_select('trailer_number', $row['id']); ?>><?php echo $row['trailer_number']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label for="vehicle">Vehicle</label>
                        <div class="form-group">
                            <div class="form-line">
                                <select class="form-control show-tick" name="vehicle" id="vehicle">
                                    <option value="" >-- Please select --</option>
                                    <?php foreach($vehicle_list as $key => $row) : ?>
                                         <option value="<?php echo $row['vehicle_number']; ?>" <?php echo custom_set_select('vehicle', $row['id']); ?>><?php echo $row['vehicle_number']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary waves-effect" type="submit" name="submit" value="submit"><i class="material-icons" style="position: relative;font-size: 16.5px;">done</i> SUBMIT</button>
            </form>    
        <?php else : ?>
           <form action="<?php echo site_url('app/reports/'.$this->uri->segment(3)); ?>/" method="get">
                <div class="row clearfix">
                    <div class="col-lg-4">
                        <label for="trailer_number">From</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="datepicker form-control" name="date_from" value="<?php echo $this->input->get('date_from') ?>" placeholder="Please choose a date...">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="trailer_number">To</label>
                            <div class="form-line"> 
                                <input type="text" class="datepicker form-control" name="date_to" value="<?php echo $this->input->get('date_to') ?>" placeholder="Please choose a date...">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="trailer_number">Report #</label>
                            <div class="form-line"> 
                                <input type="text" class="form-control" name="report_id" value="<?php echo $this->input->get('report_id') ?>" placeholder="Report #">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <label for="name">Driver</label>
                        <div class="form-group">
                            <div class="form-line">
                                <select class="form-control show-tick" name="name" id="name">
                                    <option value="" >-- Please select --</option>
                                    <?php foreach($driver_list as $key => $row) : ?>
                                         <option value="<?php echo $row->id; ?>" <?php echo custom_set_select('name',  $row->id); ?>><?php echo  $row->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label for="trailer_number">Trailer Number</label>
                        <div class="form-group">
                            <div class="form-line">
                                <select class="form-control show-tick" name="trailer_number" id="trailer_number">
                                    <option value="" >-- Please select --</option>
                                    <?php foreach($trailer_number_list as $key => $row) : ?>
                                         <option value="<?php echo $row['id']; ?>" <?php echo custom_set_select('trailer_number', $row['id']); ?>><?php echo $row['trailer_number']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label for="vehicle">Vehicle</label>
                        <div class="form-group">
                            <div class="form-line">
                                <select class="form-control show-tick" name="vehicle" id="vehicle">
                                    <option value="" >-- Please select --</option>
                                    <?php foreach($vehicle_list as $key => $row) : ?>
                                         <option value="<?php echo $row['id']; ?>" <?php echo custom_set_select('vehicle', $row['id']); ?>><?php echo $row['vehicle_number']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php if($this->uri->segment(2) == 'defect') : ?>
                    <div class="col-lg-3">
                        <label for="status">Status</label>
                        <div class="form-group">
                            <div class="form-line">
                                <select class="form-control show-tick" name="status" id="status">
                                    <option value="all" >-- ALL--</option>
                                    <option value="1" <?php echo custom_set_select('status', '1'); ?>>Open</option>
                                    <option value="2" <?php echo custom_set_select('status', '2'); ?>>Under Maintenance</option>
                                    <option value="3" <?php echo custom_set_select('status', '3'); ?>>Fixed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>

                    <?php endif; ?>
                </div>
                <button class="btn btn-primary waves-effect" type="submit" name="submit"><i class="material-icons" style="position: relative;font-size: 16.5px;">done</i> SUBMIT</button>
            </form>
        <?php endif; ?>
        
    </div>
</div>