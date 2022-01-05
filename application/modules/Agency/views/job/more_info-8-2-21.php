
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Details information</h4>
      </div>
      
      <div class="modal-body">
        <div class="form-group">
          <div class="col-md-12">
            <label class="col-sm-3 control-label">Sampling date:</label>
            <div class="col-sm-3">
                <?php //echo $more_job_info->sampling_date;
                    echo date("m/d/Y", strtotime($more_job_info->sampling_date));
                ?>
            </div>
          
            <label class="col-sm-3 control-label">Taster:</label>
            <div class="col-sm-3"><?php echo $more_job_info->taster_name;?></div>
          </div>
          <div class="col-md-12">
            <label class="col-sm-3 control-label">Store:</label>
            <div class="col-sm-3">
              
                <?php echo $more_job_info->store_name;?>
              </div>
          
            <label class="col-sm-3 control-label">Store zip:</label>
            <div class="col-sm-3"><?php echo $more_job_info->store_zipcode;?></div>
          </div>
		  <div class="col-md-12">
            <label class="col-sm-3 control-label">Store Address:</label>
			<div class="col-sm-9">
              <?php
                echo $more_job_info->address;
              ?>
              
            </div>
          </div>
          <div class="col-md-12">
            <label class="col-sm-3 control-label">Working hour:</label>
            <div class="col-sm-3">
              <?php
                if($more_job_info->working_hour>=$more_job_info->actual_time_difference)
                {
              ?>
              <font color="green"><?php echo $more_job_info->working_hour;?></font>
              <?php
                }
                else
                {
              ?>
              <font color="red"><?php echo $more_job_info->working_hour;?></font>
              <?php
                }
              ?>
              </div>
          
            <label class="col-sm-3 control-label">Sales rep:</label>
            <div class="col-sm-3"><?php echo $more_job_info->sales_rep_name;?></div>
          </div>
          <!-- Display wine details-->
          <div class="col-md-12">
          <div class="col-sm-12">
              <table border="1" width="100%">
                  <tr>
                      <th style="text-align:center;">Wine</th>
                      <th style="text-align:center;">Sampled</th>
                      <th style="text-align:center;">Sold</th>
                  </tr>
                  <?php 
                  if(count($more_job_info->wine_sampled_details)>0){
                    foreach($more_job_info->wine_sampled_details as $val)
                    {
                  ?>
                  <tr>
                        <td style="text-align:center;"><?php echo $val['name'];?></td>
                        <td style="text-align:center;"><?php echo $val['bottles_sampled'];?></td>
                        <td style="text-align:center;"><?php echo $val['bottles_sold'];?></td>
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
          </div>
          </div>
          <div class="col-md-12">
            <label class="col-sm-4 control-label">Total amount on rate:</label>
            <div class="col-sm-2">
              <?php echo $more_job_info->total_amount;?>
                
              </div>
          
            <label class="col-sm-4 control-label">Expense amount:</label>
            <div class="col-sm-2"><?php echo $more_job_info->expense_amount;?></div>
          </div>
          <div class="col-md-12">
            <label class="col-sm-4 control-label">Expense reason:</label>
            <div class="col-sm-8">
            <?php if($more_job_info->exp_reason!=''){echo $more_job_info->exp_reason;}else{echo "N/A";}?>
              </div>
          </div>
          <div class="col-md-12">
            <label class="col-sm-4 control-label">Receipt images:</label>
            <div class="col-sm-8">

                <?php
             
                  if(!empty($more_job_info->expense_images[0]['exp_images']))
                  {
                    foreach($more_job_info->expense_images as $img){
                    
                ?>
                    <div style="margin:0 5px 15px; float:left; width:40%;"><img data-enlargeable style="cursor: zoom-in" src="<?php echo BASE_URL.DIR_EXPENSE_IMAGE.$img['exp_images'];?>" width="70%"></div>
                    
                <?php
                    }
                  }
                  else
                  {
                    echo "No image available";
                  }
                ?>
              
              <br/><br/>   
            </div>
          </div>
          <div class="col-md-12"> 
               <label class="col-sm-4 control-label">Taster's Feedback:</label>
                <div class="col-sm-8"><?php if($more_job_info->general_note!=''){echo $more_job_info->general_note;}else{echo "Not Available";}?></div>
          </div>
      <div class="col-md-12">
			<?php 
			if($more_job_info->actual_start_time !='00:00:00'){
			?>
				<label class="col-sm-4 control-label">Actual start time:</label>
				<div class="col-sm-2">
					<?php echo date("g:i a", strtotime($more_job_info->actual_start_time));?>
                
				</div>
			<?php
			}
			if($more_job_info->actual_end_time != '00:00:00'){
			?>
				<label class="col-sm-4 control-label">Actual end time:</label>
				<div class="col-sm-2"><?php echo date("g:i a", strtotime($more_job_info->actual_end_time));?></div>
			<?php
			}
			?>
			</div>
        </div>
        
        
        
      </div>
      <div class="modal-footer">
        
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
      
    </div>
<script src="<?php echo base_url()?>assets/js/chosen.jquery.js" type="text/javascript"></script>
<script type="text/javascript">
// Image to Lightbox Overlay 
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
