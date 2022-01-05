
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php if($this->session->userdata('role')=='agency'){echo base_url('agency/dashboard');}else if($this->session->userdata('role')=='brand_wise_users'){echo base_url('App/billing/get_expenses_brandwise');}else{echo base_url('dashboard');}?>"><?php echo $this->lang->line('app_site_name');?></a>
		</div>

		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<?php echo $this->session->userdata('navmenu');?>
      		</ul>
      		<ul class="nav navbar-nav navbar-right">
				<li class="divider-vertical"></li>
				<li class="dropdown">
					<?php
						$id=$this->session->userdata('id');
						
						$name=get_username($this->session->userdata('role'),$id);
						
					?>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php //echo $this->session->userdata('name');

					echo $name;
					?> <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li <?php echo $page=='profile'?'class="active"':''?>><a href="<?php if($this->session->userdata('role')=='agency'){echo base_url('Agency/profile');}else if($this->session->userdata('role')=='brand_wise_users'){echo base_url('App/users/profile');}else{echo base_url('profile');}?>"><span class="glyphicon glyphicon-lock"></span> Profile</a></li>
							<li role="separator" class="divider"></li>
							<?php
							$role_type = $this->session->userdata('role');
							if($role_type =='super_administrator'){
								?>
							<li><a href="<?php echo base_url('master_password');?>"><i class="glyphicon glyphicon-lock"></i> Master Password</a></li>
							<li role="separator" class="divider"></li>
							<?php
							}else if($role_type =='administrator'){ ?>
								<li><a href="<?php echo base_url('master_password');?>"><i class="glyphicon glyphicon-lock"></i> Master Password</a></li>
								<li role="separator" class="divider"></li>
							<?php } ?>
							<!--<li><a href="<?php if($this->session->userdata('role')!='agency'){echo base_url('Auth/logout');}else{echo base_url('Agency/logout');}?>"><span class="glyphicon glyphicon-off"></span> Logout</a></li>-->
							<li><a href="<?php echo base_url('Auth/logout');?>"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>