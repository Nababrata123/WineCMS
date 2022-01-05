<div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tasting setup</h4>
      </div>
      
      <div class="modal-body">
        <div class="form-group">

          <div class="col-md-12">
            <label class="col-sm-2 control-label">Store images:</label>
            
            <div class="col-sm-10">

                <?php
                  
                    if(!empty($images))
                    {
                      foreach($images as $value){
                        $storeimageCount = 0;
                        if ($value['tasting_type'] == "store") {
                          $storeimageCount = $storeimageCount + 1;
                          ?>
                          <img src="<?php echo BASE_URL.DIR_TASTING_SETUP_IMAGE.$value['image'];?>">&nbsp;
                          
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
          </div>
          <div class="col-md-12">
            <label class="col-sm-2 control-label">Setup images:</label>
            
            <div class="col-sm-10">
          
                <?php
                  
                  if(!empty($images))
                  {
                    $tastingimageCount = 0;
                    foreach($images as $value){
                      
                      if ($value['tasting_type'] == "tasting") {
                        $tastingimageCount = $tastingimageCount + 1;
                        ?>
                        <img src="<?php echo BASE_URL.DIR_TASTING_SETUP_IMAGE.$value['image'];?>">&nbsp;
                       
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
          <div id="overlay"></div>
        </div>
      </div>
      <div class="modal-footer">
        
      <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
      
    </div>
<script src="<?php echo base_url()?>assets/js/chosen.jquery.js" type="text/javascript"></script>
<script type="text/javascript">
// Image to Lightbox Overlay 
$('img').on('click', function() {
  $('#overlay')
    .css({backgroundImage: `url(${this.src})`})
    .addClass('open')
    .one('click', function() { $(this).removeClass('open'); });
});
</script>
<style>
 img{height:100px;}

#overlay{
  position: fixed;
  top:0;
  left:0;
  width:100%;
  height:100%;
  background: rgba(0,0,0,0.8) none 50% / contain no-repeat;
  cursor: pointer;
  transition: 0.3s;
  
  visibility: hidden;
  opacity: 0;
}
#overlay.open {
  visibility: visible;
  opacity: 1;
}

#overlay:after { /* X button icon */
  content: "\2715";
  position: absolute;
  color:#fff;
  top: 10px;
  right:20px;
  font-size: 2em;
}
</style>
  