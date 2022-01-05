<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-user"></span> Users Management</h1>

        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li class="active"><a href="<?php echo base_url('App/users');?>"><span class="glyphicon glyphicon-user"></span> Users</a></li>
    			<li><a href="<?php echo base_url('App/users/add');?>"><span class="glyphicon glyphicon-plus-sign"></span>Add Users</a></li>
    		</ul>
        </div>
    </div>
</div>

<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li class="active">Users Management</li>
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

		$attributes = array('class' => 'form-inline status-form', 'id' => 'user-status-form');
		echo form_open(base_url('App/users/update_status'), $attributes);
	?>

	<!-- <div class="col-md-3"> -->
		<label>&nbsp;</label><br />
		<button type="button" class="btn btn-success" onclick="window.location='<?php echo base_url('App/users/refresh');?>'"><span class="glyphicon glyphicon-refresh"></span> Refresh</button><br /><br />
		<!-- </div> -->
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
	            	
					<td><?php echo $item->last_name . " " .  $item->first_name;?> <?php echo ($item->id == $this->session->userdata('id'))?'<span class="text-primary">(you)</span>':'';?></td>
	            	
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
	            		<a class="btn btn-primary btn-xs" href="<?php echo base_url('App/users/edit/'.$item->id);?>" title="Edit">
	            			<span class="glyphicon glyphicon-edit"></span> Edit
	            		</a>
	            	
                        <a class="btn btn-danger btn-xs permanent_delete" href="javascript:void(0)" title="Delete" data-id="<?php echo $item->id;?>">
	            			<span class="glyphicon glyphicon-trash"></span> Delete
	            		</a>
	            	</td>
	            </tr>
	            <?php } ?>
	        </tbody>
	      
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
        <h4 class="modal-title">Delete Users</h4>
      </div>
      <form>
      
      <div class="modal-footer">
        
        
          <button  class="btn btn-warning" id="permanent_delete">Permanent delete</button>
        
          <!-- <button   class="btn btn-primary" id="delete">Delete</button> -->
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
		   url:"<?php echo base_url(); ?>App/users/view_user_details/",
		   data: {user_id:user_id},
		   success:function(data){
		    $("#div_result").html(data);
		    
		    $('#myModal').modal('show');
		   }
		}); 
	}

	
	/*
	function taster_under_agency(id){
		$.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/agency/taster_under_agency/",
		   data: {user_id:id},
		   success:function(data){
		    $("#div_result").html(data);
		    
		    $('#myModal').modal('show');
		   }
		});
	}*/


    $(".delete_button").click(function(){
       var id=$(this).data('id');
       //alert(id);
        $('#permanent_delete').data('id', id);
        $('#delete').data('id', id);
       $('#myDeleteModal').modal('show');
    });
    
    $('.permanent_delete').click(function(e){
            e.preventDefault();
		   var del_id=$(this).data('id');
		//    alert(id);
            var c = confirm('Data will be deleted from the database and not be recovered.Are you sure you want to delete this record?');
            
            if(c==true) {
                window.location.href = "<?php echo base_url('App/users/delete/'); ?>"+del_id;
            }
            
	});

    $('#delete').click(function(e){
        e.preventDefault();
       var m_del_id= $('#delete').data('id');

       window.location.href = "<?php echo base_url('App/users/delete/'); ?>"+m_del_id;

    });
    // View page record by limit
	$('#view').on('change', function() {
		var view = $(this).val();
		window.location.href = base_url+"App/users/index/view/"+view;
	});
</script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {

    var from_begining='<?php echo $this->session->userdata("from_begining")?>';
	//alert(from_begining);
    if(from_begining=='yes')
    {
	   $('#user-table').DataTable({
        
        
        "lengthMenu": [10, 20, 50, 100, 500],
       "iDisplayLength": 10,    
        "stateSave": false,
		"bSort" : false
        
    	});
	}
	else
	{
		$('#user-table').DataTable({
        
        
        "lengthMenu": [10, 20, 50, 100, 500],
       "iDisplayLength": 10,    
        "stateSave": true,
		"bSort" : false
        
    	});
	}
});
</script>
<style>
	
	.table.dataTable thead .sorting, 
table.dataTable thead .sorting_asc, 
table.dataTable thead .sorting_desc {
    background : none;
}
</style>