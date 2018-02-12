<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <?php if($type == "INVOICE_LIST") : ?>
                <th>Invoice #</th>
                <th>Job #</th>
                <?php endif;?>
                <th>Job Name</th>
                <th>Paid Status</th>
                <th>Invoice Date</th>
                <th>Paid Date</th>
                <th>Demurrage</th>
                <th>Job Cost</th>
                <th>VAT</th>
                <th>Total Price</th>
                <th>Confirmed</th>
                <?php if($type == "INVOICE_HISTORY") : ?>
                <th>Updated</th>
                <th>No. of Invoices</th>
                <?php endif;?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($result as $key => $row) : ?>
                <tr>
                    <?php if($type == "INVOICE_LIST") : ?>
                    <td><?php echo $row->invoice_id; ?></td>
                    <td><?php echo $row->job_id; ?></td>
                    <?php endif;?>
                    <td>
                        <span data-trigger="hover" data-container="body" data-toggle="popover" data-placement="top" title="Job Information" data-html="true" data-content="<?php echo $this->load->view("page/customer/invoice/job_information" , (array)$row , true); ?>">
                            <?php echo $row->job_name; ?>
                        </span>
                    </td>
                    <td>
                        <?php echo $row->paid_status; ?>
                        <?php if($row->with_charge) : ?>
                            <span class="label bg-blue" data-trigger="hover" data-container="body" data-toggle="popover" data-placement="top" title="Notes" data-html="true" data-content="<?php echo $row->cancel_notes; ?>">NOTES</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $row->invoice_date; ?></td>
                    <td><?php echo $row->paid_date; ?></td>
                    <td><?php echo $row->demurrage; ?></td>
                    <td><?php echo $row->price; ?></td>
                    <td><?php echo $row->vat; ?></td>
                    <td><?php echo $row->total_price; ?></td>
                    <td >
                        <span class="label bg-blue-grey" data-trigger="hover" data-container="body" data-toggle="popover" data-placement="top" title="Confirmed Date" data-html="true" data-content="<?php echo $row->confirmed_date; ?>"><?php echo $row->confirmed_by; ?></span>
                    </td>
                    <?php if($type == "INVOICE_HISTORY") : ?>
                    <td><?php echo $row->updated; ?></td>
                    <td><?php echo $row->i_number; ?></td>
                    <?php endif;?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>