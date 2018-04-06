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

    });
</script>
<style type="text/css">
    @media (min-width: 992px) {
        .modal-lg {
            width: 80% !important;
        }
    }
</style>

<?php $this->load->view("common/row") ?>

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
