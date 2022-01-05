<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-star"></span> Edit Role &raquo; <small> <?php echo $role->role_name;?></small></h1>
    	
        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li><a href="<?php echo base_url('admin/roles');?>"><span class="glyphicon glyphicon-star"></span> Roles</a></li>
    			<li><a href="<?php echo base_url('admin/roles/add');?>"><span class="glyphicon glyphicon-plus-sign"></span> Create New Role</a></li>
    			<li><a href="<?php echo base_url('admin/permissions/matrix');?>"><span class="glyphicon glyphicon-th"></span> Permission Matrix</a></li>
    		</ul>
        </div>
    </div>
</div>

<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li><a href="<?php echo base_url('admin/roles');?>">Manage Roles</a></li>
		<li class="active">Edit Role</li>
	</ol>
	
	<div class="col-sm-9">		
		<?php
			//form validation
			echo validation_errors();

			$attributes = array('class' => 'form-horizontal', 'id' => '', 'role' => 'form', 'data-toggle' => 'validator');
			echo form_open(base_url('admin/roles/edit/'.$this->uri->segment(4)), $attributes);
      	?>
	  	
	  	<div class="form-group">
	  		<label for="inputName" class="col-sm-3 control-label">Role Name</label>
	  		<div class="col-sm-7">
	  			<input type="text" name="role_name" class="form-control" id="inputName" placeholder="Enter role name" value="<?php echo $role->role_name;?>" required>
	  			<div class="help-block with-errors"></div>
	  		</div>
	  	</div>

	  	<div class="form-group">
	  		<label for="inputDesc" class="col-sm-3 control-label">Description</label>
	  		<div class="col-sm-7">
		  		<input type="text" name="description" class="form-control" id="inputDesc" placeholder="Enter description" value="<?php echo $role->description;?>" >
		  		<div class="help-block with-errors"></div>
		  	</div>
	  	</div>
		
		<div class="form-group">
			<label class="col-sm-3 control-label">Default</label>
    		<div class="col-sm-7">
      			<div class="checkbox">
        			<label>
          				<input type="checkbox" name="default" value="1" <?php if($role->default == 1) echo "checked";?>> This role should be assigned to all new users.
        			</label>
      			</div>
    		</div>
  		</div>
  
	  	<div class="form-group">
	  		<label for="" class="col-sm-3 control-label">Removable</label>
	  		<div class="col-sm-7">
		  		<div class="radio">
				  	<label>
				    	<input type="radio" name="can_delete" id="checkboxActive" value="1" <?php if($role->can_delete == 1) echo "checked";?>>
				    	Yes
				  	</label>
				</div>
				<div class="radio">
				  	<label>
				    	<input type="radio" name="can_delete" id="checkboxinactive" value="0" <?php if($role->can_delete == 0) echo "checked";?>>
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
		<div class="panel panel-default">
			<div class="panel-heading">
    			<h3 class="panel-title">Audit Data</h3>
  			</div>
		  	<div class="panel-body">
		    	<p><span class="glyphicon glyphicon-info-sign"></span> Last Updated on: </p>
		    		<?php if (!is_null($role->updated_on)) {?>
			    		<small><?php echo datetime_display($role->updated_on);?></small> 
			    		by <small><?php echo $role->updated_by_name;?></small>
		    		<?php } else {echo "N/A";}?>
		    	<p class="text-center">&nbsp;</p>
		    	
		    	<p><span class="glyphicon glyphicon-info-sign"></span> Created on:</p>
		    		<?php if (!is_null($role->created_on)) {?>
			    		<small><?php echo datetime_display($role->created_on);?></small> 
			    		by <small><?php echo $role->created_by_name;?></small>
			    	<?php } else {echo "N/A";}?>
		    		
		    	<p class="text-center">&nbsp;</p>
		    	
		    	<p><a class="btn btn-sm btn-danger <?php echo ($role->can_delete == 0)?'disabled':'';?>" onclick="return confirm('Are you sure you want to delete this role?')" href="<?php echo base_url('admin/roles/delete/'.$role->id);?>"><span class="glyphicon glyphicon-trash"></span> Delete this Role</a></p>
		  	</div>
		</div>
	</div>
</div>