<section class="body-container">
  <div class="container">
    <div class="page-header">
      
    </div>
    <div class="card card-container"> <img id="profile-img" class="profile-img-card" src="<?php echo base_url();?>assets/images/profile-icon.png" />
      <h1 class="signup-heading">Step 1 : <span>Enter login details</span></h1>
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
      <form id="signupform" class="" role="form" data-toggle="validator" method="post" action="<?php echo base_url('signup'); ?>">
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" class="form-control" name="email" id="inputEmail" placeholder="Enter email address" data-remote="<?php echo base_url('home/check_email');?>" required>
        <div class="help-block with-errors"></div>
        <div class="help-block">This will be used at the time of login.</div>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" class="form-control" name="password" id="inputPassword" placeholder="Enter password" required>
        <div class="help-block with-errors"></div>
        <div class="help-block">Use at least one lowercase letter, one numeral, and seven characters.</div>
        <label for="inputConfirmPassword" class="sr-only">Confirm Password</label>
        <input type="password" class="form-control" name="conf_password" id="inputConfirmPassword" data-match="#inputPassword" data-match-error="Whoops, password don't match." placeholder="Please confirm password" required>
        <div class="help-block with-errors"></div>
        <div class="help-block">This should be same as password.</div>
        <button type="submit" class="btn btn-success">Create an account</button>
      </form>
      <!-- /form --> 
      
    </div>
    <!-- /card-container --> 
    
  </div>
</section>
