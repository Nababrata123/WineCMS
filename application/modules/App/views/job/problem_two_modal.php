<!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create billing</h4>
      </div>
     <?php
      echo validation_errors();
        $attributes = array('class' => 'form-horizontal', 'id' => '', 'role' => 'form', 'data-toggle' => 'validator');
        echo form_open(base_url('App/job/create_billing_two/'.$job_id), $attributes);
     ?>
      <div class="modal-body">
        <input type="hidden" name="job_id" value="<?php echo $job_id;?>">
        <div class="form-group">
          <label for="inputPhone" class="col-sm-3 control-label">Admin note:</label>
          <div class="col-sm-7">
            <textarea name="admin_note" rows="4" cols="50" required></textarea>
            <div class="help-block with-errors"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        
        
        <input type="submit" class="btn btn-primary" value="Submit">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
      <?php echo form_close();?>
    </div>
  