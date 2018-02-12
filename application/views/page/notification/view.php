<style type="text/css">
.notification {
        list-style: none;
    }

.notification-page {
    padding-bottom: 10px;
    border-bottom: 1px solid #848484;
}
.notification.unread{
    background-color: #e9e9e9 !important;
        }
        
.nav-tabs > li {
    position: relative;
    top: 0;
    left: 0;
    bottom: 2em;
    }
</style>


<div class="container-fluid">
<h3 class="notification-page">Notifications</h3>

<div class="card">
    <?php foreach($notification["notification"] as $notify) : ?>
        <ul class="nav nav-tabs nav-stacked notification">  
            <li class="notification  <?php echo ($notify->has_open) ? "read" : "unread" ;  ?>">
                
                <a href="javascript:void(0);" data-link="<?php echo site_url($notify->link); ?>" data-read="<?php echo $notify->has_open; ?>" data-id="<?php echo $notify->notification_id; ?>" class="click-notification">         
                    
                    <h5> 
                        <i class="material-icons  icon-circle <?php echo $notify->color; ?>" style="border-radius: 30px;">
                            <?php echo $notify->icon; ?> 
                        </i>

                        <span style="color:#272626 !important;word-wrap:break-word;"><?php echo $notify->message; ?></span>

                        <small class="pull-right"><i class="material-icons" style="font-size: 18.5px;">access_time</i> <?php echo $notify->created; ?></small>
                    </h5>
                            
                </a>
            </li>
        </ul>
    <?php endforeach; ?>
</div>
</div>


