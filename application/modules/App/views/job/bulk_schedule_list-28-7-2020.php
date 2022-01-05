<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-briefcase"></span> Bulk Schedule Management</h1>
        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li class="active"><a href="<?php echo base_url('App/Bulk_schedule_job');?>"><span class="glyphicon glyphicon-briefcase"></span> Bulk Schedule</a></li>
                <li><a href="<?php echo base_url('App/Bulk_schedule_job/add');?>"><span class="glyphicon glyphicon-plus-sign"></span>Create Bulk Schedule</a></li>
    		</ul>
        </div>
    </div>
</div>
<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li class="active">Bulk Schedule Management</li>
		<li align="pull-right">Server Time:&nbsp;<?php echo date("h.i a", time());?></li>
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
		echo form_open(base_url('App/Job/update_status'), $attributes);
	?>
	
	<div class="table-responsive">
		<!-- Table -->
	    <table class="table table-striped table-responsive" width="100%" id="user-table">
	    	<thead>
	    		<tr>
	          		<th>Job date</th>
	          		<th>Start time</th>
	          		<th>End time</th>
                   <!--  <th>Created on</th> -->
					<th>Store</th>
					<th>Schedule type</th>
					<th>Taster/Agency</th>
					<th>Action</th>
					
	          	</tr>
	        </thead>
	        <tbody>
	            <?php if (count($jobs) == 0) { ?>
	            <tr>
	            	<td colspan="100%" align="center">Sorry!! No Jobs found.</td>
	            </tr>
	            <?php } 
				/*echo '<pre>';
				print_r($jobs);
				die;*/
				?>
	            <?php foreach($jobs as $item) {
					$ts_name;
					if($item->taster_id!=''){
						$user_type=get_user_type('users',$item->taster_id);
						if($user_type=='agency'){
							$ts_name=get_agency_name('user_meta',$item->taster_id);
							
						}else{
							$ts_name = $this->Job_model->get_user_name($item->taster_id);
						}
					}else{
						$ts_name = 'N/A';
					}
				?>
	            <tr>
	            	<td>
	            		<?php 
                            //echo date("j-F-Y",strtotime($item->tasting_date)) ;
                             echo date("m/d/Y", strtotime($item->tasting_date));  
                        ?>
	            	</td>
	            	<td>
	            		<?php 
                            //echo $item->start_time;
                            echo date('h:i:a', strtotime($item->start_time));
                        ?>
	            	</td>
	            	<td>
	            		<?php 
                            //echo $item->end_time;
                            echo date('h:i:a', strtotime($item->end_time));
                        ?>
	            	</td>
					<td><?php echo $item->store_name;?></td>
                    <!-- <td><?php //echo date('m/d/Y h:i:a',strtotime($item->created_on));?></td> -->
                    <td><?php if($item->schedule_type!=''){echo ucfirst($item->schedule_type);}else{echo "--";}?></td>
					<td><?php echo $ts_name;?></td>
	            	<td>
	            		<a class="btn btn-primary btn-xs" href="<?php echo base_url('App/Bulk_schedule_job/publish_job/'.$item->id);?>" title="Publish" onclick="return confirm('Do you want to publish the job?')">
	            			<span class="glyphicon glyphicon-edit"></span>Publish
	            		</a>
	            		<a class="btn btn-primary btn-xs" href="<?php echo base_url('App/Bulk_schedule_job/edit_job/'.$item->id);?>" title="Edit">
	            			<span class="glyphicon glyphicon-edit"></span> Edit
	            		</a>
	            		
                        <a class="btn btn-danger btn-xs delete_button" href="javascript:void(0)"  title="Delete" data-id="<?php echo $item->id;?>">
	            			<span class="glyphicon glyphicon-trash"></span> Delete
	            		</a>
	            	</td>
	            	<?php
	            		}
	            	?>
	            </tr>
	            
	        </tbody>
	    </table>
	</div>
	<?php echo form_close();?>
	<?php 
        //echo $this->pagination->create_links(); 
        /*if(count($jobs) >10)
        {
            echo $this->pagination->create_links();
        }*/
    ?>
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
<!-- Approve job Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog" id="div_result"></div>
</div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">
	function open_modal(job_id,accepted_tester_id,pre_tester_id)
	{
		$.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/job/open_approve_modal/",
		   data: {job_id:job_id,tester_id:pre_tester_id},
		   beforeSend:function(){
		    $("#showloader").html("<center><img height='25' width='120' src='<?php echo HTTP_IMAGES_PATH;?>loading.gif' /></center>");
		   },
		   success:function(data){
		    $("#div_result").html(data);
		    $("#accepted_tester").val(accepted_tester_id);
		    $('#myModal').modal('show');
		   }
		}); 
	}
	function open_activity_modal(job_id)
	{
		$.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/job/open_activity_modal/",
		   data: {job_id:job_id},
		   success:function(data){
		    $("#div_result").html(data);
		    $('#myModal').modal('show');
		   }
		}); 
	}
	function open_sr_modal(store_id)
	{
		$.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/job/open_sr_modal/",
		   data: {store_id:store_id},
		   success:function(data){
		    $("#div_result").html(data);
		    $('#myModal').modal('show');
		   }
		}); 
	}
	function approve_request(job_id){
       var r=confirm("Do you want to approve the request?");
        if (r==true)
        {
        	$.ajax({
			   type:'POST',
			   url:"<?php echo base_url(); ?>App/job/approve_request/",
			   data: {job_id:job_id},
			   success:function(data){
			     if(data==true)
			     {
			     	swal("The request has been approved");
			     	setTimeout("location.reload(true);", 5000);
			     }
			   }
			});
        } 
        else
        {

          return false;
        }
    } 
    function problem_one_modal(job_id,taster_id)
    {
    	//alert(job_id);
    	//alert(taster_id);
    	$.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/job/open_problem_one_modal/",
		   data: {job_id:job_id,taster_id:taster_id},
		   
		   success:function(data){
		    $("#div_result").html(data);
		    $('#myModal').modal('show');
		   }
		});
    }
    function problem_two_modal(job_id)
    {
    	$.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/job/open_problem_two_modal/",
		   data: {job_id:job_id},
		   
		   success:function(data){
		    $("#div_result").html(data);
		    $('#myModal').modal('show');
		   }
		});
    }

    function edit_modal(job_id)
    {
    	//alert(job_id);
    	$.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/job/open_edit_job_modal/",
		   data: {job_id:job_id},
		   
		   success:function(data){
		   	//alert(data);
		    $("#div_result").html(data);
		    $('#myModal').modal('show');

		   }
		});
    }
    function open_sales_rep_details_modal(id)
    {
        //alert(id);
        $.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/job/open_sales_rep_details_modal/",
		   data: {user_id:id},
		   
		   success:function(data){
		   	//alert(data);
		    $("#div_result").html(data);
		    $('#myModal').modal('show');

		   }
		});
    }
    function set_question_modal(id)
    {
        //alert(id);
        $.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/job/set_question_modal/",
		   data: {job_id:id},
		   
		   success:function(data){
		   	//alert(data);
		    $("#div_result").html(data);
		    $('#myModal').modal('show');

		   }
		});
    }
    function view_setup_modal(id)
    {
        $.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/job/view_setup_modal/",
		   data: {job_id:id},
		   
		   success:function(data){
		   	//alert(data);
		    $("#div_result").html(data);
		    $('#myModal').modal('show');

		   }
		});
    }


</script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
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
</script>
<script>
// View page record by limit
	$('#view').on('change', function() {
		var view = $(this).val();
		window.location.href = base_url+"App/job/index/view/"+view;
	});
</script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#user-table').DataTable({
        "order": [3, 'desc'],
        "lengthMenu": [10, 20, 50, 100, 500],
       "iDisplayLength": 10,  
        searching: false, 
        "stateSave": true,
		"bSort" : false
    });
});
$('.delete_button').click(function(e){
    e.preventDefault();
   var m_del_id= $(this).data('id');
    var c = confirm('Are you confirm to delete the schedule?');
            
    if(c==true) {
        window.location.href = "<?php echo base_url('App/Bulk_schedule_job/temp_delete/'); ?>"+m_del_id;
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
