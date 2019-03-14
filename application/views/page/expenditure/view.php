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

        $(document).on('click' , '.delete' , function(){
            var url = $(this).data("href");
            var id = $(this).data("id");

            swal({
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: false
            },function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url : url+id ,
                        method : "GET" ,
                        success : function(){
                            swal("Deleted!", "", "success");
                            setTimeout(function(){
                                window.location.reload(true);
                            },1500);
                        }
                    });
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

                    price += i;

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
                    var i = $(v).data("price");

                    price += i;

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
                Expenditures
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
                <div class="text">EXPENDITURE</div>
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
                <div class="text">EXPENDITURE</div>
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
            <div class="text">EXPENDITURE</div>
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
            <div class="text">EXPENDITURE</div>
                <div class="number"><?php echo @$boxResult["fourth_box"]["total"]; ?></div>
            </div>
        </div>
    </div>
</div>
<div class="block-header">
    <table class="table table-bordered">
        <tr>
            <td>
                <h2 style="font-size: 40px;">GBP <span id="invoice_price">0</span> <small style="font-size: 20px;"><span><span id="invoice_selected">0</span> of <?php echo count($result)?> expenditure selected</span></small></h2>
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
                <li><a href="javascript:void(0);" class="batch_pay_invoice" data-href="<?php echo site_url("app/expenditures/pay_invoices/"); ?>">Pay Expenditure</a></li>
                <li><a href="javascript:void(0);" class="batch_download_invoice" data-href="<?php echo site_url("app/expenditures/download_invoices/"); ?>">Download Expenditure</a></li>
            </ul>
        </div>
        <a role="button" class="btn btn-default" target="_blank" href="<?php echo site_url('app/expenditures/').$request_parameters; ?>"><i class="material-icons" style="position: relative;font-size: 16.5px;">import_export</i> Export Excel</a>
        <a role="button" class="btn btn-default"  href="<?php echo site_url("app/expenditures/add"); ?>"><i class="material-icons" style="position: relative;font-size: 16.5px;">add_circle_outline</i> Add Expenditure</a>
       
        <a role="button" class="btn btn-success" data-toggle="collapse" href="#collapseExample2" aria-expanded="false" aria-controls="collapseExample2">Show Summary</a>
    </h2>
</div>

<div style="margin-bottom: 20px;">
    <div class="collapse" id="collapseExample">
        <?php $this->load->view('page/expenditure/search') ?>
    </div>
</div>
<!-- <div class="alert bg-light-blue">
  <strong>Blue</strong> rows indicates it was <strong>Updated</strong>.
</div> -->

<!-- Basic Examples -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <div class="row">
                    <div class="col-lg-6 col-xs-12">
                        <h2>
                            EXPENDITURE LIST
                        </h2>
                       
                    </div>
                </div>
            </div>
            <div class="body table-responsive" style="overflow: auto;max-height: 750px;">
                <table class="table table-bordered table-striped table-hover dt">
                    <thead>
                        <tr>
                            <th> <input id="acceptTerms-asdvv" type="checkbox" class="tr_invoice_all">
                                    <label for="acceptTerms-asdvv"></label>  </th>
                            <th>Expenditure #</th>
                            <th>Category</th>
                            <th>Payment Type</th>
                            <th>Subtotal</th>
                            <th>Discount</th>
                            <th>VAT</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Action</th>                      
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>Expenditure #</th>
                            <th>Category</th>
                            <th>Payment Type</th>
                            <th>Subtotal</th>
                            <th>Discount</th>
                            <th>VAT</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Created</th>   
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php //print_r_die($result);?>
                        <?php foreach($result as $key => $row) : ?>
                            <tr id="_tr_<?php echo $row->exp_id; ?>" class="<?php //echo ($row->status == '')? 'bg-light-green' : ''; ?>">
                                <td>
                                    
                                    <input id="acceptTerms-asd<?php echo $key; ?>"  value="<?php echo $row->exp_id; ?>" data-price="<?php echo $row->total; ?>" type="checkbox" class="tr_invoice_id <?php //echo ($row->merge_id == 0 && $row->merge == 'Y')? 'is_parent':'';?>">
                                    <label for="acceptTerms-asd<?php echo $key; ?>"></label> 
                                </td>
                                <td><?php echo $row->exp_number; ?></td>
                                <td><?php echo $row->category_name; ?></td>
                                <td><?php echo $row->payment_type_name; ?></td>
                                <td><?php echo $row->subtotal; ?></td>
                                <td><?php echo $row->discount; ?></td>
                                <td><?php echo $row->vat; ?></td>
                                <td><?php echo $row->total; ?></td>
                                <td>
                                    <span data-trigger="hover" data-container="body" data-toggle="popover" data-placement="top" title="Status Information" data-html="true" data-content="<?php echo $this->load->view("page/expenditure/status_update_info" , (array)$row->statuses[0] , true); ?>">
                                        <?php echo $row->statuses[0]->status;?>
                                    </span>
                                </td>
                               
                                <td class="td_confirmed">
                                   <?php echo $row->created; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="material-icons" style="font-size: 15px;">touch_app</i>
                                            Action <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu pull-right">
                                            <!-- <?php if($row->status_raw == "INCOMPLETE" && $row->merge_id == 0) : ?>
                                                <li><a href="<?php echo site_url("app/expenditure/pay_invoices/?id[]=".$row->exp_id); ?>">Pay Invoice</a></li>
                                            <?php endif; ?> -->
                                            <li><a href="javascript:void(0);" class="view-expenditure" data-id="<?php echo $row->exp_id; ?>">Expenditure Preview</a></li>
                                            <li><a href="<?php echo site_url("app/expenditures/edit/$row->exp_id"); ?>">Edit Expenditure</a></li>
                                            <?php //endif; ?>
                                            <?php //if($row->merge == "Y") : ?>
                                                <li><a href="<?php //echo site_url("app/expenditure/remove_merge/?id=$row->exp_id"); ?>" >Update Status</a></li>
                                            <?php //endif; ?>
                                            <li><a href="javascript:void(0);" class="delete" data-id="<?php echo $row->exp_id; ?>" data-href="<?php echo site_url("app/expenditures/delete/"); ?>">Delete Expenditure</a></li>
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
