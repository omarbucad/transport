<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_API_KEY; ?>"></script>

<script type="text/javascript">
    $(function(){
        var oTable = $('.dt').dataTable({
            stateSave: true,
            stateSaveCallback: function(settings,data) {
                  localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
            },
            stateLoadCallback: function(settings) {
                return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
            },
            "pageLength": 50 ,
            responsive: true,
            aaSorting : [],
            drawCallback: function(settings){
                var api = this.api();
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
                console.log(json)
                var modal = $('#viewMap').modal('show');

                var longitude = parseFloat(json.longitude);
                var latitude = parseFloat(json.latitude);    

                $('#map-tab').tab('show');

                modal.find(".report-images").html(" ");
                $.each(json.images, function(k , v){
                    var div = $(".report-images");

                    div.append($("<a class='col-lg-3 col-md-4 col-sm-6 col-xs-12' href='<?php echo site_url('public/upload/emergency/'); ?>"+v.images+"' data-sub-html='Accident # "+v.emergency_id+"'><img class='img-responsive thumbnail' src='<?php echo site_url('public/upload/emergency/'); ?>"+v.images+"'></a>" ));

                    modal.find(".report-images").append(div);
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

                modal.find('#map_accident_id').text(id);
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
<div class="block-header">
    <h2>
        <a role="button" class="btn btn-success" data-toggle="collapse" href="#searchcollapse" aria-expanded="false" aria-controls="collapseExample"><i class="material-icons" style="position: relative;font-size: 16.5px;">search</i> Search</a>
    </h2>
</div>
<div style="margin-bottom: 20px;">
    <div class="collapse" id="searchcollapse">
        <?php $this->load->view('page/report/accident_search'); ?>
    </div>
</div>
<!-- Basic Examples -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    ACCIDENT REPORT
                </h2>
            </div>
            <div class="body table-responsive">
                <table class="table table-bordered table-striped table-hover dt">
                    <thead>
                        <tr>
                            <th>Report #</th>
                            <th>Driver</th>
                            <th>Comment</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Report #</th>
                            <th>Driver</th>
                            <th>Comment</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach($result as $key => $row) : ?>
                            <tr>
                                <td><?php echo $row->emergency_id; ?></td>
                                <td><?php echo $row->driver; ?></td>
                                <td><?php echo $row->comment; ?></td>
                                <td><?php echo $row->created; ?></td>
                                <td>
                                     <p><a href="javascript:void(0);" class="btn btn-primary btn-xs view-map" data-id="<?php echo $row->emergency_id; ?>" data-href="<?php echo site_url('app/reports/accident_images/').$row->emergency_id; ?>"><i class="material-icons" style="font-size: 16.5px;">my_location</i> View Map</a></p>
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
                <h4 class="modal-title">Accident Report # <span id="map_accident_id"></span></h4>
            </div>
            <div class="modal-body">

                <ul class="nav nav-tabs">
                    <li class="active"><a id="map-tab" data-toggle="tab" href="#_map">Map</a></li>
                    <li><a data-toggle="tab" href="#accidentreport-image">Images</a></li>
                </ul>
                <div class="tab-content">
                    <div id="accidentreport-image" class="tab-pane fade" style="margin-bottom: 20px;">
                        <div class="list-unstyled clearfix">
                            <div class="row report-images">
                                
                            </div>
                        </div>
                    </div>
                    <div id="_map" class="tab-pane fade in active" style="width:100%;height:400px">
                        
                    </div>
                </div>
                
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
<!-- #END# Basic Examples -->
