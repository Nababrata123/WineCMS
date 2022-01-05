
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
              <?php //echo $more_job_info->sampling_date;?>
              <?php echo date("j-F-Y",strtotime($more_job_info->sampling_date)) ;?>
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
          <div class="col-md-12">
            <label class="col-sm-4 control-label">Wine sampled details:</label>
            <div class="col-sm-8">
              <?php
                if(!empty($more_job_info->wine_sampled_details))
                {
                  
                  foreach($more_job_info->wine_sampled_details as $val)
                  {
              ?>
                  <label class="control-label">Wine:</label>
                  <span><?php echo $val['name'];?></span>
                  
                  <label class="control-label">Sampled:</label>
                  <span><?php echo $val['bottles_sampled'];?></span>
                  <br>
              <?php
                  }
                }
              else
              {
                echo "No wine sampled";
              }
              ?>
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
            <label class="col-sm-2 control-label">Expense reason:</label>
            <div class="col-sm-4">
              <?php echo $more_job_info->expense_reason;?>
                
              </div>
          
            <label class="col-sm-2 control-label">General note:</label>
            <div class="col-sm-4"><?php if($more_job_info->general_note!=''){echo $more_job_info->general_note;}else{echo "null";}?></div>
          </div>
          <div class="col-md-12">
            <label class="col-sm-2 control-label">Expense images:</label>
            
            <div class="col-sm-10">
              
              
                <?php
                  
                  if(!empty($more_job_info->expense_images))
                  {
                    foreach($more_job_info->expense_images as $img){
                    
                ?>
                    
                    <img src="<?php echo BASE_URL.DIR_EXPENSE_IMAGE.$img['exp_images'];?>">&nbsp;
                    
                <?php
                    }
                  }
                  else
                  {
                    echo "No image available";
                  }
                ?>
              
              
                
            </div>
            
           
            
          </div>
          <div class="col-md-8">
             <label class="col-sm-4 control-label">Admin note:</label>
            <div class="col-sm-4"><?php if($more_job_info->admin_note!=''){echo $more_job_info->admin_note;}else{echo "null";}?></div>
          </div>
        </div>
        
        
      </div>
      <div class="modal-footer">
        
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
      
    </div>
  