<?php /*?><div class="profile-sidebar">
	<!-- SIDEBAR USERPIC -->
	<div class="profile-userpic">
		<?php if ($this->session->userdata('picture') <> ""):?>
			<img class="img-responsive" src="<?php echo base_url(DIR_PROFILE_PICTURE_THUMB.$this->session->userdata('picture'));?>" alt="<?php echo $this->session->userdata('name');?>">
		<?php else:?>
			<img class="img-responsive" src="<?php echo base_url('assets/images/no_image_profile.jpg');?>" alt="<?php echo $this->session->userdata('name');?>">			
		<?php endif;?>
	</div>
	<!-- END SIDEBAR USERPIC -->
	<!-- SIDEBAR USER TITLE -->
	<div class="profile-usertitle">
		<div class="profile-usertitle-name">
			<?php echo $this->session->userdata('name');?>
		</div>
		<!--<div class="profile-usertitle-job">
			Developer
		</div>-->
	</div>
	<!-- END SIDEBAR USER TITLE -->
	<!-- SIDEBAR BUTTONS -->
	<div class="profile-userbuttons">
		<a href="<?php echo base_url('Auth/logout');?>" class="btn btn-danger btn-sm">Logout</a>
		<!--<button type="button" class="btn btn-danger btn-sm">Message</button>-->
	</div>
	<!-- END SIDEBAR BUTTONS -->
	<!-- SIDEBAR MENU -->
	<div class="profile-usermenu">
		<ul class="nav">
			<li <?php echo ($page->page_type=="dashboard"?'class="active"':'')?>>
				<a href="<?php echo base_url('account/dashboard');?>">
					<i class="glyphicon glyphicon-home"></i>
					Dashboard </a>
			</li>
			<li <?php echo ($page->page_type=="myaccount"?'class="active"':'')?>>
				<a href="<?php echo base_url('account/myaccount');?>">
					<i class="glyphicon glyphicon-user"></i>
					My Account </a>
			</li>
			<li <?php echo ($page->page_type=="payments"?'class="active"':'')?>>
				<a href="<?php echo base_url('account/payments');?>">
					<i class="glyphicon glyphicon-ok"></i>
					Payments </a>
			</li>
			<li <?php echo ($page->page_type=="notifications"?'class="active"':'')?>>
				<a href="<?php echo base_url('account/notifications');?>">
					<i class="glyphicon glyphicon-flag"></i>
					Notifications </a>
            </li>
            <li <?php echo ($page->page_type=="orders"?'class="active"':'');?>>
				<a href="<?php echo base_url('account/orders');?>">
					<i class=" glyphicon glyphicon-gift"></i>
					Orders </a>
            </li>
            <li <?php echo ($page->page_type=="update_password"?'class="active"':'')?>>
				<a href="<?php echo base_url('account/update_password');?>">
					<i class="glyphicon glyphicon-lock"></i>
					Change Password </a>
            </li>
            <li <?php echo ($page->page_type=="delete"?'class="active"':'')?>>
				<a href="<?php echo base_url('account/delete');?>">
					<i class="glyphicon glyphicon-off"></i>
					Delete Account </a>
			</li>
		</ul>
	</div>
	<!-- END MENU -->
</div><?php */?>
