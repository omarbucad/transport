<script type="text/javascript">
    $(function () {
        $('.dataTable').DataTable({
            responsive: true,
            "aaSorting": []
        });

        $(document).on('click', '.remove-data' ,function () {
            var id = $(this).data('id');
            var href = $(this).data('href');
            var name = $(this).data('name');
            var $me = $(this);
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover the account of "+name,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel it!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    
                    $.ajax({
                        url : href ,
                        data : {id : id},
                        method : 'post',
                        success : function(response){
                            swal("Deleted!", name+" has been deleted.", "success");
                            $me.closest('tr').remove();
                        }

                    });

                } else {
                    swal("Cancelled", name+" is safe :)", "error");
                }
            });

        });

        $(document).on('click' , '.btn-update' , function(){
            window.location = $(this).data('href');
        });
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
                Reports
            </li>
            <li class="active">
                Daily Report List
            </li>
        </ol>
    </h2>
</div>  

<div class="block-header">
    <h2>
        <a role="button" class="btn btn-success" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="material-icons" style="position: relative;font-size: 16.5px;">search</i> Search</a>
        <a href="<?php echo site_url("app/reports/active_driver");?>" class="btn btn-primary" ><i class="material-icons" style="position: relative;font-size: 16.5px;">visibility</i> Active Driver</a>
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
                    DAILY REPORT LIST
                </h2>
            </div>
            <div class="body ">
                <div class="list-unstyled clearfix table-responsive" style="max-height: 650px">
                    <table class="table table-bordered table-striped table-hover dataTable" >
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Driver</th>
                                <th>Check In</th>
                                <th>Checklist</th>
                                <th><nobr>Safety Check</nobr></th>
                                <th><nobr>Start Date</nobr></th>
                                <th><nobr>Start Mileage</nobr></th>
                                <th>Vehicle</th>
                                <th>Trailer</th>
                                <th><nobr>Load Site</nobr></th>
                                <th>Destination</th>
                                <th>Begin Driving</th>
                                <th><nobr>Load Site Arrival</nobr></th>
                                <th><nobr>Load Site Pick up</nobr></th>
                                <th><nobr>Load Site Mileage</nobr></th>
                                <th><nobr>Warehouse Name</nobr></th>
                                <th><nobr>Warehouse Signature</nobr></th>
                                <th><nobr>Destination Site Arrival</nobr></th>
                                <th><nobr>Destination Site Pick Up</nobr></th>
                                <th><nobr>Destination Site Mileage</nobr></th>
                                <th><nobr>Customer Name</nobr></th>
                                <th><nobr>Customer Signature</nobr></th>
                                <th><nobr>Delivery Notes</nobr></th>
                                <th><nobr>Job Completed</nobr></th>
                                <th><nobr>Check out</nobr></th>
                                <th><nobr>End Mileage</nobr></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Image</th>
                                <th>Driver</th>
                                <th>Check In</th>
                                <th>Checklist</th>
                                <th><nobr>Safety Check</nobr></th>
                                <th><nobr>Start Date</nobr></th>
                                <th><nobr>Start Mileage</nobr></th>
                                <th>Vehicle</th>
                                <th>Trailer</th>
                                <th><nobr>Load Site</nobr></th>
                                <th>Destination</th>
                                <th>Begin Driving</th>
                                <th><nobr>Load Site Arrival</nobr></th>
                                <th><nobr>Load Site Pick up</nobr></th>
                                <th><nobr>Load Site Mileage</nobr></th>
                                <th><nobr>Warehouse Name</nobr></th>
                                <th><nobr>Warehouse Signature</nobr></th>
                                <th><nobr>Destination Site Arrival</nobr></th>
                                <th><nobr>Destination Site Pick Up</nobr></th>
                                <th><nobr>Destination Site Mileage</nobr></th>
                                <th><nobr>Customer Name</nobr></th>
                                <th><nobr>Customer Signature</nobr></th>
                                <th><nobr>Delivery Notes</nobr></th>
                                <th><nobr>Job Completed</nobr></th>
                                <th><nobr>Check out</nobr></th>
                                <th><nobr>End Mileage</nobr></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php foreach ($result as $key => $row): ?>
                                <tr>
                                    <td><img src="<?php echo $row->image_thumb; ?>" style="width: 100px;"></td>
                                    <td><?php echo $row->name; ?></td>
                                    <td><?php echo $row->time_in; ?></td>
                                    <td><?php echo $row->checklist_report['checklist_type']; ?></td>
                                    <td><?php echo $row->checklist_report['safety_check']; ?></td>
                                    <td><?php echo $row->checklist_report['start_date']; ?></td>
                                    <td><?php echo $row->checklist_report['start_mileage']; ?></td>
                                    <td><?php echo $row->checklist_report['vehicle_registration_number']; ?></td>
                                    <td><?php echo $row->checklist_report['trailer_number']; ?></td>
                                    <td><?php echo $row->job_report["load_site"]?></td>
                                    <td><?php echo $row->job_report["destination"]?></td>
                                    <td><?php echo $row->job_report["begin_driving_time"]?></td>
                                    <td><?php echo $row->job_report["a_arrived_time"]?></td>
                                    <td><?php echo $row->job_report["a_pick_up_time"]?></td>
                                    <td><?php echo $row->job_report["a_start_mileage"]?></td>
                                    <td><?php echo $row->job_report["warehouse_signature_name"]?></td>
                                    <td>
                                        <?php echo $row->job_report["warehouse_signature"]?>
                                    </td>
                                    <td><?php echo $row->job_report["b_arrived_time"]?></td>
                                    <td><?php echo $row->job_report["b_pick_up_time"]?></td>
                                    <td><?php echo $row->job_report["b_start_mileage"]?></td>
                                    <td>
                                        <?php echo $row->job_report["signature_name"]?>
                                    </td>
                                    <td>
                                        <?php echo $row->job_report["signature"]?>
                                    </td>
                                    <td><?php echo $row->job_report["delivery_notes"]?></td>
                                    <td><?php echo $row->job_report["job_complete_time"]?></td>
                                    <td><?php echo $row->time_out; ?></td>
                                    <td><?php echo $row->end_mileage; ?></td>
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


