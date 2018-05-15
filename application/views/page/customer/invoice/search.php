<script type="text/javascript">
    $(document).on("click", "input[name=is_merge]", function(){
        var val = $(this).prop("checked");
        $(this).attr("value",val);
    });
</script>
<div class="card">
    <div class="header bg-green">
        <h2>
            Search Invoice
        </h2>
    </div>
    <div class="body">
        <form action="<?php echo site_url("app/customer/invoices/"); ?>" method="get" id="_fis">
            <input type="hidden" name="outsource" value="all" id="_outsource">

            <div class="row">
                <div class="col-xs-12 col-lg-10">
                    <div class="row">
                        <div class="col-lg-3 col-xs-12">
                            <label>Invoice Number:</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="" class="form-control" name="invoice_number" value="<?php echo $this->input->get("invoice_number")?>">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-xs-12">
                            <label>Job Number:</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="" class="form-control" name="job_number" value="<?php echo $this->input->get("job_number")?>">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-xs-12">
                            <label for="name">Job Name:</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" list="_jobName" name="job_name" value="<?php echo $this->input->get("job_name"); ?>">
                                    <datalist id="_jobName"  >
                                        <?php foreach($select['job_name'] as $job_name => $r) : ?>
                                             <option value="<?php echo $job_name; ?>">   
                                        <?php endforeach; ?>
                                    </datalist>
                                </div>
                            </div>
                        </div>
                        <?php if($this->session->userdata("account_type") == SUPER_ADMIN) : ?>

                        <div class="col-lg-3 col-xs-12">
                            <label>Company Name:</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control _CustomerName" list="_CustomerName" name="customer_name" value="<?php echo $this->input->get("customer_name"); ?>">
                                    <datalist id="_CustomerName" >
                                        <?php foreach($select['customer_name'] as $job_name => $r) : ?>
                                             <option value="<?php echo $r->company_name; ?>">   
                                        <?php endforeach; ?>
                                    </datalist>
                                </div>
                            </div>
                        </div>

                        <?php endif; ?>
                    </div>
                    <div class="row">
                        

                        <div class="col-lg-3 col-xs-12">
                            <label>Paid Status:</label>
                            <select class="form-control show-tick" name="paid_by">
                                <option value="">-- All --</option>
                                <option value="UNPAID" <?php echo custom_set_select('paid_by', "UNPAID"); ?>>UNPAID</option>
                                <option value="BANK_TRANSFER" <?php echo custom_set_select('paid_by', "BANK_TRANSFER"); ?>>BANK TRANSFER</option>
                                <option value="PAID_BY_CHEQUE" <?php echo custom_set_select('paid_by', "PAID_BY_CHEQUE"); ?>>PAID BY CHEQUE</option>
                                <option value="PETTY_CASH" <?php echo custom_set_select('paid_by', "PETTY_CASH"); ?>>PETTY CASH</option>
                            </select>
                        </div>

                        <div class="col-lg-3 col-xs-12">
                            <label>Status:</label>
                            <select class="form-control show-tick" name="invoice_status">
                                <option value="">-- All --</option>
                                <option value="INCOMPLETE" <?php echo custom_set_select('invoice_status', "INCOMPLETE"); ?> >INCOMPLETE</option>
                                <option value="NEED_CONFIRMATION" <?php echo custom_set_select('invoice_status', "NEED_CONFIRMATION"); ?> >NEED CONFIRMATION</option>
                                <option value="COMPLETE" <?php echo custom_set_select('invoice_status', "COMPLETE"); ?> >COMPLETE</option>
                            </select>
                        </div>

                        <div class="col-lg-3 col-xs-12">
                            <label>Invoice Date</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="datepicker form-control" name="invoice_date_from" value="<?php echo $this->input->get("invoice_date_from"); ?>" placeholder="Please choose a date...">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-xs-12">
                            <label> <small>To:</small></label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="datepicker form-control" name="invoice_date_to" value="<?php echo $this->input->get("invoice_date_to"); ?>" placeholder="Please choose a date...">
                                </div>
                            </div>
                        </div> 
                    </div>


                    <div class="row">
                        <div class="col-lg-3 col-xs-12" >
                            <label>Paid Date</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="datepicker form-control" name="paid_date_from" value="<?php echo $this->input->get("paid_date_from"); ?>" placeholder="Please choose a date...">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-xs-12">
                            <label><small>To:</small></label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="datepicker form-control" name="paid_date_to" value="<?php echo $this->input->get("paid_date_to"); ?>" placeholder="Please choose a date...">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-xs-12" >
                            <label>Delivery Date</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="datepicker form-control" name="delivery_date_from" value="<?php echo $this->input->get("delivery_date_from"); ?>" placeholder="Please choose a date...">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-xs-12">
                            <label><small>To:</small></label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="datepicker form-control" name="delivery_date_to" value="<?php echo $this->input->get("delivery_date_to"); ?>" placeholder="Please choose a date...">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">           
                        <div class="col-lg-3 col-xs-12">
                            <label>System ID:</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="" class="form-control" name="system_id" value="<?php echo $this->input->get("system_id")?>">
                                </div>
                            </div>
                        </div>        
                        <div class="col-lg-3 col-xs-12">                            
                            <input id="acceptTerms-asdvv1" type="checkbox" name="is_merge" value="false" <?php echo ($this->input->get('is_merge'))? 'checked':''; ?>>
                            <label for="acceptTerms-asdvv1">No Merged Invoices</label>
                        </div>                
                    </div>

                </div>

                <div class="col-xs-12 col-lg-2">
                    <button class="btn btn-primary btn-block waves-effect" type="submit" name="submit" value="submit" id="_sfis"><i class="material-icons" style="position: relative;font-size: 16.5px;">done</i> Apply Filter</button>
                </div>
            </div>

        </form>
    </div>
</div>

