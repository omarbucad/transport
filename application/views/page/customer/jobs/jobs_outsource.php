<!-- Google Maps API Js -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_API_KEY; ?>"></script>
<script type="text/javascript">
    $(document).ready(function(){

        $("img.lazy").lazyload({
            threshold : 200,
            effect : "fadeIn",
            skip_invisible : true
        });

        var loading = false;

        // $(window).scroll(function() {

        //    if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {

        //         var skip = $('.panel-group > .collapsePanelLength').length;
        //         var maxReport = <?php echo $result_count; ?>;
        //         var segment1 = "<?php echo $this->uri->segment(2); ?>";
        //         if(segment1 == "dashboard"){
        //             var segment4 = "";
        //             var segment5 = "";
        //         }else{
        //             var segment4 = "<?php echo $this->uri->segment(4); ?>";
        //             var segment5 = "<?php echo $this->uri->segment(5); ?>";
        //         }


        //         if(segment4 && segment5){
        //             var url = "<?php echo site_url("app/customer/jobs/"); ?>"+segment4+"/"+segment5;
        //         }else if(segment4){
        //             var url = "<?php echo site_url("app/customer/jobs/"); ?>"+segment4;
        //         }else{
        //             var url = "<?php echo site_url("app/customer/".$request_parameters); ?>";
        //         }

        //         if(skip <= maxReport && loading == false){

        //             loading = true;

        //             $.ajax({
        //                 url : url,
        //                 data : {skip : skip},
        //                 method : "POST",
        //                 success : function(response){

        //                     $('.panel-group > .collapsePanelLength').last().after(response);

        //                     loading = false;

        //                     $("img.lazy").lazyload({
        //                         threshold : 200,
        //                         effect : "fadeIn",
        //                         skip_invisible : true
        //                     });

        //                     if($(".custom-thumbnails").data("lightGallery")){
        //                         $(".custom-thumbnails").data("lightGallery").destroy(true);
        //                     }

        //                     $('.custom-thumbnails').lightGallery({
        //                         thumbnail: true,
        //                         selector: 'a.c-thumbnails'
        //                     }); 
        //                 }
        //            });
        //         } 
        //     }
        // });

        $('.mobile-phone-number').inputmask('+99 (999) 999-99-99');
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

                    swal("Created!", "You Successfully Created Jobs", "success");

                    $("form").trigger("reset");    

                }else{
                    swal("Error", json.message , "error");
                }

                $btn.button('reset');     
            }

        });
    });

    $(document).on("click" , '.get_customer_info' , function(){
        var id = $(this).data("id");
        var url = "<?php echo site_url('app/customer/getCustomerInfo'); ?>";
        
        $.ajax({
            url : url ,
            data : {id : id},
            method : "post" ,
            success : function(response){
                var json = jQuery.parseJSON(response);
                var modal = $('#customerModal').modal('show');
                modal.find('.modal-title').html(json.company_name);
                modal.find('#_email').html(json.email);
                modal.find('#_registration_number').html(json.registration_number);
                modal.find('#_vat_number').html(json.vat_number);
                modal.find('#_address').html(json.address);
                modal.find('#_billing_address').html(json.billing_address);
                modal.find('#_created_jobs').html(json.posted_jobs);
                modal.find('#_created').html(json.created);
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

            $(this).button('loading');
  
            setTimeout(function () {
                $(this).button('reset');
            }, 2000);

            sendUpdate(url , data , form , type , $me);

        }
    });

    $(document).on('click' , '.jobCancelBtn' , function(){
         var url = "<?php echo site_url('app/customer/updateJob'); ?>";
         var modal = $(this).closest('.modal');
         var job_id = modal.find('#modal_job_id').val();
         var type = "cancel";
         var notes = modal.find('#cancel_notes').val();
         var cb = modal.find('#modal_charge_checkbox').is(":checked");
         var c = modal.find('#modal_with_charge').val();
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
                    sendUpdate(url , {jobs_id : job_id , status_type : type , notes : notes , charge_checkbox : cb , with_charge : c } , form , type , $me); 
                    $('#_panel_'+job_id).closest("tr").remove();
                } else {
                    swal("Cancelled", "safe :)", "error");
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
                console.log(response);
                var json = jQuery.parseJSON(response);

                if(json.status){
                    $panel_group = $me.closest('.panel-group');
                    var tr = $me.closest('tr').prev();

                    if(json.legend_name == "Complete"){
                        $me.closest("tr").remove();
                    }else if(json.legend_name == "Cancelled"){
                        $me.closest("tr").remove();
                    }else{
                        $me.closest('tr').remove();

                        if(tr.length == 0){
                            $('.jobstable').append(json.html);
                        }else{
                            tr.after(json.html).next().find('.collapsed').trigger('click');
                        }
                    }

                    swal("Updated!", "", "success");
                }else{
                    swal("Error!", json.message , "error");
                }

                $me.button('reset');
            }
        });
    }

    $(document).on("click" , '.checkbox-edit-demurrage' , function(){
        var input_id = $( "#"+$(this).data("inputid") );

        if($(this).is(":checked")){
            input_id.prop('readonly' , false);
        }else{
            input_id.prop('readonly' , true);
        }
    });

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

                if(json.result.price){
                    var total = (parseFloat(json.result.price) + parseFloat(json.result.vat)).toFixed(2);
                    var job = $('#_panel_'+json.job_id);
                    job.find(".compute_vat").val(json.result.vat);
                    job.find(".compute_total_price").data("value" , json.result.price);
                    job.find(".compute_total_price").val(total);
                }

                if(json.status){
                    swal("Updated!", "", "success");
                    modal.modal("hide");    
                }else{
                    swal("Error!", json.message , "error");
                }
            }
        });
    });
    $(document).on('keyup' , '.compute_total_price , .compute_vat , .compute_demurrage' , function(){
        var $form = $(this).closest('form');
        var $class = this.className;

        var demurrage = $form.find('.compute_demurrage');
        var vat = $form.find('.compute_vat');
        var total_price = $form.find('.compute_total_price');
        var total_price_original = parseFloat(total_price.data('value'));

        var $vat = 0.20;

        var total = parseFloat(demurrage.val()) + total_price_original;
        
        $vat = total * $vat;
        total = total + $vat;

        if($class.indexOf("compute_total_price") >= 0){
            vat.val($vat.toFixed(2));
        }else if($class.indexOf("compute_demurrage") >= 0){
            vat.val($vat.toFixed(2));
            total_price.val(total.toFixed(2));
        }
    });
    $(document).on('click' , '#modal_charge_checkbox' , function(){

        var form = $(this).closest('.modal').find("form");
        var div = form.find("#modal_charge_div");

        if($(this).is(":checked")){
            div.removeClass("hide");
        }else{
            div.addClass("hide");
        }
    });
    $(document).on('click' , '.view_map' , function(){
        var modal = $('#view_map_modal').modal("show");
        var latitudeA = $(this).data("latitudea");
        var longitudeA = $(this).data("longitudea");
        var latitudeB = $(this).data("latitudeb");
        var longitudeB = $(this).data("longitudeb");
        var driver = $(this).data("driver");

        setTimeout(function(){
            var uluru = {lat: latitudeA, lng: longitudeA};
            var map = new google.maps.Map(document.getElementById('_map'), {
              zoom: 14,
              center: uluru
            });

            var marker = new google.maps.Marker({
              position: uluru,
              label : driver + " at Warehouse " ,
              map: map
            });

            if(latitudeB != "NA"){
                marker = new google.maps.Marker({
                  position: {lat: latitudeB, lng: longitudeB},
                  label : driver + " at Destination ",
                  map: map
                });
            }
        },500);
    });
    $(document).on('click' , '.checkbox-with_outsouce' , function(){
        var div = $(this).closest('form').find('.outsource');

        if($(this).is(":checked")){
            $(this).closest('td').find(".hide_on_outsourced").addClass("hide");
            $(this).closest('td').find(".show_on_outsourced").removeClass("hide");
            div.removeClass('hide');
        }else{
            $(this).closest('td').find(".hide_on_outsourced").removeClass("hide");
            $(this).closest('td').find(".show_on_outsourced").addClass("hide");
            div.addClass('hide');
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
    $(document).on('change' , '#sort_by' , function(){
         var x = $(this).val();
         $("#_sort_by_jobs").val(x);
    
    });


    $(document).on('click' , '.bck-status-btn' , function(){
        var id = $(this).data("id");
        var url = "<?php echo site_url('app/customer/backToNew'); ?>";
        $me = $(this);

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
                
                $.ajax({
                    url : url ,
                    data : {jobs_id : id },
                    method : "POST" , 
                    success : function(response){
                        var json = jQuery.parseJSON(response);

                        $panel_group = $me.closest('.panel-group');
                        $prev = $me.closest('.collapsePanelLength').prev();

                        if(json.legend_name == "Complete"){
                            $me.closest(".collapsePanelLength").remove();
                        }else{
                            if($prev.length == 0){
                                $me.closest('.collapsePanelLength').remove();
                                $panel_group.prepend(json.html).find('.collapsePanelLength:first-child a.collapsed').trigger('click');
                            }else{
                                $me.closest('.collapsePanelLength').remove();
                                $prev.after(json.html).next().find('a.collapsed').trigger('click');
                            }
                        }

                        swal("Updated!", "", "success");
                    }
                });


            } else {
                swal("Cancelled", "safe :)", "error");
            }

 
        });
    });
    
</script>


<?php if($this->uri->segment(2) != "dashboard") : ?>
<div class="block-header">
    <h2>
        <ol class="breadcrumb" style="padding: 0px;">
            <li>
                <a href="<?php echo site_url('app/dashboard'); ?>">
                    Dashboard
                </a>
            </li>
            <li class="active">
                Jobs List
            </li>
        </ol>
    </h2>
</div>  
<?php endif; ?>
<div class="row">
    <div class="col-lg-4 col-xs-12">
        <div class="block-header">
            <h2>
                <a role="button" class="btn btn-success" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Search</a>
            </h2>
        </div>
    </div>
    <div class="col-lg-4  col-xs-12">

    </div>
</div>

<div style="margin-bottom: 20px;">
    <div class="collapse" id="collapseExample">
        <?php $this->load->view('page/customer/jobs/search' , array("type" , "ADMIN")) ?>
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
                <a class="label bg-blue" href="<?php echo site_url("app/customer/jobs/?status=new&submit=submit"); ?>">New Jobs</a>
                <a class="label bg-orange"  href="<?php echo site_url("app/customer/jobs/?status=to_be_allocated&submit=submit"); ?>">Truck To Be Allocated</a>
                <a class="label bg-light-green"  href="<?php echo site_url("app/customer/jobs/?status=allocated&submit=submit"); ?>">Truck Allocated</a><div class="visible-xs"></div>
                <a class="label bg-green"  href="<?php echo site_url("app/customer/jobs/?status=finished&submit=submit"); ?>">Complete</a>
                <a class="label bg-red"  href="<?php echo site_url("app/customer/jobs/?status=cancel&submit=submit"); ?>">Cancelled</a>
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
                        <div class="col-sm-3 pull-right" style="margin: -7px 15px 0 0;">
                            <select class="form-control show-tick" id="sort_by">
                                <option value="jp.created" <?php echo ($this->input->get("sort_by") == "jp.created") ? "selected" : "" ;?> >Jobs Created</option>
                                <option value="j.delivery_time" <?php echo ($this->input->get("sort_by") == "j.delivery_time") ? "selected" : "" ;?>>Delivery Date</option>
                                <option value="j.loading_time" <?php echo ($this->input->get("sort_by") == "j.loading_time") ? "selected" : "" ;?>>Pick-up Date</option>
                            </select>
                        </div>      
                </h2>
                <ul class="header-dropdown m-r-0">
                    <li>
                        <?php if($this->input->get("sort") == "ASC") : ?>
                            <a href="javascript:void(0);" id="_sort_jobs_click" data-other="arrow_downward" title="Descending">
                                <i class="material-icons">arrow_upward</i>
                            </a>
                        <?php elseif($this->input->get("sort") == "DESC") : ?>
                            <a href="javascript:void(0);" id="_sort_jobs_click" data-other="arrow_upward" title="Ascending">
                                <i class="material-icons">arrow_downward</i>
                            </a>
                        <?php else : ?>
                            <a href="javascript:void(0);" id="_sort_jobs_click" data-other="arrow_upward" title="Ascending">
                                <i class="material-icons">arrow_downward</i>
                            </a>    
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12">
                        <div class="panel-group panel-append-here custom-thumbnails" role="tablist" aria-multiselectable="false">
                            <?php $this->load->view('page/customer/ajax/ajax_admin') ?>
                            <?php if(!$result) : ?>
                                <p>No Result...</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

