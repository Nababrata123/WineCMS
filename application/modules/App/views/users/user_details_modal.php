
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">User Details</h4>
      </div>
      
      <div class="modal-body">
        <div class="form-group">
          <div class="col-md-12" style="padding:10px 0;">
            <div class="col-sm-6"><label class="control-label">First name: </label> <?php if(isset($user->first_name)){echo $user->first_name;}else{echo "null";}?></div>
            
            <div class="col-sm-6"><label class="control-label">Last name: </label> <?php if(isset($user->first_name)){echo $user->last_name;}else{echo "null";}?></div>
            
          </div>
        </div>
        
        <div class="form-group">
          <div class="col-md-12" style="padding:10px 0;">
            <div class="col-sm-6"><label class="control-label">Email id: </label> <?php if(isset($user->email)){echo $user->email;}else{echo "N/A";}?></div>
          
            <div class="col-sm-6"><label class="control-label">Brand: </label> <?php if(isset($user->brand)){echo $user->brand;}else{echo "N/A";}?></div>
            
          </div>
          </div>

          <div class="form-group">
          <div class="col-md-12" style="padding:10px 0;">
            <div class="col-sm-6"><label class="control-label">Sales rep: </label> <?php if(isset($user->salesRep)){echo $user->salesRep;}else{echo "N/A";}?></div>
            
          </div>
          </div>
     
        <!-- <div class="form-group">
          <div class="col-md-12" style="padding:10px 0;">
      
            <div class="col-sm-6"><label class="control-label">Address: </label> <?php if(isset($user->meta[1]->meta_value)){echo $user->meta[1]->meta_value;}else{echo "null";}?></div>

            <div class="col-sm-6"><label class="control-label">Phone no: </label> <?php if(isset($user->meta[0]->meta_value)){echo $user->meta[0]->meta_value;}else{echo "null";}?></div>
        </div>
      </div> -->
  </div>
      <div class="modal-footer">
        
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
      
    </div>
  