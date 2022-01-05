<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-user"></span>Add Taster from agency</h1>

        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li><a href="<?php echo base_url('Agency/tester');?>"><span class="glyphicon glyphicon-user"></span> Taster</a></li>
    			<li class="active"><a href="<?php echo base_url('Agency/tester/add');?>"><span class="glyphicon glyphicon-plus-sign"></span> Add Taster</a></li>
    		</ul>
        </div>
    </div>
</div>

<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('Agency/dashboard');?>">Dashboard</a></li>
		<li><a href="<?php echo base_url('Agency/tester');?>">Taster Management</a></li>
		<li class="active">Add Taster</li>
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
		echo form_open(base_url('Agency/tester/add'),$attributes);
      ?>
	<div class="col-sm-6">

      	<fieldset>
    		<legend>Basic Info</legend>
	      	
    		<input type="hidden" name="user_type" value="tester">
		  	<div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">First Name</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="first_name" class="form-control" id="inputFirstName" placeholder="Enter first name" value="<?php echo set_value('first_name'); ?>" required>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

			<div class="form-group">
		  		<label for="inputLastName" class="col-sm-3 control-label">Last Name</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="last_name" class="form-control" id="inputLastName" placeholder="Enter last name" value="<?php echo set_value('last_name'); ?>" required>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputEmail" class="col-sm-3 control-label">Email address</label>
		  		<div class="col-sm-7">
			  		<input type="email" name="email" class="form-control" id="inputEmail" placeholder="Enter email address" value="<?php echo set_value('email'); ?>" required >
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputPassword" class="col-sm-3 control-label">Password</label>
		  		<div class="col-sm-7">
			  		<input type="password" name="password" data-minlength="3"  class="form-control" id="inputPassword" placeholder="Enter password" required>
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputConfirmPassword" class="col-sm-3 control-label">Confirm Password</label>
		  		<div class="col-sm-7">
		  			<input type="password" name="c_password" class="form-control" id="inputConfirmPassword" data-match="#inputPassword" data-match-error="Whoops, these don't match" placeholder="Confirm password" required>
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
			  		<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-ok-sign"></span> Save User</button> or <a href="<?php echo base_url('Agency/tester');?>">Cancel</a>
			  	</div>
		  	</div>
	  	</fieldset>
	</div>

	<div class="col-sm-6">
		<fieldset>
    		<legend>Other Info</legend>

    		<!--<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Home Adress</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="meta[home_adress]"  class="form-control" id="home_adress" placeholder="Enter home adress" value="<?php echo set_value('home_adress'); ?>">
			  		<div class="help-block with-errors">Home adress</div>
			  	</div>
		  	</div>-->
            <div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">City</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="meta[city]"  class="form-control" id="city" placeholder="Enter city" value="<?php echo set_value('city'); ?>">
			  		<div class="help-block with-errors">City</div>
			  	</div>
		  	</div>
            <div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">State</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="meta[state]"  class="form-control" id="state" placeholder="Enter state" value="<?php echo set_value('state'); ?>" >
			  		<div class="help-block with-errors">State</div>
			  	</div>
		  	</div>
            <div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Zipcode</label>
		  		<div class="col-sm-7">
			  		<input type="number" name="meta[zipcode]"  class="form-control" id="zipcode" placeholder="Enter zipcode" value="<?php echo set_value('zipcode'); ?>">
			  		<div class="help-block with-errors">Zipcode</div>
			  	</div>
		  	</div>
            <div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Address</label>
		  		<div class="col-sm-7">
			  		
                    <textarea name="meta[address]"  class="form-control" id="address" placeholder="Enter address"><?php echo set_value('address'); ?></textarea>
			  		<div class="help-block with-errors">Address</div>
			  	</div>
		  	</div>
            <div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Suite number</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="meta[suite_number]"  class="form-control" id="suite_number" placeholder="Enter Suite number" value="<?php echo set_value('suite_number'); ?>">
			  		<div class="help-block with-errors">Suite number</div>
			  	</div>
		  	</div>
            <div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Apartment number</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="meta[appartment_number]"  class="form-control" id="appartment_number" placeholder="Enter Apartment number" value="<?php echo set_value('appartment_number'); ?>">
			  		<div class="help-block with-errors">Apartment number</div>
			  	</div>
		  	</div>
    		 <div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Cell Number</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="meta[phone]" data-minlength="10" maxlength="14" class="form-control" id="phone" placeholder="Enter phone number" value="<?php echo set_value('phone'); ?>">
			  		<div class="help-block with-errors">14 digit phone number</div>
			  	</div>
		  	</div>
		  	<!--<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Rate per Hour</label>
		  		<div class="col-sm-7">
			  		<input type="number" name="meta[rate_per_hour]"  class="form-control" id="rate_per_hour" placeholder="Enter rate per Hour" value="<?php echo set_value('rate_per_hour'); ?>" >
			  		<div class="help-block with-errors">Rate Per Hour</div>
			  	</div>
		  	</div>-->
    		 <div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Zone</label>
		  		<div class="col-sm-7">
			  		<select name="meta[zone][]" multiple="multiple" required>
			  			<?php
			  				foreach($zone as $value){
			  			?>
			  			<option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
			  			
			  			<?php } ?>
			  		</select>
			  		<div class="help-block with-errors">Zone</div>
			  	</div>
		  	</div>
		  	<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Vendor Number</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="meta[manual_account_number]"  maxlength="10" class="form-control" id="manual_account_number" placeholder="Enter vendor number" value="<?php echo set_value('manual_account_number'); ?>" required>
			  		<div class="help-block with-errors">Vendor number</div>
			  	</div>
		  	</div>
            <div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Tasters rate</label>
		  		<div class="col-sm-7">
			  		<input type="number" name="meta[tasters_rate]"  min="1" class="form-control" id="tasters_rate" placeholder="Enter tasters rate" value="" required>
			  		<div class="help-block with-errors">Tasters rate</div>
			  	</div>
		  	</div>
		  	
    	</fieldset>
	</div>
	<?php echo form_close();?>
</div>
<script>
	$(document).ready(function(){
		//autocomplete off 
		$( document ).on( 'focus', ':input', function(){
			$( this ).attr( 'autocomplete', 'new-password' );
		});
	});
</script>