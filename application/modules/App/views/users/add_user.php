<!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap-select.css">
<style>
  .bootstrap-select .dropdown-menu {
    width:100%;
  }
  .bootstrap-select {
      width: 100% \0;
      vertical-align: middle;
      width: 100% !important;
}
.show-tick.bootstrap-select .dropdown-menu .selected span.check-mark{
    left: 2px;
}
  </style>
<style>
.sw { border:2px solid #ccc; width:396px; height:206px; overflow-y: scroll;
    margin-left: 15px;}
.adjustmin {margin-left: 5px !important; width:120px;}
.adjustam {width:105px;}
</style>

<style>
/* Style all input fields */
input {
  width: 100%;
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
    	<h1><span class="glyphicon glyphicon-user"></span>Add User</h1>

        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li><a href="<?php echo base_url('App/users');?>"><span class="glyphicon glyphicon-user"></span> Users</a></li>
    			<li class="active"><a href="<?php echo base_url('App/users/add');?>"><span class="glyphicon glyphicon-plus-sign"></span> Add User</a></li>
    		</ul>
        </div>
    </div>
</div>

<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li><a href="<?php echo base_url('App/users');?>">Users Management</a></li>
		<li class="active">Add User</li>
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
		echo form_open_multipart(base_url('App/users/add'), $attributes);


		if($this->session->userdata('inputdata'))
		{
			$allinput=$this->session->userdata('inputdata');
			$this->session->unset_userdata('inputdata');
		}

		if($this->session->userdata('wine_ids')){
			$this->session->unset_userdata('wine_ids');
		}
       
      ?>

	  
	<div class="col-sm-6">

      	<fieldset>
    		<legend>Basic Info</legend>
	      	
    		<input type="hidden" name="user_type" value="brand_wise_users">
		  	<div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">First Name</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="first_name" class="form-control nf" id="inputFirstName" placeholder="Enter first name" value="<?php if(isset($allinput['first_name'])){echo $allinput['first_name'];} ?>" autocomplete="off" required>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

			<div class="form-group">
		  		<label for="inputLastName" class="col-sm-3 control-label">Last Name</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="last_name" class="form-control nf" id="inputLastName" placeholder="Enter last name" value="<?php if(isset($allinput['last_name'])){echo $allinput['last_name'];} ?>" autocomplete="off" required>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputEmail" class="col-sm-3 control-label">Email address</label>
		  		<div class="col-sm-7">
			  		<input type="email" name="email" class="form-control" id="inputEmail" placeholder="Enter email address" value="<?php if(isset($allinput['email'])){echo $allinput['email'];} ?>" required autocomplete="off">
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputPassword" class="col-sm-3 control-label">Password</label>
		  		<div class="col-sm-7">
			  		<!-- <input type="password" name="password"   class="form-control" id="inputPassword" placeholder="Enter password" required autocomplete="off">
			  		<div class="help-block with-errors"></div> -->

					  <input class="form-control" type="password" id="inputPassword" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" placeholder="Enter password" autocomplete="off" required>
			  		<!-- <div class="help-block with-errors"></div> -->

					  <div id="message">
					  <p >Password must contain the following:</p>
					<div><p id="letter" class="invalid">A lowercase letter</p><p id="capital" class="invalid">A capital (uppercase) letter</p><p id="number" class="invalid">A number</p><p id="length" class="invalid">Minimum 8 characters</p></div>
                 </div>

			  	</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputConfirmPassword" class="col-sm-3 control-label">Confirm Password</label>
		  		<div class="col-sm-7">
		  			<input type="password" name="c_password" class="form-control" id="inputConfirmPassword" data-match="#inputPassword" data-match-error="Whoops, these don't match" placeholder="Confirm password" required autocomplete="off">
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
			  		<button type="submit" class="btn btn-primary" id="submit_button"><span class="glyphicon glyphicon-ok-sign"></span> Save User</button> or <a href="<?php echo base_url('App/users');?>">Cancel</a>
			  	</div>
		  	</div>
	  	</fieldset>
	</div>

	<div class="col-sm-6">
		<fieldset>
    		<legend>Others Info</legend>

    		<!-- <div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Phone</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="meta[phone]" data-minlength="10" maxlength="14" class="form-control nuf" id="phone" placeholder="Enter phone number" value="<?php if(isset($allinput['phone'])){echo $allinput['phone'];} ?>">
			  		<div class="help-block with-errors">14 digit phone number</div>
			  	</div>
		  	</div>
    		<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Address</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="meta[adress]"  class="form-control" id="home_adress" placeholder="Enter address" value="<?php if(isset($allinput['adress'])){echo $allinput['adress'];} ?>" required autocomplete="off">
			  		<div class="help-block with-errors">Address</div>
			  	</div>
		  	</div> -->

		<div class="form-group">
			<label for="inputFirstName" class="col-sm-3 control-label">Sales Rep*</label>
			<!-- <input type="hidden" id="hidden_sales_rep_id" value="<?php if(isset($allinput['user_id']))
			{ echo $allinput['user_id'];}?>"> -->
			<div class="col-sm-7">
					
					<select name="user_id"  required class="form-control">
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
			<label for="inputPhone" class="col-sm-3 control-label">Select Brand(s)*</label>
			<div class="col-sm-7">
            <select id="wines" name="wine_id[]" class="selectpicker" multiple data-live-search="true" data-live-search-placeholder="Search" data-actions-box="true" required>
              <?php
               
                foreach($wine as $value){
              ?>
			  <option value="<?php echo $value->id;?>" <?php if(isset($allinput['wine_id']) && in_array($value->id,$allinput['wine_id'])){echo 'selected';} ?>><?php echo $value->name;?></option>
                
              <?php } ?>
            </select>
			</div>
          </div>

    	</fieldset>
	</div>
	<?php echo form_close();?>
</div>

<link rel="stylesheet" href="<?php echo base_url()?>assets/css/chosen.css">
<script src="<?php echo base_url()?>assets/js/chosen.jquery.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/sweetalert.min.js"></script> 
<script src="<?php echo base_url();?>assets/js/bootstrap-select.js"></script>
<script type="text/javascript">
	$(".chosen-select").chosen();
	
</script>
<script>
	$(document).ready(function(){
		//autocomplete off 
		$( document ).on( 'focus', ':input', function(){
			$( this ).attr( 'autocomplete', 'off' );
		});

		$("form").submit(function(e){
            e.preventDefault();
            
            if(!$("#submit_button").hasClass("disabled"))
            {
                var c=confirm('Are you save these details');
				
                if(c==true)
                {
				 if($('#wines').val()==null){
						$('#wines').trigger('chosen:activate');
						swal("Oops!", "Select at least one Wine.", "warning");
						return false;
					}else{
						$('form').unbind('submit').submit();
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

$(".nuf").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything

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