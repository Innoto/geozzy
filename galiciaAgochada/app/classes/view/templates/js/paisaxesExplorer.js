

    $(document).ready(function(){


      // ESTO CHEGARÍA POR CHAMADA AJAX
      var dataFilter1 = [
        {value:'*', title: 'Todas'},
        {value:'10', title: 'Galega'},
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
      var resourceMap = new google.maps.Map( document.getElementById('explorerMap'), mapOptions);


      // EXPLORADOR
      var explorer = new geozzy.explorer({debug:false});
      mapa.setMap( resourceMap );


      // FILTROS
      explorer.addFilter(
        new geozzy.filters.filterListSimple({containerQueryDiv: '.explorer-container-filter', DivId: 'filtro1', title:'Tipo de cociña', data: dataFilter1 })
      );
      explorer.addFilter(
        new geozzy.filters.filterListSimple({containerQueryDiv: '.explorer-container-filter', DivId: 'filtro2', title:'Idades', data: dataFilter2 })
      );
      explorer.addFilter(
        new geozzy.filters.filterListSimple({containerQueryDiv: '.explorer-container-filter', DivId: 'filtro3', title:'Horario de apertura', data: dataFilter3 })
      );


      // DISPLAYS
//    explorer.addDisplay( 'activeList', listaActiva );
      explorer.addDisplay( 'map', new geozzy.explorerDisplay.map() );

      explorer.exec();


    });
