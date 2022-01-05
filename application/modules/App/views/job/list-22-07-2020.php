<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-list"></span> Job Management</h1>
        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li class="active"><a href="<?php echo base_url('App/Job');?>"><span class="glyphicon glyphicon-list"></span> Job</a></li>
                <li><a href="<?php echo base_url('App/Job/add');?>"><span class="glyphicon glyphicon-plus-sign"></span>Add Job</a></li>
    		</ul>
        </div>
    </div>
</div>
<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li class="active">Job Management</li>
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

		$attributes = array('class' => 'form-inline search-form', 'id' => 'product-search-form', 'role' => 'form');
		echo form_open(base_url('App/job/search_submit'), $attributes);
	?>
	<?php
		if($this->uri->segment(5))
		{
			$seg=$this->uri->segment(5);
	?>
	<input type="hidden" name="status" value="<?php echo $seg;?>">

	<?php
		}
	?>
	<fieldset>
		<legend><span class="glyphicon glyphicon-filter"></span> Filters</legend>
		<div class="row">
            <div class="col-sm-10">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputName">Tasting date </label><br />
                        <input type="text" class="form-control datepicker" id="inputName" name="sampling_date" placeholder="Search here" value="<?php if (isset($filters['tasting_date']) && $filters['tasting_date']!="~") {echo $filters['tasting_date'];}?>" >
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputName">Taster/Agency </label><br />
                        <select name="search_by_taster[]" class="form-control" multiple="multiple" style="width:100%">
                            
                            <?php 
                            
                                $taster_id=$filters['taster'];
                                $taster_id_array=explode("@",$taster_id);
                                foreach($taster as $user)
                                {
                                    $user_type=get_user_type('users',$user['id']);
                                    if($user_type=='agency')
                                    {
                                        $name=get_agency_name('user_meta',$user['id']);
                                    }
                                    else
                                    {
                                        //$name=$user['first_name']." ".$user['last_name'];
                                        $name=$user['last_name']." ".$user['first_name'];
                                    }
                            ?>
                            <option value="<?php echo $user['id'];?>" <?php if(in_array($user['id'],$taster_id_array)){echo "selected";}?>><?php echo $name;?></option>
                        <?php }?>
                        </select>
                    </div>
                </div>
                
                <!-- For additional fields -->
                <div class="col-md-6">
                    <div class="row">

                        <div class="col-md-6">
                            <label for="inputName">Store name </label><br />
                            <select name="search_by_store" class="form-control"  style="width:100%">
                                <option value="">Choose store</option>
                                <?php 
                                    foreach($store as $val)
                                    {

                                ?>
                                <option value="<?php echo $val->id;?>" <?php if($filters['store']==$val->id){echo "selected";}?>><?php echo $val->name;?></option>
                            <?php }?>
                            </select>
                        </div>
                    
                    
                        <div class="col-md-3">
                            <label for="inputName">Entry date </label><br />
                            <input type="text" class="form-control datepicker" id="entrydate" name="entry_date" placeholder="Search here" value="<?php if (isset($filters['entry_date']) && $filters['entry_date']!="~") {echo $filters['entry_date'];}?>" >
                        </div>
                    </div>
                    <div class="row" style="margin-top: 25px;">
                        <div class="col-md-2" style="width: 10%;">
                            <label for="inputName" style="margin-top:10px;">Search:</label><br />
                        </div>
                        <div class="col-md-10">
                        <input Type="text" id="search_text" style="width: 89%;" class="form-control" name="search_text" placeholder="Store Address" autocomplete="off" value="<?php if (isset($filters['search_text']) && $filters['search_text']!="~") {echo urldecode($filters['search_text']);}?>">
                        </div>
                    </div>
                </div>
                
            <!-- Additional fields end-->
            
			</div>
            <!-- For sales rep-->
            <div class="col-sm-2">
            <div class="form-group">
                        <label for="inputName">Sales rep </label><br />
                        <select name="sales_rep" class="form-control"  style="width:100%">
                            <option value="">Choose sales rep</option>
                            <?php 
                                foreach($sales_rep as $val)
                                {

                            ?>
                            <option value="<?php echo $val['id'];?>" <?php if($filters['sales_rep']==$val['id']){echo "selected";}?>><?php echo $val['last_name']." ".$val['first_name'];?></option>
                        <?php }?>
                        </select>
            </div>
            </div>
            
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label><br />
                        <button type="submit" id="submitBtn" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>&nbsp;
                        <button type="button" class="btn btn-default" onclick="window.location='<?php echo base_url('App/job');?>'"><span class="glyphicon glyphicon-refresh"></span> Reset</button>
                    </div>
                </div>
                
            </div>
		<div class="row">
			<div class="col-md-6">&nbsp;</div>
		</div>
	</fieldset>
	<?php echo form_close();?>

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
					<!--<th>Admin note</th>-->
					<th>Sales Representative</th>
					<th>Store</th>
                    <?php
                    if($filters['status'] != "pre_assigned")
                    {
                    ?>
					<th>Agency</th>
                    <th>Taster</th>
                    <?php
                    }
                    ?>
					<?php
						if($filters['status'] != "pre_assigned" && $filters['status'] != ""){
                    ?>
					<th>Activity</th>
                    <?php
                        }
                    ?>
					<th>Status</th>
                    <?php
						if($filters['status'] == "accepted" || $filters['status'] == "problems"){
                    ?>
					<th>Ready for billing</th>
					<?php
                        }
                    ?>
					
					<?php
						/* if($filters['status'] != "problems" || $filters['status']=="" || $filters['status']=="assigned")
						{ */
						//if($filters['status'] != "problems")
						//{
					?>
					<th>Action</th>
					<?php
                        if($filters['status'] == "problems"){
                    ?>
                    <th>Out of range</th>
                    <?php
                        }
                    ?>
	          	</tr>
	        </thead>
	        <tbody>
                
	            <?php 
				if (count($jobs) == 0) { ?>
	            <tr>
	            	<td colspan="100%" align="center">Sorry!! No Jobs found.</td>
	            </tr>
	            <?php } 
				/* echo '<pre>';
				print_r($jobs);
				echo '</pre>';
				die; */
				?>
	            <?php foreach($jobs as $item) { ?>
	            <tr id="job<?php echo $item->id;?>">
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
	            	<!--<td>
	            		<?php echo substr($item->admin_note,0,50) ;?>
	            		
	            	</td>-->
	            	<td>
	            		<?php
	            			$sales_rep=$this->Job_model->get_user_name($item->user_id);
	            			echo $sales_rep;
	            		?>
	            		<!--<a class="btn btn-info btn-xs" href="javascript:void(0)" title="Activity" class="activity_button" onclick="open_sales_rep_details_modal(<?php echo $item->user_id;?>)">
	            		<span class="glyphicon glyphicon-edit"></span>
	            		View details
	            		</a>-->
	            	</td>
					<td><?php 
						echo $item->store_name;
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
                    

					
					?></td>
                    <?php
                    if($filters['status'] != "pre_assigned")
                    {
						$ag_name;
						$ts_name;
						if($item->taster_id!=''){
							$user_type=get_user_type('users',$item->taster_id);
							if($user_type=='agency'){
								$ag_name=get_agency_name('user_meta',$item->taster_id);
								if($item->agency_taster_id != 0){
									$ts_name = $this->Job_model->get_user_name($item->agency_taster_id);
								}else{
									$ts_name = 'N/A';
								}
							}else{
								$ag_name = 'N/A';
								$ts_name = $this->Job_model->get_user_name($item->taster_id);
							}
						}else{
							$ag_name = 'N/A';
							$ts_name = 'N/A';
						}
                    ?>
					<td><?php echo $ag_name;?></td>
	            	<td><?php echo $ts_name;?></td>
                    <?php
                    }
                    ?>
                    <?php
						if($filters['status'] != "pre_assigned" && $filters['status'] != ""){
                    ?>
	            	<td>
	            		<a class="btn btn-info btn-xs" href="javascript:void(0)" title="Activity" class="activity_button" onclick="open_activity_modal(<?php echo $item->id;?>)">
	            		<span class="glyphicon glyphicon-edit"></span>
	            		View
	            		</a>
	            		<?php if($filters['status'] == "pre_assigned"){?>
	            			<a class="btn btn-success btn-xs" href="javascript:void(0)" title="Special request" class="activity_button" onclick="open_sr_modal(<?php echo $item->store_id;?>)">
	            		<span class="glyphicon glyphicon-edit"></span>
	            		View special request
	            		</a>
	            		<?php } ?>
                        <!-- View tasting setup-->
                        <?php
						if($filters['status'] == "accepted" || $filters['status'] == "problems"){
                        ?>
                        <a class="btn btn-primary btn-xs" href="javascript:void(0)" title="Activity" class="setup_button" onclick="view_setup_modal(<?php echo $item->id;?>)">
	            		<span class="glyphicon glyphicon-edit"></span>
	            		Tasting setup
	            		</a>
                        <?php } ?>
                        <!--End-->
	            		
	            	</td>
                    <?php
                        }
                    ?>
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
	            		<?php 
	            			if(($diff>2) && !strpos($item->taster_id, ',') && in_array($item->taster_id, $id_string) == false && ($item->job_status==1 || $item->job_status==2))
	            			{
	            		?>
	            		<span class="label label-danger">Urgent</span><?php 
	            			}
	            			else if($item->job_status==1)
	            			{
	            		?>
	            		<span class="label label-warning">Not Published</span>
	            		<?php 
	            			}
	            			else if($item->job_status==2)
	            			{
	            		?>
	            		<span class="label label-success"> Published</span>
	            		<?php 
	            			}
	            			else if($item->job_status==3  && $item->status=='accepted')
	            			{ 
	            		?>
	            		<span class="label label-danger">Accepted and not approved</span>
	            		<!--Edit Job-->
	            		<a class="btn btn-primary btn-xs" href="javascript:void(0)" title="Edit"  onclick="edit_modal(<?php echo $item->id;?>)">
			            		<span class="glyphicon glyphicon-edit"></span>Edit
			            </a>
	            		<?php 
	            			}
	            			else if($item->job_status==3  && $item->status=='approved' )
	            			{
	            		?>
	            		<span class="label label-success">Approved</span>
	            		<!--Edit Job-->
	            		<a class="btn btn-primary btn-xs" href="javascript:void(0)" title="Edit"  onclick="edit_modal(<?php echo $item->id;?>)">
			            		<span class="glyphicon glyphicon-edit"></span>Edit
			            </a>
	            		<?php 
	            			}
	            			else if($item->job_status==3 && $item->accept_status==1 && $item->status=='cancelled')
	            			{ 
	            		?>
	            		<span class="label label-info">Canceled</span><?php 
	            			}
	            			else if($item->job_state==2  && $item->status=='completed')
	            			{
	            		?>
	            		<span class="label label-primary">Completed</span>
                        <?php
	            			
	            			if($item->late == 1 )
	            			{
	            		?>
<!--	            		<span class="label label-danger">Tasting not done</span>-->
                        
                        <span class="label label-danger">Late</span>
                        
	            		<?php
	            			}?>
                        
	            		<?php 
	            			}
	            			elseif($item->job_status==4)
	            			{
                                
                                
	            		?>
                        <?php

							if($item->late==1)
								{
									?>
									<span class="label label-danger">Late</span>
									<?php
								}
							else
							{
							?>
                        
	            		      <span class="label label-danger">Problem</span>
	            		<?php
                            }
	            			}
	            		?>
	            		<?php
	            			if($item->accept_status=='0' && $item->status=='rejected')
	            			{
	            		?>
	            		<span class="label label-danger">Rejected</span>
	            		<?php
	            			}
	            		?>
	            		<?php
	            			if($item->accept_status=='0' && $item->status=='assigned')
	            			{
	            		?>
	            		<span class="label label-info">Assigned</span>
	            		<?php
	            			}
	            		?>
                        <?php
                            //Calculate the job is overtime or not
                            //$end_time=strtotime($item->end_time);
                            //$actual_end_time=strtotime($item->finish_time);
                            //$diff = $actual_end_time - $end_time;
                            /*$diff_in_second=abs($diff);
                            $diff_in_minutes=$diff_in_second/60;*/
                            
                            if($item->overtime == 1)
                            {
                        ?>
                        
                        <?php
                            }
                        ?>
                        <?php
                            /*if($item->late==1)
                            {*/
                        ?>
                        <!--<span class="label label-danger">Late</span>-->
                        <?php
                            //}
                        ?>
	            		
                        <?php
                            if($item->status=='completed' && $item->question_id=='')
                            {
                        ?>
                        <!--Set question-->
	            		<a class="btn btn-warning btn-xs" href="javascript:void(0)" title="Edit"  onclick="set_question_modal(<?php echo $item->id;?>)">
			            		<span class="glyphicon glyphicon-edit"></span>Set question
			            </a>
                        <?php
                            }
                        ?>
	            	</td>
                    <?php
						if($filters['status'] == "accepted" || $filters['status'] == "problems"){
                    ?>
	            	<td>
	            		<?php
	            			if(($item->status=='completed' || $item->status=='problems'))
	            			{
	            			?>	
	            				<?php
	            					if($item->ready_for_billing==0)
	            					{
	            				?>
	            				<span class="label label-warning">No</span>
	            				<?php
	            					}
	            					else
	            					{
	            				?>
	            				<span class="label label-success">Yes</span>
	            			<?php } ?>
	            				<!-- Move for billing-->

	            				<?php
	            					if($item->status=='problems' && $item->ready_for_billing==0)
	            					{
	            				?>
	            				<?php
	            					if($item->job_state==0)
	            					{
	            				?>
	            				<a class="btn btn-primary btn-xs" href="javascript:void(0)" title="Billing"  id="problem_one" onclick="problem_one_modal(<?php echo $item->id;?>,<?php echo $item->taster_id;?>)">
			            		<span class="glyphicon glyphicon-edit"></span>Move to billing
			            		</a>
			            		<?php
			            			}
			            			else
			            			{
			            		?>
			            		<!--a class="btn btn-primary btn-xs" href="javascript:void(0)" title="Billing"  id="problem_two" onclick="problem_two_modal(<?php echo $item->id;?>)"-->
			            		<a class="btn btn-primary btn-xs" href="javascript:void(0)" title="Billing"  id="problem_one" onclick="problem_one_modal(<?php echo $item->id;?>,<?php echo $item->taster_id;?>)">
			            		<span class="glyphicon glyphicon-edit"></span>Move to billing
			            		</a>
			            		<?php
			            			}
			            		?>
			            		<?php 
			            			}
			            		?>
	            			<?php
	            				
	            			}
	            		?>
	            	</td>
                    <?php
                        }
                    ?>
	            	
	            	<?php
						/* if($filters['status'] == "pre_assigned" || $filters['status']=="" || $filters['status']=="assigned" || $filters['status']=="accepted")
						{ */
					
					?>
	            	<td>
						
	            		<?php
						if($filters['status'] != "problems")
						{ 
	            			if($item->job_status==1)
	            			{
									?>
									<a class="btn btn-primary btn-xs" href="<?php echo base_url('App/Job/publish_job/'.$item->id);?>" title="Publish">
									<span class="glyphicon glyphicon-edit"></span>Assign
									</a>
									<?php
	            			}
	            			else if($item->job_status==2)
	            			{
	            		?>
	            		<a class="btn btn-primary btn-xs" href="<?php echo base_url('App/Job/publish_job/'.$item->id);?>" title="Change information">
                            <span class="glyphicon glyphicon-edit"></span>Change information</a>
                        
	            		<?php		
	            			}
	            			//else if($item->job_status==3  && $item->status!='completed' && $item->status!='approved')
	            			//{
	            				//$accepted_id=$this->Job_model->get_accpted_tester_id('job_accept_reject',$item->id);
	            		?>
	            		<!--<a class="btn btn-warning btn-xs" href="javascript:void(0)" title="Approve"  id="approve_button" onclick="open_modal(<?php echo $item->id;?>,'<?php echo $accepted_id;?>','<?php echo $item->taster_id;?>')">
	            		<span class="glyphicon glyphicon-edit"></span>Approve
	            		</a>-->
	            		<?php
	            			//}
	            		?>
	            		<?php
	            			//Approve requested job from agency/tester
	            			//if($item->request_job_approval_status=='waiting')
	            			//{
	            		?>
	            			<!--<a class="btn btn-primary btn-xs" href="javascript:void(0)" title="Approve"  onclick="approve_request(<?php echo $item->id;?>);"><span class="glyphicon glyphicon-edit"></span>Approve request</a>-->
	            		<?php
	            			//}
	            			//if($item->request_job_approval_status=='approved')
	            			//{
	            		?>
	            		<!--<span class="label label-success">Request approved</span>-->
	            		<?php
	            			//}
						if($item->job_state < 1){
						?>
							<a class="btn btn-danger btn-xs delete_button" href="javascript:void(0)" title="Delete" onclick="deleteJob(<?php echo $item->id; ?>);">
								<span class="glyphicon glyphicon-trash"></span> Delete
							</a>
						<?php
							}
						}
	            		?>
						<a class="btn btn-warning btn-xs" href="<?php echo base_url('App/Job/clone_job/'.$item->id);?>" title="Change information">
                            <span class="glyphicon glyphicon-edit"></span>Clone</a>
	            	</td>
					<td>
                    <?php
                    if($filters['status'] == "problems")
                    {
                        if($item->is_out_of_range == 1 || $item->is_out_of_range == 2){
							?>
								<span class="label label-success">Yes</span> <span style="padding: 5px;" class="label label-info"> <?php 
								if($item->is_out_of_range == 2){
									echo "End";
								}else{ ?> <?php
									echo "Start";
								}?> </span> 
		
								<a style="margin:2px" class="btn btn-primary btn-xs delete_button" href="javascript:void(0)" title="Delete" onclick="openMap(<?php echo $item->latitude; ?>,<?php echo $item->longitude; ?>, <?php echo $item->store_id; ?> );">
								<span class="glyphicon glyphicon-map-marker"></span> Location
								</a>
							<?php
								}else{?>
                            
                            <!-- <a class="btn btn-danger btn-xs delete_button" href="javascript:void(0)" id="inLocation" title="Delete" onclick="this.disabled = true">
                             <span class="glyphicon glyphicon-map-marker"></span>
                        </a> --> <span class="label label-warning center">No</span>
                        <?php } }?>
                    </td>
	            	<?php
	            		
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
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="largeModal">
  <div class="modal-dialog modal-lg" id="mapResult"></div>
</div>
<!--<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>-->
<script type="text/javascript">
	function openMap(latitude, longitude, store_id){
        $.ajax({
           type:'POST',
           url:"<?php echo base_url(); ?>App/job/map_render_modal/",
           data: {latitude:latitude, longitude:longitude, store_id:store_id},
           success:function(data){
            $("#mapResult").html(data);
            $('#largeModal').modal('show');
           }
        }); 

    }
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
	function deleteJob(jobId){
		swal({
			title: "Are you sure?",
			text: "Delete this Job!",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-danger",
			confirmButtonText: "Yes, delete it!",
			cancelButtonText: "No, cancel",
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function(isConfirm) {
			if (isConfirm) {
				$.ajax({
				   type:'POST',
				   url:"<?php echo base_url(); ?>App/job/deleteJob/",
				   data: {jobId:jobId},
				   
				   success:function(data){
					if(data == 1){
						$('#job'+jobId).remove(); 
						swal("Deleted!", "Your Job has been deleted.", "success");
					}else{
						swal("Deleted!", "Unsuccessful.", "error");
					}
				   }
				});
				
			} else {
				swal("Cancelled", "Your Job is safe", "error");
			}
		});
	}

</script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/sweetalert.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
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
<script>
// View page record by limit
	$('#view').on('change', function() {
		var view = $(this).val();
		window.location.href = base_url+"App/job/index/view/"+view;
	});
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