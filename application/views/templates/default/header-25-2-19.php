<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php if($this->session->userdata('role')!='agency'){echo base_url('dashboard');}else{echo base_url('agency/dashboard');}?>"><?php echo $this->lang->line('app_site_name');?></a>
		</div>

		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<?php echo $this->session->userdata('navmenu');?>
      		</ul>
      		<ul class="nav navbar-nav navbar-right">
				<li class="divider-vertical"></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php echo $this->session->userdata('name');?> <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li <?php echo $page=='profile'?'class="active"':''?>><a href="<?php if($this->session->userdata('role')!='agency'){echo base_url('profile');}else{echo base_url('Agency/profile');}?>"><span class="glyphicon glyphicon-lock"></span> Profile</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php if($this->session->userdata('role')!='agency'){echo base_url('Auth/logout');}else{echo base_url('Agency/logout');}?>"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>