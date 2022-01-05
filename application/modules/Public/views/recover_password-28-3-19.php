<!DOCTYPE html>
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
    <script type="text/javascript" src="https://wine.managed.center/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://wine.managed.center/assets/js/plugins/validator.min.js"></script>

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
                              <input type="password" class="form-control" name="new_pwd"  required autofocus placeholder="Enter your password" data-minlength="6" id="pwd">
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
