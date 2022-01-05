 <div class="container text-left">
    <div class="row profile">
		<div class="col-md-12">
            <div class="profile-content pa-5">
					<h3 class="page-heading">
						Update your Account
					</h3>

				
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
								
				<!-- Form section -->
				<?php
					$attributes = array('class' => '', 'id' => 'accountform', 'role' => 'form', 'data-toggle' => 'validator');
					echo form_open_multipart(base_url('account/myaccount'), $attributes);
				?>
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="form-group">
								<label for="inputFname">First name</label>
								<input type="text" class="form-control" autofocus name="first_name" id="inputFname" placeholder="Enter first name" value="<?php echo $profile->first_name;?>" required>
								<div class="help-block with-errors"></div>
							</div>

							<div class="form-group">
								<label for="inputLname">Last name</label>
								<input type="text" class="form-control" name="last_name" id="inputLname" placeholder="Enter last name" value="<?php echo $profile->last_name;?>" required>
								<div class="help-block with-errors"></div>
							</div>

							<div class="form-group">
								<label for="inputPic">Profile Picture</label>
								<input type="file" class="form-control" name="profile_pic" id="inputPic" placeholder="Upload profile picture" >
								<div class="help-block with-errors">Please select jpeg or png file.</div>
								<div><img src="<?php echo base_url(DIR_PROFILE_PICTURE_THUMB.$profile->profile_pic);?> "></div>
							</div>

							<div class="row">
								<div class="col-md-6 col-sm-12">
									<div class="form-group">
										<label for="inputDOB">Date of birth</label>
										<div class="input-group">
											<input type="text" class="form-control public-calender-control" name="dob" id="inputDOB" placeholder="Enter date of birth" value="<?php echo date_display($profile->dob);?>" required>
											<span class="input-group-addon glyphicon glyphicon-calendar"></span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-6 col-sm-12">
									<div class="form-group">
										<label for="inputGender">Gender</label>
										<div class="">
											<label class="radio-inline">
												<input type="radio" name="gender" id="inlineRadio1" value="M" <?php echo ($profile->gender!="F"?"checked":"");?>> Male
											</label>
											<label class="radio-inline">
												<input type="radio" name="gender" id="inlineRadio2" value="F" <?php echo ($profile->gender=="F"?"checked":"");?>> Female
											</label>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6 col-sm-12">
									<div class="form-group">
										<label for="inputHeight">Height</label>
										<div class="input-group">
											<input type="text" class="form-control" name="height" id="inputHeight" placeholder="Enter height" value="<?php echo $profile->height;?>" required>
											<span class="input-group-addon">in feet</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-6 col-sm-12">
									<div class="form-group">
										<label for="inputHeight">&nbsp; &nbsp;</label>
										<div class="input-group">
											<input type="text" class="form-control" name="height_inc" id="inputHeight" placeholder="Enter height" value="<?php echo $profile->height_inc;?>" required>
											<span class="input-group-addon">in inches</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-12 col-sm-12">
									<div class="form-group">
										<label for="inputWeight">Weight</label>
										<div class="input-group">
											<input type="text" class="form-control" name="weight" id="inputWeight" placeholder="Enter weight" value="<?php echo $profile->weight;?>" required>
											<span class="input-group-addon">in lbs</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label for="inputCity">City</label>
								<input type="text" class="form-control" name="city" id="inputCity" placeholder="Enter city" value="<?php echo $profile->city;?>" required>
								<div class="help-block with-errors"></div>
							</div>

							<div class="form-group">
								<label for="inputCountry">Country</label>
								<select class="form-control countries" name="country" id="inputCountry" required>
									<option value="" selected>Select Country</option>
								</select>
								<div class="help-block with-errors"></div>
							</div>

							<div class="form-group">
								<label for="inputState">State</label>
								<select class="form-control states" name="state" id="inputState" required>
									<option value="" selected>Select State</option>
								</select>
								<div class="help-block with-errors"></div>
							</div>

							<button type="submit" class="btn btn-success">Save Account Info</button>
						
						</div>
                        <div class="col-md-6 col-sm-12"></div>
					</div>
				<?php echo form_close();?>	
			</div>			
		</div>			
	</div>
</div>
<!-- /container -->
<script>
jQuery(document).ready(function() {
	var loc = new locationInfo();
    loc.getCountries('<?php echo $profile->country;?>');
    jQuery(".countries").on("change", function(ev) {
        var countryId = jQuery(this).val()
        if (countryId != '') {
            loc.getStates(countryId);
        } else {
            jQuery(".states option:gt(0)").remove();
        }
	});
	
	<?php if ($profile->country != "" && $profile->state != "") {?>
		loc.getStates('<?php echo $profile->country;?>', '<?php echo $profile->state;?>');
	<?php }?>
    /*jQuery(".states").on("change", function(ev) {
        var stateId = $(this).val()
        if (stateId != '') {
            loc.getCities(stateId);
        } else {
            jQuery(".cities option:gt(0)").remove();
        }
	});*/
});
</script>