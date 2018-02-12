<div class="card">
    <div class="header bg-green">
        <h2>
            Search Outsource Accounts
        </h2>
    </div>
    <div class="body">
        <form action="<?php echo site_url("app/outsource/"); ?>" method="get">
            <div class="row">

                <div class="col-lg-4 col-xs-12">
                    <label>Company Name:</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="" class="form-control" name="company_name" value="<?php echo $this->input->get("company_name")?>">
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-xs-12">
                    <label>Registration Number:</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" name="registration_number" value="<?php echo $this->input->get("registration_number")?>">
                        </div>
                    </div>
                </div>

                 <div class="col-lg-4 col-xs-12">
                    <label>VAT Number:</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" name="vat_number" value="<?php echo $this->input->get("vat_number")?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-4 col-xs-12">
                    <label>Email:</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" name="email" value="<?php echo $this->input->get("email")?>">
                        </div>
                    </div>
                </div>

               
            </div>


            <button class="btn btn-primary waves-effect" type="submit" name="submit" value="submit"><i class="material-icons" style="position: relative;font-size: 16.5px;">done</i> SUBMIT</button>
        </form>
    </div>
</div>

