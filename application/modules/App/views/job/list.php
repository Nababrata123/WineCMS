
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
        $seg=$this->uri->segment(6);
		if($seg == 'billing_success')
		{?>
    <div class="alert alert-success alert-dismissable">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong>Well done!</strong>
        Job has been moved to billing successfully.
	</div>
    <?php } ?>
   

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

    <!-- <div>"Succes"</div> -->

<fieldset>
	<legend><span class="glyphicon glyphicon-filter"></span> Filters</legend>
       
        <div class="row">

            <!-- For Tasting Date -->
                <div  class="col-md-2" >
                    <div class="form-group" style="width:100%;">
                        <label for="inputName">Tasting date </label><br />
                        <input type="text" class="form-control datepicker" autocomplete="off" id="inputName" name="sampling_date" placeholder="Search here" style="width:100%;" value="<?php if (isset($filters['tasting_date']) && $filters['tasting_date']!="~") {echo $filters['tasting_date'];}?>" >
                    </div>
                </div>

            <!-- For Entry Date -->
                <div class="col-md-2">
                   <div class="form-group" style="width:100%;">
                        <label for="inputName">Entry date </label><br />
                        <input type="text" class="form-control datepicker" id="entrydate" autocomplete="off" name="entry_date" placeholder="Search here" style="width:100%;" value="<?php if (isset($filters['entry_date']) && $filters['entry_date']!="~") {echo $filters['entry_date'];}?>" >
                    </div>
                </div>

            <!-- For Store -->
                <div class="col-md-3">
                    <div class="form-group" style="width:100%;">
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
                </div>

             <!-- For sales rep-->
            <div class="col-sm-3">
                <div class="form-group" style="width:100%;">
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

                <!-- For Status Filter -->
            <?php if( $filters['status'] == "problems"){ ?>
                <div class="col-md-2">
                    <div class="form-group" style="width:100%;">
                        <label for="inputName" style="">Status</label><br />
                        <select name="search_by_status" class="form-control"  style="width:100%">
                        <option value="">Choose status</option>
                        <option value="accepted" <?php if($filters['search_by_status']=="accepted"){echo "selected";}?> >Accepted</option>
                        <option value="outOfRangeStart" <?php if($filters['search_by_status']=='outOfRangeStart'){echo "selected";}?> >Out of range start</option>
                        <option value="outOfRangeEnd" <?php if($filters['search_by_status']=='outOfRangeEnd'){echo "selected";}?> >Out of range end</option>
                        <!-- <option value="notCompleted" <?php if($filters['search_by_status']=='notCompleted'){echo "selected";}?> >Not completed</option>   -->
                        <option value="notSubmitted" <?php if($filters['search_by_status']=='notSubmitted'){echo "selected";}?> >Not submitted</option>               
                        </select>
                    </div>
                 </div>
                <?php }else{ ?> 
                    <div class="col-md-2">
                        <div class="form-group" style="width:100%;">
                            <label for="inputName" style="">Status</label><br />
                            <select name="search_by_status" class="form-control"  style="width:100%">
                            <option value="">Choose status</option>
                            <option value="pre_assigned" <?php if($filters['search_by_status']=='pre_assigned'){echo "selected";}?> >Pre-assigned</option>
                            <option value="assigned" <?php if($filters['search_by_status']=="assigned"){echo "selected";}?> >Assigned</option>
                            <option value="accepted" <?php if($filters['search_by_status']=="accepted"){echo "selected";}?> >Accepted</option>
                            <option value="completed" <?php if($filters['search_by_status']=='completed'){echo "selected";}?> >Completed</option>
                            <option value="canceled" <?php if($filters['search_by_status']=='canceled'){echo "selected";}?> >Canceled</option>
                            <option value="rejected" <?php if($filters['search_by_status']=='rejected'){echo "selected";}?> >Rejected</option>
                            </select>
                        </div>
                    
                    </div>
                    <?php } ?>
             
             <!-- End First Row div -->
            </div>

        <div class="row">
            <div class="col-md-6">&nbsp;</div>
        </div>

         <div class="row">

            <!-- For Sort By Date -->
            <div class="col-md-2">
                <div class="form-group" style="width:100%;">
                    <label for="inputName" style="">Sort by date</label><br/>
                    <select name="sort_by_date" class="form-control"  style="width:100%">
                    <option value="">Choose Date</option>
                    <option value="entryDate" <?php if($filters['sort_by_date']=='entryDate'){echo "selected";}?> >Entry date</option>
                    <option value="jobDate" <?php if($filters['sort_by_date']=='jobDate'){echo "selected";}?> >Job date</option>
                    </select>    
                 </div>
            </div>


             <!-- For Rating -->
             <div class="col-md-2">
                <div class="form-group" style="width:100%;">
                    <label for="inputName">Rating</label><br/>
                    <select name="search_by_rating" class="form-control"  style="width:100%">
                    <option value="">Choose Rating</option>
                    <option value="1" <?php if($filters['search_by_rating']==1){echo "selected";}?> >1☆</option>
                    <option value="2" <?php if($filters['search_by_rating']==2){echo "selected";}?> >2☆</option>
                    <option value="3" <?php if($filters['search_by_rating']==3){echo "selected";}?> >3☆</option>
                    <option value="4" <?php if($filters['search_by_rating']==4){echo "selected";}?> >4☆</option>
                    <option value="5" <?php if($filters['search_by_rating']==5){echo "selected";}?> >5☆</option>   
                    </select>
                </div>
             </div>


            <!-- For Taster -->
             <div class="col-md-3">
                <div class="form-group" style="width:100%;">
                    <label for="inputName">Taster/Agency </label><br/>
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
           

            <div class="col-sm-3">
                <div class="form-group" style="width:100%;">
                </div>
            </div> 
         

            <!-- For Search and Reset Button -->
            <div class="col-md-2">
                <div class="form-group" style="width:200px;">
                    <label>&nbsp;</label><br/>
                    <button type="submit" id="submitBtn" style="width:90px;" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>&nbsp;
                    <button type="button" class="btn btn-default" style="width:89px;" onclick="window.location='<?php echo base_url('App/job');?>'"><span class="glyphicon glyphicon-refresh"></span> Reset</button>
                </div>
            </div>

           <!-- End Second Row div -->
         </div>

        <div class="row">
            <div class="col-md-6">&nbsp;</div>
        </div>

	</fieldset>


    <ul class="nav nav-tabs">
		<li <?php if ($filters['status'] == "jobs" || $filters['status'] == "") {echo 'class="active"'; $filters['status'] = "";}?>><a href="<?php echo base_url('App/Job/index/status/jobs');?>">Jobs (<?php echo $jobs_count;?>)</a></li>
	    <li <?php if ($filters['status'] == "problems") {echo 'class="active"';}?>><a href="<?php echo base_url('App/Job/index/status/problems');?>">Problem Jobs (<?php echo $count_problems;?>)</a></li>
		<li style="float: right;">  <div class="row">
                        <div class="col-md-2" style="width: 15%;">
                            <label for="inputName" style="margin-top:10px; margin-right:50px;">Search:</label><br />
                        </div>
                        <div class="col-md-10">
                        <input Type="text" id="search_text" style="width: 90%; margin-right:50px;" class="form-control" name="search_text" placeholder="Search here" autocomplete="off" value="<?php if (isset($filters['search_text']) && $filters['search_text']!="~") {echo base64_decode($filters['search_text']);}?>">
                        </div>
        </div> </li>
	</ul>
	<?php echo form_close();?>

    <?php
		echo validation_errors();
		$attributes = array('class' => 'form-inline status-form', 'id' => 'user-status-form');
		echo form_open(base_url('App/Job/update_status'), $attributes);
	?>


<div class="table-responsive">

    <table class="table table-striped table-responsive" width="100%">
	    	<thead>
	    		<tr>
                    <th><input type="checkbox" id="checkall"></th>
	          		<th>Job date</th>
	          		<th style="width: 100px;">Start Time</th>
	          		<th style="width: 100px;">End Time</th>
					<th style="width: 130px;">Sales Rep</th>
					<th>Store</th>
					<th>Agency</th>
                    <th>Taster</th>
					<th>Status</th>
					<th>Rating</th>
					<th>Action</th>
	          	</tr>
	        </thead>
	        <tbody>

            <?php 
				if (count($jobs) == 0) { ?>
	            <tr>
	            	<td colspan="100%" align="center">Sorry!! No Jobs found.</td>
	            </tr>
                <?php } ?>
                
                <?php foreach($jobs as $item) { ?>
                <tr id="job<?php echo $item->id;?>">
                
                <!-- check box td -->
                <td>
                        <input type="hidden" name="currenttab"  value="<?php echo $filters['status']; ?>">
                        <?php $date_now = date("Y-m-d"); ?> 

                        <?php if($item->status!='completed'){ ?>
                        <?php if($item->is_archived!=1 && $filters['status'] != "problems"){ ?> 
                        <?php if($item->endtime_state< 1 && $item->job_state <1){ ?>
                            <input type="checkbox" name="item_id[<?php echo $item->id;?>]" class="checkbox-item" value="Y">
                        <?php }else if( $item->endtime_state==1 && $date_now > $item->tasting_date) {?>
                                    <input type="checkbox" name="item_id[<?php echo $item->id;?>]" class="checkbox-item" value="Y">
                        <?php } else if($item->endtime_state < 1 && $date_now > $item->tasting_date){?>
                            <input type="checkbox" name="item_id[<?php echo $item->id;?>]" class="checkbox-item" value="Y">
                       <?php }}?>

                        <?php if(($filters['status'] == "problems" && $item->finish_time != '00:00:00') || ($filters['status'] == "problems" && $date_now > $item->tasting_date)){?> 
                            <input type="checkbox" name="item_id[<?php echo $item->id;?>]" class="checkbox-item" value="Y">
                        <?php } }?>

                </td>
                
                <!-- tasting date -->
                <td>
	            		<?php 
                            //echo date("j-F-Y",strtotime($item->tasting_date)) ;
                             echo date("m/d/Y", strtotime($item->tasting_date));  
                        ?>
                </td>
                
                <!-- start time  -->
                <td>
					<?php 
						//echo $item->start_time;
						echo date('h:i:a', strtotime($item->start_time));
					?>
				</td>
                
                <!-- end time  -->
				<td>
					<?php 
						//echo $item->end_time;
						echo date('h:i:a', strtotime($item->end_time));
					?>
                </td>
                
                <!-- sales rep name -->

                <td>
					<?php
						$sales_rep=$this->Job_model->get_user_name($item->user_id);
						echo $sales_rep;
					?>
                </td>
                
                <!-- store name -->

                <td>
					<?php 
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

					?>
                </td>
                
                <!-- agency name and taster name -->

                <?php
				
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
                
                <!-- status column  -->
                <td>

                <!-- for jobs tags  -->
                <?php 
                    if($filters['status'] != "problems"){
                    
                    if($item->job_status==1 && ($item->agency_taster_id == 0 || $item->taster_id == '')){ ?>
                       <span class="label label-info">Pre-assigned</span>
                   <?php } else if($item->job_status==2 && ($item->agency_taster_id != 0 || $item->taster_id != '')){?>
                    <?php if($item->accept_status=='0' && $item->status=='rejected'){?>
                        <span class="label label-danger">Rejected</span>
                    <?php }else{ ?>
                        <span class="label label-warning">Assigned</span>
                   <?php }}else if($item->job_status==3 || $item->agency_taster_id != 0){
                            if($item->job_state==2  && $item->status=='completed'){ ?>
                                <span class="label label-success">Completed</span>
                        <?php }else if($item->job_status==3 && $item->accept_status==1 && $item->status=='cancelled'){?>
                        <span class="label label-danger">Canceled</span>
                        <?php }else if($item->job_status==3 && $item->accept_status==0){?>
                            <span class="label label-info">Pre-assigned</span>
                        <?php }else {?>
                                <span class="label label-primary">Accepted</span>
                        <?php } ?>
                    <?php } else if($item->accept_status=='0' && $item->status=='rejected'){?>
                        <span class="label label-danger">Rejected</span>
                    <?php } else if($item->job_status==3 && $item->accept_status==1 && $item->status=='cancelled'){?>
                        <span class="label label-danger">Canceled</span>
                    <?php } else if($item->job_state==2  && $item->status=='completed'){?>
                        <span class="label label-primary">Completed</span>
                    <?php }
                }?>


                <!-- for problem all tags -->
                <?php if($filters['status'] == "problems")
                    {   
                    if(($item->is_out_of_range == 1 && $item->endtime_state != 1) || ($item->is_out_of_range == 2 && $item->endtime_state != 1)){
                    ?><a style="margin:2px" href="javascript:void(0)" title="map" onclick="openMap(<?php echo $item->latitude; ?>,<?php echo $item->longitude; ?>, <?php echo $item->store_id; ?> );">
                        <span style="padding: 5px;" class="label label-warning"><?php 
                    if($item->is_out_of_range == 2){
                    echo "Out of Range - End";
                    }else if($item->is_out_of_range == 1){ ?> <?php
                    echo " Out of Range - Start";
                    }?> </span></a> 
                    <?php  }else if($item->job_state == '0'){ ?> 
                        <span class="label label-primary">Accepted</span>
                    <?php }else{?> 
                        <span style="padding: 5px;" class="label label-danger">Not Submitted</span>
                    <?php }}?>
            </td>


                <!--  rating column  -->
                <td><?php
                    
                    if(round($item->rating, 0)==1){
                         $item->rating='☆';
                    }elseif(round($item->rating, 0)==2){
                        $item->rating='☆☆';
                    }elseif(round($item->rating, 0)==3){
                         $item->rating='☆☆☆';
                    }elseif(round($item->rating, 0)==4){
                         $item->rating='☆☆☆☆';
                    }elseif(round($item->rating, 0)==5){
                         $item->rating='☆☆☆☆☆';
                    }else{
                         $item->rating='N/A';
                    }
                    echo '<span style ="margin-left:5px;margin-top:10px; background-color:#FF8C00; font-size:12px;" class="label label-warning">'.$item->rating.'</span>' ; ?>
                </td>

                <!--  Action Column  -->
                <?php
                            //get tasting date is over or not
                            $now = time(); // or your date as well
                            $your_date = strtotime($item->tasting_date);
                            $datediff = $now - $your_date;
                            $difference=round($datediff / (60 * 60 * 24));
                        	?>
                <td>
                    <?php echo '<a href="#'.$item->id.'" data-placement="bottom" data-toggle="popover" class="btn btn-info" data-container="body"  data-html="true" >&#9776</a>';?>

                    <div id="<?php echo $item->id;?>" class="hide">
                        <form class="form-inline" role="form">
                        <?php if($filters['status'] != "problems"){ ?> 
                        <a class="btn btn-warning btn-xs" href="<?php echo base_url('App/Job/clone_job/'.$item->id);?>" title="Change information" style="margin-top:2px; width: 75px;"><span class="glyphicon glyphicon-edit"></span> Clone</a> 
                        <?php } ?>
                        
                        <!-- JObs tag humbager inside button -->
                        <!-- JObs tag humbager inside button -->

                        <?php if(($item->job_status==1 && $item->confirm_status==0)) { ?>
                            <?php if($item->taster_id == ''){ ?>
                                    <a class="btn btn-info btn-xs" style="margin-top:2px; width: 75px;" href="<?php echo base_url('App/Job/publish_job/'.$item->id);?>" title="Publish">
                                    <span class="glyphicon glyphicon-edit"></span> Assign
                                </a>            
                           
                        <?php } }else if($item->accept_status==0 && $item->status=='rejected') {?> 
	            			<a class="btn btn-info btn-xs" style="margin-top:2px; width: 75px;" href="<?php echo base_url('App/Job/publish_job/'.$item->id);?>" title="Publish">
                                    <span class="glyphicon glyphicon-edit"></span> Assign
                                </a> 
                          <?php }else if(($item->job_status==3 && $item->accept_status==1 && $item->status=='cancelled')){?>
                            <a class="btn btn-info btn-xs" style="margin-top:2px; width: 75px;" href="<?php echo base_url('App/Job/publish_job/'.$item->id);?>" title="Publish">
                                    <span class="glyphicon glyphicon-edit"></span> Assign
                                </a>
                          <?php }?>
	            			


                        <!-- Problem tab hambuger button -->
                        <!-- Problem tab hambuger button  -->
                        <?php $date_now = date("Y-m-d"); ?>
                        <?php if (($filters['status'] != "problems" && $item->job_state != 2) || ($filters['status'] != "problems" && $item->endtime_state==1 && $date_now > $item->tasting_date)) { ?>
                            <a class="btn btn-danger btn-xs" href="javascript:void(0)" title="Delete" style="margin-top:2px; width: 75px;" onclick="deleteJob(<?php echo $item->id; ?>)"> <span class="glyphicon glyphicon-trash"></span> Delete</a>
                            <?php } ?>

                            <?php if( $item->is_archived!=1 && $filters['status'] != "problems"){?> 
                             
                             <?php if($item->endtime_state==1 && $date_now <= $item->tasting_date){ ?> 
                        
                                <?php if($item->endtime_state==1 || $item->endtime_state==2 || $item->endtime_state==4){ ?>
                                    <a class="btn btn-primary btn-xs" href="javascript:void(0)" title="Edit"  onclick="edit_modal(<?php echo $item->id;?>)" style="margin-top:2px; width: 75px;"> <span class="glyphicon glyphicon-edit"></span> Edit</a>
                                    <?php }else {?>
                                        <!-- <a class="btn btn-primary btn-xs" href="javascript:void(0)" title="Edit"  onclick="alert('The taster is setting up the tasting now, please wait until they start the job in order to edit the job end time')" style="margin-top:2px; width: 75px;"> <span class="glyphicon glyphicon-edit"></span> Edit</a> -->
                                        <!-- Open 27-8-21 only the end time should be editable -->
                                        <a class="btn btn-primary btn-xs" href="javascript:void(0)" title="Edit"  onclick="edit_modal(<?php echo $item->id;?>)" style="margin-top:2px; width: 75px;"> <span class="glyphicon glyphicon-edit"></span> Edit</a>

                                <?php }?> 
 
                                
                        <?php } else if($item->endtime_state==2 || $item->endtime_state==4 || $item->endtime_state==0){?> 
                            <a class="btn btn-primary btn-xs" href="javascript:void(0)" title="Edit"  onclick="edit_modal(<?php echo $item->id;?>)" style="margin-top:3px; margin-bottom: 2px; width: 75px;"> <span class="glyphicon glyphicon-edit"></span> Edit</a>
                        <?php }else if($item->endtime_state==1 && $date_now > $item->tasting_date){?> 
                                <a class="btn btn-primary btn-xs" href="javascript:void(0)" title="Edit"  onclick="edit_modal(<?php echo $item->id;?>)" style="margin-top:2px; width: 75px;"> <span class="glyphicon glyphicon-edit"></span> Edit</a> 
                        
                        <?php } ?>
                        <?php } ?>
                        <?php 
                            if( $item->is_archived!=1 &&  $filters['status'] == "problems" && $item->job_state == 1){ ?>
                                <?php if($difference == 0){ ?>
									<?php if($item->endtime_state==1 || $item->endtime_state==2 || $item->endtime_state==4 || $item->endtime_state==0){ ?> 
                                <!-- <a class="btn btn-primary btn-xs" href="javascript:void(0)" title="Edit"  onclick="alert('The taster is setting up the tasting now, please wait until they start the job in order to edit the job end time')" style="margin-top:2px; width: 75px;"> <span class="glyphicon glyphicon-edit"></span> Edit</a> -->

                                <!-- Open 27-8-21 only the end time should be editable -->
                                <a class="btn btn-primary btn-xs" href="javascript:void(0)" title="Edit"  onclick="edit_modal(<?php echo $item->id;?>)" style="margin-top:2px; width: 75px;"> <span class="glyphicon glyphicon-edit"></span> Edit</a>

                        		<?php }else if($item->endtime_state==3){?> 
                            	<a class="btn btn-primary btn-xs disabled" title="Edit" style="margin-top:2px; width: 75px;"> <span class="glyphicon glyphicon-edit"></span> Edit</a> 
                        		<?php } ?>
                                <?php } }?>

                                <?php if( $filters['status'] == "problems" && $item->job_state != '0' && ($item->endtime_state == '4')){ ?>
                                    <a class="btn btn-primary btn-xs" style="margin-top:2px; width: 95px;" href="javascript:void(0)" title="Activity" class="setup_button" onclick="view_setup_modal(<?php echo $item->id;?>)">
	            		                <span class="glyphicon glyphicon-edit"></span>
	            		                    Tasting setup
	            		                </a>
                                <?php } ?>
                                
                    <?php if($filters['status'] == "problems" && $item->job_state != '0' && ($item->endtime_state == '4' || $item->endtime_state == '1')){?>
                      <?php if($filters['status'] == "problems" && $item->ready_for_billing == 0){?>
                              <a class="btn btn-primary btn-xs" style="margin-top:2px; width: 75px;" href="javascript:void(0)" title="Billing"  onclick="problem_one_modal(<?php echo $item->id;?>,<?php echo $item->taster_id;?>)">
                              <span class="glyphicon glyphicon-edit"></span> Review</a>
                    <?php }else if( $item->is_out_of_range == 1 || $item->is_out_of_range == 2){?>
                               <a class="btn btn-primary btn-xs" style="margin-top:2px; width: 75px;" href="javascript:void(0)" title="Billing"  onclick="problem_one_modal(<?php echo $item->id;?>,<?php echo $item->taster_id;?>)">
                               <span class="glyphicon glyphicon-edit"></span> Review</a>
                      <?php } ?>
                       <?php } else{ ?>
                          <?php if($filters['status'] == "problems" && $item->ready_for_billing == 0 && $item->job_state == '0' && $item->endtime_state == '0'){?>
                                 <a class="btn btn-primary btn-xs" style="margin-top:2px; width: 95px;" href="javascript:void(0)" title="Billing"  onclick="problem_three_modal(<?php echo $item->id;?>,<?php echo $item->taster_id;?>)">
                                 <span class="glyphicon glyphicon-edit"></span> Create billing</a>
                        <?php } ?>
                    <?php } ?>
                                
                        
                                <!-- job tab delete  -->
                                <?php
                            
                            if (($filters['status'] == "problems" && $item->finish_time != '00:00:00') || ($filters['status'] == "problems" && $date_now > $item->tasting_date)){ ?>
                                <a class="btn btn-danger btn-xs delete_button" style="margin-top:2px; width: 75px;" href="javascript:void(0)" title="Delete" onclick="deleteJob(<?php echo $item->id; ?>);">
                                    <span class="glyphicon glyphicon-trash"></span> Delete
                                </a>
                                
                            <?php } ?>

                            <?php
                            
                            if ($filters['status'] != "problems" && $item->job_state == 2 ){ ?>
                
                                <a class="btn btn-danger btn-xs delete_button" style="margin-top:2px; width: 95px;" href="javascript:void(0)" title="Delete" onclick=" completed_job_details_view_modal(<?php echo $item->id; ?>);">
                                    <span class="glyphicon glyphicon-list-alt"></span> View Details
                                </a>

                                <a class="btn btn-success btn-xs" style="margin-top: 3px; width: 95px;" target="_blank" href="<?php echo base_url();?>App/job/createTextFile/<?php echo $item->id;?>" title="View"><span class="glyphicon glyphicon-download"></span> View Invoice</a>
                                
                            <?php } ?>
                        
                        </form>
                    </div>

                   
						<script type="text/javascript">
                        	$("[data-toggle=popover]").popover({
                                html: true, 
                                animation:true,
                                content: function() { return $('#<?php echo $item->id; ?>').html(); }
                            });
							
                         </script>
                    </td>


                <?php }?>
                </tbody>
			<tfoot>
                <tr>
                
                    <?php if(count($jobs) != 0){ ?> 
                    <td colspan="8">
                        With selected
                        <button type="submit" id="dltBtn" name="operation" value="delete" class="btn btn-sm btn-danger" style="margin-top:2px; width: 75px;" onclick="return confirm('Are you sure you want to delete the job(s)?')"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                    </td>
                    <?php }
                 ?>
                </tr>
            </tfoot>
	    </table></div>
	<?php echo form_close();?>

                <?php echo $this->pagination->create_links(); ?>

</div>


<!-- Modal -->
<!-- <div class="modal fade" id="thankyouModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <div style="text-align: center;"><strong>JOB</strong></div>
            </div>
            <div class="alert alert-dismissable" style="text-align: center;     background-color: #3c763d">
            <strong>Well done!</strong>
            Job has been moved to billing successfully.
	        </div>   
        </div>
    </div>
</div> -->

<div class="modal fade" id="thankyouModal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Data</h4>
                </div>
                <div class="modal-body">
                    <div class="fetched-data"><?php $message ?></div> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
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
  <div class="modal-dialog modal-lg" id="div_result"></div>
</div>
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="largeModal">
  <div class="modal-dialog modal-lg" id="mapResult"></div>
</div>

<!-- For loader -->
<div class="loader_img" style="display:none;"> </div>
<style type="text/css">
  .loader_img {
      position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url(<?php echo base_url('assets/images/loader.gif'); ?>) center no-repeat #fff;
    opacity: .6;
  }
</style>

    <script src="<?php echo base_url();?>assets/js/sweetalert.min.js"></script> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="<?php echo base_url()?>assets/js/chosen.jquery.js" type="text/javascript"></script>

    <?php if($this->session->flashdata('billing') != "") { ?>
    <script type="text/javascript">
        $(document).ready(function(){
            swal("Well done!", "Job has been moved to billing successfully.", "success");
        });
    </script>
    <?php } ?>

    <?php if($this->session->flashdata('job_create') != "") { ?>
    <script type="text/javascript">
        $(document).ready(function(){
            swal("Well done!", "Job has been created successfully.", "success");
        });
    </script>
    <?php } ?>

    <?php if($this->session->flashdata('job_create_complete') != "") { ?>
    <script type="text/javascript">
        $(document).ready(function(){
            swal("Well done!", "Job has been created and completed successfully.", "success");
        });
    </script>
    <?php } ?>

    <?php if($this->session->flashdata('clone_job_success') != "") { ?>
    <script type="text/javascript">
        $(document).ready(function(){
            swal("Well done!", "Job successfully cloned.", "success");
        });
    </script>
    <?php } ?>

    <?php if($this->session->flashdata('job_not_create') != "") { ?>
    <script type="text/javascript">
        $(document).ready(function(){
            swal("Oh snap!", "Change something and try again.", "waring");
        });
    </script>
    <?php } ?>
    
    <?php if($this->session->flashdata('job_publish_success') != "") { ?>
    <script type="text/javascript">
        $(document).ready(function(){
            swal("Oh snap!", "Job successfully published.", "success");
        });
    </script>
    <?php } ?>

<script type="text/javascript">

	function openMap(latitude, longitude, store_id){
        if (typeof latitude === 'number' && latitude !=0 && typeof longitude === 'number' && longitude !=0 ){
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
        else{
            swal("Oops!", "Sorry we could not plot the Map. Either the location permission was denied or the device did not pick up the device location coordinates correctly.", "warning");
            return false;
        }

    }
/*	function open_modal(job_id,accepted_tester_id,pre_tester_id)
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
	}*/
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
    	// alert(job_id);
    	// alert(taster_id);
        
    	$.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/job/open_problem_one_modal/",
		   data: {job_id:job_id,taster_id:taster_id},
           beforeSend:function(){
          jQuery('.loader_img').show();
        },
		   success:function(data){
		    $("#div_result").html(data);
		    $('#myModal').modal('show');
            $('.loader_img').hide();
            // $('.modal-dialog').draggable({
            //         handle: ".modal-header"
            //     });
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

    function completed_job_details_view_modal(job_id)
    {
        // alert(job_id);
    	$.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/job/completed_job_details_view_modal/",
		   data: {job_id:job_id},
		   
		   success:function(data){
		    $("#div_result").html(data);
		    $('#myModal').modal('show');
		   }
		});
    }

    function open_details_modal(job_id)
	{
		//alert(job_id);
		$.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/job/more_info/",
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
			//$("[data-toggle=popover]").popover('hide');
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
    // function set_question_modal(id)
    // {
    //     //alert(id);
    //     $.ajax({
	// 	   type:'POST',
	// 	   url:"<?php echo base_url(); ?>App/job/set_question_modal/",
	// 	   data: {job_id:id},
		   
	// 	   success:function(data){
	// 	   	//alert(data);
	// 	    $("#div_result").html(data);
	// 	    $('#myModal').modal('show');

	// 	   }
	// 	});
    // }
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
		var x= $('#myModal').is(':visible');
        if(x){
            $('#myModal').modal('hide');
        }
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
						//swal("Deleted!", "Your Job has been deleted.", "success");
						swal({ title: "Deleted!", text: "Your Job has been deleted.", type: "success", confirmButtonText: "ok", allowOutsideClick: "true" }, function () { location.reload(); })
						
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


    function problem_three_modal(job_id,taster_id)
     {

         $.ajax({
            type:'POST',
           url:"<?php echo base_url(); ?>App/job/open_problem_three_modal/",
           data: {job_id:job_id,taster_id:taster_id},
           beforeSend:function(){
          jQuery('.loader_img').show();
            },
            success:function(data){
             $("#div_result").html(data);
             $('#myModal').modal('show');
             $('.loader_img').hide();
            //  $('.modal-dialog').draggable({
            //         handle: ".modal-header"
            //     });
            }
         });
     }

</script>
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
			