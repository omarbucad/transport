<script type="text/javascript">
    $(function () {
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
                            var json = jQuery.parseJSON(response);

                            swal("Deleted!", name+" has been deleted.", "success");
                            $me.closest('tr').remove();
                            
                        }

                    });

                } else {
                    swal("Cancelled", name+" is safe :)", "error");
                }
            });

        });

        $(document).on('click' , '.btn-update' , function(){
            window.location = $(this).data('href');
        });
    });

    $(document).on('click' , '.change-password' , function(){
        var id = $(this).data("id");
        var modal = $('#change_password_modal').modal("show");

        modal.find('#_id_account').val(id);


    });

    $(document).on('click' , '#changepassword_modal_btn' , function(){
        var form = $(this).closest('.modal').find("form");
        var url = form.attr('action');
        var a = form.find('#modal_password').val();
        
        if(a.length > 5){
            $.ajax({
                url : url ,
                data : form.serialize() ,
                method : "POST" , 
                success : function(response){

                    swal("Change Password", "", "success");
                    $('#change_password_modal').modal("hide");
                }
            });
        }else{
           swal("Password length More than 5 Letters ", "", "error"); 
        }
    });
</script>

<div class="block-header">
    <h2>
        <ol class="breadcrumb" style="padding: 0px;">
            <li>
                <a href="<?php echo site_url('app/dashboard/index/4'); ?>">
                 Dashboard
                </a>
            </li>
            <li class="active">
                Accounts
            </li>
        </ol>
    </h2>
</div>  

<div class="block-header">
    <h2>
        <a role="button" class="btn btn-success" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="material-icons" style="position: relative;font-size: 16.5px;">search</i> Search</a>
        <a href="<?php echo site_url("app/accounts/add"); ?>" class="btn btn-primary"><i class="material-icons" style="position: relative;font-size: 16.5px;">add_circle_outline</i> Add Account</a>
    </h2>
</div>

<div style="margin-bottom: 20px;">
    <div class="collapse" id="collapseExample">
        <?php $this->load->view('page/accounts/search') ?>
    </div>
</div>

<!-- Basic Examples -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    USER ACCOUNTS LIST
                </h2>
            </div>
            <div class="body table-responsive">
                <div id="aniimated-thumbnials" class="list-unstyled clearfix">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Type</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Type</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php foreach ($result as $key => $row): ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo $row->image; ?>" class="thumbnail" data-sub-html="<?php echo $row->name.' '.$row->surname; ?>">
                                            <img src="<?php echo $row->image_thumb; ?>" class="img-responsive" width="75px" alt="<?php echo $row->name.' '.$row->surname; ?>">
                                        </a>
                                    </td>
                                    <td><?php echo $row->name.' '.$row->surname; ?></td>
                                    <td><?php echo $row->username; ?></td>
                                    <td><?php echo $row->account_type; ?></td>
                                    <td><?php echo $row->email; ?></td>
                                    <td><?php echo $row->status; ?></td>
                                    <td>
                                        <nobr>
                                            <button data-href="<?php echo site_url("app/accounts/updateAccount/".$row->id) ?>" class="btn btn-xs btn-info waves-effect btn-update"><i class="material-icons" style="font-size: 16px;">update</i> Update</button>
                                            <button class="btn btn-xs btn-success waves-effect change-password" data-id="<?php echo $row->id; ?>"><i class="material-icons" style="font-size: 16px;">lock_outline</i> Change Password</button>
                                            <button href="Javascript:void(0);" class="btn btn-xs btn-danger waves-effect remove-data" data-id="<?php echo $row->id; ?>" data-href="<?php echo site_url("app/accounts/removeAccount"); ?>" data-name="<?php echo $row->name.' '.$row->surname; ?>"><i class="material-icons" style="font-size: 16px;">cancel</i> Remove</button>
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
</div>
<!-- #END# Basic Examples -->
