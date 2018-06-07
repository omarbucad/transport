<div class="card">
    <div class="header bg-red">
        <h2>
            Search Accident Report
        </h2>
    </div>
    <div class="body">
             <form action="<?php echo site_url('app/reports/accident'); ?>" method="get">
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
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="emergency_id">Accident Report #</label>
                            <div class="form-line"> 
                                <input type="text" class="form-control" name="emergency_id" value="<?php echo $this->input->get('emergency_id') ?>" placeholder="Accident Report #">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label for="driver_id">Driver Name</label>
                        <div class="form-group">
                            <div class="form-line">
                                <select class="form-control" name="driver_id" id="driver_id">
                                    <option value="">- Select Driver -</option>
                                    <?php foreach($driver_list as $key => $row) : ?>
                                         <option value="<?php echo $row->id; ?>" ><?php echo  $row->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>    
                </div>
                <button class="btn btn-primary waves-effect" type="submit" name="submit" value="submit"><i class="material-icons" style="position: relative;font-size: 16.5px;">done</i> SUBMIT</button>
            </form>        
    </div>
</div>