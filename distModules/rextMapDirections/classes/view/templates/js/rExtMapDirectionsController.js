var geozzy = geozzy || {};

/*
Movido a TPL
$(document).ready( function() {
  if( typeof geozzy.rExtMapDirectionsData !== 'undefined' ) {
    geozzy.rExtMapDirectionsController.prepareMap( geozzy.rExtMapDirectionsData );
  }


  if( typeof geozzy.rExtMapDirectionsData.wrapperRoute !== 'undefined' ) {
    geozzy.rExtMapDirectionsController.prepareRoutes( geozzy.rExtMapDirectionsData );
  }
});
*/

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


  prepareMap: function prepareMap( directionsData ) {
    console.log( 'prepareMap:', directionsData );
    var that = this;

    this.resourceMapInfo = {
      title: directionsData.title,
      lat: directionsData.lat,
      lng: directionsData.lon,
      zoom: directionsData.zoom,
      wrapper: directionsData.wrapper
    };

    var $mapContainer = $( this.resourceMapInfo.wrapper );
    if( $mapContainer.length === 1 ) {
      console.log( 'prepareMap - OK: ATOPADO O WRAPPER DO MAPA!!!' );
      // gmaps init
      this.resourceMapOptions = {
        center: { lat: this.resourceMapInfo.lat, lng: this.resourceMapInfo.lng },
        zoom: this.resourceMapInfo.zoom,
        scrollwheel: false
      };
      this.resourceMap = new google.maps.Map( $mapContainer.get(0), this.resourceMapOptions );

      // add marker
      this.resourceMarker = new google.maps.Marker({
        map: this.resourceMap,
        position: new google.maps.LatLng( this.resourceMapInfo.lat, this.resourceMapInfo.lon ),
        // title: 'Resource location',
        icon: {
          url: media+'/module/admin/img/geozzy_marker.png', // This marker is 20 pixels wide by 36 pixels high.
          size: new google.maps.Size(30, 36), // The origin for this image is (0, 0).
          origin: new google.maps.Point(0, 0), // The anchor for this image is the base of the flagpole at (0, 36).
          anchor: new google.maps.Point(13, 36)
        },
        draggable: false
      });
    } // if( $mapContainer.length )
    else {
      console.log( 'prepareMap - ERROR: NON ENCONTRO O WRAPPER DO MAPA!!!' );
    }
  },

  resetMap: function resetMap() {
    this.resourceMap.setZoom( this.resourceMapOptions.zoom );
    this.resourceMap.setCenter( this.resourceMapOptions.center );
  },





  prepareRoutes: function prepareRoutes( directionsData ) {
    console.log( 'prepareRoutes', directionsData );
    var that = this;

    // Prepare Form
    this.routePanelContainer = $( directionsData.wrapperRoute );
    if( this.routePanelContainer.length === 1 ) {
      this.routePanelContainer.find('form').on( 'submit', function( evt ) {
        evt.preventDefault();
        destination = $( evt.target ).find('input').val();
        console.log( 'FORM SUBMIT', destination );
        that.resetMap();
        that.clearRoute();
        that.loadRoute( destination, false);
        return false;
      });
      this.routePanelContainer.find('.tabList' ).on( 'click', function togglePanelMap() {
        $( '#comollegarListado' ).toggle();
      } );
      this.routePanelContainer.find( '.routeModeButton' ).on( 'click', function setRouteMode( evt ) {
        that.loadRoute( false, false, $( evt.target ).data( 'route-mode' ) );
      } );

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

      // this.loadRoute( latitude+','+longitude, directionsData.title );

      // click en mapa
      this.mapClickEvent = new google.maps.event.addListener( this.resourceMap, 'click', function(ev){
        inputComollegar = '';
        that.clearRoute();
        that.resetForm();

        that.loadRoute( ev.latLng.lat()+', '+ev.latLng.lng(), false);
      });

      // click en marker evento
      //this.eventClickEvent = new google.maps.event.addListener( eventMarker, 'click', function(ev){
      //  inputComollegar = '';
      //  thisComollegar.loadRoute( ev.latLng.lat()+', '+ev.latLng.lng(), resourceData.name );
      //});

    }
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
    this.routePanelContainer.find( '.routeInfo' ).html('');
  },

  loadRoute: function loadRoute( from, fromTitle, routeMode = false ) {
    console.log( 'loadRoute:', from, fromTitle );
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
      console.log( 'traceRoute thenFunction:', that.resourceLastroute.routes[0] );

      that.routeFrom.dir = that.resourceLastroute.routes[0].legs[0].startAddress;
      that.routeTo.dir = that.resourceLastroute.routes[0].legs[0].endAddress;
      if( fromTitle ) {
        that.routeFrom.title = fromTitle;
      }
      that.routeTo.time = that.resourceLastroute.routes[0].legs[0].duration.value;
      that.routeTo.distance = that.resourceLastroute.routes[0].legs[0].distance.value;

      that.directionsDisplay.setMap( that.resourceMap );
      that.directionsDisplay.setPanel( document.getElementById( 'comollegarListado' ) );

      that.clearRoute();

      // dibuja ruta
      that.directionsDisplay.setDirections( that.resourceLastroute );
      that.tramoExtraIni = that.tramoExtra( that.routeFrom.latlng, that.resourceLastroute.routes[0].legs[0].start_location, false );
      that.tramoExtraFin = that.tramoExtra( that.routeTo.latlng, that.resourceLastroute.routes[0].legs[0].end_location, true );

      that.routePanelContainer.find('.tabList' ).show();
      that.routePanelContainer.find('.routeMode' ).show();

      travelInfo = {
        mode: that.resourceLastroute.request.travelMode,
        meters: that.resourceLastroute.routes[0].legs[0].distance.value,
        seconds: that.resourceLastroute.routes[0].legs[0].duration.value
      };
      that.setRoutePanelInfo( travelInfo );

      // desbloquea boton de cerrar
      that.blockCloseButton = false;

    });
  },

  clearRoute: function clearRoute() {
    console.log( 'clearRoute:' );
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
    console.log( 'setRoutePanelInfo:', travelInfo );
    if( travelInfo !== false ) {
      var sec = travelInfo.seconds;
      var s = sec % 60;
      sec = ( sec - s ) / 60;
      var m = sec % 60;
      var h = ( sec - m ) / 60;
      s = (s<10) ? '0'+s : s;
      m = (m<10) ? '0'+m : m;
      var timeStr = (h === 0) ? m+' min.' : h+' h. '+m+' min.';

      var km = Math.round( travelInfo.meters / 100 ) / 10;

      var modeNum = 0;
      switch( travelInfo.mode ) {
        case 'DRIVING':
          modeNum = 0;
          break;
        case 'WALKING':
          modeNum = 1;
          break;
        case 'TRANSIT':
          modeNum = 2;
          break;
        case 'BICYCLING':
          modeNum = 3;
          break;
      }
      this.routePanelContainer.find( '.routeInfo' ).html( timeStr +' ('+km+' Km)' );
    }
    else {
      this.routePanelContainer.find( '.routeInfo' ).html( '' );
    }

    this.routePanelContainer.find( '.routeModeButton' ).removeClass( 'active' );
    this.routePanelContainer.find( '.routeMode [data-route-mode="'+modeNum+'"]').addClass( 'active' );
  },

  // traceRoute(0, from, this.to.latlng, this.transport , false, function(){
  traceRoute: function traceRoute( id, from, to, mode, cache, thenFunction ) {
    console.log( 'traceRoute:', id, from, to, mode, cache, 'thenFunction' );
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
          console.log( 'DirectionsStatus.OK', result, status );

          that.resourceLastroute = result;
          if( cache ) {
            that.resourceRoutes[ mode ][ id ] = result.routes[0];
          }
          thenFunction();
        }
        else {
          console.debug( '<p>Desconocido</p>' );
          that.resourceLastroute = false;
          if( cache ) {
            that.resourceRoutes[ mode ][ id ] = false;
          }
        }
      });
    }
    else{
      thenFunction();
    }
  },

  tramoExtra: function tramoExtra( solicitada, resultado, reves ) {
    console.log( 'tramoExtra:', solicitada, resultado, reves );
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











  eventHoverStart: function( id, section ) {
    var that = this;

    that.hoverStack.push({
      start: that.getTimesTamp(),
      resourceId: id,
      section: section,
      event: 'hover'
    });
  },

  eventPrint: function(id, section) {
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
