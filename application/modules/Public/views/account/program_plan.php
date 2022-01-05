<!-- Page Content -->
<div class="container">
  <div class="row profile">
    <div class="col-md-12">
      <div class="profile-content">
       <div class="row list-video">
        <?php 
    if(!empty($records)){
      $j = 1;
      foreach ($records as $key => $val) {
        if($j == 1)
        {
      ?>
        <div class="col-md-12">
          <h3 class="page-heading" style="padding-top:0.5em;"><?php echo $val->program_type_name; ?> <span><?php echo '(Level '.$val->program_type_difficulty.')'; ?></span> </h3>
        </div>
        <?php
        }
        if($j%5 == 0)
        {
        ?>
        <div class="row"></div>
        <?php
        }
        ?>
         <div class="col-sm-12 col-md-6 col-lg-4">
          <div class="listvideo">
            <?php 
          if(!empty($val->video_info)){
            $i = 1;
            foreach ($val->video_info as $video) {
              $video_frame = 'https://r6frpp9k.videomarketingplatform.co/'.$video['tree_id'].'/'.$video['video_id'].'/'.$video['token'].'/video_frame';

              if($i==1){
           ?>
            <div class="videoimg"> <a href="<?php echo base_url('account/program_plan_details/'.$val->id); ?>"> <img src="<?php echo $video_frame; ?>" class="img-fluid" alt=""> </a> </div>
            <?php
                $i++; 
              } else{ 
            ?>
            <?php /*?><div class="small-videoimg">
                  <img src="<?php echo $video_frame;?>" class="img-fluid" alt="" height="80px" width="80px">
                </div><?php */?>
            <?php 
              }
            } 
          ?>
            <div class="caption">
              <h4><a href="<?php echo base_url('account/program_plan_details/'.$val->id); ?>"><?php echo $val->name; ?></a></h4>
            </div>
          </div>
        </div>
        <?php 

          $j++;

          } ?>
        <?php
     } 
   }else{
  ?>
        <div class="row lib-list">
          <div class="col-lg-12"> No record available </div>
        </div>
        <?php } ?>
        <div class="col-md-12" style="padding:0.5em 0;">
          <div class="back-btn"> <a href="<?php echo base_url('account/program'); ?>" >Back</a> </div>
        </div>
      </div>
    </div>
  </div>
 </div>
</div>