$(document).ready( function(){





          var timeline;
          var data;

          // Called when the Visualization API is loaded.
          function drawVisualization() {
              // Create a JSON data table
              data = [
                  {
                      'start': new Date(1800,7,23),
                      'content': 'EV1'
                  },
                  {
                      'start': new Date(1900,7,23,23,0,0),
                      'content': 'EV2'
                  },
                  {
                      'start': new Date(1850,7,24,16,0,0),
                      'content': 'EV3'
                  },
                  {
                      'start': new Date(2000,7,26),
                      'end': new Date(2010,8,2),
                      'content': 'EV4'
                  },
                  {
                      'start': new Date(1700,7,28),
                      'content': 'EV5'
                  },

                  {
                      'start': new Date(2010,8,4,12,0,0),
                      'content': 'EV6'
                  }
              ];

              // specify options
              var options = {
                  /*'width':  '100%',
                  'height': '300px',*/
                  'editable': false,   // enable dragging and editing events
                  'style': 'box',
                  'locale':'es',
                  'zoomable': false,
                  'unselectable':false,
                  'cluster':false
              };

              // Instantiate our timeline object.
              timeline = new links.Timeline( $('.castroTimelineContainer')[0] , options);
              timeline.setSelection(3);
/*
              function onRangeChanged(properties) {
                  document.getElementById('info').innerHTML += 'rangechanged ' +
                          properties.start + ' - ' + properties.end + '<br>';
              }
*/
              // attach an event listener using the links events handler
              //links.events.addListener(timeline, 'rangechanged', onRangeChanged);

              // Draw our timeline with the created data and options
              timeline.draw(data);
          }



          drawVisualization();







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

  var displayLeyenda = new geozzy.storyComponents.StoryPluginLegendView({container:'.storyLayout .castroLeyendaContainer'});

  var displayPOIS = new geozzy.storyComponents.StoryPluginPOISView();
  var displayKML = new geozzy.storyComponents.StoryPluginKMLView();

  historia.addDisplay( displayLista );
  historia.addDisplay( displayMapa );
  historia.addDisplay( displayLeyenda );
  historia.addDisplay( displayPOIS );
  historia.addDisplay( displayKML );

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
  var altoCabeceira = ($('header.headContent').height() + $('.bodyContent .titleBar').height() );

  $('.storyLayout .mapa').height( $(window).height() - altoCabeceira);
  $('.storyLayout .mapa').width( $(window).width() );
  $('.storyLayout .mapa').css( 'position', 'fixed' );
  $('.storyLayout .mapa').css( 'top', altoCabeceira );
}
