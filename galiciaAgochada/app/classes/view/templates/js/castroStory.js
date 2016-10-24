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

  $(window).on('resize', setSizes);
  google.maps.event.addListenerOnce( resourceMap , 'idle', function(){
    setSizes();
    historia.triggerEvent( 'windowResize');
    google.maps.event.addListenerOnce( resourceMap , 'mousemove', function(){
      setSizes();
      historia.triggerEvent( 'windowResize');
    });
  });
  google.maps.event.addListenerOnce( resourceMap , 'projection_changed', function(){

  });


  var historia  = new geozzy.story({
    storyReference:'castro'
  });


  var displayLista = new geozzy.storyComponents.StoryListView({
    container: '.storyLayout .lista'
  });

  var displayMapa = new geozzy.storyComponents.StoryBackgroundView({
    map: resourceMap
  });

  var displayLeyenda = new geozzy.storyComponents.StoryPluginLegendView({container:'.storyLayout .castroLeyendaContainer'});
  var displayPOIS = new geozzy.storyComponents.StoryPluginPOISView();
  var displayKML = new geozzy.storyComponents.StoryPluginKMLView();
  var displayTimeline = new geozzy.storyComponents.StoryPluginTimelineView({container:'.storyLayout .castroTimelineContainer'});


  historia.addDisplay( displayLista );
  historia.addDisplay( displayMapa );
  historia.addDisplay( displayLeyenda );
  historia.addDisplay( displayPOIS );
  historia.addDisplay( displayKML );
  historia.addDisplay( displayTimeline );


  historia.bindEvent('loadResource', function(resourceId){
    $(".storyContainer.story-loading").show();
    $(".storyContainer.story-container-du").load(
      '/'+cogumelo.publicConf.C_LANG+'/resource/'+resourceId,
      { pf: 'blk' },
      function() {
        $('html, body').css('overflowY', 'hidden');
        $('.storyLayout .lista').hide();
        $(".storyContainer.story-loading").hide();
        $(".storyContainer.story-container-du").show();
      }
    );

  });

  historia.bindEvent('loadMain', function(){
    $('.storyLayout .lista').show();
    $(".storyContainer.story-container-du").hide();
    $(".storyContainer.story-container-du").html('');
    $('html, body').css('overflowY', 'visible');
  });



  historia.exec();
  setSizes();
});



function setSizes() {
  console.log('RESIZE')
  var altoCabeceira = ($('header.headContent').height() + $('.bodyContent .titleBar').height() );

  $('.storyLayout .mapa').height( $(window).height() - altoCabeceira);
  $('.storyLayout .mapa').width( $(window).width() );
  $('.storyLayout .mapa').css( 'position', 'fixed' );
  $('.storyLayout .mapa').css( 'top', altoCabeceira );
}
