
<script type="text/javascript">
    $(document).on('click' , '.statusUpdateBtn' , function(){
        var form = $(this).closest('.modal').find('form');
        var url = form.attr('action');
        var id = form.find('#modal_report_id').val();
        var status = form.find('#modal_status').val();
        var comment = form.find('#modal_comment').val();
        var data = {id : id , status:status , comment:comment , from:"report" };

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
                if(status == ""){
                    swal("Incomplete", "Status required" , "error");
                }else{
                    $.ajax({
                        url : url ,
                        method : 'post' ,
                        data : data ,
                        success : function(response){
                            var json = jQuery.parseJSON(response);

                            $(".tablerow"+json.report_id).find('.report_status').html(json.status);

                            swal("Updated!", "Successfully Updated" , "success");
                            $('#defaultModal').modal('hide');
                            form[0].reset();
                        }
                    });
                }
            } 
        });
    });

    $(document).on('click' , '.statusMechanicUpdateBtn' , function(){
        var form = $(this).closest('.modal').find('form');
        var url = form.attr('action');
        var id = form.find('#mechanic_report_id').val();
        var status = form.find('#modal_status').val();
        var comment = form.find('#modal_comment').val();
        var data = {id : id , status:status , comment:comment , from:"report" };

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
                if(status == ""){
                    swal("Incomplete", "Status required" , "error");
                }else{
                    $.ajax({
                        url : url ,
                        method : 'post' ,
                        data : data ,
                        success : function(response){
                            var json = jQuery.parseJSON(response);
                            var tr = $("#_"+json.data['report_id']);
                            tr.find(".report_status").html(json.data['status']);
                           
                            swal("Updated!", "Successfully Updated" , "success");
                            $('#mechanicUpdateModal').modal('hide');
                            form[0].reset();

                        }
                    });
               
                }
            } 
        });
        
    });

    $(document).on('click' , '.viewmechModal' , function(){
        var href = $(this).data('href');
        var id = $(this).data('id');

        $.ajax({
            url : href ,
            method : 'get' ,
            success : function(response){
                var json = jQuery.parseJSON(response);
                var modal = $('#mechanicUpdateModal').modal('show');
                var tmp = "";
                $.each(json.checklist , function(k, v){
                        tmp += "<li>"+v.checklist_index+"</li>";
                });
                modal.find('.mechanic_update_container > nav > ol').html(tmp);
                modal.find('#mechanicUpdateModalLabel').html("Report # "+ id);
                modal.find('input#mechanic_report_id').val(id);
            }
        });
    });

    $(document).on('click' , '.viewModal' , function(){
        var href = $(this).data('href');
        var id = $(this).data('id');

        $.ajax({
            url : href ,
            method : 'get' ,
            success : function(response){
                var json = jQuery.parseJSON(response);
                var modal = $('#defaultModal').modal('show');
                var tmp = "";
                $.each(json.checklist , function(k , v){
                    tmp += "<li>"+v+"</li>";
                });
                modal.find('.modal_defect_container > nav > ol').html(tmp);
                modal.find('#defaultModalLabel').html("Report # "+json.id);
                modal.find('#modal_report_id').val(json.id);
            }
        });
        
    });
</script>

<div class="row clearfix">
    <!-- COLLAPSE 1 -->
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" >
        <div class="info-box-2 bg-light-green hover-zoom-effect" data-toggle="collapse" data-target="#cl1">
            <div class="icon">
                <i class="material-icons">local_shipping</i>
            </div>
            <div class="content">
                <div class="text text-uppercase">Active Trucks</div>
                <div class="number count-to" data-from="0" data-to="<?php echo count($_getAll['vehicle']['active']); ?>" data-speed="1000" data-fresh-interval="20"><?php echo count($_getAll['vehicle']['active']); ?></div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 collapse" id="cl1">
                <div class="card">
                    <div class="header bg-light-green">
                        <h2>
                            Active Trucks
                        </h2>
                    </div>
                    <div class="body table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Vehicle Number</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($_getAll['vehicle']['active'] as $key => $row) : ?>
                                    <tr>
                                        <td><?php echo $row; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END COLLAPSE 1-->
    <!-- COLLAPSE 1 -->
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" >
        <div class="info-box-2 bg-deep-orange hover-zoom-effect" data-toggle="collapse" data-target="#cl2">
            <div class="icon">
                <i class="material-icons">local_shipping</i>
            </div>
            <div class="content">
                <div class="text text-uppercase">Inactive Trucks</div>
                <div class="number count-to" data-from="0" data-to="<?php echo count($_getAll['vehicle']['inactive']); ?>" data-speed="1000" data-fresh-interval="20"><?php echo count($_getAll['vehicle']['inactive']); ?></div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 collapse" id="cl2">
                <div class="card">
                    <div class="header bg-deep-orange">
                        <h2>
                            Inactive Trucks
                        </h2>
                    </div>
                    <div class="body table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Vehicle Number</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($_getAll['vehicle']['inactive'] as $key => $row) : ?>
                                    <tr>
                                        <td><?php echo $row; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END COLLAPSE 1-->
    <!-- COLLAPSE 1 -->
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" >
        <div class="info-box-2 bg-light-green hover-zoom-effect" data-toggle="collapse" data-target="#cl3">
            <div class="icon">
                <i class="material-icons">local_shipping</i>
            </div>
            <div class="content">
                <div class="text text-uppercase">Active Trailers</div>
                <div class="number count-to" data-from="0" data-to="<?php echo count($_getAll['trailer']['active']); ?>" data-speed="1000" data-fresh-interval="20"><?php echo count($_getAll['trailer']['active']); ?></div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 collapse" id="cl3">
                <div class="card">
                    <div class="header bg-light-green">
                        <h2>
                            Active Trailers
                        </h2>
                    </div>
                    <div class="body table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Trailer Number</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($_getAll['trailer']['active'] as $key => $row) : ?>
                                    <tr>
                                        <td><?php echo $row; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END COLLAPSE 1-->
    <!-- COLLAPSE 1 -->
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" >
        <div class="info-box-2 bg-deep-orange hover-zoom-effect" data-toggle="collapse" data-target="#cl4">
            <div class="icon">
                <i class="material-icons">local_shipping</i>
            </div>
            <div class="content">
                <div class="text text-uppercase">Inactive Trailers</div>
                <div class="number count-to" data-from="0" data-to="<?php echo count($_getAll['trailer']['inactive']); ?>" data-speed="1000" data-fresh-interval="20"><?php echo count($_getAll['trailer']['inactive']); ?></div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 collapse" id="cl4">
                <div class="card">
                    <div class="header bg-deep-orange">
                        <h2>
                            Inactive Trailers
                        </h2>
                    </div>
                    <div class="body table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Trailer Number</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($_getAll['trailer']['inactive'] as $key => $row) : ?>
                                    <tr>
                                        <td><?php echo $row; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END COLLAPSE 1-->
</div>


<div class="row clearfix">

     <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" >
        <div class="info-box-2 bg-red hover-zoom-effect" data-toggle="collapse" data-target="#collapse4">
            <div class="icon">
                <i class="material-icons">notifications_active</i>
            </div>
            <div class="content">
                <div class="text text-uppercase" style="font-size: 12px; margin-top: 5px">Emergency Report</div>
                <div class="number count-to" data-from="0" data-to="<?php echo count($emergency_report); ?>" data-speed="1000" data-fresh-interval="20"><?php echo count($emergency_report) ; ?></div>
            </div>
        </div>
        
    </div>

    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" >
        <div class="info-box-2 bg-orange hover-zoom-effect" data-toggle="collapse" data-target="#collapse5">
            <div class="icon">
                <i class="material-icons">create</i>
            </div>
            <div class="content">
                <div class="text text-uppercase">Advisory Report</div>
                <div class="number count-to" data-from="0" data-to="<?php echo count($_getAll['advisory'] ); ?>" data-speed="1000" data-fresh-interval="20"><?php echo count($_getAll['advisory'] ); ?></div>
            </div>
        </div>
    </div>

    <!-- COLLAPSE 3-->
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" >
        <div class="info-box-2 bg-deep-orange hover-zoom-effect" data-toggle="collapse" data-target="#collapse3">
            <div class="icon">
                <i class="material-icons">report_problem</i>
            </div>
            <div class="content">
                <div class="text text-uppercase">Defective Trucks</div>
                <div class="number count-to" data-from="0" data-to="<?php echo count($_getAll['defect']) ; ?>" data-speed="1000" data-fresh-interval="20"><?php echo count($_getAll['defect']) ; ?></div>
            </div>
        </div>
        
    </div>

    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" >
        <div class="info-box-2 bg-light-blue hover-zoom-effect" data-toggle="collapse" data-target="#collapse9">
            <div class="icon">
                <i class="material-icons">build</i>
            </div>
            <div class="content">
                <div class="text text-uppercase" style="font-size: 11px; margin-top: 5px">Fixed and <br>Under Maintenance</div>
                <div class="number count-to" data-from="0" data-to="<?php echo count($totalfixedundermaintenance) ; ?>" data-speed="1000" data-fresh-interval="20"><?php echo count($totalfixedundermaintenance) ; ?></div>
            </div>
        </div>
        
    </div>

<!-- COLLAPSE TABLE -->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 collapse" id="collapse4">
                <div class="card ">
                    <div class="header bg-red">
                        <h2>
                            Emergency Report
                        </h2>
                    </div>
                    <div class="body table-responsive ">
                        <table class="table table-bordered table-striped dt">
                            <thead>
                                <tr>
                                    <th><nobr>Emergency #</nobr></th>
                                    <th><nobr>Driver</nobr></th>
                                    <th><nobr>Comment</nobr></th>
                                    <th><nobr>Status</nobr></th>
                                    <th><nobr>Latitude</nobr></th>
                                    <th><nobr>Created</nobr></th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($emergency_report  as $key => $row) : ?>
                                    <tr>
                                        <td><?php echo $row->emergency_id; ?></td>
                                        <td><?php echo $row->name . " " . $row->surname; ?></td>
                                        <td><?php echo $row->comment; ?></td>
                                        <td><?php echo $row->status; ?></td>
                                        <td><?php echo $row->created; ?></td>
                                        <td><?php echo ($row->fix_date == '0') ? "NA" : $row->fix_date; ?></td>
                                        <td><a href="javascript:void(0);" data-id="<?php echo $row->emergency_id; ?>" data-href="<?php echo site_url('app/mechanic/update_emergency_report/'.$row->emergency_id) ?>" class="btn btn-xs btn-info viewModal">Fix</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 collapse" id="collapse5">
                <div class="card">
                    <div class="header bg-orange">
                        <h2>
                            Advisory Report
                        </h2>
                    </div>
                    <div class="body table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><nobr>Report #</nobr></th>
                                    <th><nobr>Vehicle Number</nobr></th>
                                    <th><nobr>Trailer Number</nobr></th>
                                    <th><nobr>Advisory Note</nobr></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($_getAll['advisory'] as $key => $row) : ?>
                                    <tr id="_ADVISORY_<?php echo $row->id; ?>">
                                        <td><?php echo $row->id; ?></td>
                                        <td><?php echo $row->vehicle_registration_number; ?></td>
                                        <td><?php echo $row->trailer_number; ?></td>
                                        <td><?php echo $row->advisory; ?></td>
                                        <td><a href="javascript:void(0);" data-id="<?php echo $row->id; ?>" data-href="<?php echo site_url('app/reports/getReportById/'.$row->id) ?>" class="btn btn-xs btn-info viewModal">Fix</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 collapse" id="collapse3">
                <div class="card ">
                    <div class="header bg-deep-orange">
                        <h2>
                            Defective Trucks
                        </h2>
                    </div>
                    <div class="body table-responsive ">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><nobr>Report #</nobr></th>
                                    <th><nobr>Vehicle Number</nobr></th>
                                    <th><nobr>Trailer Number</nobr></th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($_getAll['defect']  as $key => $row) : ?>
                                    <tr>
                                        <th scope="row"><?php echo $row->id; ?></th>
                                        <td><a href="<?php echo site_url('app/reports/vehicles/'.$row->vehicle_number); ?>"><?php echo $row->vehicle_number; ?></a></td>
                                        <td><a href="#"><?php echo $row->trailer_number; ?></a></td>
                                        <td><?php echo $row->status; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 collapse" id="collapse9">
                <div class="card ">
                    <div class="header bg-light-blue">
                        <h2>
                            Fixed and Under Maintenance
                        </h2>
                    </div>
                    <div class="body table-responsive ">
                        <table class="table table-bordered table-striped dt">
                            <thead>
                                <tr>
                                    <th><nobr>Report #</nobr></th>
                                    <th><nobr>Report From</nobr></th>
                                    <th><nobr>Vehicle Registration Number</nobr></th>
                                    <th><nobr>Status</nobr></th>
                                    <th><nobr>Updated</nobr></th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($totalfixedundermaintenance  as $key => $row) : ?>
                                    <tr class="tablerow">
                                        <th scope="row"><?php echo $row['id']; ?></th>
                                        <td><?php echo $row['report_from']; ?></td>
                                        <td><a href="<?php echo site_url('app/reports/vehicles/'.$row['vehicle_registration_number']); ?>"><?php echo $row['vehicle_registration_number']; ?></a></td>
                                        <td class="report_status"><?php echo $row['report_status']; ?></td>
                                        <td><?php echo $row['created']; ?></td>
                                        <?php if($row['report_status'] == '<span class="label label-success">Fixed</span>') : ?>
                                           <td></td>
                                        <?php else: ?>
                                         <td>
                                            <?php if($row['report_from'] == 'DRIVER') : ?>
                                            <p><a href="javascript:void(0);" data-id="<?php echo $row['id']; ?>" data-href="<?php echo site_url('app/reports/getReportById/'.$row['id']); ?>" class="btn btn-xs btn-info viewModal"><i class="material-icons" style="font-size: 16px;">touch_app</i> Action</a></p> 
                                            <?php else : ?>  
                                            <p><a href="javascript:void(0);" data-id="<?php echo $row['id']; ?>" data-href="<?php echo site_url('app/mechanic/viewReport/'.$row['id'].'/true'); ?>" class="btn btn-xs btn-info viewmechModal"><i class="material-icons" style="font-size: 16px;">touch_app</i> Action</a></p>
                                            <?php endif; ?>                                       
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
<!-- END COLLAPSE TABLE -->
    </div>
</div>