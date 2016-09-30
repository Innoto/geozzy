$(document).ready( function(){


  // Instanciar MAPA
  var mapOptions = {
    center: { lat: 43.1, lng: -7.36 },
    mapTypeControl: false,
    zoom: 8,
    scrollwheel: false,
    draggable: !("ontouchend" in document),
    disableDoubleClickZoom: true,
    styles : mapTheme,
    mapTypeId: 'satellite'

  };

  var resourceMap = new google.maps.Map( $('.storyLayout .mapa').get( 0 ), mapOptions);

  setSizes();
  $(window).on('resize', setSizes);
  google.maps.event.addListener(resourceMap, "idle", setSizes);


  var historia  = new geozzy.story({
    storyReference:'castro'
  });


  var displayLista = new geozzy.storyComponents.StoryListView({
    container: '.storyLayout .lista'
  });

  var displayMapa = new geozzy.storyComponents.StoryBackgroundView({
    map: resourceMap
  });


  historia.addDisplay( displayLista );
  historia.addDisplay( displayMapa );

  historia.exec();
  setSizes();
});



function setSizes() {
  var altoCabeceira = ($('header.headContent').height() + $('.bodyContent .titleBar').height() );

  $('.storyLayout .mapa').height( $(window).height() - altoCabeceira);
  $('.storyLayout .mapa').width( $(window).width() );
  $('.storyLayout .mapa').css( 'position', 'fixed' );
  $('.storyLayout .mapa').css( 'top', altoCabeceira );
}
