<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <meta name="description" content="">
	    <meta name="author" content="">
	    <link rel="shortcut icon" href="assets/favicon.ico">
	    <title><?php echo $page_title;?></title>
		
		<?php echo put_headers();?>
			
		<link rel="stylesheet" type="text/css" href="<?php echo HTTP_THEME_PATH.'style.css';?>">
		<script type="text/javascript" src="<?php echo HTTP_JS_PATH.'application.js';?>"></script>
	    	
	    <script type="text/javascript">
	    	var base_url = '<?php echo base_url();?>';
	    </script>
        <!--script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js">
        </script>
        <script type="text/javascript">
            $(document).ready(function () {
                window.history.forward(1);
                //window.history.backward(0);
            });
        </script-->
  	</head>
<body>
<?php $this->load->view('templates/'.THEME.'/header'); ?>

<?php $this->load->view($main_content); ?>
		
<?php $this->load->view('templates/'.THEME.'/footer'); ?>

<!-- Modal -->
<div class="modal fade bs-example-modal-sm" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		        <h4 class="modal-title" id="myModalLabel">Confirm !!</h4>
		    </div>
		    <div class="modal-body">Are you sure you want to delete ?</div>
		    <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		        <button type="submit" class="btn btn-primary">Yes</button>
		    </div>
		</div>
	</div>
</div>
</body>
</html>