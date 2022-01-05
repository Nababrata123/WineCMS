<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-user"></span> Edit User &raquo; <small> <?php echo $user->first_name." ".$user->last_name;?></small></h1>
        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li><a href="<?php echo base_url('Agency/users');?>"><span class="glyphicon glyphicon-user"></span> Users</a></li>
    			
    		</ul>
        </div>
    </div>
</div>


<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('Agency/dashboard');?>">Dashboard</a></li>
		<li><a href="<?php echo base_url('Agency/users');?>">User Management</a></li>
		<li class="active">Edit User</li>
	</ol>

	<?php
		//form validation
		echo validation_errors();

		$attributes = array('class' => 'form-horizontal', 'id' => '', 'role' => 'form', 'data-toggle' => 'validator');
		echo form_open(base_url('agency/users/edit/'.$user->id), $attributes);
    ?>
	<div class="col-sm-6">
		<fieldset>
    		<legend>Basic Info</legend>

	      	<div class="form-group">
		  		<label for="inputRole" class="col-sm-3 control-label">Role</label>
		  		<div class="col-sm-7">
		  			<select name="role_id" id="inputRole" class="form-control" required >
	  					<option value="" selected>Select a Role</option>
	  					<?php foreach($roles as $role) { ?>
	  					<option data-token="<?php echo $role->role_token;?>" value="<?php echo $role->id;?>" <?php if ($user->role_id == $role->id) {echo 'selected';}?>><?php echo $role->role_name;?></option>
	 					<?php }?>
					</select>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">First Name</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="first_name" class="form-control" id="inputFirstName" placeholder="Enter first name" value="<?php echo $user->first_name;?>" required>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

			<div class="form-group">
		  		<label for="inputLastName" class="col-sm-3 control-label">Last Name</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="last_name" class="form-control" id="inputLastName" placeholder="Enter last name" value="<?php echo $user->last_name;?>" required>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputEmail" class="col-sm-3 control-label">Email address</label>
		  		<div class="col-sm-7">
			  		<input type="email" name="email" class="form-control" id="inputEmail" placeholder="Enter email address" value="<?php echo $user->email;?>" required readonly>
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>

		  	<!--<div class="form-group">
		  		<label for="inputPassword" class="col-sm-3 control-label">Password</label>
		  		<div class="col-sm-7">
			  		<input type="password" name="password" data-minlength="6" class="form-control" id="inputPassword" placeholder="Enter password" >
			  		<div class="help-block with-errors">Please leave the password blank, if you don't want to update.</div>
			  	</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputConfirmPassword" class="col-sm-3 control-label">Confirm Password</label>
		  		<div class="col-sm-7">
		  			<input type="password" name="c_password" class="form-control" id="inputConfirmPassword" data-match="#inputPassword" data-match-error="Whoops, these don't match" placeholder="Confirm password" >
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>-->

		  	<div class="form-group">
		  		<label for="" class="col-sm-3 control-label">Status</label>
		  		<div class="col-sm-7">
			  		<div class="radio">
					  <label for="checkboxActive">
					    <input type="radio" name="status" id="checkboxActive" value="active" <?php if($user->status != "inactive") echo "checked";?>>
					    Active
					  </label>
					</div>
					<div class="radio">
					  <label for="checkboxinactive">
					    <input type="radio" name="status" id="checkboxinactive" value="inactive" <?php if($user->status == "inactive") echo "checked";?>>
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
    			//print "<pre>"; print_r($user->meta);die;

    			foreach ($user_meta as $field) {

    				$field_name = $field['name'];
    				$value = '';
    				foreach ($user->meta as $data) {
    					//print "<pre>"; print_r($data);
    					if ($data->meta_key == $field_name) {
    						//$field_name."=".$data->meta_key."<br>";
    						$value = $data->meta_value;
    					}
    				}
    				echo render_field($field, $value);
    			}
    		?>
    	</fieldset>

    	<fieldset>
    		<legend>Audit Info</legend>
    		<p>
    			<span class="glyphicon glyphicon-info-sign"></span> Last Updated on:
		    	<?php if (!is_null($user->updated_on)) {?>
			    	<small><?php echo datetime_display($user->updated_on);?></small>
			    	by <small><?php echo $user->updated_by_name;?></small>
		    	<?php } else {echo "N/A";}?>
		    </p>

		    <p>
		    	<span class="glyphicon glyphicon-info-sign"></span> Created on:
		    	<?php if (!is_null($user->created_on)) {?>
			    	<small><?php echo datetime_display($user->created_on);?></small>
			    	by <small><?php echo $user->created_by_name;?></small>
			    <?php } else {echo "N/A";}?>
			</p>

		    <p>
		    	<span class="glyphicon glyphicon-info-sign"></span> Last Login on:
		    	<?php if (!is_null($user->last_login)) {?>
		    		<small><?php echo datetime_display($user->last_login);?></small>
		    	<?php } else {echo "N/A";}?>
		    </p>

			<p><a class="btn btn-sm btn-danger <?php echo ($user->id == $this->session->userdata('id'))?'disabled':'';?>" onclick="return confirm('Are you sure you want to delete the user account?')" href="<?php echo base_url('admin/users/delete/'.$user->id);?>"><span class="glyphicon glyphicon-trash"></span> Delete this User</a></p>
    	</fieldset>
	</div>
	<?php echo form_close();?>
</div>
<script>
	$(document).ready(function(){
		//autocomplete off 
		$( document ).on( 'focus', ':input', function(){
			$( this ).attr( 'autocomplete', 'off' );
		});
	});
</script>