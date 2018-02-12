<div class="card">
    <div class="header bg-green">
        <h2>
            Search Job Truck List
        </h2>
    </div>
    <div class="body">
        <form action="<?php echo site_url("app/warehouse/"); ?>" method="get">
           

             <div class="row">

                   
                    <div class="col-lg-4 col-xs-12">
                        <label>Job Name</label>
                        <div class="form-group">
                            <div class="form-line">
                               <input type="text" class="form-control" name="job_name" value="<?php echo $this->input->get("job_name"); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-xs-12">
                        <label>Driver Name</label>
                        <div class="form-group">
                            <div class="form-line">
                               <input type="text" class="form-control" name="driver_name" value="<?php echo $this->input->get("driver_name")?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-xs-12">
                        <label>Truck Number</label>
                        <div class="form-group">
                            <div class="form-line">
                               <input type="text" class="form-control" name="vehicle_number" value="<?php echo $this->input->get("vehicle_number")?>">
                            </div>
                        </div>
                    </div>

            </div>

            <div class="row">


                    <div class="col-lg-4 col-xs-12">
                        <label>Trailer Number <br> <small>&nbsp;</small></label>
                        <div class="form-group">
                            <div class="form-line">
                               <input type="text" class="form-control" name="trailer_number" value="<?php echo $this->input->get("trailer_number")?>">
                            </div>
                        </div>
                    </div>

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


            </div>

             <div class="row">
                   
                     <div class="col-lg-4 col-xs-12">
                    <label>Delivery Time <br> <small>From:</small></label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="datepicker form-control" name="delivery_time_from" value="<?php echo $this->input->get("delivery_time_from"); ?>" placeholder="Please choose a date...">
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-xs-12">
                    <label> <br> <small>To:</small></label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="datepicker form-control" name="delivery_time_to" value="<?php echo $this->input->get("delivery_time_to"); ?>" placeholder="Please choose a date...">
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-xs-12">
                        <label>Status <br> <small>&nbsp;</small></label>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <select class="form-control" name="status">
                                    <option value="incomplete" <?php echo ($this->input->get("status") == "incomplete") ? "selected" : "" ?> >Incomplete</option>
                                    <option value="complete" <?php echo ($this->input->get("status") == "complete") ? "selected" : "" ?> >Complete</option>
                                </select>
                            </div>
  
                        </div>
                    </div>

            </div>


          

            <button class="btn btn-primary waves-effect" type="submit" name="submit" value="submit"><i class="material-icons" style="position: relative;font-size: 16.5px;">done</i> SUBMIT</button>
        </form>
    </div>
</div>

