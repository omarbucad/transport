<section>
    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
        <!-- User Info -->
        <div class="user-info">
            <div class="image">
                <img src="<?php echo $this->session->userdata("image_thumb"); ?>" width="48" height="48" alt="<?php echo $this->session->userdata("name").' '.$this->session->userdata("surname"); ?>" />
            </div>
            <div class="info-container">
                <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $this->session->userdata("name").' '.$this->session->userdata("surname"); ?></div>
                <div class="email"><?php echo $this->session->userdata("email"); ?></div>
                <div class="btn-group user-helper-dropdown">
                    <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="<?php echo site_url('app/accounts/profile/'.$this->session->userdata('id')); ?>"><i class="material-icons">person</i>Profile</a></li>
                        <li><a href="<?php echo site_url('app/accounts/changepassword'); ?>"><i class="material-icons">lock</i>Change Password </a></li>
                        <li role="seperator" class="divider"></li>
                        <li><a href="<?php echo site_url('login/logout'); ?>"><i class="material-icons">input</i>Sign Out</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- #User Info -->
        <!-- Menu -->
        <div class="menu">
            <ul class="list">
                <?php if($this->session->userdata('account_type') != MECHANIC) : ?>
                <li class="header">TRANSPORT JOBS</li>
                <?php if($this->session->userdata('account_type') == SUPER_ADMIN) : ?>
                    <li class="<?php if($this->uri->segment(2) == 'dashboard' OR $this->uri->segment(2) == '') echo 'active' ?>" >
                        <a href="<?php echo site_url('app/dashboard') ?>">
                            <i class="material-icons">home</i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="<?php if($this->uri->segment(2) == 'company') echo 'active' ?>" >
                        <a href="<?php echo site_url('app/company') ?>">
                            <i class="material-icons">computer</i>
                            <span>Company</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if($this->session->userdata('account_type') != WAREHOUSE) : ?>
                <li class="<?php if($this->uri->segment(2) == 'customer' || $this->uri->segment(2) == 'outsource') echo 'active' ?>" >
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">assignment_ind</i>
                        <span>Customer</span>
                    </a>
                    <ul class="ml-menu">
                        <?php if($this->session->userdata('account_type') != CUSTOMER AND $this->session->userdata("account_type") != OUTSOURCE) : ?>
                        <li class="<?php if($this->uri->segment(3) == 'accounts' AND $this->uri->segment(2) == 'customer') echo 'active' ?>">
                            <a href="<?php echo site_url('app/customer/accounts') ?>">Users</a>
                        </li>
                        <li class="<?php if($this->uri->segment(2) == 'outsource') echo 'active' ?>">
                            <a href="<?php echo site_url('app/outsource/') ?>">Outsource</a>
                        </li>
                        <?php endif; ?>
                        <li class="<?php if(($this->uri->segment(3) == 'jobs' OR $this->uri->segment(3) == 'book' OR $this->uri->segment(3) == 'create') AND $this->uri->segment(2) == 'customer') echo 'active' ?>">
                            <a href="<?php echo site_url('app/customer/jobs') ?>">Jobs</a>
                        </li>
                        <li class="<?php if(($this->uri->segment(3) == 'invoices' OR $this->uri->segment(3) == 'pay_invoices') AND $this->uri->segment(2) == 'customer') echo 'active' ?>">
                            <a href="<?php echo site_url('app/customer/invoices') ?>">Invoices</a>
                        </li>
                        <li class="<?php if($this->uri->segment(3) == 'transaction_logs' AND $this->uri->segment(2) == 'customer') echo 'active' ?>">
                            <a href="<?php echo site_url('app/customer/transaction_logs') ?>">Transaction Logs</a>
                        </li>
                    </ul>
                </li>
                <?php if($this->session->userdata('account_type') == SUPER_ADMIN) : ?>
                <li class="<?php if($this->uri->segment(2) == 'warehouse' OR $this->uri->segment(2) == '') echo 'active' ?>" >
                    <a href="<?php echo site_url('app/warehouse') ?>">
                        <i class="material-icons">home</i>
                        <span>Warehouse</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php else:?>
                    <li class="<?php if($this->uri->segment(2) == 'warehouse' OR $this->uri->segment(2) == '') echo 'active' ?>" >
                        <a href="<?php echo site_url('app/warehouse') ?>">
                            <i class="material-icons">home</i>
                            <span>Warehouse</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php endif; ?>
                <?php if($this->session->userdata('account_type') != CUSTOMER AND $this->session->userdata("account_type") != OUTSOURCE) : ?>
                <li class="header">TRANSPORT APP</li>
                <?php if($this->session->userdata('account_type') != MECHANIC AND $this->session->userdata('account_type') != WAREHOUSE) : ?>
                <li class="<?php if($this->uri->segment(2) == 'vehicles') echo 'active' ?>" >
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">local_shipping</i>
                        <span>Vehicles</span>
                    </a>
                    <ul class="ml-menu">
                        <li class="<?php if($this->uri->segment(3) == 'gallery' AND $this->uri->segment(2) == 'vehicles') echo 'active' ?>">
                            <a href="<?php echo site_url('app/vehicles/gallery') ?>">Gallery</a>
                        </li>
                        <li class="<?php if(($this->uri->segment(3) == '' OR $this->uri->segment(3) == 'add') AND $this->uri->segment(2) == 'vehicles') echo 'active' ?>">
                            <a href="<?php echo site_url('app/vehicles') ?>">Vehicle Number</a>
                        </li>
                        <li class="<?php if($this->uri->segment(3) == 'driver' AND $this->uri->segment(2) == 'vehicles') echo 'active' ?>">
                            <a href="<?php echo site_url('app/vehicles/driver') ?>">Driver</a>
                        </li>
                        <li class="<?php if($this->uri->segment(3) == 'artic_type' AND $this->uri->segment(2) == 'vehicles') echo 'active' ?>">
                            <a href="<?php echo site_url('app/vehicles/artic_type') ?>">Artic Type</a>
                        </li>
                         <li class="<?php if($this->uri->segment(3) == 'division_type' AND $this->uri->segment(2) == 'vehicles') echo 'active' ?>">
                            <a href="<?php echo site_url('app/vehicles/division_type') ?>">Division</a>
                        </li>
                    </ul>
                </li>
                <li class="<?php if($this->uri->segment(2) == 'trailer') echo 'active' ?>" >
                    <a href="<?php echo site_url('app/trailer') ?>">
                        <i class="material-icons">text_fields</i>
                        <span>Trailer Numbers</span>
                    </a>
                </li>
                
                <?php endif; ?>
                <?php if($this->session->userdata('account_type') != WAREHOUSE) : ?>
                <li class="<?php if($this->uri->segment(2) == 'reports' OR $this->uri->segment(2) == "mechanic") echo 'active' ?>">
                    <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block">
                        <i class="material-icons">assessment</i>
                        <span>Reports</span>
                    </a>
                    <ul class="ml-menu" style="display: none;">
                        <li>
                            <a href="<?php echo site_url('app/reports/accident') ?>">
                                <span>Accident Report</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);"  class="menu-toggle waves-effect waves-block">
                                <span>Mechanic Checklist</span>
                            </a>
                            <ul class="ml-menu" style="display: none">
                                <li class="<?php if($this->uri->segment(2) == 'mechanic' && $this->uri->segment(3) == '') echo 'active' ?>">
                                    <a href="<?php echo site_url('app/mechanic/') ?>">Checklist</a>
                                </li>
                                <li class="<?php if($this->uri->segment(3) == 'needServicing') echo 'active' ?>">
                                    <a href="<?php echo site_url('app/mechanic/needServicing') ?>">Needs Servicing</a>
                                </li>
                                <li class="<?php if($this->uri->segment(3) == 'emergency_reports') echo 'active' ?>">
                                    <a href="<?php echo site_url('app/mechanic/emergency_reports') ?>">Emergency Report</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block">
                                <span>Driver</span>
                            </a>
                            <ul class="ml-menu" style="display: none;">
                                <li class="<?php if(($this->uri->segment(3) == 'defect' OR $this->uri->segment(3) == 'getReport' OR $this->uri->segment(3) == 'vehicles') AND $this->uri->segment(2) == 'reports') echo 'active' ?>">
                                    <a href="<?php echo site_url('app/reports/defect') ?>">Defect</a>
                                </li>
                                <li class="<?php if($this->uri->segment(3) == 'checklist' AND $this->uri->segment(2) == 'reports') echo 'active' ?>">
                                    <a href="<?php echo site_url('app/reports/checklist') ?>">Checklist</a>
                                </li>
                                <li class="<?php if($this->uri->segment(3) == 'daily' AND $this->uri->segment(2) == 'reports') echo 'active' ?>">
                                    <a href="<?php echo site_url('app/reports/daily') ?>">Daily</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                <?php endif; ?>
                <?php if($this->session->userdata('account_type') == SUPER_ADMIN) : ?>
                    <li class="<?php if($this->uri->segment(2) == 'sales') echo 'active' ?>" >
                        <a href="<?php echo site_url('app/sales') ?>">
                            <i class="material-icons">attach_money</i>
                            <span>Sales</span>
                        </a>
                    </li>
                    <li class="<?php if($this->uri->segment(2) == 'expenditures') echo 'active' ?>" >
                        <a href="<?php echo site_url('app/expenditures') ?>">
                            <i class="material-icons">trending_up</i>
                            <span>Expenditures</span>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="<?php if($this->uri->segment(2) == 'accounts' OR $this->uri->segment(2) == 'notify' ) echo 'active'?>" >
                    <a href="<?php echo site_url('app/accounts') ?>">
                        <i class="material-icons">account_box</i>
                        <span>Users</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- #Menu -->
        <!-- Footer -->
        <div class="legal">
            <div class="copyright">
                &copy; <?php echo date("Y"); ?> <a href="http://www.trackerteer.com/" style="color:black !important;">Powered by Trackerteer.com</a>.
            </div>
            <div class="version">
                <b>Version: </b> 1.0.0
            </div>
        </div>
        <!-- #Footer -->
    </aside>
    <!-- #END# Left Sidebar -->
</section>