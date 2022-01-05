
<!-- Page Content -->
<div class="container">
 <div class="row profile">
  <div class="col-md-12">
   <div class="profile-content">
   <div class="col-md-12 text-right ">
   <div class="selectWrapper" style="margin:1em 0 1em; clear:both;">
   <select name="c_search" id="c_search" onchange = custom_search(); class="selectpicker">
   <option value="">Filters</option>
   <option value="1" <?php echo ($this->input->get('cs')==1)?'selected="selected"':''; ?>>Level 1</option>
   <option value="2" <?php echo ($this->input->get('cs')==2)?'selected="selected"':''; ?>>Level 2</option>
   <option value="3" <?php echo ($this->input->get('cs')==3)?'selected="selected"':''; ?>>Level 3</option>
   <?php if(count($tag_list) > 0){ foreach($tag_list as $tag){ $tg = $this->input->get('cs');?>
   <option value="<?php echo $tag;?>" <?php echo ($tg==$tag)?'selected="selected"':''; ?> ><?php echo $tag;?></option>
   <?php } } ?>
   </select>
   </div>
   </div>
   <div class="spacer"></div>
    <?php	
    if(!empty($videoData)){

      $datacount =0;
      $csdata = $this->input->get('cs');
      if($csdata =='1' || $csdata =='2' || $csdata =='3')
      {
      foreach ($videoData as $key => $val) {

          if($csdata == $val->difficulty)
          {
            $datacount++;
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
          
          <a href="<?php echo base_url('account/library_details/'.$val->photo_id); ?>" class="read">View Video</a>
        </div>
        <div class="col-lg-2"><div class="level">Level <?php echo $val->difficulty; ?></div></div>
      </div>
    <?php
       }

     }
        if($datacount == 0) 
        {
         ?>
         <div class="row lib-list">
        <div class="col-lg-12">
          No record available
        </div>
      </div>
      <?php 
        }
       }
       else
       {
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
          
          <a href="<?php echo base_url('account/library_details/'.$val->photo_id); ?>" class="read">View Video</a>
        </div>
        <div class="col-lg-2"><div class="level">Level <?php echo $val->difficulty; ?></div></div>
      </div>
      <?php
      }
    }
    ?>
      <div id="pagination">
        <ul class="tsc_pagination">
          <!-- Show pagination links -->
          <?php foreach ($links as $link) {
            echo "<li>". $link."</li>";
          } ?>
        </ul>
      </div>

       <?php 
     }else{
    ?>
      <div class="row lib-list">
        <div class="col-lg-12">
          No record available
        </div>
      </div>
    <?php } ?>
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
<form method="get" id="frm1" name="frm1" action="<?php echo base_url('account/library'); ?>">
  <input type="hidden" name="cs" id="cs" value="">
  <input type="hidden" name="optbtn" id="optbtn" value="2">
</form>
<script>
function custom_search(){
	var current_value = document.getElementById("c_search").value;
	if(current_value!=''){
	document.getElementById('cs').value = current_value;
	document.getElementById("frm1").submit();
	}
}
</script>
<!-- End video Modal -->
