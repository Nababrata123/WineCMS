<!-- Modal content-->
<div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Job Activity</h4>
      </div>
      <div class="modal-body">
        <?php

          $accepted='';
          foreach($accepted_user as $au)
          {
            $user_type=get_user_type('users',$au['user_id']);
            if($user_type=='agency')
            {
                $name=get_agency_name('user_meta',$au['user_id']);
                $accepted.=$name.",";
            }
            else
            {
                $accepted.=$au['first_name']." ".$au['last_name'].",";
            }
            
          }
          $accepted=rtrim($accepted,",");
        ?>
        <table id="classTable" class="table table-bordered">
            <tr>
                <th>Cancelled details</th>
                <?php
                    if($job_status!='assigned'){
                ?>
                <th>Accepted details</th>
                <?php }?>
                <th>Rejected details</th>
            </tr>
            <tr>
                <td>
                    <strong>Cancelled by:&nbsp;</strong><?php if(isset($cancelled_job_details[0]['taster_name'])){echo $cancelled_job_details[0]['taster_name'];}else{echo "None";}?>
                    <br>
                    <strong>Reason:&nbsp;</strong><?php if(isset($cancelled_job_details[0]['reason'])){echo $cancelled_job_details[0]['reason'];}else{echo "No reason";}?>
                </td>
                <?php
                    if($job_status!='assigned'){
                ?>
                <td><strong>Accepted by:&nbsp;</strong><?php if($accepted!=''){echo $accepted;}else{echo "None";}?></td>
                <?php }?>
                <td><strong>Rejected by:&nbsp;</strong><?php if($rejected_users!=''){echo $rejected_users;}else{echo "None";}?></td>
            </tr>
            
            
        </table>
        
      </div>
</div>

