<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-user"></span> Create New User</h1>

        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li><a href="<?php echo base_url('admin/users');?>"><span class="glyphicon glyphicon-user"></span> Users</a></li>
    			<li class="active"><a href="<?php echo base_url('admin/users/add');?>"><span class="glyphicon glyphicon-plus-sign"></span> Create New User</a></li>
    		</ul>
        </div>
    </div>
</div>

<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li><a href="<?php echo base_url('admin/users');?>">User Management</a></li>
		<li class="active">Create User</li>
	</ol>

	<?php
		//form validation
		echo validation_errors();

		$attributes = array('class' => 'form-horizontal', 'id' => '', 'role' => 'form', 'data-toggle' => 'validator');
		echo form_open(base_url('admin/users/add'), $attributes);
      ?>
	<div class="col-sm-6">

      	<fieldset>
    		<legend>Basic Info</legend>
	      	<div class="form-group">
		  		<label for="inputRole" class="col-sm-3 control-label">Role</label>
		  		<div class="col-sm-7">
		  			<select name="role_id" id="inputRole" class="form-control" required>
	  					<option value="" selected>Select a Role</option>
	  					<?php foreach($roles as $role) { ?>
	  					<option data-token="<?php echo $role->role_token;?>" value="<?php echo $role->id;?>" <?php if (set_value('role_id') == $role->id) {echo 'selected';}?>><?php echo $role->role_name;?></option>
	 					<?php }?>
					</select>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">First Name</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="first_name" class="form-control" id="inputFirstName" placeholder="Enter first name" value="<?php echo set_value('first_name'); ?>" required>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

			<div class="form-group">
		  		<label for="inputLastName" class="col-sm-3 control-label">Last Name</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="last_name" class="form-control" id="inputLastName" placeholder="Enter last name" value="<?php echo set_value('last_name'); ?>" required>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputEmail" class="col-sm-3 control-label">Email address</label>
		  		<div class="col-sm-7">
			  		<input type="email" name="email" class="form-control" id="inputEmail" placeholder="Enter email address" value="<?php echo set_value('email'); ?>" required >
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputPassword" class="col-sm-3 control-label">Password</label>
		  		<div class="col-sm-7">
			  		<input type="password" name="password" data-minlength="6" class="form-control" id="inputPassword" placeholder="Enter password" required>
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputConfirmPassword" class="col-sm-3 control-label">Confirm Password</label>
		  		<div class="col-sm-7">
		  			<input type="password" name="c_password" class="form-control" id="inputConfirmPassword" data-match="#inputPassword" data-match-error="Whoops, these don't match" placeholder="Confirm password" required>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="" class="col-sm-3 control-label">Status</label>
		  		<div class="col-sm-7">
			  		<div class="radio">
					  <label for="checkboxActive">
					    <input type="radio" name="status" id="checkboxActive" value="active" <?php if(set_value('status') != "inactive") echo "checked";?>>
					    Active
					  </label>
					</div>
					<div class="radio">
					  <label for="checkboxinactive">
					    <input type="radio" name="status" id="checkboxinactive" value="inactive" <?php if(set_value('status') == "inactive") echo "checked";?>>
					    In-active
					  </label>
					</div>
				</div>
			</div>

		  	<div class="form-group">
		  		<div class="col-sm-offset-3 col-sm-6">
			  		<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-ok-sign"></span> Save User</button> or <a href="<?php echo base_url('admin/users');?>">Cancel</a>
			  	</div>
		  	</div>
	  	</fieldset>
	</div>

	<div class="col-sm-6">
		<fieldset>
    		<legend>Others Info</legend>

    		<?php
    			//print "<pre>"; print_r($user_meta);
    			foreach ($user_meta as $field) {
    				echo render_field($field, set_value($field['attributes']['name']));
    			}
    		?>
    		<!-- <div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Phone</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="phone" data-minlength="10" maxlength="10" class="form-control" id="inputPhone" placeholder="Enter phone number" value="<?php echo set_value('phone'); ?>" required>
			  		<div class="help-block with-errors">10 digit phone number</div>
			  	</div>
		  	</div>

		  	<div class="form-group hide">
		  		<label for="inputLocation" class="col-sm-3 control-label">Location</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="location" class="form-control" id="inputLocation" placeholder="Enter location" value="<?php echo set_value('location'); ?>">
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div> -->
    	</fieldset>
	</div>
	<?php echo form_close();?>
</div>
