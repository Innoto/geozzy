

    var explorerclass = '.paisaxesExplorer';
    var resourceMap  = false;
    var espazoNaturalCategories = false;


    $(document).ready(function(){

      var mapOptions = {
        center: { lat: 43.1, lng: -7.36 },
        zoom: 8
      };
      resourceMap = new google.maps.Map( $( explorerclass+' .explorerMap').get( 0 ), mapOptions);


      espazoNaturalCategories = new geozzy.collections.CategorytermCollection();
      espazoNaturalCategories.setUrlByIdName('rextAppEspazoNaturalType');


      // Multiple data fetch
      $.when( espazoNaturalCategories.fetch() ).done(function() {

        setExplorer(  );
      });
    });


    function setExplorer( ) {


      // ESTO CHEGARÍA POR CHAMADA AJAX
      var dataFilter1 = [
        {value:'*', title: 'Todas'},
        {value:'10', title: 'Galega swagger'},
        {value:'11', title: 'Canibal'},
        {value:'12', title: 'Indo oceánica'}
      ];




      // EXPLORADOR
      var explorer = new geozzy.explorer({debug:false});



      // DISPLAYS
      var infowindow = new geozzy.explorerDisplay.mapInfoView();
      var listaPasiva = new geozzy.explorerDisplay.pasiveListView({ el:$('.explorer-container-gallery')});
      var mapa = new geozzy.explorerDisplay.mapView({ map: resourceMap, clusterize:false });


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
            //title:'asdfasfd',
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
      if ( $(state.element).attr('icon') == false) { return state.text; }


      var $state = $(
        '<span><img width=32 height=32 src="/cgmlImg/' + $(state.element).attr('icon') + '"/></i> ' + state.text + '</span>'
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
