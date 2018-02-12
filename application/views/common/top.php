<script type="text/javascript">
    $(document).on('click' , '.click-notification' , function(){
        var id = $(this).data("id");
        var redirect_link = $(this).data("link");
        var read = $(this).data("read");
        var url = "<?php echo site_url("app/dashboard/readNotification"); ?>";

        if(read == "1"){
            window.location = redirect_link;
        }else{
            $.ajax({
                url : url ,
                data : {id : id},
                method : "POST" ,
                success : function(response){
                    window.location = redirect_link;
                }
            });
        }
    });
    $(document).on('click' , '.removeNotifCount' , function(){
        $(this).find('.label-count').html(0);
        var c = "<?php echo $notification['unread']; ?>";

        if(c != "0"){
            $.ajax({
                url : "<?php echo site_url("app/dashboard/readNotificationAll"); ?>" ,
                method : "POST" ,
                success : function(response){
                    
                }
            });
        }

    });
</script>
<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
            <a href="javascript:void(0);" class="bars"></a>
            <a class="navbar-brand text-uppercase" href="<?php echo site_url('app/dashboard'); ?>">Transport App</a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <!-- Call Search -->
                <li><a href="http://trackerteer.com/" target="_blank"> <img class="hidden-xs" src="<?php echo base_url('/public/images/logo3.png'); ?>" style="margin-top: -14px; image-rendering: -o-crisp-edges; image-rendering: -webkit-optimize-contrast;image-rendering: crisp-edges;
                -ms-interpolation-mode: nearest-neighbor; " width="700" ></a></li>
                <!-- #END# Call Search -->
                <!-- Notifications -->
                <li class="dropdown" >
                    <a href="javascript:void(0);" class="dropdown-toggle removeNotifCount" data-toggle="dropdown" role="button">
                        <i class="material-icons">notifications</i>
                        <span class="label-count"><?php echo $notification['unread']; ?></span>
                    </a>
                    <ul class="dropdown-menu" style="margin-left: auto;">
                        <li class="header">NOTIFICATIONS</li>
                        <li class="body">
                            <ul class="menu" style="overflow: auto; width:300px; height: 254px;">
                                <?php $this->load->view("common/notification"); ?>
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="<?php echo site_url("app/notify"); ?>">View All Notifications</a>
                        </li>
                    </ul>
                </li>

                <?php if($this->session->userdata("account_type") == SUPER_ADMIN || $this->session->userdata("account_type") == ADMIN) : ?>
                     <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons">rss_feed</i>
                            <span class="label-count"><?php echo count($show_online); ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">ONLINE</li>
                            <li class="body">
                                <ul class="menu tasks" style="overflow-y: auto;width: auto;height: 254px; line-height: 0px;">
                                    <?php foreach($show_online as $row) : ?>
                                        <li style="line-height: 0;padding: 7px;border-bottom: 1px solid #eee;">
                                            <a href="javascript:void(0);">
                                                <h4 style="padding-top:5px;">
                                                    <?php echo $row->name; ?>
                                                    <small><?php echo $row->still_active; ?></small>
                                                </h4>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                            
                        </ul>
                    </li>
                <?php endif; ?>
                <!-- #END# Notifications -->
            </ul>
        </div>
    </div>
</nav>