<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-user"></span> Taster Management</h1>

        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li class="active"><a href="<?php echo base_url('App/tester');?>"><span class="glyphicon glyphicon-user"></span> Taster</a></li>
    			<li><a href="<?php echo base_url('App/tester/add');?>"><span class="glyphicon glyphicon-plus-sign"></span>Add Taster</a></li>
    		</ul>
        </div>
    </div>
</div>

<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
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
		echo form_open(base_url('App/tester/search_submit'), $attributes);
	?>
	<!--<fieldset>
		<legend><span class="glyphicon glyphicon-filter"></span> Filters</legend>
		<div class="row">
			<div class="col-md-10">-->

				<!--<div class="form-group">
					<label for="inputType">Search By </label>
					<select name="field" id="inputField" class="form-control" onChange="updateSearchFields(this.value, '', '');"  >
						<option value="" selected>Select a field</option>
						<option value="name" <?php if ($filter['field'] == 'name') { echo "selected";}?>>Name</option>
						<option value="email" <?php if ($filter['field'] == 'email') { echo "selected";}?>>Email</option>
						
						<option value="status" <?php if ($filter['field'] == 'status') { echo "selected";}?>>Status</option>
                        <option value="created_by" <?php if ($filter['field'] == 'created_by') { echo "selected";}?>>Created by</option>
                        <option value="zone" <?php if ($filter['field'] == 'zone') { echo "selected";}?>>Zone</option>
					</select>
				</div>-->

				<!--<div class="form-group" id="inputOperatorWrapper">
					<select name="operator" id="inputOperator" class="form-control" >
						<option value="" selected>Select an operator</option>
						
						<option value="contains" <?php if ($filter['ope'] == 'contains') { echo "selected";}?>>Contains</option>
						<option value="equals" <?php if ($filter['ope'] == 'equals') { echo "selected";}?>>Equals</option>
						<option value="notequal" <?php if ($filter['ope'] == 'notequal') { echo "selected";}?>>Doesn't Equal</option>
					</select>
				</div>-->
                <?php
                    /*if($filter['field']=='zone')
                    {
                  ?>
                <div class="form-group" id="inputSearchWrapper">
					<input type="text" class="form-control" id="inputSearch" name="q" placeholder="Search here" value="<?php if (isset($filter['q']) && $filter['q'] <> "~") {echo $filter['q'];}?>" >
                    <?php
                        $zone=get_zone_list();
                    ?>
                    <select name="q" class="form-control" id="inputSearch">
						<option value="">- Select a zone -</option>
                        <?php
                            foreach($zone as $value)
                            {
                        ?>
                        <option value="<?php echo $value->id;?>" <?php if($value->id==$filter['q']){echo "selected";}?>><?php echo $value->name;?></option>
                        <?php
                            }
                        ?>
                    </select>
				</div>
                <?php
                    }
                    else
                    {
                ?>
				<div class="form-group" id="inputSearchWrapper">
					<input type="text" class="form-control" id="inputSearch" name="q" placeholder="Search here" value="<?php if (isset($filter['q']) && $filter['q'] <> "~") {echo $filter['q'];}?>" >
				</div>
                <?php
                    }*/
                ?>

				
				

				<!--<div class="form-group">
					<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>&nbsp;
					<button type="button" class="btn btn-default" onclick="window.location='<?php echo base_url('App/tester');?>'"><span class="glyphicon glyphicon-refresh"></span> Reset</button>
				</div>
                
				
			</div>
            <div class="col-md-2">
                
                <select name="view" id="view" style="min-height:31px; margin:2px 0 0 0; float:right;">
                    <option value="10" <?php echo (isset($filter['view']) && ($filter['view'] == '10') ? 'selected' : ''); ?>>10</option>
                    <option value="20" <?php echo (isset($filter['view']) && ($filter['view'] == '20') ? 'selected' : ''); ?>>20</option>
                    <option value="50" <?php echo (isset($filter['view']) && ($filter['view'] == '50') ? 'selected' : ''); ?>>50</option>
                    <option value="100" <?php echo (isset($filter['view']) && ($filter['view'] == '100') ? 'selected' : ''); ?>>100</option>
                    <option value="500" <?php echo (isset($filter['view']) && ($filter['view'] == '500') ? 'selected' : ''); ?>>500</option>
                </select>
                
            </div>
		</div>
		<div class="row">
			<div class="col-md-6">&nbsp;</div>
		</div>
	</fieldset>-->
	<?php echo form_close();?>
    
    <!--Particular agency wise search-->
    <?php
		/*echo validation_errors();

		$attributes = array('class' => 'form-inline search-form', 'id' => 'customer-search-form2', 'role' => 'form');
		echo form_open(base_url('App/tester/search_submit_agency'), $attributes);
	?>    <fieldset>
                <div class="form-group">
					<label for="inputType">Choose agency </label>
					<select name="agency" id="inputField" class="form-control" onChange="updateSearchFields(this.value, '', '');"  >
						<option value="" selected>Select a agency</option>
                        <?php
                        foreach($all_agency as $value){
                        ?>
						<option value="<?php echo $value->id;?>" <?php if ($filter['agency'] == $value->id) { echo "selected";}?>><?php echo $value->first_name." ".$value->last_name;?></option>
                        
						<?php }?>
					</select>
				</div>
                <div class="form-group">
					<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>&nbsp;
					
				</div>
    </fieldset>
    
    <?php echo form_close();*/?>
	<?php
		echo validation_errors();

		$attributes = array('class' => 'form-inline status-form', 'id' => 'user-status-form');
		echo form_open(base_url('App/tester/update_status'), $attributes);
	?>
	<div class="table-responsive">
	<div class="tasting-date-search-container">
            <?php
                echo validation_errors();
                $attributes = array('class' => 'form-inline search-form', 'id' => 'user-status-form', 'role' => 'form');
                echo form_open(base_url('App/tester/search_submit'), $attributes);
            ?>
                <div class="col-md-4">
                            <div class="row">

                                    <!-- <label for="inputName">Rating</label><br /> -->
                                    <select id="ratingId" name="search_by_rating" class="form-control"  style="width:102%">
                                    <option value="">Choose Rating</option>
                                    <option value="1" <?php if($filter['search_by_rating']==1){echo "selected";}?> >1☆</option>
                                    <option value="2" <?php if($filter['search_by_rating']==2){echo "selected";}?> >2☆</option>
                                    <option value="3" <?php if($filter['search_by_rating']==3){echo "selected";}?> >3☆</option>
                                    <option value="4" <?php if($filter['search_by_rating']==4){echo "selected";}?> >4☆</option>
                                    <option value="5" <?php if($filter['search_by_rating']==5){echo "selected";}?> >5☆</option>
                                        
                                    </select>
                                
                            </div>
                </div>
                <div class="form-group" style="padding-left: 100px;">
                    <button type="button" id="submitBtn" onclick="Filter()" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>&nbsp;
                    <button type="button" class="btn btn-default" onclick="window.location='<?php echo base_url('App/tester');?>'"><span class="glyphicon glyphicon-refresh"></span> Reset</button>
                </div>
            <?php echo form_close();?>
            
        </div>
		<!-- Table -->
	    <table class="table table-striped table-responsive" width="100%" id="user-table">
	    	<thead>
	    		<tr>
	          		<th><input type="checkbox" id="checkall"></th>
	          		<th>Name</th>
	          		<th>Email</th>
                    <th>Zone</th>
	          		<th>Last Login</th>
	          		<th>Status</th>
					<th>Rating</th>
	          		<th>View details</th>
                    <th>Created by</th>
					<th>Action</th>
	          	</tr>
	        </thead>
	        
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
<!-- Delete modal-->
<div id="myDeleteModal" class="modal fade" role="dialog">
  <div class="modal-dialog" id="div_result_delete">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Delete Taster</h4>
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
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog" id="div_result"></div>
</div>
<script>
	<?php if (isset($filter) && $filter['field'] <> "") {?>
		updateSearchFields('<?php echo $filter['field'];?>', '<?php echo $filter['ope'];?>', '<?php echo $filter['q'];?>');
	<?php }?>
</script>

<script type="text/javascript">
	function open_modal(user_id)
	{
		$.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/tester/view_user_details/",
		   data: {user_id:user_id},
		   success:function(data){
		    $("#div_result").html(data);
		    
		    $('#myModal').modal('show');
		   }
		}); 
	}
	function Filter(){
    //$("#user-table").dataTable().clear().destroy();
    var rate = $('#ratingId option:selected').val();

    if(rate!=""){
        $('#user-table').dataTable().fnClearTable();
        $('#user-table').dataTable().fnDestroy();
        $('#user-table').DataTable({
            "processing": true,
            "serverSide": true,
            "iDisplayLength": 50,
            "lengthMenu": [10, 20, 50, 100, 500],
            "ajax":{
                "url": "<?php echo base_url('App/tester/rating_filter') ?>",
                "data":{rate: rate}, 
                "dataType": "json",
                "type": "POST",
            },
            //"oSearch": { "bSmart": false, "bRegex": true },
            //"searching": true,
            "stateSave": true,
            "bSort" : false
        });
    }
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
                window.location.href = "<?php echo base_url('App/tester/delete/'); ?>"+del_id;
            }
            
	});

    $('#delete').click(function(e){
        e.preventDefault();
       var m_del_id= $('#delete').data('id');

       window.location.href = "<?php echo base_url('App/tester/temp_delete/'); ?>"+m_del_id;

    });
    // View page record by limit
	$('#view').on('change', function() {
		var view = $(this).val();
		window.location.href = base_url+"App/tester/index/view/"+view;
	});
</script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    /*$('#user-table').DataTable({
        
        
        "lengthMenu": [10, 20, 50, 100, 500],
       "iDisplayLength": 10,    
        
        
    });*/
    $('#user-table').DataTable({
      	"processing": true,
        "serverSide": true,
		"iDisplayLength": 50,
		"lengthMenu": [10, 20, 50, 100, 500],
        "ajax":{
		    "url": "<?php echo base_url('App/tester/test') ?>",
		    "dataType": "json",
		    "type": "POST",
		},
		"oSearch": { "bSmart": false, "bRegex": true },
		"stateSave": true,
		"bSort" : false
	});
});
</script>
<style>
	.tasting-date-search-container{
        position: absolute;
        left: 300px;
        top: 15px;
        z-index: 950;
    }
    .table-responsive{
        position: relative;
        padding-top: 20px;
    }

	.table.dataTable thead .sorting, 
table.dataTable thead .sorting_asc, 
table.dataTable thead .sorting_desc {
    background : none;
}
</style>