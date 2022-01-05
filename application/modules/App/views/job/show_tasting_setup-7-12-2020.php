
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tasting setup</h4>
      </div>
      
      <div class="modal-body">
        <div class="form-group">

          <div class="col-md-12">
            <label class="col-sm-2 control-label">Setup images:</label>
            
            <div class="col-sm-10">
              
              
                <?php
                  
                  if(!empty($images))
                  {
                    foreach($images as $value){
                    
                ?>
                    
                    <img src="<?php echo BASE_URL.DIR_TASTING_SETUP_IMAGE.$value['image'];?>">&nbsp;
                    
                <?php
                    }
                  }
                  else
                  {
                    echo "No image available";
                  }
                ?>
              
              
                
            </div>
            
           
            
          </div>
          
        </div>
        
        
      </div>
      <div class="modal-footer">
        
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
      
    </div>
  