<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-user"></span> Edit Agency &raquo; <small> <?php echo $agency->first_name." ".$agency->last_name;?></small></h1>
        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li><a href="<?php echo base_url('App/agency');?>"><span class="glyphicon glyphicon-user"></span> Agency</a></li>
    			<li><a href="<?php echo base_url('App/agency/add');?>"><span class="glyphicon glyphicon-plus-sign"></span>Add Agency</a></li>
    		</ul>
        </div>
    </div>
</div>


<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li><a href="<?php echo base_url('App/agency');?>">Agency Management</a></li>
		<li class="active">Edit Agency</li>
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

		$attributes = array('class' => 'form-horizontal', 'id' => '', 'role' => 'form', 'data-toggle' => 'validator');
		echo form_open(base_url('App/agency/edit/'.$agency->id), $attributes);
    ?>
	<div class="col-sm-6">

      	<fieldset>
    		<legend>Basic Info</legend>
	      	
    		<input type="hidden" name="user_type" value="agency">
    		<div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">Tasters under the agency:</label>
		  		<div class="col-sm-7">
		  			<?php
		  				if(!empty($tester_details))
		  				{
		  					echo "<ul>";
		  					foreach($tester_details as $val)
		  					{
		  				?>
		  					<li><strong><?php echo $val->full_name;?></strong></li>
		  				<?php
		  						
		  					}
		  					echo "</ul>";

		  				}
		  				else
		  				{
		  					echo "None";
		  				}
		  			?>
		  		</div>
		  	</div>
		  	<div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">First Name</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="first_name" class="form-control nf" id="inputFirstName" placeholder="Enter first name" value="<?php echo $agency->first_name; ?>">
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

			<div class="form-group">
		  		<label for="inputLastName" class="col-sm-3 control-label">Last Name</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="last_name" class="form-control nf" id="inputLastName" placeholder="Enter last name" value="<?php echo $agency->last_name; ?>">
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputEmail" class="col-sm-3 control-label">Email address</label>
		  		<div class="col-sm-7">
			  		<input type="email" name="email" class="form-control" id="inputEmail" placeholder="Enter email address" value="<?php echo $agency->email; ?>" readonly >
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>

		  	

		  	<div class="form-group">
		  		<label for="" class="col-sm-3 control-label">Status</label>
		  		<div class="col-sm-7">
			  		<div class="radio">
					  <label for="checkboxActive">
					    <input type="radio" name="status" id="checkboxActive" value="active" <?php if(set_value('status') != "inactive") echo "checked";?>>
					    Active
					  </label>
					</div>
					<div class="radio">
					  <label for="checkboxinactive">
					    <input type="radio" name="status" id="checkboxinactive" value="inactive" <?php if(set_value('status') == "inactive") echo "checked";?>>
					    In-active
					  </label>
					</div>
				</div>
			</div>

		  	<div class="form-group">
		  		<div class="col-sm-offset-3 col-sm-6">
			  		<button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure to save the data?');"><span class="glyphicon glyphicon-ok-sign"></span> Save User</button> or <a href="<?php echo base_url('App/Agency');?>">Cancel</a>
			  	</div>
		  	</div>
	  	</fieldset>
	</div>
	
	<div class="col-sm-6">
		<fieldset>
    		<legend>Others Info</legend>
    		<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Agency Name</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="meta[agency_name]" class="form-control" id="agency_name" placeholder="Enter agency name" value="<?php echo $user_meta[0]['meta_value']; ?>" required>
			  		<div class="help-block with-errors">Agency Name</div>
			  	</div>
		  	</div>
    		<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Phone</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="meta[phone]" data-minlength="10" maxlength="10" class="form-control nuf" id="phone" placeholder="Enter phone number" value="<?php echo $user_meta[1]['meta_value']; ?>">
			  		<div class="help-block with-errors">10 digit phone number</div>
			  	</div>
		  	</div>
    		<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Address</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="meta[adress]"  class="form-control" id="home_adress" placeholder="Enter address" value="<?php echo $user_meta[2]['meta_value']; ?>" required>
			  		<div class="help-block with-errors">Address</div>
			  	</div>
		  	</div>
    		 
		  	
    		<!--div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Taster</label>
		  		<div class="col-sm-7">
			  		<select name="meta[tester][]" multiple="multiple" required class="form-control chosen-select">
			  			<?php
			  				$tester_id=$user_meta[3]['meta_value'];
			  				$tester_id_array=explode(',',$tester_id);
			  				foreach($tester as $value){
			  			?>
			  			<option value="<?php echo $value->id;?>" <?php if(in_array($value->id,$tester_id_array)){echo "selected";}?>><?php echo $value->first_name." ".$value->last_name;?></option>
			  			
			  			<?php } ?>
			  		</select>
			  		<div class="help-block with-errors">Taster</div>
			  	</div>
		  	</div-->
		  	<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Zone</label>
		  		<div class="col-sm-7">
			  		<select name="meta[zone][]"  required class="form-control">
			  			<?php
			  				$zone_id=$user_meta[3]['meta_value'];
			  				$zone_id_array=explode(',',$zone_id);
			  				foreach($zone as $value){
			  			?>
			  			<option value="<?php echo $value->id;?>" <?php if(in_array($value->id,$zone_id_array)){echo "selected";}?>><?php echo $value->name;?></option>
			  			
			  			<?php } ?>
			  		</select>
			  		<div class="help-block with-errors">Zone</div>
			  	</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Account Number</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="meta[manual_account_number]"  maxlength="10" class="form-control nuf" id="manual_account_number" placeholder="Enter account number" value="<?php if(array_key_exists(4,$user_meta)){echo $user_meta[4]['meta_value'];}else{echo '';} ?>" required>
			  		<div class="help-block with-errors">Account number</div>
			  	</div>
		  	</div>
		  	
		  	
    	</fieldset>
	</div>
	<?php echo form_close();?>
</div>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/chosen.css">
<script src="<?php echo base_url()?>assets/js/chosen.jquery.js" type="text/javascript"></script>
<script type="text/javascript">
	$(".chosen-select").chosen();
	
</script>
<script>
/*$('.nf').bind('keyup blur',function(){ 
    var node = $(this);
    node.val(node.val().replace(/[^a-z]/g,'') ); }
);*/
$(".nuf").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     /*if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
       
               return false;
    }*/
    if (e.shiftKey || e.ctrlKey || e.altKey) {
		e.preventDefault();
	} 
	else 
	{
		var key = e.keyCode;
		if (!((key == 8) || (key == 32) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105))) {
		e.preventDefault();
		}
	}
   });
</script>