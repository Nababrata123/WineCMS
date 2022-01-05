<!DOCTYPE html>

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
/* .container {
  background-color: #f1f1f1;
  /* padding: 10px; */
/* } */ 

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

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="assets/favicon.ico">
    <title><?php echo $page_title;?></title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo HTTP_CSS_PATH; ?>bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo HTTP_THEME_PATH; ?>auth.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="<?php echo HTTP_JS_PATH; ?>html5shiv.js"></script>
      <script src="<?php echo HTTP_JS_PATH; ?>respond.min.js"></script>
    <![endif]-->

  <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://karossonline.com/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://karossonline.com/assets/js/plugins/validator.min.js"></script>

  </head>

  <body>
    <div class="container">
      <div id="loginbox" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
           <!-- Logo //Start-->
      <!--<h1 class="site-logo"><a href="<?php //echo base_url();?>" title=""><img src="<?php //echo HTTP_IMAGES_PATH; ?>logo.png" alt="UNE"></a></h1>-->
      <!-- Logo //end-->
          <div class="panel panel-default">
              <div class="panel-heading">
                  <div class="panel-title">Change your password</div>
                </div>
                <div style="padding-top:30px" class="panel-body">
                  <?php
                  echo validation_errors();
                  
                  
                  if($this->session->flashdata('message_type')) {
                    if($this->session->flashdata('message')) {

                      echo '<div class="alert alert-'.$this->session->flashdata('message_type').' alert-dismissable">';
                      echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                      echo $this->session->flashdata('message');
                      echo '</div>';
                    }
                  }
                  
                  
              ?>
              <?php if(isset($error)):?>
              <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $error;?>
              </div>
              <?php endif;?>

                    <form id="loginform" class="" role="form" data-toggle="validator" method="post" action="<?php echo base_url('Public/home/recover_password'); ?>">
                      <?php
                        $user_id=$this->uri->segment(3);
                        $key=$this->uri->segment(2);
                      ?>
                      <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                      <input type="hidden" name="key" value="<?php echo $key;?>">
                      <div class="form-group has-feedback">
                        <div class="input-group">
                              <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                              <!-- <input type="password" class="form-control" name="new_pwd"  required autofocus placeholder="Enter your password" data-minlength="3"  id="pwd"> -->
                              <input class="form-control" type="password" id="pwd" name="new_pwd" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" placeholder="Enter password" autocomplete="off" >
			
                          </div>
                          <div id="message">
					  <p >Password must contain the following:</p>
					<div><p id="letter" class="invalid">A lowercase letter</p><p id="capital" class="invalid">A capital (uppercase) letter</p><p id="number" class="invalid">A number</p><p id="length" class="invalid">Minimum 8 characters</p></div>
                    </div>
                          <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                <div class="help-block with-errors"></div>
              </div>

              <div class="form-group has-feedback">
                          <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                              <input type="password" class="form-control" id="rpwd" name="re_new_pwd"  required placeholder="Confirm your password" data-match="#pwd" data-match-error="<?php echo $this->lang->line('auth_recover_password_form_confirm_password_validation_msg')?>">
                          </div>
                          <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                <div class="help-block with-errors"></div>
              </div>

                        <div class="form-group">
                          <!-- Button -->
                            <button id="btn-login" class="btn btn-primary"><span class="glyphicon glyphicon-ok-sign"></span> Submit </button>
                        </div>

                        
                   </form>
        </div>
          </div>
        </div>

      </div>
    <!-- /container -->

      <!-- Placed at the end of the document so the pages load faster -->
      <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
      <script type="text/javascript" src="<?php echo HTTP_JS_PATH; ?>bootstrap.min.js"></script>
  </body>
</html>
<script>
var myInput = document.getElementById("pwd");
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