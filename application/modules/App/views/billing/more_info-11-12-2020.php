
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Details Information</h4>
      </div>
      
      <div class="modal-body">
        <div class="form-group">
          <div class="col-md-12">
            <div class="row">
            <label class="col-sm-3 control-label">Sampling date:</label>
            <div class="col-sm-3">
              
              <?php 
                //echo date("j-F-Y",strtotime($more_job_info->sampling_date)) ;
                echo date("m/d/Y", strtotime($more_job_info->sampling_date));
              ?>
              </div>
           
            <label class="col-sm-3 control-label">Taster:</label>
            <div class="col-sm-3"><?php echo $more_job_info->taster_name;?></div>
          </div>
          </div>
          
          <div class="col-md-12">
           <div class="row">
            <label class="col-sm-3 control-label">Store:</label>
            <div class="col-sm-3">
              
                <?php echo $more_job_info->store_name;?>
              </div>
            <label class="col-sm-3 control-label">Store zip:</label>
            <div class="col-sm-3"><?php echo $more_job_info->store_zipcode;?></div>
          </div>
          </div>
          
          <div class="row">
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
          </div>
			<div class="col-md-12">
				<label class="control-label">Sampled wine details:</label>
			</div>
			<div class="col-md-12">
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
          <div class="row">
          <div class="col-md-12">
            <label class="col-sm-3 control-label">Total amount on rate:</label>
            <div class="col-sm-3">
              <?php echo $more_job_info->total_amount;?>
                
              </div>
          
            <label class="col-sm-3 control-label">Expense amount:</label>
            <div class="col-sm-3"><?php if($more_job_info->expense_amount!="$"){echo $more_job_info->expense_amount;}else{echo "Not Available";}?></div>
          </div>
          </div>
          <div class="row">
          <div class="col-md-12">
            <label class="col-sm-4 control-label">Expense reason:</label>
            <div class="col-sm-8">
               <?php 
                //echo $more_job_info->expense_reason;
                $reason=$more_job_info->expense_reason;
                $newtext = wordwrap($reason, 8, "\n", true);
                echo "$newtext\n";
                ?>
                
              </div>
          </div>
          </div>
          <div class="row">
          <div class="col-md-12">
            <label class="col-sm-4 control-label">Taster's Feedback:</label>
            <div class="col-sm-8"><?php if($more_job_info->general_note!=''){echo $more_job_info->general_note;}else{echo "Not Available";}?></div>
          </div>
          </div>
          <div class="row">
          <div class="col-md-12">
            <label class="col-sm-4 control-label">Expense images:</label>
            
            <div class="col-sm-8">
              
              
                <?php
               // echo "<pre>";
               // print_r($more_job_info);die;
                  
                  if(!empty($more_job_info->expense_images[0]['exp_images']))
                  {
                    foreach($more_job_info->expense_images as $img){
                    
                ?>
                    
                    <div style="margin:0 5px 15px; float:left; width:40%;"><img src="<?php echo BASE_URL.DIR_EXPENSE_IMAGE.$img['exp_images'];?>" width="70%"></div>
                    
                <?php
                    }
                  }
                  else
                  {
                    echo "No image available";
                  }
                ?>
              
              <br /><br />
                
            </div>
            
          </div>
          </div>
          <div class="row">
          <div class="col-md-12">
             <label class="col-sm-4 control-label">Admin note:</label>
            <div class="col-sm-2"><?php if($more_job_info->admin_note!=''){echo $more_job_info->admin_note;}else{echo "Not Available";}?></div>
              <label class="col-sm-3 control-label">Sales rep phone:</label>
            <div class="col-sm-2"><?php if($more_job_info->phone!=''){echo $more_job_info->phone;}else{echo "Not Available";}?></div>
          </div>
          </div>
        </div>
        
        
      </div>
      <div id="overlay"></div>
      <div class="modal-footer">
        
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
      
    </div>
<script src="<?php echo base_url()?>assets/js/chosen.jquery.js" type="text/javascript"></script>
<script type="text/javascript">
// Image to Lightbox Overlay 
$('img').on('click', function() {
  $('#overlay')
    .css({backgroundImage: `url(${this.src})`})
    .addClass('open')
    .one('click', function() { $(this).removeClass('open'); });
});
</script>
<style>
 img{height:100px;}

#overlay{
  position: fixed;
  top:0;
  left:0;
  width:100%;
  height:100%;
  background: rgba(0,0,0,0.8) none 50% / contain no-repeat;
  cursor: pointer;
  transition: 0.3s;
  
  visibility: hidden;
  opacity: 0;
}
#overlay.open {
  visibility: visible;
  opacity: 1;
}

#overlay:after { /* X button icon */
  content: "\2715";
  position: absolute;
  color:#fff;
  top: 10px;
  right:20px;
  font-size: 2em;
}
</style>

  