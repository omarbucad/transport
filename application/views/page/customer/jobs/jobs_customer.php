<!-- Google Maps API Js -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_API_KEY; ?>"></script>
<script type="text/javascript">
	$(document).ready(function(){

        // $('.jobstable').DataTable({
        //     responsive: true,
        //     stateSave: true,
        //     searching: false,
        //     "bSort" : false,
        //     stateSaveCallback: function(settings,data) {
        //               localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
        //     },
        //     stateLoadCallback: function(settings) {
        //             return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
        //     },
        // });


        $("img.lazy").lazyload({
            threshold : 200,
            effect : "fadeIn",
            skip_invisible : true
        });


        $('.time12').inputmask('hh:mm t', { placeholder: '__:__ _m', alias: 'time12', hourFormat: '12' });

        var dl = [];
        var datalist = document.getElementById("_languages");

        $.ajax({
            url : "<?php echo site_url('app/customer/autocomplete') ?>" ,
            method : "POST" , 
            success : function(response){
                var json = jQuery.parseJSON(response);
                dl = json;
                $.each(json , function(k , v){
                    var option = document.createElement('option');
                    option.value = v.job_name;
                    datalist.append(option);
                });
            }
        });


        $(document).on('input' , '.autocomplete' , function(){
           var data = $(this).val();

           if(data.length != 0){
            var job_config = dl[data];

            var form = $(this).closest("form");

            form.find('[name="number_of_truck"]').val(job_config.number_of_truck).focus();
            form.find('[name="telephone"]').val(job_config.telephone).focus();
            form.find('[name="job_number"]').val(job_config.job_number).focus();
            form.find('[name="job_po_number"]').val(job_config.job_po_number).focus();
            form.find('[name="address"]').val(job_config.address).focus();
            form.find('[name="zip_code"]').val(job_config.zip_code).focus();
            form.find('[name="load_site"]').val(job_config.load_site).focus();
            form.find('[name="zip_code_load_site"]').val(job_config.zip_code_load_site).focus();
            form.find('[name="notes"]').val(job_config.notes).focus();
            form.find('[name="type_of_truck"]').selectpicker("val" , job_config.type_of_truck);

            if(job_config.division){
                form.find('[name="division"]').selectpicker("val" , job_config.division);
            }


            form.find('[name="driver_notes"]').val(job_config.driver_notes).focus();

            if(job_config.build_dismantle_other == 1){
                form.find('[name="build_dismantle"]').selectpicker("val" , "OTHER");
                form.find('[name="build_dismantle_other"]').val(job_config.build_dismantle);
            }else{
                if(job_config.build_dismantle){
                    form.find('[name="build_dismantle"]').selectpicker("val" , job_config.build_dismantle);
                }
            }

            form.find('[name="type_of_truck"]').trigger('change');
            form.find('[name="build_dismantle"]').trigger('change');

        }
    });

    });

	$(document).on('click' , '#addJobsModal' , function(){
		var modal = $('#jobsModal').modal("show");
        var form = modal.find('form');

        form[0].reset();
        form.find('[name="division"]').selectpicker("val" , "");
        form.find('[name="build_dismantle"]').selectpicker("val" , "");

        var l = modal.find('.job-clone').length;

        if(l > 1){
            var a = l - 1;
            for(x = 0 ; a < x ; x++){
                //form.find('.job-clone').last().remove();
            }
        }

    });

	$(document).on('click' , '#saveJobModal' , function(){
		var form = $(this).closest('.modal').find('form');
		var url = form.attr('action');
		var data = form.serialize();
        form.submit();  

        var $btn = $(this);

        $btn.button('loading');
    });

    $(document).on("submit" ,'#_MODAL_CREATEJOB' , function(e){
        e.preventDefault();

        var $btn = $(this).closest(".modal").find('#saveJobModal');

        $.ajax({
            url : $(this).attr("action") ,
            data : new FormData(this) ,
            method : "POST" ,
            contentType: false,
            cache: false,            
            processData:false,   
            success : function(response){

                var json = jQuery.parseJSON(response);

                if(json.status){
                    $('#jobsModal').modal("hide");

                    $('.jobstable > tbody').prepend(json.html);

                    swal({
                        title : "Created!" ,
                        text : "You Successfully Created Jobs" ,
                        type : "success" ,
                        timer: 1000,
                        showConfirmButton: false
                    });

                    $("form").trigger("reset");    

                }else{
                    swal({
                        title : "Error!" ,
                        text : json.message ,
                        type : "error" ,
                        timer: 1000,
                        showConfirmButton: false
                    });
                }
                $btn.button('reset');     
            }

        });
    });

    $(document).on('click' , '.btnUpdate' , function(){
        var type = $(this).data("type");
        var job_id = $(this).data("id");
        var url = "<?php echo site_url('app/customer/updateJob'); ?>";
        var form = $(this).closest("form");
        var $me = $(this);

        form.find("[name='jobs_id']").val(job_id);
        var data = form.serialize();

        if(type == "cancel"){
            var modal = $('#job_cancel_modal').modal("show");
            modal.find('#modal_job_id').val(job_id);
            
        }else{
            sendUpdate(url , data , form , type , $me);  
        }
    });

    $(document).on('click' , '.jobCancelBtn' , function(){
       var url = "<?php echo site_url('app/customer/updateJob'); ?>";
       var modal = $(this).closest('.modal');
       var job_id = modal.find('#modal_job_id').val();
       var type = "cancel";
       var notes = modal.find('#cancel_notes').val();
       var form = $(this).closest('.panel-collapse').find('.last_form');
       var $me = $(this);


       swal({
        title: "Are you sure?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {

        if (isConfirm) {

            sendUpdate(url , {jobs_id : job_id , status_type : type , notes : notes } , form , type , $me); 

            $('#_panel_'+job_id).closest("tr").remove();

        } else {
            swal({
                title : "Cancelled!" ,
                text : "Safe" ,
                type : "error" ,
                timer: 1000,
                showConfirmButton: false
            });
        }

        modal.modal("hide");
    });


   });

    function sendUpdate(url , data , form , type , $me){
        $.ajax({
            url : url ,
            data : data ,
            method : "POST" ,
            success : function(response){

                var json = jQuery.parseJSON(response);

                
                if(json.status){

                    var tr = $me.closest('tr').prev();

                    if(json.legend_name == "Complete"){
                        $me.closest("tr").remove();
                    }else if(json.legend_name == "Cancelled"){
                        $me.closest("tr").remove();
                    }else{
                        $me.closest('tr').remove();

                        if(tr.length == 0){
                            $('.jobstable > tbody > tr:first-child').before(json.html);
                        }else{
                            tr.after(json.html).next().find('.collapsed').trigger('click');
                        }
                    }

                    swal({
                        title : "Updated!" ,
                        text : "" ,
                        type : "success" ,
                        timer: 1000,
                        showConfirmButton: false
                    });

                }else{
                    swal({
                        title : "Error!" ,
                        text : json.message ,
                        type : "error" ,
                        timer: 1000,
                        showConfirmButton: false
                    });
                }
            }
        });
    }

    $(document).on('click' , '.btnUpdateJobs' , function(){
        var id = $(this).data("id");
        var type = $(this).data("type");
        var url = "<?php echo site_url("app/customer/getUpdateForm"); ?>";

        $.ajax({
            url : url ,
            data : {job_id : id , type : type} ,
            method : "POST", 
            success : function(response){
                var modal = $('#job_update_modal').modal("show");
                modal.find("._modal_job_id").html(id);
                modal.find(".modal-body").html(response);

                $('select').selectpicker();
                $('.time12').inputmask('hh:mm t', { placeholder: '__:__ _m', alias: 'time12', hourFormat: '12' });

                $('.dtpicker').bootstrapMaterialDatePicker({
                    format: 'DD MMMM YYYY',
                    clearButton: false,
                    weekStart: 1,
                    time: false
                });
            }
        });

        
    });

    $(document).on('click' , '#job_modal_update_btn' , function(){
        var modal = $(this).closest(".modal");
        var form = modal.find("form");
        var data = form.serialize();
        var url = "<?php echo site_url("app/customer/updateFormJob"); ?>";

        $.ajax({
            url : url ,
            method : "POST" ,
            data : data ,
            success : function(response){
                var json = jQuery.parseJSON(response);

                if(json.status){
                    swal("Updated!", "", "success");
                    swal({
                        title : "Updated!" ,
                        text : "" ,
                        type : "success" ,
                        timer: 1000,
                        showConfirmButton: false
                    });
                    modal.modal("hide");    
                }else{
                   swal({
                    title : "Error!" ,
                    text : json.message ,
                    type : "error" ,
                    timer: 1000,
                    showConfirmButton: false
                });
               }

           }
       });
    });

    $(document).on('change' , 'select._type_of_truck' , function(){
        var type = $(this).val();
        var form = $(this).closest("form");

        if(type == "ARTIC"){
            form.find('#_arctic_type').removeClass("hide").find('select').prop("disabled" , false);
        }else{
            form.find('#_arctic_type').addClass("hide").find('select').prop("disabled" , true);
        }

    });

    $(document).on('change' , 'select.build_dismantle_dropdown' , function(){
        var type = $(this).val();
        var form = $(this).closest("form");


        if(type == "OTHER"){
            form.find('#_build_dismantle').removeClass('hide').find('input').prop("disabled" , false);
        }else{
            form.find('#_build_dismantle').addClass('hide').find('input').prop("disabled" , true);
        }
    });

    $(document).on('click' , '.view_more_btn' , function(){
        if($(this).attr("aria-expanded") != "true"){
            $(this).text("View Less");
        }else{
            $(this).text("View More");
        }
    });

    $(document).on('click' , '#_sort_jobs_click' , function(){
        var other = $(this).data("other");
        var present = $(this).find("i").text();

        $(this).find("i").text($(this).data("other"));
        $(this).data("other" , present);

        if(other == "arrow_upward"){
            $('#_sort_jobs').val("ASC");
        }else{
            $('#_sort_jobs').val("DESC");
        }

        $('#jobs_search_form').trigger("click");
    });

    $(document).on('change' , '#number_of_truck' , function(){
        var num = $(this).val();
        var orig = $(this).closest('form').find('.job-clone').length;

        if(num > 0){

            if(num < orig){
                for(var i = 0 ; i < (orig - num) ; i++){
                    $(this).closest('form').find('.job-clone').last().remove();
                }
            }else{

                for(var i = orig; i < parseInt(num) ; i++){

                    var clone = $(this).closest('form').find('.job-clone').last().clone();
                    clone.find('h4 > span').text(i + 1);
                    clone.find('._loading_date').val();
                    clone.find('.form-line').addClass('focused');
                    clone.find('._delivery_date').val();
                    $(this).closest('form').find('.job-clone').last().after(clone);
                }
            }

            $('.mobile-phone-number').inputmask('+99 (999) 999-99-99');
            $('.time12').inputmask('hh:mm t', { placeholder: '__:__ _m', alias: 'time12', hourFormat: '12' });

            $('.dtpicker').bootstrapMaterialDatePicker({
                format: 'DD MMMM YYYY',
                clearButton: true,
                weekStart: 1,
                time: false
            }).on("change" , function(e , date){
                $(this).focus();
            });

        }   
    });
    $(document).on('change' , '#sort_by' , function(){
       var x = $(this).val();
       $("#_sort_by_jobs").val(x);

   });

    $(document).on('change' , '#limit_jobs' , function(){
     var x = $(this).val();
     $("#_limit_jobs").val(x);
     $('#jobs_search_form').trigger("click");
 });

    $(document).on('click' , '.cParent' , function(){
        var isLoaded = $(this).data("loaded");
        var id = $(this).data("id");
        var $me = $(this);

        if(isLoaded == false){
            $(this).data("loaded" , true);
            $.ajax({
                url : "<?php echo site_url('app/customer/loadJobById/'); ?>"+id ,
                method : "GET" ,
                beforeSend  : function(){
                    $me.closest("tr").find(".panel-body").html("Loading");
                },
                success : function(response){
                    $me.closest("tr").find(".panel-body").html(response);
                }
            });
        }

    });

    $(document).on("click" , ".j_status_btn_search" , function(){
        var type = $(this).data("type");
        $("#_j_status").val(type);
        $('#jobs_search_form').trigger("click");
    });
</script>


<style type="text/css">
    .jobstable thead{
        display:none !important;
    }
    .pagination {
        margin: auto;
    }
    @media (max-width: 765px){
        .page {
            float: left !important;
            text-align: left;
        }
    }
    
</style>


<div class="block-header">
    <h2>
        <a role="button" class="btn btn-success" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="material-icons" style="position: relative;font-size: 16.5px;">search</i> Search</a>
        <a href="javascript:void(0);" class="btn btn-primary" id="addJobsModal"><i class="material-icons" style="position: relative;font-size: 16.5px;">add_circle_outline</i> Add Jobs</a>
    </h2>
</div>

<div style="margin-bottom: 20px;">
    <div class="collapse" id="collapseExample">
        <?php $this->load->view('page/customer/jobs/search' , array("type" , "CUSTOMER")) ?>
    </div>
</div>

<div class="row clearfix" >
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    LEGEND
                </h2>
            </div>
            <div class="body legend">
                <a class="label bg-blue" style="padding: 5px;" href="<?php echo site_url("app/customer/jobs/?status=new&submit=submit"); ?>"><i class="material-icons" style="top: 3.5px;position: relative;font-size: 15.5px;padding-right: 2.5px;">bookmark_border</i> New Jobs</a>
                <a class="label bg-orange" style="padding: 5px;" href="<?php echo site_url("app/customer/jobs/?status=to_be_allocated&submit=submit"); ?>"><i class="material-icons" style="top: 3.5px;position: relative;font-size: 15.5px;padding-right: 2.5px;">bookmark</i> Truck To Be Allocated</a>
                <a class="label bg-light-green" style="padding: 5px;" href="<?php echo site_url("app/customer/jobs/?status=allocated&submit=submit"); ?>"><i class="material-icons" style="top: 3.5px;position: relative;font-size: 15.5px;padding-right: 2.5px;">book</i> Truck Allocated</a><div class="visible-xs"></div>
                <a class="label bg-green" style="padding: 5px;"  href="<?php echo site_url("app/customer/jobs/?status=partially_complete&submit=submit"); ?>"><i class="material-icons" style="top: 3.5px;position: relative;font-size: 15.5px;padding-right: 2.5px;">check_circle</i> For Confirmation</a>
                <a class="label bg-green" style="padding: 5px;" href="<?php echo site_url("app/customer/jobs/?status=finished&submit=submit"); ?>"><i class="material-icons" style="top: 3.5px;position: relative;font-size: 15.5px;padding-right: 2.5px;">check_circle</i> Complete</a>
                <a class="label bg-red" style="padding: 5px;" href="<?php echo site_url("app/customer/jobs/?status=cancel&submit=submit"); ?>"><i class="material-icons" style="top: 3.5px;position: relative;font-size: 15.5px;padding-right: 2.5px;">check_circle</i> Cancelled</a>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix" >
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    JOBS LIST
                </h2>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12">

                        <div class="col-sm-6 col-xs-12">

                            <strong>Show</strong>
                            <div style="margin: -7px 10px 0 0;display: inline-block;">
                                <select class="form-control show-tick" id="limit_jobs" style="border:hidden;border-radius:0;border-bottom:1px solid #ccc;box-shadow:none;font-size:13px;">
                                    <option value="10" <?php echo ($this->input->get("limit") == "10") ? "selected" : "" ;?> >10</option>
                                    <option value="20" <?php echo ($this->input->get("limit") == "20") ? "selected" : "" ;?>>20</option>
                                    <option value="50" <?php echo ($this->input->get("limit") == "50" OR !$this->input->get("limit")) ? "selected" : "" ;?>>50</option>
                                    <option value="100" <?php echo ($this->input->get("limit") == "100") ? "selected" : "" ;?>>100</option>
                                </select>
                            </div> 
                            <strong>entries</strong> &emsp;&emsp; <br class="visible-sm" style="line-height:3">

                            <strong class="sort">Sort by</strong>
                            <div style="margin: -7px 10px 0 0;display: inline-block;" style="border:hidden;border-radius:0;border-bottom:1px solid #ccc;box-shadow:none;font-size:13px;">
                                <select class="form-control show-tick" id="sort_by">
                                    <option value="jp.created" <?php echo ($this->input->get("sort_by") == "jp.created") ? "selected" : "" ;?> >Jobs Created</option>
                                    <option value="j.delivery_time" <?php echo ($this->input->get("sort_by") == "j.delivery_time") ? "selected" : "" ;?>>Delivery Date</option>
                                    <option value="j.loading_time" <?php echo ($this->input->get("sort_by") == "j.loading_time") ? "selected" : "" ;?>>Pick-up Date</option>
                                </select>
                            </div>


                            <li style="list-style: none;display: inline-block;">
                                <?php if($this->input->get("sort") == "ASC") : ?>
                                    <a href="javascript:void(0);" id="_sort_jobs_click" data-other="arrow_downward" title="Descending">
                                        Descending
                                    </a>
                                <?php elseif($this->input->get("sort") == "DESC") : ?>
                                    <a href="javascript:void(0);" id="_sort_jobs_click" data-other="arrow_upward" title="Ascending">
                                        Ascending
                                    </a>
                                <?php else : ?>
                                    <a href="javascript:void(0);" id="_sort_jobs_click" data-other="arrow_upward" title="Ascending">
                                        Ascending
                                    </a>    
                                <?php endif; ?>
                            </li>

                        </div>

                        <div class="col-sm-6 col-xs-12 text-right page">
                            <?php echo $links; ?>
                        </div>

                        <div class="row ">
                            <div class="col-lg-12 col-xs-12 text-right">

                               <span>
                                <button style="padding:0 12px;position:relative;font-size:17px;" class="btn btn-info j_status_btn_search" data-type="q">&#63;</button>  
                                <button style="padding:1.5px 8px;position:relative;" class="btn btn-success j_status_btn_search" data-type="c"><i class="material-icons" style="position: relative;font-size: 16.5px;">check</i></button>  
                                <button style="padding:1.5px 8px;position:relative;" class="btn btn-danger j_status_btn_search" data-type="x"><i class="material-icons" style="position: relative;font-size: 16.5px;">clear</i></button>  
                            </span>

                            </div>
                        </div>

                        <table class="jobstable" style="width: 100%; margin-bottom: 20px;">
                            <thead>
                                <tr>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $this->load->view('page/customer/ajax/ajax_customer') ?>
                            </tbody>
                        </table>


                        <div class="col-sm-6 col-xs-12">

                            <strong>Show</strong>
                            <div style="margin: -7px 10px 0 0;display: inline-block;">
                                <select class="form-control show-tick" id="limit_jobs" style="border:hidden;border-radius:0;border-bottom:1px solid #ccc;box-shadow:none;font-size:13px;">
                                    <option value="10" <?php echo ($this->input->get("limit") == "10") ? "selected" : "" ;?> >10</option>
                                    <option value="20" <?php echo ($this->input->get("limit") == "20") ? "selected" : "" ;?>>20</option>
                                    <option value="50" <?php echo ($this->input->get("limit") == "50" OR !$this->input->get("limit")) ? "selected" : "" ;?>>50</option>
                                    <option value="100" <?php echo ($this->input->get("limit") == "100") ? "selected" : "" ;?>>100</option>
                                </select>
                            </div> 
                            <strong>entries</strong> &emsp;&emsp; <br class="visible-sm" style="line-height:3">

                            <strong>Sort by</strong>
                            <div style="margin: -7px 10px 0 0;display: inline-block;">
                                <select class="form-control show-tick" id="sort_by">
                                    <option value="jp.created" <?php echo ($this->input->get("sort_by") == "jp.created") ? "selected" : "" ;?> >Jobs Created</option>
                                    <option value="j.delivery_time" <?php echo ($this->input->get("sort_by") == "j.delivery_time") ? "selected" : "" ;?>>Delivery Date</option>
                                    <option value="j.loading_time" <?php echo ($this->input->get("sort_by") == "j.loading_time") ? "selected" : "" ;?>>Pick-up Date</option>
                                </select>
                            </div>


                            <li style="list-style: none;display: inline-block;">
                                <?php if($this->input->get("sort") == "ASC") : ?>
                                    <a href="javascript:void(0);" id="_sort_jobs_click" data-other="arrow_downward" title="Descending">
                                        Descending
                                    </a>
                                <?php elseif($this->input->get("sort") == "DESC") : ?>
                                    <a href="javascript:void(0);" id="_sort_jobs_click" data-other="arrow_upward" title="Ascending">
                                        Ascending
                                    </a>
                                <?php else : ?>
                                    <a href="javascript:void(0);" id="_sort_jobs_click" data-other="arrow_upward" title="Ascending">
                                        Ascending
                                    </a>    
                                <?php endif; ?>
                            </li>

                        </div>

                        <div class="col-sm-6 col-xs-12 text-right page">
                            <?php echo $links; ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>