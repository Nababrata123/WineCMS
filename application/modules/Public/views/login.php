<section class="body-container">
	<div class="container">
		<div class="card card-container"> <img id="profile-img" class="profile-img-card" src="<?php echo base_url();?>assets/images/profile-icon.png" />
			<h1 class="signup-heading"><span>Login here</span></h1>
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
			<?php if(isset($error)):?>
			<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<?php echo $error;?> 
			</div>
			<?php endif;?>
			<form id="loginform" class="form-signin" role="form" data-toggle="validator" method="post" action="<?php echo ($redirect?base_url('do_login?redirect='.$redirect):base_url('do_login')); ?>">
				<div class="login-form-main-message"></div>
				<label for="inputEmail" class="sr-only">Email</label>
				<input type="email" id="inputEmail" class="form-control" name="email" placeholder="Enter email address" required autofocus>
				<label for="inputPassword" class="sr-only">Password</label>
				<input type="password" id="inputPassword" class="form-control" name="password" placeholder="Enter password" required>
				<div class="help-block with-errors"></div>
				<!--<div id="remember" class="checkbox">
				  <label>
					<input type="checkbox" value="remember-me">
					Remember me </label>
				</div>-->
				<input type="hidden" name="redirect" value="<?php echo $redirect;?>">
				<button type="submit" id="btn-login" class="btn">Sign in</button>
				<!--<input type="button" onclick="fb_login();" class="btn btn-block btn-primary" value="Sign in via Facebook" />-->
			</form>
			<div class="etc-login-form">
				<div><span class="text-left new"><a href="<?php echo base_url('forgot_password'); ?>">Forgot password?</a></span> <span><a href="<?php echo base_url('signup'); ?>"  class="text-right">Create an account</a></span></div>
			</div>
		</div>
		<!-- /card-container --> 
	</div>
</section>
<!-- /container --> 
