<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
  <div class="container">
    <div class="logo">
      <a class="navbar-brand js-scroll-trigger" href="<?php echo base_url(); ?>">
        WINE
      </a>
    </div>
    <?php if($this->session->userdata('id')) { ?>
    <div class="top-nav">
    <?php } else { ?>
    <div class="top-nav-l">
    <?php } ?>

      <div class="mainnav"> <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <!--ul class="navbar-nav">
            <?php if($this->session->userdata('id')){ ?>
              <li class="nav-item <?php echo ($page->page_type=='dashboard')?'active':'';?>"><a class="nav-link js-scroll-trigger" href="<?php echo base_url('account/dashboard');?>">Home</a></li>
              <li class="nav-item <?php echo ($page->page_type=='library')?'active':'';?>"><a class="nav-link js-scroll-trigger" href="<?php echo base_url('account/library');?>">Library</a></li>
              <li class="nav-item <?php echo ($page->page_type=='Program')?'active':'';?>"><a class="nav-link js-scroll-trigger" href="<?php echo base_url('account/program');?>">Programs</a></li>
              <li class="nav-item <?php echo ($page->page_type=='Saved Videos')?'active':'';?>"><a class="nav-link js-scroll-trigger" href="<?php echo base_url('account/saved_videos');?>">Saved Videos</a></li>
              <li class="nav-item <?php echo ($page->page_type=='stores')?'class="active"':'';?>"><a class="nav-link js-scroll-trigger" href="<?php echo base_url('stores');?>">Store</a></li>
              <li class="nav-item"><a href="javascript:;" id="notificationBtn" class="nav-link js-scroll-trigger popover-control" data-container="body" data-placement="bottom" data-trigger="focus" data-content=""><i class="fa fa-bell"></i> <span class="badge"></span></a></li>
            <?php } else { ?>
              <li class="nav-item"><a class="nav-link js-scroll-trigger" href="https://www.phitondemand.com/home.html" target="_blank">HOME</a></li>
              <li class="nav-item"><a class="nav-link js-scroll-trigger" href="https://www.phitondemand.com/our-story.html" target="_blank">OUR STORY</a></li>
              <li class="nav-item"><a class="nav-link js-scroll-trigger" href="https://www.phitondemand.com/pricing.html" target="_blank">Pricing</a></li>
              <li class="nav-item"><a class="nav-link js-scroll-trigger" href="https://www.phitondemand.com/future-features.html" target="_blank">FUTURE FEATURES</a></li>
              
            <?php } ?>
            
          </ul-->
        </div>
      </div>
    </div>
    <?php if($this->session->userdata('id')){ ?>
      <div class="top-right-panel text-right">
    <?php } else { ?>
      <div class="top-right-panel-l text-right">
    <?php } ?>
    
    
    </div>
  </div>
</nav>
