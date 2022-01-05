<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-th"></span> Permission Matrix</h1>
    	
        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li><a href="<?php echo base_url('admin/permissions');?>"><span class="glyphicon glyphicon-saved"></span> Permissions</a></li>
    			<li><a href="<?php echo base_url('admin/permissions/add');?>"><span class="glyphicon glyphicon-plus-sign"></span> Create New Permission</a></li>
    			<li class="active"><a href="<?php echo base_url('admin/permissions/matrix');?>"><span class="glyphicon glyphicon-th"></span> Permission Matrix</a></li>
    		</ul>
        </div>
    </div>
</div>

<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li><a href="<?php echo base_url('admin/permissions');?>">Manage Permission</a></li>
		<li class="active">Permission Matrix</li>
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
	
	<?php
		echo validation_errors();

		$attributes = array('class' => 'form-inline', 'id' => 'permission-matrix-form');
		echo form_open(base_url('admin/permissions/matrix'), $attributes);
	?>
	<div class="table-responsive">
		<!-- Table -->
	    <table class="table table-striped table-nonfluid">
	    	<thead>
	    		<tr>
	    			<th></th>
	    			<?php foreach($roles as $role) { ?>
	    				<th><?php echo $role->role_name;?></th>
	    			<?php }?>
	          		
	          	</tr>
	        </thead>
	        <tbody>
	            <?php if (count($permissions) == 0) { ?>
	            <tr>
	            	<td colspan="100%">Sorry!! No Records found.</td>
	            </tr>
	            <?php } ?>
	            <?php foreach($permissions as $permission) { ?>
	            <tr>
	            	<td><?php echo $permission->name;?> <small class="text-primary"><br><?php echo $permission->description;?></small></td>
	            	<?php foreach($roles as $role) { ?>
	            		<?php
	            			$checked = ''; 
	            			if (isset($role_permission[$role->id][$permission->id]) && $role_permission[$role->id][$permission->id] == 'Y') {
	            				$checked = ' checked';
	            			}
	            		?>
		    			<th class="text-center"><input type="checkbox" name="item_id[<?php echo $role->id;?>][<?php echo $permission->id;?>]" class="checkbox-item" <?php echo $checked;?> value="Y"></th>
	    			<?php }?>
	            </tr>
	            <?php } ?>
	        </tbody>
	        <tfoot>
				<tr>
                	<td colspan="100%">
						<button type="submit" name="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok-sign"></span> Save Changes</button>
					</td>
				</tr>
			</tfoot>
	    </table>
	</div>
	<?php echo form_close();?>
</div>