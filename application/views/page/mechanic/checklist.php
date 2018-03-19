<script type="text/javascript">
    $(function () {
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
            });
            
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
        <a role="button" class="btn btn-success" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="material-icons" style="position: relative;font-size: 16.5px;">search</i> Search</a>
        <a role="button" href="javascript:void(0);" class="btn btn-primary print_all" ><i class="material-icons" style="position: relative;font-size: 16.5px;">print</i> Print All</a>
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
                    MECHANIC CHECKLIST REPORT
                </h2>
            </div>
            <div class="body table-responsive">
                <table class="table table-bordered table-striped table-hover dt" id="mechanic_checklist_report_table">
                    <thead>
                        <tr>
                            <th><input id="acceptTerms-asdvv" type="checkbox" class="tr_invoice_all"><label for="acceptTerms-asdvv"></label>  </th>
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
                                <td></td>
                                <td><?php echo $row->report_id; ?></td>
                                <td><?php echo $row->operator; ?></td>
                                <td><?php echo $row->mileage_in; ?></td>
                                <td><?php echo $row->mileage_out; ?></td>
                                <td><?php echo $row->registration_no; ?></td>
                                <td><?php echo $row->fleet_no; ?></td>
                                <td><?php echo $row->make_type; ?></td>
                                <td><?php echo $row->report_type; ?></td>
                                <td class="report_status"><?php echo $row->r_status; ?></td>
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
