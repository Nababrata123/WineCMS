<!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Set question</h4>
      </div>
      <?php
        
        //form validation
        echo validation_errors();
        $attributes = array('class' => 'form-horizontal', 'id' => '', 'role' => 'form', 'data-toggle' => 'validator');
        echo form_open(base_url('App/job/set_question/'.$job_id), $attributes);
    ?>
      <div class="modal-body">
        <input type="hidden" name="job_id" value="<?php echo $job_id;?>">
        <div class="form-group">
          <label for="inputPhone" class="col-sm-3 control-label">Set questions:</label>
          <div class="col-sm-7">
            <select name="question_id[]"  required class="form-control" multiple="multiple">
              <option value="">Select question</option>
              <?php
                
                foreach($question_answers as $value){

                  //Get user role
                  
                  
              ?>
              <option value="<?php echo $value['id'];?>"><?php echo $value['question'];?></option>
              <?php } ?>
            </select>
            <div class="help-block with-errors"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        
        <input type="submit" name="approve" class="btn btn-primary" value="Save">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
      <?php echo form_close();?>
    </div>
  