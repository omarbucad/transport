<script type="text/javascript">
    
    $(document).on('click' , '.view-invoice' , function(){
        var id = $(this).data("id");
        var url = "<?php echo site_url("app/customer/getInvoiceListById"); ?>";

        $.ajax({
            url : url ,
            data : {id : id },
            method : "POST",
            success : function(response){
                var modal = $('#invoice_history_modal').modal("show");
                modal.find(".modal-body").html(response);

                $('[data-toggle="popover"]').popover(); 
            }
        });
    });
</script>

<div class="block-header">
    <h2>
        <ol class="breadcrumb" style="padding: 0px;">
            <li>
                <a href="<?php echo site_url('app/customer/jobs'); ?>">
                    Customer
                </a>
            </li>
            <li class="active">
                Transaction Logs
            </li>
        </ol>
    </h2>
</div>  

<div class="block-header">
    <h2>
        <a role="button" class="btn btn-success" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="material-icons" style="position: relative;font-size: 16px;">search</i> Search</a>
    </h2>
</div>

<div style="margin-bottom: 20px;">
    <div class="collapse" id="collapseExample">
        <?php $this->load->view('page/customer/transaction/search') ?>
    </div>
</div>

<!-- Basic Examples -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Transaction Logs
                </h2>
            </div>
            <div class="body table-responsive">
                <div class="list-unstyled clearfix">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                            <tr>
                                <th>Transaction #</th>
                                <th>Total Paid</th>
                                <th>Paid By</th>
                                <th>Paid Date</th>
                                <th>Cheque Number</th>
                                <th>Notes</th>
                                <th>Updated By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Transaction #</th>
                                <th>Total Paid</th>
                                <th>Paid By</th>
                                <th>Paid Date</th>
                                <th>Cheque Number</th>
                                <th>Notes</th>
                                <th>Updated By</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php foreach ($result as $key => $row): ?>
                                <tr>
                                    <td><?php echo $row->invoice_transaction_id; ?></td>
                                    <td><?php echo $row->total_price; ?></td>
                                    <td><?php echo $row->paid_by; ?></td>
                                    <td><?php echo $row->paid_date; ?></td>
                                    <td><?php echo ($row->cheque_number) ? $row->cheque_number : "NA"; ?></td>
                                    <td><?php echo $row->notes; ?></td>
                                    <td><?php echo $row->name; ?></td>
                                    <td>
                                        <a href="javascript:void(0);" class="btn btn-primary btn-xs view-invoice" data-id="<?php echo $row->invoice_transaction_id; ?>"><i class="material-icons" style="font-size: 16px;">visibility</i> View Invoices</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- #END# Basic Examples -->
