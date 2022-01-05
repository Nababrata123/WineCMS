<div class="subnav">
  <div class="container-fluid">
    <h1><span class="glyphicon glyphicon-user"></span> Billing Management</h1>
    <div id="sub-menu" class="pull-right">
      <ul class="nav nav-pills">
        <li class="active"><a href="<?php echo base_url('App/Billing');?>"><span class="glyphicon glyphicon-user"></span> Billing</a></li>
      </ul>
    </div>
  </div>
</div>
<div class="container-fluid main">
  <div class="form-group"> 
    <!--div class="col-sm-12 text-right">

	   		<div class="btn-group" role="group" aria-label="...">
				

			  	<div class="btn-group" role="group">
			    	<button type="button" class="btn btn-info"><span class="glyphicon glyphicon-download-alt"></span> Export To</button>
						<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			      		<span class="caret"></span>
			      		<span class="sr-only">Toggle Dropdown</span>
			    	</button>

			    	
			  	</div>
			</div>

	    </div--> 
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
    <div class="col-md-12" style="clear:both; text-align:right;"><a  href="<?php echo base_url('App/billing/get_expenses_brandwise')?>" >Get expenses brandwise </a></div><div style="clear:both; margin:0 0 20px;"></div>
  <?php
		echo validation_errors();

		$attributes = array('class' => 'form-inline search-form', 'id' => 'billing-search-form', 'role' => 'form');
		echo form_open(base_url('App/billing/search_submit'), $attributes);
	?>
       
  <fieldset>

    <div class="row"><div class="col-md-12"><legend><span class="glyphicon glyphicon-filter"></span> Filters</legend></div></div>

    <div class="row">
      <div class="col-md-12">
        <div class="col-md-10">
         <div class="col-md-3">
          <div class="form-group">
            <label for="inputType">Search By </label>

            <select name="field" id="inputField" class="form-control">
              <option value="" selected>Select wine type</option>
              <option value="royal" <?php if ($filter['field'] == 'royal') { echo "selected";}?>>Royal</option>
              <option value="mix" <?php if ($filter['field'] == 'mix') { echo "selected";}?>>Myx</option>
            </select>
          </div>
          </div>
          <div class="col-md-5 search-btn">
          <div class="form-group">
            <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>
            &nbsp;
            <button type="button" class="btn btn-default" onclick="window.location='<?php echo base_url('App/billing');?>'"><span class="glyphicon glyphicon-refresh"></span> Reset</button>
          </div>
          </div>
        </div>
        <div class="col-md-2">
          <select name="view" id="view" style="min-height:31px; margin:3px 0 0 0; float:right;">
            <option value="10" <?php echo (isset($filter['view']) && ($filter['view'] == '10') ? 'selected' : ''); ?>>10</option>
            <option value="20" <?php echo (isset($filter['view']) && ($filter['view'] == '20') ? 'selected' : ''); ?>>20</option>
            <option value="50" <?php echo (isset($filter['view']) && ($filter['view'] == '50') ? 'selected' : ''); ?>>50</option>
            <option value="100" <?php echo (isset($filter['view']) && ($filter['view'] == '100') ? 'selected' : ''); ?>>100</option>
            <option value="500" <?php echo (isset($filter['view']) && ($filter['view'] == '500') ? 'selected' : ''); ?>>500</option>
          </select>
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
          <th>Account</th>
          <th>Billing date</th>
          <th>Actual start time</th>
          <th>Actual end time</th>
          <th>Working hour</th>
          <th>Total Amount</th>
          <th>Q/A</th>
          <th>Additional info</th>
          <th>Details</th>
          <th>Expense</th>
          <th>Invoice</th>
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
          <td><input type="checkbox" name="item_id[<?php echo $item->id;?>]" class="checkbox-item" value="<?php echo $item->id;?>"></td>
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
	            			$sales_rep=$this->Job_model->get_user_name($item->user_id);
	            			echo $sales_rep;
	            		?></td>
          <td><?php 
                            $user_type=get_user_type('users',$item->taster_id);
                            if($user_type=='agency')
                            {
                                echo get_agency_name('user_meta',$item->taster_id);
                            }
                            else
                            {
                                echo $item->taster_name;
                            }
                            
                        ?></td>
          <td><?php echo $item->account_no;?></td>
          <td><?php //echo date("j-F-Y",strtotime($item->billing_date));
                         echo date("m/d/Y", strtotime($item->billing_date));
                        ?></td>
          <td><?php //echo $item->job_start_time;
                            echo date('h:i:a', strtotime($item->job_start_time));
                        ?></td>
          <td><?php 
                            //echo $item->finish_time;
                            echo date('h:i:a', strtotime($item->finish_time));
                        ?></td>
          <td><?php echo $item->working_hour;?></td>
          <?php
	            		$time = explode(':', $item->working_hour);
				        $total_minutes= ($time[0]*60) + ($time[1]) + ($time[2]/60);
				        if($item->agency_taster_id==0)
				            $taster_id=$item->taster_id;
				        else
				            $taster_id=$item->agency_taster_id;
				        $rate_per_hr=get_taster_rate_per_hour($taster_id);
				        $exp_amount=ltrim($item->exp_amount, '$'); 
				        $total_amount=number_format((($rate_per_hr / 60)*$total_minutes),2)+$exp_amount;
				        
	            	?>
          <td><?php 
	            			//echo $item->exp_amount;
	            			echo "$".$total_amount;
	            		?></td>
          <td><a class="btn btn-info btn-xs" href="javascript:void(0)" title="View" onclick="open_qa_modal(<?php echo $item->id;?>)"> <span class="glyphicon glyphicon-eye-open"></span> View </a></td>
          <td><a class="btn btn-info btn-xs" href="javascript:void(0)" title="View" onclick="open_modal(<?php echo $item->id;?>)"> <span class="glyphicon glyphicon-eye-open"></span> View </a></td>
          <td><a class="btn btn-info btn-xs" href="javascript:void(0)" title="View" onclick="open_details_modal(<?php echo $item->id;?>)"> <span class="glyphicon glyphicon-eye-open"></span> View </a></td>
          <td><a class="btn btn-info btn-xs" href="javascript:void(0)" title="View" onclick="open_expense_modal(<?php echo $item->id;?>)"> <span class="glyphicon glyphicon-eye-open"></span> View </a></td>
          <td><?php
                            /*if($item->has_invoice==1)
                            {*/
                        ?>
            <a class="btn btn-success btn-xs" target="_blank" href="<?php echo base_url();?>App/billing/download_invoice/<?php echo $item->id;?>" title="View"><span class="glyphicon glyphicon-download"></span></a>
            <?php 
                            /*}
                            else
                            {
                                echo "N/A";
                            }*/
                        ?>
          </td>
            
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
  <input type="submit" name="Export to csv" value="Export to csv" class="btn btn-success">
  <button  value="move_to_archive" class="btn btn-warning" id="move_to_archive">Move to archive</button>
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
  <div class="modal-dialog" id="div_result"></div>
</div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> 
<script type="text/javascript">
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
            $.ajax({
               type:'POST',
               url:"<?php echo base_url();?>App/billing/moved_to_archive",
               data: {checked_value:checked_value},
               success:function(data){
                    /*swal("Job has been moved to archived successfully");
                    window.location.reload();*/
                   
                   swal("Job has been moved to archived successfully.")
                    .then((value) => {
                      window.location.reload();
                    });
               },
               error: function() {
                    /*swal("Job are not moved to archive!");
                    window.location.reload();*/
                   
                    swal("Job are not moved to archive!")
                    .then((value) => {
                      window.location.reload();
                    });
                }
            });
        }
        
        
    });
	// View page record by limit
	$('#view').on('change', function() {
		var view = $(this).val();
		window.location.href = base_url+"App/billing/index/view/"+view;
	});
</script>