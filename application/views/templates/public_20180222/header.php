<!-- Fixed navbar -->
<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container">
    	<div class="navbar-header">
        	<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            	<span class="sr-only">Toggle navigation</span>
            	<span class="icon-bar"></span>
            	<span class="icon-bar"></span>
            	<span class="icon-bar"></span>
          	</button>
          	<a class="navbar-brand" href="<?php echo base_url();?>"><?php echo $this->lang->line('app_site_name');?></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          	<ul class="nav navbar-nav">
            	<li <?php echo ($page->page_type=='home')?'class="active"':'';?>><a href="<?php echo base_url();?>">Home</a></li>
            	<li><a href="#">Library</a></li>
            	<li><a href="#">Programs</a></li>
            	<li <?php echo ($page->page_type=='stores')?'class="active"':'';?>><a href="<?php echo base_url('stores');?>">Stores</a></li>
				<?php if ($this->cart->total_items() > 0) {?>
					<li <?php echo ($page->page_type=='cart')?'class="active"':'';?>><a href="<?php echo base_url('stores/cart');?>" title="View cart"><span class="glyphicon glyphicon-shopping-cart"></span> Cart <span class='badge'><?php echo $this->cart->total_items();?></span></a></li>
				<?php }?>
          	</ul>
			<ul class="nav navbar-nav navbar-right">
				<?php if ($this->session->userdata('is_customer_logged_in')) {?>
					<li><a href="<?php echo base_url('account/dashboard');?>"><span class="glyphicon glyphicon-user"></span> <?php echo ellipsize($this->session->userdata('name'),15);?></a></li>
				<?php }?>
			  	<li class="dropdown">
                	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-cog"></span> <span class="caret"></span></a>
                	<ul class="dropdown-menu">
						<?php if ($this->session->userdata('is_customer_logged_in')) {?>
							<li><a href="<?php echo base_url('account/payments');?>">Payment Details</a></li>
							<li><a href="<?php echo base_url('account/notifications');?>">Notifications</a></li>
							<li role="separator" class="divider"></li>
						<?php }?>
                  		<li><a href="<?php echo base_url('page/policy');?>" <?php echo ($page->page_type=='policy')?'class="active"':'';?>>Privacy Policy</a></li>
                  		<li><a href="<?php echo base_url('page/terms');?>" <?php echo ($page->page_type=='terms')?'class="active"':'';?>>Terms of Use</a></li>
                  		<li><a href="<?php echo base_url('page/contact');?>" <?php echo ($page->page_type=='contact')?'class="active"':'';?>>Contact</a></li>
						<li><a href="<?php echo base_url('page/faq');?>" <?php echo ($page->page_type=='faq')?'class="active"':'';?>>Help</a></li>
						<?php if ($this->session->userdata('is_customer_logged_in')) {?>
							<li role="separator" class="divider"></li>
							<li><a href="<?php echo base_url('Auth/logout');?>">Logout</a></li>
						<?php }?>
                	</ul>
              	</li>
            </ul>
			<form class="navbar-form navbar-right">
            	<input type="text" class="form-control" placeholder="Search...">
				<button class="btn btn-default"><span class="glyphicon glyphicon-search"></span> </button>
          	</form>
        </div><!--/.nav-collapse -->

	</div>
</nav>

<!--
<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container">
		<div class="header clearfix">
			<nav>
				<ul class="nav nav-pills pull-right">
					<li role="presentation" ><a href="<?php echo base_url();?>">Home</a></li>
					<li role="presentation" <?php echo ($page->page_type=='about')?'class="active"':'';?>><a href="<?php echo base_url('page/about');?>">About</a></li>
					<li role="presentation" <?php echo ($page->page_type=='contact')?'class="active"':'';?>><a href="<?php echo base_url('page/contact');?>">Contact</a></li>
				</ul>
			</nav>
			<h3 class="text-muted"></h3>
		</div>
  	</div>
</nav>-->
