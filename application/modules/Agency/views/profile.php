<style>
/* Style all input fields */
input {
  /* width: 100%; */
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
  margin-top: 5px;
  margin-bottom: 5px;
}

/* Style the submit button */
input[type=submit] {
  background-color: #04AA6D;
  color: white;
}

/* Style the container for inputs */
.container {
  background-color: #f1f1f1;
  /* padding: 10px; */
}

/* The message box is shown when the user clicks on the password field */
#message {
  display:none;
  background: #f1f1f1;
  color: #000;
  position: relative;
  padding: 5px;
  /* margin-top: 5px; */
}

#message p {
  padding: 0px 25px;
  font-size: 10px;
}

/* Add a green text color and a checkmark when the requirements are right */
.valid {
  color: green;
}

.valid:before {
  position: relative;
  left: -25px;
  content: "✔";
}

/* Add a red text color and an "x" when the requirements are wrong */
.invalid {
  color: #a94442;
}

.invalid:before {
  position: relative;
  left: -25px;
  content: "✖";
}
</style>
<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-user"></span> Update Profile &raquo; <small> <?php echo $user->first_name . " " . $user->last_name;?></small></h1>
    </div>
</div>


<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('Agency/dashboard');?>">Dashboard</a></li>
		<li class="active">Update Profile</li>
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

		//form validation
		echo validation_errors();

		$attributes = array('class' => 'form-horizontal', 'id' => '', 'role' => 'form', 'data-toggle' => 'validator');
		echo form_open(base_url('Agency/edit/'.$user->id), $attributes);
    ?>
    <input type="hidden" name="role_id" id="inputRole" data-token="<?php echo $user->role_token;?>" value="<?php echo $user->role_id;?>">
	<input type="hidden" name="email" value="<?php echo $user->email;?>">
	<input type="hidden" name="status" value="<?php echo $user->status;?>">
	<input type="hidden" name="ref" value="profile">

	<div class="col-sm-6">
		<fieldset>
    		<legend>Basic Info</legend>
	      	<div class="form-group">
		  		<label for="inputRole" class="col-sm-3 control-label">Role</label>
		  		<div class="col-sm-7">
		  			<p class="form-control-static"><?php echo $user->role_name;?></p>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputEmail" class="col-sm-3 control-label">Email address</label>
		  		<div class="col-sm-7">
			  		<p class="form-control-static" id="inputEmail"><?php echo $user->email;?></p>
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">First Name</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="first_name" class="form-control" id="inputFirstName" placeholder="Enter first name" value="<?php echo $user->first_name;?>" required>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

			<div class="form-group">
		  		<label for="inputLastName" class="col-sm-3 control-label">Last Name</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="last_name" class="form-control" id="inputLastName" placeholder="Enter last name" value="<?php echo $user->last_name;?>" required>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputPassword" class="col-sm-3 control-label">Password</label>
		  		<div class="col-sm-7">
			  		<!-- <input type="password" name="password" data-minlength="6" class="form-control" id="inputPassword" placeholder="Enter password" > -->
					  <input class="form-control" type="password" id="inputPassword" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" placeholder="Enter password" autocomplete="off" >
			  		<div class="help-block with-errors">Please leave the password blank, if you don't want to update.</div>
					  <div id="message">
					  <p >Password must contain the following:</p>
					<div><p id="letter" class="invalid">A lowercase letter</p><p id="capital" class="invalid">A capital (uppercase) letter</p><p id="number" class="invalid">A number</p><p id="length" class="invalid">Minimum 8 characters</p></div>
                    </div>
			  	</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputConfirmPassword" class="col-sm-3 control-label">Confirm Password</label>
		  		<div class="col-sm-7">
		  			<input type="password" name="c_password" class="form-control" id="inputConfirmPassword" data-match="#inputPassword" data-match-error="Whoops, these don't match" placeholder="Confirm password" >
		  			<div class="help-block with-errors"></div>
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
			  		<input type="text" name="meta[phone]" data-minlength="10" maxlength="14" class="form-control" id="phone" placeholder="Enter phone number" value="<?php echo $user_meta[1]['meta_value']; ?>" required>
			  		<div class="help-block with-errors">14 digit phone number</div>
			  	</div>
		  	</div>
			  <div class="form-group">
                <label for="inputPhone" class="col-sm-3 control-label">Address</label>
                <div class="col-sm-7">
                    <input type="text" name="meta[adress]"  class="form-control" id="home_adress" placeholder="Enter address" value="<?php echo $user_meta[2]['meta_value']; ?>" required>
                    <div class="help-block with-errors">Address</div>
                </div>
            </div>
    		
		  	<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Zone</label>
		  		<div class="col-sm-7">
			  		<select name="meta[zone][]"  required class="form-control" multiple="multiple" style="height: 200px;">
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
                    <input type="text" name="meta[manual_account_number]"  maxlength="10" class="form-control" id="manual_account_number" placeholder="Enter account number" value="<?php echo $user_meta[4]['meta_value']; ?>" required autocomplete="off">
                    <div class="help-block with-errors">Account number</div>
                </div>
            </div>
    	</fieldset>

    	<fieldset>
    		<legend>Audit Info</legend>
    		<p>
    			<span class="glyphicon glyphicon-info-sign"></span> Last Updated on:
		    	<?php if (!is_null($user->updated_on)) {?>
			    	<small><?php echo datetime_display($user->updated_on);?></small>
			    	by <small><?php echo $user->updated_by_name;?></small>
		    	<?php } else {echo "N/A";}?>
		    </p>

		    <p>
		    	<span class="glyphicon glyphicon-info-sign"></span> Created on:
		    	<?php if (!is_null($user->created_on)) {?>
			    	<small><?php echo datetime_display($user->created_on);?></small>
			    	by <small><?php echo $user->created_by_name;?></small>
			    <?php } else {echo "N/A";}?>
			</p>

		    <p>
		    	<span class="glyphicon glyphicon-info-sign"></span> Last Login on:
		    	<?php if (!is_null($user->last_login)) {?>
		    		<small><?php echo datetime_display($user->last_login);?></small>
		    	<?php } else {echo "N/A";}?>
		    </p>
    	</fieldset>

    	<p>&nbsp;</p>
    	<div class="form-group">
			<div class="col-sm-6">
				<button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure to save the data?');"><span class="glyphicon glyphicon-ok-sign"></span> Save Profile</button>
			</div>
		</div>
	</div>
	<?php echo form_close();?>
</div>
<script>
$(document).ready(function(){
	$(document).ready(function(){
		//autocomplete off 
		$( document ).on( 'focus', ':input', function(){
			$( this ).attr( 'autocomplete', 'off' );
		});
	});
});

</script>

<script>
var myInput = document.getElementById("inputPassword");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");

// When the user clicks on the password field, show the message box
myInput.onfocus = function() {
  document.getElementById("message").style.display = "block";
}

// When the user clicks outside of the password field, hide the message box
myInput.onblur = function() {
  document.getElementById("message").style.display = "none";
}

// When the user starts to type something inside the password field
myInput.onkeyup = function() {
  // Validate lowercase letters
  var lowerCaseLetters = /[a-z]/g;
  if(myInput.value.match(lowerCaseLetters)) {  
    letter.classList.remove("invalid");
    letter.classList.add("valid");
  } else {
    letter.classList.remove("valid");
    letter.classList.add("invalid");
  }
  
  // Validate capital letters
  var upperCaseLetters = /[A-Z]/g;
  if(myInput.value.match(upperCaseLetters)) {  
    capital.classList.remove("invalid");
    capital.classList.add("valid");
  } else {
    capital.classList.remove("valid");
    capital.classList.add("invalid");
  }

  // Validate numbers
  var numbers = /[0-9]/g;
  if(myInput.value.match(numbers)) {  
    number.classList.remove("invalid");
    number.classList.add("valid");
  } else {
    number.classList.remove("valid");
    number.classList.add("invalid");
  }
  
  // Validate length
  if(myInput.value.length >= 8) {
    length.classList.remove("invalid");
    length.classList.add("valid");
  } else {
    length.classList.remove("valid");
    length.classList.add("invalid");
  }
}
</script>