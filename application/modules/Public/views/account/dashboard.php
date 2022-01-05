  <div class="container">
    <div class="row profile">
		<div class="col-md-12">
            <div class="profile-content">
                <div class="row list-video">
                    <div class="col-sm-12 text-left">
                        <h3 class="page-heading">Latest Videos</h3>
                    </div>
                    <?php
                    foreach ($videoData as $key => $val) {
						
                        $video_frame = 'https://r6frpp9k.videomarketingplatform.co/'.$val->tree_id.'/'.$val->photo_id.'/'.$val->token.'/video_frame';

                    ?>
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="listvideo">
                                
                                <div class="video-placeholder"><img data-src="holder.js/200x100%" alt=""  class="img-fluid" src="<?php echo $video_frame; ?>" data-holder-rendered="true"></div>
                               <a href="javascript:void(0)" class="mvideo" data-id="<?php echo $val->photo_id;?>" data-title="<?php echo $val->title;?>" data-token="<?php echo $val->token;?>" data-user-id="<?php echo $this->session->userdata('id'); ?>" data-src="<?php echo base_url('account/check_saveUnsave/'); ?>">
                                <span class="play-icon"><img src="<?php echo base_url();?>assets/images/play.png" class="img-fluid" border="0" alt=""></span>
</a>
<span class="duration"><?php echo gmdate("H:i:s", $val->video_length); ?></span></div>
                                <div class="caption">
                                    <h4><a href="<?php echo base_url('account/library_details/'.$val->photo_id); ?>"><?php echo $val->title; ?></a></h4>
                                    <p>Level <?php echo $val->difficulty; ?> </p>
                                </div>
                            
                        </div>
                    <?php 
					} ?>

                    <?php if($total_count>6){ ?>
                        <div class="col-md-12 text-right">
                            <a href="<?php echo base_url('account/library'); ?>" class="more">view more..</a>
                        </div>
                    <?php } ?>
                </div>

                <div class="row list-video">
                    <div class="col-sm-12 text-left">
                        <h3 class="page-heading">Latest Program Plans</h3>
                    </div>

                    <?php 
                    foreach ($programData as $pdata) {
                    ?>
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="thumbnail">
                                <?php
                                    if(($pdata->image) && file_exists('assets/program_type/'.$pdata->image)){
                                ?>
                                    <a href="<?php echo base_url('account/program_plan/'.$pdata->id); ?>"><img  alt=""  class="img-fluid" src="<?php echo base_url('assets/program_type/'.$pdata->image); ?>"></a>
                                <?php }else{ ?>
                                    <a href="<?php echo base_url('account/program_plan/'.$pdata->id); ?>"><img  alt=""  class="img-fluid" src="<?php echo base_url('assets/images/no-image.jpg'); ?>"></a>
                                <?php } ?>

                                <div class="caption">
                                    <h4><a href="<?php echo base_url('account/program_plan/'.$pdata->id); ?>"><?php echo $pdata->name; ?></a></h4>
                                    <p>Level <?php echo $pdata->difficulty ?> </p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="col-md-12 text-right">
                        <a href="<?php echo base_url('account/program'); ?>" class="more">view more..</a>
                    </div>
                </div>
            </div>
		</div>
    </div>
</div>


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