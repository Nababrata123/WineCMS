<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-marker"></span> Cms Management</h1>

        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li class="active"><a href="<?php echo base_url('App/cms');?>"><span class="glyphicon glyphicon-marker"></span> Cms</a></li>
    			<li><a href="<?php echo base_url('App/cms/add');?>"><span class="glyphicon glyphicon-plus-sign"></span>Add Cms</a></li>
    		</ul>
        </div>
    </div>
</div>

<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li class="active">Cms Management</li>
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
	

	<ul class="nav nav-tabs">
		<li <?php if ($filters['status'] == "") {echo 'class="active"';}?>><a href="<?php echo base_url('App/cms');?>">All cms</a></li>
	    <li <?php if ($filters['status'] <> "") {echo 'class="active"';}?>><a href="<?php echo base_url('App/cms/index/status/inactive');?>">In-active</a></li>
		
	</ul>
	<?php
		echo validation_errors();

		$attributes = array('class' => 'form-inline status-form', 'id' => 'user-status-form');
		echo form_open(base_url('App/cms/update_status'), $attributes);
	?>
	<div class="table-responsive">
		<!-- Table -->
	    <table class="table table-striped table-responsive" width="100%">
	    	<thead>
	    		<tr>
	          		<th><input type="checkbox" id="checkall"></th>
	          		<th>Name</th>
	          		<th>Slug</th>
	          		<th>Created By</th>
	          		<th>Status</th>
					<th>Action</th>
	          	</tr>
	        </thead>
	        <tbody>
	            <?php if (count($zones) == 0) { ?>
	            <tr>
	            	<td colspan="100%">Sorry!! No Records found.</td>
	            </tr>
	            <?php } ?>
	            <?php foreach($zones as $item) { ?>
	            <tr>
	            	<td><input type="checkbox" name="item_id[<?php echo $item->id;?>]" class="checkbox-item" value="Y"></td>
	            	<td><?php echo $item->title;?></td>
	            	<td><?php echo $item->slug;?></td>
	            	<td><?php echo $item->created_by_name;?></td>
					
	            	<td>
	            	<?php
	            		if ($item->status == "active") {
	            			echo '<span class="label label-success">Active</span>';
	            		} else {
	            			echo '<span class="label label-warning">In-active</span>';
	            		}
	            	?>
	            	</td>
	            	<td>
						
	            		<a class="btn btn-primary btn-xs" href="<?php echo base_url('App/cms/edit/'.$item->id);?>" title="Edit">
	            			<span class="glyphicon glyphicon-edit"></span> Edit
	            		</a>
	            		<a class="btn btn-danger btn-xs" href="<?php echo base_url('App/cms/delete/'.$item->id);?>" onclick="return confirm('Are you sure  to delete the cms?');" title="Delete">
	            			<span class="glyphicon glyphicon-trash"></span> Delete
	            		</a>
	            	</td>
	            </tr>
	            <?php } ?>
	        </tbody>
	        <tfoot>
				<tr>
                	<td colspan="8">
						With selected
						<button type="submit" name="operation" value="active" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-ok-circle"></span> Activate</button>
						<button type="submit" name="operation" value="inactive" class="btn btn-sm btn-warning"><span class="glyphicon glyphicon-ban-circle"></span> Deactivate</button>
						<button type="submit" name="operation" value="delete" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete the cms?')"><span class="glyphicon glyphicon-trash"></span> Delete</button>
					</td>
				</tr>
			</tfoot>
	    </table>
	</div>
	<?php echo form_close();?>

	<?php echo $this->pagination->create_links(); ?>
</div>

<!-- Modal -->
<div class="modal fade bs-example-modal-lg" id="userDetailsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<!-- Remote data loads here -->
				<span class="glyphicon glyphicon-hourglass"></span> Loading please wait ...
			</div>
		</div>
	</div>
</div>
