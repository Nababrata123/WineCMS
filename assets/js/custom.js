(function(jQuery) {
  jQuery.noConflict();
  
  "use strict"; // Start of use strict

  // Smooth scrolling using jQuery easing
  jQuery('a.js-scroll-trigger[href*="#"]:not([href="#"])').click(function() {
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
      var target = jQuery(this.hash);
      target = target.length ? target : jQuery('[name=' + this.hash.slice(1) + ']');
      if (target.length) {
        jQuery('html, body').animate({
          scrollTop: (target.offset().top - 57)
        }, 1000, "easeInOutExpo");
        return false;
      }
    }
  });

  // Closes responsive menu when a scroll trigger link is clicked
  jQuery('.js-scroll-trigger').click(function() {
    jQuery('.navbar-collapse').collapse('hide');
  });
  
  // show hide setting
  jQuery('.setting-nav').hide();
    jQuery('.setting').click(function(){
        jQuery('.setting-nav').slideToggle();
      //  jQuery(this).toggleClass('selected');
        
      //  jQuery('.cat-menu').removeClass('selected');
    })

  // Activate scrollspy to add active class to navbar items on scroll
  jQuery('body').scrollspy({
    target: '#mainNav',
    offset: 57
  });

// video 

jQuery(document).ready(function(){
  sizeTheVideo();
  jQuery(window).resize(function(){
    sizeTheVideo();
  });  
});

function sizeTheVideo(){
  var aspectRatio = 1.78;
  
    var video = jQuery('.videoWithJs iframe');
    var videoHeight = video.outerHeight();
    var newWidth = videoHeight*aspectRatio;
		var halfNewWidth = newWidth/2;
    
  video.css({"width":newWidth+"px","left":"50%","margin-left":"-"+halfNewWidth+"px"});
}
  // Collapse Navbar
  var navbarCollapse = function() {
    if (jQuery("#mainNav").offset().top > 100) {
      jQuery("#mainNav").addClass("navbar-shrink");
    } else {
      jQuery("#mainNav").removeClass("navbar-shrink");
    }
  };
  // Collapse now if page is not at top
  navbarCollapse();
  // Collapse the navbar when page is scrolled
  jQuery(window).scroll(navbarCollapse);

  // Scroll reveal calls
  window.sr = ScrollReveal();
  sr.reveal('.sr-icons', {
    duration: 600,
    scale: 0.3,
    distance: '0px'
  }, 200);
  sr.reveal('.sr-button', {
    duration: 1000,
    delay: 200
  });
  sr.reveal('.sr-contact', {
    duration: 600,
    scale: 0.3,
    distance: '0px'
  }, 300);

  // Magnific popup calls
  // jQuery('.popup-gallery').magnificPopup({
  //   delegate: 'a',
  //   type: 'image',
  //   tLoading: 'Loading image #%curr%...',
  //   mainClass: 'mfp-img-mobile',
  //   gallery: {
  //     enabled: true,
  //     navigateByImgClick: true,
  //     preload: [0, 1]
  //   },
  //   image: {
  //     tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
  //   }
  // });


  jQuery('.mvideo').click(function(e){
    var id = jQuery(this).attr('data-id');
    var title = jQuery(this).attr('data-title');
    var token = jQuery(this).attr('data-token');
    var user_id = jQuery(this).attr('data-user-id');
    var check_video_src = jQuery(this).attr('data-src');

    var utitle = title.toUpperCase();
    var src = "//r6frpp9k.videomarketingplatform.co/v.ihtml/player.html?token="+token+"&source=embed&photo_id="+id+"&showDescriptions=0";
    jQuery('.videoFrame').attr('src', src);
    jQuery('#exampleModalLongTitle').text(utitle);
    jQuery('#saveVideo').attr('data-id', user_id);
    jQuery('#saveVideo').attr('data-video-id', id);
    jQuery('#unSaveVideo').attr('data-id', user_id);
    jQuery('#unSaveVideo').attr('data-video-id', id);

    var request = jQuery.ajax({
      url: check_video_src,
      cache: false,
      type: "POST",
      data: {video_id : id, user_id: user_id},
      dataType: "json"
    });
    request.done(function(response) {
      if(response.msg=='success'){
        jQuery('#unSaveVideo').css("display", "block");
        jQuery('#saveVideo').css("display", "none");
      }else{
        jQuery('#saveVideo').css("display", "block");
        jQuery('#unSaveVideo').css("display", "none");
      }

    });

      jQuery('#videoModel').modal('show');
  });

  jQuery('#saveVideo').click(function(e){
    var video_id = jQuery('#saveVideo').attr('data-video-id');
    var user_id = jQuery('#saveVideo').attr('data-id');
    var src = jQuery('#saveVideo').attr('data-src');

    var request = jQuery.ajax({
      url: src,
      cache: false,
      type: "POST",
      data: {video_id : video_id, user_id: user_id},
      dataType: "json"
    });
    request.done(function(response) {
      jQuery('#unSaveVideo').css("display", "block");
      jQuery('#saveVideo').css("display", "none");
    });
  });

  jQuery('#unSaveVideo').click(function(e){
    var video_id = jQuery('#unSaveVideo').attr('data-video-id');
    var user_id = jQuery('#unSaveVideo').attr('data-id');
    var src = jQuery('#unSaveVideo').attr('data-src');

    var request = jQuery.ajax({
      url: src,
      cache: false,
      type: "POST",
      data: {video_id : video_id, user_id: user_id},
      dataType: "json"
    });
    request.done(function(response) {
      if(response.msg=='success'){
        jQuery('#saveVideo').css("display", "block");
        jQuery('#unSaveVideo').css("display", "none");
      }else{
        jQuery('#saveVideo').css("display", "block");
        jQuery('#unSaveVideo').css("display", "none");
      }
    });
  });

  jQuery('.save_video').click(function(e){
    var video_id = jQuery(this).attr('data-video-id');
    var user_id = jQuery(this).attr('data-id');
    var src = jQuery(this).attr('data-src');

    var request = jQuery.ajax({
      url: src,
      cache: false,
      type: "POST",
      data: {video_id : video_id, user_id: user_id},
      dataType: "json"
    });
    request.done(function(response) {
      location.reload();
    });
  });

  jQuery('.unsave_video').click(function(e){
    var video_id = jQuery(this).attr('data-video-id');
    var user_id = jQuery(this).attr('data-id');
    var src = jQuery(this).attr('data-src');

    var request = jQuery.ajax({
      url: src,
      cache: false,
      type: "POST",
      data: {video_id : video_id, user_id: user_id},
      dataType: "json"
    });
    request.done(function(response) {
      location.reload();
    });
  });




})(jQuery); // End of use strict

