<div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tasting setup</h4>
      </div>
      
      <div class="modal-body">
        <div class="form-group">

          <div class="col-md-12">
            <label class="col-sm-2 control-label">Store image:</label>
            
            <div class="col-sm-10">

                <?php
                  
                    if(!empty($images))
                    {
                      foreach($images as $value){
                        $storeimageCount = 0;
                        if ($value['tasting_type'] == "store") {
                          $storeimageCount = $storeimageCount + 1;
                          ?>
                          <img data-enlargeable width="100" style="cursor: zoom-in" src="<?php echo BASE_URL.DIR_TASTING_SETUP_IMAGE.$value['image'];?>">&nbsp;
                          <!-- <img width="100" src="<?php echo BASE_URL."assets/css/images/wineimg.jpeg"?>"> -->
                      <?php
                        }
                      }
                      if ($storeimageCount <= 0) {
                        echo "No image available";
                      }
                    }
                  else
                  {
                    echo "No image available";
                  }
                ?>
            </div>
          </div>&nbsp;

          <div class="col-md-12">
            <label class="col-sm-2 control-label">Tasting Setup images:</label>
            
            <div class="col-sm-10">
          
                <?php
                  
                  if(!empty($images))
                  {
                    $tastingimageCount = 0;
                    foreach($images as $value){
                      
                      if ($value['tasting_type'] == "tasting") {
                        $tastingimageCount = $tastingimageCount + 1;
                        ?>
                        <img data-enlargeable width="100" style="cursor: zoom-in" src="<?php echo BASE_URL.DIR_TASTING_SETUP_IMAGE.$value['image'];?>">&nbsp;
                        <!-- <img data-enlargeable width="100" style="cursor: zoom-in" src="<?php echo BASE_URL."assets/css/images/wineimg.jpeg"?>"> -->
                       
                    <?php
                      }
                    }
                    if ($tastingimageCount <= 0) {
                      echo "No image available";
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
<script src="<?php echo base_url()?>assets/js/chosen.jquery.js" type="text/javascript"></script>
<script type="text/javascript">
// Image to Lightbox Overlay 
$('img[data-enlargeable]').addClass('img-enlargeable').click(function(){
    var src = $(this).attr('src');
    var modal;
    function removeModal(){ modal.remove(); $('body').off('keyup.modal-close'); }
    modal = $('<div>').css({
        background: 'RGBA(0,0,0,.9) url('+src+') no-repeat center',
        backgroundSize: 'contain',
        width:'100%', height:'100%',
        position:'fixed',
        zIndex:'10000',
        top:'0', left:'0',
        cursor: 'zoom-out'
    }).click(function(){
        removeModal();
    }).appendTo('body');
    //handling ESC
    $('body').on('keyup.modal-close', function(e){
      if(e.key==='Escape'){ removeModal(); } 
    });
});
</script>
