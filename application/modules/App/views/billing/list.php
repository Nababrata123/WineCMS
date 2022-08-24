<style>

.btn-success:hover {
	color: #fff;
	background-color: green;
	border: 3px solid #0e440bcf !important;
	font-size: 14px;
}

.btn-success.active, .btn-success:active, .open>.dropdown-toggle.btn-success {
	color: #fff;
	background-color: green;
	border: 3px solid #0e440bcf !important;
	font-size: 14px;
}

.btn-success.focus, .btn-success:focus {
	color: #fff;
	background-color: green;
	border: 3px solid #0e440bcf !important;
	font-size: 14px;
}

.btn-success1:hover {
    color: #fff;
    background-color: green;
    border: 1px solid #0e440bcf !important;
    font-size: 14px;
}

</style>

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/sweetalert.css">
<div class="subnav">
  <div class="container-fluid">
    <h1><span class="glyphicon glyphicon-print"></span> Billing Management</h1>
    <div id="sub-menu" class="pull-right">
      <ul class="nav nav-pills">
	  <li class="active"><a href="<?php echo base_url('App/Billing');?>"><span class="glyphicon glyphicon-print"></span> Billing</a></li>
      </ul>
    </div>
  </div>
</div>
<div class="container-fluid main">
  <div class="form-group"> 
   
  </div>
  <ol class="breadcrumb">
    <li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
    <li class="active">Billing Management</li>
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

		$attributes = array('class' => 'form-inline search-form', 'id' => 'billing-search-form', 'role' => 'form');
		echo form_open(base_url('App/billing/search_submit'), $attributes);
	?>
       
	   <fieldset>

<div class="row"><div class="col-md-12"><legend><span class="glyphicon glyphicon-filter"></span> Filters</legend></div></div>


<div class="row">
   <div class="col-md-12">
      <div class="col-md-1">
<div class="form-group">
     <select name="view" id="view" style="min-height:31px; margin:3px 0 0 0; float:right;" class="form-control">
 <option value="10" <?php echo (isset($filter['view']) && ($filter['view'] == '10') ? 'selected' : ''); ?>>10</option>
 <option value="20" <?php echo (isset($filter['view']) && ($filter['view'] == '20') ? 'selected' : ''); ?>>20</option>
 <option value="50" <?php echo (isset($filter['view']) && ($filter['view'] == '50') ? 'selected' : ''); ?>>50</option>
 <option value="100" <?php echo (isset($filter['view']) && ($filter['view'] == '100') ? 'selected' : ''); ?>>100</option>
 <option value="500" <?php echo (isset($filter['view']) && ($filter['view'] == '500') ? 'selected' : ''); ?>>500</option>
 </select>
</div>
</div>

<div class="col-md-3">
<div class="form-group">
 <label for="inputType">Search By </label>
 <select name="field" id="inputField" class="form-control">
 <option value="" selected>Select wine type</option>
 <option value="royal" <?php if ($filter['field'] == 'royal') { echo "selected";}?>>Royal</option>
 <option value="mix" <?php if ($filter['field'] == 'mix') { echo "selected";}?>>Myx</option>
 <option value="kayco" <?php if ($filter['field'] == 'kayco') { echo "selected";}?>>Kayco</option>
 <option value="other" <?php if ($filter['field'] == 'other') { echo "selected";}?>>Other</option>
 </select>
</div>
</div>

<div class="col-md-3 search-btn">
 <div class="form-group">
<button type="submit" id="submitBtn" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>&nbsp;
<button type="button" class="btn btn-default" onclick="window.location='<?php echo base_url('App/billing');?>'"><span class="glyphicon glyphicon-refresh"></span> Reset</button>
 </div>
</div>

<div class="col-md-2">
<div class="form-group" >
<label for="inputType">Sort By: </label>
 <select class="form-control" name="sort" id="sort" style="min-height:31px; margin:3px 0 0 0;">
<option value="" <?php echo (isset($filter['field']) && ($filter['field'] == 'date') ? 'selected' : ''); ?>>Sort by</option>
<option value="date" <?php echo (isset($filter['field']) && ($filter['field'] == 'date') ? 'selected' : ''); ?>>Date</option>
<option value="store" <?php echo (isset($filter['field']) && ($filter['field'] == 'store') ? 'selected' : ''); ?>>Store</option>
<option value="taster" <?php echo (isset($filter['field']) && ($filter['field'] == 'taster') ? 'selected' : ''); ?>>Taster</option>
<option value="agency" <?php echo (isset($filter['field']) && ($filter['field'] == 'agency') ? 'selected' : ''); ?>>Agency</option>
<option value="salesrep" <?php echo (isset($filter['field']) && ($filter['field'] == 'salesrep') ? 'selected' : ''); ?>>Sales rep</option>
  </select>
   </div>
 </div>
 
	<div class="col-md-3">
		<label style="float:right; margin: 0 0 0 0;" >Search: <input type="text" autocomplete="off" name="search_text" id="search_text" class="Store Address" placeholder="" aria-controls="user-table" value="<?php if(isset($filter['search_text']) && $filter['search_text']!="~"){echo urldecode($filter['search_text']);}?>"></label>
      </div>
    </div>
   </div>


		<div class="row">
		<div class="col-md-12">&nbsp;</div>
    </div>
</fieldset>
  <?php echo form_close();?>
  <?php
		echo validation_errors();
		$attributes = array('class' => 'form-inline status-form', 'id' => 'user-status-form');
		echo form_open(base_url('App/billing/generate_csv'), $attributes);
	?>
  <div class="table-responsive"> 
    <!-- Table -->
    <table class="table table-striped table-responsive" width="100%">
      <thead>
        <tr>
          <th><input type="checkbox" id="checkall"></th>
          <th>Date</th>
          <th>Start time</th>
          <th>End time</th>
          <!--<th>Admin note</th>-->
          <th>Sales rep</th>
          <th>Taster</th>
          <!--<th>Account</th>
          <th>Billing date</th>-->
          <th>Agency</th>
		  <th>Store Name</th>
          <th>Actual start time</th>
          <th>Actual end time</th>
          <th>Working hour</th>
          <th>Total Amount</th>
          <!-- <th>Q/A</th> -->
          <th>Additional info</th>
          <th>Details</th>
          <th>Expense</th>
          <th>Invoice</th>
		  <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($jobs) == 0) { ?>
        <tr>
          <td colspan="100%" align="center">Sorry!! No Jobs found.</td>
        </tr>
        <?php } 
		/* echo '<pre>';
		print_r($jobs);
		echo '</pre>'; */
		?>
        <?php foreach($jobs as $item) { ?>
        <tr id="job<?php echo $item->id;?>">
			<td>
                <input type="hidden" name="currenttab"  value="<?php echo $page; ?>">
                <input type="checkbox" name="item_id[<?php echo $item->id;?>]" class="checkbox-item" value="<?php echo $item->id;?>"></td>
			
			<td><?php //echo date("j-F-Y",strtotime($item->tasting_date)) ;
                            echo date("m/d/Y", strtotime($item->tasting_date));
                        ?></td>
			<td><?php //echo $item->start_time ;
                            echo date('h:i:a', strtotime($item->start_time));
                        ?></td>
			<td><?php //echo $item->end_time ;
                            echo date('h:i:a', strtotime($item->end_time));
                        ?></td>
			<!--<td><?php //echo substr($item->admin_note,0,50) ;?></td>-->
			<td><?php
				$sales_rep=$this->Job_model->get_salesrep_name($item->user_id);
				echo $sales_rep;
			?></td>
			<?php 
			$ts_name;
			$ag_name;
			$user_type=get_user_type('users',$item->taster_id);
			if($user_type=='agency')
			{
				$ts_name = $this->Job_model->get_user_name($item->agency_taster_id);
				$ag_name = get_agency_name('user_meta',$item->taster_id);
			}
			else
			{
				$ts_name = $item->taster_name;
				$ag_name ='N/A';
			}
			?>
			<td><?php echo $ts_name;?></td>
			<td><?php echo $ag_name;?></td>
			
			<td><?php echo $item->store_name;?></td>
			<td><?php //echo $item->job_start_time;
                            echo date('h:i:a', strtotime($item->job_start_time));
                        ?></td>
			<td><?php 
                            //echo $item->finish_time;
							//echo date('h:i:a', strtotime($item->finish_time));
							if ($item->finish_time=='00:00:00'){
                                echo 'N/A';
                            }else{
                                echo date('h:i:a', strtotime($item->finish_time));
                            }
                        ?></td>
			<td><?php echo $item->working_hour;?></td>
			<?php
	            		$time = explode(':', $item->working_hour);
				        $total_minutes= ($time[0]*60) + ($time[1]) + ($time[2]/60);

				        if($item->agency_taster_id==0){
				            $taster_id=$item->taster_id;
						 } else{
				            $taster_id=$item->agency_taster_id;
						 }
				        //$rate_per_hr=get_taster_rate_per_hour($taster_id);
                        
						 if ($item->current_taster_rate != 0){
							$rate_per_hr=$item->current_taster_rate;
						 }else{
							$rate_per_hr=$item->taster_rate;
						}

				        $exp_amount=ltrim($item->exp_amount, '$'); 
				        $total_amount=number_format((($rate_per_hr / 60)*$total_minutes),2)+$exp_amount;
				        
	            	?>
			<td><?php 
	            			//echo $item->exp_amount;
	            			echo "$".$total_amount;
	            		?></td>
			<!-- <td><a class="btn btn-info btn-xs" href="javascript:void(0)" title="View" onclick="open_qa_modal(<?php echo $item->id;?>)"> <span class="glyphicon glyphicon-eye-open"></span> View </a></td> -->
			<td><a class="btn btn-info btn-xs" href="javascript:void(0)" title="View" onclick="open_modal(<?php echo $item->id;?>)"> <span class="glyphicon glyphicon-eye-open"></span> View </a></td>
			<td><a class="btn btn-info btn-xs" href="javascript:void(0)" title="View" onclick="completed_billing_details_view_modal(<?php echo $item->id;?>)"> <span class="glyphicon glyphicon-eye-open"></span> View </a></td>
			<td><a class="btn btn-info btn-xs" href="javascript:void(0)" title="View" onclick="open_expense_modal(<?php echo $item->id;?>)"> <span class="glyphicon glyphicon-eye-open"></span> View </a></td>
			<td>
				<a class="btn btn-success btn-xs" target="_blank" href="<?php echo base_url();?>App/billing/download_invoice/<?php echo $item->id;?>" title="View" style="font-size: 15px;"><span class="glyphicon glyphicon-download"></span></a>
			</td>
			<td>
			<a class="btn btn-primary btn-xs" style="width: 95px;" title="Delete" onclick="edit_modal(<?php echo $item->id;?>)"><span class="glyphicon glyphicon-edit"></span> Edit Invoice</a>
            <a class="btn btn-warning btn-xs" style="width: 95px; margin-top:2px;" title="View" onclick="moveToArchive(<?php echo $item->id;?>);"><span class="glyphicon"></span>Move to archive</a>
            </td>
        
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
  <input type="submit" name="Export to csv" value="Export to csv" class="btn btn-success btn-success1">
  <button  value="move_to_archive" class="btn btn-warning" id="move_to_archive">Move to archive</button>
  <?php if(count($jobs) != 0){ ?>  
  <button type="submit" id="dltBtn" name="operation" style="margin-left: 1px;" value="delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete the job(s)?')"><span class="glyphicon glyphicon-trash"></span> Delete</button> 
  <?php }?>
  <?php echo form_close();?> <?php echo $this->pagination->create_links(); ?> </div>
<!-- Modal -->
<div class="modal fade bs-example-modal-lg" id="userDetailsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body"> 
        <!-- Remote data loads here --> 
        <span class="glyphicon glyphicon-hourglass"></span> Loading please wait ... </div>
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
<script src="<?php echo base_url();?>assets/js/sweetalert.min.js"></script> 
<script type="text/javascript">
	function edit_modal(job_id)
    {
        $.ajax({
           type:'POST',
           url:"<?php echo base_url(); ?>App/billing/open_edit_job_modal/",
           data: {job_id:job_id},
           
           success:function(data){
            //alert(data);
            //$("[data-toggle=popover]").popover('hide');
            $("#div_result").html(data);
            $('#myModal').modal('show');

           }
        });
    }
	function open_modal(job_id)
	{
		//alert(job_id);
		$.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/billing/view_details/",
		   data: {job_id:job_id},
		   success:function(data){
		    $("#div_result").html(data);
		    
		    $('#myModal').modal('show');
		   }
		}); 
	}

    function completed_billing_details_view_modal(job_id)
    {
        // alert(job_id);
    	$.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/job/completed_billing_details_view_modal/",
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
		   url:"<?php echo base_url(); ?>App/billing/more_info/",
		   data: {job_id:job_id},
		   success:function(data){
		    $("#div_result").html(data);
		    
		    $('#myModal').modal('show');
		   }
		}); 

	}
	function open_qa_modal(job_id)
	{
		$.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/billing/question_answers/",
		   data: {job_id:job_id},
		   success:function(data){
		    $("#div_result").html(data);
		    
		    $('#myModal').modal('show');
		   }
		}); 
	}
    function open_expense_modal(job_id)
    {
        $.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/billing/get_expenses_with_brand/",
		   data: {job_id:job_id},
		   success:function(data){
		    $("#div_result").html(data);
		    
		    $('#myModal').modal('show');
		   }
		});
    }
	$("#gcsv").click(function(){
		//alert(1);
		generate_csv();
	});
	function generate_csv()
	{
		$.ajax({
		   type:'POST',
		   url:"<?php echo base_url();?>App/billing/index/action/csv",
		   /*data: {job_id:job_id},
		   success:function(data){
		    $("#div_result").html(data);
		    
		    $('#myModal').modal('show');
		   }*/
		});
	}
    $("#move_to_archive").click(function(e){
        e.preventDefault();
        //var pageURL = $(location).attr("href");
        //alert(pageURL);
        var favorite = [];
        $.each($(".checkbox-item:checked"), function(){            
            favorite.push($(this).val());
        });
        var checked_value=favorite.join(", ");
        if(checked_value=='')
        {
            
            swal("Please select at least one record!");
        }
        else
        {
			swal({
				title: "Are you sure?",
				text: "You will not be able to see this job (s) in billing section!",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn-danger",
				confirmButtonText: "Yes, Move to archive!",
				cancelButtonText: "No, cancel!",
				closeOnConfirm: false,
				closeOnCancel: false
			},
			function(isConfirm) {
				if (isConfirm) {
					$.ajax({
					   type:'POST',
					   url:"<?php echo base_url();?>App/billing/moved_to_archive",
					   data: {checked_value:checked_value},
					   success:function(data){
						swal({title: "Moved!", text: "Job has been moved to archive successfully.",timer: 1000, type: "success"},
								function(){ 
								   location.reload();
								   tr.hide();
								});
					   },
					   error: function() {
						swal({title: "Oops!", text: "Job are not moved to archive!.",timer: 1000, type: "error"},
								function(){ 
								   location.reload();
								   tr.hide();
								});
						}
					});
				} else {
					// swal("Cancelled", "Your job has not been moved.", "error");
					swal({title: "Cancelled", text: "Your job has not been moved.",timer: 1000, type: "error"},
						function(){ 
							location.reload();
							tr.hide();
						});
				}
			});
        }
        
        
    });

	// View page record by limit
	$('#view').on('change', function() {
		var view = $(this).val();
		var search_text = $('#search_text');
        var search_text_val = search_text.val().trim();
        var inputField=$('#inputField').val();
        var sort=$('#sort').val();
		
        if(inputField!=''){
            if(sort!=''){
                window.location.href = base_url+"App/billing/index/field/"+sort+"/view/"+view;
            }else{
                window.location.href = base_url+"App/billing/index/field/"+inputField+"/view/"+view;
            }
        }else if(search_text_val!=''){
			if(sort!=''){
				window.location.href = base_url+"App/billing/index/field/"+sort+"/view/"+view;
			}else{
				window.location.href = base_url+"App/billing/index/search_text/"+search_text_val+"/view/"+view;
			}  
        }else{
            if(sort!=''){
                window.location.href = base_url+"App/billing/index/field/"+sort+"/view/"+view;
            }else{
                // window.location.href = base_url+"App/billing/index/page/1/view/"+view;
				window.location.href = base_url+"App/billing/index/view/"+view;
            }
        }
        
    });

	// sort by record 
    $('#sort').on('change', function() {
        var sort = $(this).val();
        window.location.href = base_url+"App/billing/index/field/"+sort;
    });
	//move to archive single jobs
	function moveToArchive(jobId){
		swal({
			title: "Are you sure?",
			text: "You will not be able to see this job in billing section!",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-danger",
			confirmButtonText: "Yes, Move to archive!",
			cancelButtonText: "No, cancel!",
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function(isConfirm) {
			if (isConfirm) {
				$.ajax({
					type:'POST',
					url:"<?php echo base_url();?>App/billing/moved_to_archive",
					data: {checked_value:jobId},
					success:function(data){
						if(data){
							$('#job'+jobId).remove();
							// swal("Moved!", "Job has been moved to archive successfully.", "success");
							swal({title: "Moved!", text: "Job has been moved to archive successfully.",timer: 1000, type: "success"},
								function(){ 
								   location.reload();
								   tr.hide();
							});
						}else{
							// swal("Moved!", "Your job has not been moved.", "error");
							swal({title: "Moved!", text: "Your job has not been moved.",timer: 1000, type: "error"},
								function(){ 
								   location.reload();
								   tr.hide();
							});
						}
					}
				});
			} else {
				// swal("Cancelled", "Your job has not been moved.", "error");
				swal({title: "Cancelled", text: "Your job has not been moved.",timer: 1000, type: "error"},
					function(){ 
						location.reload();
						tr.hide();
					});
			}
		});
	}


// 	$('#view').on('change', function() {
//         var view = $(this).val();
//         var search_text = $('#search_text');
//         var search_text_val = search_text.val().trim();
//         if(search_text_val!=''){
//             window.location.href = base_url+"App/billing/index/search_text/"+search_text_val+"/view/"+view;
//         }else{
//             window.location.href = base_url+"App/billing/index/view/"+view;
// }

//     });


$(document).ready(function(){
       var timeout;
       var delay = 1000; // 2 seconds
       var fieldInput = $('#search_text');
       var fldLength= fieldInput.val().length;
       fieldInput.focus();
       fieldInput[0].setSelectionRange(fldLength, fldLength);
       //$("#search_text").select();
       $("#search_text").on("keyup", function() {
       //console.log(fieldInput.val());
        if(timeout) {
        clearTimeout(timeout);
        }
        timeout = setTimeout(function() {
        $('#submitBtn').click();
       }, delay);
        });
     });
	 
</script>