<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-star"></span> Create New Role</h1>
    	
        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li><a href="<?php echo base_url('admin/roles');?>"><span class="glyphicon glyphicon-star"></span> Roles</a></li>
    			<li class="active"><a href="<?php echo base_url('admin/roles/add');?>"><span class="glyphicon glyphicon-plus-sign"></span> Create New Role</a></li>
    			<li><a href="<?php echo base_url('admin/permissions/matrix');?>"><span class="glyphicon glyphicon-th"></span> Permission Matrix</a></li>
    		</ul>
        </div>
    </div>
</div>

<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li><a href="<?php echo base_url('admin/roles');?>">Manage Roles</a></li>
		<li class="active">Create Role</li>
	</ol>
	
	<div class="col-sm-9">		
		<?php
			//form validation
			echo validation_errors();

			$attributes = array('class' => 'form-horizontal', 'id' => '', 'role' => 'form', 'data-toggle' => 'validator');
			echo form_open(base_url('admin/roles/add'), $attributes);
      	?>
	  	
	  	<div class="form-group">
	  		<label for="inputName" class="col-sm-3 control-label">Role Name</label>
	  		<div class="col-sm-7">
	  			<input type="text" name="role_name" class="form-control" id="inputName" placeholder="Enter role name" value="<?php echo set_value('role_name'); ?>" required>
	  			<div class="help-block with-errors"></div>
	  		</div>
	  	</div>

	  	<div class="form-group">
	  		<label for="inputDesc" class="col-sm-3 control-label">Description</label>
	  		<div class="col-sm-7">
		  		<input type="text" name="description" class="form-control" id="inputDesc" placeholder="Enter description" value="<?php echo set_value('description'); ?>" >
		  		<div class="help-block with-errors"></div>
		  	</div>
	  	</div>
		
		<div class="form-group">
			<label class="col-sm-3 control-label">Default</label>
    		<div class="col-sm-7">
      			<div class="checkbox">
        			<label>
          				<input type="checkbox" name="default" value="1"> This role should be assigned to all new users.
        			</label>
      			</div>
    		</div>
  		</div>
  
	  	<div class="form-group">
	  		<label for="" class="col-sm-3 control-label">Removable</label>
	  		<div class="col-sm-7">
		  		<div class="radio">
				  	<label>
				    	<input type="radio" name="can_delete" id="checkboxActive" value="1" <?php echo set_checkbox('can_delete', '1'); ?> checked>
				    	Yes
				  	</label>
				</div>
				<div class="radio">
				  	<label>
				    	<input type="radio" name="can_delete" id="checkboxinactive" value="0" <?php echo set_checkbox('can_delete', '0'); ?>>
				    	No
				  	</label>
				</div>
				<div class="help-block with-errors">Can this role be deleted?</div>
			</div>
		</div>
		
	  	<div class="form-group">
	  		<div class="col-sm-offset-3 col-sm-6">
		  		<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-ok-sign"></span> Save Role</button> or <a href="<?php echo base_url('admin/roles');?>">Cancel</a>
		  	</div>
	  	</div>
	  	<p>&nbsp;</p>
		<?php echo form_close();?>
	</div>
	<div class="col-sm-3">
		
	</div>
</div>

