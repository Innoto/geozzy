$( document ).ready(function(){
  /*Cambio de cabeceira*/
  $('.headContent').addClass('transparent');
  $(window).bind("scroll", function(e) {
    if($(this).scrollTop() > 0){
      $('.headContent').removeClass('transparent');
    }else{
      $('.headContent').addClass('transparent');
    }
  });
  /*Pechar o cabeceira reducida*/

  $(document).on('click','.navbar-collapse.in',function(e) {
    if( $(e.target).is('a') ) {
        $(this).collapse('hide');
    }
  });

  /*Sliders destacados*/
  $('.owl-carousel-gzz').owlCarousel({
    loop: true,
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

  $('.owl-carousel-gzz .owl-nav .owl-prev').html('<i class="fa fa-angle-left"></i>');
  $('.owl-carousel-gzz .owl-nav .owl-next').html('<i class="fa fa-angle-right"></i>');
  /*Effect Anchor*/
  initEffectNavs();

});

function initEffectNavs(){
  $('a.page-scroll').bind('click', function scrollSuave(event) {
    var hrefText = $(this).attr('href');
    if( hrefText.indexOf( '#' ) === 0) {
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
