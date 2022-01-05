
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
            <div class="col-sm-3"><?php if(isset($user->first_name)){echo $user->last_name;}else{echo "null";}?></div>
          </div>
          
        </div>
        
        <div class="form-group">
          <div class="col-md-12" style="padding:10px 0;">
            <label class="col-sm-2 control-label">Email id:</label>
            <div class="col-sm-4"><?php if(isset($user->email)){echo $user->email;}else{echo "null";}?></div>
          
            <label class="col-sm-3 control-label">Agency name:</label>
            <div class="col-sm-3"><?php if(isset($user->meta[0]->meta_value)){echo $user->meta[0]->meta_value;}else{echo "null";}?></div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12" style="padding:10px 0;">
            <label class="col-sm-3 control-label">Phone no:</label>
            <div class="col-sm-3"><?php if(isset($user->meta[1]->meta_value)){echo $user->meta[1]->meta_value;}else{echo "null";}?></div>
          
            <label class="col-sm-3 control-label">Address:</label>
            <div class="col-sm-3"><?php if(isset($user->meta[2]->meta_value)){echo $user->meta[2]->meta_value;}else{echo "null";}?></div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12" style="padding:10px 0;">
            <label class="col-sm-3 control-label">Zone:</label>
            <div class="col-sm-3"><?php if(isset($user->meta[3]->meta_value)){echo $user->meta[3]->meta_value;}else{echo "null";}?></div>
            <label class="col-sm-3 control-label">Account no:</label>
            <div class="col-sm-3"><?php if(isset($user->meta[4]->meta_value)){echo $user->meta[4]->meta_value;}else{echo "Not Available";}?></div>
            
          </div>
        </div>
        
      </div>
      <div class="modal-footer">
        
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
      
    </div>
  