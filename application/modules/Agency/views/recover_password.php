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
      <script type="text/javascript" src="<?php echo HTTP_JS_PATH; ?>html5shiv.js"></script>
      <script type="text/javascript" src="<?php echo HTTP_JS_PATH; ?>respond.min.js"></script>
    <![endif]-->

	<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="<?php echo HTTP_JS_PATH; ?>bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo HTTP_JS_PATH; ?>plugins/validator.min.js"></script>
  </head>

  <body>
    <div class="container">
    	<div id="recoverbox" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
         <!-- Logo //Start-->
    	<h1 class="site-logo"></h1>
    	<!-- Logo //end-->
        	<div class="panel panel-default">
            	<div class="panel-heading">
                	<div class="panel-title"><?php echo $this->lang->line('auth_recover_password_box_heading')?></div>
                </div>
                <div style="padding-top:30px" class="panel-body">
                	<?php
			        	echo validation_errors();
			        ?>
			        <?php if(isset($error)):?>
			        <div class="alert alert-danger">
			        	<?php echo $error;?>
			        </div>
			        <?php endif;?>

                    <form role="form" data-toggle="validator" method="post" action="<?php echo base_url('Agency/recover_password'); ?>">
                    	<div class="form-group has-feedback">
	                    	<div class="input-group">
	                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
	                            <input type="password" class="form-control" name="new_pwd" id="inputPassword" data-minlength="6"  placeholder="<?php echo $this->lang->line('auth_recover_password_form_password_placeholder')?>" required autofocus>
	                        </div>
	                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
    						<div class="help-block with-errors"><?php echo $this->lang->line('auth_recover_password_form_password_hint')?></div>
  						</div>

	                    <div class="form-group has-feedback">
	                        <div class="input-group">
	                        	<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
	                            <input type="password" class="form-control" name="re_new_pwd" id="inputConfirmPassword" data-match="#inputPassword" data-match-error="<?php echo $this->lang->line('auth_recover_password_form_confirm_password_validation_msg')?>" placeholder="<?php echo $this->lang->line('auth_recover_password_form_confirm_password_placeholder')?>" required >
	                        </div>
							<span class="glyphicon form-control-feedback" aria-hidden="true"></span>
    						<div class="help-block with-errors"></div>
							<input type="checkbox" onclick="showPassword();" id="check"><label for="check">Show Password</label>
  						</div>

                        <div class="form-group">
                        	<!-- Button -->
                            <button id="btn-login" class="btn btn-primary"><span class="glyphicon glyphicon-ok-sign"></span> <?php echo $this->lang->line('auth_recover_password_submit_button')?></button>
                        </div>
                        <input type="hidden" name="user_id" value="<?php echo $this->uri->segment(4);?>">
						<input type="hidden" name="key" value="<?php echo $this->uri->segment(3);?>"  /> 
                        <div class="form-group">
                            <div class="form-footer">
                               	<?php echo $this->lang->line('auth_recover_password_account_text')?>
                            	<a href="<?php echo base_url('auth'); ?>"><?php echo $this->lang->line('auth_recover_password_login_link_text')?></a>
                            </div>
                    	</div>
                   </form>
				</div>
        	</div>
        </div>

    </div> <!-- /container -->

    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="<?php echo HTTP_JS_PATH; ?>bootstrap.min.js"></script>
	<script>
		function showPassword(){
			var field1 = document.getElementById("inputPassword");
			var field2 = document.getElementById("inputConfirmPassword");
			if (field1.type === "password") {
				field1.type = "text";
				field2.type = "text";
			} else {
				field1.type = "password";
				field2.type = "password";
			}
		}
	</script>
  </body>
</html>
