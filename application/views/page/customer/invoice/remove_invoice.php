<script type="text/javascript">
    $(document).on('click' , '.remove-invoice' , function(){
        var href = $(this).data("href");

        swal({
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {

                if (isConfirm) {
                    
                    $.ajax({
                        url : href ,
                        method : "POST" ,
                        success : function(){
                            location.reload();
                        }
                    });
                
                } else {

                    swal("Cancelled", "safe :)", "error");
                     
                }

            });
    });

    $(document).on("click" , '.add-invoice' , function(){
        var id = $(".system-id").val();

        if(id.length){
            $.ajax({
                url : "<?php echo site_url('app/customer/add_merge_id'); ?>",
                method : "POST",
                data : {id : id , merge_id : "<?php echo $this->input->get("id"); ?>"},
                success : function(response){
                    var json = jQuery.parseJSON(response);

                    if(json.status){
                        alert(json.message);
                        location.reload();
                    }else{
                        alert(json.message);
                    }
                }
            });
        }
    });
</script>

<div class="block-header">
    <h2>
        <ol class="breadcrumb" style="padding: 0px;">
            <li>
                <a href="<?php echo site_url('app/dashboard/index/4'); ?>">
                 Dashboard
                </a>
            </li>
            <li class="active">
                Customer
            </li>
            <li class="active">
                Invoices
            </li>
        </ol>
    </h2>
</div>  
<!-- Basic Examples -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    INVOICE LIST
                </h2>
            </div>
            <div class="body table-responsive">
                <table class="table table-bordered table-striped table-hover dt">
                    <thead>
                        <tr>
                            <th>System ID</th>
                            <th>Invoice #</th>
                            <th>Job No.</th>
                            <th>Purchase Order number</th>
                            <th>Company Name</th>
                            <th>Job Name</th>
                            <th>Paid Status</th>
                            <th>Delivery Date</th>
                            <th>Invoice Date</th>
                            <th>Paid Date</th>
                            <th>Demurrage</th>
                            <th>Job Cost</th>
                            <th>VAT</th>
                            <th>Gross</th>
                            <th>Confirmed</th>
                            <th>Notes</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>System ID</th>
                            <th>Invoice #</th>
                            <th>Job No.</th>
                            <th>Purchase Order number</th>
                            <th>Company Name</th>
                            <th>Job Name</th>
                            <th>Paid Status</th>
                            <th>Delivery Date</th>
                            <th>Invoice Date</th>
                            <th>Paid Date</th>
                            <th>Demurrage</th>
                            <th>Job Cost</th>
                            <th>VAT</th>
                            <th>Gross</th>
                            <th>Confirmed</th>
                            <th>Notes</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach($result as $key => $row) : ?>
                            <tr id="_tr_<?php echo $row->invoice_id; ?>">
                                <td><?php echo $row->invoice_id; ?></td>
                                <td><?php echo $row->invoice_number; ?></td>
                                <td><?php echo $row->jn; ?></td>
                                <td><?php echo $row->jpo_number; ?></td>
                                <td>
                                    <span data-trigger="hover" data-container="body" data-toggle="popover" data-placement="top" title="Company Information" data-html="true" data-content="<?php echo $this->load->view("page/customer/invoice/job_information" , (array)$row , true); ?>">
                                        <?php echo $row->company_name; ?>
                                    </span>
                                </td>
                                <td>
                                    <span data-trigger="hover" data-container="body" data-toggle="popover" data-placement="top" title="Job #" data-html="true" data-content="<?php echo $row->job_id; ?>">
                                        <?php echo $row->job_name; ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="td_status"><?php echo $row->paid_status; ?></span>

                                    <?php if($row->merge == "Y") : ?>
                                        <span class="label bg-blue">MERGE</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $row->delivery_time; ?></td>
                                <td><?php echo $row->invoice_date; ?></td>
                                <td><?php echo $row->paid_date; ?></td>
                                <td><?php echo $row->demurrage; ?></td>
                                <td><?php echo $row->price; ?></td>
                                <td><?php echo $row->vat; ?></td>
                                <td>
                                    <?php echo $row->total_price; ?>
                                    <?php if($row->to_outsource == "YES") : ?>
                                        <br>
                                        <span class="label bg-blue">OUTSOURCE</span>
                                    <?php endif; ?>    
                                </td>
                                <td class="td_confirmed">
                                    <span class="label bg-blue-grey" data-trigger="hover" data-container="body" data-toggle="popover" data-placement="top" title="Confirmed Date" data-html="true" data-content="<?php echo $row->confirmed_date; ?>"><?php echo $row->confirmed_by; ?></span>
                                </td>
                                <td>
                                    <?php if($row->with_charge) : ?>
                                        <?php if($row->cancel_notes) : ?>
                                            <span class="label bg-blue" data-trigger="hover" data-container="body" data-toggle="popover" data-placement="top" title="Notes" data-html="true" data-content="<?php echo $row->cancel_notes; ?>">CANCEL NOTES</span>
                                        <?php endif; ?>
                                    <?php endif; ?>    
                                    <?php if($row->invoice_notes) : ?>
                                        <span class="label bg-blue" data-trigger="hover" data-container="body" data-toggle="popover" data-placement="top" title="Notes" data-html="true" data-content="<?php echo $row->invoice_notes; ?>">INVOICE NOTES</span>
                                    <?php else : ?>
                                         <span class="label bg-red">NO</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                     <a href="javascript:void(0);" data-href="<?php echo site_url("app/customer/remove_merge_id/?id=$row->invoice_id"); ?>" class="btn btn-primary remove-invoice">Remove Invoice</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>


            </div>

        </div>

        <div class="row">
            <div class="col-lg-3">
                <div class="form-group">
                    <label>System ID</label>
                    <input type="text" name="system_id" class="form-control system-id">
                    <br>
                    <a href="javascript:void(0);" class="btn btn-primary add-invoice">Add Invoice</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- #END# Basic Examples -->
