<form action="<?php echo site_url('app/customer/createJob') ?>" method="POST" id="_MODAL_CREATEJOB" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-6 col-xs-12">
            <fieldset>
                 <?php if($this->session->userdata("account_type") == SUPER_ADMIN) : ?>
                    <div class="form-group form-float">
                        <div class="form-line">
                            <select class="form-control" name="customer_id">
                                <?php foreach($customerList as $row) : ?>
                                    <option value="<?php echo $row->customer_id; ?>"  ><?php echo $row->company_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="help-info">Customer</div>
                    </div>
                <?php else : ?>
                    <input type="hidden" name="customer_id" value="<?php echo $this->session->userdata("customer_id"); ?>">
                <?php endif; ?>

                <?php if($this->session->userdata("special_account_type")) : ?>
                    <div class="form-group form-float">
                        <div class="form-line">
                            <select class="form-control" name="division">
                                <option value="">- Select Division -</option>
                                <?php foreach(@$type_of_division as $row) : ?>
                                    <option value="<?php echo $row->code; ?>"><?php echo $row->code; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="help-info">Division</div>
                    </div>
                <?php endif; ?>

                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control autocomplete" list="_languages" name="job_name" required autofocus="">
                        <label class="form-label">Job Name</label>
                        <datalist id="_languages">
                            <!--[if IE 9]><select disabled style="display:none"><![endif]-->
                            <!--[if IE 9]></select><![endif]-->
                        </datalist>
                    </div>
                </div>
                
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="number" step="1" minlength="1" class="form-control" name="job_po_number" required>
                        <label class="form-label">Purchase Order Number</label>
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="number" step="1" min="1" class="form-control" name="number_of_truck" id="number_of_truck" value="1" required>
                        <label class="form-label">Number of Truck (Quantity)</label>
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control " name="telephone">
                        <label class="form-label">Telephone</label>
                    </div>
                </div>
                <?php if($this->session->userdata("special_account_type")) : ?>
    
                    <div class="form-group form-float">
                        <div class="form-line">
                            <select class="form-control build_dismantle_dropdown" name="build_dismantle">
                                <option value="">- Select Build / Dismantle/JTJ - </option>
                                <option value="BUILD">Build</option>
                                <option value="DISMANTLE">Dismantle</option>
                                <option value="BUILD AND BACK LOAD">Build and Back load</option>
                                <option value="DISMANTLE AND SEND EMPTY">Dismantle and send empty</option>
                                <option value="COLLECTION EMPTYS">Collection emptys</option>
                                <option value="BLANK">Blank</option>
                                <option value="SITE TO SITE">Site To Site</option>
                                <option value="OTHER">Other</option>
                            </select>
                        </div>
                        <div class="help-info">Build/ Dismantle/JTJ</div>
                    </div>
                    <div class="form-group form-float hide " id="_build_dismantle">
                        <div class="form-line focused">
                            <input type="text" class="form-control" name="build_dismantle_other" disabled="true">
                            <label class="form-label">Build/Dismantle/JTJ</label>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="form-group form-float">
                    <div class="form-line">
                        <textarea cols="30" rows="3" name="driver_notes" class="form-control no-resize _driver_notes"><?php echo set_value('notes[]'); ?></textarea>
                        <label class="form-label">Note For the Driver</label>
                    </div>
                    <div class="help-info">Optional</div>
                </div>

                <div class="row">
                    <div class="col-xs-12 job-clone">
                        <h4>Truck # <span>1</span></h4>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text"  class="form-control" name="job_number[]" required>
                                <label class="form-label">Job Number</label>
                            </div>
                        </div>                
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group form-float">
                                    <div class="form-line focused">
                                        <input type="text" name="loading[date][]"  class="dtpicker form-control _loading_date" required>
                                        <label class="form-label">Loading Date</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group form-float">
                                    <div class="form-line focused">
                                        <input type="text" class="form-control time12 _loading_time" name="loading[time][]"  placeholder="12:00 AM" required>
                                    </div>
                                    <div class="help-info">Time</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group form-float">
                                    <div class="form-line focused">
                                        <input type="text" name="delivery[date][]"  class="dtpicker form-control _delivery_date" required>
                                        <label class="form-label">Delivery Date</label>
                                    </div>
                                </div> 
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group form-float">
                                    <div class="form-line focused">
                                        <input type="text" class="form-control time12 _delivery_time" name="delivery[time][]"  placeholder="12:00 AM" required>
                                    </div>
                                    <div class="help-info">Time</div>
                                </div>
                            </div>
                        </div>   
                    </div>
                </div>
                
            </fieldset>
        </div>
        <div class="col-lg-6 col-xs-12">
            <fieldset>
                <div class="form-group form-float">
                    <div class="form-line">
                        <select class="form-control _type_of_truck" name="type_of_truck">
                            <?php foreach(@$type_of_truck_modal as $row) : ?>
                                <option value="<?php echo $row->truck_type; ?>"><?php echo $row->truck_type; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="help-info">Type of Truck</div>
                </div>
                <div class="form-group form-float" id="_arctic_type">
                    <?php if($this->session->userdata("account_type") == SUPER_ADMIN) : ?>
                        <div class="input-group input-group-lg" style="margin-bottom: 0px; !important;">
                            <div class="form-line">
                                <select class="form-control at" name="arctic_type">
                                    <?php foreach($type_of_artic as $artic) : ?>
                                        <option value="<?php echo $artic->code; ?>"><?php echo $artic->code; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <span class="input-group-addon">
                                <a href="javascript:void(0);" class="add_artic"><i class="material-icons" style="top: -3px;position: relative;">add</i></a>
                            </span>
                        </div>
                    <?php else : ?>
                        <select class="form-control" name="arctic_type">
                            <?php foreach($type_of_artic as $artic) : ?>
                                <option value="<?php echo $artic->code; ?>"><?php echo $artic->code; ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                    <div class="help-info">Artic Type </div>

                    
                    
                </div>
                

                <div class="form-group form-float">
                    <div class="form-line">
                        <textarea name="load_site" cols="30" rows="3" class="form-control no-resize" required></textarea>
                        <label class="form-label">Load Site</label>
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" name="zip_code_load_site" required>
                        <label class="form-label">Load Site Zip Code</label>
                    </div>
                </div>     
                <div class="form-group form-float">
                    <div class="form-line">
                        <textarea name="address" cols="30" rows="3" class="form-control no-resize" required></textarea>
                        <label class="form-label">Destination</label>
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" name="zip_code" required>
                        <label class="form-label">Destination Zip Code</label>
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" name="site_contact" required>
                        <label class="form-label">Site Contact</label>
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <textarea name="notes" cols="30" rows="3" class="form-control no-resize" required></textarea>
                        <label class="form-label">Special Note for the Job</label>
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="file" name="file[]" class="form-control" multiple="" accept="image/*">
                    </div>
                    <div class="help-info">Images</div>
                </div>    
            </fieldset>
        </div>
    </div>
</form>