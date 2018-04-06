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

        $(document).on('click' , '.print_all' , function(){
            var url = "<?php echo site_url('app/reports/printReport/false'); ?>";

            var id = "";
            var a = 0;

            $.each( $('.tr_invoice_id' , allPages) , function(k , v){
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
                swal("Warning", "No Selected Invoice", "error");
            }

        });

        $(document).on('click' , '#acceptTerms-asdvv' , function(){
            var isChecked = $(this).is(":checked");

            $.each( $('.tr_invoice_id' , allPages) , function(k , v){
                if(isChecked){  
                    $(v).prop('checked', true);
                }else{

                    $(v).prop('checked', false);
                }
            });
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


        $(document).on('click' , '.viewReportModal' , function(){
            var id = $(this).data("id");

            loadReport(id);
        });

        $(document).on('click' , '#ViewReport ._prev' , function(){
            var i = $(this).data("id");
            loadReport(i);
        });

        $(document).on('click' , '#ViewReport ._next' , function(){
            var i = $(this).data("id");
            loadReport(i);
        });

        function loadReport(id){
            var modal = $('#ViewReport').modal("show");
            var url = "<?php echo site_url("app/reports/getReport/"); ?>"+id+"/<?php echo $get_form;?>";

            $.ajax({
                url : url ,
                method : "POST" ,
                beforeSend : function(){
                    modal.find('.modal-body').html("LOADING...");
                },
                success : function(response){
                    var json = jQuery.parseJSON(response);
                    modal.find('.modal-body').html(json.html);
      
                    if(json.prev.id != null){
                        modal.find("._prev").show().data("id" , json.prev.id);
                        
                    }else{
                        modal.find("._prev").hide();
                    }

                    if(json.next.id != null){
                        modal.find("._next").show().data("id" , json.next.id);
                    }else{
                        modal.find("._next").hide();
                    }
                    

                }
            });
        }

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

                                $("._tr_defect_"+json.report_id).find('._view_status').html(json.status);
                                $("._tr_defect_"+json.report_id).find('._view_fixed_by').html(json.fixed_by);

                                swal("Updated!", "Successfully Updated" , "success");
                                $('#defaultModal').modal('hide');
                                form[0].reset();
                            }
                        });
                    }
                } 
            });
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
                Checklist Report List
            </li>
        </ol>
    </h2>
</div>  
<?php $this->load->view("common/row") ?>
<div class="block-header">
    <h2>
        <span class="pull-right">
            <a role="button" href="javascript:void(0);" class="btn btn-primary print_all" ><i class="material-icons" style="position: relative;font-size: 16.5px;">print</i> Print All</a>
        </span>

        <a role="button" class="btn btn-danger" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="material-icons" style="position: relative;font-size: 16.5px;">search</i> Search</a>
        
        <a role="button" href="<?php echo site_url('app/reports/checklist/?status=fixed');?>" class="btn btn-success fixed" ><i class="material-icons" style="position: relative;font-size: 16.5px;">done</i> Fixed</a>
        <a role="button" href="<?php echo site_url('app/reports/checklist/?status=under_maintenance');?>" class="btn btn-warning under_maintenance" ><i class="material-icons" style="position: relative;font-size: 16.5px;">build</i> Under Maintenance</a>
        <a role="button" href="<?php echo site_url('app/reports/checklist/?status=open');?>" class="btn btn-primary open" ><i class="material-icons" style="position: relative;font-size: 16.5px;">error_outline</i> Open</a>
        <a role="button" href="<?php echo site_url('app/reports/checklist/?search=all');?>" class="btn btn-danger all" ><i class="material-icons" style="position: relative;font-size: 16.5px;">donut_large</i> All</a>
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
                    CHECKLIST REPORT LIST
                </h2>
            </div>
            <div class="body table-responsive">
                <form>
                    <table class="table table-bordered table-striped table-hover dt">
                    <thead>
                        <tr>
                            <th><input id="acceptTerms-asdvv" type="checkbox" class="tr_invoice_all">
                                    <label for="acceptTerms-asdvv"></label>  </th>
                            <th>Report #</th>
                            <th>Driver</th>
                            <th>Trailer Number</th>
                            <th>Vehicle</th>
                            <th>Checklist Type</th>
                            <th>Report Type</th>
                            <th>Start & End Date</th>
                            <th>Start & End Mileage</th>
                            <th>Description</th>
                            <th>Created</th>
                            <th>Status</th>
                            <th>Fixed By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>Report #</th>
                            <th>Driver</th>
                            <th>Trailer Number</th>
                            <th>Vehicle</th>
                            <th>Checklist Type</th>
                            <th>Report Type</th>
                            <th>Start & End Date</th>
                            <th>Start & End Mileage</th>
                            <th>Description</th>
                            <th>Created</th>
                            <th>Status</th>
                            <th>Fixed By</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach ($result as $key => $row): ?>
                            <tr class="_tr_defect_<?php echo $row->id; ?>">
                                <td> <input id="acceptTerms-asd<?php echo $key; ?>"  value="<?php echo $row->id; ?>" type="checkbox" class="tr_invoice_id">
                                    <label for="acceptTerms-asd<?php echo $key; ?>"></label> </td>
                                <td><?php echo $row->id; ?></td>
                                <td><?php echo $row->name.' '.$row->surname; ?></td>
                                <td><?php echo $row->trailer_number; ?></td>
                                <td><a href="<?php echo site_url('app/reports/vehicles/'.$row->vehicle_registration_number); ?>"><?php echo $row->vehicle_registration_number; ?></a></td>
                                <td><?php echo $row->checklist_type; ?></td>
                                <td><?php echo $row->report_type; ?></td>
                                <td><?php echo $row->start_date.' - '.$row->end_date; ?></td>
                                <td><?php echo $row->start_mileage.' - '.$row->end_mileage; ?></td>
                                
                                <td><?php echo $row->comment; ?></td>
                                <td><?php echo $row->created; ?></td>
                                <td class="_view_status"><?php echo $row->status; ?></td>
                                <td class="_view_fixed_by"><?php echo $row->fixed_by_name.' '.$row->fixed_by_surname; ?></td>
                                <td>
                                    <p><a href="javascript:void(0);" class="btn btn-primary btn-xs viewReportModal" data-id="<?php echo $row->id; ?>"><i class="material-icons" style="font-size: 16.5px;">visibility</i> View Report</a></p>
                                    <p><a href="javascript:void(0);" data-id="<?php echo $row->id; ?>" data-href="<?php echo site_url('app/reports/getReportById/'.$row->id) ?>" class="btn btn-xs btn-info viewModal"><i class="material-icons" style="font-size: 16px;">touch_app</i> Action</a></p>
                                    <p><a href="javascript:void(0);" data-href="<?php echo site_url('app/reports/printReport/'.$row->id); ?>" class="btn btn-xs btn-primary print-report"><i class="material-icons" style="font-size: 16px;">local_printshop</i> Print Report</a></p>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- #END# Basic Examples -->
