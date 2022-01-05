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
    	<div id="passwordbox" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <!-- Logo //Start-->
        <h1 class="site-logo"></h1>
        <!-- Logo //end-->
            <div class="panel panel-default">
                <div class="panel-heading">
                  <div class="panel-title"><?php echo $this->lang->line('auth_forgot_password_box_heading')?></div>
                </div>
                <div class="panel-body">
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

                    <form role="form" data-toggle="validator" method="post" action="<?php echo base_url('Agency/forgot_password'); ?>">
                    	<div class="form-group has-feedback">
	                    	<div class="input-group">
	                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
	                            <input type="email" class="form-control" name="email" placeholder="<?php echo $this->lang->line('auth_forgot_form_email_placeholder')?>" required autofocus>
	                        </div>
	                    	<span class="glyphicon form-control-feedback" aria-hidden="true"></span>
    						          <div class="help-block with-errors"></div>
  						          </div>

                        <div class="form-group">
                        	<!-- Button -->
                            <button id="btn-login" class="btn btn-primary"><span class="glyphicon glyphicon-ok-sign"></span> <?php echo $this->lang->line('auth_forgot_password_submit_button')?></button>
                        </div>

                        <div class="form-group">
                            <div class="form-footer">
                            	<?php echo $this->lang->line('auth_forgot_password_account_text')?>

                                <a href="<?php echo base_url('auth'); ?>"><?php echo $this->lang->line('auth_forgot_password_login_link_text')?></a>
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
  </body>
</html>
