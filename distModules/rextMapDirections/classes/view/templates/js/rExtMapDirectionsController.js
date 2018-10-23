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


  prepareRoutes: function prepareRoutes( directionsData ) {
    // console.log( 'prepareRoutes', directionsData );
    var that = this;

    if( directionsData ) {
      that.resourceMapInfo = directionsData;
    }

    // geozzy.rExtMapInstance.initialize();
    that.resourceMap = geozzy.rExtMapInstance.resourceMap; // Necesario despues de initialize()

    this.jqDirForm = $('.jsMapDirectionsForm');
    this.jqDirInput = this.jqDirForm.find( 'input[name=mapRouteOrigin]' );

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
  },

  prepareContainers: function prepareContainers() {
    // console.log( 'prepareContainers' );
    var that = this;

    if( $('.jsMapDirectionsList').length === 0 ) {
      // No hay estructura creada
      if( typeof geozzy.rExtMapDirectionsData.html.jsMapDirectionsInMap === 'string' ) {

        $jsMapDirectionsInMap = $( geozzy.rExtMapDirectionsData.html.jsMapDirectionsInMap );

        $resMapWrapper = $( geozzy.rExtMapInstance.options.wrapper ).parent('.resMapWrapper');
        this.resMapWrapperPrevCss = {
          'float': $resMapWrapper.css('float'),
          'width': $resMapWrapper.css('width')
        }
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
        $jsMapDirectionsInMap.insertAfter( $resMapWrapper )
        // $resMapWrapper.after( geozzy.rExtMapDirectionsData.html.jsMapDirectionsInMap );

        var hList = $jsMapDirectionsInMap.height();
        // console.log('hList',hList);
        $('.jsMapDirInMapBar').each( function() {
          hList -= $( this ).outerHeight(true);
        });
        // console.log('hList',hList);
        $('.jsMapDirectionsList').css( 'height', hList+'px' );
      }
    }

    this.jqDirForm = $('.jsMapDirectionsForm');
    this.jqDirInput = this.jqDirForm.find( 'input[name=mapRouteOrigin]' );

    this.jqDirInMap = $('.jsMapDirectionsInMap');
    this.jqDirRouteModes = $('.jsMapDirectionsMode');
    this.jqDirRouteList = $('.jsMapDirectionsList');

    this.jqDirRouteList.html('');

    this.manipulateContainers();

    // Botones de modo de ruta
    if( this.jqDirRouteModes ) {
      this.jqDirRouteModes.find( '.routeModeButton' ).off().on( 'click', function setRouteMode( evt ) {
        that.loadRoute( false, false, $( evt.target ).data( 'route-mode' ) );
      } );
    }

    // Boton cerrar
    $('.jsMapDirInMapClose .button').off().on( 'click', function botonClose() {
      that.destroyContainers();
    });

    this.resetMap();
  },

  manipulateContainers: function manipulateContainers() {
    // console.log( 'manipulateContainers' );
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
    // console.log( 'resetMap:' );
    var that = this;

    this.resourceMap.setZoom( this.resourceMapInfo.zoom );
    this.resourceMap.setCenter({
      lat: this.resourceMapInfo.lat,
      lng: this.resourceMapInfo.lng
    });

    google.maps.event.trigger( that.resourceMap, 'resize' );
  },

  resetForm: function resetForm() {
    // console.log( 'resetForm:' );
    this.jqDirInput.val('');
  },

  resetFromTo: function resetFromTo() {
    // console.log( 'resetFromTo:' );
    this.routeFrom = {
      title: '',
      dir: false,
      latlng: false
    };
    this.routeTo = {
      title: '',
      dir: false,
      latlng : false,
      distance: 0,
      time: 0
    };
    this.transport = 0;
    this.setMarkerFrom( null );

    if( this.jqDirRouteModes ) {
      this.jqDirRouteModes.find('.routeInfo').html('');
    }

    this.routeTo.latlng = this.resourceMapInfo.lat + ',' + this.resourceMapInfo.lng;
    if( typeof this.resourceMapInfo.title !== 'undefined' && this.resourceMapInfo.title !== '' ) {
      this.routeTo.title = this.resourceMapInfo.title;
    }
  },

  setMarkerFrom: function setMarkerFrom( latLng ) {
    // console.log( 'setMarkerFrom:' );
    if( this.markerFrom ) {
      if( latLng === false || latLng === null ) {
        this.markerFrom.setMap( null );
      }
      else {
        this.markerFrom.setPosition( new google.maps.LatLng( latLng.lat(), latLng.lng() ) );
        this.markerFrom.setMap( this.resourceMap );
      }
    }
    else {
      if( latLng !== false && latLng !== null ) {
        // add marker
        this.markerFrom = new google.maps.Marker({
          map: this.resourceMap,
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
    // console.log( 'loadRoute:', this.resourceMap );

    var that = this;

    if( from ) {
      this.routeFrom.latlng = from;
    }
    if( fromTitle ) {
      this.routeFrom.title = fromTitle;
    }
    if( routeMode !== false ) {
      this.transport = routeMode;
    }

    if( this.routeFrom.latlng && this.routeFrom.latlng !== '' ) {
      that.prepareContainers(); // jqDirForm, jqDirInput, jqDirRouteModes, jqDirRouteList

      this.resetMap();

      this.traceRoute( 0, this.routeFrom.latlng, this.routeTo.latlng, this.transport , false, function() {
        // console.log( 'traceRoute thenFunction:', that.resourceLastroute );

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
    // console.log( 'redirectGMaps:', that.resourceMapInfo );

    window.location.href = 'https://www.google.es/maps/dir//'+that.resourceMapInfo.lat+','+that.resourceMapInfo.lng;
  },

  scrollTopWrapper: function scrollTopWrapper( $elem ) {
    var scrollTo = $elem.position().top;
    // console.log( 'scrollTopWrapper: ', $elem, scrollTo );

    $( 'html, body' ).animate( {
      scrollTop: $elem.position().top - geozzy.rExtMapDirectionsData.scrollTopMargin
    }, 1000 );
  },

  clearRoute: function clearRoute() {
    // console.log( 'clearRoute:' );
    // borra ruta del mapa
    if( this.directionsDisplay ) {
      this.directionsDisplay.setDirections( {routes: []} );
    }
    if( this.tramoExtraIni ) {
      this.tramoExtraIni.setMap( null );
      this.tramoExtraIni = false;
    }
    if( this.tramoExtraFin ) {
      this.tramoExtraFin.setMap( null );
      this.tramoExtraFin = false;
    }
    this.setRoutePanelInfo( false );
  },

  setRoutePanelInfo: function setRoutePanelInfo( travelInfo ) {
    // console.log( 'setRoutePanelInfo:', typeof( travelInfo ) );
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

    if( this.jqDirRouteModes ) {
      this.jqDirRouteModes.find( '.routeInfo' ).html( htmlMsg );
      this.jqDirRouteModes.find( '.routeModeButton' ).removeClass( 'active' );
      this.jqDirRouteModes.find( '.routeModeButton[data-route-mode="' + this.transport + '"]').addClass( 'active' );
    }
  },

  // traceRoute(0, from, this.to.latlng, this.transport , false, function(){
  traceRoute: function traceRoute( id, from, to, mode, cache, thenFunction ) {
    // console.log( 'traceRoute:', id, from, to, mode, cache, 'thenFunction' );
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


    if( typeof this.resourceRoutes[ mode ][ id ] === 'undefined' || !cache ) {
      this.directionsService.route( route, function( result, status ) {

        if( status === google.maps.DirectionsStatus.OK ) {
          // console.debug( '<p>'+ route.travelMode +' Metros: '+result.routes[0].legs[0].distance.value+' Segundos: '+result.routes[0].legs[0].duration.value+'</p>' );
          // directionsDisplay.setDirections(result);
          // console.log( 'DirectionsStatus.OK', result, status );

          that.resourceLastroute = result;
          if( cache ) {
            that.resourceRoutes[ mode ][ id ] = result.routes[0];
          }
          thenFunction();
        }
        else {
          // console.log( 'DirectionsStatus.FAIL', result, status );
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
    // console.log( 'tramoExtra:', solicitada, resultado, reves );
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
        map: this.resourceMap
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
