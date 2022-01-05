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

		<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/themes/public/style.css');?>" />
		<?php echo put_headers();?>

	    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	    <!--[if lt IE 9]>
	      <script type="text/javascript" src="<?php echo HTTP_JS_PATH; ?>html5shiv.js"></script>
	      <script type="text/javascript" src="<?php echo HTTP_JS_PATH; ?>respond.min.js"></script>
	    <![endif]-->
	    <script type="text/javascript">
	    	var base_url = '<?php echo base_url();?>';
	    </script>
  	</head>
<body class="phit">

<?php $this->load->view($main_content); ?>


</body>
</html>
