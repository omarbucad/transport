<script type="text/javascript">
    $(document).on('click' , '.btn-add-artic' , function(){
        var modal = $('#vehicle_type_modal').modal("show");
    });
    $(document).on('click' , '#artictype_modal_btn' , function(){
        var form = $(this).closest('.modal').find("form");
        var url = form.attr("action");

        $.ajax({
            url : url ,
            method : "POST" ,
            data : form.serialize() ,
            success : function(response){
                location.reload();
            }
        });
    });
    $(document).on('click' , '.remove-data' , function(){
        var url = $(this).data("href");
        var id = $(this).data("id");
        var $me = $(this);

        $.ajax({
            url : url ,
            data : {id : id} ,
            method : "POST" ,
            success : function(response){
                $me.closest("tr").remove();
            }
        });
    });
    $(document).on('click' , '.btn-update-vehicle' , function(){
        var modal = $("#update_vehicle_type_modal").modal("show");
        var id = $(this).data("id");
        var code = $(this).data("code");
        modal.find('#_id').val(id);
        modal.find('#code').val(code);
        modal.find('#code').focus();
    });

    $(document).on('click' , '#artictype_modal_btn_update' , function(){
        var form = $(this).closest(".modal").find("form");
        
        $.ajax({
            url : form.attr("action") ,
            method : "POST" ,
            data : form.serialize() ,
            success : function(){
                location.reload();
            }
        });
    });
</script>

<div class="block-header">
    <h2>
        <ol class="breadcrumb" style="padding: 0px;">
            <li>
                <a href="<?php echo site_url('app/dashboard'); ?>">
                    Dashboard
                </a>
            </li>
            <li class="active">
                Vehicles
            </li>
            <li class="active">
                Artic Type
            </li>
        </ol>
    </h2>
</div>  

<div class="block-header">
    <h2>
        <a href="javascript:void(0);" class="btn btn-primary btn-add-artic"><i class="material-icons" style="position: relative;font-size: 16.5px;">add_circle_outline</i> Add Artic Type</a>
    </h2>
</div>


<!-- Basic Examples -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    ARTIC TYPE LIST
                </h2>
            </div>
            <div class="body table-responsive">
               <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <thead>
                        <tr>
                            <th>Artic Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Artic Type</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach($result as $row) : ?>
                            <tr data-id="<?php echo $row->vehicle_artic_type_id; ?>">
                                <td><?php echo $row->code; ?></td>
                                <td>
                                    <nobr>
                                        <button class="btn btn-xs btn-info waves-effect btn-update-vehicle" data-id="<?php echo $row->vehicle_artic_type_id; ?>" data-code="<?php echo $row->code; ?>"><i class="material-icons" style="font-size: 16px;">update</i> Update</button>
                                        <button href="Javascript:void(0);" class="btn btn-xs btn-danger waves-effect remove-data" data-id="<?php echo $row->vehicle_artic_type_id; ?>" data-href="<?php echo site_url("app/vehicles/removeArticType"); ?>"><i class="material-icons" style="font-size: 16px;">cancel</i> Remove</button>
                                    </nobr>
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