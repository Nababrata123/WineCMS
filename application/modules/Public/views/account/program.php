
<!-- Page Content -->
<div class="container">
 <div class="row profile">
  <div class="col-md-12">
   <div class="profile-content">
        <div class="row list-video">
    <?php 
	$i=1;
    if(!empty($records)){
      foreach ($records as $key => $val) {

       if($val->is_deleted == 0) 
       {
	  
	  ?>
        
        
         <div class="col-sm-12 col-md-6 col-lg-4">
          <div class="program">
            <a href="<?php echo base_url('account/program_plan/'.$val->id); ?>">
              <?php if(($val->image!='') && file_exists('assets/program_type/'.$val->image)){ ?>
                <img src="<?php echo base_url('assets/program_type/'.$val->image); ?>" class="img-fluid" alt="">
              <?php } else{ ?>
                <img src="<?php echo base_url('assets/images/no-image.jpg'); ?>" class="img-fluid" alt="">
              <?php } ?>
            </a>
            
            <h2><?php echo $val->name; ?></h2>
            <h3><?php echo 'Level '.$val->difficulty; ?></h3>
          </div>
         </div>
         
        
        <?php if($i%3 == 0)
	  {	
	  ?>
      <div class="divider"> </div> 
      <?php
	  }
	  ?>  
       
  <?php
  $i++;
     } 
   }
	 ?>
      </div>
      
   <?php
   }
	 else{
  ?>
    <div class="row lib-list">
       <div class="col-md-12">
        No record available
      </div>
    </div>
  <?php } ?>
  </div>
</div>
</div>
</div>
