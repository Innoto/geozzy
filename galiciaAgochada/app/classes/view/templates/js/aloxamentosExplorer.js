



    $(document).ready(function(){

      var explorerclass = '.aloxamentosExplorer';

      // ESTO CHEGARÍA POR CHAMADA AJAX
      var dataFilter1 = [
        {value:'*', title: 'Todas'},
        {value:'10', title: 'Galega swagger'},
        {value:'11', title: 'Canibal'},
        {value:'12', title: 'Indo oceánica'}
      ];


      var dataFilter2 = [
        {value:'*', title: 'Todos os públicos'},
        {value:'13', title: 'Nenos'},
        {value:'14', title: 'Adultos'},
        {value:'15', title: 'Toda a familia'}
      ];

      var dataFilter3 = [
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
      var listaActiva = new geozzy.explorerDisplay.activeListView();
      var mapa = new geozzy.explorerDisplay.mapView();
      mapa.setMap( resourceMap );


      //explorer.addDisplay( 'activeList', listaActiva );
      explorer.addDisplay( 'map', mapa );




      // FILTROS
      explorer.addFilter(
        new geozzy.filters.filterSelectSimpleView(
          {
            mainCotainerClass: explorerclass+' .explorer-container-filter',
            containerClass: 'filtro1',
            title:'Tipo de cociña',
            data: dataFilter1
          }
        )
      );
      explorer.addFilter(
        new geozzy.filters.filterSelectSimpleView(
          {
            mainCotainerClass: explorerclass+' .explorer-container-filter',
            containerClass: 'filtro2',
            title:'Idades',
            data: dataFilter2
          }
        )
      );
      explorer.addFilter(
        new geozzy.filters.filterSelectSimpleView(
          {
            mainCotainerClass: explorerclass+' .explorer-container-filter',
            containerClass: 'filtro3',
            title:'Horario de apertura',
            data: dataFilter3
          }
        )
      );


      // EXECUCIÓN EXPLORADOR
      explorer.exec();


    });
