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
    	<h1><span class="glyphicon glyphicon-home"></span> Store Management</h1>

        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li class="active"><a href="<?php echo base_url('App/store');?>"><span class="glyphicon glyphicon-home"></span> Store</a></li>
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
  
    <div class="col-md-12">
            <fieldset>
                <legend><span class="glyphicon glyphicon-filter"></span> Import CSV</legend>
			<span style="color:#a7a4a4;">Guidelines to enter data in a CSV file:-</span>
		<ul style="font-size: 12px; color: #a7a4a4;">
			<li>Mandatory fields should not be blank in the CSV. All fields are mandatory in the CSV except the Suite/apt no, Account Number, Special Request & Phone Number field.</li>
			<li>Product Type can be either royal or myx or royal/myx in the CSV in case the store sells both Royal & Myx type of Wines.</li>
			<li>Multiple Sales Representatives should be separated by # in the CSV.</li>
			<li>Please use valid IDs and separators in the CSV. Zone IDs are mentioned in the Zone Management & the Sales Representative IDs are mentioned in the Sales Representative Management.</li>
		</ul>	
            <!-- Upload csv-->
			<!-- <form class="form-horizontal" action="<?php echo base_url('App/store/import');?>" method="post" name="uploadCSV"
				enctype="multipart/form-data"> -->
				<form class="form-horizontal" action="<?php echo base_url('App/store/import');?>" onSubmit="return confirm('Warning! Please follow these steps when adding new stores, otherwise you may create duplicate stores: \nExport the existing list, add the new stores to the list (column A should be blank), save and import the updated file.')" method="post" name="uploadCSV" enctype="multipart/form-data">
					<fieldset>
			<div class="row">   
				<div class="col-md-3">
					<label class="control-label">Choose CSV File</label> 
					<br>
					<input type="file" name="file" id="file" accept=".csv" class="form-control" required>
				</div>
				<div class="col-md-3">	
					<div class="import">
					<input type="submit"  name="importSubmit" class="btn-submit btn btn-primary form-control" value="Import">
					</div>
				</div>
				
				<div class="col-md-3">
					<label>&nbsp;</label><br />
					<button type="button" class="btn btn-success" onclick="window.location='<?php echo base_url('App/store/refresh');?>'"><span class="glyphicon glyphicon-refresh"></span> Refresh</button>
				</div>
			</div>
					<div id="labelError"></div>
				</fieldset>
			</form>		
		</fieldset>
	</div>
	</div>&nbsp;&nbsp; 


	<?php
		echo validation_errors();

		$attributes = array('class' => 'form-inline status-form', 'id' => 'user-status-form');
		echo form_open(base_url('App/store/update_status'), $attributes);
	?>

	<div class="table-responsive">
		<!-- Table -->
	    <table class="table table-striped table-responsive" width="100%" id="user-table">
	    	<thead>
	    		<tr>
	          		<th><input type="checkbox" id="checkall"></th>
	          		<th>Store Name</th>
					<th>Sales Rep</th>
	          		<th>City</th>
					<th>Address</th>
 					<th>Account No</th>
					<th>Latitude</th>
                    <th>Longitude</th>
					<th>Action</th>
	          	</tr>
	        </thead>
	       
	        <!--<tfoot>
				<tr>
                	<td colspan="8">
						With selected
						<button type="submit" name="operation" value="active" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-ok-circle"></span> Activate</button>
						<button type="submit" name="operation" value="inactive" class="btn btn-sm btn-warning"><span class="glyphicon glyphicon-ban-circle"></span> Deactivate</button>
						<button type="submit" name="operation" value="delete" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete the Stores?')"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                        <button type="submit" name="operation" value="export" class="btn btn-sm btn-info">Export to csv</button>
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
						<button type="submit" name="operation" value="delete" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete the Stores?')"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                        <button type="submit" name="operation" value="export" class="btn btn-sm btn-info">Export to csv</button>
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
		// alert('Hello');die;
        e.preventDefault();
       var m_del_id= $('#delete').data('id');

       window.location.href = "<?php echo base_url('App/store/temp_delete/'); ?>"+m_del_id;

    });

	function importAlert(){

		var c = confirm("Please follow these steps when adding new stores, otherwise you may create duplicate stores: Export the existing list, add the new stores to the list (column A should be blank), save and import the updated file.");
alert(c);die;
		if(c==true){
			// alert("Hello");die;
			window.location.href = "<?php echo base_url('App/store/import');?>";
		}
	}
	

    // View page record by limit
	$('#view').on('change', function() {
		var view = $(this).val();
		window.location.href = base_url+"App/store/index/view/"+view;
	});
</script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {

/*$('#user-table').DataTable({
	  "processing": true,
		"serverSide": true,
		"ajax":{
		 "url": "<?php echo base_url('App/store/test') ?>",
		 "dataType": "json",
		 "type": "POST",
		 "data":{  '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' }
					   },
	"columns": [
			   { "data": "" },
			  { "data": "name" },
			  { "data": "city" },
			  { "data": "status" },
			  
			 
		   ]
});*/

var from_begining='<?php echo $this->session->userdata("from_begining")?>';
//alert(from_begining);
if(from_begining=='yes')
{
	$('#user-table').DataTable({
		  "processing": true,
		"serverSide": true,
		"iDisplayLength": 50,
		"lengthMenu": [10, 20, 50, 100, 500],
		"ajax":{
			"url": "<?php echo base_url('App/store/test') ?>",
			"dataType": "json",
			"type": "POST",
		},
		"stateSave": false,
		"bSort" : false
		/* "columnDefs": [ {
			'targets': [0,3,4,5], // column index (start from 0)
			'orderable': false, // set orderable false for selected columns
		}] */
	});
}
else
{
	$('#user-table').DataTable({
		  "processing": true,
		"serverSide": true,
		"iDisplayLength": 50,
		"lengthMenu": [10, 20, 50, 100, 500],
		"ajax":{
			"url": "<?php echo base_url('App/store/test') ?>",
			"dataType": "json",
			"type": "POST",
		},
		"stateSave": true,
		"bSort" : false
		/* "columnDefs": [ {
			'targets': [0,3,4,5], // column index (start from 0)
			'orderable': false, // set orderable false for selected columns
		}] */
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