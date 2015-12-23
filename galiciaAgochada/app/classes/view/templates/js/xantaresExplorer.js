

    var explorerclass = '.xantaresExplorer';
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
      var explorer = new geozzy.explorer({
        debug:false,
        explorerSectionName:'Paisaxes espectaculares',
        resourceAccess: function(id) {
          //alert('mecagoenmimaquina'+id)
          $(".explorerContainer.explorer-container-du").load(
            '/resource/'+id,
            { pf: 'blk' },
            function() {
              $(".explorerContainer.explorer-container-du").show();
            }
          );

        },
        resourceQuit: function() {

          $(".explorerContainer.explorer-container-du").hide();
          $(".explorerContainer.explorer-container-du").html('');
        }

      });



      // METRICAS

      explorer.setMetricsExplorer( new geozzy.biMetrics.controller.explorer() );
      explorer.setMetricsResource( new geozzy.biMetrics.controller.resource() );



      // DISPLAYS

      var infowindow = new geozzy.explorerDisplay.mapInfoView();

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




      //explorer.addDisplay( listaPasiva );
      explorer.addDisplay( mapa );
      explorer.addDisplay( infowindow );



      // FILTROS

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

      for (var i = 0; i < 12; i++) {

        var tempElemHtml = '<div data-resource-id="" class="col-md-12 element">'+
          '<div class="elementImg">'+
            '<img class="img-responsive" src="http://lorempixel.com/530/213/food/'+i+'" />'+
            '<ul class="elementOptions container-fluid">'+
              '<li class="elementOpt elementFav">'+
                '<i class="fa fa-heart-o"></i>'+
                '<i class="fa fa-heart"></i></li>'+
            '</ul>'+
          '</div>'+
          '<div class="elementInfo">'+
            '<div class="elementTitle">Lorem ipsum dolor sit amet, consectetur</div>'+
            '<div class="elementType"><i class="fa fa-cutlery"></i> Furancho</div>'+
            '<div class="elementPrice">'+(i*103)+'€<span>/persona</span></div>'+
          '</div>'+
        '</div>';
        $('.explorer-container-gallery').append(tempElemHtml);
      }



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





/*

    $(document).ready(function(){

      var explorerclass = '.xantaresExplorer';


      // ESTO CHEGARÍA POR CHAMADA AJAX
      var dataFilter1 = [
        {value:'*', title: 'Todas'},
        {value:'10', title: 'Galega swagger'},
        {value:'11', title: 'Canibal'},
        {value:'12', title: 'Indo oceánica'}
      ];



      // GOOGLE MAPS MAPS
      var mapOptions = {
        center: { lat: 43.1, lng: -7.36 },
        zoom: 8
      };

      var resourceMap = new google.maps.Map( $( explorerclass+' .explorerMap').get( 0 ), mapOptions);



      // EXPLORADOR
      var explorer = new geozzy.explorer({debug:false});


      // DISPLAYS
      var infowindow = new geozzy.explorerDisplay.mapInfoView();
      //var listaPasiva = new geozzy.explorerDisplay.pasiveListView({ el:$('.explorer-container-gallery')});
      var mapa = new geozzy.explorerDisplay.mapView({ map: resourceMap, clusterize:false });


      //explorer.addDisplay( listaPasiva );
      explorer.addDisplay( mapa );
      explorer.addDisplay( infowindow );

      explorer.addFilter(
        new geozzy.filters.filterSelectSimpleView(
          {
            mainCotainerClass: explorerclass+' .explorer-container-filter .explorerFilters',
            containerClass: 'tipoPaisaxe select2GeozzyCustom',
            //title:'asdfasfd',
            data: dataFilter1
          }
        )
      );

      // EXECUCIÓN EXPLORADOR
      explorer.exec();

      for (var i = 0; i < 12; i++) {

        var tempElemHtml = '<div data-resource-id="" class="col-md-12 element">'+
          '<div class="elementImg">'+
            '<img class="img-responsive" src="http://lorempixel.com/530/213/food/'+i+'" />'+
            '<ul class="elementOptions container-fluid">'+
              '<li class="elementOpt elementFav">'+
                '<i class="fa fa-heart-o"></i>'+
                '<i class="fa fa-heart"></i></li>'+
            '</ul>'+
          '</div>'+
          '<div class="elementInfo">'+
            '<div class="elementTitle">Lorem ipsum dolor sit amet, consectetur</div>'+
            '<div class="elementType"><i class="fa fa-cutlery"></i> Furancho</div>'+
            '<div class="elementPrice">'+(i*103)+'€<span>/persona</span></div>'+
          '</div>'+
        '</div>';
        $('.explorer-container-gallery').append(tempElemHtml);
      }


    });
*/
