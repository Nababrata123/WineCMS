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

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/sweetalert.css">
<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-user"></span> Master Password &raquo; <small> </small></h1>
    </div>
</div>


<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li class="active">Master Password</li>
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
	<div class="col-sm-12">
		<form id="passform" class="" role="form" data-toggle="validator" method="post" action="<?php echo base_url('admin/users/master_password'); ?>">
			<fieldset>
				<legend>Basic Info</legend>
				<div class="form-group">
					<label for="pass" class="col-sm-3 control-label" style="text-align:right;">Master Password:</label>
					<div class="col-sm-7">
						<!-- <input type="password" name="pass" class="form-control" id="pass" placeholder="Enter master password" value="<?php echo set_value('pass'); ?>" required autocomplete="new-password">
						<div class="help-block with-errors"></div> -->

						<input class="form-control" type="password" id="pass" name="pass" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" placeholder="Enter master password" autocomplete="new-password" required>
			  		<!-- <div class="help-block with-errors"></div> -->

					<div id="message">
					  <p >Password must contain the following:</p>
					<div><p id="letter" class="invalid">A lowercase letter</p><p id="capital" class="invalid">A capital (uppercase) letter</p><p id="number" class="invalid">A number</p><p id="length" class="invalid">Minimum 8 characters</p></div>
                    </div>

					</div>
				</div>

				<div class="form-group">
					<label for="com_pass" class="col-sm-3 control-label" style="text-align:right;">Confirm Password:</label>
					<div class="col-sm-7">
						<input type="password" name="com_pass" class="form-control" id="com_pass" placeholder="Enter confirm password" value="<?php echo set_value('com_pass'); ?>" required autocomplete="new-password">
						<div class="help-block with-errors"></div>
						<input type="checkbox" onclick="showPassword();" id="check"><label for="check">Show Password</label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-6">
						<button type="submit" class="btn btn-primary" onclick="return pass_validate();"><span class="glyphicon glyphicon-ok-sign"></span> Save</button> or <a href="">Cancel</a>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
	
</div>
<script src="<?php echo base_url();?>assets/js/sweetalert.min.js"></script> 
<script>
	function showPassword(){
		var field = document.getElementById("pass");
		if (field.type === "password") {
			field.type = "text";
		} else {
			field.type = "password";
		}
		var field2 = document.getElementById("com_pass");
		if (field2.type === "password") {
			field2.type = "text";
		} else {
			field2.type = "password";
		}
	}
	function pass_validate(){
		if($('#pass').val() == ''){
			$('#pass').focus();
			swal("Oops!","Enter password","warning");
			return false;
		}else if($('#com_pass').val() == ''){
			$('#com_pass').focus();
			swal("Oops!","Enter confirm password","warning");
			return false;
		}else if($('#pass').val() != $('#com_pass').val()){
			$('#com_pass').focus();
			swal("Oops!","Passwords does not match","warning");
			return false;
		}else{
			return true;
		}
	}
</script>

<script>
var myInput = document.getElementById("pass");
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