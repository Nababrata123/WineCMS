<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?php echo $meta_description;?>">
<meta name="keywords" content="<?php echo $meta_keyword;?>">
<meta name="author" content="">
<title><?php echo $page_title;?></title>

<!-- Favicon-->
<!--link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url();?>assets/images/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="<?php echo base_url();?>assets/images/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url();?>assets/images/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url();?>assets/images/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url();?>assets/images/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url();?>assets/images/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url();?>assets/images/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url();?>assets/images/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url();?>assets/images/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192" href="<?php echo base_url();?>assets/images/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url();?>assets/images/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url();?>assets/images/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url();?>assets/images/favicon-16x16.png"-->
<!-- <link rel="manifest" href="<?php echo base_url();?>assets/images/manifest.json"> -->
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="<?php echo base_url();?>assets/images/ms-icon-144x144.png">

<!-- Bootstrap core CSS -->
<!-- <link href="<?php echo base_url(); ?>vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"> -->

<!-- Custom fonts for this template -->
<!-- <link href="<?php echo base_url(); ?>vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"> -->
<!-- <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,300i,400,400i,500,500i,700,700i" rel="stylesheet"> -->



<!-- Bootstrap core JavaScript --> 
<!-- <script src="<?php echo base_url(); ?>vendor/jquery/jquery.min.js"></script> 
<script src="<?php echo base_url(); ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>  -->

<!-- Plugin JavaScript --> 
<!-- <script src="<?php echo base_url(); ?>vendor/jquery-easing/jquery.easing.min.js"></script> 
<script src="<?php echo base_url(); ?>vendor/scrollreveal/scrollreveal.min.js"></script>  -->


<!-- Development Script - DONOT EDIT/REMOVE -->
<!-- <link rel="stylesheet" href="<?php echo HTTP_CSS_PATH; ?>jquery-ui.min.css" type="text/css" /> -->
<!-- <link rel="stylesheet" href="<?php echo HTTP_CSS_PATH; ?>jquery.timepicker.css" type="text/css" /> -->

<!--<script type="text/javascript" src="<?php echo HTTP_JS_PATH; ?>jquery-2.2.4.min.js"></script>-->
<!-- <script type="text/javascript" src="<?php echo HTTP_JS_PATH; ?>jquery-ui.min.js"></script> -->
<!--<script type="text/javascript" src="<?php echo HTTP_JS_PATH; ?>bootstrap.min.js"></script>-->
<!-- <script type="text/javascript" src="<?php echo HTTP_JS_PATH; ?>plugins/validator.min.js"></script> -->
<!-- <script type="text/javascript" src="<?php echo HTTP_JS_PATH; ?>plugins/jquery.timepicker.min.js"></script> -->

<!-- <script type="text/javascript" src="<?php echo HTTP_JS_PATH.'public.js';?>"></script> -->

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
	<script type="text/javascript" src="<?php echo HTTP_JS_PATH; ?>html5shiv.js"></script>
	<script type="text/javascript" src="<?php echo HTTP_JS_PATH; ?>respond.min.js"></script>
<![endif]-->
<!-- <script type="text/javascript">
	var base_url = '<?php echo base_url();?>';

	<?php if ($this->session->userdata('id')) {?>
		// show notification on load
		showNotifications();
	<?php }?>
</script> -->
<!-- /Development Script -->


<!-- Custom styles for this template -->
<link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">

</head>

<body id="page-top" class="lib">

<?php $this->load->view('templates/'.PUBLIC_THEME.'/header'); ?>

<?php $this->load->view($main_content); ?>

<?php $this->load->view('templates/'.PUBLIC_THEME.'/footer'); ?>

<!-- Notification Details Modal -->

</body>
</html>
