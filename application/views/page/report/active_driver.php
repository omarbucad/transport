<div class="block-header">
    <h2>
        <a href="<?php echo site_url("app/reports/daily");?>" class="btn btn-primary" > Back</a>
    </h2>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    DRIVER LIST
                </h2>
            </div>
            <div class="body ">
                <div class="list-unstyled clearfix table-responsive" style="max-height: 650px">
                    <table class="table table-bordered table-striped table-hover dataTable" >
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Driver</th>
                                <th>Checklist</th>
                                <th>Job Active</th>
                                <th>Last Checklist Update</th>
                                <th>Total Mileage</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Image</th>
                                <th>Driver</th>
                                <th>Checklist</th>
                                <th>Job Active</th>
                                <th>Last Checklist Update</th>
                                <th>Total Mileage</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php foreach($result as $key => $row): ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo $row->image; ?>" class="thumbnail" data-sub-html="<?php echo $row->driver_name; ?>">
                                            <img src="<?php echo $row->image_thumb; ?>" class="img-responsive" width="75px" alt="<?php echo $row->driver_name; ?>">
                                        </a>
                                    </td>
                                    <td><a href="<?php echo site_url("app/reports/daily/?date_from=&date_to=&name=$row->id&submit=submit")?>"><?php echo $row->driver_name; ?></a></td>
                                    <td>
                                        <?php if($row->checklist) : ?>
                                            <span class="label label-success">Yes</span>
                                        <?php else : ?>
                                            <span class="label label-danger">No</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($row->job) : ?>
                                            <span class="label label-success">Yes</span>
                                        <?php else : ?>
                                            <span class="label label-danger">No</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $row->last_active; ?></td>
                                    <td><?php echo $row->mileage; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>