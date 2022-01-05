<div class="container">
	<hr>

	<footer>
		&copy; <?php echo date("Y");?> <?php echo $this->lang->line('template_footer_copyright');?>
		<br><small>Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></small>

		<p class="navbar-text navbar-right"><?php echo $this->lang->line('template_footer_powered');?> <a href="#">WebQSolutions.com</a></p>
	</footer>
</div>
