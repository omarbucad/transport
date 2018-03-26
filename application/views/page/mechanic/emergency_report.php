<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_API_KEY; ?>"></script>

<script type="text/javascript">
    
    $(document).on("click", ".fix-emergency", function(){
        var url = $(this).data("href");
        swal({
            title: "Are you sure to update status as fixed?",
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

    $(document).on("click", ".view-map", function(){
        var url = $(this).data("href");
        var id = $(this).data("id");

    
        $.ajax({
            url : url ,
            method : 'get' ,
            success : function(response){
                var json = jQuery.parseJSON(response);
                var modal = $('#viewMap').modal('show');
                modal.find("tbody").html(" ");
                var longitude;
                var latitude;
                $.each(json.result , function(k , v){
                    longitude = parseFloat(v.longitude);
                    latitude = parseFloat(v.latitude);
                    var tr = $("<tr>");

                    tr.append($("<td>").append(v.emergency_id));
                    tr.append($("<td>").append(v.name + " " + v.surname));
                    tr.append($("<td>").append(v.longitude));
                    tr.append($("<td>").append(v.latitude));
                    tr.append($("<td>").append(v.comment));
                    tr.append($("<td>").append(v.status));

                    modal.find("tbody").append(tr);
                });

                setTimeout(function(){
                    var uluru = {lat: latitude, lng: longitude};
                    var map = new google.maps.Map(document.getElementById('_map'), {
                      zoom: 14,
                      center: uluru
                    });

                    var marker = new google.maps.Marker({
                      position: uluru,
                      map: map
                    });

                   
                },500);
                
                modal.find('#map_emergency_id').text(id);
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
                <table class="table table-bordered table-striped table-hover dt">
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
                                <td><?php echo ($row->fix_date == '0') ? "NA" : $row->fix_date; ?></td>
                                <td>
                                     <p><a href="javascript:void(0);" class="btn btn-primary btn-xs view-map" data-id="<?php echo $row->emergency_id; ?>" data-href="<?php echo site_url('app/mechanic/view_emergency_report/').$row->emergency_id; ?>"><i class="material-icons" style="font-size: 16.5px;">my_location</i> View Map</a></p>

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

<!-- MODAL VIEW -->

<div class="modal fade" id="viewMap" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title">Emergency Report # <span id="map_emergency_id"></span></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <table class="table table-striped table-hover table-bordered dt">
                        <thead>
                            <tr>
                                <td>Emergency #</td>
                                <td>Driver</td>
                                <td>Longitude</td>
                                <td>Latitude</td>
                                <td>Comment</td>
                                <td>Status</td>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>                        
                    </table>
                </div>
                <div id="_map" style="width:100%;height:400px">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
<!-- #END# Basic Examples -->
