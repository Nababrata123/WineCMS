
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Question answers for job</h4>
      </div>
      
      <div class="modal-body">
        
        <div class="form-group">
          
          <?php
          if(!empty($question_answers))
          {
            foreach($question_answers as $value)
            {
        ?>
          
          <div class="col-md-12">
            <p><strong>Question:</strong>&nbsp;<?php echo $value['question'];?></p>
            <p><strong>Answer:</strong></p>
            <ul>
              <li><?php if($value['ans_text']!=''){echo $value['ans_text'];}else{echo 'No answer found';}?></li>
              
            </ul>
              <?php

                $images=$value['image'];
              ?>
            <p>
                <?php
                  foreach($images as $v)
                  {
                ?>
                    <img src="<?php echo BASE_URL.DIR_QUESTION_ANSWER_IMAGE.$v['image'];?>">&nbsp;
                <?php
                  }
                ?>
            </p>
            
          </div>
          <?php
        }
          ?>
          
          <?php
          }
          else
          {
        ?>
        <span>No question answer found</span>
          
        
        <?php
          }
        ?>
          
        </div>
        
      </div>
      <div class="modal-footer">
        
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
      
    </div>
  