$( document ).ready(function(){
  Modernizr.load({ "classPrefix": "foo-", "feature-detects": ["dom/hidden"] });
  $('.headContent').addClass('transparent');
  $(window).bind("scroll", function(e) {
    if($(this).scrollTop() > 0){
      $('.headContent').removeClass('transparent');
    }else{
      $('.headContent').addClass('transparent');
    }
  });

  $('.owl-carousel').owlCarousel({
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

  $('.owl-carousel .owl-nav .owl-prev').html('<i class="fa fa-angle-left"></i>');
  $('.owl-carousel .owl-nav .owl-next').html('<i class="fa fa-angle-right"></i>');

  initEffectNavs();

  // autodetección de idioma
  path = window.location.pathname.split('/');
  if(path[1]===''){ // url sen idioma
    if (navigator.appName == 'Netscape' || 'Microsoft Internet Explorer' || 'Opera')
      var idioma = navigator.language;
    else
      var idioma = navigator.browserLanguage;

    lang_array = idioma.split('-');
    lang = lang_array[0];
    window.location = window.location.pathname + lang;
  }

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
