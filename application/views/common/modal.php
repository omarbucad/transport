<div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo site_url('app/reports/update') ?>" method="POST">
                    <input type="hidden" name="id" id="modal_report_id">
                    <input type="hidden" name="job_id" id="modal_job_id">
                    <fieldset class="modal_defect_container">
                        <nav>
                            <ol>
                                
                            </ol>
                        </nav>
                    </fieldset>
                    <hr>
                    <fieldset>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <textarea name="comment" cols="30" rows="3" class="form-control no-resize" id="modal_comment"></textarea>
                                <label class="form-label">Comment</label>
                            </div>
                        </div>
                        <div class="form-group form-float">
                            <label class="form-label">Report Status</label>
                            <div class="form-line">
                                <select class="form-control show-tick" name="status" id="modal_status" required>
                                    <option value="" >-- Please select --</option>
                                    <option value="1" >Open</option>
                                    <option value="2" >Under Maintenance</option>
                                    <option value="3">Fixed</option>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect pull-left" data-dismiss="modal"><i class="material-icons" style="font-size: 16.5px;">block</i> CLOSE</button>
                <button type="button" class="btn btn-primary waves-effect statusUpdateBtn"><i class="material-icons" style="font-size: 16.5px;">save</i> SAVE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="jobsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Create a Job</h4>
            </div>
            <div class="modal-body">
                <div class="_error_here"></div>
                <?php $this->load->view("page/customer/ajax/add_job_form"); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect pull-left" data-dismiss="modal"><i class="material-icons" style="font-size: 16.5px;">block</i> CLOSE</button>
                <button type="button" class="btn btn-primary" id="saveJobModal"><i class="material-icons" style="font-size: 16.5px;">save</i> SAVE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ViewReport" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Report Information

                 <span class="pull-right">
                    <button type="button" class="btn btn-primary waves-effect _prev" data-id="">PREV</button>
                    <button type="button" class="btn btn-info waves-effect _next" data-id="">NEXT</button>
                    </span>   

                </h4>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="customerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel"></h4>
            </div>
            <div class="modal-body">
                <dl>
                    <dt>Email</dt>
                    <dd id="_email"></dd>
                    <dt>Registration Number</dt>
                    <dd id="_registration_number"></dd>
                    <dt>Vat Number</dt>
                    <dd id="_vat_number"></dd>
                    <dt>Address</dt>
                    <dd id="_address"></dd>
                    <dt>Billing Address</dt>
                    <dd id="_billing_address"></dd>
                    <dt>Created Jobs</dt>
                    <dd id="_created_jobs"></dd>
                    <dt>Created</dt>
                    <dd id="_created"></dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="driverModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Update Driver</h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo site_url('app/vehicles/setDefaultTruck') ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="_id_account">
                    <fieldset>
                        <div class="form-group form-float">
                            <label class="form-label">Vehicle Number</label>
                            <div class="form-line">
                                <select class="form-control show-tick" name="vehicle_id" id="modal_vehicle_id" required>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="form-group form-float">
                            <label class="form-label">Trailer Number</label>
                            <div class="form-line">
                                <select class="form-control show-tick" name="trailer_id" id="modal_trailer_id" required>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="form-group form-float">
                            <label class="form-label">License Number</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="license_number" id="modal_license_number" value="<?php echo set_value('license_number'); ?>" >
                            </div>
                        </div>
                        <div class="form-group form-float">
                            <label class="form-label">License Expiry Date</label>
                            <div class="form-line">
                                <input type="text" class="form-control datepicker" name="license_expiry_date" id="modal_license_expiry_date" value="<?php echo set_value('license_expiry_date'); ?>" >
                            </div>
                        </div>
                        <div class="form-group form-float">
                            <label class="form-label">Other Documents</label>
                            <div class="form-line">
                                <input type="file" name="file[]" class="form-control" multiple="">
                            </div>
                        </div>
                        <div class="_div_file"></div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect pull-left" data-dismiss="modal"><i class="material-icons" style="font-size: 16.5px;">block</i> CLOSE</button>
                <button type="button" class="btn btn-primary waves-effect" id="okayUpdateDriver"><i class="material-icons" style="font-size: 16.5px;">save</i> SAVE</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="job_cancel_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Job Cancel</h4>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden"  id="modal_job_id">
                    
                    <?php if($this->session->userdata("account_type") == SUPER_ADMIN) : ?>
                    <fieldset id="modal_charge_div" class="hide">
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="number" step="1" minlength="1" class="form-control" id="modal_with_charge" name="with_charge" value="0">
                                <label class="form-label">Charge</label>
                            </div>
                        </div>    
                    </fieldset>
                    <?php endif; ?>
                    <fieldset>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <textarea name="comment" cols="30" rows="3" class="form-control no-resize" id="cancel_notes"></textarea>
                                <label class="form-label">Notes</label>
                            </div>
                        </div>
                    </fieldset>
                    <?php if($this->session->userdata("account_type") == SUPER_ADMIN) : ?>
                        <input id="modal_charge_checkbox" name="charge_checkbox" type="checkbox" value="1" >
                        <label for="modal_charge_checkbox">With Charge?</label>  
                    <?php endif; ?>  
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal"><i class="material-icons" style="font-size: 16.5px;">block</i> CLOSE</button>
                <button type="button" class="btn btn-primary waves-effect jobCancelBtn"><i class="material-icons" style="font-size: 16.5px;">save</i> SAVE</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="invoice_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Invoice Information

                 <span class="pull-right">
                    <button type="button" class="btn btn-primary waves-effect _prev" data-id="">PREV</button>
                    <button type="button" class="btn btn-info waves-effect _next" data-id="">NEXT</button>
                </span>
   

                </h4>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-lg-6 col-xs-12 table-responsive">
                            <table width="100%" class="">
                                <tbody>
                                    <tr>
                                        <th>Invoice ID</th>
                                        <td id="_modal_invoice_id"></td>
                                    </tr>
                                    <tr>
                                        <th>Job No</th>
                                        <td id="_modal_job_no"></td>
                                    </tr>
                                    <tr>
                                        <th>Job Name</th>
                                        <td id="_modal_job_name"></td>
                                    </tr>
                                    <tr>
                                        <th>Paid By</th>
                                        <td id="_modal_paid_by"></td>
                                    </tr>
                                    <tr>
                                        <th>Paid Date</th>
                                        <td id="_modal_paid_date"></td>
                                    </tr>
                                    <tr>
                                        <th>Total Price</th>
                                        <td id="_modal_total_price"></td>
                                    </tr>

                            </table>                            
                        </div>

                        <div class="col-lg-6 col-xs-12">
                            <div class="row">
                                <dl>
                                    <dt>Signature</dt>
                                    <dd id="_modal_signature"></dd>
                                </dl>
                                <div class="custom-thumbnails"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
                <button type="button" class="btn btn-primary waves-effect" id="modal_confirmed_btn" data-id="">CONFIRM</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="invoice_history_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Edit History</h4>
            </div>
            <div class="modal-body">
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="job_update_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Job # <span class="_modal_job_id"></span> Information</h4>
            </div>
            <div class="modal-body">
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
                <button type="button" class="btn btn-primary waves-effect" id="job_modal_update_btn" data-id="">UPDATE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="view_map_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Map Information</h4>
            </div>
            <div class="modal-body" id="_map" style="width: 100%; height: 400px;">
 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="invoice_pdf_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Invoice Information</h4>
                <span class="pull-right">
                    <button type="button" class="btn btn-primary waves-effect _prev" data-id="">PREV</button>
                    <button type="button" class="btn btn-info waves-effect _next" data-id="">NEXT</button>
                </span>   

            </div>
            <div class="modal-body" id="_pdfViewer">
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="change_password_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Change Password</h4>
            </div>
            <div class="modal-body">
               <form action="<?php echo site_url('app/accounts/change_password') ?>" method="POST">
                    <input type="hidden" name="id" id="_id_account">
                    <fieldset>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text" name="password" id="modal_password" class="form-control">
                                <label class="form-label">New Password</label>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect pull-left" data-dismiss="modal"><i class="material-icons" style="font-size: 16.5px;">block</i> CLOSE</button>
                <button type="button" class="btn btn-primary waves-effect" id="changepassword_modal_btn"><i class="material-icons" style="font-size: 16.5px;">save</i> UPDATE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="driver_instruction_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Driver's Instructions</h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo site_url('app/customer/set_instructions') ?>" method="POST">
                    <input type="hidden" name="driver_id" id="modal_driver_id">
                    <fieldset>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <textarea name="comment" cols="30" rows="3" class="form-control no-resize" id="modal_comment"></textarea>
                                <label class="form-label">Comment</label>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
                <button type="button" class="btn btn-primary waves-effect modal_save_driver_instructions">SAVE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="vehicle_type_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Add Artic Type</h4>
            </div>
            <div class="modal-body">
               <form action="<?php echo site_url('app/vehicles/add_artic_type') ?>" method="POST">
                    <fieldset>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text" name="code" class="form-control">
                                <label class="form-label">Artic Type</label>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect pull-left" data-dismiss="modal"><i class="material-icons" style="font-size: 16.5px;">block</i> CLOSE</button>
                <button type="button" class="btn btn-primary waves-effect" id="artictype_modal_btn"><i class="material-icons" style="font-size: 16.5px;">save</i> SAVE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="update_vehicle_type_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Update Artic Type</h4>
            </div>
            <div class="modal-body">
               <form action="<?php echo site_url('app/vehicles/updateArticType') ?>" method="POST">
                    <input type="hidden" name="id" id="_id">
                    <fieldset>
                        <div class="form-group form-float">
                            <div class="form-line focused">
                                <input type="text" name="code" id="code" class="form-control">
                                <label class="form-label">Artic Type</label>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect pull-left" data-dismiss="modal"><i class="material-icons" style="font-size: 16.5px;">block</i> CLOSE</button>
                <button type="button" class="btn btn-primary waves-effect" id="artictype_modal_btn_update"><i class="material-icons" style="font-size: 16.5px;">save</i> UPDATE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="vehicle_type_truck_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Add Type of Truck</h4>
            </div>
            <div class="modal-body">
               <form action="<?php echo site_url('app/vehicles/add_vehicle_type') ?>" method="POST">
                    <fieldset>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text" name="code" class="form-control">
                                <label class="form-label">Type of Truck</label>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect pull-left" data-dismiss="modal"><i class="material-icons" style="font-size: 16.5px;">block</i> CLOSE</button>
                <button type="button" class="btn btn-primary waves-effect" id="vehicletype_modal_btn"><i class="material-icons" style="font-size: 16.5px;">save</i> SAVE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="update_vehicle_type_truck_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Update Type of Truck</h4>
            </div>
            <div class="modal-body">
               <form action="<?php echo site_url('app/vehicles/updateVehicleType') ?>" method="POST">
                    <input type="hidden" name="id" id="_id">
                    <fieldset>
                        <div class="form-group form-float">
                            <div class="form-line focused">
                                <input type="text" name="code" id="code" class="form-control">
                                <label class="form-label">Type of Truck</label>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect pull-left" data-dismiss="modal"><i class="material-icons" style="font-size: 16.5px;">block</i> CLOSE</button>
                <button type="button" class="btn btn-primary waves-effect" id="truck_type_modal_btn_update"><i class="material-icons" style="font-size: 16.5px;">save</i> UPDATE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="division_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Add Division</h4>
            </div>
            <div class="modal-body">
               <form action="<?php echo site_url('app/vehicles/add_division_type') ?>" method="POST">
                    <fieldset>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text" name="code" class="form-control">
                                <label class="form-label">Division</label>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect pull-left" data-dismiss="modal"><i class="material-icons" style="font-size: 16.5px;">block</i> CLOSE</button>
                <button type="button" class="btn btn-primary waves-effect" id="division_modal_btn"><i class="material-icons" style="font-size: 16.5px;">save</i> SAVE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="update_division_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Update Division</h4>
            </div>
            <div class="modal-body">
               <form action="<?php echo site_url('app/vehicles/updateDivisionType') ?>" method="POST">
                    <input type="hidden" name="id" id="_id">
                    <fieldset>
                        <div class="form-group form-float">
                            <div class="form-line focused">
                                <input type="text" name="code" id="code" class="form-control">
                                <label class="form-label">Division</label>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect pull-left" data-dismiss="modal"><i class="material-icons" style="font-size: 16.5px;">block</i> CLOSE</button>
                <button type="button" class="btn btn-primary waves-effect" id="division_modal_btn_update"><i class="material-icons" style="font-size: 16.5px;">save</i> UPDATE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="build_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Add Build / Dismantle / JTJ</h4>
            </div>
            <div class="modal-body">
               <form action="<?php echo site_url('app/vehicles/add_build') ?>" method="POST">
                    <fieldset>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text" name="code" class="form-control">
                                <label class="form-label">Build / Dismantle / JTJ</label>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect pull-left" data-dismiss="modal"><i class="material-icons" style="font-size: 16.5px;">block</i> CLOSE</button>
                <button type="button" class="btn btn-primary waves-effect" id="build_modal_btn"><i class="material-icons" style="font-size: 16.5px;">save</i> SAVE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="build_update_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Update Build / Dismantle / JTJ</h4>
            </div>
            <div class="modal-body">
               <form action="<?php echo site_url('app/vehicles/updateBuildType') ?>" method="POST">
                    <input type="hidden" name="id" id="_id">
                    <fieldset>
                        <div class="form-group form-float">
                            <div class="form-line focused">
                                <input type="text" name="code" id="code" class="form-control">
                                <label class="form-label">Build / Dismantle / JTJ</label>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect pull-left" data-dismiss="modal"><i class="material-icons" style="font-size: 16.5px;">block</i> CLOSE</button>
                <button type="button" class="btn btn-primary waves-effect" id="build_modal_btn_update"><i class="material-icons" style="font-size: 16.5px;">save</i> UPDATE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add_artictype_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Add Artic Type</h4>
            </div>
            <div class="modal-body">
               <form action="<?php echo site_url('app/vehicles/add_artic_type') ?>" method="POST">
                    <fieldset>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text" name="code" class="form-control">
                                <label class="form-label">Artic Type</label>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect pull-left" data-dismiss="modal"><i class="material-icons" style="font-size: 16.5px;">block</i> CLOSE</button>
                <button type="button" class="btn btn-primary waves-effect" id="add_artictype_modal_btn"><i class="material-icons" style="font-size: 16.5px;">save</i> SAVE</button>
            </div>
        </div>
    </div>
</div>
<!-- MECHANIC MODALS-->
<div class="modal fade" id="ViewMechanicReport" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Report Information</h4>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mechanicUpdateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="mechanicUpdateModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo site_url('app/mechanic/updateChecklistStatus/') ?>" method="POST">
                    <input type="hidden" name="id" id="mechanic_report_id">
                    <fieldset class="mechanic_update_container">
                        <nav>
                            <ol>
                                
                            </ol>
                        </nav>
                    </fieldset>
                    <hr>
                    <fieldset>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <textarea name="comment" cols="30" rows="3" class="form-control no-resize" id="modal_comment"></textarea>
                                <label class="form-label">Comment</label>
                            </div>
                        </div>
                        <div class="form-group form-float">
                            <label class="form-label">Report Status</label>
                            <div class="form-line">
                                <select class="form-control show-tick" name="status" id="modal_status" required>
                                    <option value="" >-- Please select --</option>
                                    <option value="1" >Open</option>
                                    <option value="2" >Under Maintenance</option>
                                    <option value="3">Fixed</option>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect pull-left" data-dismiss="modal"><i class="material-icons" style="font-size: 16.5px;">block</i> CLOSE</button>
                <button type="button" class="btn btn-primary waves-effect statusMechanicUpdateBtn"><i class="material-icons" style="font-size: 16.5px;">save</i> SAVE</button>
            </div>
        </div>
    </div>
</div>

<!-- END OF MECHANIC MODAL -->