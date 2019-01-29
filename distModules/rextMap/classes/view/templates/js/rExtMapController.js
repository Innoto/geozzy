var geozzy = geozzy || {};

geozzy.rExtMapController = function( opts ) {


  var that = this;
  var resourceLocation = __('Resource location');

  that.resourceMap = false;
  that.resourceMarker = false;

  that.loaded = false;
  that.onLoadFunctions = [];

  that.options = {
    lat: false,
    lng: false,
    zoom: 8,
    wrapper: false

  };


  $.extend(true, that.options, opts);
  if( typeof cogumelo.publicConf.rextMapConf == 'undefined') {
    cogumelo.publicConf.rextMapConf = {};
  }
  var $mapContainer = $( that.options.wrapper );


  // initialize map
  that.initialize = function() {

    if( $mapContainer.length === 1 && (that.options.lat != 0 && that.options.lng != 0 ) ) {
      that.resourceMapOptions = {
        center: { lat: that.options.lat, lng: that.options.lng },
        zoom: that.options.zoom,
        fullscreenControl: false
      };

      that.resourceMapOptions.scrollwheel = (
        typeof cogumelo.publicConf.rextMapConf.scrollwheel == 'undefined'
      )? false : cogumelo.publicConf.rextMapConf.scrollwheel;

      that.resourceMapOptions.mapTypeId = (
        typeof cogumelo.publicConf.rextMapConf.mapTypeId == 'undefined'
      )? 'roadmap' : cogumelo.publicConf.rextMapConf.mapTypeId;

      that.resourceMapOptions.styles = (
        typeof cogumelo.publicConf.rextMapConf.styles == 'undefined'
      )? false : cogumelo.publicConf.rextMapConf.styles;

      if( typeof cogumelo.publicConf.rextMapConf.styles == 'undefined' ) {
        that.resourceMapOptions.styles = false;
      }
      else {
        /* jshint ignore:start */
        eval("that.resourceMapOptions.styles = " + cogumelo.publicConf.rextMapConf.styles +";");
        /* jshint ignore:end */
      }


      that.resourceMap = new google.maps.Map( $mapContainer.get(0), that.resourceMapOptions );



      var icono = {
        url: cogumelo.publicConf.media+'/module/admin/img/geozzy_marker.png', // that marker is 20 pixels wide by 36 pixels high.
        size: new google.maps.Size(30, 36), // The origin for that image is (0, 0).
        origin: new google.maps.Point(0, 0), // The anchor for that image is the base of the flagpole at (0, 36).
        anchor: new google.maps.Point(13, 36)
      };

      // ten icono personalizado
      if( typeof cogumelo.publicConf.rextMapConf.defaultMarker != 'undefined') {
        icono.url = cogumelo.publicConf.rextMapConf.defaultMarker;
      }


      if( typeof cogumelo.publicConf.rextMapConf.defaultMarkerDimensions != 'undefined' ) {
        // ten medidas personalizadas
        if( typeof cogumelo.publicConf.rextMapConf.defaultMarkerDimensions.size != 'undefined' ) {
          icono.size = new google.maps.Size(cogumelo.publicConf.rextMapConf.defaultMarkerDimensions.size[0],cogumelo.publicConf.rextMapConf.defaultMarkerDimensions.size[1]);
        }

        // ten anchor personalizado
        if( typeof cogumelo.publicConf.rextMapConf.defaultMarkerDimensions.anchor != 'undefined' ) {
          icono.anchor = new google.maps.Point(cogumelo.publicConf.rextMapConf.defaultMarkerDimensions.anchor[0],cogumelo.publicConf.rextMapConf.defaultMarkerDimensions.anchor[1]);
        }
      }


      // add marker
      that.resourceMarker = new google.maps.Marker({
        map: that.resourceMap,
        position: new google.maps.LatLng( that.options.lat, that.options.lng ),
        title: resourceLocation,
        icon: icono
      });

      that.loaded = true;
      $.each( that.onLoadFunctions, function(i,func){
        func();
      });


    } // if( $mapContainer.length )
    else {
      that.cantRenderLigMsg();
      that.resourceMap = false;
    }
  };

  that.initializeIfNot = function() {
    if(that.loaded == false) {
      that.initialize();
    }
  };

  // preload with on demmand render
  that.renderInitButton = function() {
    if( $mapContainer.length === 1 && (that.options.lat != 0 && that.options.lng != 0 ) ) {
      $mapContainer.html('<div class="viewMapButtonContainer"><button class="viewMapButton">'+__('View map')+'</button></div>');
      that.resourceMap = 'waiting';
      $mapContainer.find('.viewMapButton').on('click', function(){
        that.initialize();
      });
    }
    else {
      that.cantRenderLigMsg();
      that.resourceMap = false;
    }
  };


  // onload Event
  that.onLoad = function( onLoadFunction ){
    if( that.loaded == false ) {
      that.onLoadFunctions.push(onLoadFunction);
    }
    else {
      onLoadFunction();
    }
  };

  // Log MSG
  that.cantRenderLigMsg = function() {
    cogumelo.log( 'rextMap - NOTICE: Cant find map wrapper or latLng = 0' );
  };

};
