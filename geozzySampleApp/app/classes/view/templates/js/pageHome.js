$( document ).ready(function(){
  initEffectNavs();
  headerTransparent();

});



function headerTransparent(){
  $('.navbar').addClass('transparent');
  $(window).bind("scroll", function(e) {
    if($(this).scrollTop() > 0){
      $('.navbar').removeClass('transparent');
    }else{
      $('.navbar').addClass('transparent');
    }
  });
}

function initEffectNavs(){
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
