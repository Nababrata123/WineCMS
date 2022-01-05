<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-user"></span> Taster Management from Agency</h1>

        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li class="active"><a href="<?php echo base_url('Agency/tester');?>"><span class="glyphicon glyphicon-user"></span> Taster</a></li>
    			<li><a href="<?php echo base_url('Agency/tester/add');?>"><span class="glyphicon glyphicon-plus-sign"></span>Add Taster</a></li>
    		</ul>
        </div>
    </div>
</div>

<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('Agency/dashboard');?>">Dashboard</a></li>
		<li class="active">Taster Management</li>
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
		echo validation_errors();

		$attributes = array('class' => 'form-inline search-form', 'id' => 'customer-search-form', 'role' => 'form');
		echo form_open(base_url('Agency/tester/search_submit'), $attributes);
	?>
	<fieldset>
		<legend><span class="glyphicon glyphicon-filter"></span> Filters</legend>
		<div class="row">
			<div class="col-md-12">

				<div class="form-group">
					<label for="inputType">Search By </label>
					<select name="field" id="inputField" class="form-control" onChange="updateSearchFields(this.value, '', '');"  >
						<option value="" selected>Select a field</option>
						<option value="name" <?php if ($filter['field'] == 'name') { echo "selected";}?>>Name</option>
						<option value="email" <?php if ($filter['field'] == 'email') { echo "selected";}?>>Email</option>
						
						<option value="status" <?php if ($filter['field'] == 'status') { echo "selected";}?>>Status</option>
					</select>
				</div>

				<div class="form-group" id="inputOperatorWrapper">
					<select name="operator" id="inputOperator" class="form-control" >
						<option value="" selected>Select an operator</option>
						
						<option value="contains" <?php if ($filter['ope'] == 'contains') { echo "selected";}?>>Contains</option>
						<option value="equals" <?php if ($filter['ope'] == 'equals') { echo "selected";}?>>Equals</option>
						<option value="notequal" <?php if ($filter['ope'] == 'notequal') { echo "selected";}?>>Doesn't Equal</option>
					</select>
				</div>

				<div class="form-group" id="inputSearchWrapper">
					<input type="text" class="form-control" id="inputSearch" name="q" placeholder="Search here" value="<?php if (isset($filter['q']) && $filter['q'] <> "~") {echo $filter['q'];}?>" >
				</div>

				

				

				<div class="form-group">
					<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>&nbsp;
					<button type="button" class="btn btn-default" onclick="window.location='<?php echo base_url('Agency/tester');?>'"><span class="glyphicon glyphicon-refresh"></span> Reset</button>
				</div>

				
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">&nbsp;</div>
		</div>
	</fieldset>
	<?php echo form_close();?>
	<?php
		echo validation_errors();

		$attributes = array('class' => 'form-inline status-form', 'id' => 'user-status-form');
		echo form_open(base_url('Agency/tester/update_status'), $attributes);
	?>
	<div class="table-responsive">
		<!-- Table -->
	    <table class="table table-striped table-responsive" width="100%">
	    	<thead>
	    		<tr>
	          		<th><input type="checkbox" id="checkall"></th>
	          		<th>Name</th>
	          		<th>Email</th>
	          		<th>Last Login</th>
	          		<th>Status</th>
					<th>Action</th>
	          	</tr>
	        </thead>
	        <tbody>
	            <?php if (count($users) == 0) { ?>
	            <tr>
	            	<td colspan="100%">Sorry!! No Records found.</td>
	            </tr>
	            <?php } ?>
	            <?php foreach($users as $item) { ?>
	            <tr>
	            	<td><input type="checkbox" name="item_id[<?php echo $item->id;?>]" class="checkbox-item" value="Y"></td>
	            	<td><?php echo $item->first_name . " " .  $item->last_name;?> <?php echo ($item->id == $this->session->userdata('id'))?'<span class="text-primary">(you)</span>':'';?></td>
	            	<td><?php echo $item->email;?></td>
	            	
					<td><?php echo ($item->last_login)?datetime_display($item->last_login):'--'?></td>
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
						<!--a class="btn btn-info btn-xs" href="<?php //echo base_url('App/tester/reset_pass/'.$item->id);?>" onclick="return confirm('Do you really want to reset the password for this user?');" title="Reset Password">
	            			<span class="glyphicon glyphicon-lock"></span> Reset Password
	            		</a-->
	            		<a class="btn btn-primary btn-xs" href="<?php echo base_url('Agency/tester/edit/'.$item->id);?>" title="Edit">
	            			<span class="glyphicon glyphicon-edit"></span> Edit
	            		</a>
	            		<!--<a class="btn btn-danger btn-xs <?php echo ($item->id == $this->session->userdata('id'))?'disabled':'';?>" href="<?php echo base_url('Agency/tester/delete/'.$item->id);?>" onclick="return confirm('Are you sure you want to delete this user account?');" title="Delete">
	            			<span class="glyphicon glyphicon-trash"></span> Delete
	            		</a>-->
                        <a class="btn btn-danger btn-xs delete_button <?php echo ($item->id == $this->session->userdata('id'))?'disabled':'';?>" href="javascript:void(0)"  title="Delete" data-id="<?php echo $item->id;?>">
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
						<button type="submit" name="operation" value="delete" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete the user account(s)?')"><span class="glyphicon glyphicon-trash"></span> Delete</button>
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
<!-- Delete modal-->
<div id="myDeleteModal" class="modal fade" role="dialog">
  <div class="modal-dialog" id="div_result_delete">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Delete Tester</h4>
      </div>
      <form>
      
      <div class="modal-footer">
        
        
          <button  class="btn btn-warning" id="permanent_delete">Permanent delete</button>
        
          <button   class="btn btn-primary" id="delete">Delete</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
      </form>
    </div>
  
  </div>
</div>
<!-- END MODAL-->
<script>
	<?php if (isset($filter) && $filter['field'] <> "") {?>
		updateSearchFields('<?php echo $filter['field'];?>', '<?php echo $filter['ope'];?>', '<?php echo $filter['q'];?>');
	<?php }?>
</script>
<script type="text/javascript">
	
    $(".delete_button").click(function(){
       var id=$(this).data('id');
       //alert(id);
        $('#permanent_delete').data('id', id);
        $('#delete').data('id', id);
       $('#myDeleteModal').modal('show');
    });
    
    $('#permanent_delete').click(function(e){
            e.preventDefault();
           var del_id= $('#permanent_delete').data('id');
            var c = confirm('Data will be deleted from the database and not be recovered.Are you sure you want to delete this record?');
            
            if(c==true) {
                window.location.href = "<?php echo base_url('Agency/tester/delete/'); ?>"+del_id;
            }
            
	});

    $('#delete').click(function(e){
        e.preventDefault();
       var m_del_id= $('#delete').data('id');

       window.location.href = "<?php echo base_url('Agency/tester/temp_delete/'); ?>"+m_del_id;

    });
</script>
