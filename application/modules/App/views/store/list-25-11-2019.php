<style>
                        .import {
                            padding-top: 27px;
                        }   
    .search-btn {
                            text-align: right;
                        }
                        
                    </style>

<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-user"></span> Store Management</h1>

        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li class="active"><a href="<?php echo base_url('App/store');?>"><span class="glyphicon glyphicon-user"></span> Store</a></li>
    			<li><a href="<?php echo base_url('App/store/add');?>"><span class="glyphicon glyphicon-plus-sign"></span>Add Store</a></li>
    		</ul>
        </div>
    </div>
</div>

<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li class="active">Store Management</li>
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

	<!-- Search store -->
<div class="row">
    <div class="col-md-6">
	<?php
		echo validation_errors();

		$attributes = array('class' => 'form-inline search-form', 'id' => 'product-search-form', 'role' => 'form');
		echo form_open(base_url('App/store/search_submit'), $attributes);
	?>

    
    
	<fieldset>
		<legend><span class="glyphicon glyphicon-filter"></span> Filters</legend>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label for="inputName">Store Name </label><br />
					<input type="text" class="form-control" id="inputName" name="name" placeholder="Search here" value="<?php if (isset($filters['name']) && $filters['name']!="~") {echo str_replace("+"," ",$filters['name']);}?>" >
				</div>
			</div>
			
			<div class="col-md-5 search-btn">
				<div class="form-group">
					<label>&nbsp;</label><br />
					<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>&nbsp;
					<button type="button" class="btn btn-default" onclick="window.location='<?php echo base_url('App/store');?>'"><span class="glyphicon glyphicon-refresh"></span> Reset</button>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">&nbsp;</div>
		</div>
	</fieldset>
	<?php echo form_close();?>
        </div>
    <div class="col-md-6">
            <fieldset>
                <legend><span class="glyphicon glyphicon-filter"></span> Import CSV</legend>
            <!-- Upload csv-->
            <form class="form-horizontal" action="<?php echo base_url('App/store/import');?>" method="post" name="uploadCSV"
            enctype="multipart/form-data">
                <fieldset>
        <div class="row">   
                <div class="col-md-6">

            <label class="control-label">Choose CSV File</label> 
            <br>
            <input type="file" name="file" id="file" accept=".csv" class="form-control" required>
            </div>
                <div class="col-md-6">
                    
                <div class="import">
                <input type="submit"  name="importSubmit"  class="btn-submit btn btn-primary form-control" value="Import">
                    </div>
            </div>
        </div>
            
                <div id="labelError"></div>
                </fieldset>
           </form>
                
        </fieldset>
    
        </div>
</div> 


	<ul class="nav nav-tabs">
		<li <?php if ($filters['status'] == "") {echo 'class="active"';}?>><a href="<?php echo base_url('App/Store');?>">All Stores</a></li>
	    <li <?php if ($filters['status'] <> "") {echo 'class="active"';}?>><a href="<?php echo base_url('App/Store/index/status/inactive');?>">In-active</a></li>
		
	</ul>
	<?php
		echo validation_errors();

		$attributes = array('class' => 'form-inline status-form', 'id' => 'user-status-form');
		echo form_open(base_url('App/store/update_status'), $attributes);
	?>
	<div class="table-responsive">
		<!-- Table -->
	    <table class="table table-striped table-responsive" width="100%">
	    	<thead>
	    		<tr>
	          		<th><input type="checkbox" id="checkall"></th>
	          		<th>Name</th>
	          		<th>City</th>
	          		<th>Status</th>
					<th>Action</th>
	          	</tr>
	        </thead>
	        <tbody>
	            <?php if (count($stores) == 0) { ?>
	            <tr>
	            	<td colspan="100%">Sorry!! No Records found.</td>
	            </tr>
	            <?php } ?>
	            <?php foreach($stores as $item) { ?>
	            <tr>
	            	<td><input type="checkbox" name="item_id[<?php echo $item->id;?>]" class="checkbox-item" value="Y"></td>
	            	<td><?php echo $item->name ;?></td>
	            	<td><?php if($item->city!=''){echo $item->city;}?></td>
	            	
					
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
						
	            		<a class="btn btn-primary btn-xs" href="<?php echo base_url('App/Store/edit/'.$item->id);?>" title="Edit">
	            			<span class="glyphicon glyphicon-edit"></span> Edit
	            		</a>
	            		<!--<a class="btn btn-danger btn-xs" href="<?php echo base_url('App/Store/delete/'.$item->id);?>" onclick="return confirm('Are you sure to delete the store?');" title="Delete">
	            			<span class="glyphicon glyphicon-trash"></span> Delete
	            		</a>-->
                        <a class="btn btn-danger btn-xs delete_button" href="javascript:void(0)"  title="Delete" data-id="<?php echo $item->id;?>">
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
						<button type="submit" name="operation" value="delete" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete the Stores?')"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                        <button type="submit" name="operation" value="export" class="btn btn-sm btn-info">Export to csv</button>
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
        <h4 class="modal-title">Delete Store</h4>
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
                window.location.href = "<?php echo base_url('App/store/delete/'); ?>"+del_id;
            }
            
	});

    $('#delete').click(function(e){
        e.preventDefault();
       var m_del_id= $('#delete').data('id');

       window.location.href = "<?php echo base_url('App/store/temp_delete/'); ?>"+m_del_id;

    });
</script>
