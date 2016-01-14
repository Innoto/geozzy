var geozzy = geozzy || {};


geozzy.resourceMapController = {
  resourceData: {
    name: 'Nome do recurso',
    city: 'A Coruña'
  },

  directionsDisplay: false,
  directionsService: false,

  resourceMap: false,
  resourceMarker: false,
  resourceMapInfo: false,
  resourceMapOptions: false,

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


  prepareMap: function prepareMap( lat, lon, zoom, wrapper ) {
    console.log( 'loadMap', lat, lon, zoom, wrapper );
    var that = this;

    this.resourceMapInfo = {
      lat: lat,
      lng: lon,
      zoom: zoom,
      wrapper: wrapper
    };

    var $mapContainer = $(wrapper);
    if( $mapContainer.length === 1 ) {
      // gmaps init
      this.resourceMapOptions = {
        center: { lat: lat, lng: lon },
        zoom: zoom,
        scrollwheel: false
      };
      this.resourceMap = new google.maps.Map( $mapContainer.get(0), this.resourceMapOptions );

      // add marker
      that.resourceMarker = new google.maps.Marker({
        map: this.resourceMap,
        position: new google.maps.LatLng( lat, lon ),
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
  },

  resetMap: function resetMap() {
    this.resourceMap.setZoom( this.resourceMapOptions.zoom );
    this.resourceMap.setCenter( this.resourceMapOptions.center );
  },





  prepareRoutes: function prepareRoutes( wrapper ) {
    console.log( 'loadRoute', wrapper );
    var that = this;

    // Prepare Form
    var $routeContainer = $(wrapper);
    if( $routeContainer.length === 1 ) {
      $routeContainer.find('form').on( 'submit', function( evt ) {
        evt.preventDefault();
        destination = $( evt.target ).find('input').val();
        console.log( 'FORM SUBMIT', destination );
        that.resetMap();
        that.clearRoute();
        that.loadroute( destination, false);
        return false;
      });
    }

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
    if( this.resourceData.name ) {
      this.routeTo.title = this.resourceData.name;
    }

    // this.loadroute( latitude+','+longitude, this.resourceData.name );

    // click en mapa
    this.mapClickEvent = new google.maps.event.addListener( this.resourceMap, 'click', function(ev){
      inputComollegar = '';
      that.loadroute( ev.latLng.lat()+', '+ev.latLng.lng(), false);
    });
    // click en marker evento
    /*
      this.eventClickEvent = new google.maps.event.addListener( eventMarker, 'click', function(ev){
        inputComollegar = '';

        thisComollegar.loadroute( ev.latLng.lat()+', '+ev.latLng.lng(), resourceData.name );
      });
    */
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
  },

  loadroute: function loadroute( from, fromTitle ) {
    var that = this;

    this.routeFrom.latlng = from;
    if( fromTitle ) {
      this.routeFrom.title = fromTitle;
    }

    this.traceRoute( 0, from, this.routeTo.latlng, this.transport , false, function() {
      console.log( that.resourceLastroute.routes[0] );

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

      // desbloquea boton de cerrar
      that.blockCloseButton = false;
    });
  },

  clearRoute: function clearRoute() {
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
  },

  // traceRoute(0, from, this.to.latlng, this.transport , false, function(){
  traceRoute: function traceRoute( id, from, to, mode, cache, thenFunction ) {
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
          console.debug( '<p>'+ route.travelMode +' Metros: '+result.routes[0].legs[0].distance.value+' Segundos: '+result.routes[0].legs[0].duration.value+'</p>' );
          //directionsDisplay.setDirections(result);

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
