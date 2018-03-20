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



<!-- Basic Examples -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    NEEDS SERVICING
                </h2>
            </div>
            <div class="body table-responsive">
                <table class="table table-bordered table-striped table-hover dt" id="mechanic_checklist_report_table">
                    <thead>
                        <tr>
                            <th><nobr>Report #</nobr></th>
                            <th><nobr>Registration #</nobr></th>
                            <th><nobr>Fleet No.</nobr></th>
                            <th><nobr>Make and Type</nobr></th>
                            <th><nobr>Servicing Date</nobr></th>
                            <th><nobr>Action</nobr></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th><nobr>Report #</nobr></th>
                            <th><nobr>Registration #</nobr></th>
                            <th><nobr>Fleet No.</nobr></th>
                            <th><nobr>Make and Type</nobr></th>
                            <th><nobr>Servicing Date</nobr></th>
                            <th><nobr>Action</nobr></th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach($servicing as $key => $row) : ?>
                            <tr id="_SERVICING_<?php echo $row->report_id; ?>">
                                <td><?php echo $row->report_id; ?></td>
                                <td><?php echo $row->registration_no; ?></td>
                                <td><?php echo $row->fleet_no; ?></td>
                                <td><?php echo $row->make_type; ?></td>
                                <td><?php echo $row->servicing_date; ?></td>
                                <td><a href="javascript:void(0);" data-id="<?php echo $row->report_id; ?>" class="btn btn-xs btn-info viewMechanicModal">View Previous Report</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- #END# Basic Examples -->
