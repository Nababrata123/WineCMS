<style>
.sw { border:2px solid #ccc; width:396px; height:206px; overflow-y: scroll;
    margin-left: 15px;}
.adjustmin {margin-left: 5px !important; width:120px;}
.adjustam {width:105px;}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/sweetalert.css">
<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-list"></span> Clone job &raquo; <small></small></h1>
        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li><a href="<?php echo base_url('App/Job/index/status/assigned');?>"><span class="glyphicon glyphicon-list"></span> Job</a></li>
                 <li><a href="<?php echo base_url('App/Job/add');?>"><span class="glyphicon glyphicon-plus-sign"></span>Add Job</a></li>
    		</ul>
        </div>
    </div>
</div>
<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li><a href="<?php echo base_url('App/Job/index/status/assigned');?>">Job Management</a></li>
		<li class="active">Clone job</li>
	</ol>
	<?php
		if($this->session->flashdata('message_type')) {
			if($this->session->flashdata('message')) {
				echo '<div class="alert alert-'.$this->session->flashdata('message_type').' alert-dismissable">';
				echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
				echo $this->session->flashdata('message');
				echo '</div>';
			}
		}
	?>
	<?php
		//form validation
		echo validation_errors();
		$attributes = array('class' => 'form-horizontal', 'id' => 'pj', 'role' => 'form', 'data-toggle' => 'validator');
		echo form_open_multipart(base_url('App/Job/clone_job/'.$job->id), $attributes);
    ?>
	<div class="col-sm-6">
      	<fieldset>
    		<legend>Basic Info</legend>
    		<div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">Sales Representative</label>
		  		<div class="col-sm-8">
                     <input type="text" readonly value="<?php echo $sales_rep;?>" class="form-control">
		  			<!-- <strong><?php echo $sales_rep;?></strong> -->
		  		</div>
		  	</div>
            <input type="hidden" name="user_id" value="<?php echo $sales_rep_id;?>">
		  	<div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">Job date*</label>
		  		<div class="col-sm-8">
		  			<input type="text" name="tasting_date" class="form-control datepicker" id="tasting_date" placeholder="Enter job date" value="" required readonly>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>
			
            <?php
                $start_hour=date('h',strtotime($job->start_time));
                $end_hour=date('h',strtotime($job->end_time));
                $start_minute=date('i',strtotime($job->start_time));
                $end_minute=date('i',strtotime($job->end_time));
            ?>
			<div class="form-group">
				<label for="inputLastName" class="col-sm-3 control-label">Start Time*</label>
				<div class="col-sm-3">
					<select name="start_time_hour" required class="form-control" id="start_time_hour">
						<option value="">Hour</option>
						<?php
						for($i=1;$i<=12;$i++){
							if($i<10){
								$hour = '0'.$i;
							}else{
								$hour = $i;
							}
						?>
						<option value="<?php echo $hour;?>"><?php echo $hour;?></option>
						<?php
						}
						?>
					</select>
					<div class="help-block with-errors df"></div>
				</div>
				<div class="col-sm-3">
					<div class="form-group adjustmin" >
						<select name="start_time_minute" required class="form-control" id="start_time_minute">
							<?php
							for($i=0;$i<=59;$i++){
								if($i<10){
									$min = '0'.$i;
								}else{
									$min = $i;
								}
							?>
							<option value="<?php echo $min;?>" ><?php echo $min;?></option>
							<?php
							}
							?>
						</select>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group adjustam">
						<select name="time_one" required class="form-control" id="time_one">
							<option value="pm">PM</option>
							<option value="am">AM</option>
						</select>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="end_time_hour" class="col-sm-3 control-label">End Time*</label>
				<div class="col-sm-3">
					<select name="end_time_hour" required class="form-control" id="end_time_hour">
						<option value="">Hour</option>
						<?php
						for($i=1;$i<=12;$i++){
							if($i<10){
								$hour = '0'.$i;
							}else{
								$hour = $i;
							}
						?>
						<option value="<?php echo $hour;?>"><?php echo $hour;?></option>
						<?php
						}
						?>
					</select>
					<div class="help-block with-errors df"></div>
				</div>
				<div class="col-sm-3">
					<div class="form-group adjustmin" >
						<select name="end_time_minute" required class="form-control" id="end_time_minute">
							<?php
							for($i=0;$i<=59;$i++){
								if($i<10){
									$min = '0'.$i;
								}else{
									$min = $i;
								}
							?>
							<option value="<?php echo $min;?>"><?php echo $min;?></option>
							<?php
							}
							?>
						</select>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group adjustam">
						<select name="time_two" required class="form-control" id="time_two">
							<option value="pm">PM</option>
							<option value="am">AM</option>
						</select>
					</div>
				</div>
			</div>
            <input type="hidden" id="hidden_store_id" value="">
		  	<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Store*</label>
		  		<div class="col-sm-8">
			  		<select name="store_id"  required class="form-control" onchange="get_tester_wine(this.value);" id="store">
			  			<option value="">Select store</option>
			  			<?php
                           
                            
			  				foreach($store as $value){
			  			?>
			  			<option value="<?php echo $value['id'];?>" <?php if($value['id']==$job->store_id){echo "selected";}?>><?php echo $value['name'];?></option>
			  			<?php } ?>
			  		</select>
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>
		  	<div class="form-group">
		  		<label for="inputConfirmPassword" class="col-sm-3 control-label">Admin note</label>
		  		<div class="col-sm-8">
		  			<textarea name="admin_note" class="form-control" id="admin_note"  placeholder="Enter admin note"><?php echo $job->admin_note;?></textarea>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>
		  	<div class="form-group">
		  		<label for="inputConfirmPassword" class="col-sm-3 control-label">Taster note</label>
		  		<div class="col-sm-8">
		  			<textarea name="taster_note" class="form-control" id="taster_note"  placeholder="Enter taster note"><?php echo $job->taster_note;?></textarea>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>
		  	<div class="form-group">
		  		<div class="col-sm-offset-3 col-sm-6">
			  		<button type="submit" class="btn btn-primary oc"><span class="glyphicon glyphicon-ok-sign"></span>Clone</button> or <a href="<?php echo base_url('App/Job/index/status/assigned');?>">Cancel</a>
			  	</div>
		  	</div>
	  	</fieldset>
	</div>
	<div class="col-sm-6">
		<fieldset>
    		<legend>Basic Info</legend>
    		<?php
		  		$taster_id=$job->taster_id;
		  		$taster_id_array=explode(",",$taster_id);
		  	?>
    		<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Assign Taster/Agency*</label>
		  		<div class="col-sm-8">
			  		<select name="taster_id"  required class="form-control" id="testers">
                        <option value="">Select Taster</option>
			  			<?php
			  				
			  				foreach($tester as $value){
			  					//Get user role
			  					
			  					$role_id=get_user_role('users',$value['id']);
			  					if($role_id=='5')
			  					{
			  						$agency_name=get_agency_name('user_meta',$value['id']);
			  					}
			  			?>
			  			<option value="<?php echo $value['id'];?>" <?php if(in_array($value['id'],$taster_id_array)){echo "selected";}?>><?php 
			  				if($role_id=='5')
			  				{
			  					echo $agency_name;
			  				}
			  				else
			  				{
			  					echo $value['last_name']." ".$value['first_name'];
			  				}
			  				
			  			?>
			  				
			  			</option>
			  			<?php } ?>
			  		</select>
			  		<br/>
			  		<!-- <input type="button" id="select_all" name="select_all" value="Select All"> -->
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>
		  	<?php
		  		/*$question_id=$job->question_id;
		  		$question_id_array=explode(",",$question_id);*/
		  	?>
		  	<!--<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Question answers</label>
		  		<div class="col-sm-7">
			  		<select name="question_id[]"  class="form-control" multiple="multiple" >
			  			<option value="">Select question</option>
			  			<?php
			  				foreach($question_answers as $value){
			  			?>
			  			<option value="<?php echo $value['id'];?>" <?php if(in_array($value['id'],$question_id_array)){echo "selected";}?>><?php echo $value['question'];?></option>
			  			<?php } ?>
			  		</select>
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>-->
		  	<?php
		  		$wine_id=$job->wine_id;
                
                //echo $wine_id;die;
		  		$wine_id_array=explode(",",$wine_id);
		  	?>
		  	<!--<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Select wine</label>
		  		<div class="col-sm-7" id="wines">
			  		
			  			<?php
			  				foreach($wine as $value){
			  			?>
			  		<?php echo $value->name;?>&nbsp;<input type="checkbox" name="wine_id[]"  value="<?php echo $value->id;?>" <?php if(in_array($value->id,$wine_id_array)){echo "checked";}?> class="wine_id">&nbsp;
			  			<?php 
			  				} 
			  			?>
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>-->
            
			<div class="form-group">
				<label for="inputPhone" class="col-sm-3 control-label">Select Wine(s)*</label>
				<div class="col-sm-8">
					<select class="chosen-select form-control" id="wines" name="wine_id[]" multiple="multiple" data-placeholder="Select wine(s)" required>
						<?php
			  				foreach($wine as $value){
			  			?>
							<option value="<?php echo $value->id;?>" <?php if(in_array($value->id,$wine_id_array)){echo "selected";}?>><?php echo $value->name;?></option>
						<?php 
			  				} 
			  			?>
					</select>
					<div class="help-block with-errors"></div>
				</div>
			</div>
    	</fieldset>
	</div>
	<?php echo form_close();?>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/chosen.css">
<script src="<?php echo base_url()?>assets/js/chosen.jquery.js" type="text/javascript"></script>
<script type="text/javascript">
	$(".chosen-select").chosen();
</script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url();?>assets/js/sweetalert.min.js"></script> 
<script type="text/javascript">
$('.datepicker').datepicker({

    format: 'm/d/yyyy',
    todayHighlight: true,
    autoclose: true,
    startDate: truncateDate(new Date()) 
});
function truncateDate(date) {
  return new Date(date.getFullYear(), date.getMonth(), date.getDate());
}
$(document).ready(function(){
	$('.oc').click(function(e) {
        var c=confirm('Are you ready to confirm the job?');
        
        if(c==true)
        {
            
			var job_id = $('#job_id').val();
			var job_date = $('#tasting_date').val();
			var start_hour = $('#start_time_hour').val();
			var start_min = $('#start_time_minute').val();
			var time_one = $('#time_one').val();
			if(time_one == 'pm' && start_hour !=12){
				start_hour = 12+parseInt(start_hour);
			}
			if(time_one == 'am' && start_hour ==12){
				start_hour = 12-parseInt(start_hour);
			}
			var end_hour = $('#end_time_hour').val();
			var end_min = $('#end_time_minute').val();
			var time_two = $('#time_two').val();
			if(time_two == 'pm' && end_hour != 12){
				end_hour = 12+parseInt(end_hour);
			}
			if(time_two == 'am' && end_hour == 12){
				end_hour = 12-parseInt(end_hour);
			}
			var time1 =  new Date(job_date+' '+start_hour+':'+start_min+':00');
			var time2 =  new Date(job_date+' '+end_hour+':'+end_min+':00');
			var curtime = new Date();
			var start_time = start_hour+':'+start_min+':00';
			var end_time = end_hour+':'+end_min+':00';
			var seconds =  (time2- time1)/1000;
			var min = seconds/60;
			var taster_id = $('#testers').val();
			var givenDate = new Date(job_date);
			var curDate = new Date().getDate();
			var curMon = new Date().getMonth()+1;
			var curYear = new Date().getFullYear();
			var todate = curMon+'/'+curDate+'/'+curYear;
			if(job_date == ''){
				swal("Oops!", "Select a job date.", "warning");
				return false;
			}else if(givenDate < new Date(todate)){
				swal("Oops!", "You can not publish the job because the tasting date is already over.", "warning");
				return false;
			}else if($('#start_time_hour').val()==''){
				$('#start_time_hour').focus();
				swal("Oops!", "Select a start time.", "warning");
				return false;
			}else if($('#end_time_hour').val()==''){
				$('#end_time_hour').focus();
				swal("Oops!", "Select an end time.", "warning");
				return false;
			}else if(time1.getTime()<curtime.getTime()){
				$('#start_time_hour').focus();
				swal("Oops!", "Start time should be greater than current time.", "warning");
				return false;
			}else if(min < 30 && min >=0){
				$('#end_time_hour').focus();
				swal("Oops!", "The start time and the end time should not be same. There should be a gap of minimum 30 min between start and end time.", "warning");
				return false;
			}else if(min < 0){
				$('#end_time_hour').focus();
				swal("Oops!", "End time should be greater than start time.", "warning");
				return false;
			}else if($('#store').val()==''){
				$('#store').focus();
				swal("Oops!", "Select a store.", "warning");
				return false;
			}else if($('#testers').val()==''){
				$('#testers').focus();
				swal("Oops!", "Select a taster/agency.", "warning");
				return false;
			}else if($('#wines').val()==''){
				$('#wines').trigger('chosen:activate');
				swal("Oops!", "Select at least one Wine.", "warning");
				return false;
			}else{
				$.ajax({
					type:'POST',
					url:"<?php echo base_url(); ?>App/job/check_tester_availablity/",
					data: {taster_id:taster_id,job_date:job_date,start_time:start_time,end_time:end_time},
					success:function(data){
						if(data>0){
							swal("Oops!", "The taster has been assigned with other job.", "warning");
							return false;
						}else{
							//$(".loader").fadeIn();
							$("#pj").submit();
							//return false;
						}
					}
				});
				return false;
			}
        }else{
			return false;
		}
    }); 

	function get_tester(id,taster_id,wine_id)
	{
		if(id!='')
		{
			$("#hidden_store_id").val(id);
			$.ajax({
				   type:'POST',
				   url:"<?php echo base_url(); ?>App/Bulk_schedule_job/get_tester/",
				   data: {store_id:id,taster_id:taster_id},
				   success:function(data){
						$("#testers").html(data);
				   }
			});
			$.ajax({
				   type:'POST',
				   url:"<?php echo base_url(); ?>App/Bulk_schedule_job/get_wine/",
				   data: {store_id:id,wine_id:wine_id},
				   success:function(data){
						$("#wines").html(data);
				   }
			});
		}
		
	}
});
	function get_tester_wine(store_id){
		//$("#hidden_store_id").val(store_id);
		if(store_id !=''){
			$.ajax({
				type:'POST',
				url:"<?php echo base_url(); ?>App/job/get_my_tester_wine/",
				data: {store_id:store_id},
				success:function(data){
					var html=JSON.parse(data);
					console.log(html.wineHtml);
					$("#testers").html(html.tasHtml);
					$("#wines").html(html.wineHtml);
					$('.chosen-select').trigger("chosen:updated");
					
				}
			});
		}
	}

</script>
