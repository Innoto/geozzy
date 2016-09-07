
  $(document).ready(function(){

    xantaresElementTpl = '' +
      '<div data-resource-id="<%- id %>" class="col-md-12 element">'+
        '<div class="elementImg">'+
          '<img class="img-responsive" src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%- img %>/explorerXantaresImg/<%- img %>.jpg" />'+
          '<div data-resource-id="<%- id %>" class="elementHover accessButton">'+
            '<ul class="elementOptions container-fluid"></ul>'+
          '</div>'+
          '<div class="elementFav">'+
            '<div style="display:none;" data-favourite-resource="<%- id %>" data-favourite-status="0" class="rExtFavourite rExtFavouriteHidden">'+
              '<i class="fa fa-heart-o fav-off"></i><i class="fa fa-heart fav-on"></i>'+
            '</div>'+
          '</div>'+
        '</div>'+
        '<div class="elementInfo">'+
          '<div class="elementTitle"><%-title%></div>'+
          '<div class="elementType"><img src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%- category.icon %>/typeIconMini/<%- category.icon %>.png"/></i> <%- category.name %></div>'+
          '<% if( typeof averagePrice != "undefined" ){%> <div class="elementPrice"> <%= averagePrice %>€<span>/persona</span> </div> <%}%>'+
        '</div>'+
      '</div>';

    explorador = new xantaresExplorer();
    explorador.interfaceFilters();

    explorador.presetExplorer( function() {
      explorador.setExplorer();
    });

  });









  /****************************
    paisaxesExplorer
   ****************************/
  xantaresExplorer = function() {
    var that = this;


    that.explorerclass = '.xantaresExplorer';
    that.mapOptions = false;
    that.resourceMap  = false;
    that.eatAndDrinkTypes = false;
    that.eatAndDrinkSpecialities = false;

    that.infowindow = false;
    that.listaMini = false;
    that.mapa = false;

    that.explorer = false;
    that.explorer2 = false;



    /**
      setInitialData. Preset objects and get values for the filters
     */
    that.presetExplorer = function( doneFunction ){
      that.mapOptions = {
        center: { lat: 43.1, lng: -7.36 },
        zoom: 8,
        mapTypeControl: false,
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
/*
      that.explorer2 =  new geozzy.explorer({
        debug: false,
        explorerId:'paisaxes',
        explorerSectionName:'Paisaxes',
        resourceQuit: function() {
          $(".explorerContainer.explorer-container-du").hide();
          $(".explorerContainer.explorer-container-du").html('');
        }

      });
*/
      that.explorer = new geozzy.explorer({
        //partialLoadSuccess: function(){ that.layoutDistributeSize() },
        debug: false,
        explorerId:'xantares',
        explorerSectionName:'Sabrosos xantares',
        resourceAccess: function(id) {
          $(".explorerContainer.explorer-loading").show();
          $(".explorerContainer.explorer-container-du").load(
            '/'+cogumelo.publicConf.C_LANG+'/resource/'+id,
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

      that.setDisplays();
      that.setFilters();
      that.setParticipation();

      that.explorer.exec();
      //that.explorer2.exec();

    }





    /**
      setDisplays. set explorer display objects
     */
    that.setDisplays = function() {

      that.infowindow = new geozzy.explorerComponents.mapInfoView();
      that.listaMini = new geozzy.explorerComponents.activeListView({
          el:$('.explorer-container-gallery'),
          categories: that.eatAndDrinkTypes,
          tplElement: xantaresElementTpl
      });
      that.listaRecomendados =  new geozzy.explorerComponents.reccommendedListView();
      that.mapa = new geozzy.explorerComponents.mapView({
          map: that.resourceMap,
          clusterize:false,
          chooseMarkerIcon: function( markerData ) {
            var iconUrl = false;
            var retObj = false;


            that.eatAndDrinkTypes.each( function(e){
              if( $.inArray(e.get('id'), markerData.get('terms')) > -1 ) {

                if( jQuery.isNumeric( e.get('icon') )  ){
                  iconUrl = cogumelo.publicConf.mediaHost+'cgmlImg/'+e.get('icon')+'/explorerXantaresMarker/marker.png';
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
          }
      });



/*
      that.mapa2 = new geozzy.explorerComponents.mapView({
          map: that.resourceMap,
          clusterize:false
      });
*/

      that.explorer.addDisplay( that.listaMini );
      that.explorer.addDisplay( that.listaRecomendados );
      that.explorer.addDisplay( that.mapa );
      //that.explorer2.addDisplay( that.mapa2 );
      that.explorer.addDisplay( that.infowindow );

    }






    /**
      setFilters. set explorer filter objects
     */
    that.setFilters = function() {

      //TEMP ADD FILTER MAP*-----------------------------------------------------------------------------------------------------------------------
      var Coords = [
        { idName: "baixominoVigo", dataCoords:"57,327,59,372,78,357,92,341,108,333,133,331,158,313,158,298,151,301,144,296,146,285,136,280,137,275,129,275,118,276,116,279,106,281,103,275,97,280,96,290,90,290,77,300", dataImg:cogumelo.publicConf.media+"/module/rextAppZona/img/baixominoVigo.svg"},
        { idName: "terrasDePontevedra", dataCoords: "97,216,88,220,82,234,88,246,85,253,74,271,85,262,89,266,75,281,66,281,59,299,72,299,81,290,83,294,94,289,97,277,102,276,107,280,116,280,118,275,137,275,132,255,127,256,110,233,111,220,107,215", dataImg:cogumelo.publicConf.media+"/module/rextAppZona/img/terrasDePontevedra.svg"},
        { idName: "arousa", dataCoords: "83,203,97,210,97,217,87,220,78,238,83,239,85,250,73,271,61,269,30,237,44,224,52,209,65,209", dataImg:cogumelo.publicConf.media+"/module/rextAppZona/img/arousa.svg"},
        { idName: "costaDaMorte", dataCoords: "3,173,1,141,12,134,13,125,18,117,34,117,41,109,51,110,44,102,66,87,77,97,99,93,101,101,109,105,110,101,120,110,117,114,109,119,102,122,94,135,89,131,87,130,87,137,70,131,68,134,62,134,55,150,58,170,52,174,46,176,44,182,35,182,25,198,22,193,27,184,20,182,23,173,11,170", dataImg:cogumelo.publicConf.media+"/module/rextAppZona/img/costaDaMorte.svg"},
        { idName: "aMarinaLucense", dataCoords: "285,96,284,74,274,81,261,78,257,64,242,64,244,53,240,48,231,58,217,48,227,9,231,5,237,24,246,9,257,14,271,23,284,39,301,44,316,45,316,57,310,66,309,69,299,69,299,78,303,81,304,90,309,95,303,103,294,102,291,100", dataImg:cogumelo.publicConf.media+"/module/rextAppZona/img/aMarinaLucense.svg"},
        { idName: "ancaresCourel", dataCoords: "280,244,285,246,307,229,312,240,317,235,316,225,321,223,316,215,339,197,345,181,345,160,338,156,328,147,327,143,333,141,345,130,340,118,330,128,324,120,326,114,313,107,312,96,303,105,294,105,288,114,289,124,281,135,285,159,292,157,299,163,298,170,291,172,293,188,286,195,266,203,270,212,246,210,259,222,261,225,270,223,270,223,269,225,280,227,283,234", dataImg:cogumelo.publicConf.media+"/module/rextAppZona/img/ancaresCourel.svg"},
        { idName: "manzanedaTrevinca", dataCoords: "260,310,270,314,281,309,288,303,297,300,303,305,308,301,318,310,322,307,328,315,339,307,342,304,350,307,352,299,352,277,345,250,329,246,313,248,301,262,294,284,290,272,280,264,272,265,275,274,272,280,264,282,259,300", dataImg:cogumelo.publicConf.media+"/module/rextAppZona/img/manzanedaTrevinca.svg"},
        { idName: "ribeiraSacra", dataCoords: "224,179,235,186,237,192,250,194,246,207,260,226,269,222,270,228,278,227,283,237,279,243,281,245,307,230,312,250,300,262,294,281,286,269,279,264,271,266,272,280,261,285,257,301,254,300,259,308,253,310,247,298,242,294,238,286,230,291,227,284,218,288,221,280,212,275,214,270,202,260,204,251,195,247,199,240,192,237,197,217,200,207,208,205,217,182", dataImg:cogumelo.publicConf.media+"/module/rextAppZona/img/ribeiraSacra.svg"},
        { idName: "verinViana", dataCoords: "223,363,234,349,241,345,237,342,243,338,242,319,253,318,255,312,281,312,284,306,294,301,302,306,307,299,317,310,321,306,330,316,327,322,323,323,319,332,320,336,324,336,327,344,321,356,310,356,304,349,297,349,298,365,292,371,274,377,238,377", dataImg:cogumelo.publicConf.media+"/module/rextAppZona/img/verinViana.svg"},
        { idName: "celanovaAlimia", dataCoords: "160,381,184,370,187,370,195,361,199,375,222,363,238,345,236,342,243,337,242,319,253,320,255,310,250,307,236,305,227,322,214,318,214,323,206,318,194,310,188,296,170,293,167,316,155,317,156,332,166,333,169,341,159,352,152,364", dataImg:cogumelo.publicConf.media+"/module/rextAppZona/img/celanovaAlimia.svg"},
        { idName: "terrasDeOurenseAllariz", dataCoords: "187,276,182,275,177,289,184,297,189,294,197,313,212,320,213,315,226,320,236,305,251,306,246,297,237,285,232,289,226,284,223,288,215,285,222,280,211,276,214,270,203,259,205,252,195,245,190,262,197,269,190,272", dataImg:cogumelo.publicConf.media+"/module/rextAppZona/img/terrasDeOurenseAllariz.svg"},
        { idName: "oRibeiro", dataCoords: "156,237,161,242,177,242,184,236,190,234,199,240,192,251,189,264,196,270,186,278,180,276,177,288,183,295,170,290,166,315,160,316,155,306,158,298,151,300,144,297,146,287,138,279,130,255", dataImg:cogumelo.publicConf.media+"/module/rextAppZona/img/oRibeiro.svg"},
        { idName: "dezaTabeiros", dataCoords: "135,186,132,195,128,195,122,202,115,204,112,201,102,203,94,210,98,217,106,214,110,221,110,234,127,256,139,252,147,242,151,242,156,237,164,242,178,240,182,237,192,236,198,208,192,204,183,198,186,185,175,185,169,182,155,183,146,186,145,189", dataImg:cogumelo.publicConf.media+"/module/rextAppZona/img/dezaTabeiros.svg"},
        { idName: "lugoTerraCha", dataCoords: "191,108,187,120,193,137,190,140,196,167,180,184,186,184,184,199,197,207,208,204,214,183,224,177,235,183,237,191,251,194,246,210,269,212,266,203,292,189,290,174,299,170,297,162,285,160,279,132,290,125,286,115,293,106,292,99,283,97,286,78,276,82,261,79,256,67,241,64,244,53,240,50,233,57,219,48,208,74,199,76,192,96", dataImg:cogumelo.publicConf.media+"/module/rextAppZona/img/lugoTerraCha.svg"},
        { idName: "terrasDeSantiago", dataCoords:"144,129,147,125,151,129,160,131,163,125,171,122,185,120,193,138,189,139,196,168,182,184,174,184,173,182,155,181,144,189,137,185,133,191,127,196,119,203,104,202,98,207,84,202,78,205,73,201,74,187,61,181,54,149,62,134,68,135,70,131,87,136,87,129,94,135,100,122,120,108,125,112,127,119,129,120,132,126,139,124", dataImg:cogumelo.publicConf.media+"/module/rextAppZona/img/terrasDeSantiago.svg"},
        { idName: "murosNoia", dataCoords: "26,199,36,181,41,184,42,175,52,176,53,173,61,174,60,179,74,189,74,204,69,209,54,207,42,229,30,237,36,214", dataImg:cogumelo.publicConf.media+"/module/rextAppZona/img/murosNoia.svg"},
        { idName: "ferrolTerra", dataCoords:"137,45,154,33,179,11,202,2,201,15,228,-1,224,14,218,47,207,76,199,74,197,80,196,88,192,100,178,96,171,88,170,80,156,80,142,68,133,59,135,54", dataImg:cogumelo.publicConf.media+"/module/rextAppZona/img/ferrolTerra.svg"},
        { idName: "aCorunaAsMarinas", dataCoords: "123,81,129,88,133,78,139,74,151,91,151,76,157,81,166,78,177,96,189,100,193,102,185,121,166,124,157,132,149,127,136,122,132,127,125,119,125,111,110,101,107,105,100,101,100,92,108,93", dataImg:cogumelo.publicConf.media+"/module/rextAppZona/img/aCorunaAsMarinas.svg"}
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
      formatState. util method
     */
    that.formatState = function(state) {

      $ret = false;

      if( $(state.element).val() == '*' &&  $(state.element).attr('icon')  !='false' ) {
        $ret = $('<span><img width=24 height=24 src="/' + $(state.element).attr('icon') + '"/></i> ' + state.text + '</span>');
      }
      else
      if ( $(state.element).attr('icon') != 'false') {
        $ret = $('<span><img width=24 height=24 src="'+cogumelo.publicConf.mediaHost+'cgmlImg/' + $(state.element).attr('icon') + '"/></i> ' + state.text + '</span>');
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

/*****************************************************************************************************************************************************/
    that.bindsParticipationStep1 = [];
    that.participationZoom = false;
    that.participationLat = false;
    that.participationLng = false;
    that.participationMarker = false;


    that.showParticipationButton = function( evento, clickCallback ) {
      var buttonDOMId = 'engadirEnMapa';
      var buttonLeft = false;
      var buttonTop = false;

      if( $('#'+buttonDOMId).length == 0 ) {
        $('body').append('<div id="' + buttonDOMId + '">' + __('Suxerir lugar') + '<div>');

      }

      google.maps.event.addListenerOnce( that.mapa.map, 'click' ,function(e) {
        $('#'+buttonDOMId).hide();
      });
      google.maps.event.addListenerOnce( that.mapa.map, 'idle' ,function(e) {
        $('#'+buttonDOMId).hide();
      });


      $('#'+buttonDOMId).css('position', 'absolute');

      $('#'+buttonDOMId).css('top', $( that.explorerclass+' .explorerMap').offset().top + evento.pixel.y );
      $('#'+buttonDOMId).css('left', $( that.explorerclass+' .explorerMap').offset().left + evento.pixel.x );

      $('#'+buttonDOMId).show();
      $('#'+buttonDOMId).one('click', function(){
        clickCallback();
        $('#'+buttonDOMId).hide();
      } );


    }

    that.setParticipation = function(){

      that.bindsParticipation();

      if(geozzy.xantaresParticipationForm.initParticipation){
        geozzy.userSessionInstance.userControlAccess( function(){
          that.initParticipationStep1();
        });
      }


      google.maps.event.addListener( that.mapa.map, 'rightclick' ,function(e) {

        that.showParticipationButton(e, function(){
          geozzy.userSessionInstance.userControlAccess( function(){
            that.initParticipationStep1();
            that.setMarker(e);
          });
        });

      });

    }

    that.bindsParticipation = function(){
      $('#initParticipation').on('click', function(){
        geozzy.userSessionInstance.userControlAccess( function(){
          that.initParticipationStep1();
        });
      });
    }
    that.initParticipationStep1 = function(){
      //Map Events
      var my_marker = {
         url: cogumelo.publicConf.media+'/img/aamarker.png',
         // This marker is 20 pixels wide by 36 pixels high.
         size: new google.maps.Size(40, 50),
         // The origin for this image is (0, 0).
         origin: new google.maps.Point(0, 0),
         // The anchor for this image is the base of the flagpole at (0, 36).
         anchor: new google.maps.Point(20, 50)
       };
       if(!that.participationMarker){
         that.participationMarker = new google.maps.Marker({
           //position: new google.maps.LatLng( latValue, lonValue ),
           title: 'Location',
           icon: my_marker,
           draggable: true
         });
       }else{
         that.participationMarker.setMap(that.mapa.map);
       }
       // Draggend event
       google.maps.event.addListener( that.participationMarker, 'dragend' ,function(e) {
         that.participationLat =  that.participationMarker.position.lat() ;
         that.participationLng =  that.participationMarker.position.lng() ;
       });
       // Click map event

       google.maps.event.addListener(that.mapa.map, 'click', function(e) {
         that.setMarker(e);
       });
       // map zoom changed
       google.maps.event.addListener(that.mapa, 'zoom_changed', function(e) {
         that.participationZoom = that.mapa.map.getZoom();
       });


      //Eventos de botones
      $('.participation-step1').show();
      that.bindsParticipationStep1[that.bindsParticipationStep1.length] = $('.participation-step1 .cancel').on('click.participationButtons', function(){
        that.closeParticipationStep1();
      });
      that.bindsParticipationStep1[that.bindsParticipationStep1.length] = $('.participation-step1 .next').on('click.participationButtons', function(){
        if(!$(this).attr('disabled')){
          that.initParticipationStep2();
        }
      });
    }

    that.setMarker = function( e ) {
      that.participationMarker.setPosition( e.latLng )
      that.participationMarker.setMap( that.mapa.map );
      that.participationLat =  that.participationMarker.position.lat() ;
      that.participationLng =  that.participationMarker.position.lng() ;
      that.participationZoom = that.mapa.map.getZoom();

      $('.participation-step1 .next').prop("disabled", false);
    };

    that.closeParticipationStep1 = function( ){
      //Desactivar mapa y permitir click en mapa
      $('.participation-step1').hide();
      $.each(that.bindsParticipationStep1, function( index, bind ) {
        bind.off();
      });
      google.maps.event.clearListeners(that.mapa.map, 'dragend');
      google.maps.event.clearListeners(that.mapa.map, 'click');
      google.maps.event.clearListeners(that.mapa.map, 'zoom_changed');
      that.participationMarker.setMap(null);

      that.bindsParticipationStep1 = [];
    }
    that.initParticipationStep2 = function(){

      that.closeParticipationStep1();
      $.ajax({
        url:  '/'+cogumelo.publicConf.C_LANG+'/participation/xantaresExplorer',
        method: "POST",
        data: { lat : that.participationLat, lng:that.participationLng, zoom:that.participationZoom },
        success: function(data){
          $('body').append(data);

        }
      });


    }

    that.closeParticipationStep2 = function(){

    }
  }
