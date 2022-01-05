
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Sales representative details</h4>
      </div>
      
      <div class="modal-body">
        <div class="form-group">
          <div class="col-md-12">
            <label class="col-sm-3 control-label">First name:</label>
            <div class="col-sm-3"><?php echo $details[0]['first_name'];?></div>
          
            <label class="col-sm-3 control-label">Last name:</label>
            <div class="col-sm-3"><?php echo $details[0]['last_name'];?></div>
          </div>
          
        </div>
        
        <div class="form-group">
          <div class="col-md-12">
            <label class="col-sm-2 control-label">Email:</label>
            <div class="col-sm-4"><?php if(isset($details[0]['email'])){echo wordwrap($details[0]['email'],16,"<br>\n",TRUE);}else{echo "null";}?></div>
          
            <label class="col-sm-2 control-label">Phone:</label>
            <div class="col-sm-4"><?php if(isset($details['meta'][0]['meta_value'])){echo $details['meta'][0]['meta_value'];}else{echo 'Not given';}?></div>
          </div>
          
        </div>
        
      </div>
      <div class="modal-footer">
        
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
      
    </div>
  