<!-- Page Content -->
<style type="text/css">
  .modal-ku {
    width: 850px;
    margin: auto;
  }
</style>

  <?php if(!empty($records['video_info'])){ ?>
  <div class="container text-left">
  <div class="row profile">
		<div class="col-md-12">
            <div class="profile-content">
      <h3 class="page-heading" style="padding-top:0.5em;"><?php echo ucwords($records['name']); ?></h3>
      <?php 
      
      foreach ($records['video_info'] as $key => $videoData) {

        $video_frame = 'https://r6frpp9k.videomarketingplatform.co/'.$videoData['tree_id'].'/'.$videoData['video_id'].'/'.$videoData['token'].'/video_frame';

      $svData = $this->App_model->getDetailsById('save_video', array('customer_id' => $this->session->userdata('id'), 'video_id' => $videoData['video_id']));

      ?>
        <div class="row lib-list">
          <div class="col-lg-8 videoimg">
            <div class="videoWithJs videoWrapper-s">
              <iframe class="videoFrame" src="//r6frpp9k.videomarketingplatform.co/v.ihtml/player.html?token=<?php echo $videoData['token'] ?>&source=embed&photo_id=<?php echo $videoData['video_id'] ?>&showDescriptions=0" style="width:100%; height:100%; position: absolute; top: 0; left: 0;" frameborder="0" border="0" scrolling="no" allowfullscreen="1" mozallowfullscreen="1" webkitallowfullscreen="1" gesture="media"></iframe>
              <span class="duration"><?php echo $videoData['duration']; ?></span> </div>
          </div>
          <div class="col-lg-4">
            <h2 class="heading"><a href="<?php echo base_url('account/library_details/'.$videoData['video_id']); ?>"><?php echo $videoData['title']; ?></a></h2>
            <div>Muscle Target: <?php echo $videoData['muscles_targeted']; ?></div>
            <div>Body Group: <?php echo $videoData['body_group']; ?></div>
            <div>Sets: <?php echo $videoData['program_sets'] ? $videoData['program_sets'] : $videoData['sets']; ?></div>
            <div>Reps: <?php echo $videoData['program_reps'] ? $videoData['program_reps'] : $videoData['reps']; ?></div>
            <div>Difficulty: Level <?php echo $videoData['difficulty']; ?></div>
            <div>Description: <?php echo $videoData['program_desc'] ? $videoData['program_desc'] : $videoData['description']; ?></div>
            <div class="btn-details">
              <button data-id="<?php echo $this->session->userdata('id'); ?>" data-video-id="<?php echo $videoData['video_id'] ?>" type="button" class="btn btn-primary save_video" data-src="<?php echo base_url('account/save_video/'); ?>" style="<?php echo empty($svData)?'display:block;':'display:none;'; ?>">Save Video</button>
              <button data-id="<?php echo $this->session->userdata('id'); ?>" data-video-id="<?php echo $videoData['video_id'] ?>" type="button" class="btn btn-primary unsave_video" data-src="<?php echo base_url('account/unsave_video/'); ?>" style="<?php echo empty($svData)?'display:none;':'display:block;'; ?>">Unsave Video</button>
            </div>
          </div>
        </div>
    <?php } ?>
  </div>
  </div>
  </div>
  </div>
  <?php } if($records['file_name'] != '' && file_exists('assets/program_pdf/'.$records['file_name'])){ ?>
   <div class="container">
    <div class="row">
  		<div class="col-md-12">
        <div class="profile-content">
          <h3 class="page-heading" style="padding-top:0.5em;">Instruction</h3>
          <div class="container">
            <div class="row">
              <div class="col-lg-6 text-left" style="padding-bottom:0.5em;"> 
                <!-- Download <?php //echo ucwords($records['name']); ?> PDF <a href="<?php //echo base_url('account/download/'.$records['id']); ?>"><i class="fa fa-download"></i></a> -->
                PDF: <?php echo $records['file_name']; ?>
              </div>
              <div class="col-lg-6 text-right" style="padding-bottom:0.5em;">
                
                <!-- Click <a href="<?php //echo base_url('account/view/'.$records['id']); ?>" target="_blank">here</a> to view in browser --> 

                Click <a data-toggle="modal" href="javascript:void(0)" data-target="#myModal">here </a> to view PDF

                <!-- Modal -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                 <h4 class="modal-title"><?php echo ucwords($records['name'])." PDF "; ?></h4>

                            </div>
                            <div class="modal-body">
                              <div class="te">
                                <iframe height="400px" width="750px" src="<?php echo base_url('assets/program_pdf/'.$records['file_name']); ?>#toolbar=0&zoom=75"></iframe>
                              </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->





             </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php } if(!empty($records['day_info'])){ ?>
   <div class="container text-left">
    <div class="row">
		<div class="col-md-12">
            <div class="profile-content">
      <h3 class="page-heading" style="padding-top:0.5em;">Day Details</h3>
      <?php foreach ($records['day_info'] as $key => $dayData) { ?>
      <div class="container">
        <div class="row">
          <div class="day-heading">Day - <?php echo $dayData->day; ?></div>
          <div class="col-lg-12">
            <ul class="day-list">
              <li>Warmup: <?php echo $dayData->warmup; ?></li>
              <li>Workout: <?php echo $dayData->workout; ?></li>
              <li>Cooldown: <?php echo $dayData->cooldown; ?></li>
            </ul>
          </div>
        </div>
      </div>
      <?php  foreach ($dayData->video_info as $key => $dayvideoData) {

        $video_frame = 'https://r6frpp9k.videomarketingplatform.co/'.$dayvideoData['tree_id'].'/'.$dayvideoData['video_id'].'/'.$dayvideoData['token'].'/video_frame';

        $dsvData = $this->App_model->getDetailsById('save_video', array('customer_id' => $this->session->userdata('id'), 'video_id' => $dayvideoData['video_id']));
      ?>
    <div class="row lib-list text-left">
        <div class="col-lg-7 videoimg">
          <div class="videoWithJs videoWrapper-s">
            <iframe class="videoFrame" src="//r6frpp9k.videomarketingplatform.co/v.ihtml/player.html?token=<?php echo $dayvideoData['token'] ?>&source=embed&photo_id=<?php echo $dayvideoData['video_id'] ?>&showDescriptions=0" style="width:100%; height:100%; position: absolute; top: 0; left: 0;" frameborder="0" border="0" scrolling="no" allowfullscreen="1" mozallowfullscreen="1" webkitallowfullscreen="1" gesture="media"></iframe>
            <span class="duration"><?php echo $dayvideoData['duration']; ?></span> </div>
        </div>
        <div class="col-lg-5">
          <h2><a href="<?php echo base_url('account/library_details/'.$dayvideoData['video_id']); ?>"><?php echo $dayvideoData['title']; ?></a></h2>
          <h3><span>Muscle Target:</span> <?php echo $dayvideoData['muscles_targeted']; ?></h3>
          <h3><span>Body Group:</span> <?php echo $dayvideoData['body_group']; ?></h3>
          <h3><span>Reps:</span> <?php echo $dayvideoData['reps']; ?></h3>
          <h3><span>Sets:</span> <?php echo $dayvideoData['sets']; ?></h3>
          <h3><span>Difficulty:</span> Level <?php echo $dayvideoData['difficulty']; ?></h3>
          <h3><span>Description:</span> <?php echo $dayvideoData['description']; ?></h3>
          <div class="btn-details">
            <button data-id="<?php echo $this->session->userdata('id'); ?>" data-video-id="<?php echo $dayvideoData['video_id'] ?>" type="button" class="btn btn-primary save_video" data-src="<?php echo base_url('account/save_video/'); ?>" style="<?php echo empty($dsvData)?'display:block;':'display:none;'; ?>">Save Video</button>
            <button data-id="<?php echo $this->session->userdata('id'); ?>" data-video-id="<?php echo $dayvideoData['video_id'] ?>" type="button" class="btn btn-primary unsave_video" data-src="<?php echo base_url('account/unsave_video/'); ?>" style="<?php echo empty($dsvData)?'display:none;':'display:block;'; ?>">Unsave Video</button>
          </div>
        </div>
      </div>
      <?php }} ?>
  </div>
  </div>
  </div>
  </div>
  <?php } ?>
