



    $(document).ready(function(){

      var explorerclass = '.paisaxesExplorer';


      // ESTO CHEGARÍA POR CHAMADA AJAX
      var dataFilter1 = [
        {value:'*', title: 'Todas'},
        {value:'10', title: 'Galega swagger'},
        {value:'11', title: 'Canibal'},
        {value:'12', title: 'Indo oceánica'}
      ];

      var dataFilter2 = [
        {value:'*', title: 'Calquera'},
        {value:'16', title: 'De mañá'},
        {value:'17', title: 'De tarde'},
        {value:'18', title: 'Todo o día'}
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
      var listaPasiva = new geozzy.explorerDisplay.pasiveListView({ el:$('.explorer-container-gallery')});
      var mapa = new geozzy.explorerDisplay.mapView();
      mapa.setMap( resourceMap );


      explorer.addDisplay( listaPasiva );
      explorer.addDisplay( mapa );




      // FILTROS
      explorer.addFilter(
        new geozzy.filters.filterSelectSimpleView(
          {
            mainCotainerClass: explorerclass+' .explorer-container-filter',
            containerClass: '.tipoPaisaxe .select2GeozzyCustom',
            title:'',
            data: dataFilter1
          }
        )
      );
      explorer.addFilter(
        new geozzy.filters.filterSelectSimpleView(
          {
            mainCotainerClass: explorerclass+' .explorer-container-filter',
            containerClass: '.tipoZona',
            title:'',
            data: dataFilter2
          }
        )
      );


      // EXECUCIÓN EXPLORADOR
      explorer.exec();

      $('.select2GeozzyCustom').select2();
    });
