var geozzy = geozzy || {};


geozzy.rExtMapDirectionsController = {
  directionsDisplay: false,
  directionsService: false,

  resourceMap: false,

  resourceMarker: false,
  resourceMapInfo: false,
  resourceMapOptions: false,

  routePanelContainer:false,

  resourceRoutes: [],
  resourceLastroute: [],

  markerFrom: false,
  routeFrom: {
    title: '',
    dir: false,
    latlng: false
  },
  routeTo: {
    title: '',
    dir: false,
    latlng : false,
    distance: 0,
    time: 0
  },

  opened: false,
  transport: 0,

  // mapClickEvent: false,
  eventClickEvent: false,
  blockCloseButton: false,

  tramoExtraIni: false,
  tramoExtraFin: false,


  jqDirForm: false,
  jqDirInput: false,

  jqDirInMap: false,
  jqDirRouteModes: false,
  jqDirRouteList: false,


  prepareRoutes: function prepareRoutes( ) {

    geozzy.rExtMapInstance.onLoad( function() {
      var that = this;

      if( geozzy.rExtMapDirectionsData ) {
        that.resourceMapInfo = geozzy.rExtMapDirectionsData;
      }

      // geozzy.rExtMapInstance.initialize();
      that.resourceMap = geozzy.rExtMapInstance.resourceMap; // Necesario despues de initialize()

      that.jqDirForm = $('.jsMapDirectionsForm');
      that.jqDirInput = that.jqDirForm.find( 'input[name=mapRouteOrigin]' );

      if( that.jqDirForm && that.jqDirForm.length === 1 ) {
        // Input de direcciones
        that.jqDirForm.on( 'submit', function( evt ) {
          evt.preventDefault();
          that.resetMap();
          that.clearRoute();
          var destination = $( evt.target ).find('input').val();
          that.loadRoute( destination, false, false );
          return false;
        });

        that.autocomplete = new google.maps.places.Autocomplete( that.jqDirInput.get(0) );
        that.autocomplete.bindTo( 'bounds', that.resourceMap );
        that.autocomplete.addListener( 'place_changed', function() {
          var place = that.autocomplete.getPlace();
          var destination = '';
          if( place.geometry && place.geometry.location ) {
            destination = place.geometry.location.lat()+', '+place.geometry.location.lng();
          }
          else {
            destination = that.jqDirInput.val();
          }
          that.loadRoute( destination, false, false );
        });

        // Prepare Routes controller
        that.directionsDisplay = new google.maps.DirectionsRenderer( { suppressMarkers: true } );
        that.directionsService = new google.maps.DirectionsService();

        // that.opened = true;
        // that.blockCloseButton = true;

        that.resourceRoutes[0] = [];
        that.resourceRoutes[1] = [];
        that.resourceRoutes[2] = [];
        that.resourceRoutes[3] = [];

        that.resetFromTo();
      }
    });
  },

  prepareContainers: function prepareContainers() {
    // cogumelo.log( 'prepareContainers' );
    var that = this;

    if( $('.jsMapDirectionsList').length === 0 ) {
      // No hay estructura creada
      if( typeof geozzy.rExtMapDirectionsData.html.jsMapDirectionsInMap === 'string' ) {

        $jsMapDirectionsInMap = $( geozzy.rExtMapDirectionsData.html.jsMapDirectionsInMap );

        $resMapWrapper = $( geozzy.rExtMapInstance.options.wrapper ).parent('.resMapWrapper');
        that.resMapWrapperPrevCss = {
          'float': $resMapWrapper.css('float'),
          'width': $resMapWrapper.css('width')
        };
        $resMapWrapper.css({
          'float': 'right',
          'width': '60%'
        });

        var hMap = $resMapWrapper.css('height');
        $jsMapDirectionsInMap.css({
          // 'background-color': 'green',
          'float': 'right',
          'width': '40%',
          'height': hMap
        });
        $jsMapDirectionsInMap.insertAfter( $resMapWrapper );
        // $resMapWrapper.after( geozzy.rExtMapDirectionsData.html.jsMapDirectionsInMap );

        var hList = $jsMapDirectionsInMap.height();
        // cogumelo.log('hList',hList);
        $('.jsMapDirInMapBar').each( function() {
          hList -= $( this ).outerHeight(true);
        });
        // cogumelo.log('hList',hList);
        $('.jsMapDirectionsList').css( 'height', hList+'px' );
      }
    }

    that.jqDirForm = $('.jsMapDirectionsForm');
    that.jqDirInput = that.jqDirForm.find( 'input[name=mapRouteOrigin]' );

    that.jqDirInMap = $('.jsMapDirectionsInMap');
    that.jqDirRouteModes = $('.jsMapDirectionsMode');
    that.jqDirRouteList = $('.jsMapDirectionsList');

    that.jqDirRouteList.html('');

    that.manipulateContainers();

    // Botones de modo de ruta
    if( that.jqDirRouteModes ) {
      that.jqDirRouteModes.find( '.routeModeButton' ).off().on( 'click', function setRouteMode( evt ) {
        that.loadRoute( false, false, $( evt.target ).data( 'route-mode' ) );
      } );
    }

    // Boton cerrar
    $('.jsMapDirInMapClose .button').off().on( 'click', function botonClose() {
      that.destroyContainers();
    });

    that.resetMap();
  },

  manipulateContainers: function manipulateContainers() {
    // cogumelo.log( 'manipulateContainers' );
  },

  destroyContainers: function destroyContainers() {
    var that = this;

    that.clearRoute();
    that.resetFromTo();
    that.resetForm();

    that.jqDirInMap.remove();

    $resMapWrapper = $( geozzy.rExtMapInstance.options.wrapper ).parent('.resMapWrapper');
    $.each( that.resMapWrapperPrevCss, function( cssName, cssValue ) {
      $resMapWrapper.css( cssName, cssValue );
    });

    that.resetMap();
    setTimeout( function resetMapRep() { that.resetMap(); }, 500 );
  },


  resetMap: function resetMap() {
    // cogumelo.log( 'resetMap:' );
    var that = this;

    that.resourceMap.setZoom( that.resourceMapInfo.zoom );
    that.resourceMap.setCenter({
      lat: that.resourceMapInfo.lat,
      lng: that.resourceMapInfo.lng
    });

    google.maps.event.trigger( that.resourceMap, 'resize' );
  },

  resetForm: function resetForm() {
    // cogumelo.log( 'resetForm:' );
    that.jqDirInput.val('');
  },

  resetFromTo: function resetFromTo() {
    // cogumelo.log( 'resetFromTo:' );
    that.routeFrom = {
      title: '',
      dir: false,
      latlng: false
    };
    that.routeTo = {
      title: '',
      dir: false,
      latlng : false,
      distance: 0,
      time: 0
    };
    that.transport = 0;
    that.setMarkerFrom( null );

    if( that.jqDirRouteModes ) {
      that.jqDirRouteModes.find('.routeInfo').html('');
    }

    that.routeTo.latlng = that.resourceMapInfo.lat + ',' + that.resourceMapInfo.lng;
    if( typeof that.resourceMapInfo.title !== 'undefined' && that.resourceMapInfo.title !== '' ) {
      that.routeTo.title = that.resourceMapInfo.title;
    }
  },

  setMarkerFrom: function setMarkerFrom( latLng ) {
    // cogumelo.log( 'setMarkerFrom:' );
    if( that.markerFrom ) {
      if( latLng === false || latLng === null ) {
        that.markerFrom.setMap( null );
      }
      else {
        that.markerFrom.setPosition( new google.maps.LatLng( latLng.lat(), latLng.lng() ) );
        that.markerFrom.setMap( that.resourceMap );
      }
    }
    else {
      if( latLng !== false && latLng !== null ) {
        // add marker
        that.markerFrom = new google.maps.Marker({
          map: that.resourceMap,
          position: new google.maps.LatLng( latLng.lat(), latLng.lng() ),
          title: 'Origin location',
          icon: {
            url: cogumelo.publicConf.media+'/module/admin/img/geozzy_marker.png', // This marker is 20 pixels wide by 36 pixels high.
            size: new google.maps.Size(30, 36), // The origin for this image is (0, 0).
            origin: new google.maps.Point(0, 0), // The anchor for this image is the base of the flagpole at (0, 36).
            anchor: new google.maps.Point(13, 36)
          },
          draggable: false
        });
      }
    }
  },

  loadRoute: function loadRoute( from, fromTitle, routeMode ) {
    // cogumelo.log( 'loadRoute:', that.resourceMap );

    var that = this;

    if( from ) {
      that.routeFrom.latlng = from;
    }
    if( fromTitle ) {
      that.routeFrom.title = fromTitle;
    }
    if( routeMode !== false ) {
      that.transport = routeMode;
    }

    if( that.routeFrom.latlng && that.routeFrom.latlng !== '' ) {
      that.prepareContainers(); // jqDirForm, jqDirInput, jqDirRouteModes, jqDirRouteList

      that.resetMap();

      that.traceRoute( 0, that.routeFrom.latlng, that.routeTo.latlng, that.transport , false, function() {
        // cogumelo.log( 'traceRoute thenFunction:', that.resourceLastroute );

        travelInfo = false;
        that.clearRoute();
        if( that.resourceLastroute ) {
          that.routeFrom.dir = that.resourceLastroute.routes[0].legs[0].start_address;
          that.routeTo.dir = that.resourceLastroute.routes[0].legs[0].end_address;
          that.routeTo.time = that.resourceLastroute.routes[0].legs[0].duration.value;
          that.routeTo.distance = that.resourceLastroute.routes[0].legs[0].distance.value;

          that.setMarkerFrom( that.resourceLastroute.routes[0].legs[0].start_location );

          that.directionsDisplay.setMap( that.resourceMap );
          that.directionsDisplay.setPanel( that.jqDirRouteList.get(0) );

          // dibuja ruta
          that.directionsDisplay.setDirections( that.resourceLastroute );
          that.tramoExtraIni = that.tramoExtra( that.routeFrom.latlng, that.resourceLastroute.routes[0].legs[0].start_location, false );
          that.tramoExtraFin = that.tramoExtra( that.routeTo.latlng, that.resourceLastroute.routes[0].legs[0].end_location, true );

          travelInfo = {
            mode: that.resourceLastroute.request.travelMode,
            meters: that.resourceLastroute.routes[0].legs[0].distance.value,
            seconds: that.resourceLastroute.routes[0].legs[0].duration.value
          };

          var $printLink = $( '.jsMapDirInMapPrint a' );

          var hrefDirection = '/directions/print';
          hrefDirection += '/title/' + encodeURI( geozzy.rExtMapDirectionsData.title );
          hrefDirection += '/origin/' + encodeURI( that.routeFrom.latlng );
          hrefDirection += '/destination/' + encodeURI( that.routeTo.latlng );
          hrefDirection += '/travelMode/' + encodeURI( that.resourceLastroute.request.travelMode );

          $printLink.attr( 'href', hrefDirection );
        }
        else {
          travelInfo = __('A route could not be found.');
        }

        // that.jqDirRouteModes.show();
        // that.jqDirRouteList.show();
        // that.routePanelContainer.find('.tabList' ).show();

        that.scrollTopWrapper( that.jqDirInMap );

        that.setRoutePanelInfo( travelInfo );

        // desbloquea boton de cerrar
        // that.blockCloseButton = false;

      });
    }
  },

  redirectGMaps: function redirectGMaps( directionsData ) {
    var that = this;

    if( directionsData ) {
      that.resourceMapInfo = directionsData;
    }
    // cogumelo.log( 'redirectGMaps:', that.resourceMapInfo );

    window.location.href = 'https://www.google.es/maps/dir//'+that.resourceMapInfo.lat+','+that.resourceMapInfo.lng;
  },

  scrollTopWrapper: function scrollTopWrapper( $elem ) {
    var scrollTo = $elem.position().top;
    // cogumelo.log( 'scrollTopWrapper: ', $elem, scrollTo );

    $( 'html, body' ).animate( {
      scrollTop: $elem.position().top - geozzy.rExtMapDirectionsData.scrollTopMargin
    }, 1000 );
  },

  clearRoute: function clearRoute() {
    // cogumelo.log( 'clearRoute:' );
    // borra ruta del mapa
    if( that.directionsDisplay ) {
      that.directionsDisplay.setDirections( {routes: []} );
    }
    if( that.tramoExtraIni ) {
      that.tramoExtraIni.setMap( null );
      that.tramoExtraIni = false;
    }
    if( that.tramoExtraFin ) {
      that.tramoExtraFin.setMap( null );
      that.tramoExtraFin = false;
    }
    that.setRoutePanelInfo( false );
  },

  setRoutePanelInfo: function setRoutePanelInfo( travelInfo ) {
    // cogumelo.log( 'setRoutePanelInfo:', typeof( travelInfo ) );
    var htmlMsg = '';
    //var modeNum = 0;

    if( travelInfo !== false ) {
      if( typeof( travelInfo ) === 'string' ) {
        htmlMsg = travelInfo;
      }
      else {
        var sec = travelInfo.seconds;
        var s = sec % 60;
        sec = ( sec - s ) / 60;
        var m = sec % 60;
        var h = ( sec - m ) / 60;
        s = (s<10) ? '0'+s : s;
        m = (m<10) ? '0'+m : m;
        var timeStr = h+':'+m;

        var km = Math.round( travelInfo.meters / 100 ) / 10;
        // htmlMsg = __('Distance')+': '+km+' Km '+__('Time')+': '+ timeStr;
        htmlMsg = '<i class="fas fa-map-marker-alt" aria-hidden="true"></i> '+km+' Km &nbsp; &nbsp; <i class="far fa-clock" aria-hidden="true"></i> '+ timeStr+'h';
      }
    }

    if( that.jqDirRouteModes ) {
      that.jqDirRouteModes.find( '.routeInfo' ).html( htmlMsg );
      that.jqDirRouteModes.find( '.routeModeButton' ).removeClass( 'active' );
      that.jqDirRouteModes.find( '.routeModeButton[data-route-mode="' + that.transport + '"]').addClass( 'active' );
    }
  },

  // traceRoute(0, from, that.to.latlng, that.transport , false, function(){
  traceRoute: function traceRoute( id, from, to, mode, cache, thenFunction ) {
    // cogumelo.log( 'traceRoute:', id, from, to, mode, cache, 'thenFunction' );
    var that = this;

    var modeString = 'DRIVING';
    switch( mode ) {
      case 0:
        modeString = 'DRIVING';
        break;
      case 1:
        modeString = 'WALKING';
        break;
      case 2:
        modeString = 'TRANSIT';
        break;
      case 3:
        modeString = 'BICYCLING';
        break;
    }

    var route = {
      origin: from,
      destination: to,
      travelMode: google.maps.TravelMode[ modeString ]
    };


    if( typeof that.resourceRoutes[ mode ][ id ] === 'undefined' || !cache ) {
      that.directionsService.route( route, function( result, status ) {

        if( status === google.maps.DirectionsStatus.OK ) {
          // console.debug( '<p>'+ route.travelMode +' Metros: '+result.routes[0].legs[0].distance.value+' Segundos: '+result.routes[0].legs[0].duration.value+'</p>' );
          // directionsDisplay.setDirections(result);
          // cogumelo.log( 'DirectionsStatus.OK', result, status );

          that.resourceLastroute = result;
          if( cache ) {
            that.resourceRoutes[ mode ][ id ] = result.routes[0];
          }
          thenFunction();
        }
        else {
          // cogumelo.log( 'DirectionsStatus.FAIL', result, status );
          that.resourceLastroute = false;
          if( cache ) {
            that.resourceRoutes[ mode ][ id ] = false;
          }
          thenFunction();
        }
      });
    }
    else{
      thenFunction();
    }
  },

  tramoExtra: function tramoExtra( solicitada, resultado, reves ) {
    // cogumelo.log( 'tramoExtra:', solicitada, resultado, reves );
    var tramo = false;
    var latLng = solicitada.split(',');
    var diferencia = Math.abs(latLng['0'] - resultado.lat()) + Math.abs(latLng['1'] - resultado.lng());
    var fromTo = false;

    if ( !isNaN(diferencia) && diferencia>0.0001 ) {
      if( reves ) {
        fromTo = [ new google.maps.LatLng( latLng['0'], latLng['1'] ), new google.maps.LatLng( resultado.lat(), resultado.lng() ) ];
      }
      else {
        fromTo = [ new google.maps.LatLng( resultado.lat(), resultado.lng() ), new google.maps.LatLng( latLng['0'], latLng['1'] ) ];
      }


      tramo = new google.maps.Polyline({
        path: fromTo,
        strokeOpacity: 0,
        icons: [{
          //icon: { path:'M 0,0 L 1,0 L 1,1 L 0,1 z', strokeOpacity:0.3, scale:3 },
          icon:{ path:'M 1,0 0,2 -1,0 z', fillColor:'#66F', strokeColor:'#66F', strokeOpacity:0.7, scale:2 },
          offset: '0',
          repeat: '8px'
        }],
        map: that.resourceMap
      });
    }

    return tramo;
  },



  eventHoverStart: function eventHoverStart( id, section ) {
    var that = this;

    that.hoverStack.push({
      start: that.getTimesTamp(),
      resourceId: id,
      section: section,
      event: 'hover'
    });
  },

  eventPrint: function eventPrint(id, section) {
    var that = this;

    if( $.inArray(id , that.printedResources)  == -1) {
      that.printedResources.push( id );
      that.addMetric({
        resourceId: id,
        section: section,
        event: 'printed'
      });
    }
  },

  last: true
};
