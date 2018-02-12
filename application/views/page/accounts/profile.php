<style type="text/css">
    .customHeader{
        overflow: hidden;
    }
    .customHeader > div{
        float: left;
    }
    .customHeader > div:first-child{
        width: 70px;
    }
    .customHeader > div:last-child{
        width: calc(100% - 70px);
    }
    .customHeader > div:last-child > h2{
        line-height: 30px;
    }
</style>
<div class="card">
    <div class="header">
       <div class="customHeader">
            <div>
                <img src="<?php echo $profile->image_thumb; ?>" class="img-circle" width="50px" height="50px">
            </div>
            <div>
                <h2>
                    <?php echo $profile->name .' '. $profile->surname; ?> <small> <?php echo $profile->account_type; ?></small>
                </h2>
            </div>
       </div>
    </div>
    <div class="body">
        <div class="row">
          <div class="col-md-3 col-sm-12">
                <legend>Email Address</legend>
                <p><?php echo $profile->email; ?></p>
                 <legend>Address</legend>
                <p><?php echo $profile->address; ?></p>
          </div>
          <div class="col-md-9 col-sm-12">
                <legend>Activities</legend>
                <ul class="list-group">
                    <li class="list-group-item">Created Defect Report<span class="badge bg-cyan"><?php echo $report_count['defect']; ?></span></li>
                    <li class="list-group-item">Created Serviceable Report<span class="badge bg-teal"><?php echo $report_count['service']; ?></span></li>
                    <li class="list-group-item">Total Report<span class="badge bg-orange"><?php echo $report_count['total']; ?></span></li>
                    <li class="list-group-item">Last Login<span class="badge bg-purple"><?php echo $profile->last_login; ?></span></li>
                </ul>
          </div>
      </div>
    </div>
</div>