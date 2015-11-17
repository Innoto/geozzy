



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
              '<li class="elementOpt elementFav"><i class="fa"></i></li>'+
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
