<style>
    .import {
        padding-top: 27px;
    }   
    .search-btn {
        text-align: right;
    }
    .cc{
        margin-right: 37px;
    }

</style>
<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-filter"></span> Manage Wine</h1>

        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
				<li><?php echo render_link('index', '<span class="glyphicon glyphicon-filter"></span> Wine');?></li>
				<li class="active"><?php echo render_link('add', '<span class="glyphicon glyphicon-plus-sign"></span> Add Wine');?></li>
			</ul>
        </div>
    </div>
</div>

<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li class="active">Wine Management</li>
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
<div class="row">
    <!--<div class="col-md-6">-->
	<?php
		/*echo validation_errors();

		$attributes = array('class' => 'form-inline search-form', 'id' => 'product-search-form', 'role' => 'form');
		echo form_open(base_url('App/wine/search_submit'), $attributes);
	?>

	<fieldset>
		<div class="row"><div class="col-md-12"><legend><span class="glyphicon glyphicon-filter"></span> Filters</legend></div></div>
		<div class="row" style="padding:0 0 20px;">
			<div class="col-md-3 cc">
				<div class="form-group">
					<label for="inputName">Wine Name </label><br />
					<input type="text" class="form-control" id="inputName" name="name" placeholder="Search here" value="<?php if (isset($filter['name'])) {echo $filter['name'];}?>" >
				</div>
			</div>
            <div class="col-md-3 cc">
				<div class="form-group">
					<label for="inputName">UPC Code </label><br />
					<input type="text" class="form-control" id="inputNameu" name="upc_code" placeholder="Search here" value="<?php if (isset($filter['upc_code'])) {echo $filter['upc_code'];}?>" >
				</div>
			</div>
			<div class="col-md-3  cc">
				<div class="form-group">
					<label for="inputName">Sampling date </label><br />
					<input type="text" class="form-control datepicker" id="inputName" name="sampling_date" placeholder="Search here" value="<?php if (isset($filter['date'])) {echo $filter['date'];}?>" >
				</div>
			</div>
			<!-- <div class="col-md-2">
				<div class="form-group">
					<label for="inputName">Sampling status </label><br />
					<select name="sampling_status" class="form-control">
						<option value="done">Done</option>
						<option value="not_done">Not done</option>
					</select>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<label for="inputName">No of bottles</label><br />
					<select name="bottles" class="form-control">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
					</select>
				</div>
			</div> -->
			<!-- Display records by limit-->
            
            <select name="view" id="view" style="min-height:31px; margin:26px 0 0 0; float:right;">
                <option value="10" <?php echo (isset($filter['view']) && ($filter['view'] == '10') ? 'selected' : ''); ?>>10</option>
                <option value="20" <?php echo (isset($filter['view']) && ($filter['view'] == '20') ? 'selected' : ''); ?>>20</option>
                <option value="50" <?php echo (isset($filter['view']) && ($filter['view'] == '50') ? 'selected' : ''); ?>>50</option>
                <option value="100" <?php echo (isset($filter['view']) && ($filter['view'] == '100') ? 'selected' : ''); ?>>100</option>
                <option value="500" <?php echo (isset($filter['view']) && ($filter['view'] == '500') ? 'selected' : ''); ?>>500</option>
            </select>
            
       <!-- End --->
			<div class="col-md-10">
				<div class="form-group">
					<label>&nbsp;</label><br />
					<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>&nbsp;
					<button type="button" class="btn btn-default" onclick="window.location='<?php echo base_url('App/wine');?>'"><span class="glyphicon glyphicon-refresh"></span> Reset</button>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">&nbsp;</div>
		</div>
        
	</fieldset>
	<?php echo form_close();*/?>
        
        
    <!--</div>-->
    <div class="col-md-6">
            <fieldset>
                <legend><span class="glyphicon glyphicon-filter"></span> Import CSV</legend>
            <!-- Upload csv-->
            <form class="form-horizontal" action="<?php echo base_url('App/wine/import');?>" method="post" name="uploadCSV"
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
	<?php
		echo validation_errors();

		$attributes = array('class' => 'form-inline status-form', 'id' => 'product-status-form');
		echo form_open(base_url('App/wine/update_status'), $attributes);
	?>

	<div class="table-responsive">
		<!-- Table -->
	    <table class="table table-striped table-responsive" width="100%" id="user-table">
	    	<thead>
	    		<tr>
	          		<th><input type="checkbox" id="checkall"></th>
					<th>Image</th>
	          		<th>Wine Name</th>
                    <th>UPC Code</th>
	          		<th>Sizes</th>
					
					<th>Created By</th>
					<th>Status</th>
					<th>View details</th>
                    <th>Action</th>
	          	</tr>
	        </thead>
	        <tbody>
	            <?php if (count($list) == 0) { ?>
	            <tr>
	            	<td colspan="100%">Sorry!! No Records found.</td>
	            </tr>
	            <?php } ?>
	            <?php foreach($list as $product) { ?>
	            <tr>
	            	<td><input type="checkbox" name="item_id[<?php echo $product->id;?>]" class="checkbox-item" value="Y"></td>
					<td>
						<?php
							
							if ($product->image == "") {
								echo '<img title="'.$product->name.'" class="img-thumbnail" style="max-width:50px; max-height:50px" class="" src="'.base_url('assets/images/dummy-wine.jpg').'">';
							} else {
								echo '<a href="'.base_url(DIR_WINE_PICTURE.$product->image).'" target="_blank"><img title="'.$product->name.'" class="img-thumbnail" style="max-width:70px; max-height:70px" src="'.base_url(DIR_WINE_PICTURE_THUMB.$product->image).'"></a>';
							}
						?>
					</td>
					<th scope="row"><?php echo character_limiter($product->name, 50);?></th>
                    <td><?php echo $product->upc_code;?></td>
					<td>
                        <?php echo (double) $product->size.''.$product->UOM; ?>
                    </td>
					<td><small><?php echo $product->created_by_name;?> on <?php echo datetime_display($product->created_on);?></small></td>
					<td><?php echo status_display($product->status);?></td>
					<td>
	            		<a class="btn btn-info btn-xs" href="javascript:void(0)" title="View" onclick="open_modal(<?php echo $product->id;?>)">
	            			<span class="glyphicon glyphicon-eye-open"></span> View
	            		</a>
	            	</td>
					<td class="text-nowrap">
						<?php 
							echo render_action(array('images', 'edit', 'delete'), $product->id);
							
						?>
                        
					</td>
	            </tr>
	            <?php } ?>
	        </tbody>
			<!--<tfoot>
				<tr>
                	<td colspan="8">
						<?php 
							echo render_buttons(array('update_status', 'delete','export'));
							
						?>
					</td>
				</tr>
			</tfoot>-->
	    </table>
        <table>
            <tr>
                	<td colspan="8">
						<?php 
							echo render_buttons(array('update_status', 'delete','export'));
							
						?>
					</td>
				</tr>
        </table>
	</div>
	<?php echo form_close();?>

	<?php //echo $this->pagination->create_links(); ?>
</div>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog" id="div_result"></div>
</div>
<script type="text/javascript">
	function open_modal(wine_id)
	{
		$.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/wine/view_details/",
		   data: {wine_id:wine_id},
		   success:function(data){
		    $("#div_result").html(data);
		    
		    $('#myModal').modal('show');
		   }
		}); 
	}
</script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
$('.datepicker').datepicker({

    format: 'yyyy-mm-dd',
    todayHighlight: true,
    autoclose: true,
    //startDate: truncateDate(new Date()) 
});
function truncateDate(date) {
  return new Date(date.getFullYear(), date.getMonth(), date.getDate());
}
// View page record by limit
$('#view').on('change', function() {
    var view = $(this).val();
    window.location.href = base_url+"App/wine/index/view/"+view;
});
</script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#user-table').DataTable({   
        "lengthMenu": [10, 20, 50, 100, 500],
       "iDisplayLength": 10,    
        "stateSave": true,
		"bSort" : false     
    });
});
</script>
<style>
	
	.table.dataTable thead .sorting, 
table.dataTable thead .sorting_asc, 
table.dataTable thead .sorting_desc {
    background : none;
}
</style>