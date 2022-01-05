<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-star"></span> Manage User Roles <small>Roles allow you to define the abilities that a user can have.</small></h1>
    	
        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li class="active"><a href="<?php echo base_url('admin/roles');?>"><span class="glyphicon glyphicon-star"></span> Roles</a></li>
    			<li><a href="<?php echo base_url('admin/roles/add');?>"><span class="glyphicon glyphicon-plus-sign"></span> Create New Role</a></li>
    			<li><a href="<?php echo base_url('admin/permissions/matrix');?>"><span class="glyphicon glyphicon-th"></span> Permission Matrix</a></li>
    		</ul>
        </div>
    </div>
</div>

<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li class="active">Manage User Roles</li>
	</ol>
	
	<?php 
		if($this->session->flashdata('message_type')) {
			if($this->session->flashdata('message')) {

				echo '<div class="alert alert-'.$this->session->flashdata('message_type').' alert-dismissable">';
				echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
				echo $this->session->flashdata('message');
				echo '</div>';
			}
		}
	?>
	
	<div class="row spacer-top spacer-bottom"></div>
	
	<div class="table-responsive">
		<!-- Table -->
	    <table class="table table-striped table-responsive" width="100%">
	    	<thead>
	    		<tr>
	          		<th>Role</th>
	          		<th># Users</th>
	          		<th>Description</th>
	          		<th>Default</th>
					<th>Action</th>
	          	</tr>
	        </thead>
	        <tbody>
	            <?php if (count($roles) == 0) { ?>
	            <tr>
	            	<td colspan="100%">Sorry!! No Records found.</td>
	            </tr>
	            <?php } ?>
	            <?php foreach($roles as $item) { ?>
	            <tr>
	            	<td><?php echo $item->role_name;?> <small class="text-primary">(<?php echo $item->role_token;?>)</small></td>
	            	<td><a href="<?php echo base_url('admin/users/index/role/'.$item->id);?>"><?php echo $item->no_of_users;?></a></td>
	            	<td><?php echo $item->description;?></td>
	            	<td><?php echo ($item->default)?'Yes':'';?></td>
	            	<td>
	            		<a class="btn btn-primary btn-xs" href="<?php echo base_url('admin/roles/edit/'.$item->id);?>" title="Edit">
	            			<span class="glyphicon glyphicon-edit"></span> Edit
	            		</a>
	            		<a class="btn btn-danger btn-xs <?php echo ($item->can_delete == 0)?'disabled':'';?>" href="<?php echo base_url('admin/roles/delete/'.$item->id);?>" onclick="return confirm('Are you sure you want to delete this role?');" title="Delete">
	            			<span class="glyphicon glyphicon-trash"></span> Delete
	            		</a>
	            	</td>
	            </tr>
	            <?php } ?>
	        </tbody>
	    </table>
	</div>
</div>
