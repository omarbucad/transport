<script type="text/javascript">
    $(document).on("click", "input[name=is_merge]", function(){
        var val = $(this).prop("checked");
        $(this).attr("value",val);
    });
</script>
<style type="text/css">
    #DataTables_Table_0_filter{
        display: none;
    }
</style>
<div class="card">
    <div class="header bg-green">
        <h2>
            Search Invoice
        </h2>
    </div>
    <div class="body">
        <form action="<?php echo site_url("app/expenditures/"); ?>" method="get" id="_fis">
            <input type="hidden" name="outsource" value="all" id="_outsource">

            <div class="row">
                <div class="col-xs-12 col-lg-10">
                    <div class="row">
                        <div class="col-lg-3 col-xs-12">
                            <label>Expenditure #:</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="" class="form-control" name="invoice_number" value="<?php echo $this->input->get("exp_number")?>">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-xs-12">
                            <label>Category</label>
                            <div class="form-line">
                                <select class="form-control" name="category">
                                    <?php foreach($categoryList as $row) : ?>
                                        <option value="<?php echo $row->category_id; ?>"><?php echo $row->category_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-xs-12">
                            <label>Payment Type</label>
                            <div class="form-line">
                                <select class="form-control" id="paid_by_select"  name="paid_by">
                                    <?php foreach($typeList as $row) : ?>
                                        <option value="<?php echo $row->payment_type_id; ?>"><?php echo $row->payment_type_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        

                    <div class="col-lg-3 col-xs-12">
                        <label>Status</label>
                        <div class="form-line">
                            <select class="form-control" name="status">
                               <option value="UNPAID">UNPAID</option>
                               <option value="PAID">PAID</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3 col-xs-12">
                        <label>Created</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="datepicker form-control" name="created_f" value="<?php echo $this->input->get("created_f"); ?>" placeholder="Please choose a date...">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-xs-12">
                        <label> <small>To:</small></label>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="datepicker form-control" name="created_to" value="<?php echo $this->input->get("created_to"); ?>" placeholder="Please choose a date...">
                            </div>
                        </div>
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

