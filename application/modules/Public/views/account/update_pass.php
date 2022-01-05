 <div class="container text-left">
    <div class="row profile">
		<div class="col-md-12">
            <div class="profile-content pa-5">
					<h3 class="page-heading">
						Change Password
						<span>Update your account password here </span>
					</h3>
				
					
				<p>&nbsp;</p>
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
				<div class="row">
					<div class="col-md-6 col-sm-12">
						<?php
							$attributes = array('class' => '', 'id' => 'passform', 'role' => 'form', 'data-toggle' => 'validator');
							echo form_open(base_url('account/update_password'), $attributes);
						?>
							<div class="form-group">
								<label for="inputEmail">Email</label>
								<p class="form-control-static"><?php echo $this->session->userdata('email');?></p>
							</div>

							<div class="form-group">
								<label for="inputPassword">Password</label>
								<input type="password" class="form-control" name="password" id="inputPassword" placeholder="Enter password" required>
								<div class="help-block with-errors"></div>
							</div>

							<div class="form-group">
								<label for="inputConfirmPassword">Confirm Password</label>
								<input type="password" class="form-control" name="conf_password" id="inputConfirmPassword" id="inputConfirmPassword" data-match="#inputPassword" data-match-error="Whoops, password don't match." placeholder="Please confirm password" required>
								<div class="help-block with-errors"></div>
							</div>
							<button type="submit" class="btn btn-success">Save Password</button>
						<?php echo form_close();?>
					</div>
                    <div class="col-md-6 col-sm-12"></div>
				</div>

            </div>
        </div>
    </div>
</div>
<!-- /container -->
<script>
jQuery(document).ready(function() {
	var loc = new locationInfo();
    loc.getCountries();
    $(".countries").on("change", function(ev) {
        var countryId = $(this).val()
        if (countryId != '') {
            loc.getStates(countryId);
        } else {
            $(".states option:gt(0)").remove();
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