
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">User Details</h4>
      </div>
      
      <div class="modal-body">
        <div class="form-group">
          <div class="col-md-12" style="padding:10px 0;">
            <label class="col-sm-3 control-label">First name:</label>
            <div class="col-sm-3"><?php if(isset($user->first_name)){echo $user->first_name;}else{echo "null";}?></div>
          
            <label class="col-sm-3 control-label">Last name:</label>
            <div class="col-sm-3"><?php if(isset($user->last_name)){echo $user->last_name;}else{echo "null";}?></div>
          </div>
          
        </div>
        
        <div class="form-group">
          <div class="col-md-12" style="padding:10px 0;">
            <label class="col-sm-2 control-label">Email id:</label>
            <div class="col-sm-4"><?php if($user->is_empty_email != 1){echo wordwrap($user->email,16,"<br>\n",TRUE);}else{echo "N/A";}?></div>
          
            <label class="col-sm-3 control-label">Home Address:</label>
            <div class="col-sm-3"><?php if(isset($metadata['address']) && $metadata['address']!=''){echo $metadata['address'];}else{echo "Not Available";}?></div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12" style="padding:10px 0;">
            <label class="col-sm-3 control-label">Phone no:</label>
            <div class="col-sm-3"><?php if(isset($metadata['phone']) && $metadata['phone']!=''){echo $metadata['phone'];}else{echo "Not Available";}?></div>
            <label class="col-sm-3 control-label">Rate per hour:</label>
            <div class="col-sm-3"><?php if(isset($metadata['rate_per_hour'])){echo $metadata['rate_per_hour'];}else{echo "N/A";}?></div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12" style="padding:10px 0;">
            <label class="col-sm-3 control-label">Zone:</label>
            <div class="col-sm-3"><?php if(isset($metadata['zone'])){echo $metadata['zone'];}else{echo "null";}?></div>
          
            <label class="col-sm-3 control-label">Vendor no:</label>
            <div class="col-sm-3"><?php if(isset($metadata['manual_account_number'])){echo $metadata['manual_account_number'];}else{echo "null";}?></div>
          </div>
        </div>
        
      </div>
      <div class="modal-footer">
        
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
      
    </div>
  