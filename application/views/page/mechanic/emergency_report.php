<script type="text/javascript">
    
    $(document).on("click", ".fix-emergency", function(){
        var url = $(this).data("href");
        swal({
            title: "Are you sure?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes",
            cancelButtonText: "No!",
            closeOnConfirm: false,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url : url ,
                    method : "POST" ,
                    success : function(response){
                        swal("Updated", "Successfully updated" , "success");
                    },
                    error: function(){
                        swal("Error!", "Something went wrong" , "error");
                    }
                });
            } 
        });
    });

</script>
<style type="text/css">
    @media (min-width: 992px) {
        .modal-lg {
            width: 80% !important;
        }
    }
</style>

<!-- Basic Examples -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    EMERGENCY REPORT
                </h2>
            </div>
            <div class="body table-responsive">
                <table class="table table-bordered table-striped table-hover dt" id="mechanic_checklist_report_table">
                    <thead>
                        <tr>
                            <th>Emergency #</th>
                            <th>Driver</th>
                            <th>Longitude</th>
                            <th>Latitude</th>
                            <th>Comment</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Date Fixed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Emergency #</th>
                            <th>Driver</th>
                            <th>Longitude</th>
                            <th>Latitude</th>
                            <th>Comment</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Date Fixed</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach($result as $key => $row) : ?>
                            <tr>
                                <td><?php echo $row->emergency_id; ?></td>
                                <td><?php echo $row->name . " " . $row->surname; ?></td>
                                <td><?php echo $row->longitude; ?></td>
                                <td><?php echo $row->latitude; ?></td>
                                <td><?php echo $row->comment; ?></td>
                                <td><?php echo $row->status; ?></td>
                                <td><?php echo $row->created; ?></td>
                                <td><?php echo $row->fix_date; ?></td>
                                <td>
                                     <p><a href="javascript:void(0);" class="btn btn-primary btn-xs viewEmergencyReportModal" data-id="<?php echo $row->emergency_id; ?>"><i class="material-icons" style="font-size: 16.5px;">my_location</i> View Map</a></p>

                                    <p><a href="javascript:void(0);" data-id="<?php echo $row->emergency_id; ?>" data-href="<?php echo site_url('app/mechanic/update_emergency_report/'.$row->emergency_id) ?>" class="btn btn-xs btn-info fix-emergency"><i class="material-icons" style="font-size: 16px;">build</i> Fix</a></p>
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
