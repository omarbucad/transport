<div class="card">
    <div class="header bg-green">
        <h2>
            Search Trailer Numbers
        </h2>
    </div>
    <div class="body">
        <form action="<?php echo site_url("app/trailer"); ?>" method="get">
            <div class="row">

                <div class="col-lg-4 col-xs-12">
                    <label>Short Trailer Number</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="" class="form-control" name="short_trailer_number" value="<?php echo $this->input->get("short_trailer_number")?>" autocomplete="false">
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-xs-12">
                    <label>Trailer Number</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="" class="form-control" name="trailer_number" value="<?php echo $this->input->get("trailer_number")?>" autocomplete="false">
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-xs-12">
                    <label>Make</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" name="trailer_make" value="<?php echo $this->input->get("trailer_make")?>">
                        </div>
                    </div>
                </div>

                 
            </div>

            <div class="row">
                <div class="col-lg-4 col-xs-12">
                    <label>Type</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" name="trailer_type" value="<?php echo $this->input->get("trailer_type")?>">
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-xs-12">
                    <label>Axle</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" name="trailer_axle" value="<?php echo $this->input->get("trailer_axle")?>">
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-xs-12">
                    <label for="status">Status</label>
                    <div class="form-group">
                        <div class="form-line">
                            <select class="form-control show-tick" name="status">
                                <option value="">-- Please select --</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
               
            </div>


            <button class="btn btn-primary waves-effect" type="submit" name="submit" value="submit"><i class="material-icons" style="position: relative;font-size: 16.5px;">done</i> SUBMIT</button>
        </form>
    </div>
</div>

