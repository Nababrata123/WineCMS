
<!-- Page Content -->
<section class="body-container">
  <div class="container lib-container">

    <?php 
    if(!empty($videoData)){
      foreach ($videoData as $key => $val) {
        
        $video_frame = 'https://r6frpp9k.videomarketingplatform.co/'.$val->tree_id.'/'.$val->photo_id.'/'.$val->token.'/video_frame';
      ?>

      <div class="row lib-list">
        <div class="col-lg-5 videoimg">
            <div class="video-placeholder"><img src="<?php echo $video_frame; ?>" class="img-fluid" alt=""></div>
          <a href="javascript:void(0)" class="mvideo" data-id="<?php echo $val->photo_id;?>" data-title="<?php echo $val->title;?>" data-token="<?php echo $val->token;?>" data-user-id="<?php echo $this->session->userdata('id'); ?>" data-src="<?php echo base_url('account/check_saveUnsave/'); ?>">
          <span class="play-icon"><img src="https://mvp.managed.center/assets/images/play.png" class="img-fluid" border="0" alt=""></span>
          </a>
            <span class="duration"><?php echo gmdate("H:i:s", $val->video_length); ?></span>
        </div>
        <div class="col-lg-5">
          <h2><a href="<?php echo base_url('account/library_details/'.$val->photo_id); ?>"><?php echo $val->title; ?></a></h2>
          <h3><span>Muscle Target:</span> <?php echo $val->muscles_targeted; ?></h3>
          <h3><span>Body Group:</span> <?php echo $val->body_group; ?></h3>
          <h3><span>Reps:</span> <?php echo $val->reps; ?></h3>
          <h3><span>Sets:</span> <?php echo $val->sets; ?></h3>
          <!-- <p> -->
            <?php 
              // echo $val->content_text; 
              // if(strlen($val->content_text)>350){
              //   echo substr($val->content_text, 0, 347).'...';
              // }else{
              //   echo $val->content_text;
              // }
            ?>
              
            <!-- </p> -->
          <a href="<?php echo base_url('account/library_details/'.$val->photo_id); ?>" class="read">Continue Reading</a>
        </div>
        <div class="col-lg-2"><div class="level">Level <?php echo $val->difficulty; ?></div></div>
      </div>
    <?php
       } 
     }else{
    ?>
      <div class="row lib-list">
        <div class="col-lg-12">
          No record available
        </div>
      </div>
    <?php } ?>
  </div>
</section>

<!-- video Modal -->
<div class="modal fade" id="videoModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLongTitle"> </h4>
          
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

    <div style="width:100%; height:300px; position: relative;">
      <iframe class="videoFrame" src="" style="width:100%; height:100%; position: absolute; top: 0; left: 0;" frameborder="0" border="0" scrolling="no" allowfullscreen="1" mozallowfullscreen="1" webkitallowfullscreen="1" gesture="media"></iframe>
    </div>

      </div>

      <div id="display-msg" style="display: none;">
          <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          </div>
        </div>


      <div class="modal-footer">

        <button id="saveVideo" data-id="" data-video-id="" type="button" class="btn btn-primary" data-src="<?php echo base_url('account/save_video/'); ?>">Save Video</button>

        <button id="unSaveVideo" data-id="" data-video-id="" type="button" class="btn btn-primary" data-src="<?php echo base_url('account/unsave_video/'); ?>" style="display: none;">Unsave Video</button>

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- End video Modal -->
