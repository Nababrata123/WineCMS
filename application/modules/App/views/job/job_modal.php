<!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Approve Job</h4>
      </div>
      <?php
        $accepted='';
        $rejected='';
        foreach($accepted_user as $au)
        {
          $accepted.=$au['first_name']." ".$au['last_name'].",";
        }
        $accepted=rtrim($accepted,",");
        foreach($rejected_user as $ru)
        {
          $rejected.=$ru['first_name']." ".$ru['last_name'].",";
        }
        $rejected=rtrim($rejected,",");
        //form validation
        echo validation_errors();
        $attributes = array('class' => 'form-horizontal', 'id' => '', 'role' => 'form', 'data-toggle' => 'validator');
        echo form_open(base_url('App/job/approve_job/'.$job_id), $attributes);
    ?>
      <div class="modal-body">
        <div class="form-group">
          <label class="col-sm-3 control-label">Accepted by:</label>
          <div class="col-sm-7"><?php if($accepted!=''){echo $accepted;}else{echo "None";}?></div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label">Rejected by:</label>
          <div class="col-sm-7"><?php if($rejected!=''){echo $rejected;}else{echo "None";}?></div>
        </div>
        <div class="form-group">
          <label for="inputPhone" class="col-sm-3 control-label">Re assign taster/agency:</label>
          <div class="col-sm-7">
            <select name="taster_id[]"  required class="form-control" multiple="multiple">
              <option value="">Select taster</option>
              <?php
                $taster_id_array=explode(",",$taster_id);
                foreach($tester as $value){

                  //Get user role
                  
                  $role_id=$this->Job_model->get_user_role('users',$value['id']);
                  if($role_id=='5')
                  {
                    $agency_name=$this->Job_model->get_agency_name('user_meta',$value['id']);
                  }
              ?>
              <option value="<?php echo $value['id'];?>" <?php if(in_array($value['id'],$taster_id_array)){echo "selected";}?>>
                <?php 
                  if($role_id=='5')
                  {
                    echo $agency_name;
                  }
                  else
                  {
                    echo $value['first_name']." ".$value['last_name'];
                  }
                  

                ?>
                  
                </option>
              <?php } ?>
            </select>
            <div class="help-block with-errors"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" id="accepted_tester" value="" name="accepted_tester_id">
        <?php
          if($job_status!='cancelled')
          {
        ?>
        <input type="submit" class="btn btn-warning" name="approve" value="Approve">
        <?php
          }
        ?>
        <input type="submit" name="approve" class="btn btn-primary" value="Reassign & Publish">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
      <?php echo form_close();?>
    </div>
  