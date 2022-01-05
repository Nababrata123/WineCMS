<div class="container">
    <div class="page-header">
		<h1>
            Thank you.
			<p><small></small></p>
		</h1>
	</div>

    <p>&nbsp;</p>
    <div class="jumbotron">
        <p>
            <?php
                if($this->session->flashdata('message')) {
				    echo $this->session->flashdata('message');
                }
            ?>
        </p>
        <p>
            <a role="button" href="<?php echo base_url('account/orders');?>" class="btn btn-lg btn-primary">View Orders</a>
        </p>
    </div>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>

</div>