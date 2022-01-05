<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-heart-empty"></span> Manage Category</h1>

        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
				<li class="active"><?php echo render_link('index', '<span class="glyphicon glyphicon-heart-empty"></span> Category');?></li>
				<li><?php echo render_link('add', '<span class="glyphicon glyphicon-plus-sign"></span> Create New Category');?></li>
    		</ul>
        </div>
    </div>
</div>

<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li class="active">Manage Category</li>
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

	<?php
		/*echo validation_errors();

		$attributes = array('class' => 'form-inline search-form', 'id' => 'product-search-form', 'role' => 'form');
		echo form_open(base_url('App/category/search_submit'), $attributes);
	?>

	<fieldset>
		<legend><span class="glyphicon glyphicon-filter"></span> Filters</legend>
		<div class="row">
			<div class="col-md-2">
				<div class="form-group">
					<label for="inputName">Category Name </label><br />
					<input type="text" class="form-control" id="inputName" name="name" placeholder="Search here" value="<?php if (isset($filters['name'])) {echo $filters['name'];}?>" >
				</div>
			</div>
			
			<div class="col-md-10">
				<div class="form-group">
					<label>&nbsp;</label><br />
					<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>&nbsp;
					<button type="button" class="btn btn-default" onclick="window.location='<?php echo base_url('App/category');?>'"><span class="glyphicon glyphicon-refresh"></span> Reset</button>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">&nbsp;</div>
		</div>
	</fieldset>
	<?php echo form_close();*/?>

	<?php
		echo validation_errors();

		$attributes = array('class' => 'form-inline status-form', 'id' => 'locations-status-form');
		echo form_open(base_url('App/category/update_status'), $attributes);
	?>

	<div class="table-responsive">
		<!-- Table -->
	    <table class="table table-striped table-responsive" width="100%" id="user-table">
	    	<thead>
	    		<tr>
					<th><input type="checkbox" id="checkall"></th>
					<th>Name</th>
					<th>Created</th>
					<th>Updated</th>
	          		<th>Status</th>
					<th>Action</th>
	          	</tr>
	        </thead>
	        <tbody>
				<?php //print "<pre>"; print_r($list); print "</pre>";	?>
	            <?php if (count($list) == 0) { ?>
	            <tr>
	            	<td colspan="100%">Sorry!! No Records found.</td>
	            </tr>
	            <?php } ?>
	            <?php foreach($list as $item) { ?>
	            <tr>
					<td><input type="checkbox" name="item_id[<?php echo $item['id'];?>]" class="checkbox-item" value="Y"></td>
					<td><?php echo $item['name'];?></td>
					<td><small>On <?php echo datetime_display($item['created_on']);?> By <?php echo $item['created_by_name'];?></small></td>
					<td><small><?php if($item['updated_on']) {?>On <?php echo datetime_display($item['updated_on']);?> By <?php echo $item['updated_by_name'];?><?php }?></small></td>
	            	<td><?php echo status_display($item['status']);?></td>
	            	<td width="15%">
						<?php
							echo render_action(array('edit', 'delete'), $item['id']);
							

						?>
	            	</td>
	            </tr>
	            <?php } ?>
	        </tbody>
			<!--<tfoot>
				<tr>
                	<td colspan="8">
						<?php echo render_buttons(array('update_status', 'delete'));?>
					</td>
				</tr>
			</tfoot>-->
	    </table>
        <table>
            <tr>
                	<td colspan="8">
						<?php echo render_buttons(array('update_status', 'delete'));?>
					</td>
				</tr>
        </table>
	</div>
	<?php echo form_close();?>

	<?php //echo $this->pagination->create_links(); ?>
</div>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#user-table').DataTable({
        
        
        "lengthMenu": [10, 20, 50, 100, 500],
       "iDisplayLength": 10,    
        
        
    });
});
</script>