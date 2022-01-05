<style>
.sw { border:2px solid #ccc; width:396px; height:206px; overflow-y: scroll;
    margin-left: 15px;}
.adjustmin {margin-left: 5px !important; width:120px;}
.adjustam {width:105px;}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/sweetalert.css">

<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-list"></span> Job Management</h1>
        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li class="active"><a href="<?php echo base_url('Agency/Job');?>"><span class="glyphicon glyphicon-list"></span> Job</a></li>
    		</ul>
        </div>
    </div>
</div>
<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('Agency/dashboard');?>">Dashboard</a></li>
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
	?><?php
		echo validation_errors();

		$attributes = array('class' => 'form-inline search-form', 'id' => 'product-search-form', 'role' => 'form');
		echo form_open(base_url('Agency/job/search_submit'), $attributes);
	?>
	
	<fieldset>
		<legend><span class="glyphicon glyphicon-filter"></span> Filters</legend>
		<div class="row">
			<div class="col-md-2">
				<div class="form-group">
					<label for="inputName">Tasting date </label><br />
					<input type="text" class="form-control datepicker" id="inputName" name="sampling_date" placeholder="Search here" value="<?php if (isset($filter['tasting_date']) && $filter['tasting_date']!="~") {echo $filter['tasting_date'];}?>" >
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<label for="inputName">Taster </label><br />
					<select name="search_by_taster" class="form-control">
						<option value="">Choose Taster</option>
						<?php foreach($taster as $user){?>
						<option value="<?php echo $user['id'];?>" <?php if($filter['taster']==$user['id']){echo "selected";}?>><?php echo $user['first_name']." ".$user['last_name'];?></option>
					<?php }?>
					</select>
				</div>
			</div>
			
		<div class="col-md-8">
			
				<div class="form-group">
					<label>&nbsp;</label><br />
					<button type="submit" id="submitBtn" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>&nbsp;
					<button type="button" class="btn btn-default" onclick="window.location='<?php echo base_url('Agency/job');?>'"><span class="glyphicon glyphicon-refresh"></span> Reset</button>
				</div>

			</div>
		</div>
		<div class="row" style="margin-top: 25px; float:right">
                        <div class="col-md-1" style="width: 5%;">
                            <label for="inputName" style="margin-top:10px;">Search:</label><br />
                        </div>
                        <div class="col-md-10">
                        <input Type="text" id="search_text" style="width: 90%; margin-left:30px;" class="form-control" name="search_text" placeholder="Search here" autocomplete="off" value="<?php if (isset($filter['search_text']) && $filter['search_text']!="~") {echo urldecode($filter['search_text']);}?>">
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
					<th>Job Status</th>
					<th>Taster</th>
					<th>Store</th>
					<!--<th>Assign taster</th>-->
					<th>Action</th>
					<!--<th>Admin approval for assign taster</th>-->
					<th>Details</th>
	          	</tr>
	        </thead>
	        <tbody>
	            <?php if (count($jobs) == 0) { ?>
	            <tr>
	            	<td colspan="100%" align="center">Sorry!! No Jobs found.</td>
	            </tr>
	            <?php } 
				
				?>
	            <?php foreach($jobs as $item) { ?>
	            <tr>
	            	<td>
	            		<?php 
                            //echo date("j-F-Y",strtotime($item->tasting_date)) ;
                            echo date("m/d/Y", strtotime($item->tasting_date));
                        ?>
	            	</td>
	            	<td>
	            		<?php //echo $item->start_time ;
                            echo date('h:i:a', strtotime($item->start_time));
                        ?>
	            	</td>
	            	<td>
	            		<?php //echo $item->end_time ;
                            echo date('h:i:a', strtotime($item->end_time));
                        ?>
	            	</td>
	            	<td>
	            		<?php echo substr($item->admin_note,0,50) ;?>
	            	</td>
	            	<td>
						<!-- <?php echo ucfirst($item->status);?> -->
                        <?php
                            $name= $this->Job_model->get_user_name($item->agency_taster_id);
                            if($name){
                        ?>
                            <span class="label label-primary">Assigned to Taster</span>
                        <?php 
                            }else{
                                $status=ucfirst($item->status);
                                if($status=='Assigned')
								    echo '<span class="label label-warning">'.$status.'</span>';
                                else if($status=='Problems')
									echo '<span class="label label-info">'.'Completed'.'</span>';
                                else
								    echo '<span class="label label-warning">'.'Assigned'.'</span>';
                                ?>
                        <?php 
                            }
                        ?>
	            		<?php
                            //get tasting date is over or not
                            $now = time(); // or your date as well
                            //$your_date = strtotime($item->tasting_date);
                            $currentDate=date('Y-m-d', time());
                            $your_date = date('Y-m-d',strtotime($item->tasting_date));
                  

                            //$datediff = $now - $your_date;
                            //$difference=round($datediff / (60 * 60 * 24));
                            //if( && $item->status!='completed' && $item->ready_for_billing=='0')
                            if($currentDate > $your_date && $item->status!='completed' && $item->ready_for_billing=='0' && $item->job_start_time=='00:00:00')
                            {
                        ?>
	            		<span class="label label-danger">Tasting not done naba</span>
	            		<?php
	            			}
	            		?>
	            	</td>
					<td>
					<?php 
					$name= $this->Job_model->get_user_name($item->agency_taster_id);
					if($name){
						echo $name;
					}else{
						//echo 'Not Assigned';
						echo '<span class="label label-info">Not Assigned</span>';
					}
					?>
					</td>
					<td>
                    <?php 
                        $data=$this->Job_model->get_more_job_info($item->id);
							echo $data->store_name;
						$address_field='<br/>';
                        if($item->address)
                        $address_field.= ucfirst($item->address).'&nbsp;&nbsp;';
                        if($item->city)
                        $address_field.= ucfirst($item->city).'&nbsp;&nbsp;';
                        if($item->state)
                        $address_field.= $item->state.'&nbsp;&nbsp;';
                        if($item->zipcode)
                        $address_field.= $item->zipcode.'&nbsp;&nbsp;';
                        echo $address_field;

                    ?>

                    </td>
	            	<!--<td>
	            		<?php
	            			//Check accepted job is approved by admin or not
	            			$approved_status=$this->Job_model->check_approval_status($item->id);
	            			if($approved_status==1 && $item->status=='approved')
	            			{
	            		?>
	            		<a class="btn btn-info btn-xs" href="javascript:void(0)" title="Assign tester" class="activity_button" onclick="open_tester_assign_modal(<?php echo $item->id;?>,<?php echo $this->session->userdata('id');?>)">
	            		<span class="glyphicon glyphicon-edit"></span>
	            		Assign
	            		</a>
	            		<?php 
	            			}
	            			else if($item->status=='completed')
	            			{
	            		?>
	            		<span class="label label-success">Completed</span>
	            		<?php
	            			}
	            			else
	            			{
	            		?>
	            		<span class="label label-danger">You are not approved by admin yet</span>
	            		<?php
	            			}
	            		?>
	            	</td>-->
	            	<td>
                        <?php
                            if($item->status=='approved' || $item->status=='problems' && $item->finish_time=='00:00:00')
                            {
                        ?>
                               <?php if (date("Y-m-d") > $item->tasting_date) {?>
                                        <span class="label label-success">Job is completed</span>
                                    <?php } else { ?>
                                        <a class="btn btn-success btn-xs" href="javascript:void(0)" title="Assign tester" class="activity_button" onclick="open_tester_assign_modal(<?php echo $item->id;?>,<?php echo $this->session->userdata('id');?>, <?php echo $item->job_state;?>)">
                                    <span class="glyphicon glyphicon-edit"></span>Assign</a>
                                    <?php } ?>

						<?php
                            }else if($item->status=='problems' && $item->start_time!='00:00:00' && $item->tasting_date<date('Y-m-d')){
                                echo '<span class="label label-success">Job is completed</span>';
                            }
                            else if($item->status=='completed')
                            {
                        ?>
                                <span class="label label-success">Job is completed</span>
                        <?php       
							}else if($item->status=='cancelled')
                            {
                        ?>
                                <span class="label label-danger">Canceled</span>
                        <?php       
                            }else if($item->status=='rejected')
                            {
                        ?>
                                <span class="label label-danger">Rejected</span>
                        <?php       
                            }
							else if($item->status=='problems' && $item->start_time!='00:00:00' && $item->finish_time!='00:00:00'){ ?>
								<span class="label label-success">Job is completed</span>
					 <?php }
                            else
                            {
                        ?>
                        <?php $date_now = date("Y-m-d");?>
                        <?php if($date_now > $item->tasting_date) {?>
                        <a class="btn btn-info btn-xs" href="javascript:void(0)" title="Activity" class="activity_button" onclick="open_accept_modal(<?php echo $item->id;?>,<?php echo $this->session->userdata('id');?>)">
                        <span class="glyphicon glyphicon-edit"></span>
                        Accept / Reject
                        </a>
                        <?php }else{ ?> 
                            
                            <a class="btn btn-info btn-xs" href="javascript:void(0)" title="Activity" class="activity_button" onclick="open_accept_modal(<?php echo $item->id;?>,<?php echo $this->session->userdata('id');?>)">
                        <span class="glyphicon glyphicon-edit"></span>
                        Accept / Reject
                        </a>
                        <?php }} ?>

                    </td>
	            	<!--<td>
	            		<?php
	            			if($item->request_job_approval_status=='pending')
	            			{
	            		?>
	            			<span class="label label-warning">Pending</span>
	            		<?php
	            			}
	            			else if($item->request_job_approval_status=='waiting')
	            			{
	            		?>
	            		<span class="label label-warning">Waiting</span>
	            		<?php
	            			}
	            			else
	            			{
	            		?>
	            		<span class="label label-success">Approved</span>
	            		<?php
	            			}
	            		?>
	            	</td>-->
	            	<td>
	            		<a class="btn btn-info btn-xs" href="javascript:void(0)" title="View" onclick="open_details_modal(<?php echo $item->id;?>)">
	            			<span class="glyphicon glyphicon-eye-open"></span> View
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
	function open_accept_modal(job_id,agency_id)
	{
		
		$.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>Agency/job/open_accept_modal/",
		   data: {job_id:job_id,agency_id:agency_id},
		   success:function(data){

			    $("#div_result").html(data);
			    $('#myModal').modal('show');
		   }
		});
	}

	function open_tester_assign_modal(job_id,user_id,job_state)
	{

		if(job_state == 1){
			swal("Oops!", "This job has been started & the taster cannot be changed for the started job.", "warning");
			return false;
		}else{
			$.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>Agency/job/open_tester_assign_modal/",
		   data: {job_id:job_id,user_id:user_id},
		   success:function(data){
			    $("#div_result").html(data);
			    $('#myModal').modal('show');
		   }
		});
	}
}

	function open_details_modal(job_id)
	{
		//alert(job_id);
		$.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>Agency/job/more_info/",
		   data: {job_id:job_id},
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

<script src="<?php echo base_url()?>assets/js/chosen.jquery.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/sweetalert.min.js"></script>

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
<script type="text/javascript" >

$(document).ready(function() {
    var fieldInput = $('#search_text');
    var fldLength= fieldInput.val().length;
    fieldInput.focus();
    fieldInput[0].setSelectionRange(fldLength, fldLength);

	var timeout;
    $("#search_text").on("keyup", function() {
        if(timeout) {
            clearTimeout(timeout);
        }
        timeout = setTimeout(function() {
            $('#submitBtn').click();
        }, 1100);
    });
});
</script>