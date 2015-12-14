

    var explorerclass = '.paisaxesExplorer';
    var resourceMap  = false;
    var espazoNaturalCategories = false;


    $(document).ready(function(){

      //var data = new Date();
      //console.log( Date.UTC(data.getUTCFullYear(),data.getUTCMonth(), data.getUTCDate() , data.getUTCHours(), data.getUTCMinutes(), data.getUTCSeconds(), data.getUTCMilliseconds()) )

      var mapOptions = {
        center: { lat: 43.1, lng: -7.36 },
        zoom: 8
      };
      resourceMap = new google.maps.Map( $( explorerclass+' .explorerMap').get( 0 ), mapOptions);


      espazoNaturalCategories = new geozzy.collection.CategorytermCollection();
      espazoNaturalCategories.setUrlByIdName('rextAppEspazoNaturalType');


      // Multiple data fetch
      $.when( espazoNaturalCategories.fetch() ).done(function() {

        setExplorer(  );
      });
    });


    function setExplorer( ) {

      // EXPLORADOR
      var explorer = new geozzy.explorer({debug:false});



      // METRICAS

      explorer.setMetricsExplorer( new geozzy.biMetrics.controller.explorer() );
      explorer.setMetricsResource( new geozzy.biMetrics.controller.resource() );



      // DISPLAYS

      var infowindow = new geozzy.explorerDisplay.mapInfoView();
      var listaPasiva = new geozzy.explorerDisplay.pasiveListView({ el:$('.explorer-container-gallery')});
      var mapa = new geozzy.explorerDisplay.mapView({
          map: resourceMap,
          clusterize:false,
          chooseMarkerIcon: function( markerData ) {
            var iconUrl = false;

            espazoNaturalCategories.each( function(e){
              //console.log(e.get('id'))
              //console.debug(markerData.get('terms'))

              if( $.inArray(e.get('id'), markerData.get('terms')) > -1 ) {

                if( jQuery.isNumeric( e.get('icon') )  ){
                  iconUrl = '/cgmlImg/'+e.get('icon')+'/explorerMarker/marker.png';
                  return false;
                }

              }

            });

            return iconUrl;
          }
      });


      //map set icons




      explorer.addDisplay( listaPasiva );
      explorer.addDisplay( mapa );
      explorer.addDisplay( infowindow );



      // FILTROS

      $( explorerclass+' .explorer-container-filter').html(
        '<div class="titleBar">'+
          '<div class="container">'+
            '<div class="row">'+
              '<div class="col-md-6 col-sm-4 hidden-xs explorerTitle" >'+
                '<img class="img-responsive" alt="Paisaxes Espectaculares" src="/media/img/paisaxesIcon.png"></img>'+
                '<h1>Paisaxes Espectaculares</h1>'+
              '</div>'+
              '<div class="col-md-6 col-sm-8 col-xs-12 explorerFilters clearfix" ></div>'+
            '</div>'+
          '</div>'+
        '</div>'
      );
      explorer.addFilter(
        new geozzy.filters.filterSelectSimpleView(
          {
            mainCotainerClass: explorerclass+' .explorer-container-filter .explorerFilters',
            containerClass: 'tipoPaisaxe select2GeozzyCustom',
            defaultOption: { icon: false, title: 'Todas as paisaxes', value:'*' },
            data: espazoNaturalCategories
          }
        )
      );





      // EXECUCIÓN EXPLORADOR
      explorer.exec();


      $('select.select2GeozzyCustom').select2({
         minimumResultsForSearch: -1,
         templateSelection: formatState,
         templateResult: formatState
      });
      //LAYOUT
      layoutDistributeSize();

    }



    $(window).bind("load resize", function() {
      layoutDistributeSize();
    });

    function formatState (state) {

      $ret = false;

      if( $(state.element).val() == '*' &&  $(state.element).attr('icon')  !='false' ) {
        $ret = $('<span><img width=32 height=32 src="/' + $(state.element).attr('icon') + '"/></i> ' + state.text + '</span>');
      }
      else
      if ( $(state.element).attr('icon') != 'false') {
        $ret = $('<span><img width=32 height=32 src="/cgmlImg/' + $(state.element).attr('icon') + '"/></i> ' + state.text + '</span>');
      }
      else {
        $ret = state.text;
      }

      return $ret;
    }

    function layoutDistributeSize(){
      var hExplorerLayout = $('.paisaxesExplorer').height();
      var hExplorerFilters = $('.paisaxesExplorer .explorer-container-filter').height();
      var hExplorerGallery = $('.paisaxesExplorer .explorer-container-gallery').height();
      var hHeader = 100;
      var hExplorerMap = hExplorerLayout - (hExplorerGallery + hExplorerFilters + hHeader);

      $('.paisaxesExplorer .explorer-container-map').height( hExplorerMap );

      console.log('hExplorerLayout: ', hExplorerLayout );
      console.log('hExplorerFilters: ', hExplorerFilters );
      console.log('hExplorerGallery: ', hExplorerGallery );
      console.log('hExplorerMap: ', hExplorerMap );
    }
