
$(document).ready( function() {

  if( typeof geozzy.rextReccommended.reccommendedView != 'undefined') {
    new geozzy.rextReccommended.reccommendedView({
      onRender:function(){
        initMyOwl();
      }
    });
  }
});

function initMyOwl(){
  $('.rExtReccommendedList').owlCarousel({
    margin:10,
    nav:true,
    responsive:{
      0:{
          items:1
      },
      767:{
          items:3
      },
      1200:{
          items:4
      }
    }
  });


  $('.rExtReccommendedList .owl-nav .owl-prev').html('<i class="fas fa-angle-left"></i>');
  $('.rExtReccommendedList .owl-nav .owl-next').html('<i class="fas fa-angle-right"></i>');

  initMyOwlNavs();
}

function initMyOwlNavs(){
  $('a.page-scroll').bind('click', function scrollSuave(event) {
    var hrefText = $(this).attr('href');
    if( hrefText.indexOf( '#' ) === 0 ) {
      var $anchor = $( hrefText );
      if( $anchor.length > 0) {
        $('html, body').stop().animate({
            scrollTop: $anchor.offset().top
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
      }
    }
  });
}
