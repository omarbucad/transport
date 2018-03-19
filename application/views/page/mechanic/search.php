<div class="card">
    <div class="header bg-red">
        <h2>
            Search Checklist Report
        </h2>
    </div>
    <div class="body">
             <form action="<?php echo site_url('app/mechanic/'); ?>" method="get">
                <div class="row clearfix">
                    <div class="col-lg-4">
                        <label for="date_from">From</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="datepicker form-control" name="date_from" value="<?php echo $this->input->get('date_from') ?>" placeholder="Please choose a date...">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="date_to">To</label>
                            <div class="form-line"> 
                                <input type="text" class="datepicker form-control" name="date_to" value="<?php echo $this->input->get('date_to') ?>" placeholder="Please choose a date...">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label for="report_type">Report Type</label>
                        <div class="form-group">
                            <div class="form-line">
                                <select class="form-control show-tick" name="report_type" id="report_type">
                                    <option value="" >-- Please select --</option>
                                    <option value="DEFECT" >DEFECT</option>
                                    <option value="SERVICEABLE">SERVICEABLE</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                     <div class="col-lg-4">
                        <div class="form-group">
                            <label for="report_id">Report #</label>
                            <div class="form-line"> 
                                <input type="text" class="form-control" name="report_id" value="<?php echo $this->input->get('report_id') ?>" placeholder="Report #">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="fleet_no">Fleet No</label>
                            <div class="form-line"> 
                                <input type="text" class="form-control" name="fleet_no" value="<?php echo $this->input->get('fleet_no') ?>" placeholder="Fleet No">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label for="registration_number">Registration Number</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control" name="registration_number" id="registration_number" list="reg_number_list" placeholder="Registration Number">
                                <datalist id="reg_number_list">
                                    <?php foreach($vehicle_list as $key => $row) : ?>
                                         <option value="<?php echo $row['vehicle_number']; ?>" ><?php echo  $row['vehicle_number']; ?></option>
                                    <?php endforeach; ?>
                                </datalist>
                                </select>
                            </div>
                        </div>
                    </div>                    
                </div>
                <button class="btn btn-primary waves-effect" type="submit" name="submit" value="submit"><i class="material-icons" style="position: relative;font-size: 16.5px;">done</i> SUBMIT</button>
            </form>        
    </div>
</div>