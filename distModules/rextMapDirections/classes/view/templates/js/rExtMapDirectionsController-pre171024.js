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

  mapClickEvent: false,
  eventClickEvent: false,
  blockCloseButton: false,

  tramoExtraIni: false,
  tramoExtraFin: false,



  prepareRoutes: function prepareRoutes( directionsData ) {
    // cogumelo.log( 'prepareRoutes', directionsData );
    var that = this;


    that.resourceMap = geozzy.rExtMapInstance.resourceMap;


    this.resourceMapInfo = directionsData;


    // Prepare Form
    this.routePanelContainer = $( directionsData.wrapperRoute );
    if( this.routePanelContainer.length === 1 ) {

      // Input de direcciones
      this.routePanelContainer.find( 'form' ).on( 'submit', function( evt ) {
        evt.preventDefault();
        that.resetMap();
        that.clearRoute();
        var destination = $( evt.target ).find('input').val();
        that.loadRoute( destination, false, false );
        return false;
      });

      this.autocomplete = new google.maps.places.Autocomplete( that.routePanelContainer.find( 'input[name=mapRouteOrigin]' ).get(0) );
      this.autocomplete.bindTo( 'bounds', this.resourceMap );
      this.autocomplete.addListener( 'place_changed', function() {
        var place = that.autocomplete.getPlace();
        var destination = '';
        if( place.geometry && place.geometry.location ) {
          destination = place.geometry.location.lat()+', '+place.geometry.location.lng();
        }
        else {
          destination = that.routePanelContainer.find( 'input[name=mapRouteOrigin]' ).val();
        }
        that.loadRoute( destination, false, false );
      });

      // Panel de listado de rutas
      this.routePanelContainer.find('.tabList' ).on( 'click', function togglePanelMap() {
        $( '#comollegarListado' ).toggle();
      } );

      // Botones de modo de ruta
      this.routePanelContainer.find( '.routeModeButton' ).on( 'click', function setRouteMode( evt ) {
        that.loadRoute( false, false, $( evt.target ).data( 'route-mode' ) );
      } );

      // Click en mapa
      // this.mapClickEvent = new google.maps.event.addListener( this.resourceMap, 'click', function(ev){
      //   inputComollegar = '';
      //   that.clearRoute();
      //   that.resetForm();
      //   that.setMarkerFrom( ev.latLng );
      //
      //   that.loadRoute( ev.latLng.lat()+', '+ev.latLng.lng(), false, false );
      // });



      // Prepare Routes controller
      this.directionsDisplay = new google.maps.DirectionsRenderer( { suppressMarkers: true } );
      this.directionsService = new google.maps.DirectionsService();

      this.opened = true;
      this.blockCloseButton = true;

      this.resourceRoutes[0] = [];
      this.resourceRoutes[1] = [];
      this.resourceRoutes[2] = [];
      this.resourceRoutes[3] = [];

      this.resetFromTo();
      $('#comollegarListado').html('');
      var inputComollegar = '';


      this.routeTo.latlng = this.resourceMapInfo.lat + ',' + this.resourceMapInfo.lng;
      if( typeof directionsData.title !== 'undefined' && directionsData.title !== '' ) {
        this.routeTo.title = directionsData.title;
      }

      // this.loadRoute( latitude+','+longitude, directionsData.title, false );

      // click en marker evento
      //this.eventClickEvent = new google.maps.event.addListener( eventMarker, 'click', function(ev){
      //  inputComollegar = '';
      //  thisComollegar.loadRoute( ev.latLng.lat()+', '+ev.latLng.lng(), resourceData.name, false );
      //});

    }
  },

  resetMap: function resetMap() {
    this.resourceMap.setZoom( this.resourceMapOptions.zoom );
    this.resourceMap.setCenter( this.resourceMapOptions.center );
  },

  resetForm: function resetForm() {
    this.routePanelContainer.find('input').val('');
  },

  resetFromTo: function resetFromTo() {
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
    this.routePanelContainer.find( '.routeInfo' ).html('');
  },

  setMarkerFrom: function setMarkerFrom( latLng ) {
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

    this.traceRoute( 0, this.routeFrom.latlng, this.routeTo.latlng, this.transport , false, function() {
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
        that.directionsDisplay.setPanel( that.routePanelContainer.find( '#comollegarListado' ).get(0) );

        // dibuja ruta
        that.directionsDisplay.setDirections( that.resourceLastroute );
        that.tramoExtraIni = that.tramoExtra( that.routeFrom.latlng, that.resourceLastroute.routes[0].legs[0].start_location, false );
        that.tramoExtraFin = that.tramoExtra( that.routeTo.latlng, that.resourceLastroute.routes[0].legs[0].end_location, true );

        travelInfo = {
          mode: that.resourceLastroute.request.travelMode,
          meters: that.resourceLastroute.routes[0].legs[0].distance.value,
          seconds: that.resourceLastroute.routes[0].legs[0].duration.value
        };
      }
      else {
        travelInfo = 'No se ha podido localizar una ruta.';
      }

      that.routePanelContainer.show();
      that.routePanelContainer.find('.tabList' ).show();
      that.routePanelContainer.find('.routeMode' ).show();

      that.scrollTopWrapper( that.routePanelContainer );

      that.setRoutePanelInfo( travelInfo );

      // desbloquea boton de cerrar
      that.blockCloseButton = false;

    });
  },

  scrollTopWrapper: function scrollTopWrapper( $elem ) {
    var scrollTo = $elem.position().top;
    cogumelo.log( 'scrollTopWrapper: ', $elem, scrollTo );

    $( 'html, body' ).animate( {
      scrollTop: $elem.position().top - geozzy.rExtMapDirectionsData.scrollTopMargin
    }, 1000 );
  },

  clearRoute: function clearRoute() {
    // cogumelo.log( 'clearRoute:' );
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
    this.routePanelContainer.find( '.tabList' ).hide();
    this.routePanelContainer.find( '.routeMode' ).hide();
    this.routePanelContainer.find( '#comollegarListado' ).hide();
    this.setRoutePanelInfo( false );
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
        htmlMsg = 'Distance: '+km+' Km Time: '+ timeStr;
      }
    }

    this.routePanelContainer.find( '.routeInfo' ).html( htmlMsg );
    this.routePanelContainer.find( '.routeModeButton' ).removeClass( 'active' );
    this.routePanelContainer.find( '.routeMode [data-route-mode="' + this.transport + '"]').addClass( 'active' );
  },

  // traceRoute(0, from, this.to.latlng, this.transport , false, function(){
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


    if( typeof this.resourceRoutes[ mode ][ id ] === 'undefined' || !cache ) {
      this.directionsService.route( route, function( result, status ) {

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
