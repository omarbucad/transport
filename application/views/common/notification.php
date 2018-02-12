<style type="text/css">
    .dropdown-menu ul.menu li:hover {
    background-color: #e9e9e9; }
    .dropdown-menu ul.menu li a:hover {
          background-color: transparent; }
</style>
<?php foreach($notification["notification"] as $notify) : ?>
<li style="line-height: 0;padding: 7px 0;" class="click-notification <?php echo ($notify->has_open) ? "read" : "unread" ;  ?>">
    <a href="javascript:void(0);" data-link="<?php echo site_url($notify->link); ?>" data-read="<?php echo $notify->has_open; ?>" data-id="<?php echo $notify->notification_id; ?>">
        <div  class="icon-circle <?php echo $notify->color; ?>">
            <i class="material-icons"><?php echo $notify->icon; ?></i>
        </div>
        <div  class="menu-info">
            <h4 style="word-wrap:break-word;width:210px;height:auto;"><?php echo $notify->message; ?></h4>
            <p>
                <i class="material-icons">access_time</i> <?php echo $notify->created; ?>
            </p>
        </div>
    </a>

</li>
<?php endforeach; ?>

 
       