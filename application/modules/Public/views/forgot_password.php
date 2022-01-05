<section class="body-container">
  <div class="container">
  <div class="page-header">
            <h1 class="text-center"><?php echo $this->lang->line('auth_forgot_password_box_heading')?></h1>
        </div>
    <div class="card card-container"> <img id="profile-img" class="profile-img-card" src="<?php echo base_url();?>assets/images/profile-icon.png" />
      <h1 class="signup-heading"><span>Reset your password</span></h1>
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
                <form role="form" class="form-signin"  data-toggle="validator" method="post" action="<?php echo base_url('forgot_password'); ?>">
        
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" class="form-control" name="email" placeholder="<?php echo $this->lang->line('auth_forgot_form_email_placeholder')?>" required autofocus>
        <div class="help-block with-errors"></div>
        <button type="submit" id="btn-login" class="btn btn-block btn-success"> <?php echo $this->lang->line('auth_forgot_password_submit_button')?></button>
      </form>
      <!-- /form -->
      <div class="etc-login-form">
        <div><span class="text-left new"><?php echo $this->lang->line('auth_forgot_password_account_text')?>
                <a href="<?php echo base_url('login'); ?>"><?php echo $this->lang->line('auth_forgot_password_login_link_text')?></a></span></div>
      </div>
      </div>
    <!-- /card-container --> 
    
  </div>
</section>

