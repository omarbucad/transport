<script type="text/javascript">
	$(document).ready(function(){

		var loading = false;

		$(window).scroll(function() {
		   if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
		       var skip = $('.append-last').length;
		       var maxReport = "<?php echo $result_count['total'] ?>";
		       if(skip <= maxReport && loading == false){
		       		loading = true;
		       		$.ajax({
			       		url : "<?php echo site_url('app/reports/vehicles') ?>",
			       		data : {id : "<?php echo urldecode($this->uri->segment(4)) ?>" , skip : skip},
			       		method : "POST",
			       		success : function(response){
			       			$('.append-last').last().after(response);

			       			$("img.lazy").lazyload({
			       				effect : "fadeIn",
			       				skip_invisible : true
			       			});

			       			$('.aniimated-thumbnials').lightGallery({
			       				thumbnail: true,
			       				selector: 'a'
			       			});

			       			$('.collapse').collapse('hide');

			       			loading = false;
			       		}
			       });
		       }
		       
		   }
		});

		$("img.lazy").lazyload({
		    effect : "fadeIn",
		    skip_invisible : true
		});

		$('.aniimated-thumbnials').lightGallery({
	        thumbnail: true,
	        selector: 'a'
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

        $(document).on('click' , '.statusUpdateBtn' , function(){
            var form = $(this).closest('.modal').find('form');
            var url = form.attr('action');
            var id = form.find('#modal_report_id').val();
            var status = form.find('#modal_status').val();
            var comment = form.find('#modal_comment').val();
            var data = {id : id , status:status , comment:comment };

            swal({
                title: "Are you sure?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                cancelButtonText: "No!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url : url ,
                        method : 'post' ,
                        data : data ,
                        success : function(response){
                            swal("Updated!", "Successfully Updated" , "success");
                            $('#defaultModal').modal('hide');
                            form[0].reset();
                        }
                    });
                   

                } else {
                    swal("Cancelled", " ", "error");
                }
            });
        });
	});
</script>

<?php if(count($result) == 0) : ?>

<p>No Result</p>

<?php else : ?>

<div class="block-header">
	<h2 class="text-uppercase"><?php echo urldecode($this->uri->segment(4)); ?> REPORTS</h2>
</div>

<div class="row clearfix">
	<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
		<div class="info-box-2 bg-light-green hover-zoom-effect">
			<div class="icon">
				<i class="material-icons">local_shipping</i>
			</div>
			<div class="content">
				<div class="text text-uppercase">Daily Reports</div>
				<div class="number count-to" data-from="0" data-to="<?php echo $result_count['service']; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $result_count['service']; ?></div>
			</div>
		</div>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
		<div class="info-box-2 bg-red hover-zoom-effect">
			<div class="icon">
				<i class="material-icons">report_problem</i>
			</div>
			<div class="content">
				<div class="text text-uppercase">Defective Reports</div>
				<div class="number count-to" data-from="0" data-to="<?php echo $result_count['defect']; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $result_count['defect']; ?></div>
			</div>
		</div>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
		<div class="info-box-2 bg-deep-orange hover-zoom-effect">
			<div class="icon">
				<i class="material-icons">local_shipping</i>
			</div>
			<div class="content">
				<div class="text text-uppercase">Total Reports</div>
				<div class="number count-to" data-from="0" data-to="<?php echo $result_count['total']; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $result_count['total']; ?></div>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('page/report/ajax'); ?>


<?php endif; ?>