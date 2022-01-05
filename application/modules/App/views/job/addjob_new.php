<style>
.sw { border:2px solid #ccc; width:396px; height:206px; overflow-y: scroll;
    margin-left: 15px;}
.adjustmin {margin-left: 5px !important; width:120px;}
.adjustam {width:105px;}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/sweetalert.css">
<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-list"></span> Add job &raquo; <small></small></h1>
        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
				<li class="active"><a href="<?php echo base_url('App/Job');?>"><span class="glyphicon glyphicon-list"></span> Job</a></li>
				<?php if($id==0){?>
				<li><a href="<?php echo base_url('App/Job/add');?>"><span class="glyphicon glyphicon-plus-sign"></span>Add Job</a></li>
				<?php } ?>		
    		</ul>
        </div>
    </div>
</div>
<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<?php if($id==0) { ?>
		<li><a href="<?php echo base_url('App/job');?>">Job Management</a></li>
		<?php }else{ ?>
		<li><a href="<?php echo base_url('App/store');?>">Store Management</a></li>
		<?php }?>
		<li class="active">Add job</li>
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
		echo form_open_multipart(base_url('App/job/add/'), $attributes);

		if($this->session->userdata('inputdata'))
		{
			$allinput=$this->session->userdata('inputdata');
			$this->session->unset_userdata('inputdata');
			//print_r($allinput);
		}
		if($this->session->userdata('wine_ids')){
			$this->session->unset_userdata('wine_ids');
		}
    ?>

	<div class="col-sm-6">
      	<fieldset>
    		<legend>Basic Info</legend>
			<?php
                if($this->session->userdata('wine_ids'))
                {
                    $names=''; 
                }
                else
                {
                    $names='';
                }  
            ?>
    		<input type="hidden" id="hidden_wine_session_id" value="<?php echo $names;?>">

            <div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">Sales Rep*</label>
                <input type="hidden" id="hidden_sales_rep_id" value="<?php if(isset($allinput['user_id']))
				{ echo $allinput['user_id'];}?>">
		  		<div class="col-sm-8">
					  <?php if($id==0){ ?> 
			  				<select name="user_id"  required class="form-control" onchange="get_store(this.value)">
						  <?php }else{ ?> 
							<select name="user_id"  required class="form-control">
						  <?php }?>
			  			<option value="">Select Sales Rep</option>
			  			<?php
			  				foreach($sales_rep as $value){
								  
			  			?>
			  			<?php
			  				if(isset($allinput['user_id']))
			  				{
			  			?>
                        
			  			<option value="<?php echo $value['id'];?>" <?php if($value['id']==$allinput['user_id']){echo "selected";}?>><?php echo $value['last_name']." ".$value['first_name'];?></option>
			  			<?php
			  				}
			  				else
			  				{
			  			?>
			  			<option value="<?php echo $value['id'];?>"><?php echo $value['last_name']." ".$value['first_name'];?></option>
			  			<?php
			  				}
			  			?>
			  			<?php } ?>
			  		</select>
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">Job Date*</label>
		  		<div class="col-sm-8">
				  <input type="text" id="tasting_date" name="tasting_date" class="form-control datepicker" placeholder="Enter job date" value="<?php if(isset($allinput['tasting_date']) && $allinput['tasting_date']!=''){echo date("m/d/Y", strtotime($allinput['tasting_date']));} ?>"  autocomplete="off" readonly="readonly" />
		  			<div class="help-block with-errors df"></div>
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
						<option value="<?php echo $hour;?>" <?php if(isset($allinput['start_time_hour']) && $allinput['start_time_hour'] == $hour){echo 'selected';}?>><?php echo $hour;?></option>
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
							<option value="<?php echo $min;?>" <?php if(isset($allinput['start_time_minute']) && $allinput['start_time_minute'] == $min){echo 'selected';}?>><?php echo $min;?></option>
							<?php
							}
							?>
						</select>
					</div>
				</div>

				<div class="col-sm-3">
					<div class="form-group adjustam">
						<select name="time_one" required class="form-control" id="time_one">
							<option value="pm" <?php if(isset($allinput['time_one']) && $allinput['time_one'] == 'pm'){echo 'selected';}?>>PM</option>
							<option value="am" <?php if(isset($allinput['time_one']) && $allinput['time_one'] == 'am'){echo 'selected';}?>>AM</option>
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
						<option value="<?php echo $hour;?>" <?php if(isset($allinput['end_time_hour']) && $allinput['end_time_hour'] == $hour){echo 'selected';}?>><?php echo $hour;?></option>
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
							<option value="<?php echo $min;?>" <?php if(isset($allinput['end_time_minute']) && $allinput['end_time_minute'] == $min){echo 'selected';}?>><?php echo $min;?></option>
							<?php
							}
							?>
						</select>
					</div>
				</div>

				<div class="col-sm-3">
					<div class="form-group adjustam">
						<select name="time_two" required class="form-control" id="time_two">
							<option value="pm" <?php if(isset($allinput['time_two']) && $allinput['time_two'] == 'pm'){echo 'selected';}?>>PM</option>
							<option value="am" <?php if(isset($allinput['time_two']) && $allinput['time_two'] == 'am'){echo 'selected';}?>>AM</option>
						</select>
					</div>
				</div>
			</div>

            <!--<input type="hidden" id="hidden_store_id" value="">-->
		  	<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Store*</label>
                <input type="hidden" id="hidden_store_id" value="<?php if(isset($allinput['store_id'])){echo $allinput['store_id'];}?>">
		  		<div class="col-sm-8">
					  <?php if($id==0){ ?>
						<select name="store_id"  required class="form-control" id="store" onchange="get_tester_wine(this.value)">
						<option value="">Select Store</option>
							<?php }else{?>
								<input type="hidden" name="store_id" id="hidden_store_id" readonly value="<?php echo $store[0]->id;?>" class="form-control">
								<input readonly value="<?php echo $store[0]->name;?>" class="form-control">
							<?php }?>
						  <?php
						  if($id==0){
			  				foreach($store as $value){
						  ?>
							<option value="<?php echo $value->id;?>" <?php if(isset($allinput['store_id']) && $value->id==$allinput['store_id']){echo "selected";}?>><?php echo $value->name;?></option>
						  <?php }} ?>
			  		</select>
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>
			
	  	</fieldset>
	</div>

	<div class="col-sm-6">
		<fieldset>
    		<legend>Basic Info</legend>
    		
			<div class="form-group">
		  		<label for="inputConfirmPassword" class="col-sm-3 control-label">Admin note</label>
		  		<div class="col-sm-8">
		  			<textarea name="admin_note" class="form-control" id="admin_note"  placeholder="Enter admin note" ><?php if(isset($allinput['admin_note'])){echo $allinput['admin_note'];} ?></textarea>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputConfirmPassword" class="col-sm-3 control-label">Taster note</label>
		  		<div class="col-sm-8">
		  			<textarea name="taster_note" class="form-control" id="taster_note"  placeholder="Enter taster note"><?php if(isset($allinput['taster_note'])){echo $allinput['taster_note'];} ?></textarea>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

    		<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Assign Taster/Agency</label>
				<input type="hidden" name="hidden_tester_id" id="hidden_tester_id" value="<?php if(isset($allinput['taster_id'])){echo $allinput['taster_id'];}?>">
		  		<div class="col-sm-8">
			  		<select name="taster_id[]"  class="form-control"  id="testers">
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
			  			<option value="<?php echo $value['id'];?>" <?php if(isset($allinput['taster_id']) && $allinput['taster_id']==$value['id']){echo 'selected';} ?>><?php 
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
			  		<!--<input type="button" id="select_all" name="select_all" value="Select All">-->
			  		<div class="help-block with-errors"></div>
			  		
			  	</div>
		  	</div>
		  	

			<div class="form-group" id="select_wine_div">
				<label for="inputPhone"  class="col-sm-3 control-label">Select Wine(s)*</label>
				<div class="col-sm-8">
					<select class="chosen-select form-control" id="wines" name="wine_id[]" multiple="multiple" data-placeholder="Select wine(s)" required>
						<?php
			  				foreach($wine as $value){
			  			?>
							<option value="<?php echo $value->id;?>" <?php if(isset($allinput['wine_id']) && in_array($value->id,$allinput['wine_id'])){echo 'selected';} ?>><?php echo $value->name;?></option>
						<?php 
			  				} 
			  			?>
					</select>
					<div class="help-block with-errors"></div>
				</div>
			</div>
		  	
    	</fieldset>
	</div>

	<?php $to_date = date('m/d/Y'); 
	$tasting_date = $this->input->post('tasting_date');
	?>
	
	<!-- Choose previous date show invoice info.. -->
	<div class="col-sm-12" id="invoice_div" style="<?php if($tasting_date == ''){echo 'display: none';} ?> " style="<?php if($to_date >= $tasting_date && $tasting_date !=''){echo 'display: none';} ?>">
	
		<div class="col-sm-6">
		<fieldset>
    		<legend>Invoice Info</legend>
    		
    		<div class="form-group">
				<label for="inputLastName" class="col-sm-3 control-label">Actual start time*</label>
				<div class="col-sm-3">
					<select name="actual_start_hour"  class="form-control" id="actual_start_hour">
						<option value="">Hour</option>
						<?php
						for($i=1;$i<=12;$i++){
							if($i<10){
								$hour = '0'.$i;
							}else{
								$hour = $i;
							}
						?>
						<option value="<?php echo $hour;?>" <?php if(isset($allinput['actual_start_hour']) && $allinput['actual_start_hour'] == $hour){echo 'selected';}?>><?php echo $hour;?></option>
						<?php
						}
						?>
					</select>
					<div class="help-block with-errors df"></div>
				</div>

				<div class="col-sm-3">
					<div class="form-group adjustmin" >
						<select name="actual_start_minute"  class="form-control" id="actual_start_minute">
							<?php
							for($i=0;$i<=59;$i++){
								if($i<10){
									$min = '0'.$i;
								}else{
									$min = $i;
								}
							?>
							<option value="<?php echo $min;?>" <?php if(isset($allinput['actual_start_minute']) && $allinput['actual_start_minute'] == $min){echo 'selected';}?>><?php echo $min;?></option>
							<?php
							}
							?>
						</select>
					</div>
				</div>

				<div class="col-sm-3">
					<div class="form-group adjustam">
						<select name="actual_time_one"  class="form-control" id="actual_time_one">
							<option value="pm" <?php if(isset($allinput['actual_time_one']) && $allinput['actual_time_one'] == 'pm'){echo 'selected';}?>>PM</option>
							<option value="am" <?php if(isset($allinput['actual_time_one']) && $allinput['actual_time_one'] == 'am'){echo 'selected';}?>>AM</option>
						</select>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label for="actual_end_hour" class="col-sm-3 control-label">Actual End Time*</label>
				<div class="col-sm-3">
					<select name="actual_end_hour"  class="form-control" id="actual_end_hour">
						<option value="">Hour</option>
						<?php
						for($i=1;$i<=12;$i++){
							if($i<10){
								$hour = '0'.$i;
							}else{
								$hour = $i;
							}
						?>
						<option value="<?php echo $hour;?>" <?php if(isset($allinput['actual_end_hour']) && $allinput['actual_end_hour'] == $hour){echo 'selected';}?>><?php echo $hour;?></option>
						<?php
						}
						?>
					</select>
					<div class="help-block with-errors df"></div>
				</div>

				<div class="col-sm-3">
					<div class="form-group adjustmin" >
						<select name="actual_end_minute"  class="form-control" id="actual_end_minute">
							<?php
							for($i=0;$i<=59;$i++){
								if($i<10){
									$min = '0'.$i;
								}else{
									$min = $i;
								}
							?>
							<option value="<?php echo $min;?>" <?php if(isset($allinput['actual_end_minute']) && $allinput['actual_end_minute'] == $min){echo 'selected';}?>><?php echo $min;?></option>
							<?php
							}
							?>
						</select>
					</div>
				</div>
				
				<div class="col-sm-3">
					<div class="form-group adjustam">
						<select name="actual_time_two"  class="form-control" id="actual_time_two">
							<option value="pm" <?php if(isset($allinput['actual_time_two']) && $allinput['actual_time_two'] == 'pm'){echo 'selected';}?>>PM</option>
							<option value="am" <?php if(isset($allinput['actual_time_two']) && $allinput['actual_time_two'] == 'am'){echo 'selected';}?>>AM</option>
						</select>
					</div>
				</div>
			</div>
		  	
    	</fieldset>
		</div>

		<div class="col-sm-6">
		<fieldset>
    		<legend>Invoice Info</legend>

		  <div class="form-group">
			<label for="inputPhone" class="col-sm-3 control-label">Expense amount:</label>
            <div class="col-sm-8">
              <input type="text" id="exp_amount" name="exp_amount" min="0" placeholder="Expense amount"  class="form-control number" value="<?php if(isset($expense_details[0]['exp_amount'])){echo ltrim($expense_details[0]['exp_amount'],'$');}?>">
              <div class="help-block with-errors"></div>
          </div>
		</div>

		<div class="form-group">
			<label for="inputPhone" class="col-sm-3 control-label">Expense reason:</label>
			<div class="col-sm-8">
				<textarea id="exp_reason" name="exp_reason" placeholder="Expense reason"  class="form-control"><?php if(isset($expense_details[0]['exp_reason'])){echo ltrim($expense_details[0]['exp_reason'],"$");}?></textarea>
				<div class="help-block with-errors"></div>
			</div>
		</div>

		<div class="form-group">
            <label for="inputPhone" class="col-sm-3 control-label">Expense Receipt:</label>
            <div class="col-sm-8">
              <input type="file" id="receipt_file" name="expense_images[]"  class="form-control" multiple="multiple">     
              <div class="help-block with-errors"></div>
            </div>
          </div>	  	
    	</fieldset>
		</div>

		<div class="col-sm-12">
			<fieldset id="wineForm">
			<legend></legend>
			<div class="col-sm-3">
			<legend><h4> Wine</h4></legend>
			</div>
			<div class="col-sm-2">
			<legend><h4>Sampled</h4></legend>
			</div>
			<div class="col-sm-3">
			<legend><h4>Opened bottles sampled</h4></legend>
			</div>
			<div class="col-sm-2">
			<legend><h4>Sold</h4></legend>
			</div>
			<div class="col-sm-2">
			<button class="add_field_button" href="#" ><span class="glyphicon glyphicon-plus-sign"></span>Add more </button>
        </div>
      	</fieldset>
		  
  <!--End-->
	</div>
	</div>

	<div class="form-group">
		<div class="col-sm-12 text-center">
			<button type="submit" class="btn btn-primary" id="submit_button"><span class="glyphicon glyphicon-ok-sign"></span>Publish</button> or <a href="<?php echo base_url('App/job');?>">Cancel</a>
		</div>
	</div>
	
	<?php echo form_close();?>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/chosen.css">
<script src="<?php echo base_url()?>assets/js/chosen.jquery.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/sweetalert.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

<script type="text/javascript">
	$(".chosen-select").chosen();
</script>

<script type="text/javascript">
	
	$(document).ready(function() {
    $('.datepicker').datepicker({
        format: 'm/d/yyyy',
		todayHighlight: true,
		autoclose: true,
    })
    //Listen for the change even on the input
    .change(dateChanged)
    .on('changeDate', dateChanged);
});

function dateChanged(ev) {
	var chosse_job_date = $('#tasting_date').val();
	var d = new Date();
	var currentDate = (d.getMonth()+1) + "/" + d.getDate() + "/" + d.getFullYear();
	
	if(chosse_job_date >= currentDate){
		$("#invoice_div").hide();
		$("#select_wine_div").show();
		// $('#actual_start_hour').prop('required',false);
	}else{
		$("#invoice_div").show();
		$("#select_wine_div").hide();
		// $('#actual_start_hour').prop('required',true);	
	}
	
    $(this).datepicker('hide');

}


/* function truncateDate(date) {
  return new Date(date.getFullYear(), date.getMonth(), date.getDate());
} */

$(document).ready(function(){

	/* $('#datepicker').datepicker({
		format: 'm/d/yyyy',
		todayHighlight: true,
		autoclose: true,
		startDate: truncateDate(new Date()),
		
	}); */

    var hidden_sales_rep_id=$("#hidden_sales_rep_id").val();
    var hidden_store_id=$("#hidden_store_id").val();
	var hidden_tester_id=$('#hidden_tester_id').val();

    if(hidden_sales_rep_id)
    {
		$.ajax({
			   type:'POST',
			   url:"<?php echo base_url(); ?>App/job/get_store_for_sales_rep/",
			   data: {sales_rep_id:hidden_sales_rep_id,hidden_store_id:hidden_store_id},
			   success:function(data){
					$("#store").html(data);
			   }
		});
	}
	if(hidden_store_id){
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
    
    $("form").submit(function(e){
            e.preventDefault();
            
            if(!$("#submit_button").hasClass("disabled"))
            {
				
                var c=confirm('Are you ready to confirm the job?');
                if(c==true)
                {

					var  winevals= $("select[name=\'wine[]\']").map(function() {
						return $(this).val();
					}).toArray();
					winevals=winevals.filter(Number);

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
					var curtime = new Date();

					if(time_one == 'pm' && time_two == 'am'){
						var time2 =  new Date(job_date+' '+end_hour+':'+end_min+':00');
						time2.setDate(time2.getDate() + 1)
					}else{
						var time2 =  new Date(job_date+' '+end_hour+':'+end_min+':00');
					}
					
					
					//alert(time1);
					//alert(curtime);
					var start_time = start_hour+':'+start_min+':00';
					var end_time = end_hour+':'+end_min+':00';
					var seconds =  (time2- time1)/1000;
					var min = seconds/60;
					var taster_id = $('#testers').val();
					if(job_date == ''){
						swal("Oops!", "Select a job date.", "warning");
						return false;
					}else if($('#start_time_hour').val()==''){
						$('#start_time_hour').focus();
						swal("Oops!", "Select a start time.", "warning");
						return false;
					}else if($('#end_time_hour').val()==''){
						$('#end_time_hour').focus();
						swal("Oops!", "Select an end time.", "warning");
						return false;
					}
					// For new task testing..
					/*else if(time1.getTime()<curtime.getTime()){
						$('#start_time_hour').focus();
						swal("Oops!", "Start time should be greater than current time.", "warning");
						return false;
					}*/
					else if(min < 30 && min >=0){
						$('#end_time_hour').focus();
						swal("Oops!", "The start time and the end time should not be same. There should be a gap of minimum 30 min between start and end time.", "warning");
						return false;
					}else if(time_one == 'pm' && time_two == 'am' && end_time > '03:00:00'){
						$('#end_time_hour').focus();
						// swal("Oops!", "End time should be greater than start time.", "warning");
						swal("Oops!", "The end time cannot be greater than 3 a.m.", "warning");
						return false;
					}else if(time_one == 'pm' && time_two == 'pm' && time1.getTime()>time2.getTime()){
						$('#start_time_hour').focus();
						swal("Oops!", "End time should be greater than start time.", "warning");
						return false;
					}else if(time_one == 'am' && time_two == 'am' && time1.getTime()>time2.getTime()){
						$('#start_time_hour').focus();
						swal("Oops!", "End time should be greater than start time.", "warning");
						return false;
					}else if($('#store').val()==''){
						$('#store').focus();
						swal("Oops!", "Select a store.", "warning");
						return false;
					}
					// For new task testing...
					/*else if($('#wines').val()==''){
						//$('#wines').focus();
						$('#wines').trigger('chosen:activate');
						swal("Oops!", "Select at least one Wine.", "warning");
						return false;
					}*/
					else if(winevals.length==0){
						$('#wine').focus();
						$.alert({ title: 'Oops!', type:'red', content: 'Please select atleast one wine', });
						return false;
					}else if(winevals.length!=0){
						var arr=winevals;
						arr =arr.filter(Number);
						let hasDuplicate = new Set(arr).size !== arr.length;
						if(hasDuplicate){
						$.alert({ title: 'Oops!', type:'red', content: 'Duplicate wines selected.', });
						return false;
						}
					}else{
						$.ajax({
							type:'POST',
							url:"<?php echo base_url(); ?>App/job/check_tester_availablity/",
							data: {taster_id:taster_id,job_date:job_date,start_time:start_time,end_time:end_time},
							success:function(data){
								if(data == 0){
									$('form').unbind('submit').submit();
								}else{
									swal("Oops!", "The taster has been assigned with other job.", "warning");
									return false;
								}
							}
						});
						// return false;
					}
					
                }else{
					return false;
				}
            }
            else
            {
                return false;
            } 
	});    
	
});


	function get_tester_wine(store_id){
		$("#hidden_store_id").val(store_id);
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
function get_store(id)
{
    //var sales_rep_id=id;
    $.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/job/get_store_for_sales_rep/",
		   data: {sales_rep_id:id},
		   success:function(data){
		    	$("#store").html(data);
		   }
	});
    
}

function DisplayDate(message) {
        alert(message);
    };

$('#select_all').click(function() {
       $('#testers option').prop('selected', true);
});

</script>

<script type="text/javascript">

$(document).ready(function() {
	var max_fields      = 10; //maximum input boxes allowed
	var wrapper   		= $(".input_fields_wrap"); //Fields wrapper
	var add_button      = $(".add_field_button"); //Add button ID
	
	var x = 1; //initlal text box count
	$(add_button).click(function(e){ //on add input button click
    <?php
      $wine='<option value="">Select Wine</option>';
      $wineList = $get_wine_list;
      foreach($wineList as $w){
        $wine.='<option value="'.$w['id'].'">'.addslashes($w['name']).'</option>';
      }
    ?>
    
    var wine='<?php echo $wine;?>';
    
    var html ='<div class="row" style="margin-top:20px;"><div class="col-sm-3"><select class="form-control" id="wine" name="wine[]">'+wine+'</select></div><div class="col-sm-2"><input value="0" name="bottles_sampled[]" onkeypress="return onlyNumberKey(event)" class="form-control"></div><div class="col-sm-3"><input value="0" onkeypress="return onlyNumberKey(event)" name="open_bottles_sampled[]" class="form-control"></div><div class="col-sm-2"><input value="0" onkeypress="return onlyNumberKey(event)" name="bottles_sold[]" class="form-control"> </div><a href="#" class="remove_field"><strong><span class="glyphicon glyphicon-minus-sign" style="padding-top: 10px;"></strong></a></div><br/>';
    
		e.preventDefault();      
			$(wrapper).append(html); //add input box
		
	});
	
	$(wrapper).on("click",".remove_field", function(e){ //user click on remove text
      
      var  winevals= $("select[name=\'wine[]\']").map(function() {
        return $(this).val();
      }).toArray();
      var arr=winevals;
     
      if(arr.length>1){
        e.preventDefault(); $(this).parent('div').remove(); x--;
      }else{
        $.alert({ title: 'Oops!', type:'red', content: 'All wines can not be deleted. Atleast one wine needed in the list.', });
        return false;
      }
  });
});
  

function onlyNumberKey(evt) { 
    
	// Only ASCII charactar in that range allowed 
	var ASCIICode = (evt.which) ? evt.which : evt.keyCode 
	if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)) 
		return false; 
	return true; 
} 
</script>
