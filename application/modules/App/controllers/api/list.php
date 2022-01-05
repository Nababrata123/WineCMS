<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-user"></span> Job Management</h1>

        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li class="active"><a href="<?php echo base_url('App/Job');?>"><span class="glyphicon glyphicon-user"></span> Job</a></li>
    			
    		</ul>
        </div>
    </div>
</div>

<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li class="active">Job Management</li>
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
		
	    <li <?php if ($filters['status'] == "pre_assigned" || $filters['status'] == "") {echo 'class="active"';}?>><a href="<?php echo base_url('App/Job/index/status/pre_assigned');?>">Pre assigned (<?php echo $count_pre_assigned;?>)</a></li>
	    <li <?php if ($filters['status'] == "assigned") {echo 'class="active"';}?>><a href="<?php echo base_url('App/Job/index/status/assigned');?>">Assigned (<?php echo $count_assigned;?>)</a></li>
	    <li <?php if ($filters['status'] == "accepted") {echo 'class="active"';}?>><a href="<?php echo base_url('App/Job/index/status/accepted');?>">Accepted (<?php echo $count_accepted;?>)</a></li>
	    <li <?php if ($filters['status'] == "problems") {echo 'class="active"';}?>><a href="<?php echo base_url('App/Job/index/status/problems');?>">Problems (<?php echo $count_problems;?>)</a></li>
		
	</ul>
	<?php
		echo validation_errors();

		$attributes = array('class' => 'form-inline status-form', 'id' => 'user-status-form');
		echo form_open(base_url('App/Job/update_status'), $attributes);
	?>
	<div class="table-responsive">
		<!-- Table -->
	    <table class="table table-striped table-responsive" width="100%">
	    	<thead>
	    		<tr>
	          		
	          		<th>Job date</th>
	          		<th>Start time</th>
	          		<th>End time</th>
					<th>Admin note</th>
					<th>Status</th>

					<?php
						if($filters['status'] == "pre_assigned" || $filters['status']=="" || $filters['status']=="assigned" || $filters['status']=="accepted")
						{
					?>
					<th>Action</th>
					<?php
						}
					?>
	          	</tr>
	        </thead>
	        <tbody>
	            <?php if (count($jobs) == 0) { ?>
	            <tr>
	            	<td colspan="100%" align="center">Sorry!! No Jobs found.</td>
	            </tr>
	            <?php } ?>
	            <?php foreach($jobs as $item) { ?>
	            <tr>
	            	
	            	<td>
	            		<?php echo date("j-F-Y",strtotime($item->tasting_date)) ;?>
	            	</td>
	            	<td>
	            		<?php echo $item->start_time ;?>
	            	</td>
	            	
					
	            	<td>
	            		<?php echo $item->end_time ;?>
	            	</td>
	            	<td>
						
	            		<?php echo substr($item->admin_note,0,50) ;?>
	            		
	            	</td>
	            	<?php
	            		//Get date difference between current date and job created date
	            		$timestamp = strtotime($item->created_on);
	            		$created_date=new DateTime(date('Y-m-d', $timestamp));
	            		$todays_date=new DateTime(date('Y-m-d'));

	            		//get date difference
	            		$diff = $todays_date->diff($created_date)->format("%a");

	            		$tester_and_agency=$this->Job_model->get_tester_or_agency($item->id);
	            		$id_string=array();
				        foreach($tester_and_agency as $id)
				        {
				            //$id_string.=$id['user_id'].",";
				            array_push($id_string,$id['id']);
				        }

	            	?>
	            	<td>
	            		<?php if(($diff>2) && !strpos($item->taster_id, ',') && in_array($item->taster_id, $id_string) == false){?><span class="label label-danger">Urgent</span><?php }else if($item->job_status==1){?><span class="label label-warning">Not Published</span><?php }else if($item->job_status==2){?><span class="label label-success"> Published</span><?php }else if($item->job_status==3 && $item->accept_status==1 && $item->status=='accepted'){ ?><span class="label label-danger">Accepted and not approved</span><?php }else if($item->job_status==3 && $item->accept_status==1 && $item->status=='approved'){?><span class="label label-success">Approved</span><?php }else if($item->job_status==3 && $item->accept_status==1 && $item->status=='canceled'){ ?><span class="label label-info">Canceled</span><?php }?>

	            		<?php
	            			if($item->accept_status=='0')
	            			{
	            		?>
	            		<span class="label label-danger">Rejected</span>
	            		<?php
	            			}
	            		?>
	            	</td>
	            	<?php
						if($filters['status'] == "pre_assigned" || $filters['status']=="" || $filters['status']=="assigned" || $filters['status']=="accepted")
						{
					?>
	            	<td>
	            		<?php
	            			if($item->job_status==1 || $item->job_status==2)
	            			{
	            		?>
	            		<a class="btn btn-primary btn-xs" href="<?php echo base_url('App/Job/publish_job/'.$item->id);?>" title="Publish">
	            			<span class="glyphicon glyphicon-edit"></span>Publish
	            		</a>
	            		<?php
	            			}
	            			else if($item->job_status==3 && $item->accept_status==1)
	            			{
	            		?>
	            		<a class="btn btn-warning btn-xs" href="javascript:void(0)" title="Approve"  id="approve_button" onclick="open_modal(<?php echo $item->id;?>,'<?php echo $item->taster_id;?>')">
	            			<span class="glyphicon glyphicon-edit"></span>Approve
	            		</a>
	            		<?php
	            			}
	            		?>
	            	</td>
	            	<?php
	            		}
	            	?>
	            </tr>
	            <?php } ?>
	        </tbody>
	        
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

<!-- Approve job Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog" id="div_result"></div>
</div>

<script type="text/javascript">
	function open_modal(job_id,tester_id)
	{
		
		$.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/job/open_approve_modal/",
		   data: {job_id:job_id,tester_id:tester_id},
		   beforeSend:function(){
		    $("#showloader").html("<center><img height='25' width='120' src='<?php echo HTTP_IMAGES_PATH;?>loading.gif' /></center>");

		   },
		   success:function(data){
		    $("#div_result").html(data);
		    $('#myModal').modal('show');
		   }
		   

		}); 
	}
</script>