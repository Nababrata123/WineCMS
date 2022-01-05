<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <meta name="description" content="<?php echo $meta_description;?>">
		<meta name="keywords" content="<?php echo $meta_keyword;?>">
	    <meta name="author" content="">
	    <link rel="shortcut icon" href="assets/favicon.ico">
	    <title><?php echo $page_title;?></title>

		<?php echo put_headers();?>

		<link rel="stylesheet" type="text/css" href="<?php echo HTTP_PUBLIC_THEME_PATH.'style.1.css';?>">
		<script type="text/javascript" src="<?php echo HTTP_JS_PATH.'public.js';?>"></script>

	    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	    <!--[if lt IE 9]>
	      <script type="text/javascript" src="<?php echo HTTP_JS_PATH; ?>html5shiv.js"></script>
	      <script type="text/javascript" src="<?php echo HTTP_JS_PATH; ?>respond.min.js"></script>
	    <![endif]-->
	    <script type="text/javascript">
	    	var base_url = '<?php echo base_url();?>';
	    </script>
  	</head>
<body>
<?php $this->load->view('templates/'.PUBLIC_THEME.'/header'); ?>

<?php $this->load->view($main_content); ?>

<?php $this->load->view('templates/'.PUBLIC_THEME.'/footer'); ?>

</body>
</html>
