<style>
.sw { border:2px solid #ccc; width:396px; height:206px; overflow-y: scroll;
    margin-left: 15px;}
.adjustmin {margin-left: 5px !important; width:120px;}
.adjustam {width:105px;}
</style>
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap-select.css" />
<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-list"></span> Add job &raquo; <small></small></h1>
        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li class="active"><a href="<?php echo base_url('App/Job');?>"><span class="glyphicon glyphicon-list"></span> Job</a></li>
                <li><a href="<?php echo base_url('App/Job/add');?>"><span class="glyphicon glyphicon-plus-sign"></span>Add Job</a></li>
    		</ul>
        </div>
    </div>
</div>
<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li><a href="<?php echo base_url('App/job');?>">Job Management</a></li>
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
       
		
    ?>
	<div class="col-sm-6">
      	<fieldset>
    		<legend>Basic Info</legend>
            <?php
                if($this->session->userdata('wine_ids'))
                {
                    /*$wine_ids=$this->session->userdata('wine_ids');
                    $names=get_wine_names($wine_ids);
                    //echo $names;die();
                    $this->session->unset_userdata('wine_ids');*/
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
			  		<select name="user_id"  required class="form-control" onchange="get_store(this.value)">
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
		  			<input type="text" name="tasting_date" class="form-control datepicker" id="tasting_date" placeholder="Enter job date" value="<?php if(isset($allinput['tasting_date']) && $allinput['tasting_date']!=''){echo date("m/d/Y", strtotime($allinput['tasting_date']));} ?>"  autocomplete="off" required>
		  			<div class="help-block with-errors df"></div>
		  		</div>
		  	</div>
			<div class="form-group">
				<label for="inputLastName" class="col-sm-3 control-label">Start Time*</label>
				<div class="col-sm-3">
					<select name="start_time_hour" required class="form-control">
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
						<select name="start_time_minute" required class="form-control">
							<?php
							for($i=0;$i<=60;$i++){
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
						<select name="time_one" required class="form-control">
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
						<select name="end_time_minute" required class="form-control">
							<?php
							for($i=0;$i<=60;$i++){
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
						<select name="time_two" required class="form-control">
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
			  		<select name="store_id"  required class="form-control" id="store" onchange="get_tester(this.value)">
			  			<option value="">Select Store</option>
			  			<?php
			  				foreach($store as $value){
			  			?>
			  			<option value="<?php echo $value->id;?>" <?php if(isset($allinput['store_id']) && $value->id==$allinput['store_id']){echo "selected";}?>><?php echo $value->name;?></option>
			  			<?php } ?>
			  		</select>
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>
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
		  		<div class="col-sm-offset-3 col-sm-6">
			  		<button type="submit" class="btn btn-primary" id="submit_button"><span class="glyphicon glyphicon-ok-sign"></span>Publish</button> or <a href="<?php echo base_url('App/job');?>">Cancel</a>
			  	</div>
		  	</div>
	  	</fieldset>
	</div>
	<div class="col-sm-6">
		<fieldset>
    		<legend>Basic Info</legend>
    		
    		<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Assign Taster/Agency*</label>
				<input type="hidden" name="test" value="<?php if(isset($allinput['taster_id'])){echo $allinput['taster_id'];} ?>">
		  		<div class="col-sm-8">
			  		<select name="taster_id[]"  class="form-control"  id="testers" required>
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
		  	<div class="form-group">
                
		  		<label for="inputPhone" class="col-sm-3 control-label">Select Wine(s)*</label>
                
                
		  		<div class="col-sm-8" id="winess">
			  		<select class="selectpicker" multiple data-live-search="true" width="100%" id="wines" required name="wine_id[]">
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
	$(".chosen-select").chosen({max_selected_options: 2});
	/*var limit = 2;
	$('input.single-checkbox').on('change', function(evt) {
	   if($(this).siblings(':checked').length >= limit) {
	       this.checked = false;
	   }
	});*/
</script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url();?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url();?>assets/js/bootstrap-select.min.js"></script>
<script type="text/javascript">
$('.datepicker').datepicker({
    format: 'm/d/yyyy',
    todayHighlight: true,
    autoclose: true,
    startDate: truncateDate(new Date()),
    
});

$('.selectpicker').selectpicker();
function truncateDate(date) {
  return new Date(date.getFullYear(), date.getMonth(), date.getDate());
}

$(document).ready(function(){
	
	var sale_rep_id = $('#user_id').val();
	var store_id = $('#store').val();
    if(sale_rep_id)
    {
		
		get_store(sale_rep_id);
	}
	if(store_id){
		get_tester(store_id);
	}
	$("form").submit(function(e){
            e.preventDefault();
            if(!$("#submit_button").hasClass("disabled"))
            {
				alert('hh');
                var c=confirm('Are you ready to confirm the job?');
                if(c==true)
                {
                   /*  //alert(1);
                    if ($('input:checkbox').filter(':checked').length < 1){
                        alert("Select at least one Wine!");
						$('.sw').focus();
						$(".sw").css("border-color","rgba(35, 169, 176, 0.13)"); 
                        return false;
                    }
                    else
                    { */
                        $('form').unbind('submit').submit();
                    /* } */
                }
                
            }
            else
            {
				alert('tr');
                return false;
            }
            
		
	});     
    
	
});

function get_tester(id)
{
    //$("#hidden_store_id").val(id);
	alert(id);
	if(id!='')
	{
		$.ajax({
			type:'POST',
			url:"<?php echo base_url(); ?>App/job/get_tester/",
			data: {store_id:id},
			success:function(data){
				$("#testers").html(data);
			}
		});
		$.ajax({
			type:'POST',
			url:"<?php echo base_url(); ?>App/job/get_wine/",
			data: {store_id:id},
			success:function(data){
				alert(data);
				$("#winess").html(data);
				$('.selectpicker').selectpicker();
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
</script>
