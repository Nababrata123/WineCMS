
<!-- Page Content -->
<div class="container">
 <div class="row profile">
  <div class="col-md-12">
   <div class="profile-content">

    <?php 
    // echo "<pre>";print_r($videoData);exit;
      
      $video_frame = 'https://r6frpp9k.videomarketingplatform.co/'.$videoData->tree_id.'/'.$videoData->photo_id.'/'.$videoData->token.'/video_frame';
    ?>

    <div class="row lib-list">
      <div class="col-lg-8 videoimg">
      
       <div class="videoWithJs videoWrapper-s">
  <iframe class="videoFrame" src="//r6frpp9k.videomarketingplatform.co/v.ihtml/player.html?token=<?php echo $videoData->token ?>&source=embed&photo_id=<?php echo $videoData->photo_id ?>&showDescriptions=0" style="width:100%; height:100%; position: absolute; top: 0; left: 0;" frameborder="0" border="0" scrolling="no" allowfullscreen="1" mozallowfullscreen="1" webkitallowfullscreen="1" gesture="media"></iframe>
   <span class="duration"><?php echo gmdate("H:i:s", $videoData->video_length); ?></span>
</div><?php /*?> <div style="width:100%; height:700px; position: relative;">
          <iframe class="videoFrame"  style="width:100%; height:100%; position: absolute; top: 0; left: 0;" frameborder="0" border="0" scrolling="no" allowfullscreen="1" mozallowfullscreen="1" webkitallowfullscreen="1" gesture="media"></iframe>
        </div><?php */?>
       

      </div>      
      <div class="col-lg-4 col-sm-12">
          <div class="back-btn">
          <a href="<?php echo base_url('account/library'); ?>">Back</a>
          </div>
            <div class="spacer"></div>
          <h2 class="heading"><?php echo $videoData->title; ?></h2>
          <h3><span>Muscle Target:</span> <?php echo $videoData->muscles_targeted; ?></h3>
          <h3><span>Body Group:</span> <?php echo $videoData->body_group; ?></h3>
          <h3><span>Sets:</span> <?php echo $videoData->sets; ?></h3>
          <h3><span>Reps:</span> <?php echo $videoData->reps; ?></h3>
        
          <h3><span>Difficulty:</span> Level <?php echo $videoData->difficulty; ?></h3>
          <h3><span>Description:</span> <?php echo $videoData->content_text; ?></h3>
        <div class="btn-details">
          
          <button id="saveVideo" data-id="<?php echo $this->session->userdata('id'); ?>" data-video-id="<?php echo $videoData->photo_id ?>" type="button" class="btn btn-primary" data-src="<?php echo base_url('account/save_video/'); ?>" style="<?php echo empty($svData)?'display:block;':'display:none;'; ?>">Save Video</button>
        
          <button id="unSaveVideo" data-id="<?php echo $this->session->userdata('id'); ?>" data-video-id="<?php echo $videoData->photo_id ?>" type="button" class="btn btn-primary" data-src="<?php echo base_url('account/unsave_video/'); ?>" style="<?php echo empty($svData)?'display:none;':'display:block;'; ?>">Unsave Video</button>
          

        </div>
      </div>

        

    </div>
  </div>
</div>
</div>
</div>

