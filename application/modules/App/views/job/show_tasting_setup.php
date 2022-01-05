<div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tasting setup</h4>
      </div>
      
      <div class="modal-body">
        <div class="form-group">

        <div class="col-md-12">
            <label class="col-sm-2 control-label">Store image:<br/> <?php if($store_setup_time !=''){echo date('h:i:a', strtotime($store_setup_time));} ?></label>
            <div class="col-sm-10">

                <?php
                  
                    if(count($store_images)>0)
                    {
                      foreach($store_images as $value){
                          ?>
                          <img data-enlargeable width="100" style="cursor: zoom-in" src="<?php echo BASE_URL.DIR_TASTING_SETUP_IMAGE.$value['image'];?>">&nbsp;
                         
                      <?php
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
          <label class="col-sm-2 control-label">Tasting Setup images:<br/> <?php if($tasting_setup_time !=''){echo date('h:i:a', strtotime($tasting_setup_time));} ?></label>
         
           
            <div class="col-sm-10">
          
                <?php
                  
                  if(count($tasting_images)>0)
                  {
                    foreach($tasting_images as $value){
                        ?>
                        <img data-enlargeable width="100" style="cursor: zoom-in" src="<?php echo BASE_URL.DIR_TASTING_SETUP_IMAGE.$value['image'];?>">&nbsp;
                       
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
