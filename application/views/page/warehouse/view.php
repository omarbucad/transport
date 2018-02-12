<script type="text/javascript">
    $(document).ready(function(){
        
        $('.dt1').DataTable({
            responsive: true,
            stateSave: true,
            "aaSorting": [],
            stateSaveCallback: function(settings,data) {
                      localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
            },
            stateLoadCallback: function(settings) {
                    return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
            },
        });
    });
    $(document).on('click' , '.update-status' , function(){
        var url = "<?php echo site_url("app/warehouse/updateStatus"); ?>";
        var id = $(this).data("id");
        var type = $(this).data("type");
        var $me = $(this);

        $.ajax({
            url : url ,
            data : {id : id , type : type} ,
            type : "POST" , 
            success : function(response){


                if(type == "PICKED UP"){
                    $me.closest('tr').remove();
                }else if(type == "UNLOADED"){
                    $me.closest('tr').remove();
                }else if(type == "LOADED"){
                    $me.closest('tr').find('.status').html(response); 
                    $me.data("type" , "PICKED UP");
                    $me.text("Picked Up");
                }else{
                    $me.closest('tr').find('.status').html(response); 
                }
                
                swal({
                    title : "Updated!" ,
                    type : "success" ,
                    timer: 1000,
                    showConfirmButton: false
                });
            }
        });
    });
</script>
<div class="block-header">
    <h2>
        <a role="button" class="btn btn-success" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="material-icons" style="position: relative;font-size: 16.5px;">search</i> Search</a>
        <?php if ($this->input->get("status") == "complete") : ?>
            <a role="button" class="btn btn-default" href="<?php echo site_url("app/warehouse/?status=incomplete"); ?>"><i class="material-icons" style="position: relative;font-size: 16.5px;">import_export</i> Job Incomplete</a>
        <?php else : ?>
            <a role="button" class="btn btn-default" href="<?php echo site_url("app/warehouse/?status=complete"); ?>"><i class="material-icons" style="position: relative;font-size: 16.5px;">import_export</i> Job Completed</a>
        <?php endif; ?>
    </h2>
</div>

<div style="margin-bottom: 20px;">
    <div class="collapse" id="collapseExample">
        <?php $this->load->view('page/warehouse/search') ?>
    </div>
</div>


<!-- Basic Examples -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    JOB TRUCK LIST
                </h2>
            </div>
            <div class="body table-responsive">
                <table class="table table-bordered table-striped table-hover dt1">
                    <thead>
                        <tr>
                            <th>Company</th>
                            <th>Job Name</th>
                            <th>Driver Name</th>
                            <th>Truck Number</th>
                            <th>Trailer Number</th>
                            <th>Loading Time</th>
                            <th>Load Site</th>
                            <th>Delivery Time</th>
                            <th>Destination</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Company</th>
                            <th>Job Name</th>
                            <th>Driver Name</th>
                            <th>Truck Number</th>
                            <th>Trailer Number</th>
                            <th>Loading Time</th>
                            <th>Load Site</th>
                            <th>Delivery Time</th>
                            <th>Destination</th>
                            
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach($result as $key => $row) : ?>
                            <tr class="_tr_<?php echo $row->jobs_id; ?>">
                                <td><?php echo $row->company_name; ?></td>
                                <td><?php echo $row->job_name.' #'.$row->job_number; ?></td>
                                <td><?php echo $row->driver_name; ?></td>
                                <td><?php echo $row->vehicle_number; ?></td>
                                <td><?php echo $row->trailer_number; ?></td>
                                <td><?php echo $row->loading_time; ?></td>
                                <td><?php echo $row->load_site; ?></td>
                                <td><?php echo $row->delivery_time; ?></td>
                                <td><?php echo $row->address; ?></td>
                                
                                <td class="status">
                                    <?php echo $row->warehouse_status; ?>
                                </td>
                                <td>
                                    <?php if($row->btn_type == "destination") : ?>
                                        <?php if($row->warehouse_status_raw != "UNLOADED") : ?>
                                            <a href="javascript:void(0);" class="btn bg-green btn-block update-status" data-id="<?php echo $row->jobs_id;?>" data-type="UNLOADED">Unloaded</a>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <?php if($row->warehouse_status_raw == "LOADED") : ?>
                                            <a href="javascript:void(0);" class="btn bg-red btn-block update-status" data-id="<?php echo $row->jobs_id;?>" data-type="PICKED UP">Picked up</a>
                                        <?php elseif($row->warehouse_status_raw == "PICKED UP") : ?>

                                        <?php else: ?>
                                            <a href="javascript:void(0);" class="btn bg-red btn-block update-status" data-id="<?php echo $row->jobs_id;?>" data-type="LOADED">Loaded</a>
                                        <?php endif; ?>
                                    <?php endif; ?>
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