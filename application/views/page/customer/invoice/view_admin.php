<script type="text/javascript">
    $(document).ready(function(){
        $("img.lazy").lazyload({
            threshold : 200,
            effect : "fadeIn",
            skip_invisible : true
        });

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

        $(document).on('click' , '.single_download_invoice' , function(){
            var url = $(this).data("href");
            window.open( url , "Invoice PDF ", "height=200,width=200");
            
        });
        $(document).on('click' , '.batch_download_invoice' , function(){
            var url = $(this).data("href");

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
                window.open( url+id , "Invoice PDF ", "height=200,width=200");
            }else{
                swal("Warning", "No Selected Invoice", "error");
            }

        });

        $(document).on('click' , '.batch_pay_invoice' , function(){
            var url = $(this).data("href");

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
                window.location.replace(url+id);
            }else{
                swal("Warning", "No Selected Invoice", "error");
            }
            
        });
        var _x = true;

        $(document).on('click' , '.batch_merge_invoice' , function(){
            var url = $(this).data("href");

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
                    


                    var id = "";
                    var a = 0;

                    var bw = 0;
                    var out = 0;

                    $.each( $('.tr_invoice_id' , allPages) , function(k , v){

                        if($(v).is(':checked') && $(v).hasClass('outsourced')){
                            out++;
                        }

                        if($(v).is(':checked') && !($(v).hasClass('outsourced'))){
                            bw++;
                        }

                        if(bw > 0 && out > 0){
                            swal("Warning", "Outsource Invoice can be Merged with Outsource Invoice only", "error");
                            _x = false;
                        }

                        if($(v).is(':checked') && $(v).hasClass('is_parent')){
                            swal("Warning", "Merged Invoice Can't be Merged to Other Invoice", "error");
                            _x = false;
                        }

                    });




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
                        var count = (id.match(/id/g) || []).length;
                        if(count > 1){
                            if(_x){

                                $.ajax({
                                    url : url+id ,
                                    method : "GET" ,
                                    success : function(){
                                        location.reload();
                                    }
                                });
                            }
                        }else{
                            swal("Warning", "Merging of Invoice Requires More Than One Selected Invoice", "error");
                            _x = false;
                           
                        }
                    }else{
                        swal("Warning", "No Selected Invoice", "error");
                         _x = true;
                        
                    }
                } else {

                    swal("Cancelled", "safe :)", "error");
                     _x = true;

                }

                _x = false;

            });

        });


        $(document).on('click' , '.tr_invoice_id' , function(){
            var selected_count = $('.tr_invoice_id' , allPages).filter(':checked').length;
            var price = 0;
            $.each( $('.tr_invoice_id' , allPages) , function(k , v){
                if($(v).is(':checked')){
                    var i = $(v).data("price");
                    if($(v).data("op") != ''){
                        price += $(v).data("price");
                    }else{
                        price += i;    
                    }

                }
            });

            $('#invoice_price').html(price.toFixed(2));
            $('#invoice_selected').html(selected_count);
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


            var selected_count = $('.tr_invoice_id' , allPages).filter(':checked').length;
            var price = 0;
            $.each( $('.tr_invoice_id' , allPages) , function(k , v){
                if($(v).is(':checked')){
                    // var i = $(v).data("price");
                    // if($(v).data("op") != ''){
                        price += $(v).data("price");
                    // }else{
                    //     price += i;    
                    // }
                }
            });

            $('#invoice_price').html(price.toFixed(2));
            $('#invoice_selected').html(selected_count);

        });


        $(document).on('change' , '._CustomerName' , function(){
            var url = "<?php echo site_url('app/customer/autocomplete'); ?>";
            var dl = [];
            var datalist = document.getElementById("_jobName");
            datalist.innerHTML = "";
            $.ajax({
                url : url ,
                data : {customer_name : $(this).val()},
                method : "post" ,
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
        });

        $(document).on('click' , '.view_invoice_pdf' , function(){
            var modal = $('#invoice_pdf_modal').modal("show");
            var id = $(this).data("id");

            var a = $("<a>" , {href : $(this).data("pdf") , text:$(this).data("pdf") });
            var object = '<object data="'+$(this).data("pdf") +'" , type="application/pdf" style="width:100%;height:800px;">'+a+'</object>';
            // var object = $("<object>" , {"data": $(this).data("pdf") , type:"application/pdf" , width:300 , height:200});
            // object.append(a);

            $('#_pdfViewer').html(object);
            //PDFObject.embed( $(this).data("pdf") , "#_pdfViewer" , {forcePDFJS: true});

            var prev = "";
            var next = "";
            var h = [];

            $.each( $('.tr_invoice_id' , allPages) , function(k , v){
                if($(v).is(':checked')){
                    var i = $(v).val();
                    h.push(i);
                
                }
            });

            $.each(h , function(k , v){

                if(v == id){
                    prev = h[k+1];
                    next = h[k-1];   
                }
            });

            if(prev == undefined || prev == ""){
                modal.find('._prev').hide();
            }else{
                modal.find('._prev').show().data("id" , prev);
            }


            if(next == undefined || next == ""){
                modal.find("._next").hide();
            }else{
                modal.find('._next').show().data("id" , next);
            }

        });

        $(document).on('click' , '#invoice_pdf_modal ._next , #invoice_pdf_modal ._prev' , function(){
            var id = $(this).data("id");
            
            //$('.modal').modal("hide");
            $('#_tr_'+id).find(".view_invoice_pdf").trigger("click");
        });
    });
    

    $(document).on('click' , '.confirm_invoice' , function(){
        var id = $(this).data("id");
        var url = $(this).data("href");

        $.ajax({
            url : url ,
            data : {id : id},
            type : "POST" ,
            success : function(response){
                 var modal = $('#invoice_modal').modal("show");
                 var json = jQuery.parseJSON(response);

                 modal.find('#modal_confirmed_btn').show();

                 modal.find('#modal_confirmed_btn').data('id' , json.invoice_id);
                 modal.find('#_modal_invoice_id').html(json.invoice_id);
                 modal.find('#_modal_job_no').html(json.job_id);
                 modal.find('#_modal_job_name').html(json.job_name);
                 modal.find('#_modal_paid_by').html(json.paid_status);
                 modal.find('#_modal_paid_date').html(json.paid_date);
                 modal.find('#_modal_total_price').html(json.total_price);
                 modal.find('#_modal_signature').html($("<img>" , {src : json.signature , class : "thumbnail img-responsive"}));

                 var img_container = modal.find('.custom-thumbnails');
                 img_container.html(" ");

                 var div = $("<div>" , {class : "row"});

                 $.each(json.images , function(k , v){
                    
                    if(!(k % 2)){
                        div = $("<div>" , {class : "row"});
                    }
                    var a = $("<a>" , { href : v.image , "data-sub-html" : json.job_name , class : "col-xs-12 col-lg-6 c-thumbnails"});
                    var img = $("<img>" , { src : v.image_thumb , class : "img-responsive thumbnail  lazy "} );
                    
                    a.append(img);
                    div.append(a);    
                    img_container.prepend(div);
                });

                if($(".custom-thumbnails").data("lightGallery")){
                    $(".custom-thumbnails").data("lightGallery").destroy(true);
                }
                    
                $('.custom-thumbnails').lightGallery({
                    thumbnail: true,
                    selector: 'a.c-thumbnails'
                }); 

                
            }
        });
    });


    $(document).on('click' , '#modal_confirmed_btn' , function(){
        var id = $(this).data("id");
        var url = "<?php echo site_url("app/customer/confirm_invoices"); ?>";

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
                        data : {invoice_id : id},
                        method : "POST" ,
                        success : function(response){
    
                            var json = jQuery.parseJSON(response);

                            var tr = $('#_tr_'+id);
                            var txt = tr.find('.td_status').html();

                            txt = txt.replace("UNCONFIRMED" , "CONFIRMED");
           
                            tr.find('.td_status').html(txt);
                            tr.find('.td_confirmed').html(json.confirmed_by);

                            $('#invoice_modal').modal("hide");

                            swal("Updated!", "", "success");
                        }
                    });
                } else {

                    swal("Cancelled", "safe :)", "error");

                }

        });

    
    });

    $(document).on('click' , '.view_invoice' , function(){
        var id = $(this).data("id");
        var url = $(this).data("href");

        $.ajax({
            url : url ,
            data : {id : id},
            type : "POST" ,
            success : function(response){
                 var modal = $('#invoice_modal').modal("show");
                 var json = jQuery.parseJSON(response);

                 modal.find('#modal_confirmed_btn').hide();

                 modal.find('#modal_confirmed_btn').data('id' , json.invoice_id);
                 modal.find('#_modal_invoice_id').html(json.invoice_id);
                 modal.find('#_modal_job_no').html(json.job_id);
                 modal.find('#_modal_job_name').html(json.job_name);
                 modal.find('#_modal_paid_by').html(json.paid_status);
                 modal.find('#_modal_paid_date').html(json.paid_date);
                

                 if(json.with_outsource){
                    modal.find('#_modal_signature').prev().hide();
                    modal.find('#_modal_signature').hide();
                    modal.find('#_modal_total_price').html("Price : "+parseFloat(json.price).toFixed(2)+"<br>"+"Outsource Price : "+parseFloat(json.outsource_price).toFixed(2));
                 }else{
                    modal.find('#_modal_total_price').html(json.total_price);
                    modal.find('#_modal_signature').prev().show();
                    modal.find('#_modal_signature').show().html($("<img>" , {src : json.signature , class : "thumbnail img-responsive"}));
                 }
                 

                 var img_container = modal.find('.custom-thumbnails');
                 img_container.html(" ");

                 var div = $("<div>" , {class : "row"});

                 $.each(json.images , function(k , v){
                    
                    if(k % 2){

                    }else{
                        div = $("<div>" , {class : "row"});
                    }
                    var a = $("<a>" , { href : v.image , "data-sub-html" : json.job_name , class : "col-xs-12 col-lg-6 c-thumbnails"});
                    var img = $("<img>" , { src : v.image_thumb , class : "img-responsive thumbnail  lazy "} );
                    
                    a.append(img);
                    div.append(a);    
                    img_container.prepend(div);
                });

                if($(".custom-thumbnails").data("lightGallery")){
                    $(".custom-thumbnails").data("lightGallery").destroy(true);
                }
                    
                $('.custom-thumbnails').lightGallery({
                    thumbnail: true,
                    selector: 'a.c-thumbnails'
                }); 

                
            }
        });
    });

    $(document).on('click' , '.view_invoice_history' , function(){
        var id = $(this).data("id");
        var url = $(this).data("href");

        $.ajax({
            url : url ,
            data : {id : id },
            method : "POST",
            success : function(response){
                var modal = $('#invoice_history_modal').modal("show");
                modal.find(".modal-body").html(response);

                $('[data-toggle="popover"]').popover(); 
            }
        });
    });
    


    $(document).on('click' , '.generate_invoice' , function(){
        var href = $(this).data("href");
        var $me = $(this);

        $.ajax({
            url : href , 
            method : "GET" ,
            success : function(response){
                
                //window.open(response , '_blank');
                swal("Generated!", "", "success");
                setTimeout(function(){
                    window.location.reload(true);
                },1500);
                //$me.closest("ul").find(".view_invoice_pdf").data("pdf" , response).trigger("click");
            }
        });
        
    });

    $(document).on('click' , '.outsource-btn' , function(){
        var data = $(this).data("value");
        $('#_outsource').val(data);
        var form = $('#_fis');
        form.find('#_sfis').trigger('click');
    });
</script>

<style>
.pdfobject-container { height: 500px;}
.pdfobject { border: 1px solid #666; }
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
                Customer
            </li>
            <li class="active">
                Invoices
            </li>
        </ol>
    </h2>
</div>  

<div class="row collapse" id="collapseExample2">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <p class="help-block"><?php echo @$boxResult['first_box']['date']; ?></p>
        <div class="info-box-3 bg-brown">
            <div class="icon">
                
            </div>
            <div class="content">
                <div class="text">SALES</div>
                <div class="number"><?php echo @$boxResult["first_box"]["total"]; ?></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <p class="help-block"><?php echo @$boxResult['second_box']['date']; ?></p>
        <div class="info-box-3 bg-grey">
            <div class="icon">
                
            </div>
            <div class="content">
                <div class="text">SALES</div>
                <div class="number"><?php echo @$boxResult["second_box"]["total"]; ?></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <p class="help-block"><?php echo @$boxResult['third_box']['date']; ?></p>
        <div class="info-box-3 bg-green">
            <div class="icon">
                
            </div>
            <div class="content">
            <div class="text">SALES</div>
                <div class="number"><?php echo @$boxResult["third_box"]["total"]; ?></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <p class="help-block"><?php echo @$boxResult['fourth_box']['date']; ?></p>
        <div class="info-box-3 bg-blue-grey">
            <div class="icon">
                
            </div>
            <div class="content">
            <div class="text">SALES</div>
                <div class="number"><?php echo @$boxResult["fourth_box"]["total"]; ?></div>
            </div>
        </div>
    </div>
</div>
<div class="block-header">
    <table class="table table-bordered">
        <tr>
            <td>
                <h2 style="font-size: 40px;">GBP <span id="invoice_price">0</span> <small style="font-size: 20px;"><span><span id="invoice_selected">0</span> of <?php echo count($result)?> invoices selected</span></small></h2>
            </td>
        </tr>
    </table>
</div>
<div class="block-header">
    <h2>
        <a role="button" class="btn btn-success" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="material-icons" style="position: relative;font-size: 16.5px;">search</i> Search</a>
        <div class="btn-group">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="material-icons" style="position: relative;font-size: 16px;">check_box</i> Batch Action <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="javascript:void(0);" class="batch_pay_invoice" data-href="<?php echo site_url("app/customer/pay_invoices/"); ?>">Pay Invoice</a></li>
                <li><a href="javascript:void(0);" class="batch_download_invoice" data-href="<?php echo site_url("app/customer/download_invoices/"); ?>">Download Invoice</a></li>
            </ul>
        </div>
        <a role="button" class="btn btn-default" target="_blank" href="<?php echo site_url("app/customer/invoices/").$request_parameters; ?>"><i class="material-icons" style="position: relative;font-size: 16.5px;">import_export</i> Export Excel</a>
        <a role="button" class="btn btn-default"  href="<?php echo site_url("app/customer/add_invoice"); ?>"><i class="material-icons" style="position: relative;font-size: 16.5px;">add_circle_outline</i> Add Invoice</a>
        <a role="button" class="btn btn-default batch_merge_invoice" data-loading-text="Loading..." href="javascript:void(0);" data-href="<?php echo site_url("app/customer/merge_invoice/"); ?>"><i class="material-icons" style="position: relative;font-size: 16.5px;">add_circle_outline</i> Merge Invoice</a>
        <a role="button" class="btn btn-success" data-toggle="collapse" href="#collapseExample2" aria-expanded="false" aria-controls="collapseExample2"> Show Sales</a>
    </h2>
</div>

<div style="margin-bottom: 20px;">
    <div class="collapse" id="collapseExample">
        <?php $this->load->view('page/customer/invoice/search') ?>
    </div>
</div>
<div class="alert bg-light-green">
  <strong>Green</strong> rows indicates that the invoice was merged.
</div>

<!-- Basic Examples -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <div class="row">
                    <div class="col-lg-6 col-xs-12">
                        <h2>
                            INVOICE LIST
                        </h2>
                       
                    </div>
                    <div class="col-lg-6 col-xs-12 text-right">
                        <a href="javascript:void(0);" class="btn btn-primary outsource-btn" data-value="yes">Outsource</a>
                        <a href="javascript:void(0);" class="btn btn-primary outsource-btn" data-value="no">Not Outsource</a>
                        <a href="javascript:void(0);" class="btn btn-primary outsource-btn" data-value="all">All</a>
                    </div>
                </div>
            </div>
            <div class="body table-responsive" style="overflow: auto;max-height: 750px;">
                <table class="table table-bordered table-striped table-hover dt">
                    <thead>
                        <tr>
                            <th> <input id="acceptTerms-asdvv" type="checkbox" class="tr_invoice_all">
                                    <label for="acceptTerms-asdvv"></label>  </th>
                            <th>System ID</th>
                            <th>Invoice #</th>
                            <th>Job No.</th>
                            <th>Purchase Order number</th>
                            <th>Company Name</th>
                            <th>Job Name</th>
                            <th>Paid Status</th>
                            <th>Delivery Date</th>
                            <th>Invoice Date</th>
                            <th>Paid Date</th>
                            <th>Demurrage</th>
                            <th>Job Cost</th>
                            <th>VAT</th>
                            <th>Gross</th>
                            <th>Confirmed</th>
                            <th>Notes</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>System ID</th>
                            <th>Invoice #</th>
                            <th>Job No.</th>
                            <th>Purchase Order number</th>
                            <th>Company Name</th>
                            <th>Job Name</th>
                            <th>Paid Status</th>
                            <th>Delivery Date</th>
                            <th>Invoice Date</th>
                            <th>Paid Date</th>
                            <th>Demurrage</th>
                            <th>Job Cost</th>
                            <th>VAT</th>
                            <th>Gross</th>
                            <th>Confirmed</th>
                            <th>Notes</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach($result as $key => $row) : ?>
                            <tr id="_tr_<?php echo $row->invoice_id; ?>" class="<?php echo ($row->merge_id > 0)? 'bg-light-green' : ''; ?>">
                                <td>
                                    
                                    <?php if($row->merge_id == 0) : ?>
                                    <input id="acceptTerms-asd<?php echo $key; ?>"  value="<?php echo $row->invoice_id; ?>" data-price="<?php echo $row->total_price_raw; ?>" data-op="<?php echo $row->total_price_raw; ?>" type="checkbox" class="tr_invoice_id <?php echo ($row->merge_id == 0 && $row->merge == 'Y')? 'is_parent':'';?>  <?php echo ($row->to_outsource == 'YES') ? 'outsourced' : ''; ?>">
                                    <label for="acceptTerms-asd<?php echo $key; ?>"></label> 
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $row->invoice_id; ?>
                                    <?php if($row->merge_id > 0) : ?>
                                    <span class="label bg-blue">MERGED AT <?php echo $row->merge_id; ?></span>
                                    <?php endif; ?>                                        
                                </td>
                                <td><?php echo $row->invoice_number; ?></td>
                                <td><?php echo $row->jn; ?></td>
                                <td><?php echo $row->jpo_number; ?></td>
                                <td>
                                    <span data-trigger="hover" data-container="body" data-toggle="popover" data-placement="top" title="Company Information" data-html="true" data-content="<?php echo $this->load->view("page/customer/invoice/job_information" , (array)$row , true); ?>">
                                        <?php echo $row->company_name; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo site_url("app/customer/jobs/?transaction_number=$row->job_id&status=all&submit=submit"); ?>" target="_blank" data-trigger="hover" data-container="body" data-toggle="popover" data-placement="top" title="Job #" data-html="true" data-content="<?php echo $row->job_id; ?>"><?php echo $row->job_name; ?></a>
                                </td>

                                <td>
                                    <span class="td_status"><?php echo $row->paid_status; ?></span>
                                    <?php if($row->merge == "Y") : ?>
                                        <span class="label bg-blue" data-trigger="hover" data-container="body" data-toggle="popover" data-placement="top" title="Invoices" data-html="true" data-content="<?php echo $row->merge_list; ?>">
                                        Parent Invoice ( <?php echo $row->merge_count;?> )
                                        </span>
                                        
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $row->delivery_time; ?></td>
                                <td><?php echo $row->invoice_date; ?></td>
                                <td><?php echo $row->paid_date; ?></td>
                                <td><?php echo $row->demurrage; ?></td>
                                <td><?php echo $row->price; ?></td>
                                <td><?php echo $row->vat; ?></td>
                                <td>
                                    <?php if($this->session->userdata("account_type") != "OUTSOURCE"): ?>
                                        <?php if($row->to_outsource == "YES") : ?>
                                            <small><strong>BW Sale: </strong><?php echo $row->bw_sale; ?></small>
                                            <br>
                                            <span class="label bg-blue" data-trigger="hover" data-toggle="popover" data-placement="top"  data-html="true" title="Outsource Name" data-content="<?php echo $row->outsource_company_name; ?>">OUTSOURCE</span>
                                            <small class="help-block">Total Price: <?php echo $row->total_price; ?></small>
                                        <?php else: ?>
                                            <?php echo $row->total_price; ?>
                                        <?php endif; ?>    
                                    <?php else: ?>
                                        <?php echo $row->total_price; ?>
                                        <span class="label bg-blue">OUTSOURCE</span>
                                    <?php endif; ?>
                                </td>
                                <td class="td_confirmed">
                                    <span class="label bg-blue-grey" data-trigger="hover" data-container="body" data-toggle="popover" data-placement="top" title="Confirmed Date" data-html="true" data-content="<?php echo $row->confirmed_date; ?>"><?php echo $row->confirmed_by; ?></span>
                                </td>
                                <td>
                                    <?php if($row->with_charge) : ?>
                                        <?php if($row->cancel_notes) : ?>
                                            <span class="label bg-blue" data-trigger="hover" data-container="body" data-toggle="popover" data-placement="top" title="Notes" data-html="true" data-content="<?php echo $row->cancel_notes; ?>">CANCEL NOTES</span>
                                        <?php endif; ?>
                                    <?php endif; ?>    
                                    <?php if($row->invoice_notes) : ?>
                                        <span class="label bg-blue" data-trigger="hover" data-container="body" data-toggle="popover" data-placement="top" title="Notes" data-html="true" data-content="<?php echo $row->invoice_notes; ?>">INVOICE NOTES</span>
                                    <?php else : ?>
                                         <span class="label bg-red">NO</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="material-icons" style="font-size: 15px;">touch_app</i>
                                            Action <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu pull-right">
                                            <?php if($row->status_raw == "INCOMPLETE" && $row->merge_id == 0) : ?>
                                                <li><a href="<?php echo site_url("app/customer/pay_invoices/?id[]=".$row->invoice_id); ?>">Pay Invoice</a></li>
                                            <?php endif; ?>
                                            <li><a href="javascript:void(0);" data-pdf="<?php echo $row->pdf; ?>" class="view_invoice_pdf <?php echo (($row->merge_id == 0 && $row->generated_pdf == '') || ($row->merge == 'N' && $row->generated_pdf == ''))? 'hidden': '';?>" data-id="<?php echo $row->invoice_id; ?>">Invoice Preview</a></li>
                                            <!-- <li><a href="javascript:void(0);" data-href="<?php //echo site_url("app/customer/confirm_invoices"); ?>" data-id="<?php //echo $row->invoice_id; ?>" class="view_invoice">Invoice Notes</a></li> -->
                                            <li><a href="javascript:void(0);" data-href="<?php echo site_url("app/customer/getInvoiceHistory"); ?>" data-id="<?php echo $row->invoice_id; ?>" class="view_invoice_history">Invoice History</a></li>
                                            <li><a href="javascript:void(0);" data-href="<?php echo site_url("app/customer/download_invoices/?id[]=".$row->invoice_id); ?>" class="single_download_invoice">Download Invoice</a></li>
                                            <?php if($row->status_raw == "NEED CONFIRMATION" ) : ?>
                                                <li><a href="javascript:void(0);" data-href="<?php echo site_url("app/customer/confirm_invoices"); ?>" data-id="<?php echo $row->invoice_id; ?>" class="confirm_invoice">Confirm Invoice</a></li>
                                            <?php endif; ?>
                                            <?php if($row->merge_id == 0) : ?>
                                            <li><a href="javascript:void(0);" data-href="<?php echo site_url("app/customer/generate_invoices/?id=".$row->invoice_id); ?>" class="generate_invoice">Generate Invoice</a></li>
                                            <li><a href="<?php echo site_url("app/customer/update_invoices/?id=".$row->invoice_id); ?>">Edit Invoice</a></li>
                                            <?php endif; ?>
                                            <?php if($row->merge == "Y") : ?>
                                                <li><a href="<?php echo site_url("app/customer/remove_merge/?id=$row->invoice_id"); ?>" >Edit Merge</a></li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
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
