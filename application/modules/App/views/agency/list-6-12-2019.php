<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-user"></span> Agency Management</h1>

        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li class="active"><a href="<?php echo base_url('App/agency');?>"><span class="glyphicon glyphicon-user"></span> Agency</a></li>
    			<li><a href="<?php echo base_url('App/agency/add');?>"><span class="glyphicon glyphicon-plus-sign"></span>Add Agency</a></li>
    		</ul>
        </div>
    </div>
</div>

<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li class="active">Agency Management</li>
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
	<!-- Search Agency -->
	<?php
		/*echo validation_errors();

		$attributes = array('class' => 'form-inline search-form', 'id' => 'product-search-form', 'role' => 'form');
		echo form_open(base_url('App/agency/search_submit'), $attributes);
	?>

	<fieldset>
		<div class="row"><div class="col-md-12"><legend><span class="glyphicon glyphicon-filter"></span> Filters</legend></div></div>
        <div class="row" style="padding:0 0 20px;">
        
				<div class="col-md-10">

			<div class="col-md-2">
				<div class="form-group">
					<label for="inputName">Agency Name </label><br />
					<input type="text" class="form-control" id="inputName" name="name" placeholder="Search here" value="<?php if (isset($filters['name'])) {echo str_replace("+"," ",$filters['name']);}?>" >
				</div>
			</div>
			
			<div class="col-md-10">
				<div class="form-group">
					<label>&nbsp;</label><br />
					<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>&nbsp;
					<button type="button" class="btn btn-default" onclick="window.location='<?php echo base_url('App/agency');?>'"><span class="glyphicon glyphicon-refresh"></span> Reset</button>
				</div>
			</div>
		</div>
        <div class="col-md-2">
        <!-- Display records by limit-->
        <select name="view" id="view" style="min-height:31px; margin:28px 0 0 0; float:right;">
            <option value="10" <?php echo (isset($filters['view']) && ($filters['view'] == '10') ? 'selected' : ''); ?>>10</option>
            <option value="20" <?php echo (isset($filters['view']) && ($filters['view'] == '20') ? 'selected' : ''); ?>>20</option>
            <option value="50" <?php echo (isset($filters['view']) && ($filters['view'] == '50') ? 'selected' : ''); ?>>50</option>
            <option value="100" <?php echo (isset($filters['view']) && ($filters['view'] == '100') ? 'selected' : ''); ?>>100</option>
            <option value="500" <?php echo (isset($filters['view']) && ($filters['view'] == '500') ? 'selected' : ''); ?>>500</option>
        </select>
       <!-- End --->
       </div>
		</div>
<div class="row"><div class="col-md-12"></div></div>
	</fieldset>
	<?php echo form_close();*/?>
    
	<!--<ul class="nav nav-tabs">
		<li <?php if ( $filters['status'] == "") {echo 'class="active"';}?>><a href="<?php echo base_url('App/agency');?>">All Users</a></li>
	    <li <?php if ($filters['status'] <> "") {echo 'class="active"';}?>><a href="<?php echo base_url('App/agency/index/status/inactive');?>">In-active</a></li>
		
	</ul>-->
	<?php
		echo validation_errors();

		$attributes = array('class' => 'form-inline status-form', 'id' => 'user-status-form');
		echo form_open(base_url('App/agency/update_status'), $attributes);
	?>
	<div class="table-responsive">
		<!-- Table -->
	    <table class="table table-striped table-responsive" width="100%" id="user-table">
	    	<thead>
	    		<tr>
	          		<th><input type="checkbox" id="checkall"></th>
	          		<th>Name</th>
	          		<th>Email</th>
	          		<th>Last Login</th>
	          		<th>Status</th>
	          		<th>View details</th>
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
	            		<a class="btn btn-info btn-xs" href="javascript:void(0)" title="View" onclick="open_modal(<?php echo $item->id;?>)">
	            			<span class="glyphicon glyphicon-eye-open"></span> View
	            		</a>
	            	</td>
	            	<td>
						
	            		<a class="btn btn-primary btn-xs" href="<?php echo base_url('App/agency/edit/'.$item->id);?>" title="Edit">
	            			<span class="glyphicon glyphicon-edit"></span> Edit
	            		</a>
	            		<!--<a class="btn btn-danger btn-xs" href="<?php echo base_url('App/agency/delete/'.$item->id);?>" onclick="return confirm('Are you sure you want to delete this user account?');" title="Delete">
	            			<span class="glyphicon glyphicon-trash"></span> Delete
	            		</a>-->
                        <a class="btn btn-danger btn-xs delete_button" href="javascript:void(0)" title="Delete" data-id="<?php echo $item->id;?>">
	            			<span class="glyphicon glyphicon-trash"></span> Delete
	            		</a>
	            	</td>
	            </tr>
	            <?php } ?>
	        </tbody>
	        <!--<tfoot>
				<tr>
                	<td colspan="8">
						With selected
						<button type="submit" name="operation" value="active" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-ok-circle"></span> Activate</button>
						<button type="submit" name="operation" value="inactive" class="btn btn-sm btn-warning"><span class="glyphicon glyphicon-ban-circle"></span> Deactivate</button>
						<button type="submit" name="operation" value="delete" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete the user account(s)?')"><span class="glyphicon glyphicon-trash"></span> Delete</button>
					</td>
				</tr>
			</tfoot>-->
	    </table>
        <table>
            <tr>
                	<td colspan="8">
						With selected
						<button type="submit" name="operation" value="active" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-ok-circle"></span> Activate</button>
						<button type="submit" name="operation" value="inactive" class="btn btn-sm btn-warning"><span class="glyphicon glyphicon-ban-circle"></span> Deactivate</button>
						<button type="submit" name="operation" value="delete" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete the user account(s)?')"><span class="glyphicon glyphicon-trash"></span> Delete</button>
					</td>
				</tr>
        </table>
	</div>
	<?php echo form_close();?>

	<?php //echo $this->pagination->create_links(); ?>
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
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog" id="div_result"></div>
</div>
<!-- Delete modal-->
<div id="myDeleteModal" class="modal fade" role="dialog">
  <div class="modal-dialog" id="div_result_delete">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Delete Agency</h4>
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
<script type="text/javascript">
	function open_modal(user_id)
	{
		$.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/agency/view_user_details/",
		   data: {user_id:user_id},
		   success:function(data){
		    $("#div_result").html(data);
		    
		    $('#myModal').modal('show');
		   }
		}); 
	}
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
                window.location.href = "<?php echo base_url('App/agency/delete/'); ?>"+del_id;
            }
            
	});

    $('#delete').click(function(e){
        e.preventDefault();
       var m_del_id= $('#delete').data('id');

       window.location.href = "<?php echo base_url('App/agency/temp_delete/'); ?>"+m_del_id;

    });
    // View page record by limit
	$('#view').on('change', function() {
		var view = $(this).val();
		window.location.href = base_url+"App/agency/index/view/"+view;
	});
</script>
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