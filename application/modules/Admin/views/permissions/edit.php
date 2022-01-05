<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-saved"></span> Edit Permission &raquo; <small> <?php echo $permission->name;?></small></h1>
    	
        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li><a href="<?php echo base_url('admin/permissions');?>"><span class="glyphicon glyphicon-saved"></span> Permissions</a></li>
    			<li><a href="<?php echo base_url('admin/permissions/add');?>"><span class="glyphicon glyphicon-plus-sign"></span> Create New Permission</a></li>
    			<li><a href="<?php echo base_url('admin/permissions/matrix');?>"><span class="glyphicon glyphicon-th"></span> Permission Matrix</a></li>
    		</ul>
        </div>
    </div>
</div>

<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li><a href="<?php echo base_url('admin/permissions');?>">Manage Permission</a></li>
		<li class="active">Edit Permission</li>
	</ol>
	
	<div class="col-sm-9">		
		<?php
			//form validation
			echo validation_errors();

			$attributes = array('class' => 'form-horizontal', 'id' => '', 'role' => 'form', 'data-toggle' => 'validator');
			echo form_open(base_url('admin/permissions/edit/'.$permission->id), $attributes);
      	?>
	  	<div class="form-group">
	  		<label for="inputFullName" class="col-sm-3 control-label">Name</label>
	  		<div class="col-sm-7">
	  			<input type="text" name="name" class="form-control" id="inputFullName" placeholder="Enter name" value="<?php echo $permission->name;?>" required>
	  			<div class="help-block with-errors"></div>
	  		</div>
	  	</div>

	  	<div class="form-group">
	  		<label for="inputDesc" class="col-sm-3 control-label">Description</label>
	  		<div class="col-sm-7">
		  		<input type="text" name="description" class="form-control" id="inputDesc" placeholder="Enter description" value="<?php echo $permission->description;?>" >
		  		<div class="help-block with-errors"></div>
		  	</div>
	  	</div>

	  	<div class="form-group">
	  		<label for="" class="col-sm-3 control-label">Status</label>
	  		<div class="col-sm-7">
		  		<div class="radio">
				  <label for="checkboxActive">
				    <input type="radio" name="status" id="checkboxActive" value="active" <?php if($permission->status != "inactive") echo "checked";?>>
				    Active
				  </label>
				</div>
				<div class="radio">
				  <label for="checkboxinactive">
				    <input type="radio" name="status" id="checkboxinactive" value="inactive" <?php if($permission->status == "inactive") echo "checked";?>>
				    In-active
				  </label>
				</div>
			</div>
		</div>
		
	  	<div class="form-group">
	  		<div class="col-sm-offset-3 col-sm-6">
		  		<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-ok-sign"></span> Save Permission</button> or <a href="<?php echo base_url('admin/permissions');?>">Cancel</a>
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
		    	
		    	<p><a class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this permission?')" href="<?php echo base_url('admin/permissions/delete/'.$permission->id);?>"><span class="glyphicon glyphicon-trash"></span> Delete this Permission</a></p>
		  	</div>
		</div>
	</div>
</div>