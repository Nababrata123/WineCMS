
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Manager verification details</h4>
      </div>
      
      <div class="modal-body">
        <div class="form-group">
          <div class="col-md-12">
            <label class="col-sm-3 control-label">Name:</label>
            <div class="col-sm-3">
              
                <?php if(isset($signature_and_comment->first_name)){echo $signature_and_comment->first_name." ".$signature_and_comment->last_name;}else{echo "null";}?>
              </div>
          
            <!--<label class="col-sm-3 control-label">Last name:</label>
            <div class="col-sm-3">
              <?php //if(isset($signature_and_comment->last_name)){echo $signature_and_comment->last_name;}else{echo "null";}?>
            </div>-->
          </div>
          <div class="col-md-12">
            <label class="col-sm-3 control-label">Cell no:</label>
            <div class="col-sm-3">
              <?php if(isset($signature_and_comment->cell_number) && ($signature_and_comment->cell_number!='')){echo $signature_and_comment->cell_number;}else{echo "Not Available";}?>
                
              </div>
          
            <label class="col-sm-3 control-label">Date:</label>
            <div class="col-sm-3">
              <?php 
                if(isset($signature_and_comment->date))
                {
                    //echo date("j-F-Y",strtotime($signature_and_comment->date));
                    echo date("m/d/Y", strtotime($signature_and_comment->date));
                }
                else
                {
                    echo "null";
                }?>
            </div>
          </div>
          <div class="col-md-12">
            <label class="col-sm-2 control-label">Signature:</label>
            <div class="col-sm-4">
              <?php
                if(!empty($signature_and_comment)){
              ?>
              <!-- <img src="<?php //echo BASE_URL.DIR_SIGNATURE_IMAGE.$signature_and_comment->signature_img;?>"> -->
                <img src="<?php echo "https://img253.managed.center/".$signature_and_comment->signature_img;?>">
              <?php
                }
                else
                {
                  echo "No signature";
                }
              ?>
                
              </div>
          
            <label class="col-sm-2 control-label">Comments:</label>
            <div class="col-sm-4"><?php if(!isset($signature_and_comment->comment) && $signature_and_comment->comment!=''){echo $signature_and_comment->comment;}else{echo "Not Available";}?>
              
            </div>
          </div>
          
        </div>
        
        
        
      </div>
      <div class="modal-footer">
        
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
      
    </div>
  