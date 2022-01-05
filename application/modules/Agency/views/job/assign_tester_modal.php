<!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Assign Job</h4>
      </div>
      <?php
        
        echo validation_errors();
        $attributes = array('class' => 'form-horizontal', 'id' => '', 'role' => 'form', 'data-toggle' => 'validator');
        echo form_open(base_url('Agency/job/assign_tester/'.$job_id), $attributes);
    ?>
    <?php if($job_state == 1) {?>
      <div class="modal-body">
        <div class="form-group">
            <div style="text-align: center;">This job has been started & the taster cannot be changed for the started job.</div>
        </div>
      </div>
    <div class="modal-footer">
       <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    </div>
      <?php }else{ ?>
        <div class="modal-body">
        <div class="form-group">
          <label for="inputPhone" class="col-sm-3 control-label">Assign taster</label>
          <div class="col-sm-7">
            <select name="taster_id"  required class="form-control" >
              <option value="">Select taster</option>
              <?php
                
                foreach($tester as $value){
              ?>
              <option value="<?php echo $value['id'];?>" ><?php echo $value['last_name']." ".$value['first_name'];?></option>
              <?php } ?>
            </select>
            <div class="help-block with-errors"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden"  name="job_id" value="<?php echo $job_id;?>">
        <input type="submit" class="btn btn-warning" name="approve" value="Assign">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
      <?php } ?>
      <?php echo form_close();?>
    </div>
  