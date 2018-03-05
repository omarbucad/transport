<script type="text/javascript">
    $(function () {
        $('.dt').DataTable({
            responsive: true,
            "aaSorting": [],
            "sDom": 'rt'
        });
    });

    $(document).on('change' , '#store_select' , function(){
    	var url = "<?php echo site_url('app/dashboard/index/'); ?>";
    	var store_id = $(this).val();

    	window.location.replace(url+store_id);
    });

    $(document).on('click' , '.viewModal' , function(){
            var href = $(this).data('href');
            var id = $(this).data('id');

            $.ajax({
                url : href ,
                method : 'get' ,
                success : function(response){
                    var json = jQuery.parseJSON(response);
                    var modal = $('#defaultModal').modal('show');
                    var tmp = "";
                    $.each(json.checklist , function(k , v){
                        tmp += "<li>"+v+"</li>";
                    });
                    modal.find('.modal_defect_container > nav > ol').html(tmp);
                    modal.find('#defaultModalLabel').html("Report # "+json.id);
                    modal.find('#modal_report_id').val(json.id);
                }
            });
            
    });
</script>


<?php if($driver_license_notif['count'] > 0) : ?>
	<div style="margin-bottom: 50px;">
		<div class="col-lg-12 bg-red" style="padding: 10px;">
			<a href="<?php echo site_url("app/vehicles/driver/").$driver_license_notif['query']; ?>" style="color:white;font-weight: bold;padding: 10px;text-decoration: none;"><?php echo $driver_license_notif['count']; ?> Driver License needs attention</a>
		</div>
	</div>
<?php endif; ?>

<div class="block-header">
	<div class="row">
		<div class="col-xs-6 col-md-9 col-lg-9">
			<h2 class="text-uppercase"><?php echo $this->session->userdata('company_name'); ?> DASHBOARD</h2> 
		</div>
		<div class="col-xs-6 col-md-3 col-lg-3">
			<select class="form-control" id="store_select">
				<?php foreach($store_list as $row): ?>
					<option value="<?php echo $row->store_id; ?>" <?php echo ($row->store_id == $store_selected_id) ? "selected='selected'" : false ; ?> ><?php echo $row->store_name; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
</div>

<?php if($show_dashboard) : ?>
	<?php /* ?>
	<div class="row">
	    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
	        <p class="help-block"><?php echo @$boxResult['first_box']['date']; ?></p>
	        <div class="info-box-3 bg-brown">
	            <div class="icon">
	                <span class="chart chart-line"><?php echo @$boxResult["first_box"]["chart"]; ?></span>
	            </div>
	            <div class="content">
	                <div class="text">SALES</div>
	                <div class="number"><?php echo @$boxResult["first_box"]["total"]; ?></div>
	            </div>
	        </div>
	    </div>
	    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
	        <p class="help-block"><?php echo @$boxResult['second_box']['date']; ?></p>
	        <div class="info-box-3 bg-grey">
	            <div class="icon">
	                <span class="chart chart-line"><?php echo @$boxResult["second_box"]["chart"]; ?></span>
	            </div>
	            <div class="content">
	                <div class="text">SALES</div>
	                <div class="number"><?php echo @$boxResult["second_box"]["total"]; ?></div>
	            </div>
	        </div>
	    </div>
	    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
	        <p class="help-block"><?php echo @$boxResult['third_box']['date']; ?></p>
	        <div class="info-box-3 bg-green">
	            <div class="icon">
	                <div class="chart chart-line"><?php echo @$boxResult["third_box"]["chart"]; ?></div>
	            </div>
	            <div class="content">
	            <div class="text">SALES</div>
	                <div class="number"><?php echo @$boxResult["third_box"]["total"]; ?></div>
	            </div>
	        </div>
	    </div>
	</div>
	
	<?php */ ?>

	<?php $this->load->view("common/row") ?>

	<div class="block-header">
		<h2 class="text-uppercase"><?php echo $this->session->userdata('company_name'); ?> JOBS</h2> 
	</div>

	<?php $this->load->view($include_page) ?>

<?php else : ?>
	<div class="row clearfix">
		<div class="col-lg-12 col-md-12 col-sm-6 col-xs-12">
			<div class="card">
				<div class="header bg-orange">
					<h2>
						Warning
					</h2>

				</div>
				<div class="body">
					You have no enabled or registered Company Information <a href="<?php echo site_url("app/company"); ?>">Click here</a> to add Company.
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>


