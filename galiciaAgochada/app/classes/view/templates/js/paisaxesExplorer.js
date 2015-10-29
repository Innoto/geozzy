



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
      var infowindow = new geozzy.explorerDisplay.mapInfoView();
      var listaPasiva = new geozzy.explorerDisplay.pasiveListView({ el:$('.explorer-container-gallery')});
      var mapa = new geozzy.explorerDisplay.mapView({ map: resourceMap, clusterize:false });


      explorer.addDisplay( listaPasiva );
      explorer.addDisplay( mapa );
      explorer.addDisplay( infowindow );



      // FILTROS

      $( explorerclass+' .explorer-container-filter').html(
        '<div class="container">'+
          '<div class="row">'+
            '<div class="col-md-6 col-sm-4 hidden-xs explorerPaisaxesTitle" >'+
              '<img class="img-responsive" alt="Paisaxes Espectaculares" src="/media/img/paisaxesIcon.png"></img>'+
              '<h1>Paisaxes Espectaculares</h1>'+
            '</div>'+
            '<div class="col-md-6 col-sm-8 col-xs-12 explorerPaisaxesFilters clearfix" ></div>'+
          '</div>'+
        '</div>'
      );


      explorer.addFilter(
        new geozzy.filters.filterSelectSimpleView(
          {
            mainCotainerClass: explorerclass+' .explorer-container-filter .explorerPaisaxesFilters',
            containerClass: 'tipoZona select2GeozzyRed',
            data: dataFilter2
          }
        )
      );
      explorer.addFilter(
        new geozzy.filters.filterSelectSimpleView(
          {
            mainCotainerClass: explorerclass+' .explorer-container-filter .explorerPaisaxesFilters',
            containerClass: 'tipoPaisaxe select2GeozzyCustom',
            //title:'asdfasfd',
            data: dataFilter1
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
      $('select.select2GeozzyRed').select2();
      //LAYOUT
      layoutDistributeSize();
    });

    $(window).bind("load resize", function() {
      layoutDistributeSize();
    });

    function formatState (state) {
      if (!state.id) { return state.text; }
      var $state = $(
        '<span><i class="fa fa-tree"></i> ' + state.text + '</span>'
      );
      return $state;
    };

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
