<nav class="navbar navbar-inverse" style="margin-bottom:0; border-radius:0">
	<div class="container-fluid">
		<footer>
			<div class="navbar-collapse collapse">
				<p class="navbar-text">
					&copy; <?php echo date("Y");?> <?php echo $this->lang->line('template_footer_copyright');?>
					<br><small>Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></small>
				</p>	
				
			</div>	
		</footer>
	</div>
</nav>
