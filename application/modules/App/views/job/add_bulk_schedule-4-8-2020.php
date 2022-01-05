<style>
.adjustmin {margin-left: 5px !important; width:120px;}
.adjustam {width:105px;}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/sweetalert.css">
<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-briefcase"></span> Add Bulk Schedule &raquo; <small></small></h1>
        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li><a href="<?php echo base_url('App/Bulk_schedule_job');?>"><span class="glyphicon glyphicon-briefcase"></span> Bulk Schedule</a></li>
                <li><a href="<?php echo base_url('App/Bulk_schedule_job/add');?>"><span class="glyphicon glyphicon-plus-sign"></span>Create Bulk Schedule</a></li>
    		</ul>
        </div>
    </div>
</div>
<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li><a href="<?php echo base_url('App/Bulk_schedule_job');?>">Bulk Schedule Management</a></li>
		<li class="active">Add Bulk Schedule</li>
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
		echo form_open_multipart(base_url('App/Bulk_schedule_job/add/'), $attributes);
        if($this->session->userdata('inputdata'))
		{
			$allinput=$this->session->userdata('inputdata');
			$this->session->unset_userdata('inputdata');
		}
    ?>
	<div class="col-sm-6">
      	<fieldset>
    		<legend>Basic Info</legend>
            <div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">Set Rules*</label>
		  		<div class="col-sm-8">
                    <select name="rules"  required class="form-control">                        
                        <option value="weekly">Weekly schedule</option>
                        <option value="monthly">Monthly schedule</option>
                    </select>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>
    		<div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">Start Date*</label>
		  		<div class="col-sm-8">
		  			<input type="text" name="start_date" class="form-control datepicker" id="start_date" placeholder="Enter start date" value="<?php echo set_value('start_date');?>" required readonly>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>
            <div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">End Date*</label>
		  		<div class="col-sm-8">
		  			<input type="text" name="end_date" class="form-control datepicker" id="end_date" placeholder="Enter end date" value="" required readonly>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>
                        <div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">Sales Rep*</label>
                <input type="hidden" id="hidden_sales_rep_id" value="<?php if(isset($allinput['user_id']))
{ echo $allinput['user_id'];}?>">
		  		<div class="col-sm-8">
			  		<select name="user_id"  required class="form-control" onchange="get_store(this.value);" id="user_id">
			  			<option value="">Select sales rep</option>
			  			<?php
			  				foreach($sales_rep as $value){
			  			?>
			  			
			  			<option value="<?php echo $value['id'];?>" <?php if(isset($allinput['user_id']) && ($allinput['user_id']==$value['id'])){echo "selected";}?> ><?php echo $value['last_name']." ".$value['first_name'];?></option>
			  			<?php
			  				}
			  			?>
			  			
			  		</select>
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>
            
		  	
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
							<option value="<?php echo $min;?>"><?php echo $min;?></option>
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
            <div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Store*</label>
				<input type="hidden" value="<?php if(isset($allinput['store_id'])){echo $allinput['store_id'];}?>" id="hidden_store_id">
		  		<div class="col-sm-8">
			  		<select name="store_id"  required class="form-control" id="store" onchange="get_tester_wine(this.value);">
			  			<option value="">Select sales rep first.</option>
			  		</select>
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>
		  	<div class="form-group">
		  		<div class="col-sm-offset-3 col-sm-6">
			  		<button type="submit" class="btn btn-primary" id="submit_button"><span class="glyphicon glyphicon-ok-sign"></span>Publish</button> or <a href="<?php echo base_url('App/Bulk_schedule_job');?>">Cancel</a>
			  	</div>
		  	</div>
	  	</fieldset>
	</div>
	<div class="col-sm-6">
      	<fieldset>
			<legend>Basic Info</legend>
			<div class="form-group">
				<label for="inputPhone" class="col-sm-3 control-label">Taster</label>
				<div class="col-sm-8">
					<select class="form-control" name="taster" id="taster" onchange="display_wine_field(this.value);">
						<option value="">Select store first</option>
					</select>
				</div>
			</div>
			<div class="form-group" id="wine_div" style="display:none;">
				<label for="inputPhone" class="col-sm-3 control-label">Select Wine(s)*</label>
				<div class="col-sm-8">
					<select class="chosen-select form-control" id="wines" name="wine[]" multiple="multiple" data-placeholder="Select wine(s)">
					</select>
				</div>
			</div>
		</fieldset>
	</div>
	<?php echo form_close();?>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/chosen.css">
<script src="<?php echo base_url()?>assets/js/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url();?>assets/js/sweetalert.min.js"></script> 
<script type="text/javascript">
	
	$(".chosen-select").chosen();
	$('.datepicker').datepicker({

		format: 'm/d/yyyy',
		todayHighlight: true,
		autoclose: true,
		//startDate: truncateDate(new Date()) 
		startDate: '+2d'
	});
	function truncateDate(date) {
		return new Date(date.getFullYear(), date.getMonth(), date.getDate());
	}
	//Get store depend on sales rep
	function get_store(sales_rep){
		if(sales_rep != ''){
			$.ajax({
				type:'POST',
				url:"<?php echo base_url(); ?>App/job/get_store_for_sales_rep/",
				data: {sales_rep_id:sales_rep},
				success:function(data){
					$("#store").html(data);
				}
			});
		}else{
			$('#store').html('<option value="">Select sales rep first.</option>');
			$('#taster').html('<option value="">Select store first</option>');
			//$('#wines').html('<option value="">Select store first</option>');
			$('#wine_div').css('display','none');
			$('#wines').val('');
			$('.chosen-select').trigger("chosen:updated");
			
		}
		
	}
	//Get taster and wine depend on store
	function get_tester_wine(store){
		if(store !=''){
			$.ajax({
				type:'POST',
				url:"<?php echo base_url(); ?>App/Bulk_schedule_job/get_my_tester_wine/",
				data: {store_id:store},
				success:function(data){
					var html=JSON.parse(data);
					console.log(html.wineHtml);
					$("#taster").html(html.tasHtml);
					$("#wines").html(html.wineHtml);
					$('.chosen-select').trigger("chosen:updated");
					
				}
			});
		}else{
			$('#taster').html('<option value="">Select store first</option>');
			$('#wines').html('<option value="">Select store first</option>');
			$('.chosen-select').trigger("chosen:updated");
			
		}
	}
	//Display wine filed
	function display_wine_field(taster){
		if(taster !=''){
			$('#wine_div').css('display','block');
			$('#wines_chosen').css('width','100%');
		}else{
			$('#wine_div').css('display','none');
			$('#wines_chosen').css('width','100%');
			$('#wines').val('');
			$('.chosen-select').trigger("chosen:updated");
			
		}
	}
	// form validation
	$("form").submit(function(e){
		//e.preventDefault();
		
		if(!$("#submit_button").hasClass("disabled"))
		{
			//alert($('#wines').val());
			var c=confirm('Are you ready to confirm the job?');
			if(c==true){
				var job_date = $('#start_date').val();
				var start_hour = $('#start_time_hour').val();
				var start_min = $('#start_time_minute').val();
				var time_one = $('#time_one').val();
				var end_hour = $('#end_time_hour').val();
				var end_min = $('#end_time_minute').val();
				var time_two = $('#time_two').val();
				if(time_one == 'pm' && start_hour !=12){
					start_hour = 12+parseInt(start_hour);
				}
				if(time_one == 'am' && start_hour ==12){
					start_hour = 12-parseInt(start_hour);
				}
				if(time_two == 'pm' && end_hour != 12){
					end_hour = 12+parseInt(end_hour);
				}
				if(time_two == 'am' && end_hour == 12){
					end_hour = 12-parseInt(end_hour);
				}
				var time1 =  new Date(job_date+' '+start_hour+':'+start_min+':00');
				var time2 =  new Date(job_date+' '+end_hour+':'+end_min+':00');
				var start_time = start_hour+':'+start_min+':00';
				var end_time = end_hour+':'+end_min+':00';
				var seconds =  (time2- time1)/1000;
				var min = seconds/60;
				//alert($('#end_date').val());
				//alert($('#start_date').val());
				if($('#start_date').val()==''){
					$('#start_date').focus();
					swal("Oops!", "Select a start date.", "warning");
					return false;
				}else if($('#end_date').val()==''){
					$('#end_date').focus();
					swal("Oops!", "Select an end date", "warning");
					return false;
				}else if(new Date($('#end_date').val()) <= new Date($('#start_date').val())){
					$('#end_date').focus();
					swal("Oops!", "The end date should be greater than start date", "warning");
					return false;
				}else if($('#user_id').val()==''){
					$('#user_id').focus();
					swal("Oops!","Select a Sales rep.","warning");
					return false;
				}else if($('#start_time_hour').val()==''){
					$('#start_time_hour').focus();
					swal("Oops!","Select a start time","warning");
					return false;
				}else if($('#end_time_hour').val()==''){
					$('#end_time_hour').focus();
					swal("Oops!","Select an end time","warning");
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
					swal("Oops!","Select a store","warning");
					return false;
				}else if($('#taster').val()!='' && $('#wines').val()==''){
					$('#wines').focus();
					swal("Oops!","Select a wine","warning");
					return false;
				}
				else{
					return true;
				}
			}else{
				return false;
			}
				
		}
	});
</script>
