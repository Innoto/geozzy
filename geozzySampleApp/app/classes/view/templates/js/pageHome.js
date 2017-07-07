$( document ).ready(function(){
  initEffectNavs();
  headerTransparent();
  secRegistroBinds();

  // new geozzy.generateModal( {
  //   classCss: "plazoInicioModal",
  //   htmlBody: __( "La inscripción para la actual edición del programa se abrirá a partir del 29 de mayo de 2017 a las 13:00 horas."),
  //   successCallback: function(){ return false; }
  // } );
});

function secRegistroBinds(){
  $('.initParticipation').on('click', function(){
    geozzy.userSessionInstance.userControlAccess(
      function(){ window.location.href = '/solicitud-inscripcion' ; },
      function(){ },
      'register'
    );
  });
  $('.continueParticipation').on('click', function(){
    geozzy.userSessionInstance.userControlAccess( function(){
      window.location.href = '/solicitud-inscripcion' ;
    });
  });
}

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
