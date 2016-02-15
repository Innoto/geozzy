


  $(document).ready(function(){
    explorador = new todosSegredosExplorer();

    explorador.interfaceFilters();

    explorador.setInitialData( function() {
      explorador.setExplorer();
      explorador.setDisplays();
      explorador.setFilters();
      explorador.exec();
      //$( '.explorerFilterElement .especialidadeEatandDrink').select2();
    });

    // autodetección de idioma
    path = window.location.pathname.split('/');

    has_lang = false;
    $(langAvailableIds).each(function(i,e){
      if (path[1]===e){
        has_lang = true;
      }
    });

    if(!has_lang){ // url sen idioma
      if (navigator.appName == 'Netscape' || 'Microsoft Internet Explorer' || 'Opera'){
        var idioma = navigator.language;
      }
      else{
        var idioma = navigator.browserLanguage;
      }
      lang_array = idioma.split('-');
      window.location = lang_array[0] + window.location.pathname;
    }

  });









  /****************************
    paisaxesExplorer
   ****************************/
  todosSegredosExplorer = function() {
    var that = this;


    that.explorerclass = '.todosSegredosExplorer';
    that.mapOptions = false;
    that.resourceMap  = false;
    that.eatAndDrinkTypes = false;
    that.eatAndDrinkSpecialities = false;

    that.infowindow = false;
    that.listaMini = false;
    that.mapa = false;




    /**
      setInitialData. Preset objects and get values for the filters
     */
    that.setInitialData = function( doneFunction ){
      that.mapOptions = {
        center: { lat: 43.1, lng: -7.36 },
        mapTypeControl: false,
        zoom: 8,
        styles : mapTheme
      };
      that.resourceMap = new google.maps.Map( $( that.explorerclass+' .explorerMap').get( 0 ), that.mapOptions);
      mapControlUtils = new mapControlsUtils();
      mapControlUtils.changeMapControls(that.resourceMap);

      that.eatAndDrinkTypes = new geozzy.collection.CategorytermCollection();
      that.eatAndDrinkTypes.setUrlByIdName('eatanddrinkType');

      that.eatAndDrinkSpecialities = new geozzy.collection.CategorytermCollection();
      that.eatAndDrinkSpecialities.setUrlByIdName('eatAndDrinkSpecialities');

      that.zonaCategories = new geozzy.collection.CategorytermCollection();
      that.zonaCategories.setUrlByIdName('rextAppZonaType');

      // Multiple data fetch
      $.when( that.eatAndDrinkTypes.fetch(), that.eatAndDrinkSpecialities.fetch(), that.zonaCategories.fetch() ).done(function() {
        doneFunction();
      });
    }




    /**
      setExplorer. instance the explorer object
     */
    that.setExplorer = function() {

      that.explorer = new geozzy.explorer({
        debug: false,
        explorerId:'todosSegredos',
        explorerSectionName:'Todos os segredos',

        resourceAccess: function(id) {
          $(".explorerContainer.explorer-loading").show();
          $(".explorerContainer.explorer-container-du").load(
            '/resource/'+id,
            { pf: 'blk' },
            function() {
              $(".explorerContainer.explorer-loading").hide();
              $(".explorerContainer.explorer-container-du").show();
            }
          );

        },
        resourceQuit: function() {
          $(".explorerContainer.explorer-container-du").hide();
          $(".explorerContainer.explorer-container-du").html('');
        }

      });

    }





    /**
      setDisplays. set explorer display objects
     */
    that.setDisplays = function() {

      that.infowindow = new geozzy.explorerDisplay.mapInfoView();
      that.listaMini = new geozzy.explorerDisplay.activeListView({
          el:$('.explorer-container-gallery'),
          categories: that.eatAndDrinkTypes
      });
      that.mapa = new geozzy.explorerDisplay.mapView({
          map: that.resourceMap,
          clusterize:true,
          chooseMarkerIcon: function( markerData ) {

            var iconUrl = false;
            var retObj = false;

            that.eatAndDrinkTypes.each( function(e){
              //console.log(e.get('id'))
              //console.debug(markerData.get('terms'))

              if( $.inArray(e.get('id'), markerData.get('terms')) > -1 ) {

                if( jQuery.isNumeric( e.get('icon') )  ){
                  iconUrl = '/cgmlImg/'+e.get('icon')+'/explorerXantaresMarker/marker.png';
                  return false;
                }

              }

            });

            if(iconUrl) {

              retObj = {
                url: iconUrl,
                // This marker is 20 pixels wide by 36 pixels high.
                size: new google.maps.Size(24, 24),
                // The origin for this image is (0, 0).
                origin: new google.maps.Point(0, 0),
                // The anchor for this image is the base of the flagpole at (0, 36).
                anchor: new google.maps.Point(12, 12)
              }
            }

            return retObj;

          },

          clustererStyles: [{
                url: "/media/img/segredosClusterIconSmall.png",
                height: 28,
                width: 28,
                textColor: '#FFFFFF',
                anchor:[0,100],
                textSize: 15
              },
              {
                url: "/media/img/segredosClusterIcon.png",
                height: 32,
                width: 32,
                textColor: '#FFFFFF',
                anchor:[0,100],
                textSize: 17
              }]
      });


      that.explorer.addDisplay( that.listaMini );
      that.explorer.addDisplay( that.mapa );
      that.explorer.addDisplay( that.infowindow );

    }






    /**
      setFilters. set explorer filter objects
     */
    that.setFilters = function() {




      //TEMP ADD FILTER MAP*-----------------------------------------------------------------------------------------------------------------------
      var Coords = [
        { idName: "baixominoVigo", dataCoords:"57,327,59,372,78,357,92,341,108,333,133,331,158,313,158,298,151,301,144,296,146,285,136,280,137,275,129,275,118,276,116,279,106,281,103,275,97,280,96,290,90,290,77,300", dataImg:media+"/module/rextAppZona/img/baixominoVigo.svg"},
        { idName: "terrasDePontevedra", dataCoords: "97,216,88,220,82,234,88,246,85,253,74,271,85,262,89,266,75,281,66,281,59,299,72,299,81,290,83,294,94,289,97,277,102,276,107,280,116,280,118,275,137,275,132,255,127,256,110,233,111,220,107,215", dataImg:media+"/module/rextAppZona/img/terrasDePontevedra.svg"},
        { idName: "arousa", dataCoords: "83,203,97,210,97,217,87,220,78,238,83,239,85,250,73,271,61,269,30,237,44,224,52,209,65,209", dataImg:media+"/module/rextAppZona/img/arousa.svg"},
        { idName: "costaDaMorte", dataCoords: "3,173,1,141,12,134,13,125,18,117,34,117,41,109,51,110,44,102,66,87,77,97,99,93,101,101,109,105,110,101,120,110,117,114,109,119,102,122,94,135,89,131,87,130,87,137,70,131,68,134,62,134,55,150,58,170,52,174,46,176,44,182,35,182,25,198,22,193,27,184,20,182,23,173,11,170", dataImg:media+"/module/rextAppZona/img/costaDaMorte.svg"},
        { idName: "aMarinaLucense", dataCoords: "285,96,284,74,274,81,261,78,257,64,242,64,244,53,240,48,231,58,217,48,227,9,231,5,237,24,246,9,257,14,271,23,284,39,301,44,316,45,316,57,310,66,309,69,299,69,299,78,303,81,304,90,309,95,303,103,294,102,291,100", dataImg:media+"/module/rextAppZona/img/aMarinaLucense.svg"},
        { idName: "ancaresCourel", dataCoords: "280,244,285,246,307,229,312,240,317,235,316,225,321,223,316,215,339,197,345,181,345,160,338,156,328,147,327,143,333,141,345,130,340,118,330,128,324,120,326,114,313,107,312,96,303,105,294,105,288,114,289,124,281,135,285,159,292,157,299,163,298,170,291,172,293,188,286,195,266,203,270,212,246,210,259,222,261,225,270,223,270,223,269,225,280,227,283,234", dataImg:media+"/module/rextAppZona/img/ancaresCourel.svg"},
        { idName: "manzanedaTrevinca", dataCoords: "260,310,270,314,281,309,288,303,297,300,303,305,308,301,318,310,322,307,328,315,339,307,342,304,350,307,352,299,352,277,345,250,329,246,313,248,301,262,294,284,290,272,280,264,272,265,275,274,272,280,264,282,259,300", dataImg:media+"/module/rextAppZona/img/manzanedaTrevinca.svg"},
        { idName: "ribeiraSacra", dataCoords: "224,179,235,186,237,192,250,194,246,207,260,226,269,222,270,228,278,227,283,237,279,243,281,245,307,230,312,250,300,262,294,281,286,269,279,264,271,266,272,280,261,285,257,301,254,300,259,308,253,310,247,298,242,294,238,286,230,291,227,284,218,288,221,280,212,275,214,270,202,260,204,251,195,247,199,240,192,237,197,217,200,207,208,205,217,182", dataImg:media+"/module/rextAppZona/img/ribeiraSacra.svg"},
        { idName: "verinViana", dataCoords: "223,363,234,349,241,345,237,342,243,338,242,319,253,318,255,312,281,312,284,306,294,301,302,306,307,299,317,310,321,306,330,316,327,322,323,323,319,332,320,336,324,336,327,344,321,356,310,356,304,349,297,349,298,365,292,371,274,377,238,377", dataImg:media+"/module/rextAppZona/img/verinViana.svg"},
        { idName: "celanovaAlimia", dataCoords: "160,381,184,370,187,370,195,361,199,375,222,363,238,345,236,342,243,337,242,319,253,320,255,310,250,307,236,305,227,322,214,318,214,323,206,318,194,310,188,296,170,293,167,316,155,317,156,332,166,333,169,341,159,352,152,364", dataImg:media+"/module/rextAppZona/img/celanovaAlimia.svg"},
        { idName: "terrasDeOurenseAllariz", dataCoords: "187,276,182,275,177,289,184,297,189,294,197,313,212,320,213,315,226,320,236,305,251,306,246,297,237,285,232,289,226,284,223,288,215,285,222,280,211,276,214,270,203,259,205,252,195,245,190,262,197,269,190,272", dataImg:media+"/module/rextAppZona/img/terrasDeOurenseAllariz.svg"},
        { idName: "oRibeiro", dataCoords: "156,237,161,242,177,242,184,236,190,234,199,240,192,251,189,264,196,270,186,278,180,276,177,288,183,295,170,290,166,315,160,316,155,306,158,298,151,300,144,297,146,287,138,279,130,255", dataImg:media+"/module/rextAppZona/img/oRibeiro.svg"},
        { idName: "dezaTabeiros", dataCoords: "135,186,132,195,128,195,122,202,115,204,112,201,102,203,94,210,98,217,106,214,110,221,110,234,127,256,139,252,147,242,151,242,156,237,164,242,178,240,182,237,192,236,198,208,192,204,183,198,186,185,175,185,169,182,155,183,146,186,145,189", dataImg:media+"/module/rextAppZona/img/dezaTabeiros.svg"},
        { idName: "lugoTerraCha", dataCoords: "191,108,187,120,193,137,190,140,196,167,180,184,186,184,184,199,197,207,208,204,214,183,224,177,235,183,237,191,251,194,246,210,269,212,266,203,292,189,290,174,299,170,297,162,285,160,279,132,290,125,286,115,293,106,292,99,283,97,286,78,276,82,261,79,256,67,241,64,244,53,240,50,233,57,219,48,208,74,199,76,192,96", dataImg:media+"/module/rextAppZona/img/lugoTerraCha.svg"},
        { idName: "terrasDeSantiago", dataCoords:"144,129,147,125,151,129,160,131,163,125,171,122,185,120,193,138,189,139,196,168,182,184,174,184,173,182,155,181,144,189,137,185,133,191,127,196,119,203,104,202,98,207,84,202,78,205,73,201,74,187,61,181,54,149,62,134,68,135,70,131,87,136,87,129,94,135,100,122,120,108,125,112,127,119,129,120,132,126,139,124", dataImg:media+"/module/rextAppZona/img/terrasDeSantiago.svg"},
        { idName: "murosNoia", dataCoords: "26,199,36,181,41,184,42,175,52,176,53,173,61,174,60,179,74,189,74,204,69,209,54,207,42,229,30,237,36,214", dataImg:media+"/module/rextAppZona/img/murosNoia.svg"},
        { idName: "ferrolTerra", dataCoords:"137,45,154,33,179,11,202,2,201,15,228,-1,224,14,218,47,207,76,199,74,197,80,196,88,192,100,178,96,171,88,170,80,156,80,142,68,133,59,135,54", dataImg:media+"/module/rextAppZona/img/ferrolTerra.svg"},
        { idName: "aCorunaAsMarinas", dataCoords: "123,81,129,88,133,78,139,74,151,91,151,76,157,81,166,78,177,96,189,100,193,102,185,121,166,124,157,132,149,127,136,122,132,127,125,119,125,111,110,101,107,105,100,101,100,92,108,93", dataImg:media+"/module/rextAppZona/img/aCorunaAsMarinas.svg"}
      ];
      that.zonaCategories.each( function(e,i){
        $.each(Coords, function(i2,e2){
          if( e2.idName == e.get('idName') ) {
            e.set('dataCoords', e2.dataCoords);
            e.set('dataImg', e2.dataImg);
          }
        });
      });
      //--------------------------------------------------------------------------------------------------------------------------------------------
      var filtroZona = new geozzy.explorerComponents.filters.filterGeoView(
        {
          mainContainerClass: that.explorerclass+' .explorer-container-map',
          containerClass: 'filterZona',
          textReset: __('Toda Galicia'),
          defaultOption: { icon: false, title: '', value:'*' },
          data: that.zonaCategories,
          onChange: that.chooseFTLayer
        }
      );


      var filtroTipos = new geozzy.explorerComponents.filters.filterButtonsView(
        {
          mainContainerClass: that.explorerclass+' .filters_fixed',
          containerClass: 'tipoEatandDrink',
          //defaultOption: { icon: false, title: 'Todos os tipos', value:'*' },
          data: that.eatAndDrinkTypes
        }
      );

      var filtroEspecialidades = new geozzy.explorerComponents.filters.filterComboView(
        {
          title:__('Especialidade'),
          mainContainerClass: that.explorerclass+' .filters_advancedFilters',
          containerClass: 'especialidadeEatandDrink',
          titleSummary: __('Especialidade'),
          summaryContainerClass: 'filters_summary_xantarestipos',
          defaultOption: { icon: false, title: __('Todas as especialidades'), value:'*' },
          data: that.eatAndDrinkSpecialities
        }
      );

      var filtroPrezo = new geozzy.explorerComponents.filters.filterSliderView(
        {
          title:__('Precio'),
          mainContainerClass: that.explorerclass+' .filters_advancedFilters',
          containerClass: 'xantaresPrice',
          titleSummary: __('Cun prezo inferior a'),
          summaryContainerClass: 'filters_summary_xantaresprezo',
          values:  [10]
        }
      );

      var filtroReset = new geozzy.explorerComponents.filters.filterResetView(
        {
          title:__('Ver todo'),
          mainContainerClass: that.explorerclass+' .filtersContainer',
          containerClass: 'resetFilters'
        }
      );


      that.explorer.addFilter( filtroEspecialidades );
      that.explorer.addFilter( filtroTipos );
      that.explorer.addFilter( filtroPrezo );
      that.explorer.addFilter( filtroReset );
      that.explorer.addFilter( filtroZona );
    }





    /**
      exec. execs the explorer
     */
    that.exec = function(){

      that.explorer.exec();

    }





    /**
      formatState. util method
     */
    that.formatState = function(state) {

      $ret = false;

      if( $(state.element).val() == '*' &&  $(state.element).attr('icon')  !='false' ) {
        $ret = $('<span><img width=24 height=24 src="/' + $(state.element).attr('icon') + '"/></i> ' + state.text + '</span>');
      }
      else
      if ( $(state.element).attr('icon') != 'false') {
        $ret = $('<span><img width=24 height=24 src="/cgmlImg/' + $(state.element).attr('icon') + '"/></i> ' + state.text + '</span>');
      }
      else {
        $ret = state.text;
      }

      return $ret;
    }


    /**
      createInterfaceFilterContainers. util method
     */
    that.interfaceFilters = function(){
      var filterContainer = $(that.explorerclass+' .explorer-container-filter');

      var filterInterface = '<div class="filtersContainer">';
      filterInterface += '<div class="filters_fixed"></div>';
      filterInterface += '<div class="filters_summary"><div class="filters_summary_xantarestipos"></div><div class="filters_summary_xantaresprezo"></div></div>';
      filterInterface += '<div class="filters_openFilters"><i class="fa fa-caret-down"></i></div>';
      filterInterface += '<div class="filters_advancedFilters"></div>';
      filterInterface += '</div>';
      filterContainer.html(filterInterface);

    }


    that.chooseFTLayer =  function( val ) {
      chooseFTLayer( val, that.zonaCategories,  that.resourceMap, that.mapOptions );
    }

  }
