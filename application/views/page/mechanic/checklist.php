<div class="block-header">
    <h2>
        <a role="button" class="btn btn-success" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="material-icons" style="position: relative;font-size: 16.5px;">search</i> Search</a>
        <a role="button" href="javascript:void(0);" class="btn btn-primary print_all" ><i class="material-icons" style="position: relative;font-size: 16.5px;">print</i> Print All</a>
    </h2>
</div>

<div style="margin-bottom: 20px;">
    <div class="collapse" id="collapseExample">
        <?php $this->load->view('page/report/search') ?>
    </div>
</div>

<!-- Basic Examples -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    DEFECT REPORT LIST
                </h2>
            </div>
            <div class="body table-responsive">
                <table class="table table-bordered table-striped table-hover dt">
                    <thead>
                        <tr>
                            <th><input id="acceptTerms-asdvv" type="checkbox" class="tr_invoice_all"><label for="acceptTerms-asdvv"></label>  </th>
                            <th>Report #</th>
                            <th>Operator</th>
                            <th>Mileage In</th>
                            <th>Mileage Out</th>
                            <th>Registration Number</th>
                            <th>Fleet No</th>
                            <th>Make and Type</th>
                            <th>Report Type</th>
                            <th>Status</th>
                            <th>Comment</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>Report #</th>
                            <th>Operator</th>
                            <th>Mileage In</th>
                            <th>Mileage Out</th>
                            <th>Registration Number</th>
                            <th>Fleet No</th>
                            <th>Make and Type</th>
                            <th>Report Type</th>
                            <th>Status</th>
                            <th>Comment</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach($result as $key => $row) : ?>
                            <tr>
                                <td></td>
                                <td><?php echo $row->report_id; ?></td>
                                <td><?php echo $row->operator; ?></td>
                                <td><?php echo $row->mileage_in; ?></td>
                                <td><?php echo $row->mileage_out; ?></td>
                                <td><?php echo $row->registration_no; ?></td>
                                <td><?php echo $row->fleet_no; ?></td>
                                <td><?php echo $row->make_type; ?></td>
                                <td><?php echo $row->report_type; ?></td>
                                <td><?php echo $row->r_status; ?></td>
                                <td><?php echo $row->comment; ?></td>
                                <td><?php echo $row->created; ?></td>
                                <td>
                                     <p><a href="javascript:void(0);" class="btn btn-primary btn-xs viewReportModal" data-id="<?php echo $row->report_id; ?>"><i class="material-icons" style="font-size: 16.5px;">visibility</i> View Report</a></p>

                                    <p><a href="javascript:void(0);" data-id="<?php echo $row->report_id; ?>" data-href="<?php echo site_url('app/reports/getReportById/'.$row->report_id) ?>" class="btn btn-xs btn-info viewModal"><i class="material-icons" style="font-size: 16px;">touch_app</i> Action</a></p>
                                    <p><a href="javascript:void(0);" data-href="<?php echo site_url('app/reports/printReport/'.$row->report_id); ?>" class="btn btn-xs btn-primary print-report"><i class="material-icons" style="font-size: 16px;">local_printshop</i> Print Report</a></p>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- #END# Basic Examples -->
