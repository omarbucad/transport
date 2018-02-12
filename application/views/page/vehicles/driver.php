<script>
    $(document).on("click" , '.setDefaultBtn' , function(){
        var id = $(this).data("id");
        var url = "<?php echo site_url('app/vehicles/getSelectVehicleAndTrailer'); ?>";
        
        $.ajax({
            url : url ,
            data : {id : id },
            method : "post" , 
            success : function(response){
                var json = jQuery.parseJSON(response);

                var modal = $('#driverModal').modal("show");
                var trailer = json.trailer;
                var vehicle = json.vehicle;
                
                modal.find('#_id_account').val(id);
                
                var trailer_temp = "";
                $.each(trailer , function(k , v){
                    trailer_temp += "<option value="+v.id+">"+v.trailer_number+"</option>";
                });
                modal.find('#modal_trailer_id').html(trailer_temp);
                
                var vehicle_temp = "";
                $.each(vehicle , function(k , v){
                    vehicle_temp += "<option value="+v.id+">"+v.vehicle_number+"</option>";
                });
                modal.find('#modal_vehicle_id').html(vehicle_temp);
                
                $('select').selectpicker('refresh');
                $('#modal_vehicle_id').selectpicker("val" , json.selected_vehicle);
                $('#modal_trailer_id').selectpicker("val" , json.selected_trailer);

                modal.find('#modal_license_number').val(json.license_number);
                modal.find('#modal_license_expiry_date').val(json.license_expiry_date);
                modal.find('._div_file').html(json.files);
            }
        });
        
    });
    
    $(document).on("click" , '#okayUpdateDriver' , function(){
        var form = $(this).closest(".modal").find("form");
        var data = form.serialize();
        var action = form.attr("action");


        $.ajax({
            url : action ,
            data : new FormData(form[0]) ,
            method : "POST" ,
            contentType: false,
            cache: false,            
            processData:false,   
            success : function(response){

                 $('#driverModal').modal("hide");
                 location.reload();
            }

        });

    });

    $(document).on('click' , '.remove-file' , function(){
        var id = $(this).data("id");
        var url = '<?php echo site_url("app/vehicles/removeFileDriver"); ?>';
        var $me = $(this);
        swal({
                title: "Are you sure?",
                text: "You will not be able to recover this inserted data!",
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
                        url : url ,
                        data : {id : id},
                        method : 'post',
                        success : function(response){
                            swal("Deleted!", "Your File Data has been deleted.", "success");
                            $me.closest('.row').remove();
                        }

                    });

                } else {
                    swal("Cancelled", "Your File Data is safe :)", "error");
                }
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
                Vehicles
            </li>
            <li class="active">
                Driver
            </li>
        </ol>
    </h2>
</div>  

<div class="block-header">
    <h2>
        <a role="button" class="btn btn-success" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="material-icons" style="position: relative;font-size: 16.5px;">search</i> Search</a>
    </h2>
</div>

<div style="margin-bottom: 20px;">
    <div class="collapse" id="collapseExample">
        <?php $this->load->view('page/vehicles/search') ?>
    </div>
</div>

<!-- Basic Examples -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    DRIVER LIST
                </h2>
            </div>
            <div class="body table-responsive">
                <div id="aniimated-thumbnials" class="list-unstyled clearfix">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Vehicle</th>
                                <th>Trailer</th>
                                <th>License Number</th>
                                <th>License Expiry Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Vehicle</th>
                                <th>Trailer</th>
                                <th>License Number</th>
                                <th>License Expiry Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php foreach($result as $key => $row) : ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo $row->image; ?>" class="thumbnail" data-sub-html="<?php echo $row->name; ?>">
                                            <img src="<?php echo $row->image_thumb; ?>" class="img-responsive" width="75px" alt="<?php echo $row->name; ?>">
                                        </a>
                                    </td>
                                    <td><?php echo $row->name ; ?></td>
                                    <td><?php echo $row->vehicle_number ; ?></td>
                                    <td><?php echo $row->trailer_number ; ?></td>
                                    <td><?php echo $row->license_number ; ?></td>
                                    <td><?php echo $row->license_expiry_date ; ?></td>
                                    <td><?php echo $row->status ; ?></td>
                                    <td>
                                        <button class="btn btn-primary btn-xs setDefaultBtn" data-id="<?php echo $row->id; ?>"><i class="material-icons" style="font-size: 16px;">directions_car</i> Update Driver</button>
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