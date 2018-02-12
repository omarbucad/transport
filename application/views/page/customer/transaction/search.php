<div class="card">
    <div class="header bg-green">
        <h2>
            Search Invoice
        </h2>
    </div>
    <div class="body">
        <form action="<?php echo site_url("app/customer/transaction_logs/"); ?>" method="get">
            <div class="row">
                <div class="col-xs-12 col-lg-10">
                    <div class="row">

                        <div class="col-lg-4 col-xs-12">
                            <label>Transaction Number:</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="" class="form-control" name="transaction_id" value="<?php echo $this->input->get("transaction_id")?>">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-xs-12">
                            <label>Cheque Number:</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="cheque_number" value="<?php echo $this->input->get("cheque_number")?>">
                                </div>
                            </div>
                        </div>

                         <div class="col-lg-4 col-xs-12">
                            <label>Paid by:</label>
                            <select class="form-control show-tick" name="paid_by">
                                <option value="">-- All --</option>
                                <option value="UNPAID" <?php echo custom_set_select('paid_by', "UNPAID"); ?>>UNPAID</option>
                                <option value="BANK_TRANSFER" <?php echo custom_set_select('paid_by', "BANK_TRANSFER"); ?>>BANK TRANSFER</option>
                                <option value="PAID_BY_CHEQUE" <?php echo custom_set_select('paid_by', "PAID_BY_CHEQUE"); ?>>PAID BY CHEQUE</option>
                                <option value="PETTY_CASH" <?php echo custom_set_select('paid_by', "PETTY_CASH"); ?>>PETTY CASH</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-xs-12" >
                            <label>Paid Date <br><small>From:</small></label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="datepicker form-control" name="paid_date_from" value="<?php echo $this->input->get("paid_date_from"); ?>" placeholder="Please choose a date...">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-xs-12">
                            <label><br> <small>To:</small></label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="datepicker form-control" name="paid_date_to" value="<?php echo $this->input->get("paid_date_to"); ?>" placeholder="Please choose a date...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-lg-2">
                    <button class="btn btn-primary waves-effect btn-block" type="submit" name="submit" value="submit"><i class="material-icons" style="position: relative;font-size: 16.5px;">done</i> Apply Filter</button>
                </div>
            </div>
            
        </form>
    </div>
</div>

