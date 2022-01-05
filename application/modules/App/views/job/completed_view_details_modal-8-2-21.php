<style>
select {
  -moz-appearance: none;
  -webkit-appearance: none;
}

select::-ms-expand {
  display: none;
}
</style>

<!-- Modal content-->

    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">View Details</h4>
      </div>
      

     <div class="modal-body">
     <div class="form-group">
        
          <legend>Basic Info</legend>

          <div class="col-md-12">
          <div class="row">
          <?php
                   $start_hour=date('h',strtotime($job->start_time));
                   $end_hour=date('h',strtotime($job->end_time));
                   $start_minute=date('i',strtotime($job->start_time));
                   $end_minute=date('i',strtotime($job->end_time));
                   $start = date('A', strtotime($job->start_time));
                   $end = date('A', strtotime($job->end_time));
              ?>
            
            <label class="col-sm-3 control-label">Scheduled Start Time: </label>
            <div class="col-sm-3"><?php echo $start_hour,':',$start_minute,' ',$start ?></div>
             
            <label class="col-sm-3 control-label">Scheduled End Time: </label>
            <div class="col-sm-3"><?php echo $end_hour,':',$end_minute,' ',$end ?></div>
            </div>
          </div>


          <div class="col-md-12">
          <div class="row">
          <?php
                  $start_hour=date('h',strtotime($job->job_start_time));
                  $end_hour=date('h',strtotime($job->finish_time));
                  $start_minute=date('i',strtotime($job->job_start_time));
                  $end_minute=date('i',strtotime($job->finish_time));
                  $start = date('A', strtotime($job->job_start_time));
                  $end = date('A', strtotime($job->finish_time));
              ?>
            
            <label class="col-sm-3 control-label">Actual Start Time: </label>
            <div class="col-sm-3"><?php echo $start_hour,':',$start_minute,' ',$start ?></div>
             
            <label class="col-sm-3 control-label">Actual End Time: </label>
            <div class="col-sm-3"><?php echo $end_hour,':',$end_minute,' ',$end ?></div>
            </div>
          </div>


          <div class="col-md-12">
          <div class="row">
            <label for="inputPhone" class="col-sm-3 control-label">Store: </label>
           
            <?php foreach($store as $value){ ?>
                <?php if($value['id']==$job->store_id){ ?>
							    <div class="col-sm-3"><?php echo $value['name'];?></div>
								<?php 
								break; 
								 } ?>
					  <?php } ?>

            <label for="inputFirstName" class="col-sm-3 control-label">Sales Rep: </label>
              
              <?php foreach($sales_rep as $value){?>  
                      <?php if($value['id']==$job->user_id){ ?>
                        <div class="col-sm-3"><?php echo $value['last_name']." ".$value['first_name'];?></div>
                      <?php
                      break; 
                      } ?>    
                <?php } ?>
            
          </div>
          </div>

          <div class="col-md-12">
          <div class="row">
            <label for="inputPhone" class="col-sm-3 control-label">Agency: </label>
            <div class="col-sm-3"><?php echo $agency_name ?></div>
            
            <label for="inputPhone" class="col-sm-3 control-label"> Taster: </label>
            <div class="col-sm-3"><?php echo $taster_name->taster_name ?></div>
          </div>
          </div>


          <div class="col-md-12">
          <div class="row">
            <label class="col-sm-3 control-label">Store Image:</label>
             
              <?php 
              if(count($store_images)>0){
                foreach($store_images as $value){?>
                   <img data-enlargeable style="cursor: zoom-in" src="<?php echo BASE_URL.DIR_TASTING_SETUP_IMAGE.$value['image'];?>">&nbsp;
              <?php } }else{ echo '<div class="col-sm-3"> No images available</div>';}?>

          </div>
          </div>


          <div class="col-md-12">
          <div class="row">
            <label class="col-sm-3 control-label">Tasting Setup Images:</label>
             
              <?php 
              if(count($tasting_images)>0){
                foreach($tasting_images as $value){?>
                   <img data-enlargeable style="cursor: zoom-in" src="<?php echo BASE_URL.DIR_TASTING_SETUP_IMAGE.$value['image'];?>">&nbsp;
              <?php } }else{ echo '<div class="col-sm-3"> No images available</div>';}?>

          </div>
          </div>&nbsp;

      
       <legend>Wine Info</legend>

        <div class="col-md-12">
				<table border="1" width="100%">
					<tr>
						<th style="text-align:center;">Wine</th>
						<th style="text-align:center;">Sampled</th>
						<th style="text-align:center;">Sold</th>
					</tr>
					<?php 
					if(count($get_wine_info)>0){
						foreach($get_wine_info as $value)
						{
					?>
					<tr>
						<td style="text-align:center;"><?php echo $value['name'];?></td>
						<td style="text-align:center;"><?php echo $value['bottles_sampled'];?></td>
						<td style="text-align:center;"><?php echo $value['bottles_sold'];?></td>
					</tr>
					<?php
						}
					}else{
					?>
						<tr>
							<td colspan="3" style="text-align:center;">No wine sampled</td>
						</tr>
					<?php
					}
					?>
				</table>
				<br>
			</div>&nbsp;
            
  
      <legend>Manager Verification Details</legend>
<!-- Name Comment -->
         <div class="col-md-12">
            <div class="row">
            <label for="inputPhone" class="col-sm-3 control-label">Name:</label>
            <div class="col-sm-3"><?php if(isset($manager_verification_details[0]['first_name'])){echo $manager_verification_details[0]['first_name']." ".$manager_verification_details[0]['last_name'];}?></div>
            
            <label for="inputPhone" class="col-sm-3 control-label">Comment:</label>
            <div class="col-sm-3"><?php if($manager_verification_details[0]['comment'] !=''){echo $manager_verification_details[0]['comment'];}else{ echo 'N/A'; }?></div>
          </div>
          </div>

<!-- Signature Image -->
          <div class="col-md-12">
          <div class="row">
            <label for="inputPhone" class="col-sm-3 control-label">Signature Image:</label>
           
            <?php if(isset($manager_verification_details[0]['signature_img']))
          {
          ?>
        
          <div class="col-sm-3">
            <img src="<?php echo $manager_verification_details[0]['signature_img'];?>">
          </div>
          <?php } else{ echo '<div class="col-sm-3"> No images available</div>';}?>
          </div>
          </div>&nbsp;
          <br>


          <legend>Expense Details</legend>
        
        <!-- Expense Amount -->
          <div class="col-md-12">
          <div class="row">

          <label for="inputExpAmount" class="col-sm-3 control-label">Expense Amount: </label>
          <div class="col-sm-3"><?php 
             $expense_amount = ltrim($expense_details[0]['exp_amount'],'$');
            if($expense_amount != ''){ echo $expense_amount; }else{ echo "N/A"; }?></div>
           

           <label for="inputExpAmount" class="col-sm-3 control-label">Expense Reason: </label>
           <div class="col-sm-3"><?php
             if($expense_details[0]['exp_reason'] != ''){echo $expense_details[0]['exp_reason']; }else{ echo "N/A"; }?></div>
          </div>
          </div>

<!-- Receipt Image -->
          <div class="col-md-12">
          <div class="row">
            <label class="col-sm-3 control-label">Receipt Image:</label>
             
              <?php 
              if(count($expense_details[0]['support_imgs'])>0){
              foreach( $expense_details[0]['support_imgs']  as $imgindx){?>
              <?php if($imgindx != ''){?>
                <?php echo '<div class="col-md-4"><img data-enlargeable style="cursor: zoom-in" src="'.BASE_URL.DIR_EXPENSE_IMAGE.$imgindx.'"> </div>'; ?>
              <?php } else{ echo '<div class="col-sm-3"> No images available</div>';}?>
           <?php } }else{ echo '<div class="col-sm-3"> No images available</div>';}?>

          </div>
          </div>&nbsp;
          <br>

          <legend>More Info </legend>
<!-- Working Hour -->
          <div class="col-md-12">
          <div class="row">
          <?php
                  $start_hour=date('H:i',strtotime($job->working_hour));
                  $end_hour=date('h',strtotime($job->end_time));
                  $start_minute=date('i',strtotime($job->working_hour));
                  $end_minute=date('i',strtotime($job->end_time));
              ?>

                <?php
	            		$time = explode(':', $job->working_hour);
				        $total_minutes= ($time[0]*60) + ($time[1]) + ($time[2]/60);
				        if($job->agency_taster_id==0)
				            $taster_id=$job->taster_id;
				        else
                    $taster_id=$job->agency_taster_id;
                         $rate_per_hr=$job->taster_rate;
                if(isset($expense_details[0]['exp_amount']))
                  $exp_amount=ltrim($expense_details[0]['exp_amount'], '$');
                  else
                  $exp_amount=0;
				        $total_amount=number_format((($rate_per_hr / 60)*$total_minutes),2)+$exp_amount;
				        
	            	?>

            <label for="inputConfirmPassword" class="col-sm-3 control-label">Taster's Feedback: </label>
            <div class="col-sm-3"><?php if(isset($general_note)){echo $general_note;}   ?></div>

            <label for="inputExpAmount" class="col-sm-3 control-label">Working hour: </label>
            <div class="col-sm-3"><?php echo $start_hour,' ','hrs'; ?></div>

          </div>
          </div>


          <div class="col-md-12">
          <div class="row">
          <label for="inputExpAmount" class="col-sm-3 control-label">Total Amount</label>
            <div class="col-sm-3"><?php echo '$'.$total_amount;?></div>
          </div>
          </div>&nbsp;
      <br> 
      
        
    </div>
  </div>
  </div>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/chosen.css">-->
<script src="<?php echo base_url()?>assets/js/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script type="text/javascript">
$('.datepicker').datepicker({

    format: 'm/d/yyyy',
    todayHighlight: true,
    autoclose: true,
    minDate:new Date()
    //startDate: truncateDate(new Date()) 
});
function truncateDate(date) {
  return new Date(date.getFullYear(), date.getMonth(), date.getDate());
}
$('#select_all').click(function() {
       $('#testers option').prop('selected', true);
});
function get_tester(id,taster_id,wine_id)
{
  $.ajax({
       type:'POST',
       url:"<?php echo base_url(); ?>App/job/get_tester/",
       data: {store_id:id,taster_id:taster_id},
       success:function(data){
          $("#testers").html(data);
       }
  });
  $.ajax({
       type:'POST',
       url:"<?php echo base_url(); ?>App/job/get_wine/",
       data: {store_id:id,wine_id:wine_id},
       success:function(data){
          $("#wines").html(data);
       }
  });
  
}
function validate(){ 
  var a = confirm('Do you wish to edit the job?');
  if(a==true){
   // alert($('#wine').val());
    
  
      var  winevals= $("select[name=\'wine[]\']").map(function() {
        return $(this).val();
      }).toArray();
      winevals=winevals.filter(Number);
      var exp_amount = $('#exp_amount').val();
      var exp_reason = $('#exp_reason').val().trim();

    var job_date = $('#tasting_date2').val();
    var start_hour = $('#start_time_hour').val();
    var start_min = $('#start_time_minute').val();
    var time_one = $('#time_one').val();
    if(time_one == 'pm' && start_hour !=12){
      start_hour = 12+parseInt(start_hour);
    }
    if(time_one == 'am' && start_hour ==12){
      start_hour = 12-parseInt(start_hour);
    }
    var end_hour = $('#end_time_hour').val();
    var end_min = $('#end_time_minute').val();
    var time_two = $('#time_two').val();
    if(time_two == 'pm' && end_hour != 12){
      end_hour = 12+parseInt(end_hour);
    }
    if(time_two == 'am' && end_hour == 12){
      end_hour = 12-parseInt(end_hour);
    }
    var time1 =  new Date(job_date+' '+start_hour+':'+start_min+':00');
    var time2 =  new Date(job_date+' '+end_hour+':'+end_min+':00');
    var curtime = new Date();
    
    //alert(time1);
    //alert(curtime);
    var start_time = start_hour+':'+start_min+':00';
    var end_time = end_hour+':'+end_min+':00';
    var seconds =  (time2- time1)/1000;
    var min = seconds/60;
    var taster_id = $('#testers').val();
    if(job_date == ''){
      $.alert({ title: 'Oops!', type:'red', content: 'Select a job date.' });
      return false;
    }else if($('#start_time_hour').val()==''){
      $('#start_time_hour').focus();
      $.alert({ title: 'Oops!', type:'red', content: 'Select a start time.' });
      return false;
    }
    else if($('#end_time_hour').val()==''){
      $('#end_time_hour').focus();
      $.alert({ title: 'Oops!', type:'red', content: 'Select an end time.' });
      return false;
    }else if(min < 0){
      $('#end_time_hour').focus();
      $.alert({ title: 'Oops!', type:'red', content: 'End time should be greater than start time.' });
      return false;
    }
    else if($('#store').val()==''){
      $('#store').focus();
      $.alert({ title: 'Oops!', type:'red', content: 'Select a store.' });
      return false;
    }else if( exp_amount!='' && exp_reason==''){
      $('#exp_reason').focus();
      $('#exp_reason').val('');
      $.alert({ title: 'Oops!', type:'red', content: 'Please enter expense reason.' });
      return false;
    }else if( exp_amount=='' && exp_reason!=''){
      $('#exp_amount').focus();
      $.alert({ title: 'Oops!', type:'red', content: 'Please enter expense amount.' });
      return false;
    }
    else if($('#testers').val()==''){
      $('#testers').focus();
      $.alert({ title: 'Oops!', type:'red', content: 'Select a taster/agency.' });
      return false;
    }else if(winevals.length==0){
      $('#wine').focus();
      $.alert({ title: 'Oops!', type:'red', content: 'Please select atleast one wine' });
      return false;
    }else if(winevals.length!=0){
      var arr=winevals;
      arr =arr.filter(Number);
      let hasDuplicate = new Set(arr).size !== arr.length;
      if(hasDuplicate){
      $.alert({ title: 'Oops!', type:'red', content: 'Duplicate wines selected.', });
      return false;
      }else{
        return true;
      }
    }
  }else{
    return false;
  }
}

</script>

<script type="text/javascript">
// var wines=('#wineData').val();
// console.log("wineData", wines);
$(document).ready(function() {
	var max_fields      = 10; //maximum input boxes allowed
	var wrapper   		= $(".input_fields_wrap"); //Fields wrapper
	var add_button      = $(".add_field_button"); //Add button ID
	
	var x = 1; //initlal text box count
	$(add_button).click(function(e){ //on add input button click
    <?php
      $wine='<option value="">Select Wine</option>';
      $wineList = $get_wine_list;
      foreach($wineList as $w){
        $wine.='<option value="'.$w['id'].'">'.addslashes($w['name']).'</option>';
      }
    ?>
    
    var wine='<?php echo $wine;?>';
    
    //  alert(wine);
    var html ='<div class="row" style="margin-top:20px;"><div class="col-sm-6"><select class="form-control" id="wine" name="wine[]">'+wine+'</select></div><div class="col-sm-2"><input value="0" name="bottles_sampled[]" onkeypress="return onlyNumberKey(event)" class="form-control"></div><div class="col-sm-2"><input value="0" onkeypress="return onlyNumberKey(event)" name="bottles_sold[]" class="form-control"> </div><a href="#" class="remove_field"><strong><span class="glyphicon glyphicon-minus-sign" style="padding-top: 10px;"></strong></a></div>';
		e.preventDefault();      
			$(wrapper).append(html); //add input box
		
	});
	
	$(wrapper).on("click",".remove_field", function(e){ //user click on remove text
    var  winevals= $("select[name=\'wine[]\']").map(function() {
        return $(this).val();
      }).toArray();
      var arr=winevals;
      //arr =arr.filter(Number);
      //let hasDuplicate = arr.some((val, i) => arr.indexOf(val) !== i);

      if(arr.length>1){
        e.preventDefault(); $(this).parent('div').remove(); x--;
      }else{
      $.alert({ title: 'Oops!', type:'red', content: 'All wines can not be deleted. Atleast one wine needed in the list.', });
        return false;
      }
  });
});
  

      function onlyNumberKey(evt) { 
          
          // Only ASCII charactar in that range allowed 
          var ASCIICode = (evt.which) ? evt.which : evt.keyCode 
          if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)) 
              return false; 
          return true; 
      } 
      function isNumberKey(evt, obj) {
      var charCode = (evt.which) ? evt.which : event.keyCode
      var value = obj.value;
      var dotcontains = value.indexOf(".") != -1;
      if (dotcontains)
          if (charCode == 46) return false;
      if (charCode == 46) return true;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) 
          return false;
      return true;
      }
</script>
<script src="<?php echo base_url()?>assets/js/chosen.jquery.js" type="text/javascript"></script>
<script type="text/javascript">
$('img[data-enlargeable]').addClass('img-enlargeable').click(function(){
    var src = $(this).attr('src');
    var modal;
    function removeModal(){ modal.remove(); $('body').off('keyup.modal-close'); }
    modal = $('<div>').css({
        background: 'RGBA(0,0,0,.9) url('+src+') no-repeat center',
        backgroundSize: 'contain',
        width:'100%', height:'100%',
        position:'fixed',
        zIndex:'10000',
        top:'0', left:'0',
        cursor: 'zoom-out'
    }).click(function(){
        removeModal();
    }).appendTo('body');
    //handling ESC
    $('body').on('keyup.modal-close', function(e){
      if(e.key==='Escape'){ removeModal(); } 
    });
});

</script>