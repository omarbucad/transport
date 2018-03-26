<script type="text/javascript">
    $(function () {
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

        var allPages = oTable.fnGetNodes();


        $(document).on('click' , '.viewMechanicModal' , function(){
            var id = $(this).data("id");

            loadReport(id);
        });

        function loadReport(id){
            var modal = $('#ViewMechanicReport').modal("show");
            var url = "<?php echo site_url("app/mechanic/viewReport/"); ?>"+id;

            $.ajax({
                url : url ,
                method : "POST" ,
                beforeSend : function(){
                    modal.find('.modal-body').html("LOADING...");
                },
                success : function(response){
                    var json = jQuery.parseJSON(response);
                    console.log(json);
                    modal.find('.modal-body').html(json.html);

                }
            });
        }
        $(document).on('click' , '.viewModal' , function(){
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

        $(document).on('click' , '.print-report' , function(){
            $.ajax({
                url : $(this).data("href"),
                method : "GET" ,
                success : function(response){

                    var newWin = window.open('','Print-Window');

                      newWin.document.open();

                      newWin.document.write('<html><body onload="window.print()">'+response+'</body></html>');

                      newWin.document.close();

                      setTimeout(function(){newWin.close();},10);
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

        $(document).on('click' , '#select_all' , function(){
            var isChecked = $(this).is(":checked");

            $.each( $('.tr_report_id' , allPages) , function(k , v){
                if(isChecked){  
                    $(v).prop('checked', true);
                }else{

                    $(v).prop('checked', false);
                }
            });
        });

        $(document).on('click' , '.print_all' , function(){
            var url = "<?php echo site_url('app/mechanic/printReport/false'); ?>";

            var id = "";
            var a = 0;

            $.each( $('.tr_report_id' , allPages) , function(k , v){
                if($(v).is(':checked')){
                    var i = $(v).val();
                    if(a == 0){
                        id += "?id[]="+i;
                        a++;
                    }else{
                        id += "&id[]="+i;
                    }

                }
            });

            if(id != ""){
                console.log(id);
                $.ajax({
                    url : url+id,
                    method : "GET" ,
                    success : function(response){
                        var newWin = window.open('','Print-Window');

                          newWin.document.open();

                          newWin.document.write('<html><body onload="window.print()">'+response+'</body></html>');

                          newWin.document.close();

                          setTimeout(function(){newWin.close();},10);
                    }
                });

            }else{
                swal("Warning", "No Selected Checklist Report", "error");
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
<div class="">    
    <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <a role="button" class="alert-link" href="<?php echo site_url('app/mechanic/emergency_reports'); ?>" style="text-decoration: none;"><h4 style="margin-bottom: 0;"><span class="number count-to" data-from="0" data-to="<?php echo count($emergency_report ); ?>" data-speed="1000" data-fresh-interval="20"><?php echo count($emergency_report ); ?> </span> EMERGENCY REPORT</h4></a>
    </div>  
    <div class="alert alert-info" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <a role="button" class="alert-link" href="<?php echo site_url('app/mechanic/needServicing'); ?>" style="text-decoration: none;"><h4 style="margin-bottom: 0;"><span class="number count-to" data-from="0" data-to="<?php echo count($servicing ); ?>" data-speed="1000" data-fresh-interval="20"><?php echo count($servicing ); ?> </span> NEEDS SERVICING</h4></a>
    </div>
</div>
<div class="block-header">
    <h2>
        <span class="pull-right">
            <a role="button" href="javascript:void(0);" class="btn btn-primary print_all" ><i class="material-icons" style="position: relative;font-size: 16.5px;">print</i> Print All</a>
        </span>
        
        <a role="button" class="btn btn-danger" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="material-icons" style="position: relative;font-size: 16.5px;">search</i> Search</a>
        <a role="button" href="<?php echo site_url('app/mechanic/?status=fixed');?>" class="btn btn-success fixed" ><i class="material-icons" style="position: relative;font-size: 16.5px;">done</i> Fixed</a>
        <a role="button" href="<?php echo site_url('app/mechanic/?status=under_maintenance');?>" class="btn btn-warning under_maintenance" ><i class="material-icons" style="position: relative;font-size: 16.5px;">build</i> Under Maintenance</a>
        <a role="button" href="<?php echo site_url('app/mechanic/?status=open');?>" class="btn btn-primary open" ><i class="material-icons" style="position: relative;font-size: 16.5px;">error_outline</i> Open</a>
        <a role="button" href="<?php echo site_url('app/mechanic/?search=all');?>" class="btn btn-danger all" ><i class="material-icons" style="position: relative;font-size: 16.5px;">donut_large</i> All</a>

    </h2>
</div>

<div style="margin-bottom: 20px;">
    <div class="collapse" id="collapseExample">
        <?php $this->load->view('page/mechanic/search') ?>
    </div>
</div>

<!-- Basic Examples -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    MECHANIC CHECKLIST REPORT
                </h2>
            </div>
            <div class="body table-responsive">
                <table class="table table-bordered table-striped table-hover dt" id="mechanic_checklist_report_table">
                    <thead>
                        <tr>
                            <th><input id="select_all" type="checkbox" class="tr_invoice_all"><label for="select_all"></label>  </th>
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
                            <tr id="_<?php echo $row->report_id; ?>">
                                <td> <input id="select_all<?php echo $key; ?>"  value="<?php echo $row->report_id; ?>" type="checkbox" class="tr_report_id">
                                    <label for="select_all<?php echo $key; ?>"></td>
                                <td><?php echo $row->report_id; ?></td>
                                <td><?php echo $row->operator; ?></td>
                                <td><?php echo $row->mileage_in; ?></td>
                                <td><?php echo $row->mileage_out; ?></td>
                                <td><?php echo $row->registration_no; ?></td>
                                <td><?php echo $row->fleet_no; ?></td>
                                <td><?php echo $row->make_type; ?></td>
                                <td><?php echo $row->report_type; ?></td>
                                <td class="report_status"><?php echo $row->r_status; ?>
                                    <br><small class="help-block"><?php echo $row->updated_status; ?></small>
                                </td>
                                <td><?php echo $row->comment; ?></td>
                                <td><?php echo $row->created; ?></td>
                                <td>
                                     <p><a href="javascript:void(0);" class="btn btn-primary btn-xs viewMechanicModal" data-id="<?php echo $row->report_id; ?>"><i class="material-icons" style="font-size: 16.5px;">visibility</i> View Report</a></p>

                                    <p><a href="javascript:void(0);" data-id="<?php echo $row->report_id; ?>" data-href="<?php echo site_url('app/mechanic/viewReport/'.$row->report_id.'/true') ?>" class="btn btn-xs btn-info viewModal"><i class="material-icons" style="font-size: 16px;">touch_app</i> Action</a></p>
                                    <p><a href="javascript:void(0);" data-href="<?php echo site_url('app/mechanic/printReport/'.$row->report_id); ?>" class="btn btn-xs btn-primary print-report"><i class="material-icons" style="font-size: 16px;">local_printshop</i> Print Report</a></p>
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
