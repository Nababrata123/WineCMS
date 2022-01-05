<section class="body-container">
  <div class="container">
    <div class="card card-container"> <img id="profile-img" class="profile-img-card" src="<?php echo base_url('assets/images/profile-icon.png'); ?>" />
      <h1 class="signup-heading">Step 2 : <span>Fill Your Details</span></h1>
      <?php
            //form validation
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
      <form id="signupform" class="" role="form" data-toggle="validator" method="post" action="<?php echo base_url('signup_step2'); ?>">
        <label for="inputFname" class="sr-only">First name</label>
        <input type="text" class="form-control" name="first_name" id="inputFname" placeholder="Enter first name" required>
        <div class="help-block with-errors"></div>
        
        <label for="inputLname" class="sr-only">Last name</label>
        <input type="text" class="form-control" name="last_name" id="inputLname" placeholder="Enter last name" required>
        <div class="help-block with-errors"></div>
        
        <label for="inputDOB" class="sr-only">Date of birth</label>
        <input type="text" class="form-control public-calender-control" name="dob" id="inputDOB" placeholder="Enter date of birth" required>
        <span class="input-group-addon glyphicon glyphicon-calendar"></span>
        <div class="help-block with-errors"></div>
        <div class="text-left"><label for="inputGender">Gender:&nbsp;&nbsp;&nbsp;&nbsp;</label>
        <label class="radio-inline">
          <input type="radio" name="gender" id="inlineRadio1" value="M" checked>
          Male &nbsp;&nbsp;</label>
        <label class="radio-inline">
          <input type="radio" name="gender" id="inlineRadio2" value="F">
          Female </label></div>
        <div class="help-block with-errors"></div>
        <label for="inputHeight" class="sr-only">Height (in feet)</label>
        <input type="text" class="form-control" name="height" id="inputHeight" placeholder="Enter height (in feet)" required>
        <div class="help-block with-errors"></div>
        <label for="inputHeight" class="sr-only">Height (in inches)</label>
        <input type="text" class="form-control" name="height_inc" id="inputHeight" placeholder="Enter height (in inches)" required>
        <div class="help-block with-errors"></div>
        <label for="inputWeight" class="sr-only">Weight (in lbs)</label>
        <input type="text" class="form-control" name="weight" id="inputWeight" placeholder="Enter weight (in lbs)" required>
        <div class="help-block with-errors"></div>
        <label for="inputCity" class="sr-only"> City</label>
        <input type="text" class="form-control" name="city" id="inputCity" placeholder="Enter city" required>
        <div class="help-block with-errors"></div>
        <label for="inputCountry" class="sr-only">Country</label>
        <select class="form-control countries" name="country" id="inputCountry" required>
          <option value="" selected>Select Country</option>
        </select>
        <div class="help-block with-errors"></div>
        <label for="inputState" class="sr-only">State</label>
        <select class="form-control states" name="state" id="inputState" required>
          <option value="" selected>Select State</option>
        </select>
        <div class="help-block with-errors"></div>
        <button type="submit" class="btn btn-success">Create an account</button>
      </form>
      <!-- /form --> 
      
    </div>
    <!-- /card-container --> 
    
  </div>
</section>
<script>
jQuery(document).ready(function() {
	var loc = new locationInfo();
    loc.getCountries();
    jQuery(".countries").on("change", function(ev) {
        var countryId = jQuery(this).val()
        if (countryId != '') {
            loc.getStates(countryId);
        } else {
            jQuery(".states option:gt(0)").remove();
        }
    });
    /*$(".states").on("change", function(ev) {
        var stateId = $(this).val()
        if (stateId != '') {
            loc.getCities(stateId);
        } else {
            $(".cities option:gt(0)").remove();
        }
	});*/
});
</script>